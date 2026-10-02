<?php
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>My information</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Religion</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row" id="profile">
                        <?php
                            $stmt=$conn->prepare("SELECT * FROM tbl_applicant_church WHERE stu='".$code."'");
                            $stmt->execute();
                            $applicantData=$stmt->fetch();
                        ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Religious belief</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse show" id="mycard-collapse">
                                    <div class="card-body">
                                        <form action="update_church" method="POST" id="update_church" class="row">
                                            <input type="hidden" name="action" value="update_church">
                                            <input type="hidden" name="stu" value="<?php echo $code; ?>">
                                            <div class="form-group col-md-4">
                                                <label>Religion</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                    <i class="fas fa-city"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="church" value="<?php echo $applicantData['church']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Country</label>
                                                <div class="input-group">
                                                    <select name="country" class="form-control select2" style="width:100%;">
                                                        <?php
                                                            $sql_nat=$conn->prepare("SELECT * FROM tbl_country");
                                                            $sql_nat->execute();
                                                            $i=1;
                                                            while($country = $sql_nat->fetch()){
                                                        ?>
                                                        <option value="<?php echo $country['cntr_id']; ?>" <?php echo $country['cntr_id']== 167 ?'selected':''; ?>><?php echo $country['cntr_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>District / City</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="city" value="<?php echo $applicantData['city']; ?>" required>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group col-md-4">
                                                <label style="opacity: 0;">Save changes</label><br/>
                                                <button type="submit" class="btn btn-primary"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                                            </div>
                                        </form>
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
        //Update Personal
        $("#update_church").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_p').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    if(data.status==200){
                        window.location.reload();
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        $(document).on('change', '#mnist', function(){
            if($(this).val() == 'yes'){
                $("#mnistact").removeAttr('hidden');
                $("#activities").attr('required', true);
            }else{
                $("#mnistact").attr('hidden', true);
                $("#activities").removeAttr('required');
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