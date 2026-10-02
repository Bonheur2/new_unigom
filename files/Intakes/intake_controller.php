<?php

include ('../../meet/con.php');
$connection=$conn;
class Intake{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_intake(){
        $prg_type=$_POST['prg_type'];
        $acad_cycle_id=$_POST['acad_cycle_id'];
        $dateString=$_POST['intake_month'];
        $intake_start=$_POST['intake_start'];
        $intake_end=$_POST['intake_end'];
        $app_start=$_POST['app_start'];
        $app_end=$_POST['app_end'];
        $reg_start=$_POST['reg_start'];
        $reg_end=$_POST['reg_end'];
        
        // Convert the date string to a UTC timestamp
        $timestamp = strtotime($dateString . "-01 00:00:00 UTC");
        
        // Format the UTC timestamp to display as "Month Year"
        $intake_month = date('F Y', $timestamp);
        $stmt = $this->connect->prepare("SELECT * FROM tbl_intake WHERE acad_cycle_id='".$acad_cycle_id."' AND prg_type='".$prg_type."' AND intake_month='".$intake_month."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_intake(prg_type,acad_cycle_id,intake_month,intake_start,intake_end,app_start,app_end,reg_start,reg_end) 
                  	VALUES('".$prg_type."','".$acad_cycle_id."','".$intake_month."','".$intake_start."','".$intake_end."','".$app_start."','".$app_end."','".$reg_start."','".$reg_end."')");
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
	
    function view_intake(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT tbl_intake.*,
                                        tbl_program_type.prg_type_full_name,
                                        tbl_acad_cycle.acad_year
                                        FROM tbl_intake 
                                        INNER JOIN tbl_program_type ON 
                                        tbl_intake.prg_type=tbl_program_type.prg_type_id
                                        INNER JOIN tbl_acad_cycle ON 
                                        tbl_intake.acad_cycle_id=tbl_acad_cycle.acad_cycle_id WHERE tbl_intake.intake_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_intake(){
        $id=$_POST['id'];
        $prg_type=$_POST['prg_type'];
        $acad_cycle_id=$_POST['acad_cycle_id'];
        $dateString=$_POST['intake_month'];
        $intake_start=$_POST['intake_start'];
        $intake_end=$_POST['intake_end'];
        $app_start=$_POST['app_start'];
        $app_end=$_POST['app_end'];
        $reg_start=$_POST['reg_start'];
        $reg_end=$_POST['reg_end'];
        
        // Convert the date string to a UTC timestamp
        $timestamp = strtotime($dateString . "-01 00:00:00 UTC");
        
        // Format the UTC timestamp to display as "Month Year"
        $intake_month = date('F Y', $timestamp);
        $stmt = $this->connect->prepare("SELECT * FROM tbl_intake WHERE acad_cycle_id='".$acad_cycle_id."' AND prg_type='".$prg_type."' AND intake_month='".$intake_month."' AND intake_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Intake already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_intake SET prg_type='".$prg_type."',
                                                               acad_cycle_id='".$acad_cycle_id."',
                                                               intake_month='".$intake_month."',
                                                               intake_start='".$intake_start."',
                                                               intake_end='".$intake_end."',
                                                               app_start='".$app_start."',
                                                               app_end='".$app_end."',
                                                               reg_start='".$reg_start."',
                                                               reg_end='".$reg_end."' 
                                                               WHERE intake_id='".$id."'");
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

    function load_students(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT r.reg_no,
                                                a.fname,a.lname
                                            FROM tbl_register_program_ug r
                                                INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                            WHERE r.intake_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
        
}	
		$intake=new Intake();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $intake->save_intake();
		        break;
		    case 'update':
		        $intake->update_intake();
		        break;
		    case 'view':
		        $intake->view_intake();
		        break;
		    case 'load_students':
		        $intake->load_students();
		        break;
		}

	?>

