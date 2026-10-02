<?php include'../../infrom.php'; ?>
<?php include'../../bar.php'; ?>
<?php  include'../../subbar.php'; ?>
<?php require'../../meet/bind.php'; ?>
<?php
    // Only offer the Google button once credentials are actually configured,
    // otherwise it would send people to a broken consent screen.
    $google_ready = false;
    $google_cfg = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'google_config.php';
    if(file_exists($google_cfg)){
        require_once($google_cfg);
        $google_ready = function_exists('google_oauth_configured') && google_oauth_configured();
    }
    $google_error = $_GET['google_error'] ?? '';
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6" style="margin:auto;">
                    <form id="create_account_form" action="/new_files/Create_account/controller.php" method="POST">
                        <input type="hidden" name="action" value="create_account">
                        <div class="card">
                            <div class="card-header row" style="justify-content:center">
                                <h4 class="col-12" style="text-align:center;margin:0;">Create Your Account</h4>
                            </div>
                            <div class="card-body row">

                                <?php if($google_error !== ''): ?>
                                <div class="col-12">
                                    <div class="alert alert-warning" style="font-size:13px;">
                                        <i class="fas fa-exclamation-triangle"></i>&nbsp;<?php echo htmlspecialchars($google_error); ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if($google_ready): ?>
                                <div class="col-12" style="margin-bottom:6px;">
                                    <a href="/new_files/Create_account/google_start.php" class="btn btn-light btn-block" style="display:flex;align-items:center;justify-content:center;gap:10px;border:1px solid #dadce0;padding:10px;font-weight:500;color:#3c4043;">
                                        <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true"><path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 0 1-1.8 2.72v2.26h2.92c1.7-1.57 2.68-3.88 2.68-6.62z"/><path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.92-2.26c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.7H.96v2.33A9 9 0 0 0 9 18z"/><path fill="#FBBC05" d="M3.97 10.72a5.4 5.4 0 0 1 0-3.44V4.95H.96a9 9 0 0 0 0 8.1l3.01-2.33z"/><path fill="#EA4335" d="M9 3.58c1.32 0 2.5.45 3.44 1.35l2.58-2.58C13.46.9 11.43 0 9 0A9 9 0 0 0 .96 4.95l3.01 2.33C4.68 5.16 6.66 3.58 9 3.58z"/></svg>
                                        Continue with Google
                                    </a>
                                </div>
                                <div class="col-12" style="display:flex;align-items:center;gap:12px;margin:10px 0 16px;">
                                    <div style="flex:1;height:1px;background:#e4e6ef;"></div>
                                    <span style="color:#98a2b3;font-size:12px;text-transform:uppercase;letter-spacing:.04em;">or</span>
                                    <div style="flex:1;height:1px;background:#e4e6ef;"></div>
                                </div>
                                <?php endif; ?>

                                <p class="col-12" style="color:#6c757d;">Enter your basic details to get started. We'll email you a verification code so you can set a password and log in.</p>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Surname (Family name) <code><b><span id="lname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                                        <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. KAMARA" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Middle name <code>(optional)</code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                                        <input type="text" class="form-control" name="mname" id="mname" placeholder="e.g. John">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>First name <code><b><span id="fname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                                        <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Peter" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Gender <code><b><span id="gender_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-users"></i></div></div>
                                        <select name="gender" id="gender" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Choose One...</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Phone number <code><b><span id="phone_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                                        <input type="text" class="form-control phone-number" name="phone" id="phone" placeholder="e.g. +243..." required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>E-mail <code><b><span id="email_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-envelope"></i></div></div>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="e.g. annet@example.com" required>
                                    </div>
                                </div>

                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Create Account</span>&nbsp;</button>
                                </div>
                                <div class="col-md-12" style="text-align:center; margin-top:12px; font-size:13px; color:#6c757d;">
                                    Already have an account? <a href="/auth">Login</a>
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

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/form-autosave.js"></script>

<script>
var caAutosave = null;

$(document).ready(function(){
    caAutosave = enableFormAutosave('#create_account_form', 'unigom_create_account_draft_v1', {});

    $("#create_account_form").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Processing...");
        $("#sBtn").attr('disabled', true);

        $.ajax({
            url: "/new_files/Create_account/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data){
                $('#spinner').fadeOut('fast');
                $("#sBtn").attr('disabled', false);
                if(data.status == 200){
                    $('#indicator').html("Redirecting...");
                    pop_up_success(data.message);
                    if (caAutosave) { caAutosave.clear(); }
                    setTimeout(function(){
                        window.location.href = "/verify_email?em=" + encodeURIComponent(data.email);
                    }, 1500);
                } else {
                    $('#indicator').html("Create Account");
                    pop_wrong(data.message);
                }
            },
            error: function(){
                $('#spinner').fadeOut('fast');
                $("#sBtn").attr('disabled', false);
                $('#indicator').html("Create Account");
                pop_wrong("Something went wrong!");
            }
        });
    });
});
</script>
