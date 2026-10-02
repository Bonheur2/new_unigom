<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Applications</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Applicants</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Paid Applications - Unlock Payment Status</h4>
                                        <div class="card-header-action">
                                            <button class="btn btn-info btn-sm" id="selectAllBtn">Select All</button>
                                            <button class="btn btn-success btn-sm ml-2" id="bulkUnlockBtn" disabled>Unlock Selected (<span id="selectedCount">0</span>)</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <strong>Info:</strong> This page shows applicants who have made payments but their payment status is still pending (0). You can unlock their payment status to allow them to proceed.
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="selectAll"></th>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Invoice Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Payment Date</th>
                                                        <th>Payment Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT 
    app.code,
    app.fname,
    app.lname,
    app.phone,
    app.email,
    inv.id AS invoice_id,
    inv.balance,
    inv.invoice_date,
    inv.payment_status,
    COALESCE(SUM(pay.amount), 0) AS total_paid,
    MAX(pay.slip_no) AS last_slip_no,
    MAX(pay.recorded_date) AS last_payment_date,
    MAX(pay.trans_code) AS last_trans_code
FROM tbl_applicants app
INNER JOIN tbl_invoice inv 
    ON app.code = inv.reg_no
INNER JOIN payment_trial pay 
    ON inv.fee_id = pay.fee_id
    AND inv.reg_no = pay.reg_no
WHERE inv.payment_status <> 1  -- not fully paid
    AND pay.status = 1  -- only approved payments
GROUP BY 
    app.code,
    app.fname,
    app.lname,
    app.phone,
    app.email,
    inv.id,
    inv.balance,
    inv.invoice_date,
    inv.payment_status
