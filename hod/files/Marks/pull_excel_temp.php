<?php
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=Marks Excel Format '".date('Y-M-d h:i',time())."'.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

include "../meet/con.php";
$connection ="";

if($conn){
    $connection = "connected";
}else{
   $connection = "no connection";
}

// request params
$intake =$_REQUEST['intake'];
$modules =$_REQUEST['modules'];
$splz =$_REQUEST['splz'];
$mode =$_REQUEST['mode'];
$level =$_REQUEST['level'];
$prgType =$_REQUEST['prgType'] ?? 0;
$status =1;

$valuesArray = explode(',', $modules);

$campus =$conn->prepare("SELECT * FROM tbl_campus WHERE camp_id 
IN(SELECT campus_id FROM tbl_program_type WHERE prg_type_id = :type) AND camp_active = :status");
$campus ->bindParam(':type', $prgType, PDO::PARAM_INT);
$campus ->bindParam(':status', $status, PDO::PARAM_INT);
$campus->execute();
$theCamp =$campus->rowCount() >0 ? $campus->fetch() :array("camp_full_name"=>"Not faund");

?>
<!--<div class="table-responsive" style="background-color:RGB(255, 255, 255)">-->
<table>
    <tr>
        <td >Campus</td>
        <td><?php echo $theCamp['camp_full_name'];?> </td>  
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
            $theSpec =$spec->fetch();
            echo $theSpec['splz_full_name'];
            ?> 
        </td>  
    </tr>
    <br>
    
    <?
    // $otherAttr =$conn ->prepare("SELECT * FROM tbl_register_program_ug WHERE splz_id = :splz AND status = :status");
    // $otherAttr ->bindParam(':status', $status, PDO::PARAM_INT);
    // $otherAttr ->bindParam(':splz', $splz, PDO::PARAM_INT);
    // $otherAttr->execute();
    ?>
    <tr>
        <td >Intake</td>
        <td >
            <?php 
            $intakeLabel =$conn ->prepare("SELECT * FROM tbl_intake WHERE intake_id = :intake AND status = :status");
            $intakeLabel ->bindParam(':status', $status, PDO::PARAM_INT);
            $intakeLabel ->bindParam(':intake', $intake, PDO::PARAM_INT);
            $intakeLabel->execute();
            $theIntake =$intakeLabel->fetch();
            echo $theIntake['intake_month'];
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
            $prgModeLabel =$conn ->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id = :modeId AND status = :status");
            $prgModeLabel ->bindParam(':status', $status, PDO::PARAM_INT);
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
        <td><?php //echo $theCamp['camp_full_name'];?> </td>  
    </tr>
    <br>
    <tr>
    <td></td>
    <td><?php //echo $theCamp['camp_full_name'];?> </td>  
</tr>
</table>
<table  border="1">
    <center>
    	<thead>
            <tr>    
                <?php
                ?>
                <th><?php echo "Serial" ;?></th>
                <th><?php echo "Identification" ;?></th>
                <th><?php echo "Names" ;?></th>
                <?
                for($counter =0; $counter < count($valuesArray); $counter +=1){
                    $module =$valuesArray[$counter];
                    try{
                        ?>
                        <!--<td><?php echo $module;?></td>-->
                        
                        <?
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
           <?
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
                for($i=0; $i <count($valuesArray); $i +=1){
                    ?>
                    <td>
                        
                    </td>
                    <td>
                        
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
