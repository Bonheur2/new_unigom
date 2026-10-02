<?php include '../../infrom.php'; ?>
<?php include '../../bar.php'; ?>
<?php include '../../subbar.php'; ?>
<?php require '../../meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-9" style="margin:auto;">
                    <form id="changefaculty_form" onsubmit="return false;">
                        <div class="card">
                            <div class="card-header row"
                                style="display:flex; flex-direction:column; align-items:center;">
                                <h4 style="text-align:center;margin:0 0 4px;">Bulletin de Changement de Facult&eacute;
                                </h4>
                                <p style="text-align:center;color:#6c757d;margin:0 0 14px;font-size:13px;">Ann&eacute;e
                                    Acad&eacute;mique 2024-2025</p>
                                <div style="display:flex; flex-wrap:wrap; justify-content:center; width:100%; gap:6px;">
                                    <button type="button" id="cf-section-1-indicator"
                                        class="btn btn-primary cf-step-btn"
                                        style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                            class="badge badge-transparent">1</span> &nbsp;Identit&eacute;</button>
                                    <button type="button" id="cf-section-2-indicator" class="btn btn-light cf-step-btn"
                                        style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                            class="badge badge-transparent">2</span> &nbsp;&Eacute;tudes
                                        secondaires</button>
                                    <button type="button" id="cf-section-3-indicator" class="btn btn-light cf-step-btn"
                                        style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                            class="badge badge-transparent">3</span> &nbsp;&Eacute;tudes apr&egrave;s
                                        les Humanit&eacute;s</button>
                                    <button type="button" id="cf-section-4-indicator" class="btn btn-light cf-step-btn"
                                        style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                            class="badge badge-transparent">4</span> &nbsp;Inscription
                                        sollicit&eacute;e</button>
                                    <button type="button" id="cf-section-5-indicator" class="btn btn-light cf-step-btn"
                                        style="width:auto; white-space:nowrap; margin-bottom:0;"><span
                                            class="badge badge-transparent">5</span> &nbsp;Compl&eacute;ments &amp;
                                        Dispenses</button>
                                </div>
                            </div>



                            <!-- ============ SECTION 1 : IDENTITE ============ -->
                            <div class="card-body row" id="cf-section-1">


                                <div class="form-group col-12 col-sm-4">
                                    <label>Nom <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="nom" placeholder="e.g. KAMARA"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Post-nom <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="postnom" placeholder="e.g. MUKA"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Pr&eacute;nom <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="prenom" placeholder="e.g. Peter"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Lieu de naissance <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="lieu_naissance"
                                            placeholder="e.g. Goma" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Date de naissance <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                        <input type="date" class="form-control" name="date_naissance" required>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-4">
                                    <label>Sexe <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-venus-mars"></i></div>
                                        </div>
                                        <select name="sexe" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">F&eacute;minin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Groupe sanguin</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-tint"></i></div>
                                        </div>
                                        <select name="groupe_sanguin" class="form-control select2">
                                            <option value="" selected>Inconnu</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>&Eacute;tat-civil <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-heart"></i></div>
                                        </div>
                                        <select name="etat_civil" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="Celibataire">C&eacute;libataire</option>
                                            <option value="Marie">Mari&eacute;(e)</option>
                                            <option value="Divorce">Divorc&eacute;(e)</option>
                                            <option value="Veuf">Veuf / Veuve</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Nationalit&eacute; <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-globe-americas"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="nationalite"
                                            placeholder="e.g. Congolaise" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Confession religieuse</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-place-of-worship"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="confession_religieuse"
                                            placeholder="e.g. Catholique">
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Nom du p&egrave;re <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-male"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="nom_pere" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Nom de la m&egrave;re <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-female"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="nom_mere" required>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-4">
                                    <label>Province d'origine des parents</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="province_parents">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>District</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="district">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Territoire d'origine</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="territoire_origine">
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Adresse du candidat (Quartier, Avenue et N&deg;)
                                        <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-home"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="adresse_candidat" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>N&deg; de t&eacute;l&eacute;phone du candidat <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                        <input type="text" class="form-control phone-number" name="telephone_candidat"
                                            required>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cfGoTo(2)"><span
                                            id="cf-spinner-1"></span>&nbsp; <span
                                            id="cf-indicator-1">Suivant</span></button>
                                </div>
                            </div>

                            <!-- ============ SECTION 2 : ETUDES SECONDAIRES ============ -->
                            <div class="card-body row" id="cf-section-2" hidden>


                                <div class="form-group col-12 col-sm-6">
                                    <label>Nom de l'&eacute;cole secondaire fr&eacute;quent&eacute;e
                                        <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-school"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="nom_ecole" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label>Province de l'&eacute;cole</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="province_ecole">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-3">
                                    <label>Territoire de l'&eacute;cole</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-map"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="territoire_ecole">
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-6">
                                    <label>Section suivie aux Humanit&eacute;s <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="section_humanites"
                                            placeholder="e.g. Scientifique, Commerciale..." required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Statut de l'&eacute;cole secondaire</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-landmark"></i></div>
                                        </div>
                                        <select name="statut_ecole" class="form-control select2">
                                            <option value="" disabled selected hidden>Choisir...</option>
                                            <option value="Public">Public</option>
                                            <option value="Protestant">Protestant</option>
                                            <option value="Catholique">Catholique</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-sm-4">
                                    <label>Ann&eacute;e d'obtention du Dipl&ocirc;me d'&Eacute;tat
                                        <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
                                        </div>
                                        <input type="number" class="form-control" name="annee_diplome" min="1970"
                                            max="2100" placeholder="e.g. 2022" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Pourcentage du Dipl&ocirc;me</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-percent"></i></div>
                                        </div>
                                        <input type="number" step="0.01" min="0" max="100" class="form-control"
                                            name="pourcentage_diplome">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Num&eacute;ro du Dipl&ocirc;me d'&Eacute;tat <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-hashtag"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="numero_diplome" required>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cfGoTo(3)"><span
                                            id="cf-spinner-2"></span>&nbsp; <span
                                            id="cf-indicator-2">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="cfGoTo(1)">Retour</button>
                                </div>
                            </div>

                            <!-- ============ SECTION 3 : ETUDES APRES LES HUMANITES ============ -->
                            <div class="card-body row" id="cf-section-3" hidden>


                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered" id="cf-etudes-table">
                                        <thead>
                                            <tr>
                                                <th>Ann&eacute;e</th>
                                                <th>&Eacute;tablissement</th>
                                                <th>Promotion</th>
                                                <th>Pourcentage</th>
                                                <th>Mention</th>
                                                <th>Dec. Jury</th>
                                                <th>Cr&eacute;dits non capitalis&eacute;s</th>
                                                <th>Session</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cf-etudes-tbody">
                                            <!-- rows injected by JS -->
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="cfAddEtudeRow()"><i class="fas fa-plus"></i> Ajouter une
                                        ann&eacute;e</button>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12"
                                    style="margin-top:16px;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cfGoTo(4)"><span
                                            id="cf-spinner-3"></span>&nbsp; <span
                                            id="cf-indicator-3">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="cfGoTo(2)">Retour</button>
                                </div>
                            </div>

                            <!-- ============ SECTION 4 : INSCRIPTION SOLLICITEE ============ -->
                            <div class="card-body row" id="cf-section-4" hidden>


                                <div class="form-group col-12 col-sm-4">
                                    <label>Domaine <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sitemap"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="domaine" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>D&eacute;partement <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-sitemap"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="departement" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4">
                                    <label>Promotion sollicit&eacute;e <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-layer-group"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="promotion_sollicitee"
                                            placeholder="e.g. G2" required>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="cf_certif"
                                            name="certification" required>
                                        <label class="custom-control-label" for="cf_certif">Je certifie sur mon honneur
                                            que les renseignements ci-haut fournis sont exacts.</label>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>Date <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                        <input type="date" class="form-control" name="date_signature" required>
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12"
                                    style="margin-top:10px;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cfGoTo(5)"><span
                                            id="cf-spinner-4"></span>&nbsp; <span
                                            id="cf-indicator-4">Suivant</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="cfGoTo(3)">Retour</button>
                                </div>
                            </div>

                            <!-- ============ SECTION 5 : COMPLEMENTS & DISPENSES ============ -->
                            <div class="card-body row" id="cf-section-5" hidden>


                                <div class="form-group col-12 col-sm-6">
                                    <label>Compl&eacute;ments</label>
                                    <textarea class="form-control" name="complements" rows="5"
                                        placeholder="Mati&egrave;res/cr&eacute;dits &agrave; compl&eacute;ter..."></textarea>
                                </div>
                                <div class="form-group col-12 col-sm-6">
                                    <label>Dispenses</label>
                                    <textarea class="form-control" name="dispenses" rows="5"
                                        placeholder="Mati&egrave;res/cr&eacute;dits dispens&eacute;s..."></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info" role="alert" style="font-size:13px; margin-top:10px;">
                                        <i class="fas fa-info-circle"></i>
                                        Prochaine &eacute;tape : votre dossier sera examin&eacute; par le Conseil
                                        Facultaire, puis soumis &agrave; l'avis du Comit&eacute; de Gestion.
                                    </div>
                                </div>

                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12"
                                    style="margin-top:10px;">
                                    <button type="button" class="btn btn-primary btn-sm" id="cf-submit-btn"
                                        onclick="cfSubmitPreview()"><i class="fas fa-check"></i>&nbsp;
                                        Soumettre</button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="cfGoTo(4)">Retour</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include '../../comb/orgin.php'; ?>
