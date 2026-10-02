<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
        <?php 
            $stmt = $conn->prepare("SELECT * FROM tbl_faculty WHERE fac_id IN($faculty)");
            $stmt->execute();
            $data = $stmt->fetch();
        ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Graduation List</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="load_class_list2" action="" method="POST">
                                    <input type="hidden" name="action" value="load_class_list">
                                    <div class="card-body pb-0 row">
                                        <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                        <input type="hidden" name="fac_id" id="fac_id" value="<?php echo $data['fac_id']; ?>">
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Department</label><br>
                                            <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                                <option></option>
                                                <?php
                                                    $sql=$conn->prepare("SELECT * FROM tbl_department WHERE fac_id IN($faculty) ORDER BY dept_id ASC");
                                                    $sql->execute();
                                                    while($department=$sql->fetch()){
                                                ?>
                                                <option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span id="spinner3"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Specialization</label><br>
                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                <?php 
                                                    $stmt = $conn->prepare("SELECT * FROM tbl_specialization WHERE fac_id IN($faculty)");
                                                    $stmt->execute();
                                                    while($splz = $stmt->fetch()){
                                                ?>
                                                <option value="<?php echo $splz['splz_id']; ?>"><?php echo $splz['splz_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Graduation Date</label><br>
                                            <select class="form-control select2" style="width:100%" name="acad_grad" required>
                                                <?php 
                                                    $sql_acad=$conn->prepare("SELECT gc.*,
                                                                    ac.acad_year
                                                                    FROM tbl_grad_cycle gc 
                                                                    INNER JOIN tbl_acad_cycle ac ON 
                                                                    gc.acad_cycle_id = ac.acad_cycle_id
                
                                                                    ORDER BY gc.grad_cycle_id ASC
                                                                    ");
                                                    $sql_acad->execute();
                                                    while($row=$sql_acad->fetch()){
                                                        
                                                ?>
                                                <option value="<?php echo $row['grad_cycle_id']; ?>"><?php echo $row['grad_date']; ?> | <?php echo $row['acad_year']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row;justify-content:center;">
                                            <button type="submit" class="btn btn-primary col-6 col-sm-6 col-lg-2" id="loagingdata"><span id="spinner20"></span>&nbsp;<i class="fas fa-wifi"></i>&nbsp;<span id="indicator20">&nbsp;Go</span></button>
                                        </div> 
                                    </div>
                                </form>
                                <form id="load_general_report" action="load_general_report" >
                                    <div class="card-body" id="stlist" style="border:2px solid grey; border-radius:5px;" hidden><br>
                                        <div id="contentsData"></div>
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
        //load specs
        $("#dept_id").change(function () {
            var formdata = {
                department: $("#dept_id").val(),
                action: "load_specs"
            };
            $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#spec").attr("hidden", true);
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner3').fadeOut('fast');
                    $("#splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                    }
                   $("#lev").removeAttr("hidden");
                   $("#spec").removeAttr("hidden");
                   $("#btn").removeAttr("hidden");
                },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
            });
        });
        $("#load_class_list2").submit(function(e){
            e.preventDefault(); 
            var formData = new FormData(document.getElementById('load_class_list2'));
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/academic/graduation_List.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (checkoutHTML){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Go");
                    $("#stlist").removeAttr("hidden");
                    $('#contentsData').html(checkoutHTML);
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Go");
                    pop_wrong("Something went wrong!");
                }
            });
        });
    });
</script>