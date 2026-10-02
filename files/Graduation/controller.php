<?php
    include ('../../meet/con.php');
    $connection=$conn;
    class GraduationCycle{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    	  }
        function save_GraduationCycle(){
            $acad_cycle_id=$_POST['acad_cycle_id'];
            $grad_date=$_POST['grad_date'];
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_grad_cycle WHERE acad_cycle_id = ? AND grad_date = ?");
            $stmt->execute([$acad_cycle_id, $grad_date]);
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Date already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_grad_cycle(acad_cycle_id, grad_date) 
                      	VALUES(?, ?)");
                if($stmt->execute([$acad_cycle_id, $grad_date])){
                    $data = array("status"=>"200","message" => "Date saved successfully!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                } else {
                    $data = array("status"=>"401","message" => "Failed to save data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
    
    	}
    	
        function view_GraduationCycle(){
        	$id=$_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_grad_cycle WHERE grad_cycle_id = ?");
            $stmt->execute([$id]);
            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function update_GraduationCycle(){
            $id=$_POST['grad_cycle_id'];
            $acad_cycle_id=$_POST['acad_cycle_id'];
            $grad_date=$_POST['grad_date'];

            $stmt = $this->connect->prepare("SELECT * FROM tbl_grad_cycle WHERE acad_cycle_id = ? AND grad_date = ? AND grad_cycle_id != ?");
            $stmt->execute([$acad_cycle_id, $grad_date, $id]);
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Date already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
            $stmt = $this->connect->prepare("UPDATE tbl_grad_cycle SET acad_cycle_id = ?, grad_date = ? WHERE grad_cycle_id = ?");
            if($stmt->execute([$acad_cycle_id, $grad_date, $id])){
                    $data = array("status"=>"200","message" => "data updated successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                } else {
                    $data = array("status"=>"401","message" => "Failed to update data");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        }
    }
    
	$GraduationCycle = new GraduationCycle();
    $action = $_POST['action'];
	switch($action){
	    case 'register':
	        $GraduationCycle->save_GraduationCycle();
	        break;
	    case 'update':
	        $GraduationCycle->update_GraduationCycle();
	        break;
	    case 'view':
	        $GraduationCycle->view_GraduationCycle();
	        break;
	}

?>

