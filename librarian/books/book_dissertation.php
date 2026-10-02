<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'phpqrcode/phpqrcode/qrlib.php';
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="save_dist"){
    $authorD=$_POST['authorD'];
    $secondauthorD=$_POST['secondauthorD'];
    $b_locationsD=$_POST['b_locationsD'];
    $publisherD=$_POST['publisherD'];
    $date_of_publicationD=$_POST['date_of_publicationD'];
    $language_publicationD=$_POST['language_publicationD'];
    $sectionD=$_POST['sectionD'];
    $b_titleD=$_POST['b_titleD'];
    $b_depD=$_POST['b_depD'];
    $filepath = 'uploads/646dc5b02e9e2.png'; 
    $check=$conn->prepare("SELECT * FROM books WHERE title='".$b_titleD."'  ");
    $check->execute();
    $row2=$check->rowCount();
    $sql = $conn->prepare("SELECT book_code FROM books ORDER BY book_id DESC LIMIT 1");
    $sql->execute();
    $row = $sql->fetch();
    $last_code = $row['book_code'];
    $b_code = str_pad(intval($last_code+1), 4, '0', STR_PAD_LEFT);
    if($row2>0){
        $data = array("status" => "404", "message" => "Alraedy Exist"); 
    }
    else{
   $stmt =$conn->prepare("INSERT INTO books(title,publication_date, department, book_code, 
            author,publisher,location,image,date_of_publication,
            language_publication,section,secondauthor,type) 
                                             VALUES('".$b_titleD."','".$date_of_publicationD."', '".$b_depD."', '".$b_code."', '".$authorD."',
                                             '".$publisherD."','".$b_locationsD."',
                                             '".$filepath."',
                                            '".$date_of_publicationD."',
                                             '".$language_publicationD."','".$sectionD."','".$secondauthorD."',2)");      
    if ($stmt->execute()) {
                $data = array("status" => "200", "message" => "Data saved successfully!");
              
            } else {
                $data = array("status" => "500", "message" => "Failed to save data!");
               
            }     
    }
    echo json_encode($data);
     
}
?>