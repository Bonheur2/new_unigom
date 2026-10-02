<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $checkPermission=$conn->prepare("SELECT sts FROM tbl_admittedPRG WHERE Stu_code = '".$code."' AND sts=1");
    $checkPermission->execute();
    $permission=$checkPermission->rowCount();
    
    $dt=date('Y-m-d');
    $checkApplications=$conn->prepare("SELECT intake_month FROM tbl_intake WHERE app_end>= '".$dt."' AND status=1");
    $checkApplications->execute();
    $appEnabled=$checkApplications->rowCount();
    
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section ">
                <div class="section-header">
                    <h3>My application</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Courses</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <?php  if($appEnabled>0){ ?>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Submit Application</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_application" action="save_application" method="POST">
                                            <input type="hidden" name="action" value="save_option">
                                            <div class="card-body pb-0 row">
                                                <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                    <select class="form-control select2" style="width:100%;" name="cump_id" id="cump_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="prg">
                                                    <label>Program Type</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type" id="prg_type" required>
                                                    </select>
                                                    &nbsp;<span id="spinner00"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="splz_id" id="splz_id" required>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="lvl">
                                                    <label>Level</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_lvl" id="prg_lvl" required>
                                                    </select>
                                                    <span id="spinnerLvl"></span>
                                                </div>


                                                 <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="mode">
                                                    <label>Learning Mode</label>
                                                    <select class="form-control select2 mode" style="width:100%;" name="mode" id="mode">
                                                        <option></option>
                                                        <?php
                                                            $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode");
                                                            $sql_mode->execute();
                                                            $i=1;
                                                            while($progs_mode=$sql_mode->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                 </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="intake">
                                                    <label>Intake</label>
                                                    <select class="form-control select2" style="width:100%;" name="intake_id" id="intake_id" required>
                                                    </select>
                                                </div>
                                               
                                                <div class="col-12 col-sm-12 col-lg-12" style="display:none;" id="appBtn">
                                                    <div style="display:flex;fex-direction:row;justify-content:center;">
                                                        <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save selection</span></button>    
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- Start invoice main Content -->
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Pending Invoice</h4>
                                    </div>
                                    <div class="card-body" id="applications_invoice">
                                    <?php
                                    $select_pending_invoice="SELECT * FROM tbl_invoice WHERE reg_no='$code' AND payment_status='0' AND fee_id='7'";
                                    $cselect_pending_invoice=$conn->prepare($select_pending_invoice);
                                    $cselect_pending_invoice->execute();
                                    $row_cselect_pending_invoice=$cselect_pending_invoice->fetch();
                                    if($row_cselect_pending_invoice){
                                        $select_fee="SELECT * FROM fee_category WHERE id='".$row_cselect_pending_invoice['fee_id']."'";
                                        $cselect_fee=$conn->prepare($select_fee);
                                        $cselect_fee->execute();
                                        $row_cselect_fee=$cselect_fee->fetch();
                                    ?>
                                    <div class="container mt-3">
                                    <div class="row">
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                        <div class="text-job"><b>Fee Category</b></div>
                                        <div class="user-detail-name"><a href="#"><b><?php echo $row_cselect_fee['name']; ?></b></a></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                        <div class="text-job"><b>Amount</b></div>
                                        <div class="user-detail-name"><a href="#"><b><?php echo $row_cselect_pending_invoice['balance']; ?></b></a></div>
                                        </div>
                                    </div>
                                    </div>
                                    <button  class="btn btn-success mb-3" type="submit">Pay Fee</button>
                                    </div>
                                    
                                    <?php
                                    }
                                    ?>
                                    </div>
                                    
                            </div>
                        
                        </div>
                        <!-- End invoice main Content -->
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Submitted Applications</h4>
                                    </div>
                                    <div class="card-body" id="applications">
                                        <?php
                                            $sql=$conn->prepare("SELECT
                                                                    tbl_admittedPRG.*,
                                                                    tbl_campus.camp_full_name,
                                                                    tbl_program_type.prg_type_full_name,
                                                                    tbl_specialization.splz_full_name,
                                                                    tbl_intake.intake_month,
                                                                    tbl_program_mode.prg_mode_full_name,
                                                                    tbl_level.level_full_name
                                                                    
                                                                FROM
                                                                    tbl_admittedPRG
                                                                INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                                INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                                INNER JOIN tbl_specialization ON tbl_admittedPRG.dept_id = tbl_specialization.splz_id
                                                                INNER JOIN tbl_intake ON tbl_admittedPRG.intake_id = tbl_intake.intake_id
                                                                INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                INNER JOIN tbl_level ON tbl_admittedPRG.level_id = tbl_level.level_id
                                                                WHERE
                                                                     tbl_admittedPRG.Stu_code = '".$code."' AND tbl_intake.status = 1;
                                                                    ");
                                            $sql->execute();
                                            $i=1;
                                            while($apps=$sql->fetch()){
                                                $prg=$apps['prg_type'];
                                        ?>
                                        <div class="card-body row" style="border-radius:5px;margin-bottom:10px;
                                                                        <?php if($apps['sts']==1){
                                                                        echo 'border: 2px solid grey';
                                                                        } elseif($apps['sts']==2){
                                                                        echo 'border: 2px solid orange';
                                                                        } elseif($apps['sts']==3){
                                                                        echo 'border: 2px solid green';
                                                                        }elseif($apps['sts']==4){
                                                                        echo 'border: 2px solid red';
                                                                        } ?>">
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Campus</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['camp_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job"><b>Program Type</b></div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['prg_type_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Program</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['splz_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                            <div class="article-user-details">
                                                    <div class="text-job">Level</div>
                                                    <div class="user-detail-name"><a href="#"><b><?php echo $apps['level_full_name']; ?></b></a></div>
                                                     </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Intake</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['intake_month']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Learning Mode</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['prg_mode_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Status</div>
                                                        <div class="user-detail-name"><a href="#"><b>
                                                            <?php if($apps['sts']==1){
                                                                echo "Pending";
                                                            }
                                                            else if($apps['sts']==2){
                                                                echo "Under review";
                                                            }
                                                            else if($apps['sts']==3){
                                                                echo "Admitted";
                                                            }
                                                            else{
                                                                echo "Rejected";
                                                            }
                                                             ?>
                                                            
                                                            </b></a></div>
                                                    </div>
                                            </div>
                                            <?php if($apps['rej_comment'] != NULL){ ?>
                                            <div class="form-group col-12 col-md-4 col-lg-4">
                                                <div class="article-user-details">
                                                    <div class="text-job text-danger">Message</div>
                                                    <div class="user-detail-name">
                                                        <a href="#">
                                                            <b>
                                                                <?php echo $apps['rej_comment']; ?>
                                                            </b>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if($apps['sts']==1){ ?>
                                            <div class="form-group col-12 col-md-12 col-lg-12" style="display:flex;flex-direction:row;justify-content:center">
                                                <button class=" col-6 col-md-2 col-lg-1 btn btn-danger btn-sm delete" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinnerdel_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatordel_<?php echo $apps['Aprg_id']; ?>">Remove</span>&nbsp;</button>&nbsp;&nbsp;
                                                <button class=" col-6 col-md-2 col-lg-1 btn btn-primary btn-sm update" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinnerup_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatorup_<?php echo $apps['Aprg_id']; ?>">update</span>&nbsp;</button>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php if($permission>0){ ?>
                                        <div class="card-footer">
                                            <a class=" col-6 col-md-2 col-lg-2 btn btn-primary btn-sm" href="edu?mis=apDocs&prg=<?php echo $prg; ?>">upload documents</a>    
                                        </div>
                                    <?php } ?>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>My documents</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse-doc" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse-doc">
                                    <div class="card-body row">
                                        <?php
                                            $sql2=$conn->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = '".$code."' ORDER BY appl_doc_id  DESC");
                                            $sql2->execute();
                                            $i=1;
                                            while($docs=$sql2->fetch()){
                                                $string=$docs['upload_doc'];
                                                $parts = explode('/', $string);
                                                $after_slash = end($parts);
                                                $parts = explode('_', $after_slash);
                                                $before_underscore = reset($parts);
                                                
                                                $isql2=$conn->prepare("SELECT * FROM tbl_document_type WHERE file_name = '".$before_underscore."' ");
                                                $isql2->execute();
                                                $moreInfo=$isql2->fetch();
                                                ?>
                                                
                                                <div class="col-12 col-md-4 col-lg-4">
                                                    <article class="article">
                                                        <div class="article-header">
                                                            <div class="article-image" data-background="../..<?php echo $moreInfo['file_type']=="image"?$docs['upload_doc']:"/student_docs/pdf.png"; ?>">
                                                            </div>
                                                            <div class="article-title">
                                                                <h2><a href="../..<?php echo $docs['upload_doc']; ?>" target="_blank"><?php echo $moreInfo['document_name'] ?></a></h2>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </div>
                                                <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating Application</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="app_id" id="app_id">
                                                    <input type="hidden" name="action" value="update_option">
                                                    <input type="hidden" name="code" value="<?php echo $code; ?>">
                                                    <div class="form-group">
                                                        <label>Campus</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_cump_id" id="e_cump_id" required>
                                                            <?php
                                                                $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                                $sql_prg->execute();
                                                                $i=1;
                                                                while($progs_faculty=$sql_prg->fetch()){
                                                                    ?>
                                                            <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                        <span id="spinner-0"></span>
                                                    </div>
                                                    <div class="form-group" id="e_prg">
                                                        <label>Program Type</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_prg_type" id="e_prg_type" required>
                                                        </select>
                                                        <span id="spinner-00"></span>
                                                    </div>
                                                    <div class="form-group" id="e_dept">
                                                        <label>Program</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_splz_id" id="e_splz_id" required>
                                                        </select>
                                                        <span id="spinner-000"></span>
                                                    </div>
                                                    <div class="form-group" id="e_lvl">
                                                        <label>Level</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_prg_lvl" id="e_prg_lvl" required>
                                                        </select>
    
                                                    </div>
                                                    <div class="form-group" id="e_intake">
                                                        <label>Intake</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_intake_id" id="e_intake_id" required>
                                                        </select>
    
                                                    </div>
                                                    <div class="form-group" id="e_mode">
                                                        <label>Learning Mode</label>
                                                        <select class="form-control select2" style="width:100%;" name="e_mode" id="e_mode">
                                                            <?php
                                                                $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode");
                                                                $sql_mode->execute();
                                                                $i=1;
                                                                while($progs_mode=$sql_mode->fetch()){
                                                                    ?>
                                                            <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
    
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" id="e_appBtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
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

    $('#department_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
//load program types
     $("#cump_id").change(function () {
            var campus = $("#cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#prg').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#intake').css({'display':'none'});
            $('#mode').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#prg').css({'display':'block'});
                    $("#prg_type").empty();
                   if(data.length>0){
                        $("#prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating program types
     $("#e_cump_id").change(function () {
            var campus = $("#e_cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#e_prg').css({'display':'none'});
            $('#e_dept').css({'display':'none'});
            $('#e_intake').css({'display':'none'});
            $('#e_mode').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $('#e_prg').css({'display':'block'});
                    $("#e_prg_type").empty();
                   if(data.length>0){
                        $("#e_prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +" ["+value.prg_type_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
//load programs
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                prg_type: p_type,
                action: "load-specs"
            };
            $('#dept').css({'display':'none'});
            $('#intake').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                     $('#mode').css({'display':'block'});
                    $("#splz_id").empty();
                   if(data.length>0){
                        $('#appBtn').css({'display':'block'});
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating programs
     $("#e_prg_type").change(function () {
            var p_type = $("#e_prg_type").val();
            var formdata = {
                prg_type: p_type,
                action: "load-specs"
            };
            $('#e_dept').css({'display':'none'});
            $('#e_intake').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#e_dept').css({'display':'block'});
                    $("#e_splz_id").empty();
                   if(data.length>0){
                       $('#e_appBtn').css({'display':'block'});
                        $.each(data, function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load level
$("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            // console.log(p_type);
            var formdata = {
                prg_type: p_type,
                action: "load_levelss"
            };
            $('#lvl').hide(); // Hide initially
            $('#spinnerLvl').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
    type: "POST",
    url: "/files/Programs/program_controller.php",
    data: formdata,
    dataType: "JSON",
    success: function (data) {
        $('#spinnerLvl').fadeOut('fast');
        $('#lvl').show(); // Show level dropdown on success
        $("#prg_lvl").empty(); // Clear existing options
        if (data && data.length > 0) {
            $("#prg_lvl").append("<option value=''>Select Level</option>");
            $.each(data, function (index, level) {
                $("#prg_lvl").append("<option value='" + level.level_id + "'>" + level.level_full_name + "</option>");
            });
        } else {
            $("#prg_lvl").append("<option value=''>No Levels Available</option>");
        }
    },
    error: function (xhr, status, error) {
        $('#spinnerLvl').fadeOut('fast');
        console.error("Error: " + xhr.responseText);
        pop_wrong("Something went wrong while loading levels!");
    }
});

        });

// Load updating level for update form
$("#e_prg_type").change(function () {
    var p_type = $("#e_prg_type").val();
    console.log(p_type);
    var formdata = {
        prg_type: p_type,  // Ensure this key matches the backend
        action: "load_levelss" // Update the action if needed
    };
    $('#e_lvl').hide(); // Hide initially
    $('#spinnerLvl').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
    
    $.ajax({
        type: "POST",
        url: "/files/Programs/program_controller.php",
        data: formdata,
        dataType: "JSON",
        success: function (data) {
            console.log(data)
            $('#spinnerLvl').fadeOut('fast');
            $('#e_lvl').show(); // Show level dropdown on success
            $("#e_prg_lvl").empty(); // Clear existing options
            if (data && data.length > 0) {
                $("#e_prg_lvl").append("<option value=''>Select Level</option>");
                $.each(data, function (index, level) {
                    $("#e_prg_lvl").append("<option value='" + level.level_id + "'>" + level.level_full_name + "</option>");
                });
            } else {
                $("#e_prg_lvl").append("<option value=''>No Levels Available</option>");
            }
        },
        error: function (xhr, status, error) {
            $('#spinnerLvl').fadeOut('fast');
            console.error("Error: " + xhr.responseText);
            pop_wrong("Something went wrong while loading levels!");
        }
    });
});

//load intakes


     $("#mode").change(function () {
         var mode=$(".mode").val();
         if(mode!=7){
           var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_intakes"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#intake').css({'display':'block'});
                    $('#mode').css({'display':'block'});
                    $("#intake_id").empty();
                  if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                  }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });   
         }
         else{
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_intakes_online"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#intake').css({'display':'block'});
                    $('#mode').css({'display':'block'});
                    $("#intake_id").empty();
                  if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                  }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });   
         
         }
           
        });

//load intakes
     $("#e_prg_type").change(function () {
            var type = $("#e_prg_type").val();
            var formdata = {
                type: type,
                action: "load_intakes"
            };
             $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#e_intake').css({'display':'block'});
                    $('#e_mode').css({'display':'block'});
                    $("#e_intake_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
//save application
    $("#save_application").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save option");
                    if(data.status==200){
                        $('#sample-login').load(location.href + " #sample-login");
                        pop_up_success(data.message);
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
          });
          
//update application
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    if(data.status==200){
                        $('#applications').load(location.href + " #applications");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });

//remove application
    $(document).on('click', '.delete',function(e){
            e.preventDefault();
        var app=$(this).data('id');
        var formData = {
            app:app,
            action:'remove'
        }
            swal({
            title: "Are you sure?",
            text: "Once removed, data will never be recovered!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinnerdel_'+app).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicatordel_'+app).html("removing...");
                    $.ajax({
                        url: "/files/application/application_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        success: function(data){
                            $('#spinnerdel_'+app).fadeOut('fast');
                            $('#indicatordel_'+app).html("remove");
                            if(data.status==200){
                                $('#applications').load(location.href + " #applications");
                                $('#applications_invoice').load(location.href + " #applications_invoice");
                                pop_up_success(data.message);
                            }
                            if(data.status==401){
                                pop_wrong(data.message);
                            }
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                        },error: function(){
                            $('#spinnerdel_'+app).fadeOut('fast');
                            $('#indicatordel_'+app).html("remove");
                            pop_wrong("Something went wrong!");
                        }
                     });
            }
           else {
                swal("Delete Cancelled!!");
            }
        });
    });

        
    //pre-update View
        $(document).on('click', '.update',function(e){
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_option'
                    };
            $('#spinnerup_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    console.log(data);
                    $('#spinnerup_'+data_id).fadeOut('fast');
                    $("#app_id").val(data_id);
                    var selectElement0 = document.getElementById('e_cump_id');
                    var selectElement1 = document.getElementById('e_prg_type');
                    var selectElement2 = document.getElementById('e_splz_id');
                    var selectElement3 = document.getElementById('e_intake_id');
                    var selectElement4 = document.getElementById('e_mode');
                    var selectElement5 = document.getElementById('e_prg_lvl');
                    $("#e_prg_type").empty();
                    $("#e_splz_id").empty();
                    $("#e_intake_id").empty();
                    $("#e_prg_lvl").empty();
                    $.each(data[1], function (index, value) {
                            $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                    $.each(data[3], function (index, value) {
                            $("#e_intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                    $.each(data[4], function (index, value) {
                            $("#e_prg_lvl").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    // Set selected values
                    var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].cump_id + '"]');
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].intake_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].mode + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].level_id + '"]');
                    selectedOption0.selected = true;
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinnerup_'+data_id).fadeOut('fast');
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