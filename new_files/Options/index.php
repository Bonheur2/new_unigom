<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Options</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Option</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Option</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_option" action="save_option" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%" id="campus_id">
                                                        <option value="0">select campus</option>
                                                        <?php
                                                            $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                            $sql_camp->execute();
                                                            while($campus = $sql_camp->fetch()):
                                                        ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Faculty <span id="spinner_fac"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="fac_id">

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Program Type <span id="spinner_pt"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="prg_type">

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Department <span id="spinner_dept"></span></label>
                                                    <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id">

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Option Full Name</label>
                                                    <input type="text" class="form-control" id="o_f_name" placeholder="Full name" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Option Short Name</label>
                                                    <input type="text" class="form-control" id="o_s_name" placeholder="Short name">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
    <h4>Registered Options</h4>
</div>
<div class="card-body pb-0">
    <?php
        $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                    FROM tbl_campus
                                    INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                    INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_option ON tbl_option.dept_id = tbl_department.dept_id
                                    WHERE tbl_campus.camp_active=1 AND tbl_faculty.status=1 AND tbl_program_type.status=1 AND tbl_department.status=1
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
            $sql_facs = $conn->prepare("SELECT DISTINCT tbl_faculty.fac_id, tbl_faculty.fac_full_name
                                        FROM tbl_faculty
                                        INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                        INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                        INNER JOIN tbl_option ON tbl_option.dept_id = tbl_department.dept_id
                                        WHERE tbl_faculty.campus_id = :camp_id
                                        AND tbl_faculty.status=1 AND tbl_program_type.status=1 AND tbl_department.status=1
                                        ORDER BY tbl_faculty.fac_full_name ASC");
            $sql_facs->execute([':camp_id' => $campus['camp_id']]);
            $faculties = $sql_facs->fetchAll();
        ?>
        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
             id="campus-<?php echo $campus['camp_id']; ?>"
             role="tabpanel">

            <!-- Faculty Tabs (nested) -->
            <ul class="nav nav-tabs mt-2" id="facTabs-<?php echo $campus['camp_id']; ?>" role="tablist">
                <?php foreach($faculties as $fIndex => $fac): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $fIndex === 0 ? 'active' : ''; ?>"
                       id="ftab-<?php echo $campus['camp_id'].'-'.$fac['fac_id']; ?>"
                       data-toggle="tab"
                       href="#fac-<?php echo $campus['camp_id'].'-'.$fac['fac_id']; ?>"
                       role="tab">
                        <?php echo $fac['fac_full_name']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Faculty Tab Contents -->
            <div class="tab-content mt-2" id="facTabsContent-<?php echo $campus['camp_id']; ?>">
                <?php foreach($faculties as $fIndex => $fac):
                    $sql_pts = $conn->prepare("SELECT DISTINCT tbl_program_type.prg_type_id, tbl_program_type.prg_type_full_name
                                                FROM tbl_program_type
                                                INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                                INNER JOIN tbl_option ON tbl_option.dept_id = tbl_department.dept_id
                                                WHERE tbl_program_type.fac_id = :fac_id
                                                AND tbl_program_type.status=1 AND tbl_department.status=1
                                                ORDER BY tbl_program_type.prg_type_full_name ASC");
                    $sql_pts->execute([':fac_id' => $fac['fac_id']]);
                    $prg_types = $sql_pts->fetchAll();
                ?>
                <div class="tab-pane fade <?php echo $fIndex === 0 ? 'show active' : ''; ?>"
                     id="fac-<?php echo $campus['camp_id'].'-'.$fac['fac_id']; ?>"
                     role="tabpanel">

                    <!-- Program Type Tabs (nested) -->
                    <ul class="nav nav-tabs mt-2" id="ptTabs-<?php echo $campus['camp_id'].'-'.$fac['fac_id']; ?>" role="tablist">
                        <?php foreach($prg_types as $pIndex => $pt): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $pIndex === 0 ? 'show active' : ''; ?>"
                               id="pttab-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id']; ?>"
                               data-toggle="tab"
                               href="#pt-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id']; ?>"
                               role="tab">
                                <?php echo $pt['prg_type_full_name']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Program Type Tab Contents -->
                    <div class="tab-content mt-2" id="ptTabsContent-<?php echo $campus['camp_id'].'-'.$fac['fac_id']; ?>">
                        <?php foreach($prg_types as $pIndex => $pt):
                            $sql_depts = $conn->prepare("SELECT DISTINCT tbl_department.dept_id, tbl_department.dept_full_name
                                                        FROM tbl_department
                                                        INNER JOIN tbl_option ON tbl_option.dept_id = tbl_department.dept_id
                                                        WHERE tbl_department.prg_type = :prg_type
                                                        AND tbl_department.status=1
                                                        ORDER BY tbl_department.dept_full_name ASC");
                            $sql_depts->execute([':prg_type' => $pt['prg_type_id']]);
                            $departments = $sql_depts->fetchAll();
                        ?>
                        <div class="tab-pane fade <?php echo $pIndex === 0 ? 'show active' : ''; ?>"
                             id="pt-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id']; ?>"
                             role="tabpanel">

                            <!-- Department Tabs (nested) -->
                            <ul class="nav nav-tabs mt-2" id="deptTabs-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id']; ?>" role="tablist">
                                <?php foreach($departments as $dIndex => $dept): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $dIndex === 0 ? 'show active' : ''; ?>"
                                       id="depttab-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id'].'-'.$dept['dept_id']; ?>"
                                       data-toggle="tab"
                                       href="#dept-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id'].'-'.$dept['dept_id']; ?>"
                                       role="tab">
                                        <?php echo $dept['dept_full_name']; ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>

                            <!-- Department Tab Contents -->
                            <div class="tab-content mt-2" id="deptTabsContent-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id']; ?>">
                                <?php foreach($departments as $dIndex => $dept): ?>
                                <div class="tab-pane fade <?php echo $dIndex === 0 ? 'show active' : ''; ?>"
                                     id="dept-<?php echo $campus['camp_id'].'-'.$fac['fac_id'].'-'.$pt['prg_type_id'].'-'.$dept['dept_id']; ?>"
                                     role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm option_table" data-dept-id="<?php echo $dept['dept_id']; ?>">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Full Name</th>
                                                <th scope="col">Short Name</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT * FROM tbl_option WHERE dept_id = :dept_id ORDER BY status ASC");
                                                $sql->execute([':dept_id' => $dept['dept_id']]);
                                                $i = 1;
                                                while($opt = $sql->fetch()):
                                            ?>
                                            <tr data-row-id="<?php echo $opt['opt_id']; ?>">
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td class="col-full-name"><?php echo $opt['opt_full_name']; ?></td>
                                                <td class="col-short-name"><?php echo $opt['opt_short_name']; ?></td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $opt['opt_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $opt['opt_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $opt['opt_id']; ?>" <?php echo $opt['status']==1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $opt['opt_id']; ?>"></span>&nbsp;
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
                            <!-- End Department Tab Contents -->

                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- End Program Type Tab Contents -->

                </div>
                <?php endforeach; ?>
            </div>
            <!-- End Faculty Tab Contents -->

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
                                    <span id="spinner_e_fac"></span>
                                </div>
                                <div class="form-group">
                                    <label>Faculty</label>
                                    <select class="form-control select2" style="width:100%" id="e_fac_id">
                                        <option value="">-- Select Campus first --</option>
                                    </select>
                                    <span id="spinner_e_pt"></span>
                                </div>
                                <div class="form-group">
                                    <label>Program Type</label>
                                    <select class="form-control select2" style="width:100%" id="e_prg_type">
                                        <option value="">-- Select Faculty first --</option>
                                    </select>
                                    <span id="spinner_e_dept"></span>
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="form-control select2" style="width:100%" id="e_dept_id">
                                        <option value="">-- Select Program Type first --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Option Full Name</label>
                                    <input type="text" class="form-control" id="e_o_f_name" placeholder="Full name" required>
                                </div>
                                <div class="form-group">
                                    <label>Option Short Name</label>
                                    <input type="text" class="form-control" id="e_o_s_name" placeholder="Short name">
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
var pendingFacId = null;
var pendingPrgType = null;
var pendingDeptId = null;

$(document).ready(function(){
    $('.option_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });

    // build a table row for an option and add it to its department's DataTable
    function addOptionRow(dept_id, row){
        var $table = $("table.option_table[data-dept-id='"+dept_id+"']");
        if($table.length === 0){
            // no tab exists yet for this department (first item) - only way to show it is a reload
            location.reload();
            return;
        }
        var dt = $table.DataTable();
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.opt_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.opt_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.opt_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.opt_id+'"></span>&nbsp;</label></div>';
        }
        var $tr = $(dt.row.add([
            dt.rows().count() + 1,
            row.opt_full_name,
            row.opt_short_name || '',
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.opt_id);
    }

    //save option
    $("#save_option").submit(function(e){
        e.preventDefault();

        var formData = {
            dept_id:$("#dept_id").val(),
            o_f_name:$("#o_f_name").val(),
            o_s_name:$("#o_s_name").val(),
            action:'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_option')[0].reset();
                    $('#campus_id').val('0').trigger('change');
                    pop_up_success(data.message);
                    addOptionRow(data.dept_id, data);
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

    // delete/toggle option
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData= {
                id: data_id,
                action:'delete'
                };
        swal({
        title: "Are you sure?",
        text: "You are about to change this option's status!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Options/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500){
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                   pop_up_success(data.message);
                }
            },
            error:function(error){
                $('#spinner3_'+data_id).fadeOut('fast');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
                pop_wrong("Something went wrong");
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
            url: "../new_files/Options/controller.php",
            data: getData,
            dataType:"json",

            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_o_f_name").val(data.opt_full_name);
                $("#e_o_s_name").val(data.opt_short_name);
                $("#f_name").html(data.opt_full_name);

                pendingFacId = data.fac_id;
                pendingPrgType = data.prg_type;
                pendingDeptId = data.dept_id;
                $("#e_campus_id").val(data.campus_id).trigger('change');
                $('#updateModal').modal('show');
			},
			error:function(error){
			    $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
			}
        });
    });

    //update option
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            pr_id:$("#e_id").val(),
            dept_id:$("#e_dept_id").val(),
            o_f_name:$("#e_o_f_name").val(),
            o_s_name:$("#e_o_s_name").val(),
            action:'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Options/controller.php",
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
                    if(data.dept_changed){
                        // row must move to a different department's tab - simplest correct path
                        location.reload();
                    } else {
                        var $row = $("tr[data-row-id='"+data.opt_id+"']");
                        $row.find('.col-full-name').text(data.opt_full_name);
                        $row.find('.col-short-name').text(data.opt_short_name || '');
                    }
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

    // load faculty when campus changes (add form)
    $("#campus_id").change(function(){
         $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#fac_id").empty();
         $("#prg_type").empty();
         $("#dept_id").empty();
        var camp_id=$("#campus_id").val();
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
            $('#spinner_fac').fadeOut('fast');
            $("#fac_id").append("<option></option>")
            $.each(data, function (index, value) {
                $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name+"</option>");
            });
            },error: function(){
                $('#spinner_fac').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
         });
    })

    // load program type when faculty changes (add form)
    $("#fac_id").change(function(){
         $('#spinner_pt').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#prg_type").empty();
         $("#dept_id").empty();
        var fac_id=$("#fac_id").val();
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_prg_type" },
            dataType: "JSON",
            success: function(data){
            $('#spinner_pt').fadeOut('fast');
            $("#prg_type").append("<option></option>")
            $.each(data, function (index, value) {
                $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name+"</option>");
            });
            },error: function(){
                $('#spinner_pt').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
         });
    })

    // load department when program type changes (add form)
    $("#prg_type").change(function(){
         $('#spinner_dept').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#dept_id").empty();
        var prg_type=$("#prg_type").val();
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { prg_type: prg_type, action: "get_department" },
            dataType: "JSON",
            success: function(data){
            $('#spinner_dept').fadeOut('fast');
            $("#dept_id").append("<option></option>")
            $.each(data, function (index, value) {
                $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name+"</option>");
            });
            },error: function(){
                $('#spinner_dept').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
         });
    })

    // load faculty when campus changes (update modal)
    $("#e_campus_id").change(function(){
        var camp_id = $(this).val();
        if(!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Loading...</option>");
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_fac').fadeOut('fast');
                $("#e_fac_id").empty().append("<option value=''>-- Select Faculty --</option>");
                $.each(data, function(index, value){
                    $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                });
                if(pendingFacId){
                    $("#e_fac_id").val(pendingFacId).trigger('change');
                    pendingFacId = null;
                }
            },
            error: function(){
                $('#spinner_e_fac').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // load program type when faculty changes (update modal)
    $("#e_fac_id").change(function(){
        var fac_id = $(this).val();
        if(!fac_id) return;
        $('#spinner_e_pt').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_prg_type").empty().append("<option value=''>Loading...</option>");
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_prg_type" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_pt').fadeOut('fast');
                $("#e_prg_type").empty().append("<option value=''>-- Select Program Type --</option>");
                $.each(data, function(index, value){
                    $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                });
                if(pendingPrgType){
                    $("#e_prg_type").val(pendingPrgType).trigger('change');
                    pendingPrgType = null;
                }
            },
            error: function(){
                $('#spinner_e_pt').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // load department when program type changes (update modal)
    $("#e_prg_type").change(function(){
        var prg_type = $(this).val();
        if(!prg_type) return;
        $('#spinner_e_dept').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_dept_id").empty().append("<option value=''>Loading...</option>");
        $.ajax({
            url: "../new_files/Options/controller.php",
            type: "POST",
            data: { prg_type: prg_type, action: "get_department" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_dept').fadeOut('fast');
                $("#e_dept_id").empty().append("<option value=''>-- Select Department --</option>");
                $.each(data, function(index, value){
                    $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                });
                if(pendingDeptId){
                    $("#e_dept_id").val(pendingDeptId).trigger('change');
                    pendingDeptId = null;
                }
            },
            error: function(){
                $('#spinner_e_dept').fadeOut('fast');
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
