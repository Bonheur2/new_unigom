<?php
 require_once "../meet/bind.php";
    if($_SESSION['access'] == false or $_SESSION['email'] == ''){
        echo"<script>window.location.replace('../index?q=true')</script>";
     }
    
    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
    
	case '0' :
        $title="Dashboard";
		$thing='base.php';
		break;


	default :
	    $title="Home";
		$thing ='more.php';
     }

   require_once("../comb/focal.php");
?>
