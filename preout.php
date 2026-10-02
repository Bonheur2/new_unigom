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
$currentTime = date('H'); // Heure actuelle (format 24 h)

if ($currentTime >= 6 && $currentTime < 18) {
    $message = "Bonne journée !";
} else {
    $message = "Bonne soirée !";
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
                                    <h3>Déconnexion réussie. <?php  echo $message ?></h3>
                                    <div class="mt-4">
                                        <span style="font-size: 16px;">Encore quelque chose à faire ?&nbsp;</span><a href="auth" class="btn btn-info btn-outline-success btn-sm btn-icon icon-left" id="log_click"><i class="fas fa-sign-out-alt"></i>Se connecter</a>
                                    </div>
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
   
  $("#container1").addClass("disable-div");
          setTimeout(function(){// redirection vers la connexion après 5 secondes
    
  
  var linkUrl = document.getElementById('log_click').href;
        location.href = linkUrl;
     
     }, 5000);
     
          
});


 </script>
