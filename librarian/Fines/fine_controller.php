<?php 
include ('../../meet/con.php');
$connection=$conn;
$action=$_POST['action'];
 if($action=="saving_fine"){
    $days = $_POST['days'];
    $money = $_POST['money'];
    
    $query1 = $conn->prepare("SELECT id FROM books_fine ORDER BY id DESC LIMIT 1");
    $query1->execute();
    $Lastid = $query1->fetchColumn();
    
    $update = $conn->prepare("UPDATE books_fine SET status =2 WHERE id ='".$Lastid."'");
    $update->execute();
    
    $query = $conn->prepare("INSERT INTO books_fine (fine, status) VALUES ('".$money."', 1)");
    
    if ($query->execute() && $update->execute()) {
        $data = array("status" => "200", "message" => "Fines added successfully!!");
    } 
    
    else {
        $data = array("status" => "500", "message" => "Failed to save!!");
    }
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
     echo $jsonData;
 }

?>