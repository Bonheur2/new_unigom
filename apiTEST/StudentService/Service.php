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
        
        public function getApplicantBasicData($trackingId, $bank_code) {
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
            return $this->sendFeedback(500, 'Error fetching associated data');
        }
    }
    
    // If not found in tbl_applicants, check tbl_admission
    else {
        $select_reg = "SELECT reg_no AS studentId, fname AS firstname, lname AS lastname FROM tbl_admission WHERE reg_no = :trackingId";
        $cselect_reg = $this->connect->prepare($select_reg);
        $cselect_reg->bindParam(':trackingId', $trackingId, PDO::PARAM_STR);
        $cselect_reg->execute();
        $row_cselect_reg = $cselect_reg->fetch(PDO::FETCH_ASSOC);

        // If found in tbl_admission, use that data
        if ($row_cselect_reg) {
            $select_faculity_bank="SELECT * FROM tbl_register_program_ug WHERE reg_no='".$trackingId."'";
            $cselect_faculity_bank=$this->connect->prepare($select_faculity_bank);
            $cselect_faculity_bank->execute();
            $row_cselect_faculity_bank=$cselect_faculity_bank->fetch(PDO::FETCH_ASSOC);
            $fac_id=$row_cselect_faculity_bank['fac_id'];
          
            $paymentAccounts = $this->getFacultyBakAccounts($bank_code,$fac_id);
            $feeCategory = $this->getFeeCategories($trackingId);

            // Check if data retrieval was successful
            if ($paymentAccounts && $feeCategory) {
                $data = [
                    'studentData' => $row_cselect_reg,
                    'feeCategories' => $feeCategory,
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
            return $this->sendFeedback(404, 'No fee category found for this student.');
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