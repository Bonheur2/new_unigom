<?php
    include('../../meet/con.php');
    $connection = $conn;
    
    class Vote {
        private $connect;
        public function __construct(){
            global $connection;
            $this->connect = $connection;
        }
        
        function generatefile() {
            $characters = '0123456789';
            $code = '';
        
            for ($i = 0; $i < 16; $i++) {
                $randomIndex = mt_rand(0, strlen($characters) - 1);
                $code .= $characters[$randomIndex];
            }
        
            return $code;
        }
        
    	function upload_file($uploadedFile){
            $uploadDirectory = "./padmin/";
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
            $fileName = $this->generatefile() . '.' . $fileExtension;
            
            $destination = $uploadDirectory . $fileName;
            move_uploaded_file($uploadedFile['tmp_name'], $destination);
            return "https://".$_SERVER['SERVER_NAME']."/files/Votes/padmin/".$fileName;
    	}
    	
    	function update_post_file($post_file, $id){
            $stmt = $this->connect->prepare("UPDATE tbl_candidates SET post_file = ? WHERE id = ?");
            $stmt->execute([$post_file, $id]);
            return;
    	}
    	
    	###########################################################################
        
        function save_session(){
            $vote_session = $_POST['vote_session'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $statement = $this->connect->prepare("INSERT INTO tbl_voting_sessions(vote_session, start, end) VALUES (?, ?, ?)");
            if ($statement->execute([$vote_session, $start, $end])) {
                $data = array("status" => 200, "message" => "session saved successfully!");
                echo json_encode($data);
            } else {
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        function view_session(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_voting_sessions WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData;
        }
        
        function update_session(){
            $id = $_POST['id'];
            $vote_session = $_POST['vote_session'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $stmt = $this->connect->prepare("UPDATE tbl_voting_sessions SET vote_session = ?, start = ?, end = ? WHERE id = ?");
            if ($stmt->execute([$vote_session, $start, $end, $id])) {
                $data = array("status" => 200, "message" => "session updated successfully!");
                echo json_encode($data);
            } else {
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        function manage_session(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_voting_sessions WHERE id = ?");
            $stmt->execute([$id]);
            $status = $stmt->fetch()['status'];
            $status = $status == 1 ? 2 : 1;
            $stmt = $this->connect->prepare("UPDATE tbl_voting_session SET status = ? WHERE id = ?");
            if ($stmt->execute([$status, $id])) {
                $data = array("status" => 200, "message" => "session updated successfully!");
                echo json_encode($data);
            } else {
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        ##################################### End Vote Session ######################################
        
        function save_position(){
            $position = $_POST['position'];
            $description = $_POST['description'];
            $statement = $this->connect->prepare("INSERT INTO tbl_voting_position(position, description) VALUES (?, ?)");
            if($statement->execute([$position, $description])){
                $data = array("status" => 200, "message" => "Position saved successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
                
        }
        
        function view_position(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_voting_position WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData;
        }
        
        function update_position(){
            $id = $_POST['id'];
            $position = $_POST['position'];
            $description = $_POST['description'];
            $stmt = $this->connect->prepare("UPDATE tbl_voting_position SET position = ?, description = ? WHERE id = ?");
            if($stmt->execute([$position, $description, $id])){
                $data = array("status" => 200, "message" => "Position updated successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        function manage_position(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_voting_position WHERE id = ?");
            $stmt->execute([$id]);
            $status = $stmt->fetch()['status'];
            $status = $status==1?2:1;
            $stmt = $this->connect->prepare("UPDATE tbl_voting_position SET status = ? WHERE id = ?");
            if($stmt->execute([$status, $id])){
                $data = array("status" => 200, "message" => "Position updated successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        ##################################### End Vote Session ######################################
        
        function save_candidate(){
            $candidate = $_POST['candidate'];
            $vote_id = $_POST['vote_id'];
            $position_id = $_POST['position_id'];
            $campus_id = $_POST['campus_id'];
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_candidates WHERE candidate = ? AND vote_id = ? AND campus_id = ?");
            $stmt->execute([$candidate, $vote_id, $campus_id]);
            if($stmt->rowCount()==0){
                if(isset($_FILES['post_file'])){
                    $post_file = $this->upload_file($_FILES['post_file']);
                }else{
                    $post_file = `NULL`;
                }
                
                $statement = $this->connect->prepare("INSERT INTO tbl_candidates(candidate, vote_id, position_id, post_file, campus_id) VALUES (?, ?, ?, ?, ?)");
                if($statement->execute([$candidate, $vote_id, $position_id, $post_file, $campus_id])){
                    $data = array("status" => 200, "message" => "candidate saved successfully!");
                    echo json_encode($data);
                } else{
                    $data = array("status" => 400, "message" => "Something went wrong, retry!");
                    echo json_encode($data);
                }
            } else{
                $data = array("status" => 400, "message" => "candidate already exists!");
                echo json_encode($data);
            }   
        }
        
        function view_candidate(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_candidates WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData;
        }
        
        function update_candidate(){
            $id = $_POST['id'];
            $candidate = $_POST['candidate'];
            $vote_id = $_POST['vote_id'];
            $position_id = $_POST['position_id'];
            $campus_id = $_POST['campus_id'];
            
            if(isset($_FILES['post_file'])){
                $post_file = $this->upload_file($_FILES['post_file']);
                $this->update_post_file($post_file, $id);
            }
            
            $stmt = $this->connect->prepare("UPDATE tbl_candidates SET candidate = ?, vote_id = ?, position_id = ?, campus_id = ? WHERE id = ?");
            if($stmt->execute([$candidate, $vote_id, $position_id, $campus_id, $id])){
                $data = array("status" => 200, "message" => "Position updated successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        function manage_candidate(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_candidates WHERE id = ?");
            $stmt->execute([$id]);
            $status = $stmt->fetch()['status'];
            $status = $status==1?2:1;
            $stmt = $this->connect->prepare("UPDATE tbl_candidates SET status = ? WHERE id = ?");
            if($stmt->execute([$status, $id])){
                $data = array("status" => 200, "message" => "Position updated successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        
        function save_vote(){
            $candidate = $_POST['candidate'];
            $voter = $_POST['voter'];
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_candidates WHERE id = ?");
            $stmt->execute([$candidate]);
            $data = $stmt->fetch();
            
            $position = $data['position_id'];
            $vote_id = $data['vote_id'];
            
            $stmt = $this->connect->prepare("UPDATE tbl_candidates SET votes = votes + 1 WHERE id = ?");
            if($stmt->execute([$candidate])){
                $stmte = $this->connect->prepare("INSERT INTO tbl_votes(voter, vote_id, position_id) VALUES(?, ?, ?)");
                $stmte->execute([$voter, $vote_id, $position]);
                
                $data = array("status" => 200, "message" => "vote added successfully!");
                echo json_encode($data);
            } else{
                $data = array("status" => 400, "message" => "Something went wrong, retry!");
                echo json_encode($data);
            }
        }
        

    }
    
    $vote = new Vote();
    $action = $_POST['action'];
    switch ($action) {
        case 'save_session':
            $vote->save_session();
            break;
        case 'update_session':
            $vote->update_session();
            break;
        case 'view_session':
            $vote->view_session();
            break;
        case 'delete_session':
            $vote->manage_session();
            break;
        
        #####################################
        case 'register':
            $vote->save_position();
            break;
        case 'update':
            $vote->update_position();
            break;
        case 'view':
            $vote->view_position();
            break;
        case 'delete':
            $vote->manage_position();
            break;
            
        #####################################    
        case 'save_candidate':
            $vote->save_candidate();
            break;
        case 'update_candidate':
            $vote->update_candidate();
            break;
        case 'view_candidate':
            $vote->view_candidate();
            break;
        case 'delete_candidate':
            $vote->manage_candidate();
            break;
            
        #####################################    
        case 'vote':
            $vote->save_vote();
            break;
    }
?>