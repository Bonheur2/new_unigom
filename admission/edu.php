<?php
require_once "../meet/bind.php";
if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
}
if($_SESSION['role_id'] == 6){
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case 'on' :
        $title="Dashboard";
		$thing='base.php';
		break;
		
	case 'new_applicant':
        $title="profile";
		$thing='../files/application/special_apply.php';
		break;
		
		case 'apppro':
        $title="Applicant problems";
		$thing='../files/applicants/applicant_problems.php';
		break;
		
	case 'profile':
        $title="profile";
		$thing='../files/profile/profile.php';
		break;
		
	case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
		
	case 'docset' :
        $title="Application setup";
		$thing='../files/Docs/documents.php';
		break;	
    case 'pver' :
        $title="Applicants";
    	$thing='../files/application/pending_verification.php';
    	break;
    case 'padm' :
        $title="Applicants";
    	$thing='../files/application/attempts.php';
    	break;
    case 'notify' :
        $title="Applicants";
    	$thing='../files/application/notify.php';
    	break;
	case 'admreq' :
        $title="Applications";
		$thing='../files/application/applications.php';
		break;
	case 'rev_app' :
        $title="Applications";
		$thing='../files/application/preliminary_review.php';
		break;
	case 'admresult' :
        $title="Admission results";
		$thing='../files/admission/results.php';
		break;

	case 'newstu' :
        $title="Student registration";
		$thing='../files/Student/register.php';
		break;

	case 'stuInfo' :
        $title="Student information";
		$thing='../files/Student/student_info.php';
		break;
		
        //reports
    	case 'bynation' :
        $title="report by Gender";
    	$thing='../files/Student_reports/bynation.php';
    	break;
    	
    	case 'bysts' :
        $title="report by status";
    	$thing='../files/Student_reports/bystatus.php';
    	break;
    	
    	case 'bygnd' :
        $title="report by gender";
    	$thing='../files/Student_reports/bygender.php';
    	break;
    	
    	case 'bypro' :
        $title="report by province";
    	$thing='../files/Student_reports/byprovince.php';
    	break;
    	
    	case 'bydist' :
        $title="report by district";
    	$thing='../files/Student_reports/bydistrict.php';
    	break;
    	
    	case 'bysec' :
        $title="report by sector";
    	$thing='../files/Student_reports/bysector.php';
    	break;
    	
    	case 'stugen' :
        $title="General report";
    	$thing='../files/Student_reports/general.php';
    	break;
    // 	case 'statgen' :
    //     $title="General statistics";
    // 	$thing='../files/Statistics/general.php';
    // 	break;
    	case 'statype' :
        $title="Statistics by program type";
    	$thing='../files/Statistics/program_type.php';
    	break;
    	case 'sttfcult' :
        $title="Statistics by faculity";
    	$thing='../files/Statistics/faculty.php';
    	break;
    	case 'sttDepart' :
        $title="Statistics by department";
    	$thing='../files/Statistics/department.php';
    	break;
    	case 'stslevel' :
        $title="Statistics by level";
    	$thing='../files/Statistics/level.php';
    	break;
    	case 'stsnation' :
        $title="Statistics by nation";
    	$thing='../files/Statistics/nationality.php';
    	break;
    	case 'stsGender' :
        $title="Statistics by gender";
    	$thing='../files/Statistics/gender.php';
    	break;
    	case 'stsacad' :
        $title="Statistics by academic year";
    	$thing='../files/Statistics/acad.php';
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
        	$thing='../lecturer/my_schedules.php';
        	break;
        case 'checkduplicate':
            $title="Time Table";
        	$thing='../files/application/check_duplicate.php';
        	break;
        case 'admrecf':
            $title="Time Table";
        	$thing='../files/application/failed payment.php';
        	break;
        case 'admpaysta':
            $title="Time Table";
        	$thing='../files/application/applicant_payed.php';
        	break;
        case 'coeiss':
            $title="Time Table";
        	$thing='../files/application/Code_Email_Issue.php';
        	break;
        case 'appres':
            $title="Time Table";
        	$thing='../files/application/applicant_passowrd_reset.php';
        	break;
        case 'appunl':
            $title="Time Table";
        	$thing='../files/application/Unlock_Payed_Application.php';
        	break;
        case 'appwcd':
            $title="Time Table";
        	$thing='../files/application/Application_without_doc.php';
        	break;
        case 'vercre':
            $title="Time Table";
        	$thing='../files/application/verify_account.php';
        	break;
        case 'creacc':
            $title="Applicant";
        	$thing='../files/application/verify_create_account.php';
        	break;	
    	
    	######### End Teaching ##########
    	
    	default :
    	    $title="Home";
    		$thing ='base.php';
        }
}
   require_once("../comb/focal.php");
?>
