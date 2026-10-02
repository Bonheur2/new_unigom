<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="campus" value="<?php echo $camp_id; ?>">
                        <section class="section">
                            <div class="section-header">
                                <h3>Academic Cycles</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Academic Cycles</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New Academic Cycle</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_acad" action="save_acad" method="POST">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Academic Year</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="acad_year" placeholder="Academic Year" required>
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
                                                    <h4>Registered Academic cycles</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="acad_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Academic year</th>
                                                                <th scope="col">status</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY status ASC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($progs=$sql->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $progs['acad_year']; ?></td>
                                                                <td><?php echo $progs['status']==1?"Active":"Finished"; ?></td>
                                                                <th>
                                                                      
                                                                <?php if($progs['status']==1){ ?>
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $progs['acad_cycle_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $progs['acad_cycle_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                                    
                                                                    <?php } else{ ?>
                                                                    
                                                                        <button type="button" class="btn btn-icon btn-warning btn-sm" disabled>closed</button>
                                                                   
                                                                    <?php } ?>
                                                                    
                                                                    <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input status_acc" data-id="<?php echo $progs['acad_cycle_id']; ?>" <?php echo $progs['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $progs['acad_cycle_id']; ?>"></span>&nbsp;
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
                                                    <h5 class="modal-title">Updating <span id="ac_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="e_id">
                                                    <div class="form-group">
                                                        <label>Academic Year</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_acad" placeholder="Academic Year" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <label class="custom-switch btn btn-light btn-sm">
                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" id="enrollment">
                                                        <span class="custom-switch-indicator"></span>&nbsp;<span id="spinner_en"></span>&nbsp;<span id="indicator_en">Module enrollment</span>
                                                    </label>
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
    $('#acad_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    //save academic cycle
    $("#save_acad").submit(function(e){
            e.preventDefault();
    
        var formData = {
            acad_year:$("#acad_year").val(),
            action:'register'
                };
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Acad_Year/acad_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_acad')[0].reset();
                        pop_up_success(data.message);
                        $('#acad_table').load(location.href + " #acad_table");
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
                url: "/files/Acad_Year/acad_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_id").val(data_id);
                    $("#e_acad").val(data.acad_year);
                    $("#ac_name").html(data.acad_year);
                    $("#enrollment").val(data_id);
                    if(data.enrollment==1){
                        $("#enrollment").attr('checked',true);
                    }
                    else{
                        $("#enrollment").attr('checked',false);
                    }
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = {
            ac_id:$("#e_id").val(),
            acad_year:$("#e_acad").val(),
            action:'update'
                };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Acad_Year/acad_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#acad_table').load(location.href + " #acad_table");
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
        
        //allow enrollment
        $("#enrollment").change(function(e){
            var formData = {
                    id:$(this).val(),
                    action:'enrollment'
                };
            $('#spinner_en').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_en').html("processing...");
            $.ajax({
                url: "/files/Acad_Year/acad_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner_en').fadeOut('fast');
                    $('#indicator_en').html("Module Enrollment");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner_en').fadeOut('fast');
                    $('#indicator_en').html("Module Enrollment");
                    pop_wrong("Something went wrong!");
                    
                }
            });
        });
        //change_status
        $(document).on('click','.status_acc',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'academic_status'
                    };
                  
            swal({
            title: "Are you sure?",
            text: "You are about to change this Academic's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Acad_Year/acad_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    console.log(data);
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                       $('#acad_table').load(location.href + " #acad_table");
                    }
                    else if(data.status==401){
                       pop_up_success(data.message);
                    }
                    
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong");
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