<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Record Payment</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Record Payment</a></div>
            </div><br>
        </div>

        <!-- Section 1: Search Student -->
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Search Student</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group col-12 col-md-6 m-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by reg. number or names"
                                        id="pay_input">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="pay_spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="pay_contents"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Academic History -->
        <div class="section-body" id="pay_info" hidden>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Student Academic History</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Specialization</th>
                                        <th>Level</th>
                                        <th>Academic Year</th>
                                        <th>Invoiced</th>
                                        <th>Paid</th>
                                        <th>Special</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="pay_academics"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Invoices + Payment Form -->
        <div class="section-body" id="pay_form_section" hidden>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Record Payment</h4>
                        </div>
                        <div class="card-body">
                            <div id="pay_student_label" class="mb-3 font-weight-bold"></div>

                            <!-- Invoice table with amount inputs -->
                            <table class="table table-bordered table-sm mb-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fee Name</th>
                                        <th>Type</th>
                                        <th>Invoiced</th>
                                        <th>Already Paid</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th style="width:150px;">Amount to Pay</th>
                                    </tr>
                                </thead>
                                <tbody id="pay_invoice_rows"></tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="7" class="text-right">Total Payment:</td>
                                        <td><span id="pay_total_display">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Payment details -->
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <label>Bank <span class="text-danger">*</span></label>
                                    <select class="form-control" id="pay_bank_id" required>
                                        <option value="" disabled selected>Select Bank</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label>Slip/Receipt No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pay_slip_no" required>
                                </div>
                                <div class="form-group col-12 col-md-2">
                                    <label>Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="pay_date" required>
                                </div>
                                <div class="form-group col-12 col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary btn-block" id="pay_submit_btn">
                                        <i class="fas fa-save"></i> Save Payment
                                    </button>
                                </div>
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

