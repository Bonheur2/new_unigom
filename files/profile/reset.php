 <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Change Password</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Change password</div>
                    </div>
                </div>
                <div class="section-body">
                    <h2 class="section-title">Hi, <?php echo $first_name ?>!</h2>
                    <p class="section-lead">Change information about yourself </p>

                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-5">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">                     
                                    <img alt="image" src="../assets/img/avatar/avtUser.png" class="rounded-circle profile-widget-picture">
                                 
                                </div>
                                <div class="profile-widget-description">
                                    <div class="profile-widget-name"><?php echo $first_name." ".$family_name ?>  <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> <?php echo $u_role ?></div></div>
                                    
                                    Be positive when talking to them and be kind. Try to greet them early in the school day. This gives you a chance to get to know them and help them out throughout the day.
                                    Introduce yourself by name and make them feel welcome.
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-7">
                          <div class="card card-primary">
                        <div class="card-header">
                            <h4>Reset Password</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">We will automatically logout afte reset your password</p>
                            <form method="POST" action="change_pwd" id="change_pwd">
                                <div class="form-group">
                                    <label for="email">Old password</label>
                                    <input id="email3" type="hidden" class="form-control" name="username" value="<?php  echo $email ?>">
                                    <input id="email" type="password" class="form-control" name="oldpwd" tabindex="1" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" tabindex="2" required>
                                    <div id="pwindicator" class="pwindicator">
                                        <div class="bar"></div>
                                        <div class="label"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="confirm-password" tabindex="2" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4"> Comfirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        
<script>
$(document).ready(function(){
 
  
$("#change_pwd").submit(function(e){
            e.preventDefault();
    
           var formdata = new FormData(this);
           
            $.ajax({
                url: "../files/profile/change_pwd.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                   // alert(3);
               if(formData==1){
                   
                 pwd_changed(formData); 
                 setTimeout(function(){// wait for 5 secs(2)
       window.location.href = "../../auth"; 
       
     }, 5000); 
               }
               
               else if(formData==0)
               {
                 notOLD_changed(formData);    
               }
               
                else if(formData==3)
               {
                 unmatch(formData);    
               }
    
        
               
          
                },error: function(){
                    alert("okey");
                }
             });
          });
          
});

   function pwd_changed(feedback) {
   swal('Well Done', 'Password Changed!', 'success');
    }
    
     function notOLD_changed(feedback) {
    iziToast.warning({
    title: 'Wrong',
    message: 'Incorrect old password',
    position: 'topRight'
  });
    }
    
         function unmatch(feedback) {
     iziToast.show({
    title: 'Please',
    message: 'new password and comfirm password was Unmatch',
    position: 'topRight'
  });
    }
   
    

 </script>