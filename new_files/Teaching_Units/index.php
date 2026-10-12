<?php
// Teaching units (UE) and their courses (EC).
// A unit belongs to a programme + level + semester, optionally narrowed to one
// department / option. Its courses are managed from the "Cours" button.


// $conn from meet/con.php is created in PDO's silent error mode, where a
// failing query just returns no rows - the list would look empty and the
// catch below would never run. Switch to exceptions for this page's queries,
// then restore the original mode so the rest of the admin shell is unaffected.
$prev_errmode = $conn->getAttribute(PDO::ATTR_ERRMODE);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$faculties = [];
$units = [];
$load_error = '';
try {
    $facStmt = $conn->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status = 1 ORDER BY fac_full_name ASC");
    $facStmt->execute();
    $faculties = $facStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    $load_error = $e->getMessage();
}

try {
    $st = $conn->prepare("SELECT u.*, pt.prg_type_full_name, fac.fac_full_name, lvl.level_full_name,
                                 dept.dept_full_name, opt.opt_full_name,
                                 (SELECT COUNT(*) FROM tbl_courses c WHERE c.ue_id = u.ue_id AND c.status = 1) AS course_count,
                                 (SELECT COALESCE(SUM(c.credits), 0) FROM tbl_courses c WHERE c.ue_id = u.ue_id AND c.status = 1) AS course_credits
                          FROM tbl_teaching_units u
                          LEFT JOIN tbl_program_type pt ON pt.prg_type_id = u.prg_type_id
                          LEFT JOIN tbl_faculty fac ON fac.fac_id = pt.fac_id
                          LEFT JOIN tbl_level lvl ON lvl.level_id = u.level_id
                          LEFT JOIN tbl_department dept ON dept.dept_id = u.dept_id
                          LEFT JOIN tbl_option opt ON opt.opt_id = u.opt_id
                          ORDER BY u.status DESC, fac.fac_full_name, pt.prg_type_full_name, lvl.level_rank, u.semester_no, u.ue_code");
    $st->execute();
    $units = $st->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    $load_error = $e->getMessage();
}

$conn->setAttribute(PDO::ATTR_ERRMODE, $prev_errmode);

// figures for the summary tiles and the filter lists
$n = function($v){ return rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.'); };
$activeUnits = 0; $activeCourses = 0; $mismatch = 0;
$filterFac = []; $filterPt = []; $filterLvl = [];
foreach($units as $u){
    if($u['status'] == 1){
        $activeUnits++;
        $activeCourses += (int) $u['course_count'];
        if($u['course_count'] > 0 && abs($u['course_credits'] - $u['credits']) > 0.001){ $mismatch++; }
    }
    if($u['fac_full_name']){ $filterFac[$u['fac_full_name']] = true; }
    if($u['prg_type_full_name']){ $filterPt[$u['prg_type_full_name']] = true; }
    if($u['level_full_name']){ $filterLvl[$u['level_full_name']] = true; }
}
ksort($filterFac); ksort($filterPt); ksort($filterLvl);
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Unités d'enseignement et cours</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item">Unités d'enseignement</div>
            </div>
        </div>

        <?php if($load_error !== ''): ?>
        <div class="alert alert-danger">
            Impossible de charger les unités : <?php echo htmlspecialchars($load_error); ?>
            <br><small>Si la table n'existe pas encore, exécutez d'abord <b>Academic/migration.sql</b>.</small>
        </div>
        <?php endif; ?>

        <!-- ===================== NEW UNIT ===================== -->
        <div class="collapse" id="new-unit">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouvelle unité d'enseignement (UE)</h4>
                <form id="unit_form">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Faculté <span class="text-danger">*</span></label>
                            <select class="form-control select2" style="width:100%" id="fac_id">
                                <option value="">Choisir une faculté</option>
                                <?php foreach($faculties as $f): ?>
                                <option value="<?php echo $f['fac_id']; ?>">
                                    <?php echo htmlspecialchars($f['fac_full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Type de programme <span class="text-danger">*</span> <span
                                    id="sp_prg"></span></label>
                            <select class="form-control select2" style="width:100%" id="prg_type_id">
                                <option value="">Choisissez d'abord une faculté</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Niveau <span class="text-danger">*</span> <span id="sp_level"></span></label>
                            <select class="form-control select2" style="width:100%" id="level_id">
                                <option value="">Choisissez d'abord un type de programme</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Semestre <span class="text-danger">*</span></label>
                            <select class="form-control select2" style="width:100%" id="semester_no">
                                <option value="1">Semestre 1</option>
                                <option value="2">Semestre 2</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Département <small class="text-muted">(vide = tous)</small> <span
                                    id="sp_dept"></span></label>
                            <select class="form-control select2" style="width:100%" id="dept_id">
                                <option value="">Tous les départements</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Option <small class="text-muted">(vide = toutes)</small> <span
                                    id="sp_opt"></span></label>
                            <select class="form-control select2" style="width:100%" id="opt_id">
                                <option value="">Toutes les options</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Code de l'UE <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ue_code" placeholder="Ex : UE101"
                                style="text-transform:uppercase">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Intitulé de l'UE <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ue_name"
                                placeholder="Ex : Mathématiques générales">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Crédits</label>
                            <input type="number" class="form-control" id="credits" step="0.5" min="0" placeholder="0">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Seuil de réussite</label>
                            <input type="number" class="form-control" id="pass_mark" step="0.01" min="0" value="10">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent w-100 justify-content-center"><span
                                    id="sp_save"></span>Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ===================== SUMMARY ===================== -->
        <div class="tv-kpis">
            <div class="tv-kpi">
                <div class="tv-stat-icon primary"><i class="fas fa-columns"></i></div>
                <div><b><?php echo $activeUnits; ?></b><span class="tv-kpi-label">Unités actives</span><span
                        class="tv-kpi-sub"><?php echo count($units); ?> enregistrées au total</span></div>
            </div>
            <div class="tv-kpi">
                <div class="tv-stat-icon green"><i class="fas fa-book"></i></div>
                <div><b><?php echo $activeCourses; ?></b><span class="tv-kpi-label">Cours actifs</span><span
                        class="tv-kpi-sub">Dans les unités actives</span></div>
            </div>
            <div class="tv-kpi">
                <div class="tv-stat-icon <?php echo $mismatch ? 'red' : 'blue'; ?>"><i
                        class="fas fa-<?php echo $mismatch ? 'exclamation-triangle' : 'check'; ?>"></i></div>
                <div><b><?php echo $mismatch; ?></b><span class="tv-kpi-label">Crédits à vérifier</span><span
                        class="tv-kpi-sub">Unités dont les cours ne totalisent pas leurs crédits</span></div>
            </div>
        </div>

        <!-- ===================== UNIT LIST ===================== -->
        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Unités enregistrées</h4>
                <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouvelle
                    UE</button>
            </div>

            <div class="tu-filters">
                <select class="form-control tu-filter" data-col="3" aria-label="Filtrer par faculté">
                    <option value="">Toutes les facultés</option>
                    <?php foreach(array_keys($filterFac) as $v): ?><option><?php echo htmlspecialchars($v); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control tu-filter" data-col="4" aria-label="Filtrer par type de programme">
                    <option value="">Tous les types de programme</option>
                    <?php foreach(array_keys($filterPt) as $v): ?><option><?php echo htmlspecialchars($v); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control tu-filter" data-col="5" aria-label="Filtrer par niveau">
                    <option value="">Tous les niveaux</option>
                    <?php foreach(array_keys($filterLvl) as $v): ?><option><?php echo htmlspecialchars($v); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control tu-filter" data-col="6" aria-label="Filtrer par semestre">
                    <option value="">Tous les semestres</option>
                    <option value="1">Semestre 1</option>
                    <option value="2">Semestre 2</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="tv-table" id="units_table" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Unité</th>
                            <th>Faculté</th>
                            <th>Programme</th>
                            <th>Niveau</th>
                            <th>Sem.</th>
                            <th>Département /<br>Option</th>
                            <th>Crédits</th>
                            <th>Seuil</th>
                            <th>Cours</th>
                            <th class="tv-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach($units as $u):
                            // the unit's credits should match the sum of its active courses
                            $credit_mismatch = $u['course_count'] > 0 && abs($u['course_credits'] - $u['credits']) > 0.001;
                        ?>
                        <tr class="<?php echo $u['status'] == 1 ? '' : 'tu-inactive'; ?>">
                            <td><?php echo $i++; ?></td>
                            <td class="tu-code"><?php echo htmlspecialchars($u['ue_code']); ?></td>
                            <td><b><?php echo htmlspecialchars($u['ue_name']); ?></b><?php if($u['status'] != 1): ?>
                                <span class="tv-tag red">Inactive</span><?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($u['fac_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['prg_type_full_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['level_full_name'] ?? ''); ?></td>
                            <td><?php echo (int) $u['semester_no']; ?></td>
                            <td><small><?php echo $u['dept_full_name'] ? htmlspecialchars($u['dept_full_name']) : 'Tous les départements'; ?><?php echo $u['opt_full_name'] ? ' &rsaquo; '.htmlspecialchars($u['opt_full_name']) : ''; ?></small>
                            </td>
                            <td style="white-space:nowrap">
                                <?php echo $n($u['credits']); ?>
                                <?php if($credit_mismatch): ?>
                                <i class="fas fa-exclamation-triangle tu-warn"
                                    title="Les cours actifs totalisent <?php echo $n($u['course_credits']); ?> crédits"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $n($u['pass_mark']); ?></td>
                            <td>
                                <button type="button" class="tu-courses-btn open_courses"
                                    data-id="<?php echo $u['ue_id']; ?>" title="Gérer les cours">
                                    <i class="fas fa-book"></i> <?php echo (int) $u['course_count']; ?>
                                </button>
                            </td>
                            <td class="tv-right" style="white-space:nowrap">
                                <div class="tv-row-actions">
                                    <button type="button" class="tv-icon-btn edit_unit"
                                        data-id="<?php echo $u['ue_id']; ?>" title="Modifier"><i
                                            class="fas fa-pen"></i></button>
                                    <label class="custom-switch mb-0" style="padding-left:0"
                                        title="Activer / désactiver">
                                        <input type="checkbox" class="custom-switch-input toggle_unit"
                                            data-id="<?php echo $u['ue_id']; ?>"
                                            <?php echo $u['status'] == 1 ? 'checked' : ''; ?>>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===================== EDIT UNIT MODAL ===================== -->
    <div class="modal fade" tabindex="-1" role="dialog" id="unitModal">
        <div class="modal-dialog modal-lg" role="document">
            <form id="unit_edit_form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'unité</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Fermer"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_ue_id">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Faculté</label>
                            <select class="form-control" id="e_fac_id">
                                <option value="">Choisir une faculté</option>
                                <?php foreach($faculties as $f): ?>
                                <option value="<?php echo $f['fac_id']; ?>">
                                    <?php echo htmlspecialchars($f['fac_full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Type de programme <span id="e_sp_prg"></span></label>
                            <select class="form-control" id="e_prg_type_id"></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Niveau <span id="e_sp_level"></span></label>
                            <select class="form-control" id="e_level_id"></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Semestre</label>
                            <select class="form-control" id="e_semester_no">
                                <option value="1">Semestre 1</option>
                                <option value="2">Semestre 2</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Département <small class="text-muted">(vide = tous)</small> <span
                                    id="e_sp_dept"></span></label>
                            <select class="form-control" id="e_dept_id"></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Option <small class="text-muted">(vide = toutes)</small> <span
                                    id="e_sp_opt"></span></label>
                            <select class="form-control" id="e_opt_id"></select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Code de l'UE</label>
                            <input type="text" class="form-control" id="e_ue_code" style="text-transform:uppercase">
                        </div>
                        <div class="form-group col-md-5">
                            <label>Intitulé de l'UE</label>
                            <input type="text" class="form-control" id="e_ue_name">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Crédits</label>
                            <input type="number" class="form-control" id="e_credits" step="0.5" min="0">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Seuil</label>
                            <input type="number" class="form-control" id="e_pass_mark" step="0.01" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span id="e_sp_save"></span>&nbsp;Enregistrer
                        les modifications</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== COURSES MODAL ===================== -->
    <div class="modal fade" tabindex="-1" role="dialog" id="coursesModal">
        <div class="modal-dialog modal-xl" role="document" style="max-width:1100px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cours de l'unité <span id="c_unit_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Fermer"><span>&times;</span></button>
                </div>
                <div class="modal-body tv-page">
                    <div id="c_credit_note" class="tu-credit-note mb-3"></div>
                    <div class="table-responsive">
                        <table class="tv-table tu-courses">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Cours</th>
                                    <th>Crédits</th>
                                    <th>Heures</th>
                                    <th>TP/<br>Interro %</th>
                                    <th>Examen<br>%</th>
                                    <th>Seuil</th>
                                    <th>Note<br>élim.</th>
                                    <th class="tv-right" style="width:110px">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="c_body"></tbody>
                        </table>
                    </div>

                    <div class="tu-course-form mt-3">
                        <h6 id="c_form_title"><i class="fas fa-plus"></i> Ajouter un cours à cette unité</h6>
                        <form id="course_form">
                            <input type="hidden" id="c_course_id">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Code</label>
                                    <input type="text" class="form-control" id="c_course_code" placeholder="Ex : MAT101"
                                        style="text-transform:uppercase">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Intitulé du cours <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_course_name">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Crédits</label>
                                    <input type="number" class="form-control" id="c_credits" step="0.5" min="0"
                                        value="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Heures</label>
                                    <input type="number" class="form-control" id="c_hours" min="0">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>TP/Interro %</label>
                                    <input type="number" class="form-control" id="c_cat_weight" step="0.01" min="0"
                                        max="100" value="40">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Examen %</label>
                                    <input type="number" class="form-control" id="c_exam_weight" step="0.01" min="0"
                                        max="100" value="60">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Seuil de réussite</label>
                                    <input type="number" class="form-control" id="c_pass_mark" step="0.01" min="0"
                                        value="10">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Note éliminatoire à l'examen <small
                                            class="text-muted">(facultatif)</small></label>
                                    <input type="number" class="form-control" id="c_exam_min_mark" step="0.01" min="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="submit"
                                        class="tv-btn tv-btn-accent w-100 justify-content-center"><span
                                            id="c_sp_save"></span><span id="c_save_label">Ajouter</span></button>
                                    <button type="button"
                                        class="tv-btn tv-btn-outline w-100 justify-content-center mt-1"
                                        id="c_cancel_edit" hidden>Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tv-page .tu-filters {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 16px
}

.tv-page .tu-filters select {
    height: 38px;
    font-size: 14px;
    border-radius: 0
}

.tv-page #units_table thead th,
.tv-page .tu-courses thead th {
    font-weight: 800;
    color: var(--tv-accent);
    padding: 10px 8px;
    font-size: 11px;
    line-height: 1.3;
    letter-spacing: .03em;
    white-space: normal;
    vertical-align: bottom
}

.tv-page #units_table td,
.tv-page .tu-courses td {
    font-size: 14px;
    padding: 11px 8px
}

.tv-page #units_table .tu-code {
    font-family: monospace;
    font-size: 13px;
    white-space: nowrap
}

.tv-page #units_table tr.tu-inactive td {
    color: var(--tv-muted)
}

.tv-page .tu-warn {
    color: #e0a100;
    cursor: help
}

.tv-page .tu-courses-btn {
    background: var(--tv-accent-soft);
    border: 1px solid transparent;
    color: var(--tv-accent);
    font-weight: 700;
    font-size: 13px;
    padding: 4px 12px;
    cursor: pointer;
    white-space: nowrap
}

.tv-page .tu-courses-btn:hover {
    border-color: var(--tv-accent)
}

.tv-page .tu-credit-note {
    font-size: 14px;
    padding: 10px 14px;
    background: var(--tv-bg);
    border-left: 3px solid var(--tv-accent)
}

.tv-page .tu-credit-note.warn {
    background: #fff4d6;
    border-left-color: #e0a100;
    color: #8a5a00
}

.tv-page .tu-course-form {
    background: var(--tv-bg);
    border: 1px solid var(--tv-border);
    padding: 16px 16px 4px
}

.tv-page .tu-course-form h6 {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--tv-text)
}

.tv-page .tu-course-form label {
    font-size: 13px
}

@media (max-width:991px) {
    .tv-page .tu-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr))
    }
}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var CTRL = "../new_files/Teaching_Units/controller.php";
var CURRENT_UE = null;
var LOADER = "<img src='../../img/ajax_loader.gif' width='15'> ";

function escapeHtml(s) {
    return $('<div>').text(s == null ? '' : s).html();
}

$(document).ready(function() {
    var unitsDt = $('#units_table').DataTable({
        "aLengthMenu": [
            [25, 50, 100, -1],
            [25, 50, 100, "Tout"]
        ],
        "iDisplayLength": 25,
        "autoWidth": false,
        "order": [],
        "language": {
            "lengthMenu": "Afficher _MENU_ éléments",
            "search": "Rechercher :",
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
            "infoEmpty": "Affichage de 0 à 0 sur 0 élément",
            "infoFiltered": "(filtré sur _MAX_ éléments au total)",
            "zeroRecords": "Aucun élément correspondant trouvé",
            "emptyTable": "Aucune unité d'enseignement enregistrée",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précédent"
            }
        }
    });

    // column filters: exact match on faculty / programme / level / semester
    $('.tu-filter').on('change', function() {
        var v = $(this).val();
        var esc = v.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        unitsDt.column($(this).data('col')).search(v ? '^' + esc + '$' : '', true, false).draw();
    });

    $('#tv-new-btn').on('click', function() {
        $('#new-unit').collapse('toggle');
    });

    // ---------------- cascades (add form) ----------------
    $('#fac_id').on('change', function() {
        resetSel('#level_id', "Choisissez d'abord un type de programme");
        resetSel('#dept_id', 'Tous les départements');
        resetSel('#opt_id', 'Toutes les options');
        loadInto('get_programmes', {
                fac_id: $(this).val()
            }, '#prg_type_id', 'Choisir un type de programme', 'prg_type_id', 'prg_type_full_name',
            '#sp_prg');
    });
    $('#prg_type_id').on('change', function() {
        var p = $(this).val();
        resetSel('#opt_id', 'Toutes les options');
        loadInto('get_levels', {
            prg_type_id: p
        }, '#level_id', 'Choisir un niveau', 'level_id', 'level_full_name', '#sp_level');
        loadInto('get_departments', {
            prg_type_id: p
        }, '#dept_id', 'Tous les départements', 'dept_id', 'dept_full_name', '#sp_dept');
    });
    $('#dept_id').on('change', function() {
        loadInto('get_options', {
            dept_id: $(this).val()
        }, '#opt_id', 'Toutes les options', 'opt_id', 'opt_full_name', '#sp_opt');
    });

    // ---------------- cascades (edit modal) ----------------
    $('#e_fac_id').on('change', function() {
        resetSel('#e_level_id', "Choisissez d'abord un type de programme");
        resetSel('#e_dept_id', 'Tous les départements');
        resetSel('#e_opt_id', 'Toutes les options');
        loadInto('get_programmes', {
                fac_id: $(this).val()
            }, '#e_prg_type_id', 'Choisir un type de programme', 'prg_type_id',
            'prg_type_full_name', '#e_sp_prg');
    });
    $('#e_prg_type_id').on('change', function() {
        var p = $(this).val();
        resetSel('#e_opt_id', 'Toutes les options');
        loadInto('get_levels', {
            prg_type_id: p
        }, '#e_level_id', 'Choisir un niveau', 'level_id', 'level_full_name', '#e_sp_level');
        loadInto('get_departments', {
            prg_type_id: p
        }, '#e_dept_id', 'Tous les départements', 'dept_id', 'dept_full_name', '#e_sp_dept');
    });
    $('#e_dept_id').on('change', function() {
        loadInto('get_options', {
            dept_id: $(this).val()
        }, '#e_opt_id', 'Toutes les options', 'opt_id', 'opt_full_name', '#e_sp_opt');
    });

    // ---------------- save unit ----------------
    $('#unit_form').on('submit', function(e) {
        e.preventDefault();
        var data = unitPayload('');
        if (!data) {
            return;
        }
        data.action = 'save_unit';
        $('#sp_save').html(LOADER);
        post(data, function(d) {
            $('#sp_save').html('');
            if (d.status == 200) {
                pop_up_success(d.message);
                setTimeout(function() {
                    location.reload();
                }, 900);
            } else {
                pop_wrong(d.message);
            }
        }, function() {
            $('#sp_save').html('');
        });
    });

    // ---------------- edit unit ----------------
    $(document).on('click', '.edit_unit', function() {
        var id = $(this).data('id');
        post({
            action: 'view_unit',
            ue_id: id
        }, function(d) {
            if (d.status != 200) {
                pop_wrong(d.message);
                return;
            }
            var u = d.data;
            $('#e_ue_id').val(u.ue_id);
            $('#e_ue_code').val(u.ue_code);
            $('#e_ue_name').val(u.ue_name);
            $('#e_semester_no').val(u.semester_no);
            $('#e_credits').val(u.credits);
            $('#e_pass_mark').val(u.pass_mark);
            $('#e_fac_id').val(u.fac_id);

            // refill each dependent list, then select the saved value, in order
            loadInto('get_programmes', {
                        fac_id: u.fac_id
                    }, '#e_prg_type_id', 'Choisir un type de programme', 'prg_type_id',
                    'prg_type_full_name', '#e_sp_prg', u.prg_type_id)
                .then(function() {
                    return $.when(
                        loadInto('get_levels', {
                                prg_type_id: u.prg_type_id
                            }, '#e_level_id', 'Choisir un niveau', 'level_id',
                            'level_full_name', '#e_sp_level', u.level_id),
                        loadInto('get_departments', {
                                prg_type_id: u.prg_type_id
                            }, '#e_dept_id', 'Tous les départements', 'dept_id',
                            'dept_full_name', '#e_sp_dept', u.dept_id)
                    );
                })
                .then(function() {
                    if (u.dept_id) {
                        return loadInto('get_options', {
                                dept_id: u.dept_id
                            }, '#e_opt_id', 'Toutes les options', 'opt_id',
                            'opt_full_name', '#e_sp_opt', u.opt_id);
                    }
                    resetSel('#e_opt_id', 'Toutes les options');
                });

            $('#unitModal').modal('show');
        });
    });

    $('#unit_edit_form').on('submit', function(e) {
        e.preventDefault();
        var data = unitPayload('e_');
        if (!data) {
            return;
        }
        data.action = 'update_unit';
        data.ue_id = $('#e_ue_id').val();
        $('#e_sp_save').html(LOADER);
        post(data, function(d) {
            $('#e_sp_save').html('');
            if (d.status == 200) {
                $('#unitModal').modal('hide');
                pop_up_success(d.message);
                setTimeout(function() {
                    location.reload();
                }, 900);
            } else {
                pop_wrong(d.message);
            }
        }, function() {
            $('#e_sp_save').html('');
        });
    });

    $(document).on('change', '.toggle_unit', function() {
        var $cb = $(this);
        post({
            action: 'toggle_unit',
            ue_id: $cb.data('id')
        }, function(d) {
            if (d.status == 200) {
                pop_up_success(d.message);
            } else {
                $cb.prop('checked', !$cb.prop('checked'));
                pop_wrong(d.message);
            }
        }, function() {
            $cb.prop('checked', !$cb.prop('checked'));
        });
    });

    // ---------------- courses ----------------
    $(document).on('click', '.open_courses', function() {
        CURRENT_UE = $(this).data('id');
        $('#coursesModal').data('dirty', false);
        resetCourseForm();
        loadCourses();
        $('#coursesModal').modal('show');
    });

    // keep CAT % + Exam % at 100 while typing
    $('#c_cat_weight').on('input', function() {
        var v = parseFloat($(this).val());
        if (!isNaN(v) && v >= 0 && v <= 100) {
            $('#c_exam_weight').val((100 - v).toFixed(2).replace(/\.00$/, ''));
        }
    });
    $('#c_exam_weight').on('input', function() {
        var v = parseFloat($(this).val());
        if (!isNaN(v) && v >= 0 && v <= 100) {
            $('#c_cat_weight').val((100 - v).toFixed(2).replace(/\.00$/, ''));
        }
    });

    $('#course_form').on('submit', function(e) {
        e.preventDefault();
        var name = $('#c_course_name').val().trim();
        if (!name) {
            pop_wrong("L'intitulé du cours est obligatoire");
            return;
        }
        var cat = parseFloat($('#c_cat_weight').val()) || 0;
        var exam = parseFloat($('#c_exam_weight').val()) || 0;
        if (Math.abs(cat + exam - 100) > 0.001) {
            pop_wrong("TP/Interro % et Examen % doivent totaliser 100");
            return;
        }

        var editing = $('#c_course_id').val() !== '';
        var data = {
            action: editing ? 'update_course' : 'save_course',
            course_id: $('#c_course_id').val(),
            ue_id: CURRENT_UE,
            course_code: $('#c_course_code').val(),
            course_name: name,
            credits: $('#c_credits').val(),
            hours: $('#c_hours').val(),
            cat_weight: cat,
            exam_weight: exam,
            pass_mark: $('#c_pass_mark').val(),
            exam_min_mark: $('#c_exam_min_mark').val()
        };
        $('#c_sp_save').html(LOADER);
        post(data, function(d) {
            $('#c_sp_save').html('');
            if (d.status == 200) {
                $('#coursesModal').data('dirty', true);
                pop_up_success(d.message);
                resetCourseForm();
                loadCourses();
            } else {
                pop_wrong(d.message);
            }
        }, function() {
            $('#c_sp_save').html('');
        });
    });

    $(document).on('click', '.edit_course', function() {
        post({
            action: 'view_course',
            course_id: $(this).data('id')
        }, function(d) {
            if (d.status != 200) {
                pop_wrong(d.message);
                return;
            }
            var c = d.data;
            $('#c_course_id').val(c.course_id);
            $('#c_course_code').val(c.course_code || '');
            $('#c_course_name').val(c.course_name);
            $('#c_credits').val(c.credits);
            $('#c_hours').val(c.hours || '');
            $('#c_cat_weight').val(c.cat_weight);
            $('#c_exam_weight').val(c.exam_weight);
            $('#c_pass_mark').val(c.pass_mark);
            $('#c_exam_min_mark').val(c.exam_min_mark || '');
            $('#c_form_title').html('<i class="fas fa-pen"></i> Modifier le cours');
            $('#c_save_label').text('Enregistrer');
            $('#c_cancel_edit').attr('hidden', false);
            $('#c_course_name').focus();
        });
    });

    $('#c_cancel_edit').on('click', resetCourseForm);

    $(document).on('change', '.toggle_course', function() {
        var $cb = $(this);
        post({
            action: 'toggle_course',
            course_id: $cb.data('id')
        }, function(d) {
            if (d.status == 200) {
                $('#coursesModal').data('dirty', true);
                pop_up_success(d.message);
                loadCourses();
            } else {
                $cb.prop('checked', !$cb.prop('checked'));
                pop_wrong(d.message);
            }
        }, function() {
            $cb.prop('checked', !$cb.prop('checked'));
        });
    });

    // the unit list shows course counts, so refresh it once courses changed
    $('#coursesModal').on('hidden.bs.modal', function() {
        if ($(this).data('dirty')) {
            location.reload();
        }
    });
});

