<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Informations de l'étudiant</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Infos étudiant</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="rechercher par matricule ou par nom" id="input">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="contents">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="info" hidden>
        <div class="section-body"> 
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informations personnelles</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <div style="padding-bottom:8px;">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Prénom :</th>
                                            <th scope="col" id="fn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nom :</th>
                                            <th scope="col" id="ln"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Sexe :</th>
                                            <th scope="col" id="gen"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">État civil :</th>
                                            <th scope="col" id="mstatus"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Date de naissance :</th>
                                            <th scope="col" id="dobo"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nationalité :</th>
                                            <th scope="col" id="nat"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Carte d'identité/passeport :</th>
                                            <th scope="col" id="nid"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Père :</th>
                                            <th scope="col" id="ftn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Mère :</th>
                                            <th scope="col" id="mtn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Rentrée :</th>
                                            <th scope="col" id="intake"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                            <br/>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_p" data-id=""><span id="spinner_p"></span>&nbsp;<span id="indicator_p">modifier</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Coordonnées</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse3" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse3">
                            <div class="card-body">
                                <div class=" ">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">E-mail :</th>
                                            <th scope="col" id="em"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Téléphone :</th>
                                            <th scope="col" id="pn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Téléphone du parent :</th>
                                            <th scope="col" id="ppn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Deuxième téléphone :</th>
                                            <th scope="col" id="secpn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Personne à contacter :</th>
                                            <th scope="col" id="kin_na"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Lien de parenté :</th>
                                            <th scope="col" id="kin_re"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Adresse :</th>
                                            <th scope="col" id="kin_ad"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">E-mail :</th>
                                            <th scope="col" id="kin_em"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Téléphone :</th>
                                            <th scope="col" id="kin_te"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_c"  data-id=""><span id="spinner_c"></span>&nbsp;<span id="indicator_c">modifier</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Adresse</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Pays :</th>
                                            <th scope="col" id="cntr"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Province :</th>
                                            <th scope="col" id="prov"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">District :</th>
                                            <th scope="col" id="dis"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Rue :</th>
                                            <th scope="col" id="sec"></th>
                                        </tr>
                                       
                                    </thead>
                                    </table>
                                </div><br><br>
                            </div>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_a"  data-id=""><span id="spinner_a"></span>&nbsp;<span id="indicator_a">modifier</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Parcours académique</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse4">
                            <p class="mb-2"><strong>Université :</strong> <span id="acad_univ"></span></p>
                            <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Campus</th>
                                    <th>Faculté</th>
                                    <th>Type de programme</th>
                                    <th>Département</th>
                                    <th>Option</th>
                                    <th>Niveau</th>
                                    <th>Année académique</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="academics">

                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success btn-sm" id="acceptance"><i class="fa fa-cloud"></i> Lettre d'admission</button>&nbsp;
                            <button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('../files/Student/fileAdmin?stu=', 'DOSSIER ÉTUDIANT', 800, 600);"><i class="fa fa-download"></i> Télécharger le dossier</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Appartenance religieuse</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse10" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse10">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Église</th>
                                                <th scope="col">Pays</th>
                                                <th scope="col">Ville</th>
                                                <th scope="col">Secteur</th>
                                                <th scope="col">Licencié</th>
                                                <th scope="col">Ministre du culte</th>
                                                <th scope="col">Activités</th>
                                                <th scope="col">Ordonné</th>
                                                
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <td id="chrch"></td>
                                                <td id="cntry"></td>
                                                <td id="cty"></td>
                                                <td id="sctr"></td>
                                                <td id="lcncd"></td>
                                                <td id="mnstr"></td>
                                                <td id="ctvts"></td>
                                                <td id="rdnd"></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_chur"  data-id=""><span id="spinner_chur"></span>&nbsp;<span id="indicator_chur">modifier</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Études antérieures</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-20" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse-20">
                            <div class="table-responsive">
                                <button class="btn btn-success btn-sm" id="update_prev_edu" data-id="">Ajouter</button>
                                <table class="table  table-striped mb-none" id="datatable-default">  
									<thead>
										<tr>
											<th>N°</th>
        									<th>Établissement</th>
        									<th>Début</th>
        									<th>Fin</th>
        									<th>Diplôme/certificat</th>
        									<th>Mention obtenue</th>
        									<th>Action</th>
										</tr>
									</thead>
									<tbody id="prev_edu_data">
									</tbody>
								</table>
					        </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Matières principales</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse5" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse5">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Cours</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="passes">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Prise en charge</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse6" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse6">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Niveau</th>
                                    <th>Garant</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="sponsors">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--update modal personal-->
    <form action="update_form_p" method="POST" id="update_form_p">
        <div class="modal fade" role="dialog" id="updateModal_p">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_personal">
                <input type="hidden" name="stu" id="stu">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier les informations personnelles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom de famille</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="lname" id="lname" placeholder="ex. GASANA" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Prénom</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="fname" id="fname" placeholder="ex. Annet" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nationalité</label>
                            <div class="input-group">
                                <select name="nationality" id="nationality"  class="form-control select2" style="width:100%;">
                                    <?php
                                        $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                        $sql_nat->execute();
                                        $i=1;
                                        while($nat=$sql_nat->fetch()){
                                    ?>
                                        <option value="<?php echo $nat['nat_id']; ?>"><?php echo $nat['nationality']; ?> </option>
                                        <?php } ?>
                                        <option value="0">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Carte d'identité/passeport</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="nid" id="enid" placeholder="ex. 1 1990 800 xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sexe</label>
                            <div class="input-group">
                                <select name="gender" id="gender"  class="form-control select2" style="width:100%;" placeholder="choisir" required>
                                    <option value="" disabled selected hidden>Choisir...</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date de naissance</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <input type="date" class="form-control" name="dob" id="dob" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>État civil</label>
                            <div class="input-group">
                                <select name="marital_status" id="marital_status" class="form-control select2" style="width:100%;" placeholder="choisir" required>
                                    <option value="" disabled selected hidden>Choisir...</option>
                                    <option value="Single">Célibataire</option>
                                    <option value="Married">Marié(e)</option>
                                    <option value="Widowed">Veuf/veuve</option>
                                    <option value="Divorced">Divorcé(e)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nom du père</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <input type="text" name="father_names" id="father_names" class="form-control" placeholder="nom complet du père" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nom de la mère</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <input type="text" name="mother_names" id="mother_names" class="form-control" placeholder="nom complet de la mère" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->  
    <!--update modal contact-->
    <form action="update_form_c" method="POST" id="update_form_c">
        <div class="modal fade" role="dialog" id="updateModal_c">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_contact">
                <input type="hidden" name="stuc" id="stuc">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier les coordonnées</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>E-mail</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <input type="email" class="form-control" name="email" id="email" placeholder="ex. gasana@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-mobile"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="ex. +250788888888" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Téléphone du parent</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="email" class="form-control" name="parent_phone" id="parent_phone" placeholder="ex. +250788888888">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deuxième téléphone (facultatif)</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="ref_phone" id="ref_phone" class="form-control" placeholder="ex. +250788888888">
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label>Personne à contacter</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_name" id="kin_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lien de parenté</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-refresh"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_relation" id="kin_relation" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Adresse</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-map"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_address" id="kin_address" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <input type="email" name="kin_email" id="kin_email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_tel" id="kin_tel" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_c"></span>&nbsp;<span id="indicator2_p">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal contact-->
    <!--update modal address-->
    <form action="update_form_a" method="POST" id="update_form_a">
        <div class="modal fade" role="dialog" id="updateModal_a">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_address">
                <input type="hidden" name="stua" id="stua">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier l'adresse</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pays de résidence</label>
                            <select name="country" id="country" class="form-control select2" style="width:100%;">
                                <?php
                                    $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                    $sql_cntr->execute();
                                    while($cntr=$sql_cntr->fetch()){
                                ?>
                                    <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                    <?php } ?>
                            </select><span id="spinner_cntr"></span> 
                        </div>
                        <div class="form-group" id="prov">
                            <label>Province</label>
                            <select name="province_id" id="province_id" class="form-control select2" style="width:100%;">
                            </select><span id="spinner_prov"></span>
                        </div>
                        <div class="form-group" id="district">
                            <label>District</label>
                            <select name="district_id" id="district_id" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_dis"></span>
                        </div>
                        <div class="form-group" id="sect">
                            <label>Secteur</label>
                            <select name="sector" id="sector" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_sect"></span>
                        </div>
                        <div class="form-group" id="cell">
                            <label>Cellule</label>
                            <select name="cell_id" id="cell_id" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_cell"></span>
                        </div>
                        <div class="form-group" id="village">
                            <label>Village</label>
                            <select name="village_id" id="village_id" class="form-control select2" style="width:100%;">
                                
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="uBtn"><span id="spinner2_a"></span>&nbsp;<span id="indicator2_a">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
    
    <!--update modal church-->
    <form action="update_form_chur" method="POST" id="update_form_chur">
        <div class="modal fade" role="dialog" id="updateModal_chur">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier l'appartenance religieuse</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_church">
                        <input type="hidden" name="stu" id="stuch">
                        <div class="form-group">
                            <label>Église</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-city"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="church" id="church" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pays</label>
                            <div class="input-group">
                                <select name="country" id="countryc" class="form-control select2" style="width:100%;">
                                    <?php
                                        $sql_nat=$conn->prepare("SELECT * FROM tbl_country");
                                        $sql_nat->execute();
                                        $i=1;
                                        while($country = $sql_nat->fetch()){
                                    ?>
                                    <option value="<?php echo $country['cntr_id']; ?>" <?php echo $applicantData['country'] == $country['cntr_id']?'selected':''; ?>><?php echo $country['cntr_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>District/ville</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="city" id="city" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Secteur</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="sector" id="sector" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Licencié ?</label>
                            <div class="input-group">
                                <select name="licenced" id="licenced" class="form-control select2" style="width:100%;" placeholder="choisir" required>
                                    <option value="no">Non</option>
                                    <option value="yes">Oui</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ordonné ?</label>
                            <div class="input-group">
                                <select name="ordained" id="ordained" class="form-control select2" style="width:100%;" placeholder="choisir" required>
                                    <option value="no">Non</option>
                                    <option value="yes">Oui</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Êtes-vous ministre du culte ?</label>
                            <div class="input-group">
                                <select name="minister" id="minister" class="form-control select2" style="width:100%;" placeholder="choisir" required>
                                    <option value="no">Non</option>
                                    <option value="yes">Oui</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Si vous êtes ministre du culte, veuillez indiquer les activités de service chrétien auxquelles vous avez participé</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-list"></i>
                                    </div>
                                </div>
                                <input type="text" name="activities" id="activities" class="form-control" placeholder="activité 1, activité 2, activité 3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="uchurBtn"><span id="spinner2_chur"></span>&nbsp;<span id="indicator2_chur">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
    
    <!--update modal academic-->
    <form action="update_form_acc" method="POST" id="update_form_acc">
        <div class="modal fade" role="dialog" id="updateModal_acc">
            <div class="modal-dialog modal-lg" role="document">
                <input type="hidden" name="action" value="update_academic">
                <input type="hidden" name="reg_prg_id" id="reg_prg_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier le parcours académique</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Matricule</label>
                                <input type="text" class="form-control" id="stu_reg_no" readonly>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Université</label>
                                <input type="text" class="form-control" id="acc_univ" readonly>
                            </div>
                            <!-- Each select fills the next one (see the "academic cascade" script below). -->
                            <div class="form-group col-12 col-md-6">
                                <label>Campus</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="camp_id" id="acc_campus" data-kind="campus" data-next="faculty" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Faculté</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="fac_id" id="acc_faculty" data-kind="faculty" data-next="program_type" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Type de programme</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="prg_type" id="acc_program_type" data-kind="program_type" data-next="department" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Département</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="dept_id" id="acc_department" data-kind="department" data-next="option" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Option</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="opt_id" id="acc_option" data-kind="option" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Niveau</label>
                                <select class="form-control select2 acad-level" style="width:100%" name="level_id" id="acc_level" data-kind="level" required></select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Statut</label>
                                <select class="form-control select2" style="width:100%" name="reg_active" id="reg_active">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="uacBtn"><span id="spinner5_acc"></span>&nbsp;<span id="indicator5_acc">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal academic-->
    
    <!--update previous education-->
    <form action="update_form_prev" method="POST" id="update_form_prev">
        <div class="modal fade" role="dialog" id="updateModal_prevedu">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="stu" id="stucodep">
                <input type="hidden" name="action" value="save_education">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Études antérieures</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-12">
                            <label>Établissement</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="school" maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>De</label>
                            <div class="input-group" >
                                <select name="from" class="form-control select2" style="width: 100%;" required>
                                  <?php
                                    $currentYear = date("Y");
                                    $startYear = 1970;
                                    $endYear = date('Y');
                                
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                      echo "<option value='$year'>$year</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>À</label>
                            <div class="input-group" >
                                <select name="to" class="form-control select2" style="width: 100%;" required>
                                  <?php
                                    $currentYear = date("Y");
                                    $startYear = 1970;
                                    $endYear = date('Y');
                                
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                      echo "<option value='$year'>$year</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Certificat ou diplôme obtenu</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="certificate" maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Mention obtenue</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="award" maxlength="60" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_prev"></span>&nbsp;<span id="indicator2_prev">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update previous education--> 
    
    <!--update modal sponsor-->
    <form action="update_form_spon" method="POST" id="update_form_spon">
        <div class="modal fade" role="dialog" id="updateModal_spon">
            <div class="modal-dialog modal-lg" role="document">
                <input type="hidden" name="action" value="update_spon">
                <input type="hidden" name="reg_prg_id" id="reg_prg_ids">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier la prise en charge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Matricule</label>
                                <input type="text" class="form-control" name="reg_no" id="stu_reg_nos" readonly>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Garant</label>
                                <select class="form-control select2" style="width:100%" name="spon_id" id="spon_id" required>
                                    <?php
                                        $sql_spon = $conn->prepare("SELECT * FROM tbl_sponsor ORDER BY spon_full_name ASC");
                                        $sql_spon->execute();
                                        while($spon = $sql_spon->fetch()){
                                    ?>
                                    <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="uasBtn"><span id="spinner5_spon"></span>&nbsp;<span id="indicator5_spon">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal sponsor-->
    
    <!--update modal pass-->
    <form action="update_form_pass" method="POST" id="update_form_pass">
        <div class="modal fade" role="dialog" id="updateModal_pass">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_pass">
                <input type="hidden" name="course_id" id="course_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier le cours</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12"> 
                                <label>Cours</label>
                                <input type="text" class="form-control" name="course" id="course" required>
                            </div>
                            <div class="form-group col-12">
                                <label>Note</label>
                                <input type="text" class="form-control" name="grade" id="grade" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="upassBtn"><span id="spinner10_pass"></span>&nbsp;<span id="indicator10_pass">Enregistrer</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
</div>
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var pageURL = pageURL+$("#input").val();
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
 
<script>
    // Parcours académique: one row per registration, read along
    // université > campus > faculté > type de programme > département > option, plus the niveau.
    function acad_status_label(value){
        if(value == 1) return 'Actif';
        if(value == 0) return 'Inactif';
        return value == null ? '' : 'Statut ' + value;
    }

    function render_academics(rows){
        var $body = $("#academics").empty();
        $("#acad_univ").text(rows.length > 0 ? (rows[0].university_name || '') : '');
        if(rows.length == 0){
            $body.html('<tr><td colspan="10" align="center">Aucune donnée trouvée</td></tr>');
            return;
        }
        rows.forEach(function(value, index){
            var $row = $('<tr>');
            [index + 1, value.camp_full_name, value.fac_full_name, value.prg_type_full_name,
             value.dept_full_name, value.opt_full_name, value.level_full_name, value.acad_year,
             acad_status_label(value.reg_active)].forEach(function(cellData){
                $row.append($('<td>').text(cellData == null ? '' : cellData));
            });
            var $button = $('<button class="btn btn-sm btn-primary edit">').attr('data-id', value.reg_prg_id)
                .append($('<span>').attr('id', 'spinner_e_' + value.reg_prg_id))
                .append($('<span>').text('modifier'));
            $row.append($('<td>').append($button));
            $body.append($row);
        });
    }

    function load_info(){
        var student = $("#input").val();
        var formData = {
            stu:student,
            action:'load_info'
        }
        $("#contents").html("");
        $('#spinner').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "../new_files/Student_Info/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>")
                if(data.length>0){
                    $("#acceptance").data('id', student);

                    $("#info").attr("hidden",false);
                    $('#update_p').data("id", student);
                    $('#update_a').data("id", student);
                    $('#update_c').data("id", student);
                    $('#update_chur').data("id", student);
                    $('#update_prev_edu').data("id", student);
                    //personal
                    $("#fn").html(data[0].fname);
                    $("#ln").html(data[0].lname);
                    $("#nid").html(data[0].ID);
                    $("#nat").html(data[0].nat);
                    $("#gen").html(data[0].gender);
                    $("#mstatus").html(data[0].marital_status);
                    $("#dobo").html(data[0].dob);
                    $("#ftn").html(data[0].father_names);
                    $("#mtn").html(data[0].mother_names);
                    $("#intake").html(data[0].intake_month+" | "+data[0].acad_year);
                    
                    //address
                    $("#cntr").html(data[0].cname);
                    $("#prov").html(data[0].pname);
                    $("#dis").html(data[0].dname);
                    $("#sec").html(data[0].street);
                    //contact 
                    $("#em").html(data[0].email);
                    $("#pn").html(data[0].phone);
                    $("#ppn").html(data[0].parent_phone);
                    $("#secpn").html(data[0].ref_phone);
                    $("#kin_na").html(data[0].kin_name);
                    $("#kin_re").html(data[0].kin_relation);
                    $("#kin_ad").html(data[0].kin_address);
                    $("#kin_em").html(data[0].kin_email);
                    $("#kin_te").html(data[0].kin_tel);
                    
                    //academics
                    render_academics(data[1]);

                    //sponsors
                    $("#sponsors").html("");
                    if (data[8].length > 0) {
                        var i = 1;
                        var html = '';
                        
                        data[8].forEach(function(value) {
                            var reg_prg_id = value.reg_prg_id;
                            var level = value.level_full_name;
                            var sponsor = value.spon_full_name;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'edits');
                            button.setAttribute('data-id', reg_prg_id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_s_' + reg_prg_id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'modifier';

                            var row = document.createElement('tr');
                            var cells = [i, level, sponsor, ''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 3 ? 'td' : 'td');
                                if (index === 3) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('sponsors');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#sponsors').html('<tr><td colspan="4" align="center">Aucune donnée trouvée</td></tr>');
                    }
                     
                    //passes
                    $("#passes").html("");
                    if (data[7].length > 0) {
                        var i = 1;
                        var html = '';
                        
                        data[7].forEach(function(value) {
                            var cs = value.course;
                            var gr = value.grade;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'editp');
                            button.setAttribute('data-id', value.id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_f_' + value.id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'modifier';

                            var row = document.createElement('tr');
                            var cells = [i, cs, gr,''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 3 ? 'td' : 'td');
                                if (index === 3) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('passes');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#passes').html('<tr><td colspan="4" align="center">Aucune donnée trouvée</td></tr>');
                    }
                    
                    //previous education
                    $("#prev_edu_data").html("");
                    if (data[9].length > 0) {
                        var i = 1;
                        
                        data[9].forEach(function(value) {
                            var edu_id = value.id;
        					var school = value.school;
        					var year_from = value.year_from;
        					var year_to = value.year_to;
        					var certificate = value.certificate;
        					var award = value.award;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'deletedu');
                            button.setAttribute('data-id', edu_id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_edu_' + edu_id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'supprimer';

                            var row = document.createElement('tr');
                            var cells = [i, school, year_from, year_to, certificate, award, ''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 6 ? 'td' : 'td');
                                if (index === 6) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('prev_edu_data');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#prev_edu_data').html('<tr><td colspan="6" align="center">Aucune donnée trouvée</td></tr>');
                    }
                    
                    //church
                    if(data[10].length>0){
                        $("#chrch").html(data[10].church);
                        $("#cntry").html(data[10].countryn);
                        $("#cty").html(data[10].city);
                        $("#sctr").html(data[10].sector);
                        $("#lcncd").html(data[10].licenced);
                        $("#rdnd").html(data[10].ordained);
                        $("#mnstr").html(data[10].minister);
                        $("#ctvts").html(data[10].activities);
                    }
                }
                else{
                    $("#info").attr("hidden",true);
                }
            },error: function(){
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Une erreur est survenue !");
            }
        });
    }
    $(document).ready(function(){
        $("#input").keyup(function(e){
            var formData = {
                keyword:$(this).val(),
                action:'search'
            }
                $("#info").attr("hidden",true);
                $('#spinner').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    url: "../new_files/Student_Info/controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data){
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            data.forEach(function(value) {
                                var reg = value.reg_no;
                                var names=value.fname+" "+value.lname;
                                html += '<tr class="stu" data-id='+reg+'>';
                                html += '<th>' + i+ '</th>';
                                html += '<th>' + reg+ '</th>';
                                html += '<td>' + names+ '</td>';
                                html += '</tr>';
                                i++;
                            });
                            $('#contents').html(html);
                        } else{
                            $('#contents').html('<tr><td colspan="3" align="center">Aucune donnée trouvée</td></tr>');
                        }
                    },error: function(){
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        pop_wrong("Une erreur est survenue !");
                    }
            });
        });
        
        $(document).on('click', '.stu', function() {
            var student = $(this).data("id");
            var formData = {
                stu:student,
                action:'load_info'
            }
            $("#input").val($(this).data("id"));
            $("#contents").html("");
            $('#spinner').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "../new_files/Student_Info/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        $("#acceptance").data('id', student);
                        
                        $("#info").attr("hidden",false);
                        $('#update_p').data("id", student);
                        $('#update_a').data("id", student);
                        $('#update_c').data("id", student);
                        $('#update_chur').data("id", student);
                        $('#update_prev_edu').data("id", student);
                        //personal
                        $("#fn").html(data[0].fname);
                        $("#ln").html(data[0].lname);
                        $("#nid").html(data[0].ID);
                        $("#nat").html(data[0].nat);
                        $("#gen").html(data[0].gender);
                        $("#mstatus").html(data[0].marital_status);
                        $("#dobo").html(data[0].dob);
                        $("#ftn").html(data[0].father_names);
                        $("#mtn").html(data[0].mother_names);
                        $("#intake").html(data[0].intake_month+" | "+data[0].acad_year);
                        
                        //address
                        $("#cntr").html(data[0].cname);
                        $("#prov").html(data[0].pname);
                        $("#dis").html(data[0].dname);
                        $("#sec").html(data[0].street);
                        //contact
                        $("#em").html(data[0].email);
                        $("#pn").html(data[0].phone);
                        $("#ppn").html(data[0].parent_phone);
                        $("#secpn").html(data[0].ref_phone);
                        $("#kin_na").html(data[0].kin_name);
                        $("#kin_re").html(data[0].kin_relation);
                        $("#kin_ad").html(data[0].kin_address);
                        $("#kin_em").html(data[0].kin_email);
                        $("#kin_te").html(data[0].kin_tel);
                            
                        //academics
                        render_academics(data[1]);

                        //passes
                        $("#passes").html("");
                        if (data[7].length > 0) {
                            var i = 1;
                            var html = '';
                            
                            data[7].forEach(function(value) {
                                var cs = value.course;
                                var gr = value.grade;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'editp');
                                button.setAttribute('data-id', value.id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_f_' + value.id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'modifier';
    
                                var row = document.createElement('tr');
                                var cells = [i, cs, gr,''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 3 ? 'td' : 'td');
                                    if (index === 3) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('passes');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#passes').html('<tr><td colspan="4" align="center">Aucune donnée trouvée</td></tr>');
                        }
                        
                        //sponsors
                        $("#sponsors").html("");
                        if (data[8].length > 0) {
                            var i = 1;
                            var html = '';
                            
                            data[8].forEach(function(value) {
                                var reg_prg_id = value.reg_prg_id;
                                var level = value.level_full_name;
                                var sponsor = value.spon_full_name;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'edits');
                                button.setAttribute('data-id', reg_prg_id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_s_' + reg_prg_id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'modifier';
    
                                var row = document.createElement('tr');
                                var cells = [i, level, sponsor, ''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 3 ? 'td' : 'td');
                                    if (index === 3) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('sponsors');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#sponsors').html('<tr><td colspan="4" align="center">Aucune donnée trouvée</td></tr>');
                        }
                        
                        //previous education
                        $("#prev_edu_data").html("");
                        if (data[9].length > 0) {
                            var i = 1;
                            
                            data[9].forEach(function(value) {
                                var edu_id = value.id;
            					var school = value.school;
            					var year_from = value.year_from;
            					var year_to = value.year_to;
            					var certificate = value.certificate;
            					var award = value.award;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'deletedu');
                                button.setAttribute('data-id', edu_id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_edu_' + edu_id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'supprimer';
    
                                var row = document.createElement('tr');
                                var cells = [i, school, year_from, year_to, certificate, award, ''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 6 ? 'td' : 'td');
                                    if (index === 6) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('prev_edu_data');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#prev_edu_data').html('<tr><td colspan="6" align="center">Aucune donnée trouvée</td></tr>');
                        }
                        
                        //church
                        if(data[10].length>0){
                            $("#chrch").html(data[10][0].church);
                            $("#cntry").html(data[10][0].countryn);
                            $("#cty").html(data[10][0].city);
                            $("#sctr").html(data[10][0].sector);
                            $("#lcncd").html(data[10][0].licenced);
                            $("#rdnd").html(data[10][0].ordained);
                            $("#mnstr").html(data[10][0].minister);
                            $("#ctvts").html(data[10][0].activities);
                        }
                    }
                    else{
                        $("#info").attr("hidden",true);
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
        
        //pre-update View personal
        $('#update_p').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_p').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_p').fadeOut('fast');
                    $("#stu").val(data_id);
                    $("#fname").val(data[0].fname);
                    $("#lname").val(data[0].lname);
                    $("#enid").val(data[0].ID);
                    $("#mother_names").val(data[0].mother_names);
                    $("#father_names").val(data[0].father_names);
                    $("#dob").val(data[0].dob);
                    var selectElement = document.getElementById('gender');
                    var selectElement2 = document.getElementById('nationality');
                    var selectElement3 = document.getElementById('marital_status');
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].gender + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].nationality + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].marital_status + '"]');
                    if(selectedOption){
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    if(selectedOption2){
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    if(selectedOption3){
                        selectedOption3.selected = true;
                        selectElement3.prepend(selectedOption3);
                    }
                    $('#updateModal_p').modal('show');
				},
				error:function(error){
				    $('#spinner_p').fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        //pre-update View contact
        $('#update_c').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_c').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_c').fadeOut('fast');
                    $("#stuc").val(data_id);
                    $("#phone").val(data[0].phone);
                    $("#email").val(data[0].email);
                    $("#parent_phone").val(data[0].parent_phone);
                    $("#ref_phone").val(data[0].ref_phone);
                    $("#kin_name").val(data[0].kin_name);
                    $("#kin_relation").val(data[0].kin_relation);
                    $("#kin_address").val(data[0].kin_address);
                    $("#kin_email").val(data[0].kin_email);
                    $("#kin_tel").val(data[0].kin_tel);
                    $('#updateModal_c').modal('show');
				},
				error:function(error){
				    $('#spinner_c').fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        //pre-update View address
        $('#update_a').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_a').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_a').fadeOut('fast');
                    $("#stua").val(data_id);
                    var selectElement0 = document.getElementById('country');
                    if(data[0].country==160){
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                        var selectElement1 = document.getElementById('province_id');
                        var selectElement2 = document.getElementById('district_id');
                        var selectElement3 = document.getElementById('sector');
                        var selectElement4 = document.getElementById('cell_id');
                        var selectElement5 = document.getElementById('village_id');
                        $.each(data[2], function (index, value) {
                                $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename +"</option>");
                            });
                        $.each(data[3], function (index, value) {
                                $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict +"</option>");
                            });
                        $.each(data[4], function (index, value) {
                                $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector +"</option>");
                            });
                        $.each(data[5], function (index, value) {
                                $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell +"</option>");
                            });
                        $.each(data[6], function (index, value) {
                                $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName +"</option>");
                            });
                        
                        // Set selected values
                        var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].province_id + '"]');
                        var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].district_id + '"]');
                        var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].sector + '"]');
                        var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].cell_id + '"]');
                        var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].village_id + '"]');
                        if(selectedOption1){
                            selectedOption1.selected = true;
                            selectElement1.prepend(selectedOption1);
                        }
                        if(selectedOption2){
                            selectedOption2.selected = true;
                            selectElement2.prepend(selectedOption2);
                        }
                        if(selectedOption3){
                            selectedOption3.selected = true;
                            selectElement3.prepend(selectedOption3);
                        }
                        if(selectedOption4){
                            selectedOption4.selected = true;
                            selectElement4.prepend(selectedOption4);
                        }
                        if(selectedOption5){
                            selectedOption5.selected = true;
                            selectElement5.prepend(selectedOption5);
                        }
                    }
                    else{
                        $("#prov").attr('hidden',true);
                        $("#district").attr('hidden',true);
                        $("#sect").attr('hidden',true);
                        $("#cell").attr('hidden',true);
                        $("#village").attr('hidden',true);
                    }
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                    $('#updateModal_a').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        
        //pre-update View church info
        $('#update_chur').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_chur').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_chur').fadeOut('fast');
                    $("#stuch").val(data_id);
                    
                    if(data[10].length != 0){
                        $("#church").val(data[10][0].church);
                        $("#city").val(data[10][0].city);
                        $("#sector").val(data[10][0].sector);
                        $("#activities").val(data[10][0].activities);
                        
                        var selectElement1 = document.getElementById('countryc');
                        var selectElement2 = document.getElementById('licenced');
                        var selectElement3 = document.getElementById('ordained');
                        var selectElement4 = document.getElementById('minister');
                        
                        // Set selected values
                        var selectedOption1 = selectElement1.querySelector('option[value="' + data[10][0].country + '"]');
                        var selectedOption2 = selectElement2.querySelector('option[value="' + data[10][0].licenced + '"]');
                        var selectedOption3 = selectElement3.querySelector('option[value="' + data[10][0].ordained + '"]');
                        var selectedOption4 = selectElement4.querySelector('option[value="' + data[10][0].minister + '"]');
                        if(selectedOption1){
                            selectedOption1.selected = true;
                            selectElement1.prepend(selectedOption1);
                            
                        }
                        if(selectedOption2){
                            selectedOption2.selected = true;
                            selectElement2.prepend(selectedOption2);
                        }
                        if(selectedOption3){
                            selectedOption3.selected = true;
                            selectElement3.prepend(selectedOption3);
                        }
                        if(selectedOption4){
                            selectedOption4.selected = true;
                            selectElement4.prepend(selectedOption4);
                        }
                    }
                    $('#updateModal_chur').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        //academic cascade: campus > faculty > program type > department > option, with the level under the program type.
        // Changing one select reloads the ones below it and empties the rest.
        var acad_below = {
            campus: ['faculty', 'program_type', 'department', 'option', 'level'],
            faculty: ['program_type', 'department', 'option', 'level'],
            program_type: ['department', 'option', 'level'],
            department: ['option'],
            option: [],
            level: []
        };

        function fill_acad_select(kind, items, selected){
            var $select = $('#acc_' + kind).empty().append('<option value="">Choisir...</option>');
            $.each(items, function(index, item){
                $select.append($('<option>').val(item.id).text(item.name));
            });
            $select.val(selected == null ? '' : String(selected)).trigger('change.select2');
        }

        function load_acad_children(kind, parent){
            if(!parent){
                fill_acad_select(kind, []);
                return;
            }
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: {action: 'load_children', kind: kind, parent: parent},
                dataType: "JSON",
                success: function(items){ fill_acad_select(kind, items); },
                error: function(){ pop_wrong("Une erreur est survenue !"); }
            });
        }

        $(document).on('change', '.acad-level', function(){
            var kind = $(this).data('kind');
            var value = $(this).val();
            acad_below[kind].forEach(function(child){ fill_acad_select(child, []); });
            var next = $(this).data('next');
            if(next) load_acad_children(next, value);
            // Levels belong to the program type, not to the department.
            if(kind == 'program_type') load_acad_children('level', value);
        });

        //pre-update View academics
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            $('#spinner_e_'+data_id).html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: {id: data_id, action: 'load_academic_info'},
                dataType: "JSON",
                success: function(data){
                    $('#spinner_e_'+data_id).fadeOut('fast');
                    if(data.status != 200){
                        pop_wrong(data.message);
                        return;
                    }
                    var record = data.record;
                    $("#reg_prg_id").val(data_id);
                    $("#stu_reg_no").val(record.reg_no);
                    $("#acc_univ").val(record.university_name || '');
                    fill_acad_select('campus', data.lists.campus, record.camp_id);
                    fill_acad_select('faculty', data.lists.faculty, record.fac_id);
                    fill_acad_select('program_type', data.lists.program_type, record.prg_type);
                    fill_acad_select('department', data.lists.department, record.dept_id);
                    fill_acad_select('option', data.lists.option, record.splz_id);
                    fill_acad_select('level', data.lists.level, record.level_id);

                    // Keep an unusual status value selectable instead of silently changing it.
                    var $status = $('#reg_active');
                    $status.find('option.extra').remove();
                    if($status.find('option[value="' + record.reg_active + '"]').length == 0){
                        $status.append($('<option class="extra">').val(record.reg_active).text(acad_status_label(record.reg_active)));
                    }
                    $status.val(String(record.reg_active)).trigger('change.select2');

                    $('#updateModal_acc').modal('show');
                },
                error: function(){
                    $('#spinner_e_'+data_id).fadeOut('fast');
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
        
        
        //pre-update View sponsor
        $(document).on('click','.edits',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'load_sponsor_info'
                };
            $('#spinner_s_'+data_id).html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_s_'+data_id).fadeOut('fast');
                    $("#reg_prg_ids").val(data_id);
                    $("#stu_reg_nos").val(data.reg_no);

                    var selectElement = document.getElementById('spon_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data.spon_id + '"]');

                    if(selectedOption){
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }

                    $('#updateModal_spon').modal('show');
				},
				error:function(error){
				    $('#spinner_s_'+data_id).fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        
        //pre-update View passes
        $(document).on('click','.editp', function () {
            var data_id = $(this).data('id');
            var getData= {
                    course_id: data_id,
                    action:'load_pass_info'
                    };
            $('#spinner_f_'+data_id).html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../new_files/Student_Info/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_f_'+data_id).fadeOut('fast');
                    $("#course_id").val(data_id);
                    $("#course").val(data.course);
                    $("#grade").val(data.grade);
                    $('#updateModal_pass').modal('show');
				},
				error:function(error){
				    $('#spinner_f_'+data_id).fadeOut('fast');
				    pop_wrong("Une erreur est survenue !");
				}
            });
        });
        
        //Update Sponsors
        $("#update_form_spon").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner5_spon').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator5_spon').html("Enregistrement...");
            $.ajax({
                url: "../files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner5_spon').fadeOut('fast');
                    $('#indicator5_spon').html("Enregistrer");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_spon").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner5_spon').fadeOut('fast');
                    $('#indicator5_spon').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });

        //Update Personal
        $("#update_form_p").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_p').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_p').html("Enregistrement...");
            $.ajax({
                url: "../new_files/Student_Info/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Enregistrer");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_p").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
        
        //Update contact
        $("#update_form_c").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_c').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_c').html("Enregistrement...");
            $.ajax({
                url: "../new_files/Student_Info/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Enregistrer");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_c").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
              
        //Update address
        $("#update_form_a").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_a').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_a').html("Enregistrement...");
            $.ajax({
                url: "../new_files/Student_Info/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Enregistrer");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_a").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
        
        
        //Update Church
        $("#update_form_chur").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_chur').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_chur').html("Enregistrement...");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_chur').fadeOut('fast');
                    $('#indicator2_chur').html("Enregistrer");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_chur").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_chur').fadeOut('fast');
                    $('#indicator2_chur').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
        
        //update academics
        $("#update_form_acc").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner5_acc').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator5_acc').html("Enregistrement...");
            $("#uacBtn").attr('disabled', true);
            $.ajax({
                url: "../new_files/Student_Info/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner5_acc').fadeOut('fast');
                    $('#indicator5_acc').html("Enregistrer");
                    $("#uacBtn").removeAttr('disabled');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_acc").modal('hide');
                        load_info();
                    } else {
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#uacBtn").removeAttr('disabled');
                    $('#spinner5_acc').fadeOut('fast');
                    $('#indicator5_acc').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });

        //update passes
        $("#update_form_pass").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner10_pass').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator10_pass').html("Enregistrement...");
            $("#upassBtn").attr('disabled', true);
            $.ajax({
                url: "../files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Enregistrer");
                    $("#upassBtn").removeAttr('disabled');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_pass").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#upassBtn").removeAttr('disabled');
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Enregistrer");
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
          
        //load provinces
        $('#country').change(function () {
            if($("#country").val()==160){
                $("#province_id").attr('required', true);
                $("#district_id").attr('required', true);
                $("#sector").attr('required', true);
                $("#cell_id").attr('required', true);
                $("#village_id").attr('required', true);
                $("#uBtn").attr('hidden', true);
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
                
                var getData= {
                        action:'load_provinces'
                        };
                $('#spinner_cntr').html("<img src='../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../files/application/application_controller.php",
                    data: getData,
                    dataType:"JSON",
                    success:function(data){
                        $('#spinner_cntr').fadeOut('fast');
                        $("#province_id").append("<option></option>")
                        $.each(data, function (index, value) {
                            $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                        });
                        $("#prov").attr('hidden',false);
    				},
    				error:function(error){
    				    $('#spinner_cntr').fadeOut('fast');
                        pop_wrong("Une erreur est survenue !"); 
    				}
                });
            }
            else{
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#uBtn").attr('hidden', false);
                $("#province_id").attr('required', false);
                $("#district_id").attr('required', false);
                $("#sector").attr('required', false);
                $("#cell_id").attr('required', false);
                $("#village_id").attr('required', false);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
            }
        });

        //load districts
        $('#province_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    pid:$('#province_id').val(),
                    action:'load_districts'
                    };
            $("#district_id").empty();
            $('#spinner_prov').html("<img src='../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_prov').fadeOut('fast');
                    $("#district_id").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict+"</option>");
                    });
                    $("#district").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_prov').fadeOut('fast');
                    pop_wrong("Une erreur est survenue !"); 
				}
            });
        });
        
        //load sectors
        $('#district_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    did: $('#district_id').val(),
                    action:'load_sectors'
                    };
            $("#sector").empty();
            $('#spinner_dis').html("<img src='../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_dis').fadeOut('fast');
                    $("#sector").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector+"</option>");
                    });
                    $("#sect").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_dis').fadeOut('fast');
                    pop_wrong("Une erreur est survenue !"); 
				}
            });
        });
        
        //load cells
        $('#sector').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    sid: $('#sector').val(),
                    action:'load_cells'
                    };
            $("#cell_id").empty();
            $('#spinner_sect').html("<img src='../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_sect').fadeOut('fast');
                    $("#cell_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell+"</option>");
                    });
                    $("#cell").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_sect').fadeOut('fast');
                    pop_wrong("Une erreur est survenue !"); 
				}
            });
        });
        
        //load villages
        $('#cell_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    cid: $('#cell_id').val(),
                    action:'load_villages'
                    };
            $("#village_id").empty();
            $('#spinner_cell').html("<img src='../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_cell').fadeOut('fast');
                    $.each(data, function (index, value) {
                        $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName+"</option>");
                    });
                    $("#village").attr('hidden',false);
                    $("#uBtn").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_cell').fadeOut('fast');
                    pop_wrong("Une erreur est survenue !"); 
				}
            });
        });
        
        
        $(document).on('click', '#update_prev_edu', function(e){
            $("#stucodep").val($(this).data('id'));
            $("#updateModal_prevedu").modal('show');
        });
        
        $("#update_form_prev").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner2_prev').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_prev').html("Enregistrement...");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner2_prev').fadeOut('fast');
                    $('#indicator2_prev').html("Enregistrer");
                    pop_up_success(formData.message)
                    load_info();
                },error: function(){
                    pop_wrong("Une erreur est survenue !");
                    $('#spinner2_prev').fadeOut('fast');
                    $('#indicator2_prev').html("Enregistrer");
                }
            });
        });
        
        $(document).on('click', '.deletedu', function(){
            var entry = $(this).data('id');
            var formdata = {
                id: entry,
                action: 'delete_education'
            }

            $('#spinner_edu_'+entry).html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                success: function(formData){
                    $('#spinner_edu_'+entry).fadeOut('fast');
                    pop_up_success(formData.message)
                    load_info();
                },error: function(){
                    pop_wrong("Une erreur est survenue !");
                    $('#spinner_edu_'+entry).fadeOut('fast');
                }
            });
        });
        
        
        $("#acceptance").click(function(){
        	var student = $(this).data('id');
        	const pageURL = "../files/Student/acceptance?k="+student;
            var left = (screen.width - 800) / 2;
            var top = (screen.height - 600) / 4;
            window.open(pageURL, "Lettre d'admission", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=600, top=' + top + ', left=' + left);
        });
    });      
</script>