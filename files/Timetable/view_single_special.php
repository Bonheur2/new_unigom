<?php
include ('../../meet/con.php');

$splz = $_GET['splz'];
$level = $_GET['lev'];
$prg_mode = $_GET['prg'];
$intake = $_GET['intake'];

function getTimetable($conn, $splz, $level, $prg_mode, $intake) {
    $stmt = $conn->prepare("
        SELECT 
            s.schedule_id,
            s.start_date, 
            s.end_date,
            s.s_code,
            m.module_name,
            r.room_name,
            us.first_name,
            us.family_name
        FROM tbl_special_schedule s
            LEFT JOIN tbl_modules tm ON s.module_id = tm.module_id
            LEFT JOIN modules m ON tm.mod_id = m.module_id
            LEFT JOIN tbl_module_leader ml ON tm.module_id = ml.module_id
            LEFT JOIN tbl_block_rooms r ON s.room_id = r.room_id
            LEFT JOIN tbl_users us ON ml.staff_id = us.Identification
        WHERE s.splz_id = ? AND s.level_id = ? AND s.prg_mode = ? AND s.intake_id = ?
        ORDER BY s.start_date ASC
    ");
    $stmt->execute([$splz, $level, $prg_mode, $intake]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$timetables = getTimetable($conn, $splz, $level, $prg_mode, $intake);
?>

<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Timetable</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Timetable</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>Timetable</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div id="information">
                                <div class="card-body" style="border: 2px solid #0a7033; width: 95%; margin: auto; margin-bottom: 20px; border-radius: 5px">
                                    <div class="form-group col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" border="1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Period</th>
                                                        <th scope="col">Module</th>
                                                        <th scope="col">Room</th>
                                                        <th scope="col">Lecturer</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($timetables as $timetable){ ?>
                                                    <?php if($timetable['stype'] != 2){ ?>
                                                        <tr style="background-color: <?php echo $timetable['start_date']<date('Y-m-d')?'#FBCEB1':''; ?>">
                                                            <td><?php echo htmlspecialchars($timetable['start_date'])." - ".htmlspecialchars($timetable['end_date']); ?></td>
                                                            <td><?php echo htmlspecialchars($timetable['module_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($timetable['room_name']); ?> &nbsp; <i class="fa fa-refresh rswap" data-id="<?php echo $timetable['schedule_id']; ?>"></i>&nbsp;<span id="spinner_<?php echo $timetable['schedule_id']; ?>"></span></td>
                                                            <td><?php echo htmlspecialchars($timetable['first_name'].' '.$timetable['family_name']); ?></td>
                                                        </tr>
                                                    <?php }else{ ?>
                                                        <tr>
                                                            <td colspan="6" style="background-color: #0a7033; color: #FFF; text-align: center;"><b>Exam Week</b></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <?php 
                                                        $stmt = $conn->prepare("SELECT * FROM tbl_schedule_comments WHERE s_code = ?");
                                                        $stmt->execute([$timetable['s_code']]);
                                                        $data = $stmt->fetch();
                                                    ?>
                                                    
                                                    <tr>
                                                        <td colspan="4">
                                                            <p><?php echo $data['comment_1']; ?></p>
                                                            <p><?php echo $data['comment_2']; ?></p>
                                                            <p><?php echo $data['comment_3']; ?></p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
                        <h5 class="modal-title">Updating schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="schedule_id" name="schedule_id">
                        <input type="hidden" name="action" value="update_schedule_special">
                        <div class="form-group">
                            <label>Rooms</label>
                                <select class="form-control select2" style="width:100%" name="room_id" id="room_id" required>
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
        $(document).on('click', '.rswap', function () {
            var ident = $(this).data('id');
            var getData= {
                    schedule: ident,
                    action: 'load-available-rooms'
                };
            
            $('.rswap').attr('disabled', true);
            $('#spinner_'+ident).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('.rswap').removeAttr('disabled');
                    $("#room_id").empty();
                    $('#spinner_'+ident).fadeOut('fast');
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#room_id").append("<option value='" + value.room_id + "'>" + value.room_name +" ["+value.room_size+"]</option>");
                        });
                    }
                    $("#schedule_id").val(ident);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('.rswap').removeAttr('disabled');
				    $('#spinner_'+ident).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        //update faculty
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
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000)
                    }
                    else{
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
    
    function pop_info(feedback) {
        iziToast.warning({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>
