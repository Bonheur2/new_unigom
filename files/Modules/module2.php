    <!-- Start app main Content -->
            <div class="main-content">
                <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
                <section class="section">
                    <div class="section-header">
                        <h3>Modules</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Module</a></div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>New module</h4>
                                        <div class="card-header-action">
                                            <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="collapse hide" id="mycard-collapse">
                                        <form id="save_module" action="save_module" method="POST">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Program Type</label><br>
                                                    <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type">
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT 
                                                                            tbl_program_type.*,
                                                                            tbl_campus.camp_full_name 
                                                                                FROM tbl_program_type 
                                                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                                ORDER BY tbl_program_type.status ASC");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3"id="dept" hidden>
                                                    <label>Department</label>
                                                    <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3" id="mn" hidden>
                                                    <label>Module Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="module_name" placeholder="module name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3" id="mc" hidden>
                                                    <label>Module Code</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-lock"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="module_code" placeholder="module code" required>
                                                    </div>
                                                </div>
                                            <div class="form-group col-12" id="dbtn" hidden>
                                                <div class="input-group" style="display:flex; flex-direction:row; justify-content:center;">
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                    &nbsp;<button type="button" class="btn btn-icon btn-primary btn-sm upload"><i class="fas fa-upload"></i> Upload CSV</button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Modules</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="module_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Module Code</th>
                                                    <th scope="col">Module Name</th>
                                                    <th scope="col">Program Type</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="module_table_export">
                                                <?php
                                                    $sql=$conn->prepare("SELECT modules.*,
                                                                        tbl_department.dept_full_name,
                                                                        tbl_program_type.prg_type_full_name
                                                                        FROM modules 
                                                                        INNER JOIN tbl_department ON 
                                                                        modules.dept_id=tbl_department.dept_id
                                                                        INNER JOIN tbl_program_type ON 
                                                                        modules.prg_type=tbl_program_type.prg_type_id
                                                                        WHERE tbl_program_type.campus_id='".$camp_id."' ORDER BY modules.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($mods=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $mods['module_code']; ?></td>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <td><?php echo $mods['prg_type_full_name']; ?></td>
                                                    <td><?php echo $mods['dept_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons" style="display:flex; flex-direction:row;">
                                                            <button type="button" data-id="<?php echo $mods['module_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $mods['module_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-sm btn-light">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $mods['module_id']; ?>" <?php echo $mods['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $mods['module_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-icon btn-secondary btn-sm export"><span id="spinner5"></span>&nbsp;<i class="fas fa-download"></i> Export CSV</button>&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><span id="m_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="m_id" name="m_id">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12">
                                                            <label>Program Type</label><br>
                                                            <select class="form-control select2" style="width:100%" name="m_prg_type" id="m_prg_type">
                                                                <?php
                                                                    $sql_prg=$conn->prepare("SELECT 
                                                                                    tbl_program_type.*,
                                                                                    tbl_campus.camp_full_name 
                                                                                        FROM tbl_program_type 
                                                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                                        ORDER BY tbl_program_type.status ASC");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner0"></span>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-12 col-lg-12">
                                                            <label>Department</label>
                                                            <select class="form-control select2" style="width:100%" name="m_dept_id" id="m_dept_id" required>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-12 col-lg-12">
                                                            <label>Module Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="m_module_name" placeholder="module name" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-12 col-lg-12">
                                                            <label>Module Code</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-lock"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="m_module_code" placeholder="module code" required>
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
                                </div>
                            </form>
                                   <!--end update modal-->
                                   
                                <!--upload modal-->
                                <form action="upload_form" id="upload_form" method="POST" enctype="multipart/form-data">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload CSV file</span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12">
                                                            <input type="hidden" name="action" value="upload">
                                                            <input type="hidden" id="camp" value="<?php echo $camp_id; ?>">
                                                            <input class="form-control" type="file" name="csv_file" accept=".csv" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-whitesmoke br">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-icon btn-success btn-sm export_format"><span id="spinner9"></span>&nbsp;<i class="fas fa-download"></i> <span id="indicator9">Get Format</span></button>
                                                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner20"></span>&nbsp;<span id="indicator20">Upload</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#module_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
     $('.upload').click(function () {
        $("#uploadModal").modal('show');
     });
//load departments
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                type: p_type,
                action: "load_departments"
            };
             $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                     $('#mn').attr('hidden',true);
                     $('#mc').attr('hidden',true);
                     $('#dbtn').attr('hidden',true);
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#dept').attr('hidden',false);
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
//show inputs
    $("#dept_id").change(function () {
        $('#mn').attr('hidden',false);
        $('#mc').attr('hidden',false);
        $('#dbtn').attr('hidden',false);
    });
    
    
//save module
    $("#save_module").submit(function(e){
            e.preventDefault();
    
        var formData = {
            prg_type:$("#prg_type").val(),
            dept_id:$("#dept_id").val(),
            module_code:$("#module_code").val(),
            module_name:$("#module_name").val(),	
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_module')[0].reset();
                        pop_up_success(data.message);
                        $('#module_table').load(location.href + " #module_table");
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

//upload modules
$("#upload_form").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this);
    formData.append('prg_type', $('#prg_type').val());
    formData.append('dept', $('#dept_id').val());
    $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $('#indicator20').html("Saving...");
    
    $.ajax({
        url: "/files/Modules/module_controller.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            $('#spinner20').fadeOut('fast');
            $('#indicator20').html("Save");
            if(data == 200){
                $('#upload_form')[0].reset();
                $("#uploadModal").modal('hide');
                pop_up_success("modules uploaded successfully!");
                $('#module_table').load(location.href + " #module_table");
            }
            if(data == 401){
                pop_wrong("Some modules are no inserted!");
            }
            if(data == 402){
                pop_wrong("Some modules already exists!");
            }
            if(data == 500){
                pop_wrong("Something went wrong!");
            }
        },
        error: function(){
            $('#spinner20').fadeOut('fast');
            $('#indicator20').html("Save");
            pop_wrong("Something went wrong!");
        }
    });
});

      
// delete department
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this module status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                        pop_up_success(data.message);
                       $('#module_table').load(location.href + " #module_table");
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
                url: "/files/Modules/module_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#m_id").val(data_id);
                    $("#m_module_code").val(data[0].module_code);
                    $("#m_module_name").val(data[0].module_name);
                    var selectElement = document.getElementById('m_prg_type');
                    var selectElement2 = document.getElementById('m_dept_id');
                    $("#m_dept_id").empty();
                    $.each(data[1], function (index, value) {
                            $("#m_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" [ "+value.dept_short_name+" ]</option>");
                        });
                    // // Set selected values
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].dept_id + '"]');
                    selectedOption.selected = true;
                    selectedOption2.selected = true;
                    $("#m_name").html(data[0].module_name+" | "+data[0].module_code);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
