<?php

include ('../../meet/con.php');
$connection=$conn;
class Campus{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_campus(){
    	$full_name = $_POST['camp_full_name'];
    	$location = $_POST['camp_city'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_campus WHERE camp_full_name='".$full_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "campus already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_campus(camp_full_name,camp_city) 
                  	VALUES('".$full_name."','".$location."')");
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "campus saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save campus!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
	
    function view_campus(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_campus WHERE camp_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function load_program_types(){
    	$id=$_POST['camp_id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_campus(){
        $id = $_POST['c_id'];    
    	$full_name = $_POST['camp_full_name'];
    	$location = $_POST['camp_city'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_campus WHERE camp_full_name='".$full_name."' AND camp_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Campus already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_campus set camp_full_name='".$full_name."',camp_city='".$location."' WHERE camp_id='".$id."'");
        if($stmt->execute()){
                $data = array("status"=>"200","message" => "campus updated successfully");
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

    
    	function manage_campus(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_campus WHERE camp_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['camp_active']==1){
                $status=2;
            }
            else{
                $status=1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_campus SET camp_active='".$status."' WHERE camp_id='".$id."'");
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
		$camp=new Campus();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $camp->save_campus();
		        break;
		    case 'update':
		        $camp->update_campus();
		        break;
		    case 'view':
		        $camp->view_campus();
		        break;
		    case 'delete':
		        $camp->manage_campus();
		        break;
		    case 'load_program_types':
		        $camp->load_program_types();
		        break;
		}

	?>

