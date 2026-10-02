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
        
        public function getApplicantBasicData($trackingId,$bank_code) {
            $query = "SELECT code AS studentId, fname AS firstname, lname AS lastname FROM tbl_applicants WHERE code = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$trackingId]);
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            
          //   $studentData = $this->getStudentBasicData($studentId);
            
            $applicantData =$stmt->rowCount();
            
                if ($applicantData==0) {
                    
                    // return $this->sendFeedback(404, 'Applicant not found',$trackingId);
                    $studentInfo = $this->getStudentBasicData($trackingId);
                    
                    if(empty($studentInfo))
                    { 
                      return $this->sendFeedback(404, 'no data found', $trackingId);   
                    }
                    
                    else {
                    
                    $facId = $studentInfo['fac_id'];
                    $paymentAccounts = $this->getFacultyBakAccounts($bank_code,$facId);
                    
                    $feeCategory = $this->getFeeCategories($trackingId);
                    // $admission_fees= $this->getFeeValue($prg_type);    
                    
                    $data = [
                        'studentData' => $studentInfo,
                        'feeCategories' => $feeCategory,
                        'paymentAccounts' => $paymentAccounts,
                       
                    ];
                    
                     return $this->sendFeedback(200, 'Success', $data);
                    }
                } 
                else{
                    
            $queryPRGTYPE = "SELECT prg_type FROM tbl_admittedPRG WHERE Stu_code = ?";
            $stmtPRGTYPE = $this->connect->prepare($queryPRGTYPE);
            $stmtPRGTYPE->execute([$trackingId]);
            
            $appldata=$stmtPRGTYPE->fetch(PDO::FETCH_ASSOC);
          
                    
                    if($stmtPRGTYPE->rowCount() > 0){
                        $prg_type=$appldata['prg_type']; 
                    $paymentAccounts = $this->getAllPaymentAccounts($bank_code);
                    $feeCategory = $this->getFeeCategory(7,$prg_type);
                    $admission_fees= $this->getFeeValue($prg_type);    
                    
                    $data = [
                        'studentData' => $studentInfo,
                        'paymentAccounts' => $paymentAccounts,
                        'feeCategories' => $feeCategory
                        
                    ];
                    // 'admission_fees' => $admission_fees
                    
                    return $this->sendFeedback(200, 'Success', $data);
                    }
                    
                    else {
                         return $this->sendFeedback(404, 'Incomplete application. Please Contact Admision Officer', $trackingId);
                    }
                }
            
          
    
            
        }
    
    public function getStudentBasicData($studentId) {
            $query = "SELECT DISTINCT(ad.reg_no) AS studentId, 
                              ad.fname AS firstname, 
                              ad.lname AS lastname,
                              ug.fac_id
                        FROM tbl_admission ad
                        INNER JOIN tbl_register_program_ug ug ON ad.reg_no = ug.reg_no
                        WHERE ad.reg_no = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$studentId]);
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
            public function getFacultyBakAccounts($bankCode,$facId) {
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = ? and fac_id=?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$bankCode,$facId]);
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
    
        public function getFeeCategories() {
            $query = "SELECT id, name, amount,known_price as fixed FROM fee_category WHERE status = 1 and id!=7";
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $feeCategories = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            //  'paymentAccounts' => $paymentAccounts,
            
            return $feeCategories;
        }
        
         public function general_feeCategory($reg_no)
{
    try {
        $query = "SELECT name, amount 
                  FROM tbl_fee_category 
                  INNER JOIN tbl_invoice ON tbl_fee_category.id = tbl_invoice.fee_id 
                  WHERE tbl_fee_category.status = 1 
                  AND tbl_invoice.reg_no = ? 
                  AND tbl_invoice.payment_status = 0 
                  ORDER BY tbl_fee_category.id DESC LIMIT 1";
                  
        $stmt = $this->connect->prepare($query);
        $stmt->execute([$reg_no]);

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

            if ($updatedInvoiceDetails['balance'] == $totalPayment['payment']) {
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

                if ($updatedInvoiceDetails['balance'] == $totalPayment['payment']) {
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

                if ($updatedInvoiceDetails['balance'] == $totalPayment['payment']) {
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

        return $this->sendFeedback(200, "Payment recorded!");
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