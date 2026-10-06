<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // initials for the avatar circle (first letter of the first two words)
    if(!function_exists('tv_initials')){
        function tv_initials($name){
            $words = preg_split('/\s+/', trim($name));
            $out = '';
            foreach(array_slice($words, 0, 2) as $w){
                $out .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            return $out;
        }
    }

    // load the whole structure once: campus > faculty > program type > departments
    $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                FROM tbl_campus
                                INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                WHERE tbl_campus.camp_active=1 AND tbl_faculty.status=1 AND tbl_program_type.status=1
                                ORDER BY tbl_campus.camp_full_name ASC");
    $sql_tabs->execute();
    $campuses = $sql_tabs->fetchAll();

    foreach($campuses as $cIndex => $campus){
        $sql_facs = $conn->prepare("SELECT DISTINCT tbl_faculty.fac_id, tbl_faculty.fac_full_name
                                    FROM tbl_faculty
                                    INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                    WHERE tbl_faculty.campus_id = :camp_id
                                    AND tbl_faculty.status=1 AND tbl_program_type.status=1
                                    ORDER BY tbl_faculty.fac_full_name ASC");
        $sql_facs->execute([':camp_id' => $campus['camp_id']]);
        $faculties = $sql_facs->fetchAll();

        $campTotal = 0;
        $campActive = 0;
        foreach($faculties as $fIndex => $fac){
            $sql_pts = $conn->prepare("SELECT DISTINCT tbl_program_type.prg_type_id, tbl_program_type.prg_type_full_name
                                       FROM tbl_program_type
                                       INNER JOIN tbl_department ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                       WHERE tbl_program_type.fac_id = :fac_id
                                       AND tbl_program_type.status=1
                                       ORDER BY tbl_program_type.prg_type_full_name ASC");
            $sql_pts->execute([':fac_id' => $fac['fac_id']]);
            $prg_types = $sql_pts->fetchAll();

            $facTotal = 0;
            $facActive = 0;
            foreach($prg_types as $pIndex => $pt){
                $sql = $conn->prepare("SELECT * FROM tbl_department WHERE prg_type = :prg_type ORDER BY status ASC");
                $sql->execute([':prg_type' => $pt['prg_type_id']]);
                $depts = $sql->fetchAll();
                $active = 0;
                foreach($depts as $d){ if($d['status'] == 1) $active++; }
                $prg_types[$pIndex]['depts'] = $depts;
                $prg_types[$pIndex]['active'] = $active;
                $facTotal += count($depts);
                $facActive += $active;
            }
            $faculties[$fIndex]['prg_types'] = $prg_types;
            $faculties[$fIndex]['total'] = $facTotal;
            $faculties[$fIndex]['active'] = $facActive;
            $campTotal += $facTotal;
            $campActive += $facActive;
        }
        $campuses[$cIndex]['faculties'] = $faculties;
        $campuses[$cIndex]['total'] = $campTotal;
        $campuses[$cIndex]['active'] = $campActive;
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Départements</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Département</a></div>
            </div>
        </div>

        <!-- Formulaire nouveau département -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau département</h4>
                <form id="save_dept" action="save_dept" method="POST">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Campus</label>
                            <select class="form-control select2" style="width:100%" id="campus_id">
                                <option value="0">Choisir un campus</option>
                                <?php
                                    $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                    $sql_camp->execute();
                                    while($camp = $sql_camp->fetch()):
                                ?>
                                <option value="<?php echo $camp['camp_id']; ?>"><?php echo htmlspecialchars($camp['camp_full_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Faculté <span id="spinner_fac"></span></label>
                            <select class="form-control select2" style="width:100%" id="fac_id">

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Type de programme <span id="spinner_pt"></span></label>
                            <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type">

                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Nom complet du département</label>
                            <input type="text" class="form-control" id="d_f_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nom abrégé du département</label>
                            <input type="text" class="form-control" id="d_s_name" placeholder="Nom abrégé">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(count($campuses) === 0): ?>
        <div class="tv-card tv-empty">
            Aucun département enregistré pour le moment.
            <?php if($canManage): ?>
            <div class="mt-3"><button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouveau département</button></div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="row">
            <!-- Structure card -->
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Structure</h4>
                    <div class="tv-tree" id="tv-tree">
                        <?php foreach($campuses as $campus): ?>
                        <div class="tv-node" data-target="campus-<?php echo $campus['camp_id']; ?>">
                            <i class="fas fa-chevron-right tv-chev"></i>
                            <i class="far fa-building"></i>
                            <span class="tv-name"><?php echo htmlspecialchars($campus['camp_full_name']); ?></span>
                            <span class="tv-count" data-count-campus="<?php echo $campus['camp_id']; ?>"><?php echo $campus['total']; ?></span>
                        </div>
                        <div class="tv-children">
                            <?php foreach($campus['faculties'] as $fac): ?>
                            <div class="tv-node" data-target="fac-<?php echo $fac['fac_id']; ?>">
                                <i class="fas fa-chevron-right tv-chev"></i>
                                <i class="far fa-folder"></i>
                                <span class="tv-name"><?php echo htmlspecialchars($fac['fac_full_name']); ?></span>
                                <span class="tv-count" data-count-fac="<?php echo $fac['fac_id']; ?>"><?php echo $fac['total']; ?></span>
                            </div>
                            <div class="tv-children">
                                <?php foreach($fac['prg_types'] as $pt): ?>
                                <div class="tv-node" data-target="pt-<?php echo $pt['prg_type_id']; ?>">
                                    <i class="far fa-folder-open"></i>
                                    <span class="tv-name"><?php echo htmlspecialchars($pt['prg_type_full_name']); ?></span>
                                    <span class="tv-count" data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo count($pt['depts']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Details card -->
            <div class="col-12 col-lg-8">
                <?php foreach($campuses as $campus): ?>
                <!-- campus pane -->
                <div class="tv-pane" id="pane-campus-<?php echo $campus['camp_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2><?php echo htmlspecialchars($campus['camp_full_name']); ?> <span class="tv-badge">Campus</span></h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>"><i class="fas fa-plus"></i> Ajouter un département</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-layer-group"></i></div>
                                <div><b><?php echo count($campus['faculties']); ?></b><span>Facultés</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-sitemap"></i></div>
                                <div><b data-count-campus="<?php echo $campus['camp_id']; ?>"><?php echo $campus['total']; ?></b><span>Départements</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b data-active-campus="<?php echo $campus['camp_id']; ?>"><?php echo $campus['active']; ?></b><span>Actifs</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Facultés</h4>
                            <span class="tv-pill-count"><?php echo count($campus['faculties']); ?></span>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table">
                                <thead>
                                <tr>
                                    <th>Faculté</th>
                                    <th>Types de programme</th>
                                    <th>Départements</th>
                                    <th class="tv-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($campus['faculties'] as $fac): ?>
                                <tr>
                                    <td><div class="tv-member"><span class="tv-avatar"><?php echo htmlspecialchars(tv_initials($fac['fac_full_name'])); ?></span><?php echo htmlspecialchars($fac['fac_full_name']); ?></div></td>
                                    <td><?php echo count($fac['prg_types']); ?></td>
                                    <td data-count-fac="<?php echo $fac['fac_id']; ?>"><?php echo $fac['total']; ?></td>
                                    <td class="tv-right"><button type="button" class="tv-link tv-open" data-target="fac-<?php echo $fac['fac_id']; ?>">Voir <i class="fas fa-arrow-right"></i></button></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php foreach($campus['faculties'] as $fac): ?>
                <!-- faculty pane -->
                <div class="tv-pane" id="pane-fac-<?php echo $fac['fac_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><?php echo htmlspecialchars($fac['fac_full_name']); ?><small><?php echo htmlspecialchars($campus['camp_full_name']); ?></small></span>
                                <span class="tv-badge">Faculté</span>
                            </h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>"><i class="fas fa-plus"></i> Ajouter un département</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-graduation-cap"></i></div>
                                <div><b><?php echo count($fac['prg_types']); ?></b><span>Types de programme</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-sitemap"></i></div>
                                <div><b data-count-fac="<?php echo $fac['fac_id']; ?>"><?php echo $fac['total']; ?></b><span>Départements</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b data-active-fac="<?php echo $fac['fac_id']; ?>"><?php echo $fac['active']; ?></b><span>Actifs</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Types de programme</h4>
                            <span class="tv-pill-count"><?php echo count($fac['prg_types']); ?></span>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table">
                                <thead>
                                <tr>
                                    <th>Type de programme</th>
                                    <th>Départements</th>
                                    <th>Actifs</th>
                                    <th class="tv-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($fac['prg_types'] as $pt): ?>
                                <tr>
                                    <td><div class="tv-member"><span class="tv-avatar"><?php echo htmlspecialchars(tv_initials($pt['prg_type_full_name'])); ?></span><?php echo htmlspecialchars($pt['prg_type_full_name']); ?></div></td>
                                    <td data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo count($pt['depts']); ?></td>
                                    <td data-active-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['active']; ?></td>
                                    <td class="tv-right"><button type="button" class="tv-link tv-open" data-target="pt-<?php echo $pt['prg_type_id']; ?>">Voir <i class="fas fa-arrow-right"></i></button></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php foreach($fac['prg_types'] as $pt): ?>
                <!-- program type pane -->
                <div class="tv-pane" id="pane-pt-<?php echo $pt['prg_type_id']; ?>" data-pt="<?php echo $pt['prg_type_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>" data-campus="<?php echo $campus['camp_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><?php echo htmlspecialchars($pt['prg_type_full_name']); ?><small><?php echo htmlspecialchars($fac['fac_full_name'].' · '.$campus['camp_full_name']); ?></small></span>
                                <span class="tv-badge">Type de programme</span>
                            </h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>" data-pt="<?php echo $pt['prg_type_id']; ?>"><i class="fas fa-plus"></i> Ajouter un département</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-sitemap"></i></div>
                                <div><b data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo count($pt['depts']); ?></b><span>Départements</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b data-active-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['active']; ?></b><span>Actifs</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon red"><i class="fas fa-ban"></i></div>
                                <div><b data-inactive-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo count($pt['depts']) - $pt['active']; ?></b><span>Inactifs</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Départements</h4>
                            <div class="tv-filter">
                                <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Tous</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actifs</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactifs</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table dept_table" data-prg-type-id="<?php echo $pt['prg_type_id']; ?>">
                                <thead>
                                <tr>
                                    <th>Département</th>
                                    <th>Nom abrégé</th>
                                    <th>Statut</th>
                                    <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($pt['depts'] as $dept): ?>
                                <tr data-row-id="<?php echo $dept['dept_id']; ?>" data-status="<?php echo $dept['status'] == 1 ? 1 : 0; ?>">
                                    <td><div class="tv-member"><span class="tv-avatar"><?php echo htmlspecialchars(tv_initials($dept['dept_full_name'])); ?></span><span class="col-full-name"><?php echo htmlspecialchars($dept['dept_full_name']); ?></span></div></td>
                                    <td><span class="tv-tag blue col-short-name"><?php echo htmlspecialchars($dept['dept_short_name']); ?></span></td>
                                    <td><span class="tv-tag <?php echo $dept['status'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $dept['status'] == 1 ? 'Actif' : 'Inactif'; ?></span></td>
                                    <?php if($canManage): ?>
                                    <td class="tv-right">
                                        <div class="tv-row-actions">
                                            <button type="button" data-id="<?php echo $dept['dept_id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                                <span id="spinner4_<?php echo $dept['dept_id']; ?>"></span><i class="fas fa-pen"></i>
                                            </button>
                                            <label class="custom-switch" title="Activer / désactiver">
                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $dept['dept_id']; ?>" <?php echo $dept['status']==1 ? 'checked' : ''; ?>>
                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $dept['dept_id']; ?>"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>
    <!--update modal-->
    <form action="update_form" method="POST" id="update_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modification de <span id="f_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="e_id" name="e_id">
                        <div class="form-group">
                            <label>Campus</label>
                            <select class="form-control select2" style="width:100%" id="e_campus_id">
                                <option value="">Choisir un campus</option>
                                <?php
                                    $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                    $sql_camp2->execute();
                                    while($camp2 = $sql_camp2->fetch()):
                                ?>
                                <option value="<?php echo $camp2['camp_id']; ?>"><?php echo htmlspecialchars($camp2['camp_full_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <span id="spinner_e_fac"></span>
                        </div>
                        <div class="form-group">
                            <label>Faculté</label>
                            <select class="form-control select2" style="width:100%" id="e_fac_id">
                                <option value="">Choisissez d'abord un campus</option>
                            </select>
                            <span id="spinner_e_pt"></span>
                        </div>
                        <div class="form-group">
                            <label>Type de programme</label>
                            <select class="form-control select2" style="width:100%" id="e_prg_type">
                                <option value="">Choisissez d'abord une faculté</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom complet du département</label>
                            <input type="text" class="form-control" id="e_d_f_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group">
                            <label>Nom abrégé du département</label>
                            <input type="text" class="form-control" id="e_d_s_name" placeholder="Nom abrégé">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Enregistrer les modifications</span></button>
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
var pendingFacId = null;
var pendingPrgType = null;
var pendingAddFacId = null;
var pendingAddPrgType = null;
var DEPT_STORE_KEY = 'dept_selected_node';
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}

function initials(name){
    return (name || '').trim().split(/\s+/).slice(0, 2).map(function(w){ return w.charAt(0).toUpperCase(); }).join('');
}

// expand a node and every ancestor above it
function openPath($node){
    $node.parents('.tv-children').each(function(){
        $(this).addClass('open').prev('.tv-node').addClass('open');
    });
    if($node.next('.tv-children').length){
        $node.addClass('open').next('.tv-children').addClass('open');
    }
}

// show the details pane for a tree node ("campus-ID", "fac-ID" or "pt-ID")
function selectNode(target){
    var $pane = $('#pane-' + target);
    if($pane.length === 0) return false;
    $('.tv-pane').removeClass('active');
    $pane.addClass('active');
    $('.tv-node').removeClass('active');
    openPath($('.tv-node[data-target="' + target + '"]').addClass('active'));
    try { localStorage.setItem(DEPT_STORE_KEY, target); } catch(e) {}
    // tables initialised while hidden need their column widths recalculated
    $pane.find('table.dept_table').each(function(){
        if($.fn.DataTable.isDataTable(this)) $(this).DataTable().columns.adjust();
    });
    return true;
}

// every department row of a program type, including rows on other pages
function allRowNodes(pt_id){
    var $table = $("table.dept_table[data-prg-type-id='" + pt_id + "']");
    if($table.length === 0) return $();
    return $($table.DataTable().rows().nodes());
}

// find a department row in any table, whatever page it is on
function findRow(dept_id){
    var found = null;
    $('table.dept_table').each(function(){
        var dt = $(this).DataTable();
        dt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(dept_id)) found = { dt: dt, row: this, $tr: $(this.node()) };
        });
    });
    return found;
}

// recompute counters for a program type and its faculty and campus
function refreshCounts(pt_id){
    var $pane = $('#pane-pt-' + pt_id);
    var fac_id = $pane.data('fac');
    var camp_id = $pane.data('campus');

    var $rows = allRowNodes(pt_id);
    var total = $rows.length;
    var active = $rows.filter('[data-status="1"]').length;
    $('[data-count-pt="' + pt_id + '"]').text(total);
    $('[data-active-pt="' + pt_id + '"]').text(active);
    $('[data-inactive-pt="' + pt_id + '"]').text(total - active);

    var sums = function(selector){
        var t = 0, a = 0;
        $(selector).each(function(){
            var $r = allRowNodes($(this).data('pt'));
            t += $r.length;
            a += $r.filter('[data-status="1"]').length;
        });
        return { total: t, active: a };
    };
    var f = sums('.tv-pane[data-fac="' + fac_id + '"]');
    $('[data-count-fac="' + fac_id + '"]').text(f.total);
    $('[data-active-fac="' + fac_id + '"]').text(f.active);
    var c = sums('.tv-pane[data-campus="' + camp_id + '"][data-pt]');
    $('[data-count-campus="' + camp_id + '"]').text(c.total);
    $('[data-active-campus="' + camp_id + '"]').text(c.active);
}

function applyStatusFilter($pane){
    $pane.find('table.dept_table').DataTable().draw(false);
}

$(document).ready(function(){
    // status chips filter through DataTables so paging stays correct
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        var $table = $(settings.nTable);
        if(!$table.hasClass('dept_table')) return true;
        var filter = $table.closest('.tv-pane').find('.tv-status-filter.active').data('filter');
        if(filter === undefined || filter === 'all') return true;
        var node = settings.aoData[dataIndex].nTr;
        return String($(node).attr('data-status')) === String(filter);
    });

    $('.dept_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Tout"]],
            "iDisplayLength": 5,
            "autoWidth": false,
            "order": [],
            "language": {
                "lengthMenu": "Afficher _MENU_ éléments",
                "search": "Rechercher :",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 élément",
                "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "emptyTable": "Aucune donnée disponible",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            }
        });
    });

    // tree: clicking a node shows its details; clicking the selected node again folds it
    $(document).on('click', '.tv-node', function(){
        var $children = $(this).next('.tv-children');
        if($(this).hasClass('active') && $children.length){
            $(this).toggleClass('open');
            $children.toggleClass('open');
            return;
        }
        selectNode($(this).data('target'));
    });
    $(document).on('click', '.tv-open', function(){
        selectNode($(this).data('target'));
    });

    // restore the last selected node, otherwise the first program type
    var saved = null;
    try { saved = localStorage.getItem(DEPT_STORE_KEY); } catch(e) {}
    if(!saved || !selectNode(saved)){
        var first = $('.tv-node[data-target^="pt-"]').first().data('target');
        if(first) selectNode(first);
    }

    // status filter chips
    $(document).on('click', '.tv-status-filter', function(){
        var $pane = $(this).closest('.tv-pane');
        $pane.find('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        applyStatusFilter($pane);
    });

    // open / close the new department form (only shown when the page is empty)
    $('#tv-new-btn').on('click', function(){
        $('#mycard-collapse').collapse('toggle');
    });

    // "Ajouter un département": open the form with campus, faculty and program type preselected
    $(document).on('click', '.tv-add-here', function(){
        pendingAddFacId = $(this).data('fac') || null;
        pendingAddPrgType = $(this).data('pt') || null;
        $('#mycard-collapse').collapse('show');
        $('#campus_id').val($(this).data('campus')).trigger('change');
        $('html, body').animate({ scrollTop: $('#mycard-collapse').offset().top - 90 }, 250);
        $('#d_f_name').focus();
    });

    // build a table row for a department and add it to its program type's table
    function addDepartmentRow(prg_type_id, row){
        var $table = $("table.dept_table[data-prg-type-id='"+prg_type_id+"']");
        if($table.length === 0){
            // no node exists yet for this program type (first item) - only way to show it is a reload
            try { localStorage.setItem(DEPT_STORE_KEY, 'pt-' + prg_type_id); } catch(e) {}
            location.reload();
            return;
        }
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<td class="tv-right"><div class="tv-row-actions">'
                + '<button type="button" data-id="'+row.dept_id+'" class="tv-icon-btn edit" title="Modifier">'
                + '<span id="spinner4_'+row.dept_id+'"></span><i class="fas fa-pen"></i></button>'
                + '<label class="custom-switch" title="Activer / désactiver">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.dept_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.dept_id+'"></span></label></div></td>';
        }
        var $tr = $('<tr data-row-id="'+row.dept_id+'" data-status="1">'
            + '<td><div class="tv-member"><span class="tv-avatar">'+escapeHtml(initials(row.dept_full_name))+'</span><span class="col-full-name">'+escapeHtml(row.dept_full_name)+'</span></div></td>'
            + '<td><span class="tv-tag blue col-short-name">'+escapeHtml(row.dept_short_name || '')+'</span></td>'
            + '<td><span class="tv-tag green tv-status-tag">Actif</span></td>'
            + actionsHtml
            + '</tr>');
        $table.DataTable().row.add($tr[0]).draw(false);
        refreshCounts(prg_type_id);
        applyStatusFilter($table.closest('.tv-pane'));
        selectNode('pt-' + prg_type_id);
    }

    //save department
    $("#save_dept").submit(function(e){
        e.preventDefault();

        var formData = {
            prg_type:$("#prg_type").val(),
            d_f_name:$("#d_f_name").val(),
            d_s_name:$("#d_s_name").val(),
            action:'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    $('#save_dept')[0].reset();
                    $('#campus_id').val('0').trigger('change');
                    pop_up_success(data.message);
                    addDepartmentRow(data.prg_type, data);
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // delete/toggle department
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData= {
                id: data_id,
                action:'delete'
                };
        swal({
        title: "Êtes-vous sûr ?",
        text: "Vous êtes sur le point de modifier le statut de ce département.",
        icon: "warning",
        buttons: ["Annuler", "Confirmer"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Department/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500){
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                    var isActive = $checkbox.prop('checked');
                    var $tr = $checkbox.closest('tr');
                    var $pane = $tr.closest('.tv-pane');
                    $tr.attr('data-status', isActive ? 1 : 0);
                    $tr.find('.tv-status-tag').removeClass('green red').addClass(isActive ? 'green' : 'red').text(isActive ? 'Actif' : 'Inactif');
                    $pane.find('table.dept_table').DataTable().row($tr[0]).invalidate('dom');
                    refreshCounts($pane.data('pt'));
                    applyStatusFilter($pane);
                    pop_up_success(data.message);
                }
            },
            error:function(error){
                $('#spinner3_'+data_id).fadeOut('fast');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
                pop_wrong("Une erreur s'est produite !");
            }
        });
        }
       else {
            // keep the switch in sync with the real status
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            swal("Opération annulée");
        }
    });
    });

    //pre-update View
    $(document).on('click','.edit',function () {
        var data_id = $(this).data('id');
        var getData= {
                id: data_id,
                action:'view'
                };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Department/controller.php",
            data: getData,
            dataType:"json",

            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_d_f_name").val(data.dept_full_name);
                $("#e_d_s_name").val(data.dept_short_name);
                $("#f_name").text(data.dept_full_name);

                pendingFacId = data.fac_id;
                pendingPrgType = data.prg_type;
                $("#e_campus_id").val(data.campus_id).trigger('change');
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update department
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            pr_id:$("#e_id").val(),
            prg_type:$("#e_prg_type").val(),
            d_f_name:$("#e_d_f_name").val(),
            d_s_name:$("#e_d_s_name").val(),
            action:'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                if(data.status==200){
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if(data.prg_type_changed){
                        // row must move to a different program type - simplest correct path
                        try { localStorage.setItem(DEPT_STORE_KEY, 'pt-' + data.prg_type); } catch(e) {}
                        location.reload();
                    } else {
                        var hit = findRow(data.dept_id);
                        if(hit){
                            hit.$tr.find('.col-full-name').text(data.dept_full_name);
                            hit.$tr.find('.col-short-name').text(data.dept_short_name || '');
                            hit.$tr.find('.tv-avatar').text(initials(data.dept_full_name));
                            hit.row.invalidate('dom').draw(false);
                        }
                    }
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // load faculty when campus changes (add form)
    $("#campus_id").change(function(){
         $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#fac_id").empty();
         $("#prg_type").empty();
        var camp_id=$("#campus_id").val();
        var formData={
            camp_id:camp_id,
            action:"get_faculty"
        }
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
            $('#spinner_fac').fadeOut('fast');
            $("#fac_id").append("<option></option>")
            $.each(data, function (index, value) {
                $("#fac_id").append("<option value='" + value.fac_id + "'>" + escapeHtml(value.fac_full_name)+"</option>");
            });
            if(pendingAddFacId){
                $("#fac_id").val(pendingAddFacId).trigger('change');
                pendingAddFacId = null;
            }
            },error: function(){
                $('#spinner_fac').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
         });
    })

    // load program type when faculty changes (add form)
    $("#fac_id").change(function(){
         $('#spinner_pt').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#prg_type").empty();
        var fac_id=$("#fac_id").val();
        var formData={
            fac_id:fac_id,
            action:"get_prg_type"
        }
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
            $('#spinner_pt').fadeOut('fast');
            $("#prg_type").append("<option></option>")
            $.each(data, function (index, value) {
                $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + escapeHtml(value.prg_type_full_name)+"</option>");
            });
            if(pendingAddPrgType){
                $("#prg_type").val(pendingAddPrgType).trigger('change');
                pendingAddPrgType = null;
            }
            },error: function(){
                $('#spinner_pt').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
         });
    })

    // load faculty when campus changes (update modal)
    $("#e_campus_id").change(function(){
        var camp_id = $(this).val();
        if(!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Chargement...</option>");
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_fac').fadeOut('fast');
                $("#e_fac_id").empty().append("<option value=''>Choisir une faculté</option>");
                $.each(data, function(index, value){
                    $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + escapeHtml(value.fac_full_name) + "</option>");
                });
                if(pendingFacId){
                    $("#e_fac_id").val(pendingFacId).trigger('change');
                    pendingFacId = null;
                }
            },
            error: function(){
                $('#spinner_e_fac').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // load program type when faculty changes (update modal)
    $("#e_fac_id").change(function(){
        var fac_id = $(this).val();
        if(!fac_id) return;
        $('#spinner_e_pt').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_prg_type").empty().append("<option value=''>Chargement...</option>");
        $.ajax({
            url: "../new_files/Department/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_prg_type" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_pt').fadeOut('fast');
                $("#e_prg_type").empty().append("<option value=''>Choisir un type de programme</option>");
                $.each(data, function(index, value){
                    $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + escapeHtml(value.prg_type_full_name) + "</option>");
                });
                if(pendingPrgType){
                    $("#e_prg_type").val(pendingPrgType).trigger('change');
                    pendingPrgType = null;
                }
            },
            error: function(){
                $('#spinner_e_pt').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

});

function pop_wrong(feedback) {
    iziToast.warning({
    title: 'Erreur',
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
