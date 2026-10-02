<?php

include ('../../meet/con.php');
$connection=$conn;
class Expense{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	  
	function sendFeedback($s, $m){
        $data = array("status" => $s, "message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;    
	}
    function save_expense(){
        $bankId = $_POST['bankId'];
        $expenseAmount = $_POST['expenseAmount'];
        $expenseDescr = $_POST['expenseDescr'];
        $transferDate = $_POST['transferDate'];
        $bn_name = $_POST['bn_name'];
        $bn_phone = $_POST['bn_phone'];
        $bn_address = $_POST['bn_address'];
        $user = $_POST['user'];

        $stmt = $this->connect->prepare("INSERT INTO tbl_funds_transfer(bankId,expenseAmount,expenseDescr,transferDate,bn_name,bn_phone,bn_address,user) 
                  	VALUES('".$bankId."','".$expenseAmount."','".$expenseDescr."','".$transferDate."','".$bn_name."','".$bn_phone."','".$bn_address."','".$user."')");
        if($stmt->execute()){
            $this->sendFeedback(200,"Data saved successfully!");
        } else {
            $this->sendFeedback(401,"Failed to save data!");
        }
	}
	
    function view_expense(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_funds_transfer WHERE expenseId='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_expense(){
        $id = $_POST['expenseId'];
        $bankId = $_POST['bankId'];
        $expenseAmount = $_POST['expenseAmount'];
        $expenseDescr = $_POST['expenseDescr'];
        $transferDate = $_POST['transferDate'];
        $bn_name = $_POST['bn_name'];
        $bn_phone = $_POST['bn_phone'];
        $bn_address = $_POST['bn_address'];
        $user = $_POST['user'];

        $stmt = $this->connect->prepare("UPDATE tbl_funds_transfer SET 
                                                                    bankId='".$bankId."', 
                                                                    expenseAmount='".$expenseAmount."', 
                                                                    expenseDescr='".$expenseDescr."', 
                                                                    transferDate='".$transferDate."', 
                                                                    bn_name='".$bn_name."', 
                                                                    bn_phone='".$bn_phone."', 
                                                                    bn_address='".$bn_address."',
                                                                    user='".$user."'
                                                                WHERE expenseId='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200,"expense updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update data!");
        }
	}
}	
		$expense=new Expense();
	    $action = $_POST['action'];
		switch($action){
		    case 'save_expense':
		        $expense->save_expense();
		        break;
		    case 'view_expense':
		        $expense->view_expense();
		        break;
		    case 'update_expense':
		        $expense->update_expense();
		        break;
		}

	?>

