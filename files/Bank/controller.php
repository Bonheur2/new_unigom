<?php
    include('../../meet/con.php');
    $connection = $conn;
    class Account{
        private $connect;
        public function __construct(){
            global $connection;
            $this->connect = $connection;
        }
        function save_account(){
            $fac_id = $_POST['fac_id'];
            $bank_name = $_POST['bank_name'];
            $bank_code = $_POST['bank_code'];
            $account_name = $_POST['account_name'];
            $account_no = $_POST['account_no'];
            $currency = $_POST['currency'];
    
            $stmt = $this->connect->prepare("SELECT * FROM tbl_bank WHERE account_no = ? AND fac_id = ?");
            $stmt->execute([$account_no, $fac_id]);
            if ($stmt->rowCount() > 0) {
                $data = array("status" => "400", "message" => "Data already exists!");
                $jsonData = json_encode($data);
                echo $jsonData;
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_bank(fac_id, bank_name, bank_code, account_name, account_no, currency) 
                      	VALUES(?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$fac_id, $bank_name, $bank_code, $account_name, $account_no, $currency])) {
                    $data = array("status" => "200", "message" => "Data saved successfully!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                } else {
                    $data = array("status" => "400", "message" => "Failed to save data!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                }
            }
        }
        function view_account(){
            $bank_id= $_POST['bank_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_bank WHERE bank_id = ?");
            $stmt->execute([$bank_id]);
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData;
        }
        function update_account(){
            $bank_id = $_POST['bank_id'];
            $fac_id = $_POST['fac_id'];
            $bank_name = $_POST['bank_name'];
            $bank_code = $_POST['bank_code'];
            $account_name = $_POST['account_name'];
            $account_no = $_POST['account_no'];
            $currency = $_POST['currency'];
    
            $stmt = $this->connect->prepare("SELECT * FROM tbl_bank WHERE account_no = ? AND fac_id = ? AND  bank_id != ?");
    
            $stmt->execute([$account_no, $fac_id, $bank_id]);
            if ($stmt->rowCount() > 0) {
                $data = array("status" => "401", "message" => "Data already exists!");
                $jsonData = json_encode($data);
                echo $jsonData;
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_bank set fac_id = ?, bank_name = ?, bank_code = ?, account_name = ?, account_no = ?, currency = ? WHERE bank_id = ?");
                if ($stmt->execute([$fac_id, $bank_name, $bank_code, $account_name, $account_no, $currency, $bank_id])) {
                    $data = array("status" => "200", "message" => "data updated successfully");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                } else {
                    $data = array("status" => "400", "message" => "Failed to update data");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                }
            }
        }
    
        function delete_account(){
            $bank_id = $_POST['bank_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_bank WHERE bank_id = ?");
            $stmt->execute([$bank_id]);
            $prevData = $stmt->fetch();
            if ($prevData['status'] == 1) {
                $status = 2;
            } else {
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_bank SET status = ? WHERE bank_id = ?");
            if ($stmt2->execute([$status, $bank_id])) {
                $data = array("status" => "200", "message" => "data removed successfully");
                $jsonData = json_encode($data);
                echo $jsonData;
            } else {
                $data = array("status" => "400", "message" => "Failed to removed data");
                $jsonData = json_encode($data);
                echo $jsonData;
            }
        }
    }
    
    $account = new Account();
    $action = $_POST['action'];
    switch ($action) {
        case 'register':
            $account->save_account();
            break;
        case 'update':
            $account->update_account();
            break;
        case 'delete':
            $account->delete_account();
            break;
        case 'view':
            $account->view_account();
            break;
    }
?>