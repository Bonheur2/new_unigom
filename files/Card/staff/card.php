<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css" rel="stylesheet">
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Staff card</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item">Staff card</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row card" style="display:flex; flex-direction:row; justify-content:center;">
                <div class=" card-body col-md-8">
                    <form method="POST" id="search">
                        <div class="input-group">
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Search by Staff ID,phone or email">
                            <div class="input-group-append">                                            
                                <button type="submit" class="btn btn-primary" id="spinner"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" id="searchResult"></div>
        </div>
    </section>
    <!--upload modal-->
    <form action="upload_form" id="upload_form" method="POST" enctype="multipart/form-data">
        <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose Image file</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body pb-0 row">
                            <div class="form-group  col-12">
                                <input type="hidden" name="action" value="upload">
                                <input class="form-control" type="file" name="photo" id="image-file" accept="image/*" required>
                            </div>
                            <div  class="form-group col-12" style="margin:auto; max-height:300px; margin-bottom:20px;">
                                <img id="cropped-image">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button id="crop-button" class="btn btn-primary btn-sm" hidden><span id="spinner2"></span>&nbsp;<span id="indicator2">Crop & Upload</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
<script>
    $(document).ready(function(){
        $("#search").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Card/staff/load_card_info.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    $('#searchResult').html(data);
                    getId();
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        function getId(){
            var key=$("#keyword").val();
                    var formData={
                       key:key
               }
              $.ajax({
                url: "/files/Card/staff/get_staffId.php",
                type: "POST",
                data: formData,
                dataType: "json",

                success: function(data){
                    if(data.status==200){
                       $("#keyword").val(data.message) 
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
            });
        }
        $(document).on('click','#uploader', function () {
            $("#uploadModal").modal('show');
        });
        
        
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

        $(document).on('change', '#image-file', function(event) {
            var file = event.target.files[0];
            var maxSize = 1 * 1024 * 1024; // 1MB in bytes
            if (!file.type.match('image.*')) {
                document.getElementById("crop-button").setAttribute('hidden',true);
                pop_info('Please select an image file');
                return;
            }
            else if (file.size > maxSize) {
                document.getElementById("crop-button").setAttribute('hidden', true);
                pop_info('Image size exceeds 1MB. Please select a smaller image.');
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
          $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
          $('#indicator2').html("Uploading...");
          $('#crop-button').attr('disabled',true);
          $.ajax({
            url: '/files/employees/employees_controller.php',
            type: 'POST',
            data: {
              action:'upload_image',
              code:$('#keyword').val(),
              image: croppedImageDataURL,
              crop_x: $('#crop-x').val(),
              crop_y: $('#crop-y').val(),
              crop_width: $('#crop-width').val(),
              crop_height: $('#crop-height').val()
            },
            dataType: 'text',
            success: function(data) {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Crop & upload");
                    $('#crop-button').attr('disabled',false);
                    if(data==200){
                        pop_up_success("Your photo is uploaded");
                        setTimeout(function() {
                          window.location.reload();
                        }, 3000);
                    }
                    if(data==500){
                        pop_info("Something went wrong!");
                    }
            },
            error: function(error) {
              $('#crop-button').attr('disabled',false);
              pop_wrong("Something went wrong!");
            }
          });
        });
    });      

   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Wrong',
        message: feedback,
        position: 'topCenter'
        });
    }
   function pop_info(feedback) {
        iziToast.info({
        title: 'Oooops',
        message: feedback,
        position: 'topCenter'
        });
    }
   function pop_up_success(feedback) {
        iziToast.success({
        title: 'done',
        message: feedback,
        position: 'topCenter'
        });
    }
</script>
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>
