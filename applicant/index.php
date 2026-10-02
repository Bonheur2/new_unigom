<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-9 col-lg-9" style="margin:auto;">
                    <?php
                    // Check for active academic year
                    $select_academic_year="SELECT * FROM tbl_acad_cycle WHERE status=1";
                    $cselect_academic_year=$conn->prepare($select_academic_year);
                    $cselect_academic_year->execute();
                    $row_cselect_academic_year=$cselect_academic_year->fetch(PDO::FETCH_ASSOC);
                    
                    // Check for active application period
                    $current_date = date('Y-m-d');
                    $select_active_period = "SELECT ap.*, ac.acad_year, ac.enrollment 
                                           FROM tbl_application_periods ap 
                                           LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
                                           WHERE ap.status = 'active' 
                                           AND ap.start_date <= ? 
                                           AND ap.end_date >= ? 
                                           ORDER BY ap.created_at DESC LIMIT 1";
                    $cselect_active_period = $conn->prepare($select_active_period);
                    $cselect_active_period->execute([$current_date, $current_date]);
                    $active_period = $cselect_active_period->fetch(PDO::FETCH_ASSOC);
                    
                    // Check for upcoming application periods
                    $select_upcoming_period = "SELECT ap.*, ac.acad_year, ac.enrollment 
                                             FROM tbl_application_periods ap 
                                             LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
                                             WHERE ap.status = 'active' 
                                             AND ap.start_date > ? 
                                             ORDER BY ap.start_date ASC LIMIT 1";
                    $cselect_upcoming_period = $conn->prepare($select_upcoming_period);
                    $cselect_upcoming_period->execute([$current_date]);
                    $upcoming_period = $cselect_upcoming_period->fetch(PDO::FETCH_ASSOC);
                    
                    
                    // Check for held application period
$select_hold_period = "SELECT ap.*, ac.acad_year 
                       FROM tbl_application_periods ap 
                       LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
                       WHERE ap.status = 'hold' 
                       ORDER BY ap.created_at DESC LIMIT 1";
$cselect_hold_period = $conn->prepare($select_hold_period);
$cselect_hold_period->execute();
$hold_period = $cselect_hold_period->fetch(PDO::FETCH_ASSOC);

