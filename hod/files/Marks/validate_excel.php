<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Marks.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    
    include "../../meet/con.php";
    
    $intake =$_REQUEST['intake'];
    $splz = $_REQUEST['splz'];
    $mode = $_REQUEST['mode'];
    $level = $_REQUEST['level'];
    $prgType = $_REQUEST['prg_type'];

    $modules = array();

    $campus =$conn->prepare("SELECT c.camp_full_name FROM tbl_campus c INNER JOIN tbl_program_type p ON c.camp_id = p.campus_id WHERE p.prg_type_id = :type");
    $campus->bindParam(':type', $prgType, PDO::PARAM_INT);
    $campus->execute();
    $theCampus = $campus->rowCount() > 0 ? $campus->fetch() :array("camp_full_name"=>"");
    
    $getModules = $conn->prepare("SELECT module_id FROM tbl_modules WHERE splz_id='".$splz."' AND level_id='".$level."' AND status=1");
    $getModules->execute();
    

    while ($mods = $getModules->fetch()){
        array_push($modules, $mods['module_id']);
    }
?>

<table>
    <tr>
        <td >Campus</td>
        <td><?php echo $theCampus['camp_full_name']; ?> </td>  
    </tr>
    <br>
    <tr>
        <td >Specialization</td>
        <td  >
            <?php 
                $spec =$conn ->prepare("SELECT * FROM tbl_specialization WHERE splz_id = :splz");
                $spec ->bindParam(':splz', $splz, PDO::PARAM_INT);
                $spec->execute();
                $theSpec =$spec->fetch();
                echo $theSpec['splz_full_name'];
            ?> 
        </td>  
    </tr>
    <br>
    <tr>
        <td >Intake</td>
        <td >
            <?php 
            $intakeLabel =$conn ->prepare("SELECT i.intake_id, i.intake_month, a.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id = a.acad_cycle_id WHERE i.intake_id = :intake");
            $intakeLabel ->bindParam(':intake', $intake, PDO::PARAM_INT);
            $intakeLabel->execute();
            $theIntake =$intakeLabel->fetch();
            echo $theIntake['intake_month']." | ".$theIntake['acad_year'];
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td >Level</td>
        <td ><?php 
            $levelLabel =$conn ->prepare("SELECT * FROM tbl_level WHERE level_id = :level AND prg_type = :type");
            $levelLabel ->bindParam(':type', $prgType, PDO::PARAM_INT);
            $levelLabel ->bindParam(':level', $level, PDO::PARAM_INT);
            $levelLabel->execute();
            $theLevel =$levelLabel->fetch();
            echo $theLevel['level_full_name'];
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td >Program</td>
        <td >
        <?php 
            $prgModeLabel =$conn ->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id = :modeId");
            $prgModeLabel ->bindParam(':modeId', $mode, PDO::PARAM_INT);
            $prgModeLabel->execute();
            $theMode =$prgModeLabel->fetch();
            echo $theMode['prg_mode_full_name'];
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td></td>
        <td></td>  
    </tr>
    <br>
    <tr>
        <td></td>
        <td></td>  
    </tr>
</table>
<table  border="1">
    <center>
    	<thead>
            <tr>    
                <?php
                ?>
                <th rowspan="2"><?php echo "S/N" ;?></th>
                <th rowspan="2"><?php echo "Identification" ;?></th>
                <th rowspan="2"><?php echo "Names" ;?></th>
                <?
                    for($counter =0; $counter < count($modules); $counter +=1){
                        $module =$modules[$counter];
                        try{
                            $modulesInfo =$conn->prepare("SELECT * FROM tbl_modules WHERE module_id = :mod AND prg_type = :prgType AND level_id = :level AND splz_id = :splz");
                            $modulesInfo ->bindParam(':mod', $module, PDO::PARAM_INT);
                            $modulesInfo ->bindParam(':prgType', $prgType, PDO::PARAM_INT);
                            $modulesInfo ->bindParam(':level', $level, PDO::PARAM_INT);
                            $modulesInfo ->bindParam(':splz', $splz, PDO::PARAM_INT);
                            $modulesInfo->execute();
                            
                            $theMods =$modulesInfo->fetchAll();
                            
                            foreach($theMods as $modId){
                                $moduleName =$conn->prepare("SELECT * FROM modules WHERE module_id = :module");
                                $moduleName ->bindParam(':module', $modId['mod_id'], PDO::PARAM_INT);
                                $moduleName->execute();
                                
                                $theName =$moduleName->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <th colspan="3" class="form-control marks"><?php echo $theName['module_name']." [".$theName['module_code'].", ".$modId['module_credits']."]";?></th>
                            <?php
                        }
                        
                    }catch(Exception $exc){
                        echo $exc->getMessage();
                    }
                }
                ?>
            </tr>
            <tr>
                <?php for ($i=0; $i<count($modules); $i++){ ?>
                <th>CAT</th>
                <th>EXAM</th>
                <th>Total/100</th>
                <?php } ?>
            </tr>
        </thead>									        
    </center>
    <tbody>
        <?php
            $counter =0;
            $students =$conn->prepare("SELECT * FROM tbl_register_program_ug WHERE intake_id = :intake AND level_id = :level AND splz_id = :splz AND prg_mode_id = :mode");
            $students ->bindParam(':intake', $intake, PDO::PARAM_INT);
            $students ->bindParam(':level', $level, PDO::PARAM_INT);
            $students ->bindParam(':splz', $splz, PDO::PARAM_INT);
            $students ->bindParam(':mode', $mode, PDO::PARAM_INT);
            $students->execute();
            
            $data =$students->fetchAll();
            foreach($data as $element){
                $counter +=1;
                $student_adm =$conn->prepare("SELECT * FROM tbl_admission WHERE reg_no = '".$element['reg_no']."'");
                $student_adm->execute();
                
                $stu_names =$student_adm->fetch();
        ?>
        <tr>
            <td><?php echo $counter;?></td>
            <td><?php echo $element['reg_no'];?></td>
            <td><?php echo $stu_names['fname']." ".$stu_names['lname']; ?></td>
        <?php
            for($i=0; $i <count($modules); $i +=1){
                $stu = $element['reg_no'];
                $marks =$conn->prepare("SELECT cat, final_exam, marks FROM tbl_markby_module WHERE module_id = ? AND intake_id = ? AND splz_id = ? AND reg_no = ? LIMIT 1");
                $marks->execute([$modules[$i], $intake, $splz, $stu]);
                $data=$marks->fetch();
        ?>
            <td>
                <?=$data['cat']; ?>
            </td>
            <td>
                <?=$data['final_exam']; ?>
            </td>
            <td>
                <?=$data['marks']; ?>
            </td>
        <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table> 