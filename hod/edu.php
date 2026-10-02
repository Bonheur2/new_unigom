<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 10){

    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
        case 'graduantList':
        $title="Graduants List";
        $thing='gradautionList.php';  
        break;
        
        case 'graduantstats':
        $title="Graduants Statistics";
        $thing='gradautionStatistics.php';  
        break;

		case 'profile':
        $title="profile";
		$thing='../files/profile/profile.php';
		break;
		
		case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
		
		case 'mod':
        $title="Modules";
		$thing='./files/Modules/module.php';
		break;
		
		case 'mod_lead_assign':
        $title="Module leaders";
		$thing='./files/Modules/module_leader.php';
		break;
		
		case 'mod_assign':
        $title="Module to year";
		$thing='./files/Modules/module_assign.php';
		break;
		
		case 'mod_assign_verify':
        $title="Year Modules";
		$thing='./files/Modules/module_assign_verify.php';
		break;
		
		case 'mod_stu':
        $title="Student - Modules";
		$thing='./files/Student/student_modules.php';
		break;
    	
    	case 'stuInfo' :
        $title="Search student";
    	$thing='./files/Student/student_info.php';
    	break;
		
    	case 'msing' :
        $title="Marks";
    	$thing='./files/Marks/individual_marks.php';
    	break;

    	case 'regmks' :
        $title="Marks";
    	$thing='./files/Marks/reg_marks_new.php';
    	break;
    	
    	case 'cmrks' :
        $title="Marks";
    	$thing='./files/Marks/check_marks.php';
    	break;
    	
    	case 'transOne' :
        $title="Transcript";
    	$thing='../files/Transcript/transcript.php';
    	break;
    	
    	//statistics
    	
    	case 'stslevel' :
        $title="Statistics by Level";
    	$thing='./files/Statistics/level.php';
    	break;

    	case 'stsnation' :
        $title="Statistics by nationality";
    	$thing='./files/Statistics/nationality.php';
    	break;

    	case 'stsGender' :
        $title="Statistics by Gender";
    	$thing='./files/Statistics/gender.php';
    	break;

    	case 'stsacad' :
        $title="Statistics by academic year";
    	$thing='./files/Statistics/acad.php';
    	break;
    	
    	case 'stsacasem' :
        $title="Statistics by academic year";
    	$thing='./files/Statistics/semester.php';
    	break;
    	
    	case 'ann' :
        $title="Announcement";
    	$thing='./files/announcements/announcement.php';
    	break;
    	
    	case 'det_ann' :
        $title="Announcement";
    	$thing='./files/announcements/det_ann.php';
    	break;
    	
    	case 'rtks' :
        $title="Retakers";
    	$thing='./files/Others/retakers.php';
    	break;
    	
    	case 'rpts' :
        $title="Repeaters";
    	$thing='./files/Others/repeaters.php';
    	break;
    	
    	case 'lectu' :
        $title="Lecturers";
    	$thing='./files/Others/lecturers.php';
    	break;
    	
    	
    	case 'attlist' :
        $title="Attendance List";
    	$thing='./files/Others/attendance.php';
    	break;
    	
    	case 'hcrep' :
        $title="Student Reports";
    	$thing='./files/Student_reports/transferable.php';
    	break;
    	
    	case 'hcrepage' :
        $title="Student Reports";
    	$thing='./files/Student_reports/hec_age_report.php';
    	break;
    	
        case 'exmat' :
        $title="Exam Attendance";
    	$thing='../files/Student/exam_attendance.php';
    	break;
    	
    	//student satisfaction
    	case 'stathecgen' :
        $title="General Statistics";
    	$thing='./files/Student/hec/statistics/general.php';
    	break;
    	
    	case 'stahectype' :
        $title="Statistics by Program type";
    	$thing='./files/Student/hec/statistics/program_type.php';
    	break;
    	
    	case 'stthecfcult' :
        $title="Statistics by Faculty";
    	$thing='./files/Student/hec/statistics/faculty.php';
    	break;
    	
    	case 'stthecsplz' :
        $title="Statistics by Department";
    	$thing='./files/Student/hec/statistics/specialization.php';
    	break;
    	
    	case 'stslevel' :
        $title="Statistics by Level";
    	$thing='./files/Student/hec/statistics/level.php';
    	break;

    	case 'stshecgender' :
        $title="Statistics by Gender";
    	$thing='./files/Student/hec/statistics/gender.php';
    	break;
    	
        ######### Teaching ##########
        	
    	case 'attlistt':
        $title="Attendance ";
    	$thing='../lecturer/attendance.php';
    	break;	
    		
    	case 'regmt' :
        $title="Marks";
    	$thing='./files/Marks/marks.php';
    	break;
    
    	case 'myAssesst' :
        $title="Lecture's assessments";
    	$thing='../files/Marks/my_assessment.php';
    	break;
    
    	case 'tmtablet':
        $title="Time Table";
    	$thing='../lecturer/schedules/my_schedules.php';
    	break;
    	
        case 'regmks' :
        $title="Marks";
    	$thing='../files/Marks/reg_marks_lecturer.php';
    	break;
    	
    	case 'admreq' :
        $title="Applications";
		$thing='./files/application/applications.php';
		break;
		case 'rev_app' :
        $title="Applications";
		$thing='./files/application/preliminary_review.php';
		break;
		case 'admresult' :
        $title="Admission results";
		$thing='./files/application/results.php';
		break;
	    case 'hfdtlst' :
        $title="Foundation List";
		$thing='../files/application/Hod_foundationList.php';
		break;
		case 'hdstl' :
        $title="Foundation List";
		$thing='../files/Student/Hod_StudentList.php';
		break;
		
    	######### End Teaching ##########

	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
