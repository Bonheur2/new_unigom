<?php
include ('../../../meet/con.php');

$staff_id = $_POST['staff_id'];
$card_no = $_POST['card_no'];

function sendFeedback($s, $m){
    $data = array("status"=> $s,"message" => $m);
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData; 
}

// check staff
$sql0 = $conn->prepare("SELECT * FROM tbl_staff WHERE staff_id = '".$staff_id."'");
$sql0->execute(); 
if($sql0->rowCount()==0){
    sendFeedback(401, "No member associated with this ID");
}
else{
    //check pre-exists
    $sql = $conn->prepare("SELECT card_no FROM tbl_staff WHERE staff_id != '".$staff_id."' AND card_no = '".$card_no."'");
    $sql->execute(); 
    if($sql->rowCount()>0){
        sendFeedback(401, "This card is owned by another staff member!");
    }
    else{
        $sql2 = $conn->prepare("SELECT card_no FROM tbl_staff WHERE staff_id = '".$staff_id."' AND card_no = '".$card_no."'");
        $sql2->execute();
        if($sql2->rowCount()>0){
            sendFeedback(401, "This card is already registered!");
        }
        else{
            $update = $conn->prepare("UPDATE tbl_staff SET card_no='".$card_no."' WHERE staff_id='".$staff_id."'");
            if($update->execute()){
                 sendFeedback(200, "Card registered successfully!");
            }
            else{
                sendFeedback(401, "Operation failed!");
            }
        }
    }
}
?>