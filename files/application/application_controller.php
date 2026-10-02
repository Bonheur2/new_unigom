<?php
    include ('../../meet/con.php');
    
    // Include PHPMailer classes
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require_once '../../mailing/autoload.php'; // If using Composer
    // OR if downloaded manually:
    // require_once 'PHPMailer/src/Exception.php';
    // require_once 'PHPMailer/src/PHPMailer.php';
    // require_once 'PHPMailer/src/SMTP.php';
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $connection=$conn;
    class Application{
        private $connect;
        
        // SMTP Configuration
        private $smtp_host = 'outbound.misnjala.edu.sl'; // e.g., 'smtp.gmail.com'
        private $smtp_port = 9001; // or 465 for SSL
        private $smtp_username = 'pm@outbound.misnjala.edu.sl';
        private $smtp_password = 'Roger12@';
        private $smtp_encryption = 'ssl'; // or 'ssl'
        private $from_email = 'pm@outbound.misnjala.edu.sl';
        private $from_name = 'Njala University MIS';
        
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    	}
        
        // Send email via Resend API (same configuration as API/Service.php)
        private function sendResendEmail($toEmail, $toName, $subject, $html) {
            $configPath = __DIR__ . '/config/resend.php';
            if (!file_exists($configPath)) {
                error_log('Resend config not found at ' . $configPath);
                return false;
            }
            $cfg = require $configPath;
            if (empty($cfg['api_key']) || $cfg['api_key'] === 're_xxxxxxxxx') {
                error_log('Resend API key not configured.');
                return false;
            }

            $payload = json_encode([
                'from'    => $cfg['from'],
                'to'      => $toName ? "$toName <$toEmail>" : $toEmail,
                'subject' => $subject,
                'html'    => $html,
            ]);

            $ch = curl_init('https://api.resend.com/emails');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $cfg['api_key'],
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT        => 15,
            ]);
            $response = curl_exec($ch);
            $http     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err      = curl_error($ch);
            curl_close($ch);

            if ($err || $http < 200 || $http >= 300) {
                error_log("Resend send failed (HTTP $http): " . ($err ?: $response));
                return false;
            }
            return true;
        }

        // New method to configure SMTP
        private function setupSMTP() {
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = $this->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $this->smtp_username;
                $mail->Password   = $this->smtp_password;
                $mail->SMTPSecure = $this->smtp_encryption;
                $mail->Port       = $this->smtp_port;
                
                // Set from address
                $mail->setFrom($this->from_email, $this->from_name);
                
                return $mail;
            } catch (Exception $e) {
                error_log("SMTP Setup Error: " . $e->getMessage());
                return false;
            }
        }
        
        function send_submission_email($code){
    $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
    $stmt->execute([$code]);
    if($stmt->rowCount()!=0){
        $appData=$stmt->fetch();
        
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        
        $year = date("Y");
        
        // Prepare email content
        $message = '
        <div style="padding: 10px;border: 1px solid lightgray;width: 100%;">
        <table>
        <thead>
        <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
        </thead>
        <tbody>
            <tr>
              <td>
                  <p>Dear <b>'.$appData['fname'].',</b></p>
                  <p>We trust this email finds you well. We are writing to express our appreciation for your recent application to '.$data['full_name'].'. We are delighted that you have taken the initiative to apply for admission to our esteemed institution.</p>
                  <p>Your application is currently under review, and our admissions committee is diligently assessing each candidate qualifications. We understand the significance of this process and the anticipation that comes with it. Rest assured that we are committed to providing a thorough and fair evaluation of all applications.</p>
                  <p>The admissions team will carefully consider your academic achievements, personal statement, and any supporting documents you have provided. We aim to make decisions that reflect our commitment to academic excellence and our mission to nurture individuals who are not only academically proficient but also contribute positively to our vibrant community.</p>
                  
                  <P>It is crucial that you complete and submit all required materials by not later than AUGUST 31, 2026. Failure to fulfill these requirements in full will result in your application being disregarded.</P>
                  <p>Please note that the review process may take some time as we strive to give each application the attention it deserves. We appreciate your patience during this period.</p>
                  <p>If you have any questions or if there are additional materials you would like to submit, please feel free to reach out to our admissions office at [helpdesk@njala.edu.sl]. We are here to assist you throughout the application process.</p>
                  <p>Thank you once again for considering '.$data['full_name'].' for your academic journey. We look forward to the possibility of welcoming you to our community.</p>
                  <p>You can continue to get in touch through our system. You will still use your email and password to log in.</p>
                  <p><a href="https://misnjala.edu.sl/auth">Login</a></p><br>
                  <p>Best regards,</p>
                  <p><b>Admission Team</b></p>
              </td>
            </tr>
               
            <tr>
                <td style="text-align: center;">
                    <hr>
                    &copy; '.$year.' NJALA University. All rights reserved.<br>
                    Designed by ITEC Ltd<br>
                    NJALA-Sierra Leone.<br>
                    Phone (+232) 74 001023,79 102840,76 811846
                </td>
            </tr>
        </tbody>
      </table> 
      </div>';

        // Send email using cURL
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                "recipient" => $appData['email'],
                "subject" => "Acknowledgment of Your Application Submission",
                "message" => $message,
                "is_html" => true
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $curlResponse = curl_exec($curl);
        curl_close($curl);

        $curlResponseData = json_decode($curlResponse, true);
        if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
            return true;
        } else {
            error_log("Submission Email Error: " . ($curlResponseData['message'] ?? 'Unknown error'));
            return false;
        }
    }
    return false;
}
        
        function send_verification_code(){
            $email=$_POST['email'];
            $stmt0 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
            $stmt0->execute();
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email='".$email."'");
            $stmt->execute();
            if($stmt->rowCount()!=0){
                $appData=$stmt->fetch();
                $expirationTime = date('Y-m-d H:i:s', strtotime('+48 hours'));
                $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES ('".$email."','".$appData['code']."','".$expirationTime."')");
                $stmt5->execute();
                
                $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                $sql->execute();
                $data=$sql->fetch();
                $year = date("Y");
                
                // Prepare cURL request
                $curl = curl_init();
                $message = '
                <div style="padding: 10px; border: 1px solid lightgray; width: 500px; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: left;">
                                    <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding-top: 10px;">
                                    <p>Dear <b>'.$appData['fname'].'</b>,</p>
                                    <p>Click the button below to verify your email instantly.</p>
                                    <p style="margin: 25px 0; text-align: center;">
                                        <a href="https://misnjala.edu.sl/verify_email?em='.urlencode($email).'&token='.urlencode($appData['code']).'&act=auto" 
                                           style="background: #1f6feb; color: #fff; text-decoration: none; padding: 12px 22px; border-radius: 6px; display: inline-block; font-weight: bold;">
                                            Verify Email
                                        </a>
                                    </p>
                                    <p>If the button does not open correctly, use this verification code on the verification page:</p>
                                    <p style="font-size: 18px; font-weight: bold; color: #000; letter-spacing: 1px;">'.$appData['code'].'</p>
                                    <p>This code will also be required when paying your application fees either at the bank or via Afrimoney.</p>
                                    <ul>
                                        <li><b>Afrimoney:</b> Dial <b>*161*2*4*1#</b></li>
                                        <li><b>Bank:</b> Visit the nearest branch of <b>Sierra Leone Commercial Bank (SLCB)</b></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;">
                                    <hr>
                                    <p style="font-size: 12px; color: #555;">
                                        &copy; '.$year.' ITEC. All rights reserved.<br>
                                        Designed by ITEC Ltd<br>
                                        KN 1 Rd, Kigali-Rwanda.<br>
                                        Phone: (+232) 79453322
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>';

    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(array(
                        "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                        "recipient" => $email,
                        "subject" => "Verification Code",
                        "message" => $message,
                        "is_html" => true
                    )),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
    
                $curlResponse = curl_exec($curl);
                curl_close($curl);
    
                $curlResponseData = json_decode($curlResponse, true);
                if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                    $data = array("status" => "200", "message" => "Verification code sent!!");
                } else {
                    $data = array("status" => "500", "message" => "Failed to send email: " . ($curlResponseData['message'] ?? 'Unknown error'));
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
            $token=$_POST['token'] ?? ($_GET['token'] ?? '');
            $pass =$token;
            $dt = date('Y-m-d H:i:s');
              $dir = [
                    'cost' => 12,
                ];
            if ($token === '') {
                $data = array("status"=>"401");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
                return;
            }
            $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
            $sth = $this->connect->prepare("SELECT email FROM email_verification_tokens WHERE token='".$token."'");
            $sth->execute();
            if ($sth->rowCount() != 0) {
                $res = $sth->fetch();
                $email=$res['email'];
                $stmt = $this->connect->prepare("INSERT INTO tbl_student_login(Identification,email,password,role_id) VALUES ('".$token."','".$email."','".$password."',5)");
                $stmt2 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
                if($stmt->execute()){
                    $stmt2->execute();
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
        function show_school(){
            $prg_type=$_POST['prg_id'];
            $select_school="SELECT * FROM tbl_faculty WHERE prg_type = ? AND status = 1";
            $cselect_school=$this->connect->prepare($select_school);
            $cselect_school->execute([$prg_type]);
            $row_cselect_school=$cselect_school->fetchAll();
            $jsonData = json_encode($row_cselect_school);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_school_marks(){
            $prg_type=$_POST['prg_id'];
            $fac_id=$_POST['fac_id'];
            $select_school="SELECT * FROM tbl_faculty WHERE prg_type='$prg_type' AND fac_id='$fac_id'";
            $cselect_school=$this->connect->prepare($select_school);
            $cselect_school->execute();
            $row_cselect_school=$cselect_school->fetchAll();
            $jsonData = json_encode($row_cselect_school);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_department(){
            $fac_id=$_POST['fac_id'];
            $prg_type=$_POST['prg_id'];
            
            $select_depart="SELECT * FROM tbl_department WHERE fac_id='$fac_id' AND  prg_type='$prg_type'";
            $cselect_depart=$this->connect->prepare($select_depart);
            $cselect_depart->execute();
            $row_cselect_departl=$cselect_depart->fetchAll();
            $jsonData = json_encode($row_cselect_departl);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_department1(){
            $fac_id=$_POST['fac_id'];
            $prg_type=$_POST['prg_id'];
            
            $select_depart="SELECT * FROM tbl_department WHERE fac_id='$fac_id' ";
            $cselect_depart=$this->connect->prepare($select_depart);
            $cselect_depart->execute();
            $row_cselect_departl=$cselect_depart->fetchAll();
            $jsonData = json_encode($row_cselect_departl);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_department_marks(){
            $fac_id=$_POST['fac_id'];
            $prg_type=$_POST['prg_id'];
            $dept_id=$_POST['dept_id'];
            $select_depart="SELECT * FROM tbl_department WHERE fac_id='$fac_id' AND prg_type='$prg_type' AND dept_id='$dept_id'";
            $cselect_depart=$this->connect->prepare($select_depart);
            $cselect_depart->execute();
            $row_cselect_departl=$cselect_depart->fetchAll();
            $jsonData = json_encode($row_cselect_departl);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_level(){
            $prg_type=$_POST['prg_type'];
            $select_level="SELECT * FROM tbl_level WHERE prg_type='$prg_type'";
            $cselect_level=$this->connect->prepare($select_level);
            $cselect_level->execute();
            $row_cselect_level=$cselect_level->fetchAll();
            $jsonData = json_encode($row_cselect_level);
            header('Content-Type: application/json');
            echo $jsonData;
            
        }
        function show_level_app()
        {
            $prg_type=$_POST['prg_type'];
            $select_level="SELECT * FROM tbl_level WHERE prg_type='$prg_type' and level_no=1";
            $cselect_level=$this->connect->prepare($select_level);
            $cselect_level->execute();
            $row_cselect_level=$cselect_level->fetchAll();
            $jsonData = json_encode($row_cselect_level);
            header('Content-Type: application/json');
            echo $jsonData;
        }
         function show_specialization(){
             $dept_id=$_POST['dept_id'];
             $prg_type=$_POST['prg_type'];
             $fac_id=$_POST['fac_id'];
             
             $select_spec="SELECT * FROM tbl_specialization WHERE  dept_id='$dept_id' AND fac_id='$fac_id' AND prg_type='$prg_type'";
             $cselect_spec=$this->connect->prepare($select_spec);
             $cselect_spec->execute();
             $row_cselect_spec=$cselect_spec->fetchAll();
             $jsonData = json_encode($row_cselect_spec);
             header('Content-Type: application/json');
             echo $jsonData;
         }
         
         function show_specialization_new(){
             $prg_type=$_POST['prg_id'];
             $fac_id=$_POST['fac_id'];
             
             $select_spec="SELECT * FROM tbl_specialization WHERE  fac_id='$fac_id' AND prg_type='$prg_type'";
             $cselect_spec=$this->connect->prepare($select_spec);
             $cselect_spec->execute();
             $row_cselect_spec=$cselect_spec->fetchAll();
             $jsonData = json_encode($row_cselect_spec);
             header('Content-Type: application/json');
             echo $jsonData;
         }
         
         function show_specialization1(){
             $dept_id=$_POST['dept_id'];
             $prg_type=$_POST['prg_type'];
             $fac_id=$_POST['fac_id'];
            
             $select_spec="SELECT * FROM tbl_specialization WHERE  dept_id='$dept_id'";
             $cselect_spec=$this->connect->prepare($select_spec);
             $cselect_spec->execute();
             $row_cselect_spec=$cselect_spec->fetchAll();
             $jsonData = json_encode($row_cselect_spec);
             header('Content-Type: application/json');
             echo $jsonData;
         }
         function show_modes(){
             
             
             $prg_type=$_POST['prg_type'];
             $select_mode="SELECT * FROM tbl_program_mode WHERE status=1";
             $cselect_mode=$this->connect->prepare($select_mode);
             $cselect_mode->execute();
             $row_cselect_mode=$cselect_mode->fetchAll();
             $jsonData = json_encode($row_cselect_mode);
             header('Content-Type: application/json');
             echo $jsonData;
         }
         function show_modes_app(){
            $prg_type=$_POST['prg_type'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id='".$prg_type."'");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data['prg_type_short_name']=='UG'||$data['prg_type_short_name']=='HD'||$data['prg_type_short_name']=='CR'){
                
             $select_mode="SELECT * FROM tbl_program_mode WHERE status=1 AND prg_mode_full_name='Day'";
             $cselect_mode=$this->connect->prepare($select_mode);
             $cselect_mode->execute();
             $row_cselect_mode=$cselect_mode->fetchAll();
             $jsonData = json_encode($row_cselect_mode);
             header('Content-Type: application/json');
             echo $jsonData;
                
            }
            else {
                
             $select_mode="SELECT * FROM tbl_program_mode WHERE status=1";
             $cselect_mode=$this->connect->prepare($select_mode);
             $cselect_mode->execute();
             $row_cselect_mode=$cselect_mode->fetchAll();
             $jsonData = json_encode($row_cselect_mode);
             header('Content-Type: application/json');
             echo $jsonData;
            }
             
         }

        
function create_password() {
    $pass = $_POST['password'];
    $email = $_POST['email'];
    $identification = $_POST['identification'];

    $password = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);

    // Check if email exists
    $check = $this->connect->prepare(
        "SELECT id FROM tbl_student_login WHERE email = ?"
    );
    $check->execute([$email]);

    if ($check->rowCount() > 0) {

        // Update existing row
        $stmt = $this->connect->prepare(
            "UPDATE tbl_student_login
             SET password = ?
             WHERE email = ?"
        );

        $result = $stmt->execute([$password, $email]);

    } else {

        // Insert new row
        $stmt = $this->connect->prepare(
            "INSERT INTO tbl_student_login
            (Identification, email, password, status, role_id)
            VALUES (?, ?, ?, 1, 5)"
        );

        $result = $stmt->execute([
            $identification,
            $email,
            $password
        ]);
    }

    echo json_encode([
        "status" => $result ? "200" : "500"
    ]);
}
       
    	function load_provinces(){
            $stmt = $this->connect->prepare("SELECT * FROM provinces");
            $stmt->execute();
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
        
    	function load_districts(){
    	    $pid=$_POST['pid'];
            $stmt = $this->connect->prepare("SELECT * FROM districts WHERE provincecode='".$pid."'");
            $stmt->execute();
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
    	function load_sectors(){
    	    $did=$_POST['did'];
            $stmt = $this->connect->prepare("SELECT * FROM sectors WHERE districtcode='".$did."'");
            $stmt->execute();
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
    	function load_cells(){
    	    $sid=$_POST['sid'];
            $stmt = $this->connect->prepare("SELECT * FROM cells WHERE sectorcode='".$sid."'");
            $stmt->execute();
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
    	function load_villages(){
    	    $cid=$_POST['cid'];
            $stmt = $this->connect->prepare("SELECT * FROM villages WHERE codecell='".$cid."'");
            $stmt->execute();
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
    	function load_program_types(){
    	    $cid=$_POST['cid'];
                $stmt = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE campus_id = ? AND status = 1 ORDER BY prg_type_full_name ASC");
                $stmt->execute([$cid]);
            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
    
    
        function apply_init(){
            // Campus prg_type_id
            
            $fname=$_POST['fname'];
            $mname=$_POST['mname'];
            $lname=$_POST['lname'];
            $email=$_POST['email'];
            $nid=$_POST['nid'];
            $phone=$_POST['phone'];
            $gender=$_POST['gender'];
            $dob = $_POST['dob'];
            $father_names=$_POST['father_name'];
            $mother_names=$_POST['mother_name'];
            $parent_phone=$_POST['parent_phone'];
            $ref_phone=$_POST['ref_phone'];
            $country=(int)$_POST['country'];
            $nationality=(int)$_POST['nationality'];
            $prg_type_id = $_POST['prg_type_id'];
            
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email='".$email."'");
            $stmt->execute();
            $stmt1 = $this->connect->prepare("SELECT * FROM tbl_users WHERE email='".$email."'");
            $stmt1->execute();
            $stmt2 = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE ID='".$nid."'");
            $stmt2->execute();
            $stmt3 = $this->connect->prepare("SELECT * FROM tbl_admission WHERE ID='".$nid."'");
            $stmt3->execute();
            $stmt4 = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE email='".$email."'");
            $stmt4->execute();
            if($stmt->rowCount()>0 || $stmt1->rowCount()>0 || $stmt4->rowCount()>0){
                $data = array("status"=>"401","message" => "Email already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            }
            else if($stmt2->rowCount()>0 || $stmt3->rowCount()>0){
                $data = array("status"=>"401","message" => "ID number already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;    
            }
            else {
               
                $checkcode = $this->connect->prepare("SELECT * FROM tbl_applicants ORDER BY applicant_id DESC limit 1");
                $checkcode->execute();
                if($checkcode->rowCount()==0){
                    $suffix='00001';
                }
                else{
                    $codeData=$checkcode->fetch();
                    $prevSuffix=substr($codeData['code'],-4);
                    if($prevSuffix<9){
                        $prevSuffix++;
                        $suffix='0000'.$prevSuffix;
                    }
                    elseif($prevSuffix<99){
                        $prevSuffix++;
                        $suffix='000'.$prevSuffix;
                    }
                    elseif($prevSuffix<999){
                        $prevSuffix++;
                        $suffix='00'.$prevSuffix;
                    }
                    elseif($prevSuffix<9999){
                        $prevSuffix++;
                        $suffix='0'.$prevSuffix;
                    }
                    else{
                        $prevSuffix++;
                        $suffix=$prevSuffix;
                    }
                }
                function generate_password() {
                $chars = "0123456789";
                $passw = substr(str_shuffle($chars), 0, 5);
                return $passw;
                    }
                
                // Generate unique code
                $currentYear = date('Y');
                do {
                    $pass = generate_password();
                    $code = substr($currentYear, -2)."NJ".$pass;
                    
                    // Check if code already exists
                    $checkCodeExists = $this->connect->prepare("SELECT code FROM tbl_applicants WHERE code = ?");
                    $checkCodeExists->execute([$code]);
                    $codeExists = $checkCodeExists->rowCount() > 0;
                } while ($codeExists);
                
                // error_log($prg_type_id);
                
                $stmt = $this->connect->prepare("INSERT INTO tbl_applicants(code,fname,mname,lname,email,nationality,ID,phone,gender,dob,father_names,mother_names,parent_phone,ref_phone,country) 
                          	VALUES('".$code."','".$fname."','".$mname."','".$lname."','".$email."','".$nationality."','".$nid."','".$phone."','".$gender."','".$dob."','".$father_names."','".$mother_names."','".$parent_phone."','".$ref_phone."','".$country."')");
                    if($stmt->execute()){
                        
                        $select_fee="SELECT * FROM tbl_fee_category WHERE name='Applicant Fee' AND prg_type_id = ? AND status=1 LIMIT 1";
                        $cselect_fee=$this->connect->prepare($select_fee);
                        $cselect_fee->execute([$prg_type_id]);
                        $row_cselect_fee=$cselect_fee->fetch();
                        
                        
                        $select_fee_amount = "SELECT * FROM tbl_program_type WHERE prg_type_id = ?";
                        $cselect_fee_amount = $this->connect->prepare($select_fee_amount);
                        $cselect_fee_amount->execute([$prg_type_id]);
                        $row_cselect_fee_amount = $cselect_fee_amount->fetch();
                        
                        
                        $select_academic = "SELECT * FROM tbl_acad_cycle WHERE status=1";
                        $cselect_academic = $this->connect->prepare($select_academic);
                        $cselect_academic->execute();
                        $row_cselect_academic = $cselect_academic->fetch();
                        $acad_cycle_id = $row_cselect_academic['acad_cycle_id'];
                        $reg_no=$code;
                        $month=date('F');
                        $fee_id=$row_cselect_fee['id'];
                        
                        
                        $select_itk = "SELECT * FROM tbl_intake WHERE prg_type = ? AND status = 1";
                        $cselect_fee_itk = $this->connect->prepare($select_itk);
                        $cselect_fee_itk->execute([$prg_type_id]);
                        $row_cselect_itk = $cselect_fee_itk->fetch();
                        
                        $intake_id = $row_cselect_itk['intake_id'];
                        
                        
                        $balance = $row_cselect_fee_amount['application_fee'];
                        $campus_id = $row_cselect_fee_amount['campus_id'];
                        
                        
                        
                        $admittedPRG = $this->connect->prepare("INSERT INTO tbl_admittedPRG (Stu_code, cump_id, prg_type, intake_id, mode, acad_id) 
                        VALUES (?, ?, ?, ?, ?, ?)");
                        $admittedPRG->execute([$reg_no, $campus_id, $prg_type_id, $intake_id, 1, $acad_cycle_id]);
                        
                        $invoice_date=date('Y-m-d H:i:s');
                        $invoice_data=[
                            'reg_no'=>$reg_no,
                            'month'=>$month,
                            'fee_id'=>$fee_id,
                            'balance'=>$balance,
                            'invoice_date'=>$invoice_date,
                            'acad_cycle_id'=>$acad_cycle_id,
                            'user_info'=>$reg_no
                            ];
                        $invoice="INSERT INTO tbl_invoice (`reg_no`,`acad_cycle_id`, `month`, `fee_id`, `balance`, `invoice_date`, `user`) 
                        values (:reg_no, :acad_cycle_id, :month, :fee_id, :balance, :invoice_date, :user_info)";
                        $cinvoice=$this->connect->prepare($invoice);
                        $cinvoice->execute($invoice_data);
                        
                        $languages = ["English", "Krio"];
                        foreach($languages as $lan){
                            $stmt55 = $this->connect->prepare("INSERT INTO tbl_language_pro (language, stu, state) VALUES ('".$lan."','".$code."', 1)");
                            $stmt55->execute();
                        }
                        $expirationTime = date('Y-m-d H:i:s', strtotime('+48 hour'));
                        $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES (?, ?, ?)");
                        $stmt5->execute([$email, $code, $expirationTime]);

                        $year = date("Y");
                        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                        $sql->execute();
                        $data = $sql->fetch();

                        $message = '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">

    <div style="background-color: #1a3c6e; padding: 24px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 90px; vertical-align: middle; padding-right: 16px;">
                    <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="80px" alt="Njala University Logo">
                </td>
                <td style="vertical-align: middle;">
                    <h2 style="color: #ffffff; margin: 0; font-size: 18px; letter-spacing: 1px;">NJALA UNIVERSITY</h2>
                    <p style="color: #a0bce0; margin: 4px 0 0; font-size: 13px;">Management Information System</p>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding: 32px 40px;">
        <h3 style="color: #1a3c6e; margin-top: 0;">Email Verification</h3>
        <p style="color: #333333; font-size: 15px;">Dear <strong>' . $fname . ' ' . $lname . '</strong>,</p>
        <p style="color: #555555; font-size: 14px; line-height: 1.6;">
            Thank you for registering with the Njala University Management Information System (MIS).
            Please verify your email address by clicking the button below to activate your account.
        </p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="https://misnjala.edu.sl/verify_email?em=' . urlencode($email) . '&token=' . urlencode($code) . '&act=auto"
               style="display:inline-block; background-color:#1f6feb; color:#ffffff; text-decoration:none;
                      padding:10px 50px; border-radius:6px; font-weight:bold; font-size:15px; letter-spacing:0.5px;">
                Verify My Email Address
            </a>
        </div>

        <div style="background-color: #f4f7fc; border: 1px dashed #1f6feb; border-radius: 6px;
                    text-align: center; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; font-size: 13px; color: #777777;">Your Verification Code</p>
            <p style="margin: 8px 0 0; font-size: 28px; font-weight: bold; color: #1a3c6e; letter-spacing: 4px;">' . $code . '</p>
        </div>

        <div style="background-color: #f0f4ff; border: 1.5px solid #1f6feb; border-radius: 6px; padding: 16px 20px; margin-top: 24px;">
            <p style="margin: 0 0 6px; font-size: 14px; font-weight: bold; color: #1a3c6e;">Next Step: Pay Your Application Fee</p>
            <p style="margin: 0 0 12px; font-size: 13px; color: #555555; line-height: 1.6;">
                After verifying your email, pay the application fee using one of the options below to complete your application.
                This verification code and link will expire in <strong>48 hours</strong>.
                If you did not create this account, please ignore this email or contact our support team immediately.
            </p>

            <div style="background-color: #ffffff; border: 1px solid #1f6feb; border-radius: 4px; padding: 10px 14px; margin-bottom: 12px;">
                <p style="margin: 0 0 4px; font-size: 13px; font-weight: bold; color: #1a3c6e;">Application Fee</p>
                <p style="margin: 0; font-size: 13px; color: #555555;">Undergraduate: <strong>SLE 500</strong></p>
                <p style="margin: 0; font-size: 13px; color: #555555;">Postgraduate: <strong>SLE 600</strong></p>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 10px; background-color: #f4f7fc; border: 1px solid #c7d7f9; border-radius: 4px; vertical-align: top; width: 48%;">
                        <p style="margin: 0 0 4px; font-size: 13px; font-weight: bold; color: #1a3c6e;">Afrimoney</p>
                        <p style="margin: 0; font-size: 13px; color: #555555;">Dial <strong>*161*2*4*1#</strong> and follow the prompts.</p>
                    </td>
                    <td style="width: 4%;"></td>
                    <td style="padding: 8px 10px; background-color: #f4f7fc; border: 1px solid #c7d7f9; border-radius: 4px; vertical-align: top; width: 48%;">
                        <p style="margin: 0 0 4px; font-size: 13px; font-weight: bold; color: #1a3c6e;">Bank Payment</p>
                        <p style="margin: 0 0 4px; font-size: 13px; color: #555555;">Visit any <strong>SLCB</strong> branch.</p>
                        <p style="margin: 0; font-size: 13px; color: #555555;">Account No: <strong style="letter-spacing:1px;">003010002429120119</strong></p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="background-color: #f0f4ff; border: 1px solid #c7d7f9; border-radius: 6px; padding: 16px 20px; margin-top: 24px;">
            <p style="margin: 0 0 8px; font-size: 13px; font-weight: bold; color: #1a3c6e;">ICT Support Desk</p>
            <p style="margin: 0 0 6px; font-size: 13px; color: #555555;">
                <strong>Email: </strong><a href="mailto:helpdesk@njala.edu.sl" style="color:#1f6feb;">helpdesk@njala.edu.sl</a>
            </p>
            <p style="margin: 0 0 6px; font-size: 13px; color: #555555;">
                <strong>Phone: </strong> +232 74 001023 &nbsp;|&nbsp; +232 31 295010 &nbsp;|&nbsp; +232 76 811846
            </p>
            <p style="margin: 0; font-size: 13px; color: #555555;">
                <strong>Available:</strong> Monday – Friday &nbsp;|&nbsp; 9:00 AM – 5:00 PM
            </p>
        </div>
    </div>

    <div style="background-color: #f4f7fc; padding: 20px 40px; text-align: center; border-top: 1px solid #e0e0e0;">
        <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="50px" alt="Njala University" style="opacity: 0.5; margin-bottom: 8px;">
        <p style="margin: 0; font-size: 12px; color: #888888;">
            &copy; ' . $year . ' <strong>Njala University</strong>. All rights reserved.<br>
            Developed &amp; Maintained by <strong>ITEC Ltd</strong><br>
            +232 74 001023 &nbsp;|&nbsp; +232 31 295010 &nbsp;|&nbsp; +232 76 811846
        </p>
        <p style="margin: 10px 0 0; font-size: 11px; color: #aaaaaa;">
            This is an automated message. Please do not reply directly to this email.
        </p>
    </div>

</div>';
                        $sent = $this->sendResendEmail($email, trim($fname." ".$lname), "Verification Code", $message);
                        if ($sent) {
                            $data = array("status" => "200", "message" => "Data saved successfully!");
                        } else {
                            $data = array("status" => "500", "message" => "Failed to send verification email.");
                        }

                        $jsonData = json_encode($data);
                        header('Content-Type: application/json');
                        echo $jsonData;
                    } else {
                        $data = array("status"=>"500","message" => "Failed to save data!");
                        $jsonData = json_encode($data);
                        header('Content-Type: application/json');
                        echo $jsonData; 
                    }
                }
            }
    	function load_profile(){
    	    $id=$_POST['student'];
    	    $info=array();
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE applicant_id='".$id."'");
            $stmt->execute();
            $data = $stmt->fetch();
            //districts
            $stmt0 = $this->connect->prepare("SELECT * FROM provinces");
            $stmt0->execute();
            $provinces = $stmt0->fetchAll();
            //districts
            $stmt1 = $this->connect->prepare("SELECT * FROM districts WHERE provincecode='".$data['province_id']."'");
            $stmt1->execute();
            $districts = $stmt1->fetchAll();
            //sectors
            $stmt2 = $this->connect->prepare("SELECT * FROM sectors WHERE districtcode='".$data['district_id']."'");
            $stmt2->execute();
            $sectors = $stmt2->fetchAll();
            //cells
            $stmt3 = $this->connect->prepare("SELECT * FROM cells WHERE sectorcode='".$data['sector']."'");
            $stmt3->execute();
            $cells = $stmt3->fetchAll();
            //villages
            $stmt4 = $this->connect->prepare("SELECT * FROM villages WHERE codecell='".$data['cell_id']."'");
            $stmt4->execute();
            $villages = $stmt4->fetchAll();
            
            array_push($info,$data,$provinces,$districts,$sectors,$cells,$villages);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        
        function update_profile_personal() {
        $stu = $_POST['stu'];
        error_log($stu);
        $fname = trim($_POST['fname'], " ");
        $lname = trim($_POST['lname'], " ");
        $nationality = $_POST['nationality'];
        $nid = trim($_POST['nid'], " ");
        $father_names = trim($_POST['father_names'], " ");
        $mother_names = trim($_POST['mother_names'], " ");
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $prevname = $_POST['prevname'];
        $mname = $_POST['mname'];
        $marital_status = $_POST['marital_status'];
        $church = $_POST['church'];
        $code=$_POST['code'];
        
    
        if ($fname == "" || $lname == "" || $nid == "" || $father_names == "" || $mother_names == "" || $marital_status == "" || $church == "") {
            $data = array("status" => "401", "message" => "Fill the form correctly");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            // Update applicant's personal information
            $stmtu = $this->connect->prepare("UPDATE tbl_applicants 
                SET fname=:fname, mname=:mname, lname=:lname, nationality=:nationality, ID=:nid, gender=:gender, dob=:dob, prevname=:prevname, 
                marital_status=:marital_status, father_names=:father_names, mother_names=:mother_names 
                WHERE applicant_id=:stu");
            
            $stmtu->bindParam(':fname', $fname);
            $stmtu->bindParam(':mname', $mname);
            $stmtu->bindParam(':lname', $lname);
            $stmtu->bindParam(':nationality', $nationality);
            $stmtu->bindParam(':nid', $nid);
            $stmtu->bindParam(':gender', $gender);
            $stmtu->bindParam(':dob', $dob);
            $stmtu->bindParam(':prevname', $prevname);
            $stmtu->bindParam(':marital_status', $marital_status);
            $stmtu->bindParam(':father_names', $father_names);
            $stmtu->bindParam(':mother_names', $mother_names);
            $stmtu->bindParam(':stu', $stu);
    
            if ($stmtu->execute()) {
                // Update or insert church details
                $stmtCheck = $this->connect->prepare("SELECT * FROM tbl_applicant_church WHERE stu = :code");
                $stmtCheck->bindParam(':code', $code);
                $stmtCheck->execute();
    
                if ($stmtCheck->rowCount() > 0) {
                    // Update existing church details
                    $stmtChurch = $this->connect->prepare("UPDATE tbl_applicant_church 
                        SET church = :church  WHERE stu = :stu");
                } else {
                    // Insert new church details
                    $stmtChurch = $this->connect->prepare("INSERT INTO tbl_applicant_church 
                        (stu, church) 
                        VALUES (:stu, :church)");
                }
    
                $stmtChurch->bindParam(':stu', $code);
                $stmtChurch->bindParam(':church', $church);
                if ($stmtChurch->execute()) {
                    $data = array("status" => "200", "message" => "Profile and church information updated successfully!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to update church information!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
            } else {
                $data = array("status" => "500", "message" => "Failed to update personal information!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }
        }
    }
    
        
        
        function update_profile_guardian(){
            $stu = $_POST['stu'];
            $kin_name = $_POST['kin_name'];
            $kin_relation = $_POST['kin_relation'];
            $kin_address = $_POST['kin_address'];
            $kin_tel = trim($_POST['kin_tel'], " ");
            $kin_email = trim($_POST['kin_email'], " ");
    
            if($kin_name=="" || $kin_relation=="" || $kin_address=="" || $kin_email==""){
                $data = array("status"=>"401","message" => "Fill the form correctly");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $stmtu = $this->connect->prepare("UPDATE tbl_applicants SET kin_name='".$kin_name."',kin_relation='".$kin_relation."',kin_address='".$kin_address."',kin_tel='".$kin_tel."',kin_email='".$kin_email."' WHERE applicant_id='".$stu."'");
                if($stmtu->execute()){
                    $data = array("status"=>"200","message" => "Profile is updated!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }else{
                    $data = array("status"=>"500","message" => "Failed to update data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
    
            }
        }
        
        function update_profile_contact(){
            $stu=$_POST['stuc'];
            $phone=trim($_POST['phone']," ");
            $email=trim($_POST['email']," ");
            $parent_phone=trim($_POST['parent_phone']," ");
            $ref_phone=trim($_POST['ref_phone']," ");
            if($phone=="" || $email=="" || $parent_phone=="" || $ref_phone=="")
            {
                $data = array("status"=>"401","message" => "Fill the form correctly");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $stmtu = $this->connect->prepare("UPDATE tbl_applicants SET email='".$email."',phone='".$phone."',parent_phone='".$parent_phone."',ref_phone='".$ref_phone."' WHERE applicant_id='".$stu."'");
                if($stmtu->execute()){
                    $data = array("status"=>"200","message" => "Profile is updated!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }else{
                    $data = array("status"=>"500","message" => "Failed to update data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
    
            }
        }
        function update_profile_address(){
            $stu=$_POST['stua'];
            $country=$_POST['country'];
            $province_id=isset($_POST['province_id']) ? $_POST['province_id'] : null;
            $district_id=isset($_POST['district_id']) ? $_POST['district_id'] : null;
            $street=trim($_POST['street'], " ");

            if($country == '167'){
                if($province_id == '' || $province_id == '0' || $district_id == '' || $district_id == '0'){
                    $data = array("status"=>"401","message" => "Please select Province and District.");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                    return;
                }
            } else {
                $province_id = null;
                $district_id = null;
                if($street == ''){
                    $data = array("status"=>"401","message" => "Please enter your Street Number.");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                    return;
                }
            }

          
            $stmtu = $this->connect->prepare("UPDATE tbl_applicants SET country = ?, 
            province_id = ?, district_id = ?, street = ? WHERE applicant_id = ?");
            if($stmtu->execute([$country, $province_id, $district_id, $street, $stu])){
                $data = array("status"=>"200","message" => "Profile is updated!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }else{
                $data = array("status"=>"500","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }
    
        }
            
        function save_education(){
            $stu = $_POST['stu'];
            $school = $_POST['school'];
            $from = $_POST['from'];
            $to = $_POST['to'];
            $award = $_POST['award'];
            $certificate = $_POST['certificate'];
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicant_education WHERE stu = ?");
            $stmt->execute([$stu]);
            
            if($stmt->rowCount()>2){
                $data = array("status"=>"401","message" => "You are allowed 3 entries");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;    
            }
            else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_applicant_education(stu, school, year_from, year_to, award, certificate)VALUES (?, ?, ?, ?, ?, ?)");
                if($stmt->execute([$stu, $school, $from, $to, $award, $certificate])){
                    $data = array("status"=>"200","message" => "Data saved successfully!");
                    $jsonData = json_encode($data);
                    echo $jsonData; 
                } else {
                    $data = array("status"=>"500","message" => "Failed to save data!");
                    $jsonData = json_encode($data);
                    echo $jsonData; 
                }
            }
        }
        
        function delete_education(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("DELETE FROM tbl_applicant_education WHERE id = ?");
            if($stmt->execute([$id])){
                $data = array("status"=>"200","message" => "school removed successfully!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to remove entry!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }
        
        function update_languages(){
            foreach($_POST['lan'] as $lan){
                $stmtu = $this->connect->prepare("UPDATE tbl_language_pro SET state = ? WHERE id = ?");
                $stmtu->execute([$_POST['state_'.$lan], $lan]);
            }
            if(!empty($_POST['other_lang'])){
            $other_lang=$_POST['other_lang'];
            $stu=$_POST['stu'];
            $stmtu2 = $this->connect->prepare("INSERT INTO tbl_language_pro (stu,language,state) VALUES(?,?,?)");
            $stmtu2->execute([$stu,$other_lang,3]);   
            }
            
            $data = array("status"=>"200","message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        
        function report_problem(){
            $app_code     = $_POST['app_code'];
            $url_info     = trim($_POST['url_info']);
            $problem_info = trim($_POST['problem_info']);
        
            if (empty($url_info) || empty($problem_info)) {
                echo json_encode(['status' => 401, 'message' => 'All fields are required.']);
                exit;
            }
        
            $stmt = $this->connect->prepare("INSERT INTO tbl_applicant_prob (app_code, url_info, problem_info) VALUES (:code, :url, :prob)");
            $stmt->bindParam(':code', $app_code);
            $stmt->bindParam(':url',  $url_info);
            $stmt->bindParam(':prob', $problem_info);
        
            if ($stmt->execute()) {
                echo json_encode(['status' => 200, 'message' => 'Problem reported successfully. We will get back to you.']);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to submit. Please try again.']);
            }
            exit;
        }
        
        function update_church(){
            $stu = $_POST['stu'];
            $church = $_POST['church'];
            $country = $_POST['country'];
            $city = $_POST['city'];
            // $sector = $_POST['sector'];
            $licensed = $_POST['licensed'];
            $ordained = $_POST['ordained'];
            $minister = $_POST['minister'];
            $activities = $_POST['activities'];
            
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicant_church WHERE stu = ?");
            $stmt->execute([$stu]);
            
            if($stmt->rowCount() > 0){
                $stmt = $this->connect->prepare("UPDATE tbl_applicant_church SET church = '".$church."', country = '".$country."', city = '".$city."' WHERE stu = '".$stu."'");
            }
            else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_applicant_church(stu, church, country, city, sector, licenced, ordained, minister, activities)VALUES ('".$stu."', '".$church."', '".$country."', '".$city."', '".$sector."', '".$licenced."', '".$ordained."', '".$minister."', '".$activities."')");
            }
            
            if($stmt->execute()){
                $data = array("status" => "200", "message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            } else {
                $data = array("status" => "500", "message" => "Failed to save data!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }
        
        function update_essay(){
            $stu = $_POST['stu'];
            $essay = $_POST['essay'];
            
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicant_essays WHERE stu = ?");
            $stmt->execute([$stu]);
            
            if($stmt->rowCount() > 0){
                $stmt = $this->connect->prepare("UPDATE tbl_applicant_essays SET essay = ? WHERE stu = ?");
            }
            else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_applicant_essays(essay, stu)VALUES (?, ?)");
            }
            
            if($stmt->execute([$essay, $stu])){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save essay!");
                $jsonData = json_encode($data);
                echo $jsonData; 
            }
        }
            
        function save_application() {
        $Stu_code = $_POST['Stu_code'];
        $cump_id = $_POST['camp_id'];
        $prg_type = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $splz_id = $_POST['splz_id'];
        
        // Get Active Academic Cycle
        $selectAcad = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 ORDER BY acad_cycle_id DESC LIMIT 1");
        $selectAcad->execute();
        $acadData = $selectAcad->fetch();
        $acad_id = $acadData['acad_cycle_id'];
        
        // Get Active intake
        $selectIntk = $this->connect->prepare("SELECT * FROM tbl_intake WHERE prg_type = ? ORDER BY intake_id DESC LIMIT 1");
        $selectIntk->execute([$prg_type]);
        $Intkdata = $selectIntk->fetch();
        $getintake_id = $Intkdata['intake_id'];
    
        // Check Application Count
        $checkCount = $this->connect->prepare("SELECT COUNT(*) as app_count FROM tbl_admittedPRG WHERE Stu_code = :Stu_code");
        $checkCount->bindParam(':Stu_code', $Stu_code);
        $checkCount->execute();
        $rowCount = $checkCount->fetch(PDO::FETCH_ASSOC);
        
        if ($rowCount['app_count'] >= 2) {
            // Maximum two applications allowed
            $data = array("status" => "401", "message" => "You have already submitted the maximum of two applications!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
            exit();
        } else {
            // Check for Duplicate Entry
            $checkDuplicate = $this->connect->prepare("SELECT COUNT(*) as duplicate_count 
                                                        FROM tbl_admittedPRG 
                                                        WHERE Stu_code = :Stu_code 
                                                        AND cump_id = :cump_id 
                                                        AND prg_type = :prg_type 
                                                        AND fac_id = :fac_id 
                                                        AND splz = :splz");
            $checkDuplicate->bindParam(':Stu_code', $Stu_code);
            $checkDuplicate->bindParam(':cump_id', $cump_id);
            $checkDuplicate->bindParam(':prg_type', $prg_type);
            $checkDuplicate->bindParam(':fac_id', $fac_id);
            $checkDuplicate->bindParam(':splz', $splz_id);
            $checkDuplicate->execute();
            $rowDuplicate = $checkDuplicate->fetch(PDO::FETCH_ASSOC);
    
            if ($rowDuplicate['duplicate_count'] > 0) {
                // Duplicate Entry Found
                $data = array("status" => "401", "message" => "You have already submitted an application with the same details!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
                exit();
            } else {
                // Get Department
                $select_department = "SELECT * FROM tbl_specialization WHERE fac_id = :fac_id AND prg_type = :prg_type AND splz_id = :splz_id";
                $cselect_department = $this->connect->prepare($select_department);
                $cselect_department->bindParam(':fac_id', $fac_id);
                $cselect_department->bindParam(':prg_type', $prg_type);
                $cselect_department->bindParam(':splz_id', $splz_id);
                $cselect_department->execute();
                $row_cselect_department = $cselect_department->fetch(PDO::FETCH_ASSOC);
                $prog = $row_cselect_department['dept_id'];
                
                // Get Program Level
                $select_level = "SELECT * FROM tbl_level WHERE prg_type = :prg_type AND level_no = 1";
                $cselect_level = $this->connect->prepare($select_level);
                $cselect_level->bindParam(':prg_type', $prg_type);
                $cselect_level->execute();
                $row_cselect_level = $cselect_level->fetch(PDO::FETCH_ASSOC);
                $prg_lvl = $row_cselect_level['level_id'];
                
                // Get Program Mode
                $select_mode = "SELECT * FROM tbl_program_mode WHERE status = 1";
                $cselect_mode = $this->connect->prepare($select_mode);
                $cselect_mode->execute();
                $row_cselect_mode = $cselect_mode->fetch(PDO::FETCH_ASSOC);
                $mode = $row_cselect_mode['prg_mode_id'];
                
                // Insert New Application
                $stmt = $this->connect->prepare("INSERT INTO tbl_admittedPRG (Stu_code, cump_id, intake_id, prg_type, dept_id, level, mode, fac_id, splz, acad_id) 
                                                 VALUES (:Stu_code, :cump_id, :intake_id, :prg_type, :dept_id, :level, :mode, :fac_id, :splz, :acad_id)");
                $stmt->bindParam(':Stu_code', $Stu_code);
                $stmt->bindParam(':cump_id', $cump_id);
                $stmt->bindParam(':intake_id', $getintake_id);
                $stmt->bindParam(':prg_type', $prg_type);
                $stmt->bindParam(':dept_id', $prog);
                $stmt->bindParam(':level', $prg_lvl);
                $stmt->bindParam(':mode', $mode);
                $stmt->bindParam(':fac_id', $fac_id);
                $stmt->bindParam(':splz', $splz_id);
                $stmt->bindParam(':acad_id', $acad_id);
    
                if ($stmt->execute()) {
                    $data = array("status" => "200", "message" => "Application Saved!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to save data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
            }
        }
    }
    
    
        
        function update_application() {
        $app = $_POST['app_id'];
        $Stu_code = $_POST['code'];
        $cump_id = $_POST['camp_id'];
        $prg_type = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $splz = $_POST['splz_id'];
    
        // Check if Application is Under Review
        $checkOptionsCount = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = :app AND sts NOT IN (0, 1)");
        $checkOptionsCount->bindParam(':app', $app);
        $checkOptionsCount->execute();
        
        // Get Active Academic Cycle
        $selectAcad = $this->connect->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 ORDER BY acad_cycle_id DESC LIMIT 1");
        $selectAcad->execute();
        $acadData = $selectAcad->fetch();
        $acad_id = $acadData['acad_cycle_id'];
        
        // Get Active intake
        $selectIntk = $this->connect->prepare("SELECT * FROM tbl_intake WHERE prg_type = ? ORDER BY intake_id DESC LIMIT 1");
        $selectIntk->execute([$prg_type]);
        $Intkdata = $selectIntk->fetch();
        $getintake_id = $Intkdata['intake_id'];
        
        if ($checkOptionsCount->rowCount() == 1) {
            $data = array("status" => "401", "message" => "Your application is under review!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
            exit();
        } else {
            // Get Department ID
            $select_department = "SELECT * FROM tbl_specialization WHERE fac_id = :fac_id AND prg_type = :prg_type AND splz_id = :splz";
            $cselect_department = $this->connect->prepare($select_department);
            $cselect_department->bindParam(':fac_id', $fac_id);
            $cselect_department->bindParam(':prg_type', $prg_type);
            $cselect_department->bindParam(':splz', $splz);
            $cselect_department->execute();
            $row_cselect_department = $cselect_department->fetch(PDO::FETCH_ASSOC);
            $prog = $row_cselect_department['dept_id'];
            
            // Get Program Level
            $select_level = "SELECT * FROM tbl_level WHERE prg_type = :prg_type AND level_no = 1";
            $cselect_level = $this->connect->prepare($select_level);
            $cselect_level->bindParam(':prg_type', $prg_type);
            $cselect_level->execute();
            $row_cselect_level = $cselect_level->fetch(PDO::FETCH_ASSOC);
            $prg_lvl = $row_cselect_level['level_id'];
            
            // Get Program Mode
            $select_mode = "SELECT * FROM tbl_program_mode WHERE status = 1";
            $cselect_mode = $this->connect->prepare($select_mode);
            $cselect_mode->execute();
            $row_cselect_mode = $cselect_mode->fetch(PDO::FETCH_ASSOC);
            $mode = $row_cselect_mode['prg_mode_id'];
    
            // Check if Same Details Already Exist
            $checkExists = $this->connect->prepare("SELECT COUNT(*) as existing_count 
                                                    FROM tbl_admittedPRG 
                                                    WHERE Stu_code = :Stu_code 
                                                    AND cump_id = :cump_id 
                                                    AND prg_type = :prg_type 
                                                    AND dept_id = :dept_id 
                                                    AND level = :level 
                                                    AND mode = :mode 
                                                    AND fac_id = :fac_id 
                                                    AND splz = :splz 
                                                    AND Aprg_id = :app");
            $checkExists->bindParam(':Stu_code', $Stu_code);
            $checkExists->bindParam(':cump_id', $cump_id);
            $checkExists->bindParam(':prg_type', $prg_type);
            $checkExists->bindParam(':dept_id', $prog);
            $checkExists->bindParam(':level', $prg_lvl);
            $checkExists->bindParam(':mode', $mode);
            $checkExists->bindParam(':fac_id', $fac_id);
            $checkExists->bindParam(':splz', $splz);
            $checkExists->bindParam(':app', $app);
            $checkExists->execute();
            
            $rowCheck = $checkExists->fetch(PDO::FETCH_ASSOC);
            if ($rowCheck['existing_count'] > 0) {
                $data = array("status" => "401", "message" => "Nothing changed!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
                exit();
            } else {
                // Proceed to Update the Application
                $update_application = $this->connect->prepare("UPDATE tbl_admittedPRG 
                                                              SET cump_id = :cump_id, 
                                                                  intake_id = :intake_id,
                                                                  acad_id = :acad_id,
                                                                  prg_type = :prg_type, 
                                                                  dept_id = :dept_id, 
                                                                  level = :level, 
                                                                  mode = :mode, 
                                                                  fac_id = :fac_id, 
                                                                  splz = :splz 
                                                              WHERE Aprg_id = :app");
                $update_application->bindParam(':cump_id', $cump_id);
                $update_application->bindParam(':intake_id', $getintake_id);
                $update_application->bindParam(':acad_id', $acad_id);
                $update_application->bindParam(':prg_type', $prg_type);
                $update_application->bindParam(':dept_id', $prog);
                $update_application->bindParam(':level', $prg_lvl);
                $update_application->bindParam(':mode', $mode);
                $update_application->bindParam(':fac_id', $fac_id);
                $update_application->bindParam(':splz', $splz);
                $update_application->bindParam(':app', $app);
                
                if ($update_application->execute()) {
                    $data = array("status" => "200", "message" => "Changes Saved!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to save changes!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
            }
        }
    }
    
    
    
    	function remove_application() {
        $aid = $_POST['app'];
        error_log($aid);
    
        // Check if Application Exists and Status is 1
        $stmt = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = :aid AND sts = 1");
        $stmt->bindParam(':aid', $aid);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Allow Deletion Only if ID is 1
        if ($data && $data['sts'] == 1) {
            $deleteStmt = $this->connect->prepare("DELETE FROM tbl_admittedPRG WHERE Aprg_id = :aid");
            $deleteStmt->bindParam(':aid', $aid);
    
            if ($deleteStmt->execute()) {
                $response = array("status" => "200", "message" => "Application removed successfully!");
                echo json_encode($response);
            } else {
                $response = array("status" => "500", "message" => "Failed to remove application!");
                echo json_encode($response);
            }
        } else {
            // Unauthorized action if not found or ID is not 1
            $response = array("status" => "401", "message" => "Unauthorized action!");
            echo json_encode($response);
        }
    }
    
        function view_application(){
            $app=$_POST['id'];
            $info = array();
            //app data
            $stmt = $this->connect->prepare("SELECT * FROM tbl_admittedPRG where Aprg_id='".$app."'");
            $stmt->execute();
            $appData = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$appData) {
                header('Content-Type: application/json');
                echo json_encode(array("status" => "404", "message" => "Application not found"));
                return;
            }
            //compus 
            $select_campus="SELECT * FROM tbl_campus WHERE camp_id='".$appData['cump_id']."'";
            $cselect_campus=$this->connect->prepare($select_campus);
            $cselect_campus->execute();
            $row_cselect_campus=$cselect_campus->fetchAll(PDO::FETCH_ASSOC);
            //program types
            $stmt1 = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$appData['cump_id']."' AND status = 1");
            $stmt1->execute();
            $prgs = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            //school
            $select_school="SELECT * FROM tbl_faculty WHERE prg_type='".$appData['prg_type']."'";
            $cselect_school=$this->connect->prepare($select_school);
            $cselect_school->execute();
            $row_cselect_school=$cselect_school->fetchAll(PDO::FETCH_ASSOC);
            //department
            $select_depart="SELECT * FROM tbl_department WHERE fac_id='".$appData['fac_id']."' AND prg_type='".$appData['prg_type']."'";
            $cselect_depart=$this->connect->prepare($select_depart);
            $cselect_depart->execute();
            $row_cselect_departl=$cselect_depart->fetchAll(PDO::FETCH_ASSOC);
            //level
            $select_level="SELECT * FROM tbl_level WHERE prg_type='".$appData['prg_type']."'";
            $cselect_level=$this->connect->prepare($select_level);
            $cselect_level->execute();
            $row_cselect_level=$cselect_level->fetchAll(PDO::FETCH_ASSOC);
            //specialization
            $select_spec="SELECT * FROM tbl_specialization WHERE prg_type='".$appData['prg_type']."' AND fac_id='".$appData['fac_id']."'";
             $cselect_spec=$this->connect->prepare($select_spec);
             $cselect_spec->execute();
             $row_cselect_spec=$cselect_spec->fetchAll(PDO::FETCH_ASSOC);
             //mode
             $select_mode="SELECT * FROM tbl_program_mode WHERE status=1";
             $cselect_mode=$this->connect->prepare($select_mode);
             $cselect_mode->execute();
             $row_cselect_mode=$cselect_mode->fetchAll(PDO::FETCH_ASSOC);
            
    
            
            
            array_push($info, $appData, $row_cselect_campus, $prgs, $row_cselect_school, $row_cselect_departl,$row_cselect_level,$row_cselect_spec,$row_cselect_mode);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        
        function submit(){
            $code = $_POST['code'];
            $stmt = $this->connect->prepare("UPDATE tbl_applicants SET submitted =1 WHERE code='".$code."'");
            if ($stmt->execute()) {
                $this->send_submission_email($code);
                echo "Application submitted succesfully!";
            }
            else{
                echo "Failed to submit application; retry!";
            }
        }
        
      function save_waec_result() {
        $code = $_POST['stu'];
        $waec_result1 = $_POST['waec_result1'];
        $waec_pin1 = $_POST['waec_pin1'];
        $waec_result2 = $_POST['waec_result2'];
        $waec_pin2 = $_POST['waec_pin2'];
        $first_exam_year = $_POST['first_exam_year'];
        $exam_body1 = $_POST['exam_body1'];
        $other_body_name1 = $_POST['other_body_name1'];
        $exam_type1 = $_POST['exam_type1'];
        $other_exm_type1 = $_POST['other_exm_type1'];
        $exam1_id = $_POST['exam1_id'];
        $second_exam_year2 = $_POST['second_exam_year2'];
        $exam_body2 = $_POST['exam_body2'];
        $other_body_name2 = $_POST['other_body_name2'];
        $exam_type2 = $_POST['exam_type2'];
        $other_exm_type2 = $_POST['other_exm_type2'];
        $exam2_id = $_POST['exam2_id'];
    
        $stt00 = $this->connect->prepare("SELECT * FROM tbl_applicant_education WHERE stu = ?");
        $stt00->execute([$code]);
        $row00 = $stt00->rowCount();
    
        if ($row00 > 0) {
            $stt01 = $this->connect->prepare("UPDATE tbl_applicant_education SET 
                waec_result1 = ?,pin1=?,waec_resilt2=?,	pin2=?,first_exam_year=?,exam_body1=?,other_body_name1=?,exam_type1=?,other_exm_type1=?,exam1_id=?,sec_exam_year2=?,
                exam_body2=?,other_body_name2=?,exam_type2=?,other_exm_type2=?,exam2_id=?
                WHERE stu = ?");
            
            $result = $stt01->execute([
                $waec_result1,$waec_pin1,$waec_result2,$waec_pin2,$first_exam_year, $exam_body1,$other_body_name1,$exam_type1,$other_exm_type1,$exam1_id,$second_exam_year2,
                $exam_body2,$other_body_name2,$exam_type2,$other_exm_type2,$exam2_id,$code
            ]);
        } else {
            $sttt02 = $this->connect->prepare("INSERT INTO tbl_applicant_education (
                stu, waec_result1, pin1, waec_resilt2, pin2, first_exam_year, exam_body1, other_body_name1, 
                exam_type1, other_exm_type1, exam1_id, sec_exam_year2, exam_body2, other_body_name2, exam_type2, 
                other_exm_type2, exam2_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $result = $sttt02->execute([
                $code, $waec_result1, $waec_pin1, $waec_result2, $waec_pin2, $first_exam_year,
                $exam_body1, $other_body_name1, $exam_type1, $other_exm_type1, $exam1_id,
                $second_exam_year2, $exam_body2, $other_body_name2, $exam_type2, $other_exm_type2,
                $exam2_id
            ]);
        }
    
        if ($result) {
            $data = array("status" => 200, "message" => "Data saved!");
        } else {
            $data = array("status" => 500, "message" => "Action failed!");
        }
    
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    function update_payt(){
        $code=$_POST['stu'];
        $vocher=$_POST['voucher_number'];
        $paidDate=$_POST['paid_date'];
        $stmt=$this->connect->prepare("UPDATE tbl_applicants SET pay_slip='".$vocher."',payt_date='".$paidDate."' WHERE code='".$code."'");
        $result=$stmt->execute();
        if ($result) {
            $data = array("status" => 200, "message" => "Data saved!");
        } else {
            $data = array("status" => 500, "message" => "Action failed!");
        }
    }
    
    function email_check() {
        header('Content-Type: application/json'); 
    
        $email = trim($_POST['email']);
    
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email = ?");
        $stmt->execute([$email]);
    
        $stmt1 = $this->connect->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt1->execute([$email]);
    
        $stmt4 = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE email = ?");
        $stmt4->execute([$email]);
    
        $status = ($stmt->rowCount() > 0 || $stmt1->rowCount() > 0 || $stmt4->rowCount() > 0) ? 401 : 200;
        $message = ($status === 401) ? "Email already exists!" : "Email is allowed!";
    
        echo json_encode(["status" => $status, "message" => $message]);
        exit;
    }
    function reg_check() {
        header('Content-Type: application/json'); 
    
        $reg_no = trim($_POST['reg_no']);
    
        $stmt = $this->connect->prepare("SELECT * FROM tbl_admission WHERE reg_no = ?");
        $stmt->execute([$reg_no]);
    
        $status = ($stmt->rowCount() > 0 ) ? 401 : 200;
        $message = ($status === 401) ? "MOT NO already exists!" : "MOT NO is allowed!";
    
        echo json_encode(["status" => $status, "message" => $message]);
        exit;
    }
    function student_semester_apply()
    {
        try {
            // Start Transaction
            $this->connect->beginTransaction();
    
            // Section 1 - Student Details
            $lname = trim($_POST['lname']);
            $mname = trim($_POST['mname']);
            $fname = trim($_POST['fname']);
            $nid = trim($_POST['nid']);
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $phone = trim($_POST['phone']);
            $email = trim($_POST['email']);
            $nationality = trim($_POST['nationality']);
            $country = trim($_POST['country']);
            $father_name = trim($_POST['father_name']);
            $mother_name = trim($_POST['mother_name']);
            $parent_phone = trim($_POST['parent_phone']);
            $ref_phone = trim($_POST['ref_phone']);
    
            // Section 2 - Academic Details
            $reg_no = trim($_POST['reg_no']);
            $camp_id = $_POST['camp_id'];
            $prg_type_id = $_POST['prg_type_id'];
            $fac_id = $_POST['fac_id'];
            $dept_id = $_POST['dept_id'];
            $splz_id = $_POST['splz_id'];
            $level_id = $_POST['level_id'];
            $courses = $_POST['courses']; // Array of courses
            $course_codes = $_POST['course_codes']; // Array of course codes
    
            // Section 3 - Payments
            
            $semester = $_POST['semester'];
            $sponsor = $_POST['sponsor'];
            $payment_names = $_POST['payment_name'] ?? [];
            $slip_numbers = $_POST['slip_number'] ?? [];
            $amounts_paid = $_POST['amount_paid'] ?? [];
    
            // Fetch Academic Cycle ID
            $select_academic = "SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1";
            $cselect_academic = $this->connect->prepare($select_academic);
            $cselect_academic->execute();
            $acad_cycle = $cselect_academic->fetch(PDO::FETCH_ASSOC);
            $acad_cycle_id = $acad_cycle['acad_cycle_id'];
    
            // Fetch Active Semester
            $select_semester = "SELECT semester FROM tbl_semester WHERE acad_year = ? AND status = 1";
            $cselect_semester = $this->connect->prepare($select_semester);
            $cselect_semester->execute([$acad_cycle_id]);
            $row_cselect_semester = $cselect_semester->fetch(PDO::FETCH_ASSOC);
            $term = $row_cselect_semester['semester'];
    
            // Insert Student Details
            $studentSQL = "INSERT INTO tbl_admission (reg_no, acad_cycle_id, fname, mname, lname, email, ID, nationality, gender, father_names, mother_names, parent_phone, ref_phone, country) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect->prepare($studentSQL);
            $stmt->execute([$reg_no, $acad_cycle_id, $fname, $mname, $lname, $email, $nid, $nationality, $gender, $father_name, $mother_name, $parent_phone, $ref_phone, $country]);
    
            // Insert Courses (Avoid Duplicates)
            foreach ($courses as $index => $course) {
                $course_code = strtoupper(trim($course_codes[$index]));
                $course_name = strtolower(trim($course));
    
                $check_course = "SELECT id FROM modules WHERE prg_type = ? AND dept_id = ? AND LOWER(module_name) = ?";
                $cselect_before_co = $this->connect->prepare($check_course);
                $cselect_before_co->execute([$prg_type_id, $dept_id, $course_name]);
                $existing_course = $cselect_before_co->fetch(PDO::FETCH_ASSOC);
    
                if (!$existing_course) {
                    // Insert new course
                        $insert_course = "INSERT INTO modules (prg_type, dept_id, module_code, module_name) VALUES (?, ?, ?, ?)";
                    $stmt_course = $this->connect->prepare($insert_course);
                    $stmt_course->execute([$prg_type_id, $dept_id, $course_code, $course_name]);
                    $mod_id = $this->connect->lastInsertId();
                } else {
                    $mod_id = $existing_course['id'];
                }
    
                // Insert into `tbl_modules`
                $insert_module = "INSERT INTO tbl_modules (prg_type, fac_id, dept_id, level_id, term_id, mod_id, splz_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_module = $this->connect->prepare($insert_module);
                $stmt_module->execute([$prg_type_id, $fac_id, $dept_id, $level_id, $term, $mod_id, $splz_id]);
            }
    
    
            $upload_dir = "uploads/receipts/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
            }
            // Before the payment loop
    if (count($payment_names) !== count($slip_numbers) || 
        count($payment_names) !== count($amounts_paid)) {
        throw new Exception("Payment data arrays have mismatched lengths");
    }
            foreach ($payment_names as $index => $payment_name) {
        $slip_number = $slip_numbers[$index];
        $amount_paid = $amounts_paid[$index];
    
        // File upload
        if (empty($_FILES['receipt']['name'][$index])) {
            throw new Exception("Receipt file is required for payment $index");
        }
        
        $file_name = basename($_FILES['receipt']['name'][$index]);
        $receipt_filename = time() . "_" . $file_name;
        $target_path = $upload_dir . $receipt_filename;
        
        if (!move_uploaded_file($_FILES['receipt']['tmp_name'][$index], $target_path)) {
            throw new Exception("File upload failed: " . $file_name);
        }
    
        // Insert invoice
        $invoice_sql = "INSERT INTO tbl_invoice 
                       (reg_no, acad_cycle_id, level_id, fee_id, balance)
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($invoice_sql);
        $stmt->execute([
            $reg_no,
            $acad_cycle_id,
            $level_id,
            $payment_name,
            $amount_paid
        ]);
        $invoice_id = $this->connect->lastInsertId();
    
        // Insert payment
        $payment_sql = "INSERT INTO payment 
                       (invoice_id, reg_no, acad_cycle_id, slip_no, bank_id, fee_id, amount, receipt_path)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($payment_sql);
        $stmt->execute([
            $invoice_id,
            $reg_no,
            $acad_cycle_id,
            $slip_number,
            1,  // Assuming default bank_id
            $payment_name,
            $amount_paid,
            $receipt_filename
        ]);
    }
    
            // Insert Semester Registration
            $prg_mode_id=1;
            $insert_semester = "INSERT INTO tbl_student_semester (reg_no, acad_cycle_id, prg_type, splz_id, fac_id, dept_id, level_id, prg_mode_id, campus, sem_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_semester = $this->connect->prepare($insert_semester);
            $stmt_semester->execute([$reg_no, $acad_cycle_id, $prg_type_id, $splz_id, $fac_id, $dept_id, $level_id, $prg_mode_id, $camp_id, $term]);
    
            // Commit Transaction
            $this->connect->commit();
            echo json_encode(["status" => 200, "message" => "Student registered successfully"]);
    
        } catch (Exception $e) {
            $this->connect->rollBack(); 
            echo json_encode(["status" => 500, "message" => "Error: " . $e->getMessage()]);
        }
    }
    
    function applicant_type() {
        $applicant_id = $_POST['stuat'];
        $app_type = $_POST['app_type'];
        
        // Use prepared statements with placeholders
        $stmt = $this->connect->prepare("UPDATE tbl_applicants SET app_type = :app_type WHERE applicant_id = :applicant_id");
        
        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':app_type', $app_type);
        $stmt->bindParam(':applicant_id', $applicant_id);
        
        // Execute the query and check the result
        if ($stmt->execute()) {
            $data = array("status" => "200", "message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            $data = array("status" => "500", "message" => "Failed to save data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
    }
    
    function applicant_group() {
        $applicant_id = $_POST['stuab'];
        $blood_group = $_POST['blood_group'];
        $disability = $_POST['disability'];
        
        if ($disability == "No") {
            $disability_detail = null;
        } else {
            $disability_detail = $_POST['disability_detail'];
        }
        
        
        // Use prepared statements with placeholders
        $stmt = $this->connect->prepare("UPDATE tbl_applicants SET blood_group = :blood_group, disability = :disability,disability_detail=:disability_detail WHERE applicant_id = :applicant_id");
        
        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':blood_group', $blood_group);
        $stmt->bindParam(':disability', $disability);
        $stmt->bindParam(':disability_detail', $disability_detail);
        $stmt->bindParam(':applicant_id', $applicant_id);
        
        // Execute the query and check the result
        if ($stmt->execute()) {
            $data = array("status" => "200", "message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            $data = array("status" => "500", "message" => "Failed to save data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
    }
    
    function applicant_prev(){
        $Stu_code=$_POST['Stu_code'];
        $prev_code=$_POST['prev_code'];
        $cump_id=$_POST['cump_id'];
        $program=$_POST['program'];
        $school=$_POST['school'];
        $specialization=$_POST['specialization'];
        $matriculation_status=$_POST['matriculation_status'];
        $admission_status=$_POST['admission_status'];
        $year_from=$_POST['year_from'];
        $year_to=$_POST['year_to'];
        
        $select_before="SELECT * FROM tbl_admittedPRG_prev WHERE Stu_code='$Stu_code'";
        $cselect_before=$this->connect->prepare($select_before);
        $cselect_before->execute();
        $row_cselect_before=$cselect_before->fetch(PDO::FETCH_ASSOC);
        if($row_cselect_before){
            $data = array("status" => "401", "message" => "Matriculation Details Already Exit!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
        else{
            
                
            $insert="INSERT INTO `tbl_admittedPRG_prev`(`Stu_code`, `prev_code`, `cump_id`, `program`, `school`, `specialization`,`matriculation_status`, `year_from`, `year_to`, `admission_status`)
            VALUES (:Stu_code,:prev_code,:cump_id,:program,:school,:specialization,:matriculation_status,:year_from,:year_to,:admission_status)";
            $cinsert=$this->connect->prepare($insert);
            $insert_data=[
                ':Stu_code'=>$Stu_code,
                ':prev_code'=>$prev_code,
                ':cump_id'=>$cump_id,
                ':program'=>$program,
                ':school'=>$school,
                ':specialization'=>$specialization,
                ':matriculation_status'=>$matriculation_status,
                ':year_from'=>$year_from,
                ':year_to'=>$year_to,
                ':admission_status'=>$admission_status
                ];
            if ($cinsert->execute($insert_data)) {
            $data = array("status" => "200", "message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            $data = array("status" => "500", "message" => "Failed to save data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
            
            
        }
        
             
        
    }
     function applicant_prev_view(){
        $id = $_POST['id'];
        
        $select_prev = "SELECT * FROM tbl_admittedPRG_prev WHERE prev_id = :id";
        $cselect_prev = $this->connect->prepare($select_prev);
        $cselect_prev->bindParam(':id', $id, PDO::PARAM_INT);
        $cselect_prev->execute();
        $row_cselect_prev = $cselect_prev->fetchAll(PDO::FETCH_ASSOC);
    
        // Directly encode the fetched data without wrapping it in another array
        header('Content-Type: application/json');
        echo json_encode($row_cselect_prev);
    }
    
    function applicant_prev_update(){
        // Retrieve POST data securely
        $prev_id = $_POST['prev_id'];
        $Stu_code = $_POST['Stu_code'];
        $prev_code = $_POST['prev_code'];
        $cump_id = $_POST['cump_id'];
        $program = $_POST['program'];
        $school = $_POST['school'];
        $specialization = $_POST['specialization'];
        $matriculation_status = $_POST['matriculation_status'];
        $admission_status = $_POST['admission_status'];
        $year_from = $_POST['year_from'];
        $year_to = $_POST['year_to'];
        
        try {
            // Check if any changes are made
            $select_prev = "SELECT * FROM tbl_admittedPRG_prev 
                            WHERE prev_id = :prev_id 
                            AND Stu_code = :Stu_code
                            AND prev_code = :prev_code
                            AND cump_id = :cump_id
                            AND program = :program
                            AND school = :school
                            AND specialization = :specialization
                            AND matriculation_status = :matriculation_status
                            AND admission_status = :admission_status
                            AND year_from = :year_from
                            AND year_to = :year_to";
    
            $cselect_prev = $this->connect->prepare($select_prev);
            $cselect_prev->bindParam(':prev_id', $prev_id, PDO::PARAM_INT);
            $cselect_prev->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cselect_prev->bindParam(':prev_code', $prev_code, PDO::PARAM_STR);
            $cselect_prev->bindParam(':cump_id', $cump_id, PDO::PARAM_STR);
            $cselect_prev->bindParam(':program', $program, PDO::PARAM_STR);
            $cselect_prev->bindParam(':school', $school, PDO::PARAM_STR);
            $cselect_prev->bindParam(':specialization', $specialization, PDO::PARAM_STR);
            $cselect_prev->bindParam(':matriculation_status', $matriculation_status, PDO::PARAM_STR);
            $cselect_prev->bindParam(':admission_status', $admission_status, PDO::PARAM_STR);
            $cselect_prev->bindParam(':year_from', $year_from, PDO::PARAM_INT);
            $cselect_prev->bindParam(':year_to', $year_to, PDO::PARAM_INT);
    
            $cselect_prev->execute();
            
            // If no changes detected
            if($cselect_prev->rowCount() > 0){
                $data = array("status" => "401", "message" => "No Change Made!");
                echo json_encode($data);
                return;
            }
            
            // Update the record
            $update = "UPDATE tbl_admittedPRG_prev 
                       SET prev_code = :prev_code, 
                           cump_id = :cump_id,
                           program = :program,
                           school = :school,
                           specialization = :specialization,
                           matriculation_status = :matriculation_status,
                           admission_status = :admission_status,
                           year_from = :year_from,
                           year_to = :year_to,
                           Stu_code = :Stu_code
                       WHERE prev_id = :prev_id";
    
            $cupdate = $this->connect->prepare($update);
            $cupdate->bindParam(':prev_code', $prev_code, PDO::PARAM_STR);
            $cupdate->bindParam(':cump_id', $cump_id, PDO::PARAM_STR);
            $cupdate->bindParam(':program', $program, PDO::PARAM_STR);
            $cupdate->bindParam(':school', $school, PDO::PARAM_STR);
            $cupdate->bindParam(':specialization', $specialization, PDO::PARAM_STR);
            $cupdate->bindParam(':matriculation_status', $matriculation_status, PDO::PARAM_STR);
            $cupdate->bindParam(':admission_status', $admission_status, PDO::PARAM_STR);
            $cupdate->bindParam(':year_from', $year_from, PDO::PARAM_INT);
            $cupdate->bindParam(':year_to', $year_to, PDO::PARAM_INT);
            $cupdate->bindParam(':prev_id', $prev_id, PDO::PARAM_INT);
            $cupdate->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
    
            if($cupdate->execute()){
                $data = array("status" => "200", "message" => "Data Updated successfully!");
                echo json_encode($data);
            } else {
                $data = array("status" => "500", "message" => "Failed to Update data!");
                echo json_encode($data);
            }
    
        } catch (PDOException $e) {
            $data = array("status" => "500", "message" => "Error: " . $e->getMessage());
            echo json_encode($data);
        }
    }
    function familly_add(){
        // Retrieve POST data
        $Stu_code = $_POST['Stu_code'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $Relationship = $_POST['Relationship'];
        $Education = $_POST['Education'];
        $Occupation = $_POST['Occupation'];
        $Address = $_POST['Address'];
        $Phone = $_POST['Phone'];
        $Email = $_POST['Email'];
        
        try {
            // Check if the user already has a record
            $check_exists = "SELECT * FROM applicant_family_sponsor WHERE Stu_code = :Stu_code";
            $cselect_before = $this->connect->prepare($check_exists);
            $cselect_before->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cselect_before->execute();
            
            // If a record already exists, do not allow a new one
            if($cselect_before->rowCount() > 0){
                $data = array("status" => "401", "message" => "You are only allowed to add one family record.");
                echo json_encode($data);
                return;
            }
    
            // Proceed to insert the new record
            $insert = "INSERT INTO applicant_family_sponsor (Stu_code, first_name, last_name, Relationship, Education, Occupation, Address, Phone, Email)
                       VALUES (:Stu_code, :first_name, :last_name, :Relationship, :Education, :Occupation, :Address, :Phone, :Email)";
    
            $cinsert = $this->connect->prepare($insert);
            $cinsert->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cinsert->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $cinsert->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $cinsert->bindParam(':Relationship', $Relationship, PDO::PARAM_STR);
            $cinsert->bindParam(':Education', $Education, PDO::PARAM_STR);
            $cinsert->bindParam(':Occupation', $Occupation, PDO::PARAM_STR);
            $cinsert->bindParam(':Address', $Address, PDO::PARAM_STR);
            $cinsert->bindParam(':Phone', $Phone, PDO::PARAM_STR);
            $cinsert->bindParam(':Email', $Email, PDO::PARAM_STR);
    
            if($cinsert->execute()){
                $data = array("status" => "200", "message" => "Family member added successfully!");
                $jsonData = json_encode($data);
                echo $jsonData;
            } else {
                $data = array("status" => "500", "message" => "Failed to add family member!");
                $jsonData = json_encode($data);
                echo $jsonData;
            }
    
        } catch (PDOException $e) {
            $data = array("status" => "500", "message" => "Error: " . $e->getMessage());
            echo json_encode($data);
        }
    }
    
    function view_family(){
        $id = $_POST['id'];
        
        $select_prev = "SELECT * FROM applicant_family_sponsor WHERE fs_id = :id";
        $cselect_prev = $this->connect->prepare($select_prev);
        $cselect_prev->bindParam(':id', $id, PDO::PARAM_INT);
        $cselect_prev->execute();
        $row_cselect_prev = $cselect_prev->fetchAll(PDO::FETCH_ASSOC);
    
        // Directly encode the fetched data without wrapping it in another array
        header('Content-Type: application/json');
        echo json_encode($row_cselect_prev);
    }
    function familly_update(){
        // Retrieve POST data
        $Stu_code = $_POST['Stu_code'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $Relationship = $_POST['Relationship'];
        $Education = $_POST['Education'];
        $Occupation = $_POST['Occupation'];
        $Address = $_POST['Address'];
        $Phone = $_POST['Phone'];
        $Email = $_POST['Email'];
        
        try {
            // Check if the record exists for the given Stu_code
            $check_exists = "SELECT * FROM applicant_family_sponsor WHERE Stu_code = :Stu_code";
            $cselect_before = $this->connect->prepare($check_exists);
            $cselect_before->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cselect_before->execute();
            
            // If no record is found, return an error
            if($cselect_before->rowCount() == 0){
                $data = array("status" => "404", "message" => "No record found to update.");
                echo json_encode($data);
                return;
            }
    
            // Proceed to update the existing record
            $update = "UPDATE applicant_family_sponsor 
                       SET first_name = :first_name,
                           last_name = :last_name,
                           Relationship = :Relationship,
                           Education = :Education,
                           Occupation = :Occupation,
                           Address = :Address,
                           Phone = :Phone,
                           Email = :Email
                       WHERE Stu_code = :Stu_code";
    
            $cupdate = $this->connect->prepare($update);
            $cupdate->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cupdate->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $cupdate->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $cupdate->bindParam(':Relationship', $Relationship, PDO::PARAM_STR);
            $cupdate->bindParam(':Education', $Education, PDO::PARAM_STR);
            $cupdate->bindParam(':Occupation', $Occupation, PDO::PARAM_STR);
            $cupdate->bindParam(':Address', $Address, PDO::PARAM_STR);
            $cupdate->bindParam(':Phone', $Phone, PDO::PARAM_STR);
            $cupdate->bindParam(':Email', $Email, PDO::PARAM_STR);
    
            if($cupdate->execute()){
                $data = array("status" => "200", "message" => "Family member updated successfully!");
                $jsonData = json_encode($data);
                echo $jsonData;
            } else {
                $data = array("status" => "500", "message" => "Failed to update family member!");
                $jsonData = json_encode($data);
                echo $jsonData;
            }
    
        } catch (PDOException $e) {
            $data = array("status" => "500", "message" => "Error: " . $e->getMessage());
            echo json_encode($data);
        }
    }
    function sponsor_add(){
        // Retrieve POST data
        $Stu_code = $_POST['Stu_code'];
        $s_first_name = $_POST['s_first_name'];
        $s_last_name = $_POST['s_last_name'];
        $s_Phone = $_POST['s_Phone'];
        $s_Address = $_POST['s_Address'];
        
        try {
            // Check if the record already exists
            $select_before = "SELECT * FROM applicant_family_sponsor WHERE Stu_code = :Stu_code";
            $cselect_before = $this->connect->prepare($select_before);
            $cselect_before->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
            $cselect_before->execute();
            $row_cselect_before = $cselect_before->fetch(PDO::FETCH_ASSOC);
    
            // If record exists, update it
            if($row_cselect_before){
                $update = "UPDATE applicant_family_sponsor 
                           SET s_first_name = :s_first_name,
                               s_last_name = :s_last_name,
                               s_Phone = :s_Phone,
                               s_Address = :s_Address 
                           WHERE Stu_code = :Stu_code";
                $cupdate = $this->connect->prepare($update);
                $cupdate->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
                $cupdate->bindParam(':s_first_name', $s_first_name, PDO::PARAM_STR);
                $cupdate->bindParam(':s_last_name', $s_last_name, PDO::PARAM_STR);
                $cupdate->bindParam(':s_Phone', $s_Phone, PDO::PARAM_STR);
                $cupdate->bindParam(':s_Address', $s_Address, PDO::PARAM_STR);
    
                if($cupdate->execute()){
                    $data = array("status" => "200", "message" => "Sponsor updated successfully!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to update sponsor!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                }
            } 
            // If no record exists, insert a new one
            else {
                $insert = "INSERT INTO applicant_family_sponsor 
                           (Stu_code, s_first_name, s_last_name, s_Phone, s_Address) 
                           VALUES (:Stu_code, :s_first_name, :s_last_name, :s_Phone, :s_Address)";
                $cinsert = $this->connect->prepare($insert);
                $cinsert->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
                $cinsert->bindParam(':s_first_name', $s_first_name, PDO::PARAM_STR);
                $cinsert->bindParam(':s_last_name', $s_last_name, PDO::PARAM_STR);
                $cinsert->bindParam(':s_Phone', $s_Phone, PDO::PARAM_STR);
                $cinsert->bindParam(':s_Address', $s_Address, PDO::PARAM_STR);
    
                if($cinsert->execute()){
                    $data = array("status" => "200", "message" => "Sponsor added successfully!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to add sponsor!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                }
            }
        } catch (PDOException $e) {
            $data = array("status" => "500", "message" => "Error: " . $e->getMessage());
            echo json_encode($data);
        }
    }
    
    function sponsor_update(){
        
        // Retrieve POST data
        $Stu_code = $_POST['Stu_code'];
        $s_first_name = $_POST['s_first_name'];
        $s_last_name = $_POST['s_last_name'];
        $s_Phone = $_POST['s_Phone'];
        $s_Address = $_POST['s_Address'];
        
        try {
            
                $update = "UPDATE applicant_family_sponsor 
                           SET s_first_name = :s_first_name,
                               s_last_name = :s_last_name,
                               s_Phone = :s_Phone,
                               s_Address = :s_Address 
                           WHERE Stu_code = :Stu_code";
                $cupdate = $this->connect->prepare($update);
                $cupdate->bindParam(':Stu_code', $Stu_code, PDO::PARAM_STR);
                $cupdate->bindParam(':s_first_name', $s_first_name, PDO::PARAM_STR);
                $cupdate->bindParam(':s_last_name', $s_last_name, PDO::PARAM_STR);
                $cupdate->bindParam(':s_Phone', $s_Phone, PDO::PARAM_STR);
                $cupdate->bindParam(':s_Address', $s_Address, PDO::PARAM_STR);
    
                if($cupdate->execute()){
                    $data = array("status" => "200", "message" => "Sponsor updated successfully!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                } else {
                    $data = array("status" => "500", "message" => "Failed to update sponsor!");
                    $jsonData = json_encode($data);
                    echo $jsonData;
                }
             
            
        } catch (PDOException $e) {
            $data = array("status" => "500", "message" => "Error: " . $e->getMessage());
            echo json_encode($data);
        }
        
    }
    
     
    function resend_verification(){
        $email = $_POST['email'];
        
        // Delete existing verification tokens for this email
        $stmt0 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email = ?");
        $stmt0->execute([$email]);
        
        // Get applicant data
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email = ?");
        $stmt->execute([$email]);
        
        if($stmt->rowCount() != 0) {
            $appData = $stmt->fetch();
            $expirationTime = date('Y-m-d H:i:s', strtotime('+48 hour'));
            
            // Insert new verification token with updated expiration
            $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt5->execute([$email, $appData['code'], $expirationTime]);
            
            // Get university data
            $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
            $sql->execute();
            $data = $sql->fetch();
            
            // Prepare cURL request
            $curl = curl_init();
            $verification_link = isset($_POST['verification_link']) ? $_POST['verification_link'] : 'verify_email?em=' . urlencode($email);
            $message = '
                    <div style="padding: 10px;border: 1px solid lightgray;width: 500px;">
                    <table>
                    <thead>
                    <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td>
                               <p>Dear <b>'.$appData['fname'].'</b></p>
                               
                               <p>Thank you for your patience with our verification system. We appreciate your understanding as we work to ensure the security of your application.</p>
                               
                               <p>Your email verification code has been resent:</p>
                               <p style="font-size:18px;"><b>'.$appData['code'].'</b></p>
                               <br>
                               <p>You can also verify your email by clicking the link below:</p>
                               <p><a href="'.$verification_link.'" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Verify Email</a></p>
                               <br>
                               
                               
                               <p style="margin-top: 20px;">We thank you for choosing NJALA University and for your patience during this process.</p>
                           </td>
                        </tr>
                        <tr>
                            <td>
                                <hr>
                                &copy; NJALA University. All rights reserved.<br>
                                Designed by ITEC Ltd<br>
                                NJALA-Sierra Leone.<br>
                                Phone (+232) 74 001023,79 102840,76 811846
                            </td>
                        </tr>
                    </tbody>
                    </table> 
                    </div>';
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array(
                    "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                    "recipient" => $email,
                    "subject" => "Verification Code - Resent",
                    "message" => $message,
                    "is_html" => true
                )),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
    
            $curlResponse = curl_exec($curl);
            curl_close($curl);
    
            $curlResponseData = json_decode($curlResponse, true);
            if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                $response = array("status" => "200", "message" => "Verification code resent successfully!");
            } else {
                $response = array("status" => "500", "message" => "Failed to send email: " . ($curlResponseData['message'] ?? 'Unknown error'));
            }
        } else {
            $response = array("status" => "401", "message" => "No applicant found with this email");
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function update_email_and_resend(){
        $oldEmail = $_POST['old_email'];
        $newEmail = $_POST['new_email'];
        $applicantCode = $_POST['applicant_code'];
        
        // Validate new email format
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $response = array("status" => "400", "message" => "Invalid email format");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Check if new email already exists in system
        $checkStmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email = ? AND code != ?");
        $checkStmt->execute([$newEmail, $applicantCode]);
        
        if ($checkStmt->rowCount() > 0) {
            $response = array("status" => "409", "message" => "Email already exists in the system");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Update applicant email
        $updateStmt = $this->connect->prepare("UPDATE tbl_applicants SET email = ? WHERE code = ?");
        $updateResult = $updateStmt->execute([$newEmail, $applicantCode]);
        
        if ($updateResult) {
            // Delete old verification tokens
            $stmt0 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email IN (?, ?)");
            $stmt0->execute([$oldEmail, $newEmail]);
            
            // Get updated applicant data
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
            $stmt->execute([$applicantCode]);
            
            if($stmt->rowCount() != 0) {
                $appData = $stmt->fetch();
                $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Insert new verification token
                $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES (?, ?, ?)");
                $stmt5->execute([$newEmail, $appData['code'], $expirationTime]);
                
                // Prepare cURL request
                $curl = curl_init();
                $verification_link = isset($_POST['verification_link']) ? $_POST['verification_link'] : 'verify_email?em=' . urlencode($newEmail);
                $message = '
                <div style="padding: 10px;border: 1px solid lightgray;width: 500px;">
                <table>
                <thead>
                <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
                </thead>
                <tbody>
                    <tr>
                       <td>
                           <p>Dear <b>'.$appData['fname'].'</b></p><br>Your email address has been updated and here is your verification code:
                           <p style="font-size:18px;"><b>'.$appData['code'].'</b></p>
                           <br>
                           <p>You can also verify your email by clicking the link below:</p>
                           <p><a href="'.$verification_link.'" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Verify Email</a></p>
                           <br>
                           
                       </td>
                    </tr>
                    <tr>
                        <td>
                            <hr>
                            &copy; '.date("Y").' NJALA University. All rights reserved.<br>
                            Designed by ITEC Ltd<br>
                            NJALA-Sierra Leone.<br>
                            Phone (+232) 74 001023,79 102840,76 811846
                        </td>
                    </tr>
                </tbody>
               </table> 
               </div>';
    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(array(
                        "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                        "recipient" => $newEmail,
                        "subject" => "Email Updated - Verification Code",
                        "message" => $message,
                        "is_html" => true
                    )),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
    
                $curlResponse = curl_exec($curl);
                curl_close($curl);
    
                $curlResponseData = json_decode($curlResponse, true);
                if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                    $response = array("status" => "200", "message" => "Email updated and verification code sent successfully!");
                } else {
                    $response = array("status" => "500", "message" => "Email updated but failed to send verification email: " . ($curlResponseData['message'] ?? 'Unknown error'));
                }
            } else {
                $response = array("status" => "404", "message" => "Applicant not found");
            }
        } else {
            $response = array("status" => "500", "message" => "Failed to update email address");
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function reset_password(){
    $email = $_POST['email'];
    $code = $_POST['code'];
    $name = $_POST['name'];
    
    // Check if applicant exists
    $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email = ? AND code = ?");
    $stmt->execute([$email, $code]);
    
    if($stmt->rowCount() != 0) {
        $appData = $stmt->fetch();
        
        // Use the applicant's code as the new password
        $newPassword = $code;
        
        // Hash the password
        $dir = ['cost' => 12];
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, $dir);
        
        // Update password in tbl_student_login
        $updateStmt = $this->connect->prepare("UPDATE tbl_student_login SET password = ? WHERE Identification = ?");
        $updateResult = $updateStmt->execute([$hashedPassword, $code]);
        
        if ($updateResult) {
            // Get university data
            $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
            $sql->execute();
            $universityData = $sql->fetch();
            $year = date("Y");
            
            // Prepare email content
            $message = '
            <div style="padding: 20px; border: 1px solid lightgray; width: 600px; font-family: Arial, sans-serif;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">
                                <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="100px">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 20px;">
                                <h3 style="color: #333;">Password Reset Notification</h3>
                                <p>Dear <b>'.$appData['fname'].' '.$appData['lname'].'</b>,</p>
                                
                                <p>Your password has been successfully reset by the administration team. Your password has been reset to your tracking number for easy access.</p>
                                
                                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                                    <p><strong>Email:</strong> '.$email.'</p>
                                    <p><strong>New Password:</strong> <span style="font-size: 16px; color: #007bff; font-weight: bold;">'.$newPassword.'</span></p>
                                    <p><strong>Tracking Number:</strong> '.$code.'</p>
                                </div>
                                
                                <p><strong>Important Security Instructions:</strong></p>
                                <ul>
                                    <li>Your password is now the same as your tracking number for convenience</li>
                                    <li>You can change this password after logging in if desired</li>
                                    <li>Do not share your password with anyone</li>
                                    <li>Keep your login credentials secure</li>
                                </ul>
                                
                                <p>You can log in to your account using the link below:</p>
                                <p style="text-align: center; margin: 20px 0;">
                                    <a href="https://misnjala.edu.sl/auth" style="background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">Login to Your Account</a>
                                </p>
                                
                                <p>If you did not request this password reset or have any concerns about your account security, please contact our support team immediately.</p>
                                
                                <p>Best regards,<br>
                                <b>IT Support Team</b><br>
                                Njala University</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 15px; border-top: 1px solid #ddd; background-color: #f8f9fa;">
                                <small>
                                    &copy; '.$year.' NJALA University. All rights reserved.<br>
                                    Designed by ITEC Ltd<br>
                                    NJALA-Sierra Leone.<br>
                                    Phone (+232) 74 001023,79 102840,76 811846
                                </small>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>';

            // Send email using cURL
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array(
                    "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                    "recipient" => $email,
                    "subject" => "Password Reset - New Login Credentials",
                    "message" => $message,
                    "is_html" => true
                )),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $curlResponse = curl_exec($curl);
            curl_close($curl);

            $curlResponseData = json_decode($curlResponse, true);
            if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                $response = array("status" => "200", "message" => "Password reset successfully! New password is the tracking number and has been sent to email.");
            } else {
                $response = array("status" => "500", "message" => "Password updated but failed to send email: " . ($curlResponseData['message'] ?? 'Unknown error'));
            }
        } else {
            $response = array("status" => "500", "message" => "Failed to update password in tbl_student_login");
        }
    } else {
        $response = array("status" => "404", "message" => "Applicant not found with the provided email and code");
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

function unlock_payment_status(){
    $invoice_id = $_POST['invoice_id'];
    $code = $_POST['code'];
    $name = $_POST['name'];
    
    // Update payment status to 1 (unlocked/approved) in tbl_invoice
    $updateStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE id = ? AND reg_no = ?");
    $updateResult = $updateStmt->execute([$invoice_id, $code]);
    
    if ($updateResult) {
        // Get applicant data for email
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
        $stmt->execute([$code]);
        
        if($stmt->rowCount() != 0) {
            $appData = $stmt->fetch();
            
            $year = date("Y");
            
            // Prepare email content
            $message = '
            <div style="padding: 20px; border: 1px solid lightgray; width: 600px; font-family: Arial, sans-serif;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">
                                <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="100px">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 20px;">
                                <h3 style="color: #28a745;">Payment Confirmed - Proceed with Application</h3>
                                <p>Dear <b>'.$appData['fname'].' '.$appData['lname'].'</b>,</p>
                                
                                <p>Great news! We have successfully received and confirmed your payment. Your application fee has been processed and approved.</p>
                                
                                <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
                                    <p><strong>Payment Status:</strong> <span style="color: #28a745; font-weight: bold;">CONFIRMED & APPROVED</span></p>
                                    <p><strong>Tracking Number:</strong> '.$code.'</p>
                                    <p><strong>Date Confirmed:</strong> '.date('Y-m-d H:i:s').'</p>
                                </div>
                                
                                <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
                                    <h4 style="color: #856404; margin-top: 0;">Next Steps - Please Continue Your Application:</h4>
                                    <ol style="color: #856404;">
                                        <li>Log in to your applicant portal using your email and password</li>
                                        <li>Complete all required sections of your application</li>
                                        <li>Upload necessary documents (transcripts, certificates, etc.)</li>
                                        <li>Submit your complete application for review</li>
                                    </ol>
                                </div>
                                
                                <p><strong>Important Reminders:</strong></p>
                                <ul>
                                    <li>Your payment has been successfully processed</li>
                                    <li>You can now access all application features</li>
                                    <li>Please complete your application as soon as possible</li>
                                    <li>Contact us if you need any assistance</li>
                                </ul>
                                
                                <p>You can log in to your account using the link below:</p>
                                <p style="text-align: center; margin: 20px 0;">
                                    <a href="https://misnjala.edu.sl/auth" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">Continue Your Application</a>
                                </p>
                                
                                <p>If you have any questions or need assistance with your application, please contact our admissions office.</p>
                                
                                <p>Thank you for choosing Njala University. We look forward to reviewing your completed application!</p>
                                
                                <p>Best regards,<br>
                                <b>Finance & Admissions Team</b><br>
                                Njala University</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 15px; border-top: 1px solid #ddd; background-color: #f8f9fa;">
                                <small>
                                    &copy; '.$year.' NJALA University. All rights reserved.<br>
                                    Designed by ITEC Ltd<br>
                                    NJALA-Sierra Leone.<br>
                                    Phone (+232) 74 001023,79 102840,76 811846
                                </small>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>';

            // Send email using cURL
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(array(
                    "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                    "recipient" => $appData['email'],
                    "subject" => "Payment Confirmed - Continue Your Application",
                    "message" => $message,
                    "is_html" => true
                )),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $curlResponse = curl_exec($curl);
            curl_close($curl);

            $curlResponseData = json_decode($curlResponse, true);
            if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                $response = array("status" => "200", "message" => "Payment status unlocked successfully and notification sent to applicant!");
            } else {
                $response = array("status" => "200", "message" => "Payment status unlocked successfully but failed to send email notification.");
            }
        } else {
            $response = array("status" => "200", "message" => "Payment status unlocked successfully!");
        }
    } else {
        $response = array("status" => "500", "message" => "Failed to unlock payment status");
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

function bulk_unlock_payment_status(){
    $selected_invoices = json_decode($_POST['selected_invoices'], true);
    
    if (empty($selected_invoices)) {
        $response = array("status" => "400", "message" => "No invoices selected");
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }
    
    $success_count = 0;
    $failed_count = 0;
    $email_success_count = 0;
    
    foreach ($selected_invoices as $invoice_data) {
        $invoice_id = $invoice_data['invoice_id'];
        $code = $invoice_data['code'];
        $name = $invoice_data['name'];
        
        // Update payment status to 1 (unlocked/approved) in tbl_invoice
        $updateStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE id = ? AND reg_no = ?");
        $updateResult = $updateStmt->execute([$invoice_id, $code]);
        
        if ($updateResult) {
            $success_count++;
            
            // Get applicant data for email notification
            $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
            $stmt->execute([$code]);
            
            if($stmt->rowCount() != 0) {
                $appData = $stmt->fetch();
                
                // Prepare email content
                $year = date("Y");
                $message = '
                <div style="padding: 20px; border: 1px solid lightgray; width: 600px; font-family: Arial, sans-serif;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="100px">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 20px;">
                                    <h3 style="color: #28a745;">Payment Confirmed - Proceed with Application</h3>
                                    <p>Dear <b>'.$appData['fname'].' '.$appData['lname'].'</b>,</p>
                                    
                                    <p>Great news! We have successfully received and confirmed your payment. Your application fee has been processed and approved.</p>
                                    
                                    <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
                                        <p><strong>Payment Status:</strong> <span style="color: #28a745; font-weight: bold;">CONFIRMED & APPROVED</span></p>
                                        <p><strong>Tracking Number:</strong> '.$code.'</p>
                                        <p><strong>Date Confirmed:</strong> '.date('Y-m-d H:i:s').'</p>
                                    </div>
                                    
                                    <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
                                        <h4 style="color: #856404; margin-top: 0;">Next Steps - Please Continue Your Application:</h4>
                                        <ol style="color: #856404;">
                                            <li>Log in to your applicant portal using your email and password</li>
                                            <li>Complete all required sections of your application</li>
                                            <li>Upload necessary documents (transcripts, certificates, etc.)</li>
                                            <li>Submit your complete application for review</li>
                                        </ol>
                                    </div>
                                    
                                    <p>You can log in to your account using the link below:</p>
                                    <p style="text-align: center; margin: 20px 0;">
                                        <a href="https://misnjala.edu.sl/auth" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">Continue Your Application</a>
                                    </p>
                                    
                                    <p>Thank you for choosing Njala University. We look forward to reviewing your completed application!</p>
                                    
                                    <p>Best regards,<br>
                                    <b>Finance & Admissions Team</b><br>
                                    Njala University</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; padding: 15px; border-top: 1px solid #ddd; background-color: #f8f9fa;">
                                    <small>
                                        &copy; '.$year.' NJALA University. All rights reserved.<br>
                                        Designed by ITEC Ltd<br>
                                        NJALA-Sierra Leone.<br>
                                        Phone (+232) 74 001023,79 102840,76 811846
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                </div>';

                // Send email using cURL
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(array(
                        "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                        "recipient" => $appData['email'],
                        "subject" => "Payment Confirmed - Continue Your Application",
                        "message" => $message,
                        "is_html" => true
                    )),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $curlResponse = curl_exec($curl);
                curl_close($curl);

                $curlResponseData = json_decode($curlResponse, true);
                if (isset($curlResponseData['success']) && $curlResponseData['success'] === true) {
                    $email_success_count++;
                }
            }
        } else {
            $failed_count++;
        }
    }
    
    if ($success_count > 0) {
        $message = "Successfully unlocked " . $success_count . " payment status(es).";
        if ($email_success_count > 0) {
            $message .= " Email notifications sent to " . $email_success_count . " applicant(s).";
        }
        if ($failed_count > 0) {
            $message .= " " . $failed_count . " failed to unlock.";
        }
        $response = array("status" => "200", "message" => $message);
    } else {
        $response = array("status" => "500", "message" => "Failed to unlock any payment status");
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}
function notify_and_reset_submission_status() {
        $applicants = $_POST['applicants'];
        
        if (empty($applicants)) {
            $response = array("status" => "400", "message" => "No applicants provided");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        $successCount = 0;
        $failedEmails = array();
        
        foreach ($applicants as $applicant) {
            $code = $applicant['code'];
            $email = $applicant['email'];
            $name = $applicant['name'];
            
            // Reset submission status to 0
            $updateStmt = $this->connect->prepare("UPDATE tbl_applicants SET submitted = 0 WHERE code = ?");
            $updateResult = $updateStmt->execute([$code]);
            
            if ($updateResult) {
                // Send notification email
                $emailSent = $this->sendDocumentReminderEmail($email, $name, $code);
                
                if ($emailSent) {
                    $successCount++;
                } else {
                    $failedEmails[] = $email;
                }
            } else {
                $failedEmails[] = $email;
            }
        }
        
        if ($successCount == count($applicants)) {
            $response = array("status" => "200", "message" => "Successfully notified and reset submission status for all " . $successCount . " applicant(s).");
        } else if ($successCount > 0) {
            $response = array("status" => "206", "message" => "Processed " . $successCount . " applicant(s) successfully. Failed: " . implode(', ', $failedEmails));
        } else {
            $response = array("status" => "500", "message" => "Failed to process any applicants. Please try again.");
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
 private function sendDocumentReminderEmail($email, $name, $trackingCode) {
        // Get university data
        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data = $sql->fetch();
        
        // Get current active application period
        $periodSql = $this->connect->prepare("SELECT * FROM tbl_application_periods WHERE status = 1 AND start_date <= NOW() AND end_date >= NOW() ORDER BY end_date ASC LIMIT 1");
        $periodSql->execute();
        $applicationPeriod = $periodSql->fetch();
        
        // Format the deadline message
        $deadlineMessage = '';
        if ($applicationPeriod) {
            $endDate = date('F j, Y \a\t g:i A', strtotime($applicationPeriod['end_date']));
            $daysLeft = ceil((strtotime($applicationPeriod['end_date']) - time()) / (60 * 60 * 24));
            
            if ($daysLeft > 0) {
                $deadlineMessage = '
                    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 15px 0; border-radius: 5px;">
                        <p style="margin: 0; color: #856404;"><strong>⏰ Important Deadline Notice:</strong></p>
                        <p style="margin: 5px 0 0 0; color: #856404;">
                            The application period for <strong>'.$applicationPeriod['period_name'].'</strong> ends on <strong>'.$endDate.'</strong>
                            <br><span style="font-size: 14px;">You have approximately <strong>'.$daysLeft.' day(s)</strong> remaining to complete and resubmit your application.</span>
                        </p>
                    </div>';
            } else {
                $deadlineMessage = '
                    <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 15px 0; border-radius: 5px;">
                        <p style="margin: 0; color: #721c24;"><strong>🚨 URGENT: Application Period Ending Soon!</strong></p>
                        <p style="margin: 5px 0 0 0; color: #721c24;">
                            The application period ends on <strong>'.$endDate.'</strong>
                            <br><span style="font-size: 14px;">Please complete your application <strong>immediately</strong> to avoid missing the deadline.</span>
                        </p>
                    </div>';
            }
        }
        
        $curl = curl_init();
        $message = '
            <div style="padding: 10px;border: 1px solid lightgray;width: 500px;">
            <table>
            <thead>
            <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
            </thead>
            <tbody>
                <tr>
                   <td>
                       <p>Dear <b>'.$name.'</b></p>
                       
                       <p>We hope this message finds you well.</p>
                       
                       <p>We noticed that your application with tracking number <strong>'.$trackingCode.'</strong> was submitted without the required supporting documents.</p>
                       
                       '.$deadlineMessage.'
                       
                       <p>To ensure your application is processed correctly, we have reset your submission status to allow you to:</p>
                       <ul>
                           <li>Upload all required documents</li>
                           <li>Review your application details</li>
                           <li>Resubmit your complete application</li>
                       </ul>
                       
                       <p><strong>Please log in to your application portal and complete the following:</strong></p>
                       <ol>
                           <li>Upload all required documents (transcripts, certificates, etc.)</li>
                           <li>Review all sections of your application</li>
                           <li>Submit your completed application <strong>before the deadline</strong></li>
                       </ol>
                       
                       <p style="color: red;"><strong>Important:</strong> Incomplete applications without proper documentation cannot be processed for admission consideration. Applications submitted after the deadline will not be accepted.</p>
                       
                       <p>If you have any questions or need assistance, please contact our admissions office immediately.</p>
                       
                       <p>Thank you for your understanding and cooperation.</p>
                   </td>
                </tr>
                <tr>
                    <td>
                        <hr>
                        &copy; '.date("Y").' NJALA University. All rights reserved.<br>
                        Admissions Office<br>
                        NJALA-Sierra Leone.<br>
                        Phone (+232) 74 001023,79 102840,76 811846
                    </td>
                </tr>
            </tbody>
           </table> 
           </div>';

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
                "recipient" => $email,
                "subject" => "Application Incomplete - Documents Required",
                "message" => $message,
                "is_html" => true
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $curlResponse = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode == 200) {
            $curlResponseData = json_decode($curlResponse, true);
            return isset($curlResponseData['success']) && $curlResponseData['success'] === true;
        }
        
        return false;
    }
    
function create_student_account() {
        $code = $_POST['code'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        
        if (empty($code) || empty($email) || empty($name)) {
            $response = array("status" => "400", "message" => "Missing required parameters");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Check if student account already exists
        $checkStmt = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE Identification = ?");
        $checkStmt->execute([$code]);
        
        if ($checkStmt->rowCount() > 0) {
            $response = array("status" => "409", "message" => "Student account already exists");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Get applicant details
        $appStmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
        $appStmt->execute([$code]);
        
        if ($appStmt->rowCount() == 0) {
            $response = array("status" => "404", "message" => "Applicant not found");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        $applicant = $appStmt->fetch();
        
        // Use the applicant's code as password
        $password = $code;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into student login table
        $insertStmt = $this->connect->prepare("INSERT INTO tbl_student_login (Identification, email, password, role_id) VALUES (?, ?, ?, 5)");
        $insertResult = $insertStmt->execute([
            $code,
            $email,
            $hashedPassword
        ]);
        
        if ($insertResult) {
            // Delete from email verification tokens
            $deleteStmt = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
            $deleteStmt->execute([$code]);
            
            // Send account creation notification email
            $emailSent = $this->sendAccountCreationEmail($email, $applicant['fname'], $code, $password);
            
            if ($emailSent) {
                $response = array("status" => "200", "message" => "Student account created successfully and notification sent!");
            } else {
                $response = array("status" => "200", "message" => "Student account created but email notification failed");
            }
        } else {
            $response = array("status" => "500", "message" => "Failed to create student account");
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }    
    
function bulk_create_student_accounts() {
        $studentsJson = $_POST['students'];
        $students = json_decode($studentsJson, true);
        
        if (empty($students)) {
            $response = array("status" => "400", "message" => "No students provided");
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        $successCount = 0;
        $failedAccounts = array();
        
        foreach ($students as $student) {
            $code = $student['code'];
            $email = $student['email'];
            $name = $student['name'];
            
            // Check if student account already exists
            $checkStmt = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE Identification = ?");
            $checkStmt->execute([$code]);
            
            if ($checkStmt->rowCount() > 0) {
                $failedAccounts[] = $name . " (already exists)";
                continue;
            }
            
            // Get applicant details
            $appStmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = ?");
            $appStmt->execute([$code]);
            
            if ($appStmt->rowCount() == 0) {
                $failedAccounts[] = $name . " (not found)";
                continue;
            }
            
            $applicant = $appStmt->fetch();
            
            // Use the applicant's code as password
            $password = $code;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into student login table
            $insertStmt = $this->connect->prepare("INSERT INTO tbl_student_login (Identification, email, password, role_id) VALUES (?, ?, ?, 5)");
            $insertResult = $insertStmt->execute([
                $code,
                $email,
                $hashedPassword
            ]);
            
            if ($insertResult) {
                // Delete from email verification tokens
                $deleteStmt = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
                $deleteStmt->execute([$code]);
                
                // Send account creation notification email
                $this->sendAccountCreationEmail($email, $applicant['fname'], $code, $password);
                
                $successCount++;
            } else {
                $failedAccounts[] = $name . " (creation failed)";
            }
        }
        
        if ($successCount == count($students)) {
            $response = array("status" => "200", "message" => "Successfully created accounts for all " . $successCount . " student(s) and sent notifications!");
        } else if ($successCount > 0) {
            $response = array("status" => "206", "message" => "Created " . $successCount . " account(s) successfully. Failed: " . implode(', ', $failedAccounts));
        } else {
            $response = array("status" => "500", "message" => "Failed to create any accounts. Please try again.");
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }    
    private function sendAccountCreationEmail($email, $name, $studentId, $password) {
    // Get university data
    $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
    $sql->execute();
    $data = $sql->fetch();
    
    $curl = curl_init();
    $studentPortalLink = 'https://misnjala.edu.sl/auth'; // Update this to your actual student portal URL
    $message = '
        <div style="padding: 10px;border: 1px solid lightgray;width: 500px;">
        <table>
        <thead>
        <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
        </thead>
        <tbody>
            <tr>
               <td>
                   <p>Dear <b>'.$name.'</b></p>
                   
                   <p>Congratulations! Your student account has been successfully created.</p>
                   
                   <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 15px 0; border-radius: 5px;">
                       <p style="margin: 0; color: #155724;"><strong>🎉 Account Details:</strong></p>
                       <p style="margin: 10px 0; color: #155724;">
                           <strong>Student ID:</strong> '.$studentId.'<br>
                           <strong>Email/Username:</strong> '.$email.'<br>
                           <strong>Password:</strong> <span style="background-color: #fff; padding: 2px 5px; border: 1px solid #ccc;">'.$password.'</span>
                       </p>
                   </div>
                   
                   <p><strong>Next Steps:</strong></p>
                   <ol>
                       <li>Log in to the student portal using your credentials above</li>
                       <li><strong>Change your password</strong> after first login for security (recommended)</li>
                       <li>Complete your profile information</li>
                       <li>Check for any additional requirements or notifications</li>
                   </ol>
                   
                   <div style="text-align: center; margin: 20px 0;">
                       <a href="'.$studentPortalLink.'" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Access Student Portal</a>
                   </div>
                   
                   <p style="color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeaa7;">
                       <strong>Security Notice:</strong> Your password is currently set to your tracking number for easy access. Please keep your login credentials secure and consider changing your password after your first login.
                   </p>
                   
                   <p>If you have any questions or need assistance accessing your account, please contact our student support office.</p>
                   
                   <p>Welcome to NJALA University!</p>
               </td>
            </tr>
            <tr>
                <td>
                    <hr>
                    &copy; '.date("Y").' NJALA University. All rights reserved.<br>
                    Designed by ITEC Ltd<br>
                    Student Services<br>
                    NJALA-Sierra Leone.<br>
                    Phone (+232) 74 001023,79 102840,76 811846
                </td>
            </tr>
        </tbody>
       </table> 
       </div>';

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
            "recipient" => $email,
            "subject" => "Student Account Created - Welcome to NJALA University",
            "message" => $message,
            "is_html" => true
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $curlResponse = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode == 200) {
        $curlResponseData = json_decode($curlResponse, true);
        return isset($curlResponseData['success']) && $curlResponseData['success'] === true;
    }
    
    return false;
}
    
    
        
    }
    		$application=new Application();
    	    $action = $_POST['action'];
    		switch($action){
    		    case 'apply':
    		        $application->apply_init();
    		        break;
    		    case 'save_option':
    		        $application->save_application();
    		        break;
    		    case 'view_option':
    		        $application->view_application();
    		        break;
    		    case 'update_option':
    		        $application->update_application();
    		        break;
    		    case 'remove':
    		        $application->remove_application();
    		        break;
    		    case 'verify_email':
    		        $application->verify_email();
    		        break;
    		    case 'send_code':
    		        $application->send_verification_code();
    		        break;
    		    case 'create_password':
    		        $application->create_password();
    		        break;
    		    case 'load_provinces':
    		        $application->load_provinces();
    		        break;
    		    case 'load_districts':
    		        $application->load_districts();
    		        break;
    		    case 'load_sectors':
    		        $application->load_sectors();
    		        break;
    		    case 'load_cells':
    		        $application->load_cells();
    		        break;
    		    case 'load_villages':
    		        $application->load_villages();
    		        break;
    		    case 'load_program_types':
    		        $application->load_program_types();
    		        break;
    		    case 'view_profile':
    		        $application->load_profile();
    		        break;
    		    case 'update_personal':
    		        $application->update_profile_personal();
    		        break;
    		    case 'update_guardian':
    		        $application->update_profile_guardian();
    		        break;
    		    case 'update_contact':
    		        $application->update_profile_contact();
    		        break;
    		    case 'update_address':
    		        $application->update_profile_address();
    		        break;
    		    case 'save_education':
    		        $application->save_education();
    		        break; 
    		    case 'delete_education':    
    		        $application->delete_education();
    		        break;
    		    case 'update_languages':
    		        $application->update_languages();
    		        break;
    		    case 'update_church':
    		        $application->update_church();
    		        break;
    		    case 'update_essay':
    		        $application->update_essay();
    		        break;
        	    case 'submit':
        	        $application->submit();
        	        break;
        	   case 'save_waec':
        	        $application->save_waec_result();
        	        break;
        	   case 'update_payment':
        	        $application->update_payt();
        	        break;
        	   case 'load_schools':
        	       $application->show_school();
        	       break;
        	   case 'load_schools_marks':
        	       $application->show_school_marks();
        	       break;
               case 'load_departments':
                   $application->show_department();
                   break;
               case 'load_department1':
                   $application->show_department1();
                   break;
               case 'load_departments_marks':
                   $application->show_department_marks();
                   break;   
               case 'load_levels':
                   $application->show_level();
                   break;
               case 'load_levels_app':
                   $application->show_level_app();
                   break;   
               case 'load_specialization':
                   $application->show_specialization();
                   break;
               case 'load_specialization_new':
                   $application->show_specialization_new();
                   break;       
               case 'load_specialization1':
                   $application->show_specialization1();
                   break;
        	   case 'load_modes':
        	       $application->show_modes();
        	       break;
        	   case 'load_modes_app':
        	       $application->show_modes_app();
        	       break; 
        	   case 'check_student_email':
        	       $application->email_check();
        	       break; 
        	   case 'check_student_reg':
        	       $application->reg_check();
        	       break; 
        	   case 'apply_semester':
        	       $application->student_semester_apply();
        	       break;
        	       
        	   case 'update_type':
        	       $application->applicant_type();
        	       break;
        	  case 'update_group':
        	       $application->applicant_group();
        	       break;
        	  case 'add_mattuculation':
        	      $application->applicant_prev();
        	      break;
        	  case 'view_previouse':    
        	      $application->applicant_prev_view();
        	      break;
        	  case 'update_matuculation':
        	      $application->applicant_prev_update();
        	      break;
        	  case 'add_familly':
        	      $application->familly_add();
        	      break;
        	  case 'show_family':
        	      $application->view_family();
        	      break;
        	  case 'update_familys':
        	      $application->familly_update();
        	      break;
        	  case 'add_sponsors':
        	      $application->sponsor_add();
        	      break;
        	 case 'update_sponsors':
        	      $application->sponsor_update();
        	      break;
        	 case 'resend_verification':
        	     $application->resend_verification();
        	     break;
        	 case 'update_email_and_resend':
        	     $application->update_email_and_resend();
        	     break;
        	case 'reset_password':
        	     $application->reset_password();
        	     break;
        	case 'unlock_payment_status':
        	     $application->unlock_payment_status();
        	     break;
        	case 'bulk_unlock_payment_status':
        	     $application->bulk_unlock_payment_status();
        	     break;
        	case 'bulk_create_student_accounts':
        	     $application->bulk_create_student_accounts();
        	     break;
        	case 'create_student_account':
        	     $application->create_student_account();
        	     break;
        	case 'notify_and_reset_submission_status':
        	     $application->notify_and_reset_submission_status();
        	     break;     
        	case 'report_problem':
        	     $application->report_problem();
        	     break;     
    		}
    
    	?>
    
