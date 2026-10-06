<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 14){
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case 'on' :
        $title="Dashboard";
		$thing='base.php';
		break;
	case 'profile':
        $title="profile";
		$thing='../files/profile/profile.php';
		break;
		
	case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
	//invoicing 
	
	case 'invTrack' :
        $title="Suivi des factures";
		$thing='../new_files/Invoice_Tracking/index.php';
		break;

	default :
	    $title="Home";
		$thing='base.php';
    }
}
   require_once("../comb/focal.php");
?>