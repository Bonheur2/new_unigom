<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Student List</h3>
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
                                                <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Program Type</label><br>
                                                <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" required>
                                                <option></option>
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
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="fac" hidden>
                                            <label>Faculty</label><br>
                                            <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                            </select>
                                            <span id="spinner2"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="dept" hidden>
                                            <label>Department</label><br>
                                            <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id"required>
                                            </select>
                                            <span id="spinner3"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                            <label>Specialization</label><br>
                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                            </select>
                                            <span id="spinner5"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="lev" hidden>
                                            <label>Level</label><br>
                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="acad" hidden>
                                                     <label>Academic Year</label>
                                                     <select class="form-control select2" style="width:100%;" name="acad_cycle_id" id="acad_cycle_id" required>
                                                     <option selected disabled>Select Academic Year</option>
                                                    <?php
                                                    $select="SELECT * FROM tbl_acad_cycle";
                                                    $cselect=$conn->prepare($select);
                                                    $cselect->execute();
                                                    foreach($cselect as $row_cselect){
                                                    
                                                    ?>
                                                    <option value="<?php echo $row_cselect['acad_cycle_id'];?>"><?php echo $row_cselect['acad_year'];?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    
                                                    </select>
                                                </div>
                                            <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                                <button type="button" id="load_btn2s" class="btn btn-icon btn-success">Print Excel</button>&nbsp;
                                                <button type="button" id="load_btns" class="btn btn-icon btn-primary">Print PDF</button>
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
            var formdata = {
                type: $("#prg_type").val(),
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
            $("#acad_cycle_id").attr("hidden", true);
            
    
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
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
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
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
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
                    $("#loader").attr('hidden',false);
                    $("#acad").removeAttr("hidden");
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
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                    }
                   $("#lev").removeAttr("hidden");
                   $("#spec").removeAttr("hidden");
                   $("#btn").removeAttr("hidden");
                },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
            });
        });
        
 
        //load class list
        $(document).on('click', '#load_btns', function () {
    var prg_type_id = $("#prg_type").val();
    var fac_id = $("#fac_id").val();
    var dept_id = $("#dept_id").val();
    var splz_id = $("#splz_id").val();
    var level_id = $("#level_id").val();
    var acad_cycle_id = $("#acad_cycle_id").val();

    // Get real names for filename
    var specialization = $("#splz_id").find("option:selected").text().trim();
    var level_name = $("#level_id").find("option:selected").text().trim();

    // Validate required fields
    if (!prg_type_id || !fac_id || !dept_id || !splz_id || !level_id || !acad_cycle_id) {
        pop_wrong("Please fill in all required fields before proceeding.");
        return;
    }

    // Generate a readable filename using names
    var filename = "Student_List_" + specialization + "_" + level_name + ".pdf";
    filename = filename.replace(/\s+/g, '_'); // Replace spaces with underscores

    // Construct URL with IDs only
    var url = "/files/Others/student_list.php?prg_type=" + encodeURIComponent(prg_type_id) +
              "&fac_id=" + encodeURIComponent(fac_id) +
              "&dept_id=" + encodeURIComponent(dept_id) +
              "&splz_id=" + encodeURIComponent(splz_id) +
              "&level_id=" + encodeURIComponent(level_id) +
              "&acad_cycle_id=" + encodeURIComponent(acad_cycle_id) +
              "&filename=" + encodeURIComponent(filename);

    window.open(url, '_blank');

    // Auto-download the file with the correct filename
    setTimeout(function () {
        var downloadUrl = url + "&download=1";
        var a = document.createElement('a');
        a.href = downloadUrl;
        a.setAttribute('download', filename);  // Force the correct filename
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }, 3000);
});




        
        //load class list
        $(document).on('click','#load_btn2s',function () {
            var prg_type = $("#prg_type").val();
            var fac_id = $("#fac_id").val();
            var dept_id = $("#dept_id").val();
            var splz_id = $("#splz_id").val();
            var level_id = $("#level_id").val();
            var acad_cycle_id = $("#acad_cycle_id").val();
            var url = "/files/Others/student_list_excel?prg_type="+prg_type+"&fac_id="+fac_id+"&dept_id="+dept_id+"&splz_id="+splz_id+"&level_id="+level_id+"&acad_cycle_id="+acad_cycle_id;
            window.open(url, '_blank');
        });
    });
</script>