<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 18){
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case 'on' :
        $title="Dashboard";
		$thing='base.php';
		break;
// 	case 'profile':
//         $title="profile";
// 		$thing='../files/profile/profile.php';
// 		break;
// 	case 'pwd':
//         $title="Change Password";
// 		$thing='../files/profile/reset.php';
// 		break;
// 	case 'ups' :
//         $title="Campuses";
// 		$thing='../files/Campus/campus.php';
// 		break;
// 	case 'user' :
//         $title="User account";
// 		$thing='users.php';
// 		break;
// 	case 'uni' :
//         $title="Settings";
// 		$thing='../files/Settings/university.php';
// 		break;	
//     case 'ann' :
//         $title="Announcement";
//     	$thing='../files/announcements/announcement.php';
//     	break;
    	
//     case 'det_ann' :
//         $title="Announcement";
//     	$thing='../files/announcements/det_ann.php';
//     	break;

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
