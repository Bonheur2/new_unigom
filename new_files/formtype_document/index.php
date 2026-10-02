<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Form Type Documents</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Form Type Document</div>
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
                                                    <label>Form Type</label>
                                                    <select class="form-control select2" style="width:100%" name="form_id" id="form_id" required>
                                                        <option value="">-- Select Form Type --</option>
                                                        <?php
                                                            $sql_forms = $conn->prepare("SELECT form_id, form_name FROM tbl_form_types WHERE status = 1 ORDER BY rank ASC, form_name ASC");
                                                            $sql_forms->execute();
                                                            while($frm = $sql_forms->fetch(PDO::FETCH_ASSOC)):
                                                        ?>
                                                        <option value="<?php echo $frm['form_id']; ?>"><?php echo htmlspecialchars($frm['form_name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Document Name</label>
                                                    <input type="text" class="form-control" id="document_name" placeholder="e.g. Curriculum Vitae" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Accepted File Formats <small class="text-muted">(pick one or more)</small></label>
                                                    <select class="form-control select2" style="width:100%" id="file_type" multiple>
                                                        <option value="pdf">PDF (.pdf)</option>
                                                        <option value="doc">Word 97-2003 (.doc)</option>
                                                        <option value="docx">Word (.docx)</option>
                                                        <option value="jpg">JPEG (.jpg)</option>
                                                        <option value="jpeg">JPEG (.jpeg)</option>
                                                        <option value="png">PNG (.png)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Max Size (MB)</label>
                                                    <input type="number" class="form-control" id="max_size_mb" min="1" max="50" placeholder="e.g. 5">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Template File</label>
                                                    <input type="file" class="form-control" id="file_name">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Required</label>
                                                    <select class="form-control select2" style="width:100%" id="is_required">
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
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
        $sql_tabs = $conn->prepare("SELECT DISTINCT tbl_form_types.form_id, tbl_form_types.form_name
                                    FROM tbl_form_types
                                    INNER JOIN tbl_formtype_document ON tbl_formtype_document.form_id = tbl_form_types.form_id
                                    WHERE tbl_form_types.status = 1
                                    ORDER BY tbl_form_types.rank ASC, tbl_form_types.form_name ASC");
        $sql_tabs->execute();
        $formTabs = $sql_tabs->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if(empty($formTabs)): ?>
        <div class="alert alert-info">No document requirements have been registered yet. Use the <b>+</b> button above to add one.</div>
    <?php else: ?>
    <!-- Form Type Tabs -->
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <?php foreach($formTabs as $index => $ft): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>"
               id="tab-<?php echo $ft['form_id']; ?>"
               data-toggle="tab"
               href="#form-<?php echo $ft['form_id']; ?>"
               role="tab">
                <?php echo htmlspecialchars($ft['form_name']); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <!-- Form Type Tab Contents -->
    <div class="tab-content mt-2" id="formTabsContent">
        <?php foreach($formTabs as $index => $ft): ?>
        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
             id="form-<?php echo $ft['form_id']; ?>"
             role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover table-sm doc_table" data-form-id="<?php echo $ft['form_id']; ?>">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Document Name</th>
                        <th scope="col">Formats</th>
                        <th scope="col">Max Size</th>
                        <th scope="col">Required</th>
                        <th scope="col">Template</th>
                        <th scope="col">International Only</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = $conn->prepare("SELECT * FROM tbl_formtype_document WHERE form_id = :form_id ORDER BY status ASC, document_name ASC");
                        $sql->execute([':form_id' => $ft['form_id']]);
                        $i = 1;
                        while($doc = $sql->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr data-row-id="<?php echo $doc['doc_id']; ?>">
                        <th scope="row"><?php echo $i++; ?></th>
                        <td class="col-doc-name"><?php echo htmlspecialchars($doc['document_name']); ?></td>
                        <td class="col-file-type"><?php echo htmlspecialchars(strtoupper(str_replace(',', ', ', $doc['file_type']))); ?></td>
                        <td class="col-max-size"><?php echo $doc['max_size_mb'] ? $doc['max_size_mb'].' MB' : ''; ?></td>
                        <td class="col-required"><?php echo $doc['is_required']==1 ? 'Yes' : 'No'; ?></td>
                        <td class="col-template">
                            <?php if(!empty($doc['file_name'])): ?>
                            <a href="<?php echo htmlspecialchars($doc['file_name']); ?>" target="_blank">View</a>
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
    <?php endif; ?>
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
                                    <label>Form Type</label>
                                    <select class="form-control select2" style="width:100%" id="e_form_id">
                                        <option value="">-- Select Form Type --</option>
                                        <?php
                                            $sql_forms2 = $conn->prepare("SELECT form_id, form_name FROM tbl_form_types WHERE status = 1 ORDER BY rank ASC, form_name ASC");
                                            $sql_forms2->execute();
                                            while($frm2 = $sql_forms2->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?php echo $frm2['form_id']; ?>"><?php echo htmlspecialchars($frm2['form_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Document Name</label>
                                    <input type="text" class="form-control" id="e_document_name" placeholder="e.g. Curriculum Vitae" required>
                                </div>
                                <div class="form-group">
                                    <label>Accepted File Formats <small class="text-muted">(pick one or more)</small></label>
                                    <select class="form-control select2" style="width:100%" id="e_file_type" multiple>
                                        <option value="pdf">PDF (.pdf)</option>
                                        <option value="doc">Word 97-2003 (.doc)</option>
                                        <option value="docx">Word (.docx)</option>
                                        <option value="jpg">JPEG (.jpg)</option>
                                        <option value="jpeg">JPEG (.jpeg)</option>
                                        <option value="png">PNG (.png)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Max Size (MB)</label>
                                    <input type="number" class="form-control" id="e_max_size_mb" min="1" max="50" placeholder="e.g. 5">
                                </div>
                                <div class="form-group">
                                    <label>Template File</label>
                                    <div id="e_file_preview" class="mb-2"></div>
                                    <input type="file" class="form-control" id="e_file_name">
                                </div>
                                <div class="form-group">
                                    <label>Required</label>
                                    <select class="form-control select2" style="width:100%" id="e_is_required">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
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

<script>
$(document).ready(function(){
    $('.doc_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });

    // select2 inside a Bootstrap modal needs the modal as its dropdown parent,
    // otherwise the search box cannot receive focus.
    $('#updateModal').on('shown.bs.modal', function(){
        $(this).find('select.select2').each(function(){
            if($(this).data('select2')){ $(this).select2('destroy'); }
            $(this).select2({ width: '100%', dropdownParent: $('#updateModal') });
        });
    });

    //save document
    $("#save_doc").submit(function(e){
        e.preventDefault();

        var formats = $("#file_type").val() || [];
        if(formats.length === 0){
            pop_wrong("Please choose at least one accepted file format");
            return;
        }

        var formData = new FormData();
        formData.append('form_id', $("#form_id").val());
        formData.append('document_name', $("#document_name").val());
        formData.append('file_type', formats.join(','));
        formData.append('max_size_mb', $("#max_size_mb").val());
        formData.append('is_required', $("#is_required").val());
        formData.append('international_required', $("#international_required").val());
        if($("#file_name")[0].files[0]){
            formData.append('file_name', $("#file_name")[0].files[0]);
        }
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/formtype_document/controller.php",
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
                    $('#form_id').val('').trigger('change');
                    $('#file_type').val(null).trigger('change');
                    pop_up_success(data.message);
                    // a new tab may be needed for this form type, so reload
                    setTimeout(function(){ location.reload(); }, 1200);
                }
                if(data.status==401 || data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // toggle document status
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = { id: data_id, action:'delete' };
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
                    url: "../new_files/formtype_document/controller.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==500 || data.status==401){
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                        }
                    },
                    error:function(){
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
        var getData = { id: data_id, action:'view' };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/formtype_document/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_document_name").val(data.document_name);
                $("#e_max_size_mb").val(data.max_size_mb);
                $("#e_is_required").val(data.is_required).trigger('change');
                $("#e_international_required").val(data.international_required).trigger('change');
                $("#e_form_id").val(data.form_id).trigger('change');
                $("#f_name").html(data.document_name);
                $("#e_file_preview").html(data.file_name ? "<a href='"+data.file_name+"' target='_blank'>View current file</a>" : "");

                // file_type is stored comma-separated: turn it back into selections
                var formats = (data.file_type || '').split(',').filter(function(v){ return v !== ''; });
                $("#e_file_type").val(formats).trigger('change');

                $('#updateModal').modal('show');
            },
            error:function(){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update document
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formats = $("#e_file_type").val() || [];
        if(formats.length === 0){
            pop_wrong("Please choose at least one accepted file format");
            return;
        }

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('form_id', $("#e_form_id").val());
        formData.append('document_name', $("#e_document_name").val());
        formData.append('file_type', formats.join(','));
        formData.append('max_size_mb', $("#e_max_size_mb").val());
        formData.append('is_required', $("#e_is_required").val());
        formData.append('international_required', $("#e_international_required").val());
        if($("#e_file_name")[0].files[0]){
            formData.append('file_name', $("#e_file_name")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/formtype_document/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save changes");
                if(data.status==200){
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    if(data.form_changed){
                        setTimeout(function(){ location.reload(); }, 1000);
                    } else {
                        var $row = $("tr[data-row-id='"+data.doc_id+"']");
                        $row.find('.col-doc-name').text(data.document_name);
                        $row.find('.col-file-type').text((data.file_type || '').split(',').join(', ').toUpperCase());
                        $row.find('.col-max-size').text(data.max_size_mb ? data.max_size_mb + ' MB' : '');
                        $row.find('.col-required').text(data.is_required == 1 ? 'Yes' : 'No');
                        $row.find('.col-intl').text(data.international_required == 1 ? 'Yes' : 'No');
                        if(data.file_name){
                            $row.find('.col-template').html("<a href='"+data.file_name+"' target='_blank'>View</a>");
                        }
                    }
                }
                if(data.status==401 || data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save changes");
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
