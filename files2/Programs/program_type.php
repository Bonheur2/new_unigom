<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Program Types</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Program Type</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-5 col-lg-5">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Program Type</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_program" action="save_program" method="POST">
                                            <div class="card-body pb-0">
                                                <div class="form-group">
                                                    <label>Campus</label>
                                                    <select name="campus" id="campus" class="form-control select2" style="width:100%" required>
                                                        <?php
                                                            $sql_camp=$conn->prepare("SELECT * FROM tbl_campus");
                                                            $sql_camp->execute();
                                                            $i=1;
                                                            while($progs_camp=$sql_camp->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_camp['camp_id']; ?>"><?php echo $progs_camp['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Program Type Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="p_f_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Program Type Short Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="p_s_name" placeholder="Short name">
                                                    </div>
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
                        <div class="col-12 col-sm-7 col-lg-7">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Program Types</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="program_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Full Name</th>
                                                    <th scope="col">Campus</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT 
                                                                            tbl_program_type.*,
                                                                            tbl_campus.camp_full_name 
                                                                                FROM tbl_program_type 
                                                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                                ORDER BY tbl_program_type.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($progs=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $progs['prg_type_full_name']; ?></td>
                                                    <td><?php echo $progs['camp_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $progs['prg_type_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $progs['prg_type_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-sm btn-light">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $progs['prg_type_id']; ?>" <?php echo $progs['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $progs['prg_type_id']; ?>"></span>&nbsp;
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
                                                    <input type="hidden" id="e_id" name="e_id">
                                                    <div class="form-group">
                                                        <label>Campus</label>
                                                            <select name="campus" id="e_campus" class="form-control select2" style="width:100%" required>
                                                            <?php
                                                                $sqls_camp=$conn->prepare("SELECT * FROM tbl_campus");
                                                                $sqls_camp->execute();
                                                                $i=1;
                                                                while($progs_camp=$sqls_camp->fetch()){
                                                                    ?>
                                                            <option value="<?php echo $progs_camp['camp_id']; ?>"><?php echo $progs_camp['camp_full_name']; ?> </option>
                                                            <?php } ?>
                                                            </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Program Type Full Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_p_f_name" placeholder="Full name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Program Type Short Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-pencil"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_p_s_name" placeholder="Short name">
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
        
    //save program type
    $("#save_program").submit(function(e){
            e.preventDefault();
    
        var formData = {
            campus:$("#campus").val(),
            p_f_name:$("#p_f_name").val(),
            p_s_name:$("#p_s_name").val(),
            action:'register'
                };
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Programs/program_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_program')[0].reset();
                        $('#program_table').load(location.href + " #program_table");
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
      
        // delete program type
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this type's status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#program_table').load(location.href + " #program_table");
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
                    action:'view'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_id").val(data_id);
                    $("#e_p_f_name").val(data.prg_type_full_name);
                    $("#pr_name").html(data.prg_type_full_name);
                    $("#e_p_s_name").val(data.prg_type_short_name);
                    var selectElement = document.getElementById('e_campus');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.campus_id + '"]');
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
    
        var formData = {
            pr_id:$("#e_id").val(),
            campus:$("#e_campus").val(),
            p_f_name:$("#e_p_f_name").val(),
            p_s_name:$("#e_p_s_name").val(),
            action:'update'
                };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Programs/program_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        $('#program_table').load(location.href + " #program_table");
                        pop_up_success(data.message);
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