<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Marks Excel Format '".date('Y-M-d h:i',time())."'.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    
    include "../../meet/con.php";
    // request params
    $acad_cycle_id =$_REQUEST['acad_cycle_id'];
    $modules =$_REQUEST['modules'];

    $splz =$_REQUEST['splz'];
    $mode =$_REQUEST['mode'];
    $level =$_REQUEST['level'];
    $prgType =$_REQUEST['prg_type'];
    $status =1;

    $valuesArray = explode(',', $modules);;

    $campus =$conn->prepare("SELECT c.camp_full_name FROM tbl_campus c INNER JOIN tbl_program_type p ON c.camp_id = p.campus_id WHERE p.prg_type_id = :type");
    $campus->bindParam(':type', $prgType, PDO::PARAM_INT);
    $campus->execute();
    $theCamp = $campus->rowCount() > 0 ? $campus->fetch(PDO::FETCH_ASSOC) :array("camp_full_name"=>"");
?>

<table>
    <tr>
        <td >Campus</td>
        <td><?php echo $theCamp['camp_full_name']; ?> </td>  
    </tr>
    <br>
    <tr>
        <td >Specialization</td>
        <td  >
            <?php 
                $spec =$conn ->prepare("SELECT * FROM tbl_specialization WHERE splz_id = :splz AND status = :status");
                $spec ->bindParam(':status', $status, PDO::PARAM_INT);
                $spec ->bindParam(':splz', $splz, PDO::PARAM_INT);
                $spec->execute();
                $theSpec =$spec->fetch(PDO::FETCH_ASSOC);
                echo $theSpec['splz_full_name'];
            ?> 
        </td>  
    </tr>
    <br>
    <tr>
        <td >Academic Year</td>
        <td >
            <?php 
            $intakeLabel =$conn ->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id = :acad_cycle_id AND status = :status");
            $intakeLabel ->bindParam(':status', $status, PDO::PARAM_INT);
            $intakeLabel ->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
            $intakeLabel->execute();
            $theIntake =$intakeLabel->fetch(PDO::FETCH_ASSOC);
            echo $theIntake['acad_year'];
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td >Level</td>
        <td ><?php 
            $levelLabel =$conn ->prepare("SELECT * FROM tbl_level WHERE level_id = :level AND prg_type = :type AND status = :status");
            $levelLabel ->bindParam(':status', $status, PDO::PARAM_INT);
            $levelLabel ->bindParam(':type', $prgType, PDO::PARAM_INT);
            $levelLabel ->bindParam(':level', $level, PDO::PARAM_INT);
            $levelLabel->execute();
            $theLevel =$levelLabel->fetch(PDO::FETCH_ASSOC);
            echo $theLevel['level_full_name'];
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td >Program</td>
        <td >
        <?php 
            $prgModeLabel =$conn ->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id = :modeId AND status = :status");
            $prgModeLabel ->bindParam(':status', $status, PDO::PARAM_INT);
            $prgModeLabel ->bindParam(':modeId', $mode, PDO::PARAM_INT);
            $prgModeLabel->execute();
            $theMode =$prgModeLabel->fetch(PDO::FETCH_ASSOC);
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
                <th><?php echo "S/N" ;?></th>
                <th><?php echo "Identification" ;?></th>
                <th><?php echo "Names" ;?></th>
                <?php
                for($counter =0; $counter < count($valuesArray); $counter +=1){
                    $module =$valuesArray[$counter];
                    try{
                        ?>
                        <!--<td><?php echo $module;?></td>-->
                        
                        <?php
                        $modulesInfo =$conn->prepare("SELECT * FROM tbl_modules WHERE module_id = :mod AND prg_type = :prgType AND level_id = :level AND splz_id = :splz");
                        $modulesInfo ->bindParam(':mod', $module, PDO::PARAM_INT);
                        $modulesInfo ->bindParam(':prgType', $prgType, PDO::PARAM_INT);
                        $modulesInfo ->bindParam(':level', $level, PDO::PARAM_INT);
                        $modulesInfo ->bindParam(':splz', $splz, PDO::PARAM_INT);
                        $modulesInfo->execute();
                        
                        $theMods =$modulesInfo->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($theMods as $modId){
                            $moduleName =$conn->prepare("SELECT * FROM modules WHERE module_id = :module");
                            $moduleName ->bindParam(':module', $modId['mod_id'], PDO::PARAM_INT);
                            $moduleName->execute();
                            
                            $theName =$moduleName->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <th colspan="2" class="form-control marks"><?php echo $theName['module_name'];?></th>
                            <?php
                        }
                        
                    }catch(Exception $exc){
                        echo $exc->getMessage();
                    }
                }
                ?>
            </tr>
        </thead>									        
    </center>
    <tbody>
           <?php
           $counter =0;
            $students =$conn->prepare("SELECT ug.*, ad.fname, ad.lname FROM tbl_register_program_ug ug JOIN tbl_admission ad ON ug.reg_no = ad.reg_no WHERE ad.acad_cycle_id = :acad_cycle_id  AND ug.level_id = :level AND ug.splz_id = :splz AND ug.prg_mode_id = :mode");
            $students->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
            $students->bindParam(':level', $level, PDO::PARAM_INT);
            $students->bindParam(':splz', $splz, PDO::PARAM_INT);
            $students->bindParam(':mode', $mode, PDO::PARAM_INT);
            $students->execute();
            
            $data =$students->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $element){
                $counter +=1;
                ?>
                <tr>
                <td><?php echo $counter;?></td>
                <td><?php echo $element['reg_no'];?></td>
                <td><?php echo $element['fname']." ".$element['lname']; ?></td>
                
                <?php
                    
                    for($i=0; $i <count($valuesArray); $i +=1){
                        $stu = $element['reg_no'];

                        $marks =$conn->prepare("SELECT cat, final_exam FROM tbl_markby_module WHERE module_id = ? AND splz_id = ? AND reg_no = ? LIMIT 1");
                        $marks->execute([$valuesArray[$i], $splz, $stu]);
                        $data=$marks->fetch(PDO::FETCH_ASSOC);
                ?>
                    <td>
                        <?php echo $data['cat']; ?>
                    </td>
                    <td>
                        <?php echo $data['final_exam']; ?>
                    </td>
                    <?php
                }
                ?>
                </tr>
                <?php
                
            }
           ?>
    </tbody>
</table> 
<!--</div>-->
