<?php
// Continue-application form for an already logged-in applicant (created via Create_account).
// Name / gender / phone / email were already collected at signup, so this form only
// covers what's still missing: origin & contact details, secondary education, the
// academic choice (campus / programme / department), and required documents.

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

// Structure des choix: shown as a "View PDF" button on the academic choice step.
// Wrapped so a missing table (before the migration runs) cannot break the form.
$choiceStructure = null;
try {
    $csStmt = $conn->prepare("SELECT title, file_path FROM tbl_choice_structure WHERE status = 1 ORDER BY structure_id DESC LIMIT 1");
    $csStmt->execute();
    $choiceStructure = $csStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $choiceStructure = null;
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
?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <form id="apply" action="apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="complete_application">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-3 col-lg-3" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Origine &amp; Contact</button>
                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-3 col-lg-3"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;&Eacute;tudes secondaires</button>
                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-3 col-lg-3"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Choix acad&eacute;mique</button>
                                    <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-3 col-lg-3"  style="margin-bottom:10px;"><span class="badge badge-transparent">4</span> &nbsp;Documents</button>
                            </div>

                            <!--origin & contact start-->
                            <div class="card-body row" id="section-1">
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Date de naissance <code><b><span id="dob_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo date('Y-m-d', strtotime('-25 years')); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Lieu de naissance</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder="e.g. Goma">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pays de naissance <code><b><span id="country_of_birth"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-flag"></i>
                                        </div>
                                    </div>
                                    <select name="country_of_birth" id="country_of_birth" class="form-control select2" required>
                                        <?php
                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                            $sql_cntr->execute();
                                            while($cntr=$sql_cntr->fetch()){
                                        ?>
                                            <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']; ?> </option>
                                            <?php } ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nationalit&eacute;</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-globe-americas"></i>
                                        </div>
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
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="father_name" id="father_name" class="form-control" placeholder="noms complets du p&egrave;re" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nom de la m&egrave;re <code><b><span id="mother_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="noms complets de la m&egrave;re" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone du p&egrave;re (le cas &eacute;ch&eacute;ant)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +2327245....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone de la m&egrave;re (le cas &eacute;ch&eacute;ant)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +232724....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Groupe sanguin</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-tint"></i>
                                        </div>
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
                                        <div class="input-group-text">
                                        <i class="fas fa-ring"></i>
                                        </div>
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
                                        <div class="input-group-text">
                                        <i class="fas fa-praying-hands"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="religious_affiliation" class="form-control" placeholder="e.g. Catholique">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Province d&rsquo;origine des parents</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="parents_province_origin" class="form-control" placeholder="e.g. Nord-Kivu">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Territoire d&rsquo;origine</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="territory_of_origin" class="form-control" placeholder="e.g. Nyiragongo">
                                    </div>
                                </div>
                                
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Adresse du candidat (Quartier &amp; Avenue, Num&eacute;ro)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-home"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="candidate_address" class="form-control" placeholder="e.g. Himbi, Avenue Bujavu No. 12">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pays de r&eacute;sidence <code><b><span id="country_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-flag"></i>
                                        </div>
                                    </div>
                                    <select name="country" id="country" class="form-control select2" required>
                                        <?php
                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                            $sql_cntr->execute();
                                            while($cntr=$sql_cntr->fetch()){
                                        ?>
                                            <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']; ?> </option>
                                            <?php } ?>
                                    </select>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection2()"><span id="spinner2"></span>&nbsp; <span id="indicator2">Suivant</span></button>
                                </div>
                            </div>
                            <!--origin & contact end-->

                            <!--secondary education start-->
                            <div class="card-body row" id="section-2" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nom de l&rsquo;&eacute;cole secondaire fr&eacute;quent&eacute;e</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-school"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_name" class="form-control" placeholder="e.g. Institut Mont Carmel">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Adresse de l&rsquo;&eacute;cole (Province)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_province" class="form-control" placeholder="e.g. Nord-Kivu">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Territoire</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_territory" class="form-control" placeholder="e.g. Nyiragongo">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pays de l&rsquo;&eacute;cole</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-flag"></i>
                                        </div>
                                    </div>
                                    <select name="secondary_school_territory_country" id="secondary_school_territory_country" class="form-control select2">
                                        <option value="" disabled selected hidden>Choisir...</option>
                                        <?php
                                            $sql_sch_cntr=$conn->prepare("SELECT cntr_id, cntr_name FROM tbl_country ORDER BY cntr_name ASC");
                                            $sql_sch_cntr->execute();
                                            while($sch_cntr=$sql_sch_cntr->fetch(PDO::FETCH_ASSOC)){
                                        ?>
                                        <option value="<?php echo $sch_cntr['cntr_id']; ?>"><?php echo htmlspecialchars($sch_cntr['cntr_name']); ?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Section suivie aux Humanit&eacute;s</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="humanities_section" class="form-control" placeholder="e.g. Scientifique">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Statut de l&rsquo;&eacute;cole secondaire</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-landmark"></i>
                                        </div>
                                    </div>
                                    <select name="secondary_school_status" id="secondary_school_status" class="form-control select2">
                                        <option value="" disabled selected hidden>Choisir...</option>
                                        <option value="Public">Public</option>
                                        <option value="Private">Priv&eacute;</option>
                                        <option value="Protestant">Protestant</option>
                                        <option value="Catholic">Catholique</option>
                                        <option value="Muslim">Musulman</option>
                                        <option value="Other">Autre</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Ann&eacute;e d&rsquo;obtention du Dipl&ocirc;me d&rsquo;&Eacute;tat</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="diploma_year" class="form-control" min="1970" max="<?php echo date('Y'); ?>" placeholder="e.g. <?php echo date('Y'); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Pourcentage du Dipl&ocirc;me</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-percentage"></i>
                                        </div>
                                    </div>
                                    <input type="number" step="0.01" min="0" max="100" name="diploma_percentage" class="form-control" placeholder="e.g. 68.5">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Num&eacute;ro du Dipl&ocirc;me d&rsquo;&Eacute;tat</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="state_diploma_number" class="form-control" placeholder="e.g. 123456789">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-lg-12">
                                    <label>Activit&eacute;s professionnelles <code>(le cas &eacute;ch&eacute;ant, apr&egrave;s les &eacute;tudes secondaires)</code></label>
                                    <textarea name="professional_activities" class="form-control" rows="3" placeholder="D&eacute;crivez vos activit&eacute;s professionnelles apr&egrave;s les &eacute;tudes secondaires"></textarea>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection3()"><span id="spinner6"></span>&nbsp; <span id="indicator6">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner5"></span>&nbsp; <span id="indicator5">Retour</span></button>
                                </div>
                            </div>
                            <!--secondary education end-->

                            <!--academic choice start-->
                            <div class="card-body row" id="section-3" hidden>

                                <?php if($choiceStructure && !empty($choiceStructure['file_path'])): ?>
                                <div class="col-12 mb-3">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap" style="margin-bottom:0;">
                                        <span>
                                            <i class="fas fa-file-pdf"></i>&nbsp;
                                            Consultez la structure des choix avant de s&eacute;lectionner vos fili&egrave;res.
                                        </span>
                                        <a href="<?php echo htmlspecialchars($choiceStructure['file_path']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>&nbsp;Voir le document
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- 1er choix -->
                                <div class="col-12">
                                    <h6 class="mb-3"><span class="badge badge-primary">1</span>&nbsp; Premier choix <code><b><span id="choice1_star"></span></b></code></h6>
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
                                    <label>Type de programme <small class="text-muted">(Licence)</small></label>
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

                                <!-- 2eme choix -->
                                <div class="col-12 mt-3">
                                    <h6 class="mb-3"><span class="badge badge-secondary">2</span>&nbsp; Deuxi&egrave;me choix <code><b><span id="choice2_star"></span></b></code></h6>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Facult&eacute;</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-university"></i></div>
                                        </div>
                                        <select name="fac_id_2" id="fac_id_2" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Chargement...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Type de programme <small class="text-muted">(Licence)</small></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-graduation-cap"></i></div>
                                        </div>
                                        <select name="prg_type_id_2" id="prg_type_id_2" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir la facult&eacute; d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>D&eacute;partement &nbsp;<span id="spinner_dept_2"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sitemap"></i></div>
                                        </div>
                                        <select name="dept_choice_2" id="dept_choice_2" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Option &nbsp;<span id="spinner_opt_2"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-stream"></i></div>
                                        </div>
                                        <select name="opt_choice_2" id="opt_choice_2" class="form-control select2" disabled>
                                            <option value="" disabled selected hidden>Choisir le d&eacute;partement d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection4()"><span id="spinner7"></span>&nbsp; <span id="indicator7">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner8"></span>&nbsp; <span id="indicator8">Retour</span></button>
                                </div>
                            </div>
                            <!--academic choice end-->

                            <!--documents start-->
                            <div class="card-body row" id="section-4" hidden>
                                <div class="col-12" id="documents_list">
                                    <div class="alert alert-light text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Chargement des documents requis...
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Soumettre</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner9"></span>&nbsp; <span id="indicator9">Retour</span></button>
                                </div>
                            </div>
                            <!--documents end-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
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
        loadFaculties(2);
        // documents are attached to the form type, not the programme, so they can
        // be fetched straight away
        loadDocuments();

        // Each choice is an independent Faculty -> Programme -> Department -> Option
        // chain, so the 2nd choice may sit in a different faculty entirely.
        [1, 2].forEach(function(n){
            $('#fac_id_' + n).on('change', function(){
                loadProgramTypesByFaculty(n, $(this).val());
            });

            $('#prg_type_id_' + n).on('change', function(){
                loadDepartments(n, $(this).val());
            });

            $('#dept_choice_' + n).on('change', function(){
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
                url: "../new_files/Application_form/controller.php",
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

    function pop_wrong_verify(feedback) {
        iziToast.warning({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }

    function loadFaculties(n) {
        var $faculty = $('#fac_id_' + n);

        $faculty.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');

        $.ajax({
            url: '../new_files/Application_form/controller.php',
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
            url: '../new_files/Application_form/controller.php',
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
            url: '../new_files/Application_form/controller.php',
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
            url: '../new_files/Application_form/controller.php',
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

    function loadDocuments() {
        var $container = $('#documents_list');
        $container.html('<div class="alert alert-light text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des documents requis...</div>');

        $.ajax({
            url: '../new_files/Application_form/controller.php',
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
        var country = document.getElementById('country').value.trim();
        var father = document.getElementById('father_name').value.trim();
        var mother = document.getElementById('mother_name').value.trim();

        $("#dob_star, #country_star, #father_star, #mother_star").html("");

        var valid = true;
        if (!dob) { $("#dob_star").html("*"); valid = false; }
        if (!country) { $("#country_star").html("*"); valid = false; }
        if (!father) { $("#father_star").html("*"); valid = false; }
        if (!mother) { $("#mother_star").html("*"); valid = false; }

        if (!valid) {
            pop_wrong_verify("Veuillez remplir tous les champs obligatoires");
            return;
        }

        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Chargement...");
            setTimeout(function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Suivant");
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
        $('#spinner5').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator5').html("Chargement...");
            setTimeout(function() {
                $('#spinner5').fadeOut('fast');
                $('#indicator5').html("Retour");
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
        $('#spinner6').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator6').html("Chargement...");
            setTimeout(function() {
                $('#spinner6').fadeOut('fast');
                $('#indicator6').html("Suivant");
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
        $('#spinner8').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator8').html("Chargement...");
            setTimeout(function() {
                $('#spinner8').fadeOut('fast');
                $('#indicator8').html("Retour");
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
        $("#choice1_star, #choice2_star").html("");

        var valid = true;
        if (!$('#fac_id_1').val() || !$('#prg_type_id_1').val() || !$('#dept_choice_1').val()) {
            $("#choice1_star").html("*");
            valid = false;
        }
        if (!$('#fac_id_2').val() || !$('#prg_type_id_2').val() || !$('#dept_choice_2').val()) {
            $("#choice2_star").html("*");
            valid = false;
        }

        if (!valid) {
            pop_wrong_verify("Veuillez compléter vos deux choix académiques");
            return;
        }

        if ($('#dept_choice_1').val() === $('#dept_choice_2').val()) {
            pop_wrong_verify("Le 1er et le 2ème choix doivent être différents");
            return;
        }

        $('#spinner7').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator7').html("Chargement...");
            setTimeout(function() {
                $('#spinner7').fadeOut('fast');
                $('#indicator7').html("Suivant");
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
        $('#spinner9').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator9').html("Chargement...");
            setTimeout(function() {
                $('#spinner9').fadeOut('fast');
                $('#indicator9').html("Retour");
                $("#section-4-indicator").removeClass('btn-primary');
                $("#section-4-indicator").addClass('btn-light');
                $("#section-3-indicator").removeClass('btn-light');
                $("#section-3-indicator").addClass('btn-primary');
                $('#section-4').attr('hidden',true);
                $('#section-3').attr('hidden',false);
                initSelect2In('#section-3');
            }, 800);
    }
</script>
