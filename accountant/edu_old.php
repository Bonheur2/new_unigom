<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 14){
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
		
    //hostels & restaurant
    case 'hblock' :
        $title="hostel blocks";
    	$thing='../files/Hostel/block.php';
    	break;
    case 'hrclass' :
        $title="room classification";
    	$thing='../files/Hostel/room_class.php';
    	break;
    case 'hroom' :
        $title="hostel rooms";
    	$thing='../files/Hostel/rooms.php';
    	break;
    case 'hrac' :
        $title="Accomodation";
		$thing='../files/Hostel/accomodation.php';
		break;
    case 'rest' :
        $title="Restarants";
		$thing='../files/Restaurant/restaurant.php';
		break;
	case 'restClass' :
        $title="Restarants";
		$thing='../files/Restaurant/class.php';
		break;
	
    case 'restu' :
        $title="Reistered students";
		$thing='../files/Restaurant/students.php';
		break;
		
		
    case 'feescateg' :
        $title="Fees category";
		$thing='../files/Fees/fee_category_spe.php';
		break;
	case 'prces' :
        $title="Prices";
		$thing='../files/Prices/prices.php';
		break;
    case 'sponcateg' :
        $title="Fees category";
		$thing='../files/Sponsors/sponsor_categ.php';
		break;
	case 'spnlist' :
        $title="Sponsor list";
		$thing='../files/Sponsors/sponsor.php';
		break;
		

	case 'expnce' :
        $title="Expenses";
		$thing='../files/Expenses/expense.php';
		break;
		
	//invoicing 
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
	case 'invTrack' :
        $title="Suivi des factures";
		$thing='../new_files/Invoice_Tracking/index.php';
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
        $title="Re activated_cnvoices_req";
	    $thing='../files/Statistics/finance/reactivated_invoices_requests.php';
		break;
    case 'view_cancelled_cnvoices_req' :
        $title="view_cancelled_cnvoices_req";
	    $thing='../files/Statistics/finance/view_status_cancelled_cnvoices_req.php';
		break;
	case 'view_re_Activated_nvoices_req' :
        $title="view_re_Activated_nvoices_req";
	    $thing='../files/Statistics/finance/view_status_re_Activated_nvoices_req.php';
		break;
		
	//payments
	case 'stuspo' :
        $title="Student sponsors";
		$thing='../files/Sponsors/student_sponsor.php';
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
    case 'spyt' :
        $title="All payments";
    	$thing='../files/Statistics/finance/payments.php';
    	break;
    case 'apppayt' :
        $title="Applicant Payment";
    	$thing='../files/Payment/individual_applicant_payment.php';
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
	//fee management
    case 'feem' :
        $title="Manage Fee";
    	$thing='../files/Fees/fee_category_spe.php';
    	break;
    case 'feemsp':
        $title="Manage Fee Specific";
    	$thing='../files/Fees/fee_category_spec.php';
    	break;
    	
	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
