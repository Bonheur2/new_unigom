<?php
    $canManage = ($role_id == 18 || $role_id == 2);

    // project root as a URL path ("" live, "/academic" under XAMPP) so stored logo paths resolve in both places
    $tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
    $tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));

    if(!function_exists('tv_initials')){
        function tv_initials($name){
            $words = preg_split('/\s+/', trim($name));
            $out = '';
            foreach(array_slice($words, 0, 2) as $w){
                $out .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            return $out;
        }
    }

    $sql = $conn->prepare("SELECT tbl_university.id, full_name, short_name, country, email, website, phone, location, po_box, logo, reg_date,
                                  (SELECT COUNT(*) FROM tbl_campus WHERE tbl_campus.university_id = tbl_university.id) AS campuses
                           FROM tbl_university ORDER BY id DESC");
    $sql->execute();
    $universities = $sql->fetchAll();
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Universités</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Université</a></div>
            </div>
        </div>

        <!-- Formulaire nouvelle université -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouvelle université</h4>
                <form id="save_university" action="save_university" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" id="full_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Sigle</label>
                            <input type="text" class="form-control" id="short_name" placeholder="Sigle">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Pays</label>
                            <input type="text" class="form-control" id="country" placeholder="Pays">
                        </div>
                        <div class="form-group col-md-3">
                            <label>E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="E-mail">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Site web</label>
                            <input type="text" class="form-control" id="website" placeholder="https://...">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Téléphone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Téléphone">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Adresse</label>
                            <input type="text" class="form-control" id="location" placeholder="Adresse">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Boîte postale</label>
                            <input type="text" class="form-control" id="po_box" placeholder="B.P.">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Date d'enregistrement</label>
                            <input type="date" class="form-control" id="reg_date">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Logo</label>
                            <input type="file" class="form-control" id="logo" accept="image/*">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span
                                    id="indicator">Enregistrer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Universités enregistrées <span
                        class="tv-pill-count ml-2"><?php echo count($universities); ?></span></h4>
                <?php if($canManage): ?>
                <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouvelle
                    université</button>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="tv-table university_table">
                    <thead>
                        <tr>
                            <th>Université</th>
                            <th>Pays</th>
                            <th>E-mail</th>
                            <th>Téléphone</th>
                            <!-- <th>Campus</th> -->
                            <?php if($canManage): ?><th class="tv-right">Actions</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($universities as $row): ?>
                        <tr>
                            <td>
                                <div class="tv-member">
                                    <?php if(!empty($row['logo'])): ?>
                                    <img class="tv-logo"
                                        src="<?php echo htmlspecialchars((strpos($row['logo'], '/') === 0 ? $tv_base_url : '').$row['logo']); ?>"
                                        alt="logo">
                                    <?php else: ?>
                                    <span
                                        class="tv-avatar"><?php echo htmlspecialchars(tv_initials($row['full_name'])); ?></span>
                                    <?php endif; ?>
                                    <span>
                                        <?php echo htmlspecialchars($row['full_name']); ?>
                                        <?php if($row['short_name'] != ''): ?><span
                                            class="tv-tag blue ml-1"><?php echo htmlspecialchars($row['short_name']); ?></span><?php endif; ?>
                                    </span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['country']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <!-- <td><?php echo $row['campuses']; ?></td> -->
                            <?php if($canManage): ?>
                            <td class="tv-right">
                                <div class="tv-row-actions">
                                    <button type="button" data-id="<?php echo $row['id']; ?>" class="tv-icon-btn edit" title="Modifier">
                                        <span id="spinner4_<?php echo $row['id']; ?>"></span><i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" data-id="<?php echo $row['id']; ?>" class="tv-icon-btn danger del" title="Supprimer">
                                        <span id="spinner3_<?php echo $row['id']; ?>"></span><i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!--update modal-->
    <form action="update_form" method="POST" id="update_form" enctype="multipart/form-data">
        <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modification de <span id="f_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="e_id" name="e_id">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" id="e_full_name" placeholder="Nom complet" required>
                        </div>
                        <div class="form-group">
                            <label>Sigle</label>
                            <input type="text" class="form-control" id="e_short_name" placeholder="Sigle">
                        </div>
                        <div class="form-group">
                            <label>Pays</label>
                            <input type="text" class="form-control" id="e_country" placeholder="Pays">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" id="e_email" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label>Site web</label>
                            <input type="text" class="form-control" id="e_website" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" class="form-control" id="e_phone" placeholder="Téléphone">
                        </div>
                        <div class="form-group">
                            <label>Adresse</label>
                            <input type="text" class="form-control" id="e_location" placeholder="Adresse">
                        </div>
                        <div class="form-group">
                            <label>Boîte postale</label>
                            <input type="text" class="form-control" id="e_po_box" placeholder="B.P.">
                        </div>
                        <div class="form-group">
                            <label>Date d'enregistrement</label>
                            <input type="date" class="form-control" id="e_reg_date">
                        </div>
                        <div class="form-group">
                            <label>Logo</label>
                            <div id="e_logo_preview" class="mb-2"></div>
                            <input type="file" class="form-control" id="e_logo" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span
                                id="indicator2">Enregistrer les modifications</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal-->
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
var BASE_URL = <?php echo json_encode($tv_base_url); ?>;

$(document).ready(function() {
    $('.university_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Tout"]
            ],
            "iDisplayLength": 5,
            "autoWidth": false,
            "order": [],
            "language": {
                "lengthMenu": "Afficher _MENU_ éléments",
                "search": "Rechercher :",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 élément",
                "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "emptyTable": "Aucune université enregistrée",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                }
            }
        });
    });

    // open / close the new university form
    $('#tv-new-btn').on('click', function() {
        $('#mycard-collapse').collapse('toggle');
    });

    //save university
    $("#save_university").submit(function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('full_name', $("#full_name").val());
        formData.append('short_name', $("#short_name").val());
        formData.append('country', $("#country").val());
        formData.append('email', $("#email").val());
        formData.append('website', $("#website").val());
        formData.append('phone', $("#phone").val());
        formData.append('location', $("#location").val());
        formData.append('po_box', $("#po_box").val());
        formData.append('reg_date', $("#reg_date").val());
        formData.append('logo', $("#logo")[0].files[0]);
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Enregistrement...");
        $.ajax({
            url: "../new_files/University/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                if (data.status == 200) {
                    $('#save_university')[0].reset();
                    pop_up_success(data.message);
                    location.reload();
                }
                if (data.status == 401) {
                    pop_wrong(data.message);
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Enregistrer");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    // delete university
    $(document).on('click', '.del', function() {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Êtes-vous sûr ?",
            text: "Vous êtes sur le point de supprimer cette université.",
            icon: "warning",
            buttons: ["Annuler", "Supprimer"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_' + data_id).html(
                    "<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/University/controller.php",
                    data: getData,
                    dataType: "json",
                    success: function(data) {
                        $('#spinner3_' + data_id).fadeOut('fast');
                        if (data.status == 500 || data.status == 401) {
                            pop_wrong(data.message);
                        } else if (data.status == 200) {
                            pop_up_success(data.message);
                            location.reload();
                        }
                    },
                    error: function(error) {
                        $('#spinner3_' + data_id).fadeOut('fast');
                        pop_wrong("Une erreur s'est produite !");
                    }
                });
            } else {
                swal("Opération annulée");
            }
        });
    });

    //pre-update View
    $(document).on('click', '.edit', function() {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'view'
        };
        $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn(
            'fast');
        $.ajax({
            type: "POST",
            url: "../new_files/University/controller.php",
            data: getData,
            dataType: "json",
            success: function(data) {
                $('#spinner4_' + data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_full_name").val(data.full_name);
                $("#e_short_name").val(data.short_name);
                $("#e_country").val(data.country);
                $("#e_email").val(data.email);
                $("#e_website").val(data.website);
                $("#e_phone").val(data.phone);
                $("#e_location").val(data.location);
                $("#e_po_box").val(data.po_box);
                $("#e_reg_date").val(data.reg_date ? String(data.reg_date).substring(0,
                    10) : '');
                $("#f_name").text(data.full_name);
                var $preview = $("#e_logo_preview").empty();
                if (data.logo) {
                    var src = (data.logo.charAt(0) === '/' ? BASE_URL : '') + data.logo;
                    $preview.append($(
                            '<img width="60" height="60" style="object-fit:contain">')
                        .attr('src', src));
                }
                $('#updateModal').modal('show');
            },
            error: function(error) {
                $('#spinner4_' + data_id).fadeOut('fast');
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

    //update university
    $("#update_form").submit(function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('full_name', $("#e_full_name").val());
        formData.append('short_name', $("#e_short_name").val());
        formData.append('country', $("#e_country").val());
        formData.append('email', $("#e_email").val());
        formData.append('website', $("#e_website").val());
        formData.append('phone', $("#e_phone").val());
        formData.append('location', $("#e_location").val());
        formData.append('po_box', $("#e_po_box").val());
        formData.append('reg_date', $("#e_reg_date").val());
        if ($("#e_logo")[0].files[0]) {
            formData.append('logo', $("#e_logo")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Enregistrement...");
        $.ajax({
            url: "../new_files/University/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                if (data.status == 200) {
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    location.reload();
                }
                if (data.status == 401) {
                    pop_wrong(data.message);
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Enregistrer les modifications");
                pop_wrong("Une erreur s'est produite !");
            }
        });
    });

});

function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Erreur',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
    });
}
</script>