if($row_cselect_academic_year && $active_period){
                        // Academic year exists and application period is active
                    ?>
                    <form id="apply" action="apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="apply">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-2 col-lg-2" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Main information</button>
                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;Supplementary data</button>
                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Secondary education</button>
                                    <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-3 col-lg-3"  style="margin-bottom:10px;"><span class="badge badge-transparent">4</span> &nbsp;Academic choice</button>
                                    <button type="button" id="section-5-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">5</span> &nbsp;Documents</button>
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
                                    <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. KAMARA" required>
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
                                    <label>National ID/ Passport <code><b><span id="nid_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="nid" id="nid" placeholder="e.g. SLxxxxxxxxxx" required>
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
                                    <label>Place of birth</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder="e.g. Goma">
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
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection2()"><span id="spinner2"></span>&nbsp; <span id="indicator2">Next</span></button>
                                </div>
                            </div>
                            <!--personal details end-->
                            <!--address start-->
                            <div class="card-body row" id="section-2" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nationality <code><b>*</b></code></label>
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
                                    <label>Country of residence<code><b>*</b></code></label>
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
                                    <label>Father's name <code><b>*</b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="father_name" class="form-control" placeholder="father's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's name <code><b>*</b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="mother_name" class="form-control" placeholder="mother's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Father's Phone (if any)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +2327245....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's Phone (if any)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +232724....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Blood Type</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-tint"></i>
                                        </div>
                                    </div>
                                    <select name="blood_type" id="blood_type" class="form-control select2">
                                        <option value="" disabled selected hidden>Choose One...</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Marital Status</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-ring"></i>
                                        </div>
                                    </div>
                                    <select name="marital_status" id="marital_status" class="form-control select2">
                                        <option value="" disabled selected hidden>Choose One...</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Religious Affiliation</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-praying-hands"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="religious_affiliation" class="form-control" placeholder="e.g. Catholic">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Parents' Province of Origin</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="parents_province_origin" class="form-control" placeholder="e.g. North Kivu">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Territory of Origin</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="territory_of_origin" class="form-control" placeholder="e.g. Nyiragongo">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-lg-12">
                                    <label>Candidate's Address (Neighborhood &amp; Avenue, Number)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-home"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="candidate_address" class="form-control" placeholder="e.g. Himbi, Avenue Bujavu No. 12">
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection3()"><span id="spinner4"></span>&nbsp; <span id="indicator4">Next</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner3"></span>&nbsp; <span id="indicator3">Back</span></button>
                                </div>
                            </div>
                            <!--address end-->
                            <!--secondary education start-->
                            <div class="card-body row" id="section-3" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Name of Secondary School Attended</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-school"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_name" class="form-control" placeholder="e.g. Institut Mont Carmel">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>School Address (Province)</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_province" class="form-control" placeholder="e.g. North Kivu">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Territory</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="secondary_school_territory" class="form-control" placeholder="e.g. Nyiragongo">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Humanities Section</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="humanities_section" class="form-control" placeholder="e.g. Scientific">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Status of Secondary School</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-landmark"></i>
                                        </div>
                                    </div>
                                    <select name="secondary_school_status" id="secondary_school_status" class="form-control select2">
                                        <option value="" disabled selected hidden>Choose One...</option>
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                        <option value="Protestant">Protestant</option>
                                        <option value="Catholic">Catholic</option>
                                        <option value="Muslim">Muslim</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>Year of Obtaining the State Diploma</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="diploma_year" class="form-control" min="1970" max="<?php echo date('Y'); ?>" placeholder="e.g. <?php echo date('Y'); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>Diploma Percentage</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-percentage"></i>
                                        </div>
                                    </div>
                                    <input type="number" step="0.01" min="0" max="100" name="diploma_percentage" class="form-control" placeholder="e.g. 68.5">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label>State Diploma Number</label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="state_diploma_number" class="form-control" placeholder="e.g. 123456789">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-lg-12">
                                    <label>Professional Activities <code>(if any, after secondary studies)</code></label>
                                    <textarea name="professional_activities" class="form-control" rows="3" placeholder="Describe any professional activities undertaken after secondary school"></textarea>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection4()"><span id="spinner6"></span>&nbsp; <span id="indicator6">Next</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner5"></span>&nbsp; <span id="indicator5">Back</span></button>
                                </div>
                            </div>
                            <!--secondary education end-->
                            <!--academic choice start-->
                            <div class="card-body row" id="section-4" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Campus <code><b><span id="camp_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-university"></i>
                                        </div>
                                    </div>
                                        <select name="camp_id" id="camp_id" class="form-control select2" required>
                                            <option value="" disabled selected hidden>Choose One...</option>
                                            <?php
                                                $sql_campus = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active = 1 ORDER BY camp_full_name ASC");
                                                $sql_campus->execute();
                                                while ($campus = $sql_campus->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $campus['camp_id']; ?>"><?php echo htmlspecialchars($campus['camp_full_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Programme Type <code><b><span id="prg_type_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                        <select name="prg_type_id" id="prg_type_id" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choose campus first...</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Please select Programme Type carefully: <br><b>Undergraduate</b> is SLE 500, <b>Postgraduate</b> is SLE 600.
                                    </small>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>1st Choice: Department <code><b><span id="dept1_star"></span></b></code> &nbsp;<span id="spinner_dept"></span></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-sitemap"></i>
                                        </div>
                                    </div>
                                        <select name="dept_choice_1" id="dept_choice_1" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choose Programme Type first...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>2nd Choice: Department <code><b><span id="dept2_star"></span></b></code></label>
                                    <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fas fa-sitemap"></i>
                                        </div>
                                    </div>
                                        <select name="dept_choice_2" id="dept_choice_2" class="form-control select2" required disabled>
                                            <option value="" disabled selected hidden>Choose Programme Type first...</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Must be different from your 1st choice.
                                    </small>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="goToSection5()"><span id="spinner7"></span>&nbsp; <span id="indicator7">Next</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner8"></span>&nbsp; <span id="indicator8">Back</span></button>
                                </div>
                            </div>
                            <!--academic choice end-->
                            <!--documents start-->
                            <div class="card-body row" id="section-5" hidden>
                                <div class="col-12" id="documents_list">
                                    <div class="alert alert-light text-center">
                                        <i class="fas fa-info-circle"></i> Select your Programme Type first to see the required documents.
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit Application</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection4()"><span id="spinner9"></span>&nbsp; <span id="indicator9">Back</span></button>
                                </div>
                            </div>
                            <!--documents end-->
                        </div>
                        
                    </form>
                    
                    <!-- Application Period Info Card -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="alert alert-primary" role="alert">
                                <h5 class="alert-heading">
                                     Application Period Active
                                </h5>
                                <hr>
                                <p class="mb-2">
                                    <strong>Period:</strong> <?php echo htmlspecialchars($active_period['period_name']); ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Academic Year:</strong> <?php echo htmlspecialchars($active_period['acad_year']); ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Application Deadline:</strong> 
                                    <span class="badge badge-success">
                                        <?php echo date('F d, Y', strtotime($active_period['end_date'])); ?>
                                    </span>
                                </p>
                                <?php if(!empty($active_period['description'])): ?>
                                <p class="mb-0">
                                    <strong>Note:</strong> <?php echo htmlspecialchars($active_period['description']); ?>
                                </p>
                                
                                <?php endif; ?>
                                <p class="mb-0">
                                    <strong>Support Contact:</strong> +232 74 001023, +232 79 102840, +232 76 811846
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    }
                    elseif($row_cselect_academic_year && $hold_period){
?>
<div class="card">
    <div class="card-body text-center">
        <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading">
                 Applications On Hold
            </h4>
            <hr>
            <p class="mb-2">The application period is currently on hold. No new applications are being accepted at this time.</p>
            <?php if(!empty($hold_period['period_name'])): ?>
            <p class="mb-2"><strong>Period:</strong> <?php echo htmlspecialchars($hold_period['period_name']); ?></p>
            <?php endif; ?>
            <?php if(!empty($hold_period['acad_year'])): ?>
            <p class="mb-2"><strong>Academic Year:</strong> <?php echo htmlspecialchars($hold_period['acad_year']); ?></p>
            <?php endif; ?>
            <?php if(!empty($hold_period['description'])): ?>
            <p class="mb-2"><strong>Note:</strong> <?php echo htmlspecialchars($hold_period['description']); ?></p>
            <?php endif; ?>
            <p class="mb-2"><strong>Support Contact:</strong> +232 74 001023, +232 79 102840, +232 76 811846</p>
            <button type="button" class="btn btn-outline-warning mt-2" onclick="location.reload()">
                <i class="fas fa-refresh"></i> Check Again
            </button>
        </div>
    </div>
</div>
<?php
}
elseif($row_cselect_academic_year && $upcoming_period){
                        // Academic year exists but application period hasn't started yet
                    ?>
                    
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">
                                    <i class="fas fa-hourglass-start"></i> Application Period Will Open Soon
                                </h4>
                                <hr>
                                <p class="mb-3">The application period is not currently active. Please check back later.</p>
                                
                                <!-- Mobile-friendly card layout -->
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="card bg-light h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-calendar-alt"></i> Upcoming Period
                                                </h6>
                                                <p class="card-text">
                                                    <strong><?php echo htmlspecialchars($upcoming_period['period_name']); ?></strong>
                                                </p>
                                                <p class="card-text small">
                                                    Academic Year: <?php echo htmlspecialchars($upcoming_period['acad_year']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="card bg-light h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-clock"></i> Opening Date
                                                </h6>
                                                <p class="card-text">
                                                    <span class="badge badge-primary badge-lg d-block mb-2">
                                                        <?php echo date('F d, Y', strtotime($upcoming_period['start_date'])); ?>
                                                    </span>
                                                </p>
                                                <p class="card-text small">
                                                    Deadline: <?php echo date('F d, Y', strtotime($upcoming_period['end_date'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(!empty($upcoming_period['description'])): ?>
                                <div class="mt-3">
                                    <div class="alert alert-light">
                                        <p class="mb-1"><strong><i class="fas fa-info-circle"></i> Additional Information:</strong></p>
                                        <p class="mb-0"><?php echo htmlspecialchars($upcoming_period['description']); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Mobile-optimized countdown section -->
                                <div class="mt-4">
                                    <h6><i class="fas fa-stopwatch"></i> Countdown to Opening:</h6>
                                    <div class="countdown-timer mb-3">
                                        <!-- Countdown will be populated by JavaScript -->
                                    </div>
                                    
                                    <!-- Mobile-friendly info badges -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 mb-2">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i> 
                                                <strong>Days until opening:</strong>
                                            </p>
                                            <span class="badge badge-info badge-lg">
                                                <?php 
                                                $days_until = (strtotime($upcoming_period['start_date']) - strtotime($current_date)) / (60 * 60 * 24);
                                                echo ceil($days_until) . ' days';
                                                ?>
                                            </span>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-2">
                                            <button type="button" class="btn btn-outline-primary btn-block" onclick="location.reload()">
                                                <i class="fas fa-refresh"></i> Check Again
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    }
                    else{
                        // No academic year or no application periods found
                    ?>
                    
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="alert alert-primary" role="alert">
                                <h4 class="alert-heading">
                                    Application Portal Closed 
                                </h4>
                                <hr>
                                <?php if(!$row_cselect_academic_year): ?>
                                    <p class="mb-3">No active academic year is currently set. Please contact the administration for more information.</p>
                                <?php else: ?>
                                    <p class="mb-3">No application periods are currently scheduled. Please check back later or contact the administration.</p>
                                <?php endif; ?>
                                
                                <div class="mt-4">
                                    <p class="mb-2">
                                        <i class="fas fa-phone"></i> 
                                        <strong>Need Help?</strong> Contact our admissions office for assistance.
                                    </p>
                                    <button type="button" class="btn btn-outline-warning" onclick="location.reload()">
                                        <i class="fas fa-refresh"></i> Refresh Page
                                    </button>
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
    </section>
</div>
<?php  include'comb/orgin.php'; ?>       
<?php  include'comb/coda.php'; ?>  
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#camp_id').on('change', function() {
            loadProgramTypes($(this).val());
        });

        if ($('#camp_id').val()) {
            loadProgramTypes($('#camp_id').val());
        }

        $('#prg_type_id').on('change', function() {
            var prgTypeId = $(this).val();
            loadDepartments(prgTypeId);
            loadDocuments(prgTypeId);
        });

        $('#dept_choice_1, #dept_choice_2').on('change', function(){
            $('#dept2_star').html("");
        });

        $("#apply").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Processing...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "../new_files/Application_form/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit Application");
                    if(data.status==200){
                        $("#apply")[0].reset();
                        swal({
                            title: "Application Submitted!",
                            text: "Your application was registered successfully. Your application code is " + data.application_code + ". Please check your email for the next steps.",
                            icon: "success",
                            button: "OK"
                        });
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled',false);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                        $("#sBtn").attr('disabled',false);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $("#sBtn").attr('disabled',false);
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

    function loadProgramTypes(campusId) {
        var $programType = $('#prg_type_id');

        $programType.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Loading...</option>').trigger('change');

        if (!campusId) {
            $programType.empty().append('<option value="" disabled selected hidden>Choose campus first...</option>').trigger('change');
            return;
        }

        $.ajax({
            url: '../new_files/Application_form/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_program_types', cid: campusId },
            success: function(data) {
                var options = '<option value="" disabled selected hidden>Choose One...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.prg_type_id + '">' + item.prg_type_full_name + '</option>';
                    });
                    $programType.html(options).prop('disabled', false).trigger('change');
                } else {
                    $programType.html('<option value="" disabled selected hidden>No program types available</option>').trigger('change');
                }
            },
            error: function() {
                $programType.html('<option value="" disabled selected hidden>Unable to load program types</option>').trigger('change');
            }
        });
    }

    function loadDepartments(prgTypeId) {
        var $dept1 = $('#dept_choice_1');
        var $dept2 = $('#dept_choice_2');

        $dept1.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Loading...</option>').trigger('change');
        $dept2.prop('disabled', true).empty().append('<option value="" disabled selected hidden>Loading...</option>').trigger('change');
        $('#spinner_dept').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

        if (!prgTypeId) {
            $dept1.empty().append('<option value="" disabled selected hidden>Choose Programme Type first...</option>').trigger('change');
            $dept2.empty().append('<option value="" disabled selected hidden>Choose Programme Type first...</option>').trigger('change');
            $('#spinner_dept').fadeOut('fast');
            return;
        }

        $.ajax({
            url: '../new_files/Application_form/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_departments', prg_type_id: prgTypeId },
            success: function(data) {
                $('#spinner_dept').fadeOut('fast');
                var options = '<option value="" disabled selected hidden>Choose One...</option>';

                if (data && data.length) {
                    $.each(data, function(_, item) {
                        options += '<option value="' + item.dept_id + '">' + item.dept_full_name + '</option>';
                    });
                    $dept1.html(options).prop('disabled', false).trigger('change');
                    $dept2.html(options).prop('disabled', false).trigger('change');
                } else {
                    $dept1.html('<option value="" disabled selected hidden>No departments available</option>').trigger('change');
                    $dept2.html('<option value="" disabled selected hidden>No departments available</option>').trigger('change');
                }
            },
            error: function() {
                $('#spinner_dept').fadeOut('fast');
                $dept1.html('<option value="" disabled selected hidden>Unable to load departments</option>').trigger('change');
                $dept2.html('<option value="" disabled selected hidden>Unable to load departments</option>').trigger('change');
            }
        });
    }

    function loadDocuments(prgTypeId) {
        var $container = $('#documents_list');
        $container.html('<div class="alert alert-light text-center"><i class="fas fa-spinner fa-spin"></i> Loading required documents...</div>');

        if (!prgTypeId) {
            $container.html('<div class="alert alert-light text-center"><i class="fas fa-info-circle"></i> Select your Programme Type first to see the required documents.</div>');
            return;
        }

        $.ajax({
            url: '../new_files/Application_form/controller.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'load_documents', prg_type_id: prgTypeId },
            success: function(data) {
                if (!data || !data.length) {
                    $container.html('<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> No documents are required for this faculty.</div>');
                    return;
                }

                var html = '<h6 class="mb-3"><i class="fas fa-paperclip"></i> Required Documents</h6><div class="row">';
                $.each(data, function(_, doc) {
                    var acceptAttr = doc.file_type ? ' accept="' + $.map(doc.file_type.split(','), function(t){ return '.'+t.trim(); }).join(',') + '"' : '';
                    html += '<div class="form-group col-12 col-sm-6 col-lg-6">'
                        + '<label>' + doc.document_name
                        + (doc.international_required == 1 ? ' <span class="badge badge-warning">International applicants only</span>' : '')
                        + '</label>'
                        + '<div class="input-group">'
                        + '<div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-file-upload"></i></div></div>'
                        + '<input type="file" class="form-control" name="documents[' + doc.doc_id + ']"' + acceptAttr + '>'
                        + '</div>'
                        + (doc.file_type ? '<small class="form-text text-muted">Accepted format(s): ' + doc.file_type + '</small>' : '')
                        + (doc.file_name ? '<small class="form-text"><a href="' + doc.file_name + '" target="_blank"><i class="fas fa-download"></i> Download template</a></small>' : '')
                        + '</div>';
                });
                html += '</div>';
                $container.html(html);
            },
            error: function() {
                $container.html('<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> Unable to load required documents.</div>');
            }
        });
    }

    function goToSection2() {
        var section1Valid = validateSection1();
        if (section1Valid) {
            $("#fname_star").html("");
            $("#lname_star").html("");
            $("#nid_star").html("");
            $("#gender_star").html("");
            $("#phone_star").html("");
            $("#email_star").html("");
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("loading...");
            setTimeout(function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Next");
                $("#section-1-indicator").removeClass('btn-primary');
                $("#section-1-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $('#section-1').attr('hidden',true);
                $('#section-2').attr('hidden',false);
                }, 1000);
        }
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
            }, 2000);

    }

    function goToSection3() {
        $('#spinner4').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator4').html("loading...");
            setTimeout(function() {
                $('#spinner4').fadeOut('fast');
                $('#indicator4').html("Next");
                $("#section-2-indicator").removeClass('btn-primary');
                $("#section-2-indicator").addClass('btn-light');
                $("#section-3-indicator").removeClass('btn-light');
                $("#section-3-indicator").addClass('btn-primary');
                $('#section-2').attr('hidden',true);
                $('#section-3').attr('hidden',false);
                }, 1000);
    }

    function goBackToSection2() {
        $('#spinner5').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator5').html("loading...");
            setTimeout(function() {
                $('#spinner5').fadeOut('fast');
                $('#indicator5').html("back");
                $("#section-3-indicator").removeClass('btn-primary');
                $("#section-3-indicator").addClass('btn-light');
                $("#section-2-indicator").removeClass('btn-light');
                $("#section-2-indicator").addClass('btn-primary');
                $('#section-3').attr('hidden',true);
                $('#section-2').attr('hidden',false);
            }, 800);
    }

    function goToSection4() {
        $('#spinner6').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator6').html("loading...");
            setTimeout(function() {
                $('#spinner6').fadeOut('fast');
                $('#indicator6').html("Next");
                $("#section-3-indicator").removeClass('btn-primary');
                $("#section-3-indicator").addClass('btn-light');
                $("#section-4-indicator").removeClass('btn-light');
                $("#section-4-indicator").addClass('btn-primary');
                $('#section-3').attr('hidden',true);
                $('#section-4').attr('hidden',false);
                }, 800);
    }

    function goBackToSection3() {
        $('#spinner8').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator8').html("loading...");
            setTimeout(function() {
                $('#spinner8').fadeOut('fast');
                $('#indicator8').html("back");
                $("#section-4-indicator").removeClass('btn-primary');
                $("#section-4-indicator").addClass('btn-light');
                $("#section-3-indicator").removeClass('btn-light');
                $("#section-3-indicator").addClass('btn-primary');
                $('#section-4').attr('hidden',true);
                $('#section-3').attr('hidden',false);
            }, 800);
    }

    function goToSection5() {
        var camp_id = $('#camp_id').val();
        var prg_type_id = $('#prg_type_id').val();
        var dept1 = $('#dept_choice_1').val();
        var dept2 = $('#dept_choice_2').val();

        $("#camp_star").html("");
        $("#prg_type_star").html("");
        $("#dept1_star").html("");
        $("#dept2_star").html("");

        var valid = true;
        if (!camp_id) { $("#camp_star").html("*"); valid = false; }
        if (!prg_type_id) { $("#prg_type_star").html("*"); valid = false; }
        if (!dept1) { $("#dept1_star").html("*"); valid = false; }
        if (!dept2) { $("#dept2_star").html("*"); valid = false; }
        if (dept1 && dept2 && dept1 === dept2) {
            $("#dept2_star").html("*");
            pop_wrong_verify("2nd choice must be different from 1st choice");
            valid = false;
        }

        if (!valid) {
            if (!(dept1 && dept2 && dept1 === dept2)) {
                pop_wrong_verify("Fill all missing fields");
            }
            return;
        }

        $('#spinner7').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator7').html("loading...");
            setTimeout(function() {
                $('#spinner7').fadeOut('fast');
                $('#indicator7').html("Next");
                $("#section-4-indicator").removeClass('btn-primary');
                $("#section-4-indicator").addClass('btn-light');
                $("#section-5-indicator").removeClass('btn-light');
                $("#section-5-indicator").addClass('btn-primary');
                $('#section-4').attr('hidden',true);
                $('#section-5').attr('hidden',false);
                }, 800);
    }

    function goBackToSection4() {
        $('#spinner9').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator9').html("loading...");
            setTimeout(function() {
                $('#spinner9').fadeOut('fast');
                $('#indicator9').html("back");
                $("#section-5-indicator").removeClass('btn-primary');
                $("#section-5-indicator").addClass('btn-light');
                $("#section-4-indicator").removeClass('btn-light');
                $("#section-4-indicator").addClass('btn-primary');
                $('#section-5').attr('hidden',true);
                $('#section-4').attr('hidden',false);
            }, 800);
    }

    function validateSection1() {
        var fname = document.getElementById('fname').value.trim();
        var lname = document.getElementById('lname').value.trim();
        var nid = document.getElementById('nid').value.trim();
        var gender = document.getElementById('gender').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var email = document.getElementById('email').value.trim();

        if (fname === '' || lname === '' || nid === '' || gender === '' || phone === '' || email === '') {
            pop_wrong_verify("Fill all missing fields");
            fname===''?$("#fname_star").html("*"):$("#fname_star").html("");
            lname===''?$("#lname_star").html("*"):$("#lname_star").html("");
            nid===''?$("#nid_star").html("*"):$("#nid_star").html("");
            gender===''?$("#gender_star").html("*"):$("#gender_star").html("");
            phone===''?$("#phone_star").html("*"):$("#phone_star").html("");
            email===''?$("#email_star").html("*"):$("#email_star").html("");
            return false;
        }

        return true;
    }

    // Auto-refresh page every 5 minutes to check for period changes
    setInterval(function() {
        // Only refresh if we're not in an active application period
        <?php if(!$active_period): ?>
        location.reload();
        <?php endif; ?>
    }, 300000); // 5 minutes

    // Countdown timer for upcoming periods
    <?php if($upcoming_period && !$active_period): ?>
    function updateCountdown() {
        var startDate = new Date('<?php echo $upcoming_period["start_date"]; ?>T00:00:00').getTime();
        var now = new Date().getTime();
        var distance = startDate - now;

        if (distance > 0) {
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Mobile-responsive countdown layout
            $('.countdown-timer').html(
                '<div class="row text-center">' +
                    '<div class="col-6 col-sm-3 mb-2">' +
                        '<div class="card bg-primary text-white">' +
                            '<div class="card-body py-2">' +
                                '<h5 class="mb-0">' + days + '</h5>' +
                                '<small>Days</small>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-6 col-sm-3 mb-2">' +
                        '<div class="card bg-info text-white">' +
                            '<div class="card-body py-2">' +
                                '<h5 class="mb-0">' + hours + '</h5>' +
                                '<small>Hours</small>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-6 col-sm-3 mb-2">' +
                        '<div class="card bg-success text-white">' +
                            '<div class="card-body py-2">' +
                                '<h5 class="mb-0">' + minutes + '</h5>' +
                                '<small>Minutes</small>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-6 col-sm-3 mb-2">' +
                        '<div class="card bg-warning text-white">' +
                            '<div class="card-body py-2">' +
                                '<h5 class="mb-0">' + seconds + '</h5>' +
                                '<small>Seconds</small>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        } else {
            $('.countdown-timer').html(
                '<div class="alert alert-success">' +
                    '<i class="fas fa-calendar-check"></i> Application period is now open!' +
                    '<div class="mt-2">' +
                        '<button class="btn btn-success btn-block" onclick="location.reload()">' +
                            '<i class="fas fa-refresh"></i> Refresh Page' +
                        '</button>' +
                    '</div>' +
                '</div>'
            );
        }
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);
    updateCountdown(); // Initialize immediately
    <?php endif; ?>
</script>