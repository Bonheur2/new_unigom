<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // project root as a URL path ("" live, "/academic" under XAMPP) so stored template paths resolve in both places
    $tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
    $tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));
    $fileUrl = function($path) use ($tv_base_url){
        return (strpos((string)$path, '/') === 0 ? $tv_base_url : '').$path;
    };
    $typeTags = function($csv){
        $out = '';
        foreach(array_filter(array_map('trim', explode(',', (string)$csv))) as $t){
            $out .= '<span class="tv-chip-sm">'.htmlspecialchars(strtoupper($t)).'</span>';
        }
        return $out !== '' ? $out : '<span class="text-muted">-</span>';
    };

    // the whole structure: every active campus > faculty > programme type, with their documents
    $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                FROM tbl_campus
                                INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                WHERE tbl_campus.camp_active=1 AND tbl_faculty.status=1 AND tbl_program_type.status=1
                                ORDER BY tbl_campus.camp_full_name ASC");
    $sql_tabs->execute();
    $campuses = $sql_tabs->fetchAll();

    foreach($campuses as $cIndex => $campus){
        $sql_facs = $conn->prepare("SELECT DISTINCT tbl_faculty.fac_id, tbl_faculty.fac_full_name
                                    FROM tbl_faculty
                                    INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                    WHERE tbl_faculty.campus_id = :camp_id AND tbl_faculty.status=1 AND tbl_program_type.status=1
                                    ORDER BY tbl_faculty.fac_full_name ASC");
        $sql_facs->execute([':camp_id' => $campus['camp_id']]);
        $faculties = $sql_facs->fetchAll();

        $campTotal = 0;
        foreach($faculties as $fIndex => $fac){
            $sql_pts = $conn->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type
                                       WHERE fac_id = :fac_id AND status=1 ORDER BY prg_type_full_name ASC");
            $sql_pts->execute([':fac_id' => $fac['fac_id']]);
            $prg_types = $sql_pts->fetchAll();

            $facTotal = 0;
            foreach($prg_types as $pIndex => $pt){
                $sql = $conn->prepare("SELECT * FROM tbl_document_type WHERE prg_type_id = :prg_type_id ORDER BY status DESC, document_name ASC");
                $sql->execute([':prg_type_id' => $pt['prg_type_id']]);
                $docs = $sql->fetchAll();
                $active = 0; $intl = 0;
                foreach($docs as $d){
                    if($d['status'] == 1){ $active++; if($d['international_required'] == 1) $intl++; }
                }
                $prg_types[$pIndex]['docs'] = $docs;
                $prg_types[$pIndex]['active'] = $active;
                $prg_types[$pIndex]['intl'] = $intl;
                $facTotal += $active;
            }
            $faculties[$fIndex]['prg_types'] = $prg_types;
            $faculties[$fIndex]['total'] = $facTotal;
            $campTotal += $facTotal;
        }
        $campuses[$cIndex]['faculties'] = $faculties;
        $campuses[$cIndex]['total'] = $campTotal;
    }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Documents par faculté</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Documents par faculté</a></div>
            </div>
        </div>

        <!-- Formulaire nouveau document -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau document demandé</h4>
                <form id="save_doc" action="save_doc" method="POST" enctype="multipart/form-data">
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
                            <label>Type de programme <span id="spinner_prg"></span></label>
                            <select class="form-control select2" style="width:100%" name="prg_type_id" id="prg_type_id">
                                <option value="">Choisissez d'abord une faculté</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nom du document</label>
                            <input type="text" class="form-control" id="document_name" placeholder="Ex : Copie de la carte d'identité" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Type de fichier</label>
                            <input type="text" class="form-control" id="file_type" placeholder="Ex : pdf,jpg" maxlength="10" title="10 caractères au maximum, séparés par des virgules">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Modèle à télécharger</label>
                            <input type="file" class="form-control" id="file_name">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Étrangers uniquement</label>
                            <select class="form-control select2" style="width:100%" id="international_required">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(count($campuses) === 0): ?>
        <div class="tv-card tv-empty">Aucun type de programme actif. Créez d'abord les facultés et les types de programme.</div>
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
                                    <span class="tv-count" data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['active']; ?></span>
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
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>"><i class="fas fa-plus"></i> Ajouter un document</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-layer-group"></i></div>
                                <div><b><?php echo count($campus['faculties']); ?></b><span>Facultés</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-paperclip"></i></div>
                                <div><b data-count-campus="<?php echo $campus['camp_id']; ?>"><?php echo $campus['total']; ?></b><span>Documents actifs</span></div>
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
                                    <th>Documents actifs</th>
                                    <th class="tv-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($campus['faculties'] as $fac): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fac['fac_full_name']); ?></td>
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
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>"><i class="fas fa-plus"></i> Ajouter un document</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-graduation-cap"></i></div>
                                <div><b><?php echo count($fac['prg_types']); ?></b><span>Types de programme</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-paperclip"></i></div>
                                <div><b data-count-fac="<?php echo $fac['fac_id']; ?>"><?php echo $fac['total']; ?></b><span>Documents actifs</span></div>
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
                                    <th>Documents actifs</th>
                                    <th class="tv-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($fac['prg_types'] as $pt): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pt['prg_type_full_name']); ?></td>
                                    <td data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['active']; ?></td>
                                    <td class="tv-right"><button type="button" class="tv-link tv-open" data-target="pt-<?php echo $pt['prg_type_id']; ?>">Voir <i class="fas fa-arrow-right"></i></button></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php foreach($fac['prg_types'] as $pt): ?>
                <!-- programme type pane -->
                <div class="tv-pane" id="pane-pt-<?php echo $pt['prg_type_id']; ?>" data-pt="<?php echo $pt['prg_type_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>" data-campus="<?php echo $campus['camp_id']; ?>">
                    <div class="tv-card">
                        <div class="tv-detail-head">
                            <h2>
                                <span><?php echo htmlspecialchars($pt['prg_type_full_name']); ?><small><?php echo htmlspecialchars($fac['fac_full_name'].' · '.$campus['camp_full_name']); ?></small></span>
                                <span class="tv-badge">Type de programme</span>
                            </h2>
                            <?php if($canManage): ?>
                            <div class="tv-actions">
                                <button type="button" class="tv-btn tv-btn-accent tv-add-here" data-campus="<?php echo $campus['camp_id']; ?>" data-fac="<?php echo $fac['fac_id']; ?>" data-pt="<?php echo $pt['prg_type_id']; ?>"><i class="fas fa-plus"></i> Ajouter un document</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="tv-stats">
                            <div class="tv-stat">
                                <div class="tv-stat-icon primary"><i class="fas fa-paperclip"></i></div>
                                <div><b data-count-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['active']; ?></b><span>Documents actifs</span></div>
                            </div>
                            <div class="tv-stat">
                                <div class="tv-stat-icon blue"><i class="fas fa-globe-africa"></i></div>
                                <div><b data-intl-pt="<?php echo $pt['prg_type_id']; ?>"><?php echo $pt['intl']; ?></b><span>Pour les étrangers uniquement</span></div>
                            </div>
                        </div>
                        <p class="tv-card-sub" style="margin:16px 0 0">Ces documents sont demandés aux candidats de ce type de programme, en plus des documents du formulaire de candidature.</p>
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
                            <table class="tv-table doc_table" data-prg-type-id="<?php echo $pt['prg_type_id']; ?>">
                                <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Type de fichier</th>
                                    <th>Modèle</th>
                                    <th>Étrangers uniquement</th>
                                    <th>Statut</th>
                                    <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($pt['docs'] as $doc): ?>
                                <tr data-row-id="<?php echo $doc['doc_id']; ?>" data-status="<?php echo $doc['status'] == 1 ? 1 : 0; ?>" data-intl="<?php echo $doc['international_required'] == 1 ? 1 : 0; ?>">
                                    <td class="col-doc-name"><?php echo htmlspecialchars($doc['document_name']); ?></td>
                                    <td class="col-file-type"><?php echo $typeTags($doc['file_type']); ?></td>
                                    <td class="col-template"><?php if(!empty($doc['file_name'])): ?><a class="tv-link" href="<?php echo htmlspecialchars($fileUrl($doc['file_name'])); ?>" target="_blank"><i class="fas fa-download"></i> Voir</a><?php else: ?><span class="text-muted">-</span><?php endif; ?></td>
                                    <td class="col-intl"><?php echo $doc['international_required'] == 1 ? '<span class="tv-tag blue">Oui</span>' : 'Non'; ?></td>
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
                <?php endforeach; ?>
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
                            <label>Campus <span id="spinner_e_fac"></span></label>
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
                            <label>Faculté <span id="spinner_e_prg"></span></label>
                            <select class="form-control select2" style="width:100%" id="e_fac_id">
                                <option value="">Choisissez d'abord un campus</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type de programme</label>
                            <select class="form-control select2" style="width:100%" id="e_prg_type_id">
                                <option value="">Choisissez d'abord une faculté</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom du document</label>
                            <input type="text" class="form-control" id="e_document_name" placeholder="Ex : Copie de la carte d'identité" required>
                        </div>
                        <div class="form-group">
                            <label>Type de fichier</label>
                            <input type="text" class="form-control" id="e_file_type" placeholder="Ex : pdf,jpg" maxlength="10" title="10 caractères au maximum, séparés par des virgules">
                        </div>
                        <div class="form-group">
                            <label>Modèle à télécharger</label>
                            <div id="e_file_preview" class="mb-2"></div>
                            <input type="file" class="form-control" id="e_file_name">
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
.tv-page .tv-chip-sm{display:inline-block;font-size:11px;font-weight:700;letter-spacing:.04em;color:var(--tv-text);background:var(--tv-bg);border:1px solid var(--tv-border);border-radius:3px;padding:1px 6px;margin-right:4px}
.tv-page .doc_table td.col-file-type{white-space:nowrap}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
var pendingFacId = null;
var pendingPrgTypeId = null;
var pendingAddFacId = null;
var pendingAddPrgType = null;
var BASE_URL = <?php echo json_encode($tv_base_url); ?>;
var canManage = <?php echo $canManage ? 'true' : 'false'; ?>;
var FDOC_STORE_KEY = 'facdoc_selected_node';

function escapeHtml(s){
    return $('<div>').text(s == null ? '' : s).html();
}
function fileUrl(path){
    return (String(path).charAt(0) === '/' ? BASE_URL : '') + path;
}
function typeTags(csv){
    var parts = String(csv || '').split(',').map(function(v){ return v.trim(); }).filter(function(v){ return v !== ''; });
    return parts.length ? parts.map(function(t){ return '<span class="tv-chip-sm">' + escapeHtml(t.toUpperCase()) + '</span>'; }).join('') : '<span class="text-muted">-</span>';
}
function templateHtml(path){
    return path ? '<a class="tv-link" href="' + escapeHtml(fileUrl(path)) + '" target="_blank"><i class="fas fa-download"></i> Voir</a>' : '<span class="text-muted">-</span>';
}
function intlHtml(v){
    return String(v) === '1' ? '<span class="tv-tag blue">Oui</span>' : 'Non';
}
function statusHtml(active){
    return '<span class="tv-tag ' + (active ? 'green' : 'red') + ' tv-status-tag">' + (active ? 'Actif' : 'Inactif') + '</span>';
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

function selectNode(target){
    var $pane = $('#pane-' + target);
    if($pane.length === 0) return false;
    $('.tv-pane').removeClass('active');
    $pane.addClass('active');
    $('.tv-node').removeClass('active');
    openPath($('.tv-node[data-target="' + target + '"]').addClass('active'));
    try { localStorage.setItem(FDOC_STORE_KEY, target); } catch(e) {}
    $pane.find('table.doc_table').each(function(){
        if($.fn.DataTable.isDataTable(this)) $(this).DataTable().columns.adjust();
    });
    return true;
}

function allRowNodes(pt_id){
    var $table = $("table.doc_table[data-prg-type-id='" + pt_id + "']");
    if($table.length === 0) return $();
    return $($table.DataTable().rows().nodes());
}

function findRow(doc_id){
    var found = null;
    $('table.doc_table').each(function(){
        var dt = $(this).DataTable();
        dt.rows().every(function(){
            if(String($(this.node()).attr('data-row-id')) === String(doc_id)) found = { row: this, $tr: $(this.node()), ptId: $(dt.table().node()).data('prg-type-id') };
        });
    });
    return found;
}

// counters count active documents, like the applicant forms
function refreshCounts(pt_id){
    var $pane = $('#pane-pt-' + pt_id);
    var $active = allRowNodes(pt_id).filter('[data-status="1"]');
    $('[data-count-pt="' + pt_id + '"]').text($active.length);
    $('[data-intl-pt="' + pt_id + '"]').text($active.filter('[data-intl="1"]').length);
    $.each(['fac', 'campus'], function(i, level){
        var id = $pane.attr('data-' + level), t = 0;
        $('.tv-pane[data-pt][data-' + level + '="' + id + '"]').each(function(){
            t += allRowNodes($(this).attr('data-pt')).filter('[data-status="1"]').length;
        });
        $('[data-count-' + level + '="' + id + '"]').text(t);
    });
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
                "emptyTable": "Aucun document demandé pour ce type de programme",
                "paginate": { "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent" }
            }
        });
    });

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

    var saved = null;
    try { saved = localStorage.getItem(FDOC_STORE_KEY); } catch(e) {}
    if(!saved || !selectNode(saved)){
        var first = $('.tv-node[data-target^="pt-"]').first().data('target');
        if(first) selectNode(first);
    }

    $(document).on('click', '.tv-status-filter', function(){
        var $pane = $(this).closest('.tv-pane');
        $pane.find('.tv-status-filter').removeClass('active');
        $(this).addClass('active');
        $pane.find('table.doc_table').DataTable().draw(false);
    });

    // "Ajouter un document": open the form with the known levels preselected
    $(document).on('click', '.tv-add-here', function(){
        pendingAddFacId = $(this).data('fac') || null;
        pendingAddPrgType = $(this).data('pt') || null;
        $('#mycard-collapse').collapse('show');
        $('#campus_id').val($(this).data('campus')).trigger('change');
        $('html, body').animate({ scrollTop: $('#mycard-collapse').offset().top - 90 }, 250);
        $('#document_name').focus();
    });

    function addDocRow(prg_type_id, row){
        var $table = $("table.doc_table[data-prg-type-id='" + prg_type_id + "']");
        if($table.length === 0){
            try { localStorage.setItem(FDOC_STORE_KEY, 'pt-' + prg_type_id); } catch(e) {}
            location.reload();
            return;
        }
        var intl = String(row.international_required) === '1';
        var actionsHtml = canManage ? '<td class="tv-right"><div class="tv-row-actions">'
            + '<button type="button" data-id="'+row.doc_id+'" class="tv-icon-btn edit" title="Modifier">'
            + '<span id="spinner4_'+row.doc_id+'"></span><i class="fas fa-pen"></i></button>'
            + '<label class="custom-switch" title="Activer / désactiver">'
            + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.doc_id+'" checked>'
            + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.doc_id+'"></span></label></div></td>' : '';
        var $tr = $('<tr data-row-id="'+row.doc_id+'" data-status="1" data-intl="'+(intl ? 1 : 0)+'">'
            + '<td class="col-doc-name">'+escapeHtml(row.document_name)+'</td>'
            + '<td class="col-file-type">'+typeTags(row.file_type)+'</td>'
            + '<td class="col-template">'+templateHtml(row.file_name)+'</td>'
            + '<td class="col-intl">'+intlHtml(row.international_required)+'</td>'
            + '<td>'+statusHtml(true)+'</td>'
            + actionsHtml
            + '</tr>');
        $table.DataTable().row.add($tr[0]).draw(false);
        refreshCounts(prg_type_id);
        selectNode('pt-' + prg_type_id);
    }

    //save document
    $("#save_doc").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('prg_type_id', $("#prg_type_id").val());
        formData.append('document_name', $("#document_name").val());
        formData.append('file_type', $("#file_type").val());
        formData.append('international_required', $("#international_required").val());
        if($("#file_name")[0].files[0]){
            formData.append('file_name', $("#file_name")[0].files[0]);
        }
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if(data.status==200){
                    // keep campus / faculty / programme so several documents can be added in a row
                    $('#document_name').val('');
                    $('#file_type').val('');
                    $('#file_name').val('');
                    $('#international_required').val('0').trigger('change');
                    pop_up_success(data.message);
                    addDocRow(data.prg_type_id, data);
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
                    url: "../new_files/Faculity_Document/controller.php",
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
                            var hit = findRow(data_id);
                            if(hit){
                                hit.$tr.attr('data-status', active ? 1 : 0);
                                hit.$tr.find('.tv-status-tag').replaceWith(statusHtml(active));
                                hit.row.invalidate('dom').draw(false);
                                refreshCounts(hit.ptId);
                            }
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
            url: "../new_files/Faculity_Document/controller.php",
            data: { id: data_id, action: 'view' },
            dataType:"json",

            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_document_name").val(data.document_name);
                $("#e_file_type").val(data.file_type);
                $("#e_international_required").val(String(data.international_required)).trigger('change');
                $("#f_name").text(data.document_name);
                var $preview = $("#e_file_preview").empty();
                if(data.file_name){
                    $preview.append($('<a target="_blank" class="tv-link"><i class="fas fa-download"></i> Voir le modèle actuel</a>').attr('href', fileUrl(data.file_name)));
                }

                pendingFacId = data.prg_fac_id || data.fac_id;
                pendingPrgTypeId = data.prg_type_id;
                $("#e_campus_id").val(data.campus_id).trigger('change');
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update document
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('prg_type_id', $("#e_prg_type_id").val());
        formData.append('document_name', $("#e_document_name").val());
        formData.append('file_type', $("#e_file_type").val());
        formData.append('international_required', $("#e_international_required").val());
        if($("#e_file_name")[0].files[0]){
            formData.append('file_name', $("#e_file_name")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                if(data.status==200){
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if(data.prg_changed){
                        // the document moves to another programme - simplest correct path
                        try { localStorage.setItem(FDOC_STORE_KEY, 'pt-' + data.prg_type_id); } catch(e) {}
                        location.reload();
                    } else {
                        var hit = findRow(data.doc_id);
                        if(hit){
                            hit.$tr.attr('data-intl', String(data.international_required) === '1' ? 1 : 0);
                            hit.$tr.find('.col-doc-name').text(data.document_name);
                            hit.$tr.find('.col-file-type').html(typeTags(data.file_type));
                            hit.$tr.find('.col-intl').html(intlHtml(data.international_required));
                            if(data.file_name){
                                hit.$tr.find('.col-template').html(templateHtml(data.file_name));
                            }
                            hit.row.invalidate('dom').draw(false);
                            refreshCounts(hit.ptId);
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

    // load programmes when faculty changes (add form)
    $("#fac_id").change(function(){
        var fac_id = $(this).val();
        $("#prg_type_id").empty().append("<option value=''>Choisissez d'abord une faculté</option>").trigger('change');
        if(!fac_id) return;
        $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_program_types" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_prg').fadeOut('fast');
                $("#prg_type_id").empty().append("<option value=''>Choisir un type de programme</option>");
                $.each(data, function(index, value){
                    $("#prg_type_id").append("<option value='" + value.prg_type_id + "'>" + escapeHtml(value.prg_type_full_name) + "</option>");
                });
                if(pendingAddPrgType){
                    $("#prg_type_id").val(pendingAddPrgType);
                    pendingAddPrgType = null;
                }
                $("#prg_type_id").trigger('change');
            },
            error: function(){
                $('#spinner_prg').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // load programmes when faculty changes (update modal)
    $("#e_fac_id").change(function(){
        var fac_id = $(this).val();
        $("#e_prg_type_id").empty().append("<option value=''>Choisissez d'abord une faculté</option>").trigger('change');
        if(!fac_id) return;
        $('#spinner_e_prg').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_program_types" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_prg').fadeOut('fast');
                $("#e_prg_type_id").empty().append("<option value=''>Choisir un type de programme</option>");
                $.each(data, function(index, value){
                    $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + escapeHtml(value.prg_type_full_name) + "</option>");
                });
                if(pendingPrgTypeId){
                    $("#e_prg_type_id").val(pendingPrgTypeId);
                    pendingPrgTypeId = null;
                }
                $("#e_prg_type_id").trigger('change');
            },
            error: function(){
                $('#spinner_e_prg').fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // load faculty when campus changes (add form)
    $("#campus_id").change(function(){
         $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#fac_id").empty();
        var camp_id=$("#campus_id").val();
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
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

    // load faculty when campus changes (update modal)
    $("#e_campus_id").change(function(){
        var camp_id = $(this).val();
        if(!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Chargement...</option>");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
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
