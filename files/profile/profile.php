 <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Profil</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                        <div class="breadcrumb-item">Profil</div>
                    </div>
                </div>
                <div class="section-body">
                    <h2 class="section-title">Bonjour, <?php echo $first_name ?>&nbsp;!</h2>
                    <p class="section-lead">Modifiez vos informations personnelles</p>

                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-5">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                    <img alt="image" src="../assets/img/avatar/avtUser.png" class="rounded-circle profile-widget-picture">

                                </div>
                                <div class="profile-widget-description">
                                    <div class="profile-widget-name"><?php echo $first_name." ".$family_name ?>  <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> <?php echo $u_role ?></div></div>

                                    Gardez vos informations &agrave; jour. Votre nom et votre num&eacute;ro de t&eacute;l&eacute;phone sont utilis&eacute;s pour vous contacter
                                    au sujet de votre candidature, veillez donc &agrave; ce qu&rsquo;ils soient corrects.
                                </div>

                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-7">
                            <div class="card">
                                <form method="post" id="change_data" action="change_data" class="needs-validation" novalidate="">
                                    <div class="card-header">
                                        <h4>Modifier le profil</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <label>Pr&eacute;nom</label>
                                                <input type="text" class="form-control" name="fname" value="<?php echo $first_name ?>" required>
                                                <div class="invalid-feedback">Veuillez saisir le pr&eacute;nom</div>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" name="lname" value="<?php echo $family_name ?>" required>
                                                <div class="invalid-feedback">Veuillez saisir le nom</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-7 col-12">
                                                <label>E-mail</label>
                                                <input type="email" class="form-control" value="<?php echo $email ?>" disabled>
                                                <div class="text-muted form-text">L&rsquo;adresse e-mail ne peut pas &ecirc;tre modifi&eacute;e ici.</div>
                                            </div>
                                            <div class="form-group col-md-5 col-12">
                                                <label>T&eacute;l&eacute;phone</label>
                                                 <input type="hidden" class="form-control" name="acc_i" value="<?php echo $acc_id ?>">
                                                <input type="tel" class="form-control" name="phone" value="<?php echo $phone_no ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group mb-0 col-12">
                                                <div class="custom-control custom-checkbox">


                                                    <input type="checkbox" name="remember" class="custom-control-input" id="newsletter">
                                                    <label class="custom-control-label" for="newsletter">Recevoir les informations de l&rsquo;universit&eacute;</label>
                                                    <div class="text-muted form-text">Vous recevrez les nouvelles informations sur l&rsquo;universit&eacute;, les offres et les promotions</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Enregistrer les modifications</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


<script>
$(document).ready(function(){


$("#change_data").submit(function(e){
            e.preventDefault();

           var formdata = new FormData(this);

            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Enregistrement...");

            $.ajax({
                url: "../files/profile/change_data.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
               $('#spinner').fadeOut('fast');
               $('#indicator').html("Enregistrer les modifications");

               if($.trim(formData) == 1){
                 pop_changed(formData);
                 setTimeout(function(){// recharger pour afficher les nouvelles valeurs
                   location.reload();
                 }, 2000);
               } else {
                 // le serveur a repondu, mais la mise a jour a echoue
                 pop_failed();
               }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Enregistrer les modifications");
                    pop_failed();
                }
             });
          });

});

   function pop_changed(feedback) {
   swal('Parfait', 'Vos informations ont été modifiées !', 'success');
    }

   function pop_failed() {
   swal('Erreur', 'Vos informations n’ont pas pu être enregistrées.', 'error');
    }


 </script>
