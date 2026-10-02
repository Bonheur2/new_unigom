<?php  
require'meet/bind.php';
$acc_id=$_REQUEST['acc_id'];
$role_id=$_REQUEST['role_id'];


$login_time = $db->prepare("update tbl_users set role_id=? WHERE id=?");
$login_time->execute(array($role_id,$acc_id));
        
?>