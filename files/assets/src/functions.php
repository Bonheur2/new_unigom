<?php
    function sendFeedback($status, $message){
        $data = array("status" => $status, "message" => $message);
        $jsonData = json_encode($data);
        echo $jsonData; 
    }
?>