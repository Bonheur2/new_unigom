<?php
include ('../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        $cump_id=$progData['cump_id'];
        $prg_type=$progData['prg_type'];
        $dept_id=$progData['dept_id'];
        $level=$progData['level'];
        $mode=$progData['mode'];
        $fac_id=$progData['fac_id'];
        $splz=$progData['splz'];
        $acad_id=$progData['acad_id'];
        
        // $dept_id=$progData['dept_id'];
        // $prg_type=$progData["prg_type"];
        // $intake_id=$progData["intake_id"];
        // $level_id=$progData["level_id"];
        
        $select_compus="SELECT * FROM tbl_campus WHERE camp_id='$cump_id'";
        $cselect_compus=$this->connect->prepare($select_compus);
        $cselect_compus->execute();
        $row_cselect_compus=$cselect_compus->fetch();
        $campus_id=$row_cselect_compus['camp_id'];
        $compus=$row_cselect_compus['camp_full_name'];
        
        $select_program="SELECT * FROM tbl_program_type WHERE prg_type_id='$prg_type'";
        $cselect_program=$this->connect->prepare($select_program);
        $cselect_program->execute();
        $row_cselect_program=$cselect_program->fetch();
        $prg_id=$row_cselect_program['prg_type_id'];
        $program=$row_cselect_program['prg_type_full_name'];
        
        $select_school="SELECT * FROM tbl_faculty WHERE fac_id='$fac_id'";
        $cselect_school=$this->connect->prepare($select_school);
        $cselect_school->execute();
        $row_cselect_school=$cselect_school->fetch();
        $fac_id=$row_cselect_school['fac_id'];
        $school1=$row_cselect_school['fac_full_name'];
        
        $select_department="SELECT * FROM tbl_department WHERE dept_id='$dept_id'";
        $cselect_department=$this->connect->prepare($select_department);
        $cselect_department->execute();
        $row_cselect_department=$cselect_department->fetch();
        $dept_id=$row_cselect_department['dept_id'];
        $department=$row_cselect_department['dept_full_name'];
        
        
        $select_specialization="SELECT * FROM tbl_specialization WHERE splz_id='$splz'";
        $cselect_specialization=$this->connect->prepare($select_specialization);
        $cselect_specialization->execute();
        $row_cselect_specialization=$cselect_specialization->fetch();
        $splz_id=$row_cselect_specialization['splz_id'];
        $specialization=$row_cselect_specialization['splz_full_name'];
        
        
        $select_level="SELECT * FROM tbl_level WHERE level_id='$level'";
        $cselect_level=$this->connect->prepare($select_level);
        $cselect_level->execute();
        $row_cselect_level=$cselect_level->fetch();
        $level_id=$row_cselect_level['level_id'];
        $level_name=$row_cselect_level['level_full_name'];
        
        
        
        $select_academic="SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id='$acad_id'";
        $cselect_academic=$this->connect->prepare($select_academic);
        $cselect_academic->execute();
        $row_cselect_academic=$cselect_academic->fetch();
        $acad_cycle_id=$row_cselect_academic['acad_cycle_id'];
        $year_name=$row_cselect_academic['acad_year'];
        
        $select_mode="SELECT * FROM tbl_program_mode WHERE prg_mode_id='$prg_type'";
        $cselect_mode=$this->connect->prepare($select_mode);
        $cselect_mode->execute();
        $row_cselect_mode=$cselect_mode->fetch();
        $prg_mode_id=$row_cselect_mode['prg_mode_id'];
        
        // $fetchDepartment = $this->connect->prepare("SELECT fac_id FROM tbl_specialization WHERE splz_id = '".$dept_id."'");
        // $fetchDepartment->execute();
        // $progDataDetails=$fetchDepartment->fetch();
        
        // $fetchDept = $this->connect->prepare("SELECT dept_id FROM tbl_specialization WHERE splz_id = '".$dept_id."'");
        // $fetchDept->execute();
        // $DeptDataDetails=$fetchDept->fetch();
        
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
                $select_semester = "SELECT * FROM tbl_semester WHERE acad_year=:acad_year AND status=1";
                $cselect_semester = $this->connect->prepare($select_semester);
                $cselect_semester->bindParam(':acad_year', $acad_id, PDO::PARAM_STR);
                $cselect_semester->execute();
                $row_cselect_semester = $cselect_semester->fetch(PDO::FETCH_ASSOC);
            
                if (!$row_cselect_semester) {
                    $data = array("status" => "401", "message" => "This Academic Year does not have an open semester!");
                    http_response_code(401);
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    exit;
                }
                else
                {
                    $sem_id=$row_cselect_semester['sem_id'];
                
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
                $school=$fac_id;
                $reg_no=$subcode.''.$school.''.$suffix;
                $dt=date('Y-m-d');
                $stmt = $this->connect->prepare("INSERT INTO tbl_admission(reg_no,acad_cycle_id,fname,lname,email,nationality,ID,phone,gender,father_names,mother_names,parent_phone,ref_phone,country,province_id,district_id,sector,cell_id,village_id, marital_status, kin_name, kin_relation, kin_address, kin_email, kin_tel) 
                      	VALUES('".$reg_no."','".$acad_cycle_id."','".$fname."','".$lname."','".$email."','".$nationality."','".$nid."','".$phone."','".$gender."','".$father_names."','".$mother_names."','".$parent_phone."','".$ref_phone."','".$country."','".$province_id."','".$district_id."','".$sector."','".$cell_id."','".$village_id."','".$marital_status."', '".$kin_name."', '".$kin_relation."', '".$kin_address."', '".$kin_email."', '".$kin_tel."')");
                
                $ustatement1 = $this->connect->prepare("UPDATE tbl_applicant_education SET stu = '".$reg_no."' WHERE stu = '".$stu_code."'");
                $ustatement2 = $this->connect->prepare("UPDATE tbl_applicant_church SET stu = '".$reg_no."' WHERE stu = '".$stu_code."'");
                $ustatement3 = $this->connect->prepare("UPDATE tbl_language_pro SET stu = '".$reg_no."' WHERE stu = '".$stu_code."';");
                // $ustatement4 = $this->connect->prepare("UPDATE tbl_invoice SET reg_no = '".$reg_no."' WHERE reg_no = '".$stu_code."';");
                // $ustatement5 = $this->connect->prepare("UPDATE payment_trial SET reg_no = '".$reg_no."' WHERE reg_no = '".$stu_code."';");
                
                //get level data
                // $leveData = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$progData["prg_type"]."' AND level_no=1");
                // $leveData->execute();
                // $lev=$leveData->fetch();
                
                //get acad year
                // $acadData = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_intake WHERE intake_id='".$intake_id."'");
                // $acadData->execute();
                // $acad=$acadData->fetch();
                
                // $acad_cycle_id=$acad['acad_cycle_id'];
                // $prg_id=$DeptDataDetails["dept_id"];
                // $level_id=$progData["level_id"];
                // $prg_mode_id=$progData["mode"];
                // $fac_id=$progDataDetails["fac_id"];
                // $dept_id=$DeptDataDetails["dept_id"];
                // $splz_id=$progData["dept_id"];
                // $intake_id=$progData["intake_id"];
                
                $username = $reg_no."@njala.edu.sl";
                
                //register applicant
                $stmt20 = $this->connect->prepare("INSERT INTO tbl_register_program_ug(reg_no,acad_cycle_id,prg_id,splz_id,level_id,prg_mode_id,prg_type,fac_id,dept_id,camp_id) 
                VALUES('".$reg_no."','".$acad_cycle_id."','".$prg_type."','".$splz_id."','".$level_id."','".$prg_mode_id."','".$prg_type."','".$fac_id."','".$dept_id."','".$cump_id."')");
                $stmt21=$this->connect->prepare("INSERT INTO tbl_student_semester(`reg_no`, `acad_cycle_id`, `prg_type`, `splz_id`, `fac_id`, `dept_id`, `level_id`, `prg_mode_id`, `campus`,`sem_id`, `date_done`)
                VALUES('".$reg_no."', '".$acad_cycle_id."', '".$prg_type."', '".$splz_id."', '".$fac_id."', '".$dept_id."', '".$level_id."', '".$prg_mode_id."', '".$cump_id."','".$sem_id."', '".$dt."')");
                if($stmt->execute()){
                    $stmt20->execute();
                    $stmt21->execute();
                    $ustatement1->execute();
                    $ustatement2->execute();
                    $ustatement3->execute();
                    // $ustatement4->execute();
                    // $ustatement5->execute();
                    
                    $stmt01 = $this->connect->prepare("SELECT * FROM tbl_student_login WHERE email='".$email."'");
                    $stmt01->execute();
                    
                    // Initialize password variable
                    $password = '';
                    
                    if($stmt01->rowCount()>0){
                        $user=$stmt01->fetch();
                        $password=$user['password'];
                        
                        //create user account
                        $stmt02 = $this->connect->prepare("INSERT INTO tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id) 
                      	VALUES('".$reg_no."','".$lname."','".$fname."','".$username."','".$phone."','".$password."',4)");
                      	$stmt02->execute();
                    } else {
                        // Generate a default password if no student login record exists
                        $password = bin2hex(random_bytes(4)); // Generates an 8-character random password
                        
                        //create user account with generated password
                        $stmt02 = $this->connect->prepare("INSERT INTO tbl_users(Identification,family_name,first_name,email,phone_no,password,role_id) 
                      	VALUES('".$reg_no."','".$lname."','".$fname."','".$username."','".$phone."','".$password."',4)");
                      	$stmt02->execute();
                    }
                    
                    //admit application
                    $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=3,student_decision='accepted' WHERE Aprg_id='".$adm."'");
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
                    $stmt08 = $this->connect->prepare("DELETE FROM tbl_student_login WHERE email='".$email."'");                    $stmt08->execute();
                    
                    // Insert into tbl_invoice
                    $select_fee = $this->connect->prepare("SELECT * FROM tbl_fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id= :dept_id AND splz_id= :splz_id
                    AND level_id=:level_id AND name='Tution Fee'");
                    $select_fee->execute([':camp_id' => $cump_id, ':prg_type_id' => $prg_type, ':fac_id' => $fac_id,':dept_id' =>$dept_id,':splz_id'=>$splz,':level_id'=>$level]);
                
                    if($select_fee->rowCount() > 0) {
                        while ($row_fee = $select_fee->fetch()) {
                            $invoice_data = [
                                'reg_no' => $reg_no, 'acad_cycle_id' => $acad_cycle_id, 'month' => date("m"),
                                'level_id' => $level, 'fee_id' => $row_fee['id'],
                                'balance' => $row_fee['amount'], 'invoice_date' => date("Y-m-d"),
                                'date' => date("Y-m-d"), 'invoice_status' => 1,
                                'approval_status' => 1, 'payment_status' => 0
                            ];
                    
                            $insert_invoice = $this->connect->prepare("INSERT INTO tbl_invoice 
                                (`reg_no`, `acad_cycle_id`, `month`, `level_id`,`fee_id`, `balance`, `invoice_date`, 
                                `date`, `invoice_status`, `approval_status`, `payment_status`)
                                VALUES (:reg_no, :acad_cycle_id, :month, :level_id, :fee_id, :balance, :invoice_date, 
                                :date, :invoice_status, :approval_status, :payment_status)");
                    
                            if(!$insert_invoice->execute($invoice_data)) {
                                error_log("Failed to insert invoice for reg_no: " . $reg_no);
                            }
                        }
                    } else {
                        error_log("No fee categories found");
                    }

                    $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                    $sql->execute();
                    $data=$sql->fetch();
                    //send email
                    $year = date("Y");
                    $to = $email;
                    $subject = 'Admission Offer to '.$data["full_name"];
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
                                        Congratulations! We are delighted to inform you that your application for admission to  '.$data["full_name"].' has been successful.You have been admitted to the '.$compus.' for the '.$program.', In School Of '.$school1.', Department of '.$department.', in '.$specialization.' in Level '.$level_name.' Academic Year '.$year_name.'.
                                   </p>
                                   <p><b>Your student ID is: : '.$reg_no.'</b></p>
                                   <p><b>Your username is : : '.$username.'</b></p>
                                   <p><b>Your password remains the same.</b></p>
                                   <p>
                                        We were impressed with your qualifications and believe that you will excel in our academic community. '.$data["full_name"].', offers world-class school, and diverse opportunities for personal and professional growth.
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
                                    &copy; '.$year.' NJALA University. All rights reserved.<br>
                                    Designed by ITEC Ltd<br>
                                    NJALA-Sierra Leone.<br>
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
                        &copy; '.$year.' NJALA University. All rights reserved.<br>
                        Designed by ITEC Ltd<br>
                        NJALA-Sierra Leone.<br>
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

        // Get the specific record details
        $checkType = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $record = $checkType->fetch();
        
        if (!$record) {
            $data = array("status"=>"404","message" => "Record not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $record['Stu_code'];
        
        // Check if this record is eligible for update (sts = 11)
        if ($record['sts'] != 11) {
            $data = array("status"=>"403","message" => "This record has already been processed!");
            echo json_encode($data);
            return;
        }
        
        // Update only the specific record based on Aprg_id
        $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=2 WHERE Aprg_id = ?");
        $stmt03->execute([$adm]);
        
        // Check how many records for this student now have sts = 2
        $checkProcessed = $this->connect->prepare("SELECT COUNT(*) as processed FROM tbl_admittedPRG WHERE Stu_code = ? AND sts = 2");
        $checkProcessed->execute([$stu_code]);
        $processedCount = $checkProcessed->fetch()['processed'];
        
        // Check how many records still have sts = 11 (unprocessed)
        $checkUnprocessed = $this->connect->prepare("SELECT COUNT(*) as unprocessed FROM tbl_admittedPRG WHERE Stu_code = ? AND sts = 11");
        $checkUnprocessed->execute([$stu_code]);
        $unprocessedCount = $checkUnprocessed->fetch()['unprocessed'];
        
        // Check total records for this student
        $checkTotal = $this->connect->prepare("SELECT COUNT(*) as total FROM tbl_admittedPRG WHERE Stu_code = ?");
        $checkTotal->execute([$stu_code]);
        $totalCount = $checkTotal->fetch()['total'];
        
        // Update applicants table if at least one record has sts=2 AND there are records with status different from 11
        if ($processedCount >= 1 && ($totalCount - $unprocessedCount) > $processedCount) {
            $stmt0 = $this->connect->prepare("UPDATE tbl_applicants SET submitted=2 WHERE code = ?");
            $stmt0->execute([$stu_code]);
            $message = "Program updated. Applicant status updated due to mixed statuses!";
        } else if ($processedCount == $totalCount) {
            $stmt0 = $this->connect->prepare("UPDATE tbl_applicants SET submitted=2 WHERE code = ?");
            $stmt0->execute([$stu_code]);
            $message = "All programs processed. Applicant status updated!";
        } else {
            $message = "Program updated. " . $unprocessedCount . " program(s) remaining.";
        }
        
        $data = array("status"=>"200","message" => $message);
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
    function cancel_up(){
       $adm=$_POST['ad_m_i'];
       $stmt03 = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=0 WHERE Aprg_id='".$adm."' AND sts=1");
        $stmt03->execute();
        
        $data = array("status"=>"200","message" => "Data saved successfully!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
     
    }
    
    // Accept application - set status to 11 and cancel others in same department only
    function Accept_Application(){
        $adm = $_POST['ad_m_i'];
        
        // Get the user's department(s) from POST data
        $user_department = isset($_POST['user_department']) ? $_POST['user_department'] : '';
        
        if(empty($user_department)){
            $data = array("status"=>"403","message" => "Access denied. Department not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code and department for this application
        $checkType = $this->connect->prepare("SELECT Stu_code, dept_id FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $dept_id = $progData['dept_id'];
        
        // Verify that the application belongs to the user's department
        $dept_array = explode(',', $user_department);
        if(!in_array($dept_id, $dept_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only process applications for your department.");
            echo json_encode($data);
            return;
        }
        
        // Update the selected application to status 11 (accepted)
        $stmt_accept = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=11 WHERE Aprg_id = ?");
        $stmt_accept->execute([$adm]);
        
        if($stmt_accept->rowCount() == 0){
            $data = array("status"=>"400","message" => "Failed to accept application. Verification failed.");
            echo json_encode($data);
            return;
        }
        
        // Cancel all other applications for this student in ALL user's departments (status 5)
        $placeholders = implode(',', array_fill(0, count($dept_array), '?'));
        $sql = "UPDATE tbl_admittedPRG SET sts=5, rej_comment='Cancelled because another application was accepted' WHERE Stu_code = ? AND Aprg_id != ? AND dept_id IN ($placeholders)";
        $stmt_cancel = $this->connect->prepare($sql);
        
        $params = array_merge([$stu_code, $adm], $dept_array);
        $stmt_cancel->execute($params);
        
        $cancelled_count = $stmt_cancel->rowCount();
        
        $message = "Application accepted successfully!";
        if($cancelled_count > 0){
            $message .= " " . $cancelled_count . " other application(s) in your department(s) cancelled.";
        }
        
        $data = array("status"=>"200","message" => $message);
        echo json_encode($data);
    }
    
    // Transfer to foundation - set status to 4 and cancel others in same department only
    function Transfer_Foundation(){
        $adm = $_POST['ad_m_i'];
        
        // Get the user's department(s) from POST data
        $user_department = isset($_POST['user_department']) ? $_POST['user_department'] : '';
        
        if(empty($user_department)){
            $data = array("status"=>"403","message" => "Access denied. Department not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code and department for this application
        $checkType = $this->connect->prepare("SELECT Stu_code, dept_id FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $dept_id = $progData['dept_id'];
        
        // Verify that the application belongs to the user's department
        $dept_array = explode(',', $user_department);
        if(!in_array($dept_id, $dept_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only process applications for your department.");
            echo json_encode($data);
            return;
        }
        
        // Update the selected application to status 4 (transferred to foundation)
        $stmt_foundation = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=6 WHERE Aprg_id = ?");
        $stmt_foundation->execute([$adm]);
        
        if($stmt_foundation->rowCount() == 0){
            $data = array("status"=>"400","message" => "Failed to transfer application. Verification failed.");
            echo json_encode($data);
            return;
        }
        
        // Cancel all other applications for this student in ALL user's departments (status 5)
        $placeholders = implode(',', array_fill(0, count($dept_array), '?'));
        $sql = "UPDATE tbl_admittedPRG SET sts=5, rej_comment='Cancelled because another application was transferred to foundation' WHERE Stu_code = ? AND Aprg_id != ? AND dept_id IN ($placeholders)";
        $stmt_cancel = $this->connect->prepare($sql);
        
        $params = array_merge([$stu_code, $adm], $dept_array);
        $stmt_cancel->execute($params);
        
        $cancelled_count = $stmt_cancel->rowCount();
        
        $message = "Application transferred to foundation successfully!";
        if($cancelled_count > 0){
            $message .= " " . $cancelled_count . " other application(s) in your department(s) cancelled.";
        }
        
        $data = array("status"=>"200","message" => $message);
        echo json_encode($data);
    }
    
    // Cancel application - set status to 5 (only cancels selected application, doesn't affect others)
    function Cancel_Application(){
        $adm = $_POST['ad_m_i'];
        $cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : '';
        
        if(empty($cancel_reason)){
            $data = array("status"=>"400","message" => "Cancellation reason is required!");
            echo json_encode($data);
            return;
        }
        
        // Get the user's department(s) from POST data
        $user_department = isset($_POST['user_department']) ? $_POST['user_department'] : '';
        
        if(empty($user_department)){
            $data = array("status"=>"403","message" => "Access denied. Department not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code, application details and department
        $checkType = $this->connect->prepare("SELECT Stu_code, dept_id FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $dept_id = $progData['dept_id'];
        
        // Verify that the application belongs to the user's department
        $dept_array = explode(',', $user_department);
        if(!in_array($dept_id, $dept_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only cancel applications for your department.");
            echo json_encode($data);
            return;
        }
        
        // Update the application to status 5 (cancelled) with reason
        $stmt_cancel = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=5, rej_comment= ? WHERE Aprg_id = ?");
        
        if($stmt_cancel->execute([$cancel_reason, $adm])){
            if($stmt_cancel->rowCount() == 0){
                $data = array("status"=>"400","message" => "Failed to cancel application. Verification failed.");
                echo json_encode($data);
                return;
            }
            
            // Send notification email to applicant
            $stmt_applicant = $this->connect->prepare("SELECT fname, lname, email FROM tbl_applicants WHERE code = ?");
            $stmt_applicant->execute([$stu_code]);
            $applicant = $stmt_applicant->fetch();
            
            if($applicant){
                // Get application details for email
                $stmt_app_details = $this->connect->prepare("SELECT 
                    tbl_admittedPRG.*,
                    tbl_department.dept_full_name,
                    tbl_specialization.splz_full_name
                FROM tbl_admittedPRG
                LEFT JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                LEFT JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                WHERE tbl_admittedPRG.Aprg_id = ?");
                $stmt_app_details->execute([$adm]);
                $app_details = $stmt_app_details->fetch();
                
                $this->sendCancellationEmail($applicant['fname'], $applicant['lname'], $applicant['email'], $cancel_reason, $app_details);
            }
            
            $data = array("status"=>"200","message" => "Application cancelled successfully! Notification sent to applicant.");
            echo json_encode($data);
        } else {
            $data = array("status"=>"400","message" => "Failed to cancel application!");
            echo json_encode($data);
        }
    }
    
    function sendCancellationEmail($fname, $lname, $email, $reason, $app_details = null){
        $year = date("Y");
        $subject = "Application Cancellation Notice";
        
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        
        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Create email headers
        $headers .= 'From: stumis'."\r\n".
        'Reply-To: stumis'."\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        // Build application details section
        $app_info = '';
        if($app_details){
            $app_info = '<div style="background-color: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Application Details:</strong></p>
                <p style="margin: 5px 0;"><strong>Department:</strong> '.$app_details['dept_full_name'].'</p>
                <p style="margin: 5px 0;"><strong>Specialization:</strong> '.$app_details['splz_full_name'].'</p>
            </div>';
        }
        
        // Compose a simple HTML email message
        $message = '<html><head>';
        $message .= ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        $message .= ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $message .= ' <title>'.$data['full_name'].'</title>';
        $message .= '</head><body>';
        $message .= '
            <div style="padding: 20px; border: 1px solid #ddd; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="https://misnjala.edu.sl/img/logo/testnjala.png" width="80px" alt="'.$data['short_name'].'">
                </div>
                <h2 style="color: #dc3545; text-align: center;">Application Cancellation Notice</h2>
                <p>Dear <strong>'.$fname.' '.$lname.'</strong>,</p>
                <p>We regret to inform you that your application to '.$data['full_name'].' has been cancelled.</p>
                '.$app_info.'
                <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0;">
                    <p style="margin: 0;"><strong>Reason for Cancellation:</strong></p>
                    <p style="margin: 10px 0 0 0;">'.$reason.'</p>
                </div>
                <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
                    <p style="margin: 0;"><strong>Important Note:</strong></p>
                    <p style="margin: 10px 0 0 0;">This cancellation only affects your application to the specific department mentioned above. If you have applications to other departments, they remain unaffected.</p>
                </div>
                <p>If you believe this decision was made in error or if you have any questions, please contact our admissions office immediately.</p>
                <p><strong>Contact Information:</strong><br>
                Phone: +232 74 001023, +232 79 102840, +232 76 811846<br>
                Email: admissions@njala.edu.sl</p>
                <p>Best regards,<br>
                <strong>'.$data['short_name'].' Admissions Office</strong></p>
                <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                <p style="text-align: center; color: #6c757d; font-size: 12px;">
                    &copy; '.$year.' '.$data['full_name'].'. All rights reserved.<br>
                    Designed by ITEC Ltd<br>
                    NJALA-Sierra Leone<br>
                    Phone (+232) 7945 3322
                </p>
            </div>';
        $message .= '</body></html>';
        
        mail($email, $subject, $message, $headers);
        return;
    }
    
    function Admit_Application(){
        $adm = $_POST['ad_m_i'];
        
        // Get the user's faculty from POST data
        $user_faculty = isset($_POST['user_faculty']) ? $_POST['user_faculty'] : '';
        
        if(empty($user_faculty)){
            $data = array("status"=>"403","message" => "Access denied. Faculty not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code, faculty and current status for this application
        $checkType = $this->connect->prepare("SELECT Stu_code, fac_id, sts FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $fac_id = $progData['fac_id'];
        $current_sts = $progData['sts'];
        
        // Verify that the application belongs to the user's faculty
        $fac_array = explode(',', $user_faculty);
        if(!in_array($fac_id, $fac_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only process applications for your faculty.");
            echo json_encode($data);
            return;
        }
        
        // Verify application is in correct status (should be 11 - accepted by HOD)
        if($current_sts != 11){
            $data = array("status"=>"403","message" => "This application has not been accepted by HOD or has already been processed.");
            echo json_encode($data);
            return;
        }
        
        // Update the selected application to status 2 (forwarded to Registrar for final admission)
        $stmt_forward = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=2 WHERE Aprg_id = ?");
        $stmt_forward->execute([$adm]);
        
        if($stmt_forward->rowCount() == 0){
            $data = array("status"=>"400","message" => "Failed to forward application. Verification failed.");
            echo json_encode($data);
            return;
        }
        
        // Cancel all other applications for this student in ALL user's faculties (status 5)
        $placeholders = implode(',', array_fill(0, count($fac_array), '?'));
        $sql = "UPDATE tbl_admittedPRG SET sts=5, rej_comment='Cancelled because another application in the same faculty was forwarded to Registrar' WHERE Stu_code = ? AND Aprg_id != ? AND fac_id IN ($placeholders)";
        $stmt_cancel = $this->connect->prepare($sql);
        
        $params = array_merge([$stu_code, $adm], $fac_array);
        $stmt_cancel->execute($params);
        
        $cancelled_count = $stmt_cancel->rowCount();
        
        $message = "Application forwarded to Registrar successfully!";
        if($cancelled_count > 0){
            $message .= " " . $cancelled_count . " other application(s) in your faculty cancelled.";
        }
        
        $data = array("status"=>"200","message" => $message);
        echo json_encode($data);
    }
    function Dean_Transfer_Foundation(){
        $adm = $_POST['ad_m_i'];
        
        // Get the user's faculty from POST data
        $user_faculty = isset($_POST['user_faculty']) ? $_POST['user_faculty'] : '';
        
        if(empty($user_faculty)){
            $data = array("status"=>"403","message" => "Access denied. Faculty not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code, faculty and current status for this application
        $checkType = $this->connect->prepare("SELECT Stu_code, fac_id, sts FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $fac_id = $progData['fac_id'];
        $current_sts = $progData['sts'];
        
        // Verify that the application belongs to the user's faculty
        $fac_array = explode(',', $user_faculty);
        if(!in_array($fac_id, $fac_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only process applications for your faculty.");
            echo json_encode($data);
            return;
        }
        
        // Verify application is in correct status (should be 11 - accepted by HOD)
        if($current_sts != 11){
            $data = array("status"=>"403","message" => "This application has not been accepted by HOD or has already been processed.");
            echo json_encode($data);
            return;
        }
        
        // Update the selected application to status 4 (transferred to foundation)
        $stmt_foundation = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=6 WHERE Aprg_id = ?");
        $stmt_foundation->execute([$adm]);
        
        if($stmt_foundation->rowCount() == 0){
            $data = array("status"=>"400","message" => "Failed to transfer application. Verification failed.");
            echo json_encode($data);
            return;
        }
        
        // Cancel all other applications for this student in ALL user's faculties (status 5)
        $placeholders = implode(',', array_fill(0, count($fac_array), '?'));
        $sql = "UPDATE tbl_admittedPRG SET sts=5, rej_comment='Cancelled because another application in the same faculty was transferred to foundation' WHERE Stu_code = ? AND Aprg_id != ? AND fac_id IN ($placeholders)";
        $stmt_cancel = $this->connect->prepare($sql);
        
        $params = array_merge([$stu_code, $adm], $fac_array);
        $stmt_cancel->execute($params);
        
        $cancelled_count = $stmt_cancel->rowCount();
        
        $message = "Application transferred to foundation successfully!";
        if($cancelled_count > 0){
            $message .= " " . $cancelled_count . " other application(s) in your faculty cancelled.";
        }
        
        $data = array("status"=>"200","message" => $message);
        echo json_encode($data);
    }
    // DEAN: Cancel application - set status to 5 (only cancels selected application)
    function Dean_Cancel_Application(){
        $adm = $_POST['ad_m_i'];
        $cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : '';
        
        if(empty($cancel_reason)){
            $data = array("status"=>"400","message" => "Cancellation reason is required!");
            echo json_encode($data);
            return;
        }
        
        // Get the user's faculty from POST data
        $user_faculty = isset($_POST['user_faculty']) ? $_POST['user_faculty'] : '';
        
        if(empty($user_faculty)){
            $data = array("status"=>"403","message" => "Access denied. Faculty not identified.");
            echo json_encode($data);
            return;
        }
        
        // Get student code and faculty for this application
        $checkType = $this->connect->prepare("SELECT Stu_code, fac_id, sts FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $fac_id = $progData['fac_id'];
        $current_sts = $progData['sts'];
        
        // Verify that the application belongs to the user's faculty
        $fac_array = explode(',', $user_faculty);
        if(!in_array($fac_id, $fac_array)){
            $data = array("status"=>"403","message" => "Access denied. You can only cancel applications for your faculty.");
            echo json_encode($data);
            return;
        }
        
        // Verify application is in correct status (should be 11 - accepted by HOD)
        if($current_sts != 11){
            $data = array("status"=>"403","message" => "This application has not been accepted by HOD or has already been processed.");
            echo json_encode($data);
            return;
        }
        
        // Update the selected application to status 5 (cancelled) with reason
        $placeholders = implode(',', array_fill(0, count($fac_array), '?'));
        $sql = "UPDATE tbl_admittedPRG SET sts=5, rej_comment=? WHERE Aprg_id = ? AND fac_id IN ($placeholders)";
        $stmt_cancel_selected = $this->connect->prepare($sql);
        
        $params = array_merge([$cancel_reason, $adm], $fac_array);
        
        if($stmt_cancel_selected->execute($params)){
            if($stmt_cancel_selected->rowCount() == 0){
                $data = array("status"=>"400","message" => "Failed to cancel application. Verification failed or application not in your faculty.");
                echo json_encode($data);
                return;
            }
            
            // Cancel ALL OTHER applications for this student in the same faculty (status 5)
            $sql_cancel_others = "UPDATE tbl_admittedPRG SET sts=5, rej_comment='Cancelled because another application in the same faculty was cancelled by Dean' WHERE Stu_code = ? AND Aprg_id != ? AND fac_id IN ($placeholders)";
            $stmt_cancel_others = $this->connect->prepare($sql_cancel_others);
            
            $params_others = array_merge([$stu_code, $adm], $fac_array);
            $stmt_cancel_others->execute($params_others);
            
            $cancelled_others_count = $stmt_cancel_others->rowCount();
            
            // Send notification email to applicant
            $stmt_applicant = $this->connect->prepare("SELECT fname, lname, email FROM tbl_applicants WHERE code = ?");
            $stmt_applicant->execute([$stu_code]);
            $applicant = $stmt_applicant->fetch();
            
            if($applicant){
                // Get application details for email
                $stmt_app_details = $this->connect->prepare("SELECT 
                    tbl_admittedPRG.*,
                    tbl_faculty.fac_full_name,
                    tbl_department.dept_full_name,
                    tbl_specialization.splz_full_name
                FROM tbl_admittedPRG
                LEFT JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                LEFT JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                LEFT JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                WHERE tbl_admittedPRG.Aprg_id = ?");
                $stmt_app_details->execute([$adm]);
                $app_details = $stmt_app_details->fetch();
                
                // $this->sendCancellationEmail($applicant['fname'], $applicant['lname'], $applicant['email'], $cancel_reason, $app_details);
            }
            
            $message = "Application cancelled successfully!";
            if($cancelled_others_count > 0){
                $message .= " " . $cancelled_others_count . " other application(s) in your faculty also cancelled.";
            }
            $message .= " Notification sent to applicant.";
            
            $data = array("status"=>"200","message" => $message);
            echo json_encode($data);
        } else {
            $data = array("status"=>"400","message" => "Failed to cancel application!");
            echo json_encode($data);
        }
    }
    
    // Simple admission function - just changes status to 3 (admitted) without full registration process
    function Simple_Admit(){
        $adm = $_POST['ad_m_i'];
        
        // Get application details
        $checkType = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $checkType->execute([$adm]);
        $progData = $checkType->fetch();
        
        if(!$progData){
            $data = array("status"=>"400","message" => "Application not found!");
            echo json_encode($data);
            return;
        }
        
        $stu_code = $progData['Stu_code'];
        $current_sts = $progData['sts'];
        $cump_id = $progData['cump_id'];
        $prg_type = $progData['prg_type'];
        $fac_id = $progData['fac_id'];
        $dept_id = $progData['dept_id'];
        $splz = $progData['splz'];
        $level = $progData['level'];
        $acad_id = $progData['acad_id'];
        
        // Verify application is in correct status (should be 2 - forwarded by Dean)
        if($current_sts != 2){
            $data = array("status"=>"403","message" => "This application cannot be admitted. It has not been forwarded by Dean or has already been processed.");
            echo json_encode($data);
            return;
        }
        
        // Simply update the application status to 3 (admitted)
        $stmt_admit = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=3, student_decision='accepted' WHERE Aprg_id = ?");
        
        if($stmt_admit->execute([$adm])){
            if($stmt_admit->rowCount() == 0){
                $data = array("status"=>"400","message" => "Failed to admit application. Verification failed.");
                echo json_encode($data);
                return;
            }
            
            // Generate invoices for the admitted student
            $select_fee = $this->connect->prepare("SELECT * FROM tbl_fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id ");
            $select_fee->execute([
                ':camp_id' => $cump_id, 
                ':prg_type_id' => $prg_type, 
                ':fac_id' => $fac_id,
                ':dept_id' => $dept_id,
                ':splz_id' => $splz,
                ':level_id' => $level
            ]);
            
            $invoice_count = 0;
            if($select_fee->rowCount() > 0) {
                while ($row_fee = $select_fee->fetch()) {
                    // Check if invoice already exists to avoid duplicates
                    $check_invoice = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = :reg_no AND acad_cycle_id = :acad_cycle_id AND fee_id = :fee_id");
                    $check_invoice->execute([
                        ':reg_no' => $stu_code,
                        ':acad_cycle_id' => $acad_id,
                        ':fee_id' => $row_fee['id']
                    ]);
                    
                    if($check_invoice->rowCount() == 0){
                        $invoice_data = [
                            'reg_no' => $stu_code,
                            'acad_cycle_id' => $acad_id,
                            'month' => date("m"),
                            'level_id' => $level,
                            'fee_id' => $row_fee['id'],
                            'balance' => $row_fee['amount'],
                            'invoice_date' => date("Y-m-d"),
                            'date' => date("Y-m-d"),
                            'invoice_status' => 1,
                            'approval_status' => 1,
                            'payment_status' => 0
                        ];
                
                        $insert_invoice = $this->connect->prepare("INSERT INTO tbl_invoice 
                            (`reg_no`, `acad_cycle_id`, `month`, `level_id`, `fee_id`, `balance`, `invoice_date`, 
                            `date`, `invoice_status`, `approval_status`, `payment_status`)
                            VALUES (:reg_no, :acad_cycle_id, :month, :level_id, :fee_id, :balance, :invoice_date, 
                            :date, :invoice_status, :approval_status, :payment_status)");
                
                        if($insert_invoice->execute($invoice_data)) {
                            $invoice_count++;
                        } else {
                            error_log("Failed to insert invoice for tracking_id: " . $stu_code);
                        }
                    }
                }
            }
            
            // Reject all other programs for this student (set to status 4)
            $stmt_reject_others = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts=4, rej_comment='Rejected because another program was admitted' WHERE Stu_code = ? AND Aprg_id != ?");
            $stmt_reject_others->execute([$stu_code, $adm]);
            
            // Update applicant status
            $stmt_update_applicant = $this->connect->prepare("UPDATE tbl_applicants SET status=3 WHERE code = ?");
            $stmt_update_applicant->execute([$stu_code]);
            
            // Get applicant details for email
            $stmt_applicant = $this->connect->prepare("SELECT fname, lname, email FROM tbl_applicants WHERE code = ?");
            $stmt_applicant->execute([$stu_code]);
            $applicant = $stmt_applicant->fetch();
            
            // Get university details
            $sql_uni = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
            $sql_uni->execute();
            $university = $sql_uni->fetch();
            
            if($applicant){
                // Send admission email with acceptance letter attached
                $this->sendAdmissionEmailWithLetter(
                    $applicant['fname'], 
                    $applicant['lname'], 
                    $applicant['email'], 
                    $stu_code,
                    $university
                );
            }
            
            $message = "Application admitted successfully!";
            if($invoice_count > 0){
                $message .= " " . $invoice_count . " invoice(s) generated.";
            } else {
                $message .= " No fee categories found or invoices already exist.";
            }
            $message .= " Admission letter sent to applicant's email.";
            
            $data = array("status"=>"200","message" => $message);
            echo json_encode($data);
        } else {
            $data = array("status"=>"500","message" => "Failed to admit application!");
            echo json_encode($data);
        }
    }
    
    function sendAdmissionEmailWithLetter($fname, $lname, $email, $stu_code, $university){
        $year = date("Y");
        $subject = "Congratulations! Admission Offer to " . $university['full_name'];
        
        // Generate PDF URL for acceptance letter (absolute URL)
        $pdf_url = "https://" . $_SERVER['HTTP_HOST'] . "/files/application/acceptance.php?k=" . $stu_code;
        
        // Simpler email message - PDF will be attached
        $html_message = '
        <div style="padding: 20px; border: 1px solid #ddd; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="https://misnjala.edu.sl/img/logo/testnjala.png" width="80px" alt="' . $university['short_name'] . '">
            </div>
            <h2 style="color: #28a745; text-align: center;">🎉 Congratulations! Offer of Admission</h2>
            <p>Dear <strong>' . $fname . ' ' . $lname . '</strong>,</p>
            <p>We are delighted to inform you that you have been <strong>ADMITTED</strong> to <strong>' . $university['full_name'] . '</strong> for the current academic year.</p>
            
            <div style="background-color: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 20px 0; border-radius: 5px;">
                <p style="margin: 0;"><strong>✅ Your Application Status: ADMITTED</strong></p>
                <p style="margin: 10px 0 0 0;">Your tracking number: <strong>' . $stu_code . '</strong></p>
            </div>
            
            <h3 style="color: #333; margin-top: 25px;">📋 Next Steps:</h3>
            <ol style="line-height: 1.8;">
                <li><strong>Review Your Acceptance Letter:</strong> Your official admission letter is attached to this email as a PDF file.</li>
                <li><strong>Check Payment Information:</strong> The attached letter contains fee payment details and deadlines.</li>
                <li><strong>Register Online:</strong> Visit <a href="https://misnjala.edu.sl" style="color: #007bff;">misnjala.edu.sl</a> to complete your registration.</li>
                <li><strong>Pay Your Fees:</strong> Make payment before the deadline stated in your acceptance letter.</li>
                <li><strong>Report to Campus:</strong> Report on the date specified in your admission letter.</li>
            </ol>
            
            <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; border-radius: 5px;">
                <p style="margin: 0;"><strong>📎 Attached Document:</strong></p>
                <p style="margin: 10px 0 0 0;">Your <strong>Admission Offer Letter</strong> is attached to this email. Please download and save it for your records.</p>
            </div>
            
            <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; border-radius: 5px;">
                <p style="margin: 0;"><strong>⚠️ Important Notes:</strong></p>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Keep your acceptance letter safe - you will need it during registration</li>
                    <li>Payment must be completed by the deadline to secure your admission</li>
                    <li>University accommodation is on a first-come-first-served basis</li>
                    <li>Print and bring your acceptance letter and payment receipt on registration day</li>
                </ul>
            </div>
            
            <h3 style="color: #333; margin-top: 25px;">📞 Need Help?</h3>
            <p><strong>Contact Information:</strong></p>
            <ul style="line-height: 1.8;">
                <li><strong>Phone:</strong> +232 74 001023, +232 79 102840, +232 76 811846</li>
                <li><strong>Email:</strong> admissions@njala.edu.sl</li>
                <li><strong>Website:</strong> <a href="https://misnjala.edu.sl" style="color: #007bff;">misnjala.edu.sl</a></li>
            </ul>
            
            <p style="margin-top: 25px;">Once again, congratulations on your admission! We look forward to welcoming you to our academic community.</p>
            
            <p style="margin-top: 20px;">Best regards,<br>
            <strong>' . $university['short_name'] . ' Registrar\'s Office</strong></p>
            
            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
            <p style="text-align: center; color: #6c757d; font-size: 12px;">
                &copy; ' . $year . ' ' . $university['full_name'] . '. All rights reserved.<br>
                Designed by ITEC Ltd<br>
                NJALA-Sierra Leone<br>
                Phone (+232) 7945 3322
            </p>
        </div>';
        
        // Prepare payload for outbound email API
        $payload = array(
            "auth_token" => "5373eee9-1ee9-4277-88aa-5d38733221ee",
            "recipient" => $email,
            "subject" => $subject,
            "message" => $html_message,
            "is_html" => true,
            "attachments" => array(
                array(
                    "url" => $pdf_url,
                    "filename" => "Admission_Offer_Letter_" . $stu_code . ".pdf"
                )
            )
        );
        
        // Send email via cURL
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://njalaoutbound.itechost.rw/out.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60, // Increased timeout for PDF download
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        // Log the response for debugging
        if ($err) {
            error_log("Email sending error: " . $err);
            return false;
        } else {
            error_log("Admission email API response (HTTP $http_code): " . $response);
            error_log("Email sent to: " . $email . " with attachment: " . $pdf_url);
            return true;
        }
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
   case 'cancel_up':
      $admission->cancel_up();
        break;        
    case 're_register':
      $admission->Re_register_student();
        break;
    case 'accept_application':
        $admission->Accept_Application();
        break;
    case 'transfer_foundation':
        $admission->Transfer_Foundation();
        break;
    case 'cancel_application':
        $admission->Cancel_Application();
        break;
    case 'admit_application':
        $admission->Admit_Application();
        break;
    case 'dean_transfer_foundation':
        $admission->Dean_Transfer_Foundation();
        break;
    case 'dean_cancel_application':
        $admission->Dean_Cancel_Application();
        break;
    case 'simple_admit':
        $admission->Simple_Admit();
        break;    
}

?>