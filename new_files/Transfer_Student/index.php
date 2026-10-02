<?php
// Transfer-from-another-school/university application form for an already
// logged-in applicant (created via Create_account). This is their first
// UNIGOM application - identity was already collected at signup, so this
// form covers what's still missing: origin & contact details, secondary
// education, previous higher-education studies, the academic choice
// (faculty / programme / department / class applied for), and required
// documents. Submits to Transfer_Student/controller.php (action=complete_application).

$Identification = $_SESSION['identification'] ?? '';
$applicant = null;
if($Identification !== ''){
    $stmt = $conn->prepare("SELECT applicant_id, fname, lname, application_code FROM tbl_applicants WHERE application_code = :identification");
    $stmt->execute([':identification' => $Identification]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(!$applicant){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-danger">We could not find your applicant record. Please log in again or contact admissions support.</div>';
    echo '</div></section></div>';
    return;
}

$admittedStmt = $conn->prepare("SELECT Aprg_id FROM tbl_admittedPRG WHERE Stu_code = :stu_code LIMIT 1");
$admittedStmt->execute([':stu_code' => $applicant['application_code']]);
if($admittedStmt->rowCount() > 0){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> You have already submitted your application. Editing your submitted details is not available yet &mdash; please contact admissions support if you need to make changes.</div>';
    echo '</div></section></div>';
    return;
}

$current_date = date('Y-m-d');
$periodStmt = $conn->prepare("SELECT id FROM tbl_application_periods WHERE status = 'active' AND start_date <= :today AND end_date >= :today ORDER BY created_at DESC LIMIT 1");
$periodStmt->execute([':today' => $current_date]);
if($periodStmt->rowCount() === 0){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-warning"><i class="fas fa-clock"></i> The application period is currently closed. You can no longer view or edit the application form.</div>';
    echo '</div></section></div>';
    return;
}

$choiceStructure = null;
try {
    $csStmt = $conn->prepare("SELECT file_path FROM tbl_choice_structure WHERE is_active = 1 ORDER BY uploaded_at DESC LIMIT 1");
    $csStmt->execute();
    $choiceStructure = $csStmt->fetch(PDO::FETCH_ASSOC) ?: null;
} catch(PDOException $e){
    $choiceStructure = null;
}
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <form id="apply" action="apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="complete_application">
                        <div class="card">
                            <div class="card-header row" style="display:flex; flex-direction:column; align-items:center;">
                                <h4 style="text-align:center;margin:0 0 4px;">Bulletin d&rsquo;inscription sp&eacute;ciale</h4>
                                <div style="display:flex; flex-wrap:wrap; justify-content:center; width:100%; gap:6px;">
                                    <button type="button" id="section-1-indicator" class="btn btn-primary pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">1</span> &nbsp;Identit&eacute;</button>
                                    <button type="button" id="section-2-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">2</span> &nbsp;&Eacute;tudes secondaires</button>
                                    <button type="button" id="section-3-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">3</span> &nbsp;&Eacute;tudes ant&eacute;rieures</button>
                                    <button type="button" id="section-4-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">4</span> &nbsp;Inscription</button>
                                    <button type="button" id="section-5-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">5</span> &nbsp;Documents</button>
                                </div>
                            </div>

                            <!-- ============ SECTION 1 : IDENTITY ============ -->
                            <div class="card-body row" id="section-1">
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Date de naissance <code><b><span id="dob_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo date('Y-m-d', strtotime('-20 years')); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Lieu de naissance</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder="e.g. Goma">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pays de naissance</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select name="country_of_birth" id="country_of_birth" class="form-control select2" required>
                                            <?php
                                                $sql_cob = $conn->prepare("SELECT cntr_id, cntr_name FROM tbl_country ORDER BY cntr_name ASC");
                                                $sql_cob->execute();
                                                while($cob = $sql_cob->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                            <option value="<?php echo $cob['cntr_id']; ?>"><?php echo htmlspecialchars($cob['cntr_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nationalit&eacute;</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-globe-americas"></i></div>
                                        </div>
                                        <select name="nationality" id="nationality" class="form-control select2">
                                            <?php
                                                $sql_nat = $conn->prepare("SELECT cntr_id, cntr_name FROM tbl_country ORDER BY cntr_name ASC");
                                                $sql_nat->execute();
                                                while($nat = $sql_nat->fetch(PDO::FETCH_ASSOC)){
                                                    $is_default = in_array(mb_strtolower(trim($nat['cntr_name'])), ['rd congo','r.d. congo','republique democratique du congo','rdc'], true);
                                            ?>
                                            <option value="<?php echo $nat['cntr_id']; ?>" <?php echo $is_default ? 'selected' : ''; ?>><?php echo htmlspecialchars($nat['cntr_name']); ?></option>
                                            <?php } ?>
                                            <option value="0">Autres</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nom du p&egrave;re <code><b><span id="father_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" name="father_name" id="father_name" class="form-control" placeholder="noms complets du p&egrave;re" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nom de la m&egrave;re <code><b><span id="mother_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                        </div>
                                        <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="noms complets de la m&egrave;re" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone du p&egrave;re (le cas &eacute;ch&eacute;ant)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +2327245....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone de la m&egrave;re (le cas &eacute;ch&eacute;ant)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +232724....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Groupe sanguin</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-tint"></i></div>
                                        </div>
                                        <select name="blood_type" id="blood_type" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>&Eacute;tat civil</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-ring"></i></div>
                                        </div>
                                        <select name="marital_status" id="marital_status" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="Single">C&eacute;libataire</option>
                                            <option value="Married">Mari&eacute;(e)</option>
                                            <option value="Divorced">Divorc&eacute;(e)</option>
                                            <option value="Widowed">Veuf(ve)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Affiliation religieuse</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-praying-hands"></i></div>
                                        </div>
                                        <input type="text" name="religious_affiliation" class="form-control" placeholder="e.g. Catholique">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Province d&rsquo;origine des parents</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" name="parents_province_origin" class="form-control" placeholder="e.g. Nord-Kivu">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Territoire d&rsquo;origine</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map-pin"></i></div>
                                        </div>
                                        <input type="text" name="territory_of_origin" class="form-control" placeholder="e.g. Nyiragongo">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Adresse du candidat (Quartier &amp; Avenue, Num&eacute;ro)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-home"></i></div>
                                        </div>
                                        <input type="text" name="candidate_address" class="form-control" placeholder="e.g. Himbi, Avenue Bujavu No. 12">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pays de r&eacute;sidence <code><b><span id="country_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select name="country" id="country" class="form-control select2" required>
                                            <?php
                                                $sql_cntr = $conn->prepare("SELECT cntr_id, cntr_name FROM tbl_country ORDER BY cntr_name ASC");
                                                $sql_cntr->execute();
                                                while($cntr = $sql_cntr->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                            <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo htmlspecialchars($cntr['cntr_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection2()"><span id="spinner1"></span>&nbsp; <span id="indicator1">Suivant</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 2 : SECONDARY EDUCATION ============ -->
                            <div class="card-body row" id="section-2" hidden>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Nom de l&rsquo;&eacute;cole secondaire fr&eacute;quent&eacute;e</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-school"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="secondary_school_name">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label>Province de l&rsquo;&eacute;cole</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="secondary_school_province">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label>Territoire de l&rsquo;&eacute;cole</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="secondary_school_territory">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Pays de l&rsquo;&eacute;cole</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select name="secondary_school_territory_country" id="secondary_school_territory_country" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <?php
                                                $sql_sch_cntr = $conn->prepare("SELECT cntr_id, cntr_name FROM tbl_country ORDER BY cntr_name ASC");
                                                $sql_sch_cntr->execute();
                                                while($sch_cntr = $sql_sch_cntr->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                            <option value="<?php echo $sch_cntr['cntr_id']; ?>"><?php echo htmlspecialchars($sch_cntr['cntr_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Section suivie aux Humanit&eacute;s</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="humanities_section" placeholder="e.g. Scientifique">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Statut de l&rsquo;&eacute;cole secondaire</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-landmark"></i></div>
                                        </div>
                                        <select name="secondary_school_status" id="secondary_school_status" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="Public">Public</option>
                                            <option value="Private">Priv&eacute;</option>
                                            <option value="Catholic">Catholique</option>
                                            <option value="Protestant">Protestant</option>
                                            <option value="Muslim">Musulman</option>
                                            <option value="Other">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-4">
                                    <label>Ann&eacute;e d&rsquo;obtention du dipl&ocirc;me</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
                                        </div>
                                        <input type="number" class="form-control" name="diploma_year" min="1970" max="<?php echo date('Y'); ?>" placeholder="e.g. <?php echo date('Y'); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Pourcentage du dipl&ocirc;me</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-percent"></i></div>
                                        </div>
                                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="diploma_percentage">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Num&eacute;ro du Dipl&ocirc;me d&rsquo;&Eacute;tat</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-hashtag"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="state_diploma_number">
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection3()"><span id="spinner2"></span>&nbsp; <span id="indicator2">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner2b"></span>&nbsp; <span id="indicator2b">Retour</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 3 : PREVIOUS STUDIES (AFTER HUMANITIES) ============ -->
                            <div class="card-body row" id="section-3" hidden>
                                <div class="col-12">
                                    <p class="text-muted">Indiquez, ann&eacute;e par ann&eacute;e, vos &eacute;tudes depuis la fin du secondaire dans votre &eacute;tablissement pr&eacute;c&eacute;dent.</p>
                                </div>
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-sm" id="previous_studies_table">
                                        <thead>
                                            <tr>
                                                <th style="min-width:100px;">Ann&eacute;e</th>
                                                <th style="min-width:180px;">&Eacute;tablissement</th>
                                                <th style="min-width:110px;">Promotion</th>
                                                <th style="min-width:100px;">Pourcentage</th>
                                                <th style="min-width:110px;">Mention</th>
                                                <th style="min-width:110px;">D&eacute;c. Jury</th>
                                                <th style="min-width:130px;">Cr&eacute;dits non capitalis&eacute;s</th>
                                                <th style="min-width:100px;">Session</th>
                                                <th style="width:40px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="previous_studies_body">
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-light btn-sm" id="add_previous_study_row"><i class="fas fa-plus"></i>&nbsp;Ajouter une ligne</button>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection4()"><span id="spinner3"></span>&nbsp; <span id="indicator3">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3b"></span>&nbsp; <span id="indicator3b">Retour</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 4 : APPLICATION FOR ENROLLMENT ============ -->
                            <div class="card-body row" id="section-4" hidden>

                                <?php if($choiceStructure && !empty($choiceStructure['file_path'])): ?>
                                <div class="col-12 mb-3">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap" style="margin-bottom:0;">
                                        <span>
                                            <i class="fas fa-file-pdf"></i>&nbsp;
                                            Consultez la structure des choix avant de s&eacute;lectionner votre fili&egrave;re.
                                        </span>
                                        <a href="<?php echo htmlspecialchars($choiceStructure['file_path']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>&nbsp;Voir le document
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <h6 class="mb-3"><i class="fas fa-exchange-alt"></i>&nbsp; Fili&egrave;re demand&eacute;e <code><b><span id="choice1_star"></span></b></code></h6>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Facult&eacute;</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-university"></i></div>
                                        </div>
                                        <select name="fac_id_1" id="fac_id_1" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Chargement...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Type de programme</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-graduation-cap"></i></div>
                                        </div>
                                        <select name="prg_type_id_1" id="prg_type_id_1" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir la facult&eacute; d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>D&eacute;partement &nbsp;<span id="spinner_dept_1"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sitemap"></i></div>
                                        </div>
                                        <select name="dept_choice_1" id="dept_choice_1" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Option &nbsp;<span id="spinner_opt_1"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-stream"></i></div>
                                        </div>
                                        <select name="opt_choice_1" id="opt_choice_1" class="form-control select2" disabled>
                                            <option value="" disabled selected hidden>Choisir le d&eacute;partement d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Promotion demand&eacute;e <small class="text-muted">(hors 1&egrave;re ann&eacute;e)</small> &nbsp;<span id="spinner_level_1"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-layer-group"></i></div>
                                        </div>
                                        <select name="level_id" id="level_id" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection5()"><span id="spinner7"></span>&nbsp; <span id="indicator7">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner8"></span>&nbsp; <span id="indicator8">Retour</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 5 : DOCUMENTS ============ -->
                            <div class="card-body row" id="section-5" hidden>
                                <div class="col-12" id="documents_list">
                                    <div class="alert alert-light text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Chargement des documents requis...
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><i class="fas fa-check"></i>&nbsp;
                                        <span id="spinner"></span>&nbsp;<span id="indicator">Soumettre</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection4()"><span id="spinner9"></span>&nbsp; <span id="indicator9">Retour</span></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include'../../comb/orgin.php'; ?>
<?php include'../../comb/coda.php'; ?>

<style>
.pg-step-btn {
    border-radius: 6px !important;
}

/* select2 replaces the <select> with a span that Bootstrap's .input-group does
   not treat as a form control, so it wraps onto its own line under the icon.
   Make it behave like the input it replaced. */
.input-group > .select2-container {
    position: relative;
    flex: 1 1 auto;
    width: 1% !important;
    min-width: 0;
}
.input-group > .select2-container .select2-selection--single {
    height: calc(1.5em + .75rem + 2px);
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.input-group > .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: calc(1.5em + .75rem);
    padding-left: .75rem;
}
.input-group > .select2-container .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + .75rem);
}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        loadFaculties(1);
        // documents are attached to the form type, not the programme, so they can
        // be fetched straight away
        loadDocuments();
        addPreviousStudyRow();

        $('#add_previous_study_row').on('click', function(){
            addPreviousStudyRow();
        });

        $(document).on('click', '.remove_previous_study_row', function(){
            if($('#previous_studies_body tr').length > 1){
                $(this).closest('tr').remove();
            } else {
                $(this).closest('tr').find('input').val('');
                $(this).closest('tr').find('select').val('');
            }
        });

        // Single chain: Faculty -> Programme -> Department -> Option, plus the
        // promotion being transferred into (all levels of the chosen programme).
        [1].forEach(function(n){
            $('#fac_id_' + n).on('change', function() {
                loadProgramTypesByFaculty(n, $(this).val());
            });

            $('#prg_type_id_' + n).on('change', function() {
                var prgTypeId = $(this).val();
                loadDepartments(n, prgTypeId);
                loadLevelsByProgramType(n, prgTypeId);
            });

            $('#dept_choice_' + n).on('change', function() {
                loadOptions(n, $(this).val());
            });
        });

        $("#apply").submit(function(e){
            e.preventDefault();

            // required documents + per-document size limit
            var missing = [], tooBig = [];
            $('.doc-input').each(function(){
                var f = this.files[0];
                if($(this).data('required') == 1 && !f){
                    missing.push($(this).data('label'));
                }
                var maxMb = parseFloat($(this).data('max-mb'));
                if(f && maxMb && f.size > maxMb * 1024 * 1024){
                    tooBig.push($(this).data('label') + ' (max ' + maxMb + ' Mo)');
                }
            });
            if(missing.length){
                pop_wrong("Documents manquants : " + missing.join(', '));
                return;
            }
            if(tooBig.length){
                pop_wrong("Fichier trop volumineux : " + tooBig.join(', '));
                return;
            }

            var formData = new FormData(this);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Traitement...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "../new_files/Transfer_Student/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Soumettre");
                    if(data.status==200){
                        $("#apply")[0].reset();
                        swal({
                            title: "Candidature soumise !",
                            text: data.message,
                            icon: "success",
                            button: "OK"
                        }).then(function(){
                            window.location.href = "../applicant/edu.php?mis=1";
                        });
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled',false);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled',false);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $("#sBtn").attr('disabled',false);
                    $('#indicator').html("Soumettre");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
    });

   function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }

    // select2 cannot measure a hidden element, so dropdowns in wizard steps that
    // start hidden render collapsed if left to the theme's page-load auto-init.
    // Re-init them once their step is actually visible, and after each AJAX refill.
    function initSelect2In(selector) {
        $(selector).find('select.select2').addBack('select.select2').each(function(){
            var $el = $(this);
            if ($el.data('select2')) {
                $el.select2('destroy');
            }
            $el.select2({ width: '100%' });
        });
    }

    function addPreviousStudyRow(){
        var row = '<tr>'
            + '<td><input type="text" class="form-control form-control-sm" name="prior_studies[year][]" placeholder="e.g. 2022"></td>'
            + '<td><input type="text" class="form-control form-control-sm" name="prior_studies[establishment][]"></td>'
            + '<td><input type="text" class="form-control form-control-sm" name="prior_studies[promotion][]"></td>'
            + '<td><input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm" name="prior_studies[percentage][]"></td>'
            + '<td><select class="form-control form-control-sm" name="prior_studies[mention][]">'
                + '<option value="">Choisir...</option>'
                + '<option value="Excellent (A)">Excellent (A)</option>'
                + '<option value="Tres Bien (B)">Tr&egrave;s Bien (B)</option>'
                + '<option value="Bien (C)">Bien (C)</option>'
                + '<option value="Assez Bien (D)">Assez Bien (D)</option>'
                + '<option value="Passable (E)">Passable (E)</option>'
                + '<option value="Insuffisant (F)">Insuffisant (F)</option>'
                + '<option value="Insatisfaisant (G)">Insatisfaisant (G)</option>'
              + '</select></td>'
            + '<td><select class="form-control form-control-sm" name="prior_studies[jury_decision][]">'
                + '<option value="">Choisir...</option>'
                + '<option value="ADM">ADM</option>'
                + '<option value="COMP">COMP</option>'
                + '<option value="DEF">DEF</option>'
                + '<option value="AJ">AJ</option>'
              + '</select></td>'
            + '<td><input type="text" class="form-control form-control-sm" name="prior_studies[credits][]"></td>'
            + '<td><input type="text" class="form-control form-control-sm" name="prior_studies[session][]"></td>'
            + '<td class="text-center"><button type="button" class="btn btn-icon btn-light btn-sm remove_previous_study_row"><i class="fas fa-trash text-danger"></i></button></td>'
            + '</tr>';
        $('#previous_studies_body').append(row);
    }

    function loadFaculties(n) {
        var $faculty = $('#fac_id_' + n);

        $faculty.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_faculties' },
            success: function(data) {
                var options = '<option value="" disabled selected hidden>Choisir...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.fac_id + '">' + item.fac_full_name + '</option>';
                    });
                    $faculty.html(options).prop('disabled', false).trigger('change');
                } else {
                    $faculty.html('<option value="" disabled selected hidden>Aucune facult&eacute; disponible</option>').trigger('change');
                }
                initSelect2In('#fac_id_' + n);
            },
            error: function() {
                $faculty.html('<option value="" disabled selected hidden>Impossible de charger les facult&eacute;s</option>').trigger('change');
            }
        });
    }

    function loadProgramTypesByFaculty(n, facId) {
        var $programType = $('#prg_type_id_' + n);

        $programType.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');

        if (!facId) {
            $programType.empty().append('<option value="" disabled selected hidden>Choisir la facult&eacute; d&rsquo;abord...</option>').trigger('change');
            return;
        }

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_program_types_by_faculty', fac_id: facId },
            success: function(data) {
                var options = '<option value="" disabled selected hidden>Choisir...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.prg_type_id + '">' + item.prg_type_full_name + '</option>';
                    });
                    $programType.html(options).prop('disabled', false).trigger('change');
                } else {
                    $programType.html('<option value="" disabled selected hidden>Aucun type de programme disponible</option>').trigger('change');
                }
                initSelect2In('#prg_type_id_' + n);
            },
            error: function() {
                $programType.html('<option value="" disabled selected hidden>Impossible de charger les types de programme</option>').trigger('change');
            }
        });
    }

    function loadDepartments(n, prgTypeId) {
        var $dept = $('#dept_choice_' + n);
        var $spin = $('#spinner_dept_' + n);

        $dept.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');
        $spin.html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

        if (!prgTypeId) {
            $dept.empty().append('<option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>').trigger('change');
            $spin.fadeOut('fast');
            return;
        }

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_departments', prg_type_id: prgTypeId },
            success: function(data) {
                $spin.fadeOut('fast');
                var options = '<option value="" disabled selected hidden>Choisir...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.dept_id + '">' + item.dept_full_name + '</option>';
                    });
                    $dept.html(options).prop('disabled', false).trigger('change');
                } else {
                    $dept.html('<option value="" disabled selected hidden>Aucun d&eacute;partement disponible</option>').trigger('change');
                }
                initSelect2In('#dept_choice_' + n);
            },
            error: function() {
                $spin.fadeOut('fast');
                $dept.html('<option value="" disabled selected hidden>Impossible de charger les d&eacute;partements</option>').trigger('change');
            }
        });
    }

    function loadOptions(n, deptId) {
        var $opt = $('#opt_choice_' + n);
        var $spin = $('#spinner_opt_' + n);

        $opt.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');
        $spin.html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

        if (!deptId) {
            $opt.empty().append('<option value="" disabled selected hidden>Choisir le d&eacute;partement d&rsquo;abord...</option>').trigger('change');
            $spin.fadeOut('fast');
            return;
        }

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_options', dept_id: deptId },
            success: function(data) {
                $spin.fadeOut('fast');
                var options = '<option value="" disabled selected hidden>Choisir...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.opt_id + '">' + item.opt_full_name + '</option>';
                    });
                    $opt.html(options).prop('disabled', false).trigger('change');
                } else {
                    $opt.html('<option value="" disabled selected hidden>Aucune option disponible</option>').trigger('change');
                }
                initSelect2In('#opt_choice_' + n);
            },
            error: function() {
                $spin.fadeOut('fast');
                $opt.html('<option value="" disabled selected hidden>Impossible de charger les options</option>').trigger('change');
            }
        });
    }

    function loadLevelsByProgramType(n, prgTypeId) {
        var $level = $('#level_id');
        var $spin = $('#spinner_level_' + n);

        $level.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');
        $spin.html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

        if (!prgTypeId) {
            $level.empty().append('<option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>').trigger('change');
            $spin.fadeOut('fast');
            return;
        }

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_levels_by_program_type', prg_type_id: prgTypeId },
            success: function(data) {
                $spin.fadeOut('fast');
                var options = '<option value="" disabled selected hidden>Choisir...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.level_id + '">' + item.level_full_name + '</option>';
                    });
                    $level.html(options).prop('disabled', false).trigger('change');
                } else {
                    $level.html('<option value="" disabled selected hidden>Aucune promotion disponible au-del&agrave; de la 1&egrave;re ann&eacute;e</option>').trigger('change');
                }
                initSelect2In('#level_id');
            },
            error: function() {
                $spin.fadeOut('fast');
                $level.html('<option value="" disabled selected hidden>Impossible de charger les promotions</option>').trigger('change');
            }
        });
    }

    function loadDocuments() {
        var $container = $('#documents_list');
        $container.html('<div class="alert alert-light text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des documents requis...</div>');

        $.ajax({
            url: '../new_files/Transfer_Student/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_documents' },
            success: function(data) {
                if (!data || !data.length) {
                    $container.html('<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Aucun document requis pour ce formulaire.</div>');
                    return;
                }

                var html = '<h6 class="mb-3"><i class="fas fa-paperclip"></i> Documents requis</h6><div class="row">';
                $.each(data, function(_, doc) {
                    var acceptAttr = doc.file_type ? ' accept="' + $.map(doc.file_type.split(','), function(t){ return '.'+t.trim(); }).join(',') + '"' : '';
                    var sizeNote = doc.max_size_mb ? ' &mdash; max ' + doc.max_size_mb + ' Mo' : '';
                    html += '<div class="form-group col-12 col-sm-6 col-lg-6">'
                        + '<label>' + doc.document_name
                        + (doc.is_required == 1 ? ' <code><b>*</b></code>' : ' <small class="text-muted">(facultatif)</small>')
                        + (doc.international_required == 1 ? ' <span class="badge badge-warning">Candidats internationaux uniquement</span>' : '')
                        + '</label>'
                        + '<div class="input-group">'
                        + '<div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-file-upload"></i></div></div>'
                        + '<input type="file" class="form-control doc-input" name="documents[' + doc.doc_id + ']"' + acceptAttr
                        + ' data-required="' + (doc.is_required == 1 ? 1 : 0) + '"'
                        + ' data-label="' + doc.document_name.replace(/"/g, '&quot;') + '"'
                        + ' data-max-mb="' + (doc.max_size_mb || '') + '">'
                        + '</div>'
                        + (doc.file_type ? '<small class="form-text text-muted">Format(s) accept&eacute;(s) : ' + doc.file_type + sizeNote + '</small>' : '')
                        + (doc.file_name ? '<small class="form-text"><a href="' + doc.file_name + '" target="_blank"><i class="fas fa-download"></i> T&eacute;l&eacute;charger le mod&egrave;le</a></small>' : '')
                        + '</div>';
                });
                html += '</div>';
                $container.html(html);
            },
            error: function() {
                $container.html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> Impossible de charger les documents requis.</div>');
            }
        });
    }

    function goToSection2() {
        var dob = document.getElementById('dob').value.trim();
        var father = document.getElementById('father_name').value.trim();
        var mother = document.getElementById('mother_name').value.trim();

        $("#dob_star, #father_star, #mother_star").html("");

        var valid = true;
        if (!dob) { $("#dob_star").html("*"); valid = false; }
        if (!father) { $("#father_star").html("*"); valid = false; }
        if (!mother) { $("#mother_star").html("*"); valid = false; }

        if (!valid) {
            pop_wrong("Veuillez remplir tous les champs obligatoires");
            return;
        }

        $('#spinner1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator1').html("Chargement...");
            setTimeout(function() {
                $('#spinner1').fadeOut('fast');
                $('#indicator1').html("Suivant");
                $("#section-1-indicator").removeClass('btn-primary');
                $("#section-1-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $('#section-1').attr('hidden',true);
                $('#section-2').attr('hidden',false);
                initSelect2In('#section-2');
                }, 800);
    }

    function goBackToSection1() {
        $('#spinner2b').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2b').html("Chargement...");
            setTimeout(function() {
                $('#spinner2b').fadeOut('fast');
                $('#indicator2b').html("Retour");
                $("#section-2-indicator").removeClass('btn-primary');
                $("#section-2-indicator").addClass('btn-light');
                $("#section-1-indicator").removeClass('btn-light');
                $("#section-1-indicator").addClass('btn-primary');
                $('#section-2').attr('hidden',true);
                $('#section-1').attr('hidden',false);
                initSelect2In('#section-1');
            }, 800);
    }

    function goToSection3() {
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Chargement...");
            setTimeout(function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Suivant");
                $("#section-2-indicator").removeClass('btn-primary');
                $("#section-2-indicator").addClass('btn-light');
                $("#section-3-indicator").removeClass('btn-light');
                $("#section-3-indicator").addClass('btn-primary');
                $('#section-2').attr('hidden',true);
                $('#section-3').attr('hidden',false);
                initSelect2In('#section-3');
                }, 800);
    }

    function goBackToSection2() {
        $('#spinner3b').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3b').html("Chargement...");
            setTimeout(function() {
                $('#spinner3b').fadeOut('fast');
                $('#indicator3b').html("Retour");
                $("#section-3-indicator").removeClass('btn-primary');
                $("#section-3-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $('#section-3').attr('hidden',true);
                $('#section-2').attr('hidden',false);
                initSelect2In('#section-2');
            }, 800);
    }

    function goToSection4() {
        $('#spinner3').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3').html("Chargement...");
            setTimeout(function() {
                $('#spinner3').fadeOut('fast');
                $('#indicator3').html("Suivant");
                $("#section-3-indicator").removeClass('btn-primary');
                $("#section-3-indicator").addClass('btn-light');
                $("#section-4-indicator").removeClass('btn-light');
                $("#section-4-indicator").addClass('btn-primary');
                $('#section-3').attr('hidden',true);
                $('#section-4').attr('hidden',false);
                initSelect2In('#section-4');
                }, 800);
    }

    function goBackToSection3() {
        $('#spinner8').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator8').html("Chargement...");
            setTimeout(function() {
                $('#spinner8').fadeOut('fast');
                $('#indicator8').html("Retour");
                $("#section-4-indicator").removeClass('btn-primary');
                $("#section-4-indicator").addClass('btn-light');
                $("#section-3-indicator").removeClass('btn-light');
                $("#section-3-indicator").addClass('btn-primary');
                $('#section-4').attr('hidden',true);
                $('#section-3').attr('hidden',false);
                initSelect2In('#section-3');
            }, 800);
    }

    function goToSection5() {
        $("#choice1_star").html("");
        if (!$('#fac_id_1').val() || !$('#prg_type_id_1').val() || !$('#dept_choice_1').val() || !$('#level_id').val()) {
            $("#choice1_star").html("*");
            pop_wrong("Veuillez compléter la filière demandée");
            return;
        }

        $('#spinner7').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator7').html("Chargement...");
            setTimeout(function() {
                $('#spinner7').fadeOut('fast');
                $('#indicator7').html("Suivant");
                $("#section-4-indicator").removeClass('btn-primary');
                $("#section-4-indicator").addClass('btn-light');
                $("#section-5-indicator").removeClass('btn-light');
                $("#section-5-indicator").addClass('btn-primary');
                $('#section-4').attr('hidden',true);
                $('#section-5').attr('hidden',false);
                initSelect2In('#section-5');
                }, 800);
    }

    function goBackToSection4() {
        $('#spinner9').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator9').html("Chargement...");
            setTimeout(function() {
                $('#spinner9').fadeOut('fast');
                $('#indicator9').html("Retour");
                $("#section-5-indicator").removeClass('btn-primary');
                $("#section-5-indicator").addClass('btn-light');
                $("#section-4-indicator").removeClass('btn-light');
                $("#section-4-indicator").addClass('btn-primary');
                $('#section-5').attr('hidden',true);
                $('#section-4').attr('hidden',false);
                initSelect2In('#section-4');
            }, 800);
    }
</script>
