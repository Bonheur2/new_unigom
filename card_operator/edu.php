<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 9){
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
	case 'stuAcs':
        $title="Student Access";
		$thing='../files/Card/access.php';
		break;
	case 'stucard':
        $title="Student Card";
		$thing='../files/Card/student.php';
		break;
	case 'cardreg':
        $title="Register student Card";
		$thing='../files/Card/register_card.php';
		break;
		
	case 'staffcard':
        $title="Student Card";
		$thing='../files/Card/staff/card.php';
		break;
	case 'staffcardreg':
        $title="Register staff Card";
		$thing='../files/Card/staff/register_card.php';
		break;
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    case 'staffcardregrep':
        $title="Staff Card Report";
    	$thing='../files/Card/staff/card_report.php';
    	break;

	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
