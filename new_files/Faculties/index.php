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
    function fac_attr($v){ return htmlspecialchars((string)$v, ENT_QUOTES); }

    // number of program types per school
    $sql_pt = $conn->prepare("SELECT fac_id, COUNT(*) AS total FROM tbl_program_type GROUP BY fac_id");
    $sql_pt->execute();
    $ptCounts = [];
    while($r = $sql_pt->fetch()){ $ptCounts[$r['fac_id']] = (int)$r['total']; }

    // load the whole structure once: campus > schools
    $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                FROM tbl_campus
                                INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                WHERE tbl_campus.camp_active=1
                                ORDER BY tbl_campus.camp_full_name ASC");
    $sql_tabs->execute();
    $campuses = $sql_tabs->fetchAll();

    foreach($campuses as $cIndex => $campus){
        $sql = $conn->prepare("SELECT tbl_faculty.*, tbl_campus.camp_full_name
                               FROM tbl_faculty
                               INNER JOIN tbl_campus ON tbl_faculty.campus_id = tbl_campus.camp_id
                               WHERE tbl_campus.camp_active=1
                               AND tbl_campus.camp_id = :camp_id
                               ORDER BY tbl_faculty.status ASC");
        $sql->execute([':camp_id' => $campus['camp_id']]);
        $schools = $sql->fetchAll();
        $active = 0;
        foreach($schools as $s){ if($s['status'] == 1) $active++; }
        $campuses[$cIndex]['schools'] = $schools;
        $campuses[$cIndex]['active'] = $active;
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Facultés</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Faculté</a></div>
            </div>
        </div>
        <div class="tv-head">
            <div>
                <p>Parcourez les facultés par campus. Sélectionnez un élément dans la structure pour afficher ses détails.</p>
            </div>
            <?php if($canManage): ?>
            <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouvelle faculté</button>
            <?php endif; ?>
        </div>

        <!-- Formulaire nouvelle faculté -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouvelle faculté</h4>
                <form id="save_program" action="save_program" method="POST">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Campus</label>
                            <select class="form-control select2" style="width:100%" name="campus_id" id="campus_id">
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
                        <div class="form-group col-md-3">
                            <label>Nom complet de la faculté</label>
                            <input type="text" class="form-control" id="f_f_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Nom abrégé de la faculté</label>
                            <input type="text" class="form-control" id="f_s_name" placeholder="Nom abrégé">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Code de la faculté</label>
                            <input type="text" class="form-control" id="f_code" placeholder="Ex : 01">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(count($campuses) === 0): ?>
        <div class="tv-card tv-empty">Aucune faculté enregistrée pour le moment. Utilisez « Nouvelle faculté » pour ajouter la première.</div>
        <?php else: ?>
        <div class="row">
            <!-- Structure card -->
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Structure</h4>
                    <div class="tv-tree" id="tv-tree">
                        <?php foreach($campuses as $campus): ?>
                        <div class="tv-tree-group" data-campus="<?php echo $campus['camp_id']; ?>">
                            <div class="tv-node tv-campus" data-target="campus-<?php echo $campus['camp_id']; ?>">
                                <i class="fas fa-chevron-right tv-chev"></i>
                                <i class="far fa-building"></i>
                                <span class="tv-name"><?php echo htmlspecialchars($campus['camp_full_name']); ?></span>
                                <span class="tv-count" data-count-campus="<?php echo $campus['camp_id']; ?>"><?php echo count($campus['schools']); ?></span>
                            </div>
                            <div class="tv-children">
                                <?php foreach($campus['schools'] as $s): ?>
                                <div class="tv-node tv-school <?php echo $s['status'] == 1 ? '' : 'tv-inactive'; ?>"
                                    data-target="school-<?php echo $s['fac_id']; ?>"
                                    data-id="<?php echo $s['fac_id']; ?>"
                                    data-campus="<?php echo $campus['camp_id']; ?>"
                                    data-campus-name="<?php echo fac_attr($campus['camp_full_name']); ?>"
                                    data-name="<?php echo fac_attr($s['fac_full_name']); ?>"
                                    data-short="<?php echo fac_attr($s['fac_short_name']); ?>"
                                    data-code="<?php echo fac_attr($s['code']); ?>"
                                    data-status="<?php echo $s['status'] == 1 ? 1 : 0; ?>"
                                    data-programs="<?php echo isset($ptCounts[$s['fac_id']]) ? $ptCounts[$s['fac_id']] : 0; ?>">
                                    <i class="far fa-folder"></i>
                                    <span class="tv-name"><?php echo htmlspecialchars($s['fac_full_name']); ?></span>
                                    <span class="tv-count"><?php echo isset($ptCounts[$s['fac_id']]) ? $ptCounts[$s['fac_id']] : 0; ?></span>
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
                <div class="tv-pane" id="pane-campus-<?php echo $campus['camp_id']; ?>" data-campus="<?php echo $campus['camp_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2><?php echo htmlspecialchars($campus['camp_full_name']); ?> <span class="tv-badge">Campus</span></h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>"><i class="fas fa-plus"></i> Ajouter une faculté</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-university"></i></div>
                                <div><b data-stat-total="<?php echo $campus['camp_id']; ?>"><?php echo count($campus['schools']); ?></b><span>Facultés</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon green"><i class="fas fa-check"></i></div>
                                <div><b data-stat-active="<?php echo $campus['camp_id']; ?>"><?php echo $campus['active']; ?></b><span>Actives</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon red"><i class="fas fa-ban"></i></div>
                                <div><b data-stat-inactive="<?php echo $campus['camp_id']; ?>"><?php echo count($campus['schools']) - $campus['active']; ?></b><span>Inactives</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Facultés</h4>
                            <div class="tv-filter">
                                <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Toutes</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actives</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactives</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table faculty_table" data-camp-id="<?php echo $campus['camp_id']; ?>">
                                <thead>
                                <tr>
                                    <th>Faculté</th>
                                    <th>Code</th>
                                    <th>Types de programme</th>
                                    <th>Statut</th>
                                    <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($campus['schools'] as $s): ?>
                                <tr data-row-id="<?php echo $s['fac_id']; ?>" data-status="<?php echo $s['status'] == 1 ? 1 : 0; ?>">
                                    <td><div class="tv-member tv-open" data-target="school-<?php echo $s['fac_id']; ?>" style="cursor:pointer"><span class="tv-avatar"><?php echo htmlspecialchars(tv_initials($s['fac_full_name'])); ?></span><span class="col-full-name"><?php echo htmlspecialchars($s['fac_full_name']); ?></span></div></td>
                                    <td><span class="tv-tag blue col-code"><?php echo htmlspecialchars($s['code']); ?></span></td>
                                    <td><?php echo isset($ptCounts[$s['fac_id']]) ? $ptCounts[$s['fac_id']] : 0; ?></td>
                                    <td><span class="tv-tag <?php echo $s['status'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $s['status'] == 1 ? 'Active' : 'Inactive'; ?></span></td>
                                    <?php if($canManage): ?>
                                    <td class="tv-right">
                                        <div class="tv-row-actions">
                                            <button type="button" data-id="<?php echo $s['fac_id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                                <span id="spinner4_<?php echo $s['fac_id']; ?>"></span><i class="fas fa-pen"></i>
                                            </button>
                                            <label class="custom-switch" title="Activer / désactiver">
                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $s['fac_id']; ?>" <?php echo $s['status']==1 ? 'checked' : ''; ?>>
                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $s['fac_id']; ?>"></span>
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

                <!-- school pane (filled from the selected tree node) -->
                <div class="tv-pane" id="pane-school">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><span id="sp-name"></span><small id="sp-campus"></small></span>
                                <span class="tv-badge">Faculté</span>
                            </h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-outline edit" id="sp-edit"><i class="fas fa-pen"></i> Modifier la faculté</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-graduation-cap"></i></div>
                                <div><b id="sp-programs">0</b><span>Types de programme</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-hashtag"></i></div>
                                <div><b id="sp-code-stat">-</b><span>Code de la faculté</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Détails</h4>
                            <button type="button" class="tv-link tv-open" id="sp-campus-link">Voir le campus <i class="fas fa-arrow-right"></i></button>
                        </div>
                        <dl class="tv-info">
                            <dt>Nom complet</dt><dd id="sp-full"></dd>
                            <dt>Nom abrégé</dt><dd id="sp-short"></dd>
                            <dt>Code</dt><dd id="sp-code"></dd>
                            <dt>Campus</dt><dd id="sp-campus-name"></dd>
                            <dt>Statut</dt>
                            <dd>
                                <span class="tv-tag" id="sp-status"></span>
                                <?php if($canManage): ?>
                                <label class="custom-switch ml-2 mb-0" title="Activer / désactiver">
                                    <input type="checkbox" class="custom-switch-input del" id="sp-switch">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </div>
                </div>
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
                        </div>
                        <div class="form-group">
                            <label>Nom complet de la faculté</label>
                            <input type="text" class="form-control" id="e_ft_f_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group">
                            <label>Nom abrégé de la faculté</label>
                            <input type="text" class="form-control" id="e_ft_s_name" placeholder="Nom abrégé">
                        </div>
                        <div class="form-group">
                            <label>Code de la faculté</label>
                            <input type="text" class="form-control" id="e_ft_code" placeholder="Ex : 01">
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
var FAC_STORE_KEY = 'fac_selected_node';
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
var currentSchoolId = null;

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}

function initials(name){
    return (name || '').trim().split(/\s+/).slice(0, 2).map(function(w){ return w.charAt(0).toUpperCase(); }).join('');
}

// fill the school pane from the data stored on its tree node
function fillSchoolPane($node){
    var id = $node.attr('data-id');
    var active = String($node.attr('data-status')) === '1';
    currentSchoolId = id;
    $('#sp-name, #sp-full').text($node.attr('data-name'));
    $('#sp-campus, #sp-campus-name').text($node.attr('data-campus-name'));
    $('#sp-short').text($node.attr('data-short') || '-');
    $('#sp-code').text($node.attr('data-code') || '-');
    $('#sp-code-stat').text($node.attr('data-code') || '-');
    $('#sp-programs').text($node.attr('data-programs') || 0);
    $('#sp-status').removeClass('green red').addClass(active ? 'green' : 'red').text(active ? 'Active' : 'Inactive');
    $('#sp-campus-link').data('target', 'campus-' + $node.attr('data-campus'));
    $('#sp-edit').data('id', id).attr('data-id', id);
    $('#sp-switch').data('id', id).attr('data-id', id).prop('checked', active);
}

// show the details pane for a tree node ("campus-ID" or "school-ID")
function selectNode(target){
    var $node = $('.tv-node[data-target="' + target + '"]');
    var $pane;
    if(target.indexOf('school-') === 0){
        if($node.length === 0) return false;
        fillSchoolPane($node);
        $pane = $('#pane-school');
    } else {
        $pane = $('#pane-' + target);
        if($pane.length === 0) return false;
        currentSchoolId = null;
    }
    $('.tv-pane').removeClass('active');
    $pane.addClass('active');
    $('.tv-node').removeClass('active');
    $node.addClass('active');
    // make sure the parent campus is expanded
    var $group = $node.closest('.tv-tree-group');
    $group.find('.tv-campus').addClass('open');
    $group.find('.tv-children').addClass('open');
    try { localStorage.setItem(FAC_STORE_KEY, target); } catch(e) {}
    // tables initialised while hidden need their column widths recalculated
    $pane.find('table.faculty_table').each(function(){
        if($.fn.DataTable.isDataTable(this)) $(this).DataTable().columns.adjust();
    });
    return true;
}

// every row of a campus table, including rows on other pages
function allRowNodes(camp_id){
    var $table = $("table.faculty_table[data-camp-id='" + camp_id + "']");
    if($table.length === 0) return $();
    return $($table.DataTable().rows().nodes());
}

// find a school row in any table, whatever page it is on
function findRow(fac_id){
    var found = null;
    $('table.faculty_table').each(function(){
        var dt = $(this).DataTable();
        dt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(fac_id)) found = { dt: dt, row: this, $tr: $(this.node()), $table: $(dt.table().node()) };
        });
    });
    return found;
}

