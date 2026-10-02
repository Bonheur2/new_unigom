<?php

// 1. Current academic cycle
$acad = $conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status = 1 LIMIT 1");
$acad->execute();
$ac = $acad->fetch(PDO::FETCH_ASSOC);

// ── STUDENT STATISTICS (enrolled / registered students) ─────
$q = $conn->prepare("SELECT COALESCE(COUNT(DISTINCT reg_no),0) AS count FROM tbl_register_program_ug WHERE reg_active = 1");
$q->execute();
$active = $q->fetch(PDO::FETCH_ASSOC)['count'];

$q = $conn->prepare("SELECT COALESCE(COUNT(DISTINCT reg_no),0) AS count FROM tbl_register_program_ug WHERE reg_active = 3");
$q->execute();
$suspended = $q->fetch(PDO::FETCH_ASSOC)['count'];

$q = $conn->prepare("SELECT COALESCE(COUNT(DISTINCT reg_no),0) AS count FROM tbl_register_program_ug WHERE reg_active = 5");
$q->execute();
$dropout = $q->fetch(PDO::FETCH_ASSOC)['count'];

// ── APPLICANT STATISTICS ─────────────────────────────────────
// Total submitted (submitted != 10 matches your reference query)
$q = $conn->prepare("SELECT COALESCE(COUNT(DISTINCT code),0) AS count FROM tbl_applicants WHERE submitted != 10");
$q->execute();
$totalSubmitted = $q->fetch(PDO::FETCH_ASSOC)['count'];

// Applicants who have been invoiced
$q = $conn->prepare("
    SELECT COALESCE(COUNT(DISTINCT app.code),0) AS count
    FROM tbl_applicants app
    INNER JOIN tbl_invoice inv ON inv.reg_no = app.code
    WHERE app.submitted != 10
");
$q->execute();
$invoiced = $q->fetch(PDO::FETCH_ASSOC)['count'];

// Applicants who have paid
$q = $conn->prepare("
    SELECT COALESCE(COUNT(DISTINCT app.code),0) AS count
    FROM tbl_applicants app
    INNER JOIN tbl_invoice inv ON inv.reg_no = app.code
    INNER JOIN payment_trial py ON py.reg_no = app.code
    WHERE app.submitted != 10
");
$q->execute();
$paid = $q->fetch(PDO::FETCH_ASSOC)['count'];

// Applicants who have NOT paid
$q = $conn->prepare("
    SELECT COALESCE(COUNT(DISTINCT app.code),0) AS count
    FROM tbl_applicants app
    INNER JOIN tbl_invoice inv ON inv.reg_no = app.code
    LEFT JOIN payment_trial py ON py.reg_no = app.code
    WHERE app.submitted != 10
      AND py.reg_no IS NULL
");
$q->execute();
$unpaid = $q->fetch(PDO::FETCH_ASSOC)['count'];



// ── CURRENT-YEAR ADMITTED (hero card headline) ───────────────
$q = $conn->prepare("
    SELECT COALESCE(COUNT(DISTINCT app.code),0) AS count
    FROM tbl_admittedPRG adm
    INNER JOIN tbl_applicants app ON app.code = adm.Stu_code
    INNER JOIN tbl_acad_cycle cy  ON cy.acad_cycle_id = adm.acad_id
    WHERE cy.status = 1
      AND app.submitted != 10
");
$q->execute();
$currentYearAdmitted = $q->fetch(PDO::FETCH_ASSOC)['count'];

// ── PAST YEARS (single query — no N+1 loop) ──────────────────
$getAcadYears = $conn->prepare("
    SELECT cy.acad_cycle_id,
           cy.acad_year,
           COALESCE(COUNT(DISTINCT app.code),0) AS admitted_count
    FROM tbl_acad_cycle cy
    LEFT JOIN tbl_admittedPRG adm ON adm.acad_id = cy.acad_cycle_id
    LEFT JOIN tbl_applicants  app ON app.code = adm.Stu_code
          AND app.submitted != 10
    WHERE cy.status != 1
    GROUP BY cy.acad_cycle_id, cy.acad_year
    ORDER BY cy.acad_cycle_id DESC
");
$getAcadYears->execute();
$pastYears = $getAcadYears->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ============================================================
     DASHBOARD MAIN CONTENT
     ============================================================ -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title"><?php echo date("F d Y"); ?></h2>

            <div class="row">

                <!-- ── LEFT COLUMN: stat cards ──────────────────── -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-12"
                     style="display:flex;flex-direction:row;flex-wrap:wrap;padding:0;">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card card-statistic-2">


                            <!-- APPLICANT STATISTICS -->
                            <div class="card-stats">
                                <div class="card-stats-title">
                                    Applicants Statistics 
                                    <div class="dropdown d-inline">
                                        <a class="font-weight-600" href="#">
                                            <?php echo htmlspecialchars($ac['acad_year']); ?>
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-stats-items">
                                    
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo number_format($paid); ?></div>
                                        <div class="card-stats-item-label">Paid</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo number_format($unpaid); ?></div>
                                        <div class="card-stats-item-label">Unpaid</div>
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- card icon + footer count -->
                            <div class="card-icon shadow-primary bg-success">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Applicants</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo number_format($totalSubmitted); ?>
                                </div>
                            </div>

                        </div><!-- /.card -->
                    </div><!-- /.col-lg-6 -->

                </div><!-- /.col-lg-8 -->

                <!-- ── RIGHT COLUMN: students by year ───────────── -->
                <div class="col-md-4">
                    <div class="card card-hero">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h1><?php echo number_format($currentYearAdmitted); ?></h1>
                            <div class="card-description">
                                Admitted &mdash; <?php echo htmlspecialchars($ac['acad_year']); ?>
                            </div>
                        </div>

                        <div class="card-body" id="top-5-scroll">
                            <ul class="list-unstyled list-unstyled-border">
                                <?php foreach ($pastYears as $row): ?>
                                <li class="media">
                                    <img class="mr-3 rounded bg-success" width="40"
                                         src="../img/mod_icon.png" alt="">
                                    <div class="media-body">
                                        <div class="media-title">
                                            <?php echo htmlspecialchars($row['acad_year']); ?>
                                        </div>
                                        <div class="mt-1">
                                            <div class="budget-price">
                                                <div class="budget-price-label">
                                                    <?php echo number_format($row['admitted_count']); ?> students
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div><!-- /.card-hero -->
                </div><!-- /.col-md-4 -->

            </div><!-- /.row -->
        </div><!-- /.section-body -->
    </section>
</div><!-- /.main-content -->

<script src="../assets/modules/chart.min.js"></script>
<script>
    var jsonNames = <?php echo $jsonNames; ?>;
    var jsonCount = <?php echo $jsonCount; ?>;

    var ctx = document.getElementById("myChart2").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: jsonNames,
            datasets: [{
                label: 'Students',
                data: jsonCount,
                backgroundColor: '#6777ef',
                borderColor: '#6777ef',
                borderWidth: 2.5,
                pointBackgroundColor: '#ffffff',
                pointRadius: 4
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                yAxes: [{
                    gridLines: { drawBorder: false, color: '#f2f2f2' },
                    ticks: { beginAtZero: true, stepSize: 150 }
                }],
                xAxes: [{
                    ticks: { display: true },
                    gridLines: { display: false }
                }]
            }
        }
    });
</script>