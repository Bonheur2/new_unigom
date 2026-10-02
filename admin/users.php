<?php if($role_id==18){ ?>
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Users</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=on">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">User List</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <form id="create_account" action="create_account" method="POST">
                                        <input type="hidden" name="action" value="create_user">
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
                                                        <label>Phone number</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control phone-number" name="phone" placeholder="e.g. 0788888888" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>Campus</label>
                                                        <select class="form-control select2" style="width:100%;" name="campus" required>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                                $sql->execute();
                                                                while($camp=$sql->fetch()){
                                                            ?>
                                                            <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>System role</label>
                                                        <select class="form-control select2" style="width:100%;" name="role" required>
                                                            <?php
                                                                $sql1=$conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id IN (1,6,7)");
                                                                $sql1->execute();
                                                                while($role=$sql1->fetch()){
                                                            ?>
                                                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <div class="control-label">Is staff?</div>
                                                        <div class="custom-switches-stacked mt-2">
                                                        <label class="custom-switch">
                                                            <input type="checkbox" name="staff" value="1" class="custom-switch-input" checked>
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div style="display:flex;flex-direction:row-reverse;" class="col-12">
                                                        <button type="submit" class="btn btn-primary btn-sm" id="cBtn"><span id="spinner"></span>&nbsp;<i class="fas fa-floppy-disk"></i> &nbsp;<span id="indicator">Save</span></button>
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
                                        </div>
                                    <div class="card-body row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="users">
                                                <thead>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Campus</th>
                                                    <th>Role</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query=$conn->prepare("SELECT 
                                                                            tbl_users.*, 
                                                                            tbl_user_roles.role,
                                                                            tbl_campus.camp_full_name 
                                                                                FROM tbl_users 
                                                                            INNER JOIN tbl_user_roles ON tbl_users.role_id=tbl_user_roles.role_id 
                                                                            LEFT JOIN tbl_campus ON tbl_users.campus_id=tbl_campus.camp_id 
                                                                                WHERE tbl_users.role_id IN (1,6,7) ORDER BY tbl_users.status ASC");
                                                    $query->execute();
                                                    $i=1;
                                                    while($user=$query->fetch()){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $user['first_name']." ".$user['family_name']; ?></td>
                                                        <td><?php echo $user['email']; ?></td>
                                                        <td><?php echo $user['camp_full_name']!=null?$user['camp_full_name']:'-'; ?></td>
                                                        <td><?php echo $user['role']; ?></td>
                                                        <td>
                                                            <div class="buttons row">
                                                            <button class="btn btn-success btn-sm view" data-id="<?php echo $user['id']; ?>"><span id="spinner2_<?php echo $user['id']; ?>"></span>&nbsp;View</button>
                                                            <button class="btn btn-primary btn-sm edit" data-id="<?php echo $user['id']; ?>"><span id="spinner1_<?php echo $user['id']; ?>"></span>&nbsp;<i class="fas fa-edit"></i>edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $user['id']; ?>" <?php echo $user['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $user['id']; ?>"></span>&nbsp;
                                                            </label>
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
                </section>
            </div>
                <!--view modal-->
                <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                    <div class="modal-dialog center" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><span id="f_name"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body row">
                                <div class="form-group col-12 col-md-6 col-lg-6">
                                    <div class="article-user-details">
                                        <div class="text-job">Firstname</div>
                                        <div class="user-detail-name"><a href="#"><b><span id="v_fname"></span></b></a></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-2 col-lg-6">
                                    <div class="article-user-details">
                                        <div class="text-job">Lastname</div>
                                        <div class="user-detail-name"><a href="#"><b><span id="v_lname"></span></b></a></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-6">
                                    <div class="article-user-details">
                                        <div class="text-job">Email</div>
                                        <div class="user-detail-name"><a href="#"><b><span id="v_email"></span></b></a></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 col-lg-6">
                                    <div class="article-user-details">
                                        <div class="text-job">Phone</div>
                                        <div class="user-detail-name"><a href="#"><b><span id="v_phone"></span></b></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end view modal-->
                <!--edit modal-->
                <form action="update_form" method="POST" id="update_form">
                    <div class="modal fade" role="dialog" id="updateModal">
                        <div class="modal-dialog modal-lg center" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><span id="fu_name"></span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body row">
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="action" value="update">
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>Surname (Family name)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. GASANA" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>First name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Annet" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>E-mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="e.g. annet@example.com" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>Phone number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control phone-number" name="phone" id="phone" placeholder="e.g. 0788888888" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>Campus</label>
                                        <select class="form-control select2" style="width:100%;" name="campus" id="campus" required>
                                        <?php
                                            $sql=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                            $sql->execute();
                                            while($camp=$sql->fetch()){
                                        ?>
                                        <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>User role</label>
                                        <select class="form-control select2" style="width:100%;" name="role" id="role_id" required>
                                            <?php
                                                $sql=$conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id IN (1,6,7)");
                                                $sql->execute();
                                                while($role=$sql->fetch()){
                                            ?>
                                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <div class="control-label">Is staff?</div>
                                            <div class="custom-switches-stacked mt-2">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="staff" id="staff" value="1" class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm" id="uBtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end update modal-->
                                   
<?php } ?>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#users').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    // create account
    $("#create_account").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Processing...");
            $("#cBtn").attr('disabled',true);
            $.ajax({
                url: "/files/User/user_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    $("#cBtn").attr('disabled',false);
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#users').load(location.href + " #users");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $("#cBtn").attr('disabled',false);
                    $('#indicator').html("Submit");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          
        
        //View
        $(document).on('click', '.view', function() {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view'
                    };
            $('#spinner2_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/User/user_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner2_'+data_id).fadeOut('fast');
                    $("#v_fname").html(data.first_name);
                    $("#f_name").html(data.first_name);
                    $("#v_lname").html(data.family_name);
                    $("#v_email").html(data.email);
                    $("#v_phone").html(data.phone_no);
                    $('#viewModal').modal('show');
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view'
                    };
            $('#spinner1_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/User/user_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner1_'+data_id).fadeOut('fast');
                    $("#id").val(data_id);
                    $("#fu_name").html(data.first_name);
                    $("#lname").val(data.family_name);
                    $("#fname").val(data.first_name);
                    $("#email").val(data.email);
                    $("#phone").val(data.phone_no);
                    
                    // Set selected values
                    var selectElement = document.getElementById('campus');
                    var selectedOption = selectElement.querySelector('option[value="' + data.campus_id + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                      selectElement.prepend(selectedOption);
                    }
                    var selectElement2 = document.getElementById('role_id');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.role_id + '"]');
                    if (selectedOption2) {
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    if(data.Identification!=null){
                        $("#staff").attr('checked', true);
                    }
                    else{
                        $("#staff").removeAttr('checked');
                    }
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner1_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
    // update account
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
            $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Processing...");
            $("#uBtn").attr('disabled',true);
            $.ajax({
                url: "/files/User/user_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    $("#uBtn").attr('disabled',false);
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#users').load(location.href + " #users");
                        $("#updateModal").modal('hide');
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $("#uBtn").attr('disabled',false);
                    $('#indicator2').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
        // delete user
        $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
                title: "Are you sure?",
                text: "You are about to change this user's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/User/user_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                      $('#users').load(location.href + " #users");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong");
				}
            });
            }
           else {
                swal("Operation cancelled!!");
            }
        });
    });

});
    </script>