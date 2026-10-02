<?php
    include ('../../meet/con.php');
    $connection = $conn;
    class Request{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect = $connection;
    	}
    	
        function generatefile() {
            $characters = '0123456789';
            $code = '';
        
            for ($i = 0; $i < 16; $i++) {
                $randomIndex = mt_rand(0, strlen($characters) - 1);
                $code .= $characters[$randomIndex];
            }
        
            return $code;
        }
        
        function sendEmail($from, $to, $fname){
            $year = date("Y");
            $subject = "Update";      
            
            $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
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
                           <p>An update has been posted to your request page. You may access your request page here: https://'.$_SERVER['SERVER_NAME'].'</p>
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
            $body .= '</body></html>';
    
    
            mail($to, $subject, $body, $headers);
            return;
        }
        
        function notify($id){
            $stmt0 = $this->connect->prepare("SELECT stu FROM tbl_requests WHERE id = ?");
            $stmt0->execute([$id]);
            $stu = $stmt0->fetch()['stu'];
            
            $stmt = $this->connect->prepare("SELECT fname, email FROM tbl_admission WHERE reg_no = ?");
            $stmt->execute([$stu]);
            $data = $stmt->fetch();
            $fname = $data['fname'];
            $email = $data['email'];
            
            $this->sendEmail("stumis", $email, $fname);
            
        }
    	
    	function upload_file($uploadedFile){
            $uploadDirectory = "./fadmin/";
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
            $fileName = $this->generatefile() . '.' . $fileExtension;
            
            $destination = $uploadDirectory . $fileName;
            move_uploaded_file($uploadedFile['tmp_name'], $destination);
            return "https://".$_SERVER['SERVER_NAME']."/files/Requests/fadmin/".$fileName;
    	}
    	
        function send_request(){
            $stu = $_POST['stu'];
        	$dedicatedTo = $_POST['dedicatedTo'];
        	$title = $_POST['title'];
        	$request = $_POST['request'];
        	
            $stmt = $this->connect->prepare("INSERT INTO tbl_requests(stu, dedicatedTo, title, request)VALUES(?, ?, ?, ?)");
            $stmt->execute([$stu, $dedicatedTo, $title, $request]);

            $data = array("status" => 200, "message" => "Your request have been received, we will reach out to you soon!");
            $jsonData = json_encode($data);
            echo $jsonData; 
    	}
    	
    	function respond(){
            $id = $_POST['id'];
        	$response = $_POST['response'];

            if(isset($_FILES['response_file'])){
                $response_file = $this->upload_file($_FILES['response_file']);
            }else{
                $response_file = `NULL`;
            }
            
            $stmt = $this->connect->prepare("UPDATE tbl_requests SET response = ?, response_file = ? WHERE id = ?");
    	    if($stmt->execute([$response, $response_file, $id])){
    	        $this->notify($id);
                $data = array("status" => 200, "message" => "All done, sender was notified!");
                $jsonData = json_encode($data);
                echo $jsonData;   
    	    } else{
                $data = array("status" => 400, "message" => "Something wwent wrong, retry!");
                $jsonData = json_encode($data);
                echo $jsonData;   
    	    }
    	}
    }	
    
	$request = new Request();
    $action = $_POST['action'];
	switch($action){
	    case 'send_request':
	        $request->send_request();
	        break;
	        
	    case 'respond':
	        $request->respond();
	        break;
	}
?>