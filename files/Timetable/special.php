<?php
    $prg_type = $_GET['prg'];
    $splz = $_GET['splz'];
    $level = $_GET['level'];
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Timetable</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Special</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>Generate</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body" style="border: 2px solid #0a7033; width: 95%; margin: auto; margin-bottom: 20px; border-radius: 5px">
                                <form action="" id="generator" class="row" method="POST">
                                    <input type="hidden" name="splz_id" value="<?php echo $splz; ?>">
                                    <input type="hidden" name="level_id" value="<?php echo $level; ?>">
                                    <input type="hidden" class="form-check-input" value="yes" name="include_exam" required>
                                    <div class="form-group col-12" style="border-right: 1px solid grey;">
                                        <h6>Modules List</h6>
                                        <hr/>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Selected</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Start Date</th>
                                                    <th scope="col">End Date</th>
                                                    <th scope="col">Comment</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT 
                                                                                tm.module_id,
                                                                                m.module_name
                                                                            FROM tbl_modules tm 
                                                                                INNER JOIN modules m ON 
                                                                                    tm.mod_id = m.module_id
                                                                            WHERE tm.splz_id = ? AND tm.level_id = ? AND tm.status = ?
                                                                            ORDER BY m.module_id ASC");
                                                    $sql->execute([$splz, $level, 1]);
                                                    while($mods=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th><input type="checkbox" name="selectedModules[]" value="<?php echo $mods['module_id']; ?>"></th>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <th><input type="date" class="form-control" name="start_date_<?php echo $mods['module_id']; ?>" value="<?php echo date("Y-m-d"); ?>" required></th>
                                                    <th><input type="date" class="form-control" name="end_date_<?php echo $mods['module_id']; ?>" value="<?php echo date("Y-m-d", strtotime('+2 weeks')); ?>" required></th>
                                                    <th><input type="comment" class="form-control" name="comment_<?php echo $mods['module_id']; ?>"></th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group col-12 row">
                                        <div class="form-group col-12 col-md-6">
                                            <label>Learning mode</label>
                                            <select class="form-control select2" style="width:100%" name="prg_mode">
                                                <?php
                                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status = 1");
                                                    $sql_mode->execute();
                                                    while($progs_mode=$sql_mode->fetch()){
                                                ?>
                                                <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Intake</label>
                                            <select class="form-control select2" style="width:100%" name="intake_id">
                                                <?php
                                                    $sql_intakes=$conn->prepare("SELECT i.intake_id,i.intake_month,ac.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle ac ON i.acad_cycle_id = ac.acad_cycle_id WHERE i.status=1 AND i.prg_type = ?");
                                                    $sql_intakes->execute([$prg_type]);
                                                    while($intake=$sql_intakes->fetch()){
                                                        ?>
                                                <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Note 1</label>
                                            <input type="text" class="form-control" name="comment_1">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Note 2</label>
                                            <input type="text" class="form-control" name="comment_2">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Note 3</label>
                                            <input type="text" class="form-control" name="comment_3">
                                        </div>
                                    
                                    </div>
                                    <div class="modal-footer bg-whitesmoke col-12">
                                        <button type="submit" class="btn btn-primary" id="genBtn"><span id="spinner200"></span>&nbsp;<span id="indicator200">Generate</span></button>
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
        $(document).on("submit", "#generator", function (e) {
            e.preventDefault();
            var formdata= new FormData(this);
            $('#spinner200').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator200').html("generating...");
            $.ajax({
                type: "POST",
                url: "/files/Timetable/logic-special.php",
                data: formdata,
                dataType:"JSON",
                contentType: false,
                processData: false,
                success:function(data){
				    $('#spinner200').fadeOut('fast');
				    $('#indicator200').html("Generate");
				    if(data.status == 200){
				    pop_up_success(data.message);
				    } else{
				        pop_info(data.message);
				    }
				},
				error:function(error){
				    $('#spinner200').fadeOut('fast');
				    $('#indicator200').html("Generate");
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
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
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function disable_inputs(){
        $('#prg_type').attr('disabled',true);
        $('#splz_id').attr('disabled',true);
        $('#level_id').attr('disabled',true);
        $('#loader').attr('disabled',true);
    }
    function enable_inputs(){
        $('#prg_type').removeAttr('disabled');
        $('#splz_id').removeAttr('disabled');
        $('#level_id').removeAttr('disabled');
        $('#loader').removeAttr('disabled');
    }
</script>