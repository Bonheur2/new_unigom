<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
    <section class="section">
        <div class="section-header">
            <!--<img src="/files/Student/hec/hec.png" width="50" height="50">-->
            <h3 style="padding-top: 20px;">Student Satisfaction</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Results</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>General statistics</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="selection" action="selection" method="POST">
                                    <div class="card-body pb-0 row">
                                        <input type="hidden" name="type" value="1">
                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                            <label>Academic Year</label><br>
                                            <select class="form-control select2" name="acad_cycle_id" style="width:100%;" id="acad_cycle_id" required>
                                                <?php
                                                    $sql_acad=$conn->prepare("SELECT * FROM tbl_acad_cycle");
                                                    $sql_acad->execute();
                                                    $i=1;
                                                    while($acad=$sql_acad->fetch()){
                                                        ?>
                                                <option value="<?php echo $acad['acad_cycle_id']; ?>"><?php echo $acad['acad_year']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row; justify-content:center;">
                                            <button type="submit" class="btn btn-primary"><span id="spinner20"></span>&nbsp;<span id="indicator20">Load results</span></button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body" id="results"></div>
    </section>
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    //load results
    $("#selection").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/files/Student/hec/statistics/load_results.php",
                type: "POST",
                data: formData,
                mimeTypes:"multipart/form-data",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load results");
                     $("#results").html(data);
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load results");
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
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>