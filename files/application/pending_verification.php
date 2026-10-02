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
                                        <h4>Pending Verifications</h4>
                                        <div class="card-header-action">
                                            <button class="btn btn-info btn-sm" id="selectAllBtn">Select All</button>
                                            <button class="btn btn-primary btn-sm ml-2" id="bulkSendBtn" disabled>Send Bulk Email (<span id="selectedCount">0</span>)</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
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
                                                        <th>Date created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT app.*
                                                                                    FROM tbl_applicants app
                                                                                    LEFT JOIN tbl_student_login stl ON app.code = stl.Identification
                                                                                    WHERE app.submitted = 0
                                                                                    AND stl.Identification IS NULL;
                                                                                    ");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="student-checkbox" data-email="<?php echo $apps['email']; ?>" data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>"></td>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['phone'] ?></td>
                                                            <td><?php echo $apps['email'] ?></td>
                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'] . '+2 hours')); ?></td>
                                                            <td>
                                                                <a class="btn btn-primary btn-sm" href="edu?mis=notify&app=<?php echo $apps['code']; ?>">Notify</a>
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

<!-- Email Edit Modal -->
<div class="modal fade" id="emailEditModal" tabindex="-1" role="dialog" aria-labelledby="emailEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailEditModalLabel">Edit Email Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="emailEditForm">
                    <div class="form-group">
                        <label for="currentEmail">Current Email:</label>
                        <input type="email" class="form-control" id="currentEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label for="newEmail">New Email Address:</label>
                        <input type="email" class="form-control" id="newEmail" placeholder="Enter new email address" required>
                        <small class="form-text text-muted">Please enter a valid email address.</small>
                    </div>
                    <input type="hidden" id="applicantCode" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateEmailBtn">Update & Send Code</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        // Handle select all checkbox
        $('#selectAll').change(function() {
            $('.student-checkbox').prop('checked', this.checked);
            updateBulkSendButton();
        });

        // Handle individual checkboxes
        $(document).on('change', '.student-checkbox', function() {
            updateBulkSendButton();
            
            // Update select all checkbox state
            var totalCheckboxes = $('.student-checkbox').length;
            var checkedCheckboxes = $('.student-checkbox:checked').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        });

        // Handle select all button
        $('#selectAllBtn').click(function() {
            var allChecked = $('.student-checkbox:checked').length === $('.student-checkbox').length;
            $('.student-checkbox').prop('checked', !allChecked);
            $('#selectAll').prop('checked', !allChecked);
            updateBulkSendButton();
        });

        // Handle bulk send button
        $('#bulkSendBtn').click(function() {
            var selectedEmails = [];
            $('.student-checkbox:checked').each(function() {
                selectedEmails.push($(this).data('email'));
            });

            if (selectedEmails.length === 0) {
                alert('Please select at least one student.');
                return;
            }

            if (confirm('Are you sure you want to send verification emails to ' + selectedEmails.length + ' students?')) {
                sendBulkEmails(selectedEmails, this);
            }
        });

        // Handle email update form submission
        $('#updateEmailBtn').click(function() {
            var currentEmail = $('#currentEmail').val();
            var newEmail = $('#newEmail').val();
            var applicantCode = $('#applicantCode').val();
            
            if (!newEmail || !validateEmail(newEmail)) {
                alert('Please enter a valid email address.');
                return;
            }

            if (newEmail === currentEmail) {
                alert('New email must be different from current email.');
                return;
            }

            // Disable button and show loading state
            $(this).prop('disabled', true).text('Updating...');
            
            $.ajax({
                url: '../files/application/application_controller.php',
                type: 'POST',
                data: {
                    old_email: currentEmail,
                    new_email: newEmail,
                    applicant_code: applicantCode,
                    action: 'update_email_and_resend',
                    verification_link: 'https://misnjala.edu.sl/verify_email?em=' + newEmail
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status == '200') {
                        alert('Email updated and verification code sent to new email successfully!');
                        // Update the email display in the table row
                        $('button[data-code="' + applicantCode + '"]').closest('tr').find('td:eq(4)').text(newEmail);
                        $('#emailEditModal').modal('hide');
                    } else {
                        alert('Error: ' + response.message);
                    }
                    $('#updateEmailBtn').prop('disabled', false).text('Update & Send Code');
                },
                error: function() {
                    alert('An error occurred while updating the email.');
                    $('#updateEmailBtn').prop('disabled', false).text('Update & Send Code');
                }
            });
        });

        // Reset modal when closed
        $('#emailEditModal').on('hidden.bs.modal', function () {
            $('#emailEditForm')[0].reset();
            $('#updateEmailBtn').prop('disabled', false).text('Update & Send Code');
        });
    });

    function updateBulkSendButton() {
        var checkedCount = $('.student-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        $('#bulkSendBtn').prop('disabled', checkedCount === 0);
    }

    function sendBulkEmails(emails, button) {
        $(button).prop('disabled', true).html('Sending... <span class="spinner-border spinner-border-sm" role="status"></span>');
        
        var totalEmails = emails.length;
        var processedEmails = 0;
        var successCount = 0;
        var failedEmails = [];

        emails.forEach(function(email, index) {
            setTimeout(function() {
                $.ajax({
                    url: '../files/application/application_controller.php',
                    type: 'POST',
                    data: {
                        email: email,
                        action: 'resend_verification',
                        verification_link: 'https://misnjala.edu.sl/verify_email?em=' + email,
                        bulk_send: true
                    },
                    dataType: 'json',
                    success: function(response) {
                        processedEmails++;
                        if(response.status == '200') {
                            successCount++;
                        } else {
                            failedEmails.push(email);
                        }
                        
                        // Update progress
                        $(button).html('Sending... (' + processedEmails + '/' + totalEmails + ') <span class="spinner-border spinner-border-sm" role="status"></span>');
                        
                        // Check if all emails processed
                        if (processedEmails === totalEmails) {
                            var message = successCount + ' emails sent successfully.';
                            if (failedEmails.length > 0) {
                                message += '\n' + failedEmails.length + ' emails failed to send.';
                            }
                            alert(message);
                            
                            // Reset button and checkboxes
                            $(button).prop('disabled', false).html('Send Bulk Email (<span id="selectedCount">0</span>)');
                            $('.student-checkbox').prop('checked', false);
                            $('#selectAll').prop('checked', false);
                            updateBulkSendButton();
                        }
                    },
                    error: function() {
                        processedEmails++;
                        failedEmails.push(email);
                        
                        if (processedEmails === totalEmails) {
                            alert('Bulk send completed with errors. Please check individual results.');
                            $(button).prop('disabled', false).html('Send Bulk Email (<span id="selectedCount">0</span>)');
                            $('.student-checkbox').prop('checked', false);
                            $('#selectAll').prop('checked', false);
                            updateBulkSendButton();
                        }
                    }
                });
            }, index * 1000); // 1 second delay between each email
        });
    }

    function resendCode(email, button) {
        // Disable button and show loading state
        $(button).prop('disabled', true).text('Sending...');
        
        $.ajax({
            url: '../files/application/application_controller.php',
            type: 'POST',
            data: {
                email: email,
                action: 'resend_verification',
                verification_link: 'https://misnjala.edu.sl/verify_email?em=' + email
            },
            dataType: 'json',
            success: function(response) {
                if(response.status == '200') {
                    alert('Verification code resent successfully!');
                    $(button).removeClass('btn-success').addClass('btn-secondary').text('Code Sent');
                } else {
                    alert('Error: ' + response.message);
                    $(button).prop('disabled', false).text('Resend Code');
                }
            },
            error: function() {
                alert('An error occurred while sending the verification code.');
                $(button).prop('disabled', false).text('Resend Code');
            }
        });
    }

    function editEmail(currentEmail, applicantCode, button) {
        // Populate modal with current data
        $('#currentEmail').val(currentEmail);
        $('#newEmail').val('');
        $('#applicantCode').val(applicantCode);
        
        // Add data attribute to button for reference
        $(button).attr('data-code', applicantCode);
        
        // Show modal
        $('#emailEditModal').modal('show');
    }

    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
</script>