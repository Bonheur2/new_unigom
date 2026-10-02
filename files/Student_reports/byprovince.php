<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Student reports</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">By Province</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="default" aria-selected="true"><b>General</b></a></li>
                                                <li class="nav-item"><a class="nav-link" id="specific-tab" data-toggle="tab" href="#specific" role="tab" aria-controls="specific" aria-selected="false"><b>Specific</b></a></li>
                                            </ul>
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show table-responsive active" id="general" role="tabpanel" aria-labelledby="year-tab">
                                                    <table class="table table-hover table-sm" id="province_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>First name (s)</th>
                                                            <th>Last name</th>
                                                            <th>Program type</th>
                                                            <th>Specialization</th>
                                                            <th>Level</th>
                                                            <th>Province</th>
                                                            <th>Status</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $stmt=$conn->prepare("SELECT tbl_admission.fname,
                                                                                         tbl_admission.lname,
                                                                                         provinces.provincename,
                                                                                         tbl_program_type.prg_type_full_name,
                                                                                         tbl_specialization.splz_full_name,
                                                                                         tbl_level.level_full_name,
                                                                                         tbl_register_program_ug.reg_active,
                                                                                         tbl_status.status_full_name
                                                                                    FROM tbl_register_program_ug 
                                                                                        INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                                                                        INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                                                                                        INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id
                                                                                        INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                                                                        INNER JOIN tbl_status ON tbl_status.status_id=tbl_register_program_ug.reg_active
                                                                                        INNER JOIN provinces ON tbl_admission.province_id=provinces.provincecode
                                                                                        WHERE tbl_register_program_ug.reg_active=1
                                                                                    ");
                                                            $stmt->execute();
                                                            $i=1;
                                                            while($stud=$stmt->fetch()){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $stud['fname']; ?></td>
                                                                <td><?php echo $stud['lname']; ?></td>
                                                                <td><?php echo $stud['prg_type_full_name']; ?></td>
                                                                <td><?php echo $stud['splz_full_name']; ?></td>
                                                                <td><?php echo $stud['level_full_name']; ?></td>
                                                                <td><?php echo $stud['provincename']; ?></td>
                                                                <td>
                                                                    <?php 
                                                                        if($stud['reg_active']==1){
                                                                            echo "<span class='badge badge-success'>".$stud['status_full_name']."</span>"; 
                                                                        }
                                                                        else{
                                                                            echo "<span class='badge badge-warning'>".$stud['status_full_name']."</span>"; 
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php if($stmt->rowCount()>0){ ?>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
                                                        <button type="button" class="btn btn-success" onclick="exportTableToExcel('province_table','report_by_province')"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                                <div class="tab-pane fade" id="specific" role="tabpanel" aria-labelledby="specific-tab">
                                                    <form id="load_province_report" action="load_province_report">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Program Type</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="prg_type" id="prg_type" required>
                                                                    <option disabled selected></option>
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
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" id="lev" hidden>
                                                                <label>Level</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="level_id" id="level_id" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" id="acad" hidden>
                                                                <label>Province</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="province" id="province">
                                                                    <?php
                                                                        $sql_prov=$conn->prepare("SELECT * FROM provinces ORDER BY provincecode ASC");
                                                                        $sql_prov->execute();
                                                                        while($prov=$sql_prov->fetch()){
                                                                            ?>
                                                                    <option value="<?php echo $prov['provincecode']; ?>"><?php echo $prov['provincename']; ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <br>
                                                                <button type="submit" class="btn btn-primary" id="load_btn" hidden><span id="spinner20"></span>&nbsp;<span id="indicator20">Load data</span></button>
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

    $('#province_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       } 
        );
    $('#province_table_spec').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       } 
        );
        
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
            $("#lev").attr("hidden", true);
            $("#level_id").empty();
            $("#acad").attr("hidden", true);
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
            $("#acad").attr("hidden", true);
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
        
//load levels
     $("#prg_type").change(function () {
            var formdata = {
                type: $("#prg_type").val(),
                action: "load_levels"
            };
             $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
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
            $("#acad").attr("hidden", true);
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
                   $("#acad").removeAttr("hidden");
                 },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
    //load_report
    $("#load_province_report").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/files/Student_reports/load_province_report.php",
                type: "POST",
                data: formData,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
                    $("#report").html(data);
                    $('#province_table_spec').DataTable().draw();
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
                    pop_wrong("Something went wrong!");
                    
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
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>