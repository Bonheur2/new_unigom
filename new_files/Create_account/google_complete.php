<?php
// Step 2 of Google sign-up: Google gives us a name and an email but never a
// gender or phone number, and both are required on every application form.
// The verified profile is held in the session; only these two fields are asked.

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__, 2));
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");

$pending = $_SESSION['google_pending'] ?? null;

// Expire the half-finished signup after 30 minutes rather than leaving a stale
// profile sitting in the session indefinitely.
if($pending && (time() - ($pending['created_at'] ?? 0)) > 1800){
    unset($_SESSION['google_pending']);
    $pending = null;
}

if(!$pending){
    header('Location: /new_files/Create_account/index.php?google_error='.urlencode('Votre connexion Google a expiré. Veuillez réessayer.'));
    exit;
}
?>
<?php include'../../infrom.php'; ?>
<?php include'../../bar.php'; ?>
<?php include'../../subbar.php'; ?>
<?php require'../../meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-5" style="margin:auto;">
                    <form id="google_complete_form" method="POST">
                        <input type="hidden" name="action" value="google_complete">
                        <div class="card">
                            <div class="card-header row" style="justify-content:center">
                                <h4 class="col-12" style="text-align:center;margin:0;">Presque terminé</h4>
                            </div>
                            <div class="card-body row">

                                <div class="col-12" style="display:flex;align-items:center;gap:12px;background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:12px 16px;margin-bottom:18px;">
                                    <i class="fab fa-google" style="font-size:20px;color:#12294d;"></i>
                                    <div style="min-width:0;">
                                        <div style="font-weight:600;color:#12294d;"><?php echo htmlspecialchars(trim($pending['fname'].' '.$pending['lname'])); ?></div>
                                        <div style="color:#667085;font-size:13px;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($pending['email']); ?></div>
                                    </div>
                                </div>

                                <p class="col-12" style="color:#6c757d;">Il nous manque deux informations pour terminer la création de votre compte.</p>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Sexe <code><b><span id="gender_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-users"></i></div></div>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Numéro de téléphone <code><b><span id="phone_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="ex. +243..." required>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>Nom (nom de famille) <code><b><span id="lname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo htmlspecialchars($pending['lname']); ?>" placeholder="ex. KAMBALE" required>
                                    </div>
                                    <small class="text-muted">Repris de votre compte Google. Corrigez-le si nécessaire.</small>
                                </div>

                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:10px;">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Terminer et créer le compte</span>&nbsp;</button>
                                </div>
                                <div class="col-md-12" style="text-align:center; margin-top:12px; font-size:13px; color:#6c757d;">
                                    <a href="/new_files/Create_account/index.php">Annuler et utiliser le formulaire classique</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include'../../org.php'; ?>
<?php include'../../comb/coda.php'; ?>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    $("#google_complete_form").submit(function(e){
        e.preventDefault();

        $("#gender_star, #phone_star, #lname_star").html("");
        var valid = true;
        if(!$("#gender").val()){ $("#gender_star").html("*"); valid = false; }
        if(!$("#phone").val().trim()){ $("#phone_star").html("*"); valid = false; }
        if(!$("#lname").val().trim()){ $("#lname_star").html("*"); valid = false; }
        if(!valid){
            pop_wrong("Veuillez remplir tous les champs obligatoires.");
            return;
        }

        var formData = new FormData(this);
        $('#spinner').html("<img src='<?php echo $app_base_url; ?>/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Création en cours...");
        $("#sBtn").attr('disabled', true);

        $.ajax({
            url: "<?php echo $app_base_url; ?>/new_files/Create_account/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data){
                $('#spinner').fadeOut('fast');
                if(data.status == 200){
                    $('#indicator').html("Redirection...");
                    pop_up_success(data.message);
                    setTimeout(function(){
                        window.location.href = data.redirect || "/applicant/edu?mis=1";
                    }, 1200);
                } else {
                    $("#sBtn").attr('disabled', false);
                    $('#indicator').html("Terminer et créer le compte");
                    pop_wrong(data.message);
                }
            },
            error: function(){
                $('#spinner').fadeOut('fast');
                $("#sBtn").attr('disabled', false);
                $('#indicator').html("Terminer et créer le compte");
                pop_wrong("Une erreur est survenue. Veuillez réessayer.");
            }
        });
    });
});
</script>
