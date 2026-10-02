<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css" rel="stylesheet">
<?php

$stmt=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE tracking_id='".$identification."' AND upload_doc like '%photo_%'");
$stmt->execute();
$data=$stmt->fetch();
$path=$data['upload_doc'];
?>
        <!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>My Photo</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">My Photo</a></div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-8" style="margin:auto;">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">                     
                                    <a href="../../<?php echo $path; ?>" target="_blank"><img alt="image" src="../../<?php echo $path; ?>" class="profile-widget-picture"></a>
                                </div>
                                <div class="profile-widget-description">
                                            <input type="hidden" name="code" id="code" value="<?php echo $identification; ?>">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group">
                                                    <label>Upload image | 2MB max</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-file"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="file" id="image-file" class="form-control" accept="image/*">
                                                    </div>
                                                    <code>.jpg, .png, .jpeg</code>
                                                </div>
                                                <div  class="col-12 col-sm-12 col-lg-8" style="max-width: 100%; max-height: 400px;margin:auto; margin-bottom:20px;">
                                                    <img id="cropped-image">
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-6" style="margin:auto;">
                                                    <button id="crop-button" class="btn btn-primary form-control" style="margin:auto;" hidden><span id="spinner"></span>&nbsp;<span id="indicator">Crop & Upload</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
    <script>
        var cropper = new Cropper(document.getElementById('cropped-image'), {
            aspectRatio: 1, // Set the aspect ratio to square
            viewMode: 1, // Restrict the crop box to the container size
            cropBoxResizable: false,
            crop: function(event) {
                $('#crop-x').val(event.detail.x);
                $('#crop-y').val(event.detail.y);
                $('#crop-width').val(event.detail.width);
                $('#crop-height').val(event.detail.height);

            }
        });

        $('#image-file').change(function(event) {
            var file = event.target.files[0];
            var maxSize = 1 * 1024 * 1024; // 1MB in bytes
            if (!file.type.match('image.*')) {
                document.getElementById("crop-button").setAttribute('hidden',true);
                pop_wrong('Please select an image file');
                return;
            }
            else if (file.size > maxSize) {
                document.getElementById("crop-button").setAttribute('hidden', true);
                pop_wrong('Image size exceeds 1MB. Please select a smaller image.');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById("crop-button").removeAttribute('hidden');
                cropper.replace(event.target.result);
            }
            reader.readAsDataURL(file);
        });



        $('#crop-button').on('click', function(event) {
          var croppedImageDataURL = cropper.getCroppedCanvas().toDataURL();
          $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
          $('#indicator').html("Uploading...");
          $('#crop-button').attr('disabled',true);
          $.ajax({
            url: '/files/Student/documents/document_controller.php',
            type: 'POST',
            data: {
              action:'upload_image',
              code:$('#code').val(),
              image: croppedImageDataURL,
              crop_x: $('#crop-x').val(),
              crop_y: $('#crop-y').val(),
              crop_width: $('#crop-width').val(),
              crop_height: $('#crop-height').val()
            },
            dataType: 'text',
            success: function(data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Crop & upload");
                    $('#crop-button').attr('disabled',false);
                    if(data==200){
                        pop_up_success("Your photo is uploaded");
                        setTimeout(function() {
                          window.location.reload();
                        }, 3000);
                    }
                    if(data==500){
                        pop_wrong("Something went wrong!");
                    }
            },
            error: function(error) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Crop & upload");
                $('#crop-button').attr('disabled',false);
                pop_wrong("Something went wrong!");
            }
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
</body>
</html>
