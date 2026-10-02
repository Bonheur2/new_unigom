<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Admitted applicants</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">List</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Registration Number</th>
                                                        <th>Program</th>
                                                        <th>School</th>
                                                        <th>Department</th>
                                                        <th>Specialization</th>
                                                        <th>Level</th>
                                                        <th>Campus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT
                                                                                tbl_register_program_ug.reg_no,
                                                                                tbl_program_type.prg_type_full_name,
                                                                                tbl_faculty.fac_full_name,
                                                                                tbl_department.dept_full_name,
                                                                                tbl_specialization.splz_full_name,
                                                                                tbl_level.level_full_name
                                                                                
                                                                            
                                                                            FROM
                                                                                tbl_register_program_ug
                                                                            INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                                                            INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                                                            INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                                                            INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                                                            
                                                                            WHERE
                                                                                tbl_register_program_ug.reg_active = 1 AND tbl_register_program_ug.acad_cycle_id='10'");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                        $cpmQuery=$conn->prepare("SELECT tbl_campus.camp_full_name FROM tbl_campus 
                                                        INNER JOIN tbl_users ON tbl_users.campus_id=tbl_campus.camp_id 
                                                        WHERE tbl_users.Identification='".$apps['reg_no']."'");
                                                        $cpmQuery->execute();
                                                        $camp_result=$cpmQuery->fetch();
                                                        
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['reg_no']; ?></td>
                                                            <td><?php echo $apps['prg_type_full_name']; ?></td>
                                                            <td><?php echo $apps['fac_full_name']; ?></td>
                                                            <td><?php echo $apps['dept_full_name']; ?></td>
                                                            <td><?php echo $apps['splz_full_name']; ?></td>
                                                            <td><?php echo $apps['level_full_name'];?></td>
                                                            <td><?php echo $camp_result['camp_full_name']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#applications').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
});
</script>