<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 2){

    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	    case 'on' :
        $title="Dashboard";
		$thing='base.php';    
		break;

		case 'npo':
        $title="new prin";
		$thing='printOut2.php';
		break;
		
		// promotion
		case 'prmone':
        $title="Student Promotion";
		$thing='../files/promotion/byOne.php';
		break;
		case 'apppro':
        $title="Applicant problems";
		$thing='../files/applicants/applicant_problems.php';
		break;
		case 'apcrs':
        $title="programme";
		$thing='../files/applicants/programme.php';
		break;
		
		
	case 'regmksOne' :
            $title="Marks";
        	$thing='../files2/Marks/reg_marks.php';
        	break;
        	
	case 'gradate':
        $title="Graduation Cycle"; 
		$thing='../files/Graduation/index.php';
		break;
    		
	case 'graduant':
        $title="Student Graduant"; 
		$thing='gradaution.php';
		break;
		
	case 'graduantList':
        $title="Graduants List";
		$thing='gradautionList.php';  
		break;
		
	case 'graduantstats':
        $title="Graduants Statistics";
		$thing='gradautionStatistics.php';  
		break;
		
	case 'UDGgraduantList':
        $title="UNDERGRADUATE";
		$thing='UDGgradautionList.php';
		break;
		
		case 'DPgraduantList':
        $title="DIPLOMA";
		$thing='DPgradautionList.php';
		break;
		
		
	case 'byspn':
		    $title ="Sponsor management";
		    $thing ="sponsor/sponsor_management.php";
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
        $title="Timetable";
		$thing='../files/Timetable/index.php';
		break;
		
	case 'sptble':
        $title="Special Timetable";
		$thing='../files/Timetable/special.php';
		break;

	case 'addroom':
        $title="Rooms";
		$thing='../files/Block/rooms.php';
		break;
		
	case 'addbloc':
        $title="Bloc";
		$thing='../files/Block/index.php';
		break;
		
	case 'gentables':
        $title="Generate Tables";
		$thing='../files/Timetable/timetables.php';
		break;		
		
	case 'tablehours':
        $title="Define Hours";
		$thing='../files/Timetable/hours.php';
		break;	
		
	case 'vt':
        $title="View Table";
		$thing='../files/Timetable/view_single.php';
		break;		
		
	case 'vt2':
        $title="View Table";
		$thing='../files/Timetable/view_single_special.php';
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
		
		case 'pdgr':
        $title="Degree Printing";
		$thing='../files/Degree/index.php';
		break;
		
		case 'pdgr1':
        $title="Degree Printing";
		$thing='../files/Degree/index_one.php';
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
		$thing='../files/Modules/module_assign_new.php';
		break;
		
		case 'mod_assign_verify':
        $title="Year Modules";
		$thing='../files/Modules/module_assign_verify.php';
		break;
		
		case 'mod_stu':
        $title="Student - Modules";
		$thing='../files/Student/student_modules.php';
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
        $title="Search student";
    	$thing='../files/Student/student_info.php';
    	break;
    	
	    case 'stuspo' :
        $title="Student sponsors";
		$thing='../files/Sponsors/student_sponsor.php';
		break;	
		
    	case 'msing' :
        $title="Marks";
    	$thing='../files/Marks/individual_marks.php';
    	break;

    	case 'regmks' :
        $title="Marks";
    	$thing='../files/Marks/reg_marks_new.php';
    	break;
    	
    	case 'regmks2' :
        $title="Marks";
    	$thing='../files/Marks/push_marks.php';
    	break;
    	
    	case 'cmrks' :
        $title="Marks";
    	$thing='../files/Marks/check_marks.php';
    	break;
    	
    	case 'assess_mod' :
        $title="Assessment";
    	$thing='../files/Assessment/assessment.php';
    	break;
    	
    	case 'transOne' :
        $title="Transcript";
    	$thing='../files/Transcript/transcript.php';
    	break;
    	case 'transAll' :
        $title="Transcript";
    	$thing='../files/Transcript/transcript_all.php';
    	break;
    	
    	case 'transLev' :
        $title="Transcript";
    	$thing='../files/Transcript/level_transcript.php';
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
        $title="Statistics by academic year";
    	$thing='../files/Statistics/acad.php';
    	break;
    	
    	case 'stsacasem' :
        $title="Statistics by academic year";
    	$thing='./semester.php';
    	break;
        //reports
        	case 'stsintake' :
        $title="Statistics by Intake";
    	$thing='../files/Statistics/intakests.php';
    	break;
    	//
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
    	
    	
    	case 'attlist' :
        $title="Attendance List";
    	$thing='../files/Others/attendance.php';
    	break;
    	
    	case 'clsrep' :
        $title="Student Modules";
    	$thing='../files/Others/class_repair.php';
    	break;
    	
    	case 'hcrep' :
        $title="Student Reports";
    	$thing='../files/Student_reports/transferable.php';
    	break;
    	
    	case 'hcrepage' :
        $title="Student Reports";
    	$thing='../files/Student_reports/hec_age_report.php';
    	break;
    	
        case 'exmat' :
        $title="Exam Attendance";
    	$thing='../files/Student/exam_attendance.php';
    	break;
    	
    	//student satisfaction
    	case 'stathecgen' :
        $title="General Statistics";
    	$thing='../files/Student/hec/statistics/general.php';
    	break;
    	
    	case 'stahectype' :
        $title="Statistics by Program type";
    	$thing='../files/Student/hec/statistics/program_type.php';
    	break;
    	
    	case 'stthecfcult' :
        $title="Statistics by Faculty";
    	$thing='../files/Student/hec/statistics/faculty.php';
    	break;
    	
    	case 'stthecsplz' :
        $title="Statistics by Department";
    	$thing='../files/Student/hec/statistics/specialization.php';
    	break;
    	
    	case 'stslevel' :
        $title="Statistics by Level";
    	$thing='../files/Student/hec/statistics/level.php';
    	break;

    	case 'stshecgender' :
        $title="Statistics by Gender";
    	$thing='../files/Student/hec/statistics/gender.php';
    	break;
    	
    	case 'reqs':
            $title="Academic Requests";
        	$thing='../files/Requests/index.php';
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
        case 'existstu':
            $title="Re-Registration";
        	$thing='../files/Re_Registration/register.php';
        	break;
        case 'amdaccept' :
        $title="Applicants";
    	$thing='../files/application/applAccept.php';
    	break;
    	
    	//register department student
    	case 'newstudep':
    	    $title="Applicants";
    	    $thing='../files/Student/register_student_department.php';
    	    break;
    //book
    case 'bkk':
        $title="Applicants";
    	$thing='../files/Faculties/book.php';
    	break;
    //review student
    case 'view_stu':
        $thing="../files/Student/review_student.php";
    	break;
    //application
    case 'apper':
        $title="Applicants Period";
    	$thing='../files/application_period/index.php';
    	break;
    case 'admpaysta':
        $title="Applicants Payed";
    	$thing='../files/application/applicant_payed.php';
    	break;
    case 'new_applicant':
        $title="profile";
		$thing='../files/application/special_apply.php';
		break;
	case 'applst':
        $title="Application List";
		$thing='../files/application/ApplicantList.php';
		break;	
    case 'fdtlst':
        $title="Foundaction List";
		$thing='../files/application/FoundationList.php';
		break;
	case 'rgsrslt':
	    $title="Student List";
		$thing='../files/Student/Registrar_StudentList.php';
		break;
        
    	
    	######### End Teaching ##########

	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
