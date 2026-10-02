<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Student Modules Management</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            </div>
        </div>
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <form id="repair" action="repair" method="POST">
                        <input type="hidden" name="action" value="repair">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-2 col-lg-2" style="margin-bottom:10px;">Students - Modules</button>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>Program Type</label>
                                    <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" required>
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
                                    <span id="spinner00"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fct">
                                    <label>Faculty</label>
                                    <select  class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                    </select>
                                    <span id="spinner000"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">
                                    <label>Department</label>
                                    <select  class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                    </select>
                                    <span id="spinner0000"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="spec">
                                    <label>Specialization</label>
                                    <select  class="form-control select2" style="width:100%" name="splz_id" id="splz_id" required>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="level">
                                    <label>Level</label>
                                    <select  class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                    </select>

                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="intake">
                                    <label>Intake</label>
                                    <select  class="form-control select2" style="width:100%" name="intake_id" id="intake_id" required>
                                    </select>

                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="mode">
                                    <label>Learning Mode</label>
                                    <select  class="form-control select2" style="width:100%" name="prg_mode_id" id="prg_mode_id">
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
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner"></span>&nbsp; <span id="indicator">Repair</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    //load faculties
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
            $('#fct').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#fct').css({'display':'block'});
                    $("#fac_id").empty();
                   if(data.length>0){
                        $("#fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load departments
     $("#fac_id").change(function () {
            var fac_id = $("#fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#spinner000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner000').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });


    //load specs
     $("#dept_id").change(function () {
            var dept_id = $("#dept_id").val();
            var formdata = {
                department: dept_id,
                action: "load_specs"
            };
            $('#spec').css({'display':'none'});
            $('#spinner0000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0000').fadeOut('fast');
                    $('#spec').css({'display':'block'});
                    $("#splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                   
                 },
                error:function(error){
                    $('#spinner0000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load levels
     $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_levels"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#level').css({'display':'block'});
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
    //load intakes
     $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_all_intakes"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#intake').css({'display':'block'});
                    $('#mode').css({'display':'block'});
                    $("#intake_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month + " | "+value.acad_year+"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
    // repair
    $("#repair").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Sending command...");
        $.ajax({
            url: "/files/admission/admission_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Repair");
                if(data.status==200){
                    pop_up_success(data.message);
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Repair");
                pop_wrong("Something went wrong!");
            }
         });
    });
});
</script>