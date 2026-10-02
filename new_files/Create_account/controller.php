<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(SITE_ROOT.DS."Config".DS."mailer.php");
if(file_exists(SITE_ROOT.DS."Config".DS."google_config.php")){
    require_once(SITE_ROOT.DS."Config".DS."google_config.php");
}

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class CreateAccount{
    private $connect;
    private $student_role_id = 5;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function generate_application_code(){
        $year_prefix = date('y');

        $sql = $this->connect->prepare("SELECT application_code FROM tbl_applicants WHERE application_code LIKE :prefix ORDER BY applicant_id DESC LIMIT 1");
        $sql->execute([':prefix' => $year_prefix.'UNG%']);
        $last = $sql->fetch(PDO::FETCH_ASSOC);

        if($last){
            $last_number = (int) substr($last['application_code'], -4);
            $next_number = $last_number + 1;
        } else {
            $next_number = 1;
        }

        return $year_prefix.'UNG'.str_pad($next_number, 4, '0', STR_PAD_LEFT);
    }

    private function store_verification_token($email, $token){
        // one active token per email - replace any existing one
        $del = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email = :email");
        $del->execute([':email' => $email]);

        $sql = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 30 MINUTE))");
        $sql->execute([':email' => $email, ':token' => $token]);
    }

    private function send_verification_email($to, $fname, $lname, $token){
        $subject = 'Verify your email - '.$token;

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">
    <div style="background:#12294d;padding:24px;text-align:center;">
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.APP_NAME.'</div>
    </div>
    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Dear '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>
      <p style="margin:0 0 20px;color:#475467;font-size:14px;line-height:1.6;">
        Thank you for creating an account. Use the verification code below to confirm your email address.
      </p>

      <div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;text-align:center;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;letter-spacing:.03em;text-transform:uppercase;margin-bottom:6px;">Verification Code</div>
        <div style="color:#12294d;font-size:22px;font-weight:700;letter-spacing:.04em;">'.htmlspecialchars($token).'</div>
      </div>

      <p style="margin:0 0 20px;color:#475467;font-size:13px;line-height:1.6;">
        This code expires in <strong>30 minutes</strong>. If you did not request this, you can safely ignore this email.
      </p>

      <p style="margin:0;color:#1d2939;font-size:14px;">Regards,<br><strong>'.APP_NAME.'</strong></p>
    </div>
    <div style="background:#f9fafb;padding:14px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>
  </div>
</div>';

        send_mail($to, $subject, $html);
    }

    private function send_credentials_email($to, $fname, $lname, $identification, $plain_password){
        $subject = 'Your account is ready';

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">
    <div style="background:#12294d;padding:24px;text-align:center;">
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.APP_NAME.'</div>
    </div>
    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Dear '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>
      <p style="margin:0 0 20px;color:#475467;font-size:14px;line-height:1.6;">
        Your email has been verified and your account is ready. Here are your login credentials:
      </p>

      <div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;text-transform:uppercase;letter-spacing:.03em;margin-bottom:4px;">Username (Email)</div>
        <div style="color:#12294d;font-size:18px;font-weight:700;margin-bottom:14px;">'.htmlspecialchars($to).'</div>
        <div style="color:#667085;font-size:12px;text-transform:uppercase;letter-spacing:.03em;margin-bottom:4px;">Password</div>
        <div style="color:#12294d;font-size:18px;font-weight:700;">'.htmlspecialchars($plain_password).'</div>
      </div>

      <p style="margin:0 0 20px;color:#475467;font-size:13px;line-height:1.6;">
        For your security, please log in and change your password as soon as possible.
      </p>

      <p style="margin:0;color:#1d2939;font-size:14px;">Regards,<br><strong>'.APP_NAME.'</strong></p>
    </div>
    <div style="background:#f9fafb;padding:14px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>
  </div>
</div>';

        send_mail($to, $subject, $html);
    }

    public function create_account(){
        $lname = trim($_POST['lname']);
        $mname = trim($_POST['mname'] ?? '');
        $fname = trim($_POST['fname']);
        $gender = $_POST['gender'];
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        if($lname == '' || $fname == '' || $gender == '' || $phone == '' || $email == ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo json_encode(['status' => 401, 'message' => 'Please provide a valid email address']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT applicant_id FROM tbl_applicants WHERE email = :email");
            $chk->execute([':email' => $email]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'An account with this email already exists']);
                return;
            }

            $chkLogin = $this->connect->prepare("SELECT id FROM tbl_student_login WHERE email = :email");
            $chkLogin->execute([':email' => $email]);
            if($chkLogin->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'An account with this email already exists']);
                return;
            }

            $max_attempts = 5;
            $attempt = 0;
            $new_id = null;
            $application_code = null;

            while($new_id === null && $attempt < $max_attempts){
                $attempt++;
                $application_code = $this->generate_application_code();

                try{
                    $sql = $this->connect->prepare("INSERT INTO tbl_applicants (application_code, lname, mname, fname, gender, phone, email, status, created_at, updated_at)
                                                    VALUES (:application_code, :lname, :mname, :fname, :gender, :phone, :email, 'pending', NOW(), NOW())");
                    $sql->execute([
                        ':application_code' => $application_code,
                        ':lname' => $lname,
                        ':mname' => $mname,
                        ':fname' => $fname,
                        ':gender' => $gender,
                        ':phone' => $phone,
                        ':email' => $email
                    ]);
                    $new_id = $this->connect->lastInsertId();
                } catch(PDOException $dup){
                    if($dup->getCode() == 23000 && $attempt < $max_attempts){
                        continue;
                    }
                    throw $dup;
                }
            }

            $this->store_verification_token($email, $application_code);
            $this->send_verification_email($email, $fname, $lname, $application_code);

            echo json_encode([
                'status' => 200,
                'message' => 'Account created. Please check your email for the verification code.',
                'email' => $email
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error creating account: '.$e->getMessage()]);
        }
    }

    public function send_code(){
        $email = trim($_POST['email']);

        if($email == ''){
            echo json_encode(['status' => 401, 'message' => 'Email is required']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT applicant_id, fname, lname, application_code FROM tbl_applicants WHERE email = :email");
            $sql->execute([':email' => $email]);
            $applicant = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$applicant){
                echo json_encode(['status' => 401, 'message' => 'No account found with this email']);
                return;
            }

            $this->store_verification_token($email, $applicant['application_code']);
            $this->send_verification_email($email, $applicant['fname'], $applicant['lname'], $applicant['application_code']);

            echo json_encode(['status' => 200, 'message' => 'A new verification code has been sent to your email']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error sending code: '.$e->getMessage()]);
        }
    }

    public function verify_email(){
        $email = trim($_POST['email']);
        $token = trim($_POST['token']);

        if($email == '' || $token == ''){
            echo json_encode(['status' => 401, 'message' => 'Email and code are required']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT id FROM email_verification_tokens WHERE email = :email AND token = :token AND expires_at >= NOW()");
            $sql->execute([':email' => $email, ':token' => $token]);
            $tokenRow = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$tokenRow){
                echo json_encode(['status' => 401, 'message' => 'Invalid or expired verification code']);
                return;
            }

            $applicantSql = $this->connect->prepare("SELECT applicant_id, application_code, fname, lname FROM tbl_applicants WHERE email = :email");
            $applicantSql->execute([':email' => $email]);
            $applicant = $applicantSql->fetch(PDO::FETCH_ASSOC);

            if(!$applicant){
                echo json_encode(['status' => 401, 'message' => 'Account not found']);
                return;
            }

            $chkLogin = $this->connect->prepare("SELECT id FROM tbl_student_login WHERE email = :email");
            $chkLogin->execute([':email' => $email]);

            if($chkLogin->rowCount() == 0){
                $plain_password = $applicant['application_code'];
                $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

                $insLogin = $this->connect->prepare("INSERT INTO tbl_student_login (Identification, email, password, role_id, status) VALUES (:identification, :email, :password, :role_id, 1)");
                $insLogin->execute([
                    ':identification' => $applicant['application_code'],
                    ':email' => $email,
                    ':password' => $hashed_password,
                    ':role_id' => $this->student_role_id
                ]);

                $updApplicant = $this->connect->prepare("UPDATE tbl_applicants SET status = 'verified', updated_at = NOW() WHERE applicant_id = :id");
                $updApplicant->execute([':id' => $applicant['applicant_id']]);

                $this->send_credentials_email($email, $applicant['fname'], $applicant['lname'], $applicant['application_code'], $plain_password);
            }

            $del = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email = :email");
            $del->execute([':email' => $email]);

            echo json_encode(['status' => 200, 'message' => 'Email verified successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error verifying email: '.$e->getMessage()]);
        }
    }

    /**
     * Finishes a Google sign-up. The name and email come from the verified
     * Google profile held in the session (never from the POST, so they cannot
     * be tampered with); only gender, phone and an editable surname are taken
     * from the form.
     *
     * No verification code is needed - Google already confirmed the address -
     * and no password is set, because these accounts sign in through Google.
     */
    public function google_complete(){
        $pending = $_SESSION['google_pending'] ?? null;

        if(!$pending || empty($pending['email'])){
            echo json_encode(['status' => 401, 'message' => 'Your Google sign-in expired. Please start again.']);
            return;
        }

        if((time() - ($pending['created_at'] ?? 0)) > 1800){
            unset($_SESSION['google_pending']);
            echo json_encode(['status' => 401, 'message' => 'Your Google sign-in expired. Please start again.']);
            return;
        }

        $email  = trim($pending['email']);
        $fname  = trim($pending['fname']);
        $lname  = trim($_POST['lname'] ?? $pending['lname']);
        $gender = $_POST['gender'] ?? '';
        $phone  = trim($_POST['phone'] ?? '');

        if($lname === '' || $fname === '' || $gender === '' || $phone === ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if(!in_array($gender, ['M', 'F'], true)){
            echo json_encode(['status' => 401, 'message' => 'Please choose a valid gender']);
            return;
        }

        try{
            // Re-check here as well as in the callback: the two run in separate
            // requests, so an account could have appeared in between.
            $chkLogin = $this->connect->prepare("SELECT id FROM tbl_student_login WHERE email = :email");
            $chkLogin->execute([':email' => $email]);
            if($chkLogin->rowCount() > 0){
                unset($_SESSION['google_pending']);
                echo json_encode(['status' => 401, 'message' => 'An account with this email already exists. Please sign in instead.']);
                return;
            }

            $chk = $this->connect->prepare("SELECT applicant_id, application_code FROM tbl_applicants WHERE email = :email");
            $chk->execute([':email' => $email]);
            $existing = $chk->fetch(PDO::FETCH_ASSOC);

            if($existing){
                $applicant_id     = $existing['applicant_id'];
                $application_code = $existing['application_code'];

                $upd = $this->connect->prepare("UPDATE tbl_applicants SET lname = :lname, fname = :fname,
                                                gender = :gender, phone = :phone, status = 'verified', updated_at = NOW()
                                                WHERE applicant_id = :id");
                $upd->execute([
                    ':lname' => $lname, ':fname' => $fname, ':gender' => $gender,
                    ':phone' => $phone, ':id' => $applicant_id
                ]);
            } else {
                $max_attempts = 5;
                $attempt = 0;
                $applicant_id = null;
                $application_code = null;

                while($applicant_id === null && $attempt < $max_attempts){
                    $attempt++;
                    $application_code = $this->generate_application_code();

                    try{
                        $sql = $this->connect->prepare("INSERT INTO tbl_applicants (application_code, lname, mname, fname, gender, phone, email, status, created_at, updated_at)
                                                        VALUES (:application_code, :lname, '', :fname, :gender, :phone, :email, 'verified', NOW(), NOW())");
                        $sql->execute([
                            ':application_code' => $application_code,
                            ':lname' => $lname,
                            ':fname' => $fname,
                            ':gender' => $gender,
                            ':phone' => $phone,
                            ':email' => $email
                        ]);
                        $applicant_id = $this->connect->lastInsertId();
                    } catch(PDOException $dup){
                        if($dup->getCode() == 23000 && $attempt < $max_attempts){
                            continue;
                        }
                        throw $dup;
                    }
                }

                if($applicant_id === null){
                    echo json_encode(['status' => 500, 'message' => 'Could not allocate an application code. Please try again.']);
                    return;
                }
            }

            // Random unusable password: this account authenticates via Google.
            $insLogin = $this->connect->prepare("INSERT INTO tbl_student_login (Identification, email, password, role_id, status)
                                                 VALUES (:identification, :email, :password, :role_id, 1)");
            $insLogin->execute([
                ':identification' => $application_code,
                ':email'          => $email,
                ':password'       => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                ':role_id'        => $this->student_role_id
            ]);

            $_SESSION['acc_id']         = $this->connect->lastInsertId();
            $_SESSION['identification'] = $application_code;
            $_SESSION['role_id']        = $this->student_role_id;
            $_SESSION['status']         = 'ON';
            $_SESSION['access']         = true;
            unset($_SESSION['google_pending']);

            $this->send_welcome_email($email, $fname, $lname, $application_code);

            echo json_encode([
                'status'   => 200,
                'message'  => 'Your account is ready. Signing you in...',
                'redirect' => defined('GOOGLE_AFTER_LOGIN_URL') ? GOOGLE_AFTER_LOGIN_URL : '/applicant/edu?mis=1'
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error creating account: '.$e->getMessage()]);
        }
    }

    /**
     * Welcome mail for a Google account. Deliberately does NOT contain a
     * password - there is none - so it states how to sign back in instead.
     */
    private function send_welcome_email($to, $fname, $lname, $application_code){
        if(!function_exists('send_mail')){
            error_log('[create_account] send_mail() undefined - welcome email skipped');
            return false;
        }

        $subject = 'Your account is ready';

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">
    <div style="background:#12294d;padding:24px;text-align:center;">
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.APP_NAME.'</div>
    </div>
    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Dear '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>
      <p style="margin:0 0 20px;color:#475467;font-size:14px;line-height:1.6;">
        Your account has been created using your Google address. You can start your application right away.
      </p>

      <div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;text-align:center;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;letter-spacing:.03em;text-transform:uppercase;margin-bottom:6px;">Your application code</div>
        <div style="color:#12294d;font-size:22px;font-weight:700;letter-spacing:.04em;">'.htmlspecialchars($application_code).'</div>
      </div>

      <p style="margin:0 0 20px;color:#475467;font-size:13px;line-height:1.6;">
        To sign in again, use the <strong>Continue with Google</strong> button on the login page &mdash;
        there is no separate password for this account.
      </p>

      <p style="margin:0;color:#1d2939;font-size:14px;">Regards,<br><strong>'.APP_NAME.'</strong></p>
    </div>
    <div style="background:#f9fafb;padding:14px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>
  </div>
</div>';

        try {
            $sent = send_mail($to, $subject, $html);
            error_log('[create_account] welcome email '.($sent ? 'sent' : 'FAILED').' to '.$to);
            return (bool) $sent;
        } catch (Exception $e) {
            error_log('[create_account] welcome email exception: '.$e->getMessage());
            return false;
        }
    }

    public function create_password(){
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if($email == '' || $password == ''){
            echo json_encode(['status' => 401, 'message' => 'Email and password are required']);
            return;
        }

        if(strlen($password) < 5){
            echo json_encode(['status' => 401, 'message' => 'Password must be at least 5 characters']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT id FROM tbl_student_login WHERE email = :email");
            $sql->execute([':email' => $email]);
            $login = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$login){
                echo json_encode(['status' => 401, 'message' => 'Account not found. Please verify your email first.']);
                return;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $upd = $this->connect->prepare("UPDATE tbl_student_login SET password = :password WHERE email = :email");
            $upd->execute([':password' => $hashed_password, ':email' => $email]);

            echo json_encode(['status' => 200, 'message' => 'Password updated successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating password: '.$e->getMessage()]);
        }
    }
}

$account = new CreateAccount();
$action = $_POST['action'] ?? '';

switch($action){
    case 'create_account':
        $account->create_account();
        break;
    case 'send_code':
        $account->send_code();
        break;
    case 'verify_email':
        $account->verify_email();
        break;
    case 'create_password':
        $account->create_password();
        break;
    case 'google_complete':
        $account->google_complete();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
