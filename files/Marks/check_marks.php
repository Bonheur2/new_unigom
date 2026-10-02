<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Marks Validation</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Check marks</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Class information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="">
                                <div class="card-body row">
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>Program Types</label>
                                        <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type">
                                            <option disabled selected>--choose one--</option>
                                            <?php
                                                $sql_prg=$conn->prepare("SELECT 
                                                                tbl_program_type.*,
                                                                tbl_campus.camp_full_name 
                                                                    FROM tbl_program_type 
                                                                INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                    ORDER BY tbl_program_type.status ASC");
                                                $sql_prg->execute();
                                                $i=1;
                                                while($progs_faculty=$sql_prg->fetch()){
                                                    ?>
                                            <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                        <span id="spinner1"></span>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                        <label>Specialization</label>
                                        <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                        </select>
                                        <span id="spinner2"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4" id="acad_cycle" hidden>
                                        <label>Academic Year</label>
                                        <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id">
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4" id="level" hidden>
                                        <label>Level</label>
                                        <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                        </select>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4" id="mode">
                                        <label>Learning mode</label>
                                        <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
                                            <option></option>
                                            <?php
                                                $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status=1");
                                                $sql_mode->execute();
                                                while($progs_mode=$sql_mode->fetch()){
                                            ?>
                                            <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                        <button type="button" id="load_btn" class="btn btn-icon btn-primary"><i class="fa fa-wifi"></i>&nbsp;Load data</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    //load specs
        $('#prg_type').change(function () {
            var getData= {
                    prg_type:$(this).val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#acad_cycle').attr('hidden',true);
            $('#level').attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#spec").attr('hidden',false);
                    $("#splz_id").empty();
                    $('#spinner1').fadeOut('fast');
                    if(data.length>0){
                        $("#splz_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                            });
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_info("Something went wrong!")
				}
            });
        });
        
        //load intakes
        $('#prg_type').change(function () {
            var getData= {
                    type:$(this).val(),
                    action:'load_all_intakes'
                };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#acad_cycle_id").empty();
                    if(data.length>0){
                        $("#acad_cycle_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#acad_cycle_id").append("<option value='" + value.acad_cycle_id + "'>" + value.acad_year+"</option>");
                            });
                    }
				},
				error:function(error){
                    pop_info("Something went wrong!")
				}
            });
        });
        
        //load levels
        $('#splz_id').change(function () {
            var getData= {
                    type:$("#prg_type").val(),
                    action:'load_levels'
                    };
            $('#acad_cycle').attr('hidden',true);
            $('#level').attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#level").attr('hidden',false);
                    $("#acad_cycle").attr('hidden',false);
                    $("#level_id").empty();
                    $('#spinner2').fadeOut('fast');
                    if(data.length>0){
                        $("#level_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner2').fadeOut('fast');
                    pop_info("Something went wrong!")
				}
            }); 
        });
        
        $('#level_id').change(function () {
            $('#mode').attr('hidden', false);
        });
        
        $('#mode_id').change(function () {
            $('#loader').attr('hidden', false);
        });
        
        $("#load_btn").click(function(){
            var prg_type = parseInt($("#prg_type").val());
            var acad_cycle_id = parseInt($("#acad_cycle_id").val());
            var splz = parseInt($("#splz_id").val());
            var mode = parseInt($("#mode_id").val());
            var level = parseInt($("#level_id").val());
            var camp = parseInt($("#level_id").val());
            
            if(Number.isInteger(prg_type) && Number.isInteger(acad_cycle_id) && Number.isInteger(splz) && Number.isInteger(mode) && Number.isInteger(level)){
                window.location.href = '/files/Marks/validate_excel_new?prg_type=' +prg_type+ '&acad_cycle_id=' + acad_cycle_id + '&splz='+ splz + '&mode='+ mode + '&level='+ level;
            }else{
                pop_info("Some field are not filled!");
            }
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
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>