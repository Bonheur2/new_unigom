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
                                            </tr>
                                            </thead>
                                            <tbody id="module_table_export">
                                            <?php
                                                $query = "SELECT modules.*,
                                                                tbl_department.dept_full_name,
                                                                tbl_program_type.prg_type_full_name
                                                                FROM modules 
                                                                INNER JOIN tbl_department ON 
                                                                modules.dept_id=tbl_department.dept_id
                                                                INNER JOIN tbl_program_type ON 
                                                                modules.prg_type=tbl_program_type.prg_type_id
                                                                WHERE tbl_program_type.campus_id='".$camp_id."' AND modules.dept_id IN(SELECT dept_id FROM tbl_department WHERE fac_id IN($faculty)')
                                                                AND modules.status = 1";
                                                
                                                $sql=$conn->prepare($query);
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