// ---------------- helpers ----------------
function post(data, ok, fail) {
    return $.ajax({
        url: CTRL,
        type: "POST",
        dataType: "JSON",
        data: data,
        success: ok,
        error: function() {
            if (fail) {
                fail();
            }
            pop_wrong("La requête a échoué");
        }
    });
}

function resetSel(sel, placeholder) {
    $(sel).html("<option value=''>" + placeholder + "</option>").trigger('change.select2');
}

/** Fills a select from an endpoint; optionally selects `value` once loaded. Returns the request. */
function loadInto(action, payload, sel, placeholder, idKey, nameKey, spinner, value) {
    resetSel(sel, placeholder);
    var firstVal = Object.keys(payload).map(function(k) {
        return payload[k];
    })[0];
    if (!firstVal) {
        return $.Deferred().resolve().promise();
    }
    $(spinner).html(LOADER);
    payload.action = action;
    return $.ajax({
            url: CTRL,
            type: "POST",
            dataType: "JSON",
            data: payload
        })
        .done(function(d) {
            $(spinner).html('');
            if (!d || d.status != 200) {
                return;
            }
            var html = "<option value=''>" + placeholder + "</option>";
            $.each(d.data, function(i, r) {
                html += "<option value='" + r[idKey] + "'>" + escapeHtml(r[nameKey]) + "</option>";
            });
            $(sel).html(html);
            if (value) {
                $(sel).val(value);
            }
            $(sel).trigger('change.select2');
        })
        .fail(function() {
            $(spinner).html('');
        });
}

