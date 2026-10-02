<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
<style>
    .pr {
         border-right: 1px solid grey;
    }
    .intl-tel-input,
    .iti{
      width: 100%;
    }
    .phone{
        padding-left: 52px !important;
    }
    @media screen and (max-width: 767px) {
        .pr {
        border-right: none;
        }
    }
</style>
    
<?php  include'infrom.php'; ?>
<?php  include'bar.php'; ?>
<?php  include'subbar.php'; ?>

        <!-- Start top menu -->
       

            <!-- Start app main Content -->
            <div class="main-content">
                <section class="section">
                   <div class="section-body">
                        <div class="col-12 col-sm-8 m-auto">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Create Demo account</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="register_demo" id="demo" class="needs-validation" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-6 pr">
                                                <div class="form-group">
                                                    <label class="control-label">Firstname</label>
                                                    <input type="text" class="form-control" name="first_name" tabindex="1" required autofocus>
                                                    <div class="invalid-feedback">Please fill in your first name</div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Lastname</label>
                                                    <input type="text" class="form-control" name="family_name" tabindex="2" required>
                                                    <div class="invalid-feedback">Please fill in your last name</div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Country</label>
                                                    <select name="country" class="form-control select2" style="width:100%;" tabindex="2" required>
                                                        <?php
                                                            $sql = $conn->prepare("SELECT * FROM tbl_country");
                                                            $sql->execute();
                                                            while($cn = $sql->fetch()){
                                                        ?>
                                                        <option value="<?php echo $cn['cntr_id']; ?>" <?php echo $cn['cntr_id']==160?'selected':''; ?>><?php echo $cn['cntr_name']; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please select your country</div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="control-label">Phone</label>
                                                    <input id="phone" name="phone" class="form-control phone" tabindex="2" maxlength="13" minlength="8" oninput="cleanPhoneNumber(this)" required>
                                                    <div class="invalid-feedback">Please fill in your phone number</div>
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required>
                                                    <div class="invalid-feedback">Please fill in your email</div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Password</label>
                                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                                    <div class="invalid-feedback p">Provide account password</div>
                                                </div>
                                                <div class="form-group cpDiv" hidden>
                                                    <label class="control-label">Confirm password</label>
                                                    <input id="cpassword" type="password" class="form-control" tabindex="2" required>
                                                    <div class="invalid-feedback cp">Provide account password</div>
                                                </div>
                                                <div style="text-align:center;" class="card-footer form-group m-auto sBtn" hidden>
                                                    <button type="submit" class="btn btn-primary btn-lg"><span id="spinner"></span>&nbsp;<span id="indicator">Register</span></button>
                                                </div>
                                            </div>

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
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
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
   function cleanPhoneNumber(input) {
     var phoneNumber = input.value;
     var cleanedPhoneNumber = phoneNumber.replace(/[^+0-9]/g, "");
     input.value = cleanedPhoneNumber;
   }
    $(document).ready(function(){
        var countryCode="RW"
        var inputMobileNumber = document.querySelector("#phone");
        var iti = window.intlTelInput(inputMobileNumber, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
        inputMobileNumber.addEventListener("keyup", function() {
              var maxLength = parseInt(this.getAttribute("maxlength"));
              var countryCode = iti.getSelectedCountryData().dialCode;
              var phoneNumber = iti.getNumber();
              if(this.value.length <= maxLength){
                  if (countryCode.endsWith("0") && phoneNumber.indexOf("0") === 0) {
                    $(this).val(phoneNumber.substring(1)); // Remove the leading zero
                  }
                  else{
                      $(this).val(phoneNumber);
                  }
              }
        });
        
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
        
        $("#password").on("blur", function() {
            var password = $(this).val();
            var isStrongPassword = validatePassword(password);
            if(isStrongPassword) {
                $(".p").hide();
            }
        });
        
        $("#cpassword, #cpassword").on("keyup blur", function() {
            var cpassword = $("#cpassword").val();
            var password = $("#password").val();
            if(cpassword === password) {
               $(".cp").hide();
               $(".sBtn").removeAttr('hidden');
            } else {
               $(".cp").show();
               $(".cp").css({'color':'red'});
               $(".cp").html("Password mismatch");
               $(".sBtn").attr('hidden',true);
            }
        });
        
        $("#demo").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $("#demo").find(":input").prop("disabled", true);
            $('#spinner').html("<img src='img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Processing...");
            $.ajax({
                url: "register_demo.php",
                type: "POST",
                data: formdata,
                dataType:"JSON", 
                processData: false,
                contentType: false,
                cache: false,
                success: function(data){
                    if(data.status==200){
                        pop_up_success();
                        $("#demo").find(":input").prop("disabled", false);
                    }
                    if(data.status==401){
                        $("#demo").find(":input").prop("disabled", false);
                        pop_info(data.message);
                    }
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Register");
                },error: function(){
                    pop_info();
                    $("#demo").find(":input").prop("disabled", false);
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Register");
                }
            });
        });
    });
    function pop_info() {
        iziToast.info({
        title: 'Ooops',
        message: 'Something went wrong!',
        position: 'topCenter'
      });
    }
    
    function pop_info(feedback) {
        iziToast.info({
        title: 'Ooops',
        message: feedback,
        position: 'topCenter'
      });
    }
    
    function pop_up_success() {
        iziToast.success({
        title: 'Account Created',
        message: 'Account created, Please verify your email',
        position: 'topCenter',
        timeout: 0
      });
    }
 </script>
