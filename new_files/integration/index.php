<?php
// Re-integration form ("Bulletin de reintegration") for an applicant who
// interrupted their studies and wants to resume at UNIGOM. Submits to
// integration/controller.php (action=submit_request), which updates the
// applicant's identity on tbl_applicants and records the re-integration
// details in tbl_integration_requests for staff review.

$Identification = $_SESSION['identification'] ?? '';
$applicant = null;
if($Identification !== ''){
    $stmt = $conn->prepare("SELECT applicant_id, fname, lname, application_code FROM tbl_applicants WHERE application_code = :identification");
    $stmt->execute([':identification' => $Identification]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(!$applicant){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-danger">Nous n&rsquo;avons pas trouv&eacute; votre dossier. Veuillez vous reconnecter ou contacter le service des admissions.</div>';
    echo '</div></section></div>';
    return;
}

$pendingStmt = $conn->prepare("SELECT request_id FROM tbl_integration_requests WHERE application_code = :application_code AND status = 'pending' LIMIT 1");
$pendingStmt->execute([':application_code' => $applicant['application_code']]);
if($pendingStmt->rowCount() > 0){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Vous avez d&eacute;j&agrave; une demande de r&eacute;int&eacute;gration en attente. Veuillez patienter jusqu&rsquo;&agrave; son examen avant d&rsquo;en soumettre une autre.</div>';
    echo '</div></section></div>';
    return;
}

$current_date = date('Y-m-d');
$periodStmt = $conn->prepare("SELECT id FROM tbl_application_periods WHERE status = 'active' AND start_date <= :today AND end_date >= :today ORDER BY created_at DESC LIMIT 1");
$periodStmt->execute([':today' => $current_date]);
if($periodStmt->rowCount() === 0){
    echo '<div class="main-content"><section class="section"><div class="section-body">';
    echo '<div class="alert alert-warning"><i class="fas fa-clock"></i> La p&eacute;riode de candidature est actuellement ferm&eacute;e. Vous ne pouvez plus consulter ni modifier le formulaire.</div>';
    echo '</div></section></div>';
    return;
}

// Academic year shown in the header, as on the printed form.
$acadStmt = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
$acadStmt->execute();
$acadRow = $acadStmt->fetch(PDO::FETCH_ASSOC);
$acad_year = $acadRow ? $acadRow['acad_year'] : '';

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
                        <input type="hidden" name="action" value="submit_request">
                        <div class="card">
                            <div class="card-header row" style="display:flex; flex-direction:column; align-items:center;">
                                <h4 style="text-align:center;margin:0 0 2px;">Bulletin de r&eacute;int&eacute;gration</h4>
                                <?php if($acad_year !== ''): ?>
                                <div style="text-align:center;color:#6c757d;font-size:13px;margin-bottom:6px;">Ann&eacute;e acad&eacute;mique <?php echo htmlspecialchars($acad_year); ?></div>
                                <?php endif; ?>
                                <div style="display:flex; flex-wrap:wrap; justify-content:center; width:100%; gap:6px;">
                                    <button type="button" id="section-1-indicator" class="btn btn-primary pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">1</span> &nbsp;Identit&eacute;</button>
                                    <button type="button" id="section-2-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">2</span> &nbsp;R&eacute;int&eacute;gration</button>
                                    <button type="button" id="section-3-indicator" class="btn btn-light pg-step-btn" style="width:auto; white-space:nowrap; margin-bottom:0;"><span class="badge badge-transparent">3</span> &nbsp;Documents</button>
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
                                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo date('Y-m-d', strtotime('-22 years')); ?>" required>
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
                                        <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +2439...">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone de la m&egrave;re (le cas &eacute;ch&eacute;ant)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +2439...">
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

                            <!-- ============ SECTION 2 : INTEGRATION & CONTINUATION ============ -->
                            <div class="card-body row" id="section-2" hidden>

                                <?php if($choiceStructure && !empty($choiceStructure['file_path'])): ?>
                                <div class="col-12 mb-3">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap" style="margin-bottom:0;">
                                        <span>
                                            <i class="fas fa-file-pdf"></i>&nbsp;
                                            Consultez la structure des choix avant de s&eacute;lectionner votre classe.
                                        </span>
                                        <a href="<?php echo htmlspecialchars($choiceStructure['file_path']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>&nbsp;Voir le document
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <h6 class="mb-3"><i class="fas fa-undo"></i>&nbsp; Int&eacute;gration et continuation des &eacute;tudes <code><b><span id="choice1_star"></span></b></code></h6>
                                </div>

                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Ann&eacute;e d&rsquo;interruption <code><b><span id="interruption_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-times"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="interruption_year" id="interruption_year" placeholder="e.g. 2021-2022" required>
                                    </div>
                                </div>

                                <!-- 2. Classe demandee : Faculte -> Programme -> Departement -> Option -> Classe -->
                                <div class="form-group col-12 col-sm-6 col-lg-3">
                                    <label>Facult&eacute; demand&eacute;e</label>
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
                                    <label>Classe demand&eacute;e &nbsp;<span id="spinner_level_1"></span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-layer-group"></i></div>
                                        </div>
                                        <select name="level_id" id="level_id" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choisir le programme d&rsquo;abord...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 3. Derniere Faculte/Departement/Classe frequentee -->
                                <div class="form-group col-12 col-sm-6">
                                    <label>Derni&egrave;re Facult&eacute; / D&eacute;partement / Classe fr&eacute;quent&eacute;e <code><b><span id="last_attended_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-history"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="last_attended" id="last_attended" placeholder="e.g. Sciences / Informatique / L2" required>
                                    </div>
                                </div>

                                <!-- 4. Dernier resultat obtenu -->
                                <div class="form-group col-12 col-sm-3">
                                    <label>Dernier r&eacute;sultat obtenu (%)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-percent"></i></div>
                                        </div>
                                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="last_result_percentage" placeholder="e.g. 65">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label>Session</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="last_result_session" placeholder="e.g. 1&egrave;re session">
                                    </div>
                                </div>

                                <!-- 5. Raison de la suspension -->
                                <div class="form-group col-12">
                                    <label>Raison de la suspension des &eacute;tudes <code><b><span id="suspension_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-pause-circle"></i></div>
                                        </div>
                                        <textarea class="form-control" name="suspension_reason" id="suspension_reason" rows="2" placeholder="Expliquez bri&egrave;vement" required></textarea>
                                    </div>
                                </div>

                                <!-- 6. Documents laisses au secretariat general academique -->
                                <div class="form-group col-12">
                                    <label>Documents laiss&eacute;s au Secr&eacute;tariat G&eacute;n&eacute;ral Acad&eacute;mique</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                                        </div>
                                        <textarea class="form-control" name="documents_left" rows="2" placeholder="e.g. Dipl&ocirc;me d&rsquo;&Eacute;tat, relev&eacute; de notes..."></textarea>
                                    </div>
                                </div>

                                <!-- 7. Preuve de reussite -->
                                <div class="form-group col-12 col-sm-6">
                                    <label>Preuve de r&eacute;ussite des &eacute;tudes ant&eacute;rieures</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-file-alt"></i></div>
                                        </div>
                                        <select name="transcript_attached" id="transcript_attached" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="1">Relev&eacute; de notes joint</option>
                                            <option value="0">Non joint</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 8. Obligations envers UNIGOM -->
                                <div class="col-12 mt-2">
                                    <h6 class="mb-2"><i class="fas fa-balance-scale"></i>&nbsp; Obligations envers l&rsquo;UNIGOM (mat&eacute;riel d&eacute;tenu, dettes)</h6>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Biblioth&egrave;que</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="obligations_library" placeholder="e.g. Aucune / 2 ouvrages">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Finances</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-money-bill-wave"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="obligations_finance" placeholder="e.g. Aucune dette">
                                    </div>
                                </div>

                                <!-- 9. Activites durant la suspension -->
                                <div class="form-group col-12">
                                    <label>Activit&eacute;s exerc&eacute;es durant la suspension</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-briefcase"></i></div>
                                        </div>
                                        <textarea class="form-control" name="activities_during_suspension" rows="2" placeholder="e.g. Emploi, formation, autre"></textarea>
                                    </div>
                                </div>

                                <!-- 10. Raison de la reprise -->
                                <div class="form-group col-12">
                                    <label>Raison de la reprise des &eacute;tudes &agrave; l&rsquo;UNIGOM <code><b><span id="resume_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-redo"></i></div>
                                        </div>
                                        <textarea class="form-control" name="resume_reason" id="resume_reason" rows="2" placeholder="Expliquez bri&egrave;vement" required></textarea>
                                    </div>
                                </div>

                                <!-- 11. Etat de sante  12. Capacite de payer -->
                                <div class="form-group col-12 col-sm-6">
                                    <label>&Eacute;tat de sant&eacute;</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-heartbeat"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="health_status" placeholder="e.g. Bon">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Capacit&eacute; de payer les frais acad&eacute;miques</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-wallet"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="tuition_ability" placeholder="e.g. Parents / Tuteur / Emploi">
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection3()"><span id="spinner2"></span>&nbsp; <span id="indicator2">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner2b"></span>&nbsp; <span id="indicator2b">Retour</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 3 : DOCUMENTS ============ -->
                            <div class="card-body row" id="section-3" hidden>
                                <div class="col-12">
                                    <p class="text-muted" id="documents_hint">S&eacute;lectionnez d&rsquo;abord un type de programme pour voir les documents requis.</p>
                                </div>
                                <div class="col-12" id="documents_wrapper"></div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><i class="fas fa-check"></i>&nbsp;
                                        <span id="spinner"></span>&nbsp;<span id="indicator">Soumettre</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3b"></span>&nbsp; <span id="indicator3b">Retour</span></button>
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

        // Single chain: Faculty -> Programme -> Department -> Option, plus the
        // class being resumed (all levels of the chosen programme).
        [1].forEach(function(n){
            $('#fac_id_' + n).on('change', function(){
                loadProgramTypesByFaculty(n, $(this).val());
            });

            $('#prg_type_id_' + n).on('change', function(){
                var prgTypeId = $(this).val();
                loadDepartments(n, prgTypeId);
                loadLevelsByProgramType(n, prgTypeId);
                loadDocuments(prgTypeId);
            });

            $('#dept_choice_' + n).on('change', function(){
                loadOptions(n, $(this).val());
            });
        });

        $("#apply").submit(function(e){
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Traitement...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "../new_files/integration/controller.php",
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
                            title: "Demande soumise !",
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

    function loadFaculties(n) {
        var $faculty = $('#fac_id_' + n);

        $faculty.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Chargement...</option>').trigger('change');

        $.ajax({
            url: '../new_files/integration/controller.php',
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
            url: '../new_files/integration/controller.php',
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
            url: '../new_files/integration/controller.php',
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
            url: '../new_files/integration/controller.php',
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
            url: '../new_files/integration/controller.php',
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
                    $level.html('<option value="" disabled selected hidden>Aucune classe disponible</option>').trigger('change');
                }
                initSelect2In('#level_id');
            },
            error: function() {
                $spin.fadeOut('fast');
                $level.html('<option value="" disabled selected hidden>Impossible de charger les classes</option>').trigger('change');
            }
        });
    }

    function loadDocuments(prgTypeId) {
        var $wrap = $('#documents_wrapper');

        if (!prgTypeId) {
            $wrap.empty();
            $('#documents_hint').html('S&eacute;lectionnez d&rsquo;abord un type de programme pour voir les documents requis.');
            return;
        }

        $.ajax({
            url: '../new_files/integration/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_documents', prg_type_id: prgTypeId },
            success: function(data) {
                $wrap.empty();

                if (data && data.length) {
                    $('#documents_hint').html('Joignez les documents demand&eacute;s ci-dessous.');
                    var html = '<div class="row">';
                    $.each(data, function(_, doc) {
                        html += '<div class="form-group col-12 col-sm-6">'
                              + '<label>' + doc.document_name
                              + (doc.international_required == 1 ? ' <span class="badge badge-info">International</span>' : '')
                              + '</label>'
                              + '<div class="input-group">'
                              + '<div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-paperclip"></i></div></div>'
                              + '<input type="file" class="form-control" name="documents[' + doc.doc_id + ']">'
                              + '</div>'
                              + (doc.file_name ? '<small><a href="' + doc.file_name + '" target="_blank">Voir le mod&egrave;le</a></small>' : '')
                              + '</div>';
                    });
                    html += '</div>';
                    $wrap.html(html);
                } else {
                    $('#documents_hint').html('Aucun document requis pour ce programme.');
                }
            },
            error: function() {
                $wrap.empty();
                $('#documents_hint').html('Impossible de charger la liste des documents.');
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
        $("#choice1_star, #interruption_star, #last_attended_star, #suspension_star, #resume_star").html("");

        var valid = true;
        if (!$('#interruption_year').val().trim()) { $("#interruption_star").html("*"); valid = false; }
        if (!$('#last_attended').val().trim()) { $("#last_attended_star").html("*"); valid = false; }
        if (!$('#suspension_reason').val().trim()) { $("#suspension_star").html("*"); valid = false; }
        if (!$('#resume_reason').val().trim()) { $("#resume_star").html("*"); valid = false; }
        if (!$('#fac_id_1').val() || !$('#prg_type_id_1').val() || !$('#dept_choice_1').val() || !$('#level_id').val()) {
            $("#choice1_star").html("*");
            valid = false;
        }

        if (!valid) {
            pop_wrong("Veuillez remplir tous les champs obligatoires");
            return;
        }

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
</script>
