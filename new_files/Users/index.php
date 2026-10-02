<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Users</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">User List</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Create User Account</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="create_user" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Surname (Family name)</label>
                                                    <input type="text" class="form-control" name="family_name" placeholder="e.g. GASANA" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>First name</label>
                                                    <input type="text" class="form-control" name="first_name" placeholder="e.g. Annet" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>E-mail</label>
                                                    <input type="email" class="form-control" name="email" placeholder="e.g. annet@example.com" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Phone number</label>
                                                    <input type="text" class="form-control" name="phone_no" placeholder="e.g. +250788...">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Role</label>
                                                    <select class="form-control select2" style="width:100%" name="role_id" id="role_id" required>
                                                        <option value="" disabled selected hidden>Select role...</option>
                                                        <?php
                                                            $sql = $conn->prepare("SELECT role_id, role FROM tbl_user_roles WHERE status = 1 ORDER BY role ASC");
                                                            $sql->execute();
                                                            while($role = $sql->fetch()):
                                                        ?>
                                                        <option value="<?php echo $role['role_id']; ?>"><?php echo htmlspecialchars($role['role']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%" name="campus_id" id="campus_id" required>
                                                        <option value="" disabled selected hidden>Select campus...</option>
                                                        <?php
                                                            $sql_camp = $conn->prepare("SELECT camp_id, camp_full_name FROM tbl_campus WHERE camp_active = 1 ORDER BY camp_full_name ASC");
                                                            $sql_camp->execute();
                                                            while($campus = $sql_camp->fetch()):
                                                        ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo htmlspecialchars($campus['camp_full_name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block" id="cBtn"><span id="spinner"></span>&nbsp;<span id="indicator">Create Account</span></button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> A username and password will be generated automatically and emailed to the user.
                                            </small>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                <div class="card-header">
                                    <h4>Registered Users</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="users_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Username</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Role</th>
                                                    <th>Campus</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = $conn->prepare("SELECT tbl_users.id, tbl_users.Identification, tbl_users.family_name, tbl_users.first_name,
                                                                                    tbl_users.email, tbl_users.phone_no, tbl_users.status,
                                                                                    tbl_user_roles.role, tbl_campus.camp_full_name
                                                                            FROM tbl_users
                                                                            INNER JOIN tbl_user_roles ON tbl_users.role_id = tbl_user_roles.role_id
                                                                            LEFT JOIN tbl_campus ON tbl_users.campus_id = tbl_campus.camp_id
                                                                            ORDER BY tbl_users.id DESC");
                                                    $query->execute();
                                                    $i = 1;
                                                    while($user = $query->fetch()):
                                                ?>
                                                <tr data-row-id="<?php echo $user['id']; ?>">
                                                    <td><?php echo $i++; ?></td>
                                                    <td class="col-identification"><?php echo htmlspecialchars($user['Identification']); ?></td>
                                                    <td class="col-name"><?php echo htmlspecialchars($user['first_name'].' '.$user['family_name']); ?></td>
                                                    <td class="col-email"><?php echo htmlspecialchars($user['email']); ?></td>
                                                    <td class="col-phone"><?php echo htmlspecialchars($user['phone_no']); ?></td>
                                                    <td class="col-role"><?php echo htmlspecialchars($user['role']); ?></td>
                                                    <td class="col-campus"><?php echo htmlspecialchars($user['camp_full_name'] ?? ''); ?></td>
                                                    <td class="col-status"><span class="badge <?php echo $user['status']==1 ? 'badge-success' : 'badge-secondary'; ?>"><?php echo $user['status']==1 ? 'Active' : 'Inactive'; ?></span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm reset-password" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['first_name'].' '.$user['family_name']); ?>">
                                                            <span id="spinner3_<?php echo $user['id']; ?>"></span>&nbsp;<i class="fa fa-key"></i>&nbsp;Reset Password
                                                        </button>
                                                    </td>
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

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    var usersDt = $('#users_table').DataTable({
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 10
    });

    function addUserRow(row){
        var $tr = $(usersDt.row.add([
            usersDt.rows().count() + 1,
            row.identification,
            row.first_name + ' ' + row.family_name,
            row.email,
            row.phone_no || '',
            row.role,
            row.camp_full_name || '',
            '<span class="badge badge-success">Active</span>',
            '<button type="button" class="btn btn-warning btn-sm reset-password" data-id="'+row.id+'" data-name="'+row.first_name+' '+row.family_name+'">'
                + '<span id="spinner3_'+row.id+'"></span>&nbsp;<i class="fa fa-key"></i>&nbsp;Reset Password</button>'
        ]).draw(false).node());
        $tr.attr('data-row-id', row.id);
    }

    //create user
    $("#create_user").submit(function(e){
        e.preventDefault();

        var formData = {
            family_name: $("[name='family_name']").val(),
            first_name: $("[name='first_name']").val(),
            email: $("[name='email']").val(),
            phone_no: $("[name='phone_no']").val(),
            role_id: $("#role_id").val(),
            campus_id: $("#campus_id").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Creating...");
        $("#cBtn").attr('disabled', true);
        $.ajax({
            url: "../new_files/Users/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Create Account");
                $("#cBtn").attr('disabled', false);
                if(data.status==200){
                    $('#create_user')[0].reset();
                    $('#role_id').val(null).trigger('change');
                    $('#campus_id').val(null).trigger('change');
                    pop_up_success(data.message);
                    addUserRow(data);
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Create Account");
                $("#cBtn").attr('disabled', false);
                pop_wrong("Something went wrong!");
            }
        });
    });

    //reset password
    $(document).on('click', '.reset-password', function(){
        var $btn = $(this);
        var user_id = $btn.data('id');
        var name = $btn.data('name');

        swal({
            title: "Reset password?",
            text: "A new password will be generated and emailed to " + name + ".",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willReset) => {
            if(willReset){
                $('#spinner3_'+user_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Users/controller.php",
                    data: { id: user_id, action: 'reset_password' },
                    dataType: "json",
                    success: function(data){
                        $('#spinner3_'+user_id).fadeOut('fast');
                        if(data.status==200){
                            pop_up_success(data.message);
                        } else {
                            pop_wrong(data.message);
                        }
                    },
                    error: function(){
                        $('#spinner3_'+user_id).fadeOut('fast');
                        pop_wrong("Something went wrong!");
                    }
                });
            }
        });
    });

});

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
</script>
