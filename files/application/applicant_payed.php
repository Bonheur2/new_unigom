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
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Filters</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>From Date:</label>
                                            <input type="date" id="from_date" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>To Date:</label>
                                            <input type="date" id="to_date" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Channel:</label>
                                            <select id="channel" class="form-control">
                                                <option value="">All Channels</option>
                                                <option value="SLCB">SLCB</option>
                                                <option value="Africell">Africell</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                                                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                                                <button type="button" id="downloadPdfBtn" class="btn btn-success">Download PDF</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="row mb-4" id="statisticsSection">
                        <?php
                            $totalSql = $conn->prepare("SELECT COUNT(*) as total_apps, SUM(amount) as total_amount FROM payment_trial
                            WHERE payment_trial.reg_no IN (SELECT code FROM tbl_applicants WHERE submitted != 10)");
$totalSql->execute();
$totals = $totalSql->fetch();

$slcbSql = $conn->prepare("SELECT COUNT(*) as slcb_count, SUM(amount) as slcb_amount FROM payment_trial pt WHERE pt.user = 'B-AGENT'
AND pt.reg_no IN (SELECT code FROM tbl_applicants WHERE submitted != 10)");
$slcbSql->execute();
$slcb = $slcbSql->fetch();

$africellSql = $conn->prepare("SELECT COUNT(*) as africell_count, SUM(amount) as africell_amount FROM payment_trial pt WHERE pt.user != 'B-AGENT'
AND pt.reg_no IN (SELECT code FROM tbl_applicants WHERE submitted != 10)");
$africellSql->execute();
$africell = $africellSql->fetch();

// Group payments by academic year
$paymentsSql = $conn->prepare("SELECT pt.*, ap.fname, ap.lname, ap.code, ac.acad_year
    FROM payment_trial pt
    INNER JOIN tbl_applicants ap ON pt.reg_no = ap.code
    INNER JOIN tbl_admittedPRG adp ON ap.code = adp.Stu_code
    INNER JOIN tbl_acad_cycle ac ON adp.acad_id = ac.acad_cycle_id
    WHERE ap.submitted != 10
    GROUP BY pt.reg_no, ac.acad_year
    ORDER BY ac.acad_year DESC, pt.recorded_date DESC");
$paymentsSql->execute();

$groupedPayments = [];
while ($row = $paymentsSql->fetch()) {
    $yearKey = !empty($row['acad_year']) ? $row['acad_year'] : 'Unknown Academic Year';
    $groupedPayments[$yearKey][] = $row;
}
$paymentYearKeys = array_keys($groupedPayments);
                        ?>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-file-alt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Payed Applications</h4>
                                    </div>
                                    <div class="card-body" id="totalApps">
                                        <?php echo $totals['total_apps']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Amount</h4>
                                    </div>
                                    <div class="card-body" id="totalAmount">
                                        <?php echo number_format($totals['total_amount']); ?> NLE
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>SLCB Payments</h4>
                                    </div>
                                    <div class="card-body" id="slcbStats">
                                        <?php echo $slcb['slcb_count']; ?> (<?php echo number_format($slcb['slcb_amount']); ?> NLE)
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Africell Payments</h4>
                                    </div>
                                    <div class="card-body" id="africellStats">
                                        <?php echo $africell['africell_count']; ?> (<?php echo number_format($africell['africell_amount']); ?> NLE)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card" id="sample-login">
            <div class="card-header">
                <h4>Pending Applications</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($paymentYearKeys)) { ?>
                    <ul class="nav nav-tabs" id="paymentYearTabs" role="tablist">
                        <?php foreach ($paymentYearKeys as $yearIndex => $yearLabel) {
                            $tabId = 'pay-year-' . preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($yearLabel));
                        ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $yearIndex === 0 ? 'active' : ''; ?>"
                                   id="<?php echo $tabId; ?>-tab"
                                   data-toggle="tab"
                                   href="#<?php echo $tabId; ?>"
                                   role="tab"
                                   aria-controls="<?php echo $tabId; ?>"
                                   aria-selected="<?php echo $yearIndex === 0 ? 'true' : 'false'; ?>">
                                    <?php echo htmlspecialchars($yearLabel); ?>
                                    <span class="badge badge-primary ml-1"><?php echo count($groupedPayments[$yearLabel]); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="tab-content pt-3" id="paymentYearTabsContent">
                        <?php foreach ($paymentYearKeys as $yearIndex => $yearLabel) {
                            $tabId = 'pay-year-' . preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($yearLabel));
                            $tableId = 'payments_' . $yearIndex;
                            $serial = 1;
                        ?>
                            <div class="tab-pane fade <?php echo $yearIndex === 0 ? 'show active' : ''; ?>"
                                 id="<?php echo $tabId; ?>"
                                 role="tabpanel"
                                 aria-labelledby="<?php echo $tabId; ?>-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm payment-table" id="<?php echo $tableId; ?>">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tracking Number</th>
                                                <th>Applicant Names</th>
                                                <th>Payment Channel</th>
                                                <th>Paid Amount</th>
                                                <th>Date Done</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($groupedPayments[$yearLabel] as $apps) { ?>
                                                <tr>
                                                    <td><?php echo $serial++; ?></td>
                                                    <td><?php echo htmlspecialchars($apps['code']); ?></td>
                                                    <td><?php echo htmlspecialchars($apps['fname'] . ' ' . $apps['lname']); ?></td>
                                                    <td><?php echo ($apps['user'] == 'B-AGENT') ? 'SLCB' : 'Africell'; ?></td>
                                                    <td><?php echo htmlspecialchars($apps['amount']) . ' NLE'; ?></td>
                                                    <td><?php echo htmlspecialchars($apps['recorded_date']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info mb-0">No pending applications found.</div>
                <?php } ?>
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
        // Initialize DataTables for each tab
$('.payment-table').each(function() {
    $(this).DataTable({
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "iDisplayLength": 25
    });
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function() {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

        // Filter button click
        $('#filterBtn').click(function(){
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            var channel = $('#channel').val();
            
            $.ajax({
                url: '../files/application/filter_applications.php',
                type: 'POST',
                data: {
                    from_date: fromDate,
                    to_date: toDate,
                    channel: channel
                },
                dataType: 'json',
                success: function(response) {
                    // Update statistics
                    $('#totalApps').text(response.stats.total_apps);
                    $('#totalAmount').text(response.stats.total_amount + ' NLE');
                    $('#slcbStats').text(response.stats.slcb_count + ' (' + response.stats.slcb_amount + ' NLE)');
                    $('#africellStats').text(response.stats.africell_count + ' (' + response.stats.africell_amount + ' NLE)');
                    
                    // Update table
                    table.clear();
                    $.each(response.data, function(index, row) {
                        table.row.add([
                            index + 1,
                            row.code,
                            row.fname + ' ' + row.lname,
                            (row.user == 'B-AGENT') ? 'SLCB' : 'Africell',
                            row.amount + ' NLE',
                            row.recorded_date
                        ]);
                    });
                    table.draw();
                }
            });
        });

        // Download PDF button click
        $('#downloadPdfBtn').click(function(){
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            var channel = $('#channel').val();
            
            // Create a form and submit it to generate PDF
            var form = $('<form>', {
                'method': 'POST',
                'action': '../files/application/generate_pdf.php',
                'target': '_blank'
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'from_date',
                'value': fromDate
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'to_date',
                'value': toDate
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'channel',
                'value': channel
            }));
            
            $('body').append(form);
            form.submit();
            form.remove();
        });

        // Reset button click
        $('#resetBtn').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#channel').val('');
            $('#filterBtn').click(); // Trigger filter with empty values
        });
    });
</script>