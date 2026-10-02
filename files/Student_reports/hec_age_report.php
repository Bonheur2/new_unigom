<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Student reports</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Age Counter</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="load_hec_report" action="load_hec_report">
                                <div class="card-body pb-0 row">
                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label>Academic Year</label><br>
                                        <select class="form-control select2" style="width:100%;" name="acad_cycle_id">
                                            <option></option>
                                            <?php
                                                $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY acad_cycle_id DESC");
                                                $sql->execute();
                                                while($acad=$sql->fetch()){
                                                    ?>
                                            <option value="<?php echo $acad['acad_cycle_id']; ?>"><?php echo $acad['acad_year']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label style="visibility: hidden;">Load button</label><br>
                                        <button type="submit" class="btn btn-primary" id="load_btn"><span id="spinner4"></span>&nbsp;<span id="indicator4">Load data</span></button>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12 table-responsive" id="report">
                                    </div>
                                </div>
                            </form>
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
    //load_report
    $("#load_hec_report").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator4').html("Loading...");
        $.ajax({
            url: "/files/Student_reports/load_hec_age_report.php",
            type: "POST",
            data: formData,
            mimeTypes:"multipart/form-data",
            contentType:false,
            processData:false,
            success: function(data){
                $('#spinner4').fadeOut('fast');
                $('#indicator4').html("Load data");
                $("#report").html(data);
            },error: function(){
                $('#spinner4').fadeOut('fast');
                $('#indicator4').html("Load data");
                pop_wrong("Coming soon!");
                
            }
        });
    });
});
</script>