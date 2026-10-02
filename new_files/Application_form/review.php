<?php
// Applicant-facing review of their own submitted application.
// Read-only mirror of Manage_application/review.php, scoped to the logged-in
// applicant via $_SESSION['identification']. Sections whose fields are all empty
// are skipped, since the five application forms collect different field sets.
// While the application period is open, links back to the form they completed
// (resolved through tbl_applicants.form_id -> tbl_form_types.form_path).

$Identification = $_SESSION['identification'] ?? '';

$applicant = null;
if($Identification !== ''){
    $stmt = $conn->prepare("SELECT tbl_applicants.*,
                                   nat_cntr.cntr_name AS nat_name,
                                   tbl_country.cntr_name AS country_name,
                                   tbl_campus.camp_full_name,
                                   tbl_program_type.prg_type_full_name,
                                   tbl_form_types.form_name,
                                   tbl_form_types.form_path,
                                   tbl_application_periods.period_name,
                                   tbl_application_periods.start_date AS period_start,
                                   tbl_application_periods.end_date AS period_end,
                                   tbl_acad_cycle.acad_year
                              FROM tbl_applicants
                              LEFT JOIN tbl_country nat_cntr ON tbl_applicants.nationality_id = nat_cntr.cntr_id
                              LEFT JOIN tbl_country ON tbl_applicants.country_id = tbl_country.cntr_id
                              LEFT JOIN tbl_campus ON tbl_applicants.camp_id = tbl_campus.camp_id
                              LEFT JOIN tbl_program_type ON tbl_applicants.prg_type_id = tbl_program_type.prg_type_id
                              LEFT JOIN tbl_form_types ON tbl_applicants.form_id = tbl_form_types.form_id
                              LEFT JOIN tbl_application_periods ON tbl_applicants.application_period_id = tbl_application_periods.id
                              LEFT JOIN tbl_acad_cycle ON tbl_application_periods.acad_cycle_id = tbl_acad_cycle.acad_cycle_id
                             WHERE tbl_applicants.application_code = :identification");
    $stmt->execute([':identification' => $Identification]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(!$applicant){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-danger">We could not find your applicant record. Please log in again or contact admissions support.</div>';
    echo '</div></section></div>';
    return;
}

$applicant_id = $applicant['applicant_id'];

// Admitted programme choices (one row per department choice).
$prgStmt = $conn->prepare("SELECT tbl_admittedPRG.sts,
                                  tbl_department.dept_full_name,
                                  tbl_faculty.fac_full_name,
                                  tbl_level.level_full_name
                             FROM tbl_admittedPRG
                             LEFT JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                             LEFT JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                             LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                            WHERE tbl_admittedPRG.Stu_code = :stu_code
                            ORDER BY tbl_admittedPRG.Aprg_id ASC");
$prgStmt->execute([':stu_code' => $applicant['application_code']]);
$programmes = $prgStmt->fetchAll(PDO::FETCH_ASSOC);

// Prior studies (shape varies by form; unused columns simply come back null).
$priorStmt = $conn->prepare("SELECT * FROM tbl_applicant_prior_studies
                              WHERE applicant_id = :id ORDER BY prior_study_id ASC");
$priorStmt->execute([':id' => $applicant_id]);
$priorStudies = $priorStmt->fetchAll(PDO::FETCH_ASSOC);

$docStmt = $conn->prepare("SELECT tbl_applicant_documents.file_path, tbl_applicant_documents.uploaded_at,
                                  tbl_document_type.document_name
                             FROM tbl_applicant_documents
                             LEFT JOIN tbl_document_type ON tbl_document_type.doc_id = tbl_applicant_documents.doc_id
                            WHERE tbl_applicant_documents.applicant_id = :id
                            ORDER BY tbl_document_type.document_name ASC");
$docStmt->execute([':id' => $applicant_id]);
$docs = $docStmt->fetchAll(PDO::FETCH_ASSOC);

// Pending change-of-domain request, if this applicant has one.
$changeRequest = null;
try {
    $reqStmt = $conn->prepare("SELECT tbl_domain_change_requests.status,
                                      tbl_domain_change_requests.requested_at,
                                      tbl_faculty.fac_full_name,
                                      tbl_program_type.prg_type_full_name,
                                      tbl_department.dept_full_name,
                                      tbl_level.level_full_name
                                 FROM tbl_domain_change_requests
                                 LEFT JOIN tbl_faculty ON tbl_domain_change_requests.requested_fac_id = tbl_faculty.fac_id
                                 LEFT JOIN tbl_program_type ON tbl_domain_change_requests.requested_prg_type_id = tbl_program_type.prg_type_id
                                 LEFT JOIN tbl_department ON tbl_domain_change_requests.requested_dept_id = tbl_department.dept_id
                                 LEFT JOIN tbl_level ON tbl_domain_change_requests.requested_level_id = tbl_level.level_id
                                WHERE tbl_domain_change_requests.application_code = :code
                                ORDER BY tbl_domain_change_requests.request_id DESC LIMIT 1");
    $reqStmt->execute([':code' => $applicant['application_code']]);
    $changeRequest = $reqStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $changeRequest = null;
}

$current_date = date('Y-m-d');
$periodStmt = $conn->prepare("SELECT end_date FROM tbl_application_periods
                               WHERE status = 'active' AND start_date <= :today AND end_date >= :today
                               ORDER BY created_at DESC LIMIT 1");
$periodStmt->execute([':today' => $current_date]);
$activePeriod = $periodStmt->fetch(PDO::FETCH_ASSOC);

$editHref = null;
if($activePeriod && !empty($applicant['form_path'])){
    $editHref = '../'.ltrim($applicant['form_path'], '/');
}

$status_badge = [
    'pending'  => 'badge-warning',
    'verified' => 'badge-info',
    'accepted' => 'badge-success',
    'rejected' => 'badge-danger'
];

// Renders a table of label => value rows, skipping empties. Returns false when
// nothing had a value, so the caller can omit the whole card.
function review_rows($rows){
    $filled = array_filter($rows, function($v){ return trim((string) $v) !== ''; });
    if(empty($filled)){
        return false;
    }

    echo '<table class="table table-sm"><tbody>';
    foreach($rows as $label => $value){
        if(trim((string) $value) === ''){
            continue;
        }
        // Labels are developer-authored constants (some carry accent entities),
        // so they are emitted as-is. Values are applicant data and stay escaped.
        echo '<tr><th scope="row">'.$label.'</th><td>'.nl2br(htmlspecialchars(html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8'))).'</td></tr>';
    }
    echo '</tbody></table>';
    return true;
}

function review_card($title, $id, $rows){
    ob_start();
    $hasContent = review_rows($rows);
    $body = ob_get_clean();

    if(!$hasContent){
        return;
    }
    ?>
    <div class="col-12 col-sm-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4><?php echo $title; ?></h4>
                <div class="card-header-action">
                    <a data-collapse="#<?php echo $id; ?>" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                </div>
            </div>
            <div class="collapse hide" id="<?php echo $id; ?>">
                <div class="card-body"><?php echo $body; ?></div>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Ma candidature</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Accueil</a></div>
                <div class="breadcrumb-item"><a href="#">Ma candidature</a></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars(trim($applicant['fname'].' '.$applicant['mname'].' '.$applicant['lname'])); ?></h4>
                                <span class="text-muted">
                                    <?php echo htmlspecialchars($applicant['email']); ?>
                                    <?php if(!empty($applicant['phone'])): ?>&nbsp;|&nbsp;<?php echo htmlspecialchars($applicant['phone']); ?><?php endif; ?>
                                </span>
                                <div class="mt-2">
                                    <span class="badge badge-light">Code: <?php echo htmlspecialchars($applicant['application_code']); ?></span>
                                    <?php if(!empty($applicant['form_name'])): ?>
                                    <span class="badge badge-light"><i class="fas fa-file-alt"></i>&nbsp;<?php echo htmlspecialchars($applicant['form_name']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($applicant['acad_year'])): ?>
                                    <span class="badge badge-light"><i class="fas fa-calendar-alt"></i>&nbsp;<?php echo htmlspecialchars($applicant['acad_year']); ?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($applicant['period_name'])): ?>
                                    <span class="badge badge-light"><i class="fas fa-hourglass-half"></i>&nbsp;<?php echo htmlspecialchars($applicant['period_name']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge <?php echo $status_badge[$applicant['status']] ?? 'badge-secondary'; ?> p-2" style="font-size:14px;"><?php echo ucfirst($applicant['status']); ?></span>
                                <?php if($editHref): ?>
                                <div class="mt-2">
                                    <a href="<?php echo htmlspecialchars($editHref); ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i>&nbsp;Edit my application</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(empty($applicant['form_name'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-light">
                        <i class="fas fa-info-circle"></i> We could not tell which form this application came from. This is normal for applications submitted before form tracking was added.
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($activePeriod): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-clock"></i> The application period is open until <strong><?php echo htmlspecialchars($activePeriod['end_date']); ?></strong>. You can still change your answers until then.
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-light">
                        <i class="fas fa-lock"></i> The application period is closed. Your application can no longer be edited.
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <?php
                review_card('Informations personnelles', 'card-personal', [
                    'First name'           => $applicant['fname'],
                    'Middle name'          => $applicant['mname'],
                    'Last name'            => $applicant['lname'],
                    'Gender'               => $applicant['gender'] === 'M' ? 'Male' : ($applicant['gender'] === 'F' ? 'Female' : ''),
                    'Date of birth'        => $applicant['dob'],
                    'Place of birth'       => $applicant['place_of_birth'],
                    'Pays de naissance'    => $applicant['country_of_birth'] ?? '',
                    'National ID/Passport' => $applicant['nid'],
                    'Blood type'           => $applicant['blood_type'],
                    'Marital status'       => $applicant['marital_status'],
                    'Religious affiliation' => $applicant['religious_affiliation'],
                    'Nationality'          => $applicant['nat_name'],
                    "Father's name"        => $applicant['father_name'],
                    "Mother's name"        => $applicant['mother_name'],
                ]);

                review_card('Origine &amp; Contact', 'card-contact', [
                    'Country of residence'        => $applicant['country_name'],
                    "Parents' province of origin" => $applicant['parents_province_origin'],
                    'District'                    => $applicant['district'] ?? '',
                    'Territory of origin'         => $applicant['territory_of_origin'],
                    "Candidate's address"         => $applicant['candidate_address'],
                    'Email'                       => $applicant['email'],
                    'Phone'                       => $applicant['phone'],
                    "Father's / parent phone"     => $applicant['parent_phone'],
                    "Mother's phone"              => $applicant['ref_phone'],
                ]);

                review_card('&Eacute;tudes secondaires', 'card-secondary', [
                    'School name'            => $applicant['secondary_school_name'],
                    'School province'        => $applicant['secondary_school_province'],
                    'School territory'       => $applicant['secondary_school_territory'],
                    'Humanities section'     => $applicant['humanities_section'],
                    'School status'          => $applicant['secondary_school_status'],
                    'Diploma year'           => $applicant['diploma_year'],
                    'Diploma percentage'     => $applicant['diploma_percentage'],
                    'State diploma number'   => $applicant['state_diploma_number'],
                    'Professional activities' => $applicant['professional_activities'],
                ]);

                review_card('Choix acad&eacute;mique', 'card-academic', [
                    'Campus'           => $applicant['camp_full_name'],
                    'Programme Type'   => $applicant['prg_type_full_name'],
                    'Ann&eacute;e acad&eacute;mique' => $applicant['acad_year'] ?? '',
                    'P&eacute;riode de candidature' => !empty($applicant['period_name'])
                        ? $applicant['period_name'].(!empty($applicant['period_start']) ? ' ('.$applicant['period_start'].' &rarr; '.$applicant['period_end'].')' : '')
                        : '',
                    'Application code' => $applicant['application_code'],
                    'Submitted on'     => !empty($applicant['created_at']) ? date('Y-m-d H:i', strtotime($applicant['created_at'])) : '',
                    'Last updated'     => !empty($applicant['updated_at']) ? date('Y-m-d H:i', strtotime($applicant['updated_at'])) : '',
                ]);
                ?>
            </div>

            <?php if(!empty($programmes)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Choix de fili&egrave;re</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Faculty</th>
                                            <th>Department</th>
                                            <th>Classe</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($programmes as $i => $prg): ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td><?php echo htmlspecialchars($prg['fac_full_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($prg['dept_full_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($prg['level_full_name'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if($prg['sts'] == 0): ?>
                                                <span class="badge badge-warning">En attente de revue</span>
                                                <?php elseif($prg['sts'] == 1): ?>
                                                <span class="badge badge-success">Accept&eacute;</span>
                                                <?php else: ?>
                                                <span class="badge badge-danger">Non retenu</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($changeRequest): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Demande de changement de domaine</h4></div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><th scope="row">Requested faculty</th><td><?php echo htmlspecialchars($changeRequest['fac_full_name'] ?? 'N/A'); ?></td></tr>
                                    <tr><th scope="row">Requested programme</th><td><?php echo htmlspecialchars($changeRequest['prg_type_full_name'] ?? 'N/A'); ?></td></tr>
                                    <tr><th scope="row">Requested department</th><td><?php echo htmlspecialchars($changeRequest['dept_full_name'] ?? 'N/A'); ?></td></tr>
                                    <?php if(!empty($changeRequest['level_full_name'])): ?>
                                    <tr><th scope="row">Requested class</th><td><?php echo htmlspecialchars($changeRequest['level_full_name']); ?></td></tr>
                                    <?php endif; ?>
                                    <tr><th scope="row">Requested on</th><td><?php echo date('Y-m-d H:i', strtotime($changeRequest['requested_at'])); ?></td></tr>
                                    <tr><th scope="row">Status</th><td><span class="badge badge-<?php echo $changeRequest['status'] === 'pending' ? 'warning' : ($changeRequest['status'] === 'approved' ? 'success' : 'danger'); ?>"><?php echo ucfirst($changeRequest['status']); ?></span></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($priorStudies)): ?>
            <?php
                // Only render columns that at least one row actually filled in.
                $priorColumns = [
                    'study_year'              => 'Year',
                    'establishment'           => 'Institution',
                    'prior_field'             => 'Field',
                    'prior_department'        => 'Department',
                    'promotion'               => 'Promotion',
                    'percentage'              => 'Percentage',
                    'mention'                 => 'Mention',
                    'jury_decision'           => 'Dec. Jury',
                    'number_of_failures'      => 'Failures',
                    'non_capitalized_credits' => 'Non-Capitalized Credits',
                    'session'                 => 'Session',
                ];
                $activeColumns = [];
                foreach($priorColumns as $col => $label){
                    foreach($priorStudies as $row){
                        if(isset($row[$col]) && trim((string) $row[$col]) !== ''){
                            $activeColumns[$col] = $label;
                            break;
                        }
                    }
                }
            ?>
            <?php if(!empty($activeColumns)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>&Eacute;tudes ant&eacute;rieures</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <?php foreach($activeColumns as $label): ?>
                                            <th><?php echo htmlspecialchars($label); ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($priorStudies as $row): ?>
                                        <tr>
                                            <?php foreach($activeColumns as $col => $label): ?>
                                            <td><?php echo htmlspecialchars($row[$col] ?? ''); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <?php if(!empty($docs)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Documents t&eacute;l&eacute;vers&eacute;s</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>T&eacute;l&eacute;vers&eacute; le</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($docs as $doc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($doc['document_name'] ?? 'Document'); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($doc['uploaded_at'])); ?></td>
                                            <td><a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
</div>
