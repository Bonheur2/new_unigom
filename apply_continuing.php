<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<?php require'meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-9 col-lg-9" style="margin:auto;">
                    <form id="apply_continuing" action="apply_continuing" method="POST">
                        <input type="hidden" name="action" value="apply_continuing">
                        <div class="card">
                            <div class="card-header row"
                                style="display:flex; flex-wrap:wrap; justify-content:center; gap:6px;">
                                <button type="button" id="section-1-indicator"
                                    class="btn btn-primary step-btn"
                                    style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                        class="badge badge-transparent">1</span> &nbsp;Informations personnelles</button>
                                <button type="button" id="section-2-indicator"
                                    class="btn btn-light step-btn"
                                    style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                        class="badge badge-transparent">2</span> &nbsp;Adresse</button>
                                <button type="button" id="section-3-indicator"
                                    class="btn btn-light step-btn"
                                    style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                        class="badge badge-transparent">3</span> &nbsp;Contact</button>
                                <button type="button" id="section-4-indicator"
                                    class="btn btn-light step-btn"
                                    style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                        class="badge badge-transparent">4</span> &nbsp;Informations acad&eacute;miques</button>
                                <button type="button" id="section-5-indicator"
                                    class="btn btn-light step-btn"
                                    style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                        class="badge badge-transparent">5</span> &nbsp;Autres</button>
                            </div>
                            <!--personal details start-->
                            <div class="card-body row" id="section-1">
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Nom (Nom de famille)
                                        <code><b><span id="lname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="lname" id="lname"
                                            placeholder="ex. GASANA" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Pr&eacute;nom <code><b><span id="fname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="fname" id="fname"
                                            placeholder="ex. Annet" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Carte d'identit&eacute;/Passeport <code><b><span id="nid_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-id-card"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="nid" id="nid"
                                            placeholder="ex. 1 1990 800 xxxxxxxxxx" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Date de naissance</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="date" class="form-control" name="dob"
                                            value="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Sexe <code><b><span id="gender_star"></span></b></code></label>
                                    <select name="gender" id="gender" class="form-control select2" style="width:100%"
                                        required>
                                        <option value="" disabled selected hidden>Choisir...</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">F&eacute;minin</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>&Eacute;tat civil</label>
                                    <div class="input-group">
                                        <select name="marital_status" class="form-control select2" style="width:100%;"
                                            placeholder="choose one" required>
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="Single">C&eacute;libataire</option>
                                            <option value="Married">Mari&eacute;(e)</option>
                                            <option value="Widowed">Veuf/Veuve</option>
                                            <option value="Divorced">Divorc&eacute;(e)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Nationalit&eacute; <code><b><span id="nat_star"></span></b></code></label>
                                    <select name="nationality" id="nationality" class="form-control select2"
                                        style="width:100%">
                                        <option value='' disabled selected>--Choisir--</option>
                                        <?php
                                            $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                            $sql_nat->execute();
                                            $i=1;
                                            while($nat=$sql_nat->fetch()){
                                        ?>
                                        <option value="<?php echo $nat['nat_id']; ?>"><?php echo $nat['nationality']; ?>
                                        </option>
                                        <?php } ?>
                                        <option value="0">Autre</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Nom du p&egrave;re <code><b><span id="pname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="father_names" id="father_names" class="form-control"
                                            placeholder="noms complets du p&egrave;re" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Nom de la m&egrave;re <code><b><span id="mname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="mother_names" id="mother_names" class="form-control"
                                            placeholder="noms complets de la m&egrave;re" required>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection2()"><span
                                            id="spinner1-1"></span>&nbsp; <span id="indicator1-1">Suivant</span></button>
                                </div>
                            </div>
                            <!--personal details end-->
                            <!--address start-->
                            <div class="card-body row" id="section-2" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                    <label>Pays de r&eacute;sidence<span id="Country_star"></label>
                                    <select name="country" id="country" class="form-control select2" style="width:100%"
                                        required>
                                        <?php
                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                            $sql_cntr->execute();
                                            $i=1;
                                            while($cntr=$sql_cntr->fetch()){
                                        ?>
                                        <option value="<?php echo $cntr['cntr_id']; ?>">
                                            <?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                        <?php } ?>
                                    </select><span id="spinner_cntr"></span>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4" id="prov" hidden>
                                    <label>Province <code><b><span id="prov_star"></span></b></code></label>
                                    <select name="province_id" id="province_id" class="form-control select2"
                                        style="width:100%">
                                    </select><span id="spinner_prov"></span>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4" id="district" hidden>
                                    <label>District <code><b><span id="dis_star"></span></b></code></label>
                                    <select name="district_id" id="district_id" class="form-control select2"
                                        style="width:100%">

                                    </select><span id="spinner_dis"></span>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-4" id="village" hidden>
                                    <label>Avenue/Rue <code><b><span id="vil_star"></span></b></code></label>
                                    <input type="text" name="street" id="street" class="form-control"
                                        style="width:100%">
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" style="margin-right:10px;"
                                        onclick="goToSection3()"><span id="spinner2-1"></span>&nbsp; <span
                                            id="indicator2-1">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="goBackToSection1()"><span id="spinner2-2"></span>&nbsp; <span
                                            id="indicator2-2">Pr&eacute;c&eacute;dent</span></button>
                                </div>
                            </div>
                            <!--address end-->
                            <!--contact start-->
                            <div class="card-body row" id="section-3" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Num&eacute;ro de t&eacute;l&eacute;phone <code><b><span id="phone_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control phone-number" name="phone" id="phone"
                                            placeholder="ex. +243..." required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>E-mail <code><b><span id="email_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="ex. annet@example.com" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>T&eacute;l&eacute;phone du parent (si disponible)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="parent_phone" class="form-control"
                                            placeholder="ex. +243...">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Deuxi&egrave;me t&eacute;l&eacute;phone (si disponible)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="ref_phone" class="form-control"
                                            placeholder="ex. +243...">
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" style="margin-right:10px;"
                                        onclick="goToSection4()"><span id="spinner3-1"></span>&nbsp; <span
                                            id="indicator3-1">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="goBackToSection2()"><span id="spinner3-2"></span>&nbsp; <span
                                            id="indicator3-2">Pr&eacute;c&eacute;dent</span></button>
                                </div>
                            </div>
                            <!--contact end-->

                            <!--academic start-->
                            <div class="card-body row" id="section-4" hidden>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>Votre matricule actuel <code><b><span id="student_id_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-id-badge"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="student_id" id="student_id"
                                            placeholder="ex. NUC/2023/001" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>Campus <code><b><span id="camp_star"></span></b></code></label>
                                    <select class="form-control select2" style="width:100%" name="cump_id" id="cump_id"
                                        required>
                                        <option value=""></option>
                                        <?php
                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                            $sql_prg->execute();
                                            $i=1;
                                            while($progs_faculty=$sql_prg->fetch()){
                                                ?>
                                        <option value="<?php echo $progs_faculty['camp_id']; ?>">
                                            <?php echo $progs_faculty['camp_full_name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                    <span id="spinner0"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="prg">
                                    <label>Type de programme <code><b><span id="prg_star"></span></b></code></label>
                                    <select class="form-control select2" style="width:100%" name="prg_type"
                                        id="prg_type" required>
                                    </select>
                                    <span id="spinner00"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fct">
                                    <label>Facult&eacute;<code><b><span id="fac_star"></span></b></code></label>
                                    <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id"
                                        required>
                                    </select>
                                    <span id="spinner000"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">
                                    <label>D&eacute;partement <code><b><span id="dept_star"></span></b></code></label>
                                    <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id"
                                        required>
                                    </select>
                                    <span id="spinner0000"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="spec">
                                    <label>Sp&eacute;cialisation</label>
                                    <select class="form-control select2" style="width:100%" name="spcs_id" id="spcs_id"
                                        required>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="level">
                                    <label>Niveau</label>
                                    <select class="form-control select2" style="width:100%" name="level_id"
                                        id="level_id" required>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="mode">
                                    <label>Mode d'apprentissage</label>
                                    <select class="form-control select2" style="width:100%" name="mode" id="mode">
                                        <?php
                                            $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status='1'");
                                            $sql_mode->execute();
                                            $i=1;
                                            while($progs_mode=$sql_mode->fetch()){
                                                ?>
                                        <option value="<?php echo $progs_mode['prg_mode_id']; ?>">
                                            <?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" style="margin-right:10px;"
                                        onclick="goToSection5()"><span id="spinner4-1"></span>&nbsp; <span
                                            id="indicator4-1">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="goBackToSection3()"><span id="spinner4-2"></span>&nbsp; <span
                                            id="indicator4-2">Pr&eacute;c&eacute;dent</span></button>
                                </div>
                            </div>
                            <!--academic end-->

                            <!--additional start-->
                            <div class="card-body row" id="section-5" hidden>
                                <div class="form-group col-md-12"
                                    style="display: flex; flex-direction: row; flex-wrap: wrap; background-color: #EDFCF4; padding-top: 10px;">
                                    <h6 class="form-group col-12">Personne &agrave; contacter en cas d'urgence</h6>
                                    <div class="form-group col-md-6">
                                        <label>Nom</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="kin_name" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Relation</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="kin_relation"
                                                placeholder="ex. Oncle" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Adresse</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-map"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="kin_address"
                                                placeholder="ex. Goma" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>E-mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="email" name="kin_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>T&eacute;l&eacute;phone</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="kin_tel" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr />
                                <br>
                                <div class="form-group col-12 col-sm-5 col-lg-5">
                                    <label class="col-form-label col-md-12">Nombre de mati&egrave;res principales<span class="required"
                                            style="color:red">*</span></label>
                                    <div class="col-12">
                                        <input type="number" class="form-control" min="1" name="tcounter"
                                            oninput="generateCourses(this.value)" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-5 col-lg-5">
                                    <label class="col-form-label col-md-12">Avez-vous un sponsor ?<span
                                            style="color:red">*</span></label>
                                    <div class="col-12">
                                        <select class="form-control" id="has_sponsor" required>
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="yes">Oui</option>
                                            <option value="no">Non</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-5 col-lg-5" id="sponsor_div" style="display:none;">
                                    <label class="col-form-label col-md-12">Choisir le sponsor<span
                                            style="color:red">*</span></label>
                                    <div class="col-12">
                                        <select class="form-control select2" style="width:100%" name="spon_id"
                                            id="spon_id">
                                            <option value="" disabled selected hidden>Choisir un sponsor...</option>
                                            <?php
                                                $sql_spon = $conn->prepare("SELECT * FROM tbl_sponsor ORDER BY spon_full_name ASC");
                                                $sql_spon->execute();
                                                while($spon = $sql_spon->fetch()){
                                            ?>
                                            <option value="<?php echo $spon['spon_id']; ?>">
                                                <?php echo $spon['spon_full_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-7 col-lg-7" id="course-container"></div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span
                                            id="spinner"></span>&nbsp; <span id="indicator">Soumettre</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="goBackToSection4()"><span id="spinner5"></span>&nbsp; <span
                                            id="indicator5">Retour</span></button>
                                </div>
                            </div>
                            <!--additional end-->
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

                    <style>
                    .step-btn {
                        border-radius: 6px !important;
                    }
                    </style>

                    <?php  include'comb/orgin.php'; ?>
                    <?php  include'comb/coda.php'; ?>

                    <!--javascript-->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

                    <script>
                    // ==================== TOAST NOTIFICATIONS ====================
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

                    // ==================== SECTION NAVIGATION ====================
                    function showSection(sectionNum) {
                        for (var i = 1; i <= 5; i++) {
                            $('#section-' + i).attr('hidden', true);
                            $('#section-' + i + '-indicator').removeClass('btn-primary').addClass('btn-light');
                        }
                        $('#section-' + sectionNum).removeAttr('hidden');
                        $('#section-' + sectionNum + '-indicator').removeClass('btn-light').addClass('btn-primary');
                    }

                    // Section 1 -> 2
                    function goToSection2() {
                        var lname = $('#lname').val();
                        var fname = $('#fname').val();
                        var nid = $('#nid').val();
                        var gender = $('#gender').val();
                        var father_names = $('#father_names').val();
                        var mother_names = $('#mother_names').val();

                        if (!lname) {
                            pop_wrong('Veuillez entrer votre nom');
                            return;
                        }
                        if (!fname) {
                            pop_wrong('Veuillez entrer votre pr&eacute;nom');
                            return;
                        }
                        if (!nid) {
                            pop_wrong('Veuillez entrer votre carte d\'identit&eacute;/passeport');
                            return;
                        }
                        if (!gender) {
                            pop_wrong('Veuillez s&eacute;lectionner votre sexe');
                            return;
                        }
                        if (!father_names) {
                            pop_wrong("Veuillez entrer le nom du p&egrave;re");
                            return;
                        }
                        if (!mother_names) {
                            pop_wrong("Veuillez entrer le nom de la m&egrave;re");
                            return;
                        }

                        showSection(2);
                    }

                    // Section 2 -> 3
                    function goToSection3() {
                        var country = $('#country').val();
                        if (!country) {
                            pop_wrong('Veuillez s&eacute;lectionner votre pays de r&eacute;sidence');
                            return;
                        }
                        showSection(3);
                    }

                    // Section 3 -> 4
                    function goToSection4() {
                        var phone = $('#phone').val();
                        var email = $('#email').val();
                        if (!phone) {
                            pop_wrong('Veuillez entrer votre num&eacute;ro de t&eacute;l&eacute;phone');
                            return;
                        }
                        if (!email) {
                            pop_wrong('Veuillez entrer votre e-mail');
                            return;
                        }
                        showSection(4);
                    }

                    // Section 4 -> 5
                    function goToSection5() {
                        var student_id = $('#student_id').val();
                        var cump_id = $('#cump_id').val();
                        if (!student_id) {
                            pop_wrong('Veuillez entrer votre matricule');
                            return;
                        }
                        if (!cump_id) {
                            pop_wrong('Veuillez s&eacute;lectionner un campus');
                            return;
                        }
                        showSection(5);
                    }

                    // Back navigation
                    function goBackToSection1() {
                        showSection(1);
                    }

                    function goBackToSection2() {
                        showSection(2);
                    }

                    function goBackToSection3() {
                        showSection(3);
                    }

                    function goBackToSection4() {
                        showSection(4);
                    }

                    // ==================== CASCADING DROPDOWNS - ADDRESS ====================

                    // Country -> Provinces
                    $(document).on('change', '#country', function() {
                        var country = $(this).val();
                        $('#spinner_cntr').html('<i class="fas fa-spinner fa-spin"></i>');
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getProvinces',
                                country: country
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner_cntr').html('');
                                var html = '<option value="">--Choisir la Province--</option>';
                                if (response.status == 200 && response.data.length > 0) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.provincecode +
                                            '">' + item.provincename + '</option>';
                                    });
                                    $('#province_id').html(html);
                                    $('#prov').removeAttr('hidden');
                                    $('#district').attr('hidden', true);
                                    $('#village').attr('hidden', true);
                                } else {
                                    // Country without provinces - show street only
                                    $('#prov').attr('hidden', true);
                                    $('#district').attr('hidden', true);
                                    $('#village').removeAttr('hidden');
                                }
                            },
                            error: function() {
                                $('#spinner_cntr').html('');
                                pop_wrong('&Eacute;chec du chargement des provinces');
                            }
                        });
                    });

                    // Province -> Districts
                    $(document).on('change', '#province_id', function() {
                        var province_id = $(this).val();
                        $('#spinner_prov').html('<i class="fas fa-spinner fa-spin"></i>');
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getDistricts',
                                province_id: province_id
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner_prov').html('');
                                var html = '<option value="">--Choisir le District--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.districtcode +
                                            '">' + item.districtname + '</option>';
                                    });
                                    $('#district_id').html(html);
                                    $('#district').removeAttr('hidden');
                                    $('#village').removeAttr('hidden');
                                }
                            },
                            error: function() {
                                $('#spinner_prov').html('');
                                pop_wrong('&Eacute;chec du chargement des districts');
                            }
                        });
                    });

                    // ==================== CASCADING DROPDOWNS - ACADEMIC ====================

                    // Campus -> Program Types
                    $(document).on('change', '#cump_id', function() {
                        var camp_id = $(this).val();
                        $('#spinner0').html('<i class="fas fa-spinner fa-spin"></i>');
                        // Reset dependent dropdowns
                        $('#fct, #dept, #spec, #level, #mode').hide();
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getProgramTypes',
                                camp_id: camp_id
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner0').html('');
                                var html = '<option value="">--Choisir le Type de programme--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.prg_type_id +
                                            '">' + item.prg_type_full_name + '</option>';
                                    });
                                    $('#prg_type').html(html);
                                    $('#prg').show();
                                }
                            },
                            error: function() {
                                $('#spinner0').html('');
                                pop_wrong('&Eacute;chec du chargement des types de programme');
                            }
                        });
                    });

                    // Program Type -> Faculties + Levels
                    $(document).on('change', '#prg_type', function() {
                        var prg_type = $(this).val();
                        $('#spinner00').html('<i class="fas fa-spinner fa-spin"></i>');
                        // Reset dependent dropdowns
                        $('#dept, #spec').hide();

                        // Load Faculties
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getFaculties',
                                prg_type: prg_type
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner00').html('');
                                var html = '<option value="">--Choisir la Facult&eacute;--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.fac_id + '">' +
                                            item.fac_full_name + '</option>';
                                    });
                                    $('#fac_id').html(html);
                                    $('#fct').show();
                                }
                            },
                            error: function() {
                                $('#spinner00').html('');
                                pop_wrong('&Eacute;chec du chargement des facult&eacute;s');
                            }
                        });

                        // Load Levels
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getLevels',
                                prg_type: prg_type
                            },
                            dataType: 'json',
                            success: function(response) {
                                var html = '<option value="">--Choisir le Niveau--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.level_id + '">' +
                                            item.level_full_name + '</option>';
                                    });
                                    $('#level_id').html(html);
                                    $('#level').show();
                                    $('#mode').show();
                                }
                            }
                        });
                    });

                    // Faculty -> Departments
                    $(document).on('change', '#fac_id', function() {
                        var fac_id = $(this).val();
                        var prg_type = $('#prg_type').val();
                        $('#spinner000').html('<i class="fas fa-spinner fa-spin"></i>');
                        $('#spec').hide();
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getDepartments',
                                fac_id: fac_id,
                                prg_type: prg_type
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner000').html('');
                                var html = '<option value="">--Choisir le D&eacute;partement--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.dept_id + '">' +
                                            item.dept_full_name + '</option>';
                                    });
                                    $('#dept_id').html(html);
                                    $('#dept').show();
                                }
                            },
                            error: function() {
                                $('#spinner000').html('');
                                pop_wrong('&Eacute;chec du chargement des d&eacute;partements');
                            }
                        });
                    });

                    // Department -> Specializations
                    $(document).on('change', '#dept_id', function() {
                        var dept_id = $(this).val();
                        var prg_type = $('#prg_type').val();
                        $('#spinner0000').html('<i class="fas fa-spinner fa-spin"></i>');
                        $.ajax({
                            url: '/files/continuing/apply_continuing_controller.php',
                            type: 'POST',
                            data: {
                                action: 'getSpecializations',
                                dept_id: dept_id,
                                prg_type: prg_type
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinner0000').html('');
                                var html = '<option value="">--Choisir la Sp&eacute;cialisation--</option>';
                                if (response.status == 200) {
                                    $.each(response.data, function(i, item) {
                                        html += '<option value="' + item.splz_id + '">' +
                                            item.splz_full_name + '</option>';
                                    });
                                    $('#spcs_id').html(html);
                                    $('#spec').show();
                                }
                            },
                            error: function() {
                                $('#spinner0000').html('');
                                pop_wrong('&Eacute;chec du chargement des sp&eacute;cialisations');
                            }
                        });
                    });

                    // ==================== PRINCIPAL PASSES GENERATOR ====================
                    function generateCourses(count) {
                        var container = document.getElementById('course-container');
                        container.innerHTML = '';
                        count = parseInt(count);
                        if (isNaN(count) || count < 1 || count > 10) return;
                        for (var i = 1; i <= count; i++) {
                            container.innerHTML += '<div class="form-group col-12 col-sm-6">' +
                                '<label>Mati&egrave;re ' + i + '</label>' +
                                '<input type="text" class="form-control" name="subject_' + i +
                                '" placeholder="Nom de la mati&egrave;re" required>' +
                                '</div>' +
                                '<div class="form-group col-12 col-sm-6">' +
                                '<label>Cote ' + i + '</label>' +
                                '<input type="text" class="form-control" name="grade_' + i +
                                '" placeholder="Cote" required>' +
                                '</div>';
                        }
                    }

                    // ==================== SPONSOR TOGGLE ====================
                    $(document).on('change', '#has_sponsor', function() {
                        if ($(this).val() == 'yes') {
                            $('#sponsor_div').show();
                            $('#spon_id').attr('required', true);
                        } else {
                            $('#sponsor_div').hide();
                            $('#spon_id').removeAttr('required');
                            $('#spon_id').val('');
                        }
                    });

                    // ==================== FORM SUBMISSION ====================
                    $(document).ready(function() {
                        $("#apply_continuing").submit(function(e) {
                            e.preventDefault();

                            var formData = $(this).serialize();
                            $('#sBtn').attr('disabled', true);
                            $('#spinner').html('<i class="fas fa-spinner fa-spin"></i>');
                            $('#indicator').html('Envoi en cours...');

                            $.ajax({
                                url: '/files/continuing/apply_continuing_controller.php',
                                type: 'POST',
                                data: formData,
                                dataType: 'json',
                                success: function(response) {
                                    $('#sBtn').attr('disabled', false);
                                    $('#spinner').html('');
                                    $('#indicator').html('Soumettre');

                                    if (response.status == 200) {
                                        pop_up_success(response.message);
                                        $('#apply_continuing')[0].reset();
                                        showSection(1);
                                        // Reset hidden sections
                                        $('#prg, #fct, #dept, #spec, #level, #mode').hide();
                                        $('#prov, #district, #village').attr('hidden',
                                            true);
                                        $('#course-container').html('');
                                        $('#sponsor_div').hide();
                                    } else {
                                        pop_wrong(response.message);
                                    }
                                },
                                error: function(xhr) {
                                    $('#sBtn').attr('disabled', false);
                                    $('#spinner').html('');
                                    $('#indicator').html('Soumettre');
                                    pop_wrong('Une erreur est survenue. Veuillez r&eacute;essayer.');
                                }
                            });
                        });
                    });
                    </script>