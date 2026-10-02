<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php require'meet/bind.php'; ?>
                <div class="main-content">
                    <section class="section">
                       <div class="section-body">
                           <div class="row">
                            <?php
                            $autoVerifyToken = $_GET['token'] ?? '';
                            $autoVerifyEmail = $_GET['em'] ?? '';
                            if(!isset($_GET['act'])){
                               $sth = $conn->prepare("SELECT * FROM tbl_student_login WHERE email='".$_GET['em']."'");
                               $sth->execute();
                               if ($sth->rowCount() == 0){ ?>
                                <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                                    <form id="verify_email" action="verify_email" method="POST">
                                        <input type="hidden" name="action" value="verify_email">
                                        <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($autoVerifyToken); ?>">
                                        <div class="card">
                                            <div class="card-header row">
                                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title">Verify Your Email</h4><br>
                                                <p class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title2">A verification code was sent to your email. If not found, check your Spam folder.</p>
                                            </div>
                                            <div class="card-body" id="pre_verify">
                                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;">
                                                    <label>Email</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-envelope"></i>
                                                        </div>
                                                    </div>
                                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $_GET['em']; ?>" readonly>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;">
                                                    <label>Verification Code</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="token" id="token_input" class="form-control" placeholder="_ _ _ _ _ _ _ _ _" minlength="9" minlength="9" value="<?php echo htmlspecialchars($autoVerifyToken); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                                    <button type="submit" class="btn btn-primary btn-sm" style="margin:auto;"><span id="spinner"></span>&nbsp; <span id="indicator">Verify</span>&nbsp;</button>
                                                </div>
                                            </div>
                                            <div class="card-body" id="verified" hidden>
                                                <div class="form-group col-12 col-sm-12 col-lg-12" style="margin:auto;">
                                                    <label style="font-size:16px;">Once your email is confirmed, you can use the verification code as your password. If you prefer to create your own password, choose an option below.</label>
                                                    <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:16px;">
                                                        <a href="/verify_email?em=<?php echo urlencode($_GET['em'] ?? '').'&act=reset'; ?>" class="btn btn-danger">
                                                            <i class="fas fa-key"></i>&nbsp; Create password
                                                        </a>
                                                        <a href="/auth" class="btn btn-danger">
                                                            <i class="fas fa-sign-in-alt"></i>&nbsp; Login
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-whitesmoke" id="card-footer">
                                                <p>Didn't receive code? <button type="button" class="btn btn-outline-info btn-sm" id="request_code"><span id="spinner2"></span>&nbsp; <span id="indicator2">resend code</span>&nbsp;</button></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php } else{ ?>
                                    <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title">Email Verified</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group col-12 col-sm-12 col-lg-12" style="margin:auto;">
                                                    <label style="font-size:16px;">Once your email is confirmed, you can use the verification code as your password. If you prefer to create your own password, choose an option below.</label>
                                                    <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:16px;">
                                                        <a href="/verify_email?em=<?php echo urlencode($_GET['em'] ?? '').'&act=reset'; ?>" class="btn btn-danger">
                                                            <i class="fas fa-key"></i>&nbsp; Create password
                                                        </a>
                                                        <a href="/auth" class="btn btn-danger">
                                                            <i class="fas fa-sign-in-alt"></i>&nbsp; Login
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                }
                                else{
                                ?>
                                <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                                    <form id="create_password" action="create_password" method="POST">
                                        <input type="hidden" name="action" value="create_password">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title3">Set your own password</h4>
                                            </div>
                                            <input type="hidden" name="email" value="<?php echo $_GET['em']; ?>">
                                            <input type="hidden" name="identification" value="<?php echo $_GET['token']; ?>">
                                            <div class="card-body">
                                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;">
                                                    <label>Password</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" name="password" id="password" minlength="5" maxlength="20" class="form-control" required>
                                                    </div>
                                                </div><br>
                                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;">
                                                    <label>Confirm Password</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" id="cpassword" class="form-control"  minlength="5" maxlength="20" required>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                                    <button type="submit" class="btn btn-primary btn-sm" style="margin:auto;" id="cbtn"><span id="spinner3"></span>&nbsp; <span id="indicator3">Create password</span>&nbsp;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                </div>
                

<?php  include'comb/orgin.php'; ?>       
<?php  include'comb/coda.php'; ?>  
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    const autoVerifyToken = <?php echo json_encode($autoVerifyToken); ?>;
    const autoVerifyEmail = <?php echo json_encode($autoVerifyEmail); ?>;

    $("#verify_email").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Processing...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    if(data.status==200){
                        $("#pre_verify").attr('hidden',true);
                        $("#title").html('Email verified');
                        $("#card-footer").attr('hidden',true);
                        $("#title2").attr('hidden',true);
                        $("#verified").attr('hidden',false);
                    }
                    if(data.status==401){
                        pop_wrong("Invalid code");
                    }
                    if(data.status==500){
                        pop_wrong("Failed to verify email");
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    pop_wrong("Something went wrong!");
                }
             });
          });

    if (autoVerifyToken && autoVerifyEmail && new URLSearchParams(window.location.search).get('act') === 'auto') {
        if ($('#token').length) {
            $('#token').val(autoVerifyToken);
        }
        if ($('#token_input').length) {
            $('#token_input').val(autoVerifyToken);
        }
        setTimeout(function() {
            $('#verify_email').trigger('submit');
        }, 300);
    }

    $("#request_code").click(function(){
        var formData = {
            email: $("#email").val(),
            action:'send_code'
        }
            $("#request_code").attr('disabled',true);
            $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Processing...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $("#request_code").attr('disabled',false);
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("resend code");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#request_code").attr('disabled',false);
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("resend code");
                    pop_wrong("Something went wrong!");
                }
             });
          }); 
    $("#create_password").submit(function(e){
            e.preventDefault();
        if($("#password").val()===$("#cpassword").val()){
        var formData = new FormData(this)
            $('#spinner3').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator3').html("Processing...");
            $("#password").attr('readonly', true);
            $("#cpassword").attr('readonly', true);
            $("#cbtn").attr('disabled', true);
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $("#password").attr('readonly', false);
                    $("#cpassword").attr('readonly', false);
                    $("#cbtn").attr('disabled', false);
                    $('#spinner3').fadeOut('fast');
                    $('#indicator').html("Submit");
                    if(data.status==200){
                        pop_up_success("Password changed!")
                        $("#password").attr('readonly', true);
                        $("#cpassword").attr('readonly', true);
                        $("#cbtn").attr('hidden', true);
                        $('#title3').html("You are being redirected!");
                        setTimeout(function() {
                          window.location.href = "/auth";
                        }, 3000);
                    }
                    if(data.status==500){
                        pop_wrong("Password changed!");
                    }
                },error: function(){
                    $('#spinner3').fadeOut('fast');
                    $('#indicator3').html("Submit");
                    $("#cbtn").attr('disabled', false);
                    $("#password").attr('readonly', false);
                    $("#cpassword").attr('readonly', false);
                    pop_wrong("Something went wrong!");
                }
             });
        }
        else{
            pop_wrong("Password mismatch");
        }
          });
    });

    function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Wrong',
        message: feedback,
        position: 'topCenter'
     });
    }
    
    function pop_up_success(feedback) {
        iziToast.success({
        title: 'Info:',
        message: feedback,
        position: 'topCenter'
      });
    }
</script>