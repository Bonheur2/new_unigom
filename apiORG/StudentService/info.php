<?php
    require_once('../../meet/con.php');
    require_once('./Service.php');
    
    
    $service = new Service($conn);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (isset($data['student_code'])) {
            // echo $service->getStudentPaymentData($data['student_code'], $data['bank_code']);
            // $data['deposter_type']
            if($data['student_code']==1){
                
                // echo $service->getApplicantBasicData($data['student_code'],$data['bank_code']);
            }
            else if($data['student_code']==2){
                
            // echo $service->sendFeedback(200, 'welcome Student',$data['student_code']);  
            //  echo $service->getApplicantBasicData($data['student_code'],$data['bank_code']);
            }
           echo $service->getApplicantBasicData($data['student_code'],$data['bank_code']);  
        } 
        else {
            
            echo $service->sendFeedback(400, 'Invalid request: student_code are required');
        }
    } else {
        echo $service->sendFeedback(405, 'Invalid request method. Use POST.');
    }
?>