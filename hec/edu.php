<?php
 require_once "../meet/bind.php";
 if($_SESSION['access'] == false){
    echo"<script>window.location.replace('/auth')</script>";
 }
 if($_SESSION['role_id'] == 22){

    $thing='../files/Student/hec/statistics/general.php';
    $view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';
    
    switch ($view) {
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

	    default :
	    $title="Home";
		$thing ='../files/Student/hec/statistics/general.php';
     }
}
   require_once("../comb/focal.php");
?>
