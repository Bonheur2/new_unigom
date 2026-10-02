<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Departments</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Department</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Department</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_department" action="save_department" method="POST">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%" name="campus_id" id="campus_id" required>
                                                        <option></option>
                                                        <?php
                                                            $sql_campus=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                            $sql_campus->execute();
                                                            $i=1;
                                                            while($campus=$sql_campus->fetch()){
                                                                ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    &nbsp;<span id="spinner_prg"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program Type</label>
                                                    <select class="form-control select2" style="width:100%" name="p_type" id="p_type" required>
                                                        <option></option>
                                                       </select>
                                                    &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div  class="form-group col-12 col-sm-4 col-lg-4" style="width:100%" id="fct" hidden>
                                                    <label>School</label>
                                                    <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="width:100%" id="dfn" hidden>
                                                    <label>Department Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="d_f_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="dsn" hidden>
                                                    <label>Department Short Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="d_s_name" placeholder="Short name">
                                                    </div>
                                                </div>
                                            <div class="form-group col-12 col-sm-3 col-lg-3" id="dbtn" hidden>
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
    <h4>Registered Departments</h4>
</div>
<div class="card-body pb-0">
    <?php
        $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                    FROM tbl_campus
                                    INNER JOIN tbl_program_type ON tbl_program_type.campus_id = tbl_campus.camp_id
                                    INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_faculty ON tbl_department.fac_id = tbl_faculty.fac_id
                                    WHERE tbl_campus.camp_active=1 AND tbl_program_type.status=1 AND tbl_faculty.status=1
                                    ORDER BY tbl_campus.camp_full_name ASC");
        $sql_tabs->execute();
        $campuses = $sql_tabs->fetchAll();
    ?>
    <!-- Campus Tabs -->
    <ul class="nav nav-tabs" id="campusTabs" role="tablist">
        <?php foreach($campuses as $index => $campus): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>"
               id="tab-<?php echo $campus['camp_id']; ?>"
               data-toggle="tab"
               href="#campus-<?php echo $campus['camp_id']; ?>"
               role="tab">
                <?php echo $campus['camp_full_name']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <!-- Campus Tab Contents -->
    <div class="tab-content mt-2" id="campusTabsContent">
        <?php foreach($campuses as $index => $campus):
            $sql_ptypes = $conn->prepare("SELECT DISTINCT tbl_program_type.prg_type_id, tbl_program_type.prg_type_full_name
                                          FROM tbl_program_type
                                          INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                          INNER JOIN tbl_faculty ON tbl_department.fac_id = tbl_faculty.fac_id
                                          WHERE tbl_program_type.campus_id = :camp_id
                                          AND tbl_program_type.status=1 AND tbl_faculty.status=1
                                          ORDER BY tbl_program_type.prg_type_full_name ASC");
            $sql_ptypes->execute([':camp_id' => $campus['camp_id']]);
            $program_types = $sql_ptypes->fetchAll();
        ?>
        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
             id="campus-<?php echo $campus['camp_id']; ?>"
             role="tabpanel">

            <!-- Program Type Tabs (nested) -->
            <ul class="nav nav-tabs mt-2" id="pTypeTabs-<?php echo $campus['camp_id']; ?>" role="tablist">
                <?php foreach($program_types as $pIndex => $ptype): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $pIndex === 0 ? 'active' : ''; ?>"
                       id="ptab-<?php echo $campus['camp_id'].'-'.$ptype['prg_type_id']; ?>"
                       data-toggle="tab"
                       href="#ptype-<?php echo $campus['camp_id'].'-'.$ptype['prg_type_id']; ?>"
                       role="tab">
                        <?php echo $ptype['prg_type_full_name']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Program Type Tab Contents -->
            <div class="tab-content mt-2" id="pTypeTabsContent-<?php echo $campus['camp_id']; ?>">
                <?php foreach($program_types as $pIndex => $ptype): ?>
                <div class="tab-pane fade <?php echo $pIndex === 0 ? 'show active' : ''; ?>"
                     id="ptype-<?php echo $campus['camp_id'].'-'.$ptype['prg_type_id']; ?>"
                     role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm dept_table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Full Name</th>
                                <th scope="col">Short Name</th>
                                <th scope="col">School</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql = $conn->prepare("SELECT tbl_department.*,
                                                    tbl_faculty.fac_full_name,
                                                    tbl_program_type.prg_type_full_name,
                                                    tbl_campus.camp_full_name
                                                    FROM tbl_department
                                                    INNER JOIN tbl_faculty ON tbl_department.fac_id = tbl_faculty.fac_id
                                                    INNER JOIN tbl_program_type ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id = tbl_campus.camp_id
                                                    WHERE tbl_program_type.status=1 AND tbl_faculty.status=1
                                                    AND tbl_campus.camp_id = :camp_id
                                                    AND tbl_program_type.prg_type_id = :prg_type_id
                                                    ORDER BY tbl_department.status ASC");
                                $sql->execute([':camp_id' => $campus['camp_id'], ':prg_type_id' => $ptype['prg_type_id']]);
                                $i = 1;
                                while($progs = $sql->fetch()):
                            ?>
                            <tr>
                                <th scope="row"><?php echo $i++; ?></th>
                                <td><?php echo $progs['dept_full_name']; ?></td>
                                <td><?php echo $progs['dept_short_name']; ?></td>
                                <td><?php echo $progs['fac_full_name']; ?></td>
                                <th>
                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                    <div class="buttons row">
                                        <button type="button" data-id="<?php echo $progs['dept_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                            <span id="spinner4_<?php echo $progs['dept_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                        </button>
                                        <label class="custom-switch btn btn-light btn-sm">
                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $progs['dept_id']; ?>" <?php echo $progs['status']==1 ? 'checked' : ''; ?>>
                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $progs['dept_id']; ?>"></span>&nbsp;
                                        </label>
                                    </div>
                                    <?php endif; ?>
                                </th>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
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
                                                    <h5 class="modal-title">Updating <span id="f_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    <input type="hidden" id="e_id" name="e_id">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Campus</label>
                                                                <select class="form-control select2" style="width:100%" id="e_campus_id">
                                                                    <option value="">-- Select Campus --</option>
                                                                    <?php
                                                                        $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                                        $sql_camp2->execute();
                                                                        while($camp2 = $sql_camp2->fetch()):
                                                                    ?>
                                                                    <option value="<?php echo $camp2['camp_id']; ?>"><?php echo $camp2['camp_full_name']; ?></option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                                <span id="spinner_e_camp"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Program Type</label>
                                                                <select class="form-control select2" style="width:100%" name="e_p_type" id="e_p_type" required>
                                                                    <option value="">-- Select Campus first --</option>
                                                                </select>
                                                                <span id="spinner00"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Select School</label>
                                                                <select class="form-control select2" style="width:100%" name="e_fac_id" id="e_fac_id" required>
                                                                    <option value="">-- Select Program Type first --</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Department Full Name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_dp_f_name" placeholder="Full name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Department Short Name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-pencil"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_dp_s_name" placeholder="Short name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
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

    $('.dept_table').each(function() {
    $(this).DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });
});
//load faculties
     $("#p_type").change(function () {
            var p_type = $("#p_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
             $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                     $('#dfn').attr('hidden',true);
                     $('#dsn').attr('hidden',true);
                     $('#dbtn').attr('hidden',true);
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#fct').attr('hidden',false);
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
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $("#e_fac_id").empty();
                   if(data.length>0){
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
        
//show inputs
    $("#fac_id").change(function () {
        $('#dfn').attr('hidden',false);
        $('#dsn').attr('hidden',false);
        $('#dbtn').attr('hidden',false);
    });
    
    
//save department
    $("#save_department").submit(function(e){
            e.preventDefault();
    
        var formData = {
            camp_id:$("#camp_id").val(),
            p_type:$("#p_type").val(),
            fac_id:$("#fac_id").val(),
            d_f_name:$("#d_f_name").val(),
            d_s_name:$("#d_s_name").val(),
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Departments/department_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_department')[0].reset();
                        pop_up_success(data.message);
                        location.reload();
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
      
// delete department
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this department's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                       pop_up_success(data.message);
                       $('#department_table').load(location.href + " #department_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
            }
           else {
                swal("operation cancelled!!");
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
                url: "/files/Departments/department_controller.php",
                data: getData,
                dataType:"json",
                
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_id").val(data_id);
                    $("#e_dp_f_name").val(data[0].dept_full_name);
                    $("#e_dp_s_name").val(data[0].dept_short_name);
                    $("#f_name").html(data[0].dept_full_name);

                    // Step 1: set campus
                    $("#e_campus_id").val(data[0].campus_id);

                    // Step 2: load program types for this campus, then set selected
                    $('#spinner_e_camp').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Faculties/faculty_controller.php",
                        data: { camp_id: data[0].campus_id, action: "get_program_type" },
                        dataType: "JSON",
                        success: function(ptypes) {
                            $('#spinner_e_camp').fadeOut('fast');
                            $("#e_p_type").empty();
                            $.each(ptypes, function(i, v) {
                                $("#e_p_type").append("<option value='" + v.prg_type_id + "'>" + v.prg_type_full_name + "</option>");
                            });
                            $("#e_p_type").val(data[0].prg_type);

                            // Step 3: load schools for this program type, then set selected
                            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                            $.ajax({
                                type: "POST",
                                url: "/files/Departments/department_controller.php",
                                data: { type: data[0].prg_type, action: "load_faculties" },
                                dataType: "JSON",
                                success: function(faculties) {
                                    $('#spinner00').fadeOut('fast');
                                    $("#e_fac_id").empty();
                                    $.each(faculties, function(i, v) {
                                        $("#e_fac_id").append("<option value='" + v.fac_id + "'>" + v.fac_full_name + "</option>");
                                    });
                                    $("#e_fac_id").val(data[0].fac_id);
                                    $('#updateModal').modal('show');
                                },
                                error: function() {
                                    $('#spinner00').fadeOut('fast');
                                    pop_wrong("Failed to load schools!");
                                    $('#updateModal').modal('show');
                                }
                            });
                        },
                        error: function() {
                            $('#spinner_e_camp').fadeOut('fast');
                            pop_wrong("Failed to load program types!");
                        }
                    });
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
//update department
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = {
            dep_id:$("#e_id").val(),
            p_type:$("#e_p_type").val(),
            fac_id:$("#e_fac_id").val(),
            d_f_name:$("#e_dp_f_name").val(),
            d_s_name:$("#e_dp_s_name").val(),
            action:'update'
                };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Departments/department_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        
                        location.reload();
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
                    pop_wrong("Something went wrong!");
                }
             });
          });
    // update modal: campus changes → load program types
    $("#e_campus_id").change(function(){
        var camp_id = $(this).val();
        if(!camp_id) return;
        $('#spinner_e_camp').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_p_type").empty().append("<option value=''>Loading...</option>");
        $("#e_fac_id").empty().append("<option value=''>-- Select Program Type first --</option>");
        $.ajax({
            url: "/files/Faculties/faculty_controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_program_type" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_camp').fadeOut('fast');
                $("#e_p_type").empty().append("<option value=''>-- Select Program Type --</option>");
                $.each(data, function(index, value){
                    $("#e_p_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                });
            },
            error: function(){
                $('#spinner_e_camp').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // update modal: program type changes → load schools
    $("#e_p_type").change(function(){
        var p_type = $(this).val();
        if(!p_type) return;
        $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Loading...</option>");
        $.ajax({
            type: "POST",
            url: "/files/Departments/department_controller.php",
            data: { type: p_type, action: "load_faculties" },
            dataType: "JSON",
            success: function(data){
                $('#spinner00').fadeOut('fast');
                $("#e_fac_id").empty().append("<option value=''>-- Select School --</option>");
                $.each(data, function(index, value){
                    $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + " [" + value.fac_short_name + "]</option>");
                });
            },
            error: function(){
                $('#spinner00').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // new setting
    $("#campus_id").change(function(){
         $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
         $("#p_type").empty();
        var camp_id=$("#campus_id").val();
        var formData={
            camp_id:camp_id,
            action:"get_program_type"
        }
        $.ajax({
            url: "/files/Faculties/faculty_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
            $('#spinner_prg').fadeOut('fast');
            $("#p_type").append("<option></option>")
            $.each(data, function (index, value) {
                $("#p_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name+"</option>");
            });    
            },error: function(){
                $('#spinner_prg').fadeOut('fast');
                pop_wrong("Something went wrong!");
                
            }
         });
    })
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