<script>
$(document).ready(function() {

    var payCurrentReg = '';
    var payCurrentAcad = '';
    var payCurrentFac = '';
    var payCurrentAcadYear = '';

    // Set default date to today
    var today = new Date().toISOString().split('T')[0];
    $('#pay_date').val(today);

    // ======================= STUDENT SEARCH =======================

    $("#pay_input").keyup(function() {
        var keyword = $(this).val();
        if (keyword.length < 2) return;
        $("#pay_info").attr("hidden", true);
        $("#pay_form_section").attr("hidden", true);
        $('#pay_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: {
                keyword: keyword,
                action: 'search'
            },
            dataType: "JSON",
            success: function(data) {
                $('#pay_spinner').html("<i class='fas fa-search'></i>");
                if (data.length > 0) {
                    var i = 1,
                        html = '';
                    data.forEach(function(value) {
                        html += '<tr class="pay_stu" data-id="' + value.reg_no +
                            '" style="cursor:pointer;">';
                        html += '<th>' + i + '</th>';
                        html += '<th>' + value.reg_no + '</th>';
                        html += '<td>' + value.fname + ' ' + value.lname + '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#pay_contents').html(html);
                } else {
                    $('#pay_contents').html(
                        '<tr><td colspan="3" align="center">No data found</td></tr>');
                }
            },
            error: function() {
                $('#pay_spinner').html("<i class='fas fa-search'></i>");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // ======================= CLICK STUDENT =======================

    $(document).on('click', '.pay_stu', function() {
        var student = $(this).data("id");
        $("#pay_input").val(student);
        $("#pay_contents").html("");
        $("#pay_form_section").attr("hidden", true);
        $('#pay_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: {
                stu: student,
                action: 'load_info_payment'
            },
            dataType: "JSON",
            success: function(data) {
                $('#pay_spinner').html("<i class='fas fa-search'></i>");
                if (data.length > 0) {
                    $("#pay_info").removeAttr('hidden');
                    var i = 1,
                        html = '';
                    data.forEach(function(value) {
                        html += '<tr>';
                        html += '<td>' + i + '</td>';
                        html += '<td>' + value.splz_full_name + '</td>';
                        html += '<td>' + value.level_full_name + '</td>';
                        html += '<td>' + value.acad_year + '</td>';
                        html += '<td>' + parseFloat(value.invoice)
                            .toLocaleString() + '</td>';
                        html += '<td>' + parseFloat(value.payment)
                            .toLocaleString() + '</td>';
                        html += '<td>' + parseFloat(value.special || 0)
                            .toLocaleString() + '</td>';
                        html +=
                            '<td><button class="btn btn-sm btn-info pay_view_invoices" ' +
                            'data-reg="' + value.reg_no + '" ' +
                            'data-acad="' + value.acad_cycle_id + '" ' +
                            'data-fac="' + value.fac_id + '" ' +
                            'data-year="' + value.acad_year + '">' +
                            '<i class="fas fa-eye"></i> View</button></td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#pay_academics').html(html);
                } else {
                    $("#pay_info").attr("hidden", true);
                    pop_info("No academic records found.");
                }
            },
            error: function() {
                $('#pay_spinner').html("<i class='fas fa-search'></i>");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // ======================= VIEW INVOICES =======================

    $(document).on('click', '.pay_view_invoices', function() {
        var reg_no = $(this).data("reg");
        var acad_cycle_id = $(this).data("acad");
        var fac_id = $(this).data("fac");
        var acad_year = $(this).data("year");
        var btn = $(this);

        payCurrentReg = reg_no;
        payCurrentAcad = acad_cycle_id;
        payCurrentFac = fac_id;
        payCurrentAcadYear = acad_year;

        $('#pay_student_label').text('Student: ' + reg_no + ' | Academic Year: ' + acad_year);
        btn.html('<img src="../../img/ajax_loader.gif" width="15">');

        // Load invoices with remaining balances
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: {
                reg_no: reg_no,
                acad_cycle_id: acad_cycle_id,
                action: 'load_student_invoices_for_payment'
            },
            dataType: "JSON",
            success: function(data) {
                btn.html('<i class="fas fa-eye"></i> View');
                if (data.length > 0) {
                    var i = 1,
                        html = '';
                    data.forEach(function(inv) {
                        var remaining = parseFloat(inv.remaining_balance) || 0;
                        var original = parseFloat(inv.original_balance) || 0;
                        var paid = parseFloat(inv.total_paid) || 0;
                        var isCancelled = inv.approval_status == 2;
                        var isFullyPaid = remaining <= 0;
                        var feeType = (inv.special_fee_id && inv.special_fee_id !=
                                '' && inv.special_fee_id != '0') ?
                            'Special' : 'Regular';
                        var statusBadge = '';
                        if (isCancelled) {
                            statusBadge =
                                '<span class="badge badge-danger">Cancelled</span>';
                        } else if (isFullyPaid) {
                            statusBadge =
                                '<span class="badge badge-success">Fully Paid</span>';
                        } else if (paid > 0) {
                            statusBadge =
                                '<span class="badge badge-warning">Partial</span>';
                        } else {
                            statusBadge =
                                '<span class="badge badge-secondary">Unpaid</span>';
                        }

                        var canPay = !isCancelled && !isFullyPaid;
                        var feeId = inv.fee_id || inv.special_fee_id;

                        html += '<tr' + (canPay ? '' : ' class="text-muted"') + '>';
                        html += '<td>' + i + '</td>';
                        html += '<td>' + inv.fee_name + '</td>';
                        html += '<td><span class="badge badge-' + (feeType ===
                                'Special' ? 'info' : 'primary') + '">' + feeType +
                            '</span></td>';
                        html += '<td>' + original.toLocaleString() + '</td>';
                        html += '<td>' + paid.toLocaleString() + '</td>';
                        html += '<td>' + remaining.toLocaleString() + '</td>';
                        html += '<td>' + statusBadge + '</td>';
                        html += '<td>';
                        if (canPay) {
                            html +=
                                '<input type="number" class="form-control form-control-sm pay_amount_input" ' +
                                'data-fee-id="' + feeId + '" ' +
                                'data-invoice-id="' + inv.id + '" ' +
                                'data-max="' + remaining + '" ' +
                                'step="0.01" min="0" max="' + remaining + '" ' +
                                'placeholder="0.00">';
                        } else {
                            html += '-';
                        }
                        html += '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#pay_invoice_rows').html(html);
                    $('#pay_total_display').text('0');
                    $('#pay_form_section').removeAttr('hidden');
                } else {
                    $('#pay_invoice_rows').html(
                        '<tr><td colspan="8" align="center">No invoices found</td></tr>'
                    );
                    $('#pay_form_section').removeAttr('hidden');
                }
            },
            error: function() {
                btn.html('<i class="fas fa-eye"></i> View');
                pop_wrong("Something went wrong!");
            }
        });

        // Load banks for student's faculty
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: {
                fac_id: fac_id,
                action: 'load_banks'
            },
            dataType: "JSON",
            success: function(data) {
                $('#pay_bank_id').empty().append(
                    '<option value="" disabled selected>Select Bank</option>');
                if (data.length > 0) {
                    data.forEach(function(bank) {
                        $('#pay_bank_id').append('<option value="' + bank.bank_id +
                            '">' +
                            bank.bank_name + ' - ' + bank.account_no + ' (' +
                            bank.currency + ')' +
                            '</option>');
                    });
                }
            },
            error: function() {
                pop_wrong("Failed to load banks!");
            }
        });
    });

    // ======================= UPDATE TOTAL =======================

    $(document).on('input', '.pay_amount_input', function() {
        var max = parseFloat($(this).data('max'));
        var val = parseFloat($(this).val()) || 0;
        if (val > max) {
            $(this).val(max);
            val = max;
        }
        if (val < 0) {
            $(this).val('');
            val = 0;
        }
        // Calculate total
        var total = 0;
        $('.pay_amount_input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#pay_total_display').text(total.toLocaleString());
    });

    // ======================= SUBMIT PAYMENT =======================

    $('#pay_submit_btn').on('click', function() {
        // Collect all invoices with amounts > 0
        var payments = [];
        $('.pay_amount_input').each(function() {
            var amount = parseFloat($(this).val()) || 0;
            if (amount > 0) {
                payments.push({
                    fee_id: $(this).data('fee-id'),
                    invoice_id: $(this).data('invoice-id'),
                    amount: amount
                });
            }
        });

        if (payments.length === 0) {
            pop_info("Please enter at least one amount to pay!");
            return;
        }
        if (!$('#pay_bank_id').val()) {
            pop_info("Please select a bank!");
            return;
        }
        if (!$('#pay_slip_no').val().trim()) {
            pop_info("Please enter a slip/receipt number!");
            return;
        }

        var total = 0;
        payments.forEach(function(p) {
            total += p.amount;
        });

        swal({
            title: "Confirm Payment",
            text: "Record " + payments.length + " payment(s) totalling " + total
                .toLocaleString() + " for student " + payCurrentReg + "?",
            icon: "info",
            buttons: true,
        }).then(function(willPay) {
            if (willPay) {
                $('#pay_submit_btn').html(
                    '<img src="../../img/ajax_loader.gif" width="15"> Saving...').prop(
                    'disabled', true);

                var postData = {
                    action: 'save_payment',
                    user: '<?php echo $identification; ?>',
                    reg_no: payCurrentReg,
                    acad_cycle_id: payCurrentAcad,
                    bank_id: $('#pay_bank_id').val(),
                    slip_no: $('#pay_slip_no').val().trim(),
                    PayMode: null,
                    date: $('#pay_date').val(),
                    comment: $('#pay_comment').val(),
                    payments: JSON.stringify(payments)
                };

                $.ajax({
                    url: "/files/Invoice/invoice_controller.php",
                    type: "POST",
                    data: postData,
                    dataType: "JSON",
                    success: function(data) {
                        $('#pay_submit_btn').html(
                            '<i class="fas fa-save"></i> Save Payment').prop(
                            'disabled', false);
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            // Reset
                            $('#pay_slip_no').val('');
                            $('#pay_comment').val('');
                            // Reload invoices to show updated balances
                            $('.pay_view_invoices[data-reg="' + payCurrentReg +
                                    '"][data-acad="' + payCurrentAcad + '"]')
                                .trigger('click');
                        } else {
                            pop_info(data.message);
                        }
                    },
                    error: function() {
                        $('#pay_submit_btn').html(
                            '<i class="fas fa-save"></i> Save Payment').prop(
                            'disabled', false);
                        pop_wrong("Something went wrong!");
                    }
                });
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
    iziToast.info({
        title: 'Ooops',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
    });
}
</script>