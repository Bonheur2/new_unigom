<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // 1 = en cours, 2 = clôturée, 0 = inactive
    $statusMeta = [
        1 => ['label' => 'En cours',  'tag' => 'green'],
        2 => ['label' => 'Clôturée',  'tag' => 'blue'],
        0 => ['label' => 'Inactive',  'tag' => 'red'],
    ];

    $sql = $conn->prepare("SELECT c.acad_cycle_id, c.acad_year, c.status,
                                  (SELECT COUNT(DISTINCT r.reg_no) FROM tbl_register_program_ug r WHERE r.acad_cycle_id = c.acad_cycle_id) AS students,
                                  (SELECT COUNT(*) FROM tbl_application_periods p WHERE p.acad_cycle_id = c.acad_cycle_id) AS periods
                           FROM tbl_acad_cycle c
                           ORDER BY c.acad_year DESC");
    $sql->execute();
    $years = $sql->fetchAll();

    $counts = [1 => 0, 2 => 0, 0 => 0];
    $current = [];
    foreach($years as $y){
        $st = isset($statusMeta[(int)$y['status']]) ? (int)$y['status'] : 0;
        $counts[$st]++;
        if($st === 1) $current[] = $y;
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Années académiques</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Année académique</a></div>
            </div>
        </div>

        <!-- Formulaire nouvelle année académique -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouvelle année académique</h4>
                <form id="save_acad_year" action="save_acad_year" method="POST">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Année académique</label>
                            <input type="text" class="form-control" id="acad_year" placeholder="Ex : 2026-2027"
                                pattern="\d{4}-\d{4}" maxlength="9" title="Format AAAA-AAAA, par exemple 2026-2027"
                                required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="acad_status">
                                <option value="1">En cours</option>
                                <option value="2">Clôturée</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span
                                    id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php if(count($current) > 1): ?>
        <div class="alert alert-warning">Plusieurs années sont marquées « En cours ». Le tableau de bord utilise la plus
            récente ; clôturez les autres si ce n'est pas voulu.</div>
        <?php endif; ?>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Années enregistrées</h4>
                <div class="tv-actions">
                    <div class="tv-filter">
                        <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Toutes</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="1">En cours</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="2">Clôturées</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactives</button>
                    </div>
                    <?php if($canManage): ?>
                    <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i>
                        Nouvelle année</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="tv-table acad_year_table">
                    <thead>
                        <tr>
                            <th>Année académique</th>
                            <th>Étudiants inscrits</th>
                            <th>Périodes de candidature</th>
                            <th>Statut</th>
                            <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($years as $row):
                        $st = isset($statusMeta[(int)$row['status']]) ? (int)$row['status'] : 0;
                    ?>
                        <tr data-row-id="<?php echo $row['acad_cycle_id']; ?>" data-status="<?php echo $st; ?>">
                            <td>
                                <div class="tv-member"><span class="tv-avatar"><i
                                            class="far fa-calendar"></i></span><span
                                        class="col-acad-year"><?php echo htmlspecialchars($row['acad_year']); ?></span>
                                </div>
                            </td>
                            <td><?php echo (int)$row['students']; ?></td>
                            <td><?php echo (int)$row['periods']; ?></td>
                            <td><span
                                    class="tv-tag <?php echo $statusMeta[$st]['tag']; ?> tv-status-tag"><?php echo $statusMeta[$st]['label']; ?></span>
                            </td>
                            <?php if($canManage): ?>
                            <td class="tv-right">
                                <div class="tv-row-actions">
                                    <button type="button" data-id="<?php echo $row['acad_cycle_id']; ?>"
                                        class="tv-icon-btn edit" title="Modifier">
                                        <span id="spinner4_<?php echo $row['acad_cycle_id']; ?>"></span><i
                                            class="fas fa-pen"></i>
                                    </button>
                                    <label class="custom-switch" title="En cours / clôturée">
                                        <input type="checkbox" name="custom-switch-checkbox"
                                            class="custom-switch-input del"
                                            data-id="<?php echo $row['acad_cycle_id']; ?>"
                                            <?php echo $st === 1 ? 'checked' : ''; ?>>
                                        <span class="custom-switch-indicator"></span><span
                                            id="spinner3_<?php echo $row['acad_cycle_id']; ?>"></span>
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
                            <input type="text" class="form-control" id="e_acad_year" placeholder="Ex : 2026-2027"
                                pattern="\d{4}-\d{4}" maxlength="9" title="Format AAAA-AAAA, par exemple 2026-2027"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="e_acad_status">
                                <option value="1">En cours</option>
                                <option value="2">Clôturée</option>
                                <option value="0">Inactive</option>
                            </select>
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

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
var STATUS = {
    1: {
        label: 'En cours',
        tag: 'green'
    },
    2: {
        label: 'Clôturée',
        tag: 'blue'
    },
    0: {
        label: 'Inactive',
        tag: 'red'
    }
};

function escapeHtml(s) {
    return $('<div>').text(s == null ? '' : s).html();
}

function statusHtml(st) {
    var m = STATUS[st] || STATUS[0];
    return '<span class="tv-tag ' + m.tag + ' tv-status-tag">' + m.label + '</span>';
}

$(document).ready(function() {
    // status chips filter through DataTables so paging stays correct
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (!$(settings.nTable).hasClass('acad_year_table')) return true;
        var filter = $('.tv-status-filter.active').data('filter');
        if (filter === undefined || filter === 'all') return true;
        return String($(settings.aoData[dataIndex].nTr).attr('data-status')) === String(filter);
    });

    var acadYearDt = $('.acad_year_table').DataTable({
        "aLengthMenu": [
            [5, 10, 25, -1],
            [5, 10, 25, "Tout"]
        ],
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
            "emptyTable": "Aucune année académique enregistrée",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précédent"
            }
        }
    });

    function findRow(id) {
        var found = null;
        acadYearDt.rows().every(function() {
            if (String($(this.node()).attr('data-row-id')) === String(id)) found = {
                row: this,
                $tr: $(this.node())
            };
        });
        return found;
    }

    // header counters, recomputed from every row (including other pages)
    function refreshCounts() {
        var $rows = $(acadYearDt.rows().nodes());
        var current = [];
        $rows.filter('[data-status="1"]').each(function() {
            current.push($(this).find('.col-acad-year').text());
        });
        $('#ay-total').text($rows.length);
        $('#ay-closed').text($rows.filter('[data-status="2"]').length);
        $('#ay-inactive').text($rows.filter('[data-status="0"]').length);
        $('#ay-current-name').text(current.length ? current.join(', ') : 'Aucune');
        if (!current.length) $('#ay-current-sub').text("Activez une année avec l'interrupteur");
    }

    function setRowStatus(hit, st) {
        hit.$tr.attr('data-status', st);
        hit.$tr.find('.tv-status-tag').replaceWith(statusHtml(st));
        hit.$tr.find('.del').prop('checked', String(st) === '1');
        hit.row.invalidate('dom').draw(false);
        refreshCounts();
    }

    $(document).on('click', '.tv-status-filter', function() {
        $('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        acadYearDt.draw(false);
    });

    // open / close the new year form
    $('#tv-new-btn').on('click', function() {
        $('#mycard-collapse').collapse('toggle');
    });

    // build a table row for an academic year and add it to the DataTable
    function addAcadYearRow(row) {
        var st = parseInt(row.status_val, 10) || 0;
        var actionsHtml = '';
        if (canManage) {
            actionsHtml = '<td class="tv-right"><div class="tv-row-actions">' +
                '<button type="button" data-id="' + row.acad_cycle_id +
                '" class="tv-icon-btn edit" title="Modifier">' +
                '<span id="spinner4_' + row.acad_cycle_id + '"></span><i class="fas fa-pen"></i></button>' +
                '<label class="custom-switch" title="En cours / clôturée">' +
                '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="' +
                row.acad_cycle_id + '"' + (st === 1 ? ' checked' : '') + '>' +
                '<span class="custom-switch-indicator"></span><span id="spinner3_' + row.acad_cycle_id +
                '"></span></label></div></td>';
        }
        var $tr = $('<tr data-row-id="' + row.acad_cycle_id + '" data-status="' + st + '">' +
            '<td><div class="tv-member"><span class="tv-avatar"><i class="far fa-calendar"></i></span><span class="col-acad-year">' +
            escapeHtml(row.acad_year) + '</span></div></td>' +
            '<td>0</td><td>0</td>' +
            '<td>' + statusHtml(st) + '</td>' +
            actionsHtml +
            '</tr>');
        acadYearDt.row.add($tr[0]).draw(false);
        refreshCounts();
    }

    //save academic year
    $("#save_acad_year").submit(function(e) {
        e.preventDefault();

        var formData = {
            acad_year: $("#acad_year").val(),
            status: $("#acad_status").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Academic_year/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if (data.status == 200) {
                    $('#save_acad_year')[0].reset();
                    $('#acad_status').val('1').trigger('change');
                    pop_up_success(data.message);
                    addAcadYearRow(data);
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

    // switch: en cours <-> clôturée
    $(document).on('click', '.del', function() {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var opening = $checkbox.prop('checked');
        var name = $checkbox.closest('tr').find('.col-acad-year').text();
        swal({
            title: "Êtes-vous sûr ?",
            text: opening ? "L'année " + name + " sera marquée « En cours »." : "L'année " +
                name + " sera clôturée.",
            icon: "warning",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: true,
        }).then((willChange) => {
            if (willChange) {
                $('#spinner3_' + data_id).html(
                    "<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Academic_year/controller.php",
                    data: {
                        id: data_id,
                        action: 'delete'
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#spinner3_' + data_id).fadeOut('fast');
                        if (data.status == 500) {
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        } else if (data.status == 200) {
                            var hit = findRow(data_id);
                            if (hit) setRowStatus(hit, data.status_val);
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
        $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn(
            'fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Academic_year/controller.php",
            data: {
                id: data_id,
                action: 'view'
            },
            dataType: "json",
            success: function(data) {
                $('#spinner4_' + data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_acad_year").val(data.acad_year);
                $("#e_acad_status").val(String(data.status)).trigger('change');
                $("#f_name").text(data.acad_year);
                $('#updateModal').modal('show');
            },
            error: function(error) {
                $('#spinner4_' + data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update academic year
    $("#update_form").submit(function(e) {
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            acad_year: $("#e_acad_year").val(),
            status: $("#e_acad_status").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Academic_year/controller.php",
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
                    var hit = findRow(data.acad_cycle_id);
                    if (hit) {
                        hit.$tr.find('.col-acad-year').text(data.acad_year);
                        setRowStatus(hit, parseInt(data.status_val, 10) || 0);
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