<?php
include ('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$connection=$conn;
class Application{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
    function send_submission_email($code){
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."'");
        $stmt->execute();
        if($stmt->rowCount()!=0){
            $appData=$stmt->fetch();
            
            $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
            $sql->execute();
            $data=$sql->fetch();
            
            $year = date("Y");
            $to = $appData['email'];
            
            $subject = 'Acknowledgment of Your Application Submission';
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
                <div style="padding: 10px;border: 1px solid lightgray;width: 100%;">
                <table>
                <thead>
                <tr><th><img src="https://misnjala.edu.sl/img/logo/testnjala.png" width="20%"></th></tr>
                </thead>
                <tbody>
                    <tr>
                      <td>
                          <p>Dear <b>'.$appData['fname'].',</b></p>
                          <p>We trust this email finds you well. We am writing to express our appreciation for your recent application to '.$data['full_name'].'. We are delighted that you have taken the initiative to apply for admission to our esteemed institution.</p>
                          <p>Your application is currently under review, and our admissions committee is diligently assessing each candidate qualifications. We understand the significance of this process and the anticipation that comes with it. Rest assured that we are committed to providing a thorough and fair evaluation of all applications.</p>
                          <p>The admissions team will carefully consider your academic achievements, personal statement, and any supporting documents you have provided. We aim to make decisions that reflect our commitment to academic excellence and our mission to nurture individuals who are not only academically proficient but also contribute positively to our vibrant community.</p>
                          
                          <P>It is crucial that you complete and submit all required materials by not later than 15/09/2024. Failure to fulfill these requirements in full will result in your application being disregarded.</P>
                          <p>Please note that the review process may take some time as we strive to give each application the attention it deserves. We appreciate your patience during this period.</p>
                          <p>If you have any questions or if there are additional materials you would like to submit, please feel free to reach out to our admissions office at [info@njala.edu.sl]. We are here to assist you throughout the application process.</p>
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
                            &copy; '.$year.' ITEC. All rights reserved.<br>
                            Designed by ITEC Ltd<br>
                            KN 1 Rd, Kigali-Rwanda.<br>
                            Phone (+250) 788730582
                        </td>
                    </tr>
                </tbody>
              </table> 
              </div>';
            $message2 .= '</body></html>';

            mail($to, $subject, $message2, $headers);
            return;
        }
    }
    function send_verification_code(){
        $email=$_POST['email'];
        $stmt0 = $this->connect->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
        $stmt0->execute();
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE email='".$email."'");
        $stmt->execute();
        if($stmt->rowCount()!=0){
            $appData=$stmt->fetch();
            $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES ('".$email."','".$appData['code']."','".$expirationTime."')");
            $stmt5->execute();
            
            $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
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
                <div style="padding: 10px;border: 1px solid lightgray;width: 500px;">
                <table>
                <thead>
                <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
                </thead>
                <tbody>
                    <tr>
                       <td>
                           <p>Dear <b>'.$appData['fname'].'</b></p><br>your email verification code is
                           <p style="font-size:18px;"><b>'.$appData['code'].'</b></p>
                           <br>
                           <p><b>!! Please remember that your verification code will no longer be valid after 1 hour if it is not utilized.</b></p>
                       </td>
                    </tr>
                       
                    <tr>
                        <td>
                            <hr>
                            &copy; '.$year.' ITEC. All rights reserved.<br>
                            Designed by ITEC Ltd<br>
                            KN 1 Rd, Kigali-Rwanda.<br>
                            Phone (+232) 79453322
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
        $pass =$_POST['token'];
        $dt = date('Y-m-d H:i:s');
          $dir = [
                'cost' => 12,
            ];
        $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
        $sth = $this->connect->prepare("SELECT email FROM email_verification_tokens WHERE token='".$token."' AND expires_at>='".$dt."'");
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
        $select_school="SELECT * FROM tbl_faculty WHERE prg_type='$prg_type'";
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
        $select_depart="SELECT * FROM tbl_department WHERE fac_id='$fac_id' AND prg_type='$prg_type'";
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
     function show_specialization(){
         $dept_id=$_POST['dept_id'];
         $prg_type=$_POST['prg_type'];
         $fac_id=$_POST['fac_id'];
         $select_spec="SELECT * FROM tbl_specialization WHERE prg_type='$prg_type' AND fac_id='$fac_id' AND dept_id='$dept_id'";
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
    function create_password(){
        $pass =$_POST['password'];
        $email = $_POST['email'];
          $dir = [
                'cost' => 12,
            ];
        $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
        $stmt = $this->connect->prepare("UPDATE tbl_student_login SET password='".$password."' WHERE email='".$email."'");
        if($stmt->execute()){
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
        $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$cid."'");
        $stmt->execute();
        $data = $stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;   
    }
    


    function apply_init(){
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
            $pass = generate_password();
                $currentYear = date('Y');
                $code=substr($currentYear, -2)."NJ".$pass;
                
                $stmt = $this->connect->prepare("INSERT INTO tbl_applicants(code,fname,mname,lname,email,nationality,ID,phone,gender,dob,father_names,mother_names,parent_phone,ref_phone,country) 
                      	VALUES('".$code."','".$fname."','".$mname."','".$lname."','".$email."','".$nationality."','".$nid."','".$phone."','".$gender."','".$dob."','".$father_names."','".$mother_names."','".$parent_phone."','".$ref_phone."','".$country."')");
                if($stmt->execute()){
                    $select_fee="SELECT * FROM tbl_fee_category WHERE name='Applicant Fees'";
                    $cselect_fee=$this->connect->prepare($select_fee);
                    $cselect_fee->execute();
                    $row_cselect_fee=$cselect_fee->fetch();
                    $reg_no=$code;
                    $month=date('m');
                    $fee_id=$row_cselect_fee['id'];
                    $balance=$row_cselect_fee['amount'];
                    $invoice_date=date('Y-m-d H:i:s');
                    $invoice_data=[
                        'reg_no'=>$reg_no,
                        'month'=>$month,
                        'fee_id'=>$fee_id,
                        'balance'=>$balance,
                        'invoice_date'=>$invoice_date
                        ];
                    $invoice="INSERT INTO tbl_invoice (`reg_no`, `month`, `fee_id`, `balance`, `invoice_date`) values (:reg_no,:month,:fee_id,:balance,:invoice_date)";
                    $cinvoice=$this->connect->prepare($invoice);
                    $cinvoice->execute($invoice_data);
                    
                    $languages = ["English", "Krio"];
                    foreach($languages as $lan){
                        $stmt55 = $this->connect->prepare("INSERT INTO tbl_language_pro (language, stu, state) VALUES ('".$lan."','".$code."', 1)");
                        $stmt55->execute();
                    }
                    $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    $stmt5 = $this->connect->prepare("INSERT INTO email_verification_tokens (email, token, expires_at) VALUES ('".$email."','".$code."','".$expirationTime."')");
                    $stmt5->execute();
                    $year = date("Y");
    
                
                    $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                    $sql->execute();
                    $data=$sql->fetch();
                    
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
                        <thead>
                        <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                               <td>
                                   <p>Dear <b>'.$fname." ".$lname.'</b></p><br>your email verification code is
                                   <p style="font-size:18px;"><b>'.$code.'</b></p>
                                   <br>
                                   <p><b>!! Please remember that your verification code will no longer be valid after 1 hour if it is not used.</b></p>
                               </td>
                            </tr>
                               
                            <tr>
                                <td>
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
                    $message2 .= '</body></html>';
                    
                    // Sending email
                    if(mail($to, $subject, $message2, $headers)){
                        $data = array("status"=>"200","message" => "Data saved successfully!");
                        $jsonData = json_encode($data);
                        header('Content-Type: application/json');
                        echo $jsonData; 
                    }
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
    function update_profile_personal(){
        $stu=$_POST['stu'];
        $fname=trim($_POST['fname']," ");
        $lname=trim($_POST['lname']," ");
        $nationality=$_POST['nationality'];
        $nid=trim($_POST['nid']," ");
        $father_names=trim($_POST['father_names']," ");
        $mother_names=trim($_POST['mother_names']," ");
        $gender=$_POST['gender'];
        $dob=$_POST['dob'];
        $prevname=$_POST['prevname'];
        $mname=$_POST['mname'];
        $marital_status=$_POST['marital_status'];
        
        if($fname=="" || $lname=="" || $nid=="" || $father_names=="" || $mother_names=="" || $marital_status==""){
            $data = array("status"=>"401","message" => "Fill the form correctly");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }else{
            $stmtu = $this->connect->prepare("UPDATE tbl_applicants SET fname='".$fname."',mname='".$mname."',lname='".$lname."',nationality='".$nationality."',ID='".$nid."',gender='".$gender."',dob='".$dob."',prevname='".$prevname."',marital_status='".$marital_status."',father_names='".$father_names."',mother_names='".$mother_names."' WHERE applicant_id='".$stu."'");
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
        $province_id=$_POST['province_id'];
        $district_id=$_POST['district_id'];
        $street=$_POST['street'];
        // $cell_id=$_POST['cell_id'];
        // $village_id=$_POST['village_id'];
        $stmtu = $this->connect->prepare("UPDATE tbl_applicants SET country='".$country."',province_id='".$province_id."',district_id='".$district_id."',street='".$street."' WHERE applicant_id='".$stu."'");
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
            $data = array("status"=>"200","message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        } else {
            $data = array("status"=>"500","message" => "Failed to save data!");
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
        
    function save_application(){
        $Stu_code=$_POST['Stu_code'];
        $cump_id=$_POST['cump_id'];
        $intake_id=$_POST['intake_id'];
        $prg_type=$_POST['prg_type'];
        $prog=$_POST['splz_id'];
        $mode=$_POST['mode'];
        $prg_lvl=$_POST['prg_lvl'];
        
        $selectAcad=$this->connect->prepare("SELECT acad_cycle_id FROM tbl_intake WHERE intake_id='".$intake_id."'");
        $selectAcad->execute();
        $acadData=$selectAcad->fetch();
        $acad_id=$acadData['acad_cycle_id'];
        
        $checkOptionsCount = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Stu_code='".$Stu_code."' AND intake_id='".$intake_id."' AND sts=1");
        $checkOptionsCount->execute();
        if($checkOptionsCount->rowCount()==2){
            $data = array("status"=>"401","message" => "You have reached 2 options!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        else {
            $checkExists = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Stu_code='".$Stu_code."' AND intake_id='".$intake_id."' AND dept_id='".$prog."' AND mode='".$mode."' AND sts=1");
            $checkExists->execute();
            if($checkExists->rowCount()>0){
                $data = array("status"=>"401","message" => "Option already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            }
            else{
                $stmt = $this->connect->prepare("INSERT INTO tbl_admittedPRG(Stu_code,cump_id,intake_id,prg_type,dept_id,level_id,mode) 
                      	VALUES('".$Stu_code."','".$cump_id."','".$intake_id."','".$prg_type."','".$prog."','".$prg_lvl."','".$mode."')");
                $stmt->execute(); 
                $month=date('F');
                $fee_id=7;
                $select_fee_amount="SELECT * FROM fee_category WHERE id='$fee_id'";
                $cselect_fee_amount=$this->connect->prepare($select_fee_amount);
                $cselect_fee_amount->execute();
                $row_cselect_fee_amount=$cselect_fee_amount->fetch();
                $balance=$row_cselect_fee_amount['amount'];
                $invoice_date=date('Y-m-d');
                $invoice_status=1;
                $approval_status=1;
                $payment_status=0;
                $invoiceStmt = $this->connect->prepare("INSERT INTO tbl_invoice (reg_no, acad_cycle_id, month, level_id, intake, fee_id, balance, invoice_date, invoice_status, approval_status,payment_status)
                                                VALUES ('".$Stu_code."','".$acad_id."','".$month."','".$prg_lvl."','".$intake_id."','".$fee_id."','".$balance."','".$invoice_date."','".$invoice_status."','".$approval_status."','".$payment_status."')");

                if($invoiceStmt->execute()){
                    $data = array("status"=>"200","message" => "Application Saved!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                    }
                else {
                    $data = array("status"=>"500","message" => "Failed to save data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        }
    }
    
    function update_application(){
        $app=$_POST['app_id'];
        $code=$_POST['code'];
        $cump_id=$_POST['e_cump_id'];
        $intake_id=$_POST['e_intake_id'];
        $prg_type=$_POST['e_prg_type'];
        $prog=$_POST['e_splz_id'];
        $mode=$_POST['e_mode'];
        $level_id=$_POST['e_prg_lvl'];
        
        $checkOptionsCount = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id='".$app."' AND sts!=1");
        $checkOptionsCount->execute();
        if($checkOptionsCount->rowCount()==2){
            $data = array("status"=>"401","message" => "Your application is under review!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
        else {
            $checkExists = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE cump_id='".$cump_id."' AND intake_id='".$intake_id."' AND prg_type='".$prg_type."' AND dept_id='".$prog."' AND mode='".$mode."' AND Aprg_id='".$app."' AND level_id='".$level_id."'");
            $checkExists->execute();
            if($checkExists->rowCount()>0){
                $data = array("status"=>"401","message" => "Nothing changed!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            }
            else{
                $checkExists = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE cump_id='".$cump_id."' AND intake_id='".$intake_id."' AND prg_type='".$prg_type."' AND dept_id='".$prog."' AND mode='".$mode."' AND Stu_code='".$code."' AND level_id='".$level_id."'");
                $checkExists->execute();
                if($checkExists->rowCount()>0){
                    $data = array("status"=>"401","message" => "Option already exists!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;   
                }
               else{
                $stmt = $this->connect->prepare("UPDATE tbl_admittedPRG SET 
                                                                    cump_id='".$cump_id."',
                                                                    intake_id='".$intake_id."',
                                                                    prg_type='".$prg_type."',
                                                                    dept_id='".$prog."',
                                                                    mode='".$mode."',
                                                                    level_id='".$level_id."'
                                                                    WHERE Aprg_id='".$app."'") ;
                if($stmt->execute()){
                    $data = array("status"=>"200","message" => "Changes Saved!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                    }
                else {
                    $data = array("status"=>"500","message" => "Failed to save changes!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
            }
        }
    }
	function remove_application() {
    $aid = $_POST['app'];

    // Fetch application data to get Stu_code and status
    $stmt = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = ?");
    $stmt->execute([$aid]);
    $data = $stmt->fetch();

    if (!$data || $data['sts'] != 1) {
        // Unauthorized action if the application does not exist or status is not 1
        $response = array("status" => "401", "message" => "Unauthorized action!");
        echo json_encode($response);
        return;
    }

    // Retrieve Stu_code for invoice check
    $Stu_code = $data['Stu_code'];

    // Check if any related invoices have payment_status = 1
    $invoiceCheckStmt = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND payment_status = 1 AND fee_id != 1");
    $invoiceCheckStmt->execute([$Stu_code]);

    if ($invoiceCheckStmt->rowCount() > 0) {
        // If any invoice has payment_status = 1, prevent deletion
        $response = array("status" => "401", "message" => "Cannot delete due to existing paid invoices!");
        echo json_encode($response);
        return;
    }

    // Delete related invoices in tbl_invoice
    $invoiceStmt = $this->connect->prepare("DELETE FROM tbl_invoice WHERE reg_no = ? AND fee_id != 1");
    $invoiceDeleted = $invoiceStmt->execute([$Stu_code]);

    if ($invoiceDeleted) {
        // After deleting invoices, delete from tbl_admittedPRG
        $deleteStmt = $this->connect->prepare("DELETE FROM tbl_admittedPRG WHERE Aprg_id = ?");
        if ($deleteStmt->execute([$aid])) {
            $response = array("status" => "200", "message" => "Application and related invoices removed!");
            echo json_encode($response);
        } else {
            $response = array("status" => "500", "message" => "Failed to remove application!");
            echo json_encode($response);
        }
    } else {
        // If invoice deletion fails
        $response = array("status" => "500", "message" => "Failed to remove related invoices!");
        echo json_encode($response);
    }
}


    function view_application(){
        $app=$_POST['id'];
        $info = array();
        //app data
        $stmt = $this->connect->prepare("SELECT * FROM tbl_admittedPRG where Aprg_id='".$app."'");
        $stmt->execute();
        $appData = $stmt->fetch();
        
        //program types
        $stmt1 = $this->connect->prepare("SELECT * FROM tbl_program_type where campus_id='".$appData['cump_id']."'");
        $stmt1->execute();
        $prgs = $stmt1->fetchAll();
        
        //program types level
        $stmt6 = $this->connect->prepare("SELECT * FROM tbl_level where level_id='".$appData['level_id']."'");
        $stmt6->execute();
        $level = $stmt6->fetchAll();

        //programs
        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_specialization where prg_type='".$appData['prg_type']."'");
        $stmt2->execute();
        $splzs = $stmt2->fetchAll();

        //intakes
        if($appData['mode']==7){
            
        $stmt5 = $this->connect->prepare("SELECT * FROM tbl_intake WHERE prg_type='".$appData['prg_type']."' AND status=1 AND app_start IS NULL ");
        $stmt5->execute();
        $intakes = $stmt5->fetchAll();      
        }
        else{
        $dt=date('Y-m-d');
        $stmt5 = $this->connect->prepare("SELECT * FROM tbl_intake WHERE prg_type='".$appData['prg_type']."' AND status=1 AND app_start<='".$dt."' AND app_end>='".$dt."'");
        $stmt5->execute();
        $intakes = $stmt5->fetchAll();    
        }
        
        
        array_push($info, $appData, $prgs, $splzs, $intakes,$level);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function submit(){
        $code = $_POST['code'];
        $stmt = $this->connect->prepare("UPDATE tbl_applicants SET submitted=1 WHERE code='".$code."'");
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
           case 'load_departments':
               $application->show_department();
               break;
           case 'load_levels':
               $application->show_level();
               break;
           case 'load_specialization':
               $application->show_specialization();
               break;
    	   case 'load_modes':
    	       $application->show_modes();
    	       break;
		}

	?>

