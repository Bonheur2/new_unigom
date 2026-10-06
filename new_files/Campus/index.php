<?php
    $canManage = ($role_id == 18 || $role_id == 2);

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

    $sql = $conn->prepare("SELECT tbl_campus.camp_id, tbl_campus.camp_full_name, tbl_campus.camp_city, tbl_campus.camp_yor, tbl_campus.camp_active, tbl_campus.camp_comments, tbl_campus.university_id, tbl_university.full_name AS university_name
                           FROM tbl_campus
                           LEFT JOIN tbl_university ON tbl_university.id = tbl_campus.university_id
                           ORDER BY tbl_campus.camp_id DESC");
    $sql->execute();
    $campuses = $sql->fetchAll();
    $activeCount = 0;
    foreach($campuses as $c){ if($c['camp_active'] == 1) $activeCount++; }

    $sql_univ = $conn->prepare("SELECT id, full_name FROM tbl_university ORDER BY full_name ASC");
    $sql_univ->execute();
    $universities = $sql_univ->fetchAll();
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Campus</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Campus</a></div>
            </div>
        </div>

        <!-- Formulaire nouveau campus -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau campus</h4>
                <form id="save_campus" action="save_campus" method="POST">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Université</label>
                            <select class="form-control select2" style="width:100%" id="university_id" required>
                                <option value="">Choisir une université</option>
                                <?php foreach($universities as $univ): ?>
                                <option value="<?php echo $univ['id']; ?>"><?php echo htmlspecialchars($univ['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nom complet du campus</label>
                            <input type="text" class="form-control" id="camp_full_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Ville</label>
                            <input type="text" class="form-control" id="camp_city" placeholder="Ville">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Année d'enregistrement</label>
                            <input type="text" class="form-control" id="camp_yor" placeholder="Ex : 2010">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="camp_active">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Commentaires</label>
                            <textarea class="form-control" id="camp_comments" placeholder="Commentaires" rows="2"></textarea>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Campus enregistrés <span class="tv-pill-count ml-2" id="camp-total"><?php echo count($campuses); ?></span></h4>
                <div class="tv-actions">
                    <div class="tv-filter">
                        <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Tous</button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actifs <span id="camp-active"><?php echo $activeCount; ?></span></button>
                        <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactifs <span id="camp-inactive"><?php echo count($campuses) - $activeCount; ?></span></button>
                    </div>
                    <?php if($canManage): ?>
                    <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouveau campus</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="tv-table campus_table">
                    <thead>
                    <tr>
                        <th>Campus</th>
                        <th>Université</th>
                        <th>Ville</th>
                        <th>Année</th>
                        <th>Commentaires</th>
                        <th>Statut</th>
                        <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($campuses as $row): ?>
                    <tr data-row-id="<?php echo $row['camp_id']; ?>" data-status="<?php echo $row['camp_active'] == 1 ? 1 : 0; ?>">
                        <td><div class="tv-member"><span class="tv-avatar"><?php echo htmlspecialchars(tv_initials($row['camp_full_name'])); ?></span><span class="col-full-name"><?php echo htmlspecialchars($row['camp_full_name']); ?></span></div></td>
                        <td class="col-university"><?php echo htmlspecialchars($row['university_name']); ?></td>
                        <td class="col-city"><?php echo htmlspecialchars($row['camp_city']); ?></td>
                        <td class="col-yor"><?php echo htmlspecialchars($row['camp_yor']); ?></td>
                        <td class="col-comments"><?php echo htmlspecialchars($row['camp_comments']); ?></td>
                        <td><span class="tv-tag <?php echo $row['camp_active'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $row['camp_active'] == 1 ? 'Actif' : 'Inactif'; ?></span></td>
                        <?php if($canManage): ?>
                        <td class="tv-right">
                            <div class="tv-row-actions">
                                <button type="button" data-id="<?php echo $row['camp_id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                    <span id="spinner4_<?php echo $row['camp_id']; ?>"></span><i class="fas fa-pen"></i>
                                </button>
                                <label class="custom-switch" title="Activer / désactiver">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['camp_id']; ?>" <?php echo $row['camp_active']==1 ? 'checked' : ''; ?>>
                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['camp_id']; ?>"></span>
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
                            <label>Université</label>
                            <select class="form-control select2" style="width:100%" id="e_university_id" required>
                                <option value="">Choisir une université</option>
                                <?php foreach($universities as $univ): ?>
                                <option value="<?php echo $univ['id']; ?>"><?php echo htmlspecialchars($univ['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom complet du campus</label>
                            <input type="text" class="form-control" id="e_camp_full_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group">
                            <label>Ville</label>
                            <input type="text" class="form-control" id="e_camp_city" placeholder="Ville">
                        </div>
                        <div class="form-group">
                            <label>Année d'enregistrement</label>
                            <input type="text" class="form-control" id="e_camp_yor" placeholder="Ex : 2010">
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control select2" style="width:100%" id="e_camp_active">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Commentaires</label>
                            <textarea class="form-control" id="e_camp_comments" placeholder="Commentaires" rows="2"></textarea>
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
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}

function initials(name){
    return (name || '').trim().split(/\s+/).slice(0, 2).map(function(w){ return w.charAt(0).toUpperCase(); }).join('');
}

$(document).ready(function(){
    // status chips filter through DataTables so paging stays correct
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        if(!$(settings.nTable).hasClass('campus_table')) return true;
        var filter = $('.tv-status-filter.active').data('filter');
        if(filter === undefined || filter === 'all') return true;
        return String($(settings.aoData[dataIndex].nTr).attr('data-status')) === String(filter);
    });

    var campusDt = $('.campus_table').DataTable({
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
            "emptyTable": "Aucun campus enregistré",
            "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
        }
    });

    // counters in the card header, including rows on other pages
    function refreshCounts(){
        var $rows = $(campusDt.rows().nodes());
        var active = $rows.filter('[data-status="1"]').length;
        $('#camp-total').text($rows.length);
        $('#camp-active').text(active);
        $('#camp-inactive').text($rows.length - active);
    }

    function findRow(camp_id){
        var found = null;
        campusDt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(camp_id)) found = { row: this, $tr: $(this.node()) };
        });
        return found;
    }

    function statusHtml(active){
        return '<span class="tv-tag ' + (active ? 'green' : 'red') + ' tv-status-tag">' + (active ? 'Actif' : 'Inactif') + '</span>';
    }

    $(document).on('click', '.tv-status-filter', function(){
        $('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        campusDt.draw(false);
    });

    // open / close the new campus form
    $('#tv-new-btn').on('click', function(){
        $('#mycard-collapse').collapse('toggle');
    });

    // build a table row for a campus and add it to the DataTable
    function addCampusRow(row){
        var active = String(row.camp_active) === '1';
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<td class="tv-right"><div class="tv-row-actions">'
                + '<button type="button" data-id="'+row.camp_id+'" class="tv-icon-btn edit" title="Modifier">'
                + '<span id="spinner4_'+row.camp_id+'"></span><i class="fas fa-pen"></i></button>'
                + '<label class="custom-switch" title="Activer / désactiver">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.camp_id+'"' + (active ? ' checked' : '') + '>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.camp_id+'"></span></label></div></td>';
        }
        var $tr = $('<tr data-row-id="'+row.camp_id+'" data-status="'+(active ? 1 : 0)+'">'
            + '<td><div class="tv-member"><span class="tv-avatar">'+escapeHtml(initials(row.camp_full_name))+'</span><span class="col-full-name">'+escapeHtml(row.camp_full_name)+'</span></div></td>'
            + '<td class="col-university">'+escapeHtml(row.university_name)+'</td>'
            + '<td class="col-city">'+escapeHtml(row.camp_city)+'</td>'
            + '<td class="col-yor">'+escapeHtml(row.camp_yor)+'</td>'
            + '<td class="col-comments">'+escapeHtml(row.camp_comments)+'</td>'
            + '<td>'+statusHtml(active)+'</td>'
            + actionsHtml
            + '</tr>');
        campusDt.row.add($tr[0]).draw(false);
        refreshCounts();
    }

    //save campus
    $("#save_campus").submit(function(e){
        e.preventDefault();

        var formData = {
            university_id: $("#university_id").val(),
            camp_full_name: $("#camp_full_name").val(),
            camp_city: $("#camp_city").val(),
            camp_yor: $("#camp_yor").val(),
            camp_active: $("#camp_active").val(),
            camp_comments: $("#camp_comments").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Campus/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    $('#save_campus')[0].reset();
                    $('#camp_active').val('1').trigger('change');
                    pop_up_success(data.message);
                    addCampusRow(data);
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

    // delete/toggle campus
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Êtes-vous sûr ?",
            text: "Vous êtes sur le point de modifier le statut de ce campus.",
            icon: "warning",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Campus/controller.php",
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
                            $tr.attr('data-status', isActive ? 1 : 0);
                            $tr.find('.tv-status-tag').replaceWith(statusHtml(isActive));
                            campusDt.row($tr[0]).invalidate('dom').draw(false);
                            refreshCounts();
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
        var getData = {
            id: data_id,
            action: 'view'
        };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Campus/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_university_id").val(data.university_id).trigger('change');
                $("#e_camp_full_name").val(data.camp_full_name);
                $("#e_camp_city").val(data.camp_city);
                $("#e_camp_yor").val(data.camp_yor);
                $("#e_camp_active").val(data.camp_active).trigger('change');
                $("#e_camp_comments").val(data.camp_comments);
                $("#f_name").text(data.camp_full_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update campus
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            university_id: $("#e_university_id").val(),
            camp_full_name: $("#e_camp_full_name").val(),
            camp_city: $("#e_camp_city").val(),
            camp_yor: $("#e_camp_yor").val(),
            camp_active: $("#e_camp_active").val(),
            camp_comments: $("#e_camp_comments").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Campus/controller.php",
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
                    var hit = findRow(data.camp_id);
                    if(hit){
                        var active = String(data.camp_active) === '1';
                        hit.$tr.attr('data-status', active ? 1 : 0);
                        hit.$tr.find('.col-full-name').text(data.camp_full_name);
                        hit.$tr.find('.tv-avatar').text(initials(data.camp_full_name));
                        hit.$tr.find('.col-university').text(data.university_name || '');
                        hit.$tr.find('.col-city').text(data.camp_city || '');
                        hit.$tr.find('.col-yor').text(data.camp_yor || '');
                        hit.$tr.find('.col-comments').text(data.camp_comments || '');
                        hit.$tr.find('.tv-status-tag').replaceWith(statusHtml(active));
                        hit.$tr.find('.del').prop('checked', active);
                        hit.row.invalidate('dom').draw(false);
                        refreshCounts();
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
