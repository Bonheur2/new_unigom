                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Student registration</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Registration</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                                    <form id="register_student" action="register_student" method="POST">
                                        <input type="hidden" name="action" value="register_new">
                                        <div class="card">
                                            <div class="card-header row" style="display:flex; justify-content:center">
                                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-2 col-lg-2" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Personal info</button>
                                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;Address info</button>
                                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Contact info</button>
                                                    <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">4</span> &nbsp;Academic info</button>
                                                    <button type="button" id="section-5-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">5</span> &nbsp;Others</button>
                                            </div>
                                            <!--personal details start-->
                                            <div class="card-body row" id="section-1">
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
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
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>First name <code><b><span id="fname_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Annet" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
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
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Date of birth</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="dob" value="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Gender <code><b><span id="gender_star"></span></b></code></label>
                                                    <select name="gender" id="gender" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Marital status</label>
                                                    <div class="input-group">
                                                        <select name="marital_status" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                                            <option value="" disabled selected hidden>Choose One...</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Widowed">Widowed</option>
                                                            <option value="Divorced">Divorced</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Nationality <code><b><span id="nat_star"></span></b></code></label>
                                                    <select name="nationality" id="nationality"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                                            $sql_nat->execute();
                                                            $i=1;
                                                            while($nat=$sql_nat->fetch()){
                                                        ?>
                                                            <option value="<?php echo $nat['nat_id']; ?>"><?php echo $nat['nationality']; ?> </option>
                                                            <?php } ?>
                                                            <option value="0">Others</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Father's name <code><b><span id="pname_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="father_names" id="father_names" class="form-control" placeholder="father's full names" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Mother's name <code><b><span id="mname_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="mother_names" id="mother_names" class="form-control" placeholder="mother's full names" required>
                                                    </div>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" onclick="goToSection2()"><span id="spinner1-1"></span>&nbsp; <span id="indicator1-1">Next</span></button>
                                                </div>
                                            </div>
                                            <!--personal details end-->
                                            <!--address start-->
                                            <div class="card-body row" id="section-2" hidden>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Country of residence</label>
                                                    <select name="country" id="country"  class="form-control select2" style="width:100%" required>
                                                        <?php
                                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                                            $sql_cntr->execute();
                                                            $i=1;
                                                            while($cntr=$sql_cntr->fetch()){
                                                        ?>
                                                            <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                                            <?php } ?>
                                                    </select><span id="spinner_cntr"></span> 
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="prov" hidden>
                                                    <label>Province <code><b><span id="prov_star"></span></b></code></label>
                                                    <select name="province_id" id="province_id"  class="form-control select2" style="width:100%">
                                                    </select><span id="spinner_prov"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="district" hidden>
                                                    <label>District <code><b><span id="dis_star"></span></b></code></label>
                                                    <select name="district_id" id="district_id"  class="form-control select2" style="width:100%">
                                                        
                                                    </select><span id="spinner_dis"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="village" hidden>
                                                    <label>Street <code><b><span id="vil_star"></span></b></code></label>
                                                    <input type="text" name="street" id="street"  class="form-control" style="width:100%">
                                                 </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection3()"><span id="spinner2-1"></span>&nbsp; <span id="indicator2-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection1()"><span id="spinner2-2"></span>&nbsp; <span id="indicator2-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--address end-->
                                            <!--contact start-->
                                            <div class="card-body row" id="section-3" hidden>
                                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                                    <label>Phone number <code><b><span id="phone_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control phone-number" name="phone" id="phone" placeholder="e.g. +250788888888" required>
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
                                                    <label>Parent Phone (if any)</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +250788888888">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                                    <label>Second Phone (if any)</label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-phone"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +250788888888">
                                                    </div>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection4()"><span id="spinner3-1"></span>&nbsp; <span id="indicator3-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3-2"></span>&nbsp; <span id="indicator3-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--contact end-->
                                            
                                            <!--academic start-->
                                            <div class="card-body row" id="section-4" hidden>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus <code><b><span id="camp_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="cump_id" id="cump_id" required>
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
                                                    <span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="prg">
                                                    <label>Program Type <code><b><span id="prg_star"></span></b></code></label>
                                                    <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" required>
                                                    </select>
                                                    <span id="spinner00"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fct">
                                                    <label>School<code><b><span id="fac_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                                    </select>
                                                    <span id="spinner000"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">
                                                    <label>Department <code><b><span id="dept_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                                    </select>
                                                    <span id="spinner0000"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="spec">
                                                    <label>Specialization</label>
                                                    <select  class="form-control select2" style="width:100%" name="spcs_id" id="spcs_id" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="level">
                                                    <label>Level</label>
                                                    <select  class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="intake">
                                                    <label>Intake</label>
                                                    <select  class="form-control select2" style="width:100%" name="intake_id" id="intake_id" required>
                                                    </select>

                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="mode">
                                                    <label>Learning Mode</label>
                                                    <select  class="form-control select2" style="width:100%" name="mode" id="mode">
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
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <!--<button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>-->
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection5()"><span id="spinner4-1"></span>&nbsp; <span id="indicator4-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner4-2"></span>&nbsp; <span id="indicator4-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--academic end-->
                                            
                                            <!--additional start-->
                                            <div class="card-body row" id="section-5" hidden>
                                                <div class="form-group col-md-12" style="display: flex; flex-direction: row; flex-wrap: wrap; background-color: #EDFCF4; padding-top: 10px;">
                                                    <h6 class="form-group col-12">Next of kin/ Guardian (to be contacted in case of emergency)</h6>
                                                    <div class="form-group col-md-6">
                                                        <label>Name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="kin_name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Relationship</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-users"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="kin_relation" placeholder="e.g. Uncle" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Address</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-map"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="kin_address" placeholder="e.g. kigali" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Email</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-envelope"></i>
                                                            </div>
                                                        </div>
                                                        <input type="email" name="kin_email" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Phone</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" name="kin_tel" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <hr/>
                                                <br>
                                                <div class="form-group col-12 col-sm-5 col-lg-5">
                                                    <label class="col-form-label col-md-12">Principal Passes<span class="required" style="color:red">*</span></label>
                                            		<div class="col-12">
                                                    	<input type="number" class="form-control" min="1" name="tcounter" onkeyup="generateCourses(this.value)" required>
                                            		</div>
                                            	</div>
                                                <div class="form-group col-12 col-sm-5 col-lg-5">
                                                    <label class="col-form-label col-md-12">Sponsor<span class="required" style="color:red">*</span></label>
                                            		<div class="col-12">
                                                        <select  class="form-control select2" style="width:100%" name="spon_id" required>
                                                            <?php
                                                                $sql_spon = $conn->prepare("SELECT * FROM tbl_sponsor ORDER BY spon_full_name ASC");
                                                                $sql_spon->execute();
                                                                while($spon = $sql_spon->fetch()){
                                                            ?>
                                                            <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                            		</div>
                                            	</div>
                                            	<div class="form-group row col-sm-7 col-lg-7" id="course-container"></div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection4()"><span id="spinner5"></span>&nbsp; <span id="indicator5">Back</span></button>
                                                </div>
                                            </div>
                                            <!--additional end-->
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $("#apply").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Processing...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    if(data.status==200){
                        var mail=$("#email").val().trim();
                        $("#apply")[0].reset();
                        pop_up_success(data.message);
                        setTimeout(function() {
                          window.location.href = "verify_email?em="+mail;
                        }, 2000);
                        
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
          
        //load provinces
        $('#country').change(function () {
            $("#province_id").attr('required', true);
            $("#district_id").attr('required', true);
            $("#sector").attr('required', true);
            $("#cell_id").attr('required', true);
            $("#village_id").attr('required', false);
            $("#prov").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#village").attr('hidden', true);
            $("#province_id").empty();
            $("#district_id").empty();
            $("#sector").empty();
            $("#cell_id").empty();
            $("#village_id").empty();
            
            var getData= {
                    action:'load_provinces'
                    };
                
            $('#spinner_cntr').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                
                url: "/files/application/application_controller",
                data: getData,
                 type: "POST",
                dataType:"JSON",
                success:function(data){
                    $('#spinner_cntr').fadeOut('fast');
                    $("#province_id").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                    });
                    $("#prov").attr('hidden',false);
				},
				error:function(error){
				    $('#spinner_cntr').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
          
           
        });

        //load districts
        $('#province_id').change(function () {
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    pid:$('#province_id').val(),
                    action:'load_districts'
                    };
            $("#district_id").empty();
            $('#spinner_prov').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_prov').fadeOut('fast');
                    $("#district_id").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict+"</option>");
                    });
                    $("#district").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_prov').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load sectors
        $('#district_id').change(function () {
             $("#village").attr('hidden', false);
        });
        
        //load cells
        
        //load villages
        
    //load program types
     $("#cump_id").change(function () {
            var campus = $("#cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#prg').css({'display':'none'});
            $('#fct').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#intake').css({'display':'none'});
            $('#level').css({'display':'none'});
            $('#mode').css({'display':'none'});
            
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
                            $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +" ["+value.prg_type_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load faculties
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
            $('#fct').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#fct').css({'display':'block'});
                    $("#fac_id").empty();
                   if(data.length>0){
                        $("#fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load departments
     $("#fac_id").change(function () {
            var fac_id = $("#fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#spinner000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner000').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });


    //load specs
     $("#dept_id").change(function () {
            var dept_id = $("#dept_id").val();
            var formdata = {
                department: dept_id,
                action: "load_specs"
            };
            $('#spec').css({'display':'none'});
            $('#spinner0000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0000').fadeOut('fast');
                    $('#spec').css({'display':'block'});
                    $("#spcs_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#spcs_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                   
                 },
                error:function(error){
                    $('#spinner0000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load levels
     $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_levels"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#level').css({'display':'block'});
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
    //load intakes
     $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_all_intakes"
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
                            $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month + " | "+value.acad_year+"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                } 
            });
        });
        
    //register new student
    $("#register_student").submit(function(e){
            e.preventDefault();
        var section4Valid = validateSection4();
            if (section4Valid) {
                $("#camp_star").html("");
                $("#prg_star").html("");
                $("#fac_star").html("");
                $("#dept_star").html("");
                var formData = new FormData(this);
                $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator').html("Saving...");
                    $.ajax({
                        url: "/files/admission/admission_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function(data){
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save option");
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
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
                     });
                }
        });
    });
     $("#prg_type").submit(function (e) { 
         e.preventDefault();
        function goToSection5() {
            

        }
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
<script>
	function generateCourses(max){
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
        field1Header.textContent = 'Course';
        field1Header.style.width = '60%';
        headerRow.appendChild(field1Header);
        
        // Header for field 2 column
        const field2Header = document.createElement('th');
        field2Header.textContent = 'Grade';
        field2Header.style.width = '30%';
        headerRow.appendChild(field2Header);
        
        // Append the header row to the table
        table.appendChild(headerRow);
        
        for (let i = 1; i <= max; i++) {
            // Create a table row
            const row = document.createElement('tr');
        
            // Create the label cell
            const labelCell = document.createElement('td');
            labelCell.textContent = + i;
        
            // Create the input for field 1
            const field1Input = document.createElement('input');
            field1Input.type = 'text';
            field1Input.className = 'form-control';
            field1Input.name = 'courses[]';
            field1Input.placeholder = 'course name';
        
            // Create the cell for field 1
            const field1Cell = document.createElement('td');
            field1Cell.appendChild(field1Input);
        
            // Create the input for field 2
            const field2Input = document.createElement('input');
            field2Input.type = 'text';
            field2Input.className = 'form-control';
            field2Input.name = 'grade[]';
            field2Input.placeholder = 'grade';
        
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
	
    //next buttons
    function goToSection2() {
        var section1Valid = validateSection1();
        if (section1Valid) {
            $("#fname_star").html("");
            $("#lname_star").html("");
            $("#nid_star").html("");
            $("#nat_star").html("");
            $("#gender_star").html("");
            $("#pname_star").html("");
            $("#mname_star").html("");
            $('#spinner1-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator1-1').html("loading...");
                setTimeout(function() {
                    $('#spinner1-1').fadeOut('fast');
                    $('#indicator1-1').html("Next");
                    $("#section-1-indicator").removeClass('btn-primary');
                    $("#section-1-indicator").addClass('btn-light');
                    $("#section-2-indicator").removeClass('btn-light');
                    $("#section-2-indicator").addClass('btn-primary');
                    $('#section-1').attr('hidden',true);
                    $('#section-2').attr('hidden',false);
                    }, 500);
        }

    }
    
    function goToSection3() {
        var section2Valid = validateSection2();
        if (section2Valid) {
            $("#prov_star").html("");
            $("#dis_star").html("");
            $("#sec_star").html("");
            $("#cel_star").html("");
            $("#vil_star").html("");
            $('#spinner2-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2-1').html("loading...");
                setTimeout(function() {
                    $('#spinner2-1').fadeOut('fast');
                    $('#indicator2-1').html("Next");
                    $("#section-2-indicator").removeClass('btn-primary');
                    $("#section-2-indicator").addClass('btn-light');
                    $("#section-3-indicator").removeClass('btn-light');
                    $("#section-3-indicator").addClass('btn-primary');
                    $('#section-2').attr('hidden',true);
                    $('#section-3').attr('hidden',false);
                    }, 500);
        }
    }

    function goToSection4() {
        var section3Valid = validateSection3();
        if (section3Valid) {
            $("#email_star").html("");
            $("#phone_star").html("");
            $('#spinner3-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator3-1').html("loading...");
                setTimeout(function() {
                    $('#spinner3-1').fadeOut('fast');
                    $('#indicator3-1').html("Next");
                    $("#section-3-indicator").removeClass('btn-primary');
                    $("#section-3-indicator").addClass('btn-light');
                    $("#section-4-indicator").removeClass('btn-light');
                    $("#section-4-indicator").addClass('btn-primary');
                    $('#section-3').attr('hidden',true);
                    $('#section-4').attr('hidden',false);
                    }, 500);
        }
    }
    
    function goToSection5() {
        var section4Valid = validateSection4();
        if (section4Valid) {
            $("#camp_star").html("");
            $("#prg_star").html("");
            $("#fac_star").html("");
            $("#dept_star").html("");
            $('#spinner4-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator4-1').html("loading...");
                setTimeout(function() {
                    $('#spinner4-1').fadeOut('fast');
                    $('#indicator4-1').html("Next");
                    $("#section-4-indicator").removeClass('btn-primary');
                    $("#section-4-indicator").addClass('btn-light');
                    $("#section-5-indicator").removeClass('btn-light');
                    $("#section-5-indicator").addClass('btn-primary');
                    $('#section-4').attr('hidden',true);
                    $('#section-5').attr('hidden',false);
                    }, 500);
        }

    }
    
    //back buttons
    function goBackToSection1() {
        $("#section-2-indicator").removeClass('btn-primary');
        $("#section-2-indicator").addClass('btn-light');
        $("#section-1-indicator").removeClass('btn-light');
        $("#section-1-indicator").addClass('btn-primary');
        $('#section-2').attr('hidden',true);
        $('#section-1').attr('hidden',false);
    }
    
    function goBackToSection2() {
        $("#section-3-indicator").removeClass('btn-primary');
        $("#section-3-indicator").addClass('btn-light');
        $("#section-2-indicator").removeClass('btn-light');
        $("#section-2-indicator").addClass('btn-primary');
        $('#section-3').attr('hidden',true);
        $('#section-2').attr('hidden',false);
    }
    
    function goBackToSection3() {
        $("#section-4-indicator").removeClass('btn-primary');
        $("#section-4-indicator").addClass('btn-light');
        $("#section-3-indicator").removeClass('btn-light');
        $("#section-3-indicator").addClass('btn-primary');
        $('#section-4').attr('hidden',true);
        $('#section-3').attr('hidden',false);
    }
    
    function goBackToSection4() {
        $("#section-5-indicator").removeClass('btn-primary');
        $("#section-5-indicator").addClass('btn-light');
        $("#section-4-indicator").removeClass('btn-light');
        $("#section-4-indicator").addClass('btn-primary');
        $('#section-5').attr('hidden',true);
        $('#section-4').attr('hidden',false);
    }
    
    //validations
    function validateSection1() {
        var fname = document.getElementById('fname').value.trim();
        var lname = document.getElementById('lname').value.trim();
        var nid = document.getElementById('nid').value.trim();
        var nat = document.getElementById('nationality').value.trim();
        var gender = document.getElementById('gender').value.trim();
        var pname = document.getElementById('father_names').value.trim();
        var mname = document.getElementById('mother_names').value.trim();

        if (fname === '' || lname === '' || nid === '' || gender === '' || nat==='' || pname === '' || mname === '') {
            pop_wrong("Fill all missing fields"); 
            fname===''?$("#fname_star").html("*"):$("#fname_star").html("");
            lname===''?$("#lname_star").html("*"):$("#lname_star").html("");
            nid===''?$("#nid_star").html("*"):$("#nid_star").html("");
            nat===''?$("#nat_star").html("*"):$("#nat_star").html("");
            gender===''?$("#gender_star").html("*"):$("#gender_star").html("");
            pname===''?$("#pname_star").html("*"):$("#pname_star").html("");
            mname===''?$("#mname_star").html("*"):$("#mname_star").html("");
            return false;
        }

        return true;
    }
    function validateSection2() {
        var cntr = document.getElementById('country').value;
        var prov = document.getElementById('province_id').value;
        var dis = document.getElementById('district_id').value;
        var sec = document.getElementById('sector').value;
        var cel = document.getElementById('cell_id').value;
        var vil = document.getElementById('village_id').value;
        if(cntr === '160'){
            if (prov === '' || dis === '' || sec === '' || cel==='' ) {
                pop_wrong("Fill all missing fields"); 
                prov===''?$("#prov_star").html("*"):$("#prov_star").html("");
                dis===''?$("#dis_star").html("*"):$("#dis_star").html("");
                sec===''?$("#sec_star").html("*"):$("#sec_star").html("");
                cel===''?$("#cel_star").html("*"):$("#cel_star").html("");
                 vil===''?$("#vil_star").html("*"):$("#vil_star").html("");
                return false;
            }
        }
        return true;
    }
    function validateSection3() {
        var email = document.getElementById('email').value.trim();
        var phone = document.getElementById('phone').value.trim();
        if (email === '' || phone === '') {
            pop_wrong("Fields with * are required"); 
            email===''?$("#email_star").html("*"):$("#email_star").html("");
            phone===''?$("#phone_star").html("*"):$("#phone_star").html("");
            return false;
        }
        return true;
    }
    function validateSection4() {
        var campus = document.getElementById('cump_id').value.trim();
        var prg_type = document.getElementById('prg_type').value.trim();
        var fac = document.getElementById('fac_id').value.trim();
        var dept = document.getElementById('dept_id').value.trim();
        if (campus === '' || prg_type === '' || fac === '' || dept === '') {
            pop_wrong("All fields are required"); 
            campus===''?$("#camp_star").html("*"):$("#camp_star").html("");
            prg_type===''?$("#prg_star").html("*"):$("#prg_star").html("");
            fac===''?$("#fac_star").html("*"):$("#fac_star").html("");
            dept===''?$("#dept_star").html("*"):$("#dept_star").html("");
            
            return false;
        }
        return true;
    }
</script>