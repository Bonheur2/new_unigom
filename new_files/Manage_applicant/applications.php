<?php
    $status_meta = [
        'pending'  => ['amber', 'En attente', 'En attente'],
        'verified' => ['blue',  'Vérifiée',   'Vérifiées'],
        'accepted' => ['green', 'Acceptée',   'Acceptées'],
        'rejected' => ['red',   'Rejetée',    'Rejetées']
    ];

    $sql = $conn->prepare("SELECT tbl_applicants.applicant_id, tbl_applicants.application_code,
                                  tbl_applicants.fname, tbl_applicants.lname,
                                  tbl_applicants.status, tbl_applicants.created_at,
                                  tbl_campus.camp_full_name,
                                  tbl_program_type.prg_type_full_name,
                                  tbl_department.dept_full_name
                           FROM tbl_applicants
                           LEFT JOIN tbl_campus ON tbl_campus.camp_id = tbl_applicants.camp_id
                           LEFT JOIN tbl_program_type ON tbl_program_type.prg_type_id = tbl_applicants.prg_type_id
                           LEFT JOIN tbl_department ON tbl_department.dept_id = tbl_applicants.dept_choice_1
                           ORDER BY tbl_applicants.applicant_id DESC");
    $sql->execute();
    $apps = $sql->fetchAll(PDO::FETCH_ASSOC);

    $counts = ['pending' => 0, 'verified' => 0, 'accepted' => 0, 'rejected' => 0];
    foreach($apps as $a){ if(isset($counts[$a['status']])) $counts[$a['status']]++; }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Candidatures soumises</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="edu?mis=sbtdap">Candidatures</a></div>
            </div>
        </div>

        <!-- status overview, also used as filters -->
        <div class="sa-stats">
            <button type="button" class="sa-stat active" data-filter="all">
                <span class="sa-stat-n"><?php echo count($apps); ?></span>
                <span class="sa-stat-l">Toutes</span>
            </button>
            <?php foreach($status_meta as $key => $m): ?>
            <button type="button" class="sa-stat sa-<?php echo $m[0]; ?>" data-filter="<?php echo $key; ?>">
                <span class="sa-stat-n"><?php echo $counts[$key]; ?></span>
                <span class="sa-stat-l"><?php echo $m[2]; ?></span>
            </button>
            <?php endforeach; ?>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Candidatures soumises</h4>
            </div>
            <p class="tv-card-sub" style="margin:-8px 0 16px"><i class="fas fa-info-circle"></i> Cliquez sur une ligne pour ouvrir le dossier du candidat.</p>
            <div class="table-responsive">
                <table class="tv-table" id="applications">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Candidat</th>
                            <th>Campus</th>
                            <th>Type de programme</th>
                            <th>Département (1er choix)</th>
                            <th>Statut</th>
                            <th>Soumise le</th>
                            <th class="tv-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($apps as $a):
                            $href = 'edu?mis=review&app='.(int) $a['applicant_id'];
                            $sm = $status_meta[$a['status']] ?? ['blue', ucfirst($a['status'])];
                        ?>
                        <tr class="clickable-row" data-href="<?php echo $href; ?>" data-status="<?php echo htmlspecialchars($a['status']); ?>">
                            <td class="sa-code"><?php echo htmlspecialchars($a['application_code']); ?></td>
                            <td><b><?php echo htmlspecialchars(trim($a['fname'].' '.$a['lname'])); ?></b></td>
                            <td><?php echo htmlspecialchars($a['camp_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['prg_type_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['dept_full_name'] ?? ''); ?></td>
                            <td><span class="tv-tag <?php echo $sm[0]; ?>"><?php echo $sm[1]; ?></span></td>
                            <td style="white-space:nowrap"><?php echo $a['created_at'] ? date('d/m/Y H:i', strtotime($a['created_at'])) : ''; ?></td>
                            <td class="tv-right"><a class="tv-btn tv-btn-accent sa-open" href="<?php echo $href; ?>">Voir</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<style>
.tv-page .sa-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px;margin-bottom:24px}
.tv-page .sa-stat{background:#fff;border:1px solid var(--tv-border);border-top:3px solid var(--tv-accent);padding:14px 18px;text-align:left;cursor:pointer;transition:box-shadow .15s,border-color .15s}
.tv-page .sa-stat:hover{box-shadow:0 2px 10px rgba(32,58,114,.08)}
.tv-page .sa-stat.sa-amber{border-top-color:#e0a100}
.tv-page .sa-stat.sa-blue{border-top-color:var(--tv-blue)}
.tv-page .sa-stat.sa-green{border-top-color:var(--tv-green)}
.tv-page .sa-stat.sa-red{border-top-color:var(--tv-red)}
.tv-page .sa-stat.active{background:var(--tv-accent-soft);border-color:var(--tv-accent)}
.tv-page .sa-stat:focus{outline:none}
.tv-page .sa-stat-n{display:block;font-size:24px;font-weight:700;color:var(--tv-text);line-height:1.1}
.tv-page .sa-stat-l{display:block;font-size:13px;font-weight:600;color:var(--tv-muted);margin-top:2px}
.tv-page #applications thead th{font-weight:800;color:var(--tv-accent);padding:12px 10px}
.tv-page #applications td{font-size:14px;padding:12px 10px}
.tv-page #applications .sa-code{font-family:monospace;font-size:13px;white-space:nowrap}
.tv-page #applications tr.clickable-row{cursor:pointer}
.tv-page #applications tr.clickable-row:hover td{background:var(--tv-bg)}
.tv-page .sa-open{padding:6px 14px;font-size:13px;border-radius:0}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        // status cards filter the table through DataTables so paging stays correct
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
            if(settings.nTable.id !== 'applications') return true;
            var filter = $('.sa-stat.active').data('filter');
            if(!filter || filter === 'all') return true;
            return $(settings.aoData[dataIndex].nTr).attr('data-status') === filter;
        });

        var dt = $('#applications').DataTable({
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
                "emptyTable": "Aucune candidature soumise",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            }
        });

        $('.sa-stat').on('click', function(){
            $('.sa-stat').removeClass('active');
            $(this).addClass('active');
            dt.draw();
        });

        $('#applications tbody').on('click', '.clickable-row', function(e){
            if ($(e.target).closest('input, button, a, label').length) {
                return;
            }
            window.location.href = $(this).data('href');
        });
    });
</script>
