<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<?php require'meet/bind.php'; ?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <?php
                    $select_academic_year="SELECT * FROM tbl_acad_cycle WHERE status=1";
                    $cselect_academic_year=$conn->prepare($select_academic_year);
                    $cselect_academic_year->execute();
                    $row_cselect_academic_year=$cselect_academic_year->fetch(PDO::FETCH_ASSOC);
                    if($row_cselect_academic_year){
                    ?>
                    <form id="applys"  method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="apply_semester">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-4 col-lg-4" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Personal Information</button>
                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-4 col-lg-4"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;Academic Information</button>
                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-4 col-lg-4"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Payment Information</button>
                            </div>
                            <!--personal details start-->
                            <div class="card-body row" id="section-1">
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Surname (Family name) <code><b><span id="lname_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. GASANA" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Middle name <code>(optional)</code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="mname" id="mname" placeholder="e.g. John" >
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>First name <code><b><span id="fname_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Peter" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>ID/ Passport <code><b><span id="nid_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="nid" id="nid" placeholder="e.g. 1 1990 800 xxxxxxxxxx" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Gender <code><b><span id="gender_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                        <select name="gender" id="gender" class="form-control select2" placeholder="choose one" required>
                                            <option value="" disabled selected hidden>Choose One...</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Date of birth</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo date('Y-m-d', strtotime('-25 years')); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Phone number <code><b><span id="phone_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control phone-number" name="phone" id="phone" placeholder="e.g. +232724....." required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>E-mail <code><b><span id="email_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="e.g. annet@example.com" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nationality <code><span id="nationality_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-globe-americas"></i>
                                        </div>
                                    </div>
                                    <select name="nationality" id="nationality" class="form-control select2">
                                        <?php
                                            $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                            $sql_nat->execute();
                                            $i=1;
                                            while($nat=$sql_nat->fetch()){
                                        ?>
                                            <option value="<?php echo $nat['nat_id']; ?>" <?php echo $nat['nat_id'] == 221?'selected':''; ?>><?php echo $nat['nationality']; ?> </option>
                                            <?php } ?>
                                            <option value="0">Others</option>
                                    </select>
                                    </div>
                                </div>
                                
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Country of residence<code><span id="country_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-flag"></i>
                                        </div>
                                    </div>
                                    <select name="country" id="country" class="form-control select2" required>
                                        <?php
                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                            $sql_cntr->execute();
                                            $i=1;
                                            while($cntr=$sql_cntr->fetch()){
                                        ?>
                                            <option value="<?php echo $cntr['cntr_id']; ?>" <?php if($cntr['cntr_id']==167) echo 'selected' ?>><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                            <?php } ?>
                                    </select>&nbsp;<span id="spinner_cntr"></span> 
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Father's name <code><span id="father_name_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="father_name" id="father_name" class="form-control" placeholder="father's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's name <code><span id="mother_name_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="mother_name" id="mother_name" class="form-control" placeholder="mother's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Father's Phone<code><span id="parent_phone_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="parent_phone" id="parent_phone" class="form-control" placeholder="e.g. +2327245....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's Phone <code><span id="ref_phone_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="ref_phone" id="ref_phone" class="form-control" placeholder="e.g. +232724....">
                                    </div>
                                </div>
                                
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-success btn-sm" onclick="goToSection2()"><span id="spinner2"></span>&nbsp; <span id="indicator2">Next</span></button>
                                </div>
                            </div>
                            <!--personal details end-->
                            <!--address start-->
                            <div class="card-body row" id="section-2" hidden>
                               <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>MAT NO <code><span id="reg_no_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="reg_no" id="reg_no" class="form-control" placeholder="e.g. +001">
                                    </div>
                                </div> 
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Campus<code><span id="camp_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2"  name="camp_id" id="e_camp_id" required>
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
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Program Type<code><span id="prg_type_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2"  name="prg_type_id" id="e_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>School<code><span id="fac_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2"  name="fac_id" id="e_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2"></span>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Department<code><span id="dept_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                                            <select class="form-control select2"  name="dept_id" id="e_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3"></span>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Specialization<code><span id="splz_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2"  name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Year<code><span id="level_id_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2"  name="level_id" id="e_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5"></span>
                                    </div>
                                </div>
                                
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Semester Module<code><span id="tcounter_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" min="1" name="tcounter" id="tcounter" oninput="generateCourses(this.value)" required>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-12 col-lg-12" id="course-container"></div>
                                
                                
                                
                                
                                
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection3()"><span id="spinner"></span>&nbsp; <span id="indicator">Next</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner3"></span>&nbsp; <span id="indicator3">Back</span></button>
                                </div>
                            </div>
                            
                            <!--third section-->
                            <div class="card-body row" id="section-3" hidden>
                            
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Select Semester (Both If fully Paid)<code><span id="semester_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2" name="semester" id="semester" required>
                                        <option selected disabled>Select Semester</option>
                                        <option value="0">Both</option>
                                        <?php
                                        $select_semester="SELECT * FROM semester";
                                        $cselect_semester=$conn->prepare($select_semester);
                                        $cselect_semester->execute();
                                        foreach($cselect_semester as $row_cselect_semester){
                                        
                                        ?>
                                        <option value="<?php echo $row_cselect_semester['id']?>"><?php echo $row_cselect_semester['name']?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                <label>Select Sponsor <code><span id="sponsor_star"></span></code></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2" name="sponsor" id="sponsorSelect" required>
                                        <option selected disabled>Select Sponsor</option>
                                        <option value="0">PRIVATE</option>
                                        <?php
                                        $select_sponsor = "SELECT * FROM tbl_sponsor";
                                        $cselect_sponsor = $conn->prepare($select_sponsor);
                                        $cselect_sponsor->execute();
                                        foreach ($cselect_sponsor as $row) {
                                        ?>
                                            <option value="<?php echo $row['spon_id'] ?>"><?php echo $row['spon_short_name'] ?></option>
                                        <?php } ?>
                                        <option value="none">None Of Above</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Hidden input field for custom sponsor -->
                            <div class="form-group col-12 col-sm-6 col-lg-6" id="custom_sponsor_div" style="display: none;">
                                <label>Enter Sponsor Name</label>
                                <input type="text" class="form-control" name="custom_sponsor" id="custom_sponsor" placeholder="Enter Sponsor Name">
                            </div>

                                
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                <label>Number Of Payments <code><span id="tcounter_star"></span></code></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" min="1" name="tcounter" id="tcounter" oninput="generatePayments(this.value)" required>
                                </div>
                            </div>
                            
                            <!-- Container for Generated Inputs -->
                            <div class="form-group row col-sm-12 col-lg-12" id="payment-container"></div>
                                
                                
                                <button type="button" class="btn btn-primary btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3"></span>&nbsp; <span id="indicator3">Back</span></button>
                                <button type="submit" class="btn btn-light btn-sm" style="margin-right:10px;" ><span id="spinner3"></span>&nbsp; <span id="indicator3">Submit</span></button>
                                
                            </div>
                            <!--address end-->
                        </div>
                        
                    </form>
                    <?php
                    }
                    else{
                    ?>
                    <p class="bg-danger">The Portail Is Now closed</p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php  include'comb/orgin.php'; ?>       
<?php  include'comb/coda.php'; ?>  
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        
        //program structure start
        
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
            url: "files/application/application_controller.php",
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
            url: "files/application/application_controller.php",
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
            url: "files/application/application_controller.php",
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
            url: "files/application/application_controller.php",
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
            url: "files/application/application_controller.php",
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
            url: "files/application/application_controller.php",
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
        
        
        //program structure end
        
        
    $("#applys").submit(function (e) {
        e.preventDefault();
        
        if (!validateSection3()) {  // FIX: Call validateSection3() instead of validateAll()
            pop_wrong("Please fill in all required fields correctly.");
            return;
        }

        var formData = new FormData(this);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Processing...");
        $("#sBtn").attr('disabled', true);

        $.ajax({
            url: "files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Submit");

                if (data.status == 200) {
                    
                    pop_up_success(data.message);

                    setTimeout(function () {
                        window.location.href = "verify_email?em=" + mail;
                    }, 2000);
                } else {
                    pop_wrong(data.message);
                    $("#sBtn").attr('disabled', false);
                }
            },
            error: function () {
                $('#spinner').fadeOut('fast');
                $("#sBtn").attr('disabled', false);
                $('#indicator').html("Submit");
                pop_wrong("Something went wrong!");
            }
        });
    });


    });

   function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Info',
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

   function pop_wrong_verify(feedback) {
        iziToast.warning({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }

    function goToSection2() {
    var section1Valid = validateSection1();
    if (section1Valid) {
        checkEmailExists();
    }
}
function goToSection3() {
    var section2Valid = validateSection2();
    if (section2Valid) {
        checkregExists();
    }
}
 function validateAll(){
     var section3validate = validateSection3();
 }

function checkEmailExists() {
    var email = $("#email").val().trim();
    
    if (email === "") {
        pop_wrong_verify("Email is required!");
        return;
    }

    $.ajax({
        url: "files/application/application_controller.php", 
        type: "POST",
        data: { email: email, action: "check_student_email" },
        dataType: "json",
        success: function(data) {
            if (data.status === 401) {
                pop_wrong(data.message);
                $("#email_star").html("*");
            } else if (data.status === 200) {
                pop_up_success(data.message);
                setTimeout(moveToNextSection, 2000);
            } else {
                pop_wrong_verify("Unexpected response from server.");
            }
        },
        error: function(xhr) {
            pop_wrong_verify("Error checking email. Try again.");
            console.error("Server Error:", xhr.responseText);
        }
    });
}
function checkregExists() {
    var reg_no = $("#reg_no").val().trim();
    
    if (email === "") {
        pop_wrong_verify("MAT NO is required!");
        return;
    }

    $.ajax({
        url: "files/application/application_controller.php", 
        type: "POST",
        data: { reg_no: reg_no, action: "check_student_reg" },
        dataType: "json",
        success: function(data) {
            if (data.status === 401) {
                pop_wrong(data.message);
                $("#reg_no_star").html("*");
            } else if (data.status === 200) {
                pop_up_success(data.message);
                setTimeout(moveToNextSection1, 2000);
            } else {
                pop_wrong_verify("Unexpected response from server.");
            }
        },
        error: function(xhr) {
            pop_wrong_verify("Error MAT NO. Try again.");
            console.error("Server Error:", xhr.responseText);
        }
    });
}




function moveToNextSection() {
            $("#fname_star").html("");
            $("#lname_star").html("");
            $("#nid_star").html("");
            $("#gender_star").html("");
            $("#phone_star").html("");
            $("#email_star").html("");
            $("#nationality_star").html("");
            $("#country_star").html("");
            $("#father_name").html("");
            $("#mother_name").html("");
            $("#parent_phone").html("");
            $("#ref_phone").html("");
    $("#spinner2").html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $("#indicator2").html("loading...");

    setTimeout(function () {
        $('#spinner2').fadeOut('fast');
        $('#indicator2').html("Next");
        $("#section-1-indicator").removeClass('btn-primary').addClass('btn-light');
        $("#section-2-indicator").removeClass('btn-light').addClass('btn-primary');
        $('#section-1').attr('hidden', true);
        $('#section-2').attr('hidden', false);
    }, 1000);
}

function moveToNextSection1() {
            $("#reg_no_star").html("");
            $("#camp_id_star").html("");
            $("#prg_type_id_star").html("");
            $("#fac_id_star").html("");
            $("#dept_id_star").html("");
            $("#splz_id_star").html("");
            $("#level_id_star").html("");
            $("#tcounter_star").html("");
    $("#spinner2").html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $("#indicator2").html("loading...");

    setTimeout(function () {
        $('#spinner2').fadeOut('fast');
        $('#indicator2').html("Next");
        $("#section-1-indicator").removeClass('btn-primary').addClass('btn-light');
        $("#section-2-indicator").removeClass('btn-primary').addClass('btn-light');
        $("#section-3-indicator").removeClass('btn-light').addClass('btn-primary');
        $('#section-1').attr('hidden', true);
        $('#section-2').attr('hidden', true);
        $('#section-3').attr('hidden', false);
    }, 1000);
}
    
    
    
    function goBackToSection1() {
        $('#spinner3').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3').html("loading...");
            setTimeout(function() {
                $('#spinner3').fadeOut('fast');
                $('#indicator3').html("back");
                $("#section-2-indicator").removeClass('btn-primary');
                $("#section-2-indicator").addClass('btn-light');
                $("#section-1-indicator").removeClass('btn-light');
                $("#section-1-indicator").addClass('btn-primary');
                
                $('#section-2').attr('hidden',true);
                $('#section-1').attr('hidden',false);
                $('#nationality').select2();
                $('#country').select2();
            }, 2000);

    }
    
    function goBackToSection2() {
        $('#spinner3').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3').html("loading...");
            setTimeout(function() {
                $('#spinner3').fadeOut('fast');
                $('#indicator3').html("back");
                $("#section-3-indicator").removeClass('btn-primary');
                $("#section-3-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $("#section-1-indicator").removeClass('btn-primary');
                $("#section-1-indicator").addClass('btn-light');
                
                $('#section-3').attr('hidden',true);
                $('#section-2').attr('hidden',false);
                $('#section-1').attr('hidden',true);
                $('#nationality').select2();
                $('#country').select2();
            }, 2000);

    }

    function validateSection1() {
        var fname = document.getElementById('fname').value.trim();
        var lname = document.getElementById('lname').value.trim();
        var nid = document.getElementById('nid').value.trim();
        var gender = document.getElementById('gender').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var email = document.getElementById('email').value.trim();
        var nationality = document.getElementById('nationality').value.trim();
        var country = document.getElementById('country').value.trim();
        var father_name = document.getElementById('father_name').value.trim();
        var mother_name = document.getElementById('mother_name').value.trim();
        var parent_phone = document.getElementById('parent_phone').value.trim();
        var ref_phone = document.getElementById('ref_phone').value.trim();

        if (fname === '' || lname === '' || nid === '' || gender === '' || phone === '' || email === '' || nationality === '' || country === '' || father_name === '' || mother_name === '' || parent_phone === '' || ref_phone === '') {
            pop_wrong_verify("Fill all missing fields"); 
            fname===''?$("#fname_star").html("*"):$("#fname_star").html("");
            lname===''?$("#lname_star").html("*"):$("#lname_star").html("");
            nid===''?$("#nid_star").html("*"):$("#nid_star").html("");
            gender===''?$("#gender_star").html("*"):$("#gender_star").html("");
            phone===''?$("#phone_star").html("*"):$("#phone_star").html("");
            email===''?$("#email_star").html("*"):$("#email_star").html("");
            nationality===''?$("#nationality_star").html("*"):$("#nationality_star").html("");
            country===''?$("#country_star").html("*"):$("#country_star").html("");
            father_name===''?$("#father_name_star").html("*"):$("#father_name_star").html("");
            mother_name===''?$("#mother_name_star").html("*"):$("#mother_name_star").html("");
            parent_phone===''?$("#parent_phone_star").html("*"):$("#parent_phone_star").html("");
            ref_phone===''?$("#ref_phone_star").html("*"):$("#ref_phone_star").html("");
            return false;
        }

        return true;
    }
    
    
    
    function validateSection2() {
    var reg_no = document.getElementById('reg_no').value.trim();
    var camp_id = document.getElementById('e_camp_id').value.trim();
    var prg_type_id = document.getElementById('e_prg_type_id').value.trim();
    var fac_id = document.getElementById('e_fac_id').value.trim();
    var dept_id = document.getElementById('e_dept_id').value.trim();
    var splz_id = document.getElementById('e_splz_id').value.trim();
    var level_id = document.getElementById('e_level_id').value.trim();
    var tcounter = document.getElementById('tcounter').value.trim();
    
    // Select all dynamically created inputs
    var courses = document.querySelectorAll("input[name='courses[]']");
    var course_codes = document.querySelectorAll("input[name='course_codes[]']");
    
    var missingFields = false;

    // Check if main fields are empty
    if (reg_no === '' || camp_id === '' || prg_type_id === '' || fac_id === '' || dept_id === '' || 
        splz_id === '' || level_id === ''  || tcounter === '') {
        missingFields = true;
    }

    // Validate each course and course_code field
    courses.forEach((course, index) => {
        if (course.value.trim() === '') {
            course.style.border = "2px solid red"; // Highlight missing field
            missingFields = true;
        } else {
            course.style.border = ""; // Reset border if filled
        }

        if (course_codes[index].value.trim() === '') {
            course_codes[index].style.border = "2px solid red"; // Highlight missing field
            missingFields = true;
        } else {
            course_codes[index].style.border = ""; // Reset border if filled
        }
    });

    // Show error message if fields are missing
    if (missingFields) {
        pop_wrong_verify("Fill all missing fields");

        // Add stars (*) for missing main fields
        reg_no === '' ? $("#reg_no_star").html("*") : $("#reg_no_star").html("");
        camp_id === '' ? $("#camp_id_star").html("*") : $("#camp_id_star").html("");
        prg_type_id === '' ? $("#prg_type_id_star").html("*") : $("#prg_type_id_star").html("");
        fac_id === '' ? $("#fac_id_star").html("*") : $("#fac_id_star").html("");
        dept_id === '' ? $("#dept_id_star").html("*") : $("#dept_id_star").html("");
        splz_id === '' ? $("#splz_id_star").html("*") : $("#splz_id_star").html("");
        level_id === '' ? $("#level_id_star").html("*") : $("#level_id_star").html("");
        tcounter === '' ? $("#tcounter_star").html("*") : $("#tcounter_star").html("");

        return false;
    }

    return true;
}

function validateSection3() {
    var semester = document.getElementById('semester').value.trim();
    var sponsor = document.getElementById('sponsorSelect').value.trim();
    var tcounter = document.getElementById('tcounter').value.trim();
    var isValid = true;

    // Reset static errors
    $("#semester_star, #sponsor_star, #tcounter_star").html("");

    // Validate static fields
    if (semester === '') {
        $("#semester_star").html("*");
        isValid = false;
    }
    if (sponsor === '') {
        $("#sponsor_star").html("*");
        isValid = false;
    }
    if (tcounter === '' || isNaN(tcounter) || parseInt(tcounter) <= 0) {
        $("#tcounter_star").html("*");
        isValid = false;
        pop_wrong_verify("Invalid number of payments");
        return false;
    }

    // Remove previous payment errors
    $('.payment-error').remove();

    // Validate dynamic payments
    const paymentCount = parseInt(tcounter);
    const paymentNames = document.querySelectorAll('[name="payment_name[]"]');
    const slipNumbers = document.querySelectorAll('[name="slip_number[]"]');
    const amountsPaid = document.querySelectorAll('[name="amount_paid[]"]');
    const receipts = document.querySelectorAll('[name="receipt[]"]');

    // Check if elements match expected count
    if (paymentNames.length !== paymentCount ||
        slipNumbers.length !== paymentCount ||
        amountsPaid.length !== paymentCount ||
        receipts.length !== paymentCount) {
        pop_wrong_verify("Mismatch in payment fields");
        return false;
    }

    // Validate each payment block
    for (let i = 0; i < paymentCount; i++) {
        let paymentValid = true;

        // Payment Name
        if (paymentNames[i].value.trim() === '') {
            $(paymentNames[i]).after('<span class="payment-error" style="color:red;">*</span>');
            paymentValid = false;
        }

        // Slip Number
        if (slipNumbers[i].value.trim() === '') {
            $(slipNumbers[i]).after('<span class="payment-error" style="color:red;">*</span>');
            paymentValid = false;
        }

        // Amount Paid
        if (amountsPaid[i].value.trim() === '' || parseFloat(amountsPaid[i].value) <= 0) {
            $(amountsPaid[i]).after('<span class="payment-error" style="color:red;">*</span>');
            paymentValid = false;
        }

        // Receipt Upload
        if (receipts[i].files.length === 0) {
            $(receipts[i]).after('<span class="payment-error" style="color:red;">*</span>');
            paymentValid = false;
        }

        if (!paymentValid) isValid = false;
    }

    if (!isValid) {
        pop_wrong_verify("Fill all missing fields in payments section");
    }

    return isValid;
}



    function generateCourses(max) {
    const container = document.getElementById('course-container');
    container.innerHTML = '';
    
    // Create the table element
    const table = document.createElement('table');
    table.className = 'table table-bordered';
    
    // Create the table header row
    const headerRow = document.createElement('tr');
    
    // Header for label column
    const labelHeader = document.createElement('th');
    labelHeader.textContent = 'S/N';
    labelHeader.style.width = '10%';
    headerRow.appendChild(labelHeader);
    
    // Header for field 1 column
    const field1Header = document.createElement('th');
    field1Header.textContent = 'Course Name';
    field1Header.style.width = '60%';
    headerRow.appendChild(field1Header);
    
    // Header for field 2 column
    const field2Header = document.createElement('th');
    field2Header.textContent = 'Course Code';
    field2Header.style.width = '30%';
    headerRow.appendChild(field2Header);
    
    // Append the header row to the table
    table.appendChild(headerRow);
    
    for (let i = 1; i <= max; i++) {
        // Create a table row
        const row = document.createElement('tr');
    
        // Create the label cell
        const labelCell = document.createElement('td');
        labelCell.textContent = i;
    
        // Create the input for field 1
        const field1Input = document.createElement('input');
        field1Input.type = 'text';
        field1Input.className = 'form-control';
        field1Input.name = 'courses[]';
        field1Input.id = `course`;
        field1Input.placeholder = 'course name';
        field1Input.required = true;
        
    
        // Create the cell for field 1
        const field1Cell = document.createElement('td');
        field1Cell.appendChild(field1Input);
    
        // Create the input for field 2
        const field2Input = document.createElement('input');
        field2Input.type = 'text';
        field2Input.className = 'form-control';
        field2Input.name = 'course_codes[]';
        field2Input.id = `course_code`;
        field2Input.placeholder = 'Course Code';
        field2Input.required = true;
    
        // Create the cell for field 2
        const field2Cell = document.createElement('td');
        field2Cell.appendChild(field2Input);
    
        // Append cells to the row
        row.appendChild(labelCell);
        row.appendChild(field1Cell);
        row.appendChild(field2Cell);
    
        // Append the row to the table
        table.appendChild(row);
    }
    
    // Append the table to the main container
    container.appendChild(table);
}

document.getElementById("sponsorSelect").addEventListener("change", function() {
    var selectedValue = this.value;
    var customSponsorDiv = document.getElementById("custom_sponsor_div");
    var customSponsorInput = document.getElementById("custom_sponsor");

    if (selectedValue === "none") {
        customSponsorDiv.style.display = "block";
        customSponsorInput.setAttribute("required", "true");
    } else {
        customSponsorDiv.style.display = "none";
        customSponsorInput.removeAttribute("required");
    }
});


function generatePayments(count) {
    let container = document.getElementById("payment-container");
    container.innerHTML = "";

    fetch('fetch_payment.php') 
        .then(response => response.json())
        .then(data => {
            for (let i = 1; i <= count; i++) {
                let inputGroup = document.createElement("div");
                inputGroup.className = "col-12 col-md-6 mb-3";

                let selectOptions = data.map(payment => `<option value="${payment.id}">${payment.name}</option>`).join('');

                inputGroup.innerHTML = `
                    <div class="card p-3 shadow-sm rounded">
                        <h5>Payment ${i}</h5>
                        <label>Payment Name</label>
                        <select name="payment_name[]" class="form-control" required>
                            <option value="">Select payment name</option>
                            ${selectOptions}
                        </select>
                        <label>Slip Number</label>
                        <input type="text" name="slip_number[]" class="form-control" required>
                        <label>Amount Paid</label>
                        <input type="number" name="amount_paid[]" class="form-control" required>
                        <label>Upload Receipt</label>
                        <input type="file" name="receipt[]" class="form-control-file" accept="image/*,.pdf" required>
                    </div>
                `;
                container.appendChild(inputGroup);
            }
        })
        .catch(error => console.error("Error:", error));
}


</script>