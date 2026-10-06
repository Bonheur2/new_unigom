<?php
// Approval setup: define what each ROLE may do, and the approval ROUTE each
// form follows. Both are pure configuration - no role is named in any code.
require_once(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

$canManageSetup = can('can_manage_setup');

// every active form with the size of its route and the requests still in progress
$formsStmt = $conn->prepare("SELECT f.form_id, f.form_name,
                                    (SELECT COUNT(*) FROM tbl_approval_workflows w WHERE w.form_id = f.form_id) AS steps,
                                    (SELECT COUNT(*) FROM tbl_approval_requests r WHERE r.form_id = f.form_id AND r.status = 'pending') AS pending
                             FROM tbl_form_types f
                             WHERE f.status = 1
                             ORDER BY f.`rank` ASC, f.form_name ASC");
$formsStmt->execute();
$formTypes = $formsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Configuration des approbations</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item">Configuration des approbations</div>
            </div>
        </div>

        <?php if(!$canManageSetup): ?>
        <div class="alert alert-warning">
            <i class="fas fa-lock"></i> Vous pouvez consulter cette page, mais votre rôle n'a pas le droit de modifier la configuration des approbations.
        </div>
        <?php endif; ?>

        <!-- section switch -->
        <div class="as-tabs" role="tablist">
            <button type="button" class="as-tab active" role="tab" aria-selected="true" data-section="roles"><i class="fas fa-user-shield"></i> Permissions des rôles</button>
            <button type="button" class="as-tab" role="tab" aria-selected="false" data-section="routes"><i class="fas fa-route"></i> Circuits d'approbation</button>
        </div>

        <!-- ============ ROLE PERMISSIONS ============ -->
        <div class="as-section" id="section-roles">
            <div class="tv-card">
                <h4 class="tv-card-title" style="margin-bottom:6px">Ce que chaque rôle peut faire</h4>
                <p class="tv-card-sub" style="margin:0 0 12px">
                    Cochez ce qu'un rôle peut faire, puis cliquez sur « Enregistrer » sur sa ligne. <b>Visibilité</b> : candidats du département, de la faculté, du campus de l'utilisateur, ou de toute l'université.
                    Survolez un titre de colonne pour son détail.
                </p>
                <div class="table-responsive">
                    <table class="tv-table as-roles" id="roles_table">
                        <thead>
                            <tr>
                                <th>Rôle</th>
                                <th class="text-center" title="Peut ouvrir la file de revue et agir sur une étape">Réviser</th>
                                <th class="text-center" title="Peut choisir laquelle des options du candidat est accordée">Choisir l'option</th>
                                <th class="text-center" title="Peut ajouter ou retirer les cours que le candidat doit suivre">Attribuer les cours</th>
                                <th class="text-center" title="Peut clôturer une demande : cela crée la fiche étudiant">Approbation finale</th>
                                <th class="text-center" title="Peut émettre la facture">Facturer</th>
                                <th class="text-center" title="Peut renvoyer une demande rejetée dans le circuit">Rouvrir</th>
                                <th class="text-center" title="Peut configurer les frais, les documents et cette page">Configuration</th>
                                <th>Visibilité</th>
                                <?php if($canManageSetup): ?><th class="tv-right">Action</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="roles_body">
                            <tr><td colspan="10" class="text-center text-muted">Chargement...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============ APPROVAL ROUTES ============ -->
        <div class="as-section" id="section-routes" hidden>
            <?php if(count($formTypes) === 0): ?>
            <div class="tv-card tv-empty">Aucun formulaire actif. Activez d'abord un type de candidature.</div>
            <?php else: ?>
            <div class="row">
                <!-- forms -->
                <div class="col-12 col-lg-4">
                    <div class="tv-card">
                        <h4 class="tv-card-title">Formulaires</h4>
                        <p class="tv-card-sub" style="margin-top:-10px">Un formulaire sans étape n'a pas de phase d'approbation.</p>
                        <div class="tv-tree as-forms">
                            <?php foreach($formTypes as $ft): ?>
                            <div class="tv-node" data-form="<?php echo $ft['form_id']; ?>" data-pending="<?php echo (int)$ft['pending']; ?>" data-name="<?php echo htmlspecialchars($ft['form_name'], ENT_QUOTES); ?>">
                                <i class="fas fa-route"></i>
                                <span class="tv-name"><?php echo htmlspecialchars($ft['form_name']); ?></span>
                                <span class="tv-count" data-steps-form="<?php echo $ft['form_id']; ?>" title="Nombre d'étapes"><?php echo (int)$ft['steps']; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- route of the selected form -->
                <div class="col-12 col-lg-8">
                    <div class="tv-card">
                        <div class="tv-detail-head" style="margin-bottom:16px">
                            <h2><span id="route_title"></span> <span class="tv-badge">Circuit</span></h2>
                        </div>
                        <div class="alert alert-warning" id="route_locked" hidden>
                            <i class="fas fa-lock"></i> <span></span>
                        </div>
                        <p class="tv-card-sub">Les étapes s'exécutent dans l'ordre. Une étape <b>Facturation</b> se termine en émettant la facture au lieu d'approuver.</p>

                        <div class="table-responsive mb-4">
                            <table class="tv-table as-steps-table" id="steps_table">
                                <thead>
                                    <tr>
                                        <th style="width:70px">Étape</th>
                                        <th>Rôle</th>
                                        <th>Nom de l'étape</th>
                                        <th style="width:140px">Type</th>
                                        <?php if($canManageSetup): ?>
                                        <th style="width:110px" class="text-center">Ordre</th>
                                        <th style="width:70px" class="tv-right">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="steps_body"></tbody>
                            </table>
                        </div>

                        <?php if($canManageSetup): ?>
                        <div class="as-add">
                            <h6><i class="fas fa-plus"></i> Ajouter une étape à la fin du circuit</h6>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Rôle qui agit</label>
                                    <select class="form-control" id="new_role_id">
                                        <option value="">Chargement...</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Nom de l'étape</label>
                                    <input type="text" class="form-control" id="new_step_name" placeholder="Ex : Revue du département">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Type</label>
                                    <select class="form-control" id="new_step_type">
                                        <option value="approval">Approbation</option>
                                        <option value="invoice">Facturation</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="button" class="tv-btn tv-btn-accent w-100 justify-content-center" id="add_step"><span id="spinner_step"></span>Ajouter</button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="tv-card">
                        <h4 class="tv-card-title"><i class="far fa-file-pdf"></i> Lettre envoyée à l'approbation finale</h4>
                        <p class="tv-card-sub" style="margin-top:-10px">Le PDF est généré et envoyé par e-mail au candidat dès que la dernière étape approuve.</p>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Document</label>
                                <select class="form-control" id="set_completion_document"<?php echo $canManageSetup ? '' : ' disabled'; ?>>
                                    <option value="none">Aucun</option>
                                    <option value="admission_letter">Lettre d'admission</option>
                                    <option value="registration_proof">Attestation d'inscription</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nom du signataire</label>
                                <input type="text" class="form-control" id="set_signatory_name" placeholder="Imprimé sous la signature"<?php echo $canManageSetup ? '' : ' disabled'; ?>>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Titre du signataire</label>
                                <input type="text" class="form-control" id="set_signatory_title" placeholder="Ex : Secrétaire général académique"<?php echo $canManageSetup ? '' : ' disabled'; ?>>
                            </div>
                        </div>
                        <?php if($canManageSetup): ?>
                        <button type="button" class="tv-btn tv-btn-accent" id="save_form_setting">Enregistrer</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<style>
/* underlined tabs */
.tv-page .as-tabs{display:flex;flex-wrap:wrap;gap:6px;border-bottom:1px solid var(--tv-border);margin-bottom:24px}
.tv-page .as-tab{background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-1px;padding:12px 18px;font-size:15px;font-weight:600;color:var(--tv-muted);cursor:pointer;white-space:nowrap;transition:color .15s,border-color .15s}
.tv-page .as-tab i{margin-right:8px}
.tv-page .as-tab:hover{color:var(--tv-accent)}
.tv-page .as-tab.active{color:var(--tv-accent);border-bottom-color:var(--tv-accent)}
.tv-page .as-tab:focus{outline:none}
.tv-page .as-tab:focus-visible{outline:2px solid var(--tv-accent);outline-offset:-2px}
/* compact permissions grid: every role fits on one screen */
.tv-page .as-roles th{padding:12px 12px;font-size:12px;line-height:1.25;white-space:normal;vertical-align:bottom}
/* bold, dark headers on this page's tables */
.tv-page .as-roles thead th,.tv-page .as-steps-table thead th{font-weight:800;color:var(--tv-accent)}
/* role names in the site colour */
.tv-page .as-roles tbody td:first-child{color:var(--tv-accent);font-weight:700}
.tv-page .as-roles td{padding:9px 12px;font-size:14px}
.tv-page .as-roles td:first-child{font-weight:600;white-space:nowrap}
.tv-page .as-roles input.cap{width:18px;height:18px;margin:0;vertical-align:middle;cursor:pointer;accent-color:var(--tv-accent)}
.tv-page .as-roles select.cap_scope{height:32px;padding:0 8px;font-size:13px;min-width:130px;border-radius:0}
.tv-page .as-roles tbody tr:hover td{background:var(--tv-bg)}
/* changed, not yet saved */
.tv-page .as-roles tr.as-dirty td,.tv-page .as-roles tr.as-dirty:hover td{background:#fff4d6}
.tv-page .as-roles tr.as-dirty td:first-child{background:#fff4d6;box-shadow:inset 4px 0 0 #e0a100}
.tv-page .as-roles .save_caps{padding:6px 14px;font-size:13px;border-radius:0}
.tv-page .as-forms .tv-node{align-items:flex-start;font-size:14px}
.tv-page .as-forms .tv-node .tv-name{white-space:normal;line-height:1.35}
.tv-page .as-forms .tv-node i{margin-top:3px}
.tv-page .tv-icon-btn:disabled{opacity:.3;cursor:not-allowed}
.tv-page .as-num{width:32px;height:32px;border-radius:50%;background:var(--tv-accent);color:#fff;font-weight:700;font-size:13px;display:inline-flex;align-items:center;justify-content:center}
.tv-page .as-steps-table td,.tv-page .as-steps-table th{padding:12px 14px}
.tv-page .as-add{background:var(--tv-bg);border:1px solid var(--tv-border);border-radius:4px;padding:16px 16px 4px}
.tv-page .as-add h6{font-size:14px;font-weight:600;margin-bottom:12px}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var CAN_MANAGE = <?php echo $canManageSetup ? 'true' : 'false'; ?>;
var ALL_ROLES = [];
var CURRENT_FORM = null;
var AS_STORE_KEY = 'approval_setup_view';
var SCOPE_LABELS = { department: 'Département', faculty: 'Faculté', campus: 'Campus', university: 'Université' };

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}

function showSection(name){
    $('.as-tab').removeClass('active').attr('aria-selected', 'false')
        .filter('[data-section="' + name + '"]').addClass('active').attr('aria-selected', 'true');
    $('.as-section').attr('hidden', true);
    $('#section-' + name).attr('hidden', false);
    try { localStorage.setItem(AS_STORE_KEY, JSON.stringify({ section: name, form: CURRENT_FORM })); } catch(e) {}
}

function selectForm(form_id){
    var $node = $('.as-forms .tv-node[data-form="' + form_id + '"]');
    if($node.length === 0) return false;
    CURRENT_FORM = String(form_id);
    $('.as-forms .tv-node').removeClass('active');
    $node.addClass('active');
    $('#route_title').text($node.data('name'));
    var pending = parseInt($node.data('pending'), 10) || 0;
    $('#route_locked').attr('hidden', pending === 0).find('span').text(
        pending + ' demande' + (pending > 1 ? 's' : '') + ' de ce formulaire ' + (pending > 1 ? 'sont' : 'est') + ' encore en cours : '
        + "les étapes ne peuvent pas être déplacées ni supprimées tant qu'elles ne sont pas terminées ou annulées."
    );
    loadSteps(form_id);
    loadFormSetting(form_id);
    try { localStorage.setItem(AS_STORE_KEY, JSON.stringify({ section: 'routes', form: CURRENT_FORM })); } catch(e) {}
    return true;
}

$(document).ready(function(){
    loadRoles();

    $('.as-tab').on('click', function(){ showSection($(this).data('section')); });
    $(document).on('click', '.as-forms .tv-node', function(){ selectForm($(this).data('form')); });

    // restore the last view (section + form), otherwise permissions + first form
    var saved = {};
    try { saved = JSON.parse(localStorage.getItem(AS_STORE_KEY) || '{}') || {}; } catch(e) {}
    if(!(saved.form && selectForm(saved.form))){
        var first = $('.as-forms .tv-node').first().data('form');
        if(first) selectForm(first);
    }
    showSection(saved.section === 'routes' ? 'routes' : 'roles');

    $('#add_step').on('click', function(){
        var form_id = CURRENT_FORM;
        var role_id = $('#new_role_id').val();
        var name    = $('#new_step_name').val().trim();

        if(!form_id){ pop_wrong("Choisissez d'abord un formulaire"); return; }
        if(!role_id){ pop_wrong("Choisissez le rôle qui agit à cette étape"); return; }
        if(!name){ pop_wrong("Donnez un nom à l'étape"); return; }

        $('#spinner_step').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: {
                action: 'save_step', form_id: form_id, role_id: role_id,
                step_name: name, step_type: $('#new_step_type').val()
            },
            success: function(d){
                $('#spinner_step').html("");
                if(d.status == 200){
                    $('#new_step_name').val('');
                    pop_up_success(d.message);
                    loadSteps(form_id);
                } else { pop_wrong(d.message); }
            },
            error: function(){ $('#spinner_step').html(""); pop_wrong("Impossible d'ajouter l'étape"); }
        });
    });

    // delete / move are delegated - the steps are rebuilt on every load
    $(document).on('click', '.del_step', function(){
        var id = $(this).data('id');
        swal({
            title: "Supprimer cette étape ?",
            text: "Le circuit sera renuméroté automatiquement.",
            icon: "warning", buttons: ["Annuler", "Supprimer"], dangerMode: true
        }).then(function(ok){
            if(!ok) return;
            $.ajax({
                url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
                data: { action: 'delete_step', workflow_id: id },
                success: function(d){
                    if(d.status == 200){ pop_up_success(d.message); loadSteps(CURRENT_FORM); }
                    else { pop_wrong(d.message); }
                },
                error: function(){ pop_wrong("Impossible de supprimer l'étape"); }
            });
        });
    });

    $(document).on('click', '.move_step', function(){
        var id  = $(this).data('id');
        var dir = $(this).data('dir');
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: { action: 'move_step', workflow_id: id, direction: dir },
            success: function(d){
                if(d.status == 200){ loadSteps(CURRENT_FORM); }
                else { pop_wrong(d.message); }
            },
            error: function(){ pop_wrong("Impossible de déplacer l'étape"); }
        });
    });

    $('#save_form_setting').on('click', function(){
        var form_id = CURRENT_FORM;
        if(!form_id){ pop_wrong("Choisissez d'abord un formulaire"); return; }
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: { action: 'save_form_setting', form_id: form_id,
                    completion_document: $('#set_completion_document').val(),
                    signatory_name: $('#set_signatory_name').val(),
                    signatory_title: $('#set_signatory_title').val() },
            success: function(d){ if(d.status == 200){ pop_up_success(d.message); } else { pop_wrong(d.message); } },
            error: function(){ pop_wrong("Impossible d'enregistrer les paramètres"); }
        });
    });

    // highlight a role only while it differs from what is saved (undoing a change clears it)
    $(document).on('change', '#roles_body input.cap, #roles_body .cap_scope', function(){
        var $row = $(this).closest('tr');
        $row.toggleClass('as-dirty', rowState($row) !== $row.data('saved'));
    });

    $(document).on('click', '.save_caps', function(){
        var $row = $(this).closest('tr');
        var payload = { action: 'save_role_caps', role_id: $row.data('role-id'), scope_level: $row.find('.cap_scope').val() };
        $row.find('input.cap').each(function(){
            payload[$(this).data('cap')] = $(this).is(':checked') ? '1' : '0';
        });

        var $btn = $(this).attr('disabled', true);
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: payload,
            success: function(d){
                $btn.attr('disabled', false);
                if(d.status == 200){ $row.data('saved', rowState($row)).removeClass('as-dirty'); pop_up_success(d.message); }
                else { pop_wrong(d.message); }
            },
            error: function(){ $btn.attr('disabled', false); pop_wrong("Impossible d'enregistrer les permissions"); }
        });
    });

    // do not lose unsaved permission changes by leaving the page
    window.addEventListener('beforeunload', function(e){
        if($('#roles_body tr.as-dirty').length){ e.preventDefault(); e.returnValue = ''; }
    });
});

