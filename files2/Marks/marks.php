<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="lecturer" value="<?php echo $identification; ?>">
                    <section class="section">
                        <div class="section-header">
                            <h3>Assessment Marks</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Marks</a></div>
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
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="intake" hidden>
                                                            <label>Intake</label>
                                                            <select class="form-control select2" style="width:100%" name="intake_id" id="intake_id">
                                                                <?php
                                                                    $sql_intakes=$conn->prepare("SELECT i.intake_id,i.intake_month,ac.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle ac ON i.acad_cycle_id = ac.acad_cycle_id WHERE i.status=1");
                                                                    $sql_intakes->execute();
                                                                    while($intake=$sql_intakes->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year']; ?> </option>
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
                                                            </select>
                                                        </div>
                                                    <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                                        <button type="button" id="load_btn" class="btn btn-icon btn-primary"><span id="spinner5"></span>&nbsp;<span id="indicator5">Load data</span>&nbsp;</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <div class="col-12 col-sm-12 col-lg-12" id="list" hidden>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Marking List</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="register_marks" action="register_marks" method="POST">
                                            <input type="hidden" name="action" value="register_marks">
                                            <input type="hidden" name="m_module" id="m_module">
                                            <input type="hidden" name="m_mode" id="m_mode">
                                            <input type="hidden" name="m_intake" id="m_intake">
                                            <input type="hidden" name="m_splz" id="m_splz">
                                            <div class="row">
                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="assessment">
                                                    <label>Assessment Type</label>
                                                    <select class="form-control" name="assess_id" id="assess_id" required>
                                                        <option></option>
                                                        <?php
                                                            $sql_assessments=$conn->prepare("SELECT * FROM tbl_assessment WHERE status=1");
                                                            $sql_assessments->execute();
                                                            while($assessment=$sql_assessments->fetch()){
                                                        ?>
                                                        <option value="<?php echo $assessment['assess_id']; ?>"><?php echo $assessment['assess_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group  col-6 col-sm-2 col-lg-2" id="number">
                                                    <label>Number</label>
                                                    <input type="number" name="assess_no" id="assess_no" class="form-control" min="1" max="20" required>
                                                    <span id="spinner6"></span>
                                                </div>
                                                <div class="form-group  col-6 col-sm-2 col-lg-2" id="per">
                                                    <label>Per (e.g. <code>50</code>)</label>
                                                    <input type="number" name="per_marks" id="per_marks" class="form-control" min="1" required>
                                                </div>
                                            </div>
                                            <table class="table table-hover table-sm" id="mark_list" hidden>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col" class="d-none d-sm-table-cell">Names</th>
                                                        <th scope="col">Registration Number</th>
                                                        <th scope="col">Marks</th>
                                                        <th scope="col">Selection</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="students">
                                                </tbody>
                                            </table> 
                                            <div class="buttons" style="display:flex;flex-direction:row; justify-content:center; margin-top:50px;">
                                                <button type="submit" class="btn btn-icon btn-primary" id="m_Btn"><span id="spinner0"></span>&nbsp;<i class="fas fa-brief-case"></i>&nbsp;<span id="indicator0">Save marks</span>&nbsp;</button>
                                            </div>
                                        </form>
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
                    user:$("#lecturer").val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
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
//load levels
        $('#splz_id').change(function () {
            var getData= {
                    splz_id:$(this).val(),
                    user:$("#lecturer").val(),
                    action:'load-levels'
                    };
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#level").attr('hidden',false);
                    $("#intake").attr('hidden',false);
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
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            var getData= {
                    module:$(this).val(),
                    user:$("#lecturer").val(),
                    action:'load-modes'
                    };
            $("#m_module").val($('#module_id').val());
            $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#mode").attr('hidden',false);
                    $('#spinner4').fadeOut('fast');
                    if(data.length>0){
                        $("#loader").attr('hidden',false);
                        $("#mode_id").empty();
                        $.each(data, function (index, value) {
                            $("#mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner4').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
 
//load class list
        $(document).on('click','#load_btn',function () {
            $("#register_marks")[0].reset();
            var getData= {
                    intake:$("#intake_id").val(),
                    splz:$("#splz_id").val(),
                    level:$("#level_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'load-student-list'
                    };
            disable_inputs();
            $("#list").attr('hidden', true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator5').html("loading...")
            
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    enable_inputs();
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("load data");
                    var html='';
                    var i=1;
                    if(data.length>0){
                        data.forEach(function(stu) {
                            html += '<tr>';
                            html += '<td>' + i+'</td>';
                            html += '<td>'+stu.reg_no+'</td>';
                            html += '<td class="d-none d-sm-table-cell">' + stu.lname+" "+stu.fname+ '</td>';
                            html += '<td><input type="number" class="form-control marks" style="width:90px;height:30px" id="marks_'+stu.reg_no+'" name="marks_'+stu.reg_no+'" value="'+stu.marks+'" step=".01" min="0" placeholder="0.00"></td>';
                            html += '<td><input type="checkbox" class="form-control" style="width:15px;height:15px" name="stu[]" value="'+stu.reg_no+'" id="stu_'+stu.reg_no+'"></td>';
                            i++;
                        });
                        $("#list").attr('hidden',false);
                        $('#students').html(html);
                    }
                    else{
                        pop_info("no data found!")
                    }
				},
				error:function(error){
				    enable_inputs();
				    $('#indicator5').html("load data");
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });


//load marks
        $('#assess_id').change(function () {
            $('#number').keyup();
        });  
//load marks
        $('#number').keyup(function () {
            $("#m_Btn").attr('hidden',true);
            var getData= {
                    intake:$("#intake_id").val(),
                    splz:$("#splz_id").val(),
                    level:$("#level_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    assess_id:$("#assess_id").val(),
                    assess_no:$("#assess_no").val(),
                    action:'load-existing-marks'
                    };
            $("#m_intake").val($("#intake_id").val());
            $("#m_splz").val($("#splz_id").val());
            $("#m_mode").val($("#mode_id").val());
            $('#spinner6').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner6').fadeOut('fast');
                    $(".marks").val('');
                    
                    $("#per_marks").val(data[1].per_marks);
                    
                    data[0].forEach(function(stu) {
                        $("#marks_"+stu.reg_no).val(stu.marks)
                    });
                    $("#m_Btn").attr('hidden',false);
                    $("#mark_list").attr('hidden',false);
				},
				error:function(error){
				    $('#spinner6').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
    
    $(document).on('keyup','.marks',function(){
        var value=$(this).val();
        var in_id=$(this).attr("id");
        var reg_no=in_id.substring(6);
        $("#stu_"+reg_no).attr('checked',true);
        if(value===''){
            $("#stu_"+reg_no).removeAttr('checked');
        }
        
    })
    //save marks
    $("#register_marks").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator0').html("Saving...");
            $(".marks").attr('disabled', true);
            $.ajax({
                url: "/files/Marks/mark_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $(".marks").attr('disabled', false);
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save marks");
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
                    $(".marks").attr('disabled', false);
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save marks");
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
    function disable_inputs(){
        $('#prg_type').attr('disabled',true);
        $('#splz_id').attr('disabled',true);
        $('#intake_id').attr('disabled',true);
        $('#level_id').attr('disabled',true);
        $('#module_id').attr('disabled',true);
        $('#mode_id').attr('disabled',true);
    }
    function enable_inputs(){
        $('#prg_type').attr('disabled',false);
        $('#splz_id').attr('disabled',false);
        $('#intake_id').attr('disabled',false);
        $('#level_id').attr('disabled',false);
        $('#module_id').attr('disabled',false);
        $('#mode_id').attr('disabled',false);
    }
</script>