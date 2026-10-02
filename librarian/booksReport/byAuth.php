<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if($action=="loadbook_by_author"){
    $author=$_POST['author'];
    $query1=$conn->prepare("SELECT i.*,b.location_name,c.book_dep_name	FROM 
     books i INNER JOIN books_location b ON i.location=b.id 
     INNER JOIN tbl_books_depart c ON i.department=c.book_id WHERE i.author='".$author."' ");
    $query1->execute();
    $rows=$query1->fetchAll();
    echo json_encode($rows);
}

?>