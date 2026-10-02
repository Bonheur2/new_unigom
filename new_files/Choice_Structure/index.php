<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Structure des choix</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Structure des choix</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light">
                                <i class="fas fa-info-circle"></i>
                                Le document actif est celui que les candidats verront sur l'&eacute;tape
                                <strong>Choix acad&eacute;mique</strong> du formulaire de candidature.
                                Un seul document peut &ecirc;tre actif &agrave; la fois.
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Nouveau document</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_structure" action="save_structure" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Titre</label>
                                                    <input type="text" class="form-control" id="title" placeholder="e.g. Structure des choix 2026" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Description <code>(facultatif)</code></label>
                                                    <input type="text" class="form-control" id="description" placeholder="e.g. Liste des fili&egrave;res et d&eacute;partements">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Fichier PDF</label>
                                                    <input type="file" class="form-control" id="structure_file" accept=".pdf" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="custom-switch mt-4">
                                                        <input type="checkbox" class="custom-switch-input" id="make_active" checked>
                                                        <span class="custom-switch-indicator"></span>
                                                        <span class="custom-switch-description">Rendre actif imm&eacute;diatement</span>
                                                    </label>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block"><span id="spinner"></span>&nbsp;<span id="indicator">T&eacute;l&eacute;verser</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Documents enregistr&eacute;s</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="structure_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Titre</th>
                                                    <th>Description</th>
                                                    <th>T&eacute;l&eacute;vers&eacute; le</th>
                                                    <th>&Eacute;tat</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT * FROM tbl_choice_structure ORDER BY structure_id DESC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                                <tr data-row-id="<?php echo $row['structure_id']; ?>">
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                                    <td class="col-state">
                                                        <?php if($row['status'] == 1): ?>
                                                        <span class="badge badge-success">Actif</span>
                                                        <?php else: ?>
                                                        <span class="badge badge-light">Inactif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn btn-icon btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>&nbsp;Voir
                                                            </a>
                                                            <?php if($row['status'] != 1): ?>
                                                            <button type="button" data-id="<?php echo $row['structure_id']; ?>" class="btn btn-icon btn-primary btn-sm activate" style="margin-left:6px;">
                                                                <span id="spinner_a_<?php echo $row['structure_id']; ?>"></span>&nbsp;<i class="fas fa-check"></i>&nbsp;Activer
                                                            </button>
                                                            <?php endif; ?>
                                                            <button type="button" data-id="<?php echo $row['structure_id']; ?>" class="btn btn-icon btn-danger btn-sm del" style="margin-left:6px;">
                                                                <span id="spinner_d_<?php echo $row['structure_id']; ?>"></span>&nbsp;<i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function(){

    $("#save_structure").submit(function(e){
        e.preventDefault();

        var fileInput = document.getElementById('structure_file');
        if(!fileInput.files.length){
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
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("T&eacute;l&eacute;verser");
                if(data.status == 200){
                    pop_up_success(data.message);
                    // A new row changes which one is active, so reload to keep
                    // the badges and Activer buttons consistent.
                    setTimeout(function(){ location.reload(); }, 900);
                } else {
                    pop_wrong(data.message);
                }
            },
            error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("T&eacute;l&eacute;verser");
                pop_wrong("Une erreur est survenue !");
            }
        });
    });

    $(document).on('click', '.activate', function(){
        var id = $(this).data('id');
        $('#spinner_a_'+id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');

        $.ajax({
            url: "../new_files/Choice_Structure/controller.php",
            type: "POST",
            data: { action: 'activate', id: id },
            dataType: "JSON",
            success: function(data){
                $('#spinner_a_'+id).fadeOut('fast');
                if(data.status == 200){
                    pop_up_success(data.message);
                    setTimeout(function(){ location.reload(); }, 700);
                } else {
                    pop_wrong(data.message);
                }
            },
            error: function(){
                $('#spinner_a_'+id).fadeOut('fast');
                pop_wrong("Une erreur est survenue !");
            }
        });
    });

    $(document).on('click', '.del', function(){
        var id = $(this).data('id');

        swal({
            title: "&Ecirc;tes-vous s&ucirc;r ?",
            text: "Ce document sera d&eacute;finitivement supprim&eacute;.",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function(willDelete){
            if(!willDelete){ return; }

            $('#spinner_d_'+id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "../new_files/Choice_Structure/controller.php",
                type: "POST",
                data: { action: 'delete', id: id },
                dataType: "JSON",
                success: function(data){
                    $('#spinner_d_'+id).fadeOut('fast');
                    if(data.status == 200){
                        pop_up_success(data.message);
                        if(data.was_active == 1){
                            setTimeout(function(){ location.reload(); }, 700);
                        } else {
                            $("tr[data-row-id='"+id+"']").remove();
                        }
                    } else {
                        pop_wrong(data.message);
                    }
                },
                error: function(){
                    $('#spinner_d_'+id).fadeOut('fast');
                    pop_wrong("Une erreur est survenue !");
                }
            });
        });
    });

});

function pop_wrong(feedback) {
    iziToast.warning({ title: 'Info', message: feedback, position: 'topCenter' });
}

function pop_up_success(feedback) {
    iziToast.success({ title: 'Info', message: feedback, position: 'topCenter' });
}
</script>
