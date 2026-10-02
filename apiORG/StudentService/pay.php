<?php
    require_once('../../meet/con.php');
    require_once('./Service.php');
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
    

        if (isset($data['student_code']) && isset($data['fee_id'])
        && isset($data['bank_id']) && isset($data['slip_number']) && isset($data['amount']) && isset($data['postedAt'])
         && isset($data['fixed']) && isset($data['transaction_id']) ) {   

            echo $service->processPayment($data['transaction_id'] ,$data['student_code'], $data['fee_id'], $data['bank_id'], $data['slip_number'], $data['amount'],
            "B-AGENT", $data['postedAt'],$data['fixed'],$data['fee_amout']);
        } else {
            echo $service->sendFeedback(400, 'Invalid request: some fields are missing');
        }
    } else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?>