<?php include '../../comb/coda.php'; ?>

<style>
.cf-step-btn {
    border-radius: 6px !important;
}

.section-title {
    font-weight: 700;
    color: var(--brand, #203A72);
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 8px;
    margin-bottom: 4px;
}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/form-autosave.js"></script>
<script>
var cfCurrentSection = 1;
var cfEtudeRowCount = 0;

function cfValidateSection(n) {
    var valid = true;
    var $section = $('#cf-section-' + n);
    $section.find('[required]').each(function() {
        var $f = $(this);
        var ok = $f.attr('type') === 'checkbox' ? $f.is(':checked') : !!$f.val();
        if (!ok) {
            valid = false;
            $f.addClass('is-invalid');
        } else {
            $f.removeClass('is-invalid');
        }
    });
    if (!valid) {
        pop_wrong("Veuillez remplir tous les champs obligatoires (*)");
    }
    return valid;
}

function cfGoTo(n) {
    if (n > cfCurrentSection && !cfValidateSection(cfCurrentSection)) {
        return;
    }

    var $spinner = $('#cf-spinner-' + cfCurrentSection);
    var $indicator = $('#cf-indicator-' + cfCurrentSection);
    if ($spinner.length) {
        $spinner.html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    }
    if ($indicator.length) {
        $indicator.html("Chargement...");
    }

    setTimeout(function() {
        if ($spinner.length) {
            $spinner.fadeOut('fast');
        }
        if ($indicator.length) {
            $indicator.html("Suivant");
        }

        cfSetSection(n);
        $('html, body').animate({
            scrollTop: $('.card-header').offset().top - 20
        }, 300);
    }, 350);
}

// Shows section n immediately, no validation/animation - used by cfGoTo()
// above and to restore whichever step the visitor was on after a reload.
function cfSetSection(n) {
    $('#cf-section-' + cfCurrentSection).attr('hidden', true);
    $('#cf-section-' + cfCurrentSection + '-indicator').removeClass('btn-primary').addClass('btn-light');

    $('#cf-section-' + n).attr('hidden', false);
    $('#cf-section-' + n + '-indicator').removeClass('btn-light').addClass('btn-primary');

    cfCurrentSection = n;
    if (cfAutosave) {
        cfAutosave.save();
    }
}

function cfAddEtudeRow() {
    cfEtudeRowCount++;
    var row = $('<tr></tr>');
    row.html(
        '<td><input type="number" class="form-control form-control-sm" name="etude_annee[]" placeholder="2023"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_etablissement[]"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_promotion[]"></td>' +
        '<td><input type="number" step="0.01" class="form-control form-control-sm" name="etude_pourcentage[]"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_mention[]"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_decjury[]"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_credits_non_cap[]"></td>' +
        '<td><input type="text" class="form-control form-control-sm" name="etude_session[]"></td>' +
        '<td><button type="button" class="btn btn-light btn-sm" onclick="$(this).closest(\'tr\').remove();"><i class="fas fa-trash text-danger"></i></button></td>'
    );
    $('#cf-etudes-tbody').append(row);
}

var cfAutosave = null;

function cfSubmitPreview() {
    if (!cfValidateSection(5)) {
        return;
    }
    pop_up_success("Aper&ccedil;u uniquement : la soumission de ce formulaire n'est pas encore connect&eacute;e.");
    if (cfAutosave) {
        cfAutosave.clear();
    }
}

$(document).ready(function() {
    cfAddEtudeRow();

    cfAutosave = enableFormAutosave('#changefaculty_form', 'unigom_cf_draft_v1', {
        rowAdders: {
            etude: cfAddEtudeRow
        },
        currentStepGetter: function() {
            return cfCurrentSection;
        },
        onRestoreStep: function(step) {
            cfSetSection(step);
        }
    });
});
</script>