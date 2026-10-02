<?php
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
    
    
    $checkPermission = $conn->prepare("
    SELECT * 
    FROM tbl_admittedPRG 
    WHERE Stu_code = :code
");
$checkPermission->bindParam(':code', $code, PDO::PARAM_STR);
$checkPermission->execute();

$applicantData = $checkPermission->fetch(PDO::FETCH_ASSOC);

$permission = $checkPermission->rowCount();

$cump_id = '';
$prg_type = '';
if ($applicantData) {
    $cump_id = $applicantData['cump_id'];
    $prg_type = $applicantData['prg_type'];
}
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>6. Application documents</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Documents</a></div>
                    </div>
                </div>
                
                <?php ApplicationDocuments($conn, $code, $prg_type); ?>
                
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Upload documents</h4>
                                </div>
                                <div class="collapse show" id="mycard-collapse">
                                    <div class="card-body">
                                        <div id="save_document" class="card-body pb-0 row"
                                             data-code="<?php echo htmlspecialchars($code, ENT_QUOTES, 'UTF-8'); ?>"
                                             data-prg="<?php echo htmlspecialchars($prg_type, ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php
                                                    $sql_docs=$conn->prepare("SELECT *, requered_status FROM tbl_document_type WHERE (prg_type='".$prg_type."' OR prg_type=0) AND status=1");
                                                    $sql_docs->execute();
                                                    $i=1;
                                                    while($doc=$sql_docs->fetch()){
                                                        $fieldName = $doc['file_name'];
                                                        $fieldId = 'doc_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $fieldName);
                                                        $docPathPattern = "/student_docs/" . $fieldName . "_" . $code . ".%";
                                                        $checkUploaded = $conn->prepare("SELECT appl_doc_id, upload_doc FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE ?");
                                                        $checkUploaded->execute([$code, $docPathPattern]);
                                                        $uploadedRow = $checkUploaded->fetch(PDO::FETCH_ASSOC);
                                                        $isUploaded = !empty($uploadedRow);
                                                ?>
                                                <div class="form-group col-12 col-sm-4 col-lg-4 doc-field-wrapper" id="wrapper_<?php echo $fieldId; ?>">
                                                    <label>
                                                        <?php echo htmlspecialchars($doc['document_name']); ?> | 2MB max
                                                        <?php if ($doc['requered_status'] == 1) { ?><span class="text-danger">*</span><?php } ?>
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-file"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="file"
                                                               class="form-control col-md-12 doc-file-input<?php echo $doc['file_type'] == 'image' ? ' doc-image-input' : ''; ?>"
                                                               id="<?php echo $fieldId; ?>"
                                                               name="<?php echo htmlspecialchars($fieldName); ?>"
                                                               accept="<?php echo $doc['file_type'] == 'image' ? '.jpg,.jpeg,.png' : '.pdf'; ?>"
                                                               data-is-image="<?php echo $doc['file_type'] == 'image' ? '1' : '0'; ?>"
                                                               data-field-id="<?php echo $fieldId; ?>"
                                                               <?php echo $isUploaded ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <code><?php echo $doc['file_type']=="image"?".jpg, .png, .jpeg (square crop)":".pdf"; ?></code>
                                                    <?php if ($doc['file_type'] == 'image') { ?>
                                                    <div id="crop-container_<?php echo $fieldId; ?>" class="crop-container mt-2" style="display:none;">
                                                        <div class="crop-preview-wrap">
                                                            <img id="crop-image_<?php echo $fieldId; ?>" alt="Crop preview">
                                                        </div>
                                                        <div class="text-center mt-2">
                                                            <button type="button"
                                                                    class="btn btn-success btn-sm crop-use-btn"
                                                                    data-field-id="<?php echo $fieldId; ?>"
                                                                    data-file-name="<?php echo htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8'); ?>">
                                                                <i class="fas fa-crop"></i> Crop &amp; Use
                                                            </button>
                                                            <button type="button"
                                                                    class="btn btn-secondary btn-sm crop-cancel-btn"
                                                                    data-field-id="<?php echo $fieldId; ?>"
                                                                    data-file-name="<?php echo htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8'); ?>">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                        <small class="text-muted d-block text-center mt-1">Drag to crop a square passport photo</small>
                                                    </div>
                                                    <div id="crop-ready_<?php echo $fieldId; ?>" class="mt-2" style="display:none;">
                                                        <span class="badge badge-info"><i class="fas fa-check"></i> Image cropped and ready to upload</span>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="mt-2 upload-status" id="status_<?php echo $fieldId; ?>">
                                                        <?php if ($isUploaded) { ?>
                                                            <span class="badge badge-primary"><i class="fas fa-check-circle"></i> Document uploaded</span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="mt-2 uploaded-actions" id="actions_<?php echo $fieldId; ?>" <?php echo $isUploaded ? '' : 'style="display:none;"'; ?>>
                                                        <a href="../..<?php echo htmlspecialchars($uploadedRow['upload_doc']); ?>"
                                                           target="_blank"
                                                           class="btn btn-success btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm delete-doc-btn"
                                                                data-appl-doc-id="<?php echo (int) $uploadedRow['appl_doc_id']; ?>"
                                                                data-input-id="<?php echo $fieldId; ?>"
                                                                data-doc-name="<?php echo htmlspecialchars($doc['document_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            <span id="del_spinner_<?php echo $fieldId; ?>"></span>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="mt-2 upload-btn-wrap" id="btnwrap_<?php echo $fieldId; ?>" <?php echo $isUploaded ? 'style="display:none;"' : ''; ?>>
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm upload-doc-btn"
                                                                data-file-name="<?php echo htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-input-id="<?php echo $fieldId; ?>"
                                                                data-doc-name="<?php echo htmlspecialchars($doc['document_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            <span id="spinner_<?php echo $fieldId; ?>"></span>&nbsp;
                                                            <span id="indicator_<?php echo $fieldId; ?>">Upload</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ReportProblem($conn, $code, $thing); ?>
            </section>
        </div>
        
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
.crop-preview-wrap {
    max-width: 100%;
    max-height: 320px;
    overflow: hidden;
    background: #f8f9fa;
    border: 1px solid #e3e6f0;
    border-radius: 4px;
}
.crop-preview-wrap img {
    display: block;
    max-width: 100%;
}
</style>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
var croppers = {};
var croppedImages = {};

function clearCropState(fieldId, fileName, clearBlob) {
    $('#crop-container_' + fieldId).hide();
    $('#crop-ready_' + fieldId).hide();
    if (croppers[fieldId]) {
        croppers[fieldId].destroy();
        delete croppers[fieldId];
    }
    if (clearBlob && fileName) {
        delete croppedImages[fileName];
    }
}

function initImageCropper(input) {
    var fieldId = $(input).data('field-id');
    var fileName = input.name;
    var file = input.files[0];

    if (!file) {
        return;
    }

    clearCropState(fieldId, fileName, true);

    var reader = new FileReader();
    reader.onload = function(e) {
        var $cropImage = $('#crop-image_' + fieldId);
        $cropImage.attr('src', e.target.result);
        $('#crop-container_' + fieldId).show();

        croppers[fieldId] = new Cropper($cropImage[0], {
            aspectRatio: 1,
            viewMode: 2,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false
        });
    };
    reader.readAsDataURL(file);
}

$(document).ready(function(){

    $('#docs_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    function markDocAsUploaded(inputId, applDocId, uploadDoc, docName) {
        $('#' + inputId).prop('disabled', true).val('');
        $('#btnwrap_' + inputId).hide();
        $('#status_' + inputId).html(
            '<span class="badge badge-primary"><i class="fas fa-check-circle"></i> Document uploaded</span>' +
            '<small class="d-block text-muted mt-1">View your file or delete it to upload again.</small>'
        );
        $('#actions_' + inputId).html(
            '<a href="../..' + uploadDoc + '" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a> ' +
            '<button type="button" class="btn btn-danger btn-sm delete-doc-btn" data-appl-doc-id="' + applDocId + '" data-input-id="' + inputId + '" data-doc-name="' + (docName || '') + '">' +
            '<span id="del_spinner_' + inputId + '"></span><i class="fas fa-trash"></i> Delete</button>'
        ).show();
        $('#wrapper_' + inputId).addClass('doc-uploaded');
    }

    function unlockDocField(inputId) {
        var $input = $('#' + inputId);
        clearCropState(inputId, $input.attr('name'), true);
        $input.prop('disabled', false).val('');
        $('#btnwrap_' + inputId).show();
        $('#status_' + inputId).html('');
        $('#actions_' + inputId).hide().html('');
        $('#wrapper_' + inputId).removeClass('doc-uploaded');
    }

    $(document).on('change', '.doc-image-input', function() {
        if (!this.disabled) {
            initImageCropper(this);
        }
    });

    $(document).on('click', '.crop-use-btn', function() {
        var fieldId = $(this).data('field-id');
        var fileName = $(this).data('file-name');
        var cropper = croppers[fieldId];

        if (!cropper) {
            pop_wrong('Please select an image first.');
            return;
        }

        var canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        canvas.toBlob(function(blob) {
            if (!blob) {
                pop_wrong('Failed to crop image. Please try again.');
                return;
            }
            croppedImages[fileName] = blob;
            $('#crop-container_' + fieldId).hide();
            cropper.destroy();
            delete croppers[fieldId];
            $('#crop-ready_' + fieldId).show();
            pop_up_success('Image cropped. Click Upload to save.');
        }, 'image/jpeg', 0.9);
    });

    $(document).on('click', '.crop-cancel-btn', function() {
        var fieldId = $(this).data('field-id');
        var fileName = $(this).data('file-name');
        $('#' + fieldId).val('');
        clearCropState(fieldId, fileName, true);
    });

    $(document).on('click', '.upload-doc-btn', function() {
        var $btn = $(this);
        var fileName = $btn.data('file-name');
        var inputId = $btn.data('input-id');
        var docName = $btn.data('doc-name');
        var $input = $('#' + inputId);

        if ($input.prop('disabled')) {
            pop_wrong(docName + ' has already been uploaded.');
            return;
        }

        var isImage = $input.data('is-image') == 1;
        var file = $input[0].files[0];

        if (isImage) {
            if (!croppedImages[fileName]) {
                pop_wrong('Please crop your ' + docName + ' before uploading.');
                return;
            }
        } else if (!file) {
            pop_wrong('Please select a file for ' + docName);
            return;
        }

        var formData = new FormData();
        formData.append('action', 'upload_single_doc');
        formData.append('code', $('#save_document').data('code'));
        formData.append('prg', $('#save_document').data('prg'));
        formData.append('file_name', fileName);

        if (isImage && croppedImages[fileName]) {
            var croppedFile = new File(
                [croppedImages[fileName]],
                fileName + '.jpg',
                { type: 'image/jpeg', lastModified: Date.now() }
            );
            formData.append(fileName, croppedFile);
        } else {
            formData.append(fileName, file);
        }

        $('#spinner_' + inputId).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator_' + inputId).html('Uploading...');
        $btn.prop('disabled', true);

        $.ajax({
            url: "../files/Student/documents/document_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data) {
                $('#spinner_' + inputId).fadeOut('fast');
                $('#indicator_' + inputId).html('Upload');

                if (data.status == 200) {
                    clearCropState(inputId, fileName, true);
                    markDocAsUploaded(inputId, data.appl_doc_id, data.upload_doc, docName);
                    pop_up_success(data.message);
                } else if (data.status == 401) {
                    markDocAsUploaded(inputId, data.appl_doc_id, data.upload_doc, docName);
                    pop_wrong(data.message);
                } else {
                    $btn.prop('disabled', false);
                    pop_wrong(data.message || 'Upload failed.');
                }
            },
            error: function() {
                $('#spinner_' + inputId).fadeOut('fast');
                $('#indicator_' + inputId).html('Upload');
                $btn.prop('disabled', false);
                pop_wrong('Something went wrong!');
            }
        });
    });

    $(document).on('click', '.delete-doc-btn', function() {
        var $btn = $(this);
        var applDocId = $btn.data('appl-doc-id');
        var inputId = $btn.data('input-id');
        var docName = $btn.data('doc-name') || 'this document';

        swal({
            title: 'Delete document?',
            text: 'You will be able to upload a new file after deleting.',
            icon: 'warning',
            buttons: true,
            dangerMode: true
        }).then(function(willDelete) {
            if (!willDelete) return;

            $('#del_spinner_' + inputId).html("<img src='../../img/ajax_loader.gif' width='12'>");
            $btn.prop('disabled', true);

            $.ajax({
                url: '../files/Student/documents/document_controller.php',
                type: 'POST',
                data: {
                    action: 'delete_doc',
                    appl_doc_id: applDocId,
                    code: $('#save_document').data('code')
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#del_spinner_' + inputId).html('');
                    $btn.prop('disabled', false);

                    if (data.status == 200) {
                        unlockDocField(inputId);
                        pop_up_success(data.message);
                    } else {
                        pop_wrong(data.message || 'Failed to delete document.');
                    }
                },
                error: function() {
                    $('#del_spinner_' + inputId).html('');
                    $btn.prop('disabled', false);
                    pop_wrong('Something went wrong!');
                }
            });
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