<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // initials for the avatar circle (first letter of the first two words)
    function pt_initials($name){
        $words = preg_split('/\s+/', trim($name));
        $out = '';
        foreach(array_slice($words, 0, 2) as $w){
            $out .= mb_strtoupper(mb_substr($w, 0, 1));
        }
        return $out;
    }

    // load the whole structure once: campus > faculty > program types
    $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                FROM tbl_campus
                                INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                WHERE tbl_campus.camp_active=1 AND tbl_faculty.status=1
                                ORDER BY tbl_campus.camp_full_name ASC");
    $sql_tabs->execute();
    $campuses = $sql_tabs->fetchAll();

    foreach($campuses as $cIndex => $campus){
        $sql_facs = $conn->prepare("SELECT DISTINCT tbl_faculty.fac_id, tbl_faculty.fac_full_name
                                    FROM tbl_faculty
                                    INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                    WHERE tbl_faculty.campus_id = :camp_id
                                    AND tbl_faculty.status=1
                                    ORDER BY tbl_faculty.fac_full_name ASC");
        $sql_facs->execute([':camp_id' => $campus['camp_id']]);
        $faculties = $sql_facs->fetchAll();

        $campTotal = 0;
        $campActive = 0;
        foreach($faculties as $fIndex => $fac){
            $sql = $conn->prepare("SELECT * FROM tbl_program_type WHERE fac_id = :fac_id ORDER BY status ASC");
            $sql->execute([':fac_id' => $fac['fac_id']]);
            $types = $sql->fetchAll();
            $active = 0;
            foreach($types as $t){ if($t['status'] == 1) $active++; }
            $faculties[$fIndex]['types'] = $types;
            $faculties[$fIndex]['active'] = $active;
            $campTotal += count($types);
            $campActive += $active;
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
            <h3>Types de programme</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Type de programme</a></div>
            </div>
        </div>


        <!-- Formulaire nouveau type de programme -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau type de programme</h4>
                <form id="save_prg_type" action="save_prg_type" method="POST">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Campus</label>
                            <select class="form-control select2" style="width:100%" id="campus_id">
                                <option value="0">Choisir un campus</option>
                                <?php
                                            $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                            $sql_camp->execute();
                                            while($campus = $sql_camp->fetch()):
                                        ?>
                                <option value="<?php echo $campus['camp_id']; ?>">
                                    <?php echo htmlspecialchars($campus['camp_full_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Faculté <span id="spinner_fac"></span></label>
                            <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id">

                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Nom complet du type de programme</label>
                            <input type="text" class="form-control" id="pt_f_name" placeholder="ex. Licence"
                                required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Nom abrégé</label>
                            <input type="text" class="form-control" id="pt_s_name" placeholder="ex. L">
                        </div>
                        <div class="form-group col-md-1">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span
                                    id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(count($campuses) === 0): ?>
        <div class="tv-card tv-empty">Aucun type de programme enregistré pour le moment. Utilisez « Nouveau type de programme » pour ajouter le premier.
        </div>
        <?php else: ?>
        <div class="row">
            <!-- Structure card -->
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Structure</h4>
                    <div class="tv-tree" id="tv-tree">
                        <?php foreach($campuses as $campus): ?>
                        <div class="tv-tree-group" data-campus="<?php echo $campus['camp_id']; ?>">
                            <div class="tv-node tv-campus" data-target="campus-<?php echo $campus['camp_id']; ?>"
                                data-search="<?php echo htmlspecialchars(mb_strtolower($campus['camp_full_name'])); ?>">
                                <i class="fas fa-chevron-right tv-chev"></i>
                                <i class="far fa-building"></i>
                                <span class="tv-name"><?php echo htmlspecialchars($campus['camp_full_name']); ?></span>
                                <span class="tv-count"
                                    data-count-campus="<?php echo $campus['camp_id']; ?>"><?php echo $campus['total']; ?></span>
                            </div>
                            <div class="tv-children">
                                <?php foreach($campus['faculties'] as $fac): ?>
                                <div class="tv-node tv-fac" data-target="fac-<?php echo $fac['fac_id']; ?>"
                                    data-search="<?php echo htmlspecialchars(mb_strtolower($fac['fac_full_name'])); ?>">
                                    <i class="far fa-folder"></i>
                                    <span class="tv-name"><?php echo htmlspecialchars($fac['fac_full_name']); ?></span>
                                    <span class="tv-count"
                                        data-count-fac="<?php echo $fac['fac_id']; ?>"><?php echo count($fac['types']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
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
                            <h2><?php echo htmlspecialchars($campus['camp_full_name']); ?> <span
                                    class="tv-badge">Campus</span></h2>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-layer-group"></i></div>
                                <div><b><?php echo count($campus['faculties']); ?></b><span>Facultés</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-graduation-cap"></i></div>
                                <div><b
                                        data-stat-campus-total="<?php echo $campus['camp_id']; ?>"><?php echo $campus['total']; ?></b><span>Types de programme</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b
                                        data-stat-campus-active="<?php echo $campus['camp_id']; ?>"><?php echo $campus['active']; ?></b><span>Actifs</span>
                                </div>
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
                                        <th>Actifs</th>
                                        <th class="tv-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($campus['faculties'] as $fac): ?>
                                    <tr>
                                        <td>
                                            <div class="tv-member"><span
                                                    class="tv-avatar"><?php echo htmlspecialchars(pt_initials($fac['fac_full_name'])); ?></span><?php echo htmlspecialchars($fac['fac_full_name']); ?>
                                            </div>
                                        </td>
                                        <td data-count-fac="<?php echo $fac['fac_id']; ?>">
                                            <?php echo count($fac['types']); ?></td>
                                        <td data-active-fac="<?php echo $fac['fac_id']; ?>">
                                            <?php echo $fac['active']; ?></td>
                                        <td class="tv-right"><button type="button" class="tv-link tv-open"
                                                data-target="fac-<?php echo $fac['fac_id']; ?>">Voir <i
                                                    class="fas fa-arrow-right"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php foreach($campus['faculties'] as $fac): ?>
                <!-- faculty pane -->
                <div class="tv-pane" id="pane-fac-<?php echo $fac['fac_id']; ?>"
                    data-fac-id="<?php echo $fac['fac_id']; ?>" data-campus="<?php echo $campus['camp_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><?php echo htmlspecialchars($fac['fac_full_name']); ?><small><?php echo htmlspecialchars($campus['camp_full_name']); ?></small></span>
                                <span class="tv-badge">Faculté</span>
                            </h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here"
                                    data-campus="<?php echo $campus['camp_id']; ?>"
                                    data-fac="<?php echo $fac['fac_id']; ?>"><i class="fas fa-plus"></i> Ajouter un type de programme</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-graduation-cap"></i></div>
                                <div><b
                                        data-stat-total="<?php echo $fac['fac_id']; ?>"><?php echo count($fac['types']); ?></b><span>Types de programme</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b
                                        data-stat-active="<?php echo $fac['fac_id']; ?>"><?php echo $fac['active']; ?></b><span>Actifs</span>
                                </div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon red"><i class="fas fa-ban"></i></div>
                                <div><b
                                        data-stat-inactive="<?php echo $fac['fac_id']; ?>"><?php echo count($fac['types']) - $fac['active']; ?></b><span>Inactifs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Types de programme</h4>
                            <div class="tv-filter">
                                <button type="button" class="tv-chip tv-status-filter active"
                                    data-filter="all">Tous</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actifs</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactifs</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table prg_type_table" data-fac-id="<?php echo $fac['fac_id']; ?>">
                                <thead>
                                    <tr>
                                        <th>Type de programme</th>
                                        <th>Nom abrégé</th>
                                        <th>Statut</th>
                                        <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($fac['types'] as $pt): ?>
                                    <tr data-row-id="<?php echo $pt['prg_type_id']; ?>"
                                        data-status="<?php echo $pt['status'] == 1 ? 1 : 0; ?>">
                                        <td>
                                            <div class="tv-member"><span
                                                    class="tv-avatar"><?php echo htmlspecialchars(pt_initials($pt['prg_type_full_name'])); ?></span><span
                                                    class="col-full-name"><?php echo htmlspecialchars($pt['prg_type_full_name']); ?></span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="tv-tag blue col-short-name"><?php echo htmlspecialchars($pt['prg_type_short_name']); ?></span>
                                        </td>
                                        <td><span
                                                class="tv-tag <?php echo $pt['status'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $pt['status'] == 1 ? 'Actif' : 'Inactif'; ?></span>
                                        </td>
                                        <?php if($canManage): ?>
                                        <td class="tv-right">
                                            <div class="tv-row-actions">
                                                <button type="button" data-id="<?php echo $pt['prg_type_id']; ?>"
                                                    class="tv-icon-btn edit" title="Modifier">
                                                    <span id="spinner4_<?php echo $pt['prg_type_id']; ?>"></span><i
                                                        class="fas fa-pen"></i>
                                                </button>
                                                <label class="custom-switch" title="Activer / désactiver">
                                                    <input type="checkbox" name="custom-switch-checkbox"
                                                        class="custom-switch-input del"
                                                        data-id="<?php echo $pt['prg_type_id']; ?>"
                                                        <?php echo $pt['status']==1 ? 'checked' : ''; ?>>
                                                    <span class="custom-switch-indicator"></span><span
                                                        id="spinner3_<?php echo $pt['prg_type_id']; ?>"></span>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="e_id" name="e_id">
                        <div class="form-group">
                            <label>Campus <span id="spinner_e_fac"></span></label>
                            <select class="form-control select2" style="width:100%" id="e_campus_id">
                                <option value="">Choisir un campus</option>
                                <?php
                                            $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                            $sql_camp2->execute();
                                            while($camp2 = $sql_camp2->fetch()):
                                        ?>
                                <option value="<?php echo $camp2['camp_id']; ?>">
                                    <?php echo htmlspecialchars($camp2['camp_full_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Faculté</label>
                            <select class="form-control select2" style="width:100%" id="e_fac_id">
                                <option value="">Choisissez d'abord un campus</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom complet du type de programme</label>
                            <input type="text" class="form-control" id="e_pt_f_name"
                                placeholder="ex. Licence" required>
                        </div>
                        <div class="form-group">
                            <label>Nom abrégé</label>
                            <input type="text" class="form-control" id="e_pt_s_name" placeholder="ex. L">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span
                                id="indicator2">Enregistrer les modifications</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal-->
</div>

<!--javascritv-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
var pendingFacId = null;
var pendingAddFacId = null;
var PT_STORE_KEY = 'pt_selected_node';

function escapeHtml(s) {
    return $('<div>').text(s == null ? '' : s).html();
}

function initials(name) {
    return (name || '').trim().split(/\s+/).slice(0, 2).map(function(w) {
        return w.charAt(0).toUpperCase();
    }).join('');
}

// show the details pane for a tree node ("campus-ID" or "fac-ID")
function selectNode(target) {
    var $pane = $('#pane-' + target);
    if ($pane.length === 0) return false;
    $('.tv-pane').removeClass('active');
    $pane.addClass('active');
    $('.tv-node').removeClass('active');
    var $node = $('.tv-node[data-target="' + target + '"]').addClass('active');
    // make sure the parent campus is expanded
    var $group = $node.closest('.tv-tree-group');
    $group.find('.tv-campus').addClass('open');
    $group.find('.tv-children').addClass('open');
    try {
        localStorage.setItem(PT_STORE_KEY, target);
    } catch (e) {}
    // tables initialised while hidden need their column widths recalculated
    $pane.find('table.prg_type_table').each(function() {
        if ($.fn.DataTable.isDataTable(this)) $(this).DataTable().columns.adjust();
    });
    return true;
}

// every row of a faculty's table, including rows on other pages
function allRowNodes(fac_id) {
    var $table = $("table.prg_type_table[data-fac-id='" + fac_id + "']");
    if ($table.length === 0) return $();
    return $($table.DataTable().rows().nodes());
}

// find a program type row in any table, whatever page it is on
function findRow(prg_type_id) {
    var found = null;
    $('table.prg_type_table').each(function() {
        var dt = $(this).DataTable();
        dt.rows().every(function() {
            if (String($(this.node()).attr('data-row-id')) === String(prg_type_id)) found = {
                dt: dt,
                row: this,
                $tr: $(this.node())
            };
        });
    });
    return found;
}

// recompute counters for one faculty after a status change or a new row
function refreshCounts(fac_id) {
    var $rows = allRowNodes(fac_id);
    var total = $rows.length;
    var active = $rows.filter('[data-status="1"]').length;
    $('[data-stat-total="' + fac_id + '"], [data-count-fac="' + fac_id + '"]').text(total);
    $('[data-stat-active="' + fac_id + '"], [data-active-fac="' + fac_id + '"]').text(active);
    $('[data-stat-inactive="' + fac_id + '"]').text(total - active);

    var camp_id = $('#pane-fac-' + fac_id).data('campus');
    var campTotal = 0,
        campActive = 0;
    $('.tv-pane[data-campus="' + camp_id + '"]').each(function() {
        var $r = allRowNodes($(this).data('fac-id'));
        campTotal += $r.length;
        campActive += $r.filter('[data-status="1"]').length;
    });
    $('[data-count-campus="' + camp_id + '"], [data-stat-campus-total="' + camp_id + '"]').text(campTotal);
    $('[data-stat-campus-active="' + camp_id + '"]').text(campActive);
}

function applyStatusFilter($pane) {
    $pane.find('table.prg_type_table').DataTable().draw(false);
}

$(document).ready(function() {
    // status chips filter through DataTables so paging stays correct
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var $table = $(settings.nTable);
        if (!$table.hasClass('prg_type_table')) return true;
        var filter = $table.closest('.tv-pane').find('.tv-status-filter.active').data('filter');
        if (filter === undefined || filter === 'all') return true;
        var node = settings.aoData[dataIndex].nTr;
        return String($(node).attr('data-status')) === String(filter);
    });

    $('.prg_type_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Tout"]
            ],
            "iDisplayLength": 5,
            "autoWidth": false,
            "language": {
                "lengthMenu": "Afficher _MENU_ éléments",
                "search": "Rechercher :",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 élément",
                "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "emptyTable": "Aucune donnée disponible",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            },
            "order": []
        });
    });

    // tree: campus click expands and shows campus details, faculty click shows faculty details
    $(document).on('click', '.tv-campus', function() {
        var $children = $(this).next('.tv-children');
        if ($(this).hasClass('active')) {
            $(this).toggleClass('open');
            $children.toggleClass('open');
            return;
        }
        selectNode($(this).data('target'));
    });
    $(document).on('click', '.tv-fac, .tv-open', function() {
        selectNode($(this).data('target'));
    });

    // restore the last selected node, otherwise the first faculty
    var saved = null;
    try {
        saved = localStorage.getItem(PT_STORE_KEY);
    } catch (e) {}
    if (!saved || !selectNode(saved)) {
        var first = $('.tv-fac').first().data('target');
        if (first) selectNode(first);
    }

    // status filter chips
    $(document).on('click', '.tv-status-filter', function() {
        var $pane = $(this).closest('.tv-pane');
        $pane.find('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        applyStatusFilter($pane);
    });

    // open / close the new program type form
    $('#tv-new-btn').on('click', function() {
        $('#mycard-collapse').collapse('toggle');
    });

    // "Add program type" from a faculty: open the form with campus and faculty preselected
    $(document).on('click', '.tv-add-here', function() {
        pendingAddFacId = $(this).data('fac');
        $('#mycard-collapse').collapse('show');
        $('#campus_id').val($(this).data('campus')).trigger('change');
        $('html, body').animate({
            scrollTop: $('#mycard-collapse').offset().top - 90
        }, 250);
        $('#pt_f_name').focus();
    });

    // build a table row for a program type and add it to its faculty's table
    function addProgramTypeRow(fac_id, row) {
        var $table = $("table.prg_type_table[data-fac-id='" + fac_id + "']");
        if ($table.length === 0) {
            // no node exists yet for this faculty (first item) - only way to show it is a reload
            try {
                localStorage.setItem(PT_STORE_KEY, 'fac-' + fac_id);
            } catch (e) {}
            location.reload();
            return;
        }
        var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
        var actionsHtml = '';
        if (canManage) {
            actionsHtml = '<td class="tv-right"><div class="tv-row-actions">' +
                '<button type="button" data-id="' + row.prg_type_id +
                '" class="tv-icon-btn edit" title="Modifier">' +
                '<span id="spinner4_' + row.prg_type_id + '"></span><i class="fas fa-pen"></i></button>' +
                '<label class="custom-switch" title="Activer / désactiver">' +
                '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="' +
                row.prg_type_id + '" checked>' +
                '<span class="custom-switch-indicator"></span><span id="spinner3_' + row.prg_type_id +
                '"></span></label></div></td>';
        }
        var $tr = $('<tr data-row-id="' + row.prg_type_id + '" data-status="1">' +
            '<td><div class="tv-member"><span class="tv-avatar">' + escapeHtml(initials(row
                .prg_type_full_name)) + '</span><span class="col-full-name">' + escapeHtml(row
                .prg_type_full_name) + '</span></div></td>' +
            '<td><span class="tv-tag blue col-short-name">' + escapeHtml(row.prg_type_short_name || '') +
            '</span></td>' +
            '<td><span class="tv-tag green tv-status-tag">Actif</span></td>' +
            actionsHtml +
            '</tr>');
        $table.DataTable().row.add($tr[0]).draw(false);
        refreshCounts(fac_id);
        applyStatusFilter($table.closest('.tv-pane'));
        selectNode('fac-' + fac_id);
    }

    //save program type
    $("#save_prg_type").submit(function(e) {
        e.preventDefault();

        var formData = {
            fac_id: $("#fac_id").val(),
            pt_f_name: $("#pt_f_name").val(),
            pt_s_name: $("#pt_s_name").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Programs/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if (data.status == 200) {
                    $('#save_prg_type')[0].reset();
                    $('#fac_id').val(null).trigger('change');
                    pop_up_success(data.message);
                    addProgramTypeRow(data.fac_id, data);
                }
                if (data.status == 401) {
                    pop_wrong(data.message);
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // delete/toggle program type
    $(document).on('click', '.del', function() {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Êtes-vous sûr ?",
            text: "Vous êtes sur le point de modifier le statut de ce type de programme.",
            icon: "warning",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_' + data_id).html(
                    "<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Programs/controller.php",
                    data: getData,
                    dataType: "json",
                    success: function(data) {
                        $('#spinner3_' + data_id).fadeOut('fast');
                        if (data.status == 500) {
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        } else if (data.status == 200) {
                            var isActive = $checkbox.prop('checked');
                            var $tr = $checkbox.closest('tr');
                            var $pane = $tr.closest('.tv-pane');
                            $tr.attr('data-status', isActive ? 1 : 0);
                            $tr.find('.tv-status-tag').removeClass('green red')
                                .addClass(isActive ? 'green' : 'red').text(
                                    isActive ? 'Actif' : 'Inactif');
                            $pane.find('table.prg_type_table').DataTable().row($tr[
                                0]).invalidate('dom');
                            refreshCounts($pane.data('fac-id'));
                            applyStatusFilter($pane);
                            pop_up_success(data.message);
                        }
                    },
                    error: function(error) {
                        $('#spinner3_' + data_id).fadeOut('fast');
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
                        pop_wrong("Une erreur s'est produite !");
                    }
                });
            } else {
                // keep the switch in sync with the real status
                $checkbox.prop('checked', !$checkbox.prop('checked'));
                swal("Opération annulée");
            }
        });
    });

    //pre-update View
    $(document).on('click', '.edit', function() {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'view'
        };
        $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn(
            'fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Programs/controller.php",
            data: getData,
            dataType: "json",

            success: function(data) {
                $('#spinner4_' + data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_pt_f_name").val(data.prg_type_full_name);
                $("#e_pt_s_name").val(data.prg_type_short_name);
                $("#f_name").text(data.prg_type_full_name);

                // Step 1: set campus, then load faculties, then set faculty
                var campusId = data
                    .campus_id; // needs campus_id returned from view_prg_type
                pendingFacId = data.fac_id;
                $("#e_campus_id").val(campusId).trigger('change');
                $('#updateModal').modal('show');
            },
            error: function(error) {
                $('#spinner4_' + data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update program type
    $("#update_form").submit(function(e) {
        e.preventDefault();

        var formData = {
            pr_id: $("#e_id").val(),
            fac_id: $("#e_fac_id").val(),
            pt_f_name: $("#e_pt_f_name").val(),
            pt_s_name: $("#e_pt_s_name").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Programs/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                if (data.status == 200) {
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if (data.fac_changed) {
                        // row must move to a different faculty - simplest correct path
                        try {
                            localStorage.setItem(PT_STORE_KEY, 'fac-' + data.fac_id);
                        } catch (e) {}
                        location.reload();
                    } else {
                        var hit = findRow(data.prg_type_id);
                        if (hit) {
                            hit.$tr.find('.col-full-name').text(data.prg_type_full_name);
                            hit.$tr.find('.col-short-name').text(data.prg_type_short_name ||
                                '');
                            hit.$tr.find('.tv-avatar').text(initials(data
                                .prg_type_full_name));
                            hit.row.invalidate('dom').draw(false);
                        }
                    }
                }
                if (data.status == 401) {
                    pop_wrong(data.message);
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // load faculty when campus changes (add form)
    $("#campus_id").change(function() {
        $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#fac_id").empty();
        var camp_id = $("#campus_id").val();
        var formData = {
            camp_id: camp_id,
            action: "get_faculty"
        }
        $.ajax({
            url: "../new_files/Programs/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner_fac').fadeOut('fast');
                $("#fac_id").append("<option></option>")
                $.each(data, function(index, value) {
                    $("#fac_id").append("<option value='" + value.fac_id + "'>" +
                        escapeHtml(value.fac_full_name) + "</option>");
                });
                if (pendingAddFacId) {
                    $("#fac_id").val(pendingAddFacId).trigger('change');
                    pendingAddFacId = null;
                }
            },
            error: function() {
                $('#spinner_fac').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");

            }
        });
    })

    // load faculty when campus changes (update modal)
    $("#e_campus_id").change(function() {
        var camp_id = $(this).val();
        if (!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Chargement...</option>");
        $.ajax({
            url: "../new_files/Programs/controller.php",
            type: "POST",
            data: {
                camp_id: camp_id,
                action: "get_faculty"
            },
            dataType: "JSON",
            success: function(data) {
                $('#spinner_e_fac').fadeOut('fast');
                $("#e_fac_id").empty().append(
                    "<option value=''>Choisir une faculté</option>");
                $.each(data, function(index, value) {
                    $("#e_fac_id").append("<option value='" + value.fac_id + "'>" +
                        escapeHtml(value.fac_full_name) + "</option>");
                });
                if (pendingFacId) {
                    $("#e_fac_id").val(pendingFacId).trigger('change');
                    pendingFacId = null;
                }
            },
            error: function() {
                $('#spinner_e_fac').fadeOut('fast');
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