function rowState($row){
    return $row.find('input.cap').map(function(){ return this.checked ? '1' : '0'; }).get().join('') + '|' + $row.find('.cap_scope').val();
}

function loadRoles(){
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_roles' },
        success: function(d){
            if(d.status != 200){ pop_wrong(d.message || "Impossible de charger les rôles"); return; }
            ALL_ROLES = d.data;

            // role picker for the route builder
            var opts = "<option value=''>Choisir un rôle</option>";
            $.each(d.data, function(i, r){
                opts += "<option value='" + r.role_id + "'>" + escapeHtml(r.role) + "</option>";
            });
            $('#new_role_id').html(opts);

            // permissions grid
            if(!d.data.length){
                $('#roles_body').html('<tr><td colspan="10" class="text-center text-muted">Aucun rôle trouvé.</td></tr>');
                return;
            }

            var caps = ['can_review_applications','can_select_choice','can_assign_courses','can_final_approve',
                        'can_invoice','can_reopen_rejected','can_manage_setup'];
            var html = '';

            $.each(d.data, function(i, r){
                html += '<tr data-role-id="' + r.role_id + '">';
                html += '<td>' + escapeHtml(r.role) + '</td>';
                $.each(caps, function(j, c){
                    html += '<td class="text-center"><input type="checkbox" class="cap" data-cap="' + c + '"'
                          + (r[c] == 1 ? ' checked' : '')
                          + (CAN_MANAGE ? '' : ' disabled') + '></td>';
                });
                html += '<td><select class="form-control form-control-sm cap_scope"' + (CAN_MANAGE ? '' : ' disabled') + '>';
                $.each(SCOPE_LABELS, function(val, label){
                    html += '<option value="' + val + '"' + (r.scope_level === val ? ' selected' : '') + '>' + label + '</option>';
                });
                html += '</select></td>';
                if(CAN_MANAGE){
                    html += '<td class="tv-right"><button type="button" class="tv-btn tv-btn-accent save_caps">Enregistrer</button></td>';
                }
                html += '</tr>';
            });
            $('#roles_body').html(html);
            $('#roles_body tr[data-role-id]').each(function(){ $(this).data('saved', rowState($(this))); });
        },
        error: function(){ pop_wrong("Impossible de charger les rôles"); }
    });
}

