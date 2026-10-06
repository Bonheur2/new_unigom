<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    $sql = $conn->prepare("SELECT f.form_id, f.form_name, f.description, f.status,
                                  (SELECT COUNT(*) FROM tbl_formtype_document d WHERE d.form_id = f.form_id AND d.status = 1) AS docs,
                                  (SELECT COUNT(*) FROM tbl_applicants a WHERE a.form_id = f.form_id) AS applicants
                           FROM tbl_form_types f
                           ORDER BY f.`rank` ASC, f.form_id ASC");
    $sql->execute();
    $forms = $sql->fetchAll();

    $activeCount = 0;
    $docTotal = 0;
    $appTotal = 0;
    foreach($forms as $f){
        if($f['status'] == 1) $activeCount++;
        $docTotal += (int)$f['docs'];
        $appTotal += (int)$f['applicants'];
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Types de candidature</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Types de candidature</a></div>
            </div>
        </div>

        <!-- Formulaire nouveau type de candidature -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau formulaire de candidature</h4>
                <form id="save_form_type" action="save_form_type" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Nom du formulaire</label>
                            <input type="text" class="form-control" id="form_name" placeholder="Ex : Bulletin d'inscription en Master" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="f_status">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description</label>
                            <textarea class="form-control" id="description" placeholder="À qui s'adresse ce formulaire ?" rows="2"></textarea>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- counters -->
        <div class="tv-kpis">
            <div class="tv-kpi">
                <div class="tv-stat-icon primary"><i class="fas fa-file-alt"></i></div>
                <div>
                    <b><span id="ft-active"><?php echo $activeCount; ?></span> / <span id="ft-total"><?php echo count($forms); ?></span></b>
                    <span class="tv-kpi-label">Formulaires actifs</span>
                    <span class="tv-kpi-sub">Seuls les formulaires actifs sont proposés aux candidats</span>
                </div>
            </div>
            <a class="tv-kpi" href="edu?mis=appdc">
                <div class="tv-stat-icon blue"><i class="fas fa-paperclip"></i></div>
                <div>
                    <b><?php echo $docTotal; ?></b>
                    <span class="tv-kpi-label">Documents demandés</span>
                    <span class="tv-kpi-sub">Tous formulaires confondus</span>
                </div>
            </a>
            <a class="tv-kpi" href="edu?mis=sbtdap">
                <div class="tv-stat-icon green"><i class="fas fa-file-signature"></i></div>
                <div>
                    <b><?php echo $appTotal; ?></b>
                    <span class="tv-kpi-label">Candidatures reçues</span>
                    <span class="tv-kpi-sub">Tous formulaires confondus</span>
                </div>
            </a>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Formulaires de candidature</h4>
                <div class="tv-actions">
                    <div class="tv-filter">
                        <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Tous</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actifs</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactifs</button>
                    </div>
                    <?php if($canManage): ?>
                    <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouveau formulaire</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php if($canManage): ?>
            <p class="tv-card-sub" style="margin:-8px 0 16px"><i class="fas fa-grip-vertical"></i> Glissez une ligne par la poignée pour changer l'ordre d'affichage aux candidats. Le nouvel ordre est enregistré automatiquement.</p>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="tv-table form_type_table">
                    <thead>
                    <tr>
                        <?php if($canManage): ?><th style="width:36px"></th><?php endif; ?>
                        <th style="width:56px">Ordre</th>
                        <th>Formulaire</th>
                        <th>Documents</th>
                        <th>Candidatures</th>
                        <th>Statut</th>
                        <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody id="form_type_tbody">
                    <?php foreach($forms as $i => $row): ?>
                    <tr data-row-id="<?php echo $row['form_id']; ?>" data-status="<?php echo $row['status'] == 1 ? 1 : 0; ?>">
                        <?php if($canManage): ?><td class="drag-handle" title="Glisser pour réordonner"><i class="fas fa-grip-vertical"></i></td><?php endif; ?>
                        <td><span class="tv-avatar col-rank-num"><?php echo $i + 1; ?></span></td>
                        <td>
                            <div class="col-form-name ft-name"><?php echo htmlspecialchars($row['form_name']); ?></div>
                            <div class="col-description ft-desc"><?php echo htmlspecialchars($row['description']); ?></div>
                        </td>
                        <td><span class="tv-pill-count"><?php echo (int)$row['docs']; ?></span></td>
                        <td><?php echo (int)$row['applicants']; ?></td>
                        <td><span class="tv-tag <?php echo $row['status'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $row['status'] == 1 ? 'Actif' : 'Inactif'; ?></span></td>
                        <?php if($canManage): ?>
                        <td class="tv-right">
                            <div class="tv-row-actions">
                                <button type="button" data-id="<?php echo $row['form_id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                    <span id="spinner4_<?php echo $row['form_id']; ?>"></span><i class="fas fa-pen"></i>
                                </button>
                                <label class="custom-switch" title="Activer / désactiver">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['form_id']; ?>" <?php echo $row['status']==1 ? 'checked' : ''; ?>>
                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['form_id']; ?>"></span>
                                </label>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="tv-empty" id="ft-empty" style="<?php echo count($forms) ? 'display:none' : ''; ?>">Aucun formulaire pour ce filtre.</div>
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
                            <label>Nom du formulaire</label>
                            <input type="text" class="form-control" id="e_form_name" placeholder="Ex : Bulletin d'inscription en Master" required>
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="e_f_status">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="e_description" placeholder="À qui s'adresse ce formulaire ?" rows="3"></textarea>
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
.tv-page .form_type_table .drag-handle{cursor:grab;color:var(--tv-muted);text-align:center;width:36px}
.tv-page .form_type_table .drag-handle:active{cursor:grabbing}
.tv-page .form_type_table .ft-name{font-weight:600;font-size:14px}
.tv-page .form_type_table .ft-desc{color:var(--tv-muted);font-size:13px;margin-top:2px}
.tv-page .form_type_table .ft-desc:empty{display:none}
.tv-page .form_type_table tr.sortable-ghost td{background:var(--tv-accent-soft)}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

<script>
var canManageFormTypes = <?php echo $canManage ? 'true' : 'false'; ?>;

function statusHtml(active){
    return '<span class="tv-tag ' + (active ? 'green' : 'red') + ' tv-status-tag">' + (active ? 'Actif' : 'Inactif') + '</span>';
}

$(document).ready(function(){

    // renumber the "Ordre" column to match current row order
    function renumberRows(){
        $('#form_type_tbody tr').each(function(index){
            $(this).find('.col-rank-num').text(index + 1);
        });
    }

    function refreshCounts(){
        var $rows = $('#form_type_tbody tr');
        $('#ft-total').text($rows.length);
        $('#ft-active').text($rows.filter('[data-status="1"]').length);
    }

    function applyFilter(){
        var filter = $('.tv-status-filter.active').data('filter');
        var shown = 0;
        $('#form_type_tbody tr').each(function(){
            var ok = filter === 'all' || String($(this).attr('data-status')) === String(filter);
            $(this).toggle(ok);
            if(ok) shown++;
        });
        $('#ft-empty').toggle(shown === 0);
    }

    $(document).on('click', '.tv-status-filter', function(){
        $('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        applyFilter();
    });

    // open / close the new form
    $('#tv-new-btn').on('click', function(){
        $('#mycard-collapse').collapse('toggle');
    });

    // drag-and-drop reordering (hidden rows keep their place in the saved order)
    if(canManageFormTypes && document.getElementById('form_type_tbody')){
        Sortable.create(document.getElementById('form_type_tbody'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(){
                renumberRows();

                var order = $('#form_type_tbody tr').map(function(){
                    return $(this).data('row-id');
                }).get();

                $.ajax({
                    url: "../new_files/Form_Types/controller.php",
                    type: "POST",
                    data: { order: order, action: 'reorder' },
                    dataType: "JSON",
                    success: function(data){
                        if(data.status==200){
                            pop_up_success(data.message);
                        } else {
                            pop_wrong(data.message);
                        }
                    },
                    error: function(){
                        pop_wrong("Une erreur s'est produite lors de l'enregistrement du nouvel ordre !");
                    }
                });
            }
        });
    }

    // build a table row for a form type and append it to the table
    function addFormTypeRow(row){
        var active = String(row.status_val) === '1';
        var $tr = $('<tr data-row-id="'+row.form_id+'" data-status="'+(active ? 1 : 0)+'">'
            + (canManageFormTypes ? '<td class="drag-handle" title="Glisser pour réordonner"><i class="fas fa-grip-vertical"></i></td>' : '')
            + '<td><span class="tv-avatar col-rank-num"></span></td>'
            + '<td><div class="col-form-name ft-name"></div><div class="col-description ft-desc"></div></td>'
            + '<td><span class="tv-pill-count">0</span></td>'
            + '<td>0</td>'
            + '<td>'+statusHtml(active)+'</td>'
            + (canManageFormTypes ? '<td class="tv-right"><div class="tv-row-actions">'
                + '<button type="button" data-id="'+row.form_id+'" class="tv-icon-btn edit" title="Modifier">'
                + '<span id="spinner4_'+row.form_id+'"></span><i class="fas fa-pen"></i></button>'
                + '<label class="custom-switch" title="Activer / désactiver">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.form_id+'"'+(active ? ' checked' : '')+'>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.form_id+'"></span></label></div></td>' : '')
            + '</tr>');
        $tr.find('.col-form-name').text(row.form_name);
        $tr.find('.col-description').text(row.description || '');
        $('#form_type_tbody').append($tr);
        renumberRows();
        refreshCounts();
        applyFilter();
    }

    //save form type
    $("#save_form_type").submit(function(e){
        e.preventDefault();

        var formData = {
            form_name: $("#form_name").val(),
            description: $("#description").val(),
            status: $("#f_status").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Form_Types/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    $('#save_form_type')[0].reset();
                    $('#f_status').val('1').trigger('change');
                    pop_up_success(data.message);
                    addFormTypeRow(data);
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

    // activate / deactivate form type
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        swal({
            title: "Êtes-vous sûr ?",
            text: $checkbox.prop('checked') ? "Ce formulaire sera de nouveau proposé aux candidats." : "Ce formulaire ne sera plus proposé aux candidats.",
            icon: "warning",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: true,
        }).then((willChange) => {
            if (willChange) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Form_Types/controller.php",
                    data: { id: data_id, action: 'delete' },
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==500){
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        }
                        else if(data.status==200){
                            var active = $checkbox.prop('checked');
                            var $tr = $checkbox.closest('tr');
                            $tr.attr('data-status', active ? 1 : 0);
                            $tr.find('.tv-status-tag').replaceWith(statusHtml(active));
                            refreshCounts();
                            applyFilter();
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
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Form_Types/controller.php",
            data: { id: data_id, action: 'view' },
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_form_name").val(data.form_name);
                $("#e_description").val(data.description);
                $("#e_f_status").val(String(data.status)).trigger('change');
                $("#f_name").text(data.form_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update form type
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            form_name: $("#e_form_name").val(),
            description: $("#e_description").val(),
            status: $("#e_f_status").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Form_Types/controller.php",
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
                    var active = String(data.status_val) === '1';
                    var $row = $("#form_type_tbody tr[data-row-id='"+data.form_id+"']");
                    $row.find('.col-form-name').text(data.form_name);
                    $row.find('.col-description').text(data.description || '');
                    $row.attr('data-status', active ? 1 : 0);
                    $row.find('.tv-status-tag').replaceWith(statusHtml(active));
                    $row.find('.del').prop('checked', active);
                    refreshCounts();
                    applyFilter();
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
