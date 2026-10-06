<?php
    // project root as a URL path ("" live, "/academic" under XAMPP) so stored PDF paths resolve in both places
    $tv_doc_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
    $tv_base_url = str_replace($tv_doc_root, '', str_replace('\\', '/', dirname(__DIR__, 2)));
    $fileUrl = function($path) use ($tv_base_url){
        return (strpos((string)$path, '/') === 0 ? $tv_base_url : '').$path;
    };

    $sql = $conn->prepare("SELECT * FROM tbl_choice_structure ORDER BY structure_id DESC");
    $sql->execute();
    $docs = $sql->fetchAll(PDO::FETCH_ASSOC);

    $active = null;
    foreach($docs as $d){ if($d['status'] == 1){ $active = $d; break; } }
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Structure des choix</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="#">Structure des choix</a></div>
            </div>
        </div>

        <!-- Formulaire nouveau document -->
        <div class="collapse" id="mycard-collapse">
            <div class="tv-card">
                <h4 class="tv-card-title">Nouveau document</h4>
                <form id="save_structure" action="save_structure" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Titre</label>
                            <input type="text" class="form-control" id="title"
                                placeholder="Ex : Structure des choix 2026" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Description <small class="text-muted">(facultatif)</small></label>
                            <input type="text" class="form-control" id="description"
                                placeholder="Ex : Liste des filières et départements">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Fichier PDF</label>
                            <input type="file" class="form-control" id="structure_file" accept=".pdf" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="custom-switch mt-2 pl-0">
                                <input type="checkbox" class="custom-switch-input" id="make_active" checked>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Rendre actif immédiatement (remplace le document
                                    actif)</span>
                            </label>
                        </div>
                        <div class="form-group col-md-6 text-md-right">
                            <button type="submit" class="tv-btn tv-btn-accent"><span id="spinner"></span><span
                                    id="indicator">Téléverser</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- the document applicants see -->

        <div class="tv-card">
            <div class="tv-list-head">
                <h4 class="tv-card-title">Documents enregistrés <span
                        class="tv-pill-count ml-1"><?php echo count($docs); ?></span></h4>
                <button type="button" class="tv-btn tv-btn-accent" id="tv-new-btn"><i class="fas fa-plus"></i> Nouveau
                    document</button>
            </div>
            <p class="tv-card-sub" style="margin:-8px 0 16px">Un seul document peut être actif à la fois : en activer un
                désactive les autres.</p>
            <div class="table-responsive">
                <table class="tv-table" id="structure_table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Téléversé le</th>
                            <th>État</th>
                            <th class="tv-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($docs as $row): ?>
                        <tr data-row-id="<?php echo $row['structure_id']; ?>">
                            <td>
                                <div class="tv-member">
                                    <span class="tv-avatar"><i class="far fa-file-pdf"></i></span>
                                    <span><?php echo htmlspecialchars($row['title']); ?></span>
                                </div>
                            </td>
                            <td><?php echo $row['description'] != '' ? htmlspecialchars($row['description']) : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td style="white-space:nowrap">
                                <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            <td class="col-state">
                                <?php if($row['status'] == 1): ?>
                                <span class="tv-tag green">Actif</span>
                                <?php else: ?>
                                <span class="tv-tag red">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="tv-right">
                                <div class="tv-row-actions">
                                    <a href="<?php echo htmlspecialchars($fileUrl($row['file_path'])); ?>"
                                        target="_blank" class="tv-icon-btn" title="Voir le PDF"><i
                                            class="far fa-eye"></i></a>
                                    <?php if($row['status'] != 1): ?>
                                    <button type="button" data-id="<?php echo $row['structure_id']; ?>"
                                        class="tv-btn tv-btn-outline activate cs-activate">
                                        <span id="spinner_a_<?php echo $row['structure_id']; ?>"></span>Activer
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" data-id="<?php echo $row['structure_id']; ?>"
                                        class="tv-icon-btn danger del" title="Supprimer">
                                        <span id="spinner_d_<?php echo $row['structure_id']; ?>"></span><i
                                            class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($docs) === 0): ?>
                        <tr>
                            <td colspan="5" class="tv-empty">Aucun document téléversé. Utilisez « Nouveau document ».
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<style>
.tv-page .cs-active {
    align-items: center
}

.tv-page .cs-active .tv-kpi-label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--tv-muted);
    margin: 0 0 4px
}

.tv-page .cs-active .cs-title {
    font-size: 20px
}

.tv-page .cs-activate {
    padding: 5px 12px;
    font-size: 13px;
    border-radius: 0
}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {

    $('#tv-new-btn').on('click', function() {
        $('#mycard-collapse').collapse('toggle');
    });

    $("#save_structure").submit(function(e) {
        e.preventDefault();

        var fileInput = document.getElementById('structure_file');
        if (!fileInput.files.length) {
            pop_wrong("Veuillez choisir un fichier PDF");
            return;
        }

        var formData = new FormData();
        formData.append('action', 'save');
        formData.append('title', $("#title").val());
        formData.append('description', $("#description").val());
        formData.append('make_active', $("#make_active").is(':checked') ? 1 : 0);
        formData.append('structure_file', fileInput.files[0]);

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Envoi...");

        $.ajax({
            url: "../new_files/Choice_Structure/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Téléverser");
                if (data.status == 200) {
                    pop_up_success(data.message);
                    // A new row changes which one is active, so reload to keep
                    // the badges and Activer buttons consistent.
                    setTimeout(function() {
                        location.reload();
                    }, 900);
                } else {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Téléverser");
                pop_wrong("Une erreur est survenue !");
            }
        });
    });

    $(document).on('click', '.activate', function() {
        var id = $(this).data('id');
        swal({
            title: "Activer ce document ?",
            text: "Il remplacera le document actuellement vu par les candidats.",
            icon: "warning",
            buttons: ["Annuler", "Activer"]
        }).then(function(ok) {
            if (!ok) {
                return;
            }
            $('#spinner_a_' + id).html("<img src='../../img/ajax_loader.gif' width='15'> ")
                .fadeIn('fast');
            $.ajax({
                url: "../new_files/Choice_Structure/controller.php",
                type: "POST",
                data: {
                    action: 'activate',
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#spinner_a_' + id).fadeOut('fast');
                    if (data.status == 200) {
                        pop_up_success(data.message);
                        setTimeout(function() {
                            location.reload();
                        }, 700);
                    } else {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    $('#spinner_a_' + id).fadeOut('fast');
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
    });

    $(document).on('click', '.del', function() {
        var id = $(this).data('id');

        swal({
            title: "Êtes-vous sûr ?",
            text: "Ce document sera définitivement supprimé.",
            icon: "warning",
            buttons: ["Annuler", "Supprimer"],
            dangerMode: true
        }).then(function(willDelete) {
            if (!willDelete) {
                return;
            }

            $('#spinner_d_' + id).html("<img src='../../img/ajax_loader.gif' width='15'>")
                .fadeIn('fast');
            $.ajax({
                url: "../new_files/Choice_Structure/controller.php",
                type: "POST",
                data: {
                    action: 'delete',
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $('#spinner_d_' + id).fadeOut('fast');
                    if (data.status == 200) {
                        pop_up_success(data.message);
                        // the header card and count change too, so reload
                        setTimeout(function() {
                            location.reload();
                        }, 700);
                    } else {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    $('#spinner_d_' + id).fadeOut('fast');
                    pop_wrong("Une erreur est survenue !");
                }
            });
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