
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
        $todaynow = date('Y-m-d');
            $stmt = $this->connect->prepare("SELECT * FROM borrowdetails WHERE borrow_details_id='". $browid."'AND borrow_status='".$borrow_status."'");
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
                $stmt2 = $this->connect->prepare("UPDATE borrowdetails
                                                    SET borrow_status ='returned',actual_returned='".$todaynow."'
                                                    WHERE borrow_details_id = '".$browid."';");
                
              $stmt2->execute();
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Book returned successfully!");
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
            $stmt = $this->connect->prepare("SELECT * FROM borrowdetails WHERE borrow_details_id='". $browid."'AND borrow_status='".$borrow_status."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Book already reported!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("UPDATE book_copies
                                                    SET status ='lost'
                                                    WHERE book_code_number = '".$copyid."';");
                $stmt2 = $this->connect->prepare("UPDATE borrowdetails
                                                    SET borrow_status ='lost',issue_date='".$todaynow."'
                                                    WHERE borrow_details_id = '".$browid."'");
                
             
                          	
                        if($stmt->execute() &&  $stmt2->execute()){
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
    
     function remove_fine(){
        $browid=$_POST['e_id2'];
        $copyid=$_POST['c_id2'];
        $borrow_status="returned";
        $todaynow = date('Y-m-d');
             
              $stmt2 = $this->connect->prepare("UPDATE borrowdetails
                                                    SET fine='0'
                                                    WHERE borrow_details_id = '".$browid."' ");
                
              $stmt2->execute();
                          	
                        if ($stmt2->execute()) {
    $data = array("status" => "200", "message" => "Fine removed successfully!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
} else {
    $data = array("status" => "500", "message" => "Failed to remove fine!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
}      
                     
 }
// upload document
  function uplaod_document(){
    $borrWdId=$_POST['book_borrowd_id'];
    $amunt_clreared=$_POST['amunt_clreared'];
    $file = $_FILES['image_file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $destination = 'fines/';
    $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    
    $file_path = $destination . $file_name_without_extension . '.' . $file_extension;
    $file_to_save=$file_name_without_extension . '.' . $file_extension;
    if(move_uploaded_file($file_tmp, $file_path)){
     $stmt_sum = $this->connect->prepare("SELECT SUM(amount) AS last_paid FROM borrowdetails WHERE borrow_details_id = '".$borrWdId."' ");
     $stmt_sum->execute();
     $data_sum=$stmt_sum->fetch();
     $last_paid=$data_sum['last_paid'];
     $paid=$last_paid+$amunt_clreared;
     $stmt2 = $this->connect->prepare("UPDATE borrowdetails SET receipt_image='".$file_name."',cleared=1,amount='".$paid."' WHERE borrow_details_id = '".$borrWdId."' ");   
    if ($stmt2->execute()) {
    $data = array("status" => "200", "message" => "Fine cleared successfully!","fine"=>$paid);
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
} else {
    $data = array("status" => "500", "message" => "Failed to remove fine!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
} 
    }
    else{
     $data = array("status" => "500", "message" => "Failed to remove fine!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;    
    }
    
 }
 function search(){
	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT borrowdetails.*,
    tbl_admission.*,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM borrowdetails 
    JOIN tbl_admission ON borrowdetails.borrow_id =tbl_admission.reg_no 
    JOIN book_copies ON borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id WHERE (borrowdetails.borrow_status ='pending' OR borrowdetails.borrow_status ='fine added') AND book_copies.book_code_number like '".$input."%' 
    OR book_copies.bar_code_copy like '".$input."%' AND borrowdetails.borrow_status !='returned' ");
    $stmt->execute();
    $data1 = $stmt->fetchAll();
    $stmt2 = $this->connect->prepare("SELECT borrowdetails.*,
    tbl_staff.*,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM borrowdetails 
    JOIN tbl_staff ON borrowdetails.borrow_id =tbl_staff.id 
    JOIN book_copies ON borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id WHERE (borrowdetails.borrow_status ='pending' OR borrowdetails.borrow_status ='fine added') AND book_copies.book_code_number like '".$input."%'
     OR book_copies.bar_code_copy like '".$input."%'  AND borrowdetails.borrow_status !='returned' ");
            $stmt2->execute();
           $data2 = $stmt2->fetchAll();

        $data = array_merge($data1, $data2);
        
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
           
        }
        function load_student_info(){
        	$stu=$_POST['stu'];
            $stmt = $this->connect->prepare("SELECT 
            borrowdetails.*, 
            tbl_admission.*,
            book_copies.book_code_number,
            book_copies.qr_code_file,
            books.*
            FROM borrowdetails 
            JOIN tbl_admission ON borrowdetails.borrow_id =tbl_admission.reg_no 
            JOIN book_copies ON borrowdetails.book_id = book_copies.id 
            JOIN books ON book_copies.book_id=books.book_id 
            WHERE borrowdetails.borrow_details_id='".$stu."'");
            $stmt->execute();
            $data=$stmt->fetch();
            
            $stmt4 = $this->connect->prepare("SELECT 
            borrowdetails.*, 
            tbl_staff.*,
            book_copies.book_code_number,
            book_copies.qr_code_file,
            books.*
            FROM borrowdetails 
            JOIN tbl_staff ON borrowdetails.borrow_id =tbl_staff.id 
            JOIN book_copies ON borrowdetails.book_id = book_copies.id 
            JOIN books ON book_copies.book_id=books.book_id 
            WHERE borrowdetails.borrow_details_id='".$stu."'AND borrowdetails.borrow_status ='pending'");
            $stmt4->execute();
            $data4=$stmt4->fetch();
            
            $date1 = strtotime(date('Y-m-d'));
            $date2 = strtotime($data['date_return']);
            $days = floor(($date1  - $date2) / (60 * 60 * 24));
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
            $sql2=$this->connect->prepare("SELECT fine FROM books_fine WHERE status=1");
            $sql2->execute();
            $fine=$sql2->fetch();
            $fineAmount=$fine['fine'];
            $fines_formula=$days*$fineAmount;
            $fine_paid=$data['amount'];
            if($fine_paid>$fines_formula || $fine_paid==$fines_formula){
              $fines=0;  
            }
            else{
             $fines=intval($fines_formula)-intval($fine_paid);
            }
            $datafine=array("fines"=>$fines);
            $info=array();
            array_push($info,$data,$data2,$datafine,$data4);
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
		        
		    case 'fine':
		        $book->calculate_fine();
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
		   case 'clear':
		        $book->remove_fine();
		        break; 
		  case 'uplaod_receipt':
		        $book->uplaod_document();
		        break; 
		        
		}

	?>

