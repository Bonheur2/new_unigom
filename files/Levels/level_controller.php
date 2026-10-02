<?php

include ('../../meet/con.php');
$connection=$conn;
class Level{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_level(){
        $pr_type = $_POST['l_type'];
    	$full_name = $_POST['l_f_name'];
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_level WHERE level_full_name='".$full_name."' AND prg_type='".$pr_type."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $checkLevel_no=$this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$pr_type."'");
            $checkLevel_no->execute();
            $c=$checkLevel_no->rowCount();
            if($c==0){
                $level_no=1;
            }
            else{
                $c++;
                $level_no=$c;
            }
            $stmt = $this->connect->prepare("INSERT INTO tbl_level(level_no,prg_type,level_full_name) 
                  	VALUES('".$level_no."','".$pr_type."','".$full_name."')");
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
    function view_level(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_level WHERE level_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
            }
        function update_level(){
        $id = $_POST['pr_id'];
        $prg_type = $_POST['l_type'];
        $full_name = $_POST['l_f_name'];
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_level WHERE level_full_name='".$full_name."' AND prg_type='".$pr_type."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_level set level_full_name='".$full_name."',prg_type='".$prg_type."' WHERE level_id='".$id."'");
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

    	function delete_level(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_level WHERE level_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_level SET status=2 WHERE level_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_level SET status=1 WHERE level_id='".$id."'");  
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
		$level=new Level();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $level->save_level();
		        break;
		    case 'update':
		        $level->update_level();
		        break;
		    case 'delete':
		        $level->delete_level();
		        break;
		    case 'view':
		        $level->view_level();
		        break;
		}

	?>

