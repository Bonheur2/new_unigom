<?php
include ('../../meet/con.php');
$connection = $conn;

$stmt = $connection->prepare("SELECT books.title, book_copies.status, COUNT(*) AS count
        FROM book_copies
        JOIN books ON book_copies.book_id = books.book_id
        GROUP BY books.title, book_copies.status
        ");

$stmt->execute();

$data = $stmt->fetchAll();
$jsonData = json_encode($data);

header('Content-Type: application/json');
echo $jsonData;
?>
