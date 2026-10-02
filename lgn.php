<?php
ob_start();
require'meet/bind.php';
if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
  try {
    $password  = $_POST['login-password'];
	$tm = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $sth = $db->query("SELECT * FROM tbl_users WHERE email='" .$_POST['login-username'] . "' AND status = 1 AND role_id in(1,2) ");
    if ($sth->rowCount() != 0) {
        
    $res = $sth->fetch();
    
    if (password_verify($password, $res['password']))
    {
    $_SESSION['acc_id'] = $res['id'];
    $_SESSION['f_name'] = $res['first_name'];
    $_SESSION['l_name'] = $res['family_name'];
    $_SESSION['role_id'] = $res['role_id'];
    $_SESSION['profile_id'] = $res['profile_id'];
    $_SESSION['email'] = $res['email'];
    $_SESSION['camp_id'] = $res['campus_id'];
    $_SESSION['mob'] = $res['phone_no'];
    $_SESSION['access'] = true;
    $today = date("Y-m-d H:i:s");
    $_SESSION['status']= 'ON';
    
    $message = 'success';
    // $login_time = $db->prepare("update tbl_users set last_logged_in=? WHERE acc_id=?");
      
    //   try {
    //     // time logged
    //     $login_time->execute(array($today, $_SESSION['acc_id']));
    //     } catch (PDOException $ex) {
    //         //Something went wrong rollback!
    //         echo $ex->getMessage();
    //     }
	
	//Condition to directory path..

	      
    if ($_SESSION['role_id'] == 1) {
        
         	?>
    <script type="text/javascript">
  window.location.href = "IT/edu";
</script>
<?php
        //header('Location: IT/edu');
       
      }
      elseif ($_SESSION['role_id'] == 2) {
          
              	?>
    <script type="text/javascript">
  window.location.href = "academic/edu";
</script>
<?php
   }
        else{
            
          
         header('Location:IT/edu');
        }
        
    }
    else{
        $errors[] = "User Name / E-Mail & Password Combination not Working";  
    }
    //END VERFIE
    			
	} else{
	     $errors[] = "User Name / E-Mail & Password Combination not Working "; 
	}  

    }
    catch (PDOException $ex) 
    {
      //Something went wrong rollback!
      echo $ex->getMessage();
    }
}

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

ob_end_flush();
?>

