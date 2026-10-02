<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Timetable hours</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Hours</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-5 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Entry</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="register" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="form-group">
                                        <label>Learning Mode</label>
                                        <select class="form-control select2" style="width:100%;" name="prg_mode_id">
                                            <?php
                                                $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode where status=1");
                                                $sql_mode->execute();
                                                $i=1;
                                                while($progs_mode=$sql_mode->fetch()){
                                                    ?>
                                            <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                            <?php } ?>
                                        </select>
    
                                    </div>
                                    <div class="form-group">
                                        <label>Start Time </label>
                                        <input type="time" class="form-control" name="hour_lower_limit" id="hour_lower_limit" value="<?php echo date("H:i"); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>End Time </label><br>
                                        <input type="time" class="form-control" name="hour_upper_limit" id="hour_upper_limit" value="<?php echo date("H:i", strtotime("+ 2 hours")); ?>">
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="hours">
                                    <thead>
                                        <tr> 
                                        	<th>#</th>
                                        	<th>Program mode</th>
                                        	<th>Start</th>
                                        	<th>End</th>
                                        	<th>Action</th>
                                        </tr>
                                    </thead>									        
                                    <tbody>
                                        <?php
                            				$hourDefined =$conn->prepare("SELECT th.*, prg.prg_mode_full_name FROM tbl_t_hours th INNER JOIN tbl_program_mode prg ON th.prg_mode_id = prg.prg_mode_id");
                            				$hourDefined->execute();
                            				$i = 1;
                        				    while($theHoursDefined =$hourDefined->fetch()){
                        				?>
                    				    <tr>                              				        
                    				        <td><?php echo $i++; ?></td>
                    				        <td><?php echo $theHoursDefined['prg_mode_full_name']; ?></td>
                    						<td><?php echo $theHoursDefined['hour_lower_limit']; ?></td>
                    						<td><?php echo $theHoursDefined['hour_upper_limit']; ?></td>
                    						<td>
                    						    <button type="button" data-id="<?php echo $theHoursDefined['hour_id'];?>" class="btn btn-primary btn-sm edit"><span id="spinner4_<?php echo $theHoursDefined['hour_id'];?>"></span>&nbsp;<i class="fa fa-edit"></i></button>
                    						    <button type="button" data-id="<?php echo $theHoursDefined['hour_id'];?>" class="btn btn-success btn-sm del"><span id="spinner3_<?php echo $theHoursDefined['hour_id'];?>"></span>&nbsp;<i class="fa fa-trash"></i></button>
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
                        <input type="hidden" id="hour_id" name="hour_id">
                        <div class="form-group">
                            <label>Learning Mode</label>
                            <select class="form-control select2" style="width:100%;" name="prg_mode_id" id="prg_mode_id">
                                <?php
                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode");
                                    $sql_mode->execute();
                                    $i=1;
                                    while($progs_mode=$sql_mode->fetch()){
                                        ?>
                                <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Start Time </label>
                            <input type="time" class="form-control" name="hour_lower_limit" id="hour_lower_limit_2">
                        </div>
                        <div class="form-group">
                            <label>End Time </label><br>
                            <input type="time" class="form-control" name="hour_upper_limit" id="hour_upper_limit_2">
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
        $('#hours').DataTable();
    
        $("#register").submit(function (e) {
            e.preventDefault();
            
            var formdata = new FormData(this);
            $("#spinner0").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#indicator0").html("saving");
            $.ajax({
                type: "POST",
                url: "/files/Timetable/controller.php",
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
                url: "/files/Timetable/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#hour_id").val(data_id);
                    $("#hour_lower_limit_2").val(data.hour_lower_limit);
                    $("#hour_upper_limit_2").val(data.hour_upper_limit);
                    var selectElement = document.getElementById('prg_mode_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.prg_mode_id + '"]');
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
        
        //update hour
        $("#update_form").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Timetable/controller.php",
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
                        $('#hours').load(location.href + " #hours");
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
        
        // delete
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                };
            swal({
                title: "Are you sure?",
                text: "You are about to delete this entry!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Timetable/controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==500){
                                pop_wrong(data.message); 
                            }
                            else if(data.status==200){
                               pop_up_success(data.message); 
                               $('#hours').load(location.href + " #hours");
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