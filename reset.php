<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php include'subbar.php'; ?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                    <form id="reset" action="reset" method="POST" autocomplete="off">
                        <div class="card">
                            <div class="card-header row">
                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title">Password Reset</h4>
                            </div>
                            <div class="card-body" id="pre_verify">
                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;">
                                    <input type="hidden" name="action" value="reset_password">
                                    <div class="form-group" hidden>
                                        <label class="control-label">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" tabindex="1" value="<?=$_GET['em']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Create Password</label>
                                        <div class="input-group">
                                            <input id="password" type="password" class="form-control" name="password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="togglePassword('password', this)">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback p">Provide account password</div>
                                    </div>
                                    <div class="form-group cpDiv" hidden>
                                        <label class="control-label">Confirm password</label>
                                        <div class="input-group">
                                            <input id="cpassword" type="password" class="form-control" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="togglePassword('cpassword', this)">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback cp">Provide account password</div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                    <button type="submit" class="btn btn-primary btn-sm" style="margin:auto;"><span id="spinner"></span>&nbsp; <span id="indicator">Reset</span>&nbsp;</button>
                                </div>
                            </div>
                            
                            <div class="card-body" id="resetmsg" hidden>
                                <div class="form-group col-12 col-sm-12 col-lg-12" style="margin:auto;">
                                    <label style="font-size:16px;">Password reset successfully.</label>
                                    <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                        <a href="/auth" class="btn btn-primary btn-sm" style="margin:auto;">Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
    function validatePassword(password) {
       var hasUppercase = /[A-Z]/.test(password);
       var hasLowercase = /[a-z]/.test(password);
       var hasNumber = /\d/.test(password);
       var hasSpecialChar = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(password);
       var isLengthValid = password.length >= 8;
       if (hasUppercase && hasLowercase && hasNumber && hasSpecialChar && isLengthValid) {
          return true;
        } else {
          return false;
        }
    }
    $(document).ready(function(){
        $("#password").on("keyup", function() {
            var password = $(this).val();
            var isStrongPassword = validatePassword(password);
            if(isStrongPassword) {
               $(".p").show();
               $(".p").css({'color':'green'});
               $(".p").html("Password is strong");
               $(".cpDiv").removeAttr('hidden');
            } else {
                $(".sBtn").attr('hidden',true);
                $(".p").show();
                $(".p").css({'color':'red'});
                $(".p").html("Password is Weak");
                $(".cpDiv").attr('hidden',true);
            }
        });
        $("#reset").submit(function(e){
            e.preventDefault();
            if($("#password").val()===$("#cpassword").val()){
            var formData = new FormData(this)
                $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator').html("Processing...");
                $("#password").attr('readonly', true);
                $("#cpassword").attr('readonly', true);
                $("#cbtn").attr('disabled', true);
                $.ajax({
                    url: "files/User/user_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType:false,
                    success: function(data){
                        if(data.status==200){
                            $("#pre_verify").attr('hidden', true);
                            $("#resetmsg").removeAttr('hidden');
                        }
                        if(data.status==500){
                            pop_wrong("Something went wrong!");
                        }
                    },error: function(){
                        $('#spinner').fadeOut('fast');
                        $('#indicator').html("Submit");
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

    function togglePassword(fieldId, iconElement) {
        const field = document.getElementById(fieldId);
        const icon = iconElement.querySelector('i');
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function pop_wrong(feedback) {
        iziToast.warning({
        title: 'info',
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