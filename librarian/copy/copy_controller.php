
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ('../../meet/con.php');
$connection=$conn;
class Books{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    
          function save_book(){
    $b_dep = $_POST['b_dep'];
    $b_title = $_POST['b_title'];
    $bar_code=$_POST['bar_code'];
    $book_class=$_POST['class_book'];
    $sql = $this->connect->prepare("SELECT book_code FROM books ORDER BY book_id DESC LIMIT 1");
    $sql->execute();
    $row = $sql->fetch();
    $last_code = $row['book_code'];
    $b_code = str_pad(intval($last_code+1), 4, '0', STR_PAD_LEFT);
    
    $reg_date = date('Y-m-d');
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $secondpublisher = $_POST['secondpublisher'];
    $isbn = $_POST['isbn'];
    $secondauthor = $_POST['secondauthor'];
    $section = $_POST['section'];
    $material_type = $_POST['material_type'];
    $lease = $_POST['lease'];
    $status = $_POST['status'];
    $associatedURL = $_POST['associatedURL'];
    $orginal_language = $_POST['orginal_language'];
    $language_publication = $_POST['language_publication'];
    $version = $_POST['version'];
    $date_of_publication = $_POST['date_of_publication'];
    $Summary = $_POST['Summary'];
    $note_on_contents = $_POST['note_on_contents'];
    $other_info = $_POST['other_info'];
    $price = $_POST['price'];
    $b_location=$_POST['b_locations'];
    
    $authorD=$_POST['authorD'];
    $secondauthorD=$_POST['secondauthorD'];
    $b_locationsD=$_POST['b_locationsD'];
    $publisherD=$_POST['publisherD'];
    $date_of_publicationD=$_POST['date_of_publicationD'];
    $language_publicationD=$_POST['language_publicationD'];
    $sectionD=$_POST['sectionD'];
    $filepath = 'uploads/646dc5b02e9e2.png'; 
    // $filepathD = 'uploads/dcover.png';
   
      $img_name = $_FILES['upload_image'];
    $image_name = $img_name['name'];
    // $base64image = $_POST['image'];
   
    $imageBefore = 'uploads/' . $image_name;
    $image_name_time = time();
    $full_name_image = 'uploads/' . $image_name_time . $image_name; 
    rename($imageBefore, $full_name_image);
    if(empty($authorD)){
    

        $stmt = $this->connect->prepare("SELECT * FROM books WHERE (title='".$b_title."' AND isbn='".$isbn."') OR bar_code='".$bar_code."'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = array("status" => "401", "message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            if (empty($image_name)) {
    
    
            $stmt = $this->connect->prepare("INSERT INTO books(title,publication_date, department, book_code, 
            author,publisher,isbn,location,image,price,other_info,note_on_contents,Summary,date_of_publication,version,
            language_publication,orginal_language,associatedURL,status,lease,material_type,section,secondauthor,secondpublisher,type,bar_code,book_class) 
                                             VALUES('".$b_title."','".$date_of_publication."', '".$b_dep."', '".$b_code."', '".$author."',
                                             '".$publisher."', '".$isbn."','".$b_location."',
                                             '".$filepath."',
                                             '".$price."','".$other_info."','".$note_on_contents."','".$Summary."','".$date_of_publication."','".$version."',
                                             '".$language_publication."','".$orginal_language."','".$associatedURL."','".$status."','".$lease."','".$material_type."','".$section."','".$secondauthor."','".$secondpublisher."',1,'".$bar_code."','".$book_class."')");
}
else{
   $stmt = $this->connect->prepare("INSERT INTO books(title,publication_date, department, book_code, 
            author,publisher,isbn,location,image,price,other_info,note_on_contents,Summary,date_of_publication,version,
            language_publication,orginal_language,associatedURL,status,lease,material_type,section,secondauthor,secondpublisher,type,bar_code,book_class) 
                                             VALUES('".$b_title."','".$date_of_publicationD."', '".$b_dep."', '".$b_code."', '".$author."',
                                             '".$publisher."', '".$isbn."','".$b_location."',
                                             '".$full_name_image."',
                                             '".$price."','".$other_info."','".$note_on_contents."','".$Summary."','".$date_of_publication."','".$version."',
                                             '".$language_publication."','".$orginal_language."','".$associatedURL."','".$status."','".$lease."','".$material_type."','".$section."','".$secondauthor."','".$secondpublisher."',1,'".$bar_code."','".$book_class."')");  
}
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
  
    else{
     $stmt = $this->connect->prepare("INSERT INTO books(title,publication_date, department, book_code, 
            author,publisher,location,image,date_of_publication,
            language_publication,section,secondauthor,type) 
                                             VALUES('".$b_title."','".$date_of_publicationD."', '".$b_dep."', '".$b_code."', '".$authorD."',
                                             '".$publisherD."','".$b_locationsD."',
                                             '".$filepath."',
                                            '".$date_of_publicationD."',
                                             '".$language_publicationD."','".$sectionD."','".$secondauthorD."',2)");      
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
 function save_location(){
        $location_name=$_POST['location_name'];
        // $location_Code = 'BKSTK'. rand(100, 999).$b_dep;
        $book_code=$_POST['Building'];
            $stmt = $this->connect->prepare("SELECT * FROM books_location WHERE location_Code='".$book_code."' AND location_name='".$location_name."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Block already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("INSERT INTO books_location(location_name,location_Code) 
                          	VALUES('".$location_name."','".$book_code."')");
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "block saved successfully!");
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
function update_book(){
    $id = $_POST['e_id'];
     
    $b_dep = $_POST['b_dep1'];
    $b_title = $_POST['b_title1'];
    $author = $_POST['author1'];
    $publisher = $_POST['publisher1'];
    $isbn = $_POST['isbn1'];
    $b_location = $_POST['b_location1'];
    $secondpublisher = $_POST['secondpublisher'];
    $section = $_POST['bsection'];
    
    $lease = $_POST['blease'];
    $status = $_POST['bstatus'];
    $associatedURL = $_POST['bassociatedURL'];
    $orginal_language = $_POST['borginal_language'];
    $language_publication = $_POST['blanguage_publication'];
    $version = $_POST['bversion'];
    $date_of_publication = $_POST['bdate_of_publication'];
    $Summary = $_POST['bSummary'];
    $note_on_contents = $_POST['bnote_on_contents'];
    $other_info = $_POST['bother_info'];
    $price = $_POST['bprice'];
    $secondauthor = $_POST['bsecondauthor'];
       
        $stmt = $this->connect->prepare("UPDATE `books` SET `title` = '$b_title', `department`='$b_dep',
        `author` = '$author', 
        `publisher` = '$publisher', 
        `isbn` = '$isbn', 
        `location` = '$b_location',
        `price` = ' $price', 
        `other_info` = '$other_info',
        `note_on_contents` = '$note_on_contents', 
        `Summary` = '$Summary', 
        `date_of_publication` = '$date_of_publication', 
        `version` = '$version', 
        `language_publication` = '$language_publication', 
        `orginal_language` = '$orginal_language', 
        `associatedURL` = '$associatedURL', 
        `status` = '$status', 
        `lease` = '$lease', 
        `secondauthor` = '$secondauthor',
        `secondpublisher` = '$secondpublisher',
        `section` = '$section' WHERE `book_id` = '".$id."'");
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
        
    }
        
function save_copy() {
    $b_id = $_POST['b_tp'];
    $barCode=$_POST['bar_code_copy'];
    $i = 1;
    $last_code2 = '000000';
    $b_location = $_POST['b_location'];
    $chec=$this->connect->prepare("SELECT * FROM book_copies WHERE 	bar_code_copy='".$barCode."'");
   $chec->execute();
   $row_chec=$chec->rowCount();
   
   $chec2=$this->connect->prepare("SELECT * FROM books WHERE bar_code='".$barCode."'");
   $chec2->execute();
   $row_chec2=$chec2->rowCount();
   if($row_chec>0 || $row_chec2>0){
     $response = array();
     $response["status"] = "400";
     $response["message"] = "Bar Code used";   
   }
   else{
      $sql = $this->connect->prepare("SELECT book_code_number FROM book_copies WHERE book_id='".$b_id."' ORDER BY id DESC LIMIT 1");
    $sql->execute();
    $row = $sql->fetch();
    
    $last_code = $row['book_code_number'];
    
    if (!empty($last_code)) {
        $sql = $this->connect->prepare("SELECT books.*, tbl_books_depart.* FROM books
        INNER JOIN tbl_books_depart ON books.department=tbl_books_depart.book_id WHERE books.book_id='".$b_id."'");
        $sql->execute();
        $row = $sql->fetch();
        $bookid = $row['book_id'];
        $title = $row['title'];
        $department = $row['book_dep_name'];
        
        $response = array();
        
        $last_numeric_part = intval(substr($last_code, 7));
    
            $b_code ='EACC'. $bookid . str_pad($last_numeric_part + $i, 4, '0', STR_PAD_LEFT);
            $b_status = 'Available';
            
            $qr_code_directory = 'QRcodes/';
            $qr_code_data = 'BK ID:'.$b_code.'  Book-name:'.$title.'  Department:'.$department .'Bar Code '.$barCode;
            $qr_code_file = $qr_code_directory . $b_code . '.png';
            QRcode::png($qr_code_data, $qr_code_file, 'L', 4, 2);

    
             $stmt = $this->connect->prepare("INSERT INTO book_copies(book_id, book_code_number, status, qr_code_file, block,bar_code_copy) 
                       VALUES('". $b_id."', '".$b_code."','".$b_status."', '".$qr_code_file."', '".$b_location."','".$barCode."')");
           
    
            if ($stmt->execute()) {
                $response["status"] = "200";
                $response["message"] = "copy of {$title} saved successfully!";
            } else {
                $response["status"] = "500";
                $response["message"] = "Failed to save data!";
            }
    
            $i++;
 
    } else {
        $sql = $this->connect->prepare("SELECT * FROM books WHERE book_id='".$b_id."'");
        $sql->execute();
        $row = $sql->fetch();
        $bookid = $row['book_id'];
        $title = $row['title'];
        $department = $row['department'];
        
        $response = array();
        
        $last_numeric_part = intval(substr($last_code2, 4));
    
            $b_code ='EACC'. $bookid . str_pad($last_numeric_part + $i, 4, '0', STR_PAD_LEFT);
            $b_status = 'Available';
            
            $qr_code_directory = 'QRcodes/';
            $qr_code_data = 'BK ID:'.$b_code.'  Book-name:'.$title.'  Department:'.$department;
            $qr_code_file = $qr_code_directory . $b_code . '.png';
            QRcode::png($qr_code_data, $qr_code_file, 'L', 4, 2);
            
            $stmt = $this->connect->prepare("INSERT INTO book_copies(book_id, book_code_number, status, qr_code_file, block,bar_code_copy) 
                       VALUES('". $b_id."', '".$b_code."','".$b_status."', '".$qr_code_file."', '".$b_location."','".$barCode."')");
           
    
            if ($stmt->execute()) {
                $response["status"] = "200";
                $response["message"] = "copies of {$title} saved successfully!";
            } else {
                $response["status"] = "500";
                $response["message"] = "Failed to save data!";
            }
    
   
      
    }  
   }
   
    
    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}



function save_author(){
        $author_name=$_POST['author_name'];
            $stmt = $this->connect->prepare("SELECT * FROM `authors` WHERE author_name='".$author_name."'");
            $stmt->execute();
            if($stmt->rowCount()>0){
                $data = array("status"=>"401","message" => "Autho already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            } else {
                $stmt = $this->connect->prepare("INSERT INTO authors(author_name) 
                          	VALUES('".$author_name."')");
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Author saved successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to save Aurhor!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }
                }
        }
function saveSecondAuthor(){
        $author_name2=$_POST['author_name_second'];
            $stmt = $this->connect->prepare("INSERT INTO second_authors(names) 
                          	VALUES('".$author_name2."')");
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Second Author saved successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to save Second Author!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }
        }
function view_copy(){
    $id = $_POST['id'];
    $stmt = $this->connect->prepare("SELECT  books.*,tbl_books_depart.book_dep_name
    FROM books INNER JOIN tbl_books_depart ON books.department=tbl_books_depart.book_id  WHERE books.book_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $bookData = $stmt->fetch();

    $stmt1 = $this->connect->prepare("SELECT COUNT(*) as total_copies FROM book_copies WHERE book_id='".$id."' AND status!='lost' ");
    $stmt1->execute();
    $totalCopies = $stmt1->fetch();

    $stmt2 = $this->connect->prepare("SELECT COUNT(book_code_number)  as lost_copies FROM book_copies WHERE book_id=:id AND status='lost'");
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
    
    $stmt5 = $this->connect->prepare("SELECT * FROM `book_copies` WHERE book_id='".$id."' AND status='Available' ");
    $stmt5->execute();
    $copies = $stmt5->fetchAll();

   
    $info=array();
    array_push($info,$bookData,$totalCopies,$lostCopies,$borrowedCopies,$availableCopies,$copies);
    $jsonData = json_encode($info);
    header('Content-Type: application/json');
    echo $jsonData; 
}



function load_book_info(){
    	$id=$_POST['stu'];
        $stmt = $this->connect->prepare("SELECT * FROM  books WHERE book_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
            }

 function search(){
        	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT books.*, tbl_books_depart.book_dep_name FROM books
        INNER JOIN tbl_books_depart ON books.department = tbl_books_depart.book_id
        INNER JOIN book_copies ON books.book_id =book_copies.book_id
        WHERE book_copies.bar_code_copy='".$input."' ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
      function search2(){
        	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT books.*, tbl_books_depart.book_dep_name FROM books
        INNER JOIN tbl_books_depart ON books.department = tbl_books_depart.book_id
        WHERE books.department='".$input."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
 function searchAutho(){
$input = $_POST['author'];
  $stmt = $this->connect->prepare("SELECT * FROM `authors` WHERE author_name like '".$input."%' ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
  
}
function searchAuthoSecondary2(){
$input = $_POST['sec_author'];
  $stmt = $this->connect->prepare("SELECT  * FROM second_authors WHERE names like '".$input."%' ");
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
		    case 'register':
		        $book->save_book();
		        break;
		    case 'bklocation':
		        $book->save_location();
		        break;
		  case 'save_author':
		        $book->save_author();
		        break;
		  case 'view':
		        $book->view_copy();
		        break;
		        
		   case 'savecopy':
		        $book->save_copy();   
		        break;
		        
		   case 'update':
		        $book->update_book();
		        break;
		   case 'search':
		        $book->search();
		        break;
		    case 'search2':
		        $book->search2();
		        break;
		  case 'searchAutho':
		        $book->searchAutho();
		        break;
		 case 'searchAuthoSecondary':
		     $book->searchAuthoSecondary2();
		     break;
		 case 'save_second_author':
		     $book->saveSecondAuthor();
		     break;
		    case 'load_info':
		        $book->load_book_info();
		        break;
		}
	?>



	
	

