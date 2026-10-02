<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 28){

    $thing='base.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
        case 'mbast':
        $title="Student Applicants";
        $thing='index.php';  
        break;
        
        case 'rev_app':
        $title="Applicants";
        $thing='review.php';  
        break;
        

    	######### End Teaching ##########

	    default :
	    $title="Home";
		$thing ='base.php';
     }
}
   require_once("../comb/focal.php");
?>
