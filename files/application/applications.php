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
                                                        <th>
                                                            <input type="checkbox" id="select-all-applicants">
                                                        </th>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Campus</th>
                                                        <th>Program</th>
                                                        <th>School</th>
                                                        <th>Department</th>
                                                        <th>Specialization</th>
                                                        
                                                        <th>Level</th>
                                                        <!--<th>Intake</th>-->
                                                        <th>Date created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    ini_set('display_errors', 1);
                                ini_set('display_startup_errors', 1);
                                error_reporting(E_ALL);
                                                        if($role_id == 2){
                                                            $query = "SELECT
                                                                                distinct(tbl_admittedPRG.Stu_code) as code,
                                                                                tbl_applicants.fname,
                                                                                tbl_applicants.lname,
                                                                                tbl_applicants.createdAt,
                                                                                
                                                                                tbl_campus.camp_full_name,
                                                                                tbl_program_type.prg_type_full_name,
                                                                                tbl_faculty.fac_full_name,
                                                                                tbl_department.dept_full_name,
                                                                                tbl_specialization.splz_full_name,
                                                                                tbl_program_mode.prg_mode_full_name,
                                                                                tbl_level.level_full_name
                                                                                
                                                                                
                                                                            FROM
                                                                                tbl_applicants
                                                                            INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                                            INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                                            INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                                                            INNER JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                            LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                            WHERE
                                                                                tbl_admittedPRG.sts = 2 AND tbl_applicants.submitted=2 GROUP BY tbl_admittedPRG.Stu_code";
                                                        }
                                                        if($role_id == 6){
                                                            $query = "SELECT
                                                                                distinct(tbl_admittedPRG.Stu_code) as code,
                                                                                tbl_applicants.fname,
                                                                                tbl_applicants.lname,
                                                                                tbl_applicants.createdAt,
                                                                                tbl_campus.camp_full_name,
                                                                                tbl_program_type.prg_type_full_name,
                                                                                tbl_faculty.fac_full_name,
                                                                                tbl_department.dept_full_name,
                                                                                tbl_specialization.splz_full_name,
                                                                                tbl_program_mode.prg_mode_full_name,
                                                                                tbl_level.level_full_name
                                                                            FROM
                                                                                tbl_applicants
                                                                            INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                                            INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                                            INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                                                            INNER JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                            LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                            WHERE tbl_applicants.submitted!=0 AND tbl_applicants.submitted!=10";
                                                        }
                                                        
                                                        $sql=$conn->prepare($query);
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                    ?>
                                                        <tr class="clickable-row" data-href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=">
                                                            <td>
                                                                <input type="checkbox" class="applicant-select" value="<?php echo htmlspecialchars($apps['code'], ENT_QUOTES, 'UTF-8'); ?>" data-tracking="<?php echo htmlspecialchars($apps['code'], ENT_QUOTES, 'UTF-8'); ?>" data-name="<?php echo htmlspecialchars($apps['fname'] . ' ' . $apps['lname'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            </td>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['camp_full_name'];?></td>
                                                            <td><?php echo $apps['prg_type_full_name'];?></td>
                                                            <td><?php echo $apps['fac_full_name'];?></td>
                                                            <td><?php echo $apps['dept_full_name'];?></td>
                                                            
                                                            <td><?php echo $apps['splz_full_name']; ?></td>
                                                            <td><?php echo $apps['level_full_name']; ?></td>
                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'])); ?></td>
                                                            <td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in="><i class="fa fa-eye"></i></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" id="archive-selected" class="btn btn-warning btn-sm m-20"><i class="fa fa-archive"></i>&nbsp;Archive Selected</button>
                                            <a href="/files/application/download_xls.php" class="btn btn-success btn-sm m-20"><i class="fa fa-download"></i>&nbsp;Excel</a>
                                        </div>
                                    </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
        </div>
<div class="modal fade" id="archiveApplicantsModal" tabindex="-1" role="dialog" aria-labelledby="archiveApplicantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveApplicantsModalLabel">Confirm Archive</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Review the selected applicants before archiving them.</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Tracking Number</th>
                                <th>Applicant Names</th>
                            </tr>
                        </thead>
                        <tbody id="selected-applicants-preview"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirm-archive-selected">Archive Selected</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            var link = $(this).data('href');
            window.location.href = link;
        });

        $('#select-all-applicants').on('change', function(){
            var isChecked = $(this).is(':checked');
            $('.applicant-select').prop('checked', isChecked);
        });

        $('#applications tbody').on('change', '.applicant-select', function(){
            var total = $('.applicant-select').length;
            var checked = $('.applicant-select:checked').length;
            $('#select-all-applicants').prop('checked', total > 0 && total === checked);
        });

        $('#archive-selected').on('click', function(){
            var selectedRows = $('.applicant-select:checked').map(function(){
                return {
                    code: $(this).val(),
                    tracking: $(this).data('tracking'),
                    name: $(this).data('name')
                };
            }).get();

            if (selectedRows.length === 0) {
                alert('Please select at least one applicant to archive.');
                return;
            }

            var previewHtml = '';
            selectedRows.forEach(function(applicant) {
                previewHtml += '<tr><td>' + applicant.tracking + '</td><td>' + applicant.name + '</td></tr>';
            });

            $('#selected-applicants-preview').html(previewHtml);
            $('#archiveApplicantsModal').modal('show');
        });

        $('#confirm-archive-selected').on('click', function(){
            var selectedCodes = $('.applicant-select:checked').map(function(){
                return $(this).val();
            }).get();

            if (selectedCodes.length === 0) {
                $('#archiveApplicantsModal').modal('hide');
                Swal.fire({
                    icon: 'warning',
                    title: 'No applicants selected',
                    text: 'Please select at least one applicant to archive.'
                });
                return;
            }

            $.ajax({
                url: '/files/application/archive_applicants.php',
                method: 'POST',
                dataType: 'json',
                data: { codes: selectedCodes },
                success: function(response) {
                    $('#archiveApplicantsModal').modal('hide');
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Archived',
                            text: response.message || 'Selected applicants were archived successfully.'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archive failed',
                            text: response.message || 'Unable to archive the selected applicants.'
                        });
                    }
                },
                error: function() {
                    $('#archiveApplicantsModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Request failed',
                        text: 'Unable to archive the selected applicants.'
                    });
                }
            });
        });

        $('#archiveApplicantsModal').on('hidden.bs.modal', function () {
            $('#selected-applicants-preview').empty();
        });

        $('#archiveApplicantsModal').on('show.bs.modal', function () {
            $('#confirm-archive-selected').prop('disabled', false);
        });
    });
</script>