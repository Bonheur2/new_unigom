<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Student Promotion</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Class promotion</a></div>
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
                                            <div class="card-body">
                                                <form id="load_class_list" action="load_class_list" method="POST">
                                                    <input type="hidden" name="action" value="load_class_list">
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
                                                            <label>School</label><br>
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
                                                        
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="acad_cycle" hidden>
                                                            <label>Academic Year</label><br>
                                                            <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id" required>

                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-2 col-lg-2" id="lev" hidden>
                                                            <label>Level</label><br>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-2 col-lg-2" id="mod" hidden>
                                                            <label>Learning Mode</label><br>
                                                            <select class="form-control select2" style="width:100%" name="mode" id="mode" required>
                                                                <?php
                                                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode ORDER BY prg_mode_full_name ASC");
                                                                    $sql_mode->execute();
                                                                    while($mode=$sql_mode->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $mode['prg_mode_id']; ?>"><?php echo $mode['prg_mode_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row;justify-content:center;" hidden>
                                                            <button type="submit" class="btn btn-primary col-6 col-sm-6 col-lg-2"><span id="spinner20"></span>&nbsp;<i class="fas fa-wifi"></i>&nbsp;<span id="indicator20">&nbsp;Go</span></button>
                                                        </div> 
                                                    </div>
                                                </form>
                                                
                                                <div class="card-body" id="stlist" style="border:2px solid grey; border-radius:5px;" hidden><br>
                                                    <h6 style="text-align:center">Student List</h6><hr/>
                                                    <form id="promote_list" action="promote_list" method="POST">
                                                        <table class="table table-hover table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Student ID</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Firstname (s)</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Lastname</th>
                                                                <th scope="col">Selection</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="contents">
                                                            </tbody>
                                                        </table>
                                                        <div class="card-footer bg-whitesmoke" style="display:flex;flex-direction:row-reverse;">
                                                            <button type="button" class="btn btn-icon btn-primary btn-sm" id="next" data-target="promoteModal"><i class="fas fa-arrow-right"></i>&nbsp;Next</button>
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
                    <!--option modal-->
                    <form id="promote" action="promote" method="POST">
                        <div class="modal fade" tabindex="-1" role="dialog" id="promoteModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Promotion confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>You are going to promote selected students to the next level. This can't be undone!</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-whitesmoke br">
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-icon btn-primary btn-sm"><span id="spinner-6"></span>&nbsp;<i class="fas fa-arrow-trend-up"></i>&nbsp;<span id="indicator-6">Promote</span>&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <!--end option modal-->
                    </form>
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
            $("#acad_cycle").attr("hidden", true);

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
                    $('#acad_cycle').css({'display':'block'});
                    $('#mode').css({'display':'block'});
                    $("#intake_id").empty();
                    $("#p_acad_cycle").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#acad_cycle_id").append("<option value='" + value.acad_cycle_id + "'> "+value.acad_year+"</option>");
                            $("#p_acad_cycle").append("<option value='" + value.acad_cycle_id + "'> "+value.acad_year+"</option>");
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
            $("#acad_cycle").attr("hidden", true);
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
            $("#intake").attr("hidden", true);
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
                        $("#acad_cycle").removeAttr("hidden");
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
        
    //load class list
    $("#load_class_list").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/files/Specialization/spec_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Go");
                    if(data[0].length>0){
                        var html='';
                        var i=1;
                        data[0].forEach(function(stu) {
                            html += '<tr>';
                            html += '<td>' + i+'</td>';
                            html += '<td>'+stu.reg_no+'</td>';
                            html += '<td class="d-none d-sm-table-cell">' +stu.fname+ '</td>';
                            html += '<td class="d-none d-sm-table-cell">' + stu.lname+ '</td>';
                            html += '<td><input type="checkbox" style="width:15px;height:15px" name="stu[]" value="'+stu.reg_no+'" checked></td>';
                            i++;
                        });
                        $('#contents').html(html);
                        $("#stlist").removeAttr('hidden');
                    }
                    else{
                        $('#contents').html(html);
                        $("#stlist").attr('hidden',true);
                        pop_wrong("no data found!")
                    }
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Go");
                    pop_wrong("Something went wrong!");
                    
                }
             });
             
             
             
             
          });

    //promote option
    $(document).on('click','#next',function(){
        $("#promoteModal").modal('show');
    });
    //promote
    $("#promote").submit(function(e){
            e.preventDefault();
            var formElement = document.querySelector('#promote_list');
            var formData = new FormData(formElement);
            formData.append('prg_type', $("#prg_type").val());
            formData.append('fac_id', $("#fac_id").val());
            formData.append('dept_id', $("#dept_id").val());
            formData.append('splz_id', $("#splz_id").val());
            formData.append('level_id', $("#level_id").val());
            formData.append('mode', $("#mode").val());
            formData.append('acad_cycle_id', $("#acad_cycle_id").val());
            formData.append('action', 'promote');
            
            console.log(acad_cycle_id);
            $('#spinner-6').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator-6').html("Promoting...");
            $.ajax({
                url: "/files/Specialization/spec_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    $('#spinner-6').fadeOut('fast');
                    $('#indicator-6').html("Promote");
                    if(data.status==200){
                        $("#promoteModal").modal('hide');
                        pop_up_success(data.message);
                    }
                    else if(data.status==401 || data.status==500){
                        pop_info(data.message);
                    }
                    
                },error: function(){
                    $('#spinner-6').fadeOut('fast');
                    $('#indicator-6').html("Promote");
                    pop_wrong("Something went wrong !");
                    
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