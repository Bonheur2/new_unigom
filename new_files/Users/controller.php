<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(SITE_ROOT.DS."Config".DS."mailer.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class Users{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function generate_identification(){
        $prefix = 'UNGO';

        $sql = $this->connect->prepare("SELECT Identification FROM tbl_users WHERE Identification LIKE :prefix ORDER BY id DESC LIMIT 1");
        $sql->execute([':prefix' => $prefix.'%']);
        $last = $sql->fetch(PDO::FETCH_ASSOC);

        if($last){
            $last_number = (int) substr($last['Identification'], strlen($prefix));
            $next_number = $last_number + 1;
        } else {
            $next_number = 1;
        }

        return $prefix.str_pad($next_number, 5, '0', STR_PAD_LEFT);
    }

    private function generate_password($length = 10){
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $password = '';
        for($i = 0; $i < $length; $i++){
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    private function send_credentials_email($to, $first_name, $family_name, $plain_password, $is_reset = false){
        $subject = $is_reset ? 'Your password has been reset' : 'Your account has been created';
        $intro = $is_reset
            ? 'Your password has been reset. Here are your updated login credentials:'
            : 'An account has been created for you. Here are your login credentials:';

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">
    <div style="background:#12294d;padding:24px;text-align:center;">
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.APP_NAME.'</div>
    </div>
    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Dear '.htmlspecialchars($first_name).' '.htmlspecialchars($family_name).',</p>
      <p style="margin:0 0 20px;color:#475467;font-size:14px;line-height:1.6;">'.$intro.'</p>

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

    public function register(){
        $family_name = trim($_POST['family_name']);
        $first_name = trim($_POST['first_name']);
        $email = trim($_POST['email']);
        $phone_no = trim($_POST['phone_no'] ?? '');
        $role_id = $_POST['role_id'];
        $campus_id = $_POST['campus_id'];

        if($family_name == '' || $first_name == '' || $email == '' || $role_id == '' || $campus_id == ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo json_encode(['status' => 401, 'message' => 'Please provide a valid email address']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT id FROM tbl_users WHERE email = :email");
            $chk->execute([':email' => $email]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'A user with this email already exists']);
                return;
            }

            $plain_password = $this->generate_password();
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            $max_attempts = 5;
            $attempt = 0;
            $new_id = null;
            $identification = null;

            while($new_id === null && $attempt < $max_attempts){
                $attempt++;
                $identification = $this->generate_identification();

                try{
                    $sql = $this->connect->prepare("INSERT INTO tbl_users (Identification, family_name, first_name, email, phone_no, password, role_id, campus_id, status, reg_date)
                                                    VALUES (:identification, :family_name, :first_name, :email, :phone_no, :password, :role_id, :campus_id, 1, NOW())");
                    $sql->execute([
                        ':identification' => $identification,
                        ':family_name' => $family_name,
                        ':first_name' => $first_name,
                        ':email' => $email,
                        ':phone_no' => $phone_no,
                        ':password' => $hashed_password,
                        ':role_id' => $role_id,
                        ':campus_id' => $campus_id
                    ]);
                    $new_id = $this->connect->lastInsertId();
                } catch(PDOException $dup){
                    if($dup->getCode() == 23000 && $attempt < $max_attempts){
                        continue;
                    }
                    throw $dup;
                }
            }

            $this->send_credentials_email($email, $first_name, $family_name, $plain_password, false);

            $roleSql = $this->connect->prepare("SELECT role FROM tbl_user_roles WHERE role_id = :id");
            $roleSql->execute([':id' => $role_id]);
            $roleRow = $roleSql->fetch(PDO::FETCH_ASSOC);

            $campSql = $this->connect->prepare("SELECT camp_full_name FROM tbl_campus WHERE camp_id = :id");
            $campSql->execute([':id' => $campus_id]);
            $campRow = $campSql->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 200,
                'message' => 'User created successfully. Login credentials have been emailed to '.$email,
                'id' => $new_id,
                'identification' => $identification,
                'family_name' => $family_name,
                'first_name' => $first_name,
                'email' => $email,
                'phone_no' => $phone_no,
                'role' => $roleRow ? $roleRow['role'] : '',
                'camp_full_name' => $campRow ? $campRow['camp_full_name'] : ''
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error creating user: '.$e->getMessage()]);
        }
    }

    public function reset_password(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT id, Identification, family_name, first_name, email FROM tbl_users WHERE id = :id");
            $sql->execute([':id' => $id]);
            $user = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$user){
                echo json_encode(['status' => 401, 'message' => 'User not found']);
                return;
            }

            $plain_password = $this->generate_password();
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            $upd = $this->connect->prepare("UPDATE tbl_users SET password = :password WHERE id = :id");
            $upd->execute([':password' => $hashed_password, ':id' => $id]);

            $this->send_credentials_email($user['email'], $user['first_name'], $user['family_name'], $plain_password, true);

            echo json_encode(['status' => 200, 'message' => 'Password has been reset and emailed to '.$user['email']]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error resetting password: '.$e->getMessage()]);
        }
    }
}

$users = new Users();
$action = $_POST['action'] ?? '';

switch($action){
    case 'register':
        $users->register();
        break;
    case 'reset_password':
        $users->reset_password();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
