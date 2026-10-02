<?php
// Where Google sends the user back after consent.
//
// Three outcomes:
//   1. Email already has a student login  -> sign them in.
//   2. Email is a known applicant with no login -> create the login, sign in.
//   3. Brand new email -> stash the profile and send them to google_complete.php
//      to supply gender + phone, which Google does not provide.

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."google_oauth.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function google_fail($message, $detail = ''){
    if($detail !== ''){
        error_log('[google_oauth] '.$message.' :: '.$detail);
    }
    $url = '/new_files/Create_account/index.php?google_error='.urlencode($message);
    header('Location: '.$url);
    exit;
}

// Google reports a refusal (e.g. the user pressed Cancel) as ?error=
if(isset($_GET['error'])){
    google_fail('Google sign-in was cancelled.', $_GET['error']);
}

$code  = $_GET['code']  ?? '';
$state = $_GET['state'] ?? '';

if($code === ''){
    google_fail('Google did not return an authorisation code.');
}

// Single-use CSRF check, consumed whether or not it matches.
if(!google_verify_state($state)){
    google_fail('Your sign-in session expired. Please try again.');
}

$mode = google_oauth_mode();

$tok = google_exchange_code($code);
if(!$tok['ok']){
    google_fail('Could not complete Google sign-in.', $tok['error']);
}

$profile = google_fetch_profile($tok['access_token']);
if(!$profile['ok']){
    google_fail('Could not read your Google profile.', $profile['error']);
}

// An unverified Google address would let someone claim an email they do not own.
if(!$profile['email_verified']){
    google_fail('Your Google email address is not verified.');
}

$email = trim($profile['email']);

try{
    // --- 1. already has a login? sign in ---
    $sth = $conn->prepare("SELECT id, Identification, role_id, status FROM tbl_student_login WHERE email = :email");
    $sth->execute([':email' => $email]);
    $login = $sth->fetch(PDO::FETCH_ASSOC);

    if($login){
        if((int) $login['status'] !== 1){
            google_fail('This account is not active. Please contact admissions support.');
        }

        $_SESSION['acc_id']         = $login['id'];
        $_SESSION['identification'] = $login['Identification'];
        $_SESSION['role_id']        = $login['role_id'];
        $_SESSION['status']         = 'ON';
        $_SESSION['access']         = true;

        $upd = $conn->prepare("UPDATE tbl_student_login SET last_logged_in = :t WHERE id = :id");
        $upd->execute([':t' => date('Y-m-d H:i:s'), ':id' => $login['id']]);

        header('Location: '.GOOGLE_AFTER_LOGIN_URL);
        exit;
    }

    // --- 2. known applicant but no login yet? create it and sign in ---
    $sthA = $conn->prepare("SELECT applicant_id, application_code, fname, lname FROM tbl_applicants WHERE email = :email");
    $sthA->execute([':email' => $email]);
    $applicant = $sthA->fetch(PDO::FETCH_ASSOC);

    if($applicant){
        // No password is set - this account signs in through Google only.
        $ins = $conn->prepare("INSERT INTO tbl_student_login (Identification, email, password, role_id, status)
                               VALUES (:identification, :email, :password, :role_id, 1)");
        $ins->execute([
            ':identification' => $applicant['application_code'],
            ':email'          => $email,
            ':password'       => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
            ':role_id'        => 4
        ]);

        $upd = $conn->prepare("UPDATE tbl_applicants SET status = 'verified', updated_at = NOW() WHERE applicant_id = :id");
        $upd->execute([':id' => $applicant['applicant_id']]);

        $_SESSION['acc_id']         = $conn->lastInsertId();
        $_SESSION['identification'] = $applicant['application_code'];
        $_SESSION['role_id']        = 4;
        $_SESSION['status']         = 'ON';
        $_SESSION['access']         = true;

        header('Location: '.GOOGLE_AFTER_LOGIN_URL);
        exit;
    }

    // --- 3. brand new: we still need gender + phone ---
    if($mode === 'login'){
        google_fail('No account found for '.$email.'. Please create an account first.');
    }

    $_SESSION['google_pending'] = [
        'email'       => $email,
        'fname'       => $profile['given_name'] !== '' ? $profile['given_name'] : $profile['name'],
        'lname'       => $profile['family_name'],
        'sub'         => $profile['sub'],
        'created_at'  => time()
    ];

    header('Location: '.GOOGLE_COMPLETE_PROFILE_URL);
    exit;

} catch(PDOException $e){
    google_fail('A database error occurred during Google sign-in.', $e->getMessage());
}
