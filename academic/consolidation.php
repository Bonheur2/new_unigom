<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=consolidation Marks.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    
    include "../meet/con.php";
    
    $acad_cycle_id =$_REQUEST['acad_cycle'];
    $splz = $_REQUEST['splz'];
    $mode = $_REQUEST['mode'];
    $prgType = $_REQUEST['prg_type'];

    $campus =$conn->prepare("SELECT c.camp_full_name FROM tbl_campus c INNER JOIN tbl_program_type p ON c.camp_id = p.campus_id WHERE p.prg_type_id = :type");
    $campus->bindParam(':type', $prgType, PDO::PARAM_INT);
    $campus->execute();
    $theCampus = $campus->rowCount() > 0 ? $campus->fetch() :array("camp_full_name"=>"");
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
        <td >Academic Year</td>
        <td >
            <?php 
            $intakeLabel =$conn ->prepare("SELECT * FROM tbl_acad_cycle  WHERE acad_cycle_id = :acad_cycle_id");
            $intakeLabel ->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);
            $intakeLabel->execute();
            $theIntake =$intakeLabel->fetch();
            echo $theIntake['acad_year'];
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
                <th rowspan="3"><?php echo "S/N" ;?></th>
                <th rowspan="3"><?php echo "Identification" ;?></th>
                <th rowspan="3"><?php echo "Names" ;?></th>
                <?php
                    $levels = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prgType."' AND status=1 ORDER BY level_id ASC");
                    $levels->execute();
                    while($lev = $levels->fetch()){
                        $getModules = $conn->prepare("SELECT module_id FROM tbl_modules WHERE splz_id='".$splz."' AND status=1 AND level_id='".$lev['level_id']."' ORDER BY module_id ASC");
                        $getModules->execute();
                        $mods = $getModules->rowCount();
                ?>
                <th colspan="<?php echo $mods; ?>"><?php echo $lev['level_full_name'] ;?></th>    
                <?php } ?>
            </tr>
            <tr>
                <?php
                    $levels2 = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prgType."' AND status=1 ORDER BY level_id ASC");
                    $levels2->execute();
                    
                    while($lev = $levels2->fetch()){
                        $getModules = $conn->prepare("SELECT * FROM tbl_modules WHERE splz_id='".$splz."' AND status=1 AND level_id='".$lev['level_id']."' ORDER BY module_id ASC");
                        $getModules->execute();
                            
                        $theMods =$getModules->fetchAll();
                        foreach($theMods as $modId){
                            $moduleName =$conn->prepare("SELECT * FROM modules WHERE module_id = :module");
                            $moduleName ->bindParam(':module', $modId['mod_id'], PDO::PARAM_INT);
                            $moduleName->execute();
                            
                            $theName =$moduleName->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <th class="form-control marks"><?php echo $theName['module_name']." [".$theName['module_code']."]";?></th>
                        <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                    $levels2 = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prgType."' AND status=1 ORDER BY level_id ASC");
                    $levels2->execute();
                    
                    while($lev = $levels2->fetch()){
                        $getModules = $conn->prepare("SELECT * FROM tbl_modules WHERE splz_id='".$splz."' AND status=1 AND level_id='".$lev['level_id']."' ORDER BY module_id ASC");
                        $getModules->execute();
                            
                        $theMods =$getModules->fetchAll();
                        foreach($theMods as $modId){
                        ?>
                        <th>Marks/100</th>
                        <?php
                    }
                }
                ?>
            </tr>
        </thead>									        
    </center>
    <tbody>
        <?php
            $counter=0;
            $students =$conn->prepare("SELECT DISTINCT(ug.reg_no), ad.fname, ad.lname FROM tbl_register_program_ug ug JOIN tbl_admission ad ON ug.reg_no = ad.reg_no WHERE ad.intake_id = :intake AND ug.splz_id = :splz AND ug.prg_mode_id = :mode");
            $students->bindParam(':intake', $intake, PDO::PARAM_INT);
            $students->bindParam(':splz', $splz, PDO::PARAM_INT);
            $students->bindParam(':mode', $mode, PDO::PARAM_INT);
            $students->execute();
            
            $data =$students->fetchAll();
            foreach($data as $element){
                $counter +=1;
        ?>
        <tr>
            <td><?php echo $counter;?></td>
            <td><?php echo $element['reg_no'];?></td>
            <td><?php echo $element['fname']." ".$element['lname']; ?></td>
            <?php
                $levels2 = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prgType."' AND status=1 ORDER BY level_id ASC");
                $levels2->execute();
                
                while($lev = $levels2->fetch()){
                    $getModules = $conn->prepare("SELECT * FROM tbl_modules WHERE splz_id='".$splz."' AND status=1 AND level_id='".$lev['level_id']."' ORDER BY module_id ASC");
                    $getModules->execute();
                    while($module = $getModules->fetch()){
                        $marks =$conn->prepare("SELECT marks FROM tbl_markby_module WHERE module_id = ? AND splz_id = ? AND reg_no = ? LIMIT 1");
                        $marks->execute([$module['module_id'], $splz, $element['reg_no']]);

                        $data=$marks->fetch();
                        
                        if($data['marks'] <50 || $data['marks'] === NULL){
                            $color = '#E5B39B';
                            $marks = 'NR';
                        }else{
                            $color = 'white';
                            $marks = $data['marks'];
                        }
                        
                ?>
                <td style="background-color: <?php echo $color; ?>">
                    <?php echo $marks; ?>
                </td>
                <?php } ?>
                <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table> 