//update module
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = {
            mod_id:$("#m_id").val(),
            prg_type:$("#m_prg_type").val(),
            dept_id:$("#m_dept_id").val(),
            module_code:$("#m_module_code").val(),
            module_name:$("#m_module_name").val(),
            action:'update'
                };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#module_table').load(location.href + " #module_table");
                        // $('#module_table').DataTable().draw();
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
    
        //export modules format
        $('.export_format').click(function () {
            var formData = {
                action:'export_init'
                }
            $('#spinner9').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator9').html("Exporting...");
            
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                success: function(data){
                    $('#spinner9').fadeOut('fast');
                    $('#indicator9').html("Get Format");
                    window.location.href = '/files/Modules/modules_format.csv';
                    pop_up_success("Modules exported successfully!");
                },
                error: function(){
                    $('#spinner9').fadeOut('fast');
                    $('#indicator9').html("Get Format");
                    pop_wrong("Something went wrong!");
                }
            });
    
        });
        
        //export modules
        $('.export').click(function () {
            var formData = {
                action:'export'
                }
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator5').html("Exporting...");
            
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                success: function(data){
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("Export");
                    window.location.href = '/files/Modules/modules.csv';
                    pop_up_success("Modules exported successfully!");
                },
                error: function(){
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("Export");
                    pop_wrong("Something went wrong!");
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