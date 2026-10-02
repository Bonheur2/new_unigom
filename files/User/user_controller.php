<?php
include ('../../meet/con.php');
$connection=$conn;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class User{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
    function generate_password() {
      $pass = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
      return $pass;
    }
    function sendFeedback($s, $m){
        $data = array("status"=>$s,"message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function send_verification_code(){
        $email=$_POST['email'];
        $stmt0 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
        $stmt0->execute();
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_users WHERE email='".$email."'");
        $stmt->execute();
        if($stmt->rowCount()!=0){
            $appData = $stmt->fetch();
            $token=$this->generate_password();
            $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES ('".$email."','".$token."','".$expirationTime."')");
            $stmt5->execute();
            
            $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
            $sql->execute();
            $data=$sql->fetch();
            $year = date("Y");
            $to = $email;
            $subject = 'Verification Code';
            $from = "stumis";
                            
            // To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            
            // Create email headers
            $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
                            
            // Compose a simple HTML email message
            $message2 = '<html><head>';
            $message2 = ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
            $message2 = ' <meta name="x-apple-disable-message-reformatting" />';
            $message2 = ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            $message2 = ' <meta name="color-scheme" content="light dark" />';
            $message2 = ' <meta name="supported-color-schemes" content="light dark" />';
            $message2 = ' <title>'.$data['full_name'].'</title>';
            $message2 = '<body">';
            $message2 .= '
                <div style="padding: 10px;border: 1px solid lightgray; text-align: center;width: 500px;">
                <table>
                <tbody>
                    <tr>
                       <td>
                           <p>Dear <b>'.$appData['first_name'].'</b></p><br>your email verification code is
                           <p style="font-size:18px;"><b>'.$token.'</b></p>
                           <br>
                           <p><b>!! Please remember that your verification code will no longer be valid after 1 hour if it is not utilized.</b></p>
                       </td>
                    </tr>
                       
                    <tr>
                        <td>
                            <hr>
                            &copy; '.$year.' NJALA UNIVERSITY. All rights reserved.<br>
                            Phone (+232) 78701222
                        </td>
                    </tr>
                </tbody>
               </table> 
               </div>';
            $message2 .= '</body></html>';
            
                // Sending email
                if(mail($to, $subject, $message2, $headers)){
                    $data = array("status"=>"200","message" => "Verification code sent!!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
        }
        else{
                    $data = array("status"=>"401","message" => "No Data found");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
        }
    }
    
    function verify_email(){
        $token=$_POST['token'];
        $dt = date('Y-m-d H:i:s');
        $sth = $this->connect->prepare("SELECT email FROM email_verification_tokens WHERE token='".$token."' AND expires_at>='".$dt."'");
        $sth->execute();
        if ($sth->rowCount() != 0) {
            $res = $sth->fetch();
            $email=$res['email'];
            $stmt2 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
            if($stmt2->execute()){
                $data = array("status"=>"200");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
            else{
                $data = array("status"=>"500");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;  
            }
        }
        else{
                $data = array("status"=>"401");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
    	}  
    }
    
    
    // function reset_password(){
    //     $pass =$_POST['password'];
    //     $email = $_POST['email'];
    //       $dir = [
    //             'cost' => 12,
    //         ];
    //     $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
    //     $stmt = $this->connect->prepare("UPDATE tbl_users SET password='".$password."' WHERE email='".$email."'");
    //     if($stmt->execute()){
    //         $data = array("status"=>"200");
    //         $jsonData = json_encode($data);
    //         header('Content-Type: application/json');
    //         echo $jsonData; 
    //     }else{
    //         $data = array("status"=>"500");
    //         $jsonData = json_encode($data);
    //         header('Content-Type: application/json');
    //         echo $jsonData;  
    //     }
    // }
    
    function sendEmailReset($to, $from, $uni, $pass, $fname){
        $year = date("Y");
        $subject = 'User Account';                                
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
        $message = ' <title>'.$uni.'</title>';
        $message = '<body">';
        $message .= '
                    <div style="padding: 10px;border: 1px solid lightgray;">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            Dear <b>'.$fname.'</b>,
                                            your account passsword at '.$uni.' have been reset successfully.
                                        </p>
                                        <p>Your new password is <b>'.$pass.'</p><br>
                                        <p>Best regards,<br>Admin</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight:bold;">
                                        <hr>
                                        &copy; '.$year.' NJALA UNIVERSITY . All rights reserved.<br>
                                        Phone (+232) 78701222
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>';
        $message .= '</body></html>';
        // Sending email
        if(mail($to, $subject, $message, $headers)){
            $this->sendFeedback(200, "Data saved successfully!");
        }
    }
    
    function force_reset_password(){
        $user = $_POST['id'];
        $pass = $this->generate_password();
        // $pass = "123";

        $dir = [
                'cost' => 12,
                ];
                
        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $udata = $sql->fetch();
        
        $getUserData = $this->connect->prepare("SELECT * FROM tbl_users WHERE id='".$user."'");
        $getUserData->execute();
        $userData = $getUserData->fetch();
        
        $first_name = $userData['first_name'];
        $email = $userData['email'];
        $phone = trim($userData['phone_no'], " ");
        $smsSender = $udata['short_name'];
        $message = 'Dear '.$first_name.', your account passsword at Njala STUMIS have been reset successfully. Your new password is '.$pass.'. Best regards, Admin';
        
        $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
        $resetPassword = $this->connect->prepare("UPDATE tbl_users SET password = ? WHERE id = ?");
        if($resetPassword->execute([$password, $user])){
            // $data = array(
            //     "sender" => "$smsSender",
            //     "recipients" => "$phone",
            //     "message" => "$message",
            // );
            // $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
            // $data = http_build_query($data);
            // $username = "twagiramungus";
            // $password = "M00dle!!@@";
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            // curl_setopt($ch, CURLOPT_POST, true);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // $result = curl_exec($ch);
            // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // curl_close($ch);
            // $result;
            // $httpcode;
            
            $this->sendEmailReset($email, "humanresources@misnjala.edu.sl", $udata['full_name'], $pass, $first_name); 
        }
        else{
            $this->sendFeedback(401, "Failed to reset password!");
        }
    }

    function sendEmail($to, $from, $uni, $pass, $fname){
        $year = date("Y");
        $subject = 'User Account';                                
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
        $message = ' <title>'.$uni.'</title>';
        $message = '<body">';
        $message .= '
                    <div style="padding: 10px;border: 1px solid lightgray;">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            Dear <b>'.$fname.'</b>,
                                            your account at '.$uni.' have been created successfully.
                                        </p>
                                        <p>Your account password is <b>'.$pass.'</p><br>
                                        <p>Best regards,<br>Admin</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight:bold;">
                                        <hr>
                                        &copy; '.$year.' NJALA UNIVERSITY . All rights reserved.<br>
                                        Phone (+232) 78701222
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>';
        $message .= '</body></html>';
        // Sending email
        if(mail($to, $subject, $message, $headers)){
            $this->sendFeedback(200, "Data saved successfully!");
        }
    }
    function create_account(){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $campus = (int)$_POST['campus'];
        // $fac = $_POST['fac_id'];
        // $dept = $_POST['dept_id'];
        $pass = $this->generate_password();

        $dir = [
                'cost' => 12,
                ];
                
        
        $fac = array();
        $dept = array();
        foreach($_POST['fac_id'] as $f){
            $fac[] = $f;
        }
        
        foreach($_POST['dept_id'] as $d){
            $dept[] = $d;
        }
        
        $facs = json_encode($fac);
        $deps = json_encode($dept);
        
        
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $udata=$sql->fetch();
            
        if($_POST['staff']==1){
            //check for duplicates
            $checkExistsStaff = $this->connect->prepare("SELECT * FROM tbl_staff WHERE email='".$email."' OR PhoneNumber='".$phone."'");
            $checkExistsStaff->execute();
            
            if($checkExistsStaff->rowCount()>0){
                $checkExistsAccount = $this->connect->prepare("SELECT * FROM tbl_users WHERE email='".$email."' OR phone_no='".$phone."'");
                $checkExistsAccount->execute();
                if($checkExistsAccount->rowCount()>0){
                    $this->sendFeedback(401, "Account already exists!");
                }
                else{
                    //create account
                    $userData=$checkExistsStaff->fetch();
                    $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
                    $create = $this->connect->prepare("INSERT into tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id,campus_id,fac_id,dept_id) 
                    VALUES ('".$userData['staff_id']."','".$userData['staff_family_name']."','".$userData['staff_first_name']."','".$email."','".$phone."','".$password."','".$role."','".$campus."','".$facs."','".$deps."')");
                    if($create->execute()){
                        $this->sendEmail($email,"stumis", $udata['full_name'],$pass, $userData['staff_family_name']);    
                    }
                    else{
                        $this->sendFeedback(401, "Failed to create account!");
                    }
                }
            }
            else{
                //generate staff ID and create account
                $subcode = $udata['short_name'];
                $char = strlen($subcode);
                $checkcode = $this->connect->prepare("SELECT staff_id FROM tbl_staff WHERE staff_id like '".$subcode."%' ORDER BY staff_id DESC limit 1");
                $checkcode->execute();
                $codeData=$checkcode->fetch();
                $prevSuffix=substr($codeData['staff_id'], $char);
                if($prevSuffix<9){
                    $prevSuffix++;
                    $suffix='000'.$prevSuffix;
                }
                elseif($prevSuffix<99){
                    $prevSuffix++;
                    $suffix='00'.$prevSuffix;
                }
                elseif($prevSuffix<999){
                    $prevSuffix++;
                    $suffix='0'.$prevSuffix;
                }
                else{
                    $prevSuffix++;
                    $suffix=$prevSuffix;
                }
                $staff_id=$subcode.''.$suffix;
                $stmt = $this->connect->prepare("INSERT INTO tbl_staff(staff_id,staff_family_name,staff_first_name,email,campus,PhoneNumber) 
                          	VALUES('".$staff_id."','".$lname."','".$fname."','".$email."','".$campus."','".$phone."')");

                $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
                $create = $this->connect->prepare("INSERT into tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id,campus_id,fac_id,dept_id) 
                VALUES ('".$staff_id."','".$lname."','".$fname."','".$email."','".$phone."','".$password."','".$role."','".$campus."','".$facs."','".$deps."')");
                if($stmt->execute()){
                    $create->execute();
                    $this->sendEmail($email, "stumis", $udata['full_name'], $pass, $fname);
                }
                else{
                    $this->sendFeedback(401, "Failed to create account!");
                }
                    
            }
        }
        else{
            $checkExistsAccount = $this->connect->prepare("SELECT * FROM tbl_users WHERE email='".$email."' OR phone_no='".$phone."'");
            $checkExistsAccount->execute();

            if($checkExistsAccount->rowCount()>0){
                $this->sendFeedback(401, "Account already exists!");
            }
            else{
                //create account
                $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
                $create = $this->connect->prepare("INSERT into tbl_users(family_name,first_name,email,phone_no,password,role_id,campus_id,fac_id,dept_id) 
                    VALUES ('".$lname."','".$fname."','".$email."','".$phone."','".$password."','".$role."','".$campus."','".$facs."','".$deps."')");

                if($create->execute()){
                    $this->sendEmail($email, "stumis", $udata['full_name'], $pass, $fname);    
                }
                else{
                    $this->sendFeedback(401,"Failed to create account!");
                }
            }
        }
    }
    //update account    
    function update_account(){
        $id = $_POST['id'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $campus = $_POST['campus'];
        $position=$_POST['e_position'];
        $fac = array();
        $dept = array();
        foreach($_POST['fac_id'] as $f){
            $fac[] = $f;
        }
        
        foreach($_POST['dept_id'] as $d){
            $dept[] = $d;
        }
        
        $facs = json_encode($fac);
        $deps = json_encode($dept);
        
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $udata=$sql->fetch();
        $up=$this->connect->prepare("UPDATE tbl_staff SET Post='".$position."',staff_family_name='".$lname."',staff_first_name='".$fname."' WHERE email='".$email."'");
        $up->execute();
        if($_POST['staff']==1){
            //check for duplicates
            $checkExistsStaff = $this->connect->prepare("SELECT * FROM tbl_staff WHERE email='".$email."' OR PhoneNumber='".$phone."'");
            $checkExistsStaff->execute();
            
            if($checkExistsStaff->rowCount()>0){
                $checkExistsAccount = $this->connect->prepare("SELECT * FROM tbl_users WHERE (email='".$email."' OR phone_no='".$phone."') AND id!='".$id."'");
                $checkExistsAccount->execute();
                if($checkExistsAccount->rowCount()>0){
                    $this->sendFeedback(401, "Account already exists!");
                }
                else{
                    //update account
                    $userData=$checkExistsStaff->fetch();
                    $update = $this->connect->prepare("UPDATE tbl_users SET Identification = '".$userData['staff_id']."',
                                                                            family_name = '".$lname."',
                                                                            first_name = '".$fname."',
                                                                            email = '".$email."',
                                                                            phone_no = '".$phone."',
                                                                            campus_id = '".$campus."',
                                                                            fac_id = '".$facs."',
                                                                            dept_id = '".$deps."',
                                                                            role_id = '".$role."' WHERE id='".$id."'");
                    
                    if($update->execute()){
                      
                        $this->sendFeedback(200,"Account updated successfully");    
                    }
                    else{
                        $this->sendFeedback(401, "Failed to update account!");
                    }
                }
            }
            else{
                //generate staff ID and update account
                $subcode=$udata['short_name'];
                $char = strlen($subcode);
                $checkcode = $this->connect->prepare("SELECT staff_id FROM tbl_staff WHERE staff_id like '".$subcode."%' ORDER BY staff_id DESC limit 1");
                $checkcode->execute();
                $codeData=$checkcode->fetch();
                $prevSuffix=$checkcode->rowCount()>0?substr($codeData['staff_id'],$char):0;

                if($prevSuffix<9){
                    $prevSuffix++;
                    $suffix='000'.$prevSuffix;
                }
                elseif($prevSuffix<99){
                    $prevSuffix++;
                    $suffix='00'.$prevSuffix;
                }
                elseif($prevSuffix<999){
                    $prevSuffix++;
                    $suffix='0'.$prevSuffix;
                }
                else{
                    $prevSuffix++;
                    $suffix=$prevSuffix;
                }
                $staff_id=$subcode.''.$suffix;
                $stmt = $this->connect->prepare("INSERT INTO tbl_staff(staff_id,staff_family_name,staff_first_name,email,campus,PhoneNumber) 
                          	VALUES('".$staff_id."','".$lname."','".$fname."','".$email."','".$campus."','".$phone."')");

                $update = $this->connect->prepare("UPDATE tbl_users SET Identification = '".$staff_id."',
                                                                        family_name = '".$lname."',
                                                                        first_name = '".$fname."',
                                                                        email = '".$email."',
                                                                        phone_no = '".$phone."',
                                                                        campus_id = '".$campus."',
                                                                        fac_id = '".$facs."',
                                                                        dept_id = '".$deps."',
                                                                        role_id = '".$role."' WHERE id='".$id."'");
                if($stmt->execute()){
                    $update->execute();
                    
                    $this->sendFeedback(200,"Account updated successfully");
                    
                }
                else{
                    $this->sendFeedback(401, "Failed to update account!");
                }
                    
            }
        }
        else{
            $checkExistsAccount = $this->connect->prepare("SELECT * FROM tbl_users WHERE (email='".$email."' OR phone_no='".$phone."') AND id!='".$id."'");
            $checkExistsAccount->execute();
            if($checkExistsAccount->rowCount()>0){
                $this->sendFeedback(401, "Account already exists!");
            }
            else{
                //update account
                $update = $this->connect->prepare("UPDATE tbl_users SET family_name = '".$lname."',
                                                                        first_name = '".$fname."',
                                                                        email = '".$email."',
                                                                        phone_no = '".$phone."',
                                                                        campus_id = '".$campus."',
                                                                        fac_id = '".$facs."',
                                                                        dept_id = '".$deps."',
                                                                        role_id = '".$role."' WHERE id='".$id."'"); 
                if($update->execute()){
                    $this->sendFeedback(200,"Account updated successfully");    
                }
                else{
                    $this->sendFeedback(401,"Failed to update account!");
                }
            }
        }
    }
    	function disable_user_account(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT status FROM tbl_users WHERE id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status = 2;
            }
            else{
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_users SET status='".$status."' WHERE id='".$id."'");
            if($stmt2->execute()){
                $this->sendFeedback(200,"operation done successfully");
            } else {
                $this->sendFeedback(401,"operation failed!");
            }
        }
    	function load_info(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_users WHERE id='".$id."'");
            $stmt->execute();
            $data2=$stmt->fetch();
            $roleId=$data2['role_id'];
            if($roleId!=4){
            $stmt1 = $this->connect->prepare("SELECT tbl_users.* FROM tbl_users INNER JOIN tbl_staff 
            ON tbl_staff.staff_id =tbl_users.Identification WHERE tbl_users.id='".$id."'");
            $stmt1->execute();    
            }
            else{
            $stmt1 = $this->connect->prepare("SELECT * FROM tbl_users WHERE id='".$id."'");
            $stmt1->execute();
             
            }
            $data=$stmt1->fetch();   
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        function create_users(){
        try {
            // Get form data
            $first_name = isset($_POST['fname']) ? $_POST['fname'] : '';
            $family_name = isset($_POST['lname']) ? $_POST['lname'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $role_id = isset($_POST['role']) ? $_POST['role'] : '';
            $campus_id = isset($_POST['campus']) ? $_POST['campus'] : '';
            $staff_id=isset($_POST['staff_id']) ? $_POST['staff_id'] : '';
            
            // Validate required fields
            if(empty($first_name) || empty($family_name) || empty($email) || empty($role_id) || empty($campus_id)) {
                $this->sendFeedback(400, "Please fill all required fields");
                return;
            }

            // Check if email already exists
            $check_email = $this->connect->prepare("SELECT * FROM tbl_users WHERE email = ?");
            $check_email->execute([$email]);
            
            if($check_email->rowCount() > 0) {
                $this->sendFeedback(400, "Email already exists");
                return;
            }
            
            // Handle faculty and department based on role
            $fac_id = null;
            $dept_id = null;
            
            // Role 11: Multiple faculties
            if($role_id == '11' && isset($_POST['faculty_ids'])) {
                $fac_id = json_encode($_POST['faculty_ids']);
            }
            // Role 10: Single faculty with multiple departments
            else if($role_id == '10') {
                if(isset($_POST['faculty_id'])) {
                    $fac_id = $_POST['faculty_id'];
                }
                if(isset($_POST['dept_ids'])) {
                    $dept_id = json_encode($_POST['dept_ids']);
                }
            }
            // Role 12: Single faculty with single department
            else if($role_id == '12') {
                if(isset($_POST['faculty_id'])) {
                    $fac_id = $_POST['faculty_id'];
                }
                if(isset($_POST['dept_id'])) {
                    $dept_id = $_POST['dept_id'];
                }
            }
            
            // Current date for registration
            $reg_date = date('Y-m-d H:i:s');
            
            // Generate random password for new users
            $password = $this->generate_password();
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Default status is active
            $status = 1;
            
            // Default privileges (can be adjusted as needed)
            $privileges = 1;
            
            // Insert new user
            $insert_query = $this->connect->prepare("INSERT INTO tbl_users 
                (Identification,family_name, first_name, email, password, role_id, campus_id, fac_id, dept_id, status, reg_date, prvg) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $result = $insert_query->execute([
                $staff_id,
                $family_name,
                $first_name,
                $email,
                $hashed_password,
                $role_id,
                $campus_id,
                $fac_id,
                $dept_id,
                $status,
                $reg_date,
                $privileges
            ]);
            
            if($result) {
                // Send welcome email with credentials
                $to = $email;
                $subject = "Welcome to Our Institution";
                
                // Get the application URL (replace with actual URL if needed)
                $app_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "https://misnjala.edu.sl";
                
                $message = "
                <html>
                <head>
                    <title>Welcome to Njala University</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px; }
                        .container { max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                        .header { background-color: #004080; color: #ffffff; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
                        .content { padding: 20px; }
                        .credentials { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #004080; }
                        .footer { margin-top: 20px; font-size: 0.9em; color: #666; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Welcome to Njala University</h2>
                        </div>
                        <div class='content'>
                            <p>Dear $first_name $family_name,</p>
                
                            <p>We are pleased to inform you that your account has been created at <strong>Njala University</strong>.</p>
                
                            <p>Here are your login credentials:</p>
                            
                            <div class='credentials'>
                                <p><strong>Application URL:</strong> $app_url</p>
                                <p><strong>Username:</strong> $email</p>
                                <p><strong>Password:</strong> $password</p>
                            </div>
                            
                            <p>Please change your password after your first login for security reasons.</p>
                
                            <p>We are excited to have you on board and look forward to the positive impact you will bring to our institution.</p>
                
                            <p>Best regards,<br>
                            The ICT Team</p>
                        </div>
                        <div class='footer'>
                            <p>If you did not expect this message or believe it was sent in error, please contact ICT Department immediately.</p>
                        </div>
                    </div>
                </body>
                </html>";
                
                // Email headers
                $headers = "From: ict@misnjala.edu.sl\r\n";
                $headers .= "Reply-To: ict@misnjala.edu.sl\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                
                // Send email
                $mail_sent = mail($to, $subject, $message, $headers);
                
                // Return success message
                $message = "User created successfully. Password: " . $password;
                if ($mail_sent) {
                    $message .= " A welcome email with credentials has been sent to the user.";
                } else {
                    $message .= " However, the welcome email could not be sent.";
                }
                
                $this->sendFeedback(200, $message);
            } else {
                $this->sendFeedback(400, "Error creating user");
            }
            
        } catch(Exception $e) {
            $this->sendFeedback(500, "Server error: " . $e->getMessage());
        }
    }
    function reset_password() {
    try {
        // Get user data
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        
        if (empty($user_id) || empty($email)) {
            $this->sendFeedback(400, "Missing user information");
            return;
        }
        
        // Generate new password
        $password = $this->generate_password();
        // Hash the password for database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the user's password
        $update_query = $this->connect->prepare("UPDATE tbl_users SET password = ? WHERE id = ?");
        $result = $update_query->execute([$hashed_password, $user_id]);
        
        if ($result) {
            // Get the application URL
            $app_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "https://misnjala.edu.sl";
            
            // Send email with new credentials
            $to = $email;
            $subject = "Your Password Has Been Reset";
            
            $message = "
            <html>
            <head>
                <title>Password Reset Notification</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px; }
                    .container { max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                    .header { background-color: #004080; color: #ffffff; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { padding: 20px; }
                    .credentials { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #004080; }
                    .footer { margin-top: 20px; font-size: 0.9em; color: #666; text-align: center; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Password Reset Notification</h2>
                    </div>
                    <div class='content'>
                        <p>Dear $name,</p>
            
                        <p>Your password for Njala University's system has been reset.</p>
            
                        <p>Here are your login credentials:</p>
                        
                        <div class='credentials'>
                            <p><strong>Application URL:</strong> $app_url</p>
                            <p><strong>Username:</strong> $email</p>
                            <p><strong>New Password:</strong> $password</p>
                        </div>
                        
                        <p>Please change your password after logging in for security reasons.</p>
            
                        <p>If you did not request this password reset, please contact the IT department immediately.</p>
            
                        <p>Best regards,<br>
                        The ICT Team</p>
                    </div>
                    <div class='footer'>
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            // Email headers
            $headers = "From: ict@misnjala.edu.sl\r\n";
            $headers .= "Reply-To: ict@misnjala.edu.sl\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            // Send email
            $mail_sent = mail($to, $subject, $message, $headers);
            
            $feedback_message = "Password has been reset successfully.";
            if ($mail_sent) {
                $feedback_message .= " Reset notification has been sent to the user's email.";
            } else {
                $feedback_message .= " However, the email notification could not be sent.";
            }
            
            $this->sendFeedback(200, $feedback_message);
        } else {
            $this->sendFeedback(400, "Error resetting password");
        }
        
    } catch(Exception $e) {
        $this->sendFeedback(500, "Server error: " . $e->getMessage());
    }
}

function get_user() {
    try {
        // Get user ID from request
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        
        if(empty($user_id)) {
            $this->sendFeedback(400, "Missing user ID");
            return;
        }
        
        // Query to get user data
        $query = $this->connect->prepare("
            SELECT u.*
            FROM tbl_users u
            LEFT JOIN tbl_staff_info st ON u.email = st.email
            WHERE u.id = ?
        ");
        $query->execute([$user_id]);
        
        if($query->rowCount() > 0) {
            $userData = $query->fetch(PDO::FETCH_ASSOC);
            $this->sendFeedback(200, "User found", $userData);
        } else {
            $this->sendFeedback(404, "User not found");
        }
    } catch(Exception $e) {
        $this->sendFeedback(500, "Server error: " . $e->getMessage());
    }
}

    }
		$user=new User();
	    $action = $_POST['action'];
		switch($action){
		    case 'create_user':
		        $user->create_account();
		        break;
		    case 'update':
		        $user->update_account();
		        break;
		    case 'delete':
		        $user->disable_user_account();
		        break;
		    case 'view':
		        $user->load_info();
		        break;
		    case 'send_code':
		        $user->send_verification_code();
		        break;
		    case 'request_code':
		        $user->send_verification_code();
		        break;
		    case 'verify_email':
		        $user->verify_email();
		        break;
		  //  case 'reset_password':
		  //      $user->reset_password();
		  //      break;
		    case 'force_reset_password':
		        $user->force_reset_password();
		        break;
		    case 'create_users':
		        $user->create_users();
		        break;
		    case 'reset_password':
            $user->reset_password();
            break; 
            case 'get_user':
            $user->get_user();
            break;
		}

	?>

