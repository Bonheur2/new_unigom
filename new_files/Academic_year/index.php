<!-- Start app main Content -->
<style>
.entity-form .field-row{
    display:flex;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:12px;
}
.entity-form .field-row .field-col{
    flex:1 1 200px;
    min-width:180px;
}
.entity-form .field-row .field-col.field-save{
    flex:0 0 auto;
}
.entity-form label{
    font-size:13px;
    font-weight:500;
    color:#4a5568;
    margin-bottom:6px;
}
.entity-form .form-control,
.entity-form .select2-container .select2-selection--single{
    height:38px !important;
    border-radius:5px;
}
.entity-form .select2-container .select2-selection--single .select2-selection__rendered{
    line-height:36px !important;
    padding-left:12px;
}
.entity-form .select2-container .select2-selection--single .select2-selection__arrow{
    height:36px !important;
}
.entity-form .btn-save-entity{
    height:38px;
    padding:0 22px;
    white-space:nowrap;
}
</style>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Academic Years</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Academic Year</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Academic Year</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body row">
                                        <form id="save_acad_year" action="save_acad_year" method="POST" class="entity-form">
                                            <div class="field-row">
                                                <div class="field-col">
                                                    <label>Academic Year</label>
                                                    <input type="text" class="form-control" id="acad_year" placeholder="Ex: 2025-2026" required>
                                                </div>
                                                <div class="field-col">
                                                    <label>Status</label>
                                                    <select class="form-control select2" style="width:100%" id="acad_status">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="field-col field-save">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-save-entity"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
                                    <h4>Registered Academic Years</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm acad_year_table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Academic Year</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT acad_cycle_id, acad_year, status FROM tbl_acad_cycle ORDER BY acad_cycle_id DESC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch()):
                                            ?>
                                            <tr data-row-id="<?php echo $row['acad_cycle_id']; ?>">
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td class="col-acad-year"><?php echo $row['acad_year']; ?></td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $row['acad_cycle_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $row['acad_cycle_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['acad_cycle_id']; ?>" <?php echo $row['status']==1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['acad_cycle_id']; ?>"></span>&nbsp;
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
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--update modal-->
            <form action="update_form" method="POST" id="update_form" class="entity-form">
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
                                    <label>Academic Year</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="e_acad_year" placeholder="Ex: 2025-2026" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" style="width:100%" id="e_acad_status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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
    var acadYearDt = $('.acad_year_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });

    // build a table row for an academic year and add it to the DataTable
    function addAcadYearRow(row){
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.acad_cycle_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.acad_cycle_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.acad_cycle_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.acad_cycle_id+'"></span>&nbsp;</label></div>';
        }
        var $tr = $(acadYearDt.row.add([
            acadYearDt.rows().count() + 1,
            row.acad_year,
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.acad_cycle_id);
    }

    //save academic year
    $("#save_acad_year").submit(function(e){
        e.preventDefault();

        var formData = {
            acad_year: $("#acad_year").val(),
            status: $("#acad_status").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Academic_year/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_acad_year')[0].reset();
                    $('#acad_status').val('1').trigger('change');
                    pop_up_success(data.message);
                    addAcadYearRow(data);
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

    // delete/toggle academic year
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Are you sure?",
            text: "You are about to change this academic year's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Academic_year/controller.php",
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
        var getData = {
            id: data_id,
            action: 'view'
        };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Academic_year/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_acad_year").val(data.acad_year);
                $("#e_acad_status").val(data.status).trigger('change');
                $("#f_name").html(data.acad_year);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update academic year
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            acad_year: $("#e_acad_year").val(),
            status: $("#e_acad_status").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Academic_year/controller.php",
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
                    var $row = $("tr[data-row-id='"+data.acad_cycle_id+"']");
                    $row.find('.col-acad-year').text(data.acad_year);
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
