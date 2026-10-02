<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Attendance List.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    
    require('../../meet/con.php');
    $splz =$_REQUEST['splz'];
    $acad_cycle_id = $_REQUEST['acad'];
    $module = $_REQUEST['modl'];
    $mode = $_REQUEST['md'];

    $getClassData=$conn->prepare("SELECT 
                                        c.camp_full_name,
                                        p.prg_type_full_name,
                                        f.fac_full_name,
                                        d.dept_full_name,
                                        s.splz_full_name,
                                        m.module_code,
                                        m.module_name
                                        
                                    FROM tbl_specialization s
                                        INNER JOIN tbl_department d ON 
                                            s.dept_id=d.dept_id
                                        INNER JOIN tbl_faculty f ON 
                                            s.fac_id=f.fac_id
                                        INNER JOIN tbl_program_type p ON 
                                            s.prg_type=p.prg_type_id
                                        INNER JOIN tbl_campus c ON 
                                            p.campus_id=c.camp_id
                                        INNER JOIN tbl_modules tm ON 
                                            s.splz_id=tm.splz_id AND tm.module_id='".$module."'
                                        INNER JOIN modules m ON 
                                            tm.mod_id=m.module_id
                                    WHERE 
                                    s.splz_id='".$splz."'");
    $getClassData->execute();
    $class_data = $getClassData->fetch();
    
    $getIntakeData=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    $getIntakeData->execute([$acad_cycle_id]);
    $intake_data = $getIntakeData->fetch();
?>

<div class="table-responsive">
<table class="table table-hover table-sm" border="1">
    <thead>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Faculty: <span style="color: blue;"><b><?php  echo " ".$class_data['fac_full_name'] ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Department: <span style="color: blue;"><b><?php  echo " ".$class_data['dept_full_name'] ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Specialization: <span style="color: blue;"><b><?php  echo " ".$class_data['splz_full_name'] ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Module Name: <span style="color: blue;"><b><?php  echo " ".$class_data['module_code'] ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Module Code: <span style="color: blue;"><b><?php  echo " ".$class_data['module_name'] ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="13"><h3 style="text-align:left">Academic Year: <span style="color: blue;"><b><?php  echo " ".$intake_data['acad_year'] ?></b></span></h3></th></tr>
        <tr>
            <th scope="col"rowspan="2">S/N</th>
            <th scope="col"rowspan="2">STUDENT ID</th>
            <th scope="col"rowspan="2">STUDENT NAME</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="10">SESSION</th>
        </tr>
        <tr>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">1</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">2</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">3</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">4</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">5</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">6</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">7</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">8</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">9</th>
            <th scope="col" class="d-none d-sm-table-cell" style="width: 100px;">10</th>
        </tr>
    </thead>
    <tbody id="contents">
    <?php
        $getStudents = $conn->prepare("SELECT 
                                            DISTINCT(tbl_register_program_ug.reg_no),
                                            tbl_admission.fname,
                                            tbl_admission.lname
                                                FROM tbl_markby_module
                                            INNER JOIN tbl_register_program_ug ON tbl_markby_module.reg_no=tbl_register_program_ug.reg_no
                                            INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                                WHERE tbl_markby_module.module_id='".$module."' 
                                                    AND tbl_admission.acad_cycle_id='".$acad_cycle_id."'
                                                    AND tbl_register_program_ug.splz_id='".$splz."' 
                                                    AND tbl_register_program_ug.prg_mode_id='".$mode."'
                                                    AND tbl_register_program_ug.reg_active=1
                                                    AND (tbl_markby_module.marks IS NULL OR tbl_markby_module.marks=0)
                                                    AND tbl_markby_module.enrolled=1
                                                    AND tbl_markby_module.status=1
                                                ");
                                                
        $getStudents->execute();
        $i=1;
        while($student=$getStudents->fetch()){
    ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $student['reg_no']; ?></td>
            <td><?php echo $student['fname']." ".$student['lname']; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>