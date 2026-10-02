<?php
require_once "../meet/bind.php";
if ($_SESSION['access'] == false) {
	echo "<script>window.location.replace('/auth')</script>";
}
if ($_SESSION['role_id'] == 2) {

	$thing = 'base.php';
	$view = (isset($_GET['mis']) && $_GET['mis'] != '') ? $_GET['mis'] : '';

	switch ($view) {
		case 'uni':
			$title = "Student Graduant";
			$thing = '../new_files/University/index.php';
			break;
		case 'ups':
			$title = "Student Graduant";
			$thing = '../new_files/Campus/index.php';
			break;
		case 'acdmc':
			$title = "Student Graduant";
			$thing = '../new_files/Academic_year/index.php';
			break;
		case 'fac':
			$title = "Student Graduant";
			$thing = '../new_files/Faculties/index.php';
			break;
		case 'prgty':
			$title = "Student Graduant";
			$thing = '../new_files/Programs/index.php';
			break;
		case 'Dprtm':
			$title = "Student Graduant";
			$thing = '../new_files/Department/index.php';
			break;
		case 'opts':
			$title = "Student Graduant";
			$thing = '../new_files/Options/index.php';
			break;
		case 'lvls':
			$title = "Student Graduant";
			$thing = '../new_files/levels/index.php';
			break;
		case 'appd':
			$title = "Student Graduant";
			$thing = '../new_files/Application_Period/index.php';
			break;
		case 'fcdt':
			$title = "Student Graduant";
			$thing = '../new_files/Faculity_Document/index.php';
			break;
		case 'sbtdap':
			$title = "Student Graduant";
			$thing = '../new_files/Manage_applicant/applications.php';
			break;
		case 'review':
			$title = "Student Graduant";
			$thing = '../new_files/Manage_applicant/review.php';
			break;
		case 'appty':
			$title = "Application Forms";
			$thing = '../new_files/Form_Types/index.php';
			break;
		case 'chstr':
			$title = "Structure des choix";
			$thing = '../new_files/Choice_Structure/index.php';
			break;
		case 'appdc':
			$title = "Structure des choix";
			$thing = '../new_files/formtype_document/index.php';
			break;
		case 'apstp':
			$title = "Approval Setup";
			$thing = '../new_files/Approval_Setup/index.php';
			break;
		case 'apprvw':
			$title = "Approval Setup";
			$thing = '../new_files/Approval_Review/index.php';
			break;
		case 'approval_review':
			$title = "Approval Setup";
			$thing = '../new_files/Approval_Review/review.php';
			break;
		case 'tchunt':
			$title = "Approval Setup";
			$thing = '../new_files/Teaching_Units/index.php';
			break;
		case 'stuInfo':
			$title = "Informations de l'étudiant";
			$thing = '../new_files/Student_Info/index.php';
			break;

	}
}
require_once("../comb/focal.php");
?>