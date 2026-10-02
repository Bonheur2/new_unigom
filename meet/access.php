<?php
//DIRECTORY_SEPARATOR is a PHP Pre-defined constants:
//(\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define ('SITE_ROOT', dirname(__DIR__));

defined('LIB_PATH') ? null : define ('LIB_PATH',SITE_ROOT.DS.'bind');

$dir = ['cost' => 12];
$disc="hello";
//load the database configuration first.
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");

$SqlPdo = $conn->prepare("SELECT * FROM tbl_users
    INNER JOIN tbl_user_roles ON tbl_user_roles.role_id=tbl_users.role_id
    WHERE tbl_users.id='".$_SESSION['acc_id']."' and tbl_users.email='".$_SESSION['email']."'");
	$SqlPdo->execute();
	
	$datain = $SqlPdo->rowCount();
	if($datain>0){
    while($GetPdo = $SqlPdo->fetch()){
     $family_name = $GetPdo['family_name'];
     $first_name = $GetPdo['first_name'];
     $acc_id = $GetPdo['id'];
     $role_id = $GetPdo['role_id'];
     $profile_id = $GetPdo['profile_id'];
     $department=$GetPdo['dep_id'];
     $u_role = $GetPdo['role'];
     $camp_id = $GetPdo['campus_id'];
     $identification = $GetPdo['Identification'];
     $dis="hello";
    }
	}
	
	else {
	    
	   $SqlPdo2 = $conn->prepare("SELECT * FROM tbl_applicants
	   INNER JOIN tbl_student_login ON tbl_student_login.Identification=tbl_applicants.code
       INNER JOIN tbl_user_roles ON tbl_user_roles.role_id=tbl_student_login.role_id
       WHERE tbl_student_login.id='".$_SESSION['acc_id']."'");
	   $SqlPdo2->execute();
	
	$datain2 = $SqlPdo2->rowCount();
	if($datain2>0){
	while($GetPdo = $SqlPdo2->fetch()){
	    
	 $family_name = $GetPdo['lname'];
     $first_name = $GetPdo['fname'];
     $acc_id = $GetPdo['applicant_id'];
     $code = $GetPdo['code'];
     $role_id = $GetPdo['role_id'];
     $dis="hello";
	}
	} else{
	    $dis="hello2";
	}
	}
?>