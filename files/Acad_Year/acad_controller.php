<?php

include ('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection=$conn;
class Acad{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_acad(){
    	$acad_year = $_POST['acad_year'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_year='".$acad_year."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            echo $jsonData;   
        } else {
            $disable = $this->connect->prepare("UPDATE tbl_acad_cycle SET status=2");
            $disable->execute();
            $stmt = $this->connect->prepare("INSERT INTO tbl_acad_cycle(acad_year) 
                  	VALUES('".$acad_year."')");
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }

	}
    function view_acad(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        echo $jsonData; 
    }
    
    function update_acad(){
        $id = $_POST['ac_id'];    
        $acad_year = $_POST['acad_year'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_year='".$acad_year."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("UPDATE tbl_acad_cycle set acad_year='".$acad_year."' WHERE acad_cycle_id='".$id."'");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "data updated successfully");
                $jsonData = json_encode($data);
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }
    }
    
	function enrollment(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT enrollment FROM tbl_acad_cycle WHERE acad_cycle_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['enrollment']==1){
            $enroll=0;
        }
        else{
            $enroll=1;
        }
        $stmt = $this->connect->prepare("UPDATE tbl_acad_cycle SET enrollment='".$enroll."' WHERE acad_cycle_id='".$id."'");
        if($stmt->execute()){
            $data = array("status"=>"200","message" => "operation done successfully");
            $jsonData = json_encode($data);
            echo $jsonData; 
        } else {
            $data = array("status"=>"500","message" => "operation failed");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    }
    function update_status(){
    $id = $_POST['id'];

    try {
        // Fetch the current semester's data
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $prevData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prevData) {
            $data = array("status" => "404", "message" => "Academic Year not found");
            echo json_encode($data);
            return;
        }

        // Determine the new status
        $newStatus = ($prevData['status'] == 1) ? 2 : 1;

        // Check if another semester already has an open status if trying to activate
        if ($newStatus == 1) {
            $stmtCheck = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE status = 1 AND acad_cycle_id != :id");
            $stmtCheck->bindParam(':id', $id);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                $data = array(
                    "status" => "401",
                    "message" => "Another Academic Year is already active. Please close it before activating this one."
                );
                echo json_encode($data);
                return;
            }
        }

        // Update the semester's status
        $stmtUpdate = $this->connect->prepare("UPDATE tbl_acad_cycle SET status = :status WHERE acad_cycle_id = :id");
        $stmtUpdate->bindParam(':status', $newStatus);
        $stmtUpdate->bindParam(':id', $id);

        if ($stmtUpdate->execute()) {
            $data = array("status" => "200", "message" => "Academic Year status updated successfully");
            echo json_encode($data);
        } else {
            $data = array("status" => "500", "message" => "Failed to update Academic Year status");
            echo json_encode($data);
        }

    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
        echo json_encode($data);
    }    
    
        
    }
}	
		$acad=new Acad();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $acad->save_acad();
		        break;
		    case 'update':
		        $acad->update_acad();
		        break;
		    case 'view':
		        $acad->view_acad();
		        break;
		    case 'enrollment':
		        $acad->enrollment();
		        break;
		    case 'academic_status':
		        $acad->update_status();
		        break;
		}

	?>

