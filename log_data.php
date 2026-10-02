<?php
ob_start();
require'meet/bind.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function auth_log($message){
    error_log('[AUTH] '.date('Y-m-d H:i:s').' - '.$message);
}

if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    $password  = $_POST['login-password'];
	$tm = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];

    auth_log('Login attempt for email="'.$_POST['login-username'].'" from ip='.$ip);

    $sth = $db->query("SELECT * FROM tbl_users WHERE email='" .$_POST['login-username'] . "' AND status = 1 AND role_id not in(0) ");
    auth_log('tbl_users lookup returned '.$sth->rowCount().' row(s)');
    if($sth->rowCount() != 0){
        $res = $sth->fetch();
        auth_log('tbl_users matched id='.$res['id'].' role_id='.$res['role_id'].' - verifying password');
        if (password_verify($password, $res['password'])){
            auth_log('tbl_users password_verify SUCCEEDED for id='.$res['id']);
            $_SESSION['acc_id'] = $res['id'];
            $_SESSION['Identification'] = $res['Identification'];
            $_SESSION['f_name'] = $res['first_name'];
            $_SESSION['l_name'] = $res['family_name'];
            $_SESSION['role_id'] = $res['role_id'];
            $_SESSION['email'] = $res['email'];
            $_SESSION['camp_id'] = $res['campus_id'];
            $_SESSION['mob'] = $res['phone_no'];
            $_SESSION['dept_id']=$res['dept_id'];
            $_SESSION['fac_id']=$res['fac_id'];
            $_SESSION['access'] = true;
            $now = date("Y-m-d H:i:s");
            $_SESSION['status']= 'ON';
            
            $message = 'success';
            $login_time = $db->prepare("update tbl_users set last_logged_in=? WHERE id=?");
              
              try {
                $login_time->execute(array($now, $res['id']));
                } catch (PDOException $ex) {
                    echo $ex->getMessage();
                }
        	
        	//Condition to directory path..
        
            if ($_SESSION['role_id'] == 18) {
                echo 18;
            }else if ($_SESSION['role_id'] == 1) {
                echo 1;
            }elseif ($_SESSION['role_id'] == 2) {
                 echo 2; 
            }elseif ($_SESSION['role_id'] == 3) {
                 echo 3; 
            }elseif ($_SESSION['role_id'] == 4) {
                 echo 4; 
            }elseif ($_SESSION['role_id'] == 5) {
                 echo 5; 
            }elseif ($_SESSION['role_id'] == 6) {
                 echo 6; 
            }elseif ($_SESSION['role_id'] == 7) {
                 echo 7; 
            }elseif ($_SESSION['role_id'] == 8) {
                 echo 8; 
            }elseif ($_SESSION['role_id'] == 9) {
                 echo 9; 
            }elseif ($_SESSION['role_id'] == 10) {
                 echo 10; 
            }elseif ($_SESSION['role_id'] == 11) {
                 echo 11; 
            }elseif ($_SESSION['role_id'] == 12) {
                 echo 12; 
            }elseif ($_SESSION['role_id'] == 13) {
                 echo 13; 
            }elseif ($_SESSION['role_id'] == 14) {
                 echo 14; 
            }elseif ($_SESSION['role_id'] == 15) {
                 echo 15; 
            }elseif ($_SESSION['role_id'] == 16) {
                 echo 16; 
            }elseif ($_SESSION['role_id'] == 17) {
                 echo 17; 
            }elseif ($_SESSION['role_id'] == 19) {
                 echo 19; 
            }elseif ($_SESSION['role_id'] == 20 || $_SESSION['role_id'] == 21) {
                 echo 20; 
            }elseif ($_SESSION['role_id'] == 22) {
                 echo 22; 
            }elseif ($_SESSION['role_id'] == 23) {
                 echo 23; 
            }elseif ($_SESSION['role_id'] == 25) {
                 echo 25; 
            }elseif($_SESSION['role_id'] == 26){
                 echo 26;
            }elseif($_SESSION['role_id'] == 28){
                 echo 28;
            }else{
                auth_log('tbl_users role_id='.$_SESSION['role_id'].' matched NO echo branch - redirecting to IT/edu with empty response body (this looks like a bug, not a credentials issue)');
                header('Location:IT/edu');
            }
        }
        else{
            auth_log('tbl_users password_verify FAILED for id='.$res['id'].' email='.$res['email']);
            $errors[] = "User Name / E-Mail & Password Combination not Working";
        }
	}else{
	    $sth = $db->query("SELECT * FROM tbl_student_login WHERE email='" .$_POST['login-username'] . "' AND status = 1");
	    auth_log('tbl_users had no match; tbl_student_login lookup (email + status=1) returned '.$sth->rowCount().' row(s)');
	    if ($sth->rowCount() != 0){
    	    $res = $sth->fetch();
    	    auth_log('tbl_student_login matched id='.$res['id'].' role_id='.$res['role_id'].' Identification='.$res['Identification'].' - verifying password');
    	    if (password_verify($password, $res['password'])){
                auth_log('tbl_student_login password_verify SUCCEEDED for id='.$res['id']);
                $_SESSION['acc_id'] = $res['id'];
                $_SESSION['identification'] = $res['Identification'];
                $_SESSION['role_id'] = $res['role_id'];
                $today = date("Y-m-d H:i:s");
                $_SESSION['status']= 'ON';
                $message = 'success';
                $_SESSION['access'] = true;
                $login_time = $db->prepare("UPDATE tbl_student_login set last_logged_in=? WHERE id=?");
                try {
                    $login_time->execute(array($today, $_SESSION['acc_id']));
                } catch (PDOException $ex) {
                        echo $ex->getMessage();
                }
                if ($_SESSION['role_id'] == 5) {
                     echo 5;
                } else {
                    auth_log('tbl_student_login role_id='.$_SESSION['role_id'].' did NOT match the expected 5 - no output echoed, client will see this as "Incorrect credentials" even though the password was correct (this looks like a bug, not a credentials issue)');
                }
            }else{
                auth_log('tbl_student_login password_verify FAILED for id='.$res['id'].' email='.$res['email']);
                $errors[] = "User Name / E-Mail & Password Combination not Working";
            }
	    }else{
	      auth_log('No matching row in tbl_users or tbl_student_login for email="'.$_POST['login-username'].'" (or status != 1)');
	      $errors[] = "User Name / E-Mail & Password Combination not Working";
	    }
	}
}

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

ob_end_flush();
?>

