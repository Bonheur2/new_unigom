<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Transcript</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Transcript</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                                    <form id="load_transcript" action="load_transcript" method="POST">
                                                        <input type="hidden" name="action" value="load_trascript">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" hidden>
                                                                <label>Student ID</label><br>
                                                                <input type="text" class="form-control" name="stu" value="<?php echo $identification; ?>" readonly>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Level</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="level" id="level">
                                                                    <?php
                                                                        $sql_level=$conn->prepare("SELECT tbl_level.level_id,tbl_level.level_full_name
                                                                                                        FROM tbl_register_program_ug
                                                                                                          INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id 
                                                                                                        WHERE tbl_register_program_ug.reg_no='".$identification."' ORDER BY tbl_level.level_id DESC");
                                                                        $sql_level->execute();
                                                                        while($level=$sql_level->fetch()){
                                                                    ?>
                                                                    <option value="<?php echo $level['level_id']; ?>"><?php echo $level['level_full_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>&nbsp;</label><br>
                                                                <button type="submit" class="btn btn-primary" id="load_btn"><span id="spinner"></span>&nbsp;<span id="indicator">Load transcript</span></button>
                                                            </div> 
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12" id="transcript">
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
     $("#load_transcript").submit(function (e) {
         e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator').html("Loading...");
            $("#load_btn").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/files/Transcript/load_transcript.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function (data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Load transcript");
                    $("#load_btn").attr("disabled", false);
                    $("#transcript").html(data);
                    $('.export').attr('hidden',true);
                 },
                error:function(error){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Load transcript");
                    pop_wrong("Something went wrong");
                    $("#load_btn").attr("disabled", false);
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