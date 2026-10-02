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
                                        <h4>Verified Applicants - Create Accounts</h4>
                                        <div class="card-header-action">
                                            <button class="btn btn-info btn-sm" id="selectAllBtn">Select All</button>
                                            <button class="btn btn-success btn-sm ml-2" id="bulkCreateAccountBtn" disabled>Create Accounts for Selected (<span id="selectedCount">0</span>)</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <strong>Info:</strong> This page shows verified applicants who don't have student accounts yet. You can create their login accounts to allow them to access the system.
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
                                                        $sql=$conn->prepare("SELECT 
    app.*, 
    ef.email AS verified_email, 
    ef.token, 
    app.email AS original_email
FROM tbl_applicants app
INNER JOIN email_verification_tokens ef 
    ON app.code = ef.token
LEFT JOIN tbl_student_login stl 
    ON app.code = stl.Identification
WHERE 
    ef.token IS NOT NULL 
    AND app.submitted != 10
    AND stl.Identification IS NULL
ORDER BY app.fname, app.lname");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                           
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="student-checkbox"  
                                                                    data-code="<?php echo $apps['code']; ?>"
                                                                    data-email="<?php echo !empty($apps['verified_email']) ? $apps['verified_email'] : $apps['original_email']; ?>" 
                                                                    data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>"
                                                                    ></td>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['phone'] ?></td>
                                                            <td><?php echo !empty($apps['verified_email']) ? $apps['verified_email'] : $apps['original_email']; ?></td>
                                                            
                                                            
                                                            <td>
                                                                <button class="btn btn-sm btn-primary create-single-account" 
                                                                        data-code="<?php echo $apps['code']; ?>"
                                                                        data-email="<?php echo !empty($apps['verified_email']) ? $apps['verified_email'] : $apps['original_email']; ?>" 
                                                                        data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>">
                                                                    Create Account
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
        var table = $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        // Handle select all functionality using event delegation
        $('#selectAll').change(function() {
            var isChecked = this.checked;
            table.$('.student-checkbox').prop('checked', isChecked);
            updateSelectedCount();
        });

        // Use event delegation for checkbox changes
        $('#applications tbody').on('change', '.student-checkbox', function() {
            updateSelectedCount();
            
            // Update select all checkbox state
            var totalCheckboxes = table.$('.student-checkbox').length;
            var checkedCheckboxes = table.$('.student-checkbox:checked').length;
            
            if (checkedCheckboxes === 0) {
                $('#selectAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#selectAll').prop('indeterminate', true);
            }
        });

        function updateSelectedCount() {
            var count = table.$('.student-checkbox:checked').length;
            $('#selectedCount').text(count);
            $('#bulkCreateAccountBtn').prop('disabled', count === 0);
        }

        // Handle bulk account creation
        $('#bulkCreateAccountBtn').click(function() {
            var selectedStudents = [];
            table.$('.student-checkbox:checked').each(function() {
                selectedStudents.push({
                    code: $(this).data('code'),
                    email: $(this).data('email'),
                    name: $(this).data('name')
                });
            });

            if (selectedStudents.length > 0) {
                if (confirm('Are you sure you want to create accounts for ' + selectedStudents.length + ' selected applicants?')) {
                    createBulkAccounts(selectedStudents);
                }
            }
        });

        // Use event delegation for single account creation - this is the key fix
        $('#applications tbody').on('click', '.create-single-account', function() {
            var code = $(this).data('code');
            var email = $(this).data('email');
            var name = $(this).data('name');

            if (confirm('Are you sure you want to create an account for ' + name + '?')) {
                createSingleAccount(code, email, name, $(this));
            }
        });

        function createSingleAccount(code, email, name, button) {
            button.prop('disabled', true).text('Creating...');
            
            $.ajax({
                url: '../files/application/application_controller.php',
                type: 'POST',
                data: {
                    action: 'create_student_account',
                    code: code,
                    email: email,
                    name: name
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == '200') {
                        alert('Account created successfully for ' + name + '!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        button.prop('disabled', false).text('Create Account');
                    }
                },
                error: function() {
                    alert('Error creating account. Please try again.');
                    button.prop('disabled', false).text('Create Account');
                }
            });
        }

        function createBulkAccounts(students) {
            $('#bulkCreateAccountBtn').prop('disabled', true).text('Creating Accounts...');
            
            $.ajax({
                url: '../files/application/application_controller.php',
                type: 'POST',
                data: {
                    action: 'bulk_create_student_accounts',
                    students: JSON.stringify(students)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == '200') {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        $('#bulkCreateAccountBtn').prop('disabled', false).text('Create Accounts for Selected (0)');
                    }
                },
                error: function() {
                    alert('Error creating accounts. Please try again.');
                    $('#bulkCreateAccountBtn').prop('disabled', false).text('Create Accounts for Selected (0)');
                }
            });
        }
    });
</script>