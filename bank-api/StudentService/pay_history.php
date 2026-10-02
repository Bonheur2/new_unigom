<?php
    require_once('../../meet/con.php');
    ini_set('display_errors', 1);
    require_once('./Service.php');
    
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $slip_number=$data['slip_number'];
        echo $service->payment_data($slip_number);
    }
    else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?>