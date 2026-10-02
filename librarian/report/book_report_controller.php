

<?php

include ('../../meet/con.php');
$connection=$conn;




class Books{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }

function borrowed(){
        	$from=$_POST['from'];
        	$to=$_POST['to'];
            $stmt = $this->connect->prepare("SELECT borrowdetails.borrow_status, 
    borrowdetails.date_return, 
    borrowdetails.issue_date,
    tbl_admission.fname,
    tbl_admission.phone,
    tbl_admission.reg_no,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM borrowdetails 
    JOIN tbl_admission ON borrowdetails.borrow_id =tbl_admission.adm_id 
    JOIN book_copies ON borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id
WHERE borrowdetails.issue_date BETWEEN '$from' AND '$to'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
function returned(){
        	$from=$_POST['from'];
        	$to=$_POST['to'];
            $stmt = $this->connect->prepare("SELECT borrowdetails.borrow_status, 
    borrowdetails.date_return, 
    borrowdetails.issue_date,
    borrowdetails.fine,
    tbl_admission.fname,
    tbl_admission.phone,
    tbl_admission.reg_no,
    book_copies.book_code_number,
    books.title,
    books.book_code
FROM borrowdetails 
JOIN tbl_admission ON borrowdetails.borrow_id = tbl_admission.adm_id 
JOIN book_copies ON borrowdetails.book_id = book_copies.id 
JOIN books ON book_copies.book_id = books.book_id
WHERE borrowdetails.issue_date BETWEEN '$from' AND '$to' 
    AND fine > 0.00 ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
 function view_copy(){

    $stmt1 = $this->connect->prepare("SELECT COUNT(*) as total_copies FROM book_copies WHERE book_id=:id");
    $stmt1->bindParam(':id', $id);
    $stmt1->execute();
    $totalCopies = $stmt1->fetch();

    $stmt2 = $this->connect->prepare("SELECT COUNT(*) as lost_copies FROM book_copies WHERE book_id=:id AND status='losted'");
    $stmt2->bindParam(':id', $id);
    $stmt2->execute();
    $lostCopies = $stmt2->fetch();

    $stmt3 = $this->connect->prepare("SELECT COUNT(*) as borrowed_copies FROM book_copies WHERE book_id=:id AND (status='Borrowd' OR status='Staff')");
    $stmt3->bindParam(':id', $id);
    $stmt3->execute();
    $borrowedCopies = $stmt3->fetch();

    $stmt4 = $this->connect->prepare("SELECT COUNT(*) as available_copies FROM book_copies WHERE book_id=:id AND status='Available'");
    $stmt4->bindParam(':id', $id);
    $stmt4->execute();
    $availableCopies = $stmt4->fetch();

   
    $info=array();
    array_push($info,$totalCopies,$lostCopies,$borrowedCopies,$availableCopies);
    $jsonData = json_encode($info);
    header('Content-Type: application/json');
    echo $jsonData; 
}
                
function delayed(){
        	$from=$_POST['from'];
        	$to=$_POST['to'];
            $stmt = $this->connect->prepare("SELECT borrowdetails.borrow_status, 
    borrowdetails.date_return, 
    borrowdetails.issue_date,
    borrowdetails.fine,
    tbl_admission.fname,
    tbl_admission.phone,
    tbl_admission.reg_no,
    book_copies.book_code_number,
    books.title,
    books.book_code
FROM borrowdetails 
JOIN tbl_admission ON borrowdetails.borrow_id = tbl_admission.adm_id 
JOIN book_copies ON borrowdetails.book_id = book_copies.id 
JOIN books ON book_copies.book_id = books.book_id
WHERE borrowdetails.issue_date BETWEEN '$from' AND '$to' 
    AND borrowdetails.borrow_status = 'losted'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
}
	
		$book=new Books();
	    $action = $_POST['action'];
		switch($action){
		    
		   case 'borrowedbookreport':
		        $book->borrowed();   
		        break;
		   case 'retun':
		        $book->returned();   
		        break;
		   case 'deryd':
		        $book->delayed();   
		        break;
		  case 'view':
		        $book->view_copy();
		        break;
		   
		}
	?>



	
	



