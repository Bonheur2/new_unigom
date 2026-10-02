<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if ($action == "search_book_by_name") {
    $book_name = $_POST['book_name'];
    
    if (!empty($book_name)) {
        $query1 = $conn->prepare("SELECT i.*, b.location_name FROM books i INNER JOIN books_location b ON i.location = b.id WHERE i.title LIKE '".$book_name."%'");
        $query1->execute();
        $rows = $query1->fetchAll();
        echo json_encode($rows);
    } else {
        echo json_encode([]);
    }
}

?>