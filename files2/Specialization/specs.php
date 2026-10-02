<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Specializations</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Specializations</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Specialization</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_specs" action="save_specs" method="POST">
                                            <input type="hidden" name="action" value="register">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program Type</label>
                                                    <select class="form-control select2" style="width:100%" name="p_type" id="p_type">
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
                                                    <span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fct">
                                                    <label>Faculty</label>
                                                    <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                                    </select>
                                                    <span id="spinner00"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">
                                                    <label>Department</label>
                                                    <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fn">
                                                    <label>Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="splz_full_name" name="splz_full_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="sn">
                                                    <label>Short Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="splz_short_name" placeholder="Short name">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="st">
                                                    <label>Type</label>
                                                    <select class="form-control select2" style="width:100%" name="state" id="state">
                                                        <option value="1">Primary</option>
                                                        <option value="0">Secondary</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dn">
                                                    <label>Degree name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-file"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="degree_name" name="degree_name" placeholder="degree name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dpn">
                                                    <label>Diploma name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="diploma_name" name="diploma_name" placeholder="diploma name" required>
                                                    </div>
                                                </div>
                                            <div class="form-group col-md-12" style="display:none;" id="dbtn">
                                                <div class="input-group" style="display:flex; flex-direction:row; justify-content:center;">
                                                    <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Specializations</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="specs_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Full Name</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Program Type</th>
                                                    <th scope="col">Faculty</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_specialization.*,
                                                                        tbl_program_type.prg_type_full_name,
                                                                        tbl_faculty.fac_full_name,
                                                                        tbl_department.dept_full_name
                                                                        FROM tbl_specialization
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_specialization.prg_type=tbl_program_type.prg_type_id
                                                                        INNER JOIN tbl_faculty ON 
                                                                        tbl_specialization.fac_id=tbl_faculty.fac_id
                                                                        INNER JOIN tbl_department ON 
                                                                        tbl_specialization.dept_id=tbl_department.dept_id
                                                                        WHERE tbl_program_type.campus_id='".$camp_id."' AND tbl_program_type.status=1 AND tbl_faculty.status=1 AND tbl_department.status=1 ORDER BY tbl_specialization.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($progs=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $progs['splz_full_name']; ?></td>
                                                    <td><?php echo $progs['state']==1?"Primary":"Secondary"; ?></td>
                                                    <td><?php echo $progs['prg_type_full_name']; ?></td>
                                                    <td><?php echo $progs['fac_full_name']; ?></td>
                                                    <td><?php echo $progs['dept_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons" style="display:flex; flex-direction:row;">
                                                            <button type="button" data-id="<?php echo $progs['splz_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $progs['splz_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>edit&nbsp;</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $progs['splz_id']; ?>" <?php echo $progs['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $progs['splz_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="splz_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="splz_id" name="splz_id">
                                                    <input type="hidden" name="action" value="update">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Program Type</label>
                                                                <select class="form-control select2" style="width:100%" name="p_type" id="e_p_type">
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
                                                                &nbsp;<span id="spinner-0"></span>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6" id="efac">
                                                                <label>Faculty</label>
                                                                <select class="form-control select2" style="width:100%" name="fac_id" id="e_fac_id" required>
                                                                </select>
                                                                &nbsp;<span id="spinner-00"></span>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6" id="edep">
                                                                <label>Department</label>
                                                                <select class="form-control select2" style="width:100%" name="dept_id" id="e_dept_id" required>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Full Name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_splz_full_name" name="splz_full_name" placeholder="Full name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Short Name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-pencil"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_splz_short_name" name="splz_short_name" placeholder="Short name">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6" id="est">
                                                                <label>Type</label>
                                                                <select class="form-control select2" style="width:100%" name="state" id="e_state">
                                                                    <option value="1">Primary</option>
                                                                    <option value="0">Secondary</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Degree name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-file"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_degree_name" name="degree_name" placeholder="degree name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Diploma name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-file-invoice"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_diploma_name" name="diploma_name" placeholder="diploma name" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-whitesmoke br">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-sm" id="edbtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#specs_table').DataTable({     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       });
//load faculties
     $("#p_type").change(function () {
            var p_type = $("#p_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
             $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                     $('#fct').css({'display':'none'});
                     $('#fn').css({'display':'none'});
                     $('#sn').css({'display':'none'});
                     $('#fac').css({'display':'none'});
                     $('#dept').css({'display':'none'});
                     $('#st').css({'display':'none'});
                     $('#dn').css({'display':'none'});
                     $('#dpn').css({'display':'none'});
                     $('#dbtn').css({'display':'none'});
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#fct').css({'display':'block'});
                    $("#fac_id").empty();
                   if(data.length>0){
                        $("#fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating faculties
     $("#e_p_type").change(function () {
            var p_type = $("#e_p_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
             $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                     $('#efac').css({'display':'none'});
                     $('#edep').css({'display':'none'});
                     $('#edbtn').attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $('#efac').css({'display':'block'});
                    $("#e_fac_id").empty();
                   if(data.length>0){
                        $("#e_fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
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
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                     $('#fn').css({'display':'none'});
                     $('#sn').css({'display':'none'});
                     $('#dept').css({'display':'none'});
                     $('#st').css({'display':'none'});
                     $('#dn').css({'display':'none'});
                     $('#dpn').css({'display':'none'});
                     $('#dbtn').css({'display':'none'});
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating departments
     $("#e_fac_id").change(function () {
            var fac_id = $("#e_fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
             $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
             $('#edep').css({'display':'none'});
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#edep').css({'display':'block'});
                    $("#e_dept_id").empty();
                   if(data.length>0){
                        $("#e_dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
//show inputs
    $("#dept_id").change(function () {
        $('#fn').css({'display':'block'});
        $('#sn').css({'display':'block'});
        $('#fac').css({'display':'block'});
        $('#sl').css({'display':'block'});
        $('#st').css({'display':'block'});
        $('#dn').css({'display':'block'});
        $('#dpn').css({'display':'block'});
        $('#dbtn').css({'display':'block'});
    });
//button handler
    $("#e_dept_id").change(function () {
        $('#edbtn').attr('disabled',false);
    });  
    
//save spec
    $("#save_specs").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Specialization/spec_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#specs_table').load(location.href + " #specs_table");
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
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                }
             });
          });
      
// delete spec
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this specialization's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Specialization/spec_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                       pop_wrong(data.message);
                    }
                    else if(data.status==200){
                       pop_up_success(data.message);
                       $('#spec_table').load(location.href + " #spec_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
				    pop_wrong("Something went wrong!");

				}
            });
            }
           else {
                swal("Operation cancelled!!");
            }
        });
        });
        
//pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Specialization/spec_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#splz_id").val(data_id);
                    $("#e_splz_full_name").val(data[0].splz_full_name);
                    $("#e_splz_short_name").val(data[0].splz_short_name);
                    $("#e_degree_name").val(data[0].degree_name);
                    $("#e_diploma_name").val(data[0].diploma_name);
                    var selectElement1 = document.getElementById('e_p_type');
                    var selectElement2 = document.getElementById('e_fac_id');
                    var selectElement3 = document.getElementById('e_dept_id');
                    var selectElement4 = document.getElementById('e_state');
                    $("#e_fac_id").empty();
                    $("#e_dept_id").empty();
                    $.each(data[1], function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                    // // Set selected values
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].state + '"]');
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    var selectedOptionText = selectedOption1.textContent;
                    $("#splz_name").html(data[0].splz_full_name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
//update spec
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Specialization/spec_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#spec_table').load(location.href + " #spec_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!")
                    
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