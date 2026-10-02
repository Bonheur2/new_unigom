<?php
/**
 * 
 * Author @macintosh
 */
?>
<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Attendance</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Download Attendance</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab" style='padding: 20px;border-radius: 8px;border: 2px solid #B59820;width:100%;margin-top:1%;margin-bottom:1%;'>
                                                    
                                                    <form id="add-attendance" action="schedules/attendance_info" method="POST">
                                                        <div class="card-body pb-0 row" style="">
                                                                
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="date">
                                                                    <label>Month </label><br>
                                                                    <input type="month" class="form-control" name="month-id" id="month-id">
                                                                     <span id="spinner0"></span>
                                                                </div>
                                                                
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                                                    <label>Class </label><br>
                                                                    <select class="form-control" name="spec-id" id="spec-id">
                                                                    </select>
                                                                    <span id="spinner1"></span>
                                                                    <!--<input type="text" class="form-control" name="stu">-->
                                                                </div>
                                                                
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="attendance" hidden>
                                                                    <label>Attendance</label><br>
                                                                    <select class="form-control" name="attendance-name" id="attendance-name">
                                                                    </select>
                                                                    <!--<input type="text" class="form-control" name="stu">-->
                                                                </div>
                                                            </div>                                                                
                                                            <input type="hidden" name="identity" id="identity" value="<?php echo $_SESSION['Identification'];?>">
                                                            <input type="hidden" name="name" id="name" value="<?php echo $_SESSION['f_name'];?>">
                                                            
                                                            <!--start student-->
                                                            <?php
                                                            $Querry =$conn->prepare("select *from tbl_register_program_ug where reg_no ='".$_SESSION['Identification']."' and reg_active =1");
                                                            $Querry->execute();
                                                            $theQuerry =$Querry->fetch();
                                                            ?>
                                                         
                                                            <input type="hidden" name="spec" id="spec" value="<?php echo $theQuerry['splz_id'];?>">
                                                            <input type="hidden" name="level" id="level" value="<?php echo $theQuerry['level_id'];?>">
                                                            <!--end student-->
                                                            
                                                            <div class="card-body pb-0 row" id="btn-save" style="display: flex;justify-content: center;" hidden>
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" style="">
                                                                    <label>&nbsp;</label>
                                                                    <button type="button" class="btn btn-primary" id="table-btn"><span id="spinner"></span>&nbsp;<span id="indicator"><i class ="fa fa-table"></i> Load Attendance</span></button>
                                                                </div> 
                                                            </div> 
                                                            <div id="table"></div>
                                                            <div class="card-body pb-0 row" id="list-btn" style="display: flex;justify-content: center;" hidden>
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" style="">
                                                                    <label>&nbsp;</label>
                                                                    <button type="submit" class="btn btn-primary" id="save-form-data"><span id="spinner0"></span>&nbsp;<span id="indicator0"><i class ="fa fa-hdd"></i> Save List</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12" id="bloc">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <?php
                include('swapp_hours.php');
                ?>
                <!--javascript-->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
                <script>
                    $(document).ready(function(){
                        
                        $('#table-btn').click(function () {
                                
                                var identity=$('#identity').val();
                                var spec =$('#spec-id').val();
                                var month =$('#month-id').val();
                                var name =$('#attendance-name').val();
                                
                        			$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');
                        			
                                    $.get('attendance/attendance_sheet?spec='+spec +'&month='+month +'&name='+name +'&identity='+identity, function (data){
                                        $('#btn-save').attr('hidden', true);
                                        $('#list-btn').attr('hidden', true);
                                        $('#date').attr('hidden', true);
                                        $('#attendance').attr('hidden', true);
                                        $('#spec').attr('hidden', true);
                                        
                                        $("#table").html(data);
                                         $('#loader1').hide();
                                    });
                            });
                            
                            $('#month-id').change(function(){
                                
                                var getData= {
                                            month:$(this).val(),
                                            id:$('#identity').val(),
                                            action:'month-classes'
                                            };
                                    $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                                    $.ajax({
                                        type: "POST",
                                        url: "../../files/Timetable/timetable_controller.php",
                                        data: getData,
                                        dataType:"json",
                                        success:function(data){
                                            $('#spec').attr('hidden', false);
                                            $("#spec-id").empty();
                                            $('#spinner0').fadeOut('fast');
                                            if(data.length>0){
                                                $("#spec-id").append("<option disabled selected>--choose one--</option>");
                                                $.each(data, function (index, value) {
                                                        $("#spec-id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                                                    });
                                            }
                        				},
                        				error:function(error){
                        				    $('#spinner0').fadeOut('fast');
                                            pop_wrong("Something went wrong!")
                        				}
                                    });
                            });
                            
                            $('#attendance-name').change(function(){
                                $('#btn-save').attr('hidden',false);
                            });
                            
                            // edit on modal
                                $('#spec-id').change(function () {
                                    var getData= {
                                                spec:$(this).val(),
                                                month:$('#month-id').val(),
                                                id:$('#identity').val(),
                                                action:'attendance-names'
                                            };
                                    $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                                    $.ajax({
                                        type: "POST",
                                        url: "../../files/Timetable/timetable_controller.php",
                                        data: getData,
                                        dataType:"json",
                                        success:function(data){
                                            $('#attendance').attr('hidden', false);
                                            $("#attendance-name").empty();
                                            $('#spinner1').fadeOut('fast');
                                            if(data.length>0){
                                                $("#attendance-name").append("<option disabled selected>--choose one--</option>");
                                                $.each(data, function (index, value) {
                                                        $("#attendance-name").append("<option value='" + value.attendance_session + "'>" + value.attendance_session +"</option>");
                                                    });
                                            }
                        				},
                        				error:function(error){
                        				    $('#spinner1').fadeOut('fast');
                                            pop_wrong("Something went wrong!")
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
            title: 'Message',
            message: feedback,
            position: 'center'
        });
    }
    // Notification  
    function pop_up_request12(feedback) {
        jQuery(function validation(){
            swal("Done ", feedback, "success", {
                button: "Ok",
            });
        });
    }
</script>