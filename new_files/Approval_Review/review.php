<?php
// Approval review - same layout as Manage_application/review.php.
//
// What is shown depends on the form that was submitted, but no form is named
// here: every card is built from the data that actually exists for this
// request, and a card with nothing in it is not rendered. So an undergraduate
// application shows its two options and secondary school, a Master request
// shows prior studies, a change of domain shows the requested new domain, and
// a re-integration shows its interruption details.
require_once($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

// Router entries - keep in step with Approval_Review/index.php.
$ROUTE_LIST   = 'edu?mis=approvals';
$ROUTE_REVIEW = 'edu?mis=approval_review';

$request_id = (int) ($_GET['req'] ?? 0);

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

    /** Table rows for label => value pairs, skipping empty values. Returns '' when all are empty. */
    function rv_rows($pairs){
        $html = '';
        foreach($pairs as $label => $value){
            if($value === null || trim((string) $value) === ''){ continue; }
            $html .= '<tr><th scope="row" style="width:45%;">'.htmlspecialchars($label).'</th><td>'
                   . nl2br(htmlspecialchars((string) $value)).'</td></tr>';
        }
        return $html;
    }

    /** A card in the review.php style. Nothing is printed when $body is empty. */
    function rv_card($id, $title, $body, $collapsed = true, $wide = false){
        if(trim($body) === ''){ return; }
        $col = $wide ? 'col-12' : 'col-12 col-sm-6 col-lg-6';
        echo '<div class="'.$col.'"><div class="card"><div class="card-header"><h4>'.$title.'</h4>';
        echo '<div class="card-header-action"><a data-collapse="#'.$id.'" class="btn btn-icon btn-info" href="#">'
           . '<i class="fas '.($collapsed ? 'fa-plus' : 'fa-minus').'"></i></a></div></div>';
        echo '<div class="collapse '.($collapsed ? 'hide' : 'show').'" id="'.$id.'"><div class="card-body">'.$body.'</div></div>';
        echo '</div></div>';
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
        'study_year' => 'Year', 'establishment' => 'Establishment', 'prior_field' => 'Field',
        'prior_department' => 'Department', 'promotion' => 'Promotion', 'percentage' => '%',
        'mention' => 'Mention', 'jury_decision' => 'Jury decision',
        'non_capitalized_credits' => 'Credits not capitalised', 'number_of_failures' => 'Failures',
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

$status_badge = [
    'pending'   => 'badge-warning',
    'approved'  => 'badge-success',
    'rejected'  => 'badge-danger',
    'cancelled' => 'badge-secondary'
];
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $request ? htmlspecialchars($request['application_code']) : 'Request not found'; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?php echo $ROUTE_LIST; ?>">Approvals</a></div>
                <div class="breadcrumb-item"><a href="#">Review</a></div>
            </div>
        </div>
    </section>

    <?php if($denied): ?>
    <section class="section"><div class="section-body">
        <div class="alert alert-warning"><i class="fas fa-lock"></i> You do not have access to this request.</div>
    </div></section>

    <?php elseif(!$request): ?>
    <section class="section"><div class="section-body">
        <div class="alert alert-danger">Request not found.</div>
    </div></section>

    <?php else: ?>
    <section class="section">
        <div class="section-body">

            <!-- header -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars(trim(($a['fname'] ?? '').' '.($a['mname'] ?? '').' '.($a['lname'] ?? ''))); ?></h4>
                                <span class="text-muted">
                                    <?php echo htmlspecialchars($a['email'] ?? ''); ?> &nbsp;|&nbsp; <?php echo htmlspecialchars($a['phone'] ?? ''); ?>
                                    &nbsp;|&nbsp; <b><?php echo htmlspecialchars($request['form_name'] ?? ''); ?></b>
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="badge <?php echo $status_badge[$request['status']] ?? 'badge-secondary'; ?> p-2" style="font-size:14px;"><?php echo ucfirst($request['status']); ?></span>
                                <?php if(!empty($request['reg_no'])): ?>
                                <div class="mt-1"><small class="text-muted">Reg. No</small> <b><?php echo htmlspecialchars($request['reg_no']); ?></b></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- route -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Approval Route</h4></div>
                        <div class="card-body">
                            <?php if(empty($route)): ?>
                                <span class="text-muted">No route is configured for this form.</span>
                            <?php else: foreach($route as $s):
                                $cls = 'badge-light';
                                if($request['status'] === 'approved' || $s['step_order'] < $request['current_step']){ $cls = 'badge-success'; }
                                if($s['step_order'] == $request['current_step'] && $request['status'] === 'pending'){ $cls = 'badge-primary'; }
                                if($s['step_order'] == $request['current_step'] && $request['status'] === 'rejected'){ $cls = 'badge-danger'; }
                            ?>
                                <span class="badge <?php echo $cls; ?> p-2 mb-1" style="margin-right:6px;">
                                    <?php echo (int) $s['step_order']; ?>. <?php echo htmlspecialchars($s['step_name']); ?>
                                    <small>(<?php echo htmlspecialchars($s['role'] ?? '?'); ?>)</small>
                                    <?php if($s['step_type'] === 'invoice'): ?><i class="fas fa-file-invoice"></i><?php endif; ?>
                                </span>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="profile">
                <?php
                // ---- personal information
                rv_card('card-personal', 'Personal Information', ($b = rv_rows([
                    'First name'            => $a['fname'] ?? '',
                    'Middle name'           => $a['mname'] ?? '',
                    'Last name'             => $a['lname'] ?? '',
                    'Gender'                => ($a['gender'] ?? '') === 'M' ? 'Male' : (($a['gender'] ?? '') === 'F' ? 'Female' : ''),
                    'Date of birth'         => $a['dob'] ?? '',
                    'Place of birth'        => $a['place_of_birth'] ?? '',
                    'Country of birth'      => rv_country($conn, $a['country_of_birth'] ?? ''),
                    'Nationality'           => rv_country($conn, $a['nationality_id'] ?? ''),
                    'Blood type'            => $a['blood_type'] ?? '',
                    'Marital status'        => $a['marital_status'] ?? '',
                    'Religious affiliation' => $a['religious_affiliation'] ?? '',
                    "Father's name"         => $a['father_name'] ?? '',
                    "Mother's name"         => $a['mother_name'] ?? ''
                ])) ? '<table class="table table-sm"><tbody>'.$b.'</tbody></table>' : '');

                // ---- origin & contact
                rv_card('card-contact', 'Origin &amp; Contact', ($b = rv_rows([
                    'Country of residence'        => rv_country($conn, $a['country_id'] ?? ''),
                    "Parents' province of origin" => $a['parents_province_origin'] ?? '',
                    'Territory of origin'         => $a['territory_of_origin'] ?? '',
                    'District'                    => $a['district'] ?? '',
                    "Candidate's address"         => $a['candidate_address'] ?? '',
                    'Email'                       => $a['email'] ?? '',
                    'Phone'                       => $a['phone'] ?? '',
                    "Father's phone"              => $a['parent_phone'] ?? '',
                    "Mother's phone"              => $a['ref_phone'] ?? ''
                ])) ? '<table class="table table-sm"><tbody>'.$b.'</tbody></table>' : '');

                // ---- secondary education (only forms that ask for it)
                rv_card('card-secondary', 'Secondary Education', ($b = rv_rows([
                    'School name'             => $a['secondary_school_name'] ?? '',
                    'School province'         => $a['secondary_school_province'] ?? '',
                    'School territory'        => $a['secondary_school_territory'] ?? '',
                    'School country'          => rv_country($conn, $a['secondary_school_territory_country'] ?? ''),
                    'Humanities section'      => $a['humanities_section'] ?? '',
                    'School status'           => $a['secondary_school_status'] ?? '',
                    'Diploma year'            => $a['diploma_year'] ?? '',
                    'Diploma percentage'      => $a['diploma_percentage'] ?? '',
                    'State diploma number'    => $a['state_diploma_number'] ?? '',
                    'Professional activities' => $a['professional_activities'] ?? ''
                ])) ? '<table class="table table-sm"><tbody>'.$b.'</tbody></table>' : '');

                // ---- change of domain: the requested new domain
                if($detail && $request['source_table'] === 'tbl_domain_change_requests'){
                    rv_card('card-domain', 'Requested New Domain', ($b = rv_rows([
                        'Faculty'         => rv_name($conn, 'tbl_faculty', 'fac_id', 'fac_full_name', $detail['requested_fac_id'] ?? ''),
                        'Programme type'  => rv_name($conn, 'tbl_program_type', 'prg_type_id', 'prg_type_full_name', $detail['requested_prg_type_id'] ?? ''),
                        'Department'      => rv_name($conn, 'tbl_department', 'dept_id', 'dept_full_name', $detail['requested_dept_id'] ?? ''),
                        'Option'          => rv_name($conn, 'tbl_option', 'opt_id', 'opt_full_name', $detail['requested_opt_id'] ?? ''),
                        'Class requested' => rv_name($conn, 'tbl_level', 'level_id', 'level_full_name', $detail['requested_level_id'] ?? '')
                    ])) ? '<table class="table table-sm"><tbody>'.$b.'</tbody></table>' : '', false);
                }

                // ---- re-integration
                if($detail && $request['source_table'] === 'tbl_integration_requests'){
                    $tr = $detail['transcript_attached'] ?? '';
                    rv_card('card-integration', 'Re-integration', ($b = rv_rows([
                        'Year of interruption'        => $detail['interruption_year'] ?? '',
                        'Faculty requested'           => rv_name($conn, 'tbl_faculty', 'fac_id', 'fac_full_name', $detail['requested_fac_id'] ?? ''),
                        'Programme type'              => rv_name($conn, 'tbl_program_type', 'prg_type_id', 'prg_type_full_name', $detail['requested_prg_type_id'] ?? ''),
                        'Department'                  => rv_name($conn, 'tbl_department', 'dept_id', 'dept_full_name', $detail['requested_dept_id'] ?? ''),
                        'Option'                      => rv_name($conn, 'tbl_option', 'opt_id', 'opt_full_name', $detail['requested_opt_id'] ?? ''),
                        'Class requested'             => rv_name($conn, 'tbl_level', 'level_id', 'level_full_name', $detail['requested_level_id'] ?? ''),
                        'Last attended'               => $detail['last_attended'] ?? '',
                        'Last result (%)'             => $detail['last_result_percentage'] ?? '',
                        'Last result session'         => $detail['last_result_session'] ?? '',
                        'Reason for suspension'       => $detail['suspension_reason'] ?? '',
                        'Documents left at registry'  => $detail['documents_left'] ?? '',
                        'Transcript attached'         => $tr === '' || $tr === null ? '' : ($tr == 1 ? 'Yes' : 'No'),
                        'Library obligations'         => $detail['obligations_library'] ?? '',
                        'Financial obligations'       => $detail['obligations_finance'] ?? '',
                        'Activities during suspension'=> $detail['activities_during_suspension'] ?? '',
                        'Reason for resuming'         => $detail['resume_reason'] ?? '',
                        'Health status'               => $detail['health_status'] ?? '',
                        'Ability to pay fees'         => $detail['tuition_ability'] ?? ''
                    ])) ? '<table class="table table-sm"><tbody>'.$b.'</tbody></table>' : '', false, true);
                }

                // ---- prior studies (only the columns this form collected)
                if($prior && $prior_cols){
                    $b = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr>';
                    foreach($prior_cols as $label){ $b .= '<th>'.htmlspecialchars($label).'</th>'; }
                    $b .= '</tr></thead><tbody>';
                    foreach($prior as $p){
                        $b .= '<tr>';
                        foreach($prior_cols as $col => $label){ $b .= '<td>'.htmlspecialchars((string) ($p[$col] ?? '')).'</td>'; }
                        $b .= '</tr>';
                    }
                    $b .= '</tbody></table></div>';
                    rv_card('card-prior', 'Previous Studies', $b, true, true);
                }
                ?>

                <?php if(!empty($options)): ?>
                <!-- programme options (application forms) -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Programme Options Applied For</h4>
                        </div>
                        <div class="card-body">
                            <?php if($may_select): ?>
                            <p class="text-muted">Choose which option this applicant is granted. Later steps will confirm the same placement.</p>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">#</th><th>Campus</th><th>Faculty</th><th>Programme</th>
                                            <th>Department</th><th>Option</th><th>Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($options as $n => $o):
                                            $is_sel = !empty($request['selected_aprg_id']) && $o['Aprg_id'] == $request['selected_aprg_id']; ?>
                                        <tr class="<?php echo $is_sel ? 'table-success' : ''; ?>">
                                            <td class="text-center">
                                                <?php if($may_select): ?>
                                                <input type="radio" name="pick_aprg" value="<?php echo (int) $o['Aprg_id']; ?>" <?php echo $is_sel ? 'checked' : ''; ?>>
                                                <?php elseif($is_sel): ?>
                                                <i class="fas fa-check text-success"></i>
                                                <?php else: ?>
                                                <?php echo $n + 1; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($o['camp_full_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($o['fac_full_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($o['prg_type_full_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($o['dept_full_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($o['opt_full_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($o['level_full_name'] ?? ''); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($may_edit_courses || !empty($courses_all)): ?>
                <!-- courses to follow -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Courses to Follow</h4>
                            <?php if(!empty($courses_active)): ?>
                            <div class="card-header-action">
                                <span class="badge badge-primary"><?php echo count($courses_active); ?> course(s) &middot;
                                <?php $tc = 0; foreach($courses_active as $c){ $tc += (float) $c['credits']; } echo rtrim(rtrim(number_format($tc, 2, '.', ''), '0'), '.'); ?> credits</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Code</th><th>Course</th><th>Unit</th><th>Level</th><th>Sem.</th>
                                            <th>Credits</th><th>Added by</th>
                                            <?php if($may_edit_courses): ?><th style="width:60px;"></th><?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($courses_active)): ?>
                                        <tr><td colspan="<?php echo $may_edit_courses ? 8 : 7; ?>" class="text-center text-muted">No courses assigned.</td></tr>
                                        <?php else: foreach($courses_active as $c): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($c['course_code'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                                            <td><?php echo htmlspecialchars($c['ue_code'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($c['level_full_name'] ?? ''); ?></td>
                                            <td><?php echo (int) $c['semester_no']; ?></td>
                                            <td><?php echo rtrim(rtrim($c['credits'], '0'), '.'); ?></td>
                                            <td><small><?php echo htmlspecialchars(trim(($c['added_first'] ?? '').' '.($c['added_last'] ?? ''))); ?>
                                                <span class="text-muted">(<?php echo htmlspecialchars($c['added_role'] ?? '?'); ?>, step <?php echo (int) $c['assigned_step']; ?>)</span></small></td>
                                            <?php if($may_edit_courses): ?>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-icon btn-danger btn-sm remove_course" data-id="<?php echo (int) $c['approval_course_id']; ?>" title="Remove"><i class="fas fa-times"></i></button>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if($may_edit_courses): ?>
                            <div class="row mt-2">
                                <div class="col-md-9">
                                    <select class="form-control" id="add_course_id">
                                        <option value="">-- choose a course to add --</option>
                                        <?php
                                            $group = null;
                                            foreach($course_choices as $c):
                                                $g = trim(($c['level_full_name'] ?? '').' · Semester '.(int) $c['semester_no'].' · '.$c['ue_code'].' '.$c['ue_name']);
                                                if($g !== $group){
                                                    if($group !== null){ echo '</optgroup>'; }
                                                    echo '<optgroup label="'.htmlspecialchars($g).'">';
                                                    $group = $g;
                                                }
                                        ?>
                                        <option value="<?php echo (int) $c['course_id']; ?>"><?php echo htmlspecialchars(($c['course_code'] ? $c['course_code'].' - ' : '').$c['course_name'].' ('.rtrim(rtrim($c['credits'], '0'), '.').' cr)'); ?></option>
                                        <?php endforeach; if($group !== null){ echo '</optgroup>'; } ?>
                                    </select>
                                    <?php if(empty($course_choices)): ?>
                                    <small class="text-muted">No more courses available for this applicant's programme. Register them in Teaching Units first.</small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary btn-block" id="btn_add_course"><i class="fas fa-plus"></i>&nbsp;Add course</button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($courses_removed)): ?>
                            <details class="mt-3">
                                <summary class="text-muted" style="cursor:pointer;">Removed courses (<?php echo count($courses_removed); ?>)</summary>
                                <table class="table table-sm mt-2 mb-0">
                                    <tbody>
                                        <?php foreach($courses_removed as $c): ?>
                                        <tr class="text-muted">
                                            <td><s><?php echo htmlspecialchars(trim(($c['course_code'] ? $c['course_code'].' - ' : '').$c['course_name'])); ?></s></td>
                                            <td><small>added by <?php echo htmlspecialchars(trim(($c['added_first'] ?? '').' '.($c['added_last'] ?? ''))); ?> (<?php echo htmlspecialchars($c['added_role'] ?? '?'); ?>, step <?php echo (int) $c['assigned_step']; ?>)</small></td>
                                            <td><small>removed by <?php echo htmlspecialchars(trim(($c['removed_first'] ?? '').' '.($c['removed_last'] ?? ''))); ?> (<?php echo htmlspecialchars($c['removed_role_name'] ?? '?'); ?>, step <?php echo (int) $c['removed_step']; ?>)</small></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </details>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($documents) || $may_send_docs): ?>
                <!-- documents sent -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Letters Sent</h4></div>
                        <div class="card-body">
                            <?php $doc_label = ['admission_letter' => 'Admission letter', 'registration_proof' => 'Proof of registration']; ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead><tr><th>Document</th><th>Sent to</th><th>Status</th><th>Date</th><th>File</th></tr></thead>
                                    <tbody>
                                        <?php if(empty($documents)): ?>
                                        <tr><td colspan="5" class="text-center text-muted">No letter sent yet.</td></tr>
                                        <?php else: foreach($documents as $doc): ?>
                                        <tr>
                                            <td><?php echo $doc_label[$doc['doc_type']] ?? $doc['doc_type']; ?></td>
                                            <td><?php echo htmlspecialchars($doc['sent_to'] ?? ''); ?></td>
                                            <td>
                                                <span class="badge <?php echo $doc['sent_status'] === 'sent' ? 'badge-success' : 'badge-danger'; ?>"><?php echo htmlspecialchars($doc['sent_status']); ?></span>
                                                <?php if(!empty($doc['note'])): ?><br><small class="text-muted"><?php echo htmlspecialchars($doc['note']); ?></small><?php endif; ?>
                                            </td>
                                            <td><small><?php echo htmlspecialchars($doc['created_at']); ?></small></td>
                                            <td>
                                                <?php if(!empty($doc['file_path'])): ?>
                                                <a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank"><i class="fas fa-file-pdf"></i>&nbsp;Open</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if($may_send_docs): ?>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <select class="form-control" id="resend_doc_type">
                                        <option value="admission_letter" <?php echo $form_setting['completion_document'] === 'admission_letter' ? 'selected' : ''; ?>>Admission letter</option>
                                        <option value="registration_proof" <?php echo $form_setting['completion_document'] === 'registration_proof' ? 'selected' : ''; ?>>Proof of registration</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info btn-block" id="btn_resend_doc"><span id="sp_resend"></span>&nbsp;<i class="fas fa-paper-plane"></i>&nbsp;Generate &amp; email</button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- uploaded documents -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Uploaded Documents</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead><tr><th>Document</th><th>Uploaded On</th><th>Action</th></tr></thead>
                                    <tbody>
                                        <?php if(empty($docs)): ?>
                                        <tr><td colspan="3" class="text-center text-muted">No documents uploaded.</td></tr>
                                        <?php else: foreach($docs as $doc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($doc['document_name'] ?? 'Document'); ?></td>
                                            <td><?php echo $doc['uploaded_at'] ? date('Y-m-d H:i', strtotime($doc['uploaded_at'])) : ''; ?></td>
                                            <td><a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- history -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>History</h4></div>
                        <div class="card-body">
                            <?php if(empty($history)): ?>
                            <span class="text-muted">No actions yet.</span>
                            <?php else: ?>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <?php foreach($history as $h):
                                        $hb = $h['action'] === 'rejected' ? 'badge-danger'
                                            : ($h['action'] === 'approved' || $h['action'] === 'invoiced' ? 'badge-success'
                                            : ($h['action'] === 'reopened' ? 'badge-warning' : 'badge-light')); ?>
                                    <tr>
                                        <td style="width:110px;"><span class="badge <?php echo $hb; ?>"><?php echo htmlspecialchars($h['action']); ?></span></td>
                                        <td>
                                            Step <?php echo (int) $h['step_order']; ?> &mdash;
                                            <?php echo htmlspecialchars(trim(($h['first_name'] ?? '').' '.($h['family_name'] ?? ''))); ?>
                                            <small class="text-muted">(<?php echo htmlspecialchars($h['role'] ?? '?'); ?>)</small>
                                            <?php if(!empty($h['comment'])): ?><br><small><?php echo nl2br(htmlspecialchars($h['comment'])); ?></small><?php endif; ?>
                                        </td>
                                        <td style="width:150px;"><small class="text-muted"><?php echo htmlspecialchars($h['acted_at']); ?></small></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($may_act || $may_reopen): ?>
                <!-- decision -->
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header"><h4><?php echo $may_act ? 'Your Decision' : 'Reopen Request'; ?></h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Comment <?php echo $may_act ? '<small class="text-muted">(required to reject)</small>' : '<small class="text-muted">(required)</small>'; ?></label>
                                <textarea class="form-control" id="rv_comment" rows="3"></textarea>
                            </div>

                            <?php if($may_reopen): ?>
                            <div class="form-group">
                                <label>Send back to step</label>
                                <select class="form-control" id="rv_reopen_step" style="max-width:320px;">
                                    <?php foreach($route as $s): ?>
                                    <option value="<?php echo (int) $s['step_order']; ?>"><?php echo (int) $s['step_order'].'. '.htmlspecialchars($s['step_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <div class="text-right">
                                <a href="<?php echo $ROUTE_LIST; ?>" class="btn btn-light">Back</a>
                                <?php if($may_act): ?>
                                <button type="button" class="btn btn-danger" id="btn_reject"><i class="fas fa-times"></i>&nbsp;Reject</button>
                                <button type="button" class="btn btn-primary" id="btn_approve"><span id="rv_spin"></span>&nbsp;<i class="fas fa-check"></i>&nbsp;Approve</button>
                                <?php else: ?>
                                <button type="button" class="btn btn-warning" id="btn_reopen"><i class="fas fa-undo"></i>&nbsp;Reopen</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var REQUEST_ID = <?php echo (int) $request_id; ?>;
var ROUTE_LIST = <?php echo json_encode($ROUTE_LIST); ?>;
var CONTROLLER = "../new_files/Approval_Review/controller.php";

$(document).ready(function(){

    $('#btn_approve').on('click', function(){
        var picked = $('input[name=pick_aprg]:checked').val() || '';
        $('#rv_spin').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $('#btn_approve, #btn_reject').attr('disabled', true);
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'approve', request_id: REQUEST_ID, comment: $('#rv_comment').val(), selected_aprg_id: picked },
            success: function(d){
                $('#rv_spin').html("");
                if(d.status == 200){
                    swal({ title: "Done", text: d.message, icon: "success", button: "OK" })
                        .then(function(){ window.location.href = ROUTE_LIST; });
                } else {
                    $('#btn_approve, #btn_reject').attr('disabled', false);
                    pop_wrong(d.message);
                }
            },
            error: function(){
                $('#rv_spin').html("");
                $('#btn_approve, #btn_reject').attr('disabled', false);
                pop_wrong("Request failed");
            }
        });
    });

    $('#btn_reject').on('click', function(){
        var c = $('#rv_comment').val().trim();
        if(!c){ pop_wrong("Please give a reason for the rejection"); return; }
        swal({ title: "Reject this application?", text: "This is final.", icon: "warning", buttons: true, dangerMode: true })
        .then(function(ok){
            if(!ok) return;
            $.ajax({
                url: CONTROLLER, type: "POST", dataType: "JSON",
                data: { action: 'reject', request_id: REQUEST_ID, comment: c },
                success: function(d){
                    if(d.status == 200){ window.location.href = ROUTE_LIST; }
                    else { pop_wrong(d.message); }
                },
                error: function(){ pop_wrong("Request failed"); }
            });
        });
    });

    $('#btn_add_course').on('click', function(){
        var id = $('#add_course_id').val();
        if(!id){ pop_wrong("Choose a course to add"); return; }
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'assign_course', request_id: REQUEST_ID, course_id: id },
            success: function(d){ if(d.status == 200){ location.reload(); } else { pop_wrong(d.message); } },
            error: function(){ pop_wrong("Request failed"); }
        });
    });

    $(document).on('click', '.remove_course', function(){
        var id = $(this).data('id');
        swal({ title: "Remove this course?", text: "It stays in the history as removed by you.", icon: "warning", buttons: true, dangerMode: true })
        .then(function(ok){
            if(!ok) return;
            $.ajax({
                url: CONTROLLER, type: "POST", dataType: "JSON",
                data: { action: 'remove_course', request_id: REQUEST_ID, approval_course_id: id },
                success: function(d){ if(d.status == 200){ location.reload(); } else { pop_wrong(d.message); } },
                error: function(){ pop_wrong("Request failed"); }
            });
        });
    });

    $('#btn_resend_doc').on('click', function(){
        var $b = $(this);
        $b.attr('disabled', true);
        $('#sp_resend').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'resend_document', request_id: REQUEST_ID, doc_type: $('#resend_doc_type').val() },
            success: function(d){
                $('#sp_resend').html('');
                swal({ title: d.status == 200 ? "Sent" : "Not sent", text: d.message, icon: d.status == 200 ? "success" : "warning", button: "OK" })
                    .then(function(){ location.reload(); });
            },
            error: function(){ $('#sp_resend').html(''); $b.attr('disabled', false); pop_wrong("Request failed"); }
        });
    });

    $('#btn_reopen').on('click', function(){
        var c = $('#rv_comment').val().trim();
        if(!c){ pop_wrong("Please say why this is being reopened"); return; }
        $.ajax({
            url: CONTROLLER, type: "POST", dataType: "JSON",
            data: { action: 'reopen', request_id: REQUEST_ID, step_order: $('#rv_reopen_step').val(), comment: c },
            success: function(d){
                if(d.status == 200){ window.location.href = ROUTE_LIST + '&status=rejected'; }
                else { pop_wrong(d.message); }
            },
            error: function(){ pop_wrong("Request failed"); }
        });
    });
});

function pop_wrong(f){ iziToast.warning({ title:'Info', message:f, position:'topCenter' }); }
</script>
