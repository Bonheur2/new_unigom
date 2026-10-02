<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php require'meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
            <?php
            if(!isset($_GET['em'])){
                $token=$_GET['app'];
                $pass =$_GET['app'];
                $email=$_GET['em'];

                $dir = [
                    'cost' => 12,
                ];
                
                $password = password_hash($pass, PASSWORD_BCRYPT, $dir);
                $sth = $conn->prepare("SELECT email FROM tbl_applicants WHERE code='".$token."' AND email = '".$email."'");
                $sth->execute();
                
                if ($sth->rowCount() > 0) {
                    $sth2 = $conn->prepare("SELECT Identification FROM tbl_student_login WHERE email = '".$email."'");
                    $sth2->execute();
                    if ($sth2->rowCount() == 0) {
                        $stmt = $conn->prepare("INSERT INTO tbl_student_login(Identification,email,password,role_id) VALUES ('".$token."','".$email."','".$password."',5)");
                        $stmt2 = $conn->prepare("DELETE FROM email_verification_tokens WHERE email='".$email."'");
                        $stmt->execute();
                        $stmt2->execute();
                    }else{
                        echo '<script>window.location.href = "https://misnjala.edu.sl/auth";</script>';
                        exit;
                    }
                ?>
                    <div class="col-12 col-md-5 col-lg-5" style="margin:auto;">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="col-12 col-md-12 col-lg-12" style="text-align:center" id="title">Email Verified</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group col-12 col-sm-12 col-lg-12" style="margin:auto;">
                                    <label style="font-size:16px;">Once your email is confirmed, you can use <b><?=$pass; ?></b> as your password. If you prefer to create your own password, you can click <a href="/verify_email?em=<?php echo $_GET['em'].'&act=reset'; ?>" class="">Create password</a>, or you can proceed to <a href="/auth" class="">Login</a></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }else{
                    echo '<script>window.location.href = "https://misnjala.edu.sl/apply";</script>';
                    exit;
                }
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