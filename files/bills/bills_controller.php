<?php

include ('../../meet/con.php');
$connection=$conn;
class Fee{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_bills(){
    	$bill_name= $_POST['bill_name'];
    	$bill_description = $_POST['bill_description'];
    	$bill_amount= $_POST['bill_amount'];
        $insertQuery="INSERT INTO bills_trial(bill_name,bill_description,bill_amount) VALUES ('".$bill_name."','".$bill_description."','".$bill_amount."')";


        $stmt = $this->connect->prepare("SELECT * FROM bills_trial WHERE bill_name='".$bill_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Bill already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare($insertQuery);
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Bill saved successfully!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save description!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }

	}
    function view_bill(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM bills_trial ");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_bill(){
        $bill_id = $_POST['id'];   
    	$bill_name = $_POST['bill_name'];
    	$bill_description = $_POST['bill_description'];
    	$bill_amount = $_POST['bill_amount'];
        $stmt = $this->connect->prepare("SELECT * FROM bills_trial WHERE bill_name='".$bill_name."' AND bill_id='".$bill_id."'");
        $stmt->execute();
        
            
    	    $updateQuery="UPDATE bills_trial set bill_name='".$bill_name."', bill_description='".$bill_description."', bill_amount='".$bill_amount."' WHERE bill_id='".$bill_id."'";
    
    	
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "category already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare($updateQuery);
        if($stmt->execute()){
                $data = array("status"=>"200","message" => "category updated successfully");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update category");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }

    
    	function manage_fee(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status=2;
            }
            else{
                $status=1;
            }
            $stmt2 = $this->connect->prepare("UPDATE fee_category SET status='".$status."' WHERE id='".$id."'");
            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        
        
}	
		$fee=new fee();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $fee->save_bills();
		        break;
		    case 'update_form':
		        $fee->update_bill();
		        break;
		    case 'view':
		        $fee->view_bill();
		        break;
		    case 'delete':
		        $fee->manage_fee();
		        break;
		}

	?>

