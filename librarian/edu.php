<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 8){
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
		

	case 'bookList' :
        $title="new prin";
		$thing='books/books.php';
		break;
	case 'revbook':
        $title="book review";
		$thing='books/book_review.php';
		break;
	case 'revdepart':
        $title="book review by department";
		$thing='books/review_by_department.php';
		break;	
	case 'issb' :
        $title="book issue";
		$thing='circulation/bookissue.php';
		break;
	case 'retbook' :
        $title="book return";
		$thing='circulation/return_books.php';
		break;	
	case 'user' :
        $title="User account";
		$thing='user/userList.php';
		break;
    case 'brwdbook' :
        $title="borrowedbooks";
		$thing='circulation/borrowed_books.php';
		break;
	case 'returnbook' :
        $title="returnbooks";
		$thing='circulation/returned_books.php';
		break;
	case 'delabook' :
        $title="delayedbooks";
		$thing='circulation/delayedbooks.php';
		break;
	case 'lostbook' :
        $title="Lostedbooks";
		$thing='circulation/losted_books.php';
		break;
	case 'rptbook' :
        $title="books report";
		$thing='report/book_report.php';
		break;
	case 'sfisBook' :
        $title="books report";
		$thing='staffcirculation/bookissue.php';
		break;
	case 'sfReturnBook' :
        $title="book return";
		$thing='staffcirculation/return_books.php';
		break;
	case 'sfrtbook' :
        $title="returned books";
		$thing='staffcirculation/returned_books.php';
		break;
	case 'sfdebook' :
        $title="delayedbooks";
		$thing='staffcirculation/delayedbooks.php';
		break;
	case 'sflosboo' :
        $title="Lostedbooks";
		$thing='staffcirculation/losted_books.php';
		break;
	
	case 'listbkon' :
        $title="new OnlineBook";
		$thing='onlinebooks/books.php';
		break;
	case 'bolclearreq' :
        $title="book clearance";
		$thing='clearance/request.php';
		break;
		case 'rptbookdep' :
        $title="department report";
		$thing='booksReport/report_department.php';
		break;
		
		case 'rptbookauth' :
        $title="Author book report";
		$thing='booksReport/report_author.php';
		break;
		case 'rptbookdel' :
        $title="Delayed book report";
		$thing='booksReport/delay_books.php';
		break;
		case 'rptbooklost' :
        $title="losted book report";
		$thing='booksReport/lost_books.php';
		break;
		case 'rptbookname' :
        $title="Name Books report";
		$thing='booksReport/report_name.php';
		break;
		case 'reqcl' :
        $title="Request Clearance";
		$thing='Rclearance/request.php';
		break;
		case 'bkslstd' :
        $title="Return Losted book";
		$thing='clearance/return_losted.php';
		break;
	    case 'setf' :
	    $title="Set fines";
	    $thing='Fines/fine.php';
	    break;
	    case 'addEbook' :
	    $title="E-book Add";
	    $thing='onlinebooks/addEBook.php';
	    break;
	     case 'listpapon' :
	    $title="E-paper list";
	    $thing='onlinebooks/listEpaper.php';
	    break;
	     case 'addEpap' :
	    $title="E-paper Add";
	    $thing='onlinebooks/addEPaper.php';
	    break;
	    
	    case 'listEbook' :
	    $title="E Books";
	    $thing='ListEbooks/list2.php';
	    break;
	    case 'barcode' :
	    $title="Books";
	    $thing='books/barcode/index.php';
	    break;
	    case 'unclrbook' :
	    $title="Uncleared Books";
	    $thing='booksReport/uncleared.php';
	    break;
	   case 'rmvfine' :
	    $title="Clear Fine";
	    $thing='clearance/clear_fine.php';
	    break;
	    case 'copies' :
	    $title="Copies";
	    $thing='copy/copies.php';
	    break;
	    case 'lcatin' :
	    $title="Loaction";
	    $thing='booksReport/location_report.php';
	    break;
	    case 'byclass' :
	    $title="Loaction";
	    $thing='booksReport/class_report.php';
	    break;
	    case 'bookDep' :
	    $title="Book Department";
	    $thing='department/book.php';
	    break;
	    
	default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
