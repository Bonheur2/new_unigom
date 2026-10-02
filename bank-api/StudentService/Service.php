<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    class Service {
        private $connect;
    
        public function __construct($conn) {
            $this->connect = $conn;
        }
    
        public function sendFeedback($status, $message, $data = null) {
            $response = [
                'status' => $status,
                'message' => $message,
                'data' => $data
            ];
            return json_encode($response);
        }

        // ==================== Resend email + activation helpers ====================

        private function generatePassword($length = 8) {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz';
            $pwd = '';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $pwd .= $chars[random_int(0, $max)];
            }
            return $pwd;
        }

        private function sendResendEmail($toEmail, $toName, $subject, $html) {
            $configPath = __DIR__ . '/config/resend.php';
            if (!file_exists($configPath)) {
                error_log('Resend config not found at ' . $configPath);
                return false;
            }
            $cfg = require $configPath;
            if (empty($cfg['api_key']) || $cfg['api_key'] === 're_xxxxxxxxx') {
                error_log('Resend API key not configured.');
                return false;
            }

            $payload = json_encode([
                'from'    => $cfg['from'],
                'to'      => $toName ? "$toName <$toEmail>" : $toEmail,
                'subject' => $subject,
                'html'    => $html,
            ]);

            $ch = curl_init('https://api.resend.com/emails');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $cfg['api_key'],
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT        => 15,
            ]);
            $response = curl_exec($ch);
            $http     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err || $http < 200 || $http >= 300) {
                error_log("Resend send failed (HTTP $http): " . ($err ?: $response));
                return false;
            }
            return true;
        }

        private function buildCredentialsEmailHtml($fullName, $regNo, $loginUsername, $plainPassword) {
            $name = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');
            $reg  = htmlspecialchars($regNo,  ENT_QUOTES, 'UTF-8');
            $usr  = htmlspecialchars($loginUsername, ENT_QUOTES, 'UTF-8');
            $pwd  = htmlspecialchars($plainPassword, ENT_QUOTES, 'UTF-8');

            return '
<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;color:#222;">
  <div style="background:#003366;color:#fff;padding:20px;text-align:center;">
    <h2 style="margin:0;">Njala University</h2>
    <p style="margin:5px 0 0;">Your Account Is Now Active</p>
  </div>
  <div style="padding:25px;background:#f9f9f9;">
    <p>Dear <strong>' . $name . '</strong>,</p>
    <p>We have received your payment. Your student account has been <strong>activated</strong> and you can now log in to the student portal using the credentials below.</p>

    <table style="width:100%;border-collapse:collapse;margin:15px 0;background:#fff;">
      <tr><td style="padding:10px;border:1px solid #ddd;"><strong>Registration Number</strong></td>
          <td style="padding:10px;border:1px solid #ddd;font-size:16px;color:#003366;"><strong>' . $reg . '</strong></td></tr>
      <tr><td style="padding:10px;border:1px solid #ddd;"><strong>Username (use this to log in)</strong></td>
          <td style="padding:10px;border:1px solid #ddd;font-family:monospace;">' . $usr . '</td></tr>
      <tr><td style="padding:10px;border:1px solid #ddd;"><strong>Temporary Password</strong></td>
          <td style="padding:10px;border:1px solid #ddd;font-family:monospace;color:#c62828;"><strong>' . $pwd . '</strong></td></tr>
    </table>

    <div style="background:#fff3cd;border-left:4px solid #ffc107;padding:12px;margin:20px 0;">
      <strong>Important:</strong> Please log in and change this password as soon as possible. Do not share these credentials with anyone.
    </div>

    <p style="margin-top:25px;">If you have any questions, contact the Registry office.</p>
    <p>Regards,<br/><strong>Njala University Registrar</strong></p>
  </div>
  <div style="background:#003366;color:#fff;text-align:center;padding:15px;font-size:12px;line-height:1.6;">
    <div style="margin-bottom:8px;font-style:italic;">This is an automated message  please do not reply directly to this email.</div>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,0.2);margin:8px 0;" />
    <div>&copy; 2025 Njala University. All rights reserved.</div>
    <div>Designed by ITEC Ltd</div>
    <div>NJALA - Sierra Leone</div>
    <div>Phone: (+232) 76 811846,79 102840,74 001023,76 559988</div>
  </div>
