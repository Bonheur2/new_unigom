<?php
include ('../../meet/con.php');

$connection=$conn;
class Decision{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
   
    function Accept_admision(){
        $code=$_POST['code'];
        $stmt=$this->connect->prepare("UPDATE  tbl_admittedPRG SET student_decision='accepted' WHERE Stu_code='".$code."' ");
        $result=$stmt->execute();
        if($result){
            $data=array("status"=>200,"message"=>"You accepted the offer !");
        }
        else{
            $data=array("status"=>500,"message"=>"Sorry failed !");
        }
        echo json_encode($data);
        
    }
     function send_email($code){
      
        $stmt = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."'");
        $stmt->execute();
        if($stmt->rowCount()!=0){
            $appData=$stmt->fetch();
            
            $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
            $sql->execute();
            $data=$sql->fetch();
            
            $year = date("Y");
            $to = $appData['email'];
            
           $subject = 'Confirmation of Declined Admission Offer';
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
                <tr><th><img src="https://misnjala.edu.sl/img/logo/testnjala.png" width="80px"></th></tr>
                </thead>
                <tbody>
                    <tr>
                      <td>
                          <p>Dear <b>'.$appData['fname'].',</b></p>
                          <p>Thank you for taking the time to consider our offer of admission. We respect your decision to decline the offer, and as such, your access to the system will be discontinued.</p>
                          <p>We appreciate your interest in our institution and the effort you put into your application. Should you have any questions or need further assistance regarding this decision or future opportunities, please do not hesitate to reach out to the university support team. They are available to help you with any inquiries you may have.</p>
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
    function Reject_admision(){
        $code=$_POST['code'];
        $stmt=$this->connect->prepare("UPDATE  tbl_admittedPRG SET student_decision='rejected',sts=0 WHERE Stu_code='".$code."' ");
        $result=$stmt->execute();
        if($result){
            $data = array("status" => 200, "message" => "You have declined the offer. Please check your email for further details.");
            $this->send_email($code);
            $stmt2=$this->connect->prepare("UPDATE tbl_student_login SET status=2 WHERE Identification='".$code."'");
            $stmt2->execute();
        }
        else{
            $data=array("status"=>500,"message"=>"Sorry failed !");
        }
        echo json_encode($data);   
    }
 }
		$decision=new Decision();
	    $action = $_POST['action'];
		switch($action){
		    case 'accept':
		        $decision->Accept_admision();
		        break;
		    case 'reject':
		        $decision->Reject_admision();
		        break;    
		        
		 
		 	}

	?>

