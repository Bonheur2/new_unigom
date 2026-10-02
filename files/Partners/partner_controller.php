<?php

include ('../../meet/con.php');
$connection=$conn;
class Partner{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_partner(){
        $par_full_name=$_POST['par_full_name'];
        $par_short_name=$_POST['par_short_name'];
        $par_country=$_POST['par_country'];
        $par_province=$_POST['par_province'];
        $par_city=$_POST['par_city'];
        $par_mou_ref=$_POST['par_mou_ref'];
        $par_yor=date('Y-m-d');
        $stmt = $this->connect->prepare("SELECT * FROM tbl_partner WHERE par_full_name='".$par_full_name."' AND par_country='".$par_country."' AND par_mou_ref='".$par_mou_ref."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            
            if(isset($_FILES['par_logo']) && $_FILES['par_logo']['name']!=""){
        		$errors = array();
        		$file_name = $_FILES['par_logo']['name'];
        		$file_size = $_FILES['par_logo']['size'];
        		$file_tmp = $_FILES['par_logo']['tmp_name'];
        		$file_type = $_FILES['par_logo']['type'];
        		$tmp = explode('.', $_FILES['par_logo']['name']);
        		$file_ext = end($tmp);
        		$extensions = array("jpeg", "JPG", "png","jpg");
        		
        		if (in_array($file_ext, $extensions) === false) {
        			array_push($errors,'extension not allowed.');
                        $data = array("status" => "image_error", "message" => "image extension not allowed");
                        echo json_encode($data);
        			}
        
        		else if ($file_size > 2097152) {
        			array_push($errors,'File size must be excately 2 MB');
                        $data = array("status" => "image_error", "message" => "Image size must be atmost 2MB");
                        echo json_encode($data);
        			}
        		if (empty($errors) == true) {
        			move_uploaded_file($file_tmp, "../../img/partners/" . $file_name);
        			$img = "/img/partners/" . $file_name;
    		
                    $stmt = $this->connect->prepare("INSERT INTO tbl_partner(par_full_name,par_short_name,par_logo,par_city,par_province,par_country,par_yor,par_mou_ref) 
                          	VALUES('".$par_full_name."','".$par_short_name."','".$img."','".$par_city."','".$par_province."','".$par_country."','".$par_yor."','".$par_mou_ref."')");
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
                else{
                    $stmt = $this->connect->prepare("INSERT INTO tbl_partner(par_full_name,par_short_name,par_city,par_province,par_country,par_yor,par_mou_ref) 
                          	VALUES('".$par_full_name."','".$par_short_name."','".$par_city."','".$par_province."','".$par_country."','".$par_yor."','".$par_mou_ref."')");
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

	}
	
    function view_partner(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_partner WHERE par_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
            }

 
        function update_partner(){
            $id=$_POST['pr_id'];
            $par_full_name=$_POST['e_par_full_name'];
            $par_short_name=$_POST['e_par_short_name'];
            $par_country=$_POST['e_par_country'];
            $par_province=$_POST['e_par_province'];
            $par_city=$_POST['e_par_city'];
            $par_mou_ref=$_POST['e_par_mou_ref'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_partner WHERE par_full_name='".$par_full_name."' AND par_country='".$par_country."' AND par_mou_ref='".$par_mou_ref."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Data already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                        $stmt = $this->connect->prepare("UPDATE tbl_partner SET 
                                                                                par_full_name='".$par_full_name."',
                                                                                par_short_name='".$par_short_name."',
                                                                                par_city='".$par_city."',
                                                                                par_province='".$par_province."',
                                                                                par_country='".$par_country."',
                                                                                par_yor='".$par_yor."',
                                                                                par_mou_ref='".$par_mou_ref."'
                                                                                WHERE par_id='".$id."'"); 
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

    	function delete_partner(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_partner WHERE par_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['par_active']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_partner SET par_active=2 WHERE par_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_partner SET par_active=1 WHERE par_id='".$id."'");  
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
            
    function load_provinces(){
    	$type=$_POST['type'];
        $stmt = $this->connect->prepare("SELECT * FROM provinces");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
        
        
}	
		$partner=new Partner();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $partner->save_partner();
		        break;
		    case 'update':
		        $partner->update_partner();
		        break;
		    case 'delete':
		        $partner->delete_partner();
		        break;
		    case 'view':
		        $partner->view_partner();
		        break;
		    case 'load_provinces':
		        $partner->load_provinces();
		        break;
		}

	?>

