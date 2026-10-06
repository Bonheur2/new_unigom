<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // project root as a URL path ("" live, "/academic" under XAMPP) so stored template paths resolve in both places
    $tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
    $tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));
    $fileUrl = function($path) use ($tv_base_url){
        return (strpos((string)$path, '/') === 0 ? $tv_base_url : '').$path;
    };

    $formatLabels = ['pdf' => 'PDF', 'doc' => 'DOC', 'docx' => 'DOCX', 'jpg' => 'JPG', 'jpeg' => 'JPEG', 'png' => 'PNG'];

    // every form type with its documents
    $sql_forms = $conn->prepare("SELECT form_id, form_name, description, status FROM tbl_form_types ORDER BY `rank` ASC, form_name ASC");
    $sql_forms->execute();
    $forms = $sql_forms->fetchAll(PDO::FETCH_ASSOC);

    $docTotal = 0;
    foreach($forms as $i => $f){
        $sql = $conn->prepare("SELECT * FROM tbl_formtype_document WHERE form_id = :form_id ORDER BY status DESC, document_name ASC");
        $sql->execute([':form_id' => $f['form_id']]);
        $docs = $sql->fetchAll(PDO::FETCH_ASSOC);
        $active = 0; $required = 0; $intl = 0;
        foreach($docs as $d){
            if($d['status'] == 1){
                $active++;
                if($d['is_required'] == 1) $required++;
                if($d['international_required'] == 1) $intl++;
            }
        }
        $forms[$i]['docs'] = $docs;
        $forms[$i]['active'] = $active;
        $forms[$i]['required'] = $required;
        $forms[$i]['intl'] = $intl;
        $docTotal += count($docs);
    }

    $formatTags = function($csv) use ($formatLabels){
        $out = '';
        foreach(array_filter(explode(',', (string)$csv)) as $ext){
            $ext = strtolower(trim($ext));
            $out .= '<span class="tv-chip-sm">'.htmlspecialchars($formatLabels[$ext] ?? strtoupper($ext)).'</span>';
        }
        return $out;
    };
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Documents de candidature</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item">Documents de candidature</div>
            </div>
        </div>

        <!-- Formulaire nouveau document -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau document demandé</h4>
                <form id="save_doc" action="save_doc" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Formulaire de candidature</label>
                            <select class="form-control select2" style="width:100%" name="form_id" id="form_id" required>
                                <option value="">Choisir un formulaire</option>
                                <?php foreach($forms as $f): if($f['status'] != 1) continue; ?>
                                <option value="<?php echo $f['form_id']; ?>"><?php echo htmlspecialchars($f['form_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nom du document</label>
                            <input type="text" class="form-control" id="document_name" placeholder="Ex : Diplôme d'État" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Formats acceptés <small class="text-muted">(un ou plusieurs)</small></label>
                            <select class="form-control select2" style="width:100%" id="file_type" multiple>
                                <option value="pdf">PDF (.pdf)</option>
                                <option value="doc">Word 97-2003 (.doc)</option>
                                <option value="docx">Word (.docx)</option>
                                <option value="jpg">JPEG (.jpg)</option>
                                <option value="jpeg">JPEG (.jpeg)</option>
                                <option value="png">PNG (.png)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Taille max. (Mo)</label>
                            <input type="number" class="form-control" id="max_size_mb" min="1" max="50" placeholder="Ex : 5">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Modèle à télécharger</label>
                            <input type="file" class="form-control" id="file_name">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Obligatoire</label>
                            <select class="form-control select2" style="width:100%" id="is_required">
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Étrangers uniquement</label>
                            <select class="form-control select2" style="width:100%" id="international_required">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(count($forms) === 0): ?>
        <div class="tv-card tv-empty">Aucun formulaire de candidature. Créez d'abord un type de candidature.</div>
        <?php else: ?>
        <div class="row">
            <!-- forms list -->
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Formulaires <span class="tv-pill-count ml-1"><?php echo $docTotal; ?> documents</span></h4>
                    <div class="tv-tree">
                        <?php foreach($forms as $f): ?>
                        <div class="tv-node <?php echo $f['status'] == 1 ? '' : 'tv-inactive'; ?>" data-target="form-<?php echo $f['form_id']; ?>" title="<?php echo htmlspecialchars($f['form_name']); ?>">
                            <i class="far fa-file-alt"></i>
                            <span class="tv-name"><?php echo htmlspecialchars($f['form_name']); ?></span>
                            <span class="tv-count" data-count-form="<?php echo $f['form_id']; ?>"><?php echo $f['active']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- documents of the selected form -->
            <div class="col-12 col-lg-8">
                <?php foreach($forms as $f): ?>
                <div class="tv-pane" id="pane-form-<?php echo $f['form_id']; ?>" data-form="<?php echo $f['form_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><?php echo htmlspecialchars($f['form_name']); ?><?php if($f['description'] != ''): ?><small><?php echo htmlspecialchars($f['description']); ?></small><?php endif; ?></span>
                                <span class="tv-tag <?php echo $f['status'] == 1 ? 'green' : 'red'; ?>"><?php echo $f['status'] == 1 ? 'Formulaire actif' : 'Formulaire inactif'; ?></span>
                            </h2>
                            <?php if($canManage && $f['status'] == 1): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-form="<?php echo $f['form_id']; ?>"><i class="fas fa-plus"></i> Ajouter un document</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-paperclip"></i></div>
                                <div><b data-active-form="<?php echo $f['form_id']; ?>"><?php echo $f['active']; ?></b><span>Documents actifs</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon red"><i class="fas fa-asterisk"></i></div>
                                <div><b data-required-form="<?php echo $f['form_id']; ?>"><?php echo $f['required']; ?></b><span>Obligatoires</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-globe-africa"></i></div>
                                <div><b data-intl-form="<?php echo $f['form_id']; ?>"><?php echo $f['intl']; ?></b><span>Pour les étrangers</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title">Documents demandés</h4>
                            <div class="tv-filter">
                                <button type="button" class="tv-chip tv-status-filter active" data-filter="all">Tous</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="1">Actifs</button>
                                <button type="button" class="tv-chip tv-status-filter" data-filter="0">Inactifs</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table doc_table" data-form-id="<?php echo $f['form_id']; ?>">
                                <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Formats</th>
                                    <th>Taille max.</th>
                                    <th>Exigence</th>
                                    <th>Statut</th>
                                    <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($f['docs'] as $doc): ?>
                                <tr data-row-id="<?php echo $doc['doc_id']; ?>" data-status="<?php echo $doc['status'] == 1 ? 1 : 0; ?>" data-required="<?php echo (int)$doc['is_required']; ?>" data-intl="<?php echo (int)$doc['international_required']; ?>">
                                    <td>
                                        <div class="tv-member">
                                            <span class="tv-avatar"><i class="far fa-file"></i></span>
                                            <span>
                                                <span class="col-doc-name d-block"><?php echo htmlspecialchars($doc['document_name']); ?></span>
                                                <span class="col-template"><?php if(!empty($doc['file_name'])): ?><a class="tv-link small" href="<?php echo htmlspecialchars($fileUrl($doc['file_name'])); ?>" target="_blank"><i class="fas fa-download"></i> Modèle</a><?php endif; ?></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="col-file-type"><?php echo $formatTags($doc['file_type']); ?></td>
                                    <td class="col-max-size"><?php echo $doc['max_size_mb'] ? (int)$doc['max_size_mb'].' Mo' : '-'; ?></td>
                                    <td class="col-flags">
                                        <span class="tv-tag <?php echo $doc['is_required'] == 1 ? 'amber' : 'blue'; ?>"><?php echo $doc['is_required'] == 1 ? 'Obligatoire' : 'Facultatif'; ?></span>
                                        <?php if($doc['international_required'] == 1): ?><span class="tv-tag blue mt-1">Étrangers</span><?php endif; ?>
                                    </td>
                                    <td><span class="tv-tag <?php echo $doc['status'] == 1 ? 'green' : 'red'; ?> tv-status-tag"><?php echo $doc['status'] == 1 ? 'Actif' : 'Inactif'; ?></span></td>
                                    <?php if($canManage): ?>
                                    <td class="tv-right">
                                        <div class="tv-row-actions">
                                            <button type="button" data-id="<?php echo $doc['doc_id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                                <span id="spinner4_<?php echo $doc['doc_id']; ?>"></span><i class="fas fa-pen"></i>
                                            </button>
                                            <label class="custom-switch" title="Activer / désactiver">
                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $doc['doc_id']; ?>" <?php echo $doc['status']==1 ? 'checked' : ''; ?>>
                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $doc['doc_id']; ?>"></span>
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
            </div>
        </div>
        <?php endif; ?>
    </section>
    <!--update modal-->
    <form action="update_form" method="POST" id="update_form" enctype="multipart/form-data">
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
                            <label>Formulaire de candidature</label>
                            <select class="form-control select2" style="width:100%" id="e_form_id">
                                <option value="">Choisir un formulaire</option>
                                <?php foreach($forms as $f): if($f['status'] != 1) continue; ?>
                                <option value="<?php echo $f['form_id']; ?>"><?php echo htmlspecialchars($f['form_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom du document</label>
                            <input type="text" class="form-control" id="e_document_name" placeholder="Ex : Diplôme d'État" required>
                        </div>
                        <div class="form-group">
                            <label>Formats acceptés <small class="text-muted">(un ou plusieurs)</small></label>
                            <select class="form-control select2" style="width:100%" id="e_file_type" multiple>
                                <option value="pdf">PDF (.pdf)</option>
                                <option value="doc">Word 97-2003 (.doc)</option>
                                <option value="docx">Word (.docx)</option>
                                <option value="jpg">JPEG (.jpg)</option>
                                <option value="jpeg">JPEG (.jpeg)</option>
                                <option value="png">PNG (.png)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Taille max. (Mo)</label>
                            <input type="number" class="form-control" id="e_max_size_mb" min="1" max="50" placeholder="Ex : 5">
                        </div>
                        <div class="form-group">
                            <label>Modèle à télécharger</label>
                            <div id="e_file_preview" class="mb-2"></div>
                            <input type="file" class="form-control" id="e_file_name">
                        </div>
                        <div class="form-group">
                            <label>Obligatoire</label>
                            <select class="form-control select2" style="width:100%" id="e_is_required">
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Étrangers uniquement</label>
                            <select class="form-control select2" style="width:100%" id="e_international_required">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
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
.tv-page .tv-chip-sm{display:inline-block;font-size:11px;font-weight:700;letter-spacing:.04em;color:var(--tv-text);background:var(--tv-bg);border:1px solid var(--tv-border);border-radius:3px;padding:1px 6px;margin:0 4px 4px 0}
.tv-page .doc_table .col-flags .tv-tag{display:inline-flex}
.tv-page .doc_table .tv-link.small{font-size:12px}
.tv-page .doc_table th,.tv-page .doc_table td{padding-left:12px;padding-right:12px}
.tv-page .doc_table td:first-child{min-width:240px}
.tv-page .doc_table td.col-file-type,.tv-page .doc_table td.col-max-size{white-space:nowrap}
.tv-page .doc_table .tv-chip-sm{margin-bottom:0}
/* form names are long and start alike: let them wrap instead of cutting them */
.tv-page .tv-tree .tv-node{align-items:flex-start;font-size:14px}
.tv-page .tv-tree .tv-node .tv-name{white-space:normal;line-height:1.35}
.tv-page .tv-tree .tv-node i{margin-top:3px}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var BASE_URL = <?php echo json_encode($tv_base_url); ?>;
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
var DOC_STORE_KEY = 'formdoc_selected_node';
var FORMAT_LABELS = { pdf: 'PDF', doc: 'DOC', docx: 'DOCX', jpg: 'JPG', jpeg: 'JPEG', png: 'PNG' };

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}
function fileUrl(path){
    return (String(path).charAt(0) === '/' ? BASE_URL : '') + path;
}
function formatTags(csv){
    return String(csv || '').split(',').filter(function(v){ return v !== ''; }).map(function(ext){
        ext = ext.trim().toLowerCase();
        return '<span class="tv-chip-sm">' + escapeHtml(FORMAT_LABELS[ext] || ext.toUpperCase()) + '</span>';
    }).join('');
}
function flagsHtml(required, intl){
    return '<span class="tv-tag ' + (required ? 'amber' : 'blue') + '">' + (required ? 'Obligatoire' : 'Facultatif') + '</span>'
        + (intl ? ' <span class="tv-tag blue mt-1">Étrangers</span>' : '');
}
function statusHtml(active){
    return '<span class="tv-tag ' + (active ? 'green' : 'red') + ' tv-status-tag">' + (active ? 'Actif' : 'Inactif') + '</span>';
}

// show the documents of a form ("form-ID")
function selectNode(target){
    var $pane = $('#pane-' + target);
    if($pane.length === 0) return false;
    $('.tv-pane').removeClass('active');
    $pane.addClass('active');
    $('.tv-node').removeClass('active');
    $('.tv-node[data-target="' + target + '"]').addClass('active');
    try { localStorage.setItem(DOC_STORE_KEY, target); } catch(e) {}
    $pane.find('table.doc_table').each(function(){
        if($.fn.DataTable.isDataTable(this)) $(this).DataTable().columns.adjust();
    });
    return true;
}

function allRowNodes(form_id){
    var $table = $("table.doc_table[data-form-id='" + form_id + "']");
    if($table.length === 0) return $();
    return $($table.DataTable().rows().nodes());
}

function findRow(doc_id){
    var found = null;
    $('table.doc_table').each(function(){
        var dt = $(this).DataTable();
        dt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(doc_id)) found = { row: this, $tr: $(this.node()), formId: $(dt.table().node()).data('form-id') };
        });
    });
    return found;
}

