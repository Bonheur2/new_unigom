<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action == "register_new_employee") {
    
    $conn->beginTransaction();
    
    try {
        
        $required = ['lname', 'fname', 'nid', 'gender', 'mstatus', 'st_dob', 'nationality', 
                    'father_names', 'mother_names', 'country', 
                    'bank_name', 'acc_number', 'salary', 'camps_id', 'accademic', 'part_full', 'start_date'];
        
        foreach($required as $field) {
            if(empty($_POST[$field])) {
                throw new Exception("Required field $field is missing");
            }
        }

        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $nid = $_POST['nid'];
        $Qualifications = $_POST['Qualifications'];
        $DepartmentUnt = $_POST['DepartmentUnt'];
        $ScaleofSalary = $_POST['ScaleofSalary'];
        $School = $_POST['School'];
        $Departmentin = $_POST['Departmentin'];
        
        
        $gender = $_POST['gender'];
        $mstatus = $_POST['mstatus'];
        $st_dob = $_POST['st_dob'];
        $nationality = $_POST['nationality'];
        $father_names = $_POST['father_names'];
        $mother_names = $_POST['mother_names'];
        $country = $_POST['country'];
        $province_id = $_POST['province_id'] ?? null;
        $district_id = $_POST['district_id'] ?? null;
        $sector = $_POST['sector'] ?? null;
        $cell_id = $_POST['cell_id'] ?? null;
        $village_id = $_POST['village_id'] ?? null;
        $phone = $_POST['phone'];
        $PSupervisor_id = $_POST['PSupervisor_id'];
        $email = $_POST['email'];
        $dep_id = $_POST['dep_id'];
        $post_id = $_POST['post_id'];
        $bank_name = $_POST['bank_name'];
        $acc_number = $_POST['acc_number'];
        $salary = $_POST['salary'];
        $campus = $_POST['camps_id'];
        $accademic = $_POST['accademic'];
        $probation = $_POST['probiton'];
        $part_full = $_POST['part_full'];
        $midleName = $_POST['midleName'];
        $nassit_number = $_POST['nassit_number'];
        $probation_years = $_POST['probation_years'];
        $start_date = $_POST['start_date'];
        $rssb = $_POST['rssb'] ?? null;
        $acc_grade = $_POST['ac_grade_id'] ?? null;
        $role_id = $_POST['role_id'] ?? null;
        $fSupervisor_id = $_POST['fSupervisor_id'] ?? null;
        

        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_info WHERE nid = ?");
        $stmt->execute([$nid, $email, $phone]);
        $count = $stmt->fetchColumn();
        
        if($count > 0) {
            throw new Exception("Employee with same NID, email or phone already exists");
        }



foreach ($_POST['filedType'] as $selected_position) {
    $onCardPost = $selected_position;
    break;
}

        // $stmt = $conn->prepare("SELECT short_name FROM tbl_university");
        // $stmt->execute();
        // $shortname = $stmt->fetchColumn();
        
        // $stmt = $conn->prepare("SELECT MAX(staff_id) AS last_id FROM tbl_staff_info");
        // $stmt->execute();
        // $lastId = $stmt->fetchColumn();
        
        // $nextNumber = $lastId ? (int) substr($lastId, strlen($shortname)) + 1 : 1;
        
        // function generateUniqueCode() {
        //     $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        //     $code = '';
        //     for ($i = 0; $i < 5; $i++) {
        //         $code .= $characters[random_int(0, strlen($characters) - 1)];
        //     }
        //     return $code;
        // }

        // function generateGuaranteedUniqueCode(PDO $conn) {
        //     do {
        //         $code = generateUniqueCode();
        //         $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_info WHERE staff_id LIKE ?");
        //         $stmt->execute(['%' . $code . '%']);
        //         $exists = $stmt->fetchColumn();
        //     } while ($exists > 0);
            
        //     return $code;
        // }
        
        // $finalCode = generateGuaranteedUniqueCode($conn);



        // $lastTwoDigits = date('y');
        
        
        // Get the university short name (e.g., 'NU')
$stmt = $conn->prepare("SELECT short_name FROM tbl_university");
$stmt->execute();
$shortname = $stmt->fetchColumn(); // 'NU'

// Get the last staff_id
$stmt = $conn->prepare("SELECT MAX(staff_id) AS last_id FROM tbl_staff_info WHERE staff_id LIKE ?");
$stmt->execute([$shortname . '%']);
$lastId = $stmt->fetchColumn();

// Extract the number part from the last ID and increment it
$nextNumber = $lastId ? (int) substr($lastId, strlen($shortname)) + 1 : 1;

// Pad the number with leading zeros to make it 5 digits
$paddedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

// Combine shortname and padded number
$newStaffId = $shortname . $paddedNumber;


        // $newStaffId = $shortname . $lastTwoDigits . $finalCode;

  
            $img = $_FILES['upload_image'];
            $file_extension = pathinfo($img['name'], PATHINFO_EXTENSION);
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            $filename = $_FILES['upload_image']['name'];
            
            if(in_array(strtolower($file_extension), $valid_extensions)) {
                $timestamp = time();
                $image_name = $timestamp . '_' . $newStaffId . '.' . $file_extension;
                // $upload_path = '/images/' . $image_name;
                // $upload_path = '/home/stumis/public_html/eacc.stumis.rw/staff_docs/' . $image_name;
                $upload_path = '/home/misnjala/public_html/staff_docs/' . $image_name;
                
                
                
                if(!move_uploaded_file($img['tmp_name'], $upload_path)) {
                    error_log("Yes");
                }
                else {
                    error_log("Nooo");
                }
            }
   
// $Qualifications = $_POST['Qualifications'];
//         $DepartmentUnt = $_POST['DepartmentUnt'];

$stmt = $conn->prepare("INSERT INTO tbl_staff_info 
    (staff_id, family_name, first_name, gender, dob, marital_status, nationality, nid, phone, 
     email, staff_image, country, province, district, sector, cell, village, mother_name, 
     father_name, bank, acc_no, Nassit_Number, campus, supervisor, Post, probation_years,
     middleName, staffCategory, contract, qualifications, deptunt, school, department)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
// $image_name
// $filename

if (isset($image_name) && !empty($image_name)) {
    $image_name = '/staff_docs/' . $image_name;
} else {
    $image_name = "";
}
$result = $stmt->execute([
    $newStaffId, $lname, $fname, $gender, $st_dob, $mstatus, $nationality, $nid, $phone,
    $email, $image_name, $country, $province_id, $district_id, $sector, $cell_id, $village_id,
    $mother_names, $father_names, $bank_name, $acc_number, $nassit_number, $campus,
    $PSupervisor_id, $onCardPost, $probation_years, $midleName, $accademic, $part_full, $Qualifications, $DepartmentUnt, $School, $Departmentin
]);

if (!$result) {
    $errorInfo = $stmt->errorInfo();
    error_log("INSERT INTO tbl_staff_info failed: " . print_r($errorInfo, true));
    throw new Exception("Database error: " . $errorInfo[2]);
}

// $stmt = $conn->prepare("INSERT INTO tbl_supervision 
//     (supervisorCode, staffCode)
//     VALUES (?, ?)");
// $result2 = $stmt->execute([
//     $PSupervisor_id, $newStaffId
// ]);

// if (!$result2) {
//     $errorInfo = $stmt->errorInfo();
//     error_log("INSERT INTO tbl_supervision failed: " . print_r($errorInfo, true));
//     throw new Exception("Database error: " . $errorInfo[2]);
// }

$rowCount = $stmt->rowCount();
error_log("Rows affected in tbl_staff_info: " . $rowCount);
        
        


        if (!empty($_POST['filedType'])) {
            foreach ($_POST['filedType'] as $selected_position) {
                $stmt = $conn->prepare("INSERT INTO tbl_staff_post 
                    (staff_id, is_acadmic, acad_grad_id, department, post, basic_salary, contract, 
                     campus, probation_period, join_date, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                
                $resultstaff_post = $stmt->execute([
                    $newStaffId, $accademic, $acc_grade, $dep_id, $selected_position, $salary, $part_full,
                    $campus, $probation, $start_date
                ]);
                
                if (!$resultstaff_post) {
                    $errorInfo = $stmt->errorInfo();
                    error_log("INSERT INTO tbl_staff_post failed: " . print_r($errorInfo, true));
                    throw new Exception("Database error: " . $errorInfo[2]);
                }
            }
        }
        
        
        
        
        
        function generateSimplePassword($length = 8) {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $password .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $password;
        }
        
        $randomPassword = generateSimplePassword(8);
        $hashedPassword = password_hash($randomPassword, PASSWORD_BCRYPT);
        
        // echo "Generated Password: " . $randomPassword . "<br>";
        // echo "Hashed Password: " . $hashedPassword;


        
        $stmt = $conn->prepare("INSERT INTO tbl_users 
            (Identification, family_name, first_name, email, phone_no, password, role_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $newStaffId, $lname, $fname, $email, $phone, $hashedPassword, $role_id
        ]);

        
        $stmt = $conn->prepare("INSERT INTO tbl_staff_salary 
            (staff_Id, gross_salary, starting_date, ScaleofSalary, status)
            VALUES (?, ?, ?, ?, 1)");
        
        $stmt->execute([$newStaffId, $salary, $start_date, $ScaleofSalary]);

        
         $conn->commit();
    error_log("Transaction committed successfully for staff: $newStaffId");
    


    $to = $email;
    $subject = "Your Staff Account Credentials";
    $message = "
    <html>
    <head>
        <title>Your Staff Account Credentials</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #f8f9fa; padding: 10px; text-align: center; }
            .content { padding: 20px; }
            .credentials { background-color: #f1f1f1; padding: 15px; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Welcome to Our Institution</h2>
            </div>
            <div class='content'>
                <p>Dear $fname $lname,</p>
                <p>Your staff account has been successfully created. Below are your login credentials:</p>
                
                <div class='credentials'>
                    <p><strong>Staff ID:</strong> $newStaffId</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Password:</strong> $randomPassword</p>
                </div>
                
                <p>If you didn't request this account, please contact HR immediately.</p>
                <p>Best regards,<br>Administration Team</p>
            </div>
        </div>
    </body>
    </html>
    ";


    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: NJALA Univeristy <humanresources@misnjala.edu.sl>" . "\r\n";
    $headers .= "Reply-To: humanresources@misnjala.edu.sl" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();


    $mailSent = mail($to, $subject, $message, $headers);
    
    if (!$mailSent) {
        error_log("Failed to send email to $email");
    } else {
        error_log("Credentials email sent successfully to $email");
    }



$data = ["status" => 200, "message" => "Employee registered successfully!"];


} catch (Exception $e) {
    $conn->rollBack();
    error_log("Error in employee registration: " . $e->getMessage());
    $data = ["status" => 500, "message" => "Error: " . $e->getMessage()];
}
    
    header('Content-Type: application/json');
    echo json_encode($data);
}


else if($action=="view_employee"){
    $emp_id=$_POST['emp_id'];
    $stmt6=$conn->prepare("SELECT tbl_staff_info.*,tbl_nationality.nationality,tbl_country.cntr_name,provinces.provincename,
    districts.namedistrict,sectors.namesector,cells.nameCell,villages.VillageName,tbl_campus.camp_full_name,
    tbl_staff_type.staff_type_full_name,
    tbl_contract_type.contr_name,
    tbl_staff_salary.gross_salary,
    tbl_staff_salary.ScaleofSalary,
    tbl_faculty.fac_full_name,
    tbl_department.dept_full_name
    
    FROM tbl_staff_info
    LEFT JOIN tbl_nationality ON tbl_staff_info.nationality=tbl_nationality.nat_id
    LEFT JOIN tbl_country ON tbl_staff_info.country=tbl_country.cntr_id
    LEFT JOIN provinces ON tbl_staff_info.province=provinces.provincecode
    LEFT JOIN districts ON tbl_staff_info.district=districts.districtcode 
    LEFT JOIN sectors ON tbl_staff_info.sector=sectors.sectorcode
    LEFT JOIN cells ON tbl_staff_info.cell=cells.codecell
    LEFT JOIN villages ON tbl_staff_info.village=villages.CodeVillage  
    LEFT JOIN tbl_campus ON tbl_staff_info.campus=tbl_campus.camp_id
    LEFT JOIN tbl_staff_type ON tbl_staff_info.staffCategory = tbl_staff_type.staff_type_id
    LEFT JOIN tbl_contract_type ON tbl_staff_info.contract = tbl_contract_type.contr_type_id
    LEFT JOIN tbl_staff_salary ON tbl_staff_info.staff_id = tbl_staff_salary.staff_Id
    LEFT JOIN tbl_faculty ON tbl_faculty.fac_id = tbl_staff_info.school
    LEFT JOIN tbl_department ON tbl_department.dept_id = tbl_staff_info.department
    WHERE tbl_staff_info.id='".$emp_id."'");
    $stmt6->execute();
    $row1=$stmt6->fetch();
    
    $staff_code = $row1['staff_id'];

    $stmt7=$conn->prepare("SELECT tbl_staff_post.*,tbl_department.dept_full_name,tbl_contract_type.contr_name,
    staff_post.staff_post_full_name,tbl_staff_type.staff_type_full_name,tbl_acad_grade.acad_grad_full_name
    FROM tbl_staff_post
    LEFT JOIN tbl_department ON tbl_staff_post.department=tbl_department.dept_id
    LEFT JOIN tbl_contract_type ON tbl_staff_post.contract=tbl_contract_type.contr_type_id
    LEFT JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id
    LEFT JOIN tbl_staff_type ON tbl_staff_post.is_acadmic=tbl_staff_type.staff_type_id
    LEFT JOIN tbl_acad_grade ON tbl_staff_post.acad_grad_id=tbl_acad_grade.acad_grad_id
    WHERE tbl_staff_post.staff_id='".$staff_code."'");
    $stmt7->execute();
    $row2=$stmt7->fetch();
    
    $rows=array();
    array_push($rows,$row1,$row2);
    echo json_encode($rows);
}

 else if($action=="edit_employee"){
     $emp_id = $_POST['emp_id'];
        $stmt7 = $conn->prepare("SELECT * FROM tbl_staff_info WHERE id='".$emp_id."'");
        $stmt7->execute();
        $row1 = $stmt7->fetch();
         $stmt8 = $conn->prepare("SELECT * FROM  tbl_staff_post WHERE id='".$emp_id."'");
        $stmt8->execute();
        $row2 = $stmt8->fetch();// Fetch all rows using fetchAll()
        $rows = array();
        array_push($rows,$row1,$row2);
        echo json_encode($rows);
 }
 else if($action=="load_provinces"){
     $c_code=$_POST['c_id'];
     $stmt17=$conn->prepare("SELECT * FROM provinces");
     $stmt17->execute();
     $provinces=$stmt17->fetchAll();
     echo json_encode($provinces);
 }
 else if($action=="load_districts"){
     $provcode=$_POST['p_id'];
     $stmt17=$conn->prepare("SELECT * FROM districts WHERE provincecode='".$provcode."'");
     $stmt17->execute();
     $rows=$stmt17->fetchAll();
     echo json_encode($rows);
 }
 else if($action=="load_sectors"){
     $distcode=$_POST['d_id'];
     $stmt18=$conn->prepare("SELECT * FROM sectors WHERE districtcode='".$distcode."'");
     $stmt18->execute();
     $rows=$stmt18->fetchAll();
     echo json_encode($rows);
  }
  else if($action=="load_cells"){
      $cellcode=$_POST['s_id'];
      $stmt19=$conn->prepare("SELECT * FROM cells WHERE sectorcode='".$cellcode."'");
     $stmt19->execute();
     $rows=$stmt19->fetchAll();
     echo json_encode($rows);
  }
  else if($action=="load_villages"){
    $villcode=$_POST['cell_id_code'];
      $stmt20=$conn->prepare("SELECT * FROM villages WHERE codecell='".$villcode."'");
     $stmt20->execute();
     $rows=$stmt20->fetchAll();
     echo json_encode($rows);  
  }
  else if($action=="confirm_edit"){
      $img = $_FILES['upload_image_edit'];
     $image_name = $img['name'];
      $staff_id=$_POST['staff_id'];
      $lname_edit=$_POST['lname_edit'];
      $fname_edit=$_POST['fname_edit'];
      $idn_edit=$_POST['idn_edit'];
      $gender_edit=$_POST['gender_edit'];
      $martial_edit=$_POST['martial_edit'];
      $dob_edit=$_POST['dob_edit'];
      $nationality_edit=$_POST['nationality_edit'];
      $father_edit=$_POST['father_edit'];
      $mother_edit=$_POST['mother_edit'];
      $rssb_edit=$_POST['rssb_edit'];
      $residence_edit=$_POST['residence_edit'];
      $province_edit=$_POST['province_edit'];
      $district_edit=$_POST['district_edit'];
      $sector_edit=$_POST['sector_edit'];
      $cell_edit=$_POST['cell_edit'];
      $village_edit=$_POST['village_edit'];
      $phone_edit=$_POST['phone_edit'];
      $email_edit=$_POST['email_edit'];
      $campus=$_POST['campus_edit'];
      $department_edit=$_POST['department_edit'];
      $academic=$_POST['accademic_edit'];
      $acc_grade=$_POST['acc_grade_edit'];
      $probition=$_POST['probition_edit'];
      $partfull=$_POST['part_full_edit'];
      $start_date=$_POST['start_date_edit'];
      $post_edit=$_POST['post_edit'];
      $bank_edit=$_POST['bank_edit'];
      $account_edit=$_POST['account_edit'];
      $salary_edit=$_POST['salary_edit'];
      $getdata=$conn->prepare("SELECT staff_image,staff_id FROM tbl_staff_info WHERE id='".$staff_id."'");
      $getdata->execute();
      $getdata2=$getdata->fetch();
      $deleteImg=$getdata2['image'];
      $staff_id2=$getdata2['staff_id'];
      if (!empty($image_name)) {
     $filenamedefaultExtension = pathinfo($image_name, PATHINFO_FILENAME);
      $file_extension = 'png';
      $timestamp = time();
      $image_path="images/".$deleteImg;
      unlink($image_path);
      $newImageName = $timestamp . $staff_id2 . $filenamedefaultExtension . '.' . $file_extension;
          $directory = 'images/';
    // Get the list of files in the directory
        $files = scandir($directory);
        
        // Remove "." and ".." from the file list
        $files = array_diff($files, array('.', '..'));
        
        // Find the file with the matching name
        $matchingFileName = null;
        foreach ($files as $file) {
            if ($file === $image_name) {
                $matchingFileName = $file;
                break;
            }
        }
    $oldFilePath = $directory . $matchingFileName;
    $newFilePath = $directory . $newImageName;
     rename($oldFilePath, $newFilePath);
      $stmt21=$conn->prepare("UPDATE  tbl_staff_info SET family_name='".$lname_edit."',first_name='".$fname_edit."',gender='".$gender_edit."',
      	dob='".$dob_edit."',marital_status='".$martial_edit."',nationality='".$nationality_edit."',nid='".$idn_edit."',phone='".$phone_edit."',
      	email='".$email_edit."',image='".$newImageName."',country='".$residence_edit."',province='".$province_edit."',district='".$district_edit."',
        sector='".$sector_edit."',cell='".$cell_edit."',village='".$village_edit."',mother_name='".$mother_edit."',
        father_name='".$father_edit."',bank='".$bank_edit."',acc_no='".$account_edit."',rssb='".$rssb_edit."',campus='".$campus."' , Post='".$post_edit."'  WHERE id='".$staff_id."'");
      	
      	$stmt23=$conn->prepare("UPDATE  tbl_staff_post SET is_acadmic='".$academic."',acad_grad_id='".$acc_grade."',department='".$department_edit."',post='".$post_edit."',basic_salary='".$salary_edit."',
      rssb='".$rssb_edit."',contract='".$partfull."',campus='".$campus."',probation_period='".$probition."',join_date='".$start_date."' WHERE id='".$staff_id."'");
      
     $stt23=$conn->prepare("UPDATE  tbl_staff_salary SET gross_salary='".$salary_edit."' WHERE staff_Id='".$staff_id2."'");     
      }
      else{
       $stmt21=$conn->prepare("UPDATE  tbl_staff_info SET family_name='".$lname_edit."',first_name='".$fname_edit."',gender='".$gender_edit."',
      	dob='".$dob_edit."',marital_status='".$martial_edit."',nationality='".$nationality_edit."',nid='".$idn_edit."',phone='".$phone_edit."',
      	email='".$email_edit."',country='".$residence_edit."',province='".$province_edit."',district='".$district_edit."',
        sector='".$sector_edit."',cell='".$cell_edit."',village='".$village_edit."',mother_name='".$mother_edit."',
        father_name='".$father_edit."',bank='".$bank_edit."',acc_no='".$account_edit."',rssb='".$rssb_edit."',campus='".$campus."' , Post='".$post_edit."'  WHERE id='".$staff_id."'");
      	
      	$stmt23=$conn->prepare("UPDATE  tbl_staff_post SET is_acadmic='".$academic."',acad_grad_id='".$acc_grade."',department='".$department_edit."',post='".$post_edit."',basic_salary='".$salary_edit."',
      rssb='".$rssb_edit."',contract='".$partfull."',campus='".$campus."',probation_period='".$probition."',join_date='".$start_date."', post='".$post_edit."' WHERE id='".$staff_id."'");  
      
      $stt23=$conn->prepare("UPDATE  tbl_staff_salary SET gross_salary='".$salary_edit."' WHERE staff_Id='".$staff_id2."'");
      }
      if($stmt21->execute() && $stmt23->execute() && $stt23->execute()){
      	 $data = array("status"=>"200","message" => "Updated Successfully!!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
    echo $jsonData;     
      	}
      	else{
      	    $data = array("status"=>"500","message" => "Something Went Wrong!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
    echo $jsonData;  
      	}
  }
  else if ($action == "delete_employee") {
    $staff_id = $_POST['staff_id'];
    $stmt22 = $conn->prepare("UPDATE tbl_staff_post SET status=2  WHERE id ='".$staff_id."'");
    if ($stmt22->execute()) {
        $data = array("status" => "200", "message" => "Successfully deleted!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        $data = array("status" => "500", "message" => "Something Went Wrong!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }
}

else if ($action == "get_employee_data") {
    $id = $_POST['emp_id'];
    
    try {
        // Get main staff info - ensure all needed fields are selected
        $stmt = $conn->prepare("SELECT 
            tbl_staff_info.staff_image, tbl_staff_info.qualifications, tbl_staff_info.deptunt, tbl_staff_info.staff_id, tbl_staff_info.first_name, 
            tbl_staff_info.family_name, tbl_staff_info.gender, tbl_staff_info.dob, 
            tbl_staff_info.marital_status, tbl_staff_info.nationality, tbl_staff_info.nid, tbl_staff_info.phone, tbl_staff_info.email, 
            tbl_staff_info.rssb, tbl_staff_info.bank, tbl_staff_info.acc_no, tbl_staff_info.country, tbl_staff_info.province, tbl_staff_info.district, 
            tbl_staff_info.sector, tbl_staff_info.cell, tbl_staff_info.village, 
            tbl_staff_info.mother_name, tbl_staff_info.father_name, tbl_staff_info.campus, staff_post.staff_post_full_name,
            tbl_staff_salary.gross_salary, tbl_staff_salary.ScaleofSalary, 
            tbl_staff_info.staffCategory, tbl_staff_info.probation_years, tbl_staff_info.contract, tbl_staff_info.Nassit_Number, tbl_staff_info.school,
            tbl_staff_info.school AS schoolld,
            tbl_staff_info.department AS departmentLD
            FROM tbl_staff_info 
            LEFT JOIN staff_post ON staff_post.staff_post_id = tbl_staff_info.supervisor
            LEFT JOIN tbl_staff_salary ON tbl_staff_salary.staff_Id = tbl_staff_info.staff_id
            
            WHERE tbl_staff_info.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($employee) {
            // Get salary info
            $stmt = $conn->prepare("SELECT * FROM tbl_staff_salary WHERE staff_Id = :staff_id");
            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $salary = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt = $conn->prepare("SELECT tbl_staff_post.post FROM tbl_staff_post 
                        INNER JOIN tbl_staff_info ON tbl_staff_info.staff_id = tbl_staff_post.staff_id 
                        WHERE tbl_staff_post.staff_id = :staff_id");

            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $selectedPositions = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get user info
            $stmt = $conn->prepare("SELECT tbl_users.*, tbl_user_roles.role FROM tbl_users 
            LEFT JOIN tbl_user_roles ON tbl_user_roles.role_id = tbl_users.role_id 
            WHERE tbl_users.Identification = :staff_id");
            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $data = array(
                "status" => "200", 
                "employee" => $employee,
                "salary" => $salary,
                "post" => $selectedPositions,
                "user" => $user
            );
        } else {
            $data = array("status" => "404", "message" => "Employee not found");
        }
    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}



else if ($action == "edit_employee_card") {
    $id = $_POST['emp_id'];
    $staff_id = $_POST['staff_id'];
    // $post = isset($_POST['post']) ? implode(',', $_POST['post']) : '';
    $post = $_POST['post'];
    $family_name = $_POST['family_name'];
    $first_name = $_POST['first_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $Schoolfld = $_POST['Schoolfld'];
    $marital_status = $_POST['marital_status'];
    $nationality = $_POST['nationality'];
    $nid = $_POST['nidId'] ?? null;
    $Nassit_Number = $_POST['Nassit_Number'] ?? null;
    $editQualifications = $_POST['editQualifications'] ?? null;
    $deptuntedit = $_POST['deptuntedit'] ?? null;

    $phone = $_POST['phoneeee'] ?? null;
    $email = $_POST['emaillll'] ?? null;
    $country = $_POST['countryy'];
    $province = ($country == 160) ? ($_POST['province'] ?? null) : null;
    $district = ($country == 160) ? ($_POST['Mydistrict'] ?? null) : null;
    $sector = ($country == 160) ? ($_POST['Mysector'] ?? null) : null;
    $cell = ($country == 160) ? ($_POST['Mycell'] ?? null) : null;
    $village = ($country == 160) ? ($_POST['Myvillage'] ?? null) : null;
    $mother_name = $_POST['mother_name'] ?? null;
    $father_name = $_POST['father_name'] ?? null;
    $bank = $_POST['bank'] ?? null;
    $acc_no = $_POST['acc_no'] ?? null;
    $rssb = $_POST['rssbms'] ?? null;
    $campus = $_POST['campus'];
    $supervisor = $_POST['fSupervisor_id'];
    $contract = $_POST['contract'] ?? null;
    $basic_salary = $_POST['basic_salary'] ?? null;
    $gross_salary = $_POST['gross_salary'] ?? null;
    $editScaleofSalary = $_POST['editScaleofSalary'] ?? null;
    
    $rolesid = $_POST['rolesid'];
    $Departmentup = $_POST['Departmentup'];
    
    $staff_category = $_POST['StaffCategory'] ?? null;
    $probation_years = $_POST['probation_years'] ?? null;

    try {
        $conn->beginTransaction();
        
        $uploadDir = '/home/misnjala/public_html/staff_docs/';
        $photoName = null;
        
        if (!empty($_FILES['staff_image']['name'])) {
            $fileExt = strtolower(pathinfo($_FILES['staff_image']['name'], PATHINFO_EXTENSION));
            $photoName = $staff_id . '_' . time() . '.' . $fileExt;
            $targetPath = $uploadDir . $photoName;
            
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExt, $allowedTypes)) {
                throw new Exception("Invalid file type");
            }
            
            if (!move_uploaded_file($_FILES['staff_image']['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload image");
            }
            
            // Delete old photo
            $stmt = $conn->prepare("SELECT staff_image FROM tbl_staff_info WHERE id = ?");
            $stmt->execute([$id]);
            if ($oldPhoto = $stmt->fetchColumn()) {
                $oldPhotoPath = $uploadDir . basename($oldPhoto);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        }
        
        // Main staff info update
        
        foreach ($post as $selected_position) {
            $onCradPost = $selected_position;
            break;
        }
        
        $sql = "UPDATE tbl_staff_info SET 
            staff_image = COALESCE(?, staff_image), 
            
            family_name = ?, 
            first_name = ?, 
            gender = ?, 
            dob = ?, 
            marital_status = ?, 
            nationality = ?, 
            nid = ?, 
            phone = ?, 
            email = ?, 
            country = ?, 
            province = ?, 
            district = ?, 
            sector = ?, 
            cell = ?, 
            village = ?, 
            mother_name = ?, 
            father_name = ?, 
            bank = ?, 
            acc_no = ?,
            Nassit_Number = ?,
            campus = ?, 
            supervisor = ?,
            staffCategory = ?,
            probation_years = ?,
            contract = ?,
            qualifications = ?,
            deptunt = ?,
            school = ?,
            department = ?,
            Post = ?
            WHERE id = ?";
        
        $params = [
            $photoName ? '/staff_docs/' . $photoName : null,
            $family_name, $first_name, $gender, $dob, $marital_status, 
            $nationality, $nid, $phone, $email, $country, $province, 
            $district, $sector, $cell, $village, $mother_name, $father_name, 
            $bank, $acc_no, $Nassit_Number, $campus, $supervisor, $staff_category, 
            $probation_years, $contract, $editQualifications, $deptuntedit, $Schoolfld, $Departmentup, $onCradPost,
            $id
        ];

        $stmt = $conn->prepare($sql);
        if (!$stmt->execute($params)) {
            // throw new Exception("Staff info update failed");
            
            $errorInfo = $stmt->errorInfo();
        throw new Exception("Staff info update failed. SQL Error: " . $errorInfo[2] . 
                           " [Code: " . $errorInfo[1] . "]");
        }
        
        // Salary update
        
        // if (!empty($gross_salary)) {
            $sql = "INSERT INTO tbl_staff_salary (staff_Id, gross_salary, ScaleofSalary, starting_date)
        VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        gross_salary = VALUES(gross_salary), 
        ScaleofSalary = VALUES(ScaleofSalary), 
        starting_date = VALUES(starting_date)";

$stmt = $conn->prepare($sql);
$stmt->execute([$staff_id, $gross_salary, $editScaleofSalary, date('Y-m-d')]);
        // }

        // Staff post update
        
        if (!empty($post)) {
            
            $deleteStmt = $conn->prepare("DELETE FROM tbl_staff_post WHERE staff_id = ?");
            $deleteStmt->execute([$staff_id]);

            $insertStmt = $conn->prepare("INSERT INTO tbl_staff_post (staff_id, post) VALUES (?, ?)");
            foreach ($post as $selected_position) {
                $insertStmt->execute([$staff_id, $selected_position]);
            }

        }
        
        // $sql = "INSERT INTO tbl_staff_post (staff_id, post, contract, basic_salary, rssb, campus) 
        //         VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE 
        //         post = VALUES(post), contract = VALUES(contract), 
        //         basic_salary = VALUES(basic_salary), rssb = VALUES(rssb), campus = VALUES(campus)";
        
        
        
        // $stmt = $conn->prepare($sql);
        // $stmt->execute([$staff_id, $post, $contract, $basic_salary, $rssb, $campus]);

        // User account update
        if (!empty($email)) {
            $sql = "UPDATE tbl_users SET 
                    family_name = ?, first_name = ?, email = ?, phone_no = ?, role_id = ?
                    WHERE Identification = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$family_name, $first_name, $email, $phone, $rolesid, $staff_id]);
        }
        
        $conn->commit();
        echo json_encode(["status" => 200, "message" => "Employee updated successfully!"]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(["status" => 500, "message" => "Error: " . $e->getMessage()]);
    }
}




else if ($action == "get_districts") {
    $provincecode = $_POST['provincecode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM districts WHERE provincecode = :provincecode ORDER BY namedistrict");
        $stmt->bindParam(':provincecode', $provincecode);
        $stmt->execute();
        
        $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($districts);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get sectors by district
else if ($action == "get_sectors") {
    $districtcode = $_POST['districtcode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM sectors WHERE districtcode = :districtcode ORDER BY namesector");
        $stmt->bindParam(':districtcode', $districtcode);
        $stmt->execute();
        
        $sectors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($sectors);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get cells by sector
else if ($action == "get_cells") {
    $sectorcode = $_POST['sectorcode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM cells WHERE sectorcode = :sectorcode ORDER BY nameCell");
        $stmt->bindParam(':sectorcode', $sectorcode);
        $stmt->execute();
        
        $cells = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cells);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get villages by cell
else if ($action == "get_villages") {
    $codecell = $_POST['codecell'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM villages WHERE codecell = :codecell ORDER BY VillageName");
        $stmt->bindParam(':codecell', $codecell);
        $stmt->execute();
        
        $villages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($villages);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

else if($action=="upload_image"){
if (isset($_POST['image'])) {
$data = $_POST['image'];
$file_name=$_POST['nameoffile'];
$image_array_1 = explode(";", $data);
$image_array_2 = explode(",", $image_array_1[1]);
$data = base64_decode($image_array_2[1]);

// Specify the directory path where you want to store the image
$directory = 'images/';
$file_path = $directory . $file_name;

// Write the decoded image data to the file
file_put_contents($file_path, $data);

// Construct the image URL
$image_url =$file_path;

echo json_encode($image_url);
     
}
}
 else if($action=="upload_image_edit"){
if (isset($_POST['image'])) {
$data = $_POST['image'];
$file_name=$_POST['nameoffile'];
$image_array_1 = explode(";", $data);
$image_array_2 = explode(",", $image_array_1[1]);
$data = base64_decode($image_array_2[1]);

$directory = 'images/';
$file_path = $directory . $file_name;
file_put_contents($file_path, $data);
$image_url =$file_path;

echo json_encode($image_url);
     
}
}


?>