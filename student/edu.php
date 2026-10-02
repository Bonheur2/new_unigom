<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 4){
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
		
	case 'mydoc' :
        $title="My documents";
		$thing='../files/Student/documents/documents.php';
		break;
		
    // start
    case 'mySchedule':
        $title ="Timetable";
        $thing ='my_schedules.php';
        break;
    // end
    
	case 'mypro' :
        $title="My Profile";
		$thing='../files/Student/documents/profile.php';
		break;
		
	case 'infoacad':
        $title="Academic Info";
		$thing='../files/Student/documents/acad_info.php';
		break;
		
	case 'myphoto':
        $title="My Photo";
		$thing='../files/Student/documents/photo.php';
		break;
		
	case 'regm':
        $title="Register on module";
		$thing='../files/Student/modules/modules.php';
		break;
		
	case 'moeva':
        $title="Module Evaluation";
		$thing='../files/Student/modules/evaluation.php';
		break;
		
	case 'mmod':
        $title="My modules";
		$thing='../files/Student/modules/my_modules.php';
		break;
	case 'reoMo':
        $title="Failed modules";
		$thing='../files/Student/modules/failed_modules.php';
		break;
	case 'mmarks':
        $title="My marks";
		$thing='../files/Student/marks.php';
		break;
	case 'mstats':
        $title="Finance Statistics";
		$thing='../files/Student/finance.php';
		break;
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    	
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    	
   case 'eblst' :
        $title="E-Books";
		$thing='ListEbooks/list2.php';
		break;
		
	case 'hec':
        $title="HEC-STUDENT SATISFACTION";
		$thing='../files/Student/hec/index.php';
		break;
		
	case 'sbox':
        $title="Suggestion Box";
		$thing='../files/Suggestions/student.php';
		break;
		
	case 'request':
        $title="Requests";
		$thing='../files/Requests/student.php';
		break;
		
	case 'msvt':
        $title="Election";
		$thing='../files/Votes/voters.php';
		break;
	case 'regonsem':
	    $title="Election";
		$thing='../files/Student/register_on_semester.php';
		break;
	
	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
