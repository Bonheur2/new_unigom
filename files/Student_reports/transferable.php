<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Student reports</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">HEC</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="load_hec_report" action="load_hec_report">
                                <div class="card-body pb-0 row">
                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label>Academic Year</label><br>
                                        <select class="form-control select2" style="width:100%;" name="acad_cycle_id">
                                            <option></option>
                                            <?php
                                                $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY acad_cycle_id DESC");
                                                $sql->execute();
                                                while($acad=$sql->fetch()){
                                                    ?>
                                            <option value="<?php echo $acad['acad_cycle_id']; ?>"><?php echo $acad['acad_year']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label>Program Type</label><br>
                                        <select class="form-control select2" style="width:100%;" name="prg_type" id="prg_type" required>
                                            <option></option>
                                            <?php
                                                $sql_prg=$conn->prepare("SELECT 
                                                                tbl_program_type.*,
                                                                tbl_campus.camp_full_name 
                                                                    FROM tbl_program_type 
                                                                INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                    ORDER BY tbl_program_type.status ASC");
                                                $sql_prg->execute();
                                                while($progs_faculty=$sql_prg->fetch()){
                                                    ?>
                                            <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                        <span id="spinner1"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4" id="fac" hidden>
                                        <label>School</label><br>
                                        <select class="form-control select2" style="width:100%;" name="fac_id" id="fac_id" required>
                                        </select>
                                        <span id="spinner2"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4" id="dept" hidden>
                                        <label>Department</label><br>
                                        <select class="form-control select2" style="width:100%;" name="dept_id" id="dept_id"required>
                                        </select>
                                        <span id="spinner3"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                        <label>Specialization</label><br>
                                        <select class="form-control select2" style="width:100%;" name="splz_id" id="splz_id">
                                        </select>
                                    </div>

                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label style="visibility: hidden;">Load button</label><br>
                                        <button type="submit" class="btn btn-primary" id="load_btn" hidden><span id="spinner4"></span>&nbsp;<span id="indicator4">Load data</span></button>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12 table-responsive" id="report">
                                    </div>
                                </div>
                            </form>
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
    //load faculties
    $("#prg_type").change(function () {
        var formdata = {
            type: p_type = $("#prg_type").val(),
            action: "load_faculties"
        };
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $("#fac").attr("hidden", true);
        $("#fac_id").empty();
        $("#dept").attr("hidden", true);
        $("#dept_id").empty();
        $("#spec").attr("hidden", true);
        $("#splz_id").empty();
        $("#load_btn").attr("hidden", true);
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner1').fadeOut('fast');
                $("#fac").removeAttr("hidden");
                $("#fac_id").empty();
               if(data.length>0){
                    $("#fac_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                    });
               }
             },
            error:function(error){
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong");
            }
             
        });
    });

        
    //load departments
    $("#fac_id").change(function () {
        var formdata = {
            fac: $("#fac_id").val(),
            action: "load_departments"
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $("#dept").attr("hidden", true);
        $("#dept_id").empty();
        $("#spec").attr("hidden", true);
        $("#splz_id").empty();
        $("#load_btn").attr("hidden", true);
        $.ajax({
            type: "POST",
            url: "/files/Faculties/faculty_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner2').fadeOut('fast');
                $("#dept").removeAttr("hidden");
                $("#dept_id").empty();
               if(data.length>0){
                    $("#dept_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                    });
               }
             },
            error:function(error){
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong");
            }
             
        });
    });

    //load specs
    $("#dept_id").change(function () {
        var formdata = {
            department: $("#dept_id").val(),
            action: "load_specs"
        };
        $("#load_btn").attr("hidden", true);
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $("#spec").attr("hidden", true);
        $.ajax({
            type: "POST",
            url: "/files/Departments/department_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner3').fadeOut('fast');
                $("#splz_id").empty();
               if(data.length>0){
                   $("#load_btn").attr("hidden", false);
                    $.each(data, function (index, value) {
                        $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                    });
               }
               $("#lev").removeAttr("hidden");
               $("#spec").removeAttr("hidden");
             },
            error:function(error){
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong");
            }
             
        });
    });
        
    //load_report
    $("#load_hec_report").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator4').html("Loading...");
        $.ajax({
            url: "/files/Student_reports/load_hec_report.php",
            type: "POST",
            data: formData,
            mimeTypes:"multipart/form-data",
            contentType:false,
            processData:false,
            success: function(data){
                $('#spinner4').fadeOut('fast');
                $('#indicator4').html("Load data");
                $("#report").html(data);
            },error: function(){
                $('#spinner4').fadeOut('fast');
                $('#indicator4').html("Load data");
                pop_wrong("Something went wrong!");
                
            }
        });
    });
});
</script>