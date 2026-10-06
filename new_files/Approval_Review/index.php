<?php
// Approval list. Rows come from approval_queue(): 'pending' shows requests
// waiting on the signed-in role at their current step; 'approved' / 'rejected'
// show finished requests. All of it limited to the user's own scope.
require_once(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

// Router entries - change here if your edu.php uses different names.
$ROUTE_LIST   = 'edu?mis=apprvw';
$ROUTE_REVIEW = 'edu?mis=approval_review';

$status = $_GET['status'] ?? 'pending';
if(!in_array($status, ['pending','approved','rejected'], true)){ $status = 'pending'; }

$titles = [
    'pending'  => 'En attente de mon action',
    'approved' => 'Approuvées',
    'rejected' => 'Rejetées'
];
$tabIcons = ['pending' => 'far fa-hourglass', 'approved' => 'fas fa-check', 'rejected' => 'fas fa-times'];

// every tab shows its own count, so all three lists are loaded
$lists = ['pending' => [], 'approved' => [], 'rejected' => []];
$load_error = '';
if(can('can_review_applications')){
    try {
        foreach(array_keys($lists) as $k){ $lists[$k] = approval_queue($conn, $k); }
    } catch(PDOException $e){
        $load_error = $e->getMessage();
    }
}
$rows = $lists[$status];

$scope_text = [
    'university' => "dans toute l'université",
    'campus'     => 'de votre campus',
    'faculty'    => 'de votre faculté',
    'department' => 'de votre département'
];
$fmt = function($d){ return $d ? date('d/m/Y H:i', strtotime($d)) : ''; };
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Revue des candidatures</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="<?php echo $ROUTE_LIST; ?>">Revue des candidatures</a></div>
            </div>
        </div>

        <?php if(!can('can_review_applications')): ?>
        <div class="alert alert-warning">
            <i class="fas fa-lock"></i> Votre rôle n'a pas le droit de réviser les candidatures.
        </div>
        <?php else: ?>

        <!-- status tabs -->
        <div class="ar-tabs" role="tablist">
            <?php foreach($titles as $key => $label): ?>
            <a role="tab" href="<?php echo $ROUTE_LIST.'&status='.$key; ?>" class="ar-tab <?php echo $key === $status ? 'active' : ''; ?>" aria-selected="<?php echo $key === $status ? 'true' : 'false'; ?>">
                <i class="<?php echo $tabIcons[$key]; ?>"></i> <?php echo $label; ?>
                <span class="ar-count <?php echo $key; ?>"><?php echo count($lists[$key]); ?></span>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title"><?php echo $titles[$status]; ?></h4>
            </div>
            <p class="tv-card-sub" style="margin:-8px 0 16px">
                <i class="fas fa-info-circle"></i>
                Candidats <b><?php echo $scope_text[scope_level()] ?? 'de votre périmètre'; ?></b><?php
                echo $status === 'pending' ? ', aux étapes dont votre rôle est responsable. Cliquez sur une ligne pour l\'examiner.' : '.'; ?>
            </p>

            <?php if($load_error !== ''): ?>
            <div class="alert alert-danger">Impossible de charger la liste : <?php echo htmlspecialchars($load_error); ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="tv-table" id="approvals">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Candidat</th>
                            <th>Formulaire</th>
                            <th>Campus</th>
                            <th>Type de programme</th>
                            <th>Département</th>
                            <th><?php echo $status === 'pending' ? 'Étape actuelle' : ($status === 'approved' ? 'Matricule' : 'Statut'); ?></th>
                            <th><?php echo $status === 'pending' ? 'Soumise le' : 'Terminée le'; ?></th>
                            <th class="tv-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $r): ?>
                        <?php $href = $ROUTE_REVIEW.'&req='.(int) $r['request_id']; ?>
                        <tr class="clickable-row" data-href="<?php echo $href; ?>">
                            <td class="ar-code"><?php echo htmlspecialchars($r['application_code']); ?></td>
                            <td><b><?php echo htmlspecialchars(trim($r['fname'].' '.$r['lname'])); ?></b></td>
                            <td class="ar-form"><?php echo htmlspecialchars($r['form_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($r['camp_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($r['prg_type_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($r['dept_full_name'] ?? ''); ?></td>
                            <td>
                                <?php if($status === 'pending'): ?>
                                    <span class="ar-step"><?php echo (int) $r['current_step']; ?></span>
                                    <?php echo htmlspecialchars($r['step_name'] ?? ''); ?>
                                    <?php if(($r['step_type'] ?? '') === 'invoice'): ?>
                                    <span class="tv-tag amber">Facturation</span>
                                    <?php endif; ?>
                                <?php elseif($status === 'approved'): ?>
                                    <?php echo $r['reg_no'] ? '<b>'.htmlspecialchars($r['reg_no']).'</b>' : '<span class="text-muted">non attribué</span>'; ?>
                                <?php else: ?>
                                    <span class="tv-tag red">Rejetée</span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap">
                                <?php echo $fmt($status === 'pending' ? $r['submitted_at'] : ($r['completed_at'] ?: $r['submitted_at'])); ?>
                            </td>
                            <td class="tv-right"><a class="tv-btn tv-btn-accent ar-open" href="<?php echo $href; ?>"><?php echo $status === 'pending' ? 'Examiner' : 'Voir'; ?></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>

<style>
/* underlined status tabs (same look as the approval setup page) */
.tv-page .ar-tabs{display:flex;flex-wrap:wrap;gap:6px;border-bottom:1px solid var(--tv-border);margin-bottom:24px}
.tv-page .ar-tab{display:inline-flex;align-items:center;gap:8px;border-bottom:3px solid transparent;margin-bottom:-1px;padding:12px 18px;font-size:15px;font-weight:600;color:var(--tv-muted);white-space:nowrap;text-decoration:none !important}
.tv-page .ar-tab:hover{color:var(--tv-accent)}
.tv-page .ar-tab.active{color:var(--tv-accent);border-bottom-color:var(--tv-accent)}
.tv-page .ar-count{font-size:12px;font-weight:700;border-radius:10px;padding:1px 8px;background:var(--tv-bg);color:var(--tv-muted)}
.tv-page .ar-tab.active .ar-count.pending{background:#fff4d6;color:#8a5a00}
.tv-page .ar-tab.active .ar-count.approved{background:var(--tv-green-soft);color:var(--tv-green)}
.tv-page .ar-tab.active .ar-count.rejected{background:var(--tv-red-soft);color:var(--tv-red)}
/* table */
.tv-page #approvals thead th{font-weight:800;color:var(--tv-accent)}
.tv-page #approvals td{font-size:14px;padding:12px 10px}
.tv-page #approvals th{padding:12px 10px}
.tv-page #approvals .ar-code{font-family:monospace;font-size:13px;white-space:nowrap}
.tv-page #approvals .ar-form{max-width:220px;font-size:13px}
.tv-page #approvals tr.clickable-row{cursor:pointer}
.tv-page #approvals tr.clickable-row:hover td{background:var(--tv-bg)}
.tv-page .ar-step{display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:var(--tv-accent);color:#fff;font-size:12px;font-weight:700;margin-right:4px}
.tv-page .ar-open{padding:6px 14px;font-size:13px;border-radius:0}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){
        $('#approvals').DataTable({
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tout"]],
            "iDisplayLength": 25,
            "autoWidth": false,
            "order": [],
            "language": {
                "lengthMenu": "Afficher _MENU_ éléments",
                "search": "Rechercher :",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 élément",
                "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "emptyTable": "Aucune demande dans cette liste",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            }
        });

        $('#approvals tbody').on('click', '.clickable-row', function(e){
            if ($(e.target).is('input, button, a, label')) {
                return;
            }
            window.location.href = $(this).data('href');
        });
    });
</script>
