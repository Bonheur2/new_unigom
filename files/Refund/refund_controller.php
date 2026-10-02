<?php

include ('../../meet/con.php');
$connection=$conn;
class Refund{
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
    function save_refund(){
        $bankId = $_POST['bankId'];
        $refundAmount = $_POST['refundAmount'];
        $refundDescr = $_POST['refundDescr'];
        $refundDate = $_POST['refundDate'];
        $bn_name = $_POST['bn_name'];
        $bn_phone = $_POST['bn_phone'];
        $bn_reg_no = $_POST['bn_reg_no'];
        $bn_address = $_POST['bn_address'];
        $user = $_POST['user'];

        $stmt = $this->connect->prepare("INSERT INTO tbl_refund(bankId,refundAmount,refundDescr,refundDate,bn_name,bn_phone,bn_reg_no,bn_address,user) 
                  	VALUES('".$bankId."','".$refundAmount."','".$refundDescr."','".$refundDate."','".$bn_name."','".$bn_phone."','".$bn_reg_no."','".$bn_address."','".$user."')");
        if($stmt->execute()){
            $this->sendFeedback(200,"Data saved successfully!");
        } else {
            $this->sendFeedback(401,"Failed to save data!");
        }
	}
	
    function view_refund(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_refund WHERE refundId='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_refund(){
        $id = $_POST['refundId'];
        $bankId = $_POST['bankId'];
        $refundAmount = $_POST['refundAmount'];
        $refundDescr = $_POST['refundDescr'];
        $refundDate = $_POST['refundDate'];
        $bn_name = $_POST['bn_name'];
        $bn_phone = $_POST['bn_phone'];
        $bn_address = $_POST['bn_address'];
        $user = $_POST['user'];

        $stmt = $this->connect->prepare("UPDATE tbl_refund SET 
                                                            bankId='".$bankId."', 
                                                            refundAmount='".$refundAmount."', 
                                                            refundDescr='".$refundDescr."', 
                                                            refundDate='".$refundDate."', 
                                                            bn_name='".$bn_name."', 
                                                            bn_phone='".$bn_phone."', 
                                                            bn_phone='".$bn_reg_no."', 
                                                            bn_address='".$bn_address."',
                                                            user='".$user."'
                                                        WHERE refundId='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200,"refund updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update data!");
        }
	}
}	
		$refund=new Refund();
	  $action = $_POST['action'];
		switch($action){
		    case 'save_refund':
		        $refund->save_refund();
		        break;
		    case 'view_refund':
		        $refund->view_refund();
		        break;
		    case 'update_refund':
		        $refund->update_refund();
		        break;
		}

	?>

