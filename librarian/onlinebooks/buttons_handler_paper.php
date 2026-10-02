<?php 
include ('../../meet/con.php');

$action = $_POST['action'];

if ($action == "edit_book") {
    $book_id = $_POST['bookId'];

    $stmt1 = $conn->prepare("SELECT books_online.*,tbl_book_program.dept_full_name
    FROM books_online INNER JOIN tbl_book_program ON books_online.department=tbl_book_program.dept_id WHERE books_online.book_id = ? AND books_online.status = 1");
    $stmt1->execute([$book_id]);
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT * FROM tbl_book_program WHERE status = 1");
    $stmt2->execute();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
      $rows=array();
     array_push($rows,$data1, $data2);
    echo json_encode($rows);
}
else if($action=="submit_form"){
$book_id = $_POST['book_id2'];
$title = $_POST['title'];
$author1=$_POST['author1'];
$secondauthor1=$_POST['secondauthor1'];
$publisher=$_POST['publisher'];
$department = $_POST['dep_id'];

$stmt3 = $conn->prepare("UPDATE books_online SET title='".$title."', department='".$department."',
  	author='".$author1."',sec_author='".$secondauthor1."',publisher='".$publisher."'
  WHERE book_id='".$book_id."'");

 if($stmt3->execute()){
   echo "successfully updated!!";
 }
 else{
   echo "failed updated!!";  
 }
}
else if($action=="edit_book_search"){
     $book_id = $_POST['bookId'];

    $stmt1 = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name
    FROM books_online INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.book_id = ? AND books_online.status = 1 AND books_online.type = 2");
    $stmt1->execute([$book_id]);
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT * FROM tbl_department WHERE status = 1");
    $stmt2->execute();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
      $rows=array();
     array_push($rows,$data1, $data2);
    echo json_encode($rows);
}
else if($action=="submit_form_search"){
$book_id = $_POST['book_id2'];
$title = $_POST['title'];
$author3=$_POST['author3'];
$secondauthor3=$_POST['secondauthor3'];
$publisher3=$_POST['publisher3'];
$department = $_POST['dep_id'];

$stmt3 = $conn->prepare("UPDATE books_online SET title='".$title."',department='".$department."',
author='".$author3."',sec_author='".$secondauthor3."',publisher='".$secondauthor3."'
WHERE book_id='".$book_id."'");

 if($stmt3->execute()){
   echo "successfully updated!!";
 }
 else{
   echo "failed updated!!";  
 }
}
else if($action=="publish book"){
$book_id=$_POST['bookId'];
$stmt4=$conn->prepare("UPDATE books_online SET public=1 WHERE book_id='".$book_id."'");
if($stmt4->execute()){
    echo "books published successfully";
}
else{
    
}
}
else if($action=="delete book"){
  $bookId = $_POST['bookId'];
$stmt5 = $conn->prepare("SELECT * FROM books_online WHERE book_id = :bookId");
$stmt5->bindParam(':bookId', $bookId);
$stmt5->execute();
$rows = $stmt5->fetch();
$book_file = $rows['book_file'];
$book_image = $rows['book_image'];

$file_path = "files/" . $book_file;
$image_path = "files/" . $book_image;

if (file_exists($file_path)) {
    if (unlink($file_path)) {
        echo "File removed successfully.";
    } else {
        echo "Failed to remove the file.";
    }
} else {
    echo "File not found.";
}

if (file_exists($image_path)) {
    if (unlink($image_path)) {
        echo "Image removed successfully.";
    } else {
        echo "Failed to remove the image.";
    }
} else {
    echo "Image not found.";
}
$stmt6=$conn->prepare("DELETE FROM books_online WHERE book_id='".$bookId."'");
$stmt6->execute();
}
else if($action=="edit_book_insert"){
$book_id = $_POST['bookId'];

    $stmt1 = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name
    FROM books_online INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.book_id = ? AND books_online.status = 1 AND books_online.type=2");
    $stmt1->execute([$book_id]);
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT * FROM tbl_department WHERE status = 1");
    $stmt2->execute();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
      $rows=array();
     array_push($rows,$data1, $data2);
    echo json_encode($rows);
    
}
else if($action=="submit_form_edit_insert"){
$book_id = $_POST['book_id2'];
$title = $_POST['title'];
$author2=$_POST['author2'];
$secondauthor2=$_POST['secauthor2'];
$publisher2=$_POST['publisher2'];
$department = $_POST['dep_id'];

$stmt3 = $conn->prepare("UPDATE books_online SET title='".$title."', department='".$department."',author='".$author2."',
sec_author='".$secondauthor2."',publisher='".$publisher2."'
WHERE book_id='".$book_id."'");

 if($stmt3->execute()){
   echo "successfully updated!!";
 }
 else{
   echo "failed updated!!";  
 }
}
else if($action=="publish_book_insert"){
$book_id=$_POST['bookId'];
$stmt4=$conn->prepare("UPDATE books_online SET public=1 WHERE book_id='".$book_id."'");
if($stmt4->execute()){
    echo "books published successfully";
}
else{
    
}
}
?>
