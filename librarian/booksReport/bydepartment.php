<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if($action=="loadbook_by_departmet"){
    $dep=$_POST['dep'];
    $query1=$conn->prepare("SELECT i.*,b.location_name	FROM 
     books i INNER JOIN books_location b ON i.location=b.id 
     WHERE i.department='".$dep."' ");
    $query1->execute();
    $rows=$query1->fetchAll();
    echo json_encode($rows);
}

?>