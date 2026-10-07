<?php
    $applicant_id = (int) ($_GET['app'] ?? 0);

    // project root as a URL path ("" live, "/academic" under XAMPP) so stored file paths resolve in both places
    $tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
    $tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));
    $fileUrl = function($path) use ($tv_base_url){
        return (strpos((string)$path, '/') === 0 ? $tv_base_url : '').$path;
    };
    $fmt = function($d, $withTime = true){ return $d ? date($withTime ? 'd/m/Y H:i' : 'd/m/Y', strtotime($d)) : ''; };

    $stmt = $conn->prepare("SELECT tbl_applicants.*,
                                    tbl_nationality.nationality AS nat_name,
                                    tbl_country.cntr_name AS country_name,
                                    tbl_campus.camp_full_name,
                                    tbl_program_type.prg_type_full_name,
                                    d1.dept_full_name AS dept_choice_1_name,
                                    d2.dept_full_name AS dept_choice_2_name,
                                    f.form_name
                                FROM tbl_applicants
                                LEFT JOIN tbl_nationality ON tbl_applicants.nationality_id = tbl_nationality.nat_id
                                LEFT JOIN tbl_country ON tbl_applicants.country_id = tbl_country.cntr_id
                                LEFT JOIN tbl_campus ON tbl_applicants.camp_id = tbl_campus.camp_id
                                LEFT JOIN tbl_program_type ON tbl_applicants.prg_type_id = tbl_program_type.prg_type_id
                                LEFT JOIN tbl_department d1 ON tbl_applicants.dept_choice_1 = d1.dept_id
                                LEFT JOIN tbl_department d2 ON tbl_applicants.dept_choice_2 = d2.dept_id
                                LEFT JOIN tbl_form_types f ON f.form_id = tbl_applicants.form_id
                                WHERE tbl_applicants.applicant_id = :id");
    $stmt->execute([':id' => $applicant_id]);
    $applicantData = $stmt->fetch(PDO::FETCH_ASSOC);

    $status_meta = [
        'pending'  => ['amber', 'En attente'],
        'verified' => ['blue',  'Vérifiée'],
        'accepted' => ['green', 'Acceptée'],
        'rejected' => ['red',   'Rejetée']
    ];
    $approval_meta = [
        'pending'   => ['amber', 'En cours'],
        'approved'  => ['green', 'Approuvée'],
        'rejected'  => ['red',   'Rejetée'],
        'cancelled' => ['blue',  'Annulée']
    ];

    $docs = [];
    $request = null;
    if($applicantData){
        // document names come from the form-type list, falling back to the old programme list
        $docSql = $conn->prepare("SELECT d.file_path, d.uploaded_at,
                                         COALESCE(ft.document_name, dt.document_name) AS document_name
                                  FROM tbl_applicant_documents d
                                  LEFT JOIN tbl_formtype_document ft ON ft.doc_id = d.doc_id
                                  LEFT JOIN tbl_document_type dt ON dt.doc_id = d.doc_id
                                  WHERE d.applicant_id = :id
                                  ORDER BY d.uploaded_at ASC");
        $docSql->execute([':id' => $applicant_id]);
        $docs = $docSql->fetchAll(PDO::FETCH_ASSOC);

        try {
            $rq = $conn->prepare("SELECT request_id, status, current_step, reg_no, completed_at FROM tbl_approval_requests WHERE applicant_id = :id ORDER BY request_id DESC LIMIT 1");
            $rq->execute([':id' => $applicant_id]);
            $request = $rq->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch(PDOException $e){
            $request = null;
        }
    }

    /** Label / value pairs, skipping empty values. Returns '' when all are empty. */
    $rows = function($pairs){
        $html = '';
        foreach($pairs as $label => $value){
            if($value === null || trim((string) $value) === ''){ continue; }
            $html .= '<dt>'.htmlspecialchars($label).'</dt><dd>'.nl2br(htmlspecialchars((string) $value)).'</dd>';
        }
        return $html === '' ? '' : '<dl class="tv-info rv-info">'.$html.'</dl>';
    };
    $section = function($title, $icon, $body){
        if(trim($body) === ''){ return; }
        echo '<div class="col-12 col-lg-6"><div class="rv-section"><h5><i class="'.$icon.'"></i> '.$title.'</h5>'.$body.'</div></div>';
    };
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3><?php echo $applicantData ? htmlspecialchars($applicantData['application_code']) : 'Candidature introuvable'; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="edu?mis=sbtdap">Candidatures</a></div>
                <div class="breadcrumb-item">Dossier</div>
            </div>
        </div>

    <?php if(!$applicantData): ?>
        <div class="alert alert-danger">Candidature introuvable.</div>
        <a href="edu?mis=sbtdap" class="tv-btn tv-btn-outline"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    <?php else:
        $sm = $status_meta[$applicantData['status']] ?? ['blue', ucfirst($applicantData['status'])];
        $full_name = trim($applicantData['fname'].' '.$applicantData['mname'].' '.$applicantData['lname']);
    ?>
        <!-- applicant header -->
        <div class="tv-card rv-head">
            <a href="edu?mis=sbtdap" class="rv-back" title="Retour à la liste"><i class="fas fa-arrow-left"></i></a>
            <span class="tv-avatar rv-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($applicantData['fname'], 0, 1).mb_substr($applicantData['lname'], 0, 1))); ?></span>
            <div class="rv-who">
                <h2><?php echo htmlspecialchars($full_name); ?></h2>
                <div class="rv-meta">
                    <?php if(!empty($applicantData['form_name'])): ?><span><i class="far fa-file-alt"></i> <?php echo htmlspecialchars($applicantData['form_name']); ?></span><?php endif; ?>
                    <?php if(!empty($applicantData['email'])): ?><span><i class="far fa-envelope"></i> <?php echo htmlspecialchars($applicantData['email']); ?></span><?php endif; ?>
                    <?php if(!empty($applicantData['phone'])): ?><span><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($applicantData['phone']); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- ================= main: tabbed content ================= -->
            <div class="col-12 col-xl-8">
                <div class="rv-tabs" role="tablist">
                    <button type="button" class="rv-tab active" data-tab="profil"><i class="far fa-user"></i> Profil</button>
                    <button type="button" class="rv-tab" data-tab="documents"><i class="fas fa-paperclip"></i> Documents <span class="rv-count"><?php echo count($docs); ?></span></button>
                </div>

                <div class="rv-pane active" id="tab-profil">
                    <div class="tv-card">
                        <div class="row">
                            <?php
                            $section('Informations personnelles', 'far fa-user', $rows([
                                'Prénom'                  => $applicantData['fname'],
                                'Post-nom'                => $applicantData['mname'],
                                'Nom'                     => $applicantData['lname'],
                                'Genre'                   => $applicantData['gender'] === 'M' ? 'Masculin' : ($applicantData['gender'] === 'F' ? 'Féminin' : ''),
                                'Date de naissance'       => $fmt($applicantData['dob'], false),
                                'Lieu de naissance'       => $applicantData['place_of_birth'],
                                "N° de carte d'identité / passeport" => $applicantData['nid'],
                                'Groupe sanguin'          => $applicantData['blood_type'],
                                'État civil'              => $applicantData['marital_status'],
                                'Religion'                => $applicantData['religious_affiliation'],
                                'Nationalité'             => $applicantData['nat_name'] ?? '',
                                'Nom du père'             => $applicantData['father_name'],
                                'Nom de la mère'          => $applicantData['mother_name']
                            ]));

                            $section('Origine et contact', 'fas fa-map-marker-alt', $rows([
                                'Pays de résidence'               => $applicantData['country_name'] ?? '',
                                "Province d'origine des parents"  => $applicantData['parents_province_origin'],
                                "Territoire d'origine"            => $applicantData['territory_of_origin'],
                                'Adresse du candidat'             => $applicantData['candidate_address'],
                                'E-mail'                          => $applicantData['email'],
                                'Téléphone'                       => $applicantData['phone'],
                                'Téléphone du père / parent'      => $applicantData['parent_phone'],
                                'Téléphone de la mère'            => $applicantData['ref_phone']
                            ]));

                            $section('Études secondaires', 'fas fa-school', $rows([
                                'École'                       => $applicantData['secondary_school_name'],
                                "Province de l'école"         => $applicantData['secondary_school_province'],
                                "Territoire de l'école"       => $applicantData['secondary_school_territory'],
                                'Section'                     => $applicantData['humanities_section'],
                                "Statut de l'école"           => $applicantData['secondary_school_status'],
                                'Année du diplôme'            => $applicantData['diploma_year'],
                                'Pourcentage'                 => $applicantData['diploma_percentage'],
                                "N° du diplôme d'État"        => $applicantData['state_diploma_number'],
                                'Activités professionnelles'  => $applicantData['professional_activities']
                            ]));

                            $section('Choix académique', 'fas fa-graduation-cap', $rows([
                                'Campus'                      => $applicantData['camp_full_name'] ?? '',
                                'Type de programme'           => $applicantData['prg_type_full_name'] ?? '',
                                'Département (1er choix)'     => $applicantData['dept_choice_1_name'] ?? '',
                                'Département (2e choix)'      => $applicantData['dept_choice_2_name'] ?? ''
                            ]));
                            ?>
                        </div>
                    </div>
                </div>

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
                </div>
            </div>

            <!-- ================= side: application summary ================= -->
            <div class="col-12 col-xl-4">
                <div class="rv-side">
                    <div class="tv-card rv-status-card">
                        <h4 class="tv-card-title"><i class="far fa-file-alt"></i> Candidature</h4>
                        <div class="rv-status-row"><span class="text-muted">Statut</span><span class="tv-tag <?php echo $sm[0]; ?>"><?php echo $sm[1]; ?></span></div>
                        <div class="rv-status-row"><span class="text-muted">Code</span><b class="sa-mono"><?php echo htmlspecialchars($applicantData['application_code']); ?></b></div>
                        <div class="rv-status-row"><span class="text-muted">Soumise le</span><span><?php echo $fmt($applicantData['created_at']); ?></span></div>
                        <?php if(!empty($applicantData['camp_full_name'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Campus</span><span class="text-right"><?php echo htmlspecialchars($applicantData['camp_full_name']); ?></span></div>
                        <?php endif; ?>
                        <?php if(!empty($applicantData['prg_type_full_name'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Programme</span><span class="text-right"><?php echo htmlspecialchars($applicantData['prg_type_full_name']); ?></span></div>
                        <?php endif; ?>
                        <?php if(!empty($applicantData['dept_choice_1_name'])): ?>
                        <div class="rv-status-row"><span class="text-muted">1er choix</span><span class="text-right"><?php echo htmlspecialchars($applicantData['dept_choice_1_name']); ?></span></div>
                        <?php endif; ?>
                        <?php if(!empty($applicantData['dept_choice_2_name'])): ?>
                        <div class="rv-status-row"><span class="text-muted">2e choix</span><span class="text-right"><?php echo htmlspecialchars($applicantData['dept_choice_2_name']); ?></span></div>
                        <?php endif; ?>
                    </div>

                    <div class="tv-card rv-status-card">
                        <h4 class="tv-card-title"><i class="fas fa-route"></i> Approbation</h4>
                        <?php if($request):
                            $am = $approval_meta[$request['status']] ?? ['blue', ucfirst($request['status'])]; ?>
                        <div class="rv-status-row"><span class="text-muted">Statut</span><span class="tv-tag <?php echo $am[0]; ?>"><?php echo $am[1]; ?></span></div>
                        <?php if($request['status'] === 'pending'): ?>
                        <div class="rv-status-row"><span class="text-muted">Étape actuelle</span><b><?php echo (int) $request['current_step']; ?></b></div>
                        <?php endif; ?>
                        <?php if(!empty($request['reg_no'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Matricule</span><b><?php echo htmlspecialchars($request['reg_no']); ?></b></div>
                        <?php endif; ?>
                        <?php if(!empty($request['completed_at'])): ?>
                        <div class="rv-status-row"><span class="text-muted">Terminée le</span><span><?php echo $fmt($request['completed_at']); ?></span></div>
                        <?php endif; ?>
                        <a href="edu?mis=approval_review&req=<?php echo (int) $request['request_id']; ?>" class="tv-btn tv-btn-accent w-100 justify-content-center mt-3"><i class="fas fa-external-link-alt"></i> Ouvrir dans la revue</a>
                        <?php else: ?>
                        <span class="text-muted">Aucune demande d'approbation pour cette candidature.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </section>
</div>

<style>
.tv-page .rv-head{display:flex;align-items:center;gap:16px;padding:20px 24px}
.tv-page .rv-back{width:36px;height:36px;border:1px solid var(--tv-border);display:flex;align-items:center;justify-content:center;color:var(--tv-accent);flex-shrink:0}
.tv-page .rv-back:hover{background:var(--tv-accent-soft)}
.tv-page .rv-avatar{width:52px;height:52px;font-size:18px}
.tv-page .rv-who{min-width:0}
.tv-page .rv-head h2{font-size:21px;font-weight:700;margin:0 0 4px;color:var(--tv-text)}
.tv-page .rv-meta{display:flex;flex-wrap:wrap;gap:4px 18px;color:var(--tv-muted);font-size:13px}
.tv-page .rv-meta i{margin-right:4px}
.tv-page .rv-tabs{display:flex;flex-wrap:wrap;gap:4px;border-bottom:1px solid var(--tv-border);margin-bottom:20px}
.tv-page .rv-tab{background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-1px;padding:12px 16px;font-size:15px;font-weight:600;color:var(--tv-muted);cursor:pointer;display:inline-flex;align-items:center;gap:8px}
.tv-page .rv-tab:hover{color:var(--tv-accent)}
.tv-page .rv-tab.active{color:var(--tv-accent);border-bottom-color:var(--tv-accent)}
.tv-page .rv-tab:focus{outline:none}
.tv-page .rv-count{font-size:12px;font-weight:700;border-radius:10px;padding:1px 8px;background:var(--tv-bg);color:var(--tv-muted)}
.tv-page .rv-tab.active .rv-count{background:var(--tv-accent-soft);color:var(--tv-accent)}
.tv-page .rv-pane{display:none}
.tv-page .rv-pane.active{display:block}
.tv-page .rv-section{margin-bottom:22px}
.tv-page .rv-section h5{font-size:15px;font-weight:700;color:var(--tv-text);margin:0 0 6px;padding-bottom:8px;border-bottom:2px solid var(--tv-accent-soft)}
.tv-page .rv-section h5 i,.tv-page .tv-card-title > i{color:var(--tv-accent);margin-right:6px}
.tv-page .rv-info{grid-template-columns:minmax(130px,44%) 1fr}
.tv-page .rv-info dt,.tv-page .rv-info dd{padding:8px 0;font-size:14px}
.tv-page .rv-files{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:10px}
.tv-page .rv-file{display:flex;align-items:center;gap:12px;border:1px solid var(--tv-border);padding:12px 14px;color:var(--tv-text);text-decoration:none !important;transition:border-color .15s}
.tv-page .rv-file:hover{border-color:var(--tv-accent)}
.tv-page .rv-file > i:first-child{font-size:22px;color:var(--tv-accent)}
.tv-page .rv-file-name{flex:1;min-width:0;font-size:14px;line-height:1.35}
.tv-page .rv-file-name small{display:block;color:var(--tv-muted);font-size:12px}
.tv-page .rv-file-open{color:var(--tv-muted);font-size:12px}
.tv-page .rv-side{position:sticky;top:90px}
.tv-page .rv-status-card{padding:20px 22px}
.tv-page .rv-status-row{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:8px 0;font-size:14px;border-bottom:1px solid var(--tv-border)}
.tv-page .rv-status-row:last-of-type{border-bottom:none}
.tv-page .sa-mono{font-family:monospace;font-size:13px}
@media (max-width:1199px){ .tv-page .rv-side{position:static} }
@media (max-width:767px){ .tv-page .rv-head{flex-wrap:wrap} }
</style>

<script>
// tabs (plain DOM: this page loads no jQuery of its own)
(function(){
    var key = 'app_tab_<?php echo (int) $applicant_id; ?>';
    function show(name){
        var tab = document.querySelector('.rv-tab[data-tab="' + name + '"]');
        if(!tab) return;
        document.querySelectorAll('.rv-tab').forEach(function(t){ t.classList.remove('active'); });
        document.querySelectorAll('.rv-pane').forEach(function(p){ p.classList.remove('active'); });
        tab.classList.add('active');
        document.getElementById('tab-' + name).classList.add('active');
        try { sessionStorage.setItem(key, name); } catch(e) {}
    }
    document.querySelectorAll('.rv-tab').forEach(function(t){
        t.addEventListener('click', function(){ show(t.getAttribute('data-tab')); });
    });
    try { var saved = sessionStorage.getItem(key); if(saved){ show(saved); } } catch(e) {}
})();
</script>
