<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Intakes</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Intakes</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New intake</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_intake" action="save_intake" method="POST">
                                            <div class="row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program Type</label>
                                                    <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type">
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$camp_id."'");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." [".$progs_faculty['prg_type_short_name']."]"; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Academic Year</label>
                                                    <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id" required>
                                                        <?php
                                                            $sql_acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                                                            $sql_acad->execute();
                                                            $i=1;
                                                            while($progs_acad=$sql_acad->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_acad['acad_cycle_id']; ?>"><?php echo $progs_acad['acad_year']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Intake Month</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="month" class="form-control" id="intake_month" placeholder="Intake month"  required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4"  style="padding:0px;">
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Intake start</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="intake_start" placeholder="intake start" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Intake End</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="intake_end" placeholder="intake end" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4"  style="padding:0px;">
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Application start</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="app_start" placeholder="application start" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Application End</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="app_end" placeholder="application end" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="padding:0px;">
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Registration start</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="reg_start" placeholder="registration start" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Registration End</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" id="reg_end" placeholder="registration end" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="form-group col-12 col-sm-3 col-lg-3">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary form-control btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Registered Intakes</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="intakes_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Intake Month</th>
                                                    <th scope="col">Academic Year</th>
                                                    <th scope="col">Intake Start</th>
                                                    <th scope="col">Intake End</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_intake.*,
                                                                        tbl_program_type.prg_type_full_name,
                                                                        tbl_acad_cycle.acad_year
                                                                        FROM tbl_intake 
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_intake.prg_type=tbl_program_type.prg_type_id
                                                                        INNER JOIN tbl_acad_cycle ON 
                                                                        tbl_intake.acad_cycle_id=tbl_acad_cycle.acad_cycle_id
                                                                        ORDER BY tbl_intake.status ASC
                                                                        ");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($progs=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $progs['intake_month']; ?> | <?php echo $progs['prg_type_full_name']; ?></td>
                                                    <td><?php echo $progs['acad_year']; ?></td>
                                                    <td><?php echo $progs['intake_start']; ?></td>
                                                    <td><?php echo $progs['intake_end']; ?></td>
                                                    <td><?php echo $progs['status']==1?'<span class="badge badge-secondary">Active</span>':'<span class="badge badge-warning">Inactive</span>' ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <?php if($progs['status']==1){  ?>
                                                            <button type="button" data-id="<?php echo $progs['intake_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $progs['intake_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; Edit</button>
                                                            <?php } ?>
                                                            <button type="button" data-id="<?php echo $progs['intake_id']; ?>" class="btn btn-icon btn-success btn-sm view"><span id="spinner3_<?php echo $progs['intake_id']; ?>"></span>&nbsp;<i class="fas fa-eye"></i> &nbsp; View</button>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
                                <!--view modal-->
                                    <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="eh_intake_month"></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="">
                                                        <table class="table table-hover table-sm">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">Program Type:</th>
                                                                <th scope="col"><span id="v_prg_type"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Academic Year:</th>
                                                                <th scope="col"><span id="v_acad_cycle"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Intake Month:</th>
                                                                <th scope="col"><span id="v_intake_month"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Intake Start:</th>
                                                                <th scope="col"><span id="v_intake_start"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Intake End:</th>
                                                                <th scope="col"><span id="v_intake_end"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Application Start:</th>
                                                                <th scope="col"><span id="v_app_start"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Application End:</th>
                                                                <th scope="col"><span id="v_app_end"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Registration Start:</th>
                                                                <th scope="col"><span id="v_reg_start"></span></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Registration End:</th>
                                                                <th scope="col"><span id="v_reg_end"></span></th>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   <!--end view modal-->
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="ehu_intake_month"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="i_id" name="i_id">
                                                        <div class="card-body pb-0 row">
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Program Type</label>
                                                            <select class="form-control select2" style="width:100%" name="e_prg_type" id="e_prg_type">
                                                                <?php
                                                                    $sql_prg=$conn->prepare("SELECT * FROM tbl_program_type WHERE status=1");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." [".$progs_faculty['prg_type_short_name']."]"; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Academic Year</label>
                                                            <select class="form-control select2" style="width:100%" name="e_acad_cycle_id" id="e_acad_cycle_id" required>
                                                                <?php
                                                                    $sql_acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                                                                    $sql_acad->execute();
                                                                    $i=1;
                                                                    while($progs_acad=$sql_acad->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $progs_acad['acad_cycle_id']; ?>"><?php echo $progs_acad['acad_year']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Intake Month</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                    </div>
                                                                </div>
                                                                <input type="month" class="form-control" id="e_intake_month" placeholder="Intake month"  required>
                                                            </div>
                                                        </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Intake start</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_intake_start" placeholder="intake start" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Intake End</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_intake_end" placeholder="intake end" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Application start</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_app_start" placeholder="application start" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Application End</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_app_end" placeholder="application end" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Registration start</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_reg_start" placeholder="registration start" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                                <label>Registration End</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="date" class="form-control" id="e_reg_end" placeholder="registration end" min="<?php echo date('Y-m-d'); ?>" required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#intakes_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    
//save intake
    $("#save_intake").submit(function(e){
            e.preventDefault();
        if($("#intake_start").val()>$("#intake_end").val()){
            pop_wrong("intake dates was set wrong!");
        }
        else if($("#app_start").val()>$("#app_end").val()){
            pop_wrong("Application dates was set wrong!");
        }
        else if($("#reg_start").val()>$("#reg_end").val()){
            pop_wrong("Registration dates was set wrong!");
        }
        else{
        var formData = {
            prg_type:$("#prg_type").val(),
            acad_cycle_id:$("#acad_cycle_id").val(),
            intake_month:$("#intake_month").val(),
            intake_start:$("#intake_start").val(),
            intake_end:$("#intake_end").val(),
            app_start:$("#app_start").val(),
            app_end:$("#app_end").val(),
            reg_start:$("#reg_start").val(),
            reg_end:$("#reg_end").val(),
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Intakes/intake_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_intake')[0].reset();
                        pop_up_success(data.message);
                        $('#intakes_table').load(location.href + " #intakes_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
        }
    });
      
// view intake
        $(document).on('click','.view',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view'
                    };
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Intakes/intake_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    $("#v_prg_type").html(data.prg_type_full_name);
                    $("#v_acad_cycle").html(data.acad_year);
                    $("#v_intake_month").html(data.intake_month);
                    $("#eh_intake_month").html(data.intake_month+" | "+data.prg_type_full_name);
                    $("#v_intake_start").html(data.intake_start);
                    $("#v_intake_end").html(data.intake_end);
                    $("#v_app_start").html(data.app_start);
                    $("#v_app_end").html(data.app_end);
                    $("#v_reg_start").html(data.reg_start);
                    $("#v_reg_end").html(data.reg_end);
                    $('#viewModal').modal('show');
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
//pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Intakes/intake_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#i_id").val(data_id);
                    $("#ehu_intake_month").html(data.intake_month+" | "+data.prg_type_full_name);
                    $("#e_intake_start").val(data.intake_start);
                    $("#e_intake_end").val(data.intake_end);
                    $("#e_app_start").val(data.app_start);
                    $("#e_app_end").val(data.app_end);
                    $("#e_reg_start").val(data.reg_start);
                    $("#e_reg_end").val(data.reg_end);
                    
                    const date = new Date(data.intake_month);
                    const year = date.getFullYear();
                    const month = (date.getMonth() + 1).toString().padStart(2, "0");
                    const intake_month = `${year}-${month}`;
                    $("#e_intake_month").val(intake_month);
                    
                    var selectElement = document.getElementById('e_prg_type');
                    var selectElement2 = document.getElementById('e_acad_cycle_id');

                    // Set selected values
                    var selectedOption = selectElement.querySelector('option[value="' + data.prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.acad_cycle_id + '"]');
                    selectedOption.selected = true;
                    selectedOption2.selected = true;
                    selectElement.prepend(selectedOption);
                    selectElement2.prepend(selectedOption2);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //update intake
        $("#update_form").submit(function(e){
            e.preventDefault();
        if($("#e_intake_start").val()>$("#e_intake_end").val()){
            pop_wrong("intake dates was set wrong!");
        }
        else if($("#e_app_start").val()>$("#e_app_end").val()){
            pop_wrong("Application dates was set wrong!");
        }
        else if($("#e_reg_start").val()>$("#e_reg_end").val()){
            pop_wrong("Registration dates was set wrong!");
        }
        else{
            var formData = {
                id:$("#i_id").val(),
                prg_type:$("#e_prg_type").val(),
                acad_cycle_id:$("#e_acad_cycle_id").val(),
                intake_month:$("#e_intake_month").val(),
                intake_start:$("#e_intake_start").val(),
                intake_end:$("#e_intake_end").val(),
                app_start:$("#e_app_start").val(),
                app_end:$("#e_app_end").val(),
                reg_start:$("#e_reg_start").val(),
                reg_end:$("#e_reg_end").val(),
                action:'update'
                    };
                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator2').html("Saving...");
                $.ajax({
                    url: "/files/Intakes/intake_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data){
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Save Changes");
                        if(data.status==200){
                            $('#update_form')[0].reset();
                            $('#updateModal').modal('hide');
                            pop_up_success(data.message);
                            $('#intake_table').load(location.href + " #intake_table");
                        }
                        if(data.status==401){
                            pop_wrong(data.message);
                        }
                        if(data.status==500){
                            pop_wrong(data.message);
                        }
                    },error: function(){
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Save Changes");
                        pop_wrong("Something went wrong!");
                        
                    }
                });
            }
        });
    });
   function pop_wrong(feedback) {
          iziToast.warning({
    title: 'Wrong',
    message: feedback,
    position: 'topCenter'
  });
    }
    
      function pop_up_success(feedback) {
    iziToast.success({
    title: 'Info:',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>