<?php
    require('../../meet/con.php');

    $prg_type = isset($_REQUEST['prg_type']) ? $_REQUEST['prg_type'] : '';
    $fac_id = isset($_REQUEST['fac_id']) ? $_REQUEST['fac_id'] : '';
    $dept_id = isset($_REQUEST['dept_id']) ? $_REQUEST['dept_id'] : '';
    $splz_id = isset($_REQUEST['splz_id']) ? $_REQUEST['splz_id'] : '';
    $level_id = isset($_REQUEST['level_id']) ? $_REQUEST['level_id'] : '';
    $acad_cycle_id = isset($_REQUEST['acad_cycle_id']) ? $_REQUEST['acad_cycle_id'] : '';

    // Get Department Name
    $getDeptName = $conn->prepare("SELECT splz_full_name FROM tbl_specialization WHERE splz_id = ?");
    $getDeptName->execute([$splz_id]);
    $dept_data = $getDeptName->fetch();
    $dept_name = $dept_data['splz_full_name'];

    // Get Level Name
    $getLevelName = $conn->prepare("SELECT level_full_name FROM tbl_level WHERE level_id = ?");
    $getLevelName->execute([$level_id]);
    $level_data = $getLevelName->fetch();
    $level_name = $level_data['level_full_name'];

    // Sanitize file name
    $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $dept_name . '_' . $level_name) . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$fileName");  
    header("Pragma: no-cache"); 
    header("Expires: 0");

    // Get class data using prepared statement
    $getClassData = $conn->prepare("
        SELECT 
            c.camp_full_name,
            p.prg_type_full_name,
            f.fac_full_name,
            d.dept_full_name,
            s.splz_full_name
        FROM tbl_specialization s
        INNER JOIN tbl_department d ON s.dept_id = d.dept_id
        INNER JOIN tbl_faculty f ON s.fac_id = f.fac_id
        INNER JOIN tbl_program_type p ON s.prg_type = p.prg_type_id
        INNER JOIN tbl_campus c ON p.campus_id = c.camp_id
        WHERE s.splz_id = ?
    ");
    $getClassData->execute([$splz_id]);
    $class_data = $getClassData->fetch();
    
    // Get intake data
    $getIntakeData = $conn->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    $getIntakeData->execute([$acad_cycle_id]);
    $intake_data = $getIntakeData->fetch();
?>

<div class="table-responsive">
<table class="table table-hover table-sm" border="1">
    <thead>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Campus: <span style="color: blue;"><b><?php echo htmlspecialchars($class_data['camp_full_name']); ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Faculty: <span style="color: blue;"><b><?php echo htmlspecialchars($class_data['fac_full_name']); ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Department: <span style="color: blue;"><b><?php echo htmlspecialchars($class_data['dept_full_name']); ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Specialization: <span style="color: blue;"><b><?php echo htmlspecialchars($class_data['splz_full_name']); ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Academic Year: <span style="color: blue;"><b><?php echo htmlspecialchars($intake_data['acad_year']); ?></b></span></h3></th></tr>
        <tr>
            <th scope="col" >S/N</th>
            <th scope="col" >STUDENT ID</th>
            <th scope="col" >STUDENT NAME</th>
        </tr>
    </thead>
    <tbody id="contents">
    <?php
        // Get students data securely
        $getStudents = $conn->prepare("
            SELECT tbl_register_program_ug.*, tbl_admission.* 
            FROM tbl_register_program_ug 
            INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no = tbl_admission.reg_no
            WHERE 
                tbl_register_program_ug.acad_cycle_id = ? AND 
                tbl_register_program_ug.splz_id = ? AND 
                tbl_register_program_ug.level_id = ? AND 
                tbl_register_program_ug.prg_type = ? AND 
                tbl_register_program_ug.fac_id = ? AND 
                tbl_register_program_ug.dept_id = ?
        ");
        $getStudents->execute([$acad_cycle_id, $splz_id, $level_id, $prg_type, $fac_id, $dept_id]);
        
        $i = 1;
        while ($student = $getStudents->fetch()) {
    ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($student['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($student['fname'] . " " . $student['lname']); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
