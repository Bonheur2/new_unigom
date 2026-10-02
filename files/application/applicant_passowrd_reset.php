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
                                        <h4>Applicants Password Reset</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
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
                                                        $sql=$conn->prepare("SELECT app.*
                                                                                    FROM tbl_applicants app
                                                                                    INNER JOIN tbl_student_login stl ON app.code = stl.Identification
                                                                                    WHERE app.submitted != 10
                                                                                    ");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['phone'] ?></td>
                                                            <td><?php echo $apps['email'] ?></td>
                                                            <td>
                                                                <button class="btn btn-warning btn-sm ml-1" onclick="resetPassword('<?php echo $apps['email']; ?>', '<?php echo $apps['code']; ?>', '<?php echo $apps['fname']; ?>', this)">Reset Password</button>
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
    });

    function resetPassword(email, code, name, button) {
        // Show confirmation dialog
        if (!confirm('Are you sure you want to reset the password for ' + name + '?')) {
            return;
        }
        
        // Disable button and show loading state
        $(button).prop('disabled', true).text('Resetting...');
        
        $.ajax({
            url: '../files/application/application_controller.php',
            type: 'POST',
            data: {
                email: email,
                code: code,
                name: name,
                action: 'reset_password'
            },
            dataType: 'json',
            success: function(response) {
                if(response.status == '200') {
                    alert('Password reset successfully! New password sent to email.');
                    $(button).removeClass('btn-warning').addClass('btn-success').text('Password Reset');
                    // Re-enable button after 5 seconds
                    setTimeout(function() {
                        $(button).removeClass('btn-success').addClass('btn-warning').text('Reset Password').prop('disabled', false);
                    }, 5000);
                } else {
                    alert('Error: ' + response.message);
                    $(button).prop('disabled', false).text('Reset Password');
                }
            },
            error: function() {
                alert('An error occurred while resetting the password.');
                $(button).prop('disabled', false).text('Reset Password');
            }
        });
    }
</script>