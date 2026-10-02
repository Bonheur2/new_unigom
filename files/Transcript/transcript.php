<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Progress Report</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Progress Report</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <!--<ul class="nav nav-tabs" id="myTab2" role="tablist">-->
                                            <!--    <li class="nav-item"><a class="nav-link active" id="year-tab" data-toggle="tab" href="#yearwise" role="tab" aria-controls="default" aria-selected="true"><b>By Year</b></a></li>-->
                                            <!--    <li class="nav-item"><a class="nav-link" id="cummulative-tab" data-toggle="tab" href="#cummulative" role="tab" aria-controls="cummulative" aria-selected="false"><b>Cummulative</b></a></li>-->
                                            <!--</ul>-->
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab">
                                                    <form id="load_transcript" action="load_transcript" method="POST" autocomplete="off">
                                                        <input type="hidden" name="action" value="load_trascript">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" style="position: relative;">
                                                                <label>Student ID</label><br>
                                                                <input type="text" class="form-control" name="stu" id="stu">
                                                                <button type="button" class="btn btn-primary btn-sm" id="search" style="position: absolute; top: 55%; right: 7%;"><span id="spinner0"><i class="fa fa-search"></i></span></button>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" id="lev" hidden>
                                                                <label>Level</label><br>
                                                                <select class="form-control select2" style="width:100%" name="level" id="level">
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" id="btn" hidden>
                                                                <label>&nbsp;</label><br>
                                                                <button type="submit" class="btn btn-primary" id="load_btn"><span id="spinner"></span>&nbsp;<span id="indicator">Load report</span></button>
                                                            </div> 
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade" id="cummulative" role="tabpanel" aria-labelledby="cummulative-tab">
                                                    <form id="load_cum_transcript" action="load_cum_load_cum_trascript">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Student ID</label><br>
                                                                <input type="text" class="form-control" name="stud">
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>&nbsp;</label><br>
                                                                <button type="submit" class="btn btn-primary" id="load_cum_btn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Load transcript</span></button>
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
    //load levels
    $("#search").on('click', function (e) {
        var formdata = {
            stu: $("#stu").val(),
            action: 'load_levels'
        };
        $("#lev").attr('hidden', true);
        $("#btn").attr('hidden', true);
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Student/student_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner0').html('<i class="fa fa-search"></i>');
                if(data.length>0){
                    $("#level").empty();
                    $("#level").append("<option disabled selected>--choose one--</option>");
                    $.each(data, function (index, value) {
                        $("#level").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                    });
                    $("#lev").removeAttr('hidden');
                    $("#btn").removeAttr('hidden');
                }else{
                    pop_wrong("No data found!");
                }
             },
            error:function(error){
                $('#spinner0').html('<i class="fa fa-search"></i>');
                pop_wrong("Something went wrong");
            }
        });
    });
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
                 },
                error:function(error){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Load transcript");
                    pop_wrong("Something went wrong");
                    $("#load_btn").attr("disabled", false);
                }
                 
            });
        });

     $("#load_cum_transcript").submit(function (e) {
         e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator2').html("Loading...");
            $("#load_cum_btn").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/files/Transcript/load_cum_transcript.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function (data) {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Load transcript");
                    $("#load_cum_btn").attr("disabled", false);
                    $("#transcript").html(data);
                 },
                error:function(error){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Load transcript");
                    pop_wrong("Something went wrong");
                    $("#load_cum_btn").attr("disabled", false);
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