<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Marks</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Marks</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content tab-bordered" id="myTab3Content">
                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab">
                                    <form id="load_marks" action="load_marks" method="POST" autocomplete="off">
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
                                                <button type="submit" class="btn btn-primary" id="load_btn"><span id="spinner"></span>&nbsp;<span id="indicator">Load marks</span></button>
                                            </div> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body" id="marks">
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
     $("#load_marks").submit(function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
        $('#indicator').html("Loading...");
        $("#load_btn").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "/files/Marks/load_marks_single_student.php",
            data: formdata,
            mimeTypes:"multipart/form-data",
            contentType:false,
            processData:false,
            success: function (data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Load marks");
                $("#load_btn").attr("disabled", false);
                $("#marks").html(data);
             },
            error:function(error){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Load marks");
                pop_wrong("Something went wrong");
                $("#load_btn").attr("disabled", false);
            }
             
        });
    });
    
     $(document).on('submit', "#save_ind_marks", function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#spinner8').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
        $('#indicator8').html("Loading...");
        $("#save_btn").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "/files/Marks/mark_controller.php",
            data: formdata,
            mimeTypes:"multipart/form-data",
            contentType:false,
            processData:false,
            success: function (data) {
                $('#spinner8').fadeOut('fast');
                $('#indicator8').html("Load marks");
                $("#save_btn").attr("disabled", false);
                $("#load_btn").trigger('click');
             },
            error:function(error){
                $('#spinner8').fadeOut('fast');
                $('#indicator8').html("Load marks");
                pop_wrong("Something went wrong");
                $("#save_btn").attr("disabled", false);
            }
             
        });
    });
    
    $(document).on('keyup input','.marks',function(){
        var module = $(this).data("id");
        $("#mod_"+module).attr('checked',true);

        if($("#cat_"+module).val() == '' && $("#exam_"+module).val() == ''){
            $("#mod_"+module).removeAttr('checked');
        }
        
    })
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