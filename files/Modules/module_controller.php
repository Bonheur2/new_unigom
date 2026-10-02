<?php

include ('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$connection=$conn;
class Module{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_module(){
        $prg_type=$_POST['prg_type'];
        $dept_id=$_POST['dept_id'];
        $module_code=$_POST['module_code'];
        $module_name=$_POST['module_name'];
        $stmt = $this->connect->prepare("SELECT * FROM modules WHERE module_code='".$module_code."' AND dept_id='".$dept_id."' AND prg_type='".$prg_type."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO modules(prg_type,dept_id,module_code,module_name) 
                  	VALUES('".$prg_type."','".$dept_id."','".$module_code."','".$module_name."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
	
    function assign_modules(){
        $program=$_POST['prg_type'];
        $department=$_POST['dept_id'];
        $module=$_POST['mod_id'];
        $faculty=$_POST['fac_id'];
        $level=$_POST['level_id'];
        $spec=$_POST['splz_id'];
        $term=$_POST['term_id'];
        $credited=$_POST['credited_module'];
        $credits=$_POST['module_credits'];
        $credit_price=$_POST['credit_price'];
        $project=$_POST['is_project'];
        $cat=$_POST['cat'];
        $exam=$_POST['exam'];
        	
        $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$program."' AND fac_id='".$faculty."' AND dept_id='".$department."' AND level_id='".$level."' AND mod_id='".$module."' AND term_id='".$term."' AND splz_id='".$spec."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_modules(prg_type,fac_id,dept_id,level_id,mod_id,term_id,module_credits,credited_module,splz_id,credit_price,is_project,cat,exam) 
                  	VALUES('".$program."','".$faculty."','".$department."','".$level."','".$module."','".$term."','".$credits."','".$credited."','".$spec."','".$credit_price."','".$project."','".$cat."','".$exam."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
	
    function assign_module_leaders(){
        $mode=$_POST['a_mode'];
        foreach($_POST['module'] as $value){
            try{
                $staff_id=$_POST['leader_'.$value];
                $assistants = [];
                foreach($_POST['assistant_'.$value] as $assist){
                    if (!in_array($assist, $assistants)) {
                        $assistants[] = $assist;
                        
                    }
                }
                $mod_assits=json_encode($assistants);
                $stmt = $this->connect->prepare("SELECT * FROM tbl_module_leader WHERE module_id='".$value."' AND mode='".$mode."'");
                $stmt->execute();
                if($stmt->rowCount()>0){
                    $stmt2 = $this->connect->prepare("SELECT * FROM tbl_module_leader WHERE module_id='".$value."' AND staff_id='".$staff_id."' AND assistants='".$mod_assits."' AND mode='".$mode."'");
                    $stmt2->execute();
                    if($stmt2->rowCount()>0){
                         
                    }
                    else{
                        $update = $this->connect->prepare("UPDATE tbl_module_leader SET staff_id='".$staff_id."', assistants='".$mod_assits."' WHERE module_id='".$value."' AND mode='".$mode."'");
                        $update->execute();
                    }
                } else {
                    $insert = $this->connect->prepare("INSERT INTO tbl_module_leader(staff_id,assistants,module_id,mode) 
                          	VALUES('".$staff_id."','".$mod_assits."','".$value."','".$mode."')");
                    $insert->execute();
                }
            }catch(PDOException $e){
            $data = array("status"=>"500","message" => "Something went wrong!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
            }
        }
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        
	}

    function update_assign(){
        $program=$_POST['prg_type'];
        $department=$_POST['dept_id'];
        $module=$_POST['mod_id'];
        $faculty=$_POST['fac_id'];
        $level=$_POST['level_id'];
        $spec=$_POST['splz_id'];
        $term=$_POST['term_id'];
        $credited=$_POST['credited_module'];
        $credits=$_POST['module_credits'];
        $credit_price=$_POST['credit_price'];
        $project=$_POST['is_project'];
        $module_id=$_POST['module_id'];
        $cat=$_POST['cat'];
        $exam=$_POST['exam'];
        	
        $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$program."' AND fac_id='".$faculty."' AND dept_id='".$department."' AND level_id='".$level."' AND mod_id='".$module."' AND term_id='".$term."' AND splz_id='".$spec."' AND module_id!='".$module_id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("UPDATE tbl_modules SET 
                                                                    prg_type='".$program."',
                                                                    fac_id='".$faculty."',
                                                                    dept_id='".$department."',
                                                                    level_id='".$level."',
                                                                    mod_id='".$module."',
                                                                    term_id='".$term."',
                                                                    module_credits='".$credits."',
                                                                    credited_module='".$credited."',
                                                                    splz_id='".$spec."',
                                                                    credit_price='".$credit_price."',
                                                                    is_project='".$project."',
                                                                    cat='".$cat."',
                                                                    exam='".$exam."'
                                                                    WHERE module_id='".$module_id."'
                                                                    ");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data updated successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save changes!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
	
    function view_module() {
    $id = $_POST['id'];
    
    // Fetch the module details
    $stmt = $this->connect->prepare("SELECT * FROM modules WHERE module_id = :id");
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch();
    
    // Fetch departments related to the program type
    $stmt = $this->connect->prepare("SELECT * FROM tbl_department WHERE prg_type = :prg_type");
    $stmt->execute(['prg_type' => $data['prg_type']]);
    $departments = $stmt->fetchAll();
    
    
    
    // Fetch program types
    $stmt = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id = :prg_type_id");
    $stmt->execute(['prg_type_id' => $data['prg_type']]);
    $programType = $stmt->fetch();

    // Prepare response data
    $info = array(
        'module_data' => $data,
        'departments' => $departments,
        'program_type' => $programType
    );
    
    // Return as JSON
    header('Content-Type: application/json');
    echo json_encode($info);
}


    
    function view_module_assign(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE module_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
       
        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type='".$data['prg_type']."'");
        $stmt2->execute();
        $data2=$stmt2->fetchAll();
        
        $stmt3 = $this->connect->prepare("SELECT * FROM tbl_department WHERE prg_type='".$data['prg_type']."'");
        $stmt3->execute();
        $data3=$stmt3->fetchAll();
        
        $stmt4 = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$data['prg_type']."'");
        $stmt4->execute();
        $data4=$stmt4->fetchAll();
        
        $stmt5 = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$data['prg_type']."'");
        $stmt5->execute();
        $data5=$stmt5->fetchAll();
        
        $stmt6 = $this->connect->prepare("SELECT * FROM modules WHERE prg_type='".$data['prg_type']."'");
        $stmt6->execute();
        $data6=$stmt6->fetchAll();
        $info=array();
        array_push($info,$data,$data2,$data3,$data4,$data5,$data6);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function load_modules(){
    	$type=$_POST['type'];
    	$dept_id=$_POST['dept_id'];
        // $stmt = $this->connect->prepare("SELECT * FROM modules WHERE prg_type='".$type."' AND dept_id='".$dept_id."'");
        $stmt = $this->connect->prepare("SELECT * FROM modules ");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }    
    function load_level_modules(){
    	$type=$_POST['type'];
    	$dept=$_POST['dept'];
    	$splz=$_POST['splz'];
    	$level=$_POST['level'];
        $stmt = $this->connect->prepare("SELECT 
                                                tbl_modules.module_id,
                                                modules.module_code,
                                                modules.module_name
                                            FROM tbl_modules
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_modules.prg_type='".$type."' AND tbl_modules.dept_id='".$dept."' AND tbl_modules.splz_id='".$splz."' AND tbl_modules.level_id='".$level."' AND tbl_modules.status=1");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }    
    function load_module_leaders(){
    	$prg_type=$_POST['prg'];
    	$fac=$_POST['fac'];
    	$dept=$_POST['dept'];
    	$spec=$_POST['spec'];
    	$level=$_POST['lev'];
    	$mode=$_POST['mode'];
    	$campus=$_POST['cms'];
        $getMods = $this->connect->prepare("SELECT 
                                            tbl_modules.module_id,
                                            modules.module_code,
                                            modules.module_name,
                                            tbl_module_leader.staff_id,
                                            tbl_module_leader.assistants
                                                FROM tbl_modules
                                            LEFT JOIN tbl_module_leader ON tbl_modules.module_id=tbl_module_leader.module_id AND tbl_module_leader.mode='".$mode."'
                                            INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                WHERE tbl_modules.prg_type='".$prg_type."' 
                                                    AND tbl_modules.fac_id='".$fac."' 
                                                    AND tbl_modules.dept_id='".$dept."' 
                                                    AND tbl_modules.splz_id='".$spec."'
                                                    AND tbl_modules.level_id='".$level."'");
        
        $getLeaders = $this->connect->prepare("SELECT family_name,first_name,Identification FROM tbl_users1 WHERE campus_id='".$campus."' AND role_id not IN (4,5,19,23) AND id NOT IN (1,2) AND status=1");
        
        $getMods->execute();
        $getLeaders->execute();
        
        $modules=$getMods->fetchAll();
        $leaders=$getLeaders->fetchAll();
        $data=array();
        array_push($data,$modules,$leaders);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
        function update_module(){
    // Fetch input values
    $id = $_POST['mod_id'];
    $prg_type = $_POST['prg_type'];
    $dept_id = $_POST['dept_id'];
    $module_code = $_POST['module_code'];
    $module_name = $_POST['module_name'];
    // Check if the data already exists (prevent duplicate entries)
    $stmt = $this->connect->prepare(
        "SELECT * FROM modules 
        WHERE module_code = :module_code 
        AND dept_id = :dept_id 
        AND prg_type = :prg_type 
        AND module_id != :id"
    );
    
    // Bind parameters
    $stmt->bindParam(':module_code', $module_code);
    $stmt->bindParam(':dept_id', $dept_id);
    $stmt->bindParam(':prg_type', $prg_type);
    $stmt->bindParam(':id', $id);
    
    $stmt->execute();
    
    // If a row already exists with the same data, return error
    if ($stmt->rowCount() > 0) {
        $data = array("status" => "401", "message" => "Data already exists!");
        echo json_encode($data);
        exit;  // Stop further execution if duplicate is found
    }

    // Proceed with the update if no duplicate data is found
    $updateStmt = $this->connect->prepare(
        "UPDATE modules 
        SET dept_id = :dept_id, prg_type = :prg_type, module_name = :module_name, module_code = :module_code 
        WHERE module_id = :id"
    );

    // Bind parameters for the update query
    $updateStmt->bindParam(':dept_id', $dept_id);
    $updateStmt->bindParam(':prg_type', $prg_type);
    $updateStmt->bindParam(':module_name', $module_name);
    $updateStmt->bindParam(':module_code', $module_code);
    $updateStmt->bindParam(':id', $id);

    // Execute the update query
    if ($updateStmt->execute()) {
        $data = array("status" => "200", "message" => "Data updated successfully");
        echo json_encode($data);
    } else {
        $data = array("status" => "500", "message" => "Failed to update data");
        echo json_encode($data);
    }
}


    	function delete_module(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM modules WHERE module_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE modules SET status=2 WHERE module_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE modules SET status=1 WHERE module_id='".$id."'");  
            }
            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
    	function delete_module_assign(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE module_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_modules SET status=2 WHERE module_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_modules SET status=1 WHERE module_id='".$id."'");  
            }

            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        //enroll to module
    	function enroll(){
            $module = $_POST['module'];
            $stu = $_POST['stu'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_markby_module WHERE module_id='".$module."' AND reg_no='".$stu."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['enrolled']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_markby_module SET enrolled=2 WHERE module_id='".$module."' AND reg_no='".$stu."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_markby_module SET enrolled=1 WHERE module_id='".$module."' AND reg_no='".$stu."'");  
            }

            if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
            
        function export_format(){
            $csv_file = fopen('modules_format.csv', 'w');
            fputcsv($csv_file, array('Node: Do not remove any header or change the provided format. remove the example row only'));
            fputcsv($csv_file, array('Module Code', 'Module Name'));
            fputcsv($csv_file, ['EX001','Example']);
            fclose($csv_file);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=modules_format.csv;');
            header('Pragma: no-cache');
            header('Expires: 0');
            exit();
        }
            
        function export_modules(){
            $sql = $this->connect->prepare("SELECT 
                                                modules.module_code, 
                                                modules.module_name, 
                                                tbl_program_type.prg_type_full_name,
                                                tbl_campus.camp_full_name,
                                                tbl_department.dept_full_name 
                                            FROM modules
                                                INNER JOIN tbl_program_type ON modules.prg_type=tbl_program_type.prg_type_id
                                                INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id
                                                INNER JOIN tbl_department ON modules.dept_id=tbl_department.dept_id
                                            WHERE modules.status=1
                                            ");
            $sql->execute();
            $csv_file = fopen('modules.csv', 'w');
            fputcsv($csv_file, array('Module Code', 'Module Name','Campus','Program Type', 'Department'));

            while($row = $sql->fetch()) {
                $csv_row = array($row['module_code'], $row['module_name'], $row['camp_full_name'],$row['prg_type_full_name'], $row['dept_full_name']);
              fputcsv($csv_file, $csv_row);
            }
            fclose($csv_file);
            $conn->close();
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=modules.csv;');
            header('Pragma: no-cache');
            header('Expires: 0');
            exit();
        }
        
        function export_module_assign(){
            $sql=$this->connect->prepare("SELECT 
                                            modules.module_code,
                                            modules.module_name,
                                            tbl_campus.camp_full_name,
                                            tbl_program_type.prg_type_full_name,
                                            tbl_faculty.fac_full_name,
                                            tbl_department.dept_full_name,
                                            tbl_specialization.splz_full_name,
                                            tbl_level.level_full_name,
                                            tbl_modules.*
                                        FROM tbl_modules 
                                            INNER JOIN tbl_program_type ON tbl_modules.prg_type=tbl_program_type.prg_type_id
                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id
                                            INNER JOIN tbl_faculty ON tbl_modules.fac_id=tbl_faculty.fac_id
                                            INNER JOIN tbl_department ON tbl_modules.dept_id=tbl_department.dept_id
                                            INNER JOIN tbl_specialization ON tbl_modules.splz_id=tbl_specialization.splz_id
                                            INNER JOIN modules ON modules.module_id=tbl_modules.mod_id
                                            INNER JOIN tbl_level ON tbl_modules.level_id=tbl_level.level_id
                                        WHERE tbl_modules.status=1 ORDER BY modules.module_code ASC");
            $sql->execute();
            $csv_file = fopen('modules_by_level.csv', 'w');
            fputcsv($csv_file, array('Module Code', 'Module Name','Campus','Program Type', 'Faculty','Department', 'Specialization','Level','Semester','credited module (1=yes, 0=no)','module credits','credit price','CAT','Exam','Module Project (yes/no)'));

            while($row = $sql->fetch()) {
                $csv_row = array($row['module_code'], $row['module_name'], $row['camp_full_name'], $row['prg_type_full_name'], $row['fac_full_name'],$row['dept_full_name'], $row['splz_full_name'], $row['level_full_name'],$row['term_id'],$row['credited_module'], $row['module_credits'], $row['credit_price'], $row['cat'], $row['exam'], $row['is_project']);
              fputcsv($csv_file, $csv_row);
            }
            fclose($csv_file);
            $conn->close();
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=modules_by_level.csv;');
            header('Pragma: no-cache');
            header('Expires: 0');
            exit();
        }
        function upload_modules(){
            $file = $_FILES['csv_file']['tmp_name'];
            $inserted=0;
            $exists=0;
            $uninserted=0;
            $prg_type=$_POST['prg_type'];
            $dept_id=$_POST['dept'];
            $handle = fopen($file, 'r');
            $j=0;
            while (($data = fgetcsv($handle)) !== false) {
              if($j<2){$j++;continue;}
              $module_code = $data[0];
              $module_name = $data[1];

              $stmt = $this->connect->prepare("SELECT * FROM modules WHERE module_code='".$module_code."' AND dept_id='".$dept_id."' AND prg_type='".$prg_type."'");
              $stmt->execute();
              if($stmt->rowCount()>0){
                  $exists++;   
              } else {
                  $data=[
                      'prg_type'=>$prg_type,
                      'dept_id'=>$dept_id,
                      'module_code'=>$module_code,
                      'module_name'=>$module_name
                      ];
                  $stmt1 = $this->connect->prepare("INSERT INTO modules(prg_type,dept_id,module_code,module_name) 
                      	VALUES(:prg_type,:dept_id,:module_code,:module_name)");
                  if($stmt1->execute($data)){
                        $inserted++;
                  }
                  else{
                        $uninserted++;  
                  }
                }
            }
            if($uninserted>0){
                echo 401;
            }
            else if($inserted>0){
                echo 200;
            }
            else if($exists>0){
                echo 402;
            }
            else{
                echo 500;
            }
            fclose($handle);
        }
        
        function export_assign_format(){
            $csv_file = fopen('modules_assign_format.csv', 'w');
            fputcsv($csv_file, array('Node: Do not remove any header or change the provided format. remove the example row only'));
            fputcsv($csv_file, array('Module Code', 'Credited module (1=yes, 0=no)', 'Module credits', 'Credit price', 'CAT marks', 'Exam marks', 'Has Project (yes/no)'));
            fputcsv($csv_file, ['EX001','1','10','5000','50','50','yes']);
            fclose($csv_file);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=modules_assign_format.csv;');
            header('Pragma: no-cache');
            header('Expires: 0');
            exit();
        }
        function upload_assign_settings(){
            $file = $_FILES['csv_file']['tmp_name'];
            $prg_type=$_POST['prg_type'];
            $fac_id=$_POST['fac_id'];
            $dept_id=$_POST['dept_id'];
            $splz_id=$_POST['splz_id'];
            $level_id=$_POST['level_id'];
            $sem=$_POST['sem'];
            
            $inserted=0;
            $exists=0;
            $uninserted=0;
            $handle = fopen($file, 'r');
            $j=0;
            while (($data = fgetcsv($handle)) !== false) {
                if($j<2){$j++;continue;}

                $getModData=$this->connect->prepare("SELECT module_id FROM modules WHERE module_code='".trim($data[0])."' AND prg_type='".$prg_type."' AND dept_id='".$dept_id."' LIMIT 1");
                $getModData->execute();
                if($getModData->rowCount()>0){
                    $module=$getModData->fetch();
                    $module_id=$module['module_id'];
                  
                    $stmt = $this->connect->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND level_id='".$level_id."' AND mod_id='".$module_id."' AND splz_id='".$splz_id."'");
                    $stmt->execute();
                    if($stmt->rowCount()>0){
                      $exists++;   
                    } else {
                      $stmt1 = $this->connect->prepare("INSERT INTO tbl_modules(prg_type,fac_id,dept_id,level_id,term_id,mod_id,module_credits,credited_module,splz_id,credit_price,cat,exam,is_project) 
                      	VALUES('".$prg_type."','".$fac_id."','".$dept_id."','".$level_id."','".$sem."','".$module_id."','".$data[2]."','".$data[1]."','".$splz_id."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."')");
                      if($stmt1->execute()){
                            $inserted++;
                      }
                      else{
                            $uninserted++;  
                      }
                    }
                }
            }
            if($uninserted>0){
                echo 401;
            }
            else if($inserted>0){
                echo 200;
            }
            else if($exists>0){
                echo 402;
            }
            else{
                echo 500;
            }
            fclose($handle);
        }
        
        function module_evaluation(){
            $module=$_POST['module_id'];
            $splz=$_POST['splz_id'];
            $stu=$_POST['stu'];
            $changes=0;
            
            //get academic year
            $getAcad = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status=1");
            $getAcad->execute();
            $acad=$getAcad->fetch();
            $acad_cycle_id=$acad['acad_cycle_id'];
            
            //get program mode
            $getMode = $this->connect->prepare("SELECT prg_mode_id FROM tbl_register_program_ug WHERE reg_no='".$stu."' AND reg_active=1");
            $getMode->execute();
            $mode=$getMode->fetch();
            $mode_id=$mode['prg_mode_id'];
            
            $getAns = $this->connect->prepare("SELECT * FROM tbl_module_evaluation WHERE stu='".$stu."' AND module_id='".$module."'");
            $getAns->execute();
            if($getAns->rowCount()>0){
                $data = array("status"=>"401","message" => "You have submitted your answers before!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
                return;
            }
            foreach($_POST['question'] as $qn){
                try{
                    $getAns = $this->connect->prepare("SELECT answer_id FROM tbl_evaluation_answer WHERE question_id='".$qn."'");
                    $getAns->execute();
                    if($getAns->rowCount()>0){
                        $insert = $this->connect->prepare("INSERT INTO tbl_module_evaluation(stu,module_id,splz_id,mode_id,acad_cycle_id,question_id,answer_id) 
                                  	VALUES('".$stu."','".$module."','".$splz."','".$mode_id."','".$acad_cycle_id."','".$qn."','".$_POST['answer_'.$qn]."')");
                    }
                    else{
                        $insert = $this->connect->prepare("INSERT INTO tbl_module_evaluation(stu,module_id,splz_id,mode_id,acad_cycle_id,question_id,answer) 
                                  	VALUES('".$stu."','".$module."','".$splz."','".$mode_id."','".$acad_cycle_id."','".$qn."','".$_POST['answer_'.$qn]."')"); 
                    }

                    if($insert->execute()){
                        $changes++;
                    }
                }catch(PDOException $e){
                $data = array("status"=>"500","message" => "Something went wrong!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
                }
            }
            if($changes>0){
                $data = array("status"=>"200");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
            else{
                $data = array("status"=>"401","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
        
        function download_data(){
    $prg_type = $_POST['prg_type'];
    $fac_id = $_POST['fac_id'];
    $dept_id = $_POST['dept_id'];
    $splz_id = $_POST['splz_id'];
    $level_id = $_POST['level_id'];
    $term_id = $_POST['term_id'];

    echo json_encode([
        'status' => 200,
        'program' => $prg_type,
        'school' => $fac_id,
        'department' => $dept_id,
        'splz_id' => $splz_id,
        'level_id' => $level_id,
        'term_id' => $term_id
    ]);
    exit;
}



function upload_csv() {
    set_time_limit(300);
    $csvData = json_decode($_POST['csv_data'], true);

    if (!isset($csvData) || !is_array($csvData)) {
        echo json_encode(['status' => 400, 'message' => 'Invalid CSV data received.']);
        return;
    }

    try {
        $this->connect->beginTransaction();

        // Get university prefix
        // $sql = $this->connect->prepare("SELECT short_name FROM tbl_university ORDER BY id ASC LIMIT 1");
        // $sql->execute();
        // $udata = $sql->fetch();
        // $subcode = $udata['short_name'];
        // $char = strlen($subcode);

        // Get last staff number
        // $checkcode = $this->connect->prepare("SELECT staff_id FROM tbl_staff WHERE staff_id LIKE ? ORDER BY LENGTH(staff_id) DESC, staff_id DESC LIMIT 1");
        // $checkcode->execute([$subcode . '%']);
        // $codeData = $checkcode->fetch();
        // $prevSuffix = $codeData ? (int) substr($codeData['staff_id'], $char) : 0;

        foreach ($csvData as $row) {
            $data = str_getcsv($row);
            if (count($data) < 8) continue;

            // list($module_code, $module_name, $staff_family_name, 
            //      $staff_first_name, $prg_type, $fac_id, $dept_id, 
            //      $splz_id, $level_id, $term_id) = $data;
                 
             list($module_code, $module_name, $prg_type, $fac_id, $dept_id, 
                 $splz_id, $level_id, $term_id) = $data;     

            // Check for existing module
            $stmt = $this->connect->prepare("SELECT module_id FROM modules WHERE module_code = ? AND dept_id = ?");
            $stmt->execute([$module_code, $dept_id]);
            $existingModule = $stmt->fetchColumn();

            if (!$existingModule) {
                // Insert new module
                $this->connect->prepare("INSERT INTO modules (module_code, module_name, prg_type, dept_id) 
                                       VALUES (?, ?, ?, ?)")
                             ->execute([$module_code, $module_name, $prg_type, $dept_id]);
                $mod_id = $this->connect->lastInsertId();
            } else {
                $mod_id = $existingModule;
            }

            // Insert into program structure
            $this->connect->prepare("INSERT INTO tbl_modules (prg_type, fac_id, dept_id, level_id, term_id, mod_id, splz_id)
                                   VALUES (?, ?, ?, ?, ?, ?, ?)")
                         ->execute([$prg_type, $fac_id, $dept_id, $level_id, $term_id, $mod_id, $splz_id]);

            // Skip staff and leader if names are empty
            // if (empty($staff_family_name) || empty($staff_first_name)) {
            //     continue; // Skip this iteration without inserting into staff or module leader
            // }

            // Check if staff already exists
            // $stmt = $this->connect->prepare("SELECT staff_id FROM tbl_staff WHERE staff_family_name = ? AND staff_first_name = ? AND Department = ?");
            // $stmt->execute([$staff_family_name, $staff_first_name, $dept_id]);
            // $staff_id = $stmt->fetchColumn();

            // if (!$staff_id) {
                // Generate new staff ID
                // $prevSuffix++;
                // $suffix = str_pad($prevSuffix, 4, '0', STR_PAD_LEFT);
                // $staff_id = $subcode . $suffix;

                // Insert new staff
                // $this->connect->prepare("INSERT INTO tbl_staff (staff_id, staff_family_name, staff_first_name, Department, Post, campus)
                //                       VALUES (?, ?, ?, ?, 13, 1)")
                //              ->execute([$staff_id, $staff_family_name, $staff_first_name, $dept_id]);
            // }

            // Link module leader
            // $this->connect->prepare("INSERT INTO tbl_module_leader (staff_id, module_id) VALUES (?, ?)")
            //              ->execute([$staff_id, $mod_id]);
        }

        $this->connect->commit();
        echo json_encode(['status' => 200, 'message' => 'Data uploaded successfully.']);
    } catch (Exception $e) {
        $this->connect->rollBack();
        echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
    }
}






function download_data1(){
    $prg_type=$_POST['prg_type'];
    $dept_id=$_POST['dept_id'];
    
    
    
    
    
    // Return the data as a JSON object
    echo json_encode([
        'status' => 200,
        'prg_type'=>$prg_type,
        'dept_id' => $dept_id
    ]);
    exit;
}
function upload_csv1() {
    set_time_limit(300); // Set execution time limit to 5 minutes
    $csvData = json_decode($_POST['csv_data'], true);
    $batchSize = 100; // Number of rows to insert per batch
    $insertCount = 0;
    $rows = [];
    $placeholders = [];

    try {
        $this->connect->beginTransaction();

        foreach ($csvData as $row) {
            $data = str_getcsv($row); // Parse CSV row

            // Ensure the row has at least 4 columns
            if (count($data) < 2) {
                continue; // Skip invalid rows
            }
            $prg_type=$_POST['prg_type'];
            $dept_id=$_POST['dept_id'];

            // Assign variables from CSV data
            list($module_code, $module_name) = $data;

            // Check if the module already exists
            $stmt = $this->connect->prepare("SELECT COUNT(*) FROM modules WHERE module_code = ? AND module_name = ? AND prg_type = ? AND dept_id = ?");
            $stmt->execute([$module_code, $module_name, $prg_type, $dept_id]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                continue; // Skip if module already exists
            }

            // Prepare batch insert data
            $rows[] = [$module_code, $module_name, $prg_type, $dept_id];
            $placeholders[] = "(?, ?, ?, ?)";
            $insertCount++;

            // Execute batch insert when batch size is reached
            if ($insertCount % $batchSize === 0) {
                $stmt = $this->connect->prepare(
                    "INSERT INTO `modules` (`module_code`, `module_name`, `prg_type`, `dept_id`) 
                    VALUES " . implode(", ", $placeholders)
                );
                $stmt->execute(array_merge(...$rows));

                // Reset batch arrays
                $rows = [];
                $placeholders = [];
            }
        }

        // Insert any remaining rows that didn't reach the batch size
        if (!empty($rows)) {
            $stmt = $this->connect->prepare(
                "INSERT INTO `modules` (`module_code`, `module_name`, `prg_type`, `dept_id`) 
                VALUES " . implode(", ", $placeholders)
            );
            $stmt->execute(array_merge(...$rows));
        }

        $this->connect->commit();
        echo json_encode(['status' => 200, 'message' => 'Data inserted successfully.']);
    } catch (Exception $e) {
        $this->connect->rollBack();
        echo json_encode(['status' => 500, 'message' => 'Error inserting data: ' . $e->getMessage()]);
    }
    exit;
}   



        
}	
		$module=new Module();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $module->save_module();
		        break;
		        
		    case 'update':
		        $module->update_module();
		        break;
		        
		    case 'delete':
		        $module->delete_module();
		        break;
		        
		    case 'view':
		        $module->view_module();
		        break;
		        
		    case 'load_modules':
		        $module->load_modules();
		        break;

		    case 'load_level_modules':
		        $module->load_level_modules();
		        break;
		        
		    case 'load-mod-lead':
		        $module->load_module_leaders();
		        break;
		        
		    case 'export':
		        $module->export_modules();
		        break;
		    
		    case 'export_init':
		        $module->export_format();
		        break;
		        
		    case 'export_assign_format':
		        $module->export_assign_format();
		        break;
		        
		    case 'export_modules':
		        $module->export_module_assign();
		        break;
		        
		    case 'upload':
		        $module->upload_modules();
		        break;
		        
		    case 'upload_mo_level':
		        $module->upload_assign_settings();
		        break;
		        
		    case 'assign':
		        $module->assign_modules();
		        break;
		        
		    case 'assign_leaders':
		        $module->assign_module_leaders();
		        break;
		        
		    case 'view_assign':
		        $module->view_module_assign();
		        break;
		        
		    case 'delete_assign':
		        $module->delete_module_assign();
		        break;
		        
		    case 'update_assign':
		        $module->update_assign();
		        break;
		        
		    case 'enroll':
		        $module->enroll();
		        break;
		        
		    case 'evaluation':
		        $module->module_evaluation();
		        break;
		        
		   case 'download_form_data':
		        $module->download_data();
		        break;
		        
		  case 'download_form_data1':
		        $module->download_data1();
		        break;      
		        
		   case 'upload_csv_data':
		       $module->upload_csv();
		       break;
		       
		 case 'upload_csv_data1':
		       $module->upload_csv1();
		       break;      
		}

	?>

