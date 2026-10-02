<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="register_new_employee"){
    $lname=$_POST['lname'];
    $fname=$_POST['fname'];
    $nid=$_POST['nid'];
    $gender=$_POST['gender'];
    $mstatus=$_POST['mstatus'];
    $st_dob=$_POST['st_dob'];
    $nationality=$_POST['nationality'];
    $father_names=$_POST['father_names'];
    $mother_names=$_POST['mother_names'];
    $country=$_POST['country'];
    $province_id=$_POST['province_id'];
    $district_id=$_POST['district_id'];
    $sector=$_POST['sector'];
    $cell_id=$_POST['cell_id'];
    $village_id=$_POST['village_id'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $dep_id=$_POST['dep_id'];
    $post_id=$_POST['post_id'];
    $bank_name=$_POST['bank_name'];
    $acc_number=$_POST['acc_number'];
    $salary=$_POST['salary'];
    $campus=$_POST['camps_id'];
    $accademic=$_POST['accademic'];
    $probation=$_POST['probiton'];
    $part_full=$_POST['part_full'];
    $start_date=$_POST['start_date'];
    $img = $_FILES['upload_image'];
    $image_name = $img['name'];
    $filenamedefaultExtension = pathinfo($image_name, PATHINFO_FILENAME);
    $file_extension = 'png';
    $timestamp = time();
    $stmt1=$conn->prepare("SELECT short_name FROM tbl_university");
    $stmt1->execute();
    $shortname=$stmt1->fetch();
    $insertshortname=$shortname['short_name'];
    
    $stmt2=$conn->prepare("SELECT MAX(staff_id) AS last_id FROM tbl_staff");
    $stmt2->execute();
    $last=$stmt2->fetch();
    $lastId = $last['last_id'];
    $nextNumber = (int) substr($lastId, 4) + 1;
    $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    $newStaffId = $insertshortname . $paddedNumber;
    $newImageName = $timestamp . $newStaffId . $filenamedefaultExtension . '.' . $file_extension;
    $stmt3 = $conn->prepare("SELECT nid FROM tbl_staff WHERE nid='".$nid."'");
    $stmt3->execute();
    $checkId = $stmt3->fetch();
    $checkedId = $checkId['nid'];
    
    $stmt4 = $conn->prepare("SELECT email FROM tbl_staff WHERE email='".$email."'");
    $stmt4->execute();
    $checkEmail = $stmt4->fetch();
    $checkedEmail = $checkEmail['email'];
    
    if ($checkedId && $checkedEmail) {
        $data = array("status"=>"401","message" => "Employee already exists!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
    echo $jsonData;   
    } else {
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

        $stmt5 = $conn->prepare("INSERT INTO tbl_staff (staff_id,is_acadmic,staff_family_name,staff_first_name,PhoneNumber,email,nid,Country,Province,
        	District,Sector,Cell,Village,Department,Post,probation_period,Nationality,staff_sex,MartialStatus,staff_dob,staff_doj,staff_active,Bank,AccountNumber,
        	basic_salary,mother_name,father_name,fulltime,campus,staff_image)
        VALUES('".$newStaffId."','".$accademic."','".$lname."','".$fname."','".$phone."','".$email."','".$nid."','".$country."','".$province_id."','".$district_id."',
        '".$sector."','".$cell_id."','".$village_id."','".$dep_id."','".$post_id."','".$probation."','".$nationality."','".$gender."','".$mstatus."',
        '".$st_dob."','".$start_date."',1,'".$bank_name."','".$acc_number."','".$salary."','".$mother_names."','".$father_names."','".$part_full."','".$campus."','".$newImageName."')");
       if($stmt5->execute()){
        $data = array("status"=>"200","message" => "Successfully Inserted!");
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
        
    }
 else if($action=="view_employee"){
    $emp_id=$_POST['emp_id'];
    $stmt6=$conn->prepare("SELECT tbl_staff.*,tbl_nationality.nationality,tbl_country.cntr_name,provinces.provincename,
    districts.namedistrict,sectors.namesector,cells.nameCell,villages.VillageName,tbl_department.dept_full_name,tbl_post.post_name,
    tbl_campus.camp_full_name
    FROM tbl_staff 
    INNER JOIN tbl_nationality ON tbl_staff.Nationality=tbl_nationality.nat_id
    INNER JOIN tbl_country ON tbl_staff.Country=tbl_country.cntr_id
    INNER JOIN provinces ON tbl_staff.Province=provinces.provincecode
    INNER JOIN districts ON tbl_staff.District=districts.districtcode 
    INNER JOIN sectors ON tbl_staff.Sector=sectors.sectorcode
    INNER JOIN cells ON tbl_staff.Cell=cells.codecell
    INNER JOIN villages ON tbl_staff.Village=villages.CodeVillage 
    INNER JOIN tbl_department ON tbl_staff.Department=tbl_department.dept_id 
    INNER JOIN tbl_post ON tbl_staff.Post=tbl_post.post_id 
    INNER JOIN tbl_campus ON tbl_staff.campus=tbl_campus.camp_id WHERE tbl_staff.id='".$emp_id."'");
    $stmt6->execute();
    $rows=$stmt6->fetch();
    echo json_encode($rows);
    
 }
 else if($action=="edit_employee"){
     $emp_id = $_POST['emp_id'];
        $stmt7 = $conn->prepare("SELECT tbl_staff.*,tbl_nationality.nationality,tbl_country.cntr_name,provinces.provincename,districts.namedistrict,
        sectors.namesector,cells.nameCell,villages.VillageName,tbl_department.dept_full_name,tbl_post.post_name,tbl_campus.camp_full_name
        FROM tbl_staff 
        INNER JOIN tbl_nationality ON tbl_staff.Nationality=tbl_nationality.nat_id 
        INNER JOIN tbl_country ON tbl_staff.Country=tbl_country.cntr_id 
        INNER JOIN provinces ON tbl_staff.Province=provinces.provincecode 
        INNER JOIN districts ON tbl_staff.District=districts.districtcode 
        INNER JOIN sectors ON tbl_staff.Sector=sectors.sectorcode 
        INNER JOIN cells ON tbl_staff.Cell=cells.codecell 
        INNER JOIN villages ON tbl_staff.Village=villages.CodeVillage 
        INNER JOIN tbl_department ON tbl_staff.Department=tbl_department.dept_id 
        INNER JOIN tbl_post ON tbl_staff.Post=tbl_post.post_id 
        INNER JOIN tbl_campus ON tbl_staff.campus=tbl_campus.camp_id WHERE tbl_staff.id='".$emp_id."'");
        $stmt7->execute();
        $row = $stmt7->fetch();
        $stmt8 = $conn->prepare("SELECT * FROM tbl_nationality");
        $stmt8->execute();
        $nationalities = $stmt8->fetchAll(); // Fetch all rows using fetchAll()
        $stmt9=$conn->prepare("SELECT * FROM tbl_country");
        $stmt9->execute();
        $countries=$stmt9->fetchAll();
        $stmt10=$conn->prepare("SELECT * FROM provinces");
        $stmt10->execute();
        $provinces=$stmt10->fetchAll();
        $stmt11=$conn->prepare("SELECT * FROM districts");
        $stmt11->execute();
        $districts=$stmt11->fetchAll();
        $stmt12=$conn->prepare("SELECT * FROM sectors");
        $stmt12->execute();
        $sectors=$stmt12->fetchAll();
        $stmt13=$conn->prepare("SELECT * FROM cells");
        $stmt13->execute();
        $cells=$stmt13->fetchAll();
        $stmt14=$conn->prepare("SELECT * FROM villages");
        $stmt14->execute();
        $villages=$stmt14->fetchAll();
        $stmt15=$conn->prepare("SELECT * FROM  tbl_department WHERE status=1");
        $stmt15->execute();
        $departments=$stmt15->fetchAll();
        $stmt16=$conn->prepare("SELECT * FROM  tbl_post WHERE status=1");
        $stmt16->execute();
        $posts=$stmt16->fetchAll();
        $stmt23=$conn->prepare("SELECT * FROM  tbl_campus WHERE camp_active=1");
        $stmt23->execute();
        $campus=$stmt23->fetchAll();
        $rows = array();
        array_push($rows, $row, $nationalities,$countries,$provinces,$districts,$sectors,$cells,$villages,$departments,$posts,$campus);
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
      $probition=$_POST['probition_edit'];
      $partfull=$_POST['part_full_edit'];
      $start_date=$_POST['start_date_edit'];
      $post_edit=$_POST['post_edit'];
      $bank_edit=$_POST['bank_edit'];
      $account_edit=$_POST['account_edit'];
      $salary_edit=$_POST['salary_edit'];
      if (!empty($image_name)) {
     $filenamedefaultExtension = pathinfo($image_name, PATHINFO_FILENAME);
      $file_extension = 'png';
      $timestamp = time();
      
      $getdata=$conn->prepare("SELECT 	staff_image,staff_id FROM tbl_staff WHERE id='".$staff_id."'");
      $getdata->execute();
      $getdata2=$getdata->fetch();
      $deleteImg=$getdata2['staff_image'];
      $staff_id2=$getdata2['staff_id'];
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
      $stmt21=$conn->prepare("UPDATE  tbl_staff SET is_acadmic='".$academic."',staff_family_name='".$fname_edit."',staff_first_name='".$lname_edit."',PhoneNumber='".$phone_edit."',
      email='".$email_edit."',nid='".$idn_edit."',Country='".$residence_edit."',Province='".$province_edit."',District='".$district_edit."',
      Sector='".$sector_edit."',Cell='".$cell_edit."',Village='".$village_edit."',Department='".$department_edit."',Post='".$post_edit."',
      probation_period='".$probition."',Nationality='".$nationality_edit."',staff_sex='". $gender_edit."',MartialStatus='".$martial_edit."',
      staff_dob='".$dob_edit."',staff_doj='".$start_date."',Bank='".$bank_edit."',AccountNumber='".$account_edit."',basic_salary='".$salary_edit."',mother_name='".$mother_edit."',
      	father_name='".$father_edit."',fulltime='".$partfull."',campus='".$campus."',staff_image='".$newImageName."' WHERE id='".$staff_id."'");  
      }
      else{
        $stmt21=$conn->prepare("UPDATE  tbl_staff SET is_acadmic='".$academic."',staff_family_name='".$fname_edit."',staff_first_name='".$lname_edit."',PhoneNumber='".$phone_edit."',
      email='".$email_edit."',nid='".$idn_edit."',Country='".$residence_edit."',Province='".$province_edit."',District='".$district_edit."',
      Sector='".$sector_edit."',Cell='".$cell_edit."',Village='".$village_edit."',Department='".$department_edit."',Post='".$post_edit."',
      probation_period='".$probition."',Nationality='".$nationality_edit."',staff_sex='". $gender_edit."',MartialStatus='".$martial_edit."',
      staff_dob='".$dob_edit."',staff_doj='".$start_date."',Bank='".$bank_edit."',AccountNumber='".$account_edit."',basic_salary='".$salary_edit."',mother_name='".$mother_edit."',
      	father_name='".$father_edit."',fulltime='".$partfull."',campus='".$campus."' WHERE id='".$staff_id."'");     
      }
      if($stmt21->execute()){
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
    $stafImage=$conn->prepare("SELECT staff_image FROM tbl_staff WHERE id ='".$staff_id."'");
    $stafImage->execute();
    $gtImage=$stafImage->fetch();
    $deleteImg=$gtImage['staff_image'];
    $image_path="images/".$deleteImg;
     unlink($image_path);
     $stmt22 = $conn->prepare("DELETE FROM tbl_staff WHERE id ='".$staff_id."'");
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
else if($action=="upload_image"){
    if (isset($_POST['image'])) {
        
        $imageData = $_POST['image'];
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
        
        $code = $_POST['code'];
        $directory = 'images/';
        
        // Remove existing files with the same code
        $existing_files = glob($directory . $code . '*[0-9].png');
        if (!empty($existing_files)) {
            foreach ($existing_files as $file) {
                if (!unlink($file)) {
                    echo "Error removing the existing file: " . $file;
                    return;
                }
            }
        }
        
        // Create new file with timestamp
        $timestamp = time();
        $new_file_path = $directory . $code . $timestamp . '.png';
        
        // Save the uploaded image
        if (file_put_contents($new_file_path, $imageData)) {
            // Check if code is an email or staff_id
            if (filter_var($code, FILTER_VALIDATE_EMAIL)) {
                // Code is an email
                $update = $conn->prepare("UPDATE tbl_staff_info SET staff_image=? WHERE email=?");
            } else {
                // Code is a staff_id
                $update = $conn->prepare("UPDATE tbl_staff_info SET staff_image=? WHERE staff_id=?");
            }
            
            if($update->execute([$new_file_path, $code])){
                echo 200;    
            } else {
                echo 500;
            }
        } else {
            echo "Error saving image file";
        }
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