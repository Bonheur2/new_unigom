<div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Users</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">User List</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <form id="create_account"  method="POST">
                                        <input type="hidden" name="campus" value="<?php echo $camp_id; ?>">
                                        <input type="hidden" name="action" id="form_action" value="create_users">
                                        <input type="hidden" name="staff_id" id="staff_id">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Create user account</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body row">
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>Surname (Family name)</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="lname" placeholder="e.g. GASANA" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>First name</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="fname" placeholder="e.g. Annet" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>E-mail</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-envelope"></i>
                                                            </div>
                                                        </div>
                                                        <input type="email" class="form-control" name="email" placeholder="e.g. annet@example.com" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>User role</label>
                                                        <select class="form-control select2" style="width:100%;" name="role" id="role_id" required>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id not in (1,4,5,7,18)");
                                                                $sql->execute();
                                                                while($role=$sql->fetch()){
                                                            ?>
                                                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div style="display:flex;flex-direction:row-reverse;" class="col-12">
                                                        <button type="submit" class="btn btn-primary btn-sm" id="cBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Registered users</h4>
                                            <div class="row col-lg-10">
                                            <div class="col-12 col-lg-9"></div>
                                            <div class="col-12 col-lg-3">
                                            <button class="btn btn-primary" type="submit" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excel</button>
                                            </div>
                                         </div>
                                        </div>
                                        <div class="card-body row">
                                            <!-- Tab navigation -->
                                            <ul class="nav nav-tabs w-100" id="userTabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users-content" role="tab" aria-controls="users-content" aria-selected="true">Users</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="employees-tab" data-toggle="tab" href="#employees-content" role="tab" aria-controls="employees-content" aria-selected="false">Employees</a>
                                                </li>
                                            </ul>
                                            
                                            <!-- Tab content -->
                                            <div class="tab-content w-100 mt-3" id="userTabsContent">
                                                <!-- Users tab -->
                                                <div class="tab-pane fade show active" id="users-content" role="tabpanel" aria-labelledby="users-tab">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="users">
                                                            <thead>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Role</th>
                                                                <th>Action</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $query=$conn->prepare("SELECT 
                                                                                        tbl_users.*, 
                                                                                        tbl_user_roles.role 
                                                                                            FROM tbl_users 
                                                                                        INNER JOIN tbl_user_roles ON tbl_users.role_id=tbl_user_roles.role_id 
                                                                                            WHERE tbl_users.campus_id='".$camp_id."' AND tbl_users.role_id!=18 ORDER BY tbl_users.status ASC");
                                                                $query->execute();
                                                                $i=1;
                                                                while($user=$query->fetch()){
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $user['first_name']." ".$user['family_name']; ?></td>
                                                                    <td><?php echo $user['email']; ?></td>
                                                                    <td><?php echo $user['role']; ?></td>
                                                                    <td>
                                                                        <div class="buttons row">
                                                                            <button class="btn btn-success btn-sm reset-password" data-id="<?php echo $user['id']; ?>"
                                                                            data-email="<?php echo $user['email']; ?>"
                                                                            data-name="<?php echo $user['first_name']." ".$user['family_name']; ?>"
                                                                            ><span id="spinner33_<?php echo $user['id']; ?>"></span>&nbsp;<i class="fa fa-refresh"></i></button>
                                                                            
                                                                            
                                                                            
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                                <!-- Employees tab -->
                                                <div class="tab-pane fade" id="employees-content" role="tabpanel" aria-labelledby="employees-tab">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="employees">
                                                            <thead>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Post</th>
                                                                <th>Action</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Modified query to only select employees without user accounts
                                                                $query=$conn->prepare("SELECT st.*,sp.*,ssp.* FROM tbl_staff_info st 
                                                                INNER JOIN tbl_staff_post sp ON st.staff_id= sp.staff_id
                                                                LEFT JOIN staff_post ssp ON ssp.staff_post_id=sp.post
                                                                LEFT JOIN tbl_users u ON st.email = u.email
                                                                WHERE u.id IS NULL
                                                                ");
                                                                $query->execute();
                                                                $i=1;
                                                                while($employee=$query->fetch()){
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $employee['first_name']." ".$employee['family_name']; ?></td>
                                                                    <td><?php echo $employee['email']; ?></td>
                                                                    <td>
                                                                        <?php 
                                                                        // Check if post data is stored as JSON array
                                                                        if (isset($employee['post']) && !empty($employee['post']) && $employee['post'][0] == '[') {
                                                                            // Decode the JSON array
                                                                            $post_ids = json_decode($employee['post'], true);
                                                                            
                                                                            if (is_array($post_ids)) {
                                                                                $post_names = [];
                                                                                
                                                                                // Fetch post names for each ID
                                                                                foreach ($post_ids as $post_id) {
                                                                                    $post_query = $conn->prepare("SELECT staff_post_full_name FROM staff_post WHERE staff_post_id = ?");
                                                                                    $post_query->execute([$post_id]);
                                                                                    $post_data = $post_query->fetch();
                                                                                    
                                                                                    if ($post_data) {
                                                                                        $post_names[] = $post_data['staff_post_full_name'];
                                                                                    }
                                                                                }
                                                                                
                                                                                // Display all post names
                                                                                echo implode(', ', $post_names);
                                                                            } else {
                                                                                echo $employee['staff_post_full_name'];
                                                                            }
                                                                        } else {
                                                                            echo $employee['staff_post_full_name'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="buttons row">
                                                                            
                                                                            <button class="btn btn-primary btn-sm edit-employee" 
                                                                                data-id="<?php echo $employee['staff_id']; ?>" 
                                                                                data-firstname="<?php echo $employee['first_name']; ?>" 
                                                                                data-familyname="<?php echo $employee['family_name']; ?>" 
                                                                                data-email="<?php echo $employee['email']; ?>"
                                                                                data-staff-id="<?php echo $employee['staff_id']; ?>"
                                                                                data-phone="<?php echo isset($employee['phone']) ? $employee['phone'] : ''; ?>">
                                                                                <span id="spinner1_<?php echo $employee['staff_id']; ?>"></span>&nbsp;<i class="fas fa-check"></i>Create
                                                                            </button>
                                                                        </div>
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
                            </div>
                        </div>
                    </section>
                </div>
                
               
                
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables
    $('.table').DataTable({ 
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
    });
    
    // Handle edit button click for employees
    $(document).on('click', '.edit-employee', function() {
        var id = $(this).data('id');
        var firstName = $(this).data('firstname');
        var familyName = $(this).data('familyname');
        var staff_id=$(this).data('staff-id');
        var email = $(this).data('email');
        var phone = $(this).data('phone') || '';
        
        // Update form action and hidden fields
        $('#form_action').val('create_users');
        $('#staff_id').val(id);
        $('#is_edit').val('1');
        $('#staff_id').val(staff_id)
        
        // Populate the form fields
        $('input[name="fname"]').val(firstName);
        $('input[name="lname"]').val(familyName);
        $('input[name="email"]').val(email);
        
        // Change form button text - keep it as "Create Account" as requested
        $('#indicator').text('Create Account');
        
        // Expand the form
        if ($('#mycard-collapse').hasClass('hide')) {
            $('#mycard-collapse').removeClass('hide').addClass('show');
        }
        
        // Scroll to the form
        $('html, body').animate({
            scrollTop: $("#create_account").offset().top - 100
        }, 500);
    });
    
    // Form submission handler
    $("#create_account").on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        var formData = new FormData(this);
        var isEdit = $('#is_edit').val();
        var action = $('#form_action').val();
        var submitBtn = $('#cBtn');
        var spinnerElement = $('#spinner');
        var indicatorElement = $('#indicator');
        
        // Show loading state
        submitBtn.attr('disabled', true);
        spinnerElement.html('<i class="fas fa-spinner fa-spin"></i>');
        
        // AJAX form submission
        $.ajax({
            url: "/files/User/user_controller.php",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    // Try to parse as JSON
                    var result = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if(result.status === 200) {
                        // Show success message
                        pop_up_success(result.message || 'User created successfully');
                        
                        // Reset form for new entry
                        $('#create_account')[0].reset();
                        $('#is_edit').val('0');
                        $('#form_action').val('create_users');
                        
                        // Remove any faculty/department selects that were added
                        $("#faculty_select_container, #department_select_container").remove();
                        
                        // Reset role dropdown to trigger the change event
                        $("#role_id").val($("#role_id option:first").val()).trigger('change');
                        
                        // Collapse the form
                        if ($('#mycard-collapse').hasClass('show')) {
                            $('#mycard-collapse').removeClass('show').addClass('hide');
                        }
                        
                        // Reload page to refresh tables after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        pop_wrong(result.message || 'Error creating user');
                    }
                } catch(e) {
                    // Not valid JSON, handle as text
                    if(typeof response === 'string' && response.includes('success')) {
                        pop_up_success('User created successfully');
                        
                        // Reset and reload like above
                        $('#create_account')[0].reset();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        pop_wrong('Error: ' + (typeof response === 'string' ? response : 'Unknown error'));
                    }
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                pop_wrong('Error - ' + errorMessage);
            },
            complete: function() {
                // Restore button state regardless of success/error
                submitBtn.attr('disabled', false);
                spinnerElement.html('');
                indicatorElement.text('Create Account');
            }
        });
    });
    
    // Handle role selection - show/hide faculty and department selects based on role
    $("#role_id").on('change', function() {
        var selectedRole = $(this).val();
        
        // Remove existing faculty and department containers if they exist
        $("#faculty_select_container, #department_select_container").remove();
        
        // Check if selected role is 11 (multiple faculty selection)
        if (selectedRole === "11") {
            // Create faculty multi-select
            var facultySelect = '<div id="faculty_select_container" class="form-group col-12 col-sm-6 col-lg-4">' +
                '<label>Select Faculties</label>' +
                '<select class="form-control select2" style="width:100%;" name="faculty_ids[]" id="faculty_ids" multiple>' +
                <?php
                $facultyQuery = $conn->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status=1");
                $facultyQuery->execute();
                while($faculty = $facultyQuery->fetch()) {
                    echo "'<option value=\"" . $faculty['fac_id'] . "\">" . $faculty['fac_full_name'] . "</option>' +";
                }
                ?>
                '</select></div>';
            
            // Add after role selection
            $(facultySelect).insertAfter($(this).closest('.form-group'));
            
            // Initialize select2 on the new element
            $('#faculty_ids').select2();
        }
        // Check if selected role is 10 (single faculty with multiple departments)
        else if (selectedRole === "10") {
            // Create faculty single-select
            var facultySelect = '<div id="faculty_select_container" class="form-group col-12 col-sm-6 col-lg-4">' +
                '<label>Select Faculty</label>' +
                '<select class="form-control select2" style="width:100%;" name="faculty_id" id="faculty_id">' +
                '<option value="">Select a faculty</option>' +
                <?php
                $facultyQuery = $conn->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status=1");
                $facultyQuery->execute();
                while($faculty = $facultyQuery->fetch()) {
                    echo "'<option value=\"" . $faculty['fac_id'] . "\">" . $faculty['fac_full_name'] . "</option>' +";
                }
                ?>
                '</select></div>';
            
            // Add after role selection
            $(facultySelect).insertAfter($(this).closest('.form-group'));
            
            // Initialize select2 on the new element
            $('#faculty_id').select2();
            
            // Add event handler for faculty selection
            $(document).on('change', '#faculty_id', function() {
                var facultyId = $(this).val();
                
                // Remove existing department container if it exists
                $("#department_select_container").remove();
                
                if (facultyId) {
                    // Create department multi-select
                    var deptSelect = '<div id="department_select_container" class="form-group col-12 col-sm-6 col-lg-4">' +
                        '<label>Select Departments</label>' +
                        '<select class="form-control select2" style="width:100%;" name="dept_ids[]" id="dept_ids" multiple>' +
                        <?php
                        $deptQuery = $conn->prepare("SELECT dept_id, dept_full_name, fac_id FROM tbl_department WHERE status=1");
                        $deptQuery->execute();
                        while($dept = $deptQuery->fetch()) {
                            echo "'<option value=\"" . $dept['dept_id'] . "\" data-faculty=\"" . $dept['fac_id'] . "\">" . $dept['dept_full_name'] . "</option>' +";
                        }
                        ?>
                        '</select></div>';
                    
                    // Add after faculty selection
                    $(deptSelect).insertAfter($("#faculty_select_container"));
                    
                    // Initialize select2 on the new element
                    $('#dept_ids').select2();
                    
                    // Show only departments from the selected faculty
                    $('#dept_ids option').each(function() {
                        if ($(this).data('faculty') == facultyId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    
                    // Refresh the select2 to reflect changes
                    $('#dept_ids').select2('destroy').select2();
                }
            });
        }
        // Check if selected role is 12 (single faculty with single department)
        else if (selectedRole === "12") {
            // Create faculty single-select
            var facultySelect = '<div id="faculty_select_container" class="form-group col-12 col-sm-6 col-lg-4">' +
                '<label>Select Faculty</label>' +
                '<select class="form-control select2" style="width:100%;" name="faculty_id" id="faculty_id">' +
                '<option value="">Select a faculty</option>' +
                <?php
                $facultyQuery = $conn->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status=1");
                $facultyQuery->execute();
                while($faculty = $facultyQuery->fetch()) {
                    echo "'<option value=\"" . $faculty['fac_id'] . "\">" . $faculty['fac_full_name'] . "</option>' +";
                }
                ?>
                '</select></div>';
            
            // Add after role selection
            $(facultySelect).insertAfter($(this).closest('.form-group'));
            
            // Initialize select2 on the new element
            $('#faculty_id').select2();
            
            // Add event handler for faculty selection
            $(document).on('change', '#faculty_id', function() {
                var facultyId = $(this).val();
                
                // Remove existing department container if it exists
                $("#department_select_container").remove();
                
                if (facultyId) {
                    // Create department single-select (not multiple)
                    var deptSelect = '<div id="department_select_container" class="form-group col-12 col-sm-6 col-lg-4">' +
                        '<label>Select Department</label>' +
                        '<select class="form-control select2" style="width:100%;" name="dept_id" id="dept_id">' +
                        '<option value="">Select a department</option>' +
                        <?php
                        $deptQuery = $conn->prepare("SELECT dept_id, dept_full_name, fac_id FROM tbl_department WHERE status=1");
                        $deptQuery->execute();
                        while($dept = $deptQuery->fetch()) {
                            echo "'<option value=\"" . $dept['dept_id'] . "\" data-faculty=\"" . $dept['fac_id'] . "\">" . $dept['dept_full_name'] . "</option>' +";
                        }
                        ?>
                        '</select></div>';
                    
                    // Add after faculty selection
                    $(deptSelect).insertAfter($("#faculty_select_container"));
                    
                    // Initialize select2 on the new element
                    $('#dept_id').select2();
                    
                    // Show only departments from the selected faculty
                    $('#dept_id option').each(function() {
                        if ($(this).data('faculty') == facultyId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    
                    // Refresh the select2 to reflect changes
                    $('#dept_id').select2('destroy').select2();
                }
            });
        }
    });
    
    // Trigger change event to handle initial state
    $("#role_id").trigger('change');
    
    // Notification functions using iziToast
    function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
    
    // AJAX form submission handler (only one submit handler)
    $("#create_account").on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        var formData = new FormData(this);
        var isEdit = $('#is_edit').val();
        var action = $('#form_action').val();
        var submitBtn = $('#cBtn');
        var spinnerElement = $('#spinner');
        var indicatorElement = $('#indicator');
        
        // Show loading state
        submitBtn.attr('disabled', true);
        spinnerElement.html('<i class="fas fa-spinner fa-spin"></i>');
        
        // AJAX form submission
        $.ajax({
            url: "/files/User/user_controller.php",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    // Try to parse as JSON
                    var result = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if(result.status === 200) {
                        // Show success message
                        pop_up_success(result.message || 'User created successfully');
                        
                        // Reset form for new entry
                        $('#create_account')[0].reset();
                        $('#is_edit').val('0');
                        $('#form_action').val('create_users');
                        
                        // Remove any faculty/department selects that were added
                        $("#faculty_select_container, #department_select_container").remove();
                        
                        // Reset role dropdown to trigger the change event
                        $("#role_id").val($("#role_id option:first").val()).trigger('change');
                        
                        // Collapse the form
                        if ($('#mycard-collapse').hasClass('show')) {
                            $('#mycard-collapse').removeClass('show').addClass('hide');
                        }
                        
                        // Reload page to refresh tables after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        pop_wrong(result.message || 'Error creating user');
                    }
                } catch(e) {
                    // Not valid JSON, handle as text
                    if(typeof response === 'string' && response.includes('success')) {
                        pop_up_success('User created successfully');
                        
                        // Reset and reload like above
                        $('#create_account')[0].reset();
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        pop_wrong('Error: ' + (typeof response === 'string' ? response : 'Unknown error'));
                    }
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                pop_wrong('Error - ' + errorMessage);
            },
            complete: function() {
                // Restore button state regardless of success/error
                submitBtn.attr('disabled', false);
                spinnerElement.html('');
                indicatorElement.text('Create Account');
            }
        });
    });
    // Reset password button click handler
    $(document).on('click', '.reset-password', function(e) {
        e.preventDefault();
        
        var userId = $(this).data('id');
        var email = $(this).data('email');
        var name = $(this).data('name');
        var button = $(this);
        var spinnerElement = $('#spinner33_' + userId);
        
        // Confirm before resetting
        if (confirm('Are you sure you want to reset the password for ' + name + '?')) {
            // Show loading state
            button.attr('disabled', true);
            spinnerElement.html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Send AJAX request to reset password
            $.ajax({
                url: "/files/User/user_controller.php",
                type: 'POST',
                data: {
                    action: 'reset_password',
                    user_id: userId,
                    email: email,
                    name: name
                },
                success: function(response) {
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if(result.status === 200) {
                            pop_up_success(result.message || 'Password has been reset successfully');
                        } else {
                            pop_wrong(result.message || 'Error resetting password');
                        }
                    } catch(e) {
                        if(typeof response === 'string' && response.includes('success')) {
                            pop_up_success('Password has been reset successfully');
                        } else {
                            pop_wrong('Error: ' + (typeof response === 'string' ? response : 'Unknown error'));
                        }
                    }
                },
                error: function(xhr, status, error) {
                    pop_wrong('Error - ' + xhr.status + ': ' + xhr.statusText);
                },
                complete: function() {
                    // Restore button state
                    button.attr('disabled', false);
                    spinnerElement.html('<i class="fa fa-refresh"></i>');
                }
            });
        }
    });
    // Handle edit button click for users
    $(document).on('click', '.edit', function() {
        var userId = $(this).data('id');
        var button = $(this);
        var spinnerElement = $('#spinner1_' + userId);
        
        // Show loading state
        button.attr('disabled', true);
        spinnerElement.html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Fetch user data
        $.ajax({
            url: "/files/User/user_controller.php",
            type: 'POST',
            data: {
                action: 'get_user',
                user_id: userId
            },
            success: function(response) {
                try {
                    var userData = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (userData && userData.status === 200 && userData.data) {
                        var user = userData.data;
                        
                        // Populate form fields with user data
                        $('#id').val(user.id || '');
                        $('#lname').val(user.family_name || '');
                        $('#fname').val(user.first_name || '');
                        $('#email').val(user.email || '');
                        $('#phone').val(user.phone || '');
                        $('#e_role_id').val(user.role_id || '').trigger('change');
                        
                        // Handle faculty and department fields
                        if (user.fac_id) {
                            try {
                                var facIds = JSON.parse(user.fac_id);
                                $('#e_fac_id').val(facIds).trigger('change');
                            } catch (e) {
                                $('#e_fac_id').val([user.fac_id]).trigger('change');
                            }
                        }
                        if (user.dept_id) {
                            try {
                                var deptIds = JSON.parse(user.dept_id);
                                $('#e_dept_id').val(deptIds).trigger('change');
                            } catch (e) {
                                $('#e_dept_id').val([user.dept_id]).trigger('change');
                            }
                        }
                        
                        // Set staff checkbox
                        $('#staff').prop('checked', user.is_staff === '1');
                        
                        // Open the modal
                        $('#updateModal').modal('show');
                    } else {
                        pop_wrong(userData.message || 'Error fetching user data');
                    }
                } catch (e) {
                    pop_wrong('Error parsing response: ' + e.message);
                }
            },
            error: function(xhr, status, error) {
                pop_wrong('Error - ' + xhr.status + ': ' + xhr.statusText);
            },
            complete: function() {
                // Restore button state
                button.attr('disabled', false);
                spinnerElement.html('<i class="fas fa-edit"></i>');
            }
        });
    });
    
    // Notification functions
    function pop_wrong(feedback) {
        alert(feedback); // Replace with your notification library if needed
    }
});
</script>

<!-- Add the modal structure if it doesn't exist -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="lname">Surname (Family name)</label>
                        <input type="text" class="form-control" id="lname" name="lname" required>
                    </div>
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="e_role_id">Role</label>
                        <select class="form-control" id="e_role_id" name="role" required>
                            <?php
                                $sql = $conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id NOT IN (1,4,5,7,18)");
                                $sql->execute();
                                while ($role = $sql->fetch()) {
                                    echo "<option value='{$role['role_id']}'>{$role['role']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!-- Add other fields as needed -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>