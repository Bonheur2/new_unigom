<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Foundaction Applications</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Foundaction Applications</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Foundaction Applications </h4>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                            ini_set('display_errors', 1);
                                            ini_set('display_startup_errors', 1);
                                            error_reporting(E_ALL);

                                            $query = "SELECT
                                                        tbl_admittedPRG.Stu_code as code,
                                                        tbl_applicants.fname,
                                                        tbl_applicants.lname,
                                                        tbl_applicants.createdAt,
                                                        tbl_acad_cycle.acad_year,
                                                        MAX(tbl_campus.camp_full_name) as camp_full_name,
                                                        MAX(tbl_program_type.prg_type_full_name) as prg_type_full_name,
                                                        MAX(tbl_faculty.fac_full_name) as fac_full_name,
                                                        MAX(tbl_department.dept_full_name) as dept_full_name,
                                                        MAX(tbl_specialization.splz_full_name) as splz_full_name,
                                                        MAX(tbl_program_mode.prg_mode_full_name) as prg_mode_full_name,
                                                        MAX(tbl_level.level_full_name) as level_full_name,
                                                        GROUP_CONCAT(DISTINCT CONCAT(tbl_department.dept_full_name, '||', tbl_specialization.splz_full_name) SEPARATOR ':::') as all_specializations,
                                                        COUNT(DISTINCT tbl_admittedPRG.Aprg_id) as app_count
                                                    FROM tbl_applicants
                                                    INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                                    INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                    INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                    INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                                                    INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                                                    INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                    INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                    INNER JOIN tbl_acad_cycle ON tbl_admittedPRG.acad_id = tbl_acad_cycle.acad_cycle_id
                                                    LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                    WHERE tbl_admittedPRG.sts = 6
                                                    GROUP BY tbl_admittedPRG.Stu_code, tbl_applicants.fname, tbl_applicants.lname, tbl_applicants.createdAt, tbl_acad_cycle.acad_year
                                                    ORDER BY tbl_acad_cycle.acad_year DESC, tbl_applicants.createdAt DESC";

                                            $sql = $conn->prepare($query);
                                            $sql->execute();

                                            $groupedApplicants = [];
                                            while ($apps = $sql->fetch()) {
                                                $yearKey = !empty($apps['acad_year']) ? $apps['acad_year'] : 'Unknown Academic Year';
                                                if (!isset($groupedApplicants[$yearKey])) {
                                                    $groupedApplicants[$yearKey] = [];
                                                }
                                                $groupedApplicants[$yearKey][] = $apps;
                                            }

                                            $yearKeys = array_keys($groupedApplicants);
                                        ?>

                                        <?php if (!empty($yearKeys)) { ?>
                                            <ul class="nav nav-tabs" id="acadYearTabs" role="tablist">
                                                <?php foreach ($yearKeys as $yearIndex => $yearLabel) {
                                                    $tabId = 'foundation-acad-year-' . preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($yearLabel));
                                                ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link <?php echo $yearIndex === 0 ? 'active' : ''; ?>"
                                                           id="<?php echo $tabId; ?>-tab"
                                                           data-toggle="tab"
                                                           href="#<?php echo $tabId; ?>"
                                                           role="tab"
                                                           aria-controls="<?php echo $tabId; ?>"
                                                           data-acad-year="<?php echo htmlspecialchars($yearLabel, ENT_QUOTES); ?>"
                                                           aria-selected="<?php echo $yearIndex === 0 ? 'true' : 'false'; ?>">
                                                            <?php echo htmlspecialchars($yearLabel); ?>
                                                            <span class="badge badge-primary ml-1"><?php echo count($groupedApplicants[$yearLabel]); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>

                                            <div class="tab-content pt-3" id="acadYearTabsContent">
                                                <?php foreach ($yearKeys as $yearIndex => $yearLabel) {
                                                    $tabId = 'foundation-acad-year-' . preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($yearLabel));
                                                    $tableId = 'foundation_applications_' . $yearIndex;
                                                    $serial = 1;
                                                ?>
                                                    <div class="tab-pane fade <?php echo $yearIndex === 0 ? 'show active' : ''; ?>"
                                                         id="<?php echo $tabId; ?>"
                                                         role="tabpanel"
                                                         aria-labelledby="<?php echo $tabId; ?>-tab">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-sm applications-table" id="<?php echo $tableId; ?>">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Tracking Number</th>
                                                                        <th>Applicant names</th>
                                                                        <th>Academic Year</th>
                                                                        <th>Campus</th>
                                                                        <th>Program</th>
                                                                        <th>School</th>
                                                                        <th>Department</th>
                                                                        <th>Specialization</th>
                                                                        <th>Level</th>
                                                                        <th>Date created</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($groupedApplicants[$yearLabel] as $apps) {
                                                                        $all_splz = explode(':::', $apps['all_specializations']);
                                                                        $specializations = [];
                                                                        foreach ($all_splz as $splz_item) {
                                                                            if (!empty($splz_item)) {
                                                                                $parts = explode('||', $splz_item);
                                                                                if (count($parts) == 2) {
                                                                                    $specializations[] = [
                                                                                        'dept_full_name' => $parts[0],
                                                                                        'splz_full_name' => $parts[1]
                                                                                    ];
                                                                                }
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <tr class="clickable-row" data-href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in=">
                                                                            <td><?php echo $serial++; ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['code']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['fname'] . ' ' . $apps['lname']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['acad_year']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['camp_full_name']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['prg_type_full_name']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['fac_full_name']); ?></td>
                                                                            <td><?php echo htmlspecialchars($apps['dept_full_name']); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                    if ($apps['app_count'] > 1 && count($specializations) > 1) {
                                                                                        echo '<div style="max-height: 80px; overflow-y: auto;">';
                                                                                        foreach ($specializations as $splz) {
                                                                                            echo '<div style="padding: 2px 0; border-bottom: 1px solid #eee;">';
                                                                                            echo '<small><strong>' . htmlspecialchars($splz['dept_full_name']) . ':</strong></small><br>';
                                                                                            echo '<span class="badge badge-info">' . htmlspecialchars($splz['splz_full_name']) . '</span>';
                                                                                            echo '</div>';
                                                                                        }
                                                                                        echo '</div>';
                                                                                        echo '<small class="text-muted">(' . $apps['app_count'] . ' applications)</small>';
                                                                                    } else {
                                                                                        echo htmlspecialchars($apps['splz_full_name']);
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo htmlspecialchars($apps['level_full_name']); ?></td>
                                                                            <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'])); ?></td>
                                                                            <td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>&in="><i class="fa fa-eye"></i></a></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="alert alert-info mb-0">No foundation applications found.</div>
                                        <?php } ?>

                                        <div class="card-footer text-right">
                                            <a href="/files/application/FoundationListExcel.php" class="btn btn-success btn-sm m-20">
                                                <i class="fa fa-download"></i>&nbsp;Excel (All Academic Years)
                                            </a>
                                            <a href="#" id="downloadSelectedYear" class="btn btn-primary btn-sm m-20">
                                                <i class="fa fa-download"></i>&nbsp;Excel (Selected Academic Year)
                                            </a>
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
        function updateSelectedYearDownloadLink(acadYear) {
            if (!acadYear) {
                $('#downloadSelectedYear')
                    .attr('href', '/files/application/FoundationListExcel.php')
                    .attr('title', 'Download Excel for selected academic year')
                    .removeClass('disabled');
                return;
            }

            $('#downloadSelectedYear')
                .attr('href', '/files/application/FoundationListExcel.php?acad_year=' + encodeURIComponent(acadYear))
                .attr('title', 'Download Excel for ' + acadYear)
                .removeClass('disabled');
        }

        $('.applications-table').each(function(){
            $(this).DataTable({
                "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                "iDisplayLength": 25
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

            var selectedYear = $(this).data('acad-year');
            updateSelectedYearDownloadLink(selectedYear);
        });

        var initialYear = $('#acadYearTabs .nav-link.active').data('acad-year');
        updateSelectedYearDownloadLink(initialYear);
        
        $('.clickable-row').on('click', function(){
            var link = $(this).data('href');
            window.location.href = link;
        })
    });
</script>