<?php 
include "meet/bind.php"; 
include'infrom.php'; 

session_unset();
session_destroy();
?>
<?php  include'bar.php'; ?>
<?php  include'subbar2.php'; ?>

        <!-- Start top menu -->
       
<?php
$currentTime = date('H'); // Get the current hour in 24-hour format

if ($currentTime >= 6 && $currentTime < 18) {
    $message = "Have a nice day!";
} else {
    $message = "Have a good night!";
}
?>
        <!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
               

               <div class="section-body">
                    <div class="row">
                     
                        
                           <div class="col-12 mb-4">
                            <div class="hero align-items-center bg-light text-black">
                                <div class="hero-inner text-center">
                                    <h3>All done! <?php  echo $message ?></h3>
                                    <div class="mt-4">
                                        <span style="font-size: 16px;">A bit more to do?&nbsp;</span><a href="auth" class="btn btn-info btn-outline-success btn-sm btn-icon icon-left" id="log_click"><i class="fas fa-sign-out-alt"></i>Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
  <?php  include'comb/orgin.php'; ?>       
   <?php  include'comb/coda.php'; ?>     
     
  <script>


$(document).ready(function(){
   
  $("#container1").addClass("disable-div");
          setTimeout(function(){// wait for 5 secs(2)
    
  
  var linkUrl = document.getElementById('log_click').href;
        location.href = linkUrl;
     
     }, 5000);
     
          
});


 </script>
