<?php

include ('../../meet/con.php');
$connection=$conn;
class Assessment{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_assessment(){
    	$name = $_POST['name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_assessment WHERE assess_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_assessment(assess_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
    function view_assessment(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_assessment WHERE assess_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_assessment(){
        $id = $_POST['e_id'];    
        $name = $_POST['name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_assessment WHERE assess_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_assessment set assess_name='".$name."' WHERE assess_id='".$id."'");
        if($stmt->execute()){
                $data = array("status"=>"200","message" => "data updated successfully");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }
    
    	function delete_assessment(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_assessment WHERE assess_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_assessment SET status=2 WHERE assess_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_assessment SET status=1 WHERE assess_id='".$id."'");  
            }
            
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
		$assessment=new Assessment();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $assessment->save_assessment();
		        break;
		    case 'update':
		        $assessment->update_assessment();
		        break;
		    case 'view':
		        $assessment->view_assessment();
		        break;
		    case 'delete':
		        $assessment->delete_assessment();
		        break;
		}

	?>