function loadFormSetting(form_id){
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_form_setting', form_id: form_id },
        success: function(d){
            if(d.status != 200){ return; }
            $('#set_completion_document').val(d.data.completion_document || 'none');
            $('#set_signatory_name').val(d.data.signatory_name || '');
            $('#set_signatory_title').val(d.data.signatory_title || '');
        }
    });
}

function loadSteps(form_id){
    $('#steps_body').html('<tr><td colspan="' + (CAN_MANAGE ? 6 : 4) + '" class="text-center text-muted">Chargement...</td></tr>');
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_steps', form_id: form_id },
        success: function(d){
            if(d.status != 200){ pop_wrong(d.message || "Impossible de charger le circuit"); return; }
            $('[data-steps-form="' + form_id + '"]').text(d.data.length);
            if(!d.data.length){
                $('#steps_body').html('<tr><td colspan="' + (CAN_MANAGE ? 6 : 4) + '" class="text-center text-muted">Aucune étape : ce formulaire n\'a pas de phase d\'approbation.</td></tr>');
                return;
            }

            var html = '';
            $.each(d.data, function(i, s){
                var last = (i === d.data.length - 1);
                var invoice = s.step_type === 'invoice';
                html += '<tr>';
                html += '<td><span class="as-num">' + s.step_order + '</span></td>';
                html += '<td>' + (s.role_name ? escapeHtml(s.role_name) : '<i class="text-danger">rôle supprimé</i>') + '</td>';
                html += '<td>' + escapeHtml(s.step_name) + '</td>';
                html += '<td>' + (invoice ? '<span class="tv-tag amber">Facturation</span>' : '<span class="tv-tag blue">Approbation</span>') + '</td>';
                if(CAN_MANAGE){
                    html += '<td class="text-center">'
                          + '<button type="button" class="tv-icon-btn move_step" data-id="' + s.workflow_id + '" data-dir="up" title="Monter"' + (i === 0 ? ' disabled' : '') + '><i class="fas fa-arrow-up"></i></button> '
                          + '<button type="button" class="tv-icon-btn move_step" data-id="' + s.workflow_id + '" data-dir="down" title="Descendre"' + (last ? ' disabled' : '') + '><i class="fas fa-arrow-down"></i></button>'
                          + '</td>';
                    html += '<td class="tv-right"><button type="button" class="tv-icon-btn danger del_step" data-id="' + s.workflow_id + '" title="Supprimer"><i class="far fa-trash-alt"></i></button></td>';
                }
                html += '</tr>';
            });
            $('#steps_body').html(html);
        },
        error: function(){ $('#steps_body').html('<tr><td colspan="' + (CAN_MANAGE ? 6 : 4) + '" class="text-center text-danger">Impossible de charger le circuit.</td></tr>'); }
    });
}

function pop_wrong(feedback) {
    iziToast.warning({ title: 'Erreur', message: feedback, position: 'topCenter' });
}
function pop_up_success(feedback) {
    iziToast.success({ title: 'Info', message: feedback, position: 'topCenter' });
}
</script>
