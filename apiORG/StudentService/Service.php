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
                    
                    $feeCategory = $this->getFeeCategories();
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
                    $feeCategory = $this->getFeeCategory(7);
                    $admission_fees= $this->getFeeValue($prg_type);    
                    
                    $data = [
                        'studentData' => $studentInfo,
                        'paymentAccounts' => $paymentAccounts,
                        'feeCategories' => $feeCategory,
                        'admission_fees' => $admission_fees
                    ];
                    
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
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = ?";
            
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
            
            return $feeCategories;
        }
        
         public function general_feeCategory() {
            $query = "SELECT id, name, amount,known_price as fixed FROM fee_category WHERE status = 1 and id!=7";
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $feeCategories = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
          return $this->sendFeedback(200, 'Success', $feeCategories);
        }
        
        
        
        public function getFeeCategory($feeId) {
            $query = "SELECT id, name FROM fee_category WHERE id = ? AND status = 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$feeId]);
            $feeCategory = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
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
        
        
    public function savePayment($reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $amount, $user, $date,$transaction_id){
            $stmt = $this->connect->prepare("INSERT INTO payment_trial(reg_no, acad_cycle_id, fee_id, bank_id, slip_no, amount, user, PayMode, date,trans_code) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?,?)");
            $stmt->execute([$reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $amount, $user, $date,$transaction_id]);
            return $this->sendFeedback(200, "Payment recorded!");
        }
        
        public function processPayment($transaction_id, $studentId, $fee, $bank_id, $slip_no, $amount, $user, $postedAt,$fixed,$fee_amout){
            $acad_year_id = $this->getActiveAcademicYear();
            $counter = $this->checkPaymentOccurrence($slip_no, $bank_id);
            if ($counter == 0) {
                // $fixeddata = $this->compareAmount($fixed, $fee_amout);
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