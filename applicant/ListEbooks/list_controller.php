<?php 
include ('../../meet/con.php');

$action = $_POST['action'];

if ($action == "viewEdit") {
    $book_id = $_POST['value'];

    $stmt1 = $conn->prepare("SELECT books_online.*,tbl_book_program.dept_full_name
    FROM books_online INNER JOIN tbl_book_program ON books_online.department=tbl_book_program.dept_id WHERE books_online.book_id = ? AND books_online.status = 1 AND books_online.type = 1 ");
    $stmt1->execute([$book_id]);
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT * FROM tbl_book_program WHERE status = 1");
    $stmt2->execute();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
      $rows=array();
     array_push($rows,$data1, $data2);
    echo json_encode($rows);
}
else if($action=="update_data")
{
    $book_id=$_POST['book_id'];
    $bk_title=$_POST['bk_title'];
    $sec_author=$_POST['sec_author'];
    $isbn=$_POST['isbn'];
    $publisher=$_POST['publisher'];
    $book_dep=$_POST['book_dep'];
    $author=$_POST['author'];
    $stmt3=$conn->prepare("UPDATE books_online SET title='".$bk_title."',department='".$book_dep."',isbn='".$isbn."',
    	author='".$author."',sec_author='".$sec_author."',publisher='".$publisher."' WHERE book_id='".$book_id."'
    ");
    if($stmt3->execute()){
         $data = array("status" => "200", "message" => "E-book Updated Successfully !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
    }
    else{
       $data = array("status" => "500", "message" => "Action Failed !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
    }
}
else if($action=="publish_book"){
    $bookId=$_POST['bookId'];
    $stmt5=$conn->prepare("UPDATE books_online SET public=1 WHERE book_id='".$bookId."'");
    if($stmt5->execute()){
          $data = array("status" => "200", "message" => "E-book Published Successfully !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
    }
    else{
       $data = array("status" => "500", "message" => "Action Failed !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
    } 
  
}

else if($action=="delete_book"){
    
  $bookId = $_POST['bookId'];
$stmt6 = $conn->prepare("SELECT * FROM books_online WHERE book_id = :bookId");
$stmt6->bindParam(':bookId', $bookId);
$stmt6->execute();
$rows = $stmt6->fetch();
$book_file = $rows['book_file'];

$file_path = "../onlinebooks/files/" . $book_file;

if (file_exists($file_path)) {
    unlink($file_path);
} 
$stmt7=$conn->prepare("DELETE FROM books_online WHERE book_id='".$bookId."'");
if($stmt7->execute()){
   $data = array("status" => "200", "message" => "E-book Published Successfully !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
    }
    else{
       $data = array("status" => "500", "message" => "Action Failed !!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
    }   


}

?>
