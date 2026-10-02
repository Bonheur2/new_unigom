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
                            <h3>Take Attendance</h3>
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
                                                    
                                                    <form id="add-attendance" action="attendance/attendance_info" method="POST">
                                                            <div class="card-body pb-0 row" style="display: flex;justify-content: center;">
                                                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                        <label>Module </label><br>
                                                                        <select class="select2" style="width:200px;" name="module_id" id="module_id">
                                                                            <option></option>
                                                                            <?php
                                                                                $block=$conn->prepare("SELECT * from modules where module_id
                                                                                IN (SELECT mod_id from tbl_modules where module_id
                                                                                IN (select module_id from tbl_module_leader where staff_id ='".$_SESSION['Identification']."'))");
                                                                                $block->execute();
                                                                                while($theBlock=$block->fetch()){
                                                                                ?>
                                                                                <option value="<?php echo $theBlock['module_id']; ?>"><?php echo $theBlock['module_name']; ?></option>
                                                                                <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            <input type="hidden" name="identity" id="identity" value="<?php echo $_SESSION['Identification'];?>">
                                                            
                                                            <div class="card-body pb-0 row" id="load-list-div" style="display: flex; justify-content: center;" hidden>
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" style="">
                                                                    <label>&nbsp;</label>
                                                                    <button type="button" class="btn btn-primary" id="load-list-btn"><span id="spinner0"></span>&nbsp;<span id="indicator0"><i class ="fa fa-hdd"></i> Load List</span></button>
                                                                </div>
                                                            </div>
                                                            
                                                            <div id="table"></div>
                                                            <div class="card-body pb-0 row" id="save-list-data" style="display: flex; justify-content: center;" hidden>
                                                                <div class="form-group  col-12 col-sm-4 col-lg-4" style="">
                                                                    <label>&nbsp;</label>
                                                                    <button type="submit" class="btn btn-primary" id="save-form-data"><span id="spinner0"></span>&nbsp;<span id="indicator0"><i class ="fa fa-file"></i> Save List</span></button>
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
                        
                        // submit form
                        $("#add-attendance").submit(function (e) {
                            e.preventDefault();
                            
                            if(($('#attendance-name').val()).length !==0){
                            var formdata = new FormData(this);
                            $.ajax({
                                type: "POST",
                                url: "../lecturer/attendance/attendance_info.php",
                                data: formdata,
                                mimeTypes:"multipart/form-data",
                                contentType:false,
                                processData:false,
                                success: function (data) {
                                    pop_up_request12("Attendance Taken!");
                                    
                                    setTimeout(function() {
                                        location.reload();
                                    }, 5000); // Reload the page after 3 seconds (3000 milliseconds)
                                                            
                                    $("#bloc").html(data);
                                    
                                },
                                error:function(error){
                                                    jQuery(function validation(){
                                                        swal("Error ", "Something Went Wrong!", "error", {
                                                            button: "Ok",
                                                        });
                                                    });
                                    setTimeout(function() {
                                        // location.reload();
                                    }, 3000);
                                }
                            });
                        }
                        else{
                            jQuery(function validation(){
                                swal("Please ", "Attendance Name is Needed!", "warning", {
                                    button: "Ok",
                                });
                            });
                            // setTimeout(function() {
                                // location.reload();
                            // }, 3000);                           
                        }
                        });      
                        
                        $('#load-list-btn').click(function () {
                                
                                var identity=$('#identity').val();
                                var name =$('#name').val();
                                var module =$('#module_id').val();
                                
                        			$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');
                        			
                                    $.get('../lecturer/attendance/attendance_list?module='+module +'&identity='+identity, function (data){
                                        $('#load-list-div').attr('hidden', true);
                                        $('#save-list-data').attr('hidden', false);
                                        $('#module').attr('hidden', true);
                                        
                                        $("#table").html(data);
                                         $('#loader1').hide();
                                    });
                            });
                            
                            $('#module_id').change(function(){
                                // alert();
                                $('#load-list-div').attr('hidden',false);
                                $('#save-list-data').attr('hidden', true);
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