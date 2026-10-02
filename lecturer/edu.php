<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 12){
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
		
	case 'tmtable':
        $title="Timetable";
		$thing='my_schedules.php';
		break;		
		
	case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
		
    	case 'attlist' :
        $title="Attendance List";
    	$thing='./attendance.php';
    	break;	
		
	case 'regm' :
        $title="Marks";
    	$thing='../files/Marks/marks.php';
    	break;
	case 'myAssess' :
        $title="Lecture's assessments";
    	$thing='../files/Marks/my_assessment.php';
    	break;
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    	
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;

	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
