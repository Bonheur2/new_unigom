<?php
//DIRECTORY_SEPARATOR is a PHP Pre-defined constants:
//(\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define ('SITE_ROOT', dirname(__DIR__));

defined('LIB_PATH') ? null : define ('LIB_PATH',SITE_ROOT.DS.'meet');

$dir = ['cost' => 12];
//load the database configuration first.
require_once __DIR__ . DS . "session.php";
require_once __DIR__ . DS . "con.php";





// $SqlPdo = $conn->prepare("SELECT
//                                 u.id,
//                                 u.first_name,
//                                 u.family_name,
//                                 u.role_id,
//                                 usr.role,
//                                 u.phone_no,
//                                 u.email,
//                                 u.campus_id,
//                                 u.Identification,
//                                 u.prvg,
//                                 dh.dept_id,
//                                 fd.fac_id
//                             FROM
//                                 tbl_users u
//                             INNER JOIN tbl_user_roles usr ON
//                                 usr.role_id = u.role_id
//                             LEFT JOIN tbl_department_hod dh ON
//                                 u.Identification = dh.identification AND dh.lead_end IS NULL
//                             LEFT JOIN tbl_faculty_dean fd ON
//                                 u.Identification = fd.identification AND fd.lead_end IS NULL
//                             WHERE
//                                 u.id = ? AND u.email = ?");

$SqlPdo = $conn->prepare("SELECT
                                u.id,
                                u.first_name,
                                u.family_name,
                                u.role_id,
                                usr.role,
                                u.phone_no,
                                u.email,
                                u.campus_id,
                                u.Identification,
                                u.prvg,
                                u.dept_id,
                                u.fac_id
                            FROM
                                tbl_users u
                            INNER JOIN tbl_user_roles usr ON
                                usr.role_id = u.role_id
                            WHERE
                                u.id = ? AND u.email = ?");
	$SqlPdo->execute([$_SESSION ['acc_id' ], $_SESSION ['email']]);
	$datain = $SqlPdo->rowCount();
	if($datain>0){
    while($GetPdo = $SqlPdo->fetch()){
        $family_name = $GetPdo['family_name'];
        $first_name = $GetPdo['first_name'];
        $acc_id = $GetPdo['id'];
        $role_id = $GetPdo['role_id'];
     
        $faculty = json_decode($GetPdo['fac_id'], true);
        $faculty = array_map('intval', $faculty);
        $faculty = implode(',', $faculty);
        
        $department = json_decode($GetPdo['dept_id'], true);
        $department = array_map('intval', $department);
        $department = implode(',', $department);
     
     
        $u_role = $GetPdo['role'];
        $phone_no = $GetPdo['phone_no'];
        $email = $GetPdo['email'];
        $camp_id = $GetPdo['campus_id'];
        $identification = $GetPdo['Identification'];
        $prvg=$GetPdo['prvg'];
    }
	}
	else {
	    
	   $SqlPdo2 = $conn->prepare("SELECT * FROM tbl_applicants
	   INNER JOIN tbl_student_login ON tbl_student_login.Identification=tbl_applicants.application_code
       INNER JOIN tbl_user_roles ON tbl_user_roles.role_id=tbl_student_login.role_id
       WHERE tbl_student_login.id='".$_SESSION['acc_id']."'");
	   $SqlPdo2->execute();
	
	$datain2 = $SqlPdo2->rowCount();
	if($datain2>0){
	while($GetPdo = $SqlPdo2->fetch()){
	    
	$family_name = $GetPdo['fname'];
     $first_name = $GetPdo['lname'];
     $email = $GetPdo['email'];
     $phone_no = $GetPdo['phone'];
     
     $acc_id = $GetPdo['applicant_id'];
     $code = $GetPdo['code'];
     $role_id = $GetPdo['role_id'];
	}
	} else{
	   
	}
	}
	
	
	    $chkPer = $conn->prepare("
    SELECT * 
    FROM tbl_admittedPRG 
    WHERE Stu_code = :code
");
$chkPer->bindParam(':code', $code, PDO::PARAM_STR);
$chkPer->execute();

$applica = $chkPer->fetch(PDO::FETCH_ASSOC);

$permission = $chkPer->rowCount();


if ($applica) {
    $cump_idii = $applica['cump_id'];
    $prg_ty = $applica['prg_type'];
}
?>