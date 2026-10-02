<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Sponsors</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Sponsors</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Sponsor</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_sponsor" action="save_sponsor" method="POST">
                                            <input type="hidden" name="action" value="save_sponsor">
                                            <div class="row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Sponsor Category</label>
                                                    <select class="form-control select2" style="width:100%" name="spon_cat_id" id="spon_cat_id" required>
                                                            <?php
                                                                $sql_sp_cat=$conn->prepare("SELECT * FROM tbl_sponsor_category");
                                                                $sql_sp_cat->execute();
                                                                while($sp_cat=$sql_sp_cat->fetch()){
                                                                    ?>
                                                            <option value="<?php echo $sp_cat['spon_cat_id']; ?>"><?php echo $sp_cat['spon_cat_name']; ?> </option>
                                                            <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group  col-12 col-md-6 col-lg-4">
                                                    <label>Full name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-user-tie"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="spon_full_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-md-6 col-lg-4">
                                                    <label>Short Name (<i>optional</i>)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="spon_short_name" placeholder="Short name">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-md-6 col-lg-4">
                                                    <label>Address</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-city"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="address" placeholder="address" required>
                                                    </div>
                                                </div>
                                                 <div class="form-group col-12 col-md-6 col-lg-4">
                                                    <label>Sponsor Email</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-e mail"></i>
                                                            </div>
                                                        </div>
                                                        <input type="email" class="form-control" name="sponsor_email" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-md-6 col-lg-4">
                                                    <label>Phone</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="phone" placeholder="phone" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer pt-10" style="display:flex;flex-direction:row;justify-content:center;">
                                                <button type="submit" class="btn btn-primary col-5 col-md-3 col-lg-2"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Student Sponsors</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="sponsor_table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Full Name</th>
                                                        <th scope="col">Short Name</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Address</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Phone</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_sponsor.*,
                                                    tbl_sponsor_category.spon_cat_name
                                                    FROM tbl_sponsor 
                                                    INNER JOIN tbl_sponsor_category ON tbl_sponsor.spon_cat_id=tbl_sponsor_category.spon_cat_id 
                                                    ORDER BY tbl_sponsor.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($spon=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th><?php echo $i++; ?></th>
                                                    <td><?php echo $spon['spon_full_name']; ?></td>
                                                    <td><?php echo $spon['spon_short_name']; ?></td>
                                                    <td><?php echo $spon['spon_cat_name']; ?></td>
                                                    <td><?php echo $spon['address']; ?></td>
                                                    <td><?php echo $spon['spon_email']; ?></td>
                                                    <td><?php echo $spon['phone']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $spon['spon_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $spon['spon_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $spon['spon_id']; ?>" <?php echo $spon['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $spon['spon_id']; ?>"></span>&nbsp;
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
                                                    <h5 class="modal-title">Updating <span id="s_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="s_id" name="s_id">
                                                    <input type="hidden" name="action" value="update">
                                                    <div class="form-group">
                                                        <label>Sponsor Category</label>
                                                        <select class="form-control select2" style="width:100%" name="s_spon_cat_id" id="s_spon_cat_id" >
                                                                <?php
                                                                    $sql_sp_cat=$conn->prepare("SELECT * FROM tbl_sponsor_category");
                                                                    $sql_sp_cat->execute();
                                                                    while($sp_cat=$sql_sp_cat->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $sp_cat['spon_cat_id']; ?>"><?php echo $sp_cat['spon_cat_name']; ?> </option>
                                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Full name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-user-tie"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="s_spon_full_name" id="s_spon_full_name" placeholder="Full name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Short Name (<i>optional</i>)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-pencil"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="s_spon_short_name" id="s_spon_short_name" placeholder="Short name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-city"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="s_address" id="s_address" placeholder="address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-e mail"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="sponsor_email" id="sponsor_email" placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-phone"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="s_phone" id="s_phone" placeholder="phone">
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
    $('#sponsor_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    //save academic cycle
    $("#save_sponsor").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Sponsors/sponsor_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_sponsor')[0].reset();
                        pop_up_success(data.message);
                        $('#sponsor_table').load(location.href + " #sponsor_table");
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
                url: "/files/Sponsors/sponsor_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#s_id").val(data_id);
                    $("#s_name").html(data.spon_full_name);
                    $("#s_spon_full_name").val(data.spon_full_name);
                    $("#s_spon_short_name").val(data.spon_short_name);
                    $("#sponsor_email").val(data.spon_email);
                    $("#s_address").val(data.address);
                    $("#s_phone").val(data.phone);
                    var selectElement = document.getElementById('s_spon_cat_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.spon_cat_id + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                    }
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
                url: "/files/Sponsors/sponsor_controller.php",
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
                        $('#sponsor_table').load(location.href + " #sponsor_table");
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
          
        // delete sponsor
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this sponsor's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Sponsors/sponsor_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                       $('#sponsor_table').load(location.href + " #sponsor_table");
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