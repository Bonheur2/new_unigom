                    <!-- Start app main Content -->
                        <div class="main-content">
                            <section class="section">
                                <div class="section-header">
                                    <h3>Financial report</h3>
                                    <div class="section-header-breadcrumb">
                                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                        <div class="breadcrumb-item"><a href="#">Class</a></div>
                                    </div><br>
                                </div>
                                <div class="section-body">
                                    <div class="card">
                                        <div class="card-body row">
                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                                <label>Program Types</label>
                                                <select class="form-control select2" style="width:100%" id="prg_type">
                                                    <option disabled selected>--choose one--</option>
                                                    <?php
                                                        $sql_progs=$conn->prepare("SELECT prg_type_id,prg_type_full_name FROM tbl_program_type WHERE campus_id='".$camp_id."'");
                                                        $sql_progs->execute();
                                                        while($prg_type=$sql_progs->fetch()){
                                                    ?>
                                                    <option value="<?php echo $prg_type['prg_type_id']; ?>"><?php echo $prg_type['prg_type_full_name']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                                <span id="spinner1"></span>
                                            </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-4" id="spec" hidden>
                                                <label>Specialization</label>
                                                <select class="form-control select2" style="width:100%" id="splz_id"></select>
                                            </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-4" id="level" hidden>
                                                <label>Level</label>
                                                <select class="form-control select2" style="width:100%" id="level_id"></select>
                                            </div>
                                            <div class="form-group  col-12 col-sm-6 col-lg-4" id="intke" hidden>
                                                <label>Academic Year</label>
                                                <select class="form-control select2" style="width:100%" id="intake_id">
                                                    <option disabled selected>--choose one--</option>
                                                    <?php
                                                        $sql_intakes=$conn->prepare("SELECT * FROM tbl_acad_cycle");
                                                        $sql_intakes->execute();
                                                        while($intake=$sql_intakes->fetch()){
                                                    ?>
                                                    <option value="<?php echo $intake['acad_cycle_id']; ?>"><?php echo $intake['acad_year']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-4" id="rep">
                                                <label>Report type</label><br>
                                                <select class="form-control select2" style="width:100%" name="type" id="type" required>
                                                    <option value="1">Balance</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-12" style="display:flex;flex-direction:row;justify-content:center;">
                                                <span id="loader"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="report">

                                    </div>
                                </div>
                            </section>
                        </div>
                    
                
    <!--javascript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script>
    $(document).ready(function(){
        
        $('#report_table').DataTable({     
          "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
       });
       
        //load specs
        $('#prg_type').change(function () {
            var getData= {
                    prg_type:$(this).val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#intke').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#rep').attr('hidden',true);
            $('#loader').attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner1').fadeOut('fast');
                    $("#splz_id").empty();
                    if(data.length>0){
                        $("#splz_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                        $("#spec").attr('hidden',false);
                        $('#intke').attr('hidden',false);
                        $('#rep').attr('hidden',false);
                        $('#loader').attr('hidden',false);
                    }
                    else{
                        pop_info("no specializations found!")
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        //load levels
        $('#prg_type').change(function () {
            var getData= {
                    type:$(this).val(),
                    action:'load_levels'
                    };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#level_id").empty();
                    if(data.length>0){
                        $("#level_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                        $("#level").attr('hidden',false);
                    }
                    else{
                        pop_info("no levels found!")
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });

        //load report  
        $(document).on('change','#prg_type, #splz_id,#level_id,#intake_id, #type',function () {
            if($("#splz_id").val()!=null && $("#level_id").val()!=null && $("#intake_id").val()!=null){
                var formdata = {
                    splz: $("#splz_id").val(),
                    level: $("#level_id").val(),
                    intake: $("#intake_id").val(),
                    type: $("#type").val(),
                };
                $('#loader').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/Financial_report/load_class_report.php",
                    data: formdata,
                    mimeTypes:"multipart/form-data",
                    success: function (data) {
                        $('#loader').fadeOut('fast');
                        $("#report").html(data);
                        $("#report_table").DataTable().draw();
                    },
                    error:function(error){
                        $('#loader').fadeOut('fast');
                        pop_wrong("Something went wrong!");
                    }
                });
            }
        });
        
    });
        
       function pop_wrong(feedback) {
            iziToast.warning({
            title: 'Error',
            message: feedback,
            position: 'topCenter'
          });
        }
       function pop_info(feedback) {
            iziToast.info({
            title: 'Ooops',
            message: feedback,
            position: 'topCenter'
          });
        }
       function pop_up_success(feedback) {
        iziToast.success({
        title: 'info',
        message: feedback,
        position: 'topCenter'
      });
        }
    </script>