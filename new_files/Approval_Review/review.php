<?php
// Approval review.
//
// What is shown depends on the form that was submitted, but no form is named
// here: every card is built from the data that actually exists for this
// request, and a card with nothing in it is not rendered. So an undergraduate
// application shows its two options and secondary school, a Master request
// shows prior studies, a change of domain shows the requested new domain, and
// a re-integration shows its interruption details.
require_once(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

// Router entries - keep in step with Approval_Review/index.php and academic/edu.php.
$ROUTE_LIST   = 'edu?mis=apprvw';
$ROUTE_REVIEW = 'edu?mis=approval_review';

$request_id = (int) ($_GET['req'] ?? 0);

// project root as a URL path ("" live, "/academic" under XAMPP) so stored file paths resolve in both places
$tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
$tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));
$fileUrl = function($path) use ($tv_base_url){
    return (strpos((string)$path, '/') === 0 ? $tv_base_url : '').$path;
};
$fmt = function($d, $withTime = true){ return $d ? date($withTime ? 'd/m/Y H:i' : 'd/m/Y', strtotime($d)) : ''; };

// ---------------------------------------------------------------- helpers
if(!function_exists('rv_name')){
    /** One name out of a lookup table, or '' when the id is empty/unknown. */
    function rv_name($conn, $table, $id_col, $name_col, $id){
        if($id === null || $id === '' || $id === '0'){ return ''; }
        $st = $conn->prepare("SELECT $name_col FROM $table WHERE $id_col = :id LIMIT 1");
        $st->execute([':id' => $id]);
        $v = $st->fetchColumn();
        return $v !== false ? $v : '';
    }

    /** Country fields hold a tbl_country id on the newer forms, free text on older rows. */
    function rv_country($conn, $v){
        if($v === null || $v === ''){ return ''; }
        if(ctype_digit((string) $v)){
            $n = rv_name($conn, 'tbl_country', 'cntr_id', 'cntr_name', $v);
            return $n !== '' ? $n : $v;
        }
        return $v;
    }

    /** Label / value pairs, skipping empty values. Returns '' when all are empty. */
    function rv_rows($pairs){
        $html = '';
        foreach($pairs as $label => $value){
            if($value === null || trim((string) $value) === ''){ continue; }
            $html .= '<dt>'.htmlspecialchars($label).'</dt><dd>'.nl2br(htmlspecialchars((string) $value)).'</dd>';
        }
        return $html === '' ? '' : '<dl class="tv-info rv-info">'.$html.'</dl>';
    }

    /** A titled block inside the Profil tab. Nothing is printed when $body is empty. */
    function rv_section($title, $icon, $body, $wide = false){
        if(trim($body) === ''){ return; }
        echo '<div class="'.($wide ? 'col-12' : 'col-12 col-lg-6').'"><div class="rv-section">'
           . '<h5><i class="'.$icon.'"></i> '.$title.'</h5>'.$body.'</div></div>';
    }
}

// ---------------------------------------------------------------- load
$request = null;
$denied  = false;

