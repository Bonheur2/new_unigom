<?php

include ('../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection=$conn;
class Specialization{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_spec(){
        $prg_type=$_POST['p_type'];
        $fac_id=$_POST['fac_id'];
        $dept_id=$_POST['dept_id'];
        $splz_full_name=ucwords($_POST['splz_full_name']);
        $splz_short_name=ucwords($_POST['splz_short_name']);
        $degree_name=ucwords($_POST['degree_name']);
        $diploma_name=ucwords($_POST['diploma_name']);
        $state=$_POST['state'];
        $credits=$_POST['credits'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND splz_full_name='".$splz_full_name."' AND state='".$state."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            if($state==1){
                $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND state='".$state."'");
                $stmt->execute();
                if($stmt->rowCount()>=1){
                    $data = array("status"=>"401","message" => "Primary specialization already exists!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                    return false;
                }
            }

            $stmt = $this->connect->prepare("INSERT INTO tbl_specialization(prg_type,fac_id,dept_id,splz_full_name,splz_short_name,state,degree_name,diploma_name,totalCredits) 
                          	VALUES('".$prg_type."','".$fac_id."','".$dept_id."','".$splz_full_name."','".$splz_short_name."','".$state."','".$degree_name."','".$diploma_name."','".$credits."')");
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
	
function view_spec(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT tbl_specialization.*, tbl_program_type.campus_id 
                                         FROM tbl_specialization 
                                         INNER JOIN tbl_program_type ON tbl_specialization.prg_type = tbl_program_type.prg_type_id
                                         WHERE tbl_specialization.splz_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type = :prg_type");
        $stmt2->execute([':prg_type' => $data['prg_type']]);
        $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $this->connect->prepare("SELECT * FROM tbl_department WHERE prg_type = :prg_type");
        $stmt3->execute([':prg_type' => $data['prg_type']]);
        $data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $info = array($data, $data2, $data3);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    function update_spec(){
        $id = $_POST['splz_id'];
        $prg_type=$_POST['p_type'];
        $fac_id=$_POST['fac_id'];
        $dept_id=$_POST['dept_id'];
        $splz_full_name=$_POST['splz_full_name'];
        $splz_short_name=$_POST['splz_short_name'];
        $degree_name=$_POST['degree_name'];
        $diploma_name=$_POST['diploma_name'];
        $credits=$_POST['credits'];
        $state=$_POST['state'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND splz_full_name='".$splz_full_name."' AND state='".$state."' AND splz_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            if($state==1){
                $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$prg_type."' AND fac_id='".$fac_id."' AND dept_id='".$dept_id."' AND state='".$state."' AND splz_id!='".$id."'");
                $stmt->execute();
                if($stmt->rowCount()>=1){
                    $data = array("status"=>"401","message" => "Primary specialization already exists!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;   
                    return false;
                }
            }
                $stmt = $this->connect->prepare(" UPDATE tbl_specialization SET 
                                                                    prg_type='".$prg_type."',
                                                                    fac_id='".$fac_id."',
                                                                    dept_id='".$dept_id."',
                                                                    splz_full_name='".$splz_full_name."',
                                                                    splz_short_name='".$splz_short_name."',
                                                                    degree_name='".$degree_name."',
                                                                    diploma_name='".$diploma_name."',
                                                                    state='".$state."',
                                                                    totalCredits='".$credits."'
                                                                WHERE splz_id='".$id."'");
            if($stmt->execute()){
                    $data = array("status"=>"200","message" => "data updated successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                } else {
                    $data = array("status"=>"500","message" => "Failed to update data");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
        }
    }

	function delete_spec(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE splz_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $stmt2 = $this->connect->prepare("UPDATE tbl_specialization SET status=2 WHERE splz_id='".$id."'");
        }
        else{
            $stmt2 = $this->connect->prepare("UPDATE tbl_specialization SET status=1 WHERE splz_id='".$id."'");  
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
    
    function load_modules(){
    	$splz_id=$_POST['splz_id'];
    	$level_id=$_POST['level_id'];
        $getModules = $this->connect->prepare("SELECT 
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.module_id
                                                    FROM tbl_modules
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id 
                                                    WHERE tbl_modules.splz_id='".$splz_id."' AND tbl_modules.level_id='".$level_id."' AND tbl_modules.status=1");
        $getModules->execute();
        $modules=$getModules->fetchAll();
        $jsonData = json_encode($modules);
        header('Content-Type: application/json');
        echo $jsonData; 
    } 

    function load_students(){
        $camp_id=$_POST['camp_id'];
        $prg_type_id=$_POST['prg_type_id'];
        $fac_id=$_POST['fac_id'];
        $dept_id=$_POST['dept_id'];
        $splz_id=$_POST['splz_id'];
        $level_id=$_POST['level_id'];
        
    	
        $getStudents = $this->connect->prepare("SELECT r.reg_no, a.fname, a.lname
                                                    FROM tbl_register_program_ug r
                                                        INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                    WHERE 
                                                    r.prg_id='".$prg_type_id."' AND
                                                    r.splz_id='".$splz_id."' AND
                                                    r.level_id='".$level_id."' AND
                                                    r.prg_type='".$prg_type_id."' AND
                                                    r.fac_id='".$fac_id."' AND
                                                    r.dept_id='".$dept_id."'");
        $getStudents->execute();
        $students=$getStudents->fetchAll();
        
        $jsonData = json_encode($students);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function load_class_list(){
        $prg_type=$_POST['prg_type'];
    	$fac=$_POST['fac_id'];
    	$dept=$_POST['dept_id'];
    	$splz=$_POST['splz_id'];
    	$acad_cycle_id=$_POST['acad_cycle_id'];
    	$level=$_POST['level_id'];
    	$mode=$_POST['mode'];
        $getStudents = $this->connect->prepare("SELECT 
                                                    tbl_register_program_ug.reg_no,
                                                    tbl_admission.fname,
                                                    tbl_admission.lname
                                                FROM tbl_register_program_ug
                                                    INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                                WHERE
                                                    tbl_register_program_ug.prg_type='".$prg_type."' AND 
                                                    tbl_register_program_ug.fac_id='".$fac."' AND 
                                                    tbl_register_program_ug.dept_id='".$dept."' AND
                                                    tbl_register_program_ug.splz_id='".$splz."' AND
                                                    tbl_admission.acad_cycle_id='".$acad_cycle_id."' AND
                                                    tbl_register_program_ug.level_id='".$level."' AND
                                                    tbl_register_program_ug.prg_mode_id='".$mode."' AND
                                                    tbl_register_program_ug.reg_active=1");
        $getStudents->execute();
        $students=$getStudents->fetchAll();

        //get possible specializations
        $getSpecs = $this->connect->prepare("SELECT splz_id,splz_full_name FROM tbl_specialization WHERE dept_id='".$dept."' AND status=1");
        $getSpecs->execute();
        $specs=$getSpecs->fetchAll();
        $data=array();
        array_push($data,$students,$specs);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function promote(){
        $prg_type=$_POST['prg_type'];
    	$fac=$_POST['fac_id'];
    	$dept=$_POST['dept_id'];
    	$splz=$_POST['splz_id'];
    	$level=$_POST['level_id'];
    	$mode=$_POST['mode'];
    	$acad_cycle=$_POST['acad_cycle_id'];
    	$dt=date('Y-m-d');
    	
    	$pass_marks = 60;
    	
        //check next level
        $currentLevelData = $this->connect->prepare("SELECT level_no FROM tbl_level WHERE level_id='".$level."' AND prg_type='".$prg_type."'");
        $currentLevelData->execute();
        $currentLevel=$currentLevelData->fetch();
        $hasNext = $this->connect->prepare("SELECT level_id,level_full_name FROM tbl_level WHERE level_no>'".$currentLevel['level_no']."' AND prg_type='".$prg_type."' ORDER BY level_no ASC LIMIT 1");
        $hasNext->execute();
        
        //get academic year
        $activeAcad = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE acad_cycle_id !='$acad_cycle' AND status=1 ");
        $activeAcad->execute();
        if($activeAcad->rowCount()>0){
            if($hasNext->rowCount()>0){
                $nextLevel=$hasNext->fetch();
                $newLevel=$nextLevel['level_id'];
                $acad=$activeAcad->fetch();
                $acad_cycle_id=$acad['acad_cycle_id'];
                try {
                    $this->connect->beginTransaction();
                    foreach($_POST['stu'] as $reg_no){
                        
                        
                        //check failed credits
                        $checkFailedCredits = $this->connect->prepare("SELECT
                                                                            SUM(tbl_modules.module_credits) as sum
                                                                        FROM
                                                                            tbl_markby_module
                                                                        INNER JOIN tbl_modules ON tbl_markby_module.module_id = tbl_modules.module_id
                                                                        WHERE
                                                                            (tbl_markby_module.marks < '".$pass_marks."' OR tbl_markby_module.marks IS NULL) AND 
                                                                            tbl_markby_module.reg_no = '".$reg_no."' AND 
                                                                            tbl_markby_module.enrolled = 1 AND 
                                                                            tbl_markby_module.status = 1");
                        $checkFailedCredits->execute();
                        $failedCredits=$checkFailedCredits->fetch();
                        $failedCreditsCount=$failedCredits['sum'];
                        
                        //promotion
                        if($failedCreditsCount==0){
                            //close year marks
                            $closeMarks = $this->connect->prepare("UPDATE tbl_markby_module SET status=6 WHERE reg_no='".$reg_no."' AND enrolled=1 AND status=1");
                            $closeMarks->execute(); 
                                    
                            $checkRecent = $this->connect->prepare("SELECT * FROM tbl_register_program_ug WHERE reg_no='".$reg_no."' AND level_id='".$level."' AND reg_active=1");
                            $checkRecent->execute();
                            if($checkRecent->rowCount()==0){
                                //promote student
                                $update = $this->connect->prepare("UPDATE tbl_register_program_ug SET reg_active=6 WHERE reg_no='".$reg_no."' AND prg_type='".$prg_type."' AND  fac_id='".$fac."' AND dept_id='".$dept."' AND splz_id='".$$splz."' AND level_id='".$level."' AND reg_active=1");
                                $update->execute();
                                
                                $select_comp="SELECT * FROM tbl_program_type WHERE prg_type_id='$prg_type'";
                                $cselect_comp=$this->connect->prepare($select_comp);
                                $cselect_comp->execute();
                                $row_cselect_comp=$cselect_comp->fetch(PDO::FETCH_ASSOC);
                                
                                $camp_id=$row_cselect_comp['campus_id'];
                                
                               $sql = "INSERT INTO `tbl_register_program_ug` 
                                            (`reg_no`, `acad_cycle_id`, `prg_id`, `splz_id`, `level_id`, `prg_mode_id`, `prg_type`, `fac_id`, `dept_id`,`camp_id`)
                                        VALUES 
                                            (:reg_no, :acad_cycle_id, :prg_id, :splz_id, :level_id, :prg_mode_id, :prg_type, :fac_id, :dept_id,:camp_id)";
                                            
                                $promote = $this->connect->prepare($sql);
                                
                                // Bind parameters to avoid SQL injection
                                $promote->bindParam(':reg_no', $reg_no, PDO::PARAM_STR);
                                $promote->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
                                $promote->bindParam(':prg_id', $prg_type, PDO::PARAM_INT);
                                $promote->bindParam(':splz_id', $splz, PDO::PARAM_INT);
                                $promote->bindParam(':level_id', $newLevel, PDO::PARAM_INT);
                                $promote->bindParam(':prg_mode_id', $mode, PDO::PARAM_INT);
                                $promote->bindParam(':prg_type', $prg_type, PDO::PARAM_STR);
                                $promote->bindParam(':fac_id', $fac, PDO::PARAM_INT);
                                $promote->bindParam(':dept_id', $dept, PDO::PARAM_INT);
                                $promote->bindParam(':camp_id', $camp_id, PDO::PARAM_INT);
                            
                                // Execute the prepared statement
                                $promote->execute();
                                    
                                // $promote = $this->connect->prepare("INSERT INTO tbl_register_program_ug(`reg_no`,`acad_cycle_id`,`prg_id`,`splz_id`,`level_id`,`prg_mode_id`,`prg_type`,`fac_id`,`dept_id`)VALUES('".$reg_no."','".$acad_cycle_id."','".$dept."','".$splz."','".$newLevel."','".$mode."','".$prg_type."','".$fac."','".$dept."')");
                                // $promote->execute();
                                    
                                //get modules
                                $getModules = $this->connect->prepare("SELECT module_id FROM tbl_modules WHERE level_id='".$newLevel."' AND prg_type='".$prg_type."' AND  fac_id='".$fac."' AND dept_id='".$dept."' AND splz_id='".$splz."'");
                                $getModules->execute();
                                while($module=$getModules->fetch()){
                                    $assignMod = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no,enrolled)VALUES('".$module['module_id']."','".$acad_cycle_id."','".$splz."','".$reg_no."',0)");
                                    $assignMod->execute(); 
                                }
                                $select_fee="SELECT * FROM tbl_fee_category WHERE prg_type_id='$prg_type' AND fac_id='$fac' AND dept_id='$dept' AND splz_id='$splz' AND level_id='$newLevel'";
                                $cselect_fee=$this->connect->prepare($select_fee);
                                $cselect_fee->execute();
                                $row_cselect_fee=$cselect_fee->fetch(PDO::FETCH_ASSOC);
                                
                                $fee_id=$row_cselect_fee['id'];
                                $balance=$row_cselect_fee['amount'];
                                $invoice_date=date('Y-m-d');
                                
                                $insert_invoice = "INSERT INTO tbl_invoice (reg_no, acad_cycle_id, level_id, fee_id, balance, invoice_date) 
                                VALUES (:reg_no, :acad_cycle_id, :level_id, :fee_id, :balance, :invoice_date)";
                                $cinsert_invoice = $this->connect->prepare($insert_invoice);
                                $cinsert_invoice->execute([
                                    ':reg_no' => $reg_no,
                                    ':acad_cycle_id' => $acad_cycle_id,
                                    ':level_id' => $newLevel,
                                    ':fee_id' => $fee_id,
                                    ':balance' => $balance,
                                    ':invoice_date' => $invoice_date
                                ]);
                                
                                
                            }
                        }
                        //promotion with retake
                        else{
                            $checkRecent = $this->connect->prepare("SELECT * FROM tbl_register_program_ug WHERE reg_no='".$reg_no."' AND level_id='".$newLevel."' AND reg_active=1");
                            $checkRecent->execute();
                            if($checkRecent->rowCount()==0){
                                //close year marks (passed)
                                $closeMarks = $this->connect->prepare("UPDATE tbl_markby_module SET status=6 WHERE reg_no='".$reg_no."' AND marks>='".$pass_marks."' AND enrolled=1 AND status=1");
                                $closeMarks->execute(); 
                                
                                //promote student
                                $update = $this->connect->prepare("UPDATE tbl_register_program_ug SET reg_active=6 WHERE reg_no='".$reg_no."' AND prg_type='".$prg_type."' AND  fac_id='".$fac."' AND dept_id='".$dept."' AND splz_id='".$splz."' AND level_id='".$level."' AND reg_active=1");
                                $update->execute();
                                
                                $select_comp="SELECT * FROM tbl_program_type WHERE prg_type_id='$prg_type'";
                                $cselect_comp=$this->connect->prepare($select_comp);
                                $cselect_comp->execute();
                                $row_cselect_comp=$cselect_comp->fetch(PDO::FETCH_ASSOC);
                                
                                $camp_id=$row_cselect_comp['campus_id'];
                                
                                $sql = "INSERT INTO `tbl_register_program_ug` 
                                            (`reg_no`, `acad_cycle_id`, `prg_id`, `splz_id`, `level_id`, `prg_mode_id`, `prg_type`, `fac_id`, `dept_id`,`camp_id`)
                                        VALUES 
                                            (:reg_no, :acad_cycle_id, :prg_id, :splz_id, :level_id, :prg_mode_id, :prg_type, :fac_id, :dept_id,:camp_id)";
                                            
                                $promote = $this->connect->prepare($sql);
                                
                                // Bind parameters to avoid SQL injection
                                $promote->bindParam(':reg_no', $reg_no, PDO::PARAM_STR);
                                $promote->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
                                $promote->bindParam(':prg_id', $prg_type, PDO::PARAM_INT);
                                $promote->bindParam(':splz_id', $splz, PDO::PARAM_INT);
                                $promote->bindParam(':level_id', $newLevel, PDO::PARAM_INT);
                                $promote->bindParam(':prg_mode_id', $mode, PDO::PARAM_INT);
                                $promote->bindParam(':prg_type', $prg_type, PDO::PARAM_STR);
                                $promote->bindParam(':fac_id', $fac, PDO::PARAM_INT);
                                $promote->bindParam(':dept_id', $dept, PDO::PARAM_INT);
                                $promote->bindParam(':camp_id', $camp_id, PDO::PARAM_INT);
                                
                            
                                // Execute the prepared statement
                                $promote->execute();
    
                                // $promote = $this->connect->prepare("INSERT INTO tbl_register_program_ug (reg_no, acad_cycle_id, prg_id, splz_id, level_id, prg_mode_id, prg_type, fac_id, dept_id) 
                                //                                     VALUES (:reg_no, :acad_cycle_id, :prg_id, :splz_id, :level_id, :prg_mode_id, :prg_type, :fac_id, :dept_id)");
                                // $promote->execute([
                                //     ':reg_no' => $reg_no,
                                //     ':acad_cycle_id' => $acad_cycle_id,
                                //     ':prg_id' => $dept,
                                //     ':splz_id' => $splz,
                                //     ':level_id' => $newLevel,
                                //     ':prg_mode_id' => $mode,
                                //     ':prg_type' => $prg_type,
                                //     ':fac_id' => $fac,
                                //     ':dept_id' => $dept
                                // ]);
                                        
                                //get modules
                                $getModules = $this->connect->prepare("SELECT module_id FROM tbl_modules WHERE level_id='".$newLevel."' AND prg_type='".$prg_type."' AND  fac_id='".$fac."' AND dept_id='".$dept."' AND splz_id='".$splz."'");
                                $getModules->execute();
                                while($module=$getModules->fetch()){
                                    $assignMod = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no,enrolled)VALUES('".$module['module_id']."','".$acad_cycle_id."','".$splz."','".$reg_no."',0)");
                                    $assignMod->execute(); 
                                }
                                
                                //get retakes
                                $stmt = $this->connect->prepare("SELECT module_id,splz_id FROM tbl_markby_module WHERE marks<'".$pass_marks."' AND enrolled=1 AND status=1");
                                $stmt->execute();
                                
                                //close failed year marks
                                $closeFailed = $this->connect->prepare("UPDATE tbl_markby_module SET status=16 WHERE reg_no='".$reg_no."' AND marks<'".$pass_marks."' AND enrolled=1 AND status=1");
                                $closeFailed->execute();
                                
                                //assign retakes
                                while($retake=$stmt->fetch()){
                                    $re_assignMod = $this->connect->prepare("INSERT INTO tbl_markby_module(module_id,acad_cycle_id,splz_id,reg_no,enrolled)VALUES('".$retake['module_id']."','".$acad_cycle_id."','".$retake['splz_id']."','".$reg_no."',1)");
                                    $re_assignMod->execute(); 
                                }
                                
                                $select_fee="SELECT * FROM tbl_fee_category WHERE prg_type_id='$prg_type' AND fac_id='$fac' AND dept_id='$dept' AND splz_id='$splz' AND level_id='$newLevel'";
                                $cselect_fee=$this->connect->prepare($select_fee);
                                $cselect_fee->execute();
                                $row_cselect_fee=$cselect_fee->fetch(PDO::FETCH_ASSOC);
                                
                                $fee_id=$row_cselect_fee['id'];
                                $balance=$row_cselect_fee['amount'];
                                $invoice_date=date('Y-m-d');
                                
                                $insert_invoice = "INSERT INTO tbl_invoice (reg_no, acad_cycle_id, level_id, fee_id, balance, invoice_date) 
                                VALUES (:reg_no, :acad_cycle_id, :level_id, :fee_id, :balance, :invoice_date)";
                                $cinsert_invoice = $this->connect->prepare($insert_invoice);
                                $cinsert_invoice->execute([
                                    ':reg_no' => $reg_no,
                                    ':acad_cycle_id' => $acad_cycle_id,
                                    ':level_id' => $newLevel,
                                    ':fee_id' => $fee_id,
                                    ':balance' => $balance,
                                    ':invoice_date' => $invoice_date
                                ]);
                                
                            }
                        }
                    }
                    if($this->connect->commit()){
                        $data = array("status"=>"200","message" => "Students promoted to ".$nextLevel['level_full_name']);
                        $jsonData = json_encode($data);
                        header('Content-Type: application/json');
                        echo $jsonData; 
                    }
                } catch (PDOException $e) {
                    $this->connect->rollback();
                    $data = array("status"=>"500","message" => "Something went wrong!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
            else{
                //update status
                try {
                    $this->connect->beginTransaction();
                    foreach($_POST['stu'] as $reg_no){
                        $update = $this->connect->prepare("UPDATE tbl_register_program_ug SET reg_active=6 WHERE reg_no='".$reg_no."' AND prg_type='".$prg_type."' AND  fac_id='".$fac."' AND dept_id='".$dept."' AND splz_id='".$$splz."' AND level_id='".$level."' AND reg_active=1");
                        $update->execute();
                    }
                    if($this->connect->commit()){
                        $data = array("status"=>"200","message" => "Students completed their program!");
                        $jsonData = json_encode($data);
                        header('Content-Type: application/json');
                        echo $jsonData; 
                    }
                } catch (PDOException $e) {
                    $this->connect->rollback();
                    $data = array("status"=>"500","message" => "Something went wrong!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
            }
        }else{
            $data = array("status"=>"401","message" => "There is no active academic year!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;  
        }
    }
    function fee_categories(){
        
    $prg_type_id = $_POST['prg_type_id'];
    $fac_id = $_POST['fac_id'];
    $dept_id = $_POST['dept_id'];
    $splz_id = $_POST['splz_id'];
    $level_id = $_POST['level_id'];

    // Query to filter fee categories
    $stmt = $this->connect->prepare("
        SELECT id, name 
        FROM tbl_fee_category 
        WHERE  prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' AND level_id='$level_id'");
    $stmt->execute();
    $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($fees);
    exit;
        
    }
}	
		$spec=new Specialization();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $spec->save_spec();
		        break;
		    case 'update':
		        $spec->update_spec();
		        break;
		    case 'delete':
		        $spec->delete_spec();
		        break;
		    case 'view':
		        $spec->view_spec();
		        break;
		    case 'load-modules':
		        $spec->load_modules();
		        break;
		    case 'load_class_list':
		        $spec->load_class_list();
		        break;
		    case 'load_students':
		        $spec->load_students();
		        break;
		    case 'promote':
		        $spec->promote();
		        break;
		    case 'filter_fee_categories':
		        $spec->fee_categories();
		        break;
		}

	?>

