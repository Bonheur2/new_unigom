<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 23){

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
		
		case 'iclass':
        $title="Item Classification";
		$thing='../files/assets/pages/item_class.php';
		break;
		
		case 'brds':
        $title="Brands";
		$thing='../files/assets/pages/brands.php';
		break;
		
		case 'itmz':
        $title="Items";
		$thing='../files/assets/pages/items.php';
		break;
		
		case 'ctgz':
        $title="Item Categories";
		$thing='../files/assets/pages/categories.php';
		break;
		
		case 'areg':
        $title="Registration";
		$thing='../files/assets/pages/registration.php';
		break;
		
		case 'aver':
        $title="Verification";
		$thing='../files/assets/pages/verification.php';
		break;

	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
