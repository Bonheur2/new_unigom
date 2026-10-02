<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
    <section class="section">
        <div class="section-header">
            <h3>Modules by Level</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Review</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Program information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM tbl_department WHERE dept_id IN($department)");
                            $stmt->execute();
                            $data = $stmt->fetch();
                        ?>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="load_modules" action="load_modules" method="POST">
                                    <div class="card-body pb-0 row">
                                        <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                        <input type="hidden" name="fac_id" id="fac_id" value="<?php echo $data['fac_id']; ?>">
                                        <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $department; ?>">

                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Specialization</label><br>
                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                <?php 
                                                    $stmt = $conn->prepare("SELECT * FROM tbl_specialization WHERE dept_id IN($department)");
                                                    $stmt->execute();
                                                    while($splz = $stmt->fetch()){
                                                ?>
                                                <option value="<?php echo $splz['splz_id']; ?>"><?php echo $splz['splz_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Level</label><br>
                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                                <?php 
                                                    $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type = '".$data['prg_type']."'");
                                                    $stmt->execute();
                                                    while($lev = $stmt->fetch()){
                                                ?>
                                                <option value="<?php echo $lev['level_id']; ?>"><?php echo $lev['level_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label style="opacity: 0;">Level</label><br>
                                            <button type="submit" class="btn btn-sm btn-primary" id="btn"><span id="spinner20"></span>&nbsp;<span id="indicator20">Load modules</span></button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12" id="mod" hidden>
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <div class="col-9 col-md-10">
                                <h4>Registered Modules</h4>
                            </div>
                        </div>
                        <div class="card-body" id="modules"></div>
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
        //load modules
        $("#load_modules").submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $('#modules').html("");
            $('#btn').attr('disabled', true);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/hod/files/Modules/load_modules.php",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#spinner20').fadeOut('fast');
                    $('#mod').removeAttr('hidden');
                    $('#btn').removeAttr('disabled');
                    $('#modules').html(data);
                 },
                error:function(error){
                    $('#spinner20').fadeOut('fast');
                    $('#btn').removeAttr('disabled');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
    });
</script>