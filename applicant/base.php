<?php
// Applicant welcome / form-picker page.
// Shown as the Home view for a logged-in applicant (tbl_student_login, role_id 4).
// Lets the applicant choose which registered application form (tbl_form_types)
// they want to continue with.

$identification = $_SESSION['identification'] ?? '';
$acc_email = $_SESSION['email'] ?? '';

$applicant = null;
if($acc_email !== ''){
    $stmt = $conn->prepare("SELECT fname, mname, lname, application_code, status FROM tbl_applicants WHERE application_code = :identification");
    $stmt->execute([':identification' => $identification]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);
}

$univStmt = $conn->prepare("SELECT full_name, short_name, logo FROM tbl_university ORDER BY id ASC LIMIT 1");
$univStmt->execute();
$univData = $univStmt->fetch(PDO::FETCH_ASSOC);
// plain UTF-8, not entities: this value is printed through htmlspecialchars()
$univ_short_name = $univData['short_name'] ?? ($univData['full_name'] ?? 'l’université');

$formTypesStmt = $conn->prepare("SELECT form_id, form_name, description, rank, form_path FROM tbl_form_types WHERE status = 1 ORDER BY rank ASC");
$formTypesStmt->execute();
$formTypes = $formTypesStmt->fetchAll(PDO::FETCH_ASSOC);

$displayName = $applicant ? trim($applicant['fname'].' '.$applicant['lname']) : $identification;
?>
<style>
.welcome-hero{
    background:#12294d;
    border-radius:12px;
    padding:32px 28px;
    color:#ffffff;
    margin-bottom:24px;
}
.welcome-hero h2{
    color:#ffffff;
    margin:0 0 6px;
    font-size:22px;
}
.welcome-hero p{
    color:#c7d3e8;
    margin:0;
    font-size:14px;
}
.welcome-hero .code-pill{
    display:inline-block;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.25);
    border-radius:20px;
    padding:4px 14px;
    font-size:13px;
    margin-top:14px;
}
.form-pick-card{
    border:1px solid #e3e7ed;
    border-radius:10px;
    padding:22px 20px;
    height:100%;
    display:flex;
    flex-direction:column;
    transition:box-shadow .15s ease, transform .15s ease;
    text-decoration:none;
    color:inherit;
}
.form-pick-card:hover{
    box-shadow:0 4px 14px rgba(16,24,40,.10);
    transform:translateY(-2px);
    text-decoration:none;
    color:inherit;
}
.form-pick-card .rank-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:32px;
    height:32px;
    border-radius:8px;
    background:#f2f6ff;
    color:#12294d;
    font-weight:700;
    font-size:13px;
    margin-bottom:14px;
}
.form-pick-card h4{
    margin:0 0 8px;
    font-size:16px;
    color:#1d2939;
}
.form-pick-card p{
    margin:0;
    color:#667085;
    font-size:13px;
    flex-grow:1;
}
.form-pick-card .go{
    margin-top:14px;
    font-size:13px;
    font-weight:600;
    color:#2f6fed;
}
</style>

<div class="main-content">
    <section class="section">
        <div class="section-body">

            <div class="welcome-hero">
                <h2>Bienvenue<?php echo $displayName ? ', '.htmlspecialchars($displayName) : ''; ?>&nbsp;!</h2>
                <p>Vous &ecirc;tes connect&eacute;(e) au portail des candidats de <?php echo htmlspecialchars($univ_short_name); ?>. Choisissez un formulaire ci-dessous pour poursuivre votre candidature.</p>
                <?php if($applicant && !empty($applicant['application_code'])): ?>
                <span class="code-pill"><i class="fas fa-hashtag"></i>&nbsp;<?php echo htmlspecialchars($applicant['application_code']); ?></span>
                <?php endif; ?>
            </div>

            <?php if(empty($formTypes)): ?>
            <div class="card">
                <div class="card-body text-center">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Aucun formulaire de candidature n&rsquo;est disponible pour le moment. Veuillez r&eacute;essayer plus tard ou contacter le service des admissions.
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3">Quel formulaire souhaitez-vous remplir&nbsp;?</h5>
                </div>
                <?php foreach($formTypes as $form): ?>
                <?php $formHref = !empty($form['form_path']) ? '../'.ltrim($form['form_path'], '/') : '../Application_form/apply.php'; ?>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <a href="<?php echo htmlspecialchars($formHref); ?>" class="form-pick-card">
                        <span class="rank-badge"><?php echo str_pad($form['rank'], 2, '0', STR_PAD_LEFT); ?></span>
                        <h4><?php echo htmlspecialchars($form['form_name']); ?></h4>
                        <?php if(!empty($form['description'])): ?>
                        <p><?php echo htmlspecialchars($form['description']); ?></p>
                        <?php endif; ?>
                        <span class="go">Continuer avec ce formulaire <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </section>
</div>
