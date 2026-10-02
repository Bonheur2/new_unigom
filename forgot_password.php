<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php include'subbar.php'; ?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                    <form id="forgot_password" action="forgot_password" method="POST">
                        <div class="card">
                            <div class="card-header row">
                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title">Reset Your Password</h4><br>
                                <p class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title2"></p>
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
                                    <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group col-12 col-sm-8 col-lg-8" style="margin:auto;" id="vcode" hidden>
                                    <label>Verification Code</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="token" id="token" class="form-control" placeholder="_ _ _ _ _ _" minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-12" style="display:flex; justify-content:center; margin-top:20px;">
                                    <button type="submit" class="btn btn-primary btn-sm" style="margin:auto;"><span id="spinner"></span>&nbsp; <span id="indicator">Request Code</span>&nbsp;</button>
                                </div>
                            </div>
                            <div class="card-footer bg-whitesmoke" id="card-footer" hidden>
                                <p>Didn't receive code? <button type="button" class="btn btn-outline-info btn-sm" id="request_code"><span id="spinner2"></span>&nbsp; <span id="indicator2">resend code</span>&nbsp;</button></p>
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
$(document).ready(function(){
    $("#forgot_password").submit(function(e){
        e.preventDefault();
        if($("#token").val()===''){    
            var formData = {
                email:$("#email").val(),
                action: 'request_code'
                        }
                $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator').html("Processing...");
                $.ajax({
                    url: "files/User/user_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data){
                        $('#spinner').fadeOut('fast');
                        $('#indicator').html("Request code");
                        if(data.status==200){
                            $('#indicator').html("Submit");
                            $("#title2").html("Verification code was sent to your email")
                            $("#card-footer").attr('hidden',false);
                            $("#vcode").attr('hidden',false);
                        }
                        if(data.status==401){
                            pop_wrong("Unknown Email");
                        }
                        if(data.status==500){
                            pop_wrong("Failed to send email");
                        }
                    },error: function(){
                        $('#spinner').fadeOut('fast');
                        $('#indicator').html("Request code");
                        pop_wrong("Something went wrong!");
                    }
                 });
        }
        else{
            var formData = {
                token:$("#token").val(),
                action: 'verify_email'
                        }
                $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator').html("Processing...");
                $.ajax({
                    url: "files/User/user_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data){
                        $('#spinner').fadeOut('fast');
                        $('#indicator').html("Request code");
                        if(data.status==200){
                           window.location.href="reset?em="+$("#email").val();
                        }
                        if(data.status==401){
                            pop_wrong("Unknown Email");
                        }
                        if(data.status==500){
                            pop_wrong("Failed to verify email");
                        }
                    },error: function(){
                        $('#spinner').fadeOut('fast');
                        $('#indicator').html("Request code");
                        pop_wrong("Something went wrong!");
                    }
                 });
        }
    });

    $("#request_code").click(function(){
        var formData = {
            email: $("#email").val(),
            action:'send_code'
        }
        $("#request_code").attr('disabled',true);
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Processing...");
        $.ajax({
            url: "files/User/user_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $("#request_code").attr('disabled',false);
                $("#title2").html("Verification code was sent to your email");
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
});

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