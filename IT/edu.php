<?php
require_once "../meet/bind.php";
if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
}
if($_SESSION['role_id'] == 1){

    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case 'on' :
        $title="Dashboard";
		$thing='base.php';
		break;
	case 'urat':
        $title="Users";
		$thing='../new_files/Users/index.php';
		break;
	case 'apprvw':
        $title = "Approval Setup";
        $thing = '../new_files/Approval_Review/index.php';
        break;
    case 'approval_review':
        $title = "Approval Setup";
        $thing = '../new_files/Approval_Review/review.php';
        break;    
		
	
	

	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
