<?php
header('Content-Type: application/json');
include ('meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




try {
    $select="SELECT DISTINCT name FROM tbl_fee_category WHERE status = 1";
    $cselect=$conn->prepare($select);
    $cselect->execute();
    $payments = $cselect->fetchAll(PDO::FETCH_ASSOC);

    if (empty($payments)) {
        echo json_encode(["error" => "No payments found"]);
    } else {
        echo json_encode($payments);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}







?>