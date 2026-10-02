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
	case 'profile':
        $title="profile";
		$thing='../files/profile/profile.php';
		break;
		
	case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
		
	case 'user' :
        $title="User account";
		$thing='../files/User/userList.php';
		break;
		
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    	
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    	
    ######### Teaching ##########
    	
	case 'attlistt':
        $title="Attendance ";
    	$thing='../lecturer/attendance.php';
    	break;	
		
	case 'regmt' :
        $title="Marks";
    	$thing='../files/Marks/marks.php';
    	break;

	case 'myAssesst' :
        $title="Lecture's assessments";
    	$thing='../files/Marks/my_assessment.php';
    	break;

	case 'tmtablet':
        $title="Time Table";
    	$thing='../lecturer/schedules/my_schedules.php';
    	break;
	
	######### End Teaching ##########
	
    case 'vopos' :
        $title="Voting Position";
    	$thing='../files/Votes/position.php';
    	break;

    case 'cand' :
        $title="Candidates";
    	$thing='../files/Votes/candidate.php';
    	break;

    case 'setg' :
        $title="Vote Settings";
    	$thing='../files/Votes/settings.php';
    	break;
    case 'vstats' :
        $title="Vote Statistics";
    	$thing='../files/Votes/statistics.php';
    	break;
    case 'empList' :
        $title="User";
    	$thing='../files/Employees/employees.php';
    	break;	
	

	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
