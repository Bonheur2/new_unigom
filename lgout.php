<?php
// 	$tm=date("Y-m-d H:i:s");
//     $logout_user = $conn->prepare("update tbl_user_session set time_logout=?,status=? where sess_id=?");
        try {
    //         // insert into tbl_personal_ug
    //         $logout_user->execute(array($tm, 'OFF', $_SESSION['sess_id']));
    //         // end to update
    // 		$name=strtoupper($_SESSION['email']);
    
            session_unset();
            session_destroy();
            header('Location: /auth');
            exit;
        }
        catch (PDOException $ex) {
            //Something went wrong rollback!			
            echo $ex->getMessage();
        }

?>