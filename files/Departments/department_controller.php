<?php

include ('../../meet/con.php');
$connection=$conn;
class Department{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_department(){
        $pr_type = $_POST['p_type'];
        $fac = $_POST['fac_id'];
    	$full_name = ucwords($_POST['d_f_name']);
        $short_name = ucwords($_POST['d_s_name']);

        $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE dept_full_name='".$full_name."' AND prg_type='".$pr_type."' AND fac_id='".$fac."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_department(fac_id,prg_type,dept_full_name,dept_short_name) 
                  	VALUES('".$fac."','".$pr_type."','".$full_name."','".$short_name."')");
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
	
function view_department(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT tbl_department.*, tbl_program_type.campus_id 
                                         FROM tbl_department 
                                         INNER JOIN tbl_program_type ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                         WHERE tbl_department.dept_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();

        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type = :prg_type");
        $stmt2->execute([':prg_type' => $data['prg_type']]);
        $data2 = $stmt2->fetchAll();

        $info = array($data, $data2);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
        function update_department(){
        $id = $_POST['dep_id'];
        $pr_type = $_POST['p_type'];
        $fac = $_POST['fac_id'];
    	$full_name = $_POST['d_f_name'];
        $short_name = $_POST['d_s_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE dept_full_name='".$full_name."' AND prg_type='".$pr_type."' AND fac_id='".$fac."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_department SET fac_id='".$fac."',prg_type='".$pr_type."',dept_full_name='".$full_name."',dept_short_name='".$short_name."' WHERE dept_id='".$id."'");
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

	function delete_department(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE dept_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $stmt2 = $this->connect->prepare("UPDATE tbl_department SET status=2 WHERE dept_id='".$id."'");
        }
        else{
            $stmt2 = $this->connect->prepare("UPDATE tbl_department SET status=1 WHERE dept_id='".$id."'");  
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
            
    
function load_faculties(){
        $prg_type = $_POST['type'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type = :prg_type AND status=1");
        $stmt->execute([':prg_type' => $prg_type]);
        $data = $stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    function load_specs(){
    	$dept_id=$_POST['department'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE dept_id='".$dept_id."' AND status=1");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }        
        
}	
		$department=new Department();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $department->save_department();
		        break;
		    case 'update':
		        $department->update_department();
		        break;
		    case 'delete':
		        $department->delete_department();
		        break;
		    case 'view':
		        $department->view_department();
		        break;
		    case 'load_faculties':
		        $department->load_faculties();
		        break;
		    case 'load_specs':
		        $department->load_specs();
		        break;
		}

	?>

