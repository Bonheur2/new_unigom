
<?php

include ('../../meet/con.php');
$connection=$conn;

class Borrow{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
     function return_book(){
        $browid=$_POST['e_id'];
        $copyid=$_POST['c_id'];
        $borrow_status="returned";
            $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_borrowdetails WHERE borrow_details_id='". $browid."'AND borrow_status='".$borrow_status."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Book already Returned!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("UPDATE book_copies
                                                    SET status ='Available'
                                                    WHERE book_code_number = '".$copyid."';");
                $stmt2 = $this->connect->prepare("UPDATE tbl_staff_borrowdetails
                                                    SET borrow_status ='returned'
                                                    WHERE borrow_details_id = '".$browid."';");
                
              $stmt2->execute();
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Book returnded successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to return book!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }       
                     }
             }
             
    function lost_book(){
        $browid=$_POST['e_id1'];
        $copyid=$_POST['c_id1'];
        $borrow_status="lost";
        $todaynow = date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_borrowdetails WHERE borrow_details_id='". $browid."'AND borrow_status='".$borrow_status."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Book already reported!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("UPDATE book_copies
                                                    SET status ='losted'
                                                    WHERE book_code_number = '".$copyid."';");
                $stmt2 = $this->connect->prepare("UPDATE tbl_staff_borrowdetails
                                                    SET borrow_status ='losted',issue_date='".$todaynow."'
                                                    WHERE borrow_details_id = '".$browid."';");
                
              $stmt2->execute();
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Book reported successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to report book!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }       
                     }
             }

 function search(){
        	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT tbl_staff_borrowdetails.*,
    tbl_staff.*,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM tbl_staff_borrowdetails 
    JOIN tbl_staff ON tbl_staff_borrowdetails.borrow_id =tbl_staff.id 
    JOIN book_copies ON tbl_staff_borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id WHERE tbl_staff_borrowdetails.borrow_status ='pending' AND (book_copies.book_code_number like '".$input."%')");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function load_student_info(){
        	$stu=$_POST['stu'];
            $stmt = $this->connect->prepare("SELECT 
            tbl_staff_borrowdetails.*, 
            tbl_staff.*,
            book_copies.book_code_number,
            book_copies.qr_code_file,
            books.title,
            books.book_code
            FROM tbl_staff_borrowdetails 
            JOIN tbl_staff ON tbl_staff_borrowdetails.borrow_id =tbl_staff.id 
            JOIN book_copies ON tbl_staff_borrowdetails.book_id = book_copies.id 
            JOIN books ON book_copies.book_id=books.book_id 
            WHERE tbl_staff_borrowdetails.borrow_details_id='".$stu."'AND tbl_staff_borrowdetails.borrow_status ='pending'");
            $stmt->execute();
            $data=$stmt->fetch();
            
            $sql=$this->connect->prepare("SELECT
                                    tbl_register_program_ug.*,
                                    tbl_program_type.prg_type_full_name,
                                    tbl_faculty.fac_full_name,
                                    tbl_department.dept_full_name,
                                    tbl_specialization.splz_full_name,
                                    tbl_level.level_full_name,
                                    tbl_program_mode.prg_mode_full_name
                                    FROM tbl_register_program_ug
                                    INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                    INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                    INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                    INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
                                        WHERE
                                    tbl_register_program_ug.reg_no = '".$stu."' ORDER BY tbl_register_program_ug.reg_active ASC");
            $sql->execute();
            $data2=$sql->fetchAll();
            $info=array();
            array_push($info,$data,$data2);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
        }


}
	
		$book=new Borrow();
	    $action = $_POST['action'];
		switch($action){
		    case 'borrowdetails':
		        $book->return_book();
		        break;
		    case 'search':
		        $book->search();
		        break;
		    case 'load_info':
		        $book->load_student_info(); 
		        break;
		    case 'lostbook':
		        $book->lost_book();
		        break;   
		}

	?>

