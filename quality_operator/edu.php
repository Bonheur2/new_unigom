<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 16){

    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	    case 'on' :
        $title="Dashboard";
		$thing='base.php';    
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
    	
    	case 'hcrep' :
        $title="Student Reports";
    	$thing='../files/Student_reports/transferable.php';
    	break;
    	
    	case 'hcrepage' :
        $title="Student Reports";
    	$thing='../files/Student_reports/hec_age_report.php';
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
    	
    	case 'sbox':
            $title="Suggestions box";
        	$thing='../files/Suggestions/index.php';
        	break;

	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
