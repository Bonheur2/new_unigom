<!-- Start app main Content -->
<div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Staff Position</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Staff Position</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Staff Position</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="collapse hide" id="mycard-collapse">
                                        <form class="col-12 row" id="save_staff_post" action="save_staff_post" method="POST">
                                            <input type="hidden" name="action" value="save_staff_post">
                                            <div class="form-group col-md-4">
                                                <label>Staff Type</label>
                                                <select class="form-control select2" name="staff_type_id" style="width:100%" required>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT * FROM tbl_staff_type ORDER BY staff_type_full_name ASC");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($type=$sql->fetch()){
                                                    ?>
                                                    <option value="<?php echo $type['staff_type_id']; ?>"><?php echo $type['staff_type_full_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Post full name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            &nbsp;<i class="fas fa-pencil"></i>&nbsp;
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="staff_post_full_name" placeholder="Type full name" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Post short name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="staff_post_short_name" placeholder="Type short name">
                                                </div>
                                            </div>
                                            <div class="card-footer pt-">
                                                <button type="submit" class="btn btn-primary "><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Staff Position</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="post_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Staff Position Name</th>
                                                    <!--<th scope="col">Short Name</th>-->
                                                    <th scope="col">Staff Category</th>
                                                    <th scope="col">Edit</th>
                                                    <th scope="col">Satus</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT staff_post.*, tbl_staff_type.staff_type_full_name FROM staff_post
                                                    INNER JOIN tbl_staff_type ON tbl_staff_type.staff_type_id = staff_post.staff_type_id
                                                    ORDER BY staff_post_full_name ASC");
                                                    
                                                    $sql->execute();
                                                    $i=1;
                                                    while($type=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $type['staff_post_full_name']; ?></td>
                                                    <!--<td><?php echo $type['staff_post_short_name']; ?></td>-->
                                                    <td><?php echo $type['staff_type_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $type['staff_post_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $type['staff_post_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            
                                                        </div>
                                                    </th>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <label class="custom-switch btn btn-sm btn-light">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $type['staff_post_id']; ?>" <?php echo $type['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $type['staff_post_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                                    </th>
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
         <!--update modal-->
        <form action="update_form" method="POST" id="update_form">
            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Updating <span id="r_name"></span> position</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="staff_post_id_edit" name="staff_post_id_edit">
                            <input type="hidden" name="action" value="update_post">
                            <div class="form-group">
                                <label>Post full name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="post_full_edit" name="post_full_edit" placeholder="Full name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Post short name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="post_short_edit" name="post_short_edit" placeholder="short name" >
                                </div>
                            </div>
                            <div class="form-group">
                            <label>Staff Category</label>
                            <select class="form-control select2" name="staff_type_edit" style="width:100%" id="staff_type_edit">
                                <?php
                                    $sql=$conn->prepare("SELECT * FROM tbl_staff_type ORDER BY staff_type_full_name ASC");
                                    $sql->execute();
                                    $i=1;
                                    while($type=$sql->fetch()){
                                ?>
                                <option value="<?php echo $type['staff_type_id']; ?>"><?php echo $type['staff_type_full_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#post_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       }); 
    //save department
    $("#save_staff_post").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Staff/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_staff_post')[0].reset();
                        $('#post_table').load(location.href + " #post_table");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_info(data.message);  
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
      
        // delete department
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_staff_post'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this department's status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Staff/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_info(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#post_table').load(location.href + " #post_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
           else {
                swal("operation Cancelled!!");
            }
        });
        });
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_staff_post'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Staff/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#staff_post_id_edit").val(data_id);
                    $("#post_full_edit").val(data.staff_post_full_name);
                    $("#post_short_edit").val(data.staff_post_short_name);
                    $("#r_name").html(data.staff_post_full_name);
                    
                    var selectStaffPost = $("#staff_type_edit");
                    selectStaffPost.val(data.staff_type_id).change();

                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
    //update department
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Staff/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        $('#post_table').load(location.href + " #post_table");
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_info(data.message);  
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!"); 
                    
                }
             });
          });
    });
</script>