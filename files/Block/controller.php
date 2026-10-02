<?php
    include ('../../meet/con.php');

    $connection=$conn;
    class Block{
        private $connect;
        public function __construct() {
            global $connection;
            $this->connect=$connection;
        }
        
        function sendFeedback($status, $message){
            $data = array("status"=>$status,"message" => $message);
            $jsonData = json_encode($data);
            echo $jsonData;   
        }
        function save_block(){
            $block_full_name = $_POST['block_full_name'];
            $block_short_name = $_POST['block_short_name'];
            $campus=$_POST['camp_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_blocks WHERE block_full_name = ? AND block_short_name = ? AND campus=? ");
            $stmt->execute([$block_full_name, $block_short_name,$campus]);
            
            if($stmt->rowCount()>0){
                $this->sendFeedback(401, "block already exists!");  
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_blocks(block_full_name, block_short_name,campus) VALUES(?, ?, ?)");
                
                if($stmt->execute([$block_full_name, $block_short_name,$campus])){
                    $this->sendFeedback(200, "block saved successfully!");  
                }else{
                    $this->sendFeedback(401, "Failed to save block!");  
                }
            }

        }
        
        function view_block(){
            $id=$_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_blocks WHERE block_id = ?");
            $stmt->execute([$id]);

            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
                
        function update_block(){
        $id = $_POST['block_id'];    
        $block_full_name = $_POST['block_full_name'];
        $block_short_name = $_POST['block_short_name'];
        $camp_id = $_POST['camp_id'];

        // Check if block already exists
        $stmt = $this->connect->prepare("SELECT * FROM tbl_blocks WHERE block_full_name = ? AND block_short_name = ? AND block_id != ? AND campus = ?");
        $stmt->execute([$block_full_name, $block_short_name, $id, $camp_id]);
    
        if($stmt->rowCount() > 0){
            $this->sendFeedback(401, "Block already exists!");    
        } else {
            // Fix the UPDATE statement
            $stmt = $this->connect->prepare("UPDATE tbl_blocks SET block_full_name = ?, block_short_name = ?, campus = ? WHERE block_id = ?");
            if($stmt->execute([$block_full_name, $block_short_name, $camp_id, $id])){ // Fix argument order
                $this->sendFeedback(200, "Block updated successfully!");   
            } else {
                $this->sendFeedback(401, "Failed to update data!");  
            }
        }
    }


        
        function manage_block(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_blocks WHERE block_id = ?");
            $stmt->execute([$id]);
            $prevData=$stmt->fetch();
            if($prevData['status'] == 1){
                $status = 2;
            }
            else{
                $status = 1;
            }

            $stmt2 = $this->connect->prepare("UPDATE tbl_blocks SET status = ? WHERE block_id = ?");
            if($stmt2->execute([$status, $id])){
                $this->sendFeedback(200, "operation done successfully!");  
            } else {
                $this->sendFeedback(401, "operation failed");  
            }
        }   
    }	

    $block=new Block();
    $action = $_POST['action'];
    switch($action){
        case 'register':
            $block->save_block();
            break;
        case 'update':
            $block->update_block();
            break;
        case 'view':
            $block->view_block();
            break;
        case 'delete':
            $block->manage_block();
            break;
    }

?>

