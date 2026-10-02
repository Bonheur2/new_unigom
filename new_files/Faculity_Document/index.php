<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Faculty Documents</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Faculty Document</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Document Requirement</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_doc" action="save_doc" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%" id="campus_id">
                                                        <option value="0">select campus</option>
                                                        <?php
                                                            $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                            $sql_camp->execute();
                                                            while($campus = $sql_camp->fetch()):
                                                        ?>
                                                        <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Faculty <span id="spinner_fac"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="fac_id">

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Programme <span id="spinner_prg"></span></label>
                                                    <select class="form-control select2" style="width:100%" name="prg_type_id" id="prg_type_id">
                                                        <option value="">-- Select Faculty first --</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Document Name</label>
                                                    <input type="text" class="form-control" id="document_name" placeholder="e.g. National ID Copy" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Accepted File Type</label>
                                                    <input type="text" class="form-control" id="file_type" placeholder="e.g. pdf, jpg">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Template File</label>
                                                    <input type="file" class="form-control" id="file_name">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>International Only</label>
                                                    <select class="form-control select2" style="width:100%" id="international_required">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">

                                <div class="card-header">
    <h4>Registered Documents</h4>
</div>
<div class="card-body pb-0">
    <?php
        $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_campus.camp_id, tbl_campus.camp_full_name
                                    FROM tbl_campus
                                    INNER JOIN tbl_faculty ON tbl_faculty.campus_id = tbl_campus.camp_id
                                    INNER JOIN tbl_program_type ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_document_type ON tbl_document_type.prg_type_id = tbl_program_type.prg_type_id
                                    WHERE tbl_campus.camp_active=1 AND tbl_faculty.status=1
                                    ORDER BY tbl_campus.camp_full_name ASC");
        $sql_tabs->execute();
        $campuses = $sql_tabs->fetchAll();
    ?>
    <!-- Campus Tabs -->
    <ul class="nav nav-tabs" id="campusTabs" role="tablist">
        <?php foreach($campuses as $index => $campus): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>"
               id="tab-<?php echo $campus['camp_id']; ?>"
               data-toggle="tab"
               href="#campus-<?php echo $campus['camp_id']; ?>"
               role="tab">
                <?php echo $campus['camp_full_name']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <!-- Campus Tab Contents -->
    <div class="tab-content mt-2" id="campusTabsContent">
        <?php foreach($campuses as $index => $campus):
            $sql_facs = $conn->prepare("SELECT DISTINCT tbl_program_type.prg_type_id, tbl_program_type.prg_type_full_name,
                                               tbl_faculty.fac_full_name
                                        FROM tbl_program_type
                                        INNER JOIN tbl_faculty ON tbl_faculty.fac_id = tbl_program_type.fac_id
                                        INNER JOIN tbl_document_type ON tbl_document_type.prg_type_id = tbl_program_type.prg_type_id
                                        WHERE tbl_faculty.campus_id = :camp_id
                                        AND tbl_faculty.status=1
                                        ORDER BY tbl_faculty.fac_full_name ASC, tbl_program_type.prg_type_full_name ASC");
            $sql_facs->execute([':camp_id' => $campus['camp_id']]);
            $faculties = $sql_facs->fetchAll();
        ?>
        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
             id="campus-<?php echo $campus['camp_id']; ?>"
             role="tabpanel">

            <!-- Faculty Tabs (nested) -->
            <ul class="nav nav-tabs mt-2" id="facTabs-<?php echo $campus['camp_id']; ?>" role="tablist">
                <?php foreach($faculties as $fIndex => $fac): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $fIndex === 0 ? 'active' : ''; ?>"
                       id="ftab-<?php echo $campus['camp_id'].'-'.$fac['prg_type_id']; ?>"
                       data-toggle="tab"
                       href="#fac-<?php echo $campus['camp_id'].'-'.$fac['prg_type_id']; ?>"
                       role="tab">
                        <?php echo $fac['prg_type_full_name']; ?>
                        <small class="text-muted d-block"><?php echo $fac['fac_full_name']; ?></small>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Faculty Tab Contents -->
            <div class="tab-content mt-2" id="facTabsContent-<?php echo $campus['camp_id']; ?>">
                <?php foreach($faculties as $fIndex => $fac): ?>
                <div class="tab-pane fade <?php echo $fIndex === 0 ? 'show active' : ''; ?>"
                     id="fac-<?php echo $campus['camp_id'].'-'.$fac['prg_type_id']; ?>"
                     role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm doc_table" data-prg-type-id="<?php echo $fac['prg_type_id']; ?>">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Document Name</th>
                                <th scope="col">File Type</th>
                                <th scope="col">Template</th>
                                <th scope="col">International Only</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql = $conn->prepare("SELECT * FROM tbl_document_type WHERE prg_type_id = :prg_type_id ORDER BY status ASC");
                                $sql->execute([':prg_type_id' => $fac['prg_type_id']]);
                                $i = 1;
                                while($doc = $sql->fetch()):
                            ?>
                            <tr data-row-id="<?php echo $doc['doc_id']; ?>">
                                <th scope="row"><?php echo $i++; ?></th>
                                <td class="col-doc-name"><?php echo $doc['document_name']; ?></td>
                                <td class="col-file-type"><?php echo $doc['file_type']; ?></td>
                                <td class="col-template">
                                    <?php if(!empty($doc['file_name'])): ?>
                                    <a href="<?php echo $doc['file_name']; ?>" target="_blank">View</a>
                                    <?php endif; ?>
                                </td>
                                <td class="col-intl"><?php echo $doc['international_required']==1 ? 'Yes' : 'No'; ?></td>
                                <th>
                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                    <div class="buttons row">
                                        <button type="button" data-id="<?php echo $doc['doc_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                            <span id="spinner4_<?php echo $doc['doc_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                        </button>
                                        <label class="custom-switch btn btn-light btn-sm">
                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $doc['doc_id']; ?>" <?php echo $doc['status']==1 ? 'checked' : ''; ?>>
                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $doc['doc_id']; ?>"></span>&nbsp;
                                        </label>
                                    </div>
                                    <?php endif; ?>
                                </th>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- End Faculty Tab Contents -->

        </div>
        <?php endforeach; ?>
    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--update modal-->
            <form action="update_form" method="POST" id="update_form" enctype="multipart/form-data">
                <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Updating <span id="f_name"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="e_id" name="e_id">
                                <div class="form-group">
                                    <label>Campus <span id="spinner_e_fac"></span></label>
                                    <select class="form-control select2" style="width:100%" id="e_campus_id">
                                        <option value="">-- Select Campus --</option>
                                        <?php
                                            $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                            $sql_camp2->execute();
                                            while($camp2 = $sql_camp2->fetch()):
                                        ?>
                                        <option value="<?php echo $camp2['camp_id']; ?>"><?php echo $camp2['camp_full_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Faculty <span id="spinner_e_prg"></span></label>
                                    <select class="form-control select2" style="width:100%" id="e_fac_id">
                                        <option value="">-- Select Campus first --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Programme</label>
                                    <select class="form-control select2" style="width:100%" id="e_prg_type_id">
                                        <option value="">-- Select Faculty first --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Document Name</label>
                                    <input type="text" class="form-control" id="e_document_name" placeholder="e.g. National ID Copy" required>
                                </div>
                                <div class="form-group">
                                    <label>Accepted File Type</label>
                                    <input type="text" class="form-control" id="e_file_type" placeholder="e.g. pdf, jpg">
                                </div>
                                <div class="form-group">
                                    <label>Template File</label>
                                    <div id="e_file_preview" class="mb-2"></div>
                                    <input type="file" class="form-control" id="e_file_name">
                                </div>
                                <div class="form-group">
                                    <label>International Only</label>
                                    <select class="form-control select2" style="width:100%" id="e_international_required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
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
var pendingFacId = null;
var pendingPrgTypeId = null;

$(document).ready(function(){
    $('.doc_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });

    // build a table row for a document and add it to its faculty's DataTable
    function addDocRow(prg_type_id, row){
        var $table = $("table.doc_table[data-prg-type-id='"+prg_type_id+"']");
        if($table.length === 0){
            // no tab exists yet for this programme (first item) - only way to show it is a reload
            location.reload();
            return;
        }
        var dt = $table.DataTable();
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var templateHtml = row.file_name ? "<a href='"+row.file_name+"' target='_blank'>View</a>" : '';
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.doc_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.doc_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.doc_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.doc_id+'"></span>&nbsp;</label></div>';
        }
        var $tr = $(dt.row.add([
            dt.rows().count() + 1,
            row.document_name,
            row.file_type || '',
            templateHtml,
            row.international_required == 1 ? 'Yes' : 'No',
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.doc_id);
    }

    //save document
    $("#save_doc").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('prg_type_id', $("#prg_type_id").val());
        formData.append('document_name', $("#document_name").val());
        formData.append('file_type', $("#file_type").val());
        formData.append('international_required', $("#international_required").val());
        if($("#file_name")[0].files[0]){
            formData.append('file_name', $("#file_name")[0].files[0]);
        }
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_doc')[0].reset();
                    $('#fac_id').val(null).trigger('change');
                    $('#prg_type_id').empty().append("<option value=''>-- Select Faculty first --</option>").trigger('change');
                    pop_up_success(data.message);
                    addDocRow(data.prg_type_id, data);
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // delete/toggle document
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData= {
                id: data_id,
                action:'delete'
                };
        swal({
        title: "Are you sure?",
        text: "You are about to change this document's status!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
        $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Faculity_Document/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner3_'+data_id).fadeOut('fast');
                if(data.status==500){
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    pop_wrong(data.message);
                }
                else if(data.status==200){
                   pop_up_success(data.message);
                }
            },
            error:function(error){
                $('#spinner3_'+data_id).fadeOut('fast');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
                pop_wrong("Something went wrong");
            }
        });
        }
       else {
            swal("operation cancelled!!");
        }
    });
    });

    //pre-update View
    $(document).on('click','.edit',function () {
        var data_id = $(this).data('id');
        var getData= {
                id: data_id,
                action:'view'
                };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/Faculity_Document/controller.php",
            data: getData,
            dataType:"json",

            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_document_name").val(data.document_name);
                $("#e_file_type").val(data.file_type);
                $("#e_international_required").val(data.international_required).trigger('change');
                $("#f_name").html(data.document_name);
                $("#e_file_preview").html(data.file_name ? "<a href='"+data.file_name+"' target='_blank'>View current file</a>" : "");

                pendingFacId = data.prg_fac_id || data.fac_id;
                pendingPrgTypeId = data.prg_type_id;
                $("#e_campus_id").val(data.campus_id).trigger('change');
                $('#updateModal').modal('show');
			},
			error:function(error){
			    $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
			}
        });
    });

    //update document
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('prg_type_id', $("#e_prg_type_id").val());
        formData.append('document_name', $("#e_document_name").val());
        formData.append('file_type', $("#e_file_type").val());
        formData.append('international_required', $("#e_international_required").val());
        if($("#e_file_name")[0].files[0]){
            formData.append('file_name', $("#e_file_name")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save Changes");
                if(data.status==200){
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if(data.prg_changed){
                        location.reload();
                    } else {
                        var $row = $("tr[data-row-id='"+data.doc_id+"']");
                        $row.find('.col-doc-name').text(data.document_name);
                        $row.find('.col-file-type').text(data.file_type || '');
                        $row.find('.col-intl').text(data.international_required == 1 ? 'Yes' : 'No');
                        if(data.file_name){
                            $row.find('.col-template').html("<a href='"+data.file_name+"' target='_blank'>View</a>");
                        }
                    }
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save Changes");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // load programmes when faculty changes (add form)
    $("#fac_id").change(function(){
        var fac_id = $(this).val();
        $("#prg_type_id").empty().append("<option value=''>-- Select Faculty first --</option>").trigger('change');
        if(!fac_id) return;
        $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_program_types" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_prg').fadeOut('fast');
                $("#prg_type_id").empty().append("<option value=''>-- Select Programme --</option>");
                $.each(data, function(index, value){
                    $("#prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                });
                $("#prg_type_id").trigger('change');
            },
            error: function(){
                $('#spinner_prg').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // load programmes when faculty changes (update modal)
    $("#e_fac_id").change(function(){
        var fac_id = $(this).val();
        $("#e_prg_type_id").empty().append("<option value=''>-- Select Faculty first --</option>").trigger('change');
        if(!fac_id) return;
        $('#spinner_e_prg').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { fac_id: fac_id, action: "get_program_types" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_prg').fadeOut('fast');
                $("#e_prg_type_id").empty().append("<option value=''>-- Select Programme --</option>");
                $.each(data, function(index, value){
                    $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                });
                if(pendingPrgTypeId){
                    $("#e_prg_type_id").val(pendingPrgTypeId);
                    pendingPrgTypeId = null;
                }
                $("#e_prg_type_id").trigger('change');
            },
            error: function(){
                $('#spinner_e_prg').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // load faculty when campus changes (add form)
    $("#campus_id").change(function(){
         $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
         $("#fac_id").empty();
        var camp_id=$("#campus_id").val();
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
            $('#spinner_fac').fadeOut('fast');
            $("#fac_id").append("<option></option>")
            $.each(data, function (index, value) {
                $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name+"</option>");
            });
            },error: function(){
                $('#spinner_fac').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
         });
    })

    // load faculty when campus changes (update modal)
    $("#e_campus_id").change(function(){
        var camp_id = $(this).val();
        if(!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $("#e_fac_id").empty().append("<option value=''>Loading...</option>");
        $.ajax({
            url: "../new_files/Faculity_Document/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_e_fac').fadeOut('fast');
                $("#e_fac_id").empty().append("<option value=''>-- Select Faculty --</option>");
                $.each(data, function(index, value){
                    $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                });
                if(pendingFacId){
                    $("#e_fac_id").val(pendingFacId).trigger('change');
                    pendingFacId = null;
                }
            },
            error: function(){
                $('#spinner_e_fac').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

});

function pop_wrong(feedback) {
    iziToast.warning({
    title: 'Error',
    message: feedback,
    position: 'topCenter'
  });
}

function pop_up_success(feedback) {
iziToast.success({
title: 'info',
message: feedback,
position: 'topCenter'
});
}
</script>
