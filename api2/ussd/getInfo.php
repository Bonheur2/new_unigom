<?php
    require_once('../../meet/con.php');
    require_once('./Service.php');
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (isset($data['studentId']) && isset($data['service_code'])) {
            $service_id = $service->authenticateServiceCode($data['service_code']);
            if($service_id !=0){
                echo $service->getStudentBalance($data['studentId']);
            }else{
                echo $service->sendFeedback(404, "Unknown Service Code!");
            }
            
        } else {
            echo $service->sendFeedback(400, 'Invalid request: studentId and service_code are required');
        }
    } else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?>