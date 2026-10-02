<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Account</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body row">
                                <form id="save_account" action="save_account" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>School</label>
                                            <select class="form-control select2" style="width:100%" name="fac_id">
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_faculty ORDER BY status ASC");
                                                    $sql->execute();
                                                    while ($facs = $sql->fetch()) {
                                                ?>
                                                <option value="<?php echo $facs['fac_id']; ?>"><?php echo $facs['fac_full_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>Bank Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-city"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="bank_name" placeholder="Type here">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>Bank code (<code>swift code is recommended</code>)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-card"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="bank_code" placeholder="Type here">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>Account Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="account_name" placeholder="Type here" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>Account Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-pencil"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="account_no" placeholder="Type here">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <label>Currency</label>
                                            <select class="form-control select2" style="width:100%" name="currency">
                                                <option value="USD">United States Dollar (USD)</option>
                                                <option value="EUR">Euro (EUR)</option>
                                                <option value="JPY">Japanese Yen (JPY)</option>
                                                <option value="GBP">British Pound Sterling (GBP)</option>
                                                <option value="AUD">Australian Dollar (AUD)</option>
                                                <option value="CAD">Canadian Dollar (CAD)</option>
                                                <option value="CHF">Swiss Franc (CHF)</option>
                                                <option value="CNY">Chinese Yuan (CNY)</option>
                                                <option value="SEK">Swedish Krona (SEK)</option>
                                                <option value="NZD">New Zealand Dollar (NZD)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-2">
                                            <label>&nbsp;</label>
                                            <div class="input-group">
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
                            <h4>Registered Accounts</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="account_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Bank name</th>
                                            <th scope="col">Bank code</th>
                                            <th scope="col">Account name</th>
                                            <th scope="col">Account number</th>
                                            <th scope="col">Currency</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = $conn->prepare("SELECT bk.*,fac.fac_full_name
                                                                    FROM tbl_bank bk
                                                                        INNER JOIN tbl_faculty fac ON bk.fac_id = fac.fac_id 
                                                                    WHERE fac.status = 1
                                                                    ORDER BY bk.bank_name ASC");
                                        $sql->execute();
                                        $i = 1;
                                        while ($accounts = $sql->fetch()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td><?php echo $accounts['bank_name']; ?></td>
                                                <td><?php echo $accounts['bank_code']; ?></td>
                                                <td><?php echo $accounts['account_name']; ?></td>
                                                <td><?php echo $accounts['account_no']; ?></td>
                                                <td><?php echo $accounts['currency']; ?></td>
                                                <th>
                                                    <?php //if ($role_id == 18) { ?>
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $accounts['bank_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $accounts['bank_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $accounts['bank_id']; ?>" <?php echo $accounts['status'] == 1 ? 'checked' : ''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $accounts['bank_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                                    <?php //} ?>
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
                        <h5 class="modal-title">Updating account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="bank_id" name="bank_id">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>School</label>
                            <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id">
                                <?php
                                    $sql = $conn->prepare("SELECT * FROM tbl_faculty ORDER BY status ASC");
                                    $sql->execute();
                                    while ($facs = $sql->fetch()) {
                                ?>
                                <option value="<?php echo $facs['fac_id']; ?>"><?php echo $facs['fac_full_name']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Bank Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-city"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Type here">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Bank code (<code>swift code is recommended</code>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-card"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="bank_code" id="bank_code" placeholder="Type here">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Account Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="account_name" id="account_name" placeholder="Type here" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Account Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pencil"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Type here">
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Currency</label>
                            <select class="form-control select2" style="width:100%" name="currency" id="currency">
                                <option value="USD">United States Dollar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                                <option value="JPY">Japanese Yen (JPY)</option>
                                <option value="GBP">British Pound Sterling (GBP)</option>
                                <option value="AUD">Australian Dollar (AUD)</option>
                                <option value="CAD">Canadian Dollar (CAD)</option>
                                <option value="CHF">Swiss Franc (CHF)</option>
                                <option value="CNY">Chinese Yuan (CNY)</option>
                                <option value="SEK">Swedish Krona (SEK)</option>
                                <option value="NZD">New Zealand Dollar (NZD)</option>
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
    $(document).ready(function() {
        $('table').DataTable({'paging': false})
        //save account
        $("#save_account").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Bank/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if (data.status == 200) {
                        pop_up_success(data.message);
                        $('#account_table').load(location.href + " #account_table");
                    } else {
                        pop_info(data.message);
                    }
                },
                error: function() {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_info("Something went wrong!");
                }
            });
        });

        // delete account
        $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData = {
                bank_id: data_id,
                action: 'delete'
            };
            swal({
                title: "Are you sure?",
                text: "You are about to change this account's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Bank/controller.php",
                        data: getData,
                        dataType: "json",
                        success: function(data) {
                            $('#spinner3_' + data_id).fadeOut('fast');
                            if (data.status == 200) {
                                pop_up_success(data.message);
                                $('#account_table').load(location.href + " #account_table");
                            } else {
                                pop_info(data.message);
                            }
                        },
                        error: function(error) {
                            $('#spinner3_' + data_id).fadeOut('fast');
                            pop_info("Something went wrong");
                        }
                    });
                } else {
                    swal("operation cancelled!!");
                }
            });
        });

        //pre-update View
        $(document).on('click', '.edit', function() {
            var data_id = $(this).data('id');
            var getData = {
                bank_id: data_id,
                action: 'view'
            };
            $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Bank/controller.php",
                data: getData,
                dataType: "json",
                success: function(data) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    $("#bank_id").val(data_id);
                    $("#bank_name").val(data.bank_name);
                    $("#bank_code").val(data.bank_code);
                    $("#account_name").val(data.account_name);
                    $("#account_no").val(data.account_no);
                    
                    var selectElement = document.getElementById('fac_id');
                    var selectElement2 = document.getElementById('currency');

                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.fac_id + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.currency + '"]');
                    if (selectedOption) {
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    if (selectedOption2) {
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    $('#updateModal').modal('show');
                },
                error: function(error) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    pop_info("Something went wrong!");
                }
            });
        });

        //update account
        $("#update_form").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Bank/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if (data.status == 200) {
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#account_table').load(location.href + " #account_table");
                    } else {
                        pop_info(data.message);
                    }
                },
                error: function() {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_info("Something went wrong!");

                }
            });
        });
    });

    function pop_info(feedback) {
        iziToast.warning({
            title: 'info',
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