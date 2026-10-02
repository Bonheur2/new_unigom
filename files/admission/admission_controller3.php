<?php
include ('../../meet/con.php');
$connection=$conn;
class Admission{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
	  
    function sendSMS($sender, $phone, $fname, $reg_no, $username, $password){
        $message = 'Dear '.$fname.', Your registration number is '.$reg_no.', Username: '.$username.', password: remained the same';
        $data = array(
                    "sender"=>"$sender",
                    "recipients"=>"$phone",
                    "message"=>"$message",
                    );
        $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
        $data = http_build_query($data);
        $username = "twagiramungus";
        $password = "M00dle!!@@";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result=curl_exec($ch);
        $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result;
        $httpcode;
    }
    
    function deleteModules($intake_id, $splz_id, $reg_no){
        $stmt = $this->connect->prepare("DELETE FROM tbl_markby_module WHERE intake_id='".$intake_id."' AND splz_id='".$splz_id."' AND reg_no='".$reg_no."'");
        $stmt->execute();
        return;
    }
    
    function getPreviousData($id){
        $stmt = $this->connect->prepare("SELECT * FROM tbl_register_program_ug WHERE reg_prg_id = '".$id."'");
        $stmt->execute();
        $data = $stmt->fetch();
        return $data;
    }
    function checkOccurences($reg_no, $splz_id, $level_id, $acad_cycle_id){
        $count = 0;
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_markby_module WHERE reg_no = '".$reg_no."' AND splz_id='".$splz_id."' AND acad_cycle_id='".$acad_cycle_id."' AND marks IS NOT NULL");
        $stmt->execute();
        $count+=$stmt->rowCount();
        
        // $stmt2 = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = '".$reg_no."' AND level_id='".$level_id."' AND acad_cycle_id='".$acad_cycle_id."'");
        // $stmt2->execute();
        // $count+=$stmt2->rowCount();
        
        // $stmt3 = $this->connect->prepare("SELECT * FROM payment WHERE reg_no = '".$reg_no."' AND acad_cycle_id='".$acad_cycle_id."' AND status=1");
        // $stmt3->execute();
        // $count+=$stmt3->rowCount();
        
        return 0;
    }

