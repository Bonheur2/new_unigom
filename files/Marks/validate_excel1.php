<?php
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=Marks.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

include "../../meet/con.php";

$intake = $_REQUEST['intake'];
$splz = $_REQUEST['splz'];
$mode = $_REQUEST['mode'];
$level = $_REQUEST['level'];
$prgType = $_REQUEST['prg_type'];

$modules = array();

$campus = $conn->prepare("SELECT c.camp_full_name FROM tbl_campus c INNER JOIN tbl_program_type p ON c.camp_id = p.campus_id WHERE p.prg_type_id = :type");
$campus->bindParam(':type', $prgType, PDO::PARAM_INT);
$campus->execute();
$theCampus = $campus->fetch(PDO::FETCH_ASSOC);

$getModules = $conn->prepare("SELECT module_id FROM tbl_modules WHERE splz_id = :splz AND level_id = :level AND status = 1");
$getModules->bindParam(':splz', $splz, PDO::PARAM_INT);
$getModules->bindParam(':level', $level, PDO::PARAM_INT);
$getModules->execute();

while ($mods = $getModules->fetch(PDO::FETCH_ASSOC)) {
    $modules[] = $mods['module_id'];
}
?>

<table>
    <tr>
        <td>Campus</td>
        <td><?php echo htmlspecialchars($theCampus['camp_full_name']); ?></td>  
    </tr>
    <br>
    <tr>
        <td>Specialization</td>
        <td>
            <?php 
                $spec = $conn->prepare("SELECT * FROM tbl_specialization WHERE splz_id = :splz");
                $spec->bindParam(':splz', $splz, PDO::PARAM_INT);
                $spec->execute();
                $theSpec = $spec->fetch(PDO::FETCH_ASSOC);
                echo htmlspecialchars($theSpec['splz_full_name']);
            ?> 
        </td>  
    </tr>
    <br>
    <tr>
        <td>Intake</td>
        <td>
            <?php 
            $intakeLabel = $conn->prepare("SELECT i.intake_id, i.intake_month, a.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id = a.acad_cycle_id WHERE i.intake_id = :intake");
            $intakeLabel->bindParam(':intake', $intake, PDO::PARAM_INT);
            $intakeLabel->execute();
            $theIntake = $intakeLabel->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($theIntake['intake_month']) . " | " . htmlspecialchars($theIntake['acad_year']);
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td>Level</td>
        <td>
            <?php 
            $levelLabel = $conn->prepare("SELECT * FROM tbl_level WHERE level_id = :level AND prg_type = :type");
            $levelLabel->bindParam(':type', $prgType, PDO::PARAM_INT);
            $levelLabel->bindParam(':level', $level, PDO::PARAM_INT);
            $levelLabel->execute();
            $theLevel = $levelLabel->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($theLevel['level_full_name']);
            ?>
        </td>  
    </tr>
    <br>
    <tr>
        <td>Program</td>
        <td>
        <?php 
            $prgModeLabel = $conn->prepare("SELECT * FROM tbl_program_mode WHERE prg_mode_id = :modeId");
            $prgModeLabel->bindParam(':modeId', $mode, PDO::PARAM_INT);
            $prgModeLabel->execute();
            $theMode = $prgModeLabel->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($theMode['prg_mode_full_name']);
            ?>
        </td>  
    </tr>
</table>
<table border="1">
    <thead>
        <tr>    
            <th rowspan="2"><?php echo "S/N"; ?></th>
            <th rowspan="2"><?php echo "Identification"; ?></th>
            <th rowspan="2"><?php echo "Names"; ?></th>
            <?php
            for ($counter = 0; $counter < count($modules); $counter++) {
                $module = $modules[$counter];
                $modulesInfo = $conn->prepare("SELECT * FROM tbl_modules WHERE module_id = :mod AND prg_type = :prgType AND level_id = :level AND splz_id = :splz");
                $modulesInfo->bindParam(':mod', $module, PDO::PARAM_INT);
                $modulesInfo->bindParam(':prgType', $prgType, PDO::PARAM_INT);
                $modulesInfo->bindParam(':level', $level, PDO::PARAM_INT);
                $modulesInfo->bindParam(':splz', $splz, PDO::PARAM_INT);
                $modulesInfo->execute();
                
                $theMods = $modulesInfo->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($theMods as $modId) {
                    $moduleName = $conn->prepare("SELECT * FROM modules WHERE module_id = :module");
                    $moduleName->bindParam(':module', $modId['module_id'], PDO::PARAM_INT);
                    $moduleName->execute();
                    
                    $theName = $moduleName->fetch(PDO::FETCH_ASSOC);
            ?>
            <th colspan="3"><?php echo htmlspecialchars($theName['module_name']) . " [" . htmlspecialchars($theName['module_code']) . ", " . htmlspecialchars($modId['module_credits']) . "]"; ?></th>
            <?php
                }
            }
            ?>
        </tr>
        <tr>
            <?php for ($i = 0; $i < count($modules); $i++) { ?>
            <th>CAT</th>
            <th>EXAM</th>
            <th>Total/100</th>
            <?php } ?>
        </tr>
    </thead>									        
    <tbody>
        <?php
        $counter = 0;
        $students =$conn->prepare("SELECT ug.*, ad.fname, ad.lname FROM tbl_register_program_ug ug JOIN tbl_admission ad ON ug.reg_no = ad.reg_no WHERE ad.intake_id = :intake  AND ug.level_id = :level AND ug.splz_id = :splz AND ug.prg_mode_id = :mode");
        $students->bindParam(':intake', $intake, PDO::PARAM_INT);
        $students->bindParam(':level', $level, PDO::PARAM_INT);
        $students->bindParam(':splz', $splz, PDO::PARAM_INT);
        $students->bindParam(':mode', $mode, PDO::PARAM_INT);
        $students->execute();
        
        $data = $students->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $element) {
            $counter++;
        ?>
        <tr>
            <td><?php echo $counter; ?></td>
            <td><?php echo htmlspecialchars($element['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($element['fname']) . " " . htmlspecialchars($element['lname']); ?></td>
            <?php
            for ($i = 0; $i < count($modules); $i++) {
                $stu = $element['reg_no'];
                $marks = $conn->prepare("SELECT cat, final_exam, marks FROM tbl_markby_module WHERE module_id = ? AND splz_id = ? AND reg_no = ? LIMIT 1");
                $marks->execute([$modules[$i], $splz, $stu]);
                $data = $marks->fetch(PDO::FETCH_ASSOC);
            ?>
            <td><?php echo htmlspecialchars($data['cat']); ?></td>
            <td><?php echo htmlspecialchars($data['final_exam']); ?></td>
            <td><?php echo htmlspecialchars($data['marks']); ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>