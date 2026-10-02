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
                                                    <th>Application Code</th>
                                                    <th>Applicant Name</th>
                                                    <th>Campus</th>
                                                    <th>Programme Type</th>
                                                    <th>1st Choice Department</th>
                                                    <th>Status</th>
                                                    <th>Date Submitted</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = $conn->prepare("SELECT tbl_applicants.applicant_id, tbl_applicants.application_code,
                                                                                  tbl_applicants.fname, tbl_applicants.lname,
                                                                                  tbl_applicants.status, tbl_applicants.created_at,
                                                                                  tbl_campus.camp_full_name,
                                                                                  tbl_program_type.prg_type_full_name,
                                                                                  tbl_department.dept_full_name
                                                                            FROM tbl_applicants
                                                                            LEFT JOIN tbl_campus ON tbl_campus.camp_id = tbl_applicants.camp_id
                                                                            LEFT JOIN tbl_program_type ON tbl_program_type.prg_type_id = tbl_applicants.prg_type_id
                                                                            LEFT JOIN tbl_department ON tbl_department.dept_id = tbl_applicants.dept_choice_1
                                                                            ORDER BY tbl_applicants.applicant_id DESC");
                                                    $sql->execute();
                                                    $i = 1;
                                                    $status_badge = [
                                                        'pending' => 'badge-warning',
                                                        'verified' => 'badge-info',
                                                        'accepted' => 'badge-success',
                                                        'rejected' => 'badge-danger'
                                                    ];
                                                    while($apps = $sql->fetch()):
                                                ?>
                                                <tr class="clickable-row" data-href="review?app=<?php echo $apps['applicant_id']; ?>">
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo htmlspecialchars($apps['application_code']); ?></td>
                                                    <td><?php echo htmlspecialchars($apps['fname'].' '.$apps['lname']); ?></td>
                                                    <td><?php echo htmlspecialchars($apps['camp_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($apps['prg_type_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($apps['dept_full_name'] ?? ''); ?></td>
                                                    <td><span class="badge <?php echo $status_badge[$apps['status']] ?? 'badge-secondary'; ?>"><?php echo ucfirst($apps['status']); ?></span></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($apps['created_at'])); ?></td>
                                                    <td><a class="btn btn-primary btn-sm" href="edu?mis=review&app=<?php echo $apps['applicant_id']; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                                                </tr>
                                                <?php endwhile; ?>
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
        $('#applications').DataTable({
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        $('#applications tbody').on('click', '.clickable-row', function(e){
            if ($(e.target).is('input, button, a, label')) {
                return;
            }
            window.location.href = $(this).data('href');
        });
    });
</script>
