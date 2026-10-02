<?php
    include ('../../meet/con.php');
    $connection=$conn;
    
    class THour{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    	}
    	
    	function sendFeedback($status, $message){
            $data = array("status" => $status, "message" => $message);
            $jsonData = json_encode($data);
            echo $jsonData; 
    	}
    	
    	function checkOccurenceInRange($prg_mode, $initial, $final){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_t_hours WHERE prg_mode_id = ? AND (
                (hour_lower_limit <= ? AND hour_upper_limit >= ?) OR
                (hour_lower_limit >= ? AND hour_upper_limit <= ?) OR
                (hour_lower_limit <= ? AND hour_upper_limit <= ? AND hour_upper_limit >= ?) OR
                (hour_lower_limit >= ? AND hour_upper_limit >= ? AND hour_lower_limit <= ?))
                ");
            $stmt->execute([$prg_mode, $initial, $final, $initial, $final, $initial, $final, $initial, $initial, $final, $final]);
            return $stmt->rowCount();
    	}
    	
    	function checkOccurenceInRangeWithId($prg_mode, $initial, $final, $hour_id){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_t_hours WHERE prg_mode_id = ? AND hour_id != ? AND (
                (hour_lower_limit <= ? AND hour_upper_limit >= ?) OR
                (hour_lower_limit >= ? AND hour_upper_limit <= ?) OR
                (hour_lower_limit <= ? AND hour_upper_limit <= ? AND hour_upper_limit >= ?) OR
                (hour_lower_limit >= ? AND hour_upper_limit >= ? AND hour_lower_limit <= ?)
                )");
                                                        
            $stmt->execute([$prg_mode, $hour_id, $initial, $final, $initial, $final, $initial, $final, $initial, $initial, $final, $final]);
            return $stmt->rowCount();
    	}
    	
        function save_THour(){
            $prg_mode_id = $_POST['prg_mode_id'];
        	$hour_lower_limit = $_POST['hour_lower_limit'];
            $hour_upper_limit = $_POST['hour_upper_limit'];
            
            if($this->checkOccurenceInRange($prg_mode_id, $hour_lower_limit, $hour_upper_limit) > 0){
                $this->sendFeedback(401, "Data already exists!");
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_t_hours(prg_mode_id, hour_lower_limit, hour_upper_limit)VALUES(?, ?, ?)");
                if($stmt->execute([$prg_mode_id, $hour_lower_limit, $hour_upper_limit])){
                    $this->sendFeedback(200, "Data saved successfully!");
                } else {
                    $this->sendFeedback(401, "Failed to save data!");
                }
            }
    	}
    	
        function view_THour(){
        	$id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_t_hours WHERE hour_id = ?");
            $stmt->execute([$id]);
            
            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    
        function update_THour(){
            $id = $_POST['hour_id'];
            $prg_mode_id = $_POST['prg_mode_id'];
            $hour_lower_limit = $_POST['hour_lower_limit'];
            $hour_upper_limit = $_POST['hour_upper_limit'];
            
            if($this->checkOccurenceInRangeWithId($prg_mode_id, $hour_lower_limit, $hour_upper_limit, $id)>0){
                $this->sendFeedback(401, "Data already exists!");
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_t_hours set hour_lower_limit = ?, hour_upper_limit = ?, prg_mode_id = ? WHERE hour_id = ?");
                if($stmt->execute([$hour_lower_limit, $hour_upper_limit, $prg_mode_id, $id])){
                    $this->sendFeedback(200, "data updated successfully!");
                } else {
                    $this->sendFeedback(401, "Failed to update data!");
                }
            }
        }

    	function delete_THour(){
            $id = $_POST['id'];
            $stmt2 = $this->connect->prepare("DELETE FROM tbl_t_hours WHERE hour_id = ?");
            if($stmt2->execute([$id])){
                $this->sendFeedback(200, "data removed successfully!");
            } else {
                $this->sendFeedback(401, "Failed to removed data!");
            }
        }
        
        function getClassSize($splzId, $levelId, $prg_mode, $moduleId){
            $stmt = $this->connect->prepare("SELECT DISTINCT(mm.reg_no) FROM tbl_markby_module mm INNER JOIN tbl_register_program_ug ug ON mm.reg_no = ug.reg_no WHERE ug.splz_id = ? AND ug.level_id = ? AND ug.prg_mode_id = ? AND mm.module_id = ? AND mm.status = 1 AND mm.enrolled = 1 AND ug.reg_active = 1");
            $stmt->execute([$splzId, $levelId, $prg_mode, $moduleId]);
            return $stmt->rowCount();
        }
        
        function load_available_rooms(){
            $schedule = $_POST['schedule'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_t_schedule WHERE schedule_id = ?");
            $stmt->execute([$schedule]);
            $sdata = $stmt->fetch();
            
            $class_size = $this->getClassSize($sdata['splz_id'], $sdata['level_id'], $sdata['prg_mode'], $sdata['module_id']);

            $stmt2 = $this->connect->prepare("SELECT room_id, room_name, room_size FROM tbl_block_rooms WHERE room_size >= ? AND status = 1 AND room_id NOT IN (
                SELECT room_id FROM tbl_t_schedule WHERE s_date = '".$sdata['s_date']."' AND hour_id = '".$sdata['hour_id']."' 
                ) ORDER BY room_size ASC");
            $stmt2->execute([$class_size]);
            echo json_encode($stmt2->fetchAll());
        }
        
        function update_schedule(){
            $schedule = $_POST['schedule_id'];
            
            $stmt0 = $this->connect->prepare("SELECT * FROM tbl_t_schedule WHERE schedule_id = ?");
            $stmt0->execute([$schedule]);
            $sdata = $stmt0->fetch();
            
            $room = $_POST['room_id'];
            $stmt = $this->connect->prepare("UPDATE tbl_t_schedule SET room_id = ? WHERE module_id = ? AND splz_id = ? AND prg_mode = ? AND term_id = ?");
            if($stmt->execute([$room, $sdata['module_id'], $sdata['splz_id'], $sdata['prg_mode'], $sdata['term_id']])){
               $this->sendFeedback(200, "Schedule updated successfully!");
            }else{
                $this->sendFeedback(400, "Failed to update schedule!");
            }
        }
        
        function update_schedule_special(){
            $schedule = $_POST['schedule_id'];
            
            $stmt0 = $this->connect->prepare("SELECT * FROM tbl_special_schedule WHERE schedule_id = ?");
            $stmt0->execute([$schedule]);
            $sdata = $stmt0->fetch();
            
            $room = $_POST['room_id'];
            $stmt = $this->connect->prepare("UPDATE tbl_special_schedule SET room_id = ? WHERE module_id = ? AND splz_id = ? AND prg_mode = ?");
            if($stmt->execute([$room, $sdata['module_id'], $sdata['splz_id'], $sdata['prg_mode']])){
               $this->sendFeedback(200, "Schedule updated successfully!");
            }else{
                $this->sendFeedback(400, "Failed to update schedule!");
            }
        }
    }
    
	$THour = new THour();
    $action = $_POST['action'];
	switch($action){
	    case 'register':
	        $THour->save_THour();
	        break;
	    case 'update':
	        $THour->update_THour();
	        break;
	    case 'delete':
	        $THour->delete_THour();
	        break;
	    case 'view':
	        $THour->view_THour();
	        break;
	    case 'load-available-rooms':
	        $THour->load_available_rooms();
	        break;
	    case 'update_schedule':
	        $THour->update_schedule();
	        break;
	    case 'update_schedule_special':
	        $THour->update_schedule_special();
	        break;
	}

?>

