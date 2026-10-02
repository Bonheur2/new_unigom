<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
    <section class="section">
        <div class="section-header">
            <h3>Modules by Level</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Review</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Program information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="load_modules" action="load_modules" method="POST">
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
                                        <div class="form-group  col-12 col-sm-12 col-lg-12">
                                            <button type="submit" class="btn btn-sm btn-primary" id="btn" hidden><span id="spinner20"></span>&nbsp;<span id="indicator20">Load modules</span></button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12" id="mod" hidden>
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <div class="col-9 col-md-10">
                                <h4>Registered Modules</h4>
                            </div>
                        </div>
                        <div class="card-body" id="modules"></div>
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
        $("#prg_type").change(function () {
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
    
    
        //load modules
        $("#load_modules").submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $('#modules').html("");
            $('#btn').attr('disabled', true);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/load_modules.php",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#spinner20').fadeOut('fast');
                    $('#mod').removeAttr('hidden');
                    $('#btn').removeAttr('disabled');
                    $('#modules').html(data);
                 },
                error:function(error){
                    $('#spinner20').fadeOut('fast');
                    $('#btn').removeAttr('disabled');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
              
        // delete module
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_assign'
                    };
            swal({
                title: "Are you sure?",
                text: "You are about to change this module status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Modules/module_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                            else if(data.status==200){
                                pop_up_success(data.message);
                                $("#load_modules").trigger('submit');
                            }
        				},
        				error:function(error){
        				    $('#spinner3_'+data_id).fadeOut('fast');
                            pop_wrong("Something went wrong!");
        				}
                    });
                }
               else {
                    swal("operation Cancelled!!");
                }
            });
        });
    });
</script>