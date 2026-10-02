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
                                        <h4>Applications Without Document</h4>
                                        <div class="card-header-action">
                                            <button class="btn btn-info btn-sm" id="selectAllBtn">Select All</button>
                                            <button class="btn btn-warning btn-sm ml-2" id="bulkNotifyBtn" disabled>Notify & Reset Selected (<span id="selectedCount">0</span>)</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning">
                                            <strong>Notice:</strong> This page shows applicants who have submitted applications but not uploaded required documents. You can notify them and reset their submission status to allow them to complete their application properly.
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="selectAll"></th>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT app.*,appdc.* FROM tbl_applicants app
                                                                                    LEFT JOIN tbl_application_doc appdc ON app.code = appdc.tracking_id
                                                                                    WHERE app.submitted = 1
                                                                                    AND appdc.upload_doc IS NULL;");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                           
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="student-checkbox"  
                                                                    data-code="<?php echo $apps['code']; ?>"
                                                                    data-email="<?php echo $apps['email']; ?>" 
                                                                    data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>"
                                                                    ></td>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['phone'] ?></td>
                                                            <td><?php echo $apps['email'] ?></td>
                                                            
                                                            
                                                            <td>
                                                                <button class="btn btn-warning btn-sm notify-single-btn" 
                                                                        data-code="<?php echo $apps['code']; ?>"
                                                                        data-email="<?php echo $apps['email']; ?>"
                                                                        data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>">
                                                                    Notify & Reset
                                                                </button>
                                                            </td>
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
        $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        // Handle select all functionality
        $('#selectAll').change(function() {
            $('.student-checkbox').prop('checked', this.checked);
            updateSelectedCount();
        });

        // Handle individual checkbox changes
        $(document).on('change', '.student-checkbox', function() {
            updateSelectedCount();
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            } else if ($('.student-checkbox:checked').length === $('.student-checkbox').length) {
                $('#selectAll').prop('checked', true);
            }
        });

        // Handle select all button
        $('#selectAllBtn').click(function() {
            $('#selectAll').click();
        });

        // Update selected count and button state
        function updateSelectedCount() {
            var selectedCount = $('.student-checkbox:checked').length;
            $('#selectedCount').text(selectedCount);
            $('#bulkNotifyBtn').prop('disabled', selectedCount === 0);
        }

        // Handle bulk notify and reset
        $('#bulkNotifyBtn').click(function() {
            var selectedApplicants = [];
            $('.student-checkbox:checked').each(function() {
                selectedApplicants.push({
                    code: $(this).data('code'),
                    email: $(this).data('email'),
                    name: $(this).data('name')
                });
            });

            if (selectedApplicants.length === 0) {
                alert('Please select at least one applicant.');
                return;
            }

            if (confirm('Are you sure you want to notify ' + selectedApplicants.length + ' applicant(s) and reset their submission status? This will allow them to resubmit their applications with proper documents.')) {
                notifyAndResetApplicants(selectedApplicants);
            }
        });

        // Handle single notify and reset
        $(document).on('click', '.notify-single-btn', function() {
            var code = $(this).data('code');
            var email = $(this).data('email');
            var name = $(this).data('name');

            if (confirm('Are you sure you want to notify ' + name + ' and reset their submission status?')) {
                notifyAndResetApplicants([{code: code, email: email, name: name}]);
            }
        });

        // Function to notify and reset applicants
        function notifyAndResetApplicants(applicants) {
            $.ajax({
                url: '../files/application/application_controller.php',
                type: 'POST',
                data: {
                    action: 'notify_and_reset_submission_status',
                    applicants: applicants
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#bulkNotifyBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                    $('.notify-single-btn').prop('disabled', true);
                },
                success: function(response) {
                    if (response.status == '200') {
                        alert(response.message);
                        location.reload(); // Reload to update the list
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                },
                complete: function() {
                    $('#bulkNotifyBtn').prop('disabled', false).html('Notify & Reset Selected (<span id="selectedCount">0</span>)');
                    $('.notify-single-btn').prop('disabled', false);
                }
            });
        }
    });
</script>