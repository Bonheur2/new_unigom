<!-- Start app main Content -->
<div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Marks</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Push Marks</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Push Marks</h4>
                                </div>
                                <div class="card-body row">
                                    <form action="upload_exam_marks" method="POST" id="upload_exam_marks" enctype="multipart/form-data" class="row">
                                        <div class="form-group col-md-6">
                                            <label>Upload your file</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-clipboard"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Upload your file</label>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary btn-sm" id="upload_btn"><span id="spinner_u"></span>&nbsp;<span id="indicator_u">Upload</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $("#upload_exam_marks").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner_u').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_u').html("Uploading...");
            $("#upload_btn").attr('disabled', true);
            $.ajax({
                url: "push_marks.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $("#upload_btn").attr('disabled', false);
                    $('#spinner_u').fadeOut('fast');
                    $('#indicator_u').html("Upload");
                    if(data.status==200){
                        $("#upload_exam_marks")[0].reset();
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(xhr, status, error){
                    $("#upload_btn").attr('disabled', false);
                    $('#spinner_u').fadeOut('fast');
                    $('#indicator_u').html("Upload");
                    pop_wrong("something went wrong");
                }
            });
        });
    });
   function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
   function pop_info(feedback) {
        iziToast.warning({
            title: 'info:',
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