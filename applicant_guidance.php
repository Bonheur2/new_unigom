<?php include 'infrom.php'; ?>
<?php include 'bar.php'; ?>
<?php include 'subbar.php'; ?>
<?php require 'meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-6" style="margin:auto;">
                        <form id="apply" action="apply" method="POST">
                            <input type="hidden" name="action" value="apply">


                            <div class="card">
                                <div class="card-header row" style=" justify-content:center">
                                    <a href="<?php echo $app_base_url; ?>/new_files/Create_account/index">
                                        <button type="button" id="section-1-indicator" class="btn btn-primary col-12" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Nouvelle candidature</button>
                                    </a>
                                </div>


                                <!--personal details start-->
                                <div class="card-body row" id="section-1">
                                    <h5>Pour les nouveaux candidats :</h5>

                                    <ul>
                                        <li>Cliquez sur le bouton « Créer un compte ».</li>
                                        <li>Remplissez les informations demandées pour créer votre compte.</li>
                                        <li>Une fois votre compte créé, vous pourrez commencer votre candidature.</li>
                                    </ul>



                                    <div class="text-center" style=" justify-content:center">
                                        <a href="<?php echo $app_base_url; ?>/new_files/Create_account/index" class="btn btn-primary col-12"><i class="fas fa-user-graduate mr-2"></i> Créer un compte</a>
                                    </div>
                                </div>
                                <!-- /apply end-->


                                <!--address end-->
                            </div>
                            
                        </form>
                    </div>
                    
                    
                    <!--<div class="col-lg-3 col-md-3">-->
                    <!--   <h4>Application Short Video Guide</h4>-->
                    <!--   <video width="400" controls>-->
                    <!--       <source src="app.mp4" type="video/mp4">-->
                    <!--    </video>-->
                    <!--  </div>-->
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'org.php'; ?>
<?php include 'comb/coda.php'; ?>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#apply").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner').html("<img src='<?php echo $app_base_url; ?>/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Traitement en cours...");
            $("#sBtn").attr('disabled', true);
            $.ajax({
                url: "<?php echo $app_base_url; ?>/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Soumettre");
                    if (data.status == 200) {
                        var mail = $("#email").val().trim();
                        $("#apply")[0].reset();
                        pop_up_success(data.message);
                        setTimeout(function() {
                            window.location.href = "verify_email?em=" + mail;
                        }, 2000);

                    }
                    if (data.status == 401) {
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled', false);
                    }
                    if (data.status == 500) {
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled', false);
                    }
                },
                error: function() {
                    $('#spinner').fadeOut('fast');
                    $("#sBtn").attr('disabled', false);
                    $('#indicator').html("Soumettre");
                    pop_wrong("Une erreur est survenue. Veuillez réessayer.");
                }
            });
        });
    });

    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Info',
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

    function pop_wrong_verify(feedback) {
        iziToast.warning({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }

    function goToSection2() {
        var section1Valid = validateSection1();
        if (section1Valid) {
            $("#fname_star").html("");
            $("#lname_star").html("");
            $("#nid_star").html("");
            $("#gender_star").html("");
            $("#phone_star").html("");
            $("#email_star").html("");
            $('#spinner2').html("<img src='<?php echo $app_base_url; ?>/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Chargement...");
            setTimeout(function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Suivant");
                $("#section-1-indicator").removeClass('btn-primary');
                $("#section-1-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $('#section-1').attr('hidden', true);
                $('#section-2').attr('hidden', false);
            }, 1000);
        }
    }

    function goBackToSection1() {
        $('#spinner3').html("<img src='<?php echo $app_base_url; ?>/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3').html("Chargement...");
        setTimeout(function() {
            $('#spinner3').fadeOut('fast');
            $('#indicator3').html("Retour");
            $("#section-2-indicator").removeClass('btn-primary');
            $("#section-2-indicator").addClass('btn-light');
            $("#section-1-indicator").removeClass('btn-light');
            $("#section-1-indicator").addClass('btn-primary');
            $('#section-2').attr('hidden', true);
            $('#section-1').attr('hidden', false);
        }, 2000);

    }

    function validateSection1() {
        var fname = document.getElementById('fname').value.trim();
        var lname = document.getElementById('lname').value.trim();
        var nid = document.getElementById('nid').value.trim();
        var gender = document.getElementById('gender').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var email = document.getElementById('email').value.trim();

        if (fname === '' || lname === '' || nid === '' || gender === '' || phone === '' || email === '') {
            pop_wrong_verify("Veuillez remplir tous les champs obligatoires.");
            fname === '' ? $("#fname_star").html("*") : $("#fname_star").html("");
            lname === '' ? $("#lname_star").html("*") : $("#lname_star").html("");
            nid === '' ? $("#nid_star").html("*") : $("#nid_star").html("");
            gender === '' ? $("#gender_star").html("*") : $("#gender_star").html("");
            phone === '' ? $("#phone_star").html("*") : $("#phone_star").html("");
            email === '' ? $("#email_star").html("*") : $("#email_star").html("");
            return false;
        }

        return true;
    }
</script>