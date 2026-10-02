<?php

require'../../meet/bind.php';
if(isset($_POST) & !empty($_POST)){
        $username=$_POST['username'];
        $oldpwd=$_POST['oldpwd'];
        $pwd=$_POST['password'];
        $cpawd=$_POST['confirm-password'];
        
    $sth = $db->query("SELECT * FROM tbl_users WHERE email='" .$username. "' AND status = 1 ");
    if ($sth->rowCount() != 0) {
        
    $res = $sth->fetch();
    
    if (password_verify($oldpwd, $res['password'])){
    
    if($pwd==$cpawd){
        
        $hash = password_hash($cpawd, PASSWORD_DEFAULT);
        $login_time = $db->prepare("update tbl_users set password=?WHERE email=?");
      
      
        $login_time->execute(array($hash,$username));
     echo 1;  
     
     
     
    }
    
    else
    {
        echo 3;
    }
    
    }
    else{
    echo 0;
    }
    }
 
 
  
//   if(){}
  
    // $login_time = $db->prepare("update tbl_users set first_name=?,family_name=?,phone_no=? WHERE id=?");
      
      
    //     $login_time->execute(array($fname,$lname,$phone, $acc_i));
       
  
    
    
}


?>

