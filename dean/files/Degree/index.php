<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body pb-0 row">
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
                                </div>
                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="mod" hidden>
                                    <label>Graduation Date</label><br>
                                    <select class="form-control select2" style="width:100%" name="acad_grad" id="acad_grad" required>
                                        <?php 
                                            $sql_acad=$conn->prepare("SELECT gc.*,
                                                            ac.acad_year
                                                            FROM tbl_grad_cycle gc 
                                                            INNER JOIN tbl_acad_cycle ac ON 
                                                            gc.acad_cycle_id = ac.acad_cycle_id
        
                                                            ORDER BY gc.grad_cycle_id ASC
                                                            ");
                                            $sql_acad->execute();
                                            while($row=$sql_acad->fetch()){
                                                
                                        ?>
                                        <option value="<?php echo $row['grad_cycle_id']; ?>"><?php echo $row['grad_date']; ?> | <?php echo $row['acad_year']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row;justify-content:center;" hidden>
                                    <button type="button" class="btn btn-primary col-6 col-sm-6 col-lg-2" id="print"><i class="fas fa-wifi"></i>&nbsp;Go</button>
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
            $("#mod").attr("hidden", true);
    
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
            $("#lev").attr("hidden", true);
            $("#mod").attr("hidden", true);
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
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    }
                },
                error:function(error){
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
            $("#btn").attr("hidden",true);
            $("#mod").attr("hidden", true);
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner3').fadeOut('fast');
                    $("#splz_id").empty();
                   if(data.length>0){
                        $("#lev").removeAttr("hidden");
                        $("#spec").removeAttr("hidden");
                        $("#mod").removeAttr("hidden");
                        $("#btn").removeAttr("hidden");
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
            });
        });

        $("#print").click(function(e){
            var prg_type = $('#prg_type').val();
        	var fac = $('#fac_id').val();
        	var dept = $('#dept_id').val();
        	var splz = $('#splz_id').val();
        	var level = $('#level_id').val();
        	var grad = $('#acad_grad').val();
        	
        	const pageURL = "/files/Degree/export?ptype="+prg_type+"&fac="+fac+"&dept="+dept+"&splz="+splz+"&grad="+grad;

            var left = (screen.width - 800) / 2;
            var top = (screen.height - 600) / 4;
            window.open(pageURL, "Degrees", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=600, top=' + top + ', left=' + left);

        });
    });
</script>