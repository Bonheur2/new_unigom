<?php
include ('../../meet/con.php');
$connection=$conn;
class Mark{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }

    function load_specs(){
    	$prg_type=$_POST['prg_type'];
    	$user=$_POST['user'];
        $getSpecs = $this->connect->prepare("SELECT 
                                                distinct(tbl_modules.splz_id),
                                                tbl_specialization.splz_full_name
                                                    FROM tbl_module_leader
                                                INNER JOIN tbl_modules ON tbl_module_leader.module_id=tbl_modules.module_id 
                                                INNER JOIN tbl_specialization ON tbl_modules.splz_id=tbl_specialization.splz_id 
                                                    WHERE tbl_module_leader.staff_id='".$user."' AND tbl_modules.prg_type='".$prg_type."'");
        $getSpecs->execute();
        $specs=$getSpecs->fetchAll();
        $jsonData = json_encode($specs);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    function load_levels(){
    	$splz_id=$_POST['splz_id'];
    	$user=$_POST['user'];
        $getLevels = $this->connect->prepare("SELECT 
                                                distinct(tbl_modules.level_id),
                                                tbl_level.level_full_name
                                                    FROM tbl_module_leader
                                                INNER JOIN tbl_modules ON tbl_module_leader.module_id=tbl_modules.module_id 
                                                INNER JOIN tbl_level ON tbl_modules.level_id=tbl_level.level_id 
                                                    WHERE tbl_module_leader.staff_id='".$user."' AND tbl_modules.splz_id='".$splz_id."'");
        $getLevels->execute();
        $levels=$getLevels->fetchAll();
        $jsonData = json_encode($levels);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    
    function load_modules(){
    	$splz_id=$_POST['splz_id'];
    	$level_id=$_POST['level_id'];
    	$user=$_POST['user'];
        $getModules = $this->connect->prepare("SELECT 
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.module_id
                                                    FROM tbl_module_leader
                                                INNER JOIN tbl_modules ON tbl_module_leader.module_id=tbl_modules.module_id 
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id 
                                                    WHERE tbl_module_leader.staff_id='".$user."' AND tbl_modules.splz_id='".$splz_id."' AND tbl_modules.level_id='".$level_id."' AND tbl_modules.status=1");
        $getModules->execute();
        $modules=$getModules->fetchAll();
        $jsonData = json_encode($modules);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    
    function load_program_modes(){
    	$module=$_POST['module'];
    	$user=$_POST['user'];
        $getModes = $this->connect->prepare("SELECT 
                                                tbl_program_mode.prg_mode_id,
                                                tbl_program_mode.prg_mode_full_name
                                                    FROM tbl_module_leader
                                                INNER JOIN tbl_program_mode ON tbl_module_leader.mode=tbl_program_mode.prg_mode_id 
                                                    WHERE tbl_module_leader.staff_id='".$user."' AND tbl_module_leader.module_id='".$module."'");
        $getModes->execute();
        $modes=$getModes->fetchAll();
        $jsonData = json_encode($modes);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    
    function load_class(){
        $acad_cycle_id=$_POST['acad_cycle_id'];
    	$splz=$_POST['splz'];
    	$level=$_POST['level'];
    	$module=$_POST['module'];
    	$mode=$_POST['mode'];
        $getStudents = $this->connect->prepare("SELECT 
                                                tbl_register_program_ug.reg_no,
                                                tbl_admission.fname,
                                                tbl_admission.lname,
                                                tbl_markby_module.module_id
                                                    FROM tbl_markby_module
                                                INNER JOIN tbl_register_program_ug ON tbl_markby_module.reg_no=tbl_register_program_ug.reg_no
                                                INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                                    WHERE tbl_markby_module.module_id='".$module."' 
                                                        AND tbl_admission.acad_cycle_id='".$acad_cycle_id."' 
                                                        AND tbl_register_program_ug.splz_id='".$splz."' 
                                                        AND tbl_register_program_ug.prg_mode_id='".$mode."'
                                                        AND tbl_register_program_ug.reg_active=1
                                                        AND tbl_markby_module.enrolled=1
                                                        AND tbl_markby_module.status=1
                                                    ");
        $getStudents->execute();
        $students=$getStudents->fetchAll();
        $jsonData = json_encode($students);
        header('Content-Type: application/json');
        echo $jsonData; 
    } 

    function load_exam_marks_list(){
        
        $dataList =array();
        
        $acad_cycle_id=$_POST['acad_cycle_id'];
    	$splz=$_POST['splz'];
    	$level=$_POST['level'];
    	$modules=$_POST['module'];
    	$mode=$_POST['mode'];
    	$ii =0;
        // $valuesArray = array_map('intval', explode(',', $modules));

        
    	foreach($modules as $module){
    	    $ii +=1;
            $getStudents = $this->connect->prepare("SELECT 
                                                    tbl_register_program_ug.reg_no,
                                                    tbl_admission.fname,
                                                    tbl_admission.lname,
                                                    tbl_markby_module.module_id,
                                                    tbl_markby_module.cat,
                                                    tbl_markby_module.final_exam
                                                    FROM tbl_markby_module
                                                    INNER JOIN tbl_register_program_ug ON tbl_markby_module.reg_no=tbl_register_program_ug.reg_no
                                                    INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                                        WHERE tbl_markby_module.module_id= :moduleid
                                                            AND (tbl_admission.acad_cycle_id='".$acad_cycle_id."' OR (tbl_markby_module.status=1 AND tbl_markby_module.module_id= :moduleid))
                                                            AND tbl_register_program_ug.splz_id='".$splz."' 
                                                            AND tbl_register_program_ug.prg_mode_id='".$mode."'
                                                            AND tbl_register_program_ug.reg_active=1
                                                            AND tbl_markby_module.enrolled=1
                                                            AND tbl_markby_module.status=1
                                                        ");
            $getStudents->bindParam(':moduleid', $module, PDO::PARAM_INT);                                          
            $getStudents->execute();
            $students=$getStudents->fetchAll();
            $counted =count($students);
            
            $getModuleData = $this->connect->prepare("SELECT cat, exam, mod_id FROM tbl_modules WHERE module_id='".$module."'");
            $getModuleData->execute();
            $moduleData=$getModuleData->fetchAll();
            
            $modInfoArray =array();
            foreach($moduleData as $modular){
                $modName = $this->connect->prepare("SELECT * FROM modules WHERE module_id = :module");
                $modName->bindParam(':module', $modular['mod_id'], PDO::PARAM_INT);
                $modName->execute();
                $theMode =$modName->fetch();
                
                $array =array(
                    "cats"=>$modular['cat'],
                    "exam"=>$modular['exam'],
                    "module-name"=>$theMode['module_name'],
                );
                $modInfoArray[] =$array;
            }
            $holderArray =array($students, $modInfoArray);
            $dataList[] =$holderArray;
            // array_push($dataList, $students, $modInfoArray );
    	}
        
        $jsonData = json_encode($dataList);
        header('Content-Type: application/json');
        echo $jsonData; 
    } 
    

    function load_class_marks(){
        $acad_cycle_id=$_POST['acad_cycle_id'];
    	$splz=$_POST['splz'];
    	$level=$_POST['level'];
    	$module=$_POST['module'];
    	$mode=$_POST['mode'];
    	$assess_id=$_POST['assess_id'];
    	$assess_no=$_POST['assess_no'];
        $getMarks = $this->connect->prepare("SELECT 
                                                tbl_register_program_ug.reg_no,
                                                tbl_admission.fname,
                                                tbl_assessment_marks.marks
                                                    FROM tbl_assessment_marks
                                                INNER JOIN tbl_register_program_ug ON tbl_assessment_marks.reg_no=tbl_register_program_ug.reg_no
                                                INNER JOIN tbl_admission ON tbl_admission.reg_no=tbl_register_program_ug.reg_no
                                                    WHERE tbl_assessment_marks.module_id='".$module."' 
                                                        AND tbl_admission.intake_id='".$acad_cycle_id."' 
                                                        AND tbl_register_program_ug.splz_id='".$splz."' 
                                                        AND tbl_register_program_ug.prg_mode_id='".$mode."'
                                                        AND tbl_register_program_ug.reg_active=1
                                                        AND tbl_assessment_marks.assess_id='".$assess_id."'
                                                        AND tbl_assessment_marks.assess_no='".$assess_no."'
                                                    ");
        $getMarks->execute();
        $marks=$getMarks->fetchAll();
        
        //get per marks
        $getPerMarks = $this->connect->prepare("SELECT per_marks 
                                                    FROM tbl_assessment_marks 
                                                    WHERE tbl_assessment_marks.module_id='".$module."' 
                                                        AND tbl_assessment_marks.assess_id='".$assess_id."'
                                                        AND tbl_assessment_marks.assess_no='".$assess_no."'
                                                    ");
        $getPerMarks->execute();
        $per_marks=$getPerMarks->fetch();
        
        $array=array();
        
        array_push($array,$marks,$per_marks);
        $jsonData = json_encode($array);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    
    function register_marks(){
        $assess_id=$_POST['assess_id'];
        $assess_no=$_POST['assess_no'];
        $intake_id=$_POST['m_intake'];
        $splz_id=$_POST['m_splz'];
        $mode=$_POST['m_mode'];
        $module=$_POST['m_module'];
        $per_marks=$_POST['per_marks'];
        $dt=date('Y-m-d');
        $changes=0;

        foreach($_POST['stu'] as $value){
            $marks=$_POST['marks_'.$value];
            if($marks>$per_marks){
            $data = array("status"=>"401","message" => "Some marks are above maximum!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
            return false;
            }
        }
        
        foreach($_POST['stu'] as $value){
            try{
                $marks=$_POST['marks_'.$value];
                $stmt = $this->connect->prepare("SELECT * FROM tbl_assessment_marks WHERE module_id='".$module."' AND assess_id='".$assess_id."' AND assess_no='".$assess_no."' AND reg_no='".$value."'");
                $stmt->execute();
                if($stmt->rowCount()>0){
                    $update = $this->connect->prepare("UPDATE tbl_assessment_marks SET marks='".$marks."', per_marks='".$per_marks."' WHERE module_id='".$module."' AND assess_id='".$assess_id."' AND assess_no='".$assess_no."' AND reg_no='".$value."'");
                    if($update->execute()){
                        $changes++;
                    }
                    }
                else {
                    $insert = $this->connect->prepare("INSERT INTO tbl_assessment_marks(reg_no,module_id,intake_id,splz_id,mode,assess_id,assess_no,per_marks,marks,date) 
                          	VALUES('".$value."','".$module."','".$intake_id."','".$splz_id."','".$mode."','".$assess_id."','".$assess_no."','".$per_marks."','".$marks."','".$dt."')");
                    if($insert->execute()){
                        $changes++;
                    }
                }
            }catch(PDOException $e){
            $data = array("status"=>"500","message" => "Something went wrong!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
            }
        }
        if($changes>0){
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"401","message" => "Failed to save marks!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
	}
	
    function register_exam_marks(){
        $intake_id=$_POST['m_intake'];
        $splz_id=$_POST['m_splz'];
        $module=$_POST['m_module'];
        $per_marks_cat=$_POST['permarks_cat'];
        $per_marks_exam=$_POST['permarks_exam'];
        $changes=0;

        foreach($_POST['stu'] as $value){
            $exam_marks=$_POST['exam_'.$value];
            $cat_marks=$_POST['cat_'.$value];
            if($cat_marks>$per_marks_cat || $exam_marks>$per_marks_exam){
            $data = array("status"=>"401","message" => "Some marks are above maximum!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
            return false;
            }
        }
        
        foreach($_POST['stu'] as $value){
            try{
                $exam_marks=$_POST['exam_'.$value];
                $cat_marks=$_POST['cat_'.$value];
                $marks=($cat_marks+$exam_marks)*100/($per_marks_cat+$per_marks_exam);
                $update = $this->connect->prepare("UPDATE tbl_markby_module SET cat='".$cat_marks."', final_exam='".$exam_marks."', marks='".$marks."' WHERE module_id='".$module."' AND splz_id='".$splz_id."' AND reg_no='".$value."' AND status=1");
                if($update->execute()){
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
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"401","message" => "Failed to save marks!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
	}
	
    function upload_exam_marks(){
        $intake_id=$_POST['u_intake'];
        $splz_id=$_POST['u_splz'];
        $module=$_POST['u_module'];
        $file = $_FILES['csv_file']['tmp_name'];
        $inserted=0;
        $changes=0;
        $valuesArray = explode(',', $module);
        
        if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
           echo "found<br>";
        } else{
            echo "not found<br>";
        }
        
        echo "uploaded modules <br>";
        print_r($module);
        
        //get module data
        
        foreach($valuesArray as $mod){
            
            $getModuleData=$this->connect->prepare("SELECT cat, exam FROM tbl_modules WHERE module_id='".$mod."'");
            $getModuleData->execute();
        	$moduleData=$getModuleData->fetch();
        	$per_marks_cat=$moduleData['cat'];
        	$per_marks_exam=$moduleData['exam'];
        
        	echo "module: $mod<br>";
            //handle file data
            $handle = fopen($file, 'r');
            $pos=0;
            
            $rows = [];
            $subArrays =array();
            	
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                echo 110;
        	    if($pos<8){
                    $pos++;
                    continue;
        	    }
        	    else{
                    if($data[3]>$per_marks_cat || $data[4]>$per_marks_exam){
                        $resp = array("status"=>"401","message" => "Some marks are above maximum!");
                        $jsonData = json_encode($resp);
                        header('Content-Type: application/json');
                        echo $jsonData;
                        return false;
                    } 
        	    }
        	}
        }
    	$handle = fopen($file, 'r');
        $pos=0;
        while (($data = fgetcsv($handle)) !== false) {
    	    if($pos<8){
                $pos++;
                continue;
    	    }
    	    else{
    	      $rows[] =$data;  
    	    }
        }
        echo "<br><br>uploaded files <br>";
            print_r($rows);
            echo "<br>";
        $modMarksCounter=0;
        try{
            foreach($rows as $student){
                echo "for each student<br> ";
                    print_r($student);
                    echo " <br> ";
                    
                $reg_no=$student[1];
                $cat=$student[2];
                $exam=$student[3];
            
                for($i = 2; $i < count($student) ; $i += 2) {
                    $subArray = array_slice($student, $i, 2);
                    $subArrays[] = $subArray;
                }
            
                for($counter =0; $counter<count($valuesArray); $counter += 1){
                    $onlyOne =$subArrays[$counter];
                    
                    $internalMod=$this->connect->prepare("SELECT cat, exam FROM tbl_modules WHERE module_id='".$module[$counter]."'");
                    $internalMod->execute();
                	$internalMod=$internalMod->fetch();
                    
                    $marks=($cat+$exam)*100/($per_marks_cat+$per_marks_exam);
                    $update = $this->connect->prepare("UPDATE tbl_marks SET cat='".$cat."', final_exam='".$exam."', marks='".$marks."' WHERE module_id='".$module[$counter]."' AND splz_id='".$splz_id."' AND reg_no='".$reg_no."' AND status=1");
                    if($update->execute()){
                        $changes++;
                    }
                }
                $subArrays =array();
            }
        }catch(PDOException $e){
            $data = array("status"=>"500","message" => "Something went wrong!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }
    	   // }
        
    	fclose($handle);
        if($changes>0){
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"401","message" => "Failed to save marks!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
	}
	
	//load class assessments
    function load_class_assessments(){
        $acad_cycle_id=$_POST['acad_cycle_id'];
    	$splz=$_POST['splz'];
    	$module=$_POST['module'];
    	$mode=$_POST['mode'];
        $getAssessments = $this->connect->prepare("SELECT 
                                                tbl_assessment.assess_name,
                                                tbl_assessment_marks.assess_id,
                                                tbl_assessment_marks.status,
                                                tbl_assessment_marks.assess_no,
                                                tbl_assessment_marks.per_marks,
                                                AVG(tbl_assessment_marks.marks) as average
                                                    FROM tbl_assessment_marks
                                                INNER JOIN tbl_assessment ON tbl_assessment_marks.assess_id=tbl_assessment.assess_id
                                                    WHERE tbl_assessment_marks.module_id='".$module."' 
                                                        AND tbl_assessment_marks.splz_id='".$splz."' 
                                                        AND tbl_assessment_marks.mode='".$mode."'
                                                        AND tbl_assessment_marks.acad_cycle_id='".$acad_cycle_id."'
                                                    GROUP BY tbl_assessment_marks.assess_id,tbl_assessment_marks.assess_no");
        $getAssessments->execute();
        $assessments=$getAssessments->fetchAll();
        $jsonData = json_encode($assessments);
        header('Content-Type: application/json');
        echo $jsonData; 
    }  
    
    function view_assessment(){
        $assess_id=$_POST['assess_id'];
        $assess_no=$_POST['assess_no'];
        $splz=$_POST['splz'];
        $acad_cycle_id=$_POST['acad_cycle_id'];
        $module=$_POST['module'];
        $mode=$_POST['mode'];
        $getData = $this->connect->prepare("SELECT 
                                                DISTINCT(tbl_assessment_marks.assess_id),
                                                tbl_assessment.assess_name,
                                                tbl_assessment_marks.assess_no,
                                                tbl_assessment_marks.per_marks
                                                    FROM tbl_assessment_marks
                                                INNER JOIN tbl_assessment ON tbl_assessment_marks.assess_id=tbl_assessment.assess_id
                                                    WHERE tbl_assessment_marks.module_id='".$module."' 
                                                        AND tbl_assessment_marks.splz_id='".$splz."' 
                                                        AND tbl_assessment_marks.mode='".$mode."'
                                                        AND tbl_assessment_marks.acad_cycle_id='".$acad_cycle_id."'
                                                        AND tbl_assessment_marks.assess_id='".$assess_id."'
                                                        AND tbl_assessment_marks.assess_no='".$assess_no."'
                                                        AND tbl_assessment_marks.status=1");
        $getData->execute();
        $data=$getData->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_cat_group(){
        $assess_id=$_POST['assess_id'];
        $assess_no=$_POST['assess_no'];
        $splz=$_POST['splz'];
        $acad_cycle_id=$_POST['acad_cycle_id'];
        $module=$_POST['module'];
        $mode=$_POST['mode'];
        $check = $this->connect->prepare("SELECT DISTINCT(status) FROM tbl_assessment_marks
                                                WHERE module_id='".$module."' 
                                                    AND splz_id='".$splz."' 
                                                    AND mode='".$mode."'
                                                    AND acad_cycle_id='".$acad_cycle_id."'
                                                    AND assess_id='".$assess_id."'
                                                    AND assess_no='".$assess_no."'");
        $check->execute();
        $statusData=$check->fetch();
        if($statusData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $changeStatus = $this->connect->prepare("UPDATE tbl_assessment_marks SET status='".$status."'
                                                WHERE module_id='".$module."' 
                                                    AND splz_id='".$splz."' 
                                                    AND mode='".$mode."'
                                                    AND acad_cycle_id='".$acad_cycle_id."'
                                                    AND assess_id='".$assess_id."'
                                                    AND assess_no='".$assess_no."'
                                                    AND status='".$statusData['status']."'");
        if($changeStatus->execute()){
            $data = array("status"=>"200","message" => "changes saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"500","message" => "Something went wrong!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function update_class_marks(){
        $id=$_POST['a_id'];
        $splz=$_POST['e_splz'];
        $intake=$_POST['e_intake'];
        $assess_no=$_POST['e_assess_no'];
        $mode=$_POST['e_mode'];
        $module=$_POST['e_module'];
        $assess_id=$_POST['assess_id'];
        $per_marks=$_POST['e_per_marks'];
        
            try{
                $update = $this->connect->prepare("UPDATE tbl_assessment_marks SET 
                marks=marks*$per_marks/per_marks, 
                per_marks='".$per_marks."', 
                assess_id='".$assess_id."'
                WHERE module_id='".$module."' 
                    AND assess_id='".$id."' 
                    AND assess_no='".$assess_no."' 
                    AND splz_id='".$splz."' 
                    AND intake_id='".$intake."' 
                    AND mode='".$mode."'");
                if($update->execute()){
                    $changes++;
                }
            }catch(PDOException $e){
            $data = array("status"=>"500","message" => "Something went wrong!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
            }
        if($changes>0){
            $data = array("status"=>"200","message" => "changes saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function generate_cat_marks(){
        $acad_cycle_id=$_POST['acad_cycle_id'];
        $splz_id=$_POST['splz'];
        $mode=$_POST['mode'];
        $module=$_POST['module'];
        $changes=0;
        $per_marks=0;
        $getAssessTotal = $this->connect->prepare("SELECT 
                                                tbl_assessment.assess_name,
                                                tbl_assessment_marks.assess_id,
                                                tbl_assessment_marks.status,
                                                tbl_assessment_marks.assess_no,
                                                tbl_assessment_marks.per_marks,
                                                AVG(tbl_assessment_marks.marks) as average
                                                    FROM tbl_assessment_marks
                                                INNER JOIN tbl_assessment ON tbl_assessment_marks.assess_id=tbl_assessment.assess_id
                                                    WHERE tbl_assessment_marks.module_id='".$module."' 
                                                        AND tbl_assessment_marks.splz_id='".$splz_id."' 
                                                        AND tbl_assessment_marks.mode='".$mode."'
                                                        AND tbl_assessment_marks.acad_cycle_id='".$acad_cycle_id."'
                                                        AND tbl_assessment_marks.status=1
                                                    GROUP BY tbl_assessment_marks.assess_id,tbl_assessment_marks.assess_no");
        $getAssessTotal->execute();
        while($assessTotal=$getAssessTotal->fetch()){
            $per_marks+=$assessTotal['per_marks'];
        }
        //get module total per CAT
        
        $getModuleCAT = $this->connect->prepare("SELECT cat FROM tbl_modules WHERE module_id='".$module."' AND status=1");
        $getModuleCAT->execute();
        $moduleCAT=$getModuleCAT->fetch();
        $cat_per=$moduleCAT['cat'];

        //get all students' marks
        $getMarks = $this->connect->prepare("SELECT reg_no,SUM(marks) as total_marks FROM tbl_assessment_marks 
                                                            WHERE module_id='".$module."' 
                                                                AND splz_id='".$splz_id."' 
                                                                AND acad_cycle_id='".$acad_cycle_id."' 
                                                                AND mode='".$mode."' 
                                                                AND splz_id='".$splz_id."' 
                                                                AND status=1 GROUP BY reg_no");
        $getMarks->execute();

        while($marks=$getMarks->fetch()){
            try{
                $cat_marks=$marks['total_marks']*$cat_per/$per_marks;
                $update = $this->connect->prepare("UPDATE tbl_markby_module SET cat='".$cat_marks."' WHERE module_id='".$module."' AND splz_id='".$splz_id."' AND reg_no='".$marks['reg_no']."' AND status=1");
                if($update->execute()){
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
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"401","message" => "Failed to save marks!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function export_cat_list(){
        $intake=$_POST['intake'];
    	$splz=$_POST['splz'];
    	$module=$_POST['module'];
    	$mode=$_POST['mode'];
    	$level=$_POST['level'];
        $getClassData=$this->connect->prepare("SELECT 
                                            tbl_campus.camp_full_name,
                                            tbl_program_type.prg_type_full_name,
                                            tbl_faculty.fac_full_name,
                                            tbl_department.dept_full_name,
                                            tbl_specialization.splz_full_name
                                        FROM tbl_specialization 
                                            INNER JOIN tbl_department ON tbl_specialization.dept_id=tbl_department.dept_id
                                            INNER JOIN tbl_faculty ON tbl_specialization.fac_id=tbl_faculty.fac_id
                                            INNER JOIN tbl_program_type ON tbl_specialization.prg_type=tbl_program_type.prg_type_id
                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id
                                        WHERE 
                                        tbl_specialization.splz_id='".$splz."'");
        $getClassData->execute();

    	$classData=$getClassData->fetch();
        $getIntake=$this->connect->prepare("SELECT intake_month FROM tbl_intake WHERE intake_id='".$intake."'");
        $getIntake->execute();
    	$intakeData=$getIntake->fetch();
    	
        $getLevel=$this->connect->prepare("SELECT level_full_name FROM tbl_level WHERE level_id='".$level."'");
        $getLevel->execute();
    	$levelData=$getLevel->fetch();

        $getMode=$this->connect->prepare("SELECT prg_mode_full_name FROM tbl_program_mode WHERE prg_mode_id='".$mode."'");
        $getMode->execute();
    	$modeData=$getMode->fetch();
    	
        $getModuleData=$this->connect->prepare("SELECT 
                                            modules.module_code,
                                            modules.module_name,
                                            tbl_modules.cat,
                                            tbl_modules.exam,
                                            tbl_modules.module_credits
                                        FROM tbl_modules 
                                            INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                        WHERE 
                                        tbl_modules.module_id='".$module."'");
        $getModuleData->execute();
    	$moduleData=$getModuleData->fetch();
    
        $sql=$this->connect->prepare("SELECT 
                                            tbl_admission.fname,
                                            tbl_admission.lname,
                                            tbl_register_program_ug.reg_no,
                                            tbl_markby_module.cat,
                                            tbl_markby_module.final_exam
                                        FROM tbl_markby_module 
                                            INNER JOIN tbl_admission ON tbl_markby_module.reg_no=tbl_admission.reg_no
                                            INNER JOIN tbl_register_program_ug ON tbl_admission.reg_no=tbl_register_program_ug.reg_no
                                        WHERE 
                                        tbl_markby_module.enrolled=1 AND 
                                        tbl_markby_module.module_id='".$module."' AND
                                        tbl_admission.intake_id='".$intake."' AND
                                        tbl_register_program_ug.splz_id='".$splz."' AND
                                        tbl_register_program_ug.level_id='".$level."' AND
                                        tbl_register_program_ug.prg_mode_id='".$mode."' AND
                                        tbl_register_program_ug.reg_active=1 AND
                                        tbl_markby_module.status=1
                                        ORDER BY tbl_register_program_ug.reg_no ASC");
        $sql->execute();
        $csv_file = fopen('marklist_'.$level.'_'.$mode.'_'.$module.'.csv', 'w');
        fputcsv($csv_file,array('Campus:', $classData['camp_full_name']));
        fputcsv($csv_file,array('Program:', $classData['splz_full_name']));
        fputcsv($csv_file,array('Intake:', $intakeData['intake_month']));
        fputcsv($csv_file,array('Level:', $levelData['level_full_name']));
        fputcsv($csv_file,array('Program mode:', $modeData['prg_mode_full_name']));
        fputcsv($csv_file,array('Exam mark sheet:',$moduleData['module_name'].' ['.$moduleData['module_code'].', '.$moduleData['module_credits'].']'));
        fputcsv($csv_file,array());
        fputcsv($csv_file, array('S/N', 'Student ID','Names','CAT/'.$moduleData['cat'],'Exam/'.$moduleData['exam']));
        $i=1;
        while($row = $sql->fetch()) {
            $csv_row = array($i, $row['reg_no'], $row['fname']." ".$row['lname'], $row['cat'], $row['final_exam']);
          fputcsv($csv_file, $csv_row);
          $i++;
        }
        fclose($csv_file);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=marklist_'.$level.'_'.$mode.'_'.$module.'.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo 'marklist_'.$level.'_'.$mode.'_'.$module.'.csv';
        $conn->close();
    }
    
    
    
	function manage_enrollement(){
        $mark_id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT enrolled FROM tbl_markby_module WHERE mark_id = ?");
        $stmt->execute([$mark_id]);
        $prevData=$stmt->fetch();
        if($prevData['enrolled']==1){
            $flag = 0;
        }
        else{
            $flag = 1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_markby_module SET enrolled = ? WHERE mark_id = ?");
        
        if($stmt2->execute([$flag, $mark_id])){
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
    
	function manage_excemption(){
        $mark_id = $_POST['id'];
        $marks = $_POST['marks'];
        $stmt = $this->connect->prepare("SELECT enrolled FROM tbl_markby_module WHERE mark_id = ?");
        $stmt->execute([$mark_id]);
        $prevData=$stmt->fetch();
        if($prevData['enrolled']==1 || $prevData['enrolled']==0){
            $flag = 2;
        }
        else{
            $flag = 1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_markby_module SET enrolled = ?, marks = ? WHERE mark_id = ?");
        
        if($stmt2->execute([$flag, $marks, $mark_id])){
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
    
    
    function save_ind_marks(){
        $stu=$_POST['stu'];
        $changes=0;

        foreach($_POST['selectedModules'] as $module){
            $cat = $_POST['cat_'.$module];
            $exam = $_POST['exam_'.$module];
            
            $Xcat = $cat;
            $Xexam = $exam;
            
            
            if($cat == ''){
                $Xcat = `null`;
                $cat = 0;
            }
            if($exam == ''){
                $Xexam = `null`;
                $exam = 0;
            }
            
            $marks = $cat+$exam;
            
            if($Xcat == `null` && $Xexam == `null`){
                $marks = `null`;
            }

            $update = $this->connect->prepare("UPDATE tbl_markby_module SET cat = ?, final_exam = ?, marks = ?, cat_status = 'yes', final_exam_status = 'yes', marks_status = 'yes' WHERE module_id='".$module."' AND reg_no = '".$stu."' AND status IN(1, 6)");
            if($update->execute([$Xcat, $Xexam, $marks])){
                $changes++;
            }
        }
        if($changes>0){
            $data = array("status"=>"200","message" => "data saved successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        else{
            $data = array("status"=>"401","message" => "Failed to save marks!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
}	

$mark=new Mark();
$action = $_POST['action'];
switch($action){
    case 'load-specs':
        $mark->load_specs();
        break;
    case 'load-levels':
        $mark->load_levels();
        break;
    case 'load-modules':
        $mark->load_modules();
        break;
    case 'load-modes':
        $mark->load_program_modes();
        break;
    case 'load-student-list':
        $mark->load_class();
        break;
    case 'load-exam-mark-list':
        $mark->load_exam_marks_list();
        break;
    case 'load-existing-marks':
        $mark->load_class_marks();
        break;
    case 'register_marks':
        $mark->register_marks();
        break;
    case 'register_exam_marks':
        $mark->register_exam_marks();
        break;
    case 'load-assessment-list':
        $mark->load_class_assessments();
        break;
    case 'load-assessment-data':
        $mark->view_assessment();
        break;
    case 'update_marks':
        $mark->update_class_marks();
        break;
    case 'modify-cat-members':
        $mark->update_cat_group();
        break;
    case 'generate-cat-marks':
        $mark->generate_cat_marks();
        break;
    case 'load-exam-mark-list-csv':
        $mark->export_cat_list();
        break;
    case 'upload_marks':
        $mark->upload_exam_marks();
        break;
    case 'enroll':
        $mark->manage_enrollement();
        break;
    case 'excempt':
        $mark->manage_excemption();
        break;
    case 'save_ind_marks':
        $mark->save_ind_marks();
        break; 
}
?>

