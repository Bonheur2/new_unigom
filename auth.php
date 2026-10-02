<?php  include'infrom.php'; ?>
<?php  include'bar.php'; ?>
<?php  include'subbar.php'; ?>

<!-- Start app main Content -->
<div class="main-content" style="display:flex; align-items:center; min-height:calc(100vh - 200px);">
    <section class="section" style="width:100%;">
     <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Login</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="log_data" id="log_data" class="needs-validation" novalidate="">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="login-username" tabindex="1" required autofocus>
                                    <div class="invalid-feedback">
                                        Please fill in your email
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="login-password" tabindex="2" required>
                                    <div class="invalid-feedback">
                                        please fill in your password
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="remember" id="rememberbut" class="custom-control-input" tabindex="3" id="remember-me">
                                        <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        <div class="float-right">
                                            <a href="/forgot_password" class="text-small">
                                            Forgot Password?
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="loginbutton" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        <span id="spinner"></span>&nbsp;<span id="indicator">Login</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
        
<?php  include'org.php'; ?>         
<?php  include'comb/coda.php'; ?>     
<script>
    $(document).ready(function(){
        $("#log_data").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            document.getElementById("loginbutton").disabled = true;
            document.getElementById("rememberbut").disabled = true;
            document.getElementById("password").disabled = true;
            document.getElementById("email").disabled = true;
            $("#spinner").html("<img src='./img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#indicator").html("Authenticating...");
            
            $.ajax({
                url: "log_data.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data", 
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Login");
                    $("#loginbutton").removeAttr('disabled');
                    if(formData==0){
                        pop_wrong("Incorrect credentials");  
                    } else{
                        pop_up_success(formData);
                        setTimeout(function(){
                            if(formData==18){
                                window.location.href = "admin/edu";  
                            } else if(formData==1){
                                window.location.href = "IT/edu";
                            } else if(formData==2){
                                window.location.href = "academic/edu";  
                            } else if(formData==3){
                                window.location.href = "finance/edu";  
                            } else if(formData==4){
                                window.location.href = "student/edu";  
                            } else if(formData==5){
                                window.location.href = "applicant/edu";  
                            } else if(formData==6){
                            window.location.href = "admission/edu";  
                            } else if(formData==7){
                                window.location.href = "hr/edu";  
                            } else if(formData==8){
                                window.location.href = "librarian/edu";  
                            } else if(formData==9){
                                window.location.href = "card_operator/edu";  
                            } else if(formData==10){
                                window.location.href = "hod/edu";  
                            } else if(formData==11){
                                window.location.href = "dean/edu";  
                            } else if(formData==12){
                                window.location.href = "lecturer/edu";  
                            } else if(formData==13){
                                window.location.href = "daf/edu";  
                            } else if(formData==14){
                                window.location.href = "accountant/edu";  
                            } else if(formData==15){
                                window.location.href = "recovery/edu";  
                            } else if(formData==16){
                                window.location.href = "quality_operator/edu";  
                            } else if(formData==17){
                                window.location.href = "exam_officer/edu";  
                            } else if(formData==19){
                                window.location.href = "visitor/edu?mis=0";  
                            } else if(formData==20){
                                window.location.href = "management/edu?mis=1";  
                            } else if(formData==22){
                                window.location.href = "hec/edu?mis=1";  
                            } else if(formData==23){
                                window.location.href = "massets/edu?mis=1";  
                            }else if(formData==25){
                                window.location.href = "records_manager/edu";
                            }else if(formData==26){
                                window.location.href = "Hr_Office/edu";
                            }else if(formData==28){
                                window.location.href = "MBA/edu";
                            }
                            
                        }, 1500); 
                    }
                       
                    document.getElementById("loginbutton").disabled = false;
                    document.getElementById("rememberbut").disabled = false;
                    document.getElementById("password").disabled = false;
                    document.getElementById("email").disabled = false;
                },error: function(){
                    $("#loginbutton").removeAttr('disabled');
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Login");
                    pop_wrong("Something went wrong!");
                }
            });
        });
    });

   function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Ooops',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'Welcome to NUMIS',
            message: 'Logged in successfully ',
            position: 'topCenter'
        });
    }
</script>
