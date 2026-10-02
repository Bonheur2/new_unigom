<?php
    require_once('../../meet/con.php');
    require_once('./Service.php');
    
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
      echo $service->getDeposterOption();
    }
    else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?>