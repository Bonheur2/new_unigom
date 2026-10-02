<?php
    include ('../../meet/con.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $connection=$conn;
    class Student{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    		$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}
    	
    	public function search() {
            $keyword = $_POST['keyword'];
            
            $sql = "SELECT DISTINCT a.`applicant_id`, a.`code`, a.`fname`, a.`mname`, a.`lname`, a.`email`, a.`phone`,
                   i.`id` as invoice_id, i.`balance`, i.`invoice_date`, i.`payment_status`
            FROM `tbl_applicants` a
            INNER JOIN `tbl_invoice` i ON a.`code` = i.`reg_no`
            LEFT JOIN `payment_trial` p ON i.`reg_no` = p.`reg_no` AND i.`fee_id` = p.`fee_id`
            WHERE (i.`payment_status` = 'unpaid' OR i.`payment_status` IS NULL OR p.`id` IS NULL)
            AND (a.`fname` LIKE :keyword 
                OR a.`lname` LIKE :keyword 
                OR a.`mname` LIKE :keyword 
                OR a.`email` LIKE :keyword 
                OR a.`phone` LIKE :keyword 
                OR a.`code` LIKE :keyword)
            ORDER BY a.`fname`, a.`lname`";
    
            $stmt = $this->connect->prepare($sql);
            $searchTerm = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        }

        public function save_payment() {
            try {
                $reg_no = $_POST['reg_no'];
                $trans_code = $_POST['trans_code'];
                $slip_no = $_POST['slip_no'];
                $user = $_POST['user'];
                $amount = $_POST['amount'];
                $comment = $_POST['comment'];
                $PayMode=1;
                
                $select_before = "SELECT * FROM tbl_invoice WHERE reg_no = :reg_no";
                $cselect_before = $this->connect->prepare($select_before);
                $cselect_before->bindParam(':reg_no', $reg_no, PDO::PARAM_STR);
                $cselect_before->execute();
                $row_cselect_before = $cselect_before->fetch(PDO::FETCH_ASSOC);
                $acad_cycle_id = $row_cselect_before['acad_cycle_id'];
                
                if($user == 'B-AGENT'){
                    $fee_id = 1;
                    $bank_id = 1; // Set appropriate bank_id for B-AGENT
                } else {
                    $fee_id = 33;
                    $bank_id = 2; // Set appropriate bank_id for others
                }
                
                // Check if trans_code already exists
                $checkTransSql = "SELECT COUNT(*) FROM `payment_trial` WHERE `trans_code` = :trans_code";
                $checkTransStmt = $this->connect->prepare($checkTransSql);
                $checkTransStmt->bindParam(':trans_code', $trans_code, PDO::PARAM_STR);
                $checkTransStmt->execute();
                
                if($checkTransStmt->fetchColumn() > 0) {
                    echo json_encode(['success' => false, 'message' => 'Transaction code already exists!']);
                    return;
                }
                
                // Check if slip_no already exists
                $checkSlipSql = "SELECT COUNT(*) FROM `payment_trial` WHERE `slip_no` = :slip_no";
                $checkSlipStmt = $this->connect->prepare($checkSlipSql);
                $checkSlipStmt->bindParam(':slip_no', $slip_no, PDO::PARAM_STR);
                $checkSlipStmt->execute();
                
                if($checkSlipStmt->fetchColumn() > 0) {
                    echo json_encode(['success' => false, 'message' => 'Slip number already exists!']);
                    return;
                }
                
                // Insert into payment_trial table
                $sql = "INSERT INTO `payment_trial` (`trans_code`, `reg_no`, `acad_cycle_id`, `bank_id`, `slip_no`, `user`, `date`, `fee_id`, `amount`, `recorded_date`, `PayMode`, `comment`, `status`) 
                        VALUES (:trans_code, :reg_no, :acad_cycle_id, :bank_id, :slip_no, :user, NOW(), :fee_id, :amount, NOW(), :pay_mode, :comment, '1')";
                
                $stmt = $this->connect->prepare($sql);
                $stmt->bindParam(':trans_code', $trans_code, PDO::PARAM_STR);
                $stmt->bindParam(':reg_no', $reg_no, PDO::PARAM_STR);
                $stmt->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
                $stmt->bindParam(':bank_id', $bank_id, PDO::PARAM_INT);
                $stmt->bindParam(':slip_no', $slip_no, PDO::PARAM_STR);
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                $stmt->bindParam(':fee_id', $fee_id, PDO::PARAM_INT);
                $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
                $stmt->bindParam(':pay_mode', $PayMode, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                
                if($stmt->execute()) {
                    // Update invoice payment status
                    $updateSql = "UPDATE `tbl_invoice` SET `payment_status` = '1' WHERE `reg_no` = :reg_no";
                    $updateStmt = $this->connect->prepare($updateSql);
                    $updateStmt->bindParam(':reg_no', $reg_no, PDO::PARAM_STR);
                    $updateStmt->execute();
                    
                    echo json_encode(['success' => true, 'message' => 'Payment recorded successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to record payment!']);
                }
            } catch(Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        }

    }	
	$student=new Student();
    $action = $_POST['action'];
	switch($action){
	    case 'search':
	        $student->search();
	        break;
	    case 'save_payment':
	        $student->save_payment();
	        break;
	}

?>