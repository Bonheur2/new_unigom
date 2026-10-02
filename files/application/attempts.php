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
        <h4>Pending Applications</h4>
    </div>
    <div class="card-body">
        <?php
            $sql=$conn->prepare("SELECT tbl_applicants.code, tbl_applicants.fname, tbl_applicants.lname, 
            tbl_applicants.phone, tbl_applicants.email, tbl_applicants.createdAt, tbl_acad_cycle.acad_year
            FROM tbl_admittedPRG
            INNER JOIN tbl_applicants ON tbl_applicants.code = tbl_admittedPRG.Stu_code
            INNER JOIN tbl_acad_cycle ON tbl_acad_cycle.acad_cycle_id = tbl_admittedPRG.acad_id
            WHERE tbl_applicants.submitted != 10
            ORDER BY tbl_acad_cycle.acad_year DESC");
            $sql->execute();
            $rows = $sql->fetchAll();

            // Group rows by academic year
            $grouped = [];
            foreach ($rows as $apps) {
                $grouped[$apps['acad_year']][] = $apps;
            }
            $years = array_keys($grouped);
        ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="acadTabs" role="tablist">
            <?php foreach ($years as $idx => $year): ?>
                <li class="nav-item">
    <a class="nav-link <?php echo $idx === 0 ? 'active' : ''; ?>"
       id="tab-<?php echo $idx; ?>"
       data-toggle="tab"
       href="#pane-<?php echo $idx; ?>"
       role="tab">
        <?php echo htmlspecialchars($year); ?>
        <span class="badge badge-primary ml-1"><?php echo count($grouped[$year]); ?></span>
    </a>
</li>
            <?php endforeach; ?>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-3" id="acadTabsContent">
            <?php foreach ($years as $idx => $year): ?>
                <div class="tab-pane fade <?php echo $idx === 0 ? 'show active' : ''; ?>"
                     id="pane-<?php echo $idx; ?>"
                     role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm applications-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tracking Number</th>
                                    <th>Applicant Names</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($grouped[$year] as $apps): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $apps['code']; ?></td>
                                        <td><?php echo $apps['fname']." ".$apps['lname']; ?></td>
                                        <td><?php echo $apps['phone']; ?></td>
                                        <td><?php echo $apps['email']; ?></td>
                                        <td><?php echo date('Y-m-d H:i:s', strtotime($apps['createdAt'])); ?></td>
                                        <!--<td><a class="btn btn-primary btn-sm" href="edu?mis=notify&app=<?php echo $apps['code']; ?>">Notify</a></td>-->
                                        <td><a class="btn btn-primary btn-sm" href="edu?mis=rev_app&app=<?php echo $apps['code']; ?>"><i class="fa-solid fa-eye"></i> View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
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
    $('.applications-table').each(function(){
        $(this).DataTable({
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });
    });
});

</script>