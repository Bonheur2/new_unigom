<?php
include ('../../meet/con.php');
$connection=$conn;
class Contract{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	function sendFeedback($s, $m){
        $data = array("status"=>$s,"message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
	}
    function save_type(){
    	$name = $_POST['contr_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_contract_type WHERE contr_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_contract_type(contr_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"type saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save type!");
            }
        }

	}
	
    function view_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_contract_type WHERE contr_type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

 
    function update_type(){
        $id = $_POST['contr_type_id'];    
        $name = $_POST['contr_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_contract_type WHERE contr_name='".$name."' AND contr_type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_contract_type SET contr_name='".$name."' WHERE contr_type_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "type updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update type!");
            }
        }
    }
    
    function delete_type(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_contract_type WHERE contr_type_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_contract_type SET status='".$status."' WHERE contr_type_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
}	
		$type=new Contract();
	    $action = $_POST['action'];
		switch($action){
		    case 'save_type':
		        $type->save_type();
		        break;
		    case 'update':
		        $type->update_type();
		        break;
		    case 'delete':
		        $type->delete_type();
		        break;
		    case 'view':
		        $type->view_type();
		        break;
		}

	?>

