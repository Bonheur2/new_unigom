<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="campus" value="<?php echo $camp_id; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Partners</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Partners</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Partner</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_partner" action="save_partner" method="POST">
                                            <input type="hidden" name="action" value="register">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-md-4">
                                                    <label>Partner Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="par_full_name" id="par_full_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Partner Short Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="par_short_name" id="par_short_name" placeholder="Short name">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Country</label>
                                                    <select class="form-control select2" style="width:100%;" name="par_country" id="par_country">
                                                            <?php
                                                                $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                                                $sql_cntr->execute();
                                                                $i=1;
                                                                while($cntr=$sql_cntr->fetch()){
                                                                    ?>
                                                            <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                                            <?php } ?>
                                                    </select><span id="spinner10"></span>
                                                </div>
                                                <div class="form-group col-md-4" style="display:none;" id="par_province_loc">
                                                    <label>Province</label>
                                                    <select class="form-control select2" style="width:100%;" name="par_province" id="par_province"></select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label>City</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-city"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="par_city" id="par_city" placeholder="city">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>MOU Reference</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-signature"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="par_mou_ref" id="par_mou_ref" placeholder="MOU Reference" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Logo</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-file"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" name="par_logo" id="par_logo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer pt-">
                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Partners</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="partner_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Full Name</th>
                                                    <th scope="col">Short Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT * FROM tbl_partner ORDER BY par_active ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($pars=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $pars['par_full_name']; ?></td>
                                                    <td><?php echo $pars['par_short_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $pars['par_id']; ?>" class="btn btn-icon btn-success btn-sm view"><span id="spinner2_<?php echo $pars['par_id']; ?>"></span>&nbsp;<i class="fas fa-eye"></i>&nbsp; view</button>
                                                            <button type="button" data-id="<?php echo $pars['par_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $pars['par_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $pars['par_id']; ?>" <?php echo $pars['par_active']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $pars['par_id']; ?>"></span>&nbsp;
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
                                                    <h5 class="modal-title">Updating <span id="pr_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="pr_id" name="pr_id">
                                                    <input type="hidden" name="action" value="update">
                                                    <div class="card-body pb-0 row">
                                                    <div class="form-group col-md-12">
                                                        <label>Partner Full Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_par_full_name" id="e_par_full_name" placeholder="Full name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Partner Short Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-pencil"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_par_short_name" id="e_par_short_name" placeholder="Short name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Country</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_par_country" id="e_par_country">
                                                                <?php
                                                                    $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                                                    $sql_cntr->execute();
                                                                    $i=1;
                                                                    while($cntr=$sql_cntr->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                                                <?php } ?>
                                                        </select><span id="spinner100"></span>
                                                    </div>
                                                    <div class="form-group col-md-12" id="e_par_province_loc" style="display:none;">
                                                        <label>Province</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_par_province" id="e_par_province">
                                                            <?php
                                                                $sql_prv=$conn->prepare("SELECT * FROM provinces");
                                                                $sql_prv->execute();
                                                                $i=1;
                                                                while($prv=$sql_prv->fetch()){
                                                                ?>
                                                                <option value="<?php echo $prv['provincecode']; ?>"><?php echo $prv['provincename']; ?> </option>
                                                                <?php } ?> 
                                                        </select>
                                                        </div>
                                                    </div>
    
                                                    <div class="form-group col-md-12">
                                                        <label>City</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-city"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_par_city" id="e_par_city" placeholder="city">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>MOU Reference</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-signature"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="e_par_mou_ref" id="e_par_mou_ref" placeholder="MOU Reference" required>
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
        
    //save partner
    $("#save_partner").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Partners/partner_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_partner')[0].reset();
                        $('#partner_table').load(location.href + " #partner_table");
                        pop_up_success(data.message);
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
      
        // delete partner
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this partner's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Partners/partner_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                    //   $('#partner_table').load(location.href + " #partner_table");
                    window.location.reload();
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
           else {
                swal("Operation cancelled!!");
            }
        });
        });

//load provinces
        $('#par_country').change(function () {
            if($("#par_country").val()==160){
            var getData= {
                    action:'load_provinces'
                    };
            $('#spinner10').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Partners/partner_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner10').fadeOut('fast');
                        $.each(data, function (index, value) {
                            $("#par_province").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                        });
                        $("#par_province_loc").css({'display':'block'});

				},
				error:function(error){
				    $('#spinner10').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
        });
        
        $('#e_par_country').change(function () {
            if($("#e_par_country").val()==160){
            var getData= {
                    action:'load_provinces'
                    };
            $('#spinner100').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Partners/partner_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner100').fadeOut('fast');
                        $.each(data, function (index, value) {
                            $("#par_province").append("<option value='" + value.province_id + "'>" + value.province_name+"</option>");
                        });
                        $("#par_province_loc").css({'display':'block'});

				},
				error:function(error){
				    $('#spinner100').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
            else{
               $("#e_par_province").empty();
               $("#e_par_province_loc").css({'display':'none'});
            }
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
                url: "/files/Partners/partner_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $('#pr_id').val(data_id);
                    $('#pr_name').html(data.par_full_name);
                    $('#e_par_full_name').val(data.par_full_name);
                    $('#e_par_short_name').val(data.par_short_name);
                    $('#e_par_city').val(data.par_city);
                    $('#e_par_mou_ref').val(data.par_mou_ref);
                    if(data.par_country==160){
                        $('#e_par_province_loc').css({'display':'block'});
                        var selectElement = document.getElementById('e_par_province');
                        var selectedOption = selectElement.querySelector('option[value="' + data.par_province + '"]');
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    var selectElement2 = document.getElementById('e_par_country');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.par_country + '"]');
                    selectedOption2.selected = true;
                    selectElement2.prepend(selectedOption2);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
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