// recompute counters for one campus
function refreshCounts(camp_id){
    var $rows = allRowNodes(camp_id);
    var total = $rows.length;
    var active = $rows.filter('[data-status="1"]').length;
    $('[data-stat-total="' + camp_id + '"], [data-count-campus="' + camp_id + '"]').text(total);
    $('[data-stat-active="' + camp_id + '"]').text(active);
    $('[data-stat-inactive="' + camp_id + '"]').text(total - active);
}

function applyStatusFilter($pane){
    $pane.find('table.faculty_table').DataTable().draw(false);
}

// keep row, tree node and school pane in sync after a status change
function setSchoolStatus(fac_id, isActive){
    var hit = findRow(fac_id);
    if(hit){
        hit.$tr.attr('data-status', isActive ? 1 : 0);
        hit.$tr.find('.tv-status-tag').removeClass('green red').addClass(isActive ? 'green' : 'red').text(isActive ? 'Active' : 'Inactive');
        hit.$tr.find('.del').prop('checked', isActive);
        hit.row.invalidate('dom');
        refreshCounts(hit.$table.data('camp-id'));
        applyStatusFilter(hit.$table.closest('.tv-pane'));
    }
    var $node = $('.tv-school[data-id="' + fac_id + '"]');
    $node.attr('data-status', isActive ? 1 : 0).toggleClass('tv-inactive', !isActive);
    if(String(currentSchoolId) === String(fac_id)) fillSchoolPane($node);
}

