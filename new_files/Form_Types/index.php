<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Application Forms</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Application Forms</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Application Form</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_form_type" action="save_form_type" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Form Name</label>
                                                    <input type="text" class="form-control" id="form_name" placeholder="e.g. Undergraduate Application" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Status</label>
                                                    <select class="form-control select2" style="width:100%" id="f_status">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Description</label>
                                                    <textarea class="form-control" id="description" placeholder="Describe this form's purpose" rows="2"></textarea>
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
                                    <h4>Registered Application Forms</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                    <p class="text-muted mb-2"><i class="fas fa-arrows-alt"></i> Drag rows by the handle to reorder. The new order saves automatically.</p>
                                    <?php endif; ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm form_type_table">
                                            <thead>
                                            <tr>
                                                <th scope="col" style="width:40px;"></th>
                                                <th scope="col">#</th>
                                                <th scope="col">Form Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="form_type_tbody">
                                            <?php
                                                $sql = $conn->prepare("SELECT form_id, form_name, description, status FROM tbl_form_types ORDER BY `rank` ASC, form_id ASC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch()):
                                            ?>
                                            <tr data-row-id="<?php echo $row['form_id']; ?>">
                                                <td class="drag-handle text-center" style="cursor:grab;">
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <i class="fas fa-grip-vertical text-muted"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <th scope="row" class="col-rank-num"><?php echo $i++; ?></th>
                                                <td class="col-form-name"><?php echo htmlspecialchars($row['form_name']); ?></td>
                                                <td class="col-description"><?php echo htmlspecialchars($row['description']); ?></td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $row['form_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $row['form_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['form_id']; ?>" <?php echo $row['status']==1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['form_id']; ?>"></span>&nbsp;
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
                                    <label>Form Name</label>
                                    <input type="text" class="form-control" id="e_form_name" placeholder="e.g. Undergraduate Application" required>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" style="width:100%" id="e_f_status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" id="e_description" placeholder="Describe this form's purpose" rows="2"></textarea>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

<script>
var canManageFormTypes = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;

$(document).ready(function(){

    // renumber the visible "#" column to match current row order
    function renumberRows(){
        $('#form_type_tbody tr').each(function(index){
            $(this).find('.col-rank-num').text(index + 1);
        });
    }

    // drag-and-drop reordering
    if(canManageFormTypes && document.getElementById('form_type_tbody')){
        Sortable.create(document.getElementById('form_type_tbody'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(){
                renumberRows();

                var order = $('#form_type_tbody tr').map(function(){
                    return $(this).data('row-id');
                }).get();

                $.ajax({
                    url: "../new_files/Form_Types/controller.php",
                    type: "POST",
                    data: { order: order, action: 'reorder' },
                    dataType: "JSON",
                    success: function(data){
                        if(data.status==200){
                            pop_up_success(data.message);
                        } else {
                            pop_wrong(data.message);
                        }
                    },
                    error: function(){
                        pop_wrong("Something went wrong while saving the new order!");
                    }
                });
            }
        });
    }

    // build a table row for a form type and append it to the table
    function addFormTypeRow(row){
        var actionsHtml = '';
        if(canManageFormTypes){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.form_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.form_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.form_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.form_id+'"></span>&nbsp;</label></div>';
        }
        var handleHtml = canManageFormTypes ? '<i class="fas fa-grip-vertical text-muted"></i>' : '';
        var $tr = $('<tr data-row-id="'+row.form_id+'">'
            + '<td class="drag-handle text-center" style="cursor:grab;">'+handleHtml+'</td>'
            + '<th scope="row" class="col-rank-num"></th>'
            + '<td class="col-form-name"></td>'
            + '<td class="col-description"></td>'
            + '<th></th>'
            + '</tr>');
        $tr.find('.col-form-name').text(row.form_name);
        $tr.find('.col-description').text(row.description || '');
        $tr.find('th').last().html(actionsHtml);
        $('#form_type_tbody').append($tr);
        renumberRows();
    }

    //save form type
    $("#save_form_type").submit(function(e){
        e.preventDefault();

        var formData = {
            form_name: $("#form_name").val(),
            description: $("#description").val(),
            status: $("#f_status").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Form_Types/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_form_type')[0].reset();
                    $('#f_status').val('1').trigger('change');
                    pop_up_success(data.message);
                    addFormTypeRow(data);
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

    // delete/toggle form type
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Are you sure?",
            text: "You are about to change this form's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Form_Types/controller.php",
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
            url: "../new_files/Form_Types/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_form_name").val(data.form_name);
                $("#e_description").val(data.description);
                $("#e_f_status").val(data.status).trigger('change');
                $("#f_name").html(data.form_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update form type
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            form_name: $("#e_form_name").val(),
            description: $("#e_description").val(),
            status: $("#e_f_status").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Form_Types/controller.php",
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
                    var $row = $("tr[data-row-id='"+data.form_id+"']");
                    $row.find('.col-form-name').text(data.form_name);
                    $row.find('.col-description').text(data.description || '');
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