</div>';
        }

        // After a payment is recorded, decide if the student should be activated.
        // Activation = (1) set tbl_admission.reg_no = applicant_code,
        //              (2) reset password + flip status=1 in tbl_users,
        //              (3) email plain credentials.
        // Runs only when the incoming $regNo is actually an applicant_code (no row in
        // tbl_admission has reg_no = $regNo), AND the student has paid any amount.
        private function activateStudentIfPaid($regNo) {
            // Already activated? (a row exists where reg_no = the value supplied)
            $check = $this->connect->prepare("SELECT 1 FROM tbl_admission WHERE reg_no = ? LIMIT 1");
            $check->execute([$regNo]);
            if ($check->fetch()) return;

            // Look up by applicant_code
            $stmt = $this->connect->prepare(
                "SELECT adm_id, applicant_code, fname, lname, email, reg_no
                 FROM tbl_admission WHERE applicant_code = ? LIMIT 1"
            );
            $stmt->execute([$regNo]);
            $adm = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$adm) return;
            if (!empty($adm['reg_no'])) return; // already linked

            // Has any payment been recorded for this code?
            $payStmt = $this->connect->prepare(
                "SELECT COALESCE(SUM(amount), 0) AS paid FROM payment_trial WHERE reg_no = ?"
            );
            $payStmt->execute([$regNo]);
            $paid = (float) $payStmt->fetch(PDO::FETCH_ASSOC)['paid'];
            if ($paid <= 0) return;

            // ---- activate ----
            $newPassword     = $this->generatePassword(8);
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // 1. set reg_no = applicant_code in tbl_admission
            $u1 = $this->connect->prepare(
                "UPDATE tbl_admission SET reg_no = applicant_code WHERE adm_id = ?"
            );
            $u1->execute([$adm['adm_id']]);

            // 2. reset password + activate user account
            $u2 = $this->connect->prepare(
                "UPDATE tbl_users SET password = ?, status = '1' WHERE Identification = ?"
            );
            $u2->execute([$newPasswordHash, $adm['applicant_code']]);

            // 3. fetch the actual login row from tbl_users (this is what the student will type in)
            $userStmt = $this->connect->prepare(
                "SELECT Identification, email FROM tbl_users WHERE Identification = ? LIMIT 1"
            );
            $userStmt->execute([$adm['applicant_code']]);
            $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

            $loginUsername = $userRow['email']          ?? $adm['applicant_code'];
            $loginEmail    = $userRow['email']          ?? '';
            $regNoDisplay  = $userRow['Identification'] ?? $adm['applicant_code'];

            // 4. email credentials (delivered to personal email; body shows the tbl_users.email as username)
            $fullName = trim($adm['fname'] . ' ' . $adm['lname']);
            $html     = $this->buildCredentialsEmailHtml($fullName, $regNoDisplay, $loginUsername, $newPassword);
            $this->sendResendEmail(
                $adm['email'],
                $fullName,
                'Njala University - Your Login Credentials',
                $html
            );
        }
    function verify_applicant($student_id){
        $token=$student_id;
        $pass =$student_id;
        $dt = date('Y-m-d H:i:s');
            $dir = [
                'cost' => 12,
            ];
        $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
        $sth = $this->connect->prepare("SELECT email FROM email_verification_tokens WHERE token='".$token."'");
        $sth->execute();
        if ($sth->rowCount() != 0) {
            $res = $sth->fetch();
            $email=$res['email'];
            $stmt = $this->connect->prepare("INSERT INTO tbl_student_login(Identification,email,password,role_id) VALUES ('".$token."','".$email."','".$password."',5)");
            $stmt2 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
            if($stmt->execute()){
                $stmt2->execute();

                // Send email notification
                $subject = "Account Verification Successful";
                $message = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Account Verification Successful</title>
                    <style>
                        body {
                            font-family: 'Arial', sans-serif;
                            line-height: 1.6;
                            color: #333;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            box-shadow: 0 0 20px rgba(0,0,0,0.1);
                        }
                        .header {
                            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                            color: white;
                            padding: 30px 20px;
                            text-align: center;
                        }
                        .header h1 {
                            margin: 0;
                            font-size: 28px;
                            font-weight: 300;
                        }
                        .content {
                            padding: 40px 30px;
                        }
                        .credentials-card {
                            background-color: #f8f9fa;
                            border: 1px solid #e9ecef;
                            border-radius: 8px;
                            padding: 25px;
                            margin: 25px 0;
                        }
                        .detail-row {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 12px 0;
                            border-bottom: 1px solid #dee2e6;
                        }
                        .detail-row:last-child {
                            border-bottom: none;
                        }
                        .detail-label {
                            font-weight: 600;
                            color: #495057;
                        }
                        .detail-value {
                            color: #212529;
                            font-weight: 500;
                        }
                        .success-badge {
                            background-color: #d4edda;
                            color: #155724;
                            padding: 15px;
                            border-radius: 6px;
                            text-align: center;
                            margin: 20px 0;
                            border: 1px solid #c3e6cb;
                        }
                        .footer {
                            background-color: #2c3e50;
                            color: white;
                            padding: 25px;
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>✅ Account Verified Successfully</h1>
                        </div>
                        
                        <div class='content'>
                            <div class='success-badge'>
                                <strong>Your account has been automatically verified!</strong>
                            </div>
                            
                            <p>Dear Student,</p>
                            
                            <p>We are pleased to inform you that your account has been successfully verified through our automated bank verification system. You can now proceed with your application and access the student portal using the login credentials below:</p>
                            
                            <div class='credentials-card'>
                                <h3 style='text-align: center; margin-bottom: 20px;'>Your Login Credentials</h3>
                                
                                <div class='detail-row'>
                                    <span class='detail-label'>Email:</span>
                                    <span class='detail-value'>{$email}</span>
                                </div>
                                
                                <div class='detail-row'>
                                    <span class='detail-label'>Registration Number as password:</span>
                                    <span class='detail-value'>{$token}</span>
                                </div>
                            </div>
                            
                            <p><strong>Important Notes:</strong></p>
                            <ul>
                                <li>Please keep these credentials secure and do not share them with anyone</li>
                                <li>Use your email address and registration number to log into the student portal</li>
                                <li>You can now proceed with completing your application process</li>
                                <li>If you experience any issues logging in, please contact our support team</li>
                            </ul>
                            
                            <p>Thank you for choosing our institution. We look forward to supporting your academic journey.</p>
                        </div>
                        
                        <div class='footer'>
                            <p><strong>Student Services Team</strong><br>
                            Njala University</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                $this->sendResendEmail($email, '', $subject, $message);
            }
            else{
                // $data = array("status"=>"500");
                // $jsonData = json_encode($data);
                // header('Content-Type: application/json');
                // echo $jsonData;  
            }
        }
        else{
                // $data = array("status"=>"401");
                // $jsonData = json_encode($data);
                // header('Content-Type: application/json');
                // echo $jsonData; 
        }  
        
    }    
    public function getApplicantBasicData($trackingId, $bank_code) {
    $this->verify_applicant($trackingId);
    // Prepare queries with placeholders to prevent SQL injection
    $select_code = "SELECT code AS studentId, fname AS firstname, lname AS lastname FROM tbl_applicants WHERE code = :trackingId AND status IN (1, 2)";
    $cselect_code = $this->connect->prepare($select_code);
    $cselect_code->bindParam(':trackingId', $trackingId, PDO::PARAM_STR);
    $cselect_code->execute();
    $row_cselect_code = $cselect_code->fetch(PDO::FETCH_ASSOC);

    // If found in tbl_applicants, use that data
    if ($row_cselect_code) {
        
        $fac_id=0;
        $paymentAccounts = $this->getFacultyBakAccounts($bank_code,$fac_id);
        $feeCategory = $this->getFeeCategories($trackingId);

        // Check if data retrieval was successful
        if ($paymentAccounts && $feeCategory) {
            $data = [
                'studentData' => $row_cselect_code,
                'feeCategories' => $feeCategory,
                'paymentAccounts' => $paymentAccounts,
            ];

            return $this->sendFeedback(200, 'Success', $data);
        } else {
            // return $this->sendFeedback(500, 'Error fetching associated data homie');
            return $this->sendFeedback(500, 'No unpaid invoices found for this student, it seams like student have already paid all invoices.');
        }
    }
    
    // If not found in tbl_applicants, check tbl_admission by reg_no OR applicant_code
    else {
        $select_reg = "SELECT
                          COALESCE(NULLIF(reg_no, ''), applicant_code) AS studentId,
                          applicant_code,
                          reg_no,
                          fname AS firstname,
                          lname AS lastname
                       FROM tbl_admission
                       WHERE reg_no = :trackingId OR applicant_code = :trackingId
                       LIMIT 1";
        $cselect_reg = $this->connect->prepare($select_reg);
        $cselect_reg->bindParam(':trackingId', $trackingId, PDO::PARAM_STR);
        $cselect_reg->execute();
        $row_cselect_reg = $cselect_reg->fetch(PDO::FETCH_ASSOC);

        // If found in tbl_admission, use that data
        if ($row_cselect_reg) {
            // The identifier the invoice + registration tables use is the NU reg_no.
            // For continuing students that have applicant_code = reg_no, this is the same value.
            $invoiceId = $row_cselect_reg['studentId'];

            // Look up fac_id from tbl_register_program_ug. reg_no is the NU number;
            // for continuing students it can also be matched via old_reg_no.
            $select_faculity_bank = "SELECT fac_id FROM tbl_register_program_ug
                                     WHERE reg_no = :id OR old_reg_no = :id
                                     LIMIT 1";
            $cselect_faculity_bank = $this->connect->prepare($select_faculity_bank);
            $cselect_faculity_bank->execute([':id' => $invoiceId]);
            $row_cselect_faculity_bank = $cselect_faculity_bank->fetch(PDO::FETCH_ASSOC);
            $fac_id = $row_cselect_faculity_bank['fac_id'] ?? 0;

            $paymentAccounts = $this->getFacultyBakAccounts($bank_code, $fac_id);
            $feeCategory = $this->getFeeCategories($invoiceId);

            // Clean response: drop the internal lookup columns
            $studentData = [
                'studentId' => $invoiceId,
                'firstname' => $row_cselect_reg['firstname'],
                'lastname'  => $row_cselect_reg['lastname'],
            ];

            if ($paymentAccounts && is_array($feeCategory)) {
                $data = [
                    'studentData'     => $studentData,
                    'feeCategories'   => $feeCategory,
                    'paymentAccounts' => $paymentAccounts,
                ];
                return $this->sendFeedback(200, 'Success', $data);
            } else {
                return $this->sendFeedback(500, 'Error fetching associated data');
            }
        } else {
            return $this->sendFeedback(400, 'Invalid request: student_code is Invalid');
        }
    }
}

    
    public function getStudentBasicData($studentId) {
            // Match by reg_no OR applicant_code so continuing students (whose
            // reg_no may not yet be linked) still resolve.
            $query = "SELECT DISTINCT
                              COALESCE(NULLIF(ad.reg_no, ''), ad.applicant_code) AS studentId,
                              ad.fname AS firstname,
                              ad.lname AS lastname,
                              ug.fac_id
                        FROM tbl_admission ad
                        LEFT JOIN tbl_register_program_ug ug
                               ON ug.reg_no = ad.reg_no OR ug.old_reg_no = ad.applicant_code
                        WHERE ad.reg_no = ? OR ad.applicant_code = ?
                        LIMIT 1";

            $stmt = $this->connect->prepare($query);
            $stmt->execute([$studentId, $studentId]);
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;

            return $studentInfo;
        }
        
        public function getDepo() {
            $query = "SELECT d_option_name FROM deposit_option WHERE d_sts =1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $studentInfo;
        }
        
        public function getAllPaymentAccounts($bankCode) {
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = ? AND bank_id=1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$bankCode]);
            $paymentAccounts = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $paymentAccounts;
        }
            public function getFacultyBakAccounts($bankCode,$fac_id) {
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = '$bankCode' and fac_id='$fac_id'";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $paymentAccounts = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $paymentAccounts;
        } 
        
    
        public function getPaymentAccounts($bankCode, $facId) {
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = ? AND fac_id = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$bankCode, $facId]);
            $paymentAccounts = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $paymentAccounts;
        }
    
        public function getFeeCategories($reg_no) { 
            try {
        $query = "SELECT fc.id as fee_id,fc.name as fee_name, 
                         fc.amount - IFNULL(SUM(p.amount), 0) AS amount
                  FROM tbl_fee_category fc
                  INNER JOIN tbl_invoice i ON fc.id = i.fee_id
                  LEFT JOIN payment_trial p ON i.reg_no = p.reg_no AND i.fee_id = p.fee_id
                  WHERE fc.status = 1 
                  AND i.reg_no = ? 
                  AND i.payment_status = 0 
                  GROUP BY fc.name, fc.amount
                  ORDER BY fc.id DESC ";
                  
        $stmt = $this->connect->prepare($query);
        $stmt->execute([$reg_no]);

        if ($stmt->rowCount() > 0) {
            $feeCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $feeCategories;
        } else {
            // return $this->sendFeedback(404, 'No fee category found for this student.');
            return [];
        }
    } catch (Exception $e) {
        return $this->sendFeedback(500, 'Error: ' . $e->getMessage());
    }
        }
        
         public function general_feeCategory()
{
    try {
        $query = "SELECT name, amount FROM tbl_fee_category ";
                  
        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $feeCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->sendFeedback(200, 'Success', $feeCategories);
        } else {
            return $this->sendFeedback(404, 'No fee category found for this student.');
        }
    } catch (Exception $e) {
        return $this->sendFeedback(500, 'Error: ' . $e->getMessage());
    }
}

        
        public function payment_data($slip_number) {
            
            $query = "SELECT payment_trial.amount as Paid_amount, payment_trial.fee_id, payment_trial.reg_no,payment_trial.slip_no as Slip_number 
            ,tbl_fee_category.name as Payment_for FROM payment_trial
           
            inner join tbl_fee_category on tbl_fee_category.id=payment_trial.fee_id
            WHERE slip_no ='".trim($slip_number)."' ";
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $feeCategoriesData = $stmt->rowCount();
            $feeCategories = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            if($feeCategoriesData>0){
            return $this->sendFeedback(200, 'Success', $feeCategories);
            }
            else{
              return $this->sendFeedback(500, 'fail, Wrong slip number', $feeCategories);  
            }
        }
        
        
        
        public function getFeeCategory($feeId,$feeId2) {
             $feeCategory=[];
            $query = "SELECT fee_category.id, fee_category.name ,admission_fee as amount FROM fee_category,tbl_program_type  WHERE fee_category.id = ?  and
            tbl_program_type.prg_type_full_name != fee_category.name and prg_type_id = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$feeId,$feeId2]);
            $feeCategory2 = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            $feeCategory[]=$feeCategory2;
        //     $query = "SELECT admission_fee as amount FROM tbl_program_type WHERE prg_type_id = ? ";
        //     $stmt = $this->connect->prepare($query);
        //     $stmt->execute([$feeId2]);
        //     $feeCategoryAd = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
        //     $stmt = null;
        //   $feeCategory[]=$feeCategoryAd;
            
            return $feeCategory;
        }
        
           public function getFeeValue($feeId) {
            $query = "SELECT admission_fee FROM tbl_program_type WHERE prg_type_id = ? ";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$feeId]);
            $feeCategory = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $feeCategory;
        }
          
         
        public function getActiveAcademicYear() {
            $query = "SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $acad = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['acad_cycle_id'] : 1;
            $stmt = null;
            
            return $acad;
        }
    
        public function getStudentPaymentData($studentId, $bankCode) {
            $studentData = $this->getStudentBasicData($studentId);
            if (empty($studentData)) {
                $applicantData = $this->getApplicantBasicData($studentId);
                if (empty($applicantData)) {
                    return $this->sendFeedback(404, 'Student not found');
                } 
                else{
                    
                    $paymentAccounts = $this->getAllPaymentAccounts($bankCode);
                    $feeCategory = $this->getFeeCategory(7);
                    
                    
                    $data = [
                        'studentData' => $applicantData,
                        'paymentAccounts' => $paymentAccounts,
                        'feeCategories' => $feeCategory
                    ];
                }
            } 
            else{
                $facId = $studentData['fac_id'];
                unset($studentData['fac_id']);
                $paymentAccounts = $this->getPaymentAccounts($bankCode,$facId);
                $feeCategories = $this->getFeeCategories();
                
                $data = [
                    'studentData' => $studentData,
                    'paymentAccounts' => $paymentAccounts,
                    'feeCategories' => $feeCategories
                ];
            }
    
            return $this->sendFeedback(200, 'Success', $data);
        }
        
        public function checkPaymentOccurrence($slip, $bank){
            $stmt = $this->connect->prepare("SELECT id FROM payment_trial WHERE slip_no = ? AND bank_id = ? AND status=1");
            $stmt->execute([$slip, $bank]);
            return $stmt->rowCount();
        }
        
         public function compareAmount($fixed, $fee_amout){
            $stmt = $this->connect->prepare("SELECT id FROM payment_trial WHERE slip_no = ? AND bank_id = ? AND status=1");
            $stmt->execute([$fixed, $fee_amout]);
            // return $stmt->rowCount();
            return 0;
        }
        
        
        
        public function getDeposterOption(){
            
            // $optonData = $this->getDepo();
            $query = "SELECT d_option_id as option_id ,d_option_name as option_name FROM deposit_option WHERE d_sts =1";
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            
            $optionData= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            
                    
            return $this->sendFeedback(200, 'Success', $optionData);
         
        }
        
        
    public function savePayment($regNo, $acadCycleId, $feeId, $bankId, $slipNo, $amountPaid, $userId, $paymentDate, $transId)
{
    try {
        // Insert payment record
        $insertStmt = $this->connect->prepare(
            "INSERT INTO payment_trial(reg_no, acad_cycle_id, fee_id, bank_id, slip_no, amount, user, PayMode, date, trans_code)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)"
        );
        $insertStmt->execute([$regNo, $acadCycleId, $feeId, $bankId, $slipNo, $amountPaid, $userId, $paymentDate, $transId]);

        // Fetch fee details
        $feeDetailsStmt = $this->connect->prepare("SELECT * FROM tbl_fee_category WHERE id = ?");
        $feeDetailsStmt->execute([$feeId]);
        $feeDetails = $feeDetailsStmt->fetch(PDO::FETCH_ASSOC);

        // Fetch invoice details
        $invoiceDetailsStmt = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND fee_id = ?");
        $invoiceDetailsStmt->execute([$regNo, $feeId]);
        $invoiceDetails = $invoiceDetailsStmt->fetch(PDO::FETCH_ASSOC);

        if ($feeId == 1) {
            $totalPaymentStmt = $this->connect->prepare("SELECT SUM(amount) AS payment FROM payment_trial WHERE reg_no = ? AND fee_id = ?");
            $totalPaymentStmt->execute([$regNo, $feeId]);
            $totalPayment = $totalPaymentStmt->fetch(PDO::FETCH_ASSOC);

            $invoiceDetailsStmt = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND fee_id = ?");
            $invoiceDetailsStmt->execute([$regNo, $feeId]);
            $updatedInvoiceDetails = $invoiceDetailsStmt->fetch(PDO::FETCH_ASSOC);

            if ($updatedInvoiceDetails['balance'] >= $totalPayment['payment']) {
                $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE reg_no = ? AND fee_id = ?");
                $updateInvoiceStmt->execute([$regNo, $feeId]);
            } else {
                $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 0 WHERE reg_no = ? AND fee_id = ?");
                $updateInvoiceStmt->execute([$regNo, $feeId]);
            }
        } else {
            $invoiceDetailsStmt = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND fee_id = ?");
            $invoiceDetailsStmt->execute([$regNo, $feeId]);
            $updatedInvoiceDetails = $invoiceDetailsStmt->fetch(PDO::FETCH_ASSOC);

            if ($updatedInvoiceDetails['level_id'] == 1) {
                $totalPaymentStmt = $this->connect->prepare("SELECT SUM(amount) AS payment FROM payment_trial WHERE reg_no = ? AND fee_id = ?");
                $totalPaymentStmt->execute([$regNo, $feeId]);
                $totalPayment = $totalPaymentStmt->fetch(PDO::FETCH_ASSOC);

                if ($updatedInvoiceDetails['balance'] >= $totalPayment['payment']) {
                    // Mark invoice as paid
                    $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE reg_no = ? AND fee_id = ?");
                    $updateInvoiceStmt->execute([$regNo, $feeId]);

                    // Update admission status
                    $updateAdmissionStmt = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 1 WHERE Stu_code = ?");
                    $updateAdmissionStmt->execute([$regNo]);
                } else {
                    // Mark invoice as unpaid
                    $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 0 WHERE reg_no = ? AND fee_id = ?");
                    $updateInvoiceStmt->execute([$regNo, $feeId]);

                    // Update admission status
                    $updateAdmissionStmt = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 0 WHERE Stu_code = ?");
                    $updateAdmissionStmt->execute([$regNo]);
                }
            } else {
                $totalPaymentStmt = $this->connect->prepare("SELECT SUM(amount) AS payment FROM payment_trial WHERE reg_no = ? AND fee_id = ?");
                $totalPaymentStmt->execute([$regNo, $feeId]);
                $totalPayment = $totalPaymentStmt->fetch(PDO::FETCH_ASSOC);

                if ($updatedInvoiceDetails['balance'] >= $totalPayment['payment']) {
                    // Mark invoice as paid
                    $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE reg_no = ? AND fee_id = ?");
                    $updateInvoiceStmt->execute([$regNo, $feeId]);
                } else {
                    // Mark invoice as unpaid
                    $updateInvoiceStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 0 WHERE reg_no = ? AND fee_id = ?");
                    $updateInvoiceStmt->execute([$regNo, $feeId]);
                }
            }
        }
        
        // Activate continuing-student account if this payment is against an applicant_code.
        // This sets tbl_admission.reg_no = applicant_code, resets password + status in
        // tbl_users, and emails fresh login credentials via Resend.
        try {
            $this->activateStudentIfPaid($regNo);
        } catch (Exception $e) {
            error_log('activateStudentIfPaid failed: ' . $e->getMessage());
        }

        // Get student email and necessary information for the email
        $studentEmailStmt = $this->connect->prepare("SELECT email FROM tbl_admission WHERE reg_no = ?");
        $studentEmailStmt->execute([$regNo]);
        $studentEmail = $studentEmailStmt->fetch(PDO::FETCH_ASSOC)['email'] ?? '';

        if (!$studentEmail) {
            // Try by applicant_code (continuing students before reg_no is linked)
            $admByCodeStmt = $this->connect->prepare("SELECT email FROM tbl_admission WHERE applicant_code = ?");
            $admByCodeStmt->execute([$regNo]);
            $studentEmail = $admByCodeStmt->fetch(PDO::FETCH_ASSOC)['email'] ?? '';
        }

        if (!$studentEmail) {
            // Try to get email from applicants table if not found in admission table
            $applicantEmailStmt = $this->connect->prepare("SELECT email FROM tbl_applicants WHERE code = ?");
            $applicantEmailStmt->execute([$regNo]);
            $studentEmail = $applicantEmailStmt->fetch(PDO::FETCH_ASSOC)['email'] ?? '';
        }
        
        // Get fee name
        $feeName = $feeDetails['name'] ?? 'Tuition fees';
        
        // Send email confirmation if email is available
        if ($studentEmail) {
            $message = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Payment Confirmation</title>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        box-shadow: 0 0 20px rgba(0,0,0,0.1);
                    }
                    .header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 30px 20px;
                        text-align: center;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: 300;
                    }
                    .header .icon {
                        font-size: 48px;
                        margin-bottom: 10px;
                    }
                    .content {
                        padding: 40px 30px;
                    }
                    .greeting {
                        font-size: 18px;
                        color: #2c3e50;
                        margin-bottom: 20px;
                    }
                    .message {
                        font-size: 16px;
                        color: #555;
                        margin-bottom: 30px;
                        line-height: 1.8;
                    }
                    .details-card {
                        background-color: #f8f9fa;
                        border: 1px solid #e9ecef;
                        border-radius: 8px;
                        padding: 25px;
                        margin: 25px 0;
                    }
                    .details-title {
                        color: #495057;
                        font-size: 18px;
                        font-weight: 600;
                        margin-bottom: 20px;
                        text-align: center;
                    }
                    .detail-row {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 12px 0;
                        border-bottom: 1px solid #dee2e6;
                    }
                    .detail-row:last-child {
                        border-bottom: none;
                    }
                    .detail-label {
                        font-weight: 600;
                        color: #495057;
                        font-size: 14px;
                    }
                    .detail-value {
                        color: #212529;
                        font-size: 14px;
                        font-weight: 500;
                    }
                    .amount {
                        color: #28a745;
                        font-weight: 700;
                        font-size: 16px;
                    }
                    .success-badge {
                        background-color: #d4edda;
                        color: #155724;
                        padding: 15px;
                        border-radius: 6px;
                        text-align: center;
                        margin: 20px 0;
                        border: 1px solid #c3e6cb;
                    }
                    .footer {
                        background-color: #2c3e50;
                        color: white;
                        padding: 25px;
                        text-align: center;
                    }
                    .footer p {
                        margin: 0;
                        font-size: 14px;
                    }
                    .signature {
                        margin-top: 15px;
                        font-style: italic;
                    }
                    @media (max-width: 600px) {
                        .container {
                            margin: 0;
                            box-shadow: none;
                        }
                        .content {
                            padding: 20px 15px;
                        }
                        .details-card {
                            padding: 15px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <div class='icon'>✅</div>
                        <h1>Payment Confirmation</h1>
                    </div>
                    
                    <div class='content'>
                        <div class='greeting'>Dear Student,</div>
                        
                        <div class='success-badge'>
                            <strong>✓ Payment Successfully Processed</strong>
                        </div>
                        
                        <div class='message'>
                            We are pleased to confirm that your payment has been successfully recorded in our system. Below are the details of your transaction:
                        </div>
                        
                        <div class='details-card'>
                            <div class='details-title'>Payment Details</div>
                            
                            <div class='detail-row'>
                                <span class='detail-label'>Student ID:</span>
                                <span class='detail-value'>{$regNo}</span>
                            </div>
                            
                            <div class='detail-row'>
                                <span class='detail-label'>Fee Category:</span>
                                <span class='detail-value'>{$feeName}</span>
                            </div>
                            
                            <div class='detail-row'>
                                <span class='detail-label'>Amount Paid:</span>
                                <span class='detail-value amount'>SLE " . number_format($amountPaid, 2) . "</span>
                            </div>
                            
                            <div class='detail-row'>
                                <span class='detail-label'>Slip Number:</span>
                                <span class='detail-value'>{$slipNo}</span>
                            </div>
                            
                            <div class='detail-row'>
                                <span class='detail-label'>Transaction Date:</span>
                                <span class='detail-value'>" . date('F j, Y - g:i A') . "</span>
                            </div>
                        </div>
                        
                        <div class='message'>
                            <strong>Important:</strong> Please keep this confirmation email for your records. If you have any questions about this payment or need assistance, please contact our finance office.
                        </div>
                    </div>
                    
                    <div class='footer'>
                        <p>Thank you for your prompt payment!</p>
                        <div class='signature'>
                            <strong>Payment Management System</strong><br>
                            Njala University
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";

            $this->sendResendEmail($studentEmail, '', "Payment Confirmation - " . $feeName, $message);
        }

        return $this->sendFeedback(200, "Payment recorded successfully!");
    } catch (Exception $e) {
        return $this->sendFeedback(500, "Error: " . $e->getMessage());
    }
}


        
        public function processPayment($transaction_id, $studentId, $fee, $bank_id, $slip_no, $amount, $user, $postedAt){
            $acad_year_id = $this->getActiveAcademicYear();
            $counter = $this->checkPaymentOccurrence($slip_no, $bank_id);
            
            
            if ($counter == 0) {
                // $fixeddata = $this->compareAmount($fixed, $fee_amout);
               $query = "SELECT known_price, amount FROM tbl_fee_category WHERE id = '".$fee."' AND status = 1";
               $stmt = $this->connect->prepare($query);
               $stmt->execute();
            
            $optionData= $stmt->fetch(PDO::FETCH_ASSOC);
            $fixed=$optionData['known_price'];
            $fee_amout=$optionData['amount'];
                if($fixed==1){
                    
                    if($fee_amout!=$amount){
                   return $this->sendFeedback(400, "Unmatch amount,can paid $fee_amout instead of  $amount");
                    }
                   else{
                    //   return $this->sendFeedback(400, "macting amount",$fee_amout); 
                     return $this->savePayment($studentId, $acad_year_id, $fee, $bank_id, $slip_no, $amount, $user, $postedAt,$transaction_id);
                   }
                }
                else {
                return $this->savePayment($studentId, $acad_year_id, $fee, $bank_id, $slip_no, $amount, $user, $postedAt,$transaction_id);
                }
            } else {
                return $this->sendFeedback(400, "Payment was recorderd before!");
            }
        }
    
        public function __destruct() {
            if ($this->connect) {
                $this->connect = null;
            }
        }
    }
?>