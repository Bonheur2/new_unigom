<?php
// Approval list - same layout as Manage_application/applications.php.
// Rows come from approval_queue(): 'pending' shows requests waiting on the
// signed-in role at their current step; 'approved' / 'rejected' show finished
// requests. All of it limited to the user's own scope.
require_once(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

// Router entries - change here if your edu.php uses different names.
$ROUTE_LIST   = 'edu?mis=apprvw';
$ROUTE_REVIEW = 'edu?mis=approval_review';

$status = $_GET['status'] ?? 'pending';
if(!in_array($status, ['pending','approved','rejected'], true)){ $status = 'pending'; }

$rows = [];
$load_error = '';
if(can('can_review_applications')){
    try {
        $rows = approval_queue($conn, $status);
    } catch(PDOException $e){
        $load_error = $e->getMessage();
    }
}

$titles = [
    'pending'  => 'Waiting on me',
    'approved' => 'Approved',
    'rejected' => 'Rejected'
];
$status_badge = [
    'pending'  => 'badge-warning',
    'approved' => 'badge-success',
    'rejected' => 'badge-danger'
];
$scope_text = [
    'university' => 'across the whole university',
    'campus'     => 'on your campus',
    'faculty'    => 'in your faculty',
    'department' => 'in your department'
];
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Approvals</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="<?php echo $ROUTE_LIST; ?>">Approvals</a></div>
                    </div>
                </div>
                <div class="section-body">

                    <?php if(!can('can_review_applications')): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-lock"></i> Your role does not have permission to review applications.
                    </div>
                    <?php else: ?>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                <div class="card-header">
                                    <h4><?php echo $titles[$status]; ?></h4>
                                    <div class="card-header-action">
                                        <?php foreach($titles as $key => $label): ?>
                                        <a href="<?php echo $ROUTE_LIST.'&status='.$key; ?>"
                                           class="btn btn-sm <?php echo $key === $status ? 'btn-primary' : 'btn-light'; ?>">
                                            <?php echo $label; ?>
                                            <?php if($key === $status): ?>
                                            <span class="badge badge-transparent"><?php echo count($rows); ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-info-circle"></i>
                                        Showing applicants <b><?php echo $scope_text[scope_level()] ?? 'in your scope'; ?></b><?php
                                        echo $status === 'pending' ? ', at the steps your role is responsible for.' : '.'; ?>
                                    </p>

                                    <?php if($load_error !== ''): ?>
                                    <div class="alert alert-danger">Could not load the list: <?php echo htmlspecialchars($load_error); ?></div>
                                    <?php endif; ?>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="approvals">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Application Code</th>
                                                    <th>Applicant Name</th>
                                                    <th>Form</th>
                                                    <th>Campus</th>
                                                    <th>Programme Type</th>
                                                    <th>Department</th>
                                                    <th><?php echo $status === 'pending' ? 'Current Step' : ($status === 'approved' ? 'Reg. No' : 'Status'); ?></th>
                                                    <th><?php echo $status === 'pending' ? 'Date Submitted' : 'Date Completed'; ?></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; foreach($rows as $r): ?>
                                                <?php $href = $ROUTE_REVIEW.'&req='.(int) $r['request_id']; ?>
                                                <tr class="clickable-row" data-href="<?php echo $href; ?>">
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo htmlspecialchars($r['application_code']); ?></td>
                                                    <td><?php echo htmlspecialchars(trim($r['fname'].' '.$r['lname'])); ?></td>
                                                    <td><?php echo htmlspecialchars($r['form_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($r['camp_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($r['prg_type_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($r['dept_full_name'] ?? ''); ?></td>
                                                    <td>
                                                        <?php if($status === 'pending'): ?>
                                                            <span class="badge badge-primary"><?php echo (int) $r['current_step']; ?></span>
                                                            <?php echo htmlspecialchars($r['step_name'] ?? ''); ?>
                                                            <?php if(($r['step_type'] ?? '') === 'invoice'): ?>
                                                            <span class="badge badge-warning">invoice</span>
                                                            <?php endif; ?>
                                                        <?php elseif($status === 'approved'): ?>
                                                            <?php echo $r['reg_no'] ? '<b>'.htmlspecialchars($r['reg_no']).'</b>' : '<span class="text-muted">not issued</span>'; ?>
                                                        <?php else: ?>
                                                            <span class="badge <?php echo $status_badge[$r['status']] ?? 'badge-secondary'; ?>"><?php echo ucfirst($r['status']); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $when = $status === 'pending' ? $r['submitted_at'] : ($r['completed_at'] ?: $r['submitted_at']);
                                                            echo $when ? date('Y-m-d H:i', strtotime($when)) : '';
                                                        ?>
                                                    </td>
                                                    <td><a class="btn btn-primary btn-sm" href="<?php echo $href; ?>"><i class="fa fa-eye"></i>&nbsp;<?php echo $status === 'pending' ? 'Review' : 'View'; ?></a></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </section>
        </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){
        $('#approvals').DataTable({
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });

        $('#approvals tbody').on('click', '.clickable-row', function(e){
            if ($(e.target).is('input, button, a, label')) {
                return;
            }
            window.location.href = $(this).data('href');
        });
    });
</script>
