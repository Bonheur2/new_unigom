<?php
require_once "../meet/bind.php";
require_once "../procx/applk/procx_mnj.php";

// Check session access
if (!isset($_SESSION['access']) || $_SESSION['access'] === false) {
    echo "<script>window.location.replace('/auth')</script>";
    exit;
}

if ($_SESSION['role_id'] == 5) {
    $title = "Home";
    $thing = 'base.php';
    $view = isset($_GET['mis']) ? $_GET['mis'] : '';

    $select_payment = "SELECT i.*, fc.*
        FROM tbl_invoice i
        INNER JOIN tbl_fee_category fc ON i.fee_id = fc.id
        WHERE i.reg_no = :reg_no
          AND i.payment_status = 1
          AND fc.name = :fee_name";
    $cselect_payment = $conn->prepare($select_payment);
    $cselect_payment->execute([
    ':reg_no' => $code,
    ':fee_name' => 'Applicant Fee'
]);
    $row_cselect_payment = $cselect_payment->fetch();

    $payment_status = $row_cselect_payment ? $row_cselect_payment['payment_status'] : 0;

    if ($payment_status != 1 && $view !== '1') {
       
        $view = '1';
        $title = "Home";
        $thing = 'base.php';
    }

    switch ($view) {
        case '1':
            $title = "Home";
            $thing = 'base.php';
            break;
        case 'profile':
            $title = "Profile";
            $thing = '../files/profile/profile.php';
            break;
        case 'pwd':
            $title = "Change Password";
            $thing = '../files/profile/reset.php';
            break;
        case 'perInfo':
            $title = "Personal Information";
            $thing = '../files/application/personal_info.php';
            break;
        case 'prevEdu':
            $title = "Previous Education";
            $thing = '../files/application/previous_education.php';
            break;
        case 'lanPro':
            $title = "Language Proficiency";
            $thing = '../files/application/language_proficiency.php';
            break;
        case 'chAf':
            $title = "Church Affiliation";
            $thing = '../files/application/church_affiliation.php';
            break;
        case 'apcrs':
            $title = "Course";
            $thing = '../files/application/course_picker_new.php';
            break;
        case 'apDocs':
            $title = "Application Documents";
            $thing = '../files/application/documents.php';
            break;
        case 'info':
            $title = "Department Details";
            $thing = '../files/Departments/details.php';
            break;
        case 'ann':
            $title = "Announcement";
            $thing = '../files/announcements/announcement.php';
            break;
        case 'det_ann':
            $title = "Announcement Details";
            $thing = '../files/announcements/det_ann.php';
            break;
        case 'essay':
            $title = "Essays";
            $thing = '../files/application/essays.php';
            break;
        case 'sbt':
            $title = "Submit Application";
            $thing = '../files/application/submit.php';
            break;
        case 'payinfo':
            $title = "Application Payment";
            $thing = '../files/application/payt.php';
            break;
        case 'fasp':
            $title = "Family & Sponsor";
            $thing = '../files/application/family_sponsor.php';
            break;
        default:
            $title = "Home";
            $thing = 'base.php';
            break;
    }
}

// Include the focal file
require_once("../comb/focal.php");
?>
