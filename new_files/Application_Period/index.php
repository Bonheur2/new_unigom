<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    $statusLabels = ['active' => 'Ouverte', 'hold' => 'Suspendue', 'closed' => 'Clôturée', 'inactive' => 'Inactive'];
    $statusTags   = ['active' => 'green', 'hold' => 'amber', 'closed' => 'blue', 'inactive' => 'red'];

    // every academic year (a period may belong to a year that is now closed); current year first
    $sql_ay = $conn->prepare("SELECT acad_cycle_id, acad_year, status FROM tbl_acad_cycle ORDER BY (status = 1) DESC, acad_year DESC");
    $sql_ay->execute();
    $acadYears = $sql_ay->fetchAll(PDO::FETCH_ASSOC);
    $currentAy = 0;
    foreach($acadYears as $y){ if($y['status'] == 1){ $currentAy = (int)$y['acad_cycle_id']; break; } }

    $sql = $conn->prepare("SELECT p.*, c.acad_year,
                                  (SELECT COUNT(*) FROM tbl_applicants a WHERE a.application_period_id = p.id) AS applicants
                           FROM tbl_application_periods p
                           LEFT JOIN tbl_acad_cycle c ON c.acad_cycle_id = p.acad_cycle_id
                           ORDER BY p.start_date DESC, p.id DESC");
    $sql->execute();
    $periods = $sql->fetchAll(PDO::FETCH_ASSOC);

    $today = date('Y-m-d');
    // where a period stands in time, independent of its status
    $timing = function($start, $end) use ($today){
        if($today < $start){
            $d = (int)round((strtotime($start) - strtotime($today)) / 86400);
            return ['key' => 'upcoming', 'text' => 'Commence dans '.$d.' jour'.($d > 1 ? 's' : '')];
        }
        if($today > $end){
            return ['key' => 'past', 'text' => 'Terminée'];
        }
        $d = (int)round((strtotime($end) - strtotime($today)) / 86400);
        return ['key' => 'running', 'text' => $d == 0 ? 'Dernier jour' : $d.' jour'.($d > 1 ? 's' : '').' restant'.($d > 1 ? 's' : '')];
    };
    $fmt = function($date){ return $date ? date('d/m/Y', strtotime($date)) : ''; };

    $openNow = 0;
    $nextClose = null;
    $appTotal = 0;
    foreach($periods as $p){
        $appTotal += (int)$p['applicants'];
        if($p['status'] === 'active' && $today >= $p['start_date'] && $today <= $p['end_date']){
            $openNow++;
            if($nextClose === null || $p['end_date'] < $nextClose['end_date']) $nextClose = $p;
        }
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Périodes de candidature</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Période de candidature</a></div>
            </div>
        </div>

        <!-- Formulaire nouvelle période -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouvelle période de candidature</h4>
                <form id="save_period" action="save_period" method="POST">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Année académique</label>
                            <select class="form-control select2" style="width:100%" id="acad_cycle_id" required>
                                <?php foreach($acadYears as $y): ?>
                                <option value="<?php echo $y['acad_cycle_id']; ?>" <?php echo (int)$y['acad_cycle_id'] === $currentAy ? 'selected' : ''; ?>><?php echo htmlspecialchars($y['acad_year']); ?><?php echo $y['status'] == 1 ? ' (en cours)' : ''; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nom de la période</label>
                            <input type="text" class="form-control" id="period_name" placeholder="Ex : Première session" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="p_status">
                                <?php foreach($statusLabels as $val => $label): ?>
                                <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Date d'ouverture</label>
                            <input type="date" class="form-control" id="start_date" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Date de clôture</label>
                            <input type="date" class="form-control" id="end_date" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Notes (facultatif)">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- counters -->
        <div class="tv-kpis">
            <div class="tv-kpi">
                <div class="tv-stat-icon green"><i class="far fa-calendar-check"></i></div>
                <div>
                    <b><?php echo $openNow; ?></b>
                    <span class="tv-kpi-label">Ouverte<?php echo $openNow > 1 ? 's' : ''; ?> aujourd'hui</span>
                    <span class="tv-kpi-sub">Statut « Ouverte » et date du jour comprise dans la période</span>
                </div>
            </div>
            <div class="tv-kpi">
                <div class="tv-stat-icon red"><i class="far fa-hourglass"></i></div>
                <div>
                    <?php if($nextClose): $left = (int)round((strtotime($nextClose['end_date']) - strtotime($today)) / 86400); ?>
                    <b><?php echo $fmt($nextClose['end_date']); ?></b>
                    <span class="tv-kpi-label">Prochaine clôture</span>
                    <span class="tv-kpi-sub"><?php echo htmlspecialchars($nextClose['period_name']); ?> · <?php echo $left == 0 ? "aujourd'hui" : 'dans '.$left.' jour'.($left > 1 ? 's' : ''); ?></span>
                    <?php else: ?>
                    <b>-</b>
                    <span class="tv-kpi-label">Prochaine clôture</span>
                    <span class="tv-kpi-sub">Aucune période ouverte en ce moment</span>
                    <?php endif; ?>
                </div>
            </div>
            <a class="tv-kpi" href="edu?mis=sbtdap">
                <div class="tv-stat-icon primary"><i class="fas fa-file-signature"></i></div>
                <div>
                    <b><?php echo $appTotal; ?></b>
                    <span class="tv-kpi-label">Candidatures reçues</span>
                    <span class="tv-kpi-sub">Sur <?php echo count($periods); ?> période<?php echo count($periods) > 1 ? 's' : ''; ?></span>
                </div>
            </a>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Périodes enregistrées</h4>
                <div class="tv-actions">
                    <div class="tv-filter">
                        <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Toutes</button>
                        <?php foreach($statusLabels as $val => $label): ?>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="<?php echo $val; ?>"><?php echo $label; ?>s</button>
                        <?php endforeach; ?>
                    </div>
                    <?php if($canManage): ?>
                    <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouvelle période</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="tv-table period_table">
                    <thead>
                    <tr>
                        <th>Période</th>
                        <th>Année académique</th>
                        <th>Date d'ouverture</th>
                        <th>Date de clôture</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($periods as $row):
                        $t = $timing($row['start_date'], $row['end_date']);
                        $st = isset($statusLabels[$row['status']]) ? $row['status'] : 'inactive';
                        $stale = $st === 'active' && $t['key'] === 'past';
                    ?>
                    <tr data-row-id="<?php echo $row['id']; ?>" data-status="<?php echo $st; ?>" data-end="<?php echo htmlspecialchars($row['end_date']); ?>">
                        <td>
                            <div class="tv-member">
                                <span class="tv-avatar"><i class="far fa-calendar-alt"></i></span>
                                <span class="col-period-name"><?php echo htmlspecialchars($row['period_name']); ?></span>
                            </div>
                        </td>
                        <td class="col-acad-year"><?php echo htmlspecialchars($row['acad_year']); ?></td>
                        <td class="col-start"><?php echo $fmt($row['start_date']); ?></td>
                        <td class="col-end"><?php echo $fmt($row['end_date']); ?></td>
                        <td class="col-description"><?php echo $row['description'] != '' ? htmlspecialchars($row['description']) : '<span class="text-muted">-</span>'; ?></td>
                        <td class="col-status">
                            <?php if($canManage): ?>
                            <select class="ap-status-select row-status ap-<?php echo $statusTags[$st]; ?>" data-id="<?php echo $row['id']; ?>" aria-label="Statut">
                                <?php foreach($statusLabels as $val => $label): ?>
                                <option value="<?php echo $val; ?>" <?php echo $st === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span id="spinner3_<?php echo $row['id']; ?>"></span>
                            <?php else: ?>
                            <span class="tv-tag <?php echo $statusTags[$st]; ?>"><?php echo $statusLabels[$st]; ?></span>
                            <?php endif; ?>
                            <span class="ap-stale tv-tag amber mt-1" <?php echo $stale ? '' : 'style="display:none"'; ?> title="La date de clôture est passée mais la période est toujours ouverte">Date dépassée</span>
                        </td>
                        <?php if($canManage): ?>
                        <td class="tv-right">
                            <div class="tv-row-actions">
                                <button type="button" data-id="<?php echo $row['id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                    <span id="spinner4_<?php echo $row['id']; ?>"></span><i class="fas fa-pen"></i>
                                </button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                            <label>Année académique</label>
                            <select class="form-control select2" style="width:100%" id="e_acad_cycle_id" required>
                                <?php foreach($acadYears as $y): ?>
                                <option value="<?php echo $y['acad_cycle_id']; ?>"><?php echo htmlspecialchars($y['acad_year']); ?><?php echo $y['status'] == 1 ? ' (en cours)' : ''; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom de la période</label>
                            <input type="text" class="form-control" id="e_period_name" placeholder="Ex : Première session" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Date d'ouverture</label>
                                <input type="date" class="form-control" id="e_start_date" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Date de clôture</label>
                                <input type="date" class="form-control" id="e_end_date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="e_p_status">
                                <?php foreach($statusLabels as $val => $label): ?>
                                <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" id="e_description" placeholder="Notes (facultatif)">
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

<style>
.tv-page .period_table .col-start,.tv-page .period_table .col-end{white-space:nowrap}
.tv-page .period_table .col-description{max-width:220px;font-size:14px}
/* eight columns: tighter cells so the table fits the card */
.tv-page .period_table th,.tv-page .period_table td{padding-left:10px;padding-right:10px}
.tv-page .period_table th{letter-spacing:.02em;font-size:12px}
.tv-page .period_table td:first-child{min-width:170px}
/* inline status picker styled like the status badges */
.tv-page .ap-status-select{border:1px solid transparent;border-radius:0;padding:8px 14px;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;cursor:pointer;outline:none}
.tv-page .ap-status-select:focus{border-color:var(--tv-accent)}
.tv-page .ap-status-select.ap-green{background:var(--tv-green-soft);color:var(--tv-green)}
.tv-page .ap-status-select.ap-amber{background:#fff4d6;color:#8a5a00}
.tv-page .ap-status-select.ap-blue{background:var(--tv-blue-soft);color:var(--tv-blue)}
.tv-page .ap-status-select.ap-red{background:var(--tv-red-soft);color:var(--tv-red)}
.tv-page .ap-status-select option{background:#fff;color:var(--tv-text);text-transform:none}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
var STATUS_LABELS = <?php echo json_encode($statusLabels); ?>;
var STATUS_TAGS = <?php echo json_encode($statusTags); ?>;
var TODAY = <?php echo json_encode($today); ?>;

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}
function frDate(iso){
    if(!iso) return '';
    var p = String(iso).substring(0, 10).split('-');
    return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : iso;
}
function statusSelectHtml(id, current){
    var html = '<select class="ap-status-select row-status ap-' + STATUS_TAGS[current] + '" data-id="' + id + '" aria-label="Statut">';
    $.each(STATUS_LABELS, function(val, label){
        html += '<option value="' + val + '"' + (val === current ? ' selected' : '') + '>' + label + '</option>';
    });
    return html + '</select> <span id="spinner3_' + id + '"></span>';
}

$(document).ready(function(){
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        if(!$(settings.nTable).hasClass('period_table')) return true;
        var filter = $('.tv-status-filter.active').data('filter');
        if(filter === undefined || filter === 'all') return true;
        return String($(settings.aoData[dataIndex].nTr).attr('data-status')) === String(filter);
    });

    var periodDt = $('.period_table').DataTable({
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
            "emptyTable": "Aucune période de candidature enregistrée",
            "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
        }
    });

    $('.row-status').each(function(){ $(this).data('previous', $(this).val()); });

    function findRow(id){
        var found = null;
        periodDt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(id)) found = { row: this, $tr: $(this.node()) };
        });
        return found;
    }

    // keep the row's status attribute, badge colour and "Date dépassée" flag in sync
    function applyStatus($tr, st, end){
        $tr.attr('data-status', st);
        var $sel = $tr.find('.row-status');
        $sel.removeClass('ap-green ap-amber ap-blue ap-red').addClass('ap-' + STATUS_TAGS[st]).val(st).data('previous', st);
        var endDate = end || $tr.data('end');
        $tr.find('.ap-stale').toggle(st === 'active' && endDate && TODAY > endDate);
    }

    $(document).on('click', '.tv-status-filter', function(){
        $('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        periodDt.draw(false);
    });

    $('#tv-new-btn').on('click', function(){
        $('#mycard-collapse').collapse('toggle');
    });

    function addPeriodRow(row){
        var st = row.status_val || 'active';
        var $tr = $('<tr data-row-id="'+row.id+'" data-status="'+st+'">'
            + '<td><div class="tv-member"><span class="tv-avatar"><i class="far fa-calendar-alt"></i></span>'
            + '<span class="col-period-name">'+escapeHtml(row.period_name)+'</span></div></td>'
            + '<td class="col-acad-year">'+escapeHtml(row.acad_year)+'</td>'
            + '<td class="col-start">'+frDate(row.start_date)+'</td>'
            + '<td class="col-end">'+frDate(row.end_date)+'</td>'
            + '<td class="col-description">'+descHtml(row.description)+'</td>'
            + '<td class="col-status">'+(canManage ? statusSelectHtml(row.id, st) : '<span class="tv-tag '+STATUS_TAGS[st]+'">'+STATUS_LABELS[st]+'</span>')
            + ' <span class="ap-stale tv-tag amber mt-1" style="display:none" title="La date de clôture est passée mais la période est toujours ouverte">Date dépassée</span></td>'
            + (canManage ? '<td class="tv-right"><div class="tv-row-actions"><button type="button" data-id="'+row.id+'" class="tv-icon-btn edit" title="Modifier">'
                + '<span id="spinner4_'+row.id+'"></span><i class="fas fa-pen"></i></button></div></td>' : '')
            + '</tr>');
        $tr.data('end', row.end_date);
        periodDt.row.add($tr[0]).draw(false);
        applyStatus($tr, st, row.end_date);
    }

    //save application period
    $("#save_period").submit(function(e){
        e.preventDefault();

        var formData = {
            acad_cycle_id: $("#acad_cycle_id").val(),
            period_name: $("#period_name").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val(),
            status: $("#p_status").val(),
            description: $("#description").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Application_Period/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    var keepYear = $('#acad_cycle_id').val();
                    $('#save_period')[0].reset();
                    $('#acad_cycle_id').val(keepYear).trigger('change');
                    $('#p_status').val('active').trigger('change');
                    pop_up_success(data.message);
                    addPeriodRow(data);
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

    // change status inline
    $(document).on('change','.row-status',function () {
        var $select = $(this);
        var data_id = $select.data('id');
        var new_status = $select.val();
        var previous_status = $select.data('previous');
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Application_Period/controller.php",
            data: { id: data_id, status: new_status, action: 'change_status' },
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500 || data.status==401){
                    $select.val(previous_status);
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                    var hit = findRow(data_id);
                    if(hit){
                        applyStatus(hit.$tr, new_status);
                        hit.row.invalidate('dom').draw(false);
                    }
                    pop_up_success(data.message);
                }
            },
            error:function(error){
                $('#spinner3_'+data_id).fadeOut('fast');
                $select.val(previous_status);
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //pre-update View
    $(document).on('click','.edit',function () {
        var data_id = $(this).data('id');
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Application_Period/controller.php",
            data: { id: data_id, action: 'view' },
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_acad_cycle_id").val(String(data.acad_cycle_id)).trigger('change');
                $("#e_period_name").val(data.period_name);
                $("#e_start_date").val(data.start_date);
                $("#e_end_date").val(data.end_date);
                $("#e_p_status").val(data.status).trigger('change');
                $("#e_description").val(data.description);
                $("#f_name").text(data.period_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update application period
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            acad_cycle_id: $("#e_acad_cycle_id").val(),
            period_name: $("#e_period_name").val(),
            start_date: $("#e_start_date").val(),
            end_date: $("#e_end_date").val(),
            status: $("#e_p_status").val(),
            description: $("#e_description").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Application_Period/controller.php",
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
                    var hit = findRow(data.id);
                    if(hit){
                        hit.$tr.data('end', data.end_date);
                        hit.$tr.find('.col-acad-year').text(data.acad_year || '');
                        hit.$tr.find('.col-period-name').text(data.period_name);
                        hit.$tr.find('.col-description').html(descHtml(data.description));
                        hit.$tr.find('.col-start').text(frDate(data.start_date));
                        hit.$tr.find('.col-end').text(frDate(data.end_date));
                        applyStatus(hit.$tr, data.status_val, data.end_date);
                        hit.row.invalidate('dom').draw(false);
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
