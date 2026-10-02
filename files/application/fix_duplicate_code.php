<?php
include ('../../meet/con.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['applicant_id'])) {
    $applicant_id = intval($_POST['applicant_id']);
    
    try {
        $conn->beginTransaction();
        
        // Get the applicant record
        $stmt = $conn->prepare("SELECT * FROM tbl_applicants WHERE applicant_id = ?");
        $stmt->execute([$applicant_id]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception("Applicant not found.");
        }
        
        $applicant = $stmt->fetch();
        $old_code = $applicant['code'];
        $fname = $applicant['fname'];
        $lname = $applicant['lname'];
        $email = $applicant['email'];
        
        // Check if the email exists in tbl_student_login table
        $checkStudentLogin = $conn->prepare("SELECT `id`, `Identification`, `email` FROM `tbl_student_login` WHERE `email` = ?");
        $checkStudentLogin->execute([$email]);
        $studentLoginExists = $checkStudentLogin->fetch();
        
        // Generate a new unique code
        $currentYear = date('Y');
        
        function generate_password() {
            $chars = "0123456789";
            $passw = substr(str_shuffle($chars), 0, 5);
            return $passw;
        }
        
        do {
            $pass = generate_password();
            $new_code = substr($currentYear, -2)."NJ".$pass;
            
            // Check if code already exists
            $checkCodeExists = $conn->prepare("SELECT code FROM tbl_applicants WHERE code = ?");
            $checkCodeExists->execute([$new_code]);
            $codeExists = $checkCodeExists->rowCount() > 0;
        } while ($codeExists);
        
        // Update the applicant record
        $updateApplicant = $conn->prepare("UPDATE tbl_applicants SET code = ? WHERE applicant_id = ?");
        $updateApplicant->execute([$new_code, $applicant_id]);
        
        // Update related invoice record
        $updateInvoice = $conn->prepare("UPDATE tbl_invoice SET reg_no = ? WHERE reg_no = ? AND (SELECT COUNT(*) FROM tbl_applicants WHERE code = ?) > 1");
        $updateInvoice->execute([$new_code, $old_code, $old_code]);
        
        // Update any other tables that might reference this code
        // For example: tbl_language_pro
        $updateLanguagePro = $conn->prepare("UPDATE tbl_language_pro SET stu = ? WHERE stu = ? AND (SELECT applicant_id FROM tbl_applicants WHERE applicant_id = ?) = ?");
        $updateLanguagePro->execute([$new_code, $old_code, $applicant_id, $applicant_id]);
        
        // Update tbl_student_login if the email exists there
        if ($studentLoginExists) {
            $updateStudentLogin = $conn->prepare("UPDATE `tbl_student_login` SET `Identification` = ? WHERE `email` = ?");
            $updateStudentLogin->execute([$new_code, $email]);
        }
        
        // Send email notification about code change
        $year = date("Y");
        $message = '
        <div style="padding: 10px;border: 1px solid lightgray; text-align: center;width: 500px;">
        <table>
        <thead>
        <tr><th><img src="https://misnjala.edu.sl/img/logo/NJALA.png" width="20%"></th></tr>
        </thead>
        <tbody>
            <tr>
               <td>
                   <p>Dear <b>'.$fname." ".$lname.'</b></p><br>
                   <p>Your application tracking code has been updated due to system maintenance:</p>
                   <p>Old Code: <b>'.$old_code.'</b></p>
                   <p>New Code: <b style="font-size:18px;">'.$new_code.'</b></p>
                   <br>
                   <p><b>!! Please use your new code for all future communications.</b></p>
               </td>
            </tr>
            <tr>
                <td>
                    <hr>
                    &copy; '.$year.' Njala University. All rights reserved.<br>
                    Designed by ITEC Ltd<br>
                    Phone (+232) 74 001023
                </td>
            </tr>
        </tbody>
       </table> 
       </div>';

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
                "subject" => "IMPORTANT: Application Code Updated",
                "message" => $message,
                "is_html" => true
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $curlResponse = curl_exec($curl);
        curl_close($curl);
        
        // Commit the transaction
        $conn->commit();
        
        $response = [
            'status' => 'success',
            'message' => 'Code updated successfully from ' . $old_code . ' to ' . $new_code,
            'new_code' => $new_code
        ];
        
    } catch (Exception $e) {
        $conn->rollBack();
        
        $response = [
            'status' => 'error',
            'message' => 'Failed to update code: ' . $e->getMessage()
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If not a POST request or missing required parameters
$response = [
    'status' => 'error',
    'message' => 'Invalid request'
];

header('Content-Type: application/json');
echo json_encode($response);
exit;
