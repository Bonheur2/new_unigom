<?php
include ('.../../meet/con.php');
$action=$_POST['action'];
if($action=="searchAutho"){
    $input=$_POST['author'];
    $stmt = $conn->prepare("SELECT * FROM `authors` WHERE author_name like '".$input."%' ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
}


?>