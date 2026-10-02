<?php
include ('../../../meet/con.php');

$keyword =$_POST['key'];
$sql = $conn->prepare("SELECT * FROM tbl_staff WHERE (staff_id = '".$keyword."' OR email='".$keyword."' OR 	PhoneNumber='".$keyword."') AND staff_active=1 ORDER BY id DESC LIMIT 1");
$sql->execute(); 
$data=$sql->fetch();
$rowCount=$sql->rowCount();
if($rowCount>0){
    $staffId=$data['staff_id'];
    $data=array("status"=>200,"message"=>$staffId);
    echo json_encode($data);
}
?>