function unitPayload(p) {
    var d = {
        ue_code: $('#' + p + 'ue_code').val().trim(),
        ue_name: $('#' + p + 'ue_name').val().trim(),
        prg_type_id: $('#' + p + 'prg_type_id').val(),
        level_id: $('#' + p + 'level_id').val(),
        semester_no: $('#' + p + 'semester_no').val(),
        dept_id: $('#' + p + 'dept_id').val(),
        opt_id: $('#' + p + 'opt_id').val(),
        credits: $('#' + p + 'credits').val(),
        pass_mark: $('#' + p + 'pass_mark').val()
    };
    if (!d.prg_type_id) {
        pop_wrong("Choisissez le type de programme");
        return null;
    }
    if (!d.level_id) {
        pop_wrong("Choisissez le niveau");
        return null;
    }
    if (!d.ue_code || !d.ue_name) {
        pop_wrong("Le code et l'intitulé de l'unité sont obligatoires");
        return null;
    }
    return d;
}

function num(v) {
    return v === null || v === '' ? '' : String(parseFloat(v));
}

function loadCourses() {
    $('#c_body').html('<tr><td colspan="9" class="text-center text-muted">Chargement...</td></tr>');
    post({
        action: 'load_courses',
        ue_id: CURRENT_UE
    }, function(d) {
        if (d.status != 200) {
            $('#c_body').html('<tr><td colspan="9" class="text-danger">' + escapeHtml(d.message) +
                '</td></tr>');
            return;
        }
        $('#c_unit_title').text(d.unit.ue_code + ' : ' + d.unit.ue_name);

        var total = 0,
            html = '';
        $.each(d.data, function(i, c) {
            if (c.status == 1) {
                total += parseFloat(c.credits) || 0;
            }
            html += '<tr' + (c.status == 1 ? '' : ' class="text-muted"') + '>' +
                '<td>' + escapeHtml(c.course_code || '') + '</td>' +
                '<td>' + escapeHtml(c.course_name) + (c.status == 1 ? '' :
                    ' <span class="tv-tag red">Inactif</span>') + '</td>' +
                '<td>' + num(c.credits) + '</td>' +
                '<td>' + (c.hours || '') + '</td>' +
                '<td>' + num(c.cat_weight) + '</td>' +
                '<td>' + num(c.exam_weight) + '</td>' +
                '<td>' + num(c.pass_mark) + '</td>' +
                '<td>' + num(c.exam_min_mark) + '</td>' +
                '<td class="tv-right" style="white-space:nowrap">' +
                '<div class="tv-row-actions"><button type="button" class="tv-icon-btn edit_course" data-id="' +
                c.course_id + '" title="Modifier"><i class="fas fa-pen"></i></button>' +
                '<label class="custom-switch mb-0" style="padding-left:0" title="Activer / désactiver"><input type="checkbox" class="custom-switch-input toggle_course" data-id="' +
                c.course_id + '"' + (c.status == 1 ? ' checked' : '') +
                '><span class="custom-switch-indicator"></span></label></div>' +
                '</td></tr>';
        });
        $('#c_body').html(html ||
            '<tr><td colspan="9" class="text-center text-muted">Aucun cours pour le moment.</td></tr>');

        var unitCredits = parseFloat(d.unit.credits) || 0;
        var note = 'Cours actifs : <b>' + num(total) + '</b> crédits · Unité : <b>' + num(unitCredits) +
            '</b> crédits';
        var off = d.data.length && Math.abs(total - unitCredits) > 0.001;
        if (off) {
            note = '<i class="fas fa-exclamation-triangle"></i> ' + note +
                '. Les deux totaux ne correspondent pas.';
        }
        $('#c_credit_note').toggleClass('warn', !!off).html(note);
    });
}

function resetCourseForm() {
    $('#course_form')[0] && $('#course_form')[0].reset();
    $('#c_course_id').val('');
    $('#c_cat_weight').val(40);
    $('#c_exam_weight').val(60);
    $('#c_pass_mark').val(10);
    $('#c_form_title').html('<i class="fas fa-plus"></i> Ajouter un cours à cette unité');
    $('#c_save_label').text('Ajouter');
    $('#c_cancel_edit').attr('hidden', true);
}

function pop_wrong(f) {
    iziToast.warning({
        title: 'Erreur',
        message: f,
        position: 'topCenter'
    });
}

function pop_up_success(f) {
    iziToast.success({
        title: 'Info',
        message: f,
        position: 'topCenter'
    });
}
</script>