<?php
    include ('../meet/con.php');
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$dept=$_POST['prg_id'];
	$splz=$_POST['splz_id'];
	$mode=$_POST['prg_mode_id'];
	$award=$_POST['award'];
    $grad_acad=$_POST['acad_grad'];
    
    $getSplz = $conn->prepare("SELECT * FROM tbl_specialization WHERE splz_id='".$splz."' ");
    $getSplz->execute();
    $splz_data = $getSplz->fetch();
    
    $splz_name = $splz_data['splz_full_name'];
    $required_credits = $splz_data['totalCredits'];
    $degree_name = $splz_data['degree_name'];

    foreach($_POST['selectedStu'] as $stu){
        $tcredits = 0;
        $avg = 0;   
        $l1=0;
        $l2=0;
        $l3=0;
        $l4=0;

        $getLastLevel = $conn->prepare("SELECT level_id, prg_type FROM tbl_register_program_ug WHERE reg_no='".$stu."' ORDER BY reg_prg_id DESC LIMIT 1");
        $getLastLevel->execute();
        $last_level=$getLastLevel->fetch();

        $getLevels = $conn->prepare("SELECT DISTINCT(level_id) FROM tbl_register_program_ug WHERE reg_no='".$stu."' ORDER BY reg_prg_id ASC");
        $getLevels->execute();
        
        $levels = $getLevels->rowCount();
        $l = [0, 0, 0, 0];
        $i = 0; 
        
        while($level=$getLevels->fetch()){
            $credits = 0;
            $creditpts = 0;
            $getModules = $conn->prepare('SELECT 
                                            DISTINCT(tbl_markby_module.module_id),
                                            modules.module_code,
                                            tbl_modules.module_credits,
                                            tbl_markby_module.marks,
                                            tbl_markby_module.enrolled
                                        FROM tbl_markby_module
                                            INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                            INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                        WHERE tbl_markby_module.reg_no="'.$stu.'" AND
                                              tbl_modules.level_id="'.$level['level_id'].'" AND
                                              tbl_modules.credited_module=1 AND
                                              tbl_markby_module.enrolled IN (1,2) AND 
                                              (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                              tbl_markby_module.marks IS NOT NULL AND tbl_markby_module.marks>=60
                                            ');
            $getModules->execute(); 

            while($module=$getModules->fetch()){
                $tcredits += $module['module_credits'];
                $credits += $module['module_credits'];
                $creditpts += $module['module_credits'] * $module['marks'];
            }
            $l[$i] = $creditpts/$credits;
            $i++;
        }

        $avg = array_sum($l)/$levels;
        
        if($tcredits>=$required_credits){
            #################### Classification ###########################
            $prg_type = $last_level['prg_type'];
            
            if ($prg_type == 1) {
                $totalclass = 0;
                $creditsclass = 0;
                $avg = 0;
                $under80 = 0;
                $under70 = 0;
                $under60 = 0;
            
                $punder80 = 0;
                $punder70 = 0;
                $punder60 = 0;
            
                $getStudentMarks = $conn->prepare("SELECT tm.module_credits, tm.is_project, mm.marks, mm.status FROM tbl_markby_module mm INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id WHERE tm.level_id IN(1, 2, 3, 4) AND mm.reg_no = ? AND mm.marks IS NOT NULL AND mm.status IN (1, 6)");
                $getStudentMarks->execute([$stu]);
            
                while ($marks = $getStudentMarks->fetch()) {
                    $totalclass += $marks['marks'] * $marks['module_credits'];
                    $creditsclass += $marks['module_credits'];
                    if ($marks['marks'] < 60) {
                        $under80 += 1;
                        $under70 += 1;
                        $under60 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                            $punder60 += 1;
                        }
                    } else if ($marks['marks'] < 70) {
                        $under80 += 1;
                        $under70 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                        }
                    } else if ($marks['marks'] < 80) {
                        $under80 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                        }
                    }
                }
            
                $avg = $totalclass / ($creditsclass > 0 ? $creditsclass : 1);
            
                if ($avg >= 80) {
                    $class = "First Class Honours";
                } else if ($avg >= 70) {
                    $class = "Second Class Honours, Upper Division";
                } else if ($avg >= 60) {
                    $class = "Second Class Honours, Lower Division";
                } else {
                    $class = "Pass";
                }
            } else if ($prg_type == 2) {
                $totalclass = 0;
                $creditsclass = 0;
                $avg = 0;
                $under80 = 0;
                $under70 = 0;
                $under60 = 0;
            
                $punder80 = 0;
                $punder70 = 0;
                $punder60 = 0;
            
                $getStudentMarks = $conn->prepare("SELECT tm.module_credits, tm.is_project, mm.marks, mm.status FROM tbl_markby_module mm INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id WHERE tm.level_id IN(6) AND mm.reg_no = ? AND mm.marks IS NOT NULL AND mm.status IN (1, 6)");
                $getStudentMarks->execute([$stu]);
            
                while ($marks = $getStudentMarks->fetch()) {
                    $totalclass += $marks['marks'] * $marks['module_credits'];
                    $creditsclass += $marks['module_credits'];
                    if ($marks['marks'] < 60) {
                        $under80 += 1;
                        $under70 += 1;
                        $under60 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                            $punder60 += 1;
                        }
                    } else if ($marks['marks'] < 70) {
                        $under80 += 1;
                        $under70 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                        }
                    } else if ($marks['marks'] < 80) {
                        $under80 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                        }
                    }
                }
            
                $avg = $totalclass / ($creditsclass > 0 ? $creditsclass : 1);
            
                if ($avg >= 80) {
                    $class = "High Distinction";
                } else if ($avg >= 70) {
                    $class = "Distinction";
                } else if ($avg >= 60) {
                    $class = "Satisfaction";
                } else {
                    $class = "Pass";
                }
            } else if ($prg_type == 3) {
                $totalclass = 0;
                $creditsclass = 0;
                $avg = 0;
                $under80 = 0;
                $under70 = 0;
                $under60 = 0;
            
                $punder80 = 0;
                $punder70 = 0;
                $punder60 = 0;
            
                $getStudentMarks = $conn->prepare("SELECT tm.module_credits, tm.is_project, mm.marks, mm.status FROM tbl_markby_module mm INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id WHERE tm.level_id IN(7, 8) AND mm.reg_no = ? AND mm.marks IS NOT NULL AND mm.status IN (1, 6)");
                $getStudentMarks->execute([$stu]);
            
                while ($marks = $getStudentMarks->fetch()) {
                    $totalclass += $marks['marks'] * $marks['module_credits'];
                    $creditsclass += $marks['module_credits'];
                    if ($marks['marks'] < 60) {
                        $under80 += 1;
                        $under70 += 1;
                        $under60 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                            $punder60 += 1;
                        }
                    } else if ($marks['marks'] < 70) {
                        $under80 += 1;
                        $under70 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                        }
                    } else if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                        $under80 += 1;
                        if ($marks['is_project']) {
                            $punder80 += 1;
                        }
                    }
                }
            
                $avg = $totalclass / ($creditsclass > 0 ? $creditsclass : 1);
            
                if ($avg >= 80) {
                    $class = "High Distinction";
                } else if ($avg >= 70) {
                    $class = "Distinction";
                } else if ($avg >= 60) {
                    $class = "Satisfaction";
                } else {
                    $class = "Pass";
                }
            } else if ($prg_type == 4) {
                $totalclass = 0;
                $creditsclass = 0;
                $avg = 0;
                $under80 = 0;
                $under70 = 0;
                $under60 = 0;
            
                $punder80 = 0;
                $punder70 = 0;
                $punder60 = 0;
            
                $getStudentMarks = $conn->prepare("SELECT tm.module_credits, tm.is_project, mm.marks, mm.status FROM tbl_markby_module mm INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id WHERE tm.level_id IN(9, 10, 11) AND mm.reg_no = ? AND mm.marks IS NOT NULL AND mm.status IN (1, 6)");
                $getStudentMarks->execute([$stu]);
            
                while ($marks = $getStudentMarks->fetch()) {
                    $totalclass += $marks['marks'] * $marks['module_credits'];
                    $creditsclass += $marks['module_credits'];
                    if ($marks['marks'] < 60) {
                        $under80 += 1;
                        $under70 += 1;
                        $under60 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                            $punder60 += 1;
                        }
                    } else if ($marks['marks'] < 70) {
                        $under80 += 1;
                        $under70 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                            $punder70 += 1;
                        }
                    } else if ($marks['marks'] < 80) {
                        $under80 += 1;
                        if ($marks['is_project'] == 1 || $marks['is_project'] == 'yes') {
                            $punder80 += 1;
                        }
                    }
                }
            
                $avg = $totalclass / ($creditsclass > 0 ? $creditsclass : 1);
            
                if ($avg >= 80) {
                    $class = "High Distinction";
                } else if ($avg >= 70) {
                    $class = "Distinction";
                } else if ($avg >= 60) {
                    $class = "Satisfaction";
                } else {
                    $class = "Pass";
                }
            }  
            
            #################### Classification ###########################
            
            try{
                $degre_code=substr($stu,4,20);
                $stmt0 = $conn->prepare("SELECT * FROM tbl_graduants WHERE reg_no = ? AND splz_id = ?");
                $stmt0->execute([$stu, $splz]);
                if($stmt0->rowCount() == 0){
                	$stmt = $conn->prepare("INSERT INTO tbl_graduants(reg_no,degree_no,level_id,splz_id,prg_id,l1_marks,l2_marks,l3_marks,l4_marks,cum_marks,prg_mode_id,prg_award_id,grad_cycle_id, classification) 
                	                                        VALUES ('".trim($stu)."','".$degre_code."','".$last_level['level_id']."','".$splz."','".$dept."','".$l[0]."','".$l[1]."' ,'".$l[2]."','".$l[3]."','".$avg."','".$mode."' ,'".$award."','".$grad_acad."','".$class."')");
                    
                    $stmt->execute();
                } else{
                	$stmt = $conn->prepare("UPDATE tbl_graduants SET l1_marks = ?, l2_marks = ?, l3_marks = ?, l4_marks = ?, cum_marks = ?, grad_cycle_id = ?, classification = ? WHERE reg_no = ?");
                    $stmt->execute([$l[0], $l[1], $l[2], $l[3], $avg, $grad_acad, $class, $stu]);
                }
            }
			catch (PDOException $ex){
			    echo $ex->getMessage();
			}
        }
    }
    echo 1;
?>