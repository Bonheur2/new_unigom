<?php
include ('meet/con.php');
$connection=$conn;
class Demo{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function sendFeedback($s, $m){
        $data = array("status"=>$s,"message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function encryptEmail($em) {
        $key = "zjsDDASF#gashcs%";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($em, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    function sendEmail($to, $from, $uni, $fname){
        $encryptedEmail = $this->encryptEmail($to);
        $link = 'https://'.$_SERVER['SERVER_NAME'].'/verify?challenge=' . $encryptedEmail;
        $year = date("Y");
        $subject = 'Email Verification';                                
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                                            
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
                                                            
        // Compose a simple HTML email message
        $message = '<html><head>';
        $message = ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        $message = ' <meta name="x-apple-disable-message-reformatting" />';
        $message = ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $message = ' <meta name="color-scheme" content="light dark" />';
        $message = ' <meta name="supported-color-schemes" content="light dark" />';
        $message = ' <title>Email Verification</title>';
        $message = '<body">';
        $message .= '
                    <div style="padding: 10px;border: 1px solid lightgray;">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            Dear <b>'.$fname.'</b>,
                                            your demo account at '.$uni.' have been created successfully.<br>
                                            Please verify your email to make it active. Open the link provided below.<br><br>
                                            <a href="'.$link.'" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 14px; text-align: center; text-decoration: none; cursor: pointer;">Verify Email</a>
                                        </p>
                                        <br>
                                        <p>Best regards,<br>Admin</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight:bold;">
                                        <hr>
                                        &copy; '.$year.' ITEC. All rights reserved.<br>
                                        Designed by ITEC Ltd<br>
                                        KN 1 Rd, Kigali-Rwanda.<br>
                                        Phone (+250) 788730582
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>';
        $message .= '</body></html>';
        // Sending email
        if(mail($to, $subject, $message, $headers)){
            $this->sendFeedback(200, "");
        }
    }
    function create_account(){
        $fname = trim($_POST['first_name']);
        $lname = trim($_POST['family_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $country = $_POST['country'];
        $pass = trim($_POST['password']);
        $dir = [
                'cost' => 12,
                ];
                
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $udata=$sql->fetch();
            
        $checkAccount = $this->connect->prepare("SELECT * FROM tbl_users WHERE (email='".$email."' OR phone_no='".$phone."') AND status=1");
        $checkAccount->execute();
        if($checkAccount->rowCount()>0){
            $this->sendFeedback(401, "Provided credentials already exists!");
        }
        else{
            $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
            $create = $this->connect->prepare("INSERT into tbl_users(family_name,first_name,email,phone_no,password,role_id,status,country) 
                    VALUES ('".$lname."','".$fname."','".$email."','".$phone."','".$password."',19,2,'".$country."')");
            if($create->execute()){
                $this->sendEmail($email, $udata['email'], $udata['full_name'], $fname);    
            }
            else{
                $this->sendFeedback(401, "Failed to create account!");
            }
        }
    }
}

$demo=new Demo();
$demo->create_account();
?>

