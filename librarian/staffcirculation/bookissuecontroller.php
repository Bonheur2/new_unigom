
<?php

include ('../../meet/con.php');
$connection=$conn;
class Borrow{
    
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
     function borrowdetails(){
        $studentid=$_POST['studentid'];
        $copyid=$_POST['copyid'];
        $retundate=$_POST['retundate'];
        $borrow_status="Staff";
        $todaynow = date('Y-m-d');
        $today = new DateTime();
        $futureDate = $today->add(new DateInterval("P{$retundate}D"));
        $futureretuningDate = $futureDate->format('Y-m-d');


        
            $stmt = $this->connect->prepare("SELECT * FROM book_copies WHERE id='".$copyid."' AND (status='Borrowd' OR status='Staff')");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Book already Borrowed!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                 $stmt = $this->connect->prepare("INSERT INTO borrowdetails(borrow_id,book_id,date_return,issue_date) 
                          	VALUES('".$studentid."','".$copyid."','".$futureretuningDate."','".$todaynow."')");
                $stmt2 = $this->connect->prepare(" UPDATE book_copies
                                                    SET status ='Borrowd'
                                                    WHERE id = '".$copyid."';");
                
               $stmt2->execute();
                          	
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
        
        function search(){
        	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_staff WHERE staff_id like '".$input."%' OR staff_family_name like '".$input."%' OR staff_first_name like '".$input."%'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function load_student_info(){
        	$stu=$_POST['stu'];
            $stmt = $this->connect->prepare("SELECT *
            FROM tbl_staff
            WHERE staff_id='".$stu."'");
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
                                    tbl_register_program_ug.reg_no = '".$stu."' ORDER BY tbl_register_program_ug.reg_active ASC LIMIT 1");
            $sql->execute();
            $data2=$sql->fetch();
            
    $stmt2 = $this->connect->prepare("SELECT tbl_staff_borrowdetails.*,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM tbl_staff_borrowdetails 
    JOIN tbl_staff ON tbl_staff_borrowdetails.borrow_id =tbl_staff.id 
    JOIN book_copies ON tbl_staff_borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id WHERE (tbl_staff_borrowdetails.borrow_status ='pending'OR tbl_staff_borrowdetails.borrow_status ='losted') AND tbl_staff.staff_id='".$stu."' ");
            $stmt2->execute();
            $data3=$stmt2->fetchAll();
            
            $info=array();
            array_push($info,$data,$data2,$data3);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
            
            
            
        }
        
        function load_rtn_info(){
        $stu=$_POST['stu'];
        	$bkid=$_POST['bkid'];
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
            WHERE tbl_staff_borrowdetails.borrow_status ='returned' AND tbl_staff.staff_id='".$stu."'AND book_copies.book_code_number='".$bkid."' ");
            $stmt->execute();
            $data=$stmt->fetch();
            
            $info=array();
            array_push($info,$data);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
           
        }
        
         function load_lstd_info(){
        	$stu=$_POST['stu'];
        	$bkid=$_POST['bkid'];
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
            WHERE tbl_staff_borrowdetails.borrow_status ='losted' AND tbl_staff.staff_id='".$stu."'AND book_copies.book_code_number='".$bkid."' ");
            $stmt->execute();
            $data=$stmt->fetch();
            
           
            
            
            $info=array();
            array_push($info,$data);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
            
            
            
        }
        
         function load_dry_info(){
        $stu=$_POST['stu'];
        	$bkid=$_POST['bkid'];
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
            WHERE tbl_staff.staff_id='".$stu."'AND book_copies.book_code_number='".$bkid."' ");
            $stmt->execute();
            $data=$stmt->fetch();
            
            $info=array();
            array_push($info,$data);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
           
        }
        
        
        
  function searchbook(){
        	$input=$_POST['book'];
            $stmt = $this->connect->prepare("SELECT * FROM `book_copies` WHERE status='Available' AND book_code_number LIKE '" . $input . "%'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        } 
        
function loadcopyinfo() {
    $bk = $_POST['bk'];
    $stmt = $this->connect->prepare("SELECT book_copies.*, books.* FROM book_copies
        JOIN books ON book_copies.book_id = books.book_id
        WHERE book_copies.id = :bk");
    $stmt->bindParam(':bk', $bk);
    $stmt->execute();
    $data = $stmt->fetch();

    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
}


}
	
		$book=new Borrow();
	    $action = $_POST['action'];
		switch($action){
		    case 'borrowdetails':
		        $book->borrowdetails();
		        break;
		    case 'search':
		        $book->search();
		        break;
		    case 'load_info':
		        $book->load_student_info();
		        break;
		    case 'load_rtn_info':
		        $book->load_rtn_info();
		        break;
		   case 'loadcopy':
		        $book->searchbook();
		        break;
		   case 'copyinfo':
		        $book->loadcopyinfo();
		        break;
		  case 'load_lstd_info':
		        $book->load_lstd_info();
		        break;
		  case 'load_dry_info':
		        $book->load_dry_info();
		        break;
		}
		
		

	?>

