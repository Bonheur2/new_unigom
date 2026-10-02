<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Schools</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">School</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New School</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_program" action="save_program" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Campus</label>
                                                    <select  class="form-control select2" style="width:100%" name="campus_id" id="campus_id">
                                                        <option value="0">select campus</option>
                                                        <?php
                                                            $sql_camp=$conn->prepare("SELECT * FROM  tbl_campus WHERE camp_active=1");
                                                            $sql_camp->execute();
                                                            $i=1;
                                                            while($campus=$sql_camp->fetch()){
                                                                ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>School Full Name</label>
                                                    <input type="text" class="form-control" id="f_f_name" placeholder="Full name" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>School Short Name</label>
                                                    <input type="text" class="form-control" id="f_s_name" placeholder="Short name">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>School Code</label>
                                                    <input type="text" class="form-control" id="f_code" placeholder="Ex:01">
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
    <h4>Registered Schools</h4>
</div>
<div class="card-body pb-0">
    <?php
        $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                    FROM tbl_campus
                                    INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                    WHERE tbl_campus.camp_active=1
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
        <?php foreach($campuses as $index => $campus): ?>
        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
             id="campus-<?php echo $campus['camp_id']; ?>"
             role="tabpanel">

            <div class="table-responsive">
                <table class="table table-hover table-sm faculty_table" data-camp-id="<?php echo $campus['camp_id']; ?>">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = $conn->prepare("SELECT tbl_faculty.*, tbl_campus.camp_full_name
                                            FROM tbl_faculty
                                            INNER JOIN tbl_campus ON tbl_faculty.campus_id = tbl_campus.camp_id
                                            WHERE tbl_campus.camp_active=1
                                            AND tbl_campus.camp_id = :camp_id
                                            ORDER BY tbl_faculty.status ASC");
                        $sql->execute([':camp_id' => $campus['camp_id']]);
                        $i = 1;
                        while($progs = $sql->fetch()):
                    ?>
                    <tr data-row-id="<?php echo $progs['fac_id']; ?>">
                        <th scope="row"><?php echo $i++; ?></th>
                        <td class="col-full-name"><?php echo $progs['fac_full_name']; ?></td>
                        <td class="col-code"><?php echo $progs['code']; ?></td>
                        <th>
                            <?php if($role_id == 18 || $role_id == 2): ?>
                            <div class="buttons row">
                                <button type="button" data-id="<?php echo $progs['fac_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                    <span id="spinner4_<?php echo $progs['fac_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                </button>
                                <label class="custom-switch btn btn-light btn-sm">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $progs['fac_id']; ?>" <?php echo $progs['status']==1 ? 'checked' : ''; ?>>
                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $progs['fac_id']; ?>"></span>&nbsp;
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
                                                            </div>
                                                            <div class="form-group">
                                                                <label>School Full Name</label>
                                                                <input type="text" class="form-control" id="e_ft_f_name" placeholder="Full name" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>School Short Name</label>
                                                                <input type="text" class="form-control" id="e_ft_s_name" placeholder="Short name">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>School Code</label>
                                                                <input type="text" class="form-control" id="e_ft_code" placeholder="Ex:01">
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
    $('.faculty_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });

    // build a table row for a school and add it to its campus's DataTable
    function addFacultyRow(camp_id, row){
        var $table = $("table.faculty_table[data-camp-id='"+camp_id+"']");
        if($table.length === 0){
            // no tab exists yet for this campus (first item) - only way to show it is a reload
            location.reload();
            return;
        }
        var dt = $table.DataTable();
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.fac_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.fac_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.fac_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.fac_id+'"></span>&nbsp;</label></div>';
        }
        var $tr = $(dt.row.add([
            dt.rows().count() + 1,
            row.fac_full_name,
            row.code || '',
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.fac_id);
    }

    //save faculty
    $("#save_program").submit(function(e){
            e.preventDefault();

        var formData = {
            campus_id:$("#campus_id").val(),
            ft_f_name:$("#f_f_name").val(),
            ft_s_name:$("#f_s_name").val(),
            ft_code:$("#f_code").val(),
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "../new_files/Faculties/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_program')[0].reset();
                        pop_up_success(data.message);
                        addFacultyRow(data.campus_id, data);
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

        // delete faculty
        $(document).on('click','.del',function () {
            var $checkbox = $(this);
            var data_id = $checkbox.data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this faculty's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Faculties/controller.php",
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
                url: "../new_files/Faculties/controller.php",
                data: getData,
                dataType:"json",

                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#e_id").val(data_id);
                    $("#e_campus_id").val(data.campus_id).trigger('change');
                    $("#e_ft_f_name").val(data.fac_full_name);
                    $("#e_ft_s_name").val(data.fac_short_name);
                    $("#e_ft_code").val(data.code);
                    $("#f_name").html(data.fac_full_name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });

    //update faculty
    $("#update_form").submit(function(e){
            e.preventDefault();

        var formData = {
            pr_id:$("#e_id").val(),
            campus_id:$("#e_campus_id").val(),
            ft_f_name:$("#e_ft_f_name").val(),
            ft_s_name:$("#e_ft_s_name").val(),
            ft_s_codee:$("#e_ft_code").val(),
            action:'update'
                };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "../new_files/Faculties/controller.php",
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
                        if(data.campus_changed){
                            // row must move to a different campus's tab - simplest correct path
                            location.reload();
                        } else {
                            var $row = $("tr[data-row-id='"+data.fac_id+"']");
                            $row.find('.col-full-name').text(data.fac_full_name);
                            $row.find('.col-code').text(data.code || '');
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

    document.getElementById('f_code').addEventListener('input', function (e) {
            let value = e.target.value;
            value = value.replace(/[^a-zA-Z0-9]/g, '');
            if (value.length > 2) {
                value = value.slice(0, 2);
            }
            e.target.value = value;
        });
    document.getElementById('e_ft_code').addEventListener('input', function (e) {
            let value = e.target.value;
            value = value.replace(/[^a-zA-Z0-9]/g, '');
            if (value.length > 2) {
                value = value.slice(0, 2);
            }
            e.target.value = value;
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
