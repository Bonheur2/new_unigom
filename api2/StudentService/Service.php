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
        
        public function getApplicantBasicData($trackingId) {
            $query = "SELECT code AS studentId, fname AS firstname, lname AS lastname FROM tbl_applicants WHERE code = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$trackingId]);
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $studentInfo;
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
        
        public function getAllPaymentAccounts($bankCode) {
            $query = "SELECT bank_id, account_name, account_no AS account_number, currency FROM tbl_bank WHERE bank_code = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$bankCode]);
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
            $query = "SELECT id, name, amount FROM fee_category WHERE status = 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $feeCategories = $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $feeCategories;
        }
        
        public function getFeeCategory($feeId) {
            $query = "SELECT id, name, amount FROM fee_category WHERE id = ? AND status = 1";
            
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
                } else{
                    $paymentAccounts = $this->getAllPaymentAccounts($bankCode);
                    $feeCategory = $this->getFeeCategory(7);
                    
                    $data = [
                        'studentData' => $applicantData,
                        'paymentAccounts' => $paymentAccounts,
                        'feeCategories' => $feeCategory
                    ];
                }
            } else{
                $facId = $studentData['fac_id'];
                unset($studentData['fac_id']);
                $paymentAccounts = $this->getPaymentAccounts($bankCode, $facId);
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
            $stmt = $this->connect->prepare("SELECT id FROM payment WHERE slip_no = ? AND bank_id = ? AND status=1");
            $stmt->execute([$slip, $bank]);
            return $stmt->rowCount();
        }
        
        public function savePayment($reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $amount, $user, $date){
            $stmt = $this->connect->prepare("INSERT INTO payment(reg_no, acad_cycle_id, fee_id, bank_id, slip_no, amount, user, PayMode, date) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)");
            $stmt->execute([$reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $amount, $user, $date]);
            return $this->sendFeedback(200, "Payment recorded!");
        }
        
        public function processPayment($studentId, $fee, $bank_id, $slip_no, $amount, $user, $postedAt){
            $acad_year_id = $this->getActiveAcademicYear();
            $counter = $this->checkPaymentOccurrence($slip_no, $bank_id);
            if ($counter == 0) {
                return $this->savePayment($studentId, $acad_year_id, $fee, $bank_id, $slip_no, $amount, $user, $postedAt);
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