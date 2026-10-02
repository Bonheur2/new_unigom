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

    switch ($view) {
        case '1':
            $title = "Home";
            $thing = 'base.php';
            break;
        case 'profile':
            $title = "Profile";
            $thing = '../files/profile/profile.php';
            break;
        case 'newapplicant':
            $title = "newapplicant";
            $thing = '../new_files/Application_form/new_appliaction.php';
            break;
        case 'Postgraduate':
            $title = "Postgraduate";
            $thing = '../new_files/Postgraduate/index.php';
            break;
        case 'Master':
            $title = "Master";
            $thing = '../new_files/Masters/index.php';
            break;
        case 'domaine':
            $title = "Change of Domain";
            $thing = '../new_files/Change_Domain/index.php';
            break;
        case 'Transifer':
            $title = "Transifer";
            $thing = '../new_files/Transfer_Student/index.php';
            break;
        case 'integration':
            $title = "Integration";
            $thing = '../new_files/integration/index.php';
            break;    
        case 'review':
            $title = "My Application";
            $thing = '../new_files/Application_form/review.php';
            break;
        case 'profile':
            $title = "Profile";
            $thing = '../files/profile/profile.php';
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