$(document).ready(function(){
    // status chips filter through DataTables so paging stays correct
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        var $table = $(settings.nTable);
        if(!$table.hasClass('faculty_table')) return true;
        var filter = $table.closest('.tv-pane').find('.tv-status-filter.active').data('filter');
        if(filter === undefined || filter === 'all') return true;
        var node = settings.aoData[dataIndex].nTr;
        return String($(node).attr('data-status')) === String(filter);
    });

    $('.faculty_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Tout"]],
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

    // tree: campus click expands and shows campus details, school click shows school details
    $(document).on('click', '.tv-campus', function(){
        var $children = $(this).next('.tv-children');
        if($(this).hasClass('active')){
            $(this).toggleClass('open');
            $children.toggleClass('open');
            return;
        }
        selectNode($(this).data('target'));
    });
    $(document).on('click', '.tv-school, .tv-open', function(){
        selectNode($(this).data('target'));
    });

    // restore the last selected node, otherwise the first campus
    var saved = null;
    try { saved = localStorage.getItem(FAC_STORE_KEY); } catch(e) {}
    if(!saved || !selectNode(saved)){
        var first = $('.tv-campus').first().data('target');
        if(first) selectNode(first);
    }

    // status filter chips
    $(document).on('click', '.tv-status-filter', function(){
        var $pane = $(this).closest('.tv-pane');
        $pane.find('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        applyStatusFilter($pane);
    });

    // open / close the new school form
    $('#tv-new-btn').on('click', function(){
        $('#mycard-collapse').collapse('toggle');
    });

    // "Add school" from a campus: open the form with the campus preselected
    $(document).on('click', '.tv-add-here', function(){
        $('#mycard-collapse').collapse('show');
        $('#campus_id').val($(this).data('campus')).trigger('change');
        $('html, body').animate({ scrollTop: $('#mycard-collapse').offset().top - 90 }, 250);
        $('#f_f_name').focus();
    });

    // build a table row and a tree node for a school
    function addFacultyRow(camp_id, row){
        var $table = $("table.faculty_table[data-camp-id='"+camp_id+"']");
        if($table.length === 0){
            // no node exists yet for this campus (first item) - only way to show it is a reload
            try { localStorage.setItem(FAC_STORE_KEY, 'school-' + row.fac_id); } catch(e) {}
            location.reload();
            return;
        }
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<td class="tv-right"><div class="tv-row-actions">'
                + '<button type="button" data-id="'+row.fac_id+'" class="tv-icon-btn edit" title="Modifier">'
                + '<span id="spinner4_'+row.fac_id+'"></span><i class="fas fa-pen"></i></button>'
                + '<label class="custom-switch" title="Activer / désactiver">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.fac_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.fac_id+'"></span></label></div></td>';
        }
        var $tr = $('<tr data-row-id="'+row.fac_id+'" data-status="1">'
            + '<td><div class="tv-member tv-open" data-target="school-'+row.fac_id+'" style="cursor:pointer"><span class="tv-avatar">'+escapeHtml(initials(row.fac_full_name))+'</span><span class="col-full-name">'+escapeHtml(row.fac_full_name)+'</span></div></td>'
            + '<td><span class="tv-tag blue col-code">'+escapeHtml(row.code || '')+'</span></td>'
            + '<td>0</td>'
            + '<td><span class="tv-tag green tv-status-tag">Active</span></td>'
            + actionsHtml
            + '</tr>');
        $table.DataTable().row.add($tr[0]).draw(false);

        var $group = $('.tv-tree-group[data-campus="' + camp_id + '"]');
        var $node = $('<div class="tv-node tv-school"><i class="far fa-folder"></i><span class="tv-name"></span><span class="tv-count">0</span></div>');
        $node.attr({
            'data-target': 'school-' + row.fac_id,
            'data-id': row.fac_id,
            'data-campus': camp_id,
            'data-campus-name': $group.find('.tv-campus .tv-name').text(),
            'data-name': row.fac_full_name,
            'data-short': row.fac_short_name || '',
            'data-code': row.code || '',
            'data-status': 1,
            'data-programs': 0
        });
        $node.find('.tv-name').text(row.fac_full_name);
        $group.find('.tv-children').append($node);

        refreshCounts(camp_id);
        applyStatusFilter($table.closest('.tv-pane'));
        selectNode('campus-' + camp_id);
    }

    //save faculty
    $("#save_program").submit(function(e){
        e.preventDefault();

        var formData = {
            campus_id:$("#campus_id").val(),
            ft_f_name:$("#f_f_name").val(),
            ft_s_name:$("#f_s_name").val(),
            ft_code:$("#f_code").val(),
            action:'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Faculties/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    $('#save_program')[0].reset();
                    pop_up_success(data.message);
                    addFacultyRow(data.campus_id, data);
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

    // delete faculty
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData= {
                id: data_id,
                action:'delete'
                };
        swal({
        title: "Êtes-vous sûr ?",
        text: "Vous êtes sur le point de modifier le statut de cette faculté.",
        icon: "warning",
        buttons: ["Annuler", "Confirmer"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Faculties/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500){
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                    setSchoolStatus(data_id, $checkbox.prop('checked'));
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
            url: "../new_files/Faculties/controller.php",
            data: getData,
            dataType:"json",

            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_campus_id").val(data.campus_id).trigger('change');
                $("#e_ft_f_name").val(data.fac_full_name);
                $("#e_ft_s_name").val(data.fac_short_name);
                $("#e_ft_code").val(data.code);
                $("#f_name").text(data.fac_full_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update faculty
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            pr_id:$("#e_id").val(),
            campus_id:$("#e_campus_id").val(),
            ft_f_name:$("#e_ft_f_name").val(),
            ft_s_name:$("#e_ft_s_name").val(),
            ft_s_codee:$("#e_ft_code").val(),
            action:'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Faculties/controller.php",
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
                    if(data.campus_changed){
                        // school must move to a different campus - simplest correct path
                        try { localStorage.setItem(FAC_STORE_KEY, 'school-' + data.fac_id); } catch(e) {}
                        location.reload();
                    } else {
                        var hit = findRow(data.fac_id);
                        if(hit){
                            hit.$tr.find('.col-full-name').text(data.fac_full_name);
                            hit.$tr.find('.col-code').text(data.code || '');
                            hit.$tr.find('.tv-avatar').text(initials(data.fac_full_name));
                            hit.row.invalidate('dom').draw(false);
                        }
                        var $node = $('.tv-school[data-id="' + data.fac_id + '"]');
                        $node.attr({
                            'data-name': data.fac_full_name,
                            'data-short': data.fac_short_name || '',
                            'data-code': data.code || ''
                        });
                        $node.find('.tv-name').text(data.fac_full_name);
                        if(String(currentSchoolId) === String(data.fac_id)) fillSchoolPane($node);
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

    document.getElementById('f_code').addEventListener('input', function (e) {
        let value = e.target.value;
        value = value.replace(/[^a-zA-Z0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2);
        }
        e.target.value = value;
    });
    document.getElementById('e_ft_code').addEventListener('input', function (e) {
        let value = e.target.value;
        value = value.replace(/[^a-zA-Z0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2);
        }
        e.target.value = value;
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
