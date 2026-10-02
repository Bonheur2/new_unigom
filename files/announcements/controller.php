<?php

include ('../../meet/con.php');
$connection=$conn;
class Announcement{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	function sendFeedBack($s,$m){
            $data = array("status"=>$s,"message" => $m);
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;  
	}
    function save_ann(){
        $title=$_POST['title'];
        $target=$_POST['target'];
        $expires=$_POST['expires_at'];
        $user=$_POST['user'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_announcement WHERE title='".$title."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedBack(401,"announcement already exists!");
        } else {
            
            if(isset($_FILES['file']) && $_FILES['file']['name']!=""){
        		$errors = array();
        		$file_name = $_FILES['file']['name'];
        		$file_size = $_FILES['file']['size'];
        		$file_tmp = $_FILES['file']['tmp_name'];
        		$file_type = $_FILES['file']['type'];
        		$tmp = explode('.', $_FILES['file']['name']);
        		$file_ext = end($tmp);
        		$extensions = array("jpeg", "JPG", "png","jpg",pdf);
        		
        		if (in_array($file_ext, $extensions) === false) {
        			array_push($errors,'extension not allowed.');
        			$this->sendFeedBack(401,"file extension not allowed");
        		}
        
        		else if ($file_size > 5242880) {
        			array_push($errors,'File size must be excately 5 MB');
        			$this->sendFeedBack(401,"file size must be atmost 5MB");
        			}
        		if (empty($errors) == true) {
        			move_uploaded_file($file_tmp, $file_name);
        			$file = "/files/announcements/" . $file_name;
                    $stmt = $this->connect->prepare("INSERT INTO tbl_announcement(title,file,expires_at,target,user) 
                          	VALUES('".$title."','".$file."','".$expires."','".$target."','".$user."')");
                    if($stmt->execute()){
                        $this->sendFeedBack(200,"Data published successfully!");
                    } else {
                        $this->sendFeedBack(401,"Failed to publish data!");
                    }
        		}
            }
        }

	}
	
    function view_ann(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_announcement WHERE id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

 
        function update_ann(){
            $id=$_POST['id'];
            $title=$_POST['title'];
            $target=$_POST['target'];
            $expires=$_POST['expires_at'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_announcement WHERE title='".$title."' AND id!='".$id."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $this->sendFeedBack(401,"Title already exists!");
            } else {
                    $stmt = $this->connect->prepare("UPDATE tbl_announcement SET 
                                                                                title='".$title."',
                                                                                target='".$target."',
                                                                                expires_at='".$expires."'
                                                                                WHERE id='".$id."'"); 
                        if($stmt->execute()){
                            $this->sendFeedBack(200,"Data saved successfully!");
                        } else {
                            $this->sendFeedBack(401,"Failed to update data!");
                        }
                }
        }

    	function delete_ann(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT file FROM tbl_announcement WHERE id='".$id."'");
            $stmt->execute();
            $filePath=$stmt->fetch();
            $file = str_replace("/files/announcements/", "", $filePath['file']);
            if (file_exists($file)) {
                unlink($file);
            }
            $stmt = $this->connect->prepare("DELETE FROM tbl_announcement WHERE id='".$id."'");
            if($stmt->execute()){
                $this->sendFeedBack(200,"operation done successfully");
            } else {
                $this->sendFeedBack(401,"Failed to remove data!");
                }
        }
        
        function readAll(){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_announcement");
            $stmt->execute();
            while($data=$stmt->fetch()){
                $storedReaders = json_decode($data['readers'], true);
                $reader = $_POST['reader'];
                if (!in_array($reader, $storedReaders)) {
                    $storedReaders[] = $reader;
                    $newReaders=json_encode($storedReaders);
                    $update = $this->connect->prepare("UPDATE tbl_announcement SET readers='".$newReaders."' WHERE id='".$data['id']."'");
                    $update->execute();
                }
            }
            echo 1;
        }
        function read(){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_announcement WHERE id='".$_POST['not']."'");
            $stmt->execute();
            $data=$stmt->fetch();
            $storedReaders = json_decode($data['readers'], true);
            $reader = $_POST['reader'];
            if (!in_array($reader, $storedReaders)) {
                $storedReaders[] = $reader;
                $newReaders=json_encode($storedReaders);
                $update = $this->connect->prepare("UPDATE tbl_announcement SET readers='".$newReaders."' WHERE id='".$_POST['not']."'");
                $update->execute();
                }
            echo 1;
        }
}	
		$announce=new Announcement();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $announce->save_ann();
		        break;
		    case 'update':
		        $announce->update_ann();
		        break;
		    case 'delete':
		        $announce->delete_ann();
		        break;
		    case 'view':
		        $announce->view_ann();
		        break;
		    case 'read_all':
		        $announce->readAll();
		        break;
		    case 'read':
		        $announce->read();
		        break;
		}

	?>

