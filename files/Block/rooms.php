<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Rooms</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Rooms</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-5 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Room</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="register" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="form-group">
                                        <label>Block</label>
                                        <select class="form-control select2" style="width:100%;" name="block_id" required>
                                            <option selected disabled>Select Block</option>
                                            <?php
                                                $sql = $conn->prepare("SELECT * FROM tbl_blocks WHERE status = 1");
                                                $sql->execute();
                                                while($block = $sql->fetch()){
                                                    ?>
                                            <option value="<?php echo $block['block_id']; ?>"><?php echo $block['block_full_name']." ".$block['block_short_name']; ?></option>
                                            <?php } ?>
                                        </select>
    
                                    </div>
                                    <div class="form-group">
                                        <label>Room name </label>
                                        <input type="text" class="form-control" name="room_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Room Capacity </label><br>
                                        <input type="number" class="form-control" name="room_size" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary"><span id="spinner0"></span>&nbsp;<span id="indicator0">Save</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-7 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4>Registered rooms</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="rooms">
                                    <thead>
                                        <tr> 
                                        	<th>#</th>
                                        	<th>Block</th>
                                        	<th>Room</th>
                                        	<th>Capacity</th>
                                        	<th>Action</th>
                                        </tr>
                                    </thead>									        
                                    <tbody>
                                        <?php
                            				$sql = $conn->prepare("SELECT tr.*, tb.block_full_name, tb.block_short_name FROM tbl_block_rooms tr INNER JOIN tbl_blocks tb ON tr.block_id = tb.block_id WHERE tb.status = 1");
                            				$sql->execute();
                            				$i = 1;
                        				    while($room = $sql->fetch()){
                        				?>
                    				    <tr>                              				        
                    				        <td><?php echo $i++; ?></td>
                    				        <td><?php echo $room['block_full_name']." ".$room['block_short_name']; ?></td>
                    						<td><?php echo $room['room_name']; ?></td>
                    						<td><?php echo $room['room_size']; ?></td>
                    						<td>
                                            <div class="buttons row">
                                                <button type="button" data-id="<?php echo $room['room_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $room['room_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                <label class="custom-switch btn btn-light btn-sm">
                                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $room['room_id']; ?>" <?php echo $room['status']==1?'checked':''; ?>>
                                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $room['room_id']; ?>"></span>&nbsp;
                                                </label>
                                            </div>
                    						</td>
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
                        <h5 class="modal-title">Update</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="room_id" name="room_id">
                        <div class="form-group">
                            <label>Block</label>
                            <select class="form-control select2" style="width:100%;" name="block_id" id="block_id" required>
                                <?php
                                    $sql = $conn->prepare("SELECT * FROM tbl_blocks WHERE status = 1");
                                    $sql->execute();
                                    while($block = $sql->fetch()){
                                        ?>
                                <option value="<?php echo $block['block_id']; ?>"><?php echo $block['block_full_name']." ".$block['block_short_name']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label>Room name </label>
                            <input type="text" class="form-control" name="room_name" id="room_name" required>
                        </div>
                        <div class="form-group">
                            <label>Room Capacity </label><br>
                            <input type="number" class="form-control" name="room_size" id="room_size" required>
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
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function(){
        $('#rooms').DataTable();
    
        $("#register").submit(function (e) {
            e.preventDefault();
            
            var formdata = new FormData(this);
            $("#spinner0").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#indicator0").html("saving");
            $.ajax({
                type: "POST",
                url: "/files/Block/room_controller.php",
                data: formdata,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#spinner0").fadeOut('fast');
                    $("#indicator0").html("save");
                    if(data.status == 200){
                        pop_up_success(data.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    else{
                        pop_wrong(data.message);
                    }
                },
                error:function(error){
                    $("#spinner0").fadeOut('fast');
                    $("#indicator0").html("save");
                    pop_wrong("Something Went Wrong!");
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
                url: "/files/Block/room_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#room_id").val(data_id);
                    $("#room_name").val(data.room_name);
                    $("#room_size").val(data.room_size);
                    var selectElement = document.getElementById('block_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.block_id + '"]');
                    if (selectedOption) {
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //update room
        $("#update_form").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Block/room_controller.php",
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
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#rooms').load(location.href + " #rooms");
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                    
                }
            });
        });
        
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                };
            swal({
                title: "Are you sure?",
                text: "You are about to change this room's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Block/room_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==200){
                               pop_up_success(data.message);
                               $('#rooms').load(location.href + " #rooms");
                            } else{
                                pop_wrong(data.message);
                            }
        				},
        				error:function(error){
        				    $('#spinner3_'+data_id).fadeOut('fast');
                            pop_wrong("Something went wrong!");
        				}
                    });
                } else {
                    swal("operation cancelled!!");
                }
            });
        });
    });
                            

    
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }
                    
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'Success',
            message: feedback,
            position: 'center'
        });
    }
</script>