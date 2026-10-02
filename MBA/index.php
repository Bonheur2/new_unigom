<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Student Applicant</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Student Applicant</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                <label>Program Types</label>
                                <select class="form-control" style="width:100%" name="prg_type" id="prg_type">
                                    <option disabled selected>--choose one--</option>
                                    <?php
                                            $sql_prg=$conn->prepare("SELECT 
                                                            tbl_program_type.*,
                                                            tbl_campus.camp_full_name 
                                                                FROM tbl_program_type 
                                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                ORDER BY tbl_program_type.status ASC");
                                            $sql_prg->execute();
                                            while($progs_faculty=$sql_prg->fetch()){
                                        ?>
                                    <option value="<?php echo $progs_faculty['prg_type_id']; ?>">
                                        <?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-12" style="display: flex; flex-direction: row-reverse">
                                <button type="button" id="loadDataBtn" class="btn btn-icon btn-primary" hidden>Load
                                    Data</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="row" id="results_div" hidden>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
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
                                            <th>Applicant Names</th>
                                            <th>Campus</th>
                                            <th>Program</th>
                                            <th>School</th>
                                            <th>Department</th>
                                            <th>Specialization</th>
                                            <th>Level</th>
                                            <th>Date Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="applicants_body">
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

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {

    // Show Load Data button when program type is selected
    $(document).on('change', '#prg_type', function() {
        if ($(this).val()) {
            $('#loadDataBtn').prop('hidden', false);
            $('#results_div').prop('hidden', true);
        }
    });

    // Load Data button click → fetch applicants
    $(document).on('click', '#loadDataBtn', function() {
        var prg_type = $('#prg_type').val();

        if (!prg_type) {
            swal("Warning", "Please select a Program Type first.", "warning");
            return;
        }

        // Destroy existing DataTable if initialized
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#applications')) {
            $('#applications').DataTable().destroy();
        }
        $('#applicants_body').empty();
        $('#results_div').prop('hidden', true);

        $('#loadDataBtn').html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled',
            true);

        $.ajax({
            type: "POST",
            url: "controller.php",
            data: {
                prg_type: prg_type,
                action: 'load-applicants'
            },
            dataType: "json",
            success: function(data) {
                $('#loadDataBtn').html('Load Data').prop('disabled', false);
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        var createdAt = value.createdAt ? value.createdAt : '';
                        var row =
                            '<tr class="clickable-row" data-href="review.php?app=' +
                            value.code + '">' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.code + '</td>' +
                            '<td>' + value.fname + ' ' + value.lname + '</td>' +
                            '<td>' + value.camp_full_name + '</td>' +
                            '<td>' + value.prg_type_full_name + '</td>' +
                            '<td>' + value.fac_full_name + '</td>' +
                            '<td>' + value.dept_full_name + '</td>' +
                            '<td>' + value.splz_full_name + '</td>' +
                            '<td>' + (value.level_full_name || '') + '</td>' +
                            '<td>' + createdAt + '</td>' +
                            '<td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=' +
                            value.code +
                            '"><i class="fa fa-eye"></i></a></td>' +
                            '</tr>';
                        $('#applicants_body').append(row);
                    });
                    $('#results_div').prop('hidden', false);

                    // Initialize DataTable if available
                    if ($.fn.DataTable) {
                        $('#applications').DataTable({
                            "aLengthMenu": [
                                [25, 50, 100, -1],
                                [25, 50, 100, "All"]
                            ],
                            "iDisplayLength": 25
                        });
                    }

                    // Make rows clickable
                    $(document).on('click', '.clickable-row', function() {
                        window.location.href = $(this).data('href');
                    });
                } else {
                    swal("Info", "No applicants found for the selected criteria.", "info");
                }
            },
            error: function(xhr) {
                $('#loadDataBtn').html('Load Data').prop('disabled', false);
                console.log(xhr.responseText);
                swal("Error", "Something went wrong!", "error");
            }
        });
    });

});
</script>