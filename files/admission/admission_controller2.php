<?php
include ('../../meet/con.php');
$connection=$conn;
class Admission{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}

    function Admit(){
        $adm = $_POST['admId'];

// Get applicant data
        $checkType = $this->connect->prepare("SELECT tbl_admittedPRG.*,tbl_program_type.prg_type_full_name, tbl_specialization.splz_full_name
        FROM tbl_admittedPRG
        INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
        INNER JOIN tbl_specialization ON tbl_admittedPRG.dept_id = tbl_specialization.splz_id 
        
        WHERE Aprg_id = :adm");
        $checkType->bindParam(':adm', $adm);
        $checkType->execute();
        $progData = $checkType->fetch();
        $stu_code = $progData['Stu_code'];
        $stmt0 = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code = :stu_code");
        $stmt0->bindParam(':stu_code', $stu_code);
        $stmt0->execute();

        if($stmt0->rowCount() > 0) {
            $stud=$stmt0->fetch();
            
            $fname=$stud['fname'];
            $lname=$stud['lname'];
            $email=$stud['email'];
            
             
                //register applicant
                
                 
                    $filenameee = $_FILES['adm_letter']['name'];
                    $fileName = $_FILES['adm_letter']['tmp_name'];
                    
                    $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
                    $sql->execute();
                    $data = $sql->fetch();
                    
                    $year = date("Y");
                    $to = $email;
                    $subject = 'Congratulations on Your OFFER OF ADMISSION to ' . $data["full_name"];
                    $fromemail = 'stumis';
                    
                    $message2 = "<html><head>";
                    $message2 .= " <meta name='viewport' content='width=device-width, initial-scale=1.0' />";
                    $message2 .= " <meta name='x-apple-disable-message-reformatting' />";
                    $message2 .= " <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
                    $message2 .= " <meta name='color-scheme' content='light dark' />";
                    $message2 .= " <meta name='supported-color-schemes' content='light dark' />";
                    $message2 .= " <title>" . $data['full_name'] . "</title>";
                    $message2 .= "<body>";
                    $message2 .= "
                        <div style='padding: 10px;border: 1px solid lightgray; width: 100%;'>
                        <table>
                        <thead>
                        <tr><th><img src='https://misnjala.edu.sl/img/logo/testnjala.png' width='80px'></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                               <td>
                                   <p>
                                      Dear <b>" . $fname . " " . $lname . "</b>,
                                           We are thrilled to inform you that you have been offered  an Admission to " . $data['full_name'] . ". Congratulations on your admission! Please find the enclosed document for details regarding your application.
                                        </p>
                                       <br>
                                     <p>
                                        Best regards,<br>
                                        " . $data['full_name'] . " Admission Committee
                                    </p>
                               </td>
                            </tr>
                            <tr>
                                <td style='text-align:center;font-weight:bold;'>
                                    <hr>
                                    &copy; " . $year . " ITEC. All rights reserved.<br>
                                    Designed by ITEC Ltd<br>
                                    KN 1 Rd, Kigali-Rwanda.<br>
                                    Phone (+232) 7945 3322
                                </td>
                            </tr>
                        </tbody>
                       </table> 
                       </div>";
                    $message2 .= "</body></html>";
                    
                    $content = file_get_contents($fileName);
                    $content = chunk_split(base64_encode($content));
                    
                    $separator = md5(time());
                    $eol = "\r\n";
                    
                    // Main header
                    $headers = "From: " . $data["full_name"] . " <" . $fromemail . ">" . $eol;
                    $headers .= "MIME-Version: 1.0" . $eol;
                    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
                    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
                    $headers .= "This is a MIME encoded message." . $eol;
                    
                    // Message body
                    $body = "--" . $separator . $eol;
                    $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
                    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
                    $body .= $message2 . $eol;
                    
                    // Attachment
                    $body .= "--" . $separator . $eol;
                    $body .= "Content-Type: application/octet-stream; name=\"" . $filenameee . "\"" . $eol;
                    $body .= "Content-Transfer-Encoding: base64" . $eol;
                    $body .= "Content-Disposition: attachment; filename=\"" . $filenameee . "\"" . $eol;
                    $body .= $content . $eol;
                    $body .= "--" . $separator . "--";
                    
                    // Send the email
                    if (mail($to, $subject, $body, $headers)) {
                      $data = array("status"=>"200","message" => "Data saved successfully!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                    $upEmail=$this->connect->prepare("UPDATE tbl_admittedPRG SET provisional_email='yes' WHERE Aprg_id='".$adm."'");
                    $upEmail->execute();
                    } 
                    else{
                        $data = array("status"=>"500","message" => "Failed to send email!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;   
                    }
                  
        }
    }
 }

$admission=new Admission();
$action = $_POST['action'];
switch($action){
    case 'admin_with_file':
        $admission->Admit();
        break;
   
}

?>