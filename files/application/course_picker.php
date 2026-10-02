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
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="e_camp_id" required>
                                                                <option selected disabled>Select Campus</option>
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
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id" id="e_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="e_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="e_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="e_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Mode</label>
                                                     <select class="form-control select2" style="width:100%;" name="prg_mode_id" id="e_prg_mode_id" required>
                                                     <option selected disabled>Select Mode</option>
                                                                
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
                        
                        <!-- Start invoice main Content -->
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Pending Invoice</h4>
                                    </div>
                                    <div class="card-body" id="applications_invoice">
                                    <?php
                                    $select_pending_invoice="SELECT * FROM tbl_invoice WHERE reg_no='$code' AND payment_status='0'";
                                    $cselect_pending_invoice=$conn->prepare($select_pending_invoice);
                                    $cselect_pending_invoice->execute();
                                    $row_cselect_pending_invoice=$cselect_pending_invoice->fetch();
                                    if($row_cselect_pending_invoice){
                                        $select_fee="SELECT * FROM tbl_fee_category WHERE id='".$row_cselect_pending_invoice['fee_id']."'";
                                        $cselect_fee=$conn->prepare($select_fee);
                                        $cselect_fee->execute();
                                        $row_cselect_fee=$cselect_fee->fetch();
                                        
                                        $select_payment="SELECT * FROM payment_trial where reg_no='".$row_cselect_pending_invoice['reg_no']."' and fee_id='".$row_cselect_pending_invoice['fee_id']."'";
                                        $cselect_payment=$conn->prepare($select_payment);
                                        $cselect_payment->execute();
                                        $row_cselect_payment=$cselect_payment->fetch();
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
                                        <div class="user-detail-name"><a href="#"><b><?php echo $row_cselect_pending_invoice['balance']." SLL"; ?></b></a></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                        <div class="text-job"><b>Announcement <i class="far fa-bell"></i></b></div>
                                        <div class="user-detail-name">To have your application reviewed, please pay 100% of the  <?php echo $row_cselect_fee['name']; ?>. Thank you!</div>
                                        </div>
                                    </div>
                                    </div>
                                    
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
                                                                    tbl_faculty.fac_full_name,
                                                                    tbl_department.dept_full_name,
                                                                    tbl_specialization.splz_full_name,
                                                                    tbl_program_mode.prg_mode_full_name,
                                                                    tbl_level.level_full_name
                                                                    
                                                                FROM
                                                                    tbl_admittedPRG
                                                                INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                                INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                                INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                                                INNER JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                                                INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                                INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                INNER JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                WHERE
                                                                     tbl_admittedPRG.Stu_code = '".$code."' ;
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
                                                        <div class="text-job"><b>Program</b></div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['prg_type_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">School</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['fac_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                            <div class="article-user-details">
                                                    <div class="text-job">Department</div>
                                                    <div class="user-detail-name"><a href="#"><b><?php echo $apps['dept_full_name']; ?></b></a></div>
                                                     </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Specialization</div>
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
                                                        <div class="text-job">Learning Mode</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $apps['prg_mode_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Status</div>
                                                        <div class="user-detail-name"><a href="#"><b>
                                                            <?php 
                                                            if($apps['sts']==0){
                                                                echo "Complete Payment";
                                                            }
                                                            else if($apps['sts']==1){
                                                                echo "Under review";
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
                                            <?php if($apps['sts']==0){ ?>
                                            
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
                                                    <div class="form-group ">
                                                    <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="u_camp_id" required>
                                                                <option selected disabled>Select Campus</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner00"></span>
                                                </div>
                                                <div class="form-group ">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id" id="u_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                                </div>
                                                <div class="form-group ">
                                                     <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="u_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner02"></span>
                                                </div>
                                                <div class="form-group ">
                                                    <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="u_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner03"></span>
                                                </div>
                                                <div class="form-group ">
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="u_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner04"></span>
                                                </div>
                                                <div class="form-group ">
                                                     <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="u_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner05"></span>
                                                </div>
                                                <div class="form-group ">
                                                     <label>Mode</label>
                                                     <select class="form-control select2" style="width:100%;" name="prg_mode_id" id="u_prg_mode_id" required>
                                                     <option selected disabled>Select Mode</option>
                                                                
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

// Step 1: When Campus is selected     
        $("#e_camp_id").change(function () {
        var campus = $("#e_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#e_prg_type_id').css({'display':'none'});
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for programs
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner0').fadeOut('fast');
                $('#e_prg_type_id').css({'display':'block'}); // Show program section

                // Clear existing program options
                $("#e_prg_type_id").empty();
                $("#e_prg_type_id").append("<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_camp_id").change(function () {
        var campus = $("#u_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#u_prg_type_id').css({'display':'none'});
        $('#u_fac_id').css({'display':'none'});
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

        // Show loading spinner for programs
        $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner0').fadeOut('fast');
                $('#u_prg_type_id').css({'display':'block'}); // Show program section

                // Clear existing program options
                $("#u_prg_type_id").empty();
                $("#u_prg_type_id").append("<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner00').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 2: When Program is selected   
$("#e_prg_type_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for schools
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner1').fadeOut('fast');
                $('#e_fac_id').css({'display':'block'}); // Show school section

                // Clear existing school options
                $("#e_fac_id").empty();
                $("#e_fac_id").append("<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_prg_type_id").change(function () {
        var program = $("#u_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#u_fac_id').css({'display':'none'});
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

        // Show loading spinner for schools
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner1').fadeOut('fast');
                $('#u_fac_id').css({'display':'block'}); // Show school section

                // Clear existing school options
                $("#u_fac_id").empty();
                $("#u_fac_id").append("<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 3: When School is selected
    $("#e_fac_id").change(function () {
        var school = $("#e_fac_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner2').fadeOut('fast');
                $('#e_dept_id').css({'display':'block'}); // Show department section

                // Clear existing department options
                $("#e_dept_id").empty();
                $("#e_dept_id").append("<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //updating 
    $("#u_fac_id").change(function () {
        var school = $("#u_fac_id").val();
        var program = $("#u_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner2').fadeOut('fast');
                $('#u_dept_id').css({'display':'block'}); // Show department section

                // Clear existing department options
                $("#u_dept_id").empty();
                $("#u_dept_id").append("<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 4: When Department is selected
    $("#e_dept_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var school = $("#e_fac_id").val();
        
        var formdata = {
            dept_id: department,
            prg_type:program,
            fac_id:school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#e_splz_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner3').fadeOut('fast');
                $('#e_splz_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_splz_id").empty();
                $("#e_splz_id").append("<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_dept_id").change(function () {
        var department = $("#u_dept_id").val();
        var program = $("#u_prg_type_id").val();
        var school = $("#u_fac_id").val();
        
        var formdata = {
            dept_id: department,
            prg_type:program,
            fac_id:school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#u_splz_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner03').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner03').fadeOut('fast');
                $('#u_splz_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#u_splz_id").empty();
                $("#u_splz_id").append("<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner03').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 5: When Specialization is selected
    $("#e_splz_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type:program,
            action: "load_levels_app"
        };

        // Hide level section initially
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner4').fadeOut('fast');
                $('#e_level_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_level_id").empty();
                $("#e_level_id").append("<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_splz_id").change(function () {
        var department = $("#u_dept_id").val();
        var program = $("#u_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type:program,
            action: "load_levels_app"
        };

        // Hide level section initially
        $('#u_level_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner4').fadeOut('fast');
                $('#e_level_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#u_level_id").empty();
                $("#u_level_id").append("<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 6: When Level is selected
    $("#e_level_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_type:program,
            action: "load_modes_app"
        };

        // Hide level section initially
        $('#e_prg_mode_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner5').fadeOut('fast');
                $('#e_prg_mode_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

                // Clear existing level options
                $("#e_prg_mode_id").empty();
                $("#e_prg_mode_id").append("<option value='' disabled selected>Select Mode</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner5').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_level_id").change(function () {
        var program = $("#u_prg_type_id").val();
        var formdata = {
            prg_type:program,
            action: "load_modes_app"
        };

        // Hide level section initially
        $('#u_prg_mode_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner5').fadeOut('fast');
                $('#u_prg_mode_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

                // Clear existing level options
                $("#u_prg_mode_id").empty();
                $("#u_prg_mode_id").append("<option value='' disabled selected>Select Mode</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_prg_mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner5').fadeOut('fast');
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
                    
                    $('#spinnerup_'+data_id).fadeOut('fast');
                    $("#app_id").val(data_id);
                    var selectElement0 = document.getElementById('u_camp_id');
                    var selectElement1 = document.getElementById('u_prg_type_id');
                    var selectElement2 = document.getElementById('u_fac_id');
                    var selectElement3 = document.getElementById('u_dept_id');
                    var selectElement4 = document.getElementById('u_splz_id');
                    var selectElement5 = document.getElementById('u_level_id');
                    var selectElement6 = document.getElementById('u_prg_mode_id');
                    $("#u_prg_type_id").empty();
                    $("#u_fac_id").empty();
                    $("#u_dept_id").empty();
                    $("#u_splz_id").empty();
                    $("#u_level_id").empty();
                    $("#u_prg_mode_id").empty();
                    $.each(data[1], function (index, value) {
                            $("#u_camp_id").append("<option value='" + value.camp_id + "'>" + value.camp_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#u_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });    
                    $.each(data[3], function (index, value) {
                            $("#u_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                    $.each(data[4], function (index, value) {
                            $("#u_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                    $.each(data[6], function (index, value) {
                            $("#u_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });    
                    $.each(data[5], function (index, value) {
                            $("#u_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    $.each(data[7], function (index, value) {
                            $("#u_prg_mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name +"</option>");
                        });    
                    
                    // Set selected values
                    var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].cump_id + '"]');
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].splz + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].level + '"]');
                    var selectedOption6 = selectElement6.querySelector('option[value="' + data[0].mode + '"]');
                    
                    selectedOption0.selected = true;
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    selectedOption6.selected = true;
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinnerup_'+data_id).fadeOut('fast');
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