<?php
    include ('../../meet/con.php');
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $connection = $conn;

    class Application {
        private $connect;

        public function __construct() {
            global $connection;
            $this->connect = $connection;
        }

        // ==================== CASCADING LOOKUPS ====================

        public function getProvinces() {
            $country = $_POST['country'];
            $stmt = $this->connect->prepare("SELECT provincecode, provincename FROM provinces WHERE country = :country");
            $stmt->execute([':country' => $country]);
            $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $provinces]);
        }

        public function getDistricts() {
            $province_id = $_POST['province_id'];
            $stmt = $this->connect->prepare("SELECT districtcode, namedistrict FROM districts WHERE provincecode = :province_id");
            $stmt->execute([':province_id' => $province_id]);
            $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $districts]);
        }

        public function getProgramTypes() {
            $camp_id = $_POST['camp_id'];
            $stmt = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE campus_id = :camp_id AND status = '1'");
            $stmt->execute([':camp_id' => $camp_id]);
            $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $programs]);
        }

        public function getFaculties() {
            $prg_type = $_POST['prg_type'];
            $stmt = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE prg_type = :prg_type AND status = '1'");
            $stmt->execute([':prg_type' => $prg_type]);
            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $faculties]);
        }

        public function getDepartments() {
            $fac_id = $_POST['fac_id'];
            $prg_type = $_POST['prg_type'];
            $stmt = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department WHERE fac_id = :fac_id AND prg_type = :prg_type AND status = '1'");
            $stmt->execute([':fac_id' => $fac_id, ':prg_type' => $prg_type]);
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $departments]);
        }

        public function getSpecializations() {
            $dept_id = $_POST['dept_id'];
            $prg_type = $_POST['prg_type'];
            $stmt = $this->connect->prepare("SELECT splz_id, splz_full_name FROM tbl_specialization WHERE dept_id = :dept_id AND prg_type = :prg_type AND status = '1'");
            $stmt->execute([':dept_id' => $dept_id, ':prg_type' => $prg_type]);
            $specializations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $specializations]);
        }

        public function getLevels() {
            $prg_type = $_POST['prg_type'];
            $stmt = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE prg_type = :prg_type AND status = '1' AND level_no > 1 ORDER BY level_rank ASC");
            $stmt->execute([':prg_type' => $prg_type]);
            $levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 200, 'data' => $levels]);
        }

        // ==================== HELPERS ====================

        private function generateRegNo() {
            $yearTwo = date('y'); // e.g. "25"
            $prefix  = 'NU' . $yearTwo;

            $stmt = $this->connect->prepare(
                "SELECT MAX(CAST(SUBSTRING(reg_no, 5) AS UNSIGNED)) AS max_seq
                 FROM tbl_register_program_ug
                 WHERE reg_no LIKE :prefix"
            );
            $stmt->execute([':prefix' => $prefix . '%']);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $next = ((int) ($row['max_seq'] ?? 0)) + 1;
            return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
        }

        // Most-specific fee match. Tries full criteria first, then progressively
        // drops splz_id and dept_id to fall back to broader categories.
        private function findFeeCategory($camp_id, $prg_type_id, $fac_id, $dept_id, $splz_id, $level_id) {
            $attempts = [
                // 1. fully specific (with specialization)
                [
                    'sql' => "SELECT id, amount FROM tbl_fee_category
                              WHERE status='1' AND camp_id=:camp AND prg_type_id=:prg
                                AND fac_id=:fac AND dept_id=:dept AND splz_id=:splz AND level_id=:level
                              LIMIT 1",
                    'params' => [':camp'=>$camp_id, ':prg'=>$prg_type_id, ':fac'=>$fac_id,
                                 ':dept'=>$dept_id, ':splz'=>$splz_id, ':level'=>$level_id],
                    'require' => $splz_id,
                ],
                // 2. no specialization
                [
                    'sql' => "SELECT id, amount FROM tbl_fee_category
                              WHERE status='1' AND camp_id=:camp AND prg_type_id=:prg
                                AND fac_id=:fac AND dept_id=:dept AND level_id=:level
                              LIMIT 1",
                    'params' => [':camp'=>$camp_id, ':prg'=>$prg_type_id, ':fac'=>$fac_id,
                                 ':dept'=>$dept_id, ':level'=>$level_id],
                    'require' => true,
                ],
                // 3. drop department
                [
                    'sql' => "SELECT id, amount FROM tbl_fee_category
                              WHERE status='1' AND camp_id=:camp AND prg_type_id=:prg
                                AND fac_id=:fac AND level_id=:level
                              LIMIT 1",
                    'params' => [':camp'=>$camp_id, ':prg'=>$prg_type_id, ':fac'=>$fac_id,
                                 ':level'=>$level_id],
                    'require' => true,
                ],
                // 4. drop faculty too
                [
                    'sql' => "SELECT id, amount FROM tbl_fee_category
                              WHERE status='1' AND camp_id=:camp AND prg_type_id=:prg AND level_id=:level
                              LIMIT 1",
                    'params' => [':camp'=>$camp_id, ':prg'=>$prg_type_id, ':level'=>$level_id],
                    'require' => true,
                ],
            ];

            foreach ($attempts as $a) {
                if (!$a['require']) continue;
                $stmt = $this->connect->prepare($a['sql']);
                $stmt->execute($a['params']);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) return $row;
            }
            return null;
        }

        private function getActiveAcadCycle() {
            $stmt = $this->connect->prepare(
                "SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = '1' LIMIT 1"
            );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['acad_cycle_id'] : null;
        }

        private function generatePassword($length = 8) {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz';
            $pwd = '';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $pwd .= $chars[random_int(0, $max)];
            }
            return $pwd;
        }

        private function fail($message) {
            echo json_encode(['status' => 400, 'message' => $message]);
            exit;
        }

        // Decode the base64 data-URL from the Cropper.js output and save as JPEG.
        // Returns the relative path stored in tbl_admission.passport_photo.
        private function savePassportPhoto($dataUrl, $regNo) {
            if (!preg_match('#^data:image/(jpeg|png);base64,#', $dataUrl, $m)) {
                throw new RuntimeException('Invalid photo format.');
            }
            $base64 = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $bin    = base64_decode($base64, true);
            if ($bin === false) {
                throw new RuntimeException('Could not decode photo data.');
            }
            if (strlen($bin) > 3 * 1024 * 1024) {
                throw new RuntimeException('Photo exceeds the 3 MB limit.');
            }

            // Project root /uploads/passports/
            $dir = __DIR__ . '/../../uploads/passports';
            if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new RuntimeException('Could not create photo storage directory.');
            }

            $filename = $regNo . '.jpg';
            $fullPath = $dir . '/' . $filename;
            if (file_put_contents($fullPath, $bin) === false) {
                throw new RuntimeException('Could not save the photo file.');
            }

            return 'uploads/passports/' . $filename;
        }

        // ==================== EMAIL (Resend) ====================

        private function sendResendEmail($toEmail, $toName, $subject, $html) {
            $cfg = require __DIR__ . '/config/resend.php';

            if (empty($cfg['api_key']) || $cfg['api_key'] === 're_xxxxxxxxx') {
                throw new RuntimeException('Resend API key is not configured.');
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

            if ($err) {
                throw new RuntimeException('Email send failed: ' . $err);
            }
            if ($http < 200 || $http >= 300) {
                throw new RuntimeException('Email send failed (HTTP ' . $http . '): ' . $response);
            }
        }

        
        private function buildPaymentEmailHtml($fullName, $applicantCode, $totalAmount, $dueAmount, $phone, $password) {
            $name = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');
            $code = htmlspecialchars($applicantCode, ENT_QUOTES, 'UTF-8');
            $total = number_format((float) $totalAmount, 2);
            $due   = number_format((float) $dueAmount, 2);

            return '
            <div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;color:#222;">
  <div style="background:#003366;color:#fff;padding:20px;text-align:center;">
    <h2 style="margin:0;">Njala University</h2>
    <p style="margin:5px 0 0;">Continuing Student Registration</p>
  </div>
  <div style="padding:25px;background:#f9f9f9;">
    <p>Dear <strong>' . $name . '</strong>,</p>
    <p>Thank you for completing your continuing student registration. To finalize your enrolment for this academic cycle, please pay <strong>60% of your tuition fee</strong> using the details below.</p>

    <table style="width:100%;border-collapse:collapse;margin:15px 0;background:#fff;">
      <tr><td style="padding:10px;border:1px solid #ddd;"><strong>Applicant Code (use this when paying)</strong></td>
          <td style="padding:10px;border:1px solid #ddd;font-size:16px;color:#003366;"><strong>' . $code . '</strong></td></tr>
      <tr><td style="padding:10px;border:1px solid #ddd;">Phone number</td>
          <td style="padding:10px;border:1px solid #ddd;">' . $phone . '</td></tr>
          <tr><td style="padding:10px;border:1px solid #ddd;">Password</td>
          <td style="padding:10px;border:1px solid #ddd;">' . $password . '</td></tr>
          <tr><td style="padding:10px;border:1px solid #ddd;">Total Tuition</td>
          <td style="padding:10px;border:1px solid #ddd;">' . $total . '</td></tr>
      <tr><td style="padding:10px;border:1px solid #ddd;"><strong>Amount Due Now (60%)</strong></td>
          <td style="padding:10px;border:1px solid #ddd;color:#c62828;"><strong>' . $due . '</strong></td></tr>
    </table>

    <h4 style="color:#003366;margin-bottom:8px;">Payment Channels</h4>
    <ul style="padding-left:20px;line-height:1.7;">
      <li><strong>EduPay:</strong> <a href="https://edupay.wan.gov.sl/lookup">Click here and pay with EduPay</a> Use Phone number and Password above to login to EduPay</li>
      <li><strong>Africell Money:</strong>  Enter your Applicant Code <strong>' . $code . '</strong> as the reference.</li>
      <li><strong>Sierra Leone Commercial Bank (SLCB):</strong>  Make a deposit and quote your Applicant Code <strong>' . $code . '</strong> on the slip.</li>
    </ul>

    <div style="background:#fff3cd;border:1px solid #ffc107;padding:12px;margin:20px 0;">
      <strong>Important:</strong> Once your payment of 60% is confirmed, your login credentials (username and password) will be sent to this email address. You will need them to access the student portal.
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
</div>
';
        }

        // ==================== MAIN SUBMIT ====================

        public function applyContinuing() {
            // ---- collect ----
            $lname         = trim($_POST['lname']         ?? '');
            $fname         = trim($_POST['fname']         ?? '');
            $nid           = trim($_POST['nid']           ?? '');
            $dob           = $_POST['dob']                ?? null;
            $gender        = $_POST['gender']             ?? '';
            $marital       = $_POST['marital_status']     ?? '';
            $nationality   = $_POST['nationality']        ?? '';
            $father_names  = trim($_POST['father_names']  ?? '');
            $mother_names  = trim($_POST['mother_names']  ?? '');

            $country       = $_POST['country']            ?? '';
            $province_id   = $_POST['province_id']        ?? null;
            $district_id   = $_POST['district_id']        ?? null;
            $street        = $_POST['street']             ?? null;

            $phone         = trim($_POST['phone']         ?? '');
            $email         = trim($_POST['email']         ?? '');
            $parent_phone  = $_POST['parent_phone']       ?? null;
            $ref_phone     = $_POST['ref_phone']          ?? null;

            $student_id    = trim($_POST['student_id']    ?? ''); // becomes old_reg_no / applicant_code
            $camp_id       = $_POST['cump_id']            ?? '';
            $prg_type      = $_POST['prg_type']           ?? '';
            $fac_id        = $_POST['fac_id']             ?? '';
            $dept_id       = $_POST['dept_id']            ?? '';
            $spcs_id       = $_POST['spcs_id']            ?? null;
            $level_id      = $_POST['level_id']           ?? '';
            $prg_mode_id   = $_POST['mode']               ?? '';

            $kin_name      = trim($_POST['kin_name']      ?? '');
            $kin_relation  = trim($_POST['kin_relation']  ?? '');
            $kin_address   = trim($_POST['kin_address']   ?? '');
            $kin_email     = $_POST['kin_email']          ?? null;
            $kin_tel       = trim($_POST['kin_tel']       ?? '');
            $spon_id       = !empty($_POST['spon_id'])    ? $_POST['spon_id'] : null;

            $photo_data    = $_POST['passport_photo_data'] ?? '';

            // ---- validate required ----
            if (!$lname || !$fname || !$nid || !$gender || !$father_names || !$mother_names) {
                $this->fail('Please fill in all personal info fields.');
            }
            if (!$country) {
                $this->fail('Please select country of residence.');
            }
            if (!$phone || !$email) {
                $this->fail('Phone and email are required.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->fail('Please provide a valid email address.');
            }
            if (!$student_id || !$camp_id) {
                $this->fail('Student ID and Campus are required.');
            }
            if (!$kin_name || !$kin_relation || !$kin_address || !$kin_tel) {
                $this->fail('Next of kin information is required.');
            }
            if (!$photo_data || strpos($photo_data, 'data:image/') !== 0) {
                $this->fail('Please upload your passport photo.');
            }

            // ---- uniqueness checks ----
            // 1. email must be unique in tbl_users and tbl_admission
            $stmt = $this->connect->prepare("SELECT 1 FROM tbl_users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $this->fail('This email is already registered. Please use a different email or log in.');
            }

            $stmt = $this->connect->prepare("SELECT 1 FROM tbl_admission WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $this->fail('An application with this email already exists.');
            }

            // 2. old_reg_no (student_id) must not already exist in tbl_register_program_ug
            $stmt = $this->connect->prepare("SELECT 1 FROM tbl_register_program_ug WHERE old_reg_no = :old LIMIT 1");
            $stmt->execute([':old' => $student_id]);
            if ($stmt->fetch()) {
                $this->fail('A registration already exists for this Student ID.');
            }

            // 3. nid must be unique in tbl_admission
            $stmt = $this->connect->prepare("SELECT 1 FROM tbl_admission WHERE ID = :nid LIMIT 1");
            $stmt->execute([':nid' => $nid]);
            if ($stmt->fetch()) {
                $this->fail('An application with this ID/Passport already exists.');
            }

            // ---- resolve active academic cycle ----
            $acad_cycle_id = $this->getActiveAcadCycle();
            if (!$acad_cycle_id) {
                $this->fail('No active academic cycle is configured. Please contact the administrator.');
            }

            // ---- resolve fee category (fail fast before any inserts) ----
            $fee = $this->findFeeCategory($camp_id, $prg_type, $fac_id, $dept_id, $spcs_id, $level_id);
            if (!$fee) {
                $this->fail('No fee category found for the selected campus/program/level. Please contact the administrator.');
            }

            // ---- generate identifiers ----
            try {
                $this->connect->beginTransaction();

                $reg_no       = $this->generateRegNo();              // NU2500001
                $username     = $reg_no;
                $login_email  = $reg_no . '@njala.edu.sl';
                $password     = $this->generatePassword(8);          // plain text (returned to UI)
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // ---- save passport photo (file written to disk; path goes into tbl_admission) ----
                $photo_path = $this->savePassportPhoto($photo_data, $reg_no);

                // ---- insert tbl_admission ----
                // applicant_code = generated reg_no (NU...). reg_no column itself NOT inserted here.
                $sqlAdm = "INSERT INTO tbl_admission
                    (applicant_code, acad_cycle_id, fname, lname, email, ID, nationality, marital_status,
                     phone, gender, father_names, mother_names, parent_phone, ref_phone,
                     country, province_id, district_id, street,
                     kin_name, kin_relation, kin_address, kin_email, kin_tel, spon_id,
                     passport_photo)
                    VALUES
                    (:applicant_code, :acad_cycle_id, :fname, :lname, :email, :nid, :nationality, :marital,
                     :phone, :gender, :father, :mother, :parent_phone, :ref_phone,
                     :country, :province_id, :district_id, :street,
                     :kin_name, :kin_relation, :kin_address, :kin_email, :kin_tel, :spon_id,
                     :passport_photo)";
                $stmt = $this->connect->prepare($sqlAdm);
                $stmt->execute([
                    ':applicant_code' => $reg_no,
                    ':acad_cycle_id'  => $acad_cycle_id,
                    ':fname'          => $fname,
                    ':lname'          => $lname,
                    ':email'          => $email,
                    ':nid'            => $nid,
                    ':nationality'    => $nationality,
                    ':marital'        => $marital,
                    ':phone'          => $phone,
                    ':gender'         => $gender,
                    ':father'         => $father_names,
                    ':mother'         => $mother_names,
                    ':parent_phone'   => $parent_phone,
                    ':ref_phone'      => $ref_phone,
                    ':country'        => $country,
                    ':province_id'    => $province_id,
                    ':district_id'    => $district_id,
                    ':street'         => $street,
                    ':kin_name'       => $kin_name,
                    ':kin_relation'   => $kin_relation,
                    ':kin_address'    => $kin_address,
                    ':kin_email'      => $kin_email,
                    ':kin_tel'        => $kin_tel,
                    ':spon_id'        => $spon_id,
                    ':passport_photo' => $photo_path,
                ]);

                // ---- insert tbl_register_program_ug ----
                // reg_no = generated NU-number; old_reg_no = student_id
                $sqlReg = "INSERT INTO tbl_register_program_ug
                    (reg_no, acad_cycle_id, prg_id, splz_id, level_id, prg_mode_id, prg_type,
                     fac_id, dept_id, camp_id, old_reg_no, spon_id, reg_active)
                    VALUES
                    (:reg_no, :acad_cycle_id, :prg_id, :splz_id, :level_id, :prg_mode_id, :prg_type,
                     :fac_id, :dept_id, :camp_id, :old_reg_no, :spon_id, '1')";
                $stmt = $this->connect->prepare($sqlReg);
                $stmt->execute([
                    ':reg_no'      => $reg_no,
                    ':acad_cycle_id' => $acad_cycle_id,
                    ':prg_id'      => $dept_id,           // adjust if you have a distinct program id
                    ':splz_id'     => $spcs_id,
                    ':level_id'    => $level_id,
                    ':prg_mode_id' => $prg_mode_id,
                    ':prg_type'    => $prg_type,
                    ':fac_id'      => $fac_id,
                    ':dept_id'     => $dept_id,
                    ':camp_id'     => $camp_id,
                    ':old_reg_no'  => $student_id,
                    ':spon_id'     => $spon_id,
                ]);

                // ---- insert tbl_users ----
                // Identification = reg_no, email = reg_no@njala.edu.sl, role_id = 4, status = 0
                $sqlUsr = "INSERT INTO tbl_users
                    (Identification, family_name, first_name, email, phone_no, password,
                     role_id, campus_id, fac_id, dept_id, status, reg_date, country)
                    VALUES
                    (:identification, :family_name, :first_name, :email, :phone, :password,
                     4, :campus_id, :fac_id, :dept_id, '0', NOW(), :country)";
                $stmt = $this->connect->prepare($sqlUsr);
                $stmt->execute([
                    ':identification' => $reg_no,
                    ':family_name'    => $lname,
                    ':first_name'     => $fname,
                    ':email'          => $login_email,
                    ':phone'          => $phone,
                    ':password'       => $password_hash,
                    ':campus_id'      => $camp_id,
                    ':fac_id'         => $fac_id,
                    ':dept_id'        => $dept_id,
                    ':country'        => $country,
                ]);

                // ---- insert tbl_invoice ----
                // Initial balance = fee amount (nothing paid yet).
                $sqlInv = "INSERT INTO tbl_invoice
                    (reg_no, acad_cycle_id, level_id, fee_id, balance, invoice_date,
                     user, date)
                    VALUES
                    (:reg_no, :acad_cycle_id, :level_id, :fee_id, :balance, NOW(),
                     :user, NOW())";
                $stmt = $this->connect->prepare($sqlInv);
                $stmt->execute([
                    ':reg_no'        => $reg_no,
                    ':acad_cycle_id' => $acad_cycle_id,
                    ':level_id'      => $level_id,
                    ':fee_id'        => $fee['id'],
                    ':balance'       => $fee['amount'],
                    ':user'          => $reg_no,
                ]);

                // ---- send payment-instructions email (60% due) ----
                // Sent inside the transaction so any failure rolls everything back.
                $totalFee  = (float) $fee['amount'];
                $dueAmount = round($totalFee * 0.60, 2);
                $fullName  = trim($fname . ' ' . $lname);
                $html = $this->buildPaymentEmailHtml($fullName, $reg_no, $totalFee, $dueAmount, $phone, $password);
                $this->sendResendEmail($email, $fullName, 'Njala University - Complete Your Registration (60% Payment Required)', $html);

                $this->connect->commit();

                echo json_encode([
                    'status'  => 200,
                    'message' => 'Application submitted. Payment instructions have been sent to your email.',
                    'reg_no'  => $reg_no,
                    'email'   => $email,
                    'due'     => $dueAmount,
                ]);
            } catch (PDOException $e) {
                if ($this->connect->inTransaction()) {
                    $this->connect->rollBack();
                }
                echo json_encode([
                    'status'  => 500,
                    'message' => 'Database error: ' . $e->getMessage(),
                ]);
            } catch (RuntimeException $e) {
                if ($this->connect->inTransaction()) {
                    $this->connect->rollBack();
                }
                echo json_encode([
                    'status'  => 500,
                    'message' => 'Could not send payment instructions email. Your application was not saved. Please try again. (' . $e->getMessage() . ')',
                ]);
            }
        }
    }

    $application = new Application();
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'getProvinces':       $application->getProvinces();       break;
        case 'getDistricts':       $application->getDistricts();       break;
        case 'getProgramTypes':    $application->getProgramTypes();    break;
        case 'getFaculties':       $application->getFaculties();       break;
        case 'getDepartments':     $application->getDepartments();     break;
        case 'getSpecializations': $application->getSpecializations(); break;
        case 'getLevels':          $application->getLevels();          break;
        case 'apply_continuing':   $application->applyContinuing();    break;
        default:
            echo json_encode(['status' => 400, 'message' => 'Invalid action.']);
    }