if(!can('can_review_applications')){
    $denied = true;
} elseif($request_id > 0){
    if(!approval_can_view($conn, $request_id)){
        $denied = true;
    } else {
        $st = $conn->prepare("SELECT r.*, f.form_name
                              FROM tbl_approval_requests r
                              LEFT JOIN tbl_form_types f ON f.form_id = r.form_id
                              WHERE r.request_id = :id");
        $st->execute([':id' => $request_id]);
        $request = $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}

if($request){
    $st = $conn->prepare("SELECT * FROM tbl_applicants WHERE applicant_id = :id");
    $st->execute([':id' => $request['applicant_id']]);
    $a = $st->fetch(PDO::FETCH_ASSOC) ?: [];

    // route + where the request sits on it
    $st = $conn->prepare("SELECT w.step_order, w.step_name, w.step_type, ro.role
                          FROM tbl_approval_workflows w
                          LEFT JOIN tbl_user_roles ro ON ro.role_id = w.role_id
                          WHERE w.form_id = :f AND w.status = 1
                          ORDER BY w.step_order ASC");
    $st->execute([':f' => $request['form_id']]);
    $route = $st->fetchAll(PDO::FETCH_ASSOC);

    $may_act    = may_act_on($conn, $request);
    $may_select = $may_act && can('can_select_choice');
    $may_reopen = $request['status'] === 'rejected' && can('can_reopen_rejected');

    // courses assigned during review - wrapped so the page still opens if the
    // course tables have not been migrated yet
    $may_edit_courses = $may_act && can('can_assign_courses');
    $courses_all = [];
    $course_choices = [];
    $documents = [];
    $form_setting = approval_form_setting($conn, $request['form_id']);
    try {
        $courses_all = approval_assigned_courses($conn, $request_id, true);
        if($may_edit_courses){
            $assigned_ids = [];
            foreach($courses_all as $c){ if($c['status'] === 'active'){ $assigned_ids[] = (int) $c['course_id']; } }
            foreach(approval_course_choices($conn, $request) as $c){
                if(!in_array((int) $c['course_id'], $assigned_ids, true)){ $course_choices[] = $c; }
            }
        }
        $st = $conn->prepare("SELECT * FROM tbl_approval_documents WHERE request_id = :id ORDER BY created_at DESC, document_id DESC");
        $st->execute([':id' => $request_id]);
        $documents = $st->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
        $courses_all = $course_choices = $documents = [];
    }
    $courses_active  = array_values(array_filter($courses_all, function($c){ return $c['status'] === 'active'; }));
    $courses_removed = array_values(array_filter($courses_all, function($c){ return $c['status'] === 'removed'; }));
    $may_send_docs   = $request['status'] === 'approved' && can('can_final_approve');

    // programme options - only for the application forms (no source table)
    $options = [];
    if(empty($request['source_table'])){
        $st = $conn->prepare("SELECT prg.Aprg_id, camp.camp_full_name, fac.fac_full_name,
                                     pt.prg_type_full_name, dept.dept_full_name,
                                     opt.opt_full_name, lvl.level_full_name
                              FROM tbl_admittedPRG prg
                              LEFT JOIN tbl_campus camp ON camp.camp_id = prg.cump_id
                              LEFT JOIN tbl_faculty fac ON fac.fac_id = prg.fac_id
                              LEFT JOIN tbl_program_type pt ON pt.prg_type_id = prg.prg_type
                              LEFT JOIN tbl_department dept ON dept.dept_id = prg.dept_id
                              LEFT JOIN tbl_option opt ON opt.opt_id = prg.splz
                              LEFT JOIN tbl_level lvl ON lvl.level_id = prg.level
                              WHERE prg.Stu_code = :code
                              ORDER BY prg.Aprg_id ASC");
        $st->execute([':code' => $request['application_code']]);
        $options = $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // the form's own detail row, if it has one
    $detail = null;
    if($request['source_table'] === 'tbl_domain_change_requests' || $request['source_table'] === 'tbl_integration_requests'){
        $st = $conn->prepare("SELECT * FROM ".$request['source_table']." WHERE request_id = :id");
        $st->execute([':id' => $request['source_id']]);
        $detail = $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // prior studies - keep only the columns this form actually filled in
    $st = $conn->prepare("SELECT * FROM tbl_applicant_prior_studies WHERE applicant_id = :id ORDER BY study_year ASC");
    $st->execute([':id' => $request['applicant_id']]);
    $prior = $st->fetchAll(PDO::FETCH_ASSOC);
    $prior_labels = [
        'study_year' => 'Année', 'establishment' => 'Établissement', 'prior_field' => 'Filière',
        'prior_department' => 'Département', 'promotion' => 'Promotion', 'percentage' => '%',
        'mention' => 'Mention', 'jury_decision' => 'Décision du jury',
        'non_capitalized_credits' => 'Crédits non capitalisés', 'number_of_failures' => 'Échecs',
        'session' => 'Session'
    ];
    $prior_cols = [];
    foreach($prior_labels as $col => $label){
        foreach($prior as $p){
            if(isset($p[$col]) && trim((string) $p[$col]) !== ''){ $prior_cols[$col] = $label; break; }
        }
    }

    // documents - names come from the form-type list, falling back to the old
    // programme list for uploads made before the switch
    $st = $conn->prepare("SELECT d.file_path, d.uploaded_at,
                                 COALESCE(ft.document_name, dt.document_name) AS document_name
                          FROM tbl_applicant_documents d
                          LEFT JOIN tbl_formtype_document ft ON ft.doc_id = d.doc_id
                          LEFT JOIN tbl_document_type dt ON dt.doc_id = d.doc_id
                          WHERE d.applicant_id = :id
                          ORDER BY d.uploaded_at ASC");
    $st->execute([':id' => $request['applicant_id']]);
    $docs = $st->fetchAll(PDO::FETCH_ASSOC);

    // history
    $st = $conn->prepare("SELECT act.step_order, act.action, act.comment, act.acted_at,
                                 u.first_name, u.family_name, ro.role
                          FROM tbl_approval_actions act
                          LEFT JOIN tbl_users u ON u.id = act.user_id
                          LEFT JOIN tbl_user_roles ro ON ro.role_id = act.role_id
                          WHERE act.request_id = :id
                          ORDER BY act.acted_at ASC, act.action_id ASC");
    $st->execute([':id' => $request_id]);
    $history = $st->fetchAll(PDO::FETCH_ASSOC);
}

$status_tag = [
    'pending'   => ['amber', 'En attente'],
    'approved'  => ['green', 'Approuvée'],
    'rejected'  => ['red',   'Rejetée'],
    'cancelled' => ['blue',  'Annulée']
];
$action_tag = [
    'approved' => ['green', 'Approuvé'],
    'invoiced' => ['green', 'Facturé'],
    'rejected' => ['red',   'Rejeté'],
    'reopened' => ['amber', 'Rouvert'],
    'selected' => ['blue',  'Option choisie']
];
$doc_label = ['admission_letter' => "Lettre d'admission", 'registration_proof' => "Attestation d'inscription"];
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3><?php echo $request ? htmlspecialchars($request['application_code']) : 'Demande introuvable'; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="<?php echo $ROUTE_LIST; ?>">Revue des candidatures</a></div>
                <div class="breadcrumb-item">Examen</div>
            </div>
        </div>

    <?php if($denied): ?>
        <div class="alert alert-warning"><i class="fas fa-lock"></i> Vous n'avez pas accès à cette demande.</div>

    <?php elseif(!$request): ?>
        <div class="alert alert-danger">Demande introuvable.</div>
        <a href="<?php echo $ROUTE_LIST; ?>" class="tv-btn tv-btn-outline"><i class="fas fa-arrow-left"></i> Retour à la liste</a>

    <?php else: ?>
        <?php
            $st = $status_tag[$request['status']] ?? ['blue', ucfirst($request['status'])];
            $full_name = trim(($a['fname'] ?? '').' '.($a['mname'] ?? '').' '.($a['lname'] ?? ''));
            $has_programme = !empty($options) || $may_edit_courses || !empty($courses_all);
            $current_step_name = '';
            foreach($route as $s){ if($s['step_order'] == $request['current_step']){ $current_step_name = $s['step_name']; } }
        ?>

        <!-- applicant header -->
        <div class="tv-card rv-head">
            <a href="<?php echo $ROUTE_LIST; ?>" class="rv-back" title="Retour à la liste"><i class="fas fa-arrow-left"></i></a>
            <span class="tv-avatar rv-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($a['fname'] ?? '', 0, 1).mb_substr($a['lname'] ?? '', 0, 1))); ?></span>
            <div class="rv-who">
                <h2><?php echo htmlspecialchars($full_name); ?></h2>
                <div class="rv-meta">
                    <span><i class="far fa-file-alt"></i> <?php echo htmlspecialchars($request['form_name'] ?? ''); ?></span>
                    <?php if(!empty($a['email'])): ?><span><i class="far fa-envelope"></i> <?php echo htmlspecialchars($a['email']); ?></span><?php endif; ?>
                    <?php if(!empty($a['phone'])): ?><span><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($a['phone']); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- ================= main: tabbed content ================= -->
            <div class="col-12 col-xl-8">
                <div class="rv-tabs" role="tablist">
                    <button type="button" class="rv-tab active" data-tab="profil"><i class="far fa-user"></i> Profil</button>
                    <?php if($has_programme): ?>
                    <button type="button" class="rv-tab" data-tab="programme"><i class="fas fa-list-ol"></i> Programme
                        <?php if(!empty($courses_active)): ?><span class="rv-count"><?php echo count($courses_active); ?></span><?php endif; ?></button>
                    <?php endif; ?>
                    <button type="button" class="rv-tab" data-tab="documents"><i class="fas fa-paperclip"></i> Documents <span class="rv-count"><?php echo count($docs); ?></span></button>
                    <button type="button" class="rv-tab" data-tab="historique"><i class="fas fa-history"></i> Historique <span class="rv-count"><?php echo count($history); ?></span></button>
                </div>

                <!-- ---------- Profil ---------- -->
                <div class="rv-pane active" id="tab-profil">
                    <div class="tv-card">
                        <div class="row">
                            <?php
                            rv_section('Informations personnelles', 'far fa-user', rv_rows([
                                'Prénom'                => $a['fname'] ?? '',
                                'Post-nom'              => $a['mname'] ?? '',
                                'Nom'                   => $a['lname'] ?? '',
                                'Genre'                 => ($a['gender'] ?? '') === 'M' ? 'Masculin' : (($a['gender'] ?? '') === 'F' ? 'Féminin' : ''),
                                'Date de naissance'     => !empty($a['dob']) ? $fmt($a['dob'], false) : '',
                                'Lieu de naissance'     => $a['place_of_birth'] ?? '',
                                'Pays de naissance'     => rv_country($conn, $a['country_of_birth'] ?? ''),
                                'Nationalité'           => rv_country($conn, $a['nationality_id'] ?? ''),
                                'Groupe sanguin'        => $a['blood_type'] ?? '',
                                'État civil'            => $a['marital_status'] ?? '',
                                'Religion'              => $a['religious_affiliation'] ?? '',
                                'Nom du père'           => $a['father_name'] ?? '',
                                'Nom de la mère'        => $a['mother_name'] ?? ''
                            ]));

                            rv_section('Origine et contact', 'fas fa-map-marker-alt', rv_rows([
                                'Pays de résidence'           => rv_country($conn, $a['country_id'] ?? ''),
                                'Province d\'origine des parents' => $a['parents_province_origin'] ?? '',
                                'Territoire d\'origine'       => $a['territory_of_origin'] ?? '',
                                'District'                    => $a['district'] ?? '',
                                'Adresse du candidat'         => $a['candidate_address'] ?? '',
                                'E-mail'                      => $a['email'] ?? '',
                                'Téléphone'                   => $a['phone'] ?? '',
                                'Téléphone du père'           => $a['parent_phone'] ?? '',
                                'Téléphone de la mère'        => $a['ref_phone'] ?? ''
                            ]));

                            rv_section('Études secondaires', 'fas fa-school', rv_rows([
                                'École'                   => $a['secondary_school_name'] ?? '',
                                'Province de l\'école'    => $a['secondary_school_province'] ?? '',
                                'Territoire de l\'école'  => $a['secondary_school_territory'] ?? '',
                                'Pays de l\'école'        => rv_country($conn, $a['secondary_school_territory_country'] ?? ''),
                                'Section'                 => $a['humanities_section'] ?? '',
                                'Statut de l\'école'      => $a['secondary_school_status'] ?? '',
                                'Année du diplôme'        => $a['diploma_year'] ?? '',
                                'Pourcentage'             => $a['diploma_percentage'] ?? '',
                                'N° du diplôme d\'État'   => $a['state_diploma_number'] ?? '',
                                'Activités professionnelles' => $a['professional_activities'] ?? ''
                            ]));

                            if($detail && $request['source_table'] === 'tbl_domain_change_requests'){
                                rv_section('Nouveau domaine demandé', 'fas fa-exchange-alt', rv_rows([
                                    'Faculté'           => rv_name($conn, 'tbl_faculty', 'fac_id', 'fac_full_name', $detail['requested_fac_id'] ?? ''),
                                    'Type de programme' => rv_name($conn, 'tbl_program_type', 'prg_type_id', 'prg_type_full_name', $detail['requested_prg_type_id'] ?? ''),
                                    'Département'       => rv_name($conn, 'tbl_department', 'dept_id', 'dept_full_name', $detail['requested_dept_id'] ?? ''),
                                    'Option'            => rv_name($conn, 'tbl_option', 'opt_id', 'opt_full_name', $detail['requested_opt_id'] ?? ''),
                                    'Classe demandée'   => rv_name($conn, 'tbl_level', 'level_id', 'level_full_name', $detail['requested_level_id'] ?? '')
                                ]));
                            }

                            if($detail && $request['source_table'] === 'tbl_integration_requests'){
                                $tr = $detail['transcript_attached'] ?? '';
                                rv_section('Réintégration', 'fas fa-undo', rv_rows([
                                    'Année d\'interruption'        => $detail['interruption_year'] ?? '',
                                    'Faculté demandée'             => rv_name($conn, 'tbl_faculty', 'fac_id', 'fac_full_name', $detail['requested_fac_id'] ?? ''),
                                    'Type de programme'            => rv_name($conn, 'tbl_program_type', 'prg_type_id', 'prg_type_full_name', $detail['requested_prg_type_id'] ?? ''),
                                    'Département'                  => rv_name($conn, 'tbl_department', 'dept_id', 'dept_full_name', $detail['requested_dept_id'] ?? ''),
                                    'Option'                       => rv_name($conn, 'tbl_option', 'opt_id', 'opt_full_name', $detail['requested_opt_id'] ?? ''),
                                    'Classe demandée'              => rv_name($conn, 'tbl_level', 'level_id', 'level_full_name', $detail['requested_level_id'] ?? ''),
                                    'Dernière classe suivie'       => $detail['last_attended'] ?? '',
                                    'Dernier résultat (%)'         => $detail['last_result_percentage'] ?? '',
                                    'Session du dernier résultat'  => $detail['last_result_session'] ?? '',
                                    'Motif de la suspension'       => $detail['suspension_reason'] ?? '',
                                    'Documents laissés au secrétariat' => $detail['documents_left'] ?? '',
                                    'Relevé joint'                 => $tr === '' || $tr === null ? '' : ($tr == 1 ? 'Oui' : 'Non'),
                                    'Obligations envers la bibliothèque' => $detail['obligations_library'] ?? '',
                                    'Obligations financières'      => $detail['obligations_finance'] ?? '',
                                    'Activités pendant la suspension' => $detail['activities_during_suspension'] ?? '',
                                    'Motif de la reprise'          => $detail['resume_reason'] ?? '',
                                    'État de santé'                => $detail['health_status'] ?? '',
                                    'Capacité à payer les frais'   => $detail['tuition_ability'] ?? ''
                                ]), true);
                            }

                            if($prior && $prior_cols){
                                $b = '<div class="table-responsive"><table class="tv-table rv-table"><thead><tr>';
                                foreach($prior_cols as $label){ $b .= '<th>'.htmlspecialchars($label).'</th>'; }
                                $b .= '</tr></thead><tbody>';
                                foreach($prior as $p){
                                    $b .= '<tr>';
                                    foreach($prior_cols as $col => $label){ $b .= '<td>'.htmlspecialchars((string) ($p[$col] ?? '')).'</td>'; }
                                    $b .= '</tr>';
                                }
                                $b .= '</tbody></table></div>';
                                rv_section('Études antérieures', 'fas fa-graduation-cap', $b, true);
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php if($has_programme): ?>
                <!-- ---------- Programme ---------- -->
                <div class="rv-pane" id="tab-programme">
                    <?php if(!empty($options)): ?>
                    <div class="tv-card">
                        <h4 class="tv-card-title"><i class="fas fa-list-ol"></i> Options demandées</h4>
                        <?php if($may_select): ?>
                        <p class="tv-card-sub" style="margin-top:-10px">Choisissez l'option accordée à ce candidat. Les étapes suivantes confirmeront le même placement.</p>
                        <?php endif; ?>
                        <div class="rv-options">
                            <?php foreach($options as $n => $o):
                                $is_sel = !empty($request['selected_aprg_id']) && $o['Aprg_id'] == $request['selected_aprg_id']; ?>
                            <label class="rv-option <?php echo $is_sel ? 'selected' : ''; ?> <?php echo $may_select ? 'pickable' : ''; ?>">
                                <span class="rv-option-pick">
                                    <?php if($may_select): ?>
                                    <input type="radio" name="pick_aprg" value="<?php echo (int) $o['Aprg_id']; ?>" <?php echo $is_sel ? 'checked' : ''; ?>>
                                    <?php elseif($is_sel): ?>
                                    <i class="fas fa-check"></i>
                                    <?php else: ?>
                                    <?php echo $n + 1; ?>
                                    <?php endif; ?>
                                </span>
                                <span class="rv-option-body">
                                    <b><?php echo htmlspecialchars($o['dept_full_name'] ?? ''); ?><?php if(!empty($o['opt_full_name']) && $o['opt_full_name'] !== $o['dept_full_name']): ?> · <?php echo htmlspecialchars($o['opt_full_name']); ?><?php endif; ?></b>
                                    <small><?php echo htmlspecialchars(implode(' · ', array_filter([$o['prg_type_full_name'] ?? '', $o['level_full_name'] ?? '', $o['fac_full_name'] ?? '', $o['camp_full_name'] ?? '']))); ?></small>
                                </span>
                                <?php if($is_sel): ?><span class="tv-tag green">Accordée</span><?php else: ?><span class="tv-tag blue">Choix <?php echo $n + 1; ?></span><?php endif; ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($may_edit_courses || !empty($courses_all)): ?>
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title"><i class="fas fa-book"></i> Cours à suivre</h4>
                            <?php if(!empty($courses_active)): ?>
                            <span class="tv-pill-count"><?php echo count($courses_active); ?> cours ·
                            <?php $tc = 0; foreach($courses_active as $c){ $tc += (float) $c['credits']; } echo rtrim(rtrim(number_format($tc, 2, '.', ''), '0'), '.'); ?> crédits</span>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table class="tv-table rv-table">
                                <thead>
                                    <tr>
                                        <th>Code</th><th>Cours</th><th>UE</th><th>Niveau</th><th>Sem.</th>
                                        <th>Crédits</th><th>Ajouté par</th>
                                        <?php if($may_edit_courses): ?><th style="width:50px"></th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($courses_active)): ?>
                                    <tr><td colspan="<?php echo $may_edit_courses ? 8 : 7; ?>" class="text-center text-muted">Aucun cours attribué.</td></tr>
                                    <?php else: foreach($courses_active as $c): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($c['course_code'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($c['ue_code'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($c['level_full_name'] ?? ''); ?></td>
                                        <td><?php echo (int) $c['semester_no']; ?></td>
                                        <td><?php echo rtrim(rtrim($c['credits'], '0'), '.'); ?></td>
                                        <td><small><?php echo htmlspecialchars(trim(($c['added_first'] ?? '').' '.($c['added_last'] ?? ''))); ?>
                                            <span class="text-muted">(<?php echo htmlspecialchars($c['added_role'] ?? '?'); ?>, étape <?php echo (int) $c['assigned_step']; ?>)</span></small></td>
                                        <?php if($may_edit_courses): ?>
                                        <td class="text-center">
                                            <button type="button" class="tv-icon-btn danger remove_course" data-id="<?php echo (int) $c['approval_course_id']; ?>" title="Retirer"><i class="fas fa-times"></i></button>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($may_edit_courses): ?>
                        <div class="row mt-3">
                            <div class="col-md-9 mb-2">
                                <select class="form-control" id="add_course_id">
                                    <option value="">Choisir un cours à ajouter</option>
                                    <?php
                                        $group = null;
                                        foreach($course_choices as $c):
                                            $g = trim(($c['level_full_name'] ?? '').' · Semestre '.(int) $c['semester_no'].' · '.$c['ue_code'].' '.$c['ue_name']);
                                            if($g !== $group){
                                                if($group !== null){ echo '</optgroup>'; }
                                                echo '<optgroup label="'.htmlspecialchars($g).'">';
                                                $group = $g;
                                            }
                                    ?>
                                    <option value="<?php echo (int) $c['course_id']; ?>"><?php echo htmlspecialchars(($c['course_code'] ? $c['course_code'].' : ' : '').$c['course_name'].' ('.rtrim(rtrim($c['credits'], '0'), '.').' cr.)'); ?></option>
                                    <?php endforeach; if($group !== null){ echo '</optgroup>'; } ?>
                                </select>
                                <?php if(empty($course_choices)): ?>
                                <small class="text-muted">Plus aucun cours disponible pour le programme de ce candidat. Enregistrez-les d'abord dans Unités d'enseignement.</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="tv-btn tv-btn-accent w-100 justify-content-center" id="btn_add_course"><i class="fas fa-plus"></i> Ajouter</button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($courses_removed)): ?>
                        <details class="mt-3">
                            <summary class="text-muted" style="cursor:pointer">Cours retirés (<?php echo count($courses_removed); ?>)</summary>
                            <table class="table table-sm mt-2 mb-0">
                                <tbody>
                                    <?php foreach($courses_removed as $c): ?>
                                    <tr class="text-muted">
                                        <td><s><?php echo htmlspecialchars(trim(($c['course_code'] ? $c['course_code'].' : ' : '').$c['course_name'])); ?></s></td>
                                        <td><small>ajouté par <?php echo htmlspecialchars(trim(($c['added_first'] ?? '').' '.($c['added_last'] ?? ''))); ?> (<?php echo htmlspecialchars($c['added_role'] ?? '?'); ?>, étape <?php echo (int) $c['assigned_step']; ?>)</small></td>
                                        <td><small>retiré par <?php echo htmlspecialchars(trim(($c['removed_first'] ?? '').' '.($c['removed_last'] ?? ''))); ?> (<?php echo htmlspecialchars($c['removed_role_name'] ?? '?'); ?>, étape <?php echo (int) $c['removed_step']; ?>)</small></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </details>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- ---------- Documents ---------- -->
                <div class="rv-pane" id="tab-documents">
                    <div class="tv-card">
                        <div class="tv-list-head">
                            <h4 class="tv-card-title"><i class="fas fa-paperclip"></i> Documents téléversés</h4>
                            <span class="tv-pill-count"><?php echo count($docs); ?></span>
                        </div>
                        <?php if(empty($docs)): ?>
                        <span class="text-muted">Aucun document téléversé.</span>
                        <?php else: ?>
                        <div class="rv-files">
                            <?php foreach($docs as $doc): ?>
                            <a class="rv-file" href="<?php echo htmlspecialchars($fileUrl($doc['file_path'])); ?>" target="_blank" title="Ouvrir le document">
                                <i class="far <?php echo preg_match('/\.(jpe?g|png|gif|webp)$/i', $doc['file_path']) ? 'fa-file-image' : 'fa-file-pdf'; ?>"></i>
                                <span class="rv-file-name"><?php echo htmlspecialchars($doc['document_name'] ?? 'Document'); ?>
                                    <small><?php echo $fmt($doc['uploaded_at']); ?></small></span>
                                <i class="fas fa-external-link-alt rv-file-open"></i>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($documents) || $may_send_docs): ?>
                    <div class="tv-card">
                        <h4 class="tv-card-title"><i class="far fa-envelope"></i> Lettres envoyées</h4>
                        <?php if(empty($documents)): ?>
                        <span class="text-muted">Aucune lettre envoyée pour le moment.</span>
                        <?php else: ?>
                        <ul class="rv-list">
                            <?php foreach($documents as $doc): ?>
                            <li>
                                <i class="far fa-file-pdf"></i>
                                <span class="rv-file-name"><?php echo $doc_label[$doc['doc_type']] ?? htmlspecialchars($doc['doc_type']); ?>
                                    <small><?php echo htmlspecialchars($doc['sent_to'] ?? ''); ?> · <?php echo $fmt($doc['created_at']); ?></small>
                                    <?php if(!empty($doc['note'])): ?><small><?php echo htmlspecialchars($doc['note']); ?></small><?php endif; ?></span>
                                <span class="tv-tag <?php echo $doc['sent_status'] === 'sent' ? 'green' : 'red'; ?>"><?php echo $doc['sent_status'] === 'sent' ? 'Envoyée' : 'Échec'; ?></span>
                                <?php if(!empty($doc['file_path'])): ?>
                                <a class="tv-link" href="<?php echo htmlspecialchars($fileUrl($doc['file_path'])); ?>" target="_blank"><i class="far fa-eye"></i> Ouvrir</a>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>

                        <?php if($may_send_docs): ?>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-2">
                                <select class="form-control" id="resend_doc_type">
                                    <option value="admission_letter" <?php echo $form_setting['completion_document'] === 'admission_letter' ? 'selected' : ''; ?>>Lettre d'admission</option>
                                    <option value="registration_proof" <?php echo $form_setting['completion_document'] === 'registration_proof' ? 'selected' : ''; ?>>Attestation d'inscription</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="tv-btn tv-btn-outline w-100 justify-content-center" id="btn_resend_doc"><span id="sp_resend"></span><i class="fas fa-paper-plane"></i> Générer et envoyer</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ---------- Historique ---------- -->
                <div class="rv-pane" id="tab-historique">
                    <div class="tv-card">
                        <h4 class="tv-card-title"><i class="fas fa-history"></i> Historique des décisions</h4>
                        <?php if(empty($history)): ?>
                        <span class="text-muted">Aucune action pour le moment.</span>
                        <?php else: ?>
                        <ul class="rv-timeline">
                            <?php foreach($history as $h):
                                $ht = $action_tag[$h['action']] ?? ['blue', ucfirst($h['action'])]; ?>
                            <li class="rv-t-<?php echo $ht[0]; ?>">
                                <div class="rv-t-head">
                                    <span class="tv-tag <?php echo $ht[0]; ?>"><?php echo $ht[1]; ?></span>
                                    <b><?php echo htmlspecialchars(trim(($h['first_name'] ?? '').' '.($h['family_name'] ?? ''))); ?></b>
                                    <span class="text-muted">(<?php echo htmlspecialchars($h['role'] ?? '?'); ?>) · étape <?php echo (int) $h['step_order']; ?></span>
                                    <small class="text-muted ml-auto"><?php echo $fmt($h['acted_at']); ?></small>
                                </div>
                                <?php if(!empty($h['comment'])): ?><div class="rv-h-comment"><?php echo nl2br(htmlspecialchars($h['comment'])); ?></div><?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ================= side: status, route, decision ================= -->
            <div class="col-12 col-xl-4">
                <div class="rv-side">
                    <div class="tv-card rv-status-card">
                        <div class="rv-status-row">
                            <span class="text-muted">Statut</span>
                            <span class="tv-tag <?php echo $st[0]; ?> rv-status"><?php echo $st[1]; ?></span>
                        </div>
                        <?php if(!empty($request['reg_no'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Matricule</span><b><?php echo htmlspecialchars($request['reg_no']); ?></b></div>
                        <?php endif; ?>
                        <?php if($request['status'] === 'pending' && $current_step_name !== ''): ?>
                        <div class="rv-status-row"><span class="text-muted">Étape actuelle</span><b><?php echo (int) $request['current_step']; ?>. <?php echo htmlspecialchars($current_step_name); ?></b></div>
                        <?php endif; ?>
                        <div class="rv-status-row"><span class="text-muted">Soumise le</span><span><?php echo $fmt($request['submitted_at']); ?></span></div>
                        <?php if(!empty($request['completed_at'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Terminée le</span><span><?php echo $fmt($request['completed_at']); ?></span></div>
                        <?php endif; ?>
                    </div>

                    <div class="tv-card">
                        <h4 class="tv-card-title"><i class="fas fa-route"></i> Circuit d'approbation</h4>
                        <?php if(empty($route)): ?>
                            <span class="text-muted">Aucun circuit n'est configuré pour ce formulaire.</span>
                        <?php else: ?>
                        <ol class="rv-route">
                            <?php foreach($route as $s):
                                $state = 'todo'; $label = 'À venir';
                                if($request['status'] === 'approved' || $s['step_order'] < $request['current_step']){ $state = 'done'; $label = 'Terminée'; }
                                if($s['step_order'] == $request['current_step'] && $request['status'] === 'pending'){ $state = 'current'; $label = 'En cours'; }
                                if($s['step_order'] == $request['current_step'] && $request['status'] === 'rejected'){ $state = 'rejected'; $label = 'Rejetée ici'; }
                            ?>
                            <li class="rv-<?php echo $state; ?>">
                                <span class="rv-dot"><?php echo $state === 'done' ? '<i class="fas fa-check"></i>' : ($state === 'rejected' ? '<i class="fas fa-times"></i>' : (int) $s['step_order']); ?></span>
                                <span class="rv-step-text">
                                    <span class="rv-step-name"><?php echo htmlspecialchars($s['step_name']); ?><?php if($s['step_type'] === 'invoice'): ?> <i class="fas fa-file-invoice" title="Facturation"></i><?php endif; ?></span>
                                    <span class="rv-step-role"><?php echo htmlspecialchars($s['role'] ?? '?'); ?> · <span class="rv-step-state"><?php echo $label; ?></span></span>
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php endif; ?>
                    </div>

                    <?php if($may_act || $may_reopen): ?>
                    <div class="tv-card rv-decision" id="decision">
                        <h4 class="tv-card-title"><i class="fas fa-gavel"></i> <?php echo $may_act ? 'Votre décision' : 'Rouvrir la demande'; ?></h4>
                        <?php if($may_select && !empty($options)): ?>
                        <p class="tv-card-sub" style="margin-top:-10px">Choisissez d'abord l'option accordée dans l'onglet <a href="#" class="rv-goto" data-tab="programme">Programme</a>.</p>
                        <?php endif; ?>
                        <div class="form-group">
                            <label>Commentaire <?php echo $may_act ? '<small class="text-muted">(obligatoire pour rejeter)</small>' : '<small class="text-muted">(obligatoire)</small>'; ?></label>
                            <textarea class="form-control" id="rv_comment" rows="3"></textarea>
                        </div>

                        <?php if($may_reopen): ?>
                        <div class="form-group">
                            <label>Renvoyer à l'étape</label>
                            <select class="form-control" id="rv_reopen_step">
                                <?php foreach($route as $s): ?>
                                <option value="<?php echo (int) $s['step_order']; ?>"><?php echo (int) $s['step_order'].'. '.htmlspecialchars($s['step_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="rv-decision-actions">
                            <?php if($may_act): ?>
                            <button type="button" class="tv-btn tv-btn-accent" id="btn_approve"><span id="rv_spin"></span><i class="fas fa-check"></i> Approuver</button>
                            <button type="button" class="tv-btn rv-btn-reject" id="btn_reject"><i class="fas fa-times"></i> Rejeter</button>
                            <?php else: ?>
                            <button type="button" class="tv-btn tv-btn-accent" id="btn_reopen"><i class="fas fa-undo"></i> Rouvrir</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </section>
</div>

<style>
/* header */
.tv-page .rv-head{display:flex;align-items:center;gap:16px;padding:20px 24px}
.tv-page .rv-back{width:36px;height:36px;border:1px solid var(--tv-border);display:flex;align-items:center;justify-content:center;color:var(--tv-accent);flex-shrink:0}
.tv-page .rv-back:hover{background:var(--tv-accent-soft)}
.tv-page .rv-avatar{width:52px;height:52px;font-size:18px}
.tv-page .rv-who{min-width:0}
.tv-page .rv-head h2{font-size:21px;font-weight:700;margin:0 0 4px;color:var(--tv-text)}
.tv-page .rv-meta{display:flex;flex-wrap:wrap;gap:4px 18px;color:var(--tv-muted);font-size:13px}
.tv-page .rv-meta i{margin-right:4px}
/* tabs */
.tv-page .rv-tabs{display:flex;flex-wrap:wrap;gap:4px;border-bottom:1px solid var(--tv-border);margin-bottom:20px}
.tv-page .rv-tab{background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-1px;padding:12px 16px;font-size:15px;font-weight:600;color:var(--tv-muted);cursor:pointer;display:inline-flex;align-items:center;gap:8px}
.tv-page .rv-tab:hover{color:var(--tv-accent)}
.tv-page .rv-tab.active{color:var(--tv-accent);border-bottom-color:var(--tv-accent)}
.tv-page .rv-tab:focus{outline:none}
.tv-page .rv-count{font-size:12px;font-weight:700;border-radius:10px;padding:1px 8px;background:var(--tv-bg);color:var(--tv-muted)}
.tv-page .rv-tab.active .rv-count{background:var(--tv-accent-soft);color:var(--tv-accent)}
.tv-page .rv-pane{display:none}
.tv-page .rv-pane.active{display:block}
/* profile sections */
.tv-page .rv-section{margin-bottom:22px}
.tv-page .rv-section h5{font-size:15px;font-weight:700;color:var(--tv-text);margin:0 0 6px;padding-bottom:8px;border-bottom:2px solid var(--tv-accent-soft)}
.tv-page .rv-section h5 i{color:var(--tv-accent);margin-right:6px}
.tv-page .rv-info{grid-template-columns:minmax(130px,44%) 1fr}
.tv-page .rv-info dt,.tv-page .rv-info dd{padding:8px 0;font-size:14px}
.tv-page .tv-card-title > i{color:var(--tv-accent);margin-right:6px}
.tv-page .rv-table th{font-weight:800;color:var(--tv-accent);padding:10px}
.tv-page .rv-table td{font-size:14px;padding:10px}
/* options as cards */
.tv-page .rv-options{display:grid;gap:10px}
.tv-page .rv-option{display:flex;align-items:center;gap:14px;border:1px solid var(--tv-border);padding:14px 16px;margin:0;font-weight:normal}
.tv-page .rv-option.pickable{cursor:pointer}
.tv-page .rv-option.pickable:hover{border-color:var(--tv-accent)}
.tv-page .rv-option.selected{border-color:var(--tv-green);background:var(--tv-green-soft)}
.tv-page .rv-option-pick{width:28px;display:flex;justify-content:center;color:var(--tv-green);font-weight:700}
.tv-page .rv-option-pick input{width:18px;height:18px;accent-color:var(--tv-accent);cursor:pointer}
.tv-page .rv-option-body{flex:1;min-width:0}
.tv-page .rv-option-body b{display:block;font-size:15px;color:var(--tv-text)}
.tv-page .rv-option-body small{color:var(--tv-muted);font-size:13px}
/* documents grid */
.tv-page .rv-files{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:10px}
.tv-page .rv-file{display:flex;align-items:center;gap:12px;border:1px solid var(--tv-border);padding:12px 14px;color:var(--tv-text);text-decoration:none !important;transition:border-color .15s}
.tv-page .rv-file:hover{border-color:var(--tv-accent)}
.tv-page .rv-file > i:first-child{font-size:22px;color:var(--tv-accent)}
.tv-page .rv-file-name{flex:1;min-width:0;font-size:14px;line-height:1.35}
.tv-page .rv-file-name small{display:block;color:var(--tv-muted);font-size:12px}
.tv-page .rv-file-open{color:var(--tv-muted);font-size:12px}
.tv-page .rv-list{list-style:none;margin:0;padding:0}
.tv-page .rv-list li{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--tv-border)}
.tv-page .rv-list li:last-child{border-bottom:none}
.tv-page .rv-list li > i{color:var(--tv-accent);font-size:18px}
/* history timeline */
.tv-page .rv-timeline{list-style:none;margin:0;padding:0 0 0 18px;border-left:2px solid var(--tv-border)}
.tv-page .rv-timeline li{position:relative;padding:0 0 18px 16px}
.tv-page .rv-timeline li:before{content:"";position:absolute;left:-25px;top:4px;width:12px;height:12px;border-radius:50%;background:#fff;border:3px solid var(--tv-blue)}
.tv-page .rv-timeline li.rv-t-green:before{border-color:var(--tv-green)}
.tv-page .rv-timeline li.rv-t-red:before{border-color:var(--tv-red)}
.tv-page .rv-timeline li.rv-t-amber:before{border-color:#e0a100}
.tv-page .rv-t-head{display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:14px}
.tv-page .rv-h-comment{margin-top:6px;padding:8px 12px;background:var(--tv-bg);border-left:3px solid var(--tv-border);font-size:13px}
/* side column */
.tv-page .rv-side{position:sticky;top:90px}
.tv-page .rv-status-card{padding:18px 22px}
.tv-page .rv-status-row{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:7px 0;font-size:14px;border-bottom:1px solid var(--tv-border)}
.tv-page .rv-status-row:last-child{border-bottom:none}
.tv-page .rv-status{font-size:13px;padding:5px 12px}
.tv-page .rv-route{list-style:none;margin:0;padding:0}
.tv-page .rv-route li{display:flex;gap:12px;position:relative;padding-bottom:16px}
.tv-page .rv-route li:not(:last-child):before{content:"";position:absolute;left:15px;top:32px;bottom:0;width:2px;background:var(--tv-border)}
.tv-page .rv-route li.rv-done:not(:last-child):before{background:var(--tv-green)}
.tv-page .rv-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;background:var(--tv-bg);border:2px solid var(--tv-border);color:var(--tv-muted);flex-shrink:0;position:relative;z-index:1}
.tv-page .rv-done .rv-dot{background:var(--tv-green);border-color:var(--tv-green);color:#fff}
.tv-page .rv-current .rv-dot{background:var(--tv-accent);border-color:var(--tv-accent);color:#fff;box-shadow:0 0 0 4px var(--tv-accent-soft)}
.tv-page .rv-rejected .rv-dot{background:var(--tv-red);border-color:var(--tv-red);color:#fff}
.tv-page .rv-step-text{display:flex;flex-direction:column;padding-top:4px}
.tv-page .rv-step-name{font-weight:600;font-size:14px;color:var(--tv-text)}
.tv-page .rv-step-role{font-size:13px;color:var(--tv-muted)}
.tv-page .rv-step-state{font-weight:700}
.tv-page .rv-current .rv-step-state{color:var(--tv-accent)}
.tv-page .rv-done .rv-step-state{color:var(--tv-green)}
.tv-page .rv-rejected .rv-step-state{color:var(--tv-red)}
.tv-page .rv-decision{border-top:3px solid var(--tv-accent)}
.tv-page .rv-decision-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.tv-page .rv-decision-actions .tv-btn{justify-content:center}
.tv-page .rv-decision-actions .tv-btn:only-child{grid-column:1 / -1}
.tv-page .rv-btn-reject{background:#fff;border-color:var(--tv-red);color:var(--tv-red)}
.tv-page .rv-btn-reject:hover{background:var(--tv-red-soft);color:var(--tv-red)}
@media (max-width:1199px){ .tv-page .rv-side{position:static} }
@media (max-width:767px){ .tv-page .rv-head{flex-wrap:wrap} }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var REQUEST_ID = <?php echo (int) $request_id; ?>;
var ROUTE_LIST = <?php echo json_encode($ROUTE_LIST); ?>;
var CONTROLLER = "../new_files/Approval_Review/controller.php";

function showTab(name){
    var $tab = $('.rv-tab[data-tab="' + name + '"]');
    if(!$tab.length) return;
    $('.rv-tab').removeClass('active');
    $tab.addClass('active');
    $('.rv-pane').removeClass('active');
    $('#tab-' + name).addClass('active');
    try { sessionStorage.setItem('rv_tab_' + REQUEST_ID, name); } catch(e) {}
}

$(document).ready(function(){

    $('.rv-tab').on('click', function(){ showTab($(this).data('tab')); });
    $(document).on('click', '.rv-goto', function(e){ e.preventDefault(); showTab($(this).data('tab')); window.scrollTo({ top: 0, behavior: 'smooth' }); });
    // reopen the tab the reviewer was on (course add/remove reloads the page)
    try { var t = sessionStorage.getItem('rv_tab_' + REQUEST_ID); if(t){ showTab(t); } } catch(e) {}

    // the whole option card selects its radio
    $(document).on('change', 'input[name=pick_aprg]', function(){
        $('.rv-option').removeClass('selected');
        $(this).closest('.rv-option').addClass('selected');
    });

    $('#btn_approve').on('click', function(){
        var picked = $('input[name=pick_aprg]:checked').val() || '';
        $('#rv_spin').html("<img src='../../img/ajax_loader.gif' width='15'> ");
        $('#btn_approve, #btn_reject').attr('disabled', true);
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'approve', request_id: REQUEST_ID, comment: $('#rv_comment').val(), selected_aprg_id: picked },
            success: function(d){
                $('#rv_spin').html("");
                if(d.status == 200){
                    swal({ title: "Terminé", text: d.message, icon: "success", button: "OK" })
                        .then(function(){ window.location.href = ROUTE_LIST; });
                } else {
                    $('#btn_approve, #btn_reject').attr('disabled', false);
                    pop_wrong(d.message);
                }
            },
            error: function(){
                $('#rv_spin').html("");
                $('#btn_approve, #btn_reject').attr('disabled', false);
                pop_wrong("La requête a échoué");
            }
        });
    });

    $('#btn_reject').on('click', function(){
        var c = $('#rv_comment').val().trim();
        if(!c){ pop_wrong("Indiquez le motif du rejet"); $('#rv_comment').focus(); return; }
        swal({ title: "Rejeter cette candidature ?", text: "Cette décision est définitive.", icon: "warning", buttons: ["Annuler", "Rejeter"], dangerMode: true })
        .then(function(ok){
            if(!ok) return;
            $.ajax({
                url: CONTROLLER, type: "POST", dataType: "JSON",
                data: { action: 'reject', request_id: REQUEST_ID, comment: c },
                success: function(d){
                    if(d.status == 200){ window.location.href = ROUTE_LIST; }
                    else { pop_wrong(d.message); }
                },
                error: function(){ pop_wrong("La requête a échoué"); }
            });
        });
    });

    $('#btn_add_course').on('click', function(){
        var id = $('#add_course_id').val();
        if(!id){ pop_wrong("Choisissez un cours à ajouter"); return; }
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'assign_course', request_id: REQUEST_ID, course_id: id },
            success: function(d){ if(d.status == 200){ location.reload(); } else { pop_wrong(d.message); } },
            error: function(){ pop_wrong("La requête a échoué"); }
        });
    });

    $(document).on('click', '.remove_course', function(){
        var id = $(this).data('id');
        swal({ title: "Retirer ce cours ?", text: "Il restera dans l'historique comme retiré par vous.", icon: "warning", buttons: ["Annuler", "Retirer"], dangerMode: true })
        .then(function(ok){
            if(!ok) return;
            $.ajax({
                url: CONTROLLER, type: "POST", dataType: "JSON",
                data: { action: 'remove_course', request_id: REQUEST_ID, approval_course_id: id },
                success: function(d){ if(d.status == 200){ location.reload(); } else { pop_wrong(d.message); } },
                error: function(){ pop_wrong("La requête a échoué"); }
            });
        });
    });

    $('#btn_resend_doc').on('click', function(){
        var $b = $(this);
        $b.attr('disabled', true);
        $('#sp_resend').html("<img src='../../img/ajax_loader.gif' width='15'> ");
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'resend_document', request_id: REQUEST_ID, doc_type: $('#resend_doc_type').val() },
            success: function(d){
                $('#sp_resend').html('');
                swal({ title: d.status == 200 ? "Envoyée" : "Non envoyée", text: d.message, icon: d.status == 200 ? "success" : "warning", button: "OK" })
                    .then(function(){ location.reload(); });
            },
            error: function(){ $('#sp_resend').html(''); $b.attr('disabled', false); pop_wrong("La requête a échoué"); }
        });
    });

    $('#btn_reopen').on('click', function(){
        var c = $('#rv_comment').val().trim();
        if(!c){ pop_wrong("Indiquez pourquoi la demande est rouverte"); $('#rv_comment').focus(); return; }
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'reopen', request_id: REQUEST_ID, step_order: $('#rv_reopen_step').val(), comment: c },
            success: function(d){
                if(d.status == 200){ window.location.href = ROUTE_LIST + '&status=rejected'; }
                else { pop_wrong(d.message); }
            },
            error: function(){ pop_wrong("La requête a échoué"); }
        });
    });
});

function pop_wrong(f){ iziToast.warning({ title:'Erreur', message:f, position:'topCenter' }); }
</script>
