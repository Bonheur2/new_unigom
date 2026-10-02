<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 17){
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	    case '1' :
        $title="Dashboard";
		$thing='base.php';
		break;
		
		// promotion
		case 'prmone':
        $title="Student Promotion";
		$thing='../files/promotion/byOne.php';
		break;
		
		case 'promclass':
        $title="Student Promotion";
		$thing='../files/promotion/byClass.php';
		break;
		
		// card
	    case 'stucard':
        $title="Student Card";
		$thing='../files/Card/student.php';
		break;
    	case 'cardreg':
        $title="Register student Card";
		$thing='../files/Card/register_card.php';
		break;

// 		start of timetable cases

		case 'timetable':
        $title="Time Tables";
		$thing='tables/time_table_haub.php';
		break;
		

		case 'addroom':
        $title="Rooms";
		$thing='rooms/add_room.php';
		break;
		
	    case 'addbloc':
        $title="Bloc";
		$thing='rooms/fac_blocks.php';
		break;
		
	    case 'gentables':
        $title="Generate Tables";
		$thing='tables/generated_tables.php';
		break;		

// end of timetable cases
		
		case 'ptype':
        $title="Program Types";
		$thing='../files/Programs/program_type.php';
		break;
		case 'profile':
        $title="profile";
		$thing='../files/profile/profile.php';
		break;
		
		case 'pwd':
        $title="Change Password";
		$thing='../files/profile/reset.php';
		break;
		case 'pfac':
        $title="Faculties";
		$thing='../files/Faculties/faculty.php';
		break;
		
		case 'pdept':
        $title="Departments";
		$thing='../files/Departments/department.php';
		break;
		
		case 'splz':
        $title="Specialization";
		$thing='../files/Specialization/specs.php';
		break;
		
		case 'acady':
        $title="Academic Year";
		$thing='../files/Acad_Year/acad_year.php';
		break;
		
		case 'acaintake':
        $title="Intakes";
		$thing='../files/Intakes/intakes.php';
		break;
		
		case 'sem':
        $title="Semester";
		$thing='../files/Semester/semester.php';
		break;
		
		case 'mod':
        $title="Modules";
		$thing='../files/Modules/module.php';
		break;
		
		case 'mod_lead_assign':
        $title="Module leaders";
		$thing='../files/Modules/module_leader.php';
		break;
		
		case 'lvlyear':
        $title="Levels";
		$thing='../files/Levels/level.php';
		break;
		
		case 'prg-mode':
        $title="Program mode";
		$thing='../files/Programs/program_mode.php';
		break;		
		
		case 'partn':
        $title="Partners";
		$thing='../files/Partners/partner.php';
		break;
		
		case 'mod_assign':
        $title="Module to year";
		$thing='../files/Modules/module_assign.php';
		break;

    	case 'admreq' :
        $title="Applicants";
    	$thing='../files/application/applications.php';
    	break;
    	
		case 'admresult' :
        $title="Admission result";
		$thing='../files/admission/results.php';
		break;
    		
    	case 'rev_app' :
        $title="Review Application";
    	$thing='../files/application/review.php';
    	break;
    	
    	case 'newstu' :
        $title="New student";
    	$thing='../files/Student/register.php';
    	break;
    	
    	case 'stuInfo' :
        $title="New student";
    	$thing='../files/Student/student_info.php';
    	break;
    	
    	case 'spsor' :
        $title="Sponsors";
    	$thing='../files/Sponsors/sponsor.php';
    	break;

    	case 'regmks' :
        $title="Marks";
    	$thing='../files/Marks/reg_marks_new.php';
    	break;
    	
    	case 'assess_mod' :
        $title="Assessment";
    	$thing='../files/Assessment/assessment.php';
    	break;
    	
    	case 'transOne' :
        $title="Transcript";
    	$thing='../files/Transcript/transcript.php';
    	break;
    	
    	case 'evres' :
        $title="Evaluation Results";
    	$thing='../files/Modules/evaluation.php';
    	break;
    	
    	//statistics
    	
    	case 'statype' :
        $title="Statistics by Program type";
    	$thing='../files/Statistics/program_type.php';
    	break;
    	
    	case 'sttfcult' :
        $title="Statistics by Faculty";
    	$thing='../files/Statistics/faculty.php';
    	break;
    	
    	case 'sttDepart' :
        $title="Statistics by Department";
    	$thing='../files/Statistics/department.php';
    	break;
    	
    	case 'stslevel' :
        $title="Statistics by Level";
    	$thing='../files/Statistics/level.php';
    	break;

    	case 'stsnation' :
        $title="Statistics by nationality";
    	$thing='../files/Statistics/nationality.php';
    	break;

    	case 'stsGender' :
        $title="Statistics by Gender";
    	$thing='../files/Statistics/gender.php';
    	break;

    	case 'stsacad' :
        $title="Statistics by Gender";
    	$thing='../files/Statistics/acad.php';
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
    	
    	case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    	
    	case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    	
    	case 'rtks' :
        $title="Retakers";
    	$thing='../files/Others/retakers.php';
    	break;
    	
    	case 'rpts' :
        $title="Repeaters";
    	$thing='../files/Others/repeaters.php';
    	break;
    	
    	case 'lectu' :
        $title="Lecturers";
    	$thing='../files/Others/lecturers.php';
    	break;
    	//session management
    	case 'tablehours':
        $title="Define Hours";
		$thing='../files/Timetable/hours.php';
		break;
		
		case 'addbloc':
        $title="Bloc";
		$thing='../files/Block/index.php';
		break;
		
		case 'addroom':
        $title="Rooms";
		$thing='../files/Block/rooms.php';
		break;
		
		case 'timetable':
        $title="Timetable";
		$thing='../files/Timetable/index.php';
		break;
		
		case 'gentables':
        $title="Generate Tables";
		$thing='../files/Timetable/timetables.php';
		break;
		
		case 'attlist' :
        $title="Attendance List";
    	$thing='../files/Others/attendance.php';
    	break;
    	
    	case 'exmat' :
        $title="Exam Attendance";
    	$thing='../files/Student/exam_attendance.php';
    	break;
		
		
	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
