<?php
include ('../../meet/con.php');
?>
<hr/>
<table class="table table-hover table-sm" id="nationality_table_spec">
    <thead>
        <th>#</th>
        <th>First name (s)</th>
        <th>Last name</th>
        <th>Nationality</th>
        <th>Program type</th>
        <th>Specialization</th>
        <th>Level</th>
        <th>Status</th>
    </thead>
    <tbody>
        <?php
            $stmt=$conn->prepare("SELECT tbl_admission.fname,
                                         tbl_admission.lname,
                                         tbl_program_type.prg_type_full_name,
                                         tbl_specialization.splz_full_name,
                                         tbl_level.level_full_name,
                                         tbl_nationality.nationality,
                                         tbl_register_program_ug.reg_active,
                                         tbl_status.status_full_name
                                    FROM tbl_register_program_ug 
                                         INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                         INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                                         INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id
                                         INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                         INNER JOIN tbl_nationality ON tbl_admission.nationality=tbl_nationality.nat_id
                                         INNER JOIN tbl_status ON tbl_status.status_id=tbl_register_program_ug.reg_active
                                    WHERE 
                                        tbl_register_program_ug.prg_type='".$_REQUEST['prg_type']."' AND
                                        tbl_register_program_ug.fac_id='".$_REQUEST['fac_id']."' AND
                                        tbl_register_program_ug.dept_id='".$_REQUEST['dept_id']."' AND
                                        tbl_register_program_ug.splz_id='".$_REQUEST['splz_id']."' AND
                                        tbl_register_program_ug.level_id='".$_REQUEST['level_id']."' AND
                                        tbl_register_program_ug.acad_cycle_id='".$_REQUEST['acad_id']."'");
                                        
            $stmt->execute();
            $i=1;
            while($stud=$stmt->fetch()){
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $stud['fname']; ?></td>
                <td><?php echo $stud['lname']; ?></td>
                <td><?php echo $stud['nationality']; ?></td>
                <td><?php echo $stud['prg_type_full_name']; ?></td>
                <td><?php echo $stud['splz_full_name']; ?></td>
                <td><?php echo $stud['level_full_name']; ?></td>
                <td>
                    <?php 
                        if($stud['reg_active']==1){
                            echo "<span class='badge badge-success'>".$stud['status_full_name']."</span>"; 
                        }
                        else{
                            echo "<span class='badge badge-warning'>".$stud['status_full_name']."</span>"; 
                        }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php if($stmt->rowCount()>0){ ?>
<div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
    <button type="button" class="btn btn-success" onclick="exportTableToExcel('nationality_table_spec','report_by_nationality')"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>
<?php } ?>