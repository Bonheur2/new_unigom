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
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM tbl_department WHERE dept_id IN($department)");
                            $stmt->execute();
                            $data = $stmt->fetch();
                        ?>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="">
                                <div class="card-body row">
                                    <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                    <input type="hidden" name="dept_id" id="user_dept_id" value="<?php echo $data['dept_id']; ?>">
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="e_camp_id" required>
                                                                <option selected disabled>Select Campus</option>
                                                                <?php
                                                                    $sql_prg=$conn->prepare("SELECT * FROM tbl_campus where camp_id='".$camp_id."'");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                                <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id" id="e_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="e_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="e_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="e_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Mode</label>
                                                     <select class="form-control select2" style="width:100%;" name="prg_mode_id" id="e_prg_mode_id" required>
                                                     <option selected disabled>Select Mode</option>
                                                                
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Academic Year</label>
                                                     <select class="form-control select2" style="width:100%;" name="acad_cycle_id" id="e_acad_cycle_id" required>
                                                     <option selected disabled>Select Academic Year</option>
                                                     <?php
                                                     $select_acad="SELECT * FROM tbl_acad_cycle ORDER BY status ASC";
                                                     $cselect_acad=$conn->prepare($select_acad);
                                                     $cselect_acad->execute();
                                                     foreach($cselect_acad as $row_cselect_acad){
                                                     
                                                     ?>
                                                     <option value="<?php echo $row_cselect_acad['acad_cycle_id'];?>"><?php echo $row_cselect_acad['acad_year'];?></option>
                                                     <?php
                                                     }
                                                     
                                                     ?>
                                                                
                                                    </select>
                                                </div>
                                    <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader">
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
        // Step 1: When Campus is selected     
        $("#e_camp_id").change(function () {
        var campus = $("#e_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#e_prg_type_id').css({'display':'none'});
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for programs
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner0').fadeOut('fast');
                $('#e_prg_type_id').css({'display':'block'}); // Show program section

                // Clear existing program options
                $("#e_prg_type_id").empty();
                $("#e_prg_type_id").append("<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 2: When Program is selected   
$("#e_prg_type_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for schools
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner1').fadeOut('fast');
                $('#e_fac_id').css({'display':'block'}); // Show school section

                // Clear existing school options
                $("#e_fac_id").empty();
                $("#e_fac_id").append("<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 3: When School is selected
    $("#e_fac_id").change(function () {
        var school = $("#e_fac_id").val();
        var program = $("#e_prg_type_id").val();
        var dept=$("#user_dept_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            dept_id:dept,
            action: "load_departments_marks"
        };

        // Hide department and level sections initially
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner2').fadeOut('fast');
                $('#e_dept_id').css({'display':'block'}); // Show department section

                // Clear existing department options
                $("#e_dept_id").empty();
                $("#e_dept_id").append("<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 4: When Department is selected
    $("#e_dept_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var school = $("#e_fac_id").val();
        
        var formdata = {
            dept_id: department,
            prg_type:program,
            fac_id:school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#e_splz_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner3').fadeOut('fast');
                $('#e_splz_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_splz_id").empty();
                $("#e_splz_id").append("<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 5: When Specialization is selected
    $("#e_splz_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type:program,
            action: "load_levels"
        };

        // Hide level section initially
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner4').fadeOut('fast');
                $('#e_level_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_level_id").empty();
                $("#e_level_id").append("<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 6: When Level is selected
    $("#e_level_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_type:program,
            action: "load_modes_app"
        };

        // Hide level section initially
        $('#e_prg_mode_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner5').fadeOut('fast');
                $('#e_prg_mode_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

                // Clear existing level options
                $("#e_prg_mode_id").empty();
                $("#e_prg_mode_id").append("<option value='' disabled selected>Select Mode</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner5').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
        
        
        
        
        
        
        
        $("#load_btn").click(function(){
            var e_camp_id=parseInt($("#e_camp_id").val())
            var e_prg_type_id=parseInt($("#e_prg_type_id").val())
            var e_fac_id=parseInt($("#e_fac_id").val())
            var e_dept_id=parseInt($("#e_dept_id").val())
            var e_splz_id=parseInt($("#e_splz_id").val())
            var e_level_id=parseInt($("#e_level_id").val())
            var e_prg_mode_id=parseInt($("#e_prg_mode_id").val())
            var e_acad_cycle_id=parseInt($("#e_acad_cycle_id").val())
            
            if((Number.isInteger(e_camp_id) && Number.isInteger(e_prg_type_id) && Number.isInteger(e_fac_id) && Number.isInteger(e_dept_id) && Number.isInteger(e_splz_id)&& Number.isInteger(e_level_id)&& Number.isInteger(e_prg_mode_id)&& Number.isInteger(e_acad_cycle_id)))
            {
                window.location.href = '/files/Marks/validate_excel?e_camp_id=' +e_camp_id+ '&e_prg_type_id=' + e_prg_type_id + '&e_fac_id='+ e_fac_id + '&e_dept_id='+ e_dept_id + '&e_splz_id='+ e_splz_id+ '&e_level_id='+ e_level_id+ '&e_prg_mode_id='+ e_prg_mode_id+ '&e_acad_cycle_id='+ e_acad_cycle_id;
            }else{
                pop_info("Some field are not filled!");
            }
        });
    });
</script>