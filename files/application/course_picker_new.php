<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    $checkPermission = $conn->prepare("
    SELECT * 
    FROM tbl_admittedPRG 
    WHERE Stu_code = :code
");

$checkPermission->bindParam(':code', $code, PDO::PARAM_STR);
$checkPermission->execute();

$applicantData = $checkPermission->fetch(PDO::FETCH_ASSOC);

$permission = $checkPermission->rowCount();

$cump_id = '';
$prg_type = '';
if ($applicantData) {
    $cump_id = $applicantData['cump_id'];
    $prg_type = $applicantData['prg_type'];
}
    
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
                    <h3>5. Programme Sought - <?php echo $code;?></h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Courses</a></div>
                    </div>
                </div>
                <?php 
                ProgrammeSought($conn, $code); 
                ?>
                <div class="section-body">
                    <div class="row">
                        
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="mb-1">Programme Sought and Interview Location</h4>
                                        <p class="text-info mb-0">
                                            Please note: You are allowed to choose <strong>up to two programmes</strong> of your interest.
                                            Make sure to select them in order of preference.
                                        </p>
                                    </div>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info mt-1" href="#">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="collapse show" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_application" action="save_application" method="POST">
                                            <input type="hidden" name="action" value="save_option">
                                            <div class="card-body pb-0 row">
                                                <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                            <!--<select class="form-control select2" style="width:100%;" name="camp_id" id="e_camp_id" required>-->
                                                            <input type="hidden" name="camp_id" value="<?php echo $cump_id; ?>">
                                                            <select class="form-control select2" style="width:100%;" name="camp_id_display" id="e_camp_id" required disabled>
                                                                <option value="" disabled <?php echo empty($cump_id) ? 'selected' : ''; ?>>Select Campus</option>
                                                                <?php
                                                                    $sql_campus = $conn->prepare("SELECT * FROM tbl_campus");
                                                                    $sql_campus->execute();
                                                                    $i=1;
                                                                    while($data_campus = $sql_campus->fetch()){
                                                                ?>
                                                                <option value="<?php echo $data_campus['camp_id']; ?>"
                                                                    <?php echo (!empty($cump_id) && $cump_id == $data_campus['camp_id']) ? 'selected' : ''; ?>>
                                                                    <?php echo $data_campus['camp_full_name']; ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                            &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program Type</label>
                                                    <!--<select class="form-control select2" style="width:100%;" name="prg_type_id" id="e_prg_type_id" required>-->
                                                    <!--    <option value="" disabled selected>Select Program Type</option>-->
                                                    <!--</select>-->
                                                    <input type="hidden" name="prg_type_id" id="e_prg_type_id_hidden" value="<?php echo htmlspecialchars($prg_type, ENT_QUOTES, 'UTF-8'); ?>">
<select class="form-control select2" style="width:100%;" name="prg_type_id_display" id="e_prg_type_id" required disabled>
    <option value="" disabled selected>Select Program Type</option>
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
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                                </div>
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
                        
                        
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <?php
                                    
                                            $allApps = [];
$sqlAll=$conn->prepare("SELECT cump_id, prg_type FROM tbl_admittedPRG WHERE Stu_code = '".$code."'");
$sqlAll->execute();
while($row=$sqlAll->fetch()){ $allApps[] = $row; }
$campuses = array_unique(array_column($allApps, 'cump_id'));
$programs = array_unique(array_column($allApps, 'prg_type'));
$hasMismatch = count($campuses) > 1 || count($programs) > 1;
?>
                                        <h4>Submitted Applications</h4>
                                        
                                    </div>
                                    
                                    <?php if($hasMismatch){ ?>
                                        <div class="alert alert-danger mt-2 mb-0 py-2" style="font-size:13px;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>Warning:</strong> All submitted applications must have the <strong>same Campus and Program</strong>. Please correct the highlighted entries.
                                        </div>
                                        <?php } ?>
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
                                                                LEFT JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                                                LEFT JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                                                LEFT JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                                LEFT JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                                LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                WHERE
                                                                     tbl_admittedPRG.Stu_code = '".$code."'
                                                                ORDER BY
                                                                     tbl_admittedPRG.Aprg_id ASC;
                                                                    ");
                                            $sql->execute();
                                            $i=1;
                                            while($apps=$sql->fetch()){
                                                $prg=$apps['prg_type'];
                                        ?>
                                        
                                        <div class="card-body row" style="border-radius:5px;margin-bottom:10px;
                                                                        <?php if($hasMismatch){
                                                                        echo 'border: 2px solid red; background-color: #fff5f5;';
                                                                        } elseif($apps['sts']==1){
                                                                        echo 'border: 2px solid grey';
                                                                        } elseif($apps['sts']==2){
                                                                        echo 'border: 2px solid orange';
                                                                        } elseif($apps['sts']==3){
                                                                        echo 'border: 2px solid green';
                                                                        }elseif($apps['sts']==4){
                                                                        echo 'border: 2px solid red';
                                                                        } ?>">

                                            <div class="col-12">
                                                <span class="badge badge-primary" style="font-size:12px;">
                                                    <?php
                                                        $choiceLabels = array(1 => '1st Choice', 2 => '2nd Choice');
                                                        echo isset($choiceLabels[$i]) ? $choiceLabels[$i] : $i.'th Choice';
                                                        $i++;
                                                    ?>
                                                </span>
                                                <hr>
                                            </div>
                                            
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
                                            <?php if($apps['sts']==1){ ?>
                                            
                                            <div class="form-group col-12 col-md-12 col-lg-12" style="display:flex;flex-direction:row;justify-content:center">
                                                <?php if(count($allApps) > 1){ ?>
                                                <button class=" col-6 col-md-2 col-lg-1 btn btn-danger btn-sm delete" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinnerdel_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatordel_<?php echo $apps['Aprg_id']; ?>">Remove</span>&nbsp;</button>&nbsp;&nbsp;
                                                <?php } ?>
                                                <button class=" col-6 col-md-2 col-lg-1 btn btn-primary btn-sm update" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinnerup_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatorup_<?php echo $apps['Aprg_id']; ?>">update</span>&nbsp;</button>
                                            </div>
                                            
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    
                            </div>
                        <?php ReportProblem($conn, $code, $thing); ?>
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
                                                            <!--<select class="form-control select2" style="width:100%;" name="camp_id" id="u_camp_id" required>-->
                                                            <!--    <option selected disabled>Select Campus</option>-->
                                                                
                                                            <!--</select>-->
                                                            
                                                            <input type="hidden" name="camp_id" id="u_camp_id_hidden" value="">
                                                            <select class="form-control select2" style="width:100%;" name="camp_id_display" id="u_camp_id" required disabled>
                                                                <option selected disabled>Select Campus</option>
                                                                
                                                            </select>

                                                            &nbsp;<span id="spinner00"></span>
                                                </div>
                                                <div class="form-group ">
                                                    <label>Program</label>
                                                    <!--<select class="form-control select2" style="width:100%;" name="prg_type_id" id="u_prg_type_id" required>-->
                                                    <!--    <option selected Disabled>Select Program</option>-->
                                                    <!--</select>-->
                                                    <input type="hidden" name="prg_type_id" id="u_prg_type_id_hidden" value="">
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id_display" id="u_prg_type_id" required disabled>
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
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="u_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner04"></span>
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
    var preselectedPrgType = "<?php echo htmlspecialchars($prg_type, ENT_QUOTES, 'UTF-8'); ?>";
    var isInitialCampusLoad = true;
    var isPopulatingUpdateModal = false;

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
                        $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                    });
                }

                if (isInitialCampusLoad && preselectedPrgType) {
                    $("#e_prg_type_id").val(preselectedPrgType).trigger("change");
                }
                isInitialCampusLoad = false;
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                isInitialCampusLoad = false;
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
    $("#u_camp_id").change(function () {
        if (isPopulatingUpdateModal) return;
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
        if (isPopulatingUpdateModal) return;
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
            action: "load_specialization_new"
        };

        

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                $('#spinner2').fadeOut('fast');
                $('#e_splz_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

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
                $('#spinner03').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //updating 
    $("#u_fac_id").change(function () {
        if (isPopulatingUpdateModal) return;
        var school = $("#u_fac_id").val();
        var program = $("#u_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            action: "load_specialization_new"
        };


        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                $('#spinner2').fadeOut('fast');
                $('#u_splz_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

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

                    if (!data || !data[0] || data.status === "404") {
                        pop_wrong(data && data.message ? data.message : "Application not found!");
                        return;
                    }

                    var app = data[0];
                    $("#app_id").val(data_id);

                    $("#u_camp_id").empty().append("<option value='' disabled>Select Campus</option>");
                    $("#u_prg_type_id").empty().append("<option value='' disabled>Select Program</option>");
                    $("#u_fac_id").empty().append("<option value='' disabled>Select School</option>");
                    $("#u_splz_id").empty().append("<option value='' disabled>Select Specialization</option>");

                    $.each(data[1] || [], function (index, value) {
                        $("#u_camp_id").append("<option value='" + value.camp_id + "'>" + value.camp_full_name + "</option>");
                    });
                    $.each(data[2] || [], function (index, value) {
                        $("#u_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + "</option>");
                    });
                    $.each(data[3] || [], function (index, value) {
                        $("#u_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                    $.each(data[6] || [], function (index, value) {
                        $("#u_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });

                    isPopulatingUpdateModal = true;
                    $("#u_camp_id").val(app.cump_id);
                    $("#u_prg_type_id").val(app.prg_type);
                    $("#u_fac_id").val(app.fac_id);
                    $("#u_splz_id").val(app.splz);
                    $("#u_camp_id_hidden").val(app.cump_id);
                    $("#u_prg_type_id_hidden").val(app.prg_type);
                    $("#u_camp_id, #u_prg_type_id, #u_fac_id, #u_splz_id").trigger("change.select2");
                    isPopulatingUpdateModal = false;

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

    // Load program types when campus is preselected on page load
    if ($("#e_camp_id").val()) {
        $("#e_camp_id").trigger("change");
    }
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