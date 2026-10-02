<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Campuses</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Campuses</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New Campus</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_campus" action="save_campus" method="POST">
                                                        <input type="hidden" name="action" value="register">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Full name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="camp_full_name" name="camp_full_name" placeholder="Full name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Location</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-city"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="camp_city" name="camp_city" placeholder="Location" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-8 col-lg-8">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered Campuses</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="camp_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Campus</th>
                                                                <th scope="col">Location</th>
                                                                <th scope="col">status</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_campus ORDER BY camp_full_name ASC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($camp=$sql->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $camp['camp_full_name']; ?></td>
                                                                <td><?php echo $camp['camp_city']; ?></td>
                                                                <td><?php echo $camp['camp_active']==1?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-light'>Inactive</span>"; ?></td>
                                                                <th>
                                                                    <div class="buttons row">
                                                                    <button type="button" data-id="<?php echo $camp['camp_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $camp['camp_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                                    <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $camp['camp_id']; ?>" <?php echo $camp['camp_active']==1?'checked':''; ?>>
                                                                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $camp['camp_id']; ?>"></span>&nbsp;
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
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="c_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="c_id" name="c_id">
                                                    <input type="hidden" name="action" value="update">
                                                    <div class="form-group">
                                                        <label>Full name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_camp_full_name" name="camp_full_name" placeholder="Full name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Location</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-city"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_camp_city" name="camp_city" placeholder="Location" required>
                                                        </div>
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
                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#camp_table').DataTable(
         {     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    //save campus
    $("#save_campus").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Campus/campus_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_campus')[0].reset();
                        pop_up_success(data.message);
                        $('#camp_table').load(location.href + " #camp_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
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
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Campus/campus_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#c_id").val(data_id);
                    $("#e_camp_full_name").val(data.camp_full_name);
                    $("#e_camp_city").val(data.camp_city);
                    $("#c_name").html(data.camp_full_name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update program type
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Campus/campus_controller.php",
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
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#camp_table').load(location.href + " #camp_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          
// activate/deactivate campus
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this campus's status! This operation will affect different reports",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Campus/campus_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                       pop_up_success(data.message);
                      $('#camp_table').load(location.href + " #camp_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
            }
           else {
                swal("operation cancelled!!");
            }
        });
        });
    });
    
   function pop_wrong(feedback) {
          iziToast.warning({
    title: 'Wrong',
    message: feedback,
    position: 'topCenter'
  });
    }
    
      function pop_up_success(feedback) {
    iziToast.success({
    title: 'Info:',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>