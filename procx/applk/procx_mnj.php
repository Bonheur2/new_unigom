<?php
function Personalinfo($connection, $applId) {
    $conn = $connection;
    $acc_id = $applId;
    
    $stmt = $conn->prepare("SELECT tbl_applicants.*,
                                                                 tbl_nationality.nationality as nat,
                                                                 tbl_country.cntr_name as cname,
                                                                 provinces.provincename as pname,
                                                                 districts.namedistrict as dname,
                                                                 sectors.namesector as sname,
                                                                 cells.namecell as cellname,
                                                                 villages.VillageName as vname
                                                                 FROM tbl_applicants
                                                                 LEFT JOIN tbl_nationality ON
                                                                 tbl_applicants.nationality=tbl_nationality.nat_id
                                                                 LEFT JOIN tbl_country ON
                                                                 tbl_applicants.country=tbl_country.cntr_id
                                                                 LEFT JOIN provinces ON
                                                                 tbl_applicants.province_id=provinces.provincecode
                                                                 LEFT JOIN districts ON
                                                                 tbl_applicants.district_id=districts.districtcode
                                                                 LEFT JOIN sectors ON
                                                                 tbl_applicants.sector=sectors.sectorcode
                                                                 LEFT JOIN cells ON
                                                                 tbl_applicants.cell_id=cells.codecell
                                                                 LEFT JOIN villages ON
                                                                 tbl_applicants.village_id=villages.CodeVillage
                                                                 WHERE tbl_applicants.applicant_id='".$acc_id."'");
                                    $stmt->execute();
                                    $applicantData=$stmt->fetch();
$fields = [
    $applicantData['lname'] ?? '',
    $applicantData['fname'] ?? '',
    $applicantData['nationality'] ?? '',
    $applicantData['ID'] ?? '',
    $applicantData['gender'] ?? '',
    $applicantData['dob'] ?? '',
    $applicantData['marital_status'] ?? '',
    $applicantData['father_names'] ?? '',
    $applicantData['mother_names'] ?? '',
    $applicantData['email'] ?? '',
    $applicantData['phone'] ?? '',
    $applicantData['country'] ?? '',
    $applicantData['street'] ?? '',
    $applicantData['blood_group'] ?? '',
    $applicantData['disability'] ?? '',
    $applicantData['kin_name'] ?? '',
    $applicantData['kin_relation'] ?? '',
    $applicantData['kin_tel'] ?? '',
];
$filled = count(array_filter($fields, function($v) { return trim($v) !== ''; }));
$total = count($fields);
$percent = round(($filled / $total) * 100);
$color = $percent < 40 ? 'danger' : ($percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Profile Completion</span>
        <span><?php echo $percent; ?>% (<?php echo $filled; ?>/<?php echo $total; ?> fields)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar"
             style="width:<?php echo $percent; ?>%"
             aria-valuenow="<?php echo $percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>

<?php
}
?>




<?php
function ApplicationSummary($connection, $applId, $prg_type) {
    $conn = $connection;
    $code = $applId;

    // --- 1. Personal Info ---
    $stmt = $conn->prepare("SELECT * FROM tbl_applicants WHERE code = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $applicantData = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $pi_fields = [
        $applicantData['lname'] ?? '',
        $applicantData['fname'] ?? '',
        $applicantData['nationality'] ?? '',
        $applicantData['ID'] ?? '',
        $applicantData['gender'] ?? '',
        $applicantData['dob'] ?? '',
        $applicantData['marital_status'] ?? '',
        $applicantData['father_names'] ?? '',
        $applicantData['mother_names'] ?? '',
        $applicantData['email'] ?? '',
        $applicantData['phone'] ?? '',
        $applicantData['country'] ?? '',
        $applicantData['street'] ?? '',
        $applicantData['blood_group'] ?? '',
        $applicantData['disability'] ?? '',
        $applicantData['kin_name'] ?? '',
        $applicantData['kin_relation'] ?? '',
        $applicantData['kin_tel'] ?? '',
    ];
    $pi_filled  = count(array_filter($pi_fields, function($v) { return trim($v) !== ''; }));
    $pi_total   = count($pi_fields);
    $pi_percent = round(($pi_filled / $pi_total) * 100);

    // --- 2. Previous Education ---
    $edu_fields = [];
    $s1 = $conn->prepare("SELECT * FROM tbl_admittedPRG_prev WHERE Stu_code = :code");
    $s1->bindParam(':code', $code, PDO::PARAM_STR);
    $s1->execute();
    $prev = $s1->fetch(PDO::FETCH_ASSOC) ?: [];
    $edu_fields[] = $prev['admission_status'] ?? '';
    $edu_fields[] = $prev['matriculation_status'] ?? '';
    $s2 = $conn->prepare("SELECT COUNT(*) as cnt FROM tbl_applicant_education WHERE stu = :code");
    $s2->bindParam(':code', $code, PDO::PARAM_STR);
    $s2->execute();
    $edu_count = $s2->fetch(PDO::FETCH_ASSOC);
    $edu_fields[] = ($edu_count['cnt'] > 0) ? 'filled' : '';
    
    // WAEC Scratch Card information is optional and not counted toward completion.
    $edu_filled  = count(array_filter($edu_fields, function($v) { return trim($v) !== ''; }));
    $edu_total   = count($edu_fields);
    $edu_percent = round(($edu_filled / $edu_total) * 100);

    // --- 3. Language Proficiency ---
    $s4 = $conn->prepare("SELECT * FROM tbl_language_pro WHERE stu = :code");
    $s4->bindParam(':code', $code, PDO::PARAM_STR);
    $s4->execute();
    $lang_total  = $s4->rowCount();
    $lang_filled = 0;
    while ($lr = $s4->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($lr['state'])) $lang_filled++;
    }
    $lang_percent = $lang_total > 0 ? round(($lang_filled / $lang_total) * 100) : 0;

    // --- 4. Family & Sponsor ---
    $s5 = $conn->prepare("SELECT * FROM applicant_family_sponsor WHERE Stu_code = :code");
    $s5->bindParam(':code', $code, PDO::PARAM_STR);
    $s5->execute();
    $fs = $s5->fetch(PDO::FETCH_ASSOC) ?: [];
    $fs_fields = [
        $fs['first_name'] ?? '',
        $fs['last_name'] ?? '',
        $fs['Relationship'] ?? '',
        $fs['Education'] ?? '',
        $fs['Occupation'] ?? '',
        $fs['Address'] ?? '',
        $fs['Phone'] ?? '',
        $fs['Email'] ?? '',
        $fs['s_first_name'] ?? '',
        $fs['s_last_name'] ?? '',
        $fs['s_Phone'] ?? '',
        $fs['s_Address'] ?? '',
    ];
    $fs_filled  = count(array_filter($fs_fields, function($v) { return trim($v) !== ''; }));
    $fs_total   = count($fs_fields);
    $fs_percent = round(($fs_filled / $fs_total) * 100);

    // --- 5. Programme Sought ---
    $s6 = $conn->prepare("SELECT * FROM tbl_admittedPRG WHERE Stu_code = :code");
    $s6->bindParam(':code', $code, PDO::PARAM_STR);
    $s6->execute();
    $prg_rows   = $s6->fetchAll(PDO::FETCH_ASSOC);
    $prg_filled = 0;
    $prg_total  = 0;
    foreach ($prg_rows as $pr) {
        foreach (array('cump_id', 'prg_type', 'fac_id', 'splz') as $f) {
            $prg_total++;
            if (trim($pr[$f] ?? '') !== '') $prg_filled++;
        }
    }
    if ($prg_total === 0) { $prg_total = 4; }
    $prg_percent = round(($prg_filled / $prg_total) * 100);
    
    if ($prg_type==2 || $prg_type==4 || $prg_type==6 || $prg_type==8) {
        // --- 6. Research Proposal ---
        $s7 = $conn->prepare("SELECT COUNT(*) as cnt, SUM(CASE WHEN essay IS NOT NULL AND TRIM(essay) != '' THEN 1 ELSE 0 END) as filled
                              FROM tbl_essay_questions qn
                              LEFT JOIN tbl_applicant_essays ess ON qn.id = ess.question_id AND ess.stu = :code");
        $s7->bindParam(':code', $code, PDO::PARAM_STR);
        $s7->execute();
        $rp         = $s7->fetch(PDO::FETCH_ASSOC);
        $rp_total   = (int)$rp['cnt'] > 0 ? (int)$rp['cnt'] : 1;
        $rp_filled  = (int)$rp['filled'];
        $rp_percent = round(($rp_filled / $rp_total) * 100);
    }

    // --- 7. Documents ---
    $prg_type = !empty($prg_rows[0]['prg_type']) ? $prg_rows[0]['prg_type'] : 0;
    $s8 = $conn->prepare("SELECT file_name FROM tbl_document_type WHERE (prg_type = ? OR prg_type = 0) AND status = 1");
    $s8->execute(array($prg_type));
    $docTypes   = $s8->fetchAll(PDO::FETCH_ASSOC);
    $doc_total  = count($docTypes);
    $doc_filled = 0;
    foreach ($docTypes as $doc) {
        $pattern = "/student_docs/" . $doc['file_name'] . "_" . $code . ".%";
        $chk = $conn->prepare("SELECT appl_doc_id FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE ?");
        $chk->execute(array($code, $pattern));
        if ($chk->rowCount() > 0) $doc_filled++;
    }
    $doc_percent = $doc_total > 0 ? round(($doc_filled / $doc_total) * 100) : 0;

    // --- Build sections array ---
    if ($prg_type==2 || $prg_type==4 || $prg_type==6 || $prg_type==8) {
        $sections = array(
            array('label' => '1. Personal Information', 'filled' => $pi_filled,   'total' => $pi_total,   'percent' => $pi_percent),
            array('label' => '2. Previous Education',   'filled' => $edu_filled,  'total' => $edu_total,  'percent' => $edu_percent),
            array('label' => '3. Language Proficiency', 'filled' => $lang_filled, 'total' => $lang_total, 'percent' => $lang_percent),
            array('label' => '4. Family & Sponsor',     'filled' => $fs_filled,   'total' => $fs_total,   'percent' => $fs_percent),
            array('label' => '5. Programme Sought',     'filled' => $prg_filled,  'total' => $prg_total,  'percent' => $prg_percent),
            array('label' => '6. Documents',            'filled' => $doc_filled,  'total' => $doc_total,  'percent' => $doc_percent),
            array('label' => '7. Research Proposal',    'filled' => $rp_filled,   'total' => $rp_total,   'percent' => $rp_percent),
        );
    } else {
        $sections = array(
            array('label' => '1. Personal Information', 'filled' => $pi_filled,   'total' => $pi_total,   'percent' => $pi_percent),
            array('label' => '2. Previous Education',   'filled' => $edu_filled,  'total' => $edu_total,  'percent' => $edu_percent),
            array('label' => '3. Language Proficiency', 'filled' => $lang_filled, 'total' => $lang_total, 'percent' => $lang_percent),
            array('label' => '4. Family & Sponsor',     'filled' => $fs_filled,   'total' => $fs_total,   'percent' => $fs_percent),
            array('label' => '5. Programme Sought',     'filled' => $prg_filled,  'total' => $prg_total,  'percent' => $prg_percent),
            array('label' => '6. Documents',            'filled' => $doc_filled,  'total' => $doc_total,  'percent' => $doc_percent),
        );
    }

    $overall_filled  = array_sum(array_column($sections, 'filled'));
    $overall_total   = array_sum(array_column($sections, 'total'));
    $overall_percent = $overall_total > 0 ? round(($overall_filled / $overall_total) * 100) : 0;
    $overall_color   = $overall_percent < 40 ? 'danger' : ($overall_percent < 80 ? 'warning' : 'success');

// Return overall percent so callers can use it
$GLOBALS['app_overall_percent'] = $overall_percent;
?>

<div class="card mb-4">
    <div class="card-header">
        <h4 class="mb-0">Application Completion Summary</h4>
    </div>
    <div class="card-body">

        <!-- Overall progress -->
        <div class="mb-4">
            <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:600; margin-bottom:4px;">
                <span>Overall Completion</span>
                <span><?php echo $overall_percent; ?>%</span>
            </div>
            <div class="progress" style="height:14px; border-radius:7px;">
                <div class="progress-bar bg-<?php echo $overall_color; ?>" role="progressbar"
                     style="width:<?php echo $overall_percent; ?>%; border-radius:7px;"
                     aria-valuenow="<?php echo $overall_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>

        <div style="text-align:center; margin-bottom:10px;">
            <button type="button" class="btn btn-info btn-sm" id="toggleSectionDetails">
                <i class="fas fa-eye"></i>&nbsp; View Section Details
            </button>
        </div>

        <div id="sectionDetails" style="display:none;">
            <hr>
            <!-- Per-section rows -->
            <?php foreach ($sections as $sec):
                $c = $sec['percent'] < 40 ? 'danger' : ($sec['percent'] < 80 ? 'warning' : 'primary');
            ?>
            <div style="margin-bottom:14px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
                    <span><?php echo $sec['label']; ?></span>
                    <span class="text-<?php echo $c; ?>">
                        <?php echo $sec['percent']; ?>%
                        (<?php echo $sec['filled']; ?>/<?php echo $sec['total']; ?>)
                    </span>
                </div>
                <div class="progress" style="height:8px; border-radius:4px;">
                    <div class="progress-bar bg-<?php echo $c; ?>" role="progressbar"
                         style="width:<?php echo $sec['percent']; ?>%; border-radius:4px;"
                         aria-valuenow="<?php echo $sec['percent']; ?>" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
            document.getElementById('toggleSectionDetails').addEventListener('click', function() {
                var details = document.getElementById('sectionDetails');
                var btn = this;
                if (details.style.display === 'none') {
                    details.style.display = 'block';
                    btn.innerHTML = '<i class="fas fa-eye-slash"></i>&nbsp; Hide Section Details';
                    btn.classList.remove('btn-info');
                    btn.classList.add('btn-secondary');
                } else {
                    details.style.display = 'none';
                    btn.innerHTML = '<i class="fas fa-eye"></i>&nbsp; View Section Details';
                    btn.classList.remove('btn-secondary');
                    btn.classList.add('btn-info');
                }
            });
        </script>

    </div>
</div>

<?php
}
?>


<?php
function PreviousEducation($connection, $applId) {
    $conn = $connection;
    $code = $applId;

                        $edu_fields = [];

                        // Matriculation section
                        
                        $select_prev = "SELECT * FROM tbl_admittedPRG_prev WHERE Stu_code = :code";
                        
                        $cselect_prev = $conn->prepare($select_prev);
                        $cselect_prev->bindParam(':code', $code, PDO::PARAM_STR);
                        $cselect_prev->execute(); 
                        
                        $row_cselect_prev = $cselect_prev->fetch(PDO::FETCH_ASSOC);
                        
                        $edu_fields[] = $row_cselect_prev['admission_status'] ?? '';
                        $edu_fields[] = $row_cselect_prev['matriculation_status'] ?? '';

                        // Secondary school records (check if at least one exists)
                        $stmt_edu_check = $conn->prepare("SELECT COUNT(*) as cnt FROM tbl_applicant_education WHERE stu='".$code."'");
                        $stmt_edu_check->execute();
                        $edu_count = $stmt_edu_check->fetch();
                        $edu_fields[] = ($edu_count['cnt'] > 0) ? 'filled' : '';
                        
                        
                        // WAEC Scratch Card information is optional and not counted toward completion.

                        $edu_filled = count(array_filter($edu_fields, function($v) { return trim($v) !== ''; }));
                        $edu_total  = count($edu_fields);
                        $edu_percent = round(($edu_filled / $edu_total) * 100);
                        $edu_color = $edu_percent < 40 ? 'danger' : ($edu_percent < 80 ? 'warning' : 'primary');
                    ?>
                    <div style="width:100%; margin-top:-10px; margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
                            <span>Section Completion</span>
                            <span><?php echo $edu_percent; ?>% (<?php echo $edu_filled; ?>/<?php echo $edu_total; ?> fields)</span>
                        </div>
                        <div class="progress" style="height:10px;">
                            <div class="progress-bar bg-<?php echo $edu_color; ?>" role="progressbar"
                                 style="width:<?php echo $edu_percent; ?>%"
                                 aria-valuenow="<?php echo $edu_percent; ?>"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
    

<?php
}
?>


<?php
function LanguageProficiency($connection, $applId) {
    
    $conn = $connection;
    $code = $applId;
    $lang_stmt = $conn->prepare("SELECT * FROM tbl_language_pro WHERE stu='".$code."'");
    $lang_stmt->execute();
    $lang_total = $lang_stmt->rowCount();
    $lang_filled = 0;
    while($lang_row = $lang_stmt->fetch()) {
        if(!empty($lang_row['state'])) $lang_filled++;
    }
    $lang_percent = $lang_total > 0 ? round(($lang_filled / $lang_total) * 100) : 0;
    $lang_color = $lang_percent < 40 ? 'danger' : ($lang_percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Section Completion</span>
        <span><?php echo $lang_percent; ?>% (<?php echo $lang_filled; ?>/<?php echo $lang_total; ?> fields)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $lang_color; ?>" role="progressbar"
             style="width:<?php echo $lang_percent; ?>%"
             aria-valuenow="<?php echo $lang_percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>

<?php
}
?>


<?php
function FamilySponsor($connection, $applId) {
    $conn = $connection;
    $code = $applId;

    $stmt = $conn->prepare("SELECT * FROM applicant_family_sponsor WHERE Stu_code = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $fields = [
        $row['first_name'] ?? '',
        $row['last_name'] ?? '',
        $row['Relationship'] ?? '',
        $row['Education'] ?? '',
        $row['Occupation'] ?? '',
        $row['Address'] ?? '',
        $row['Phone'] ?? '',
        $row['Email'] ?? '',
        $row['s_first_name'] ?? '',
        $row['s_last_name'] ?? '',
        $row['s_Phone'] ?? '',
        $row['s_Address'] ?? '',
    ];
    $filled = count(array_filter($fields, function($v) { return trim($v) !== ''; }));
    $total = count($fields);
    $percent = round(($filled / $total) * 100);
    $color = $percent < 40 ? 'danger' : ($percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Section Completion</span>
        <span><?php echo $percent; ?>% (<?php echo $filled; ?>/<?php echo $total; ?> fields)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar"
             style="width:<?php echo $percent; ?>%"
             aria-valuenow="<?php echo $percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>

<?php
}
?>


<?php
function ApplicationDocuments($connection, $applId, $prgType) {
    $conn = $connection;
    $code = $applId;
    $prg_type = $prgType;

    $stmt = $conn->prepare("SELECT file_name FROM tbl_document_type WHERE (prg_type = ? OR prg_type = 0) AND status = 1");
    $stmt->execute([$prg_type]);
    $docTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = count($docTypes);
    $filled = 0;

    foreach ($docTypes as $doc) {
        $fieldName = $doc['file_name'];
        $pattern = "/student_docs/" . $fieldName . "_" . $code . ".%";
        $check = $conn->prepare("SELECT appl_doc_id FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE ?");
        $check->execute([$code, $pattern]);
        if ($check->rowCount() > 0) {
            $filled++;
        }
    }

    $percent = $total > 0 ? round(($filled / $total) * 100) : 0;
    $color = $percent < 40 ? 'danger' : ($percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Section Completion</span>
        <span><?php echo $percent; ?>% (<?php echo $filled; ?>/<?php echo $total; ?> documents)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar"
             style="width:<?php echo $percent; ?>%"
             aria-valuenow="<?php echo $percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>

<?php
}
?>




<?php
function ProgrammeSought($connection, $applId) {
    $conn = $connection;
    $code = $applId;

    $stmt = $conn->prepare("SELECT * FROM tbl_admittedPRG WHERE Stu_code = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $filled = 0;
    $total  = 0;

    foreach ($rows as $row) {
        foreach (array('cump_id', 'prg_type', 'fac_id', 'splz') as $f) {
            $total++;
            if (trim($row[$f] ?? '') !== '') $filled++;
        }
    }

    if ($total === 0) { $total = 4; }

    $percent = round(($filled / $total) * 100);
    $color   = $percent < 40 ? 'danger' : ($percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Section Completion</span>
        <span><?php echo $percent; ?>% (<?php echo $filled; ?>/<?php echo $total; ?> fields)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar"
             style="width:<?php echo $percent; ?>%"
             aria-valuenow="<?php echo $percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>
<?php
}
?>





<?php
function ReportProblem($connection, $applId, $url) {
    $conn = $connection;
    $code = $applId;

    $stmt = $conn->prepare("SELECT * FROM tbl_applicant_prob WHERE app_code = :code ORDER BY recordedAt DESC");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div style="background-color:#f0f4ff; border:1.5px solid #1f6feb; border-radius:6px; padding:16px 20px; margin-bottom:16px;">
    <p style="margin:0 0 6px; font-size:14px; font-weight:bold; color:#1a3c6e;"><i class="fas fa-exclamation-circle"></i>&nbsp; Report a Problem</p>
    <p style="margin:0 0 10px; font-size:13px; color:#555555; line-height:1.6;">
        Encountered an issue while filling your application? Describe it below and our team will respond as soon as possible.
    </p>

    <form id="report_problem_form">
        <input type="hidden" name="action" value="report_problem">
        <input type="hidden" name="app_code" value="<?php echo $code; ?>">
        <div style="display:none;">
            <input type="text" name="url_info" value="<?php echo $url; ?>">
        </div>
        <div style="background-color:#ffffff; border:1px solid #1f6feb; border-radius:4px; padding:10px 14px; margin-bottom:14px;">
            <p style="margin:0 0 6px; font-size:13px; font-weight:bold; color:#1a3c6e;">Describe the problem</p>
            <textarea name="problem_info" rows="4" placeholder="Describe the issue you encountered..." required
                style="width:100%; border:1px solid #c7d7f9; border-radius:4px; padding:8px 10px; font-size:13px; color:#555555; resize:none; outline:none;"></textarea>
        </div>
        <button type="submit" style="background-color:#1f6feb; color:#fff; border:none; border-radius:4px; padding:8px 20px; font-size:13px; font-weight:bold; cursor:pointer;">
            <span id="prob_spinner"></span>&nbsp;<span id="prob_indicator">Submit Report</span>
        </button>
    </form>

    <?php if (!empty($problems)): ?>
    <div style="margin-top:18px;">
        <p style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#1a3c6e;">Your Previous Reports</p>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background-color:#1f6feb; color:#fff;">
                        <th style="padding:8px 10px; border:1px solid #c7d7f9;">#</th>
                        <th style="padding:8px 10px; border:1px solid #c7d7f9;">Problem</th>
                        <th style="padding:8px 10px; border:1px solid #c7d7f9;">Answer</th>
                        <th style="padding:8px 10px; border:1px solid #c7d7f9;">Status</th>
                        <th style="padding:8px 10px; border:1px solid #c7d7f9;">Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($problems as $i => $p): ?>
                    <tr style="background-color:<?php echo $i % 2 === 0 ? '#f4f7fc' : '#ffffff'; ?>;">
                        <td style="padding:8px 10px; border:1px solid #c7d7f9; color:#555555;"><?php echo $i + 1; ?></td>
                        <td style="padding:8px 10px; border:1px solid #c7d7f9; color:#555555;"><?php echo htmlspecialchars($p['problem_info']); ?></td>
                        <td style="padding:8px 10px; border:1px solid #c7d7f9; color:#555555;"><?php echo !empty($p['problem_answer']) ? htmlspecialchars($p['problem_answer']) : '<span style="color:#aaa;">Awaiting response</span>'; ?></td>
                        <td style="padding:8px 10px; border:1px solid #c7d7f9; text-align:center;">
                            <?php if ($p['status'] == 1): ?>
                                <span style="background-color:#d4edda; color:#155724; padding:3px 10px; border-radius:4px; font-size:12px; font-weight:bold;">Solved</span>
                            <?php else: ?>
                                <span style="background-color:#fff3cd; color:#856404; padding:3px 10px; border-radius:4px; font-size:12px; font-weight:bold;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:8px 10px; border:1px solid #c7d7f9; color:#555555;"><?php echo $p['recordedAt']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function(){
    $("#report_problem_form").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $('#prob_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#prob_indicator').html("Submitting...");
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#prob_spinner').fadeOut('fast');
                $('#prob_indicator').html("Submit Report");
                if(data.status == 200){
                    iziToast.success({ title: 'Success', message: data.message, position: 'topCenter' });
                    setTimeout(function(){ window.location.reload(); }, 1500);
                } else {
                    iziToast.warning({ title: 'Error', message: data.message, position: 'topCenter' });
                }
            },
            error: function(){
                $('#prob_spinner').fadeOut('fast');
                $('#prob_indicator').html("Submit Report");
                iziToast.warning({ title: 'Error', message: 'Something went wrong!', position: 'topCenter' });
            }
        });
    });
});
</script>
<?php
}
?>


<?php
function ResearchProposal($connection, $applId) {
    $conn = $connection;
    $code = $applId;

    $stmt = $conn->prepare("SELECT COUNT(*) as cnt, SUM(CASE WHEN essay IS NOT NULL AND TRIM(essay) != '' THEN 1 ELSE 0 END) as filled
                            FROM tbl_essay_questions qn
                            LEFT JOIN tbl_applicant_essays ess ON qn.id = ess.question_id AND ess.stu = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $total  = (int)$row['cnt'] > 0 ? (int)$row['cnt'] : 1;
    $filled = (int)$row['filled'];
    $percent = round(($filled / $total) * 100);
    $color   = $percent < 40 ? 'danger' : ($percent < 80 ? 'warning' : 'primary');
?>
<div style="width:100%; margin-top:-10px; margin-bottom:15px;">
    <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:3px;">
        <span>Section Completion</span>
        <span><?php echo $percent; ?>% (<?php echo $filled; ?>/<?php echo $total; ?> questions answered)</span>
    </div>
    <div class="progress" style="height:10px;">
        <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar"
             style="width:<?php echo $percent; ?>%"
             aria-valuenow="<?php echo $percent; ?>"
             aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>
<?php
}
?>