// counters only count active documents, like the applicant forms
function refreshCounts(form_id){
    var $active = allRowNodes(form_id).filter('[data-status="1"]');
    $('[data-count-form="' + form_id + '"], [data-active-form="' + form_id + '"]').text($active.length);
    $('[data-required-form="' + form_id + '"]').text($active.filter('[data-required="1"]').length);
    $('[data-intl-form="' + form_id + '"]').text($active.filter('[data-intl="1"]').length);
}

$(document).ready(function(){
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
        var $table = $(settings.nTable);
        if(!$table.hasClass('doc_table')) return true;
        var filter = $table.closest('.tv-pane').find('.tv-status-filter.active').data('filter');
        if(filter === undefined || filter === 'all') return true;
        return String($(settings.aoData[dataIndex].nTr).attr('data-status')) === String(filter);
    });

    $('.doc_table').each(function() {
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
                "emptyTable": "Aucun document demandé pour ce formulaire",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            }
        });
    });

    $(document).on('click', '.tv-node', function(){
        selectNode($(this).data('target'));
    });

    var saved = null;
    try { saved = localStorage.getItem(DOC_STORE_KEY); } catch(e) {}
    if(!saved || !selectNode(saved)){
        var first = $('.tv-node').first().data('target');
        if(first) selectNode(first);
    }

    $(document).on('click', '.tv-status-filter', function(){
        var $pane = $(this).closest('.tv-pane');
        $pane.find('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        $pane.find('table.doc_table').DataTable().draw(false);
    });

    // "Ajouter un document": open the form with this form type preselected
    $(document).on('click', '.tv-add-here', function(){
        $('#mycard-collapse').collapse('show');
        $('#form_id').val($(this).data('form')).trigger('change');
        $('html, body').animate({ scrollTop: $('#mycard-collapse').offset().top - 90 }, 250);
        $('#document_name').focus();
    });

    // select2 inside a Bootstrap modal needs the modal as its dropdown parent,
    // otherwise the search box cannot receive focus.
    $('#updateModal').on('shown.bs.modal', function(){
        $(this).find('select.select2').each(function(){
            if($(this).data('select2')){ $(this).select2('destroy'); }
            $(this).select2({ width: '100%', dropdownParent: $('#updateModal') });
        });
    });

    function addDocRow(row){
        var $table = $("table.doc_table[data-form-id='" + row.form_id + "']");
        if($table.length === 0){
            try { localStorage.setItem(DOC_STORE_KEY, 'form-' + row.form_id); } catch(e) {}
            location.reload();
            return;
        }
        var required = String(row.is_required) === '1';
        var intl = String(row.international_required) === '1';
        var actionsHtml = canManage ? '<td class="tv-right"><div class="tv-row-actions">'
            + '<button type="button" data-id="'+row.doc_id+'" class="tv-icon-btn edit" title="Modifier">'
            + '<span id="spinner4_'+row.doc_id+'"></span><i class="fas fa-pen"></i></button>'
            + '<label class="custom-switch" title="Activer / désactiver">'
            + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.doc_id+'" checked>'
            + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.doc_id+'"></span></label></div></td>' : '';
        var $tr = $('<tr data-row-id="'+row.doc_id+'" data-status="1" data-required="'+(required ? 1 : 0)+'" data-intl="'+(intl ? 1 : 0)+'">'
            + '<td><div class="tv-member"><span class="tv-avatar"><i class="far fa-file"></i></span><span>'
            + '<span class="col-doc-name d-block">'+escapeHtml(row.document_name)+'</span>'
            + '<span class="col-template">'+(row.file_name ? '<a class="tv-link small" href="'+escapeHtml(fileUrl(row.file_name))+'" target="_blank"><i class="fas fa-download"></i> Modèle</a>' : '')+'</span>'
            + '</span></div></td>'
            + '<td class="col-file-type">'+formatTags(row.file_type)+'</td>'
            + '<td class="col-max-size">'+(row.max_size_mb ? escapeHtml(row.max_size_mb)+' Mo' : '-')+'</td>'
            + '<td class="col-flags">'+flagsHtml(required, intl)+'</td>'
            + '<td>'+statusHtml(true)+'</td>'
            + actionsHtml
            + '</tr>');
        $table.DataTable().row.add($tr[0]).draw(false);
        refreshCounts(row.form_id);
        selectNode('form-' + row.form_id);
    }

    //save document
    $("#save_doc").submit(function(e){
        e.preventDefault();

        var formats = $("#file_type").val() || [];
        if(formats.length === 0){
            pop_wrong("Choisissez au moins un format de fichier accepté");
            return;
        }

        var formData = new FormData();
        formData.append('form_id', $("#form_id").val());
        formData.append('document_name', $("#document_name").val());
        formData.append('file_type', formats.join(','));
        formData.append('max_size_mb', $("#max_size_mb").val());
        formData.append('is_required', $("#is_required").val());
        formData.append('international_required', $("#international_required").val());
        if($("#file_name")[0].files[0]){
            formData.append('file_name', $("#file_name")[0].files[0]);
        }
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/formtype_document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    var keepForm = $('#form_id').val();
                    $('#save_doc')[0].reset();
                    $('#form_id').val(keepForm).trigger('change');
                    $('#file_type').val(null).trigger('change');
                    pop_up_success(data.message);
                    addDocRow(data);
                }
                if(data.status==401 || data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // activate / deactivate document
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        swal({
            title: "Êtes-vous sûr ?",
            text: $checkbox.prop('checked') ? "Ce document sera de nouveau demandé aux candidats." : "Ce document ne sera plus demandé aux candidats.",
            icon: "warning",
            buttons: ["Annuler", "Confirmer"],
            dangerMode: true,
        }).then((willChange) => {
            if (willChange) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/formtype_document/controller.php",
                    data: { id: data_id, action:'delete' },
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==500 || data.status==401){
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        }
                        else if(data.status==200){
                            var active = $checkbox.prop('checked');
                            var hit = findRow(data_id);
                            if(hit){
                                hit.$tr.attr('data-status', active ? 1 : 0);
                                hit.$tr.find('.tv-status-tag').replaceWith(statusHtml(active));
                                hit.row.invalidate('dom').draw(false);
                                refreshCounts(hit.formId);
                            }
                            pop_up_success(data.message);
                        }
                    },
                    error:function(){
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
            url: "../new_files/formtype_document/controller.php",
            data: { id: data_id, action:'view' },
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_document_name").val(data.document_name);
                $("#e_max_size_mb").val(data.max_size_mb);
                $("#e_is_required").val(String(data.is_required)).trigger('change');
                $("#e_international_required").val(String(data.international_required)).trigger('change');
                $("#e_form_id").val(String(data.form_id)).trigger('change');
                $("#f_name").text(data.document_name);
                var $preview = $("#e_file_preview").empty();
                if(data.file_name){
                    $preview.append($('<a target="_blank" class="tv-link"><i class="fas fa-download"></i> Voir le modèle actuel</a>').attr('href', fileUrl(data.file_name)));
                }

                // file_type is stored comma-separated: turn it back into selections
                var formats = (data.file_type || '').split(',').filter(function(v){ return v !== ''; });
                $("#e_file_type").val(formats).trigger('change');

                $('#updateModal').modal('show');
            },
            error:function(){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update document
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formats = $("#e_file_type").val() || [];
        if(formats.length === 0){
            pop_wrong("Choisissez au moins un format de fichier accepté");
            return;
        }

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('form_id', $("#e_form_id").val());
        formData.append('document_name', $("#e_document_name").val());
        formData.append('file_type', formats.join(','));
        formData.append('max_size_mb', $("#e_max_size_mb").val());
        formData.append('is_required', $("#e_is_required").val());
        formData.append('international_required', $("#e_international_required").val());
        if($("#e_file_name")[0].files[0]){
            formData.append('file_name', $("#e_file_name")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/formtype_document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                if(data.status==200){
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if(data.form_changed){
                        // the document moves to another form - simplest correct path
                        try { localStorage.setItem(DOC_STORE_KEY, 'form-' + data.form_id); } catch(e) {}
                        setTimeout(function(){ location.reload(); }, 1000);
                    } else {
                        var hit = findRow(data.doc_id);
                        if(hit){
                            var required = String(data.is_required) === '1';
                            var intl = String(data.international_required) === '1';
                            hit.$tr.attr({ 'data-required': required ? 1 : 0, 'data-intl': intl ? 1 : 0 });
                            hit.$tr.find('.col-doc-name').text(data.document_name);
                            hit.$tr.find('.col-file-type').html(formatTags(data.file_type));
                            hit.$tr.find('.col-max-size').text(data.max_size_mb ? data.max_size_mb + ' Mo' : '-');
                            hit.$tr.find('.col-flags').html(flagsHtml(required, intl));
                            if(data.file_name){
                                hit.$tr.find('.col-template').html('<a class="tv-link small" href="'+escapeHtml(fileUrl(data.file_name))+'" target="_blank"><i class="fas fa-download"></i> Modèle</a>');
                            }
                            hit.row.invalidate('dom').draw(false);
                            refreshCounts(hit.formId);
                        }
                    }
                }
                if(data.status==401 || data.status==500){
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
