<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Levels</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Levels</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Level</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_level" action="save_level" method="POST">
                                            <div class="row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%" name="campus_id" id="campus_id" required>
                                                        <option></option>
                                                        <?php
                                                            $sql_campus=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                            $sql_campus->execute();
                                                            $i=1;
                                                            while($campus=$sql_campus->fetch()){
                                                                ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    &nbsp;<span id="spinner_prg"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program Type</label>
                                                    <select class="form-control select2" style="width:100%" name="p_type" id="p_type" required>
                                                        <option></option>
                                                       </select>
                                                    &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Level Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="l_f_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-1 col-lg-1">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Levels</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="level_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Full Name</th>
                                                    <th scope="col">Campus</th>
                                                    <th scope="col">Program Type</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_level.*,
                                                    tbl_program_type.prg_type_full_name,
                                                    tbl_campus.camp_full_name
                                                    FROM tbl_level 
                                                    INNER JOIN tbl_program_type ON tbl_level.prg_type=tbl_program_type.prg_type_id
                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                    WHERE tbl_program_type.status=1 ORDER BY tbl_level.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($lvs=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $lvs['level_full_name']; ?></td>
                                                    <td><?php echo $lvs['camp_full_name']; ?></td>
                                                    <td><?php echo $lvs['prg_type_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $lvs['level_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $lvs['level_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $lvs['level_id']; ?>" <?php echo $lvs['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $lvs['level_id']; ?>"></span>&nbsp;
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
                                                    <h5 class="modal-title">Updating <span id="f_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                        <input type="hidden" id="e_id" name="e_id">
                                                            <div class="form-group">
                                                                <label>Program Type</label>
                                                                    <select class="form-control select2" style="width:100%" name="e_l_type" id="e_l_type">
                                                                        <?php
                                                                            $sql_prg_u=$conn->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$camp_id."' AND status=1");
                                                                            $sql_prg_u->execute();
                                                                            $i=1;
                                                                            while($progs_level_u=$sql_prg_u->fetch()){
                                                                                ?>
                                                                        <option value="<?php echo $progs_level_u['prg_type_id']; ?>"><?php echo $progs_level_u['prg_type_full_name']." [".$progs_level_u['prg_type_short_name']."]"; ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Level Full Name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_l_f_name" placeholder="Full name" required>
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
    $('#level_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
        
    //save level
    $("#save_level").submit(function(e){
            e.preventDefault();
    
        var formData = {
            l_type:$("#p_type").val(),
            l_f_name:$("#l_f_name").val(),
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Levels/level_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_level')[0].reset();
                        pop_up_success(data.message);
                        $('#level_table').load(location.href + " #level_table");
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
      
        // delete level
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this level's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Levels/level_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                       $('#level_table').load(location.href + " #level_table");
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
                url: "/files/Levels/level_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_id").val(data_id);
                    $("#e_l_f_name").val(data.level_full_name);
                    var selectElement = document.getElementById('e_l_type');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.prg_type + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                      var selectedOptionText = selectedOption.textContent;
                      $("#f_name").html(data.level_full_name+" | "+selectedOptionText);
                    }
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update level
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = {
            pr_id:$("#e_id").val(),
            l_type:$("#e_l_type").val(),
            l_f_name:$("#e_l_f_name").val(),
            action:'update'
                };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Levels/level_controller.php",
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
                        $('#level_table').load(location.href + " #level_table");
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
           // new setting
    $("#campus_id").change(function(){
         $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
         $("#p_type").empty();
        var camp_id=$("#campus_id").val();
        var formData={
            camp_id:camp_id,
            action:"get_program_type"
        }
        $.ajax({
            url: "/files/Faculties/faculty_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
            $('#spinner_prg').fadeOut('fast');
            $("#p_type").append("<option></option>")
            $.each(data, function (index, value) {
                $("#p_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name+"</option>");
            });    
            },error: function(){
                $('#spinner_prg').fadeOut('fast');
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