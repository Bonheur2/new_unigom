<?php

include ('../../meet/con.php');
include 'phpqrcode/qrlib.php'; // Include the PHP QR Code library
$connection=$conn;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Faculty{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_faculty() {
    $pr_type = $_POST['ft_type'];
    $full_name = ucwords($_POST['ft_f_name']); 
    $short_name = ucwords($_POST['ft_s_name']);
    $f_code = $_POST['ft_code'];

    // Check if data already exists
    $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE fac_full_name = :full_name AND prg_type = :pr_type");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':pr_type', $pr_type);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $data = array("status" => "401", "message" => "Data already exists!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        // Insert the data into the database
        $stmt = $this->connect->prepare("INSERT INTO tbl_faculty(prg_type, fac_full_name, fac_short_name, code) 
                                         VALUES(:pr_type, :full_name, :short_name, :f_code)");
        $stmt->bindParam(':pr_type', $pr_type);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':short_name', $short_name);
        $stmt->bindParam(':f_code', $f_code);

        if ($stmt->execute()) {
            $data = array("status" => "200", "message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            $data = array("status" => "500", "message" => "Failed to save data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
    }
}

function view_faculty(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT tbl_faculty.*, tbl_program_type.campus_id 
                                         FROM tbl_faculty 
                                         INNER JOIN tbl_program_type ON tbl_faculty.prg_type = tbl_program_type.prg_type_id
                                         WHERE fac_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }        
    
    function update_faculty(){
        $id = $_POST['pr_id'];
        $prg_type = $_POST['ft_type'];
        $full_name = $_POST['ft_f_name'];
        $short_name = $_POST['ft_s_name'];
        $fac_code=$_POST['ft_s_codee'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE fac_full_name='".$full_name."' AND prg_type='".$prg_type."' AND code='".$fac_code."'");
        $stmt->execute();
        // if($stmt->rowCount()>0){
        //     $data = array("status"=>"401","message" => "Data already exists!");
        //     $jsonData = json_encode($data);
        //     header('Content-Type: application/json');
        //     echo $jsonData;   
        // } else {
            
        $stmt = $this->connect->prepare("UPDATE tbl_faculty set fac_full_name='".$full_name."',fac_short_name='".$short_name."',prg_type='".$prg_type."',code='".$fac_code."' WHERE fac_id='".$id."'");
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
        // }
    }

    	function delete_faculty(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE fac_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_faculty SET status=2 WHERE fac_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_faculty SET status=1 WHERE fac_id='".$id."'");  
            }
            
            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "data removed successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "Failed to removed data");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
    function load_departments(){
    	$fac=$_POST['fac'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE fac_id='".$fac."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function load_departments1(){
    	$fac=$_POST['fac'];
    	$prg_type=$_POST['program'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE fac_id='".$fac."' AND prg_type='".$prg_type."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function load_specs(){
    	$fac=$_POST['fac'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE fac_id='".$fac."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function get_prg_type()
    {
        $camp_id=$_POST['camp_id'];
        $query=$this->connect->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$camp_id."' AND status=1");
        $query->execute();
        $data=$query->fetchAll();
        echo json_encode($data);
    }
    function get_spec(){
        $dep_id=$_POST['dep_id'];
        $stmt=$this->connect->prepare("SELECT * FROM  tbl_specialization WHERE dept_id ='".$dep_id."'");
        $stmt->execute();
        $result=$stmt->fetchAll();
        echo json_encode($result);
    }
    

function register_book() {
    $academic = $_POST['acad_cycle_id'];
    $response = [];

    if (isset($_FILES['graduation_book']) && $_FILES['graduation_book']['error'] == 0) {
        $upload_dir = "uploads/books/";
        $qr_dir = "uploads/qrcodes/";

        // Sanitize the original file name
        $file_name = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES['graduation_book']['name']);
        $file_tmp = $_FILES['graduation_book']['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_path = $upload_dir . $file_name;

        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($_FILES['graduation_book']['type'], $allowed_types)) {
            echo json_encode(['status' => 401, 'message' => 'Invalid file type. Only PDF or DOCX allowed.']);
            return;
        }

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Generate File URL
            $file_url = "https://misnjala.edu.sl/files/Faculties/uploads/books/" . $file_name;

            // Generate QR Code with the same file name
            $qr_code_path = $qr_dir . $file_name . ".png";
            QRcode::png($file_url, $qr_code_path, QR_ECLEVEL_L, 5, 2);

            // Insert file and QR code path into database
            $stmt = $this->connect->prepare("INSERT INTO tbl_books (acad_cycle_id, file_name, file_path, qr_code) VALUES (?, ?, ?, ?)");
            $stmt->execute([$academic, $file_name, $file_path, $qr_code_path]);

            if ($stmt) {
                echo json_encode(['status' => 200, 'message' => 'Book uploaded successfully!', 'qr_code' => $qr_code_path]);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Database error.']);
            }
        } else {
            echo json_encode(['status' => 500, 'message' => 'File upload failed.']);
        }
    } else {
        echo json_encode(['status' => 401, 'message' => 'No file uploaded.']);
    }
}



        
}	
		$faculty=new Faculty();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $faculty->save_faculty();
		        break;
		    case 'update':
		        $faculty->update_faculty();
		        break;
		    case 'delete':
		        $faculty->delete_faculty();
		        break;
		    case 'view':
		        $faculty->view_faculty();
		        break;
		    case 'load_departments':
		        $faculty->load_departments();
		        break;
		    case 'load_departments1':
		        $faculty->load_departments1();
		        break;      
		    case 'load_specs':
		        $faculty->load_specs();
		        break;
		   case 'get_program_type':
		       $faculty->get_prg_type();
		       break;
		  case 'load_spec':
		      $faculty->get_spec();
		       break;
		 case 'register_book':
		     $faculty->register_book();
		     break;
		}

	?>

