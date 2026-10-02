<?php
    require_once('../../meet/con.php');
    require_once('./Service.php');
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['studentId']) && isset($data['service_code']) && isset($data['amount']) && isset($data['transaction_id'])) {
            $is_student = $service->checkStudent($data['studentId']);
            if($is_student == 1){
                echo $service->processPayment($data['studentId'], 1, $data['service_code'], $data['amount'], $data['transaction_id']);
            } else if($is_student == 2){
                echo $service->processPayment($data['studentId'], 7, $data['service_code'], $data['amount'], $data['transaction_id']);
            } else{
                echo $service->sendFeedback(400, 'Unknown student ID');
            }
            
        } else {
            echo $service->sendFeedback(400, 'Invalid request: some fields are missing');
        }
    } else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?> 