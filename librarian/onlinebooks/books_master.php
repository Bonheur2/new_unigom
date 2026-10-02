<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="save_E_book"){
$currentDate = date('Y-m-d');
$d_id = $_POST['d_name'];
$b_title = $_POST['b_title'];
$isbn=$_POST['isbn'];
$file = $_FILES['book_file'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$img=$_FILES['image_file'];
$image_name=$img['name'];

$destination = 'files/';
$file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

// Set file path with desired extension
$file_path = $destination . $file_name_without_extension . '.' . $file_extension;
$file_to_save=$file_name_without_extension . '.' . $file_extension;
if (!move_uploaded_file($file_tmp, $file_path)) {
    echo "fail";
} else {
    $check=$conn->prepare("SELECT * FROM books_online WHERE isbn='".$isbn."'");
    $check->execute();
    if($row=$check->rowCount()>0){
       $data = array("status" => "401", "message" => "Book already exist!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;  
    }
    else{
     $query = $conn->prepare("INSERT INTO books_online (title, department, book_file,isbn,book_image,created_at, status,type) VALUES (?, ?,?, ?, ?,?,?,?)");
    $query->bindParam(1, $b_title);
    $query->bindParam(2, $d_id);
    $query->bindParam(3, $file_to_save);
    $query->bindParam(4, $isbn);
    $query->bindParam(5, $image_name);
    $query->bindParam(6, $currentDate);
    $query->bindValue(7, 1, PDO::PARAM_INT);
    $query->bindValue(8, 1, PDO::PARAM_INT);

    if ($query->execute()) {
        $data = array("status" => "200", "message" => "Book inserted successfully!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
        
    }  
    else {
        $data = array("status" => "500", "message" => "Saving failed!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }
    }
    
}


        
        
}
else if($action=="save_E_paper"){
$currentDate = date('Y-m-d');
$b_title = $_POST['b_title'];
$file = $_FILES['book_file'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
// $img=$_FILES['image_file'];
// $image_name=$img['name'];
$author=$_POST['author'];
$secauthor=$_POST['secondauthor'];
$publish=$_POST['publisher'];
$coverImage = 'image/coverpage.png'; 
$destination = 'files/';
$file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

// Set file path with desired extension
$file_path = $destination . $file_name_without_extension . '.' . $file_extension;
$file_to_save=$file_name_without_extension . '.' . $file_extension;
if (!move_uploaded_file($file_tmp, $file_path)) {
    echo "fail";
} else {
    $check=$conn->prepare("SELECT * FROM books_online WHERE title='".$b_title."' AND author='".$author."'");
    $check->execute();
    if($row=$check->rowCount()>0){
       $data = array("status" => "401", "message" => "paper already exist!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;  
    }
    else{
     $query = $conn->prepare("INSERT INTO books_online (title, department,book_file,book_image,created_at, status,type,author,sec_author,publisher)
     VALUES ('".$b_title."',12,'".$file_to_save."','".$coverImage."','".$currentDate."',1,2,'".$author."','".$secauthor."','".$publish."')");
   
    if ($query->execute()) {
        $data = array("status" => "200", "message" => "paper inserted successfully!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
        
    }  
    else {
        $data = array("status" => "500", "message" => "Saving failed!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }
    }
    
}


      
}
?>