HAVING total_paid >= inv.balance  -- only show when payment is sufficient
ORDER BY last_payment_date DESC;

                                                                                    ");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($apps=$sql->fetch()){
                                                            // Calculate if payment is sufficient
                                                            $paymentSufficient = $apps['total_paid'] >= $apps['balance'];
                                                            $paymentStatus = $paymentSufficient ? 'success' : 'warning';
                                                            $statusText = $paymentSufficient ? 'Paid - Pending Unlock' : 'Partial Payment';
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="student-checkbox" 
                                                                    data-invoice-id="<?php echo $apps['invoice_id']; ?>" 
                                                                    data-code="<?php echo $apps['code']; ?>"
                                                                    data-email="<?php echo $apps['email']; ?>" 
                                                                    data-name="<?php echo $apps['fname']." ".$apps['lname']; ?>"
                                                                    <?php echo $paymentSufficient ? '' : 'disabled'; ?>></td>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $apps['code']; ?></td>
                                                            <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                                            <td><?php echo $apps['phone'] ?></td>
                                                            <td><?php echo $apps['email'] ?></td>
                                                            <td>NLE <?php echo number_format($apps['balance'], 2); ?></td>
                                                            <td>
                                                                <span class="text-<?php echo $paymentStatus; ?>">
                                                                    NLE <?php echo number_format($apps['total_paid'], 2); ?>
                                                                </span>
                                                                <?php if($paymentSufficient): ?>
                                                                    <i class="fas fa-check-circle text-success ml-1"></i>
                                                                <?php else: ?>
                                                                    <i class="fas fa-exclamation-circle text-warning ml-1"></i>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['last_payment_date'])); ?></td>
                                                            <td>
                                                                <span class="badge badge-<?php echo $paymentStatus; ?>"><?php echo $statusText; ?></span>
                                                                <br><small>Slip: <?php echo $apps['last_slip_no']; ?></small>
                                                                <br><small>Trans: <?php echo $apps['last_trans_code']; ?></small>
                                                            </td>
                                                            <td>
                                                                <?php if($paymentSufficient): ?>
                                                                    <button class="btn btn-success btn-sm" onclick="unlockSingle('<?php echo $apps['invoice_id']; ?>', '<?php echo $apps['code']; ?>', '<?php echo $apps['fname']." ".$apps['lname']; ?>', this)">
                                                                        <i class="fas fa-unlock"></i> Unlock
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-secondary btn-sm" disabled title="Payment insufficient">
                                                                        <i class="fas fa-lock"></i> Locked
                                                                    </button>
                                                                <?php endif; ?>
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        // Handle select all functionality - only select enabled checkboxes
        $('#selectAll').change(function() {
            $('.student-checkbox:not(:disabled)').prop('checked', this.checked);
            updateSelectedCount();
        });

        // Handle individual checkbox changes
        $(document).on('change', '.student-checkbox', function() {
            updateSelectedCount();
            
            // Update select all checkbox - only consider enabled checkboxes
            var totalCheckboxes = $('.student-checkbox:not(:disabled)').length;
            var checkedCheckboxes = $('.student-checkbox:not(:disabled):checked').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
        });

        // Handle bulk unlock - only process enabled and checked checkboxes
        $('#bulkUnlockBtn').click(function() {
            var selectedInvoices = [];
            var selectedNames = [];
            
            $('.student-checkbox:not(:disabled):checked').each(function() {
                selectedInvoices.push({
                    invoice_id: $(this).data('invoice-id'),
                    code: $(this).data('code'),
                    name: $(this).data('name')
                });
                selectedNames.push($(this).data('name'));
            });

            if (selectedInvoices.length === 0) {
                alert('Please select at least one applicant with sufficient payment to unlock.');
                return;
            }

            if (confirm('Are you sure you want to unlock payment status for ' + selectedInvoices.length + ' selected applicant(s)?\n\nApplicants:\n' + selectedNames.join('\n'))) {
                bulkUnlockPayments(selectedInvoices);
            }
        });

        function updateSelectedCount() {
            var count = $('.student-checkbox:not(:disabled):checked').length;
            $('#selectedCount').text(count);
            $('#bulkUnlockBtn').prop('disabled', count === 0);
        }

        // Update info alert to be more descriptive
        $('.alert-info').html('<strong>Info:</strong> This page shows applicants who have made payments. Only applicants with payments greater than or equal to their invoice amount can be unlocked. Partial payments are shown but cannot be unlocked until full payment is received.');
    });

    function unlockSingle(invoiceId, code, name, button) {
        if (!confirm('Are you sure you want to unlock payment status for ' + name + '?\n\nThis will mark their payment as approved and allow them to proceed.')) {
            return;
        }

        // Disable button and show loading state
        $(button).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Unlocking...');
        
        $.ajax({
            url: '../files/application/application_controller.php',
            type: 'POST',
            data: {
                invoice_id: invoiceId,
                code: code,
                name: name,
                action: 'unlock_payment_status'
            },
            dataType: 'json',
            success: function(response) {
                if(response.status == '200') {
                    alert('Payment status unlocked successfully for ' + name + '!\n\nThey can now proceed with their application.');
                    // Remove the row from table or update status
                    $(button).closest('tr').fadeOut(500, function() {
                        $(this).remove();
                        // Update counts after row removal
                        updateSelectedCount();
                    });
                } else {
                    alert('Error: ' + response.message);
                    $(button).prop('disabled', false).html('<i class="fas fa-unlock"></i> Unlock');
                }
            },
            error: function() {
                alert('An error occurred while unlocking the payment status.');
                $(button).prop('disabled', false).html('<i class="fas fa-unlock"></i> Unlock');
            }
        });
    }

    function bulkUnlockPayments(selectedInvoices) {
        $('#bulkUnlockBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Unlocking...');
        
        $.ajax({
            url: '../files/application/application_controller.php',
            type: 'POST',
            data: {
                selected_invoices: JSON.stringify(selectedInvoices),
                action: 'bulk_unlock_payment_status'
            },
            dataType: 'json',
            success: function(response) {
                if(response.status == '200') {
                    alert('Payment status unlocked successfully for ' + selectedInvoices.length + ' applicant(s)!\n\nThey can now proceed with their applications.');
                    // Reload the page to refresh the data
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $('#bulkUnlockBtn').prop('disabled', false).html('Unlock Selected (<span id="selectedCount">0</span>)');
                }
            },
            error: function() {
                alert('An error occurred while unlocking payment statuses.');
                $('#bulkUnlockBtn').prop('disabled', false).html('Unlock Selected (<span id="selectedCount">0</span>)');
            }
        });
    }
</script>