<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Application Periods</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Application Period</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Application Period</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_period" action="save_period" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Academic Year</label>
                                                    <select class="form-control select2" style="width:100%" id="acad_cycle_id" required>
                                                        <option value="">-- Select Academic Year --</option>
                                                        <?php
                                                            $sql_ay = $conn->prepare("SELECT acad_cycle_id, acad_year FROM tbl_acad_cycle WHERE status=1 ORDER BY acad_year DESC");
                                                            $sql_ay->execute();
                                                            while($ay = $sql_ay->fetch()):
                                                        ?>
                                                        <option value="<?php echo $ay['acad_cycle_id']; ?>"><?php echo $ay['acad_year']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Period Name</label>
                                                    <input type="text" class="form-control" id="period_name" placeholder="e.g. First Round" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Status</label>
                                                    <select class="form-control select2" style="width:100%" id="p_status">
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                        <option value="closed">Closed</option>
                                                        <option value="hold">Hold</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Start Date</label>
                                                    <input type="date" class="form-control" id="start_date" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>End Date</label>
                                                    <input type="date" class="form-control" id="end_date" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Description</label>
                                                    <input type="text" class="form-control" id="description" placeholder="Optional notes">
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
                                    <h4>Registered Application Periods</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm period_table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Academic Year</th>
                                                <th scope="col">Period Name</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">End Date</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT tbl_application_periods.*, tbl_acad_cycle.acad_year
                                                                        FROM tbl_application_periods
                                                                        LEFT JOIN tbl_acad_cycle ON tbl_acad_cycle.acad_cycle_id = tbl_application_periods.acad_cycle_id
                                                                        ORDER BY tbl_application_periods.id DESC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch()):
                                            ?>
                                            <tr data-row-id="<?php echo $row['id']; ?>">
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td class="col-acad-year"><?php echo $row['acad_year']; ?></td>
                                                <td class="col-period-name"><?php echo $row['period_name']; ?></td>
                                                <td class="col-start-date"><?php echo $row['start_date']; ?></td>
                                                <td class="col-end-date"><?php echo $row['end_date']; ?></td>
                                                <td class="col-description"><?php echo $row['description']; ?></td>
                                                <td class="col-status">
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <select class="form-control form-control-sm row-status" style="min-width:110px" data-id="<?php echo $row['id']; ?>">
                                                        <option value="active" <?php echo $row['status']=='active' ? 'selected' : ''; ?>>Active</option>
                                                        <option value="inactive" <?php echo $row['status']=='inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                        <option value="closed" <?php echo $row['status']=='closed' ? 'selected' : ''; ?>>Closed</option>
                                                        <option value="hold" <?php echo $row['status']=='hold' ? 'selected' : ''; ?>>Hold</option>
                                                    </select>
                                                    <span id="spinner3_<?php echo $row['id']; ?>"></span>
                                                    <?php else: ?>
                                                        <?php echo ucfirst($row['status']); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $row['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
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
                                    <label>Academic Year</label>
                                    <select class="form-control select2" style="width:100%" id="e_acad_cycle_id" required>
                                        <option value="">-- Select Academic Year --</option>
                                        <?php
                                            $sql_ay2 = $conn->prepare("SELECT acad_cycle_id, acad_year FROM tbl_acad_cycle WHERE status=1 ORDER BY acad_year DESC");
                                            $sql_ay2->execute();
                                            while($ay2 = $sql_ay2->fetch()):
                                        ?>
                                        <option value="<?php echo $ay2['acad_cycle_id']; ?>"><?php echo $ay2['acad_year']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Period Name</label>
                                    <input type="text" class="form-control" id="e_period_name" placeholder="e.g. First Round" required>
                                </div>
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" id="e_start_date" required>
                                </div>
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" id="e_end_date" required>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" style="width:100%" id="e_p_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="closed">Closed</option>
                                        <option value="hold">Hold</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" id="e_description" placeholder="Optional notes">
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
    var periodDt = $('.period_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });

    $('.row-status').each(function(){
        $(this).data('previous', $(this).val());
    });

    var statusLabels = {active:'Active', inactive:'Inactive', closed:'Closed', hold:'Hold'};

    function statusOptionsHtml(current){
        var html = '';
        $.each(statusLabels, function(val, label){
            html += '<option value="'+val+'"'+(val===current ? ' selected' : '')+'>'+label+'</option>';
        });
        return html;
    }

    // build a table row for an application period and add it to the DataTable
    function addPeriodRow(row){
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var statusHtml = '';
        var actionsHtml = '';
        var currentStatus = row.status_val || 'active';
        if(canManage){
            statusHtml = '<select class="form-control form-control-sm row-status" style="min-width:110px" data-id="'+row.id+'">'
                + statusOptionsHtml(currentStatus)
                + '</select> <span id="spinner3_'+row.id+'"></span>';
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button></div>';
        } else {
            statusHtml = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
        }
        var $tr = $(periodDt.row.add([
            periodDt.rows().count() + 1,
            row.acad_year || '',
            row.period_name,
            row.start_date || '',
            row.end_date || '',
            row.description || '',
            statusHtml,
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.id);
        $tr.find('.row-status').data('previous', currentStatus);
    }

    //save application period
    $("#save_period").submit(function(e){
        e.preventDefault();

        var formData = {
            acad_cycle_id: $("#acad_cycle_id").val(),
            period_name: $("#period_name").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val(),
            status: $("#p_status").val(),
            description: $("#description").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Application_Period/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_period')[0].reset();
                    $('#p_status').val('active').trigger('change');
                    pop_up_success(data.message);
                    addPeriodRow(data);
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

    // change status inline
    $(document).on('change','.row-status',function () {
        var $select = $(this);
        var data_id = $select.data('id');
        var new_status = $select.val();
        var previous_status = $select.data('previous');
        var getData = {
            id: data_id,
            status: new_status,
            action: 'change_status'
        };
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Application_Period/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500 || data.status==401){
                    $select.val(previous_status);
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                    $select.data('previous', new_status);
                    pop_up_success(data.message);
                }
            },
            error:function(error){
                $('#spinner3_'+data_id).fadeOut('fast');
                $select.val(previous_status);
                pop_wrong("Something went wrong");
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
            url: "../new_files/Application_Period/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_acad_cycle_id").val(data.acad_cycle_id).trigger('change');
                $("#e_period_name").val(data.period_name);
                $("#e_start_date").val(data.start_date);
                $("#e_end_date").val(data.end_date);
                $("#e_p_status").val(data.status).trigger('change');
                $("#e_description").val(data.description);
                $("#f_name").html(data.period_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update application period
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            acad_cycle_id: $("#e_acad_cycle_id").val(),
            period_name: $("#e_period_name").val(),
            start_date: $("#e_start_date").val(),
            end_date: $("#e_end_date").val(),
            status: $("#e_p_status").val(),
            description: $("#e_description").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Application_Period/controller.php",
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
                    var $row = $("tr[data-row-id='"+data.id+"']");
                    $row.find('.col-acad-year').text(data.acad_year || '');
                    $row.find('.col-period-name').text(data.period_name);
                    $row.find('.col-start-date').text(data.start_date || '');
                    $row.find('.col-end-date').text(data.end_date || '');
                    $row.find('.col-description').text(data.description || '');
                    $row.find('.row-status').val(data.status_val).data('previous', data.status_val);
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
