<?php
    include ('../meet/con.php');
    $file = $_FILES['csv_file']['tmp_name'];
    $changes=0;
    
    try{
        $handle = fopen($file, 'r');
    
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $student = str_replace(" ", "", $data[0]);
            $module_code = str_replace(" ", "", $data[1]);
            
            $cat = floatval($data[3]);
            $exam = floatval($data[4]);
            
            $getStudent = $conn->prepare("SELECT acad_cycle_id, splz_id FROM tbl_register_program_ug WHERE reg_no = ? ORDER BY reg_prg_id DESC LIMIT 1");
            $getStudent->execute([$student]);
            
            if($getStudent->rowCount() > 0){
                $stu_data = $getStudent->fetch();
                $acad_cycle_id = $stu_data['acad_cycle_id'];
                $splz_id = $stu_data['splz_id'];
                
                $getModule = $conn->prepare("SELECT module_id FROM modules WHERE module_code = ?");
                $getModule->execute([$module_code]);
                
                if($getModule->rowCount() > 0){
                    $mod_id = $getModule->fetch()['module_id'];
                    $getModuleData = $conn->prepare("SELECT module_id, cat, exam FROM tbl_modules WHERE mod_id = ? AND splz_id = ?");
                    $getModuleData->execute([$mod_id, $splz_id]);
                    
                    if($getModuleData->rowCount() > 0){
                        $moduleData = $getModuleData->fetch();
                        
                        $module_id = $moduleData['module_id'];
                        $per_cat_marks = $moduleData['cat'];
                        $per_exam_marks = $moduleData['exam'];
                        
                        if($cat <= $per_cat_marks && $exam <= $per_exam_marks){
                            $marks = $cat + $exam;

                            $checkMarks = $conn->prepare("SELECT mark_id FROM tbl_markby_module WHERE reg_no = ? AND module_id = ? AND status != 16 ORDER BY mark_id DESC LIMIT 1");
                            $checkMarks->execute([$student, $module_id]);

                            if($checkMarks->rowCount() > 0){
                                $mark_id = $checkMarks->fetch()['mark_id'];

                                $update = $conn->prepare("UPDATE tbl_markby_module SET cat = ?, cat_status='yes', final_exam = ? , final_exam_status='yes', marks = ?, marks_status='yes' WHERE mark_id = ?");
                                if($update->execute([$cat, $exam, $marks, $mark_id])){
                                    $changes++;
                                }
                            }else{
                                $insert = $conn->prepare("INSERT INTO tbl_markby_module(reg_no, splz_id, module_id, acad_cycle_id, cat, final_exam, marks, cat_status, final_exam_status, marks_status)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if($insert->execute([$student, $splz_id, $module_id, $acad_cycle_id, $cat, $exam, $marks, 'yes', 'yes', 'yes'])){
                                    $changes++;
                                }
                            }
                        } else{
                            $data = array("status"=>"401","message" => "Failed to save marks! Some marks are above the maximum; fix it and retry! #".$student." *".$module_code);
                            $jsonData = json_encode($data);
                            echo $jsonData; 
                            exit;
                        }
                    } else{
                        $data = array("status"=>"401","message" => "Failed to save marks! Check your module assignments! #".$module_code);
                        $jsonData = json_encode($data);
                        echo $jsonData; 
                        exit;
                    }
                } else{
                    $data = array("status"=>"401","message" => "Failed to save marks! There is unknown module! #".$student." *".$module_code);
                    $jsonData = json_encode($data);
                    echo $jsonData; 
                    exit;
                }
            }
    	}
    	fclose($handle);
    }
    catch(PDOException $e){
        $data = array("status"=>"401","message" => "Something went wrong!");
        $jsonData = json_encode($data);
        echo $jsonData;   
    }
    
    if($changes>0){
        $data = array("status"=>"200","message" => "marks pushed successfully!");
        $jsonData = json_encode($data);
        echo $jsonData; 
    }else{
        $data = array("status"=>"401","message" => "Failed to save marks!");
        $jsonData = json_encode($data);
        echo $jsonData; 
    }
?>