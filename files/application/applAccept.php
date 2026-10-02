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
                                        <h4>Submitted Applications</h4>
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
                                                        <th>Date created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                            $query = "SELECT
                                                                                distinct(tbl_admittedPRG.Stu_code) as code,
                                                                                tbl_admittedPRG.acad_id,
                                                                                tbl_applicants.fname,
                                                                                tbl_applicants.lname,
                                                                                tbl_applicants.createdAt,
                                                                                tbl_specialization.splz_full_name,
                                                                                tbl_campus.camp_full_name
                                                                            FROM
                                                                                tbl_applicants
                                                                            INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.dept_id = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id=tbl_campus.camp_id 
                                                                            WHERE
                                                                            tbl_admittedPRG.sts = 3 AND tbl_applicants.submitted=2 AND tbl_admittedPRG.student_decision='accepted' ";
                                          
                                                       
                                                        
                                                        $sql=$conn->prepare($query);
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                    ?>
                                                        <tr class="clickable-row" data-href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=<?php echo $apps['acad_id']; ?>">
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['camp_full_name'];?></td>
                                                            <td><?php echo $apps['splz_full_name']; ?></td>
                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'])); ?></td>
                                                            <td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=<?php echo $apps['acad_id']; ?>"><i class="fa fa-eye"></i></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer text-right">
                                            <a href="/files/application/Accepteddownload_xls.php" class="btn btn-success btn-sm m-20"><i class="fa fa-download"></i>&nbsp;Excel</a>
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