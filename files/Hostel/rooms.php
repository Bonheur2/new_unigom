<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Hostel rooms</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Hostel rooms</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New room</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_room" action="save_room" method="POST">
                                                        <input type="hidden" name="action" value="save_room">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group col-12 col-sm-3 col-lg-3">
                                                                <label>Building</label>
                                                                <select type="text" class="form-control select2" style="width:100%;" name="block_id" id="block_id" required>
                                                                    <option></option>
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
                                                                <select type="text" class="form-control select2" style="width:100%;" name="class_id" id="class_id" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="rc" hidden>
                                                                <label>Room code</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-home"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="room_code" placeholder="room code" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="cp" hidden>
                                                                <label>Capacity</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-warehouse"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" name="capacity" placeholder="Type here" min="1" required>
                                                                </div>
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
                                                    <h4>Registered rooms</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="room_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Room code</th>
                                                                <th scope="col">Building</th>
                                                                <th scope="col">Class</th>
                                                                <th scope="col">Capacity</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql2=$conn->prepare("SELECT tbl_hostel_room.*,
                                                                                            tbl_room_class.class_name,
                                                                                            tbl_hostel_block.block_name
                                                                                        FROM tbl_hostel_room
                                                                                            INNER JOIN tbl_room_class ON tbl_hostel_room.class_id=tbl_room_class.class_id
                                                                                            INNER JOIN tbl_hostel_block ON tbl_hostel_room.block_id=tbl_hostel_block.block_id
                                                                                        WHERE tbl_room_class.status=1 ORDER BY tbl_hostel_room.status ASC");
                                                                $sql2->execute();
                                                                $i=1;
                                                                while($room=$sql2->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $room['room_code']; ?></td>
                                                                <td><?php echo $room['block_name']; ?></td>
                                                                <td><?php echo $room['class_name']; ?></td>
                                                                <td><?php echo $room['capacity']; ?></td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $room['room_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $room['room_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                        <label class="custom-switch btn btn-sm btn-light">
                                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $room['room_id']; ?>" <?php echo $room['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $room['room_id']; ?>"></span>&nbsp;
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
                                                            <h5 class="modal-title">Updating <span id="r_name"></span></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="room_id" id="room_id">
                                                            <input type="hidden" name="action" value="update_room">
                                                            <div class="form-group col-12">
                                                                <label>Building</label>
                                                                <select type="text" class="form-control select2" style="width:100%;" name="block_id" id="e_block_id" required>
                                                                    <?php
                                                                        $sql4=$conn->prepare("SELECT * FROM tbl_hostel_block WHERE campus_id='".$camp_id."' AND status=1");
                                                                        $sql4->execute();
                                                                        while($block4=$sql4->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $block4['block_id']; ?>"><?php echo $block4['block_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span id="spinner-0"></span>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Room class</label>
                                                                <select type="text" class="form-control select2" style="width:100%;" name="class_id" id="e_class_id" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Room code</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-home"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="room_code" id="room_code" placeholder="room code" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Capacity</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-warehouse"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Type here" min="1" required>
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
    $('#room_table').DataTable({     
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
            $("#cp").attr('hidden',true);
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
                        $.each(data, function (index, value) {
                            $("#class_id").append("<option value='" + value.class_id + "'>" + value.class_name +"</option>");
                        });
                        $("#cls").removeAttr('hidden');
                        $("#cp").removeAttr('hidden');
                        $("#rc").removeAttr('hidden');
                        $("#btn").removeAttr('hidden');
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
    //save room
    $("#save_room").submit(function(e){
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
                        $('#save_room')[0].reset();
                        pop_up_success(data.message);
                        $('#room_table').load(location.href + " #room_table");
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
                    action:'view_room'
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
                        
                    var selectElement = document.getElementById('e_block_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].block_id + '"]');
                    selectedOption.selected = true;
                    selectElement.prepend(selectedOption);
                        
                    var selectElement2 = document.getElementById('e_class_id');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].class_id + '"]');
                    selectedOption2.selected = true;
                    selectElement2.prepend(selectedOption2);
                    
                    $("#room_id").val(data_id);
                    $("#room_code").val(data[0].room_code);
                    $("#capacity").val(data[0].capacity);
                    $("#r_name").html(data[0].room_code+" | "+selectedOption2.textContent+" | "+selectedOption.textContent)
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
                        $('#room_table').load(location.href + " #room_table");
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
                    action:'delete_room'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this room's status",
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
                        $('#room_table').load(location.href + " #room_table");
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