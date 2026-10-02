<?php
/**
 * 
 * Author @macintosh
 */
 ?>
<?php include('../../meet/con.php');?>

<?php

// request vars
$id =$_REQUEST['identity'];
$prgMode =$_REQUEST['mode'];
$class =$_REQUEST['class'];
$level=$_REQUEST['level'];
$day =$_REQUEST['day'];
$module =$_REQUEST['module-id'];
$date =date('Y-m-d');
$attendanceSession =$_REQUEST['attendance-name'].date('Y-m-d H');

// find out the program type and program mode of incoming spec

    $dept=$conn->prepare("SELECT * from tbl_specialization where dept_id IN(select Department from tbl_staff where staff_id =:staff) and splz_id =:spec");
    $dept->bindParam(':staff',$id);
    $dept->bindParam(':spec',$class);
    $dept->execute();
    $theDept=$dept->fetch();
    $prgType =$theDept['prg_type'];
 try{   
     if (isset($_REQUEST['reg_numbs'])) {
        $regNums =$_REQUEST['reg_numbs'];
        $attended = $_REQUEST['attended'];
        $comments = $_REQUEST['comment'];  
        
        for($counter =0; $counter <count($regNums); $counter +=1){
            
            $regNum = $regNums[$counter];
            $isChecked = isset($attended[$regNum]) ? true : false;
            $comment = empty($comments[$counter]) ? 'NULL' : $comments[$counter];
            
            $insertQuerry =$conn->prepare("insert into tbl_class_attendance(program_type, program_mode, specialization, level, learning_day, module_id, student_reg_number, attendance_status, additional_info, date, attendance_lecturer, attendance_session)
            VALUES(:type, :mode_id, :class_id, :level_id, :day_id, :module_id, :reg_number, :attendance, :information, :date_id, :attendance_lecturer, :attend_name)");
            
            $insertQuerry->bindParam(':type', $prgType, PDO::PARAM_INT);
            $insertQuerry->bindParam(':mode_id', $prgMode, PDO::PARAM_INT);
            $insertQuerry->bindParam(':class_id', $class, PDO::PARAM_INT);
            $insertQuerry->bindParam(':level_id', $level, PDO::PARAM_INT);
            $insertQuerry->bindParam(':day_id', $day, PDO::PARAM_INT);
            $insertQuerry->bindParam(':module_id', $module, PDO::PARAM_INT);
            $insertQuerry->bindParam(':reg_number', $regNum, PDO::PARAM_STR);
            $insertQuerry->bindParam(':attendance', $isChecked, PDO::PARAM_INT);
            $insertQuerry->bindParam(':information', $comment, PDO::PARAM_STR);
            $insertQuerry->bindParam(':date_id', $date, PDO::PARAM_STR);
            $insertQuerry->bindParam(':attendance_lecturer', $id, PDO::PARAM_STR);
            $insertQuerry->bindParam(':attend_name', $attendanceSession, PDO::PARAM_STR);
            
            // execute insert query
            $insertQuerry->execute();
            if($insertQuerry){
                echo 1;
            }
            
        }
    }
    else if(isset($_REQUEST['reg_numbs_copy'])){

        $regNumsCopy =$_REQUEST['reg_numbs_copy'];
        $attendedCopy = $_REQUEST['attended_copy'];
        $commentsCopy = $_REQUEST['comment_copy'];  
        
        for($counter =0; $counter <count($regNumsCopy); $counter +=1){
            
            $regNumCopy = $regNumsCopy[$counter];
            $isCheckedCopy = isset($attendedCopy[$regNumCopy]) ? true : false;
            $commentCopy = empty($commentsCopy[$counter]) ? 'NULL' : $commentsCopy[$counter];
            
            $updateQuerry =$conn->prepare("UPDATE tbl_class_attendance SET  attendance_status =  :attendance, additional_info = :information, attendance_session = :attendance_session where attendance_id = :attendance_id");
            
            
            $updateQuerry->bindParam(':attendance', $isCheckedCopy, PDO::PARAM_INT);
            $updateQuerry->bindParam(':information', $commentCopy, PDO::PARAM_STR);
            $updateQuerry->bindParam(':attendance_id', $regNumCopy, PDO::PARAM_INT);
            $updateQuerry->bindParam(':attendance_session', $attendanceSession, PDO::PARAM_STR);
            
            // execute insert query
            $updateQuerry->execute();
            if($updateQuerry){
                echo 2;
            }
            
        }
            
    }
 }catch(Exception $exc){
     echo $exc->getMessage();
     echo $exc->getTraceAsString();
 }
?>