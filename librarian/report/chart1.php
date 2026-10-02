<?php
include ('../../meet/con.php');
$connection = $conn;

$stmt = $connection->prepare("SELECT borrow_status,issue_date, COUNT(*) AS circulation FROM borrowdetails GROUP BY borrow_status,issue_date ORDER BY issue_date");

$stmt->execute();

$data = $stmt->fetchAll();
$jsonData = json_encode($data);

header('Content-Type: application/json');
echo $jsonData;
?>
