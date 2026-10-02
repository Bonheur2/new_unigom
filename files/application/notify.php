<?php
    $code = $_GET['app'];
    $stmt = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."'");
    $stmt->execute();
    if($stmt->rowCount()!=0){
        $appData=$stmt->fetch();
        
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        
        $year = date("Y");
        $to = $appData['email'];

        $subject = 'Early Submission Encouraged for Prompt Admission Review';
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
        $message2 .= "
            <div style='padding: 10px;border: 1px solid lightgray;width: 100%;'>
            <table>
            <thead>
            <tr><th><img src='https://misnjala.edu.sl/img/logo/testnjala.png' width='80px' ></th></tr>
            </thead>
            <tbody>
                <tr>
                  <td>
                      <p>Dear <b>".$appData['fname'].",</b></p>
                      <p>We hope this message finds you in good spirits. We are excited about your interest in joining ".$data['full_name'].", and we wanted to reach out with a friendly reminder to consider submitting your admission application as early as possible.</p>
                      <p>While there is no specific deadline, submitting your application early can significantly enhance the speed at which we review and process your materials. This proactive approach not only provides you with a faster admission decision but also allows you more time to plan for the next steps in your academic journey.</p>
                      <p>We understand that everyone's schedule is unique, and we appreciate the effort you're putting into your application. If you have already submitted your materials, we extend our gratitude for your promptness. If not, we encourage you to take advantage of the opportunity to stand out with an early submission.</p>
                      <p>Should you have any questions or need assistance with any aspect of the application process, feel free to reach out to our admissions team at [info@njala.edu.sl]. We are here to support you every step of the way.</p>
                      <br>
                      <p><b>Access the system via:</b></p>
                      <p>System Access Link: <a href='https://misnjala.edu.sl/'>Click here</a></p>
                      <p>Email Verification Link (if not verified): <a href='https://misnjala.edu.sl/verify_email_v2?em=$to&app=$code'>Click here</a></p><br>
                      <p>Thank you for considering ".$data['full_name']." for your academic pursuits. We look forward to receiving your application soon.</p><br>
                      <p>Best regards,</p>
                      <p><b>Admission Team</b></p>
                  </td>
                </tr>
                   
                <tr>
                    <td style='text-align: center;'>
                        <hr>
                        &copy; ".$year." NJALA University. All rights reserved.<br>
                        Designed by ITEC Ltd<br>
                        NJALA-Sierra Leone.<br>
                        Phone (+232) 7945 3322
                    </td>
                </tr>
            </tbody>
          </table> 
          </div>";
        $message2 .= '</body></html>';

        mail($to, $subject, $message2, $headers);
        // echo '<script>window.location.href = "https://misnjala.edu.sl/admission/edu?mis=pver";</script>';
        // exit;
    }
?>

<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Application Notification</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="?mis=1">Dashboard</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="col-12 mb-4">
                <div class="hero align-items-center bg-light text-dark">
                    <div class="hero-inner">
                        <h2>Applicant notified</h2>
                        <div style='padding: 10px;border: 1px solid lightgray;width: 100%;'>
                            <table>
                                <tbody>
                                    <tr>
                                      <td>
                                          <p>Dear <b><?=$appData['fname']; ?>,</b></p>
                                          <p>We hope this message finds you in good spirits. We are excited about your interest in joining <?=$data['full_name']; ?>, and we wanted to reach out with a friendly reminder to consider submitting your admission application as early as possible.</p>
                                          <p>While there is no specific deadline, submitting your application early can significantly enhance the speed at which we review and process your materials. This proactive approach not only provides you with a faster admission decision but also allows you more time to plan for the next steps in your academic journey.</p>
                                          <p>We understand that everyone's schedule is unique, and we appreciate the effort you're putting into your application. If you have already submitted your materials, we extend our gratitude for your promptness. If not, we encourage you to take advantage of the opportunity to stand out with an early submission.</p>
                                          <p>Should you have any questions or need assistance with any aspect of the application process, feel free to reach out to our admissions team at [<span style="color: blue;">info@njala.edu.sl</span>]. We are here to support you every step of the way.</p>
                                          <p>Thank you for considering <?=$data['full_name']; ?> for your academic pursuits. We look forward to receiving your application soon.</p><br>
                                          
                                          <br>
                                          <p><b>Access the system via:</b></p>
                                          <p>System Access Link: <a href="https://misnjala.edu.sl/">Click here</a></p>
                                          <p>Email Verification Link (if not verified): <a href="https://misnjala.edu.sl/verify_email_v2?em=<?=$to ?>&app=<?=$code ?>">Click here</a></p><br>
                                          <p>Thank you for considering <?=$data['full_name']; ?> for your academic pursuits. We look forward to receiving your application soon.</p><br>
                                          <p>Best regards,</p>
                                          <p><b>Admission Team</b></p>
                                      </td>
                                    </tr>
                                       
                                    <tr>
                                        <td style='text-align: center;'>
                                            <hr>
                                            &copy; <?=$year ?> NJALA University. All rights reserved.<br>
                                            Designed by ITEC Ltd<br>
                                            NJALA-Sierra Leone.<br>
                                            Phone (+232) 7945 3322
                                        </td>
                                    </tr>
                                </tbody>
                          </table> 
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
