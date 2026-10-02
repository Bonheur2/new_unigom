<?php require'../meet/bind.php'; ?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Special Fee Category</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Special Fee Category</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Special Fee Category</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_special_invoice" method="POST">
                                    <input type="hidden" name="action" value="save">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Name <code>*</code></label>
                                            <input type="text" class="form-control" name="name" placeholder="Fee category name" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Description</label>
                                            <input type="text" class="form-control" name="description" placeholder="Description">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Has known price? <code>*</code></label>
                                            <select class="form-control" id="has_price" required>
                                                <option value="" disabled selected hidden>Choose...</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2" id="amount_div" style="display:none;">
                                            <label>Amount <code>*</code></label>
                                            <input type="number" class="form-control" name="amount" id="amount_input" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                        <div class="form-group col-md-1" style="margin-top:30px;">
                                            <button type="submit" class="btn btn-primary btn-block" id="sBtn">
                                                <span id="spinner"></span>&nbsp;<span id="indicator">Save</span>
                                            </button>
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
                            <h4>Registered Special Fee Categories</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="sp_inv_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Known Price</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = $conn->prepare("SELECT * FROM tbl_special_invoice ORDER BY status ASC, sp_inv_id DESC");
                                            $sql->execute();
                                            $i = 1;
                                            while($row = $sql->fetch()){
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td><?php echo $row['has_known_price'] == 1 ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'; ?></td>
                                            <td><?php echo $row['has_known_price'] == 1 ? number_format($row['amount'], 2) : 'N/A'; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit" data-id="<?php echo $row['sp_inv_id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input del"
                                                        data-id="<?php echo $row['sp_inv_id']; ?>"
                                                        <?php if($row['status'] == 1) echo 'checked'; ?>>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
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
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Special Fee Category</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="update_form" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="sp_inv_id" id="up_sp_inv_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name <code>*</code></label>
                        <input type="text" class="form-control" name="name" id="up_name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description" id="up_description">
                    </div>
                    <div class="form-group">
                        <label>Has known price? <code>*</code></label>
                        <select class="form-control" id="up_has_price" required>
                            <option value="" disabled selected hidden>Choose...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="form-group" id="up_amount_div" style="display:none;">
                        <label>Amount <code>*</code></label>
                        <input type="number" class="form-control" name="amount" id="up_amount" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uBtn">
                        <span id="u_spinner"></span>&nbsp;<span id="u_indicator">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('#sp_inv_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });

    // Save form - toggle amount
    $('#has_price').on('change', function() {
        if ($(this).val() == 'yes') {
            $('#amount_div').show();
            $('#amount_input').attr('required', true);
        } else {
            $('#amount_div').hide();
            $('#amount_input').removeAttr('required').val('');
        }
    });

    // Update modal - toggle amount
    $('#up_has_price').on('change', function() {
        if ($(this).val() == 'yes') {
            $('#up_amount_div').show();
            $('#up_amount').attr('required', true);
        } else {
            $('#up_amount_div').hide();
            $('#up_amount').removeAttr('required').val('');
        }
    });

    // Save
    $("#save_special_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#sBtn').attr('disabled', true);
        $('#spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#indicator').html('Saving...');

        $.ajax({
            url: '/files/Fee_categories/special_invoice_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#sBtn').attr('disabled', false);
                $('#spinner').html('');
                $('#indicator').html('Save');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#save_special_invoice')[0].reset();
                    $('#amount_div').hide();
                    $('#sp_inv_table').load(location.href + " #sp_inv_table");
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#sBtn').attr('disabled', false);
                $('#spinner').html('');
                $('#indicator').html('Save');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // Update
    $("#update_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#uBtn').attr('disabled', true);
        $('#u_spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#u_indicator').html('Updating...');

        $.ajax({
            url: '/files/Fee_categories/special_invoice_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#uBtn').attr('disabled', false);
                $('#u_spinner').html('');
                $('#u_indicator').html('Update');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#updateModal').modal('hide');
                    $('#sp_inv_table').load(location.href + " #sp_inv_table");
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#uBtn').attr('disabled', false);
                $('#u_spinner').html('');
                $('#u_indicator').html('Update');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });
});

// Edit - load data into modal
$(document).on('click', '.edit', function() {
    var id = $(this).data('id');
    $.ajax({
        url: '/files/Fee_categories/special_invoice_controller.php',
        type: 'POST',
        data: { action: 'view', sp_inv_id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status == 200) {
                $('#up_sp_inv_id').val(response.data.sp_inv_id);
                $('#up_name').val(response.data.name);
                $('#up_description').val(response.data.description);
                if (response.data.amount && parseFloat(response.data.amount) > 0) {
                    $('#up_has_price').val('yes');
                    $('#up_amount_div').show();
                    $('#up_amount').val(response.data.amount).attr('required', true);
                } else {
                    $('#up_has_price').val('no');
                    $('#up_amount_div').hide();
                    $('#up_amount').val('').removeAttr('required');
                }
                $('#updateModal').modal('show');
            } else {
                pop_wrong(response.message);
            }
        }
    });
});

// Toggle status (soft delete)
$(document).on('click', '.del', function() {
    var id = $(this).data('id');
    var el = $(this);
    swal({
        title: "Are you sure?",
        text: "You want to change the status?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function(willDelete) {
        if (willDelete) {
            $.ajax({
                url: '/files/Fee_categories/special_invoice_controller.php',
                type: 'POST',
                data: { action: 'delete', sp_inv_id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        pop_up_success(response.message);
                        $('#sp_inv_table').load(location.href + " #sp_inv_table");
                    } else {
                        pop_wrong(response.message);
                    }
                }
            });
        } else {
            el.prop('checked', !el.prop('checked'));
        }
    });
});

function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Wrong',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Info:',
        message: feedback,
        position: 'topCenter'
    });
}
</script>
