<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 13){
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case '1' :
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
		
	case 'feescateg' :
        $title="Fees category";
		$thing='../files/Fees/feescateg.php';
		break;

	case 'sponcateg' :
        $title="Fees category";
		$thing='../files/Sponsors/sponsor_categ.php';
		break;

	case 'spnlist' :
        $title="Sponsor list";
		$thing='../files/Sponsors/sponsor.php';
		break;

	case 'prces' :
        $title="Prices";
		$thing='../files/Prices/prices.php';
		break;

	case 'expnce' :
        $title="Expenses";
		$thing='../files/Expenses/expense.php';
		break;

	case 'rednd' :
        $title="Refund";
		$thing='../files/Refund/refund.php';
		break;
		
	case 'stuspo' :
        $title="Student sponsors";
		$thing='../files/Sponsors/student_sponsor.php';
		break;	
	case 'stubyspo' :
        $title="Students by sponsors";
		$thing='../files/Sponsors/students_by_sponsor.php';
		break;
   	case 'byspn':
	   $title ="Sponsor management";
	   $thing ="sponsor/sponsor_management.php";
	   break;		
		
	
	case 'indpayt' :
        $title="Individual payment";
		$thing='../files/Payment/individual_payment.php';
		break;
	
	case 'pytNeg' :
        $title="Payment negotiation";
		$thing='../files/Payment/negociation.php';
		break;
	
	case 'balTrans' :
        $title="Balance Transfer";
		$thing='../files/Payment/transfer.php';
		break;
	
	case 'indInvo' :
        $title="Individual invoice";
		$thing='../files/Invoice/individual_invoice.php';
		break;
	
	case 'multInvo' :
        $title="Invoice by class";
		$thing='../files/Invoice/class_invoice.php';
		break;
		
	case 'CustInvo' :
        $title="Special invoice";
		$thing='../files/Invoice/special_invoice.php';
		break;
		
	//reports
	case 'indreport' :
        $title="Individual financial report";
		$thing='../files/Financial_report/individual_report.php';
		break;
	case 'ptyByclassRpt' :
        $title="Class financial report";
		$thing='../files/Financial_report/class_report.php';
		break;
	case 'sbal' :
        $title="balances";
		$thing='../files/Statistics/finance/balances.php';
		break;
	
	//statistics
	case 'gnlststcs' :
        $title="General statistics";
		$thing='../files/Statistics/finance/general.php';
		break;
	case 'allinvc' :
        $title="All invoices";
		$thing='../files/Statistics/finance/invoices.php';
		break;
	case 'spyt' :
        $title="All payments";
		$thing='../files/Statistics/finance/payments.php';
		break;
	case 'sbal' :
        $title="balances";
		$thing='../files/Statistics/finance/balances.php';
		break;
	case 'invocps' :
        $title="Invoices by campus";
		$thing='../files/Statistics/finance/campus_invoice.php';
		break;
	case 'pytcps' :
        $title="Payments by campus";
		$thing='../files/Statistics/finance/campus_payment.php';
		break;
	case 'sfee' :
        $title="Statistics by fee";
		$thing='../files/Statistics/finance/fee_category.php';
		break;
	case 'sintk' :
        $title="Statistics by intake";
		$thing='../files/Statistics/finance/intake.php';
		break;
    case 'ann' :
        $title="Announcement";
    	$thing='../files/announcements/announcement.php';
    	break;
    case 'det_ann' :
        $title="Announcement";
    	$thing='../files/announcements/det_ann.php';
    	break;
    case 'exmat' :
        $title="Exam Attendance";
    	$thing='../files/Invoice/exam_attendance.php';
    	break;
    	
    case 'cancelled' :
        $title="Cancelled invoices";
		$thing='../files/Statistics/finance/cancelled_invoices.php';
		break;
	case 'cancelled_cnvoices_req' :
        $title="cancelled_cnvoices_req";
		$thing='../files/Statistics/finance/cancelled_invoices_requests.php';
		break;	
	case 're_Activated_nvoices_req' :
        $title="cancelled_cnvoices_req";
		$thing='../files/Statistics/finance/reactivated_invoices_requests.php';
		break;
		
		
		 
		
		
	default :
	    $title="Home";
		$thing ='base.php';
     }
 }
   require_once("../comb/focal.php");
?>
