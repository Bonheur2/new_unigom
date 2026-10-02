<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1 style="text-align:left">Generated Time tables</h1>
                            <div class="section-header-breadcrumb">
                                
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab">
                                                    
                                                    <!--<form id="add-table" action="tables/bloc_info" method="POST">-->
                                                        <div style='padding: 20px;border-radius: 8px;border: 2px solid #B59820;width:100%;margin-top:1%;margin-bottom:1%;'>
                                                             <div class="card-body pb-0 row" style="display: flex;justify-content: center;">
                                                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                        <label>Term </label><br>
                                                                        <select class="form-control select2" name="term" id="term">
                                                                            <option></option>
                                                                            <?php
                                                                                $block=$conn->prepare("SELECT * from tbl_semester where 1");
                                                                                $block->execute();
                                                                                while($theBlock=$block->fetch()){
                                                                                ?>
                                                                                <option value="<?php echo $theBlock['sem_id']; ?>"><?php echo $theBlock['semester']; ?></option>
                                                                                <?php } ?>
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
                                                                    <button type="button" class="btn btn-primary" id="table-btn"><span id="spinner"></span>&nbsp;<span id="indicator"><i class ="fa fa-table"></i> View Tables</span></button>
                                                                </div> 
                                                            </div> 
                                                            <div id="table"></div>
                                                            </div>
                                                    <!--</form>-->
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
                    $("#swapp-hours").submit(function (e) {
                        e.preventDefault();
                        var formdata = new FormData(this);
                        $.ajax({
                            type: "POST",
                            url: "tables/table_info.php",
                            data: formdata,
                            mimeTypes:"multipart/form-data",
                            contentType:false,
                            processData:false,
                            success: function (data) {
                                if(data ==1){
                                    pop_up_request12("Hours Swapped!");
                                    
                                    setTimeout(function() {
                                        location.reload();
                                    }, 5000); // Reload the page after 3 seconds (3000 milliseconds)
                                                        
                                    $("#bloc").html(data);
                                }
                                else{
                                    pop_wrong('Querry/Page Problem');
                                                            
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000); // Reload the page after 3 seconds (3000 milliseconds)
                                                            
                                    $("#bloc").html(data);                        
                                }
                            },
                            error:function(error){
                                pop_wrong("Something went wrong");
                                setTimeout(function() {
                                    // location.reload();
                                }, 3000);
                            }
                        });
                    });      
                        
                        
                        // click the btn
                        $('#table-btn').click(function () {
                            
                            var identity=$('#identity').val();
                            var spec =$('#spec').val();
                            var level =$('#level').val();
                            var name =$('#name').val();
                            var term =$('#term').val();
                            
                    			$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');
                    			
                                $.get('tables/all_time_tables?term='+term, function (data){
                                    $('#btn-save').attr('hidden', true);
                                    $("#table").html(data);
                                     $('#loader1').hide();
                                });
                        });
                        
                        $('#term').change(function(){
                            $('#btn-save').attr('hidden',false);
                        });
                        
                                // edit on modal
        $('#edit-spec').change(function () {
            var getData= {
                    spec:$(this).val(),
                    action:'table-levels'
                    };
            // $('#spec-terms').attr('hidden',true);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    // $("#spec-terms").attr('hidden',false);
                    $("#edit-level").empty();
                    $('#spinner20').fadeOut('fast');
                    if(data.length>0){
                        $("#edit-level").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#edit-level").append("<option value='" + value.level_id + "'>" + value.level_id +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner20').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        // levels
        // edit on modal
        $('#edit-level').change(function () {
            var getData= {
                    spec:$('#edit-spec').val(),
                    level:$(this).val(),
                    action:'table-terms'
                    };
            // $('#spec-terms').attr('hidden',true);
            $('#spinner30').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    // $("#spec-terms").attr('hidden',false);
                    $("#edit-term").empty();
                    $('#spinner30').fadeOut('fast');
                    if(data.length>0){
                        $("#edit-term").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#edit-term").append("<option value='" + value.term_id + "'>" + value.term_id +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner30').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        }); 
        
       $('#edit-term').change(function () {
            var getData= {
                    spec:$('#edit-spec').val(),
                    level:$('#edit-level').val(),
                    term:$(this).val(),
                    action:'table-days'
                    };
            // $('#spec-terms').attr('hidden',true);
            $('#spinner60').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    // $("#spec-terms").attr('hidden',false);
                    $("#edit-day").empty();
                    $("#edit-day-to").empty();
                    $('#spinner60').fadeOut('fast');
                    if(data.length>0){
                        $("#edit-day").append("<option disabled selected>--choose one--</option>");
                        $("#edit-day-to").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#edit-day").append("<option value='" + value.day_id + "'>" + value.day_name +"</option>");
                            $("#edit-day-to").append("<option value='" + value.day_id + "'>" + value.day_name +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner60').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });         
        
        // move from
        $('#edit-day').change(function () {
            var getData= {
                    spec:$('#edit-spec').val(),
                    level:$('#edit-level').val(),
                    term:$('#edit-term').val(),
                    day:$(this).val(),
                    action:'table-hours'
                    };
            // $('#spec-terms').attr('hidden',true);
            $('#spinner40').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    // $("#spec-terms").attr('hidden',false);
                    $("#move-from").empty();
                    $('#spinner40').fadeOut('fast');
                    if(data.length>0){
                        $("#move-from").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#move-from").append("<option value='" + value.hour_id + "'>" + value.hour_id +"</option>");
                        });
                    }
                    else{
                       pop_wrong("Something went wrong!") 
                    }
				},
				error:function(error){
				    $('#spinner40').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        }); 
        
        // move to
        $('#edit-day-to').change(function () {
            var getData= {
                    spec:$('#edit-spec').val(),
                    level:$('#edit-level').val(),
                    day:$('#edit-day-to').val(),
                    term:$('#edit-term').val(),
                    action:'table-unique-hours'
                    };
            // $('#spec-terms').attr('hidden',true);
            $('#spinner70').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    // $("#spec-terms").attr('hidden',false);
                    $("#move-to").empty();
                    $('#spinner70').fadeOut('fast');
                    if(data.length>0){
                        $("#move-to").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#move-to").append("<option value='" + value.hour_id + "'>" + value.hour_id +"</option>");
                        });
                    }
                    else{
                       pop_wrong("Something went wrong!") 
                    }
				},
				error:function(error){
				    $('#spinner70').fadeOut('fast');
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