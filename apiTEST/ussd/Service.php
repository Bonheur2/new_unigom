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
        
        public function authenticateServiceCode($serviceCode) {
            $query = "SELECT * FROM tbl_bank WHERE bank_code = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$serviceCode]);
            $auth = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['bank_id'] : 0;
            $stmt = null;
            
            return $auth;
        }
        
        public function getApplicantBasicData($trackingId) {
            $query = "SELECT code AS studentId, fname, lname FROM tbl_applicants WHERE code = ?";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$trackingId]);
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $studentInfo;
        }
    
        public function getStudentBasicData($studentId) {
            $query = "SELECT a.reg_no, a.fname, a.lname, r.reg_no, s.splz_full_name, l.level_full_name
                FROM tbl_register_program_ug r
                INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                INNER JOIN tbl_level l ON r.level_id = l.level_id
                WHERE r.reg_no = ? ORDER BY r.reg_prg_id DESC LIMIT 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$studentId]);
            $studentInfo = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $studentInfo;
        }
        
        public function getFeeCategory($feeId) {
            $query = "SELECT id, name, amount FROM fee_category WHERE id = ? AND status = 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$feeId]);
            $feeCategory = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            $stmt = null;
            
            return $feeCategory;
        }
        
        public function checkStudent($studentId){
            $studentData = $this->getStudentBasicData($studentId);
            if(!empty($studentData)){
                return 1;
            } else { 
                $applicantData = $this->getApplicantBasicData($studentId);
                if (!empty($applicantData)) {
                    return 2;
                    
                } else{
                    return 0;
                }
            }
        }
        
        public function getStudentBalance($studentId) {
            $studentData = $this->getStudentBasicData($studentId);
            if(!empty($studentData)){
                // Get debt and payments
                $debtData = $this->connect->prepare("SELECT SUM(balance) as debt FROM tbl_invoice WHERE reg_no = ?");
                $debtData->execute([$studentId]);
                $debt = $debtData->fetch()['debt'];
                
                $payHistory = $this->connect->prepare("SELECT SUM(amount) as paid FROM payment WHERE reg_no = ? AND status = 1");
                $payHistory->execute([$studentId]);
                $paid = $payHistory->fetch()['paid'];
                
                $balance = $debt - $paid;
        
                $feeCategory = $this->getFeeCategory(1);
                
                $data = array(
                    "studentId" => $studentData['reg_no'],
                    "firstName" => $studentData['fname'],
                    "lastName" => $studentData['lname'],
                    "specialization" => $studentData['splz_full_name'],
                    "level" => $studentData['level_full_name'],
                    'fee_category' => $feeCategory['name'],
                    "balance" => $balance
                );
            } else { 
                $applicantData = $this->getApplicantBasicData($studentId);
                if (!empty($applicantData)) {
                    $feeCategory = $this->getFeeCategory(7);
                    
                    $data = array(
                        "studentId" => $applicantData['studentId'],
                        "firstName" => $applicantData['fname'],
                        "lastName" => $applicantData['lname'],
                        'feeCategory' => $feeCategory['name'],
                        "balance" => $feeCategory['amount']
                    );
                    
                } else{
                    return $this->sendFeedback(404, 'Student not found');
                }
                
            }
    
            return $this->sendFeedback(200, 'Success', $data);
        }
        
        public function getActiveAcademicYear() {
            $query = "SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $acad = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['acad_cycle_id'] : 1;
            $stmt = null;
            
            return $acad;
        }
        
        public function checkPaymentOccurrence($slip, $bank){
            $stmt = $this->connect->prepare("SELECT id FROM payment WHERE slip_no = ? AND bank_id = ? AND status=1");
            $stmt->execute([$slip, $bank]);
            return $stmt->rowCount();
        }
        
        public function savePayment($studentId, $acad_year_id, $fee, $service_id, $transId, $amount, $user, $postedAt){
            $stmt = $this->connect->prepare("INSERT INTO payment(reg_no, acad_cycle_id, fee_id, bank_id, slip_no, amount, user, PayMode, date) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)");
            $stmt->execute([$studentId, $acad_year_id, $fee, $service_id, $transId, $amount, $user, $postedAt]);
              
            $data = array(
                "studentId" => $studentId, 
                "PCODE" => $transId, 
                "amount" => $amount, 
                "postedBy" => $user, 
                "postedAt" => $postedAt 
                );
            return $this->sendFeedback(200, "Payment recorded!", $data);
        }
        
        public function processPayment($studentId, $fee, $service_code, $amount, $transId){
            $acad_year_id = $this->getActiveAcademicYear();
            
            $user = "USSD";
            $postedAt = date("Y-m-d H:i:s");
            $service_id = $this->authenticateServiceCode($service_code);
            
            if($service_id != 0){
                $counter = $this->checkPaymentOccurrence($transId, $service_id);
                if ($counter == 0) {
                    return $this->savePayment($studentId, $acad_year_id, $fee, $service_id, $transId, $amount, $user, $postedAt);
                } else {
                    return $this->sendFeedback(400, "Payment was recorded before!");
                }
            }else{
                return $this->sendFeedback(404, "Unknown Service Code!");
            }
        }
        
    
        public function __destruct() {
            if ($this->connect) {
                $this->connect = null;
            }
        }
    }
?>