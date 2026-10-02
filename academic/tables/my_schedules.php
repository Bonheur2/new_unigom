<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1 style="text-align:left">Welcome <?php echo $_SESSION['Identification'];?> </h1>
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
                                                    
                                                    <form id="add-table" action="rooms/bloc_info" method="POST">
                                                        <div style='padding: 20px;border-radius: 8px;border: 2px solid #B59820;width:100%;margin-top:1%;margin-bottom:1%;background-color:RGB(144, 238, 144);'>
                                                             <div class="card-body pb-0 row" style="display: flex;justify-content: center;">
                                                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                        <label>Term </label><br>
                                                                        <select class="form-control" name="term" id="term">
                                                                            <option></option>
                                                                            <?php
                                                                                $block=$conn->prepare("SELECT * from tbl_semester WHERE 1");
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
                                                                    <button type="button" class="btn btn-primary" id="table-btn"><span id="spinner"></span>&nbsp;<span id="indicator"><i class ="fa fa-table"></i> Your Table</span></button>
                                                                </div> 
                                                            </div> 
                                                            <div id="table"></div>
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
                <!--javascript-->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
                <script>
                    $(document).ready(function(){
                        
                        // submit
                        $("#add-table").submit(function (e) {
                         e.preventDefault();
                            var formdata = new FormData(this);
                            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
                            // $('#indicator').html("Loading...");
                            $("#load_btn").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "rooms/bloc_info.php",
                                data: formdata,
                                mimeTypes:"multipart/form-data",
                                contentType:false,
                                processData:false,
                                success: function (data) {
                                    if(data ==1){
                                        $('#spinner').fadeOut('fast');
                                        // $('#indicator').html("Add");
                                        $("#load_btn").attr("disabled", false);
                                        pop_up_success('Data saved');
                                        
                                        setTimeout(function() {
                                            location.reload();
                                        }, 5000); // Reload the page after 3 seconds (3000 milliseconds)
                                        
                                       $("#bloc").html(data);
                                    }
                                    else{
                                        // $('#indicator').html("Add");
                                        $("#load_btn").attr("disabled", false);
                                        pop_wrong('Data not saved');
                                        
                                        setTimeout(function() {
                                            location.reload();
                                        }, 3000); // Reload the page after 3 seconds (3000 milliseconds)
                                        
                                       $("#bloc").html(data);                        
                                    }
                                 },
                                error:function(error){
                                    $('#spinner').fadeOut('fast');
                                    // $('#indicator').html("Load transcript");
                                    pop_wrong("Something went wrong");
                                    $("#load_btn").attr("disabled", false);
                                }
                                 
                            });
                        });
                        
                        // delete
                        $('#table-btn').click(function () {
                            
                            var identity=$('#identity').val();
                            var spec =$('#spec').val();
                            var level =$('#level').val();
                            var name =$('#name').val();
                            var term =$('#term').val();
                            
                    			$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');
                    			
                                $.get('schedules/actual_table?identification='+identity +'&specialization='+spec +'&level='+level +'&name='+name +'&term='+term, function (data){
                                    $('#btn-save').attr('hidden', true);
                                    $("#table").html(data);
                                     $('#loader1').hide();
                                });
                        });
                        
                        $('#term').change(function(){
                            $('#btn-save').attr('hidden',false);
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

</script>