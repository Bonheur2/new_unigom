<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 7){
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
	case 'acaRank':
        $title="Academic Rank";
		$thing='../files/Staff/rank.php';
		break;
	case 'contype':
        $title="Contract type";
		$thing='../files/Contract/contract_type.php';
		break;	
	case 'staPlac':
        $title="Staff placement type";
		$thing='../files/Staff/placement_type.php';
		break;
	case 'staType':
        $title="Staff promotion type";
		$thing='../files/Staff/promotion_type.php';
		break;	
	case 'stafType':
        $title="Staff type";
		$thing='../files/Staff/staff_type.php';
		break;	
	case 'stf_dept':
        $title="Staff departments";
		$thing='../files/Staff/department.php';
		break;
	case 'stfPost':
        $title="Staff post";
		$thing='../files/Staff/post.php';
		break;
	case 'Leavetype':
        $title="Leave type";
		$thing='../files/Staff/leave_type.php';
		break;
		
    // case 'empList' :
    //     $title="employees list";
    // 	$thing='../files/employees/employees.php';
    // 	break;	
    case 'empList' :
        $title="employees list";
    	$thing='../files/Employees/employees.php';
    	break;
    case 'paylist' :
        $title="Payroll";
    	$thing='../files/Payroll/payroll.php';
    	break;
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    case 'stfgross' :
        $title="Staff gross salary";
    	$thing='../files/Payroll/salary.php';
    	break;
case 'stfHour' :
        $title="Staff Working Hours";
    	$thing='../files/Staff/workhours.php';
    	break;
case 'allStfpay' :
        $title="Staff payroll";
    	$thing='../files/payroll_reports/allstaff.php';
    	break;
case 'allstfBank' :
        $title="Staff payroll";
        $thing='../files/payroll_reports/bank.php';
        break;
case 'indpayl' :
        $title="Staff payroll";
        $thing='../files/payroll_reports/individual.php';
        break;
case 'stfbank':
        $title="Staff bank";
		$thing='../files/Staff/bank.php';
		break;
case 'allStfinfor':
        $title="Staff report";
		$thing='../files/Staff_reports/allstaff.php';
		break;
case 'rptSchool':
        $title="Staff report";
		$thing='../files/Staff_reports/rptSchoolui.php';
		break;
case 'rptCampus':
        $title="Staff report";
		$thing='../files/Staff_reports/rptCampusui.php';
		break;
case 'stafgenderr':
        $title="Staff report";
		$thing='../files/Staff_reports/bygender.php';
		break;
case 'stapostr':
        $title="Staff report";
		$thing='../files/Staff_reports/byposition.php';
		break;
case 'stfbysts':
        $title="Staff report";
		$thing='../files/Staff_reports/bystatus.php';
		break;
case 'stfGenr':
        $title="Staff report";
		$thing='../files/Staff_reports/general.php';
		break;	    	
	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
