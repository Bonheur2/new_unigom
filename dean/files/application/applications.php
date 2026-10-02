<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Applications</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Applicants</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Submitted Applications </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Campus</th>
                                                        <th>Program</th>
                                                        <th>School</th>
                                                        <th>Department</th>
                                                        <!--<th>Specialization</th>-->
                                                        <th>Level</th>
                                                        
                                                        <th>Study Mode</th>
                                                        <th>Academic Year</th>
                                                        <th>Date created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                       $query = "SELECT
                                                                        distinct(tbl_admittedPRG.Stu_code) as code,
                                                                        tbl_admittedPRG.sts,
                                                                        tbl_applicants.fname,
                                                                        tbl_applicants.lname,
                                                                        tbl_applicants.createdAt,
                                                                        tbl_campus.camp_full_name,
                                                                        tbl_program_type.prg_type_full_name,
                                                                        tbl_faculty.fac_full_name,
                                                                        tbl_department.dept_full_name,
                                                                        tbl_specialization.splz_full_name,
                                                                        tbl_program_mode.prg_mode_full_name,
                                                                        tbl_level.level_full_name,
                                                                        tbl_acad_cycle.acad_year
                                                                            FROM
                                                                                tbl_applicants
                                                                            INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                                            INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                                            INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                                                                            INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                                                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                            INNER JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                            INNER JOIN tbl_acad_cycle ON tbl_admittedPRG.acad_id = tbl_acad_cycle.acad_cycle_id 
                                                                            WHERE tbl_applicants.submitted!=0  AND tbl_specialization.fac_id IN ($faculty) GROUP BY tbl_admittedPRG.Stu_code ";
                                                     
                                                        
                                                        $sql=$conn->prepare($query);
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                    ?>
                                                        <tr class="clickable-row" data-href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=<?php echo $apps['intake_id']; ?>">
                                                            <td>
                                                             <?php if($apps['sts']==2 || $apps['sts']==3 ){
                                                             ?>
                                                             <span class="badge badge-secondary"><?php echo $i++; ?></span>
                                                             <?php }
                                                             else{
                                                               echo $i++;  
                                                             }
                                                             ?>
                                                              </td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['camp_full_name'];?></td>
                                                            <td><?php echo $apps['prg_type_full_name'];?></td>
                                                            <td><?php echo $apps['fac_full_name'];?></td>
                                                            <td><?php echo $apps['dept_full_name'];?></td>
                                                            
                                                            <!--<td><?php echo $apps['splz_full_name']; ?></td>-->
                                                            <td><?php echo $apps['level_full_name']; ?></td>
                                                            <td><?php echo $apps['prg_mode_full_name']; ?></td>
                                                            <td><?php echo $apps['acad_year']; ?></td>
                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'])); ?></td>
                                                            <td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=<?php echo $apps['intake_id']; ?>"><i class="fa fa-eye"></i></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer text-right">
                                            <a href="../dean/files/application/download_exl.php?fac=<?php echo $fac_id ?>" class="btn btn-success btn-sm m-20"><i class="fa fa-download"></i>&nbsp;Excel</a>
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
        $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });
        
        $('.clickable-row').on('click', function(){
            var link = $(this).data('href');
            window.location.href = link;
        })
    });
</script>