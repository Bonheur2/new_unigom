<?php
    include ('../../meet/con.php');
    $connection=$conn;
    
    class Room{
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
    	
    	function checkOccurence($block_id, $room_name){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_block_rooms WHERE block_id = ? AND room_name = ?");
            $stmt->execute([$block_id, $room_name]);
            return $stmt->rowCount();
    	}
    	
    	function checkOccurenceWithId($block_id, $room_name, $room_id){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_block_rooms WHERE block_id = ? AND room_name = ? AND room_id != ?");
                                                        
            $stmt->execute([$block_id, $room_name, $room_id]);
            return $stmt->rowCount();
    	}
    	
        function save_Room(){
            $block_id = $_POST['block_id'];
        	$room_name = $_POST['room_name'];
            $room_size = $_POST['room_size'];
            
            if($this->checkOccurence($block_id, $room_name) > 0){
                $this->sendFeedback(401, "Data already exists!");
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_block_rooms(block_id, room_name, room_size)VALUES(?, ?, ?)");
                if($stmt->execute([$block_id, $room_name, $room_size])){
                    $this->sendFeedback(200, "Data saved successfully!");
                } else {
                    $this->sendFeedback(401, "Failed to save data!");
                }
            }
    	}
    	
        function view_Room(){
        	$id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_block_rooms WHERE room_id = ?");
            $stmt->execute([$id]);
            
            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    
        function update_Room(){
            $id = $_POST['room_id'];
            $block_id = $_POST['block_id'];
            $room_name = $_POST['room_name'];
            $room_size = $_POST['room_size'];
            
            if($this->checkOccurenceWithId($block_id, $room_name, $id)>0){
                $this->sendFeedback(401, "Data already exists!");
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_block_rooms set room_name = ?, room_size = ?, block_id = ? WHERE room_id = ?");
                if($stmt->execute([$room_name, $room_size, $block_id, $id])){
                    $this->sendFeedback(200, "data updated successfully!");
                } else {
                    $this->sendFeedback(401, "Failed to update data!");
                }
            }
        }

        function manage_Room(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_block_rooms WHERE room_id = ?");
            $stmt->execute([$id]);
            $prevData=$stmt->fetch();
            if($prevData['status'] == 1){
                $status = 2;
            }
            else{
                $status = 1;
            }

            $stmt2 = $this->connect->prepare("UPDATE tbl_block_rooms SET status = ? WHERE room_id = ?");
            if($stmt2->execute([$status, $id])){
                $this->sendFeedback(200, "operation done successfully!");  
            } else {
                $this->sendFeedback(401, "operation failed");  
            }
        }
    }
    
	$room = new Room();
    $action = $_POST['action'];
	switch($action){
	    case 'register':
	        $room->save_Room();
	        break;
	    case 'update':
	        $room->update_Room();
	        break;
	    case 'delete':
	        $room->manage_Room();
	        break;
	    case 'view':
	        $room->view_Room();
	        break;
	}

?>