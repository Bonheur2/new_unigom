<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Students in hostel</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Students</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Add student</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_tenant" action="save_tenant" method="POST">
                                                        <input type="hidden" name="action" value="save_tenant">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group col-12 col-sm-3 col-lg-3">
                                                                <label>Building</label>
                                                                <select class="form-control select2" style="width:100%;" name="block_id" id="block_id" required>
                                                                    <option disabled selected></option>
                                                                    <?php
                                                                        $sql=$conn->prepare("SELECT * FROM tbl_hostel_block WHERE campus_id='".$camp_id."' AND status=1");
                                                                        $sql->execute();
                                                                        while($block=$sql->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $block['block_id']; ?>"><?php echo $block['block_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span id="spinner0"></span>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="cls" hidden>
                                                                <label>Room class</label>
                                                                <select class="form-control select2" style="width:100%;" name="class_id" id="class_id" required>
                                                                </select>
                                                                <span id="spinner1"></span>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="rc" hidden>
                                                                <label>Room</label>
                                                                <select class="form-control select2" style="width:100%;" name="room_id" id="room_id" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="stu" hidden>
                                                                <label>Student ID</label>
                                                                <select class="form-control select2" style="width:100%;" name="reg_no" required>
                                                                    <?php
                                                                        $sql1=$conn->prepare("SELECT r.reg_no, a.lname, a.fname FROM tbl_register_program_ug r INNER JOIN tbl_admission a ON r.reg_no=a.reg_no WHERE r.reg_active=1");
                                                                        $sql1->execute();
                                                                        while($stu=$sql1->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['fname']." ".$stu['lname']." | ".$stu['reg_no']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12" id="btn" hidden>
                                                                <center>
                                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Residential students</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="tenant_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Student ID</th>
                                                                <th scope="col">Names</th>
                                                                <th scope="col">Room code</th>
                                                                <th scope="col">Class</th>
                                                                <th scope="col">Building</th>
                                                                <th scope="col">Academic Year</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql2=$conn->prepare("SELECT t.*,
                                                                                             r.room_code,
                                                                                             c.class_name,
                                                                                             b.block_name,
                                                                                             a.fname,a.lname,
                                                                                             ac.acad_year
                                                                                        FROM tbl_tenants t
                                                                                            INNER JOIN tbl_admission a ON t.reg_no=a.reg_no
                                                                                            INNER JOIN tbl_acad_cycle ac ON t.acad_cycle_id=ac.acad_cycle_id
                                                                                            INNER JOIN tbl_hostel_room r ON t.room_id=r.room_id
                                                                                            INNER JOIN tbl_room_class c ON r.class_id=c.class_id
                                                                                            INNER JOIN tbl_hostel_block b ON r.block_id=b.block_id
                                                                                        WHERE t.status=1");
                                                                $sql2->execute();
                                                                $i=1;
                                                                while($tenant=$sql2->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $tenant['reg_no']; ?></td>
                                                                <td><?php echo $tenant['lname']." ".$tenant['fname']; ?></td>
                                                                <td><?php echo $tenant['room_code']; ?></td>
                                                                <td><?php echo $tenant['class_name']; ?></td>
                                                                <td><?php echo $tenant['block_name']; ?></td>
                                                                <td><?php echo $tenant['acad_year']; ?></td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $tenant['tenant_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $tenant['tenant_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                        <label class="custom-switch btn btn-sm btn-light">
                                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $tenant['tenant_id']; ?>" <?php echo $tenant['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $tenant['tenant_id']; ?>"></span>&nbsp;
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
                                                            <h5 class="modal-title">Updating <span id="t_name"></span></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="tenant_id" id="tenant_id">
                                                            <input type="hidden" name="action" value="update_tenant">
                                                            <div class="form-group col-12">
                                                                <label>Building</label>
                                                                <select class="form-control select2" style="width:100%;" name="block_id" id="e_block_id" required>
                                                                    <?php
                                                                        $sql=$conn->prepare("SELECT * FROM tbl_hostel_block WHERE campus_id='".$camp_id."' AND status=1");
                                                                        $sql->execute();
                                                                        while($block=$sql->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $block['block_id']; ?>"><?php echo $block['block_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span id="spinner-0"></span>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Room class</label>
                                                                <select class="form-control select2" style="width:100%;" name="class_id" id="e_class_id" required>
                                                                </select>
                                                                <span id="spinner-1"></span>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Room</label>
                                                                <select class="form-control select2" style="width:100%;" name="room_id" id="e_room_id" required>
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
                                           <!--end update modal-->
                                    </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#tenant_table').DataTable({     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });
       
    //load room classes
    $("#block_id").change(function () {
            var formdata = {
                id: $("#block_id").val(),
                action: "load_classes"
            };
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#cls").attr('hidden',true);
            $("#stu").attr('hidden',true);
            $("#rc").attr('hidden',true);
            $("#btn").attr('hidden',true);
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $("#class_id").empty();
                   if(data.length>0){
                       $("#class_id").append("<option disabled selected></option>");
                        $.each(data, function (index, value) {
                            $("#class_id").append("<option value='" + value.class_id + "'>" + value.class_name +"</option>");
                        });
                        $("#cls").removeAttr('hidden');
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });

    //load updating room classes
     $("#e_block_id").change(function () {
            var formdata = {
                id: $("#e_block_id").val(),
                action: "load_classes"
            };
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $("#e_class_id").empty();
                  if(data.length>0){
                      $("#e_class_id").append("<option disabled selected></option>");
                        $.each(data, function (index, value) {
                            $("#e_class_id").append("<option value='" + value.class_id + "'>" + value.class_name +"</option>");
                        });
                  }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
    //load available rooms
    $("#class_id").change(function () {
            var formdata = {
                id: $("#class_id").val(),
                action: "load_available_rooms"
            };
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#stu").attr('hidden',true);
            $("#rc").attr('hidden',true);
            $("#btn").attr('hidden',true);
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $("#room_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            var available=value.capacity-value.num_tenants;
                            $("#room_id").append("<option value='" + value.room_id + "'>" + value.room_code +" ( capacity: "+ value.capacity +" ) | "+ available +"</option>");
                        });
                        
                        $("#stu").removeAttr('hidden');
                        $("#rc").removeAttr('hidden');
                        $("#btn").removeAttr('hidden');
                   }
                   else{
                       $("#room_id").append("<option disabled selected>No room available</option>");
                       $("#rc").removeAttr('hidden');
                   }
                 },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });  
        
    //load updating available rooms
    $("#e_class_id").change(function () {
            var formdata = {
                id: $("#e_class_id").val(),
                action: "load_available_rooms"
            };
            $('#spinner-1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-1').fadeOut('fast');
                    $("#e_room_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            var available=value.capacity-value.num_tenants;
                            $("#e_room_id").append("<option value='" + value.room_id + "'>" + value.room_code +" ( capacity: "+ value.capacity +" ) | "+ available +"</option>");
                        });
                   }
                   else{
                       $("#e_room_id").append("<option disabled>No room available</option>");
                   }
                 },
                error:function(error){
                    $('#spinner-1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        }); 
    //save tenant
    $("#save_tenant").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Hostel/hostel_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#tenant_table').load(location.href + " #tenant_table");
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
                    action:'view_tenant'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_class_id").empty();
                    $.each(data[1], function (index, value) {
                        $("#e_class_id").append("<option value='" + value.class_id + "'>" + value.class_name +"</option>");
                    });
                        
                    $("#e_room_id").empty();
                    $.each(data[2], function (index, value) {
                        var available=value.capacity-value.num_tenants;
                        $("#e_room_id").append("<option value='" + value.room_id + "'>" + value.room_code +" ( capacity: "+ value.capacity +" ) | "+ available +"</option>");
                    });
                        
                    var selectElement = document.getElementById('e_block_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].block_id + '"]');
                    selectedOption.selected = true;
                    selectElement.prepend(selectedOption);
                        
                    var selectElement2 = document.getElementById('e_class_id');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].class_id + '"]');
                    selectedOption2.selected = true;
                    selectElement2.prepend(selectedOption2);
                    
                    var selectElement3 = document.getElementById('e_room_id');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].room_id + '"]');
                    selectedOption3.selected = true;
                    selectElement3.prepend(selectedOption3);

                    $("#tenant_id").val(data_id);
                    $("#t_name").html(data[0].reg_no+" | "+selectedOption3.textContent+" | "+selectedOption2.textContent+" | "+selectedOption.textContent)
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update class
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Hostel/hostel_controller.php",
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
                        $('#tenant_table').load(location.href + " #tenant_table");
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

    // delete room
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_tenant'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this contract's status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Hostel/hostel_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#tenant_table').load(location.href + " #tenant_table");
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