    function Admit(){
        $adm=$_POST['ad_m_i'];
        //get applicant data
        $checkType = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = '".$adm."'");
        $checkType->execute();
        $progData=$checkType->fetch();
        $stu_code=$progData['Stu_code'];
        $dept_id=$progData['dept_id'];
        $prg_type=$progData["prg_type"];
        $intake_id=$progData["intake_id"];
        
        
        $fetchDepartment = $this->connect->prepare("SELECT fac_id FROM tbl_specialization WHERE splz_id = '".$dept_id."'");
        $fetchDepartment->execute();
        $progDataDetails=$fetchDepartment->fetch();
        
        $fetchDept = $this->connect->prepare("SELECT dept_id FROM tbl_specialization WHERE splz_id = '".$dept_id."'");
        $fetchDept->execute();
        $DeptDataDetails=$fetchDept->fetch();
        
        $stmt0 = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code='".$stu_code."'");
        $stmt0->execute();
        if($stmt0->rowCount()>0){
            $stud=$stmt0->fetch();
            
            $fname=$stud['fname'];
            $lname=$stud['lname'];
            $email=$stud['email'];
            $nid=$stud['ID'];
            $phone=$stud['phone'];
            $gender=$stud['gender'];
            $father_names=$stud['father_names'];
            $mother_names=$stud['mother_names'];
            $parent_phone=$stud['parent_phone'];
            $ref_phone=$stud['ref_phone'];
            
            $dob = $stud['dob']; 
            $marital_status = $stud['marital_status']; 
            $kin_name = $stud['kin_name']; 
            $kin_relation = $stud['kin_relation']; 
            $kin_address = $stud['kin_address']; 
            $kin_email = $stud['kin_email']; 
            $kin_tel = $stud['kin_tel']; 
            
            $country=(int)$stud['country'];
            $nationality=(int)$stud['nationality'];
            $province_id=(int)$stud['province_id'];
            $district_id=(int)$stud['district_id'];
            $sector=(int)$stud['sector'];
            $cell_id=(int)$stud['cell_id'];
            $village_id=(int)$stud['village_id'];
            
         //check for non duplicates
            $checkExists = $this->connect->prepare("SELECT * FROM tbl_admission WHERE email='".$email."' OR ID='".$nid."'");
            $checkExists->execute();
            if($checkExists->rowCount()>0){
                $data = array("status"=>"401","message" => "Data already exists!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;    
            }
            else{
                //generate registration number and save data
                $currentYear = date('Y');
                $templ="NU".substr($currentYear, -2);
                $subcode="NU".substr($currentYear, -2);
                $checkcode = $this->connect->prepare("SELECT reg_no FROM tbl_admission WHERE reg_no like '%".$templ."%' ORDER BY adm_id DESC limit 1");
                $checkcode->execute();
                $codeData=$checkcode->fetch();
                $prevSuffix=substr($codeData['reg_no'],-4);
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
                $school=$progDataDetails["fac_id"];
                $reg_no=$subcode.''.$school.''.$suffix;
                $dt=date('Y-m-d');
                $stmt = $this->connect->prepare("INSERT INTO tbl_admission(reg_no,intake_id,fname,lname,email,nationality,ID,phone,gender,father_names,mother_names,parent_phone,ref_phone,country,province_id,district_id,sector,cell_id,village_id, marital_status, kin_name, kin_relation, kin_address, kin_email, kin_tel) 
                      	VALUES('".$reg_no."','".$intake_id."','".$fname."','".$lname."','".$email."','".$nationality."','".$nid."','".$phone."','".$gender."','".$father_names."','".$mother_names."','".$parent_phone."','".$ref_phone."','".$country."','".$province_id."','".$district_id."','".$sector."','".$cell_id."','".$village_id."','".$marital_status."', '".$kin_name."', '".$kin_relation."', '".$kin_address."', '".$kin_email."', '".$kin_tel."')");
                
                $ustatement1 = $this->connect->prepare("UPDATE tbl_applicant_education SET stu = '".$reg_no."' WHERE stu = '".$stu_code."'");
                $ustatement2 = $this->connect->prepare("UPDATE tbl_applicant_church SET stu = '".$reg_no."' WHERE stu = '".$stu_code."'");
                $ustatement3 = $this->connect->prepare("UPDATE tbl_language_pro SET stu = '".$reg_no."' WHERE stu = '".$stu_code."';");
                //get level data
                $leveData = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$progData["prg_type"]."' AND level_no=1");
                $leveData->execute();
                $lev=$leveData->fetch();
                
                //get acad year
                $acadData = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_intake WHERE intake_id='".$intake_id."'");
                $acadData->execute();
                $acad=$acadData->fetch();
                
                $acad_cycle_id=$acad['acad_cycle_id'];
                $prg_id=$DeptDataDetails["dept_id"];
                $level_id=$lev['level_id'];
                $prg_mode_id=$progData["mode"];
                $fac_id=$progDataDetails["fac_id"];
                $dept_id=$DeptDataDetails["dept_id"];
                $splz_id=$progData["dept_id"];
                
                $username = $reg_no."@njala.edu.sl";
                
                //register applicant
                $stmt20 = $this->connect->prepare("INSERT INTO tbl_register_program_ug(reg_no,acad_cycle_id,prg_id,splz_id,level_id,prg_mode_id,prg_type,fac_id,dept_id) 
                VALUES('".$reg_no."','".$acad_cycle_id."','".$prg_type."','".$splz_id."','".$level_id."','".$prg_mode_id."','".$prg_type."','".$fac_id."','".$dept_id."')");
                if($stmt->execute()){
                    $stmt20->execute();
                    $ustatement1->execute();
                    $ustatement2->execute();
                    $ustatement3->execute();
                    
                    $stmt01 = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE email='".$email."'");
                    $stmt01->execute();
                    if($stmt01->rowCount()>0){
                        $user=$stmt01->fetch();
                        $password=$user['password'];
                        
                        //create user account
                        $stmt02 = $this->connect->prepare("INSERT INTO tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id) 
                      	VALUES('".$reg_no."','".$lname."','".$fname."','".$username."','".$phone."','".$password."',4)");
                      	$stmt02->execute();
                      	//admit application
                        $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=3 WHERE Aprg_id='".$adm."'");
                      	$stmt03->execute();
                      	
                      	//get all modules assigned
                        $stmt04 = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND splz_id='".$splz_id."' AND level_id='".$level_id."' AND status=1");
                        $stmt04->execute();
                        if($stmt04->rowCount()>0){
                            while($module=$stmt04->fetch()){
                                $stmt041 = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no) VALUES('".$module['module_id']."','".$acad_cycle_id."','".$splz_id."','".$reg_no."')");
                                $stmt041->execute();  
                            }
                        }
                      	//reject all other programs
                        $stmt05 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=4 WHERE Stu_code='".$stu_code."' AND Aprg_id!='".$adm."'");
                      	$stmt05->execute();

                        //reassign docs
                        $stmt06 = $this->connect->prepare("UPDATE tbl_application_doc SET tracking_id='".$reg_no."' WHERE tracking_id='".$stu_code."'");
                      	$stmt06->execute();
                        
                        //update application
                        $stmt07 = $this->connect->prepare("UPDATE tbl_applicants SET status=3 WHERE code='".$stu_code."'");
                      	$stmt07->execute();
                      	
                      	//remove applicant account
                        $stmt08 = $this->connect->prepare("DELETE FROM tbl_student_login WHERE email='".$email."'");
                      	$stmt08->execute();
                    }

                    $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                    $sql->execute();
                    $data=$sql->fetch();
                    //send email
                    $year = date("Y");
                    $to = $email;
                    $subject = 'Congratulations on Your Admission to '.$data["full_name"];
                    $from = 'stumis';
                                            
                    $this->sendSMS($data["short_name"], $phone, $fname, $reg_no, $username, $password);
                    
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
                    $message2 = ' <title>'.$data["full_name"].'</title>';
                    $message2 = '<body">';
                    $message2 .= '
                        <div style="padding: 10px;border: 1px solid lightgray; width: 100%;">
                        <table>
                        <thead>
                        
                        <tr><th><img src="https://misnjala.edu.sl/img/logo/testnjala.png" width="80px"></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                               <td>
                                   <p>
                                        Dear <b>'.$fname.' '.$lname.'</b>,
                                        We are thrilled to inform you that your application for admission to '.$data["full_name"].' has been successful! Congratulations on your admission to the '.$progData['prg_type_full_name'].' program in the school of '.$progData['fac_full_name'].' for the '.$progData['intake_month'].' intake.
                                   </p>
                                   <p><b>Your student ID is: : '.$reg_no.'</b></p>
                                   <p>
                                        We were impressed with your qualifications and believe that you will excel in our academic community. '.$data["full_name"].', offers world-class school, and diverse opportunities for personal and professional growth.
                                   </p>
                                   <p>
                                        To accept your admission, please follow the instructions provided in your admission offer letter. If you have any questions, feel free to contact us. We are here to assist you in any way we can.
                                   </p>
                                   <p>
                                        We look forward to welcoming you to our campus community and supporting you throughout your educational journey at '.$data["full_name"].'. Congratulations once again on your well-deserved admission!
                                   </p>
                                   <br>
                                   <p>
                                        Best regards,<br>
                                        '.$data["short_name"].' Admission Committee
                                    </p>
                               </td>
                            </tr>
                               
                            <tr>
                                <td style="text-align:center;font-weight:bold;">
                                    <hr>
                                    &copy; '.$year.' ITEC. All rights reserved.<br>
                                    Designed by ITEC Ltd<br>
                                    KN 1 Rd, Kigali-Rwanda.<br>
                                    Phone (+232) 7945 3322
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
    }
    
    function sendNotificationEmail($from, $to, $fname){
        $year = date("Y");
        $subject = "Update";      
        
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        
        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
                        
        // Compose a simple HTML email message
        $body = '<html><head>';
        $body = ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        $body = ' <meta name="x-apple-disable-message-reformatting" />';
        $body = ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $body = ' <meta name="color-scheme" content="light dark" />';
        $body = ' <meta name="supported-color-schemes" content="light dark" />';
        $body = ' <title>'.$data['full_name'].'</title>';
        $body = '<body">';
        $body .= '
            <div style="padding: 10px;border: 1px solid lightgray; text-align: center;width: 500px;">
            <table>
            <tbody>
                <tr>
                   <td>
                       <p>Dear <b>'.$fname.'</b></p>
                       <p>An update has been posted to your application page. You may access your application page here: https://'.$_SERVER['SERVER_NAME'].'</p>
                   </td>
                </tr>
                   
                <tr>
                    <td>
                        <hr>
                        &copy; '.$year.' ITEC. All rights reserved.<br>
                        Designed by ITEC Ltd<br>
                        KN 1 Rd, Kigali-Rwanda.<br>
                        Phone (+232) 7945 3322
                    </td>
                </tr>
            </tbody>
           </table> 
           </div>';
        $body .= '</body></html>';


        mail($to, $subject, $body, $headers);
        return;
    }
    
    function notify($Aprg_id){
        $stmt0 = $this->connect->prepare("SELECT Stu_code FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $stmt0->execute([$Aprg_id]);
        $stu = $stmt0->fetch()['Stu_code'];
        
        $stmt = $this->connect->prepare("SELECT fname, email FROM tbl_applicants WHERE code = ?");
        $stmt->execute([$stu]);
        $data = $stmt->fetch();
        $fname = $data['fname'];
        $email = $data['email'];
        
        $this->sendNotificationEmail("stumis", $email, $fname);
        
    }
    
    function Forward(){
        $adm=$_POST['ad_m_i'];

        $checkType = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = '".$adm."'");
        $checkType->execute();
        $stu_code = $checkType->fetch()['Stu_code'];
        
        $stmt0 = $this->connect->prepare("UPDATE tbl_applicants SET submitted=2 WHERE code='".$stu_code."'");
        if($stmt0->execute()){
            $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=2 WHERE Stu_code='".$stu_code."' AND sts=11");
          	$stmt03->execute();
        }
        $data = array("status"=>"200","message" => "Data saved successfully!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    //  HOD Forward
    function Forward_Up(){
       $adm=$_POST['ad_m_i'];
       $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=11 WHERE Aprg_id='".$adm."' AND sts=1");
        $stmt03->execute();
        
        $data = array("status"=>"200","message" => "Data saved successfully!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
     
    }
    
    
    function Reject(){
        $adm=$_POST['ad_m_id'];
        $reason=$_POST['reason'];
        $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=4, rej_comment='".$reason."' WHERE Aprg_id='".$adm."'");
        if($stmt03->execute()){
            $this->notify($adm);
            $data = array("status"=>"200","message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        else {
            $data = array("status"=>"500","message" => "Failed to save data!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    }
    
    function Communicate(){
        $adm=$_POST['ad_m_id'];
        $message=$_POST['message'];
        $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=1, rej_comment='".$message."' WHERE Aprg_id='".$adm."'");
        if($stmt03->execute()){
            $this->notify($adm);
            $data = array("status"=>"200","message" => "Data saved successfully!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        else {
            $data = array("status"=>"500","message" => "Failed to save data!");
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    }
    
    function Register(){
        //personal
        $fname=$_POST['fname'];
        $lname=$_POST['lname'];
        $email=$_POST['email'];
        $nid=$_POST['nid'];
        $gender=$_POST['gender'];
        $father_names=$_POST['father_names'];
        $mother_names=$_POST['mother_names'];
        $dob = $_POST['dob']; 
        $marital_status = $_POST['marital_status']; 
        $kin_name = $_POST['kin_name']; 
        $kin_relation = $_POST['kin_relation']; 
        $kin_address = $_POST['kin_address']; 
        $kin_email = $_POST['kin_email']; 
        $kin_tel = $_POST['kin_tel']; 
        
        //address
        $country=(int)$_POST['country'];
        $nationality=(int)$_POST['nationality'];
        $province_id=(int)$_POST['province_id'];
        $district_id=(int)$_POST['district_id'];
        $street=$_POST['street'];
        
        //contact
        $phone=$_POST['phone'];
        $email=$_POST['email'];
        $parent_phone=$_POST['parent_phone'];
        $ref_phone=$_POST['ref_phone'];
            
        //academic
        $prg_type=$_POST["prg_type"];
        $fac_id=$_POST["fac_id"];
        $dept_id=$_POST["dept_id"];
        $splz_id=$_POST["spcs_id"];
        $prg_id=$_POST["spcs_id"];
        $level_id=$_POST['level_id'];
        $intake_id=$_POST["intake_id"];
        $prg_mode_id=$_POST["mode"];
        
        //passes
        
        $courses = $_POST['courses'];
        $grades = $_POST['grade'];
        
        // sponsorship
        $sponsor = $_POST['spon_id'];
        
        //get acad year
        $acadData = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_intake WHERE intake_id='".$intake_id."'");
        $acadData->execute();
        $acad=$acadData->fetch();
        $acad_cycle_id=$acad["acad_cycle_id"];
        
            
        //check for non duplicates
        $checkExists = $this->connect->prepare("SELECT * FROM tbl_admission WHERE email='".$email."' OR ID='".$nid."'");
        $checkExists->execute();
        if($checkExists->rowCount()>0){
            $data = array("status"=>"401","message" => "Email or ID was used before!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;    
        }
        else{
            //generate registration number and save data
            $currentYear = date('Y');
            $templ="NU".substr($currentYear, -2);
            $subcode="NU".substr($currentYear, -2).$prg_type;
            $checkcode = $this->connect->prepare("SELECT * FROM tbl_admission WHERE reg_no like '".$templ."%' ORDER BY adm_id DESC limit 1");
            $checkcode->execute();
            $codeData=$checkcode->fetch();
            $prevSuffix=substr($codeData['reg_no'],-4);
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
            $reg_no=$subcode.''.$fac_id.''.$suffix;
            $dt=date('Y-m-d');

            $stmt = $this->connect->prepare("INSERT INTO tbl_admission(intake_id,reg_no,fname,lname,email,nationality,ID,phone,gender,father_names,mother_names,parent_phone,ref_phone,country,province_id,district_id,sector,cell_id,village_id, dob, marital_status, kin_name, kin_relation, kin_address, kin_email, kin_tel,street) 
                  	VALUES('".$intake_id."','".$reg_no."','".$fname."','".$lname."','".$email."','".$nationality."','".$nid."','".$phone."','".$gender."','".$father_names."','".$mother_names."','".$parent_phone."','".$ref_phone."','".$country."','".$province_id."','".$district_id."','".$sector."','".$cell_id."','".$village_id."','".$dob."', '".$marital_status."', '".$kin_name."', '".$kin_relation."', '".$kin_address."', '".$kin_email."', '".$kin_tel."','".$street."')");
            
            //register
            $stmt20 = $this->connect->prepare("INSERT INTO tbl_register_program_ug
                                                (`reg_no`,`acad_cycle_id`,`prg_id`,`splz_id`,`level_id`,`prg_mode_id`,`prg_type`,`fac_id`,`dept_id`,`spon_id`)VALUES
                                                ('".$reg_no."','".$acad_cycle_id."','".$prg_id."','".$splz_id."','".$level_id."','".$prg_mode_id."','".$prg_type."',
                                                '".$fac_id."','".$dept_id."','".$sponsor."')");
            
            $username = $reg_no."@njala.edu.sl";
            
            if($stmt->execute()){
                    $stmt20->execute();
                    
                    for($i=0; $i<count($courses); $i++){
                        $pass = $this->connect->prepare("INSERT INTO tbl_principal_pass(reg_no, course, grade) VALUES ('".$reg_no."', '".$courses[$i]."', '".$grades[$i]."')");
                  	    $pass->execute();
                    }
                    
                    $dir = [
                        'cost' => 12,
                    ];
                    $password = password_hash($reg_no, PASSWORD_BCRYPT, $dir);
                    $stmt02 = $this->connect->prepare("INSERT INTO tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id) 
                  	VALUES('".$reg_no."','".$lname."','".$fname."','".$username."','".$phone."','".$password."',4)");
                  	$stmt02->execute();

                  	//get all modules assigned
                    $stmt04 = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' 
                    AND splz_id='".$splz_id."' AND level_id='".$level_id."' AND status=1");
                    $stmt04->execute();
                    if($stmt04->rowCount()>0){
                        while($module=$stmt04->fetch()){
                        $stmt041 = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no)VALUES('".$module['module_id']."','".$acad_cycle_id."','".$splz_id."','".$reg_no."')");
                        $stmt041->execute();  
                        }
                    }
                }

                $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                $sql->execute();
                $data=$sql->fetch();
                
                                    
                $this->sendSMS($data["short_name"], $phone, $fname, $reg_no, $username, $reg_no);
                
                //send email
                $year = date("Y");
                $to = $email;
                $subject = 'Congratulations on Your Admission to '.$data["full_name"];
                $from = 'stumis';
                                
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
                $message2 = ' <title>'.$data["full_name"].'</title>';
                $message2 = '<body">';
                $message2 .= '
                    <div style="padding: 10px;border: 1px solid lightgray; width: 100%;">
                    <table>
                    <thead>
                    <tr><th><img src="https://misnjala.edu.sl/img/logo/nlogo.png" width="100%" height="30px"></th></tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td>
                               <p>
                                    Dear <b>'.$fname.' '.$lname.'</b>,
                                    We are thrilled to inform you that your registration to '.$data["full_name"].' has been successful!</b>.
                               </p>
                                <p><b>Your student ID is: : '.$reg_no.'</b></p>
                                <p><b>Your username is : : '.$username.'</b></p>
                                <p><b>Your password is : : '.$reg_no.'</b></p>
                               <p>
                                    '.$data["full_name"].' offers world-class school, and diverse opportunities for personal and professional growth.
                               </p>
                               <p>
                                    If you have any questions, feel free to contact us. We are here to assist you in any way we can.
                               </p>
                               <p>
                                    We look forward to welcoming you to our campus community and supporting you throughout your educational journey at '.$data["full_name"].'. Congratulations once again on your well-deserved admission!
                               </p>
                               <br>
                               <p>You can access the NUMIS through the following link click login</p>
                               <p><a href="https://misnjala.edu.sl/auth">Login</a></p><br>
                               <p>
                                    Best regards,<br>
                                    '.$data["short_name"].' Admission Committee
                                </p>
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
                $message2 .= '</body></html>';
            
                // Sending email
                if(mail($to, $subject, $message2, $headers)){
                    $data = array("status"=>"200","message" => "Data saved successfully!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
                else {
                    $data = array("status"=>"200","message" => "Data saved successfully!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        }
    
    function updateRegistration(){
        $id = $_POST["reg_prg_id"];
        $reg_no = $_POST["reg_no"];
        $prg_mode_id = $_POST["prg_mode_id"];
        $reg_active = $_POST["reg_active"];
        
        $prev_data = $this->getPreviousData($id);
        
        if($allowed>0){
            $data = array("status"=>"401","message" => "Access denied, You can't update this data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;    
        }
        else{
            $stmt = $this->connect->prepare("UPDATE tbl_register_program_ug SET 
                                                            prg_mode_id = $prg_mode_id,
                                                            reg_active = $reg_active
                                                    WHERE reg_prg_id = $id");
            
            if($stmt->execute()){
                if($reg_active == 6){
                    $stmt = $this->connect->prepare("UPDATE tbl_markby_module SET status = 6 WHERE reg_no = ? AND acad_cycle_id = ? AND marks >= 50");
                    $stmt->execute([$reg_no, $prev_data['acad_cycle_id']]);
                }
                
                $data = array("status"=>"200","message" => "Data updated successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"401","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }
    
    function updatePrincipalPass(){
        $id = $_POST["course_id"];
        $course = $_POST["course"];
        $grade = $_POST["grade"];
        $stmt = $this->connect->prepare("UPDATE tbl_principal_pass SET course = ?, grade = ? WHERE id = ?");
            
        if($stmt->execute([$course, $grade, $id])){
            $data = array("status"=>"200","message" => "Data updated successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }else{
            $data = array("status"=>"401","message" => "Failed to update data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function updateSponsorship(){
        $id = $_POST["reg_prg_id"];
        $sponsor = $_POST["spon_id"];
        
        $stmt = $this->connect->prepare("UPDATE tbl_register_program_ug SET spon_id = $sponsor WHERE reg_prg_id = $id");
  
        if($stmt->execute()){
            $data = array("status"=>"200","message" => "Data updated successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }else{
            $data = array("status"=>"401","message" => "Failed to update data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function Repair(){
        $prg_type=$_POST["prg_type"];
        $fac_id=$_POST["fac_id"];
        $dept_id=$_POST["dept_id"];
        $splz_id=$_POST["splz_id"];
        $prg_id=$_POST["splz_id"];
        $level_id=$_POST['level_id'];
        $intake_id=$_POST["intake_id"];
        $prg_mode_id=$_POST["prg_mode_id"];
        $flag=0;
        
        $getstudents = $this->connect->prepare("SELECT ug.reg_no, ug.acad_cycle_id FROM tbl_register_program_ug ug JOIN tbl_admission ad ON ug.reg_no = ad.reg_no WHERE ad.intake_id = ? AND ug.splz_id = ? AND ug.level_id = ? AND ug.prg_mode_id = ? AND ug.prg_type = ? AND ug.fac_id = ?");
        $getstudents->execute([$intake_id, $splz_id, $level_id, $prg_mode_id, $prg_type, $fac_id]);
        while($student=$getstudents->fetch()){
            $reg_no = $student['reg_no'];
            $acad_cycle_id = $student['acad_cycle_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND splz_id='".$splz_id."' AND level_id='".$level_id."' AND status=1");
            $stmt->execute();
            if($stmt->rowCount()>0){
                while($module = $stmt->fetch()){
                    $module_id = $module['module_id'];
                    $stmt2 = $this->connect->prepare("SELECT reg_no FROM tbl_markby_module WHERE module_id='".$module_id."' AND splz_id='".$splz_id."' AND reg_no='".$reg_no."'");
                    $stmt2->execute();
                    if($stmt2->rowCount()==0){
                        $stmt3 = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no)VALUES('".$module_id."','".$acad_cycle_id."','".$splz_id."','".$reg_no."')");
                        $stmt3->execute();
                        $flag=$flag+1;
                    } else{
                        $stmt3 = $this->connect->prepare("UPDATE tbl_markby_module SET acad_cycle_id = '".$acad_cycle_id."' WHERE splz_id = '".$splz_id."' AND reg_no = '".$reg_no."' AND module_id = '".$module_id."'");
                        $stmt3->execute();
                        $flag=$flag+1;
                    }
                }
            }
        }
        if($flag>0){
            $data = array("status"=>"200","message" => "Operation done successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else {
            $data = array("status"=>"200","message" => "Up to date!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
 function LoadProgramAppl(){
     $app=$_POST['app'];
     $data_appl=$this->connect->prepare("SELECT
                                            tbl_admittedPRG.*
                                        FROM
                                        tbl_admittedPRG
                                        INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                        INNER JOIN tbl_specialization ON tbl_admittedPRG.dept_id = tbl_specialization.splz_id
                                        INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                        WHERE
                                        tbl_admittedPRG.Aprg_id = '".$app."' ");
    $data_appl->execute();
    $data_result=$data_appl->fetch();
    echo json_encode($data_result);
    
 }
 function Change_course(){
    if (!isset($_POST['ad_m_id_spec']) || !isset($_POST['campus_id_app']) || !isset($_POST['spec_id_up'])) {
        $data = array("status" => 400, "message" => "Missing required parameters.");
        echo json_encode($data);
        return;
    }

    // Assign variables and sanitize input
    $Adm_id = $_POST['ad_m_id_spec'];
    $campus_id = $_POST['campus_id_app'];
    $prg_type = $_POST['p_type_spec'];
    $fac_id = $_POST['fac_id_spec'];
    $spec_id = $_POST['spec_id_up'];

    // Prepare the SQL statement
    $stmt = $this->connect->prepare("UPDATE tbl_admittedPRG SET cump_id = :campus_id, prg_type = :prg_type, dept_id = :spec_id WHERE Aprg_id = :Adm_id");

    // Bind parameters
    $stmt->bindParam(':campus_id', $campus_id);
    $stmt->bindParam(':prg_type', $prg_type);
    $stmt->bindParam(':spec_id', $spec_id);
    $stmt->bindParam(':Adm_id', $Adm_id);

    // Execute the statement
    $result = $stmt->execute();

    // Check the result and return appropriate response
    if($result){
        $data = array("status" => 200, "message" => "Updated successfully!");
    } else {
        // Get error info
        $errorInfo = $stmt->errorInfo();
        $data = array("status" => 500, "message" => "Failed to update.", "error" => $errorInfo[2]);
    }
    echo json_encode($data);
}

}

$admission=new Admission();
$action = $_POST['action'];
switch($action){
    case 'admit':
        $admission->Admit();
        break;
    case 'reject':
        $admission->Reject();
        break;
    case 'forward':
        $admission->Forward();
        break;
    case 'communicate':
        $admission->Communicate();
        break;
    case 'register_new':
        $admission->Register();
        break;
    case 'repair':
        $admission->Repair();
        break;
    case 'update_acc':
        $admission->updateRegistration();
        break;
    case 'update_pass':
        $admission->updatePrincipalPass();
        break;
    case 'update_spon':
        $admission->updateSponsorship();
        break;
    case 'load_program_applic':
        $admission->LoadProgramAppl();
        break;
    case 'change_course_picker':
        $admission->Change_course();
        break;
    case 'forward_up':
      $admission->Forward_Up();
        break; 
}

?>