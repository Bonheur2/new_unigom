<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="lecturer" value="<?php echo $identification; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Attendance</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">List</a></div>
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
                                                        <option>choose one</option>
                                                        <?php
                                                            $sql_progs=$conn->prepare("SELECT 
                                                                            distinct(tbl_program_type.prg_type_id),
                                                                            tbl_program_type.prg_type_full_name,
                                                                            tbl_campus.camp_full_name,
                                                                            tbl_modules.prg_type
                                                                            FROM tbl_module_leader
                                                                            INNER JOIN tbl_modules ON tbl_module_leader.module_id=tbl_modules.module_id 
                                                                            INNER JOIN tbl_program_type ON tbl_modules.prg_type=tbl_program_type.prg_type_id 
                                                                            INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id
                                                                            WHERE tbl_module_leader.staff_id='".$identification."'");
                                                            $sql_progs->execute();
                                                            while($prg_type=$sql_progs->fetch()){
                                                                ?>
                                                        <option value="<?php echo $prg_type['prg_type_id']; ?>"><?php echo $prg_type['prg_type_full_name']." | ".$prg_type['camp_full_name']; ?> </option>
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
                                                        <?php
                                                            $sql_intakes=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY status ASC");
                                                            $sql_intakes->execute();
                                                            while($intake=$sql_intakes->fetch()){
                                                                ?>
                                                        <option value="<?php echo $intake['acad_cycle_id']; ?>"><?php echo $intake['acad_year']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="level" hidden>
                                                    <label>Level</label>
                                                    <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                                    </select>
                                                    <span id="spinner3"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4"  id="module" hidden>
                                                    <label>Module</label>
                                                    <select class="form-control select2" style="width:100%" name="module_id" id="module_id">
                                                    </select>
                                                    <span id="spinner4"></span>
                                                </div>
                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="mode" hidden>
                                                    <label>Learning mode</label>
                                                    <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
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
                                                <button type="button" id="load_btn2" class="btn btn-icon btn-success">Print Excel</button>&nbsp;
                                                <button type="button" id="load_btn" class="btn btn-icon btn-primary">Print PDF</button>
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
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
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
                    pop_wrong("Something went wrong!")
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
                                $("#acad_cycle_id").append("<option value='" + value.acad_cycle_id + "'> "+value.acad_year  +"</option>");
                            });
                    }
				},
				error:function(error){
                    pop_wrong("Something went wrong!")
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
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
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
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        //load modules
        //load modules
        $('#level_id').change(function () {
            var getData= {
                    splz_id:$("#splz_id").val(),
                    level_id:$("#level_id").val(),
                    user:$("#lecturer").val(),
                    action:'load-modules'
                    };
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#module").attr('hidden',false);
                    $("#module_id").empty();
                    $('#spinner3').fadeOut('fast');
                    if(data.length>0){
                        $("#module_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#module_id").append("<option value='" + value.module_id + "'>" + value.module_name +" ( "+value.module_code+" )</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        //load modes
        $('#module_id').change(function () {
            $("#mode").attr('hidden',false);
            $("#loader").attr('hidden',false);
        });
 
        //load class list
        $(document).on('click','#load_btn',function () {
            var acad_cycle = $("#acad_cycle_id").val();
            var splz = $("#splz_id").val();
            var module = $("#module_id").val();
            var mode = $("#mode_id").val();
            var url = "/files/Others/attendance-list?splz="+splz+"&acad="+acad_cycle+"&modl="+module+"&md="+mode;
            window.open(url, '_blank');
        });
        
        //load class list
        $(document).on('click','#load_btn2',function () {
            var acad_cycle = $("#acad_cycle_id").val();
            var splz = $("#splz_id").val();
            var module = $("#module_id").val();
            var mode = $("#mode_id").val();
            var url = "/files/Others/attendance-list-excel?splz="+splz+"&acad="+acad_cycle+"&modl="+module+"&md="+mode;
            window.open(url, '_blank');
        });
    });
</script>