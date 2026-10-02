<?php
include ('../../meet/con.php');

$reg_no = $_POST['reg_no'];
$card_no = $_POST['card_no'];

function sendFeedback($s, $m){
    $data = array("status"=> $s,"message" => $m);
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData; 
}

// check student
$sql0 = $conn->prepare("SELECT * FROM tbl_admission WHERE reg_no = '".$reg_no."'");
$sql0->execute(); 
if($sql0->rowCount()==0){
    sendFeedback(401, "No student associated with this ID");
}
else{
    //check pre-exists
    $sql = $conn->prepare("SELECT card_no FROM tbl_admission WHERE reg_no != '".$reg_no."' AND card_no = '".$card_no."'");
    $sql->execute(); 
    if($sql->rowCount()>0){
        sendFeedback(401, "This card is owned by another student!");
    }
    else{
        $sql2 = $conn->prepare("SELECT card_no FROM tbl_admission WHERE reg_no = '".$reg_no."' AND card_no = '".$card_no."'");
        $sql2->execute();
        if($sql2->rowCount()>0){
            sendFeedback(401, "This card is already registered!");
        }
        else{
            $update = $conn->prepare("UPDATE tbl_admission SET card_no='".$card_no."' WHERE reg_no='".$reg_no."'");
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