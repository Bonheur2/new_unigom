<?php

include ('../../meet/con.php');
$connection=$conn;
class Program{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_type(){
    	$full_name = $_POST['p_f_name'];
        $short_name = $_POST['p_s_name'];
        $camp_id = $_POST['campus'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_full_name='".$full_name."' AND campus_id='".$camp_id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_program_type(prg_type_full_name,prg_type_short_name,campus_id) 
                  	VALUES('".$full_name."','".$short_name."','".$camp_id."')");
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
    function save_mode(){
    	$full_name = $_POST['f_name'];
        $short_name = $_POST['s_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_full_name='".$full_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_program_mode(prg_mode_full_name,prg_mode_short_name) 
                  	VALUES('".$full_name."','".$short_name."')");
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
	
    function view_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function view_mode(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

 
    function update_type(){
        $id = $_POST['pr_id'];    
        $full_name = $_POST['p_f_name'];
        $short_name = $_POST['p_s_name'];
        $campus= $_POST['campus'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_full_name='".$full_name."' AND campus_id='".$campus."' AND prg_type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_program_type set prg_type_full_name='".$full_name."',prg_type_short_name='".$short_name."',campus_id='".$campus."' WHERE prg_type_id='".$id."'");
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
    
    function update_mode(){
        $id=$_POST['pr_id'];
        $full_name = $_POST['p_f_name'];
        $short_name = $_POST['p_s_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_full_name='".$full_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_program_mode set prg_mode_full_name='".$full_name."',prg_mode_short_name='".$short_name."' WHERE prg_mode_id='".$id."'");
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

    	function delete_type(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_program_type SET status=2 WHERE prg_type_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_program_type SET status=1 WHERE prg_type_id='".$id."'");  
            }
            
            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            }
        }

    	function delete_mode(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_program_mode SET status=2 WHERE prg_mode_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_program_mode SET status=1 WHERE prg_mode_id='".$id."'");  
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
    
        function load_departments(){
        	$type=$_POST['type'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE prg_type='".$type."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function get_hod_departments($user){
            $deps = array();
            $stmt = $this->connect->prepare("SELECT * FROM tbl_department_hod WHERE identification = ? AND lead_end IS NULL");
            $stmt->execute([$user]);
            if($stmt->rowCount()>0){ 
                while($data = $stmt->fetch()){
                    array_push($deps, $data['dept_id']);
                }
            }
            return $deps;
        }
        function get_dean_faculties($user){
            $facs = array();
            $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty_dean WHERE identification = ? AND lead_end IS NULL");
            $stmt->execute([$user]);
            if($stmt->rowCount()>0){ 
                while($data = $stmt->fetch()){
                    array_push($facs, $data['fac_id']);
                }
            }
            return $facs;
        }
        
        function load_specs(){
        	$type = $_POST['prg_type'];
        	$user = $_POST['prvg'];
            $deps = $this->get_hod_departments($user); //only for HODs
            $facs = $this->get_dean_faculties($user); //only for Deans
            $depts = implode(',', $deps);
            $facts = implode(',', $facs);
            if(count($deps)>0){
                // HOD query string
                $query = "SELECT * FROM tbl_specialization WHERE prg_type='".$type."' AND dept_id IN ($depts)";
            }else if(count($facs)>0){
                // Dean query string
                $query = "SELECT * FROM tbl_specialization WHERE prg_type='".$type."' AND fac_id IN ($facts)";
            }
            else{ 
                //general query string
                $query = "SELECT * FROM tbl_specialization WHERE prg_type='".$type."'";
            }
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function load_faculties(){
        	$type=$_POST['type'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type='".$type."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function load_levels(){
        	$type=$_POST['type'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$type."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function load_intakes(){
        	$type=$_POST['type'];
        	$dt=date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT * FROM tbl_intake WHERE prg_type='".$type."' AND status=1 AND app_start<='".$dt."' AND app_end>='".$dt."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_class_levels(){
        	$spec=$_POST['spec'];
        	$dt=date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE splz_id='".$spec."' AND status=1 GROUP BY level_id");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_class_terms(){
        	$spec=$_POST['spec'];
        	$dt=date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE splz_id='".$spec."' AND level_id='".$_REQUEST['level_id']."' AND term_id >0 AND status=1 GROUP BY term_id");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_max_room(){
            $maxRoom =$this->connect->prepare("select COUNT(room_id) as rooms from tbl_block_rooms where block_id='".$_REQUEST['block']."'");
            $maxRoom->execute();
            if($maxRoom->rowCount()>0){
                $data=$maxRoom->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
            else{
                echo 0;
            }
        }
        
        function load_specs_per_mode(){
            $mode =$this->connect->prepare("select *from tbl_specialization where prg_type ='".$_REQUEST['type']."' and splz_id IN(select splz_id from tbl_register_program_ug where prg_mode_id ='".$_REQUEST['mode']."')");
            $mode->execute();
            $data=$mode->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function availableModes(){
            $type =$this->connect->prepare("SELECT prg_mode_id, prg_mode_full_name from tbl_program_mode where status =1 and  prg_mode_id IN(select prg_mode_id from tbl_register_program_ug where prg_type ='".$_REQUEST['type']."')");
            $type->execute();
            $data=$type->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        } 
        
        function deleteARecord(){
            $record =$this->connect->prepare("delete from tbl_program_block where prg_block_id = :id");
            $record->bindParam(':id', $_REQUEST['id'], PDO::PARAM_INT);
            $record->execute();
            if($record){
                echo 1;
            }
        }          
        
        function editARecord(){
            try {
                $updateRecord = $this->connect->prepare("UPDATE tbl_program_block SET block_id = :newid WHERE prg_block_id = :delid");
                $updateRecord->bindParam(':newid', $_REQUEST['newid'], PDO::PARAM_INT);
                $updateRecord->bindParam(':delid', $_REQUEST['delid'], PDO::PARAM_INT);
                $updateRecord->execute();
            
                if ($updateRecord) {
                    echo 1; 
                }
                else{
                    echo 2; 
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } 
        
        
        function table_levels(){
            $editspec =$this->connect->prepare("SELECT *from tbl_t_schedule where sub_class_id ='".$_REQUEST['spec']."' group by level_id");
            $editspec->execute();
            if($editspec){
                $data=$editspec->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }    

        function table_terms(){
            $editTerms =$this->connect->prepare("SELECT term_id from tbl_t_schedule where sub_class_id ='".$_REQUEST['spec']."' and level_id='".$_REQUEST['level']."' group by term_id");
            $editTerms->execute();
            if($editTerms){
                $data=$editTerms->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }   
        
        function table_days(){
            $editDay =$this->connect->prepare("SELECT day_id, day_name from tbl_t_days where day_id IN
            (SELECT learning_day_id from tbl_t_schedule where sub_class_id ='".$_REQUEST['spec']."' and level_id='".$_REQUEST['level']."' and term_id ='".$_REQUEST['term']."' group by learning_day_id)");
            $editDay->execute();
            if($editDay){
                $data=$editDay->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }            
        
        function table_hours(){
            $editHour =$this->connect->prepare("SELECT hour_id from tbl_t_schedule where sub_class_id ='".$_REQUEST['spec']."' and level_id='".$_REQUEST['level']."' and term_id ='".$_REQUEST['term']."'  and learning_day_id ='".$_REQUEST['day']."' ORDER BY hour_id ASC");
            $editHour->execute();
            if($editHour){
                $data=$editHour->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }  
        
        function table_unique_hours(){
            $setHourTo =$this->connect->prepare("SELECT hour_id from tbl_t_schedule where hour_id NOT IN
            ( SELECT hour_id from tbl_t_schedule where sub_class_id ='".$_REQUEST['spec']."' and level_id='".$_REQUEST['level']."' and term_id ='".$_REQUEST['term']."' and learning_day_id ='".$_REQUEST['day']."') GROUP BY hour_id ORDER BY hour_id ASC");
            $setHourTo->execute();
            if($setHourTo){
                $data=$setHourTo->fetchAll();
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
        
         function load_all_intakes(){
        	$type=$_POST['type'];
        	$dt=date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT i.intake_id as intake_id,i.intake_month as intake_month,a.acad_year as acad_year , i.acad_cycle_id as acad_cycle_id,
                                                                            			b.prg_type_full_name AS prg_type_full_name,b.prg_type_short_name AS prg_type_short_name 
                                                                            			FROM tbl_intake i,tbl_acad_cycle a,tbl_program_type b 
                                                                            			WHERE i.acad_cycle_id=a.acad_cycle_id AND i.prg_type=b.prg_type_id and  prg_type='".$type."' and i.status=1
                                                                            			ORDER BY i.intake_id DESC");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
}	
		$program=new Program();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $program->save_type();
		        break;
		    case 'register_mode':
		        $program->save_mode();
		        break;
		    case 'update':
		        $program->update_type();
		        break;
		    case 'update_mode':
		        $program->update_mode();
		        break;
		    case 'delete':
		        $program->delete_type();
		        break;
		    case 'delete_mode':
		        $program->delete_mode();
		        break;
		    case 'view':
		        $program->view_type();
		        break;
		    case 'view_mode':
		        $program->view_mode();
		        break;
		    case 'load_faculties':
		        $program->load_faculties();
		        break;
		    case 'load_departments':
		        $program->load_departments();
		        break;
		    case 'load-specs':
		        $program->load_specs();
		        break;
		    case 'load_levels':
		        $program->load_levels();
		        break;
		    case 'load_intakes':
		        $program->load_intakes();
		        break;
            case 'load-class-levels':
		        $program->load_class_levels();
		        break;
            case 'load-class-terms':
		        $program->load_class_terms();
		        break;	
		        
		    case 'load-max-room':
		        $program->load_max_room();
		        break;
		        
		    case 'load-specs-per-mode':
		       $program->load_specs_per_mode();
		       break;
		       
		    case 'availableModes':
		      $program->availableModes();
		      break;
		      
		    case 'delete-record':
		      $program->deleteARecord();
		      break;
		      
		    case 'edit_record':
		      $program->editARecord();
		      break;
		      
		  case 'table-levels':
		      $program->table_levels();
		      break;
		      
		  case 'table-terms':
		      $program->table_terms();
		      break;
		      
		  case 'table-days':
		      $program->table_days();
		      break;
		      
		        case 'load_all_intakes':
		        $program->load_all_intakes();
		        break;
		      
		  case 'table-hours':
		      $program->table_hours();
		      break;
		      
		  case 'table-unique-hours':
		      $program->table_unique_hours();
		      break;		      
		            
		}

	?>

