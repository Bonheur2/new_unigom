<?php
include ('../../meet/con.php');
$connection=$conn;
class Settings{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	function updateInfo(){
        $stmt = $this->connect->prepare("UPDATE tbl_university SET 
                                                                full_name='".$_POST['full_name']."',
                                                                short_name='".$_POST['short_name']."',
                                                                country='".$_POST['country']."',
                                                                location='".$_POST['location']."',
                                                                phone='".$_POST['phone']."',
                                                                email='".$_POST['email']."',
                                                                website='".$_POST['website']."',
                                                                po_box='".$_POST['po_box']."'");
        if($stmt->execute()) {
            echo 200;
        }
        else{
            echo 500;
        }
	}
	function uploadLogo(){
	    $file_name = $_FILES['logo']['name'];
		$file_size = $_FILES['logo']['size'];
		$file_tmp = $_FILES['logo']['tmp_name'];
		$file_type = $_FILES['logo']['type'];
		$tmp = explode('.', $_FILES['logo']['name']);
		$file_ext = end($tmp);

		if ($file_size > 3145728) {
			echo 401;
		} else{
			move_uploaded_file($file_tmp, "../../img/logo/" . $file_name);
			$img = "/img/logo/" . $file_name;
            $stmt = $this->connect->prepare("UPDATE tbl_university SET logo='".$img."'");
            if($stmt->execute()) {
                echo 200;
            }
            else{
                echo 500;
            }
        }   
    }
}	
		$set=new Settings();
	    $action = $_POST['action'];
		switch($action){
		    case 'update_details':
		        $set->updateInfo();
		        break;
		    case 'upload_logo':
		        $set->uploadLogo();
		        break;
		}

	?>

