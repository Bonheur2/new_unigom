<?php

include ('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$connection=$conn;
class Semester{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	  
    function savesemester(){
    $semester = $_POST['semester'];
    $acad_year = $_POST['acad_year'];

    try {
        // Check if the current semester and academic year already exist with an active status
        $stmt = $this->connect->prepare("SELECT * FROM tbl_semester WHERE semester = :semester AND acad_year = :acad_year");
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':acad_year', $acad_year);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = array("status" => "401", "message" => "This semester already exists with an active status!");
            echo json_encode($data);
            return;
        }
        else
        {
        // Check if there is any previous semester with an active status
        $stmt = $this->connect->prepare("SELECT * FROM tbl_semester WHERE acad_year = :acad_year AND status = 1 ORDER BY sem_id DESC LIMIT 1");
        $stmt->bindParam(':acad_year', $acad_year);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $previousSemester = $stmt->fetch(PDO::FETCH_ASSOC);
            // Debug: Print fetched previous semester details
            error_log("Previous active semester found: " . json_encode($previousSemester));

            // Notify that the previous semester must be closed
            $data = array(
                "status" => "401",
                "message" => "Please close the previous semester  before adding a new one."
            );
            echo json_encode($data);
            return;
        }
        else{
            // Insert the new semester
        $stmt = $this->connect->prepare("INSERT INTO tbl_semester (semester, acad_year, status) VALUES (:semester, :acad_year, 1)");
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':acad_year', $acad_year);

        if ($stmt->execute()) {
            $data = array("status" => "200", "message" => "Semester saved successfully!");
            echo json_encode($data);
        } else {
            $data = array("status" => "500", "message" => "Failed to save semester.");
            echo json_encode($data);
        }
        }
        }

    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
        echo json_encode($data);
    }
}


    function viewsemester(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT S.*, A.* FROM tbl_semester S INNER JOIN tbl_acad_cycle A ON S.acad_year = A.acad_cycle_id WHERE sem_id='".$id."' ");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function updatesemester(){
        $id = $_POST['sem_id'];    
        $semester = $_POST['semester'];
        $acad_year=$_POST['acad_cycle_id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_semester WHERE semester='".$semester."' AND acad_year='".$acad_year."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_semester set semester='".$semester."' WHERE sem_id='".$id."'");
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
    
    function deletesemester() {
    $id = $_POST['id'];

    try {
        // Fetch the current semester's data
        $stmt = $this->connect->prepare("SELECT * FROM tbl_semester WHERE sem_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $prevData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prevData) {
            $data = array("status" => "404", "message" => "Semester not found");
            echo json_encode($data);
            return;
        }

        // Determine the new status
        $newStatus = ($prevData['status'] == 1) ? 2 : 1;

        // Check if another semester already has an open status if trying to activate
        if ($newStatus == 1) {
            $stmtCheck = $this->connect->prepare("SELECT * FROM tbl_semester WHERE status = 1 AND sem_id != :id");
            $stmtCheck->bindParam(':id', $id);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                $data = array(
                    "status" => "401",
                    "message" => "Another semester is already active. Please close it before activating this one."
                );
                echo json_encode($data);
                return;
            }
        }

        // Update the semester's status
        $stmtUpdate = $this->connect->prepare("UPDATE tbl_semester SET status = :status WHERE sem_id = :id");
        $stmtUpdate->bindParam(':status', $newStatus);
        $stmtUpdate->bindParam(':id', $id);

        if ($stmtUpdate->execute()) {
            $data = array("status" => "200", "message" => "Semester status updated successfully");
            echo json_encode($data);
        } else {
            $data = array("status" => "500", "message" => "Failed to update semester status");
            echo json_encode($data);
        }

    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
        echo json_encode($data);
    }
}

}	
		$sem=new Semester();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $sem->savesemester();
		        break;
		    case 'update':
		        $sem->updatesemester();
		        break;
		    case 'view':
		        $sem->viewsemester();
		        break;
            case 'delete':
                $sem->deletesemester();
                break;
		}

	?>

