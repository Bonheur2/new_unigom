<?php 
include "meet/bind.php"; 
include'infrom.php'; 

?>
<?php  include'bar.php'; ?>
<?php  include'subbar2.php'; ?>

        <!-- Start top menu -->
       

        <!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
               

               <div class="section-body">
                    <!--<h2 class="section-title">How to Access system and Application Requirement?</h2>-->
                    <!--<p class="section-lead">Toggle the visibility of content across your project with a few classes and our JavaScript plugins.</p>-->

                    <div class="row">
                     
                        
                           <div class="col-12 mb-4">
                            <div class="hero align-items-center bg-info text-white">
                                <div class="hero-inner text-center">
                                    <h2>Dear, <?php  echo $first_name ?>!</h2>
                                    <p class="lead">System Role have been successfully Changed.</p>
                                    <div class="mt-4">
                                        <a href="lgout" class="btn btn-outline-white btn-lg btn-icon icon-left" id="log_click"><i class="fas fa-sign-out-alt"></i> continue</a>
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
     
     }, 3000);
     
          
});


 </script>
