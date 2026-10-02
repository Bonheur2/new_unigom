<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Student Information</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Student Info</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="search by reg. number or names" id="input">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="contents">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="info" hidden>
        <div class="section-body"> 
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <div style="padding-bottom:8px;">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Firstname:</th>
                                            <th scope="col" id="fn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Lastname:</th>
                                            <th scope="col" id="ln"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Gender:</th>
                                            <th scope="col" id="gen"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Marital status:</th>
                                            <th scope="col" id="mstatus"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Date of Birth:</th>
                                            <th scope="col" id="dobo"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nationality:</th>
                                            <th scope="col" id="nat"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">ID/passport:</th>
                                            <th scope="col" id="nid"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Father:</th>
                                            <th scope="col" id="ftn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Mother:</th>
                                            <th scope="col" id="mtn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Intake:</th>
                                            <th scope="col" id="intake"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                            <br/>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_p" data-id=""><span id="spinner_p"></span>&nbsp;<span id="indicator_p">update info</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Contact information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse3" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse3">
                            <div class="card-body">
                                <div class=" ">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Email:</th>
                                            <th scope="col" id="em"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Phone:</th>
                                            <th scope="col" id="pn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Parent's Phone:</th>
                                            <th scope="col" id="ppn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Second Phone:</th>
                                            <th scope="col" id="secpn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Next of Kin:</th>
                                            <th scope="col" id="kin_na"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Relation:</th>
                                            <th scope="col" id="kin_re"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Address:</th>
                                            <th scope="col" id="kin_ad"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Email:</th>
                                            <th scope="col" id="kin_em"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Phone:</th>
                                            <th scope="col" id="kin_te"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_c"  data-id=""><span id="spinner_c"></span>&nbsp;<span id="indicator_c">update info</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Address information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Country:</th>
                                            <th scope="col" id="cntr"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Province:</th>
                                            <th scope="col" id="prov"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">District:</th>
                                            <th scope="col" id="dis"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Street:</th>
                                            <th scope="col" id="sec"></th>
                                        </tr>
                                       
                                    </thead>
                                    </table>
                                </div><br><br>
                            </div>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_a"  data-id=""><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Academic information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Program Type</th>
                                    <th>School</th>
                                    <th>Specialzization</th>
                                    <th>Level</th>
                                    <th>Academic Year</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="academics">
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success btn-sm" id="acceptance"><i class="fa fa-cloud"></i> Admission letter</button>&nbsp;
                            <button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('/files/Student/fileAdmin?stu=', 'STUDENT FILE', 800, 600);"><i class="fa fa-download"></i> Download File</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Religious Belief</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse10" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse10">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">church</th>
                                                <th scope="col">country</th>
                                                <th scope="col">city</th>
                                                <th scope="col">sector</th>
                                                <th scope="col">licenced</th>
                                                <th scope="col">minister</th>
                                                <th scope="col">activities</th>
                                                <th scope="col">ordained</th>
                                                
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <td id="chrch"></td>
                                                <td id="cntry"></td>
                                                <td id="cty"></td>
                                                <td id="sctr"></td>
                                                <td id="lcncd"></td>
                                                <td id="mnstr"></td>
                                                <td id="ctvts"></td>
                                                <td id="rdnd"></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                <button class="btn btn-primary btn-sm" id="update_chur"  data-id=""><span id="spinner_chur"></span>&nbsp;<span id="indicator_chur">update info</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Previous Education</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-20" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse-20">
                            <div class="table-responsive">
                                <button class="btn btn-success btn-sm" id="update_prev_edu" data-id="">Add new</button>
                                <table class="table  table-striped mb-none" id="datatable-default">  
									<thead>
										<tr>
											<th>S/N</th>
        									<th>Institution Name</th>
        									<th>Joined From</th>
        									<th>Ended</th>
        									<th>Diploma/ Certificate</th>
        									<th>Award obtained</th>
        									<th>Action</th>
										</tr>
									</thead>
									<tbody id="prev_edu_data">
									</tbody>
								</table>
					        </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Principal Passes</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse5" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse5">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="passes">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sponsorship</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse6" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse6">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Level</th>
                                    <th>Sponsor</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="sponsors">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--update modal personal-->
    <form action="update_form_p" method="POST" id="update_form_p">
        <div class="modal fade" role="dialog" id="updateModal_p">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_personal">
                <input type="hidden" name="stu" id="stu">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Personal Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Surname (Family name)</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. GASANA" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>First name</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Annet" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <div class="input-group">
                                <select name="nationality" id="nationality"  class="form-control select2" style="width:100%;">
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
                        </div>
                        <div class="form-group">
                            <label>ID/ Passport</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="nid" id="enid" placeholder="e.g. 1 1990 800 xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="input-group">
                                <select name="gender" id="gender"  class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                    <option value="" disabled selected hidden>Choose One...</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date of birth</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <input type="date" class="form-control" name="dob" id="dob" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Marital status</label>
                            <div class="input-group">
                                <select name="marital_status" id="marital_status" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                    <option value="" disabled selected hidden>Choose One...</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Father's name</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <input type="text" name="father_names" id="father_names" class="form-control" placeholder="father's full names" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mother's name</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <input type="text" name="mother_names" id="mother_names" class="form-control" placeholder="mother's full names" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->  
    <!--update modal contact-->
    <form action="update_form_c" method="POST" id="update_form_c">
        <div class="modal fade" role="dialog" id="updateModal_c">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_contact">
                <input type="hidden" name="stuc" id="stuc">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Contact Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <input type="email" class="form-control" name="email" id="email" placeholder="e.g. gasana@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-mobile"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="e.g. +250788888888" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Parent Phone</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="email" class="form-control" name="parent_phone" id="parent_phone" placeholder="e.g. +250788888888">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Second Phone (optional)</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="ref_phone" id="ref_phone" class="form-control" placeholder="e.g. +250788888888">
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label>Next of Kin</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_name" id="kin_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Relation</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-refresh"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_relation" id="kin_relation" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-map"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_address" id="kin_address" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <input type="email" name="kin_email" id="kin_email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="kin_tel" id="kin_tel" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_c"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal contact-->
    <!--update modal address-->
    <form action="update_form_a" method="POST" id="update_form_a">
        <div class="modal fade" role="dialog" id="updateModal_a">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_address">
                <input type="hidden" name="stua" id="stua">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Address Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Country of residence</label>
                            <select name="country" id="country" class="form-control select2" style="width:100%;">
                                <?php
                                    $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                    $sql_cntr->execute();
                                    while($cntr=$sql_cntr->fetch()){
                                ?>
                                    <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                    <?php } ?>
                            </select><span id="spinner_cntr"></span> 
                        </div>
                        <div class="form-group" id="prov">
                            <label>Province</label>
                            <select name="province_id" id="province_id" class="form-control select2" style="width:100%;">
                            </select><span id="spinner_prov"></span>
                        </div>
                        <div class="form-group" id="district">
                            <label>District</label>
                            <select name="district_id" id="district_id" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_dis"></span>
                        </div>
                        <div class="form-group" id="sect">
                            <label>Sector</label>
                            <select name="sector" id="sector" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_sect"></span>
                        </div>
                        <div class="form-group" id="cell">
                            <label>Cell</label>
                            <select name="cell_id" id="cell_id" class="form-control select2" style="width:100%;">
                                
                            </select><span id="spinner_cell"></span>
                        </div>
                        <div class="form-group" id="village">
                            <label>Village</label>
                            <select name="village_id" id="village_id" class="form-control select2" style="width:100%;">
                                
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="uBtn"><span id="spinner2_a"></span>&nbsp;<span id="indicator2_a">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
    
    <!--update modal church-->
    <form action="update_form_chur" method="POST" id="update_form_chur">
        <div class="modal fade" role="dialog" id="updateModal_chur">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Church Affiliation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_church">
                        <input type="hidden" name="stu" id="stuch">
                        <div class="form-group">
                            <label>Church</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-city"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="church" id="church" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <div class="input-group">
                                <select name="country" id="countryc" class="form-control select2" style="width:100%;">
                                    <?php
                                        $sql_nat=$conn->prepare("SELECT * FROM tbl_country");
                                        $sql_nat->execute();
                                        $i=1;
                                        while($country = $sql_nat->fetch()){
                                    ?>
                                    <option value="<?php echo $country['cntr_id']; ?>" <?php echo $applicantData['country'] == $country['cntr_id']?'selected':''; ?>><?php echo $country['cntr_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>District / City</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="city" id="city" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sector</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="sector" id="sector" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>licenced?</label>
                            <div class="input-group">
                                <select name="licenced" id="licenced" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ordained?</label>
                            <div class="input-group">
                                <select name="ordained" id="ordained" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Are you a minister?</label>
                            <div class="input-group">
                                <select name="minister" id="minister" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>If you are a minister, kindly, list Christian service activities in which you have engaged in</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-list"></i>
                                    </div>
                                </div>
                                <input type="text" name="activities" id="activities" class="form-control" placeholder="activity 1, activity 2, activity 3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="uchurBtn"><span id="spinner2_chur"></span>&nbsp;<span id="indicator2_chur">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
    
    <!--update modal academic-->
    <form action="update_form_acc" method="POST" id="update_form_acc">
        <div class="modal fade" role="dialog" id="updateModal_acc">
            <div class="modal-dialog modal-lg" role="document">
                <input type="hidden" name="action" value="update_acc">
                <input type="hidden" name="reg_prg_id" id="reg_prg_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Academic Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6" id="prg">
                                <label>Student ID</label>
                                <input type="text" class="form-control" name="reg_no" id="stu_reg_no" readonly>
                            </div>
                            <div class="form-group col-12 col-md-6" id="prg">
                                <label>Program Type</label>
                                <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" disabled readonly>
                                    <?php
                                        $sql_prg=$conn->prepare("SELECT 
                                                        tbl_program_type.*,
                                                        tbl_campus.camp_full_name 
                                                            FROM tbl_program_type 
                                                        INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                            ORDER BY tbl_program_type.status ASC");
                                        $sql_prg->execute();
                                        $i=1;
                                        while($progs_faculty=$sql_prg->fetch()){
                                            ?>
                                    <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                <span id="spinner00"></span>
                            </div>
                            <div class="form-group col-12 col-md-6" id="fct">
                                <label>School</label>
                                <select  class="form-control select2" style="width:100%" name="fac_id" id="fac_id" disabled readonly>
                                </select>
                                <span id="spinner000"></span>
                            </div>
                            <div class="form-group col-12 col-md-6" id="dept">
                                <label>Department</label>
                                <select  class="form-control select2" style="width:100%" name="dept_id" id="dept_id" disabled readonly>
                                </select>
                                <span id="spinner0000"></span>
                            </div>
                            <div class="form-group col-12 col-md-6" id="spec">
                                <label>Specialization</label>
                                <select  class="form-control select2" style="width:100%" name="splz_id" id="splz_id" disabled readonly>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6" id="level">
                                <label>Level</label>
                                <select  class="form-control select2" style="width:100%" name="level_id" id="level_id" disabled readonly>
                                </select>
    
                            </div>
                            <div class="form-group col-12 col-md-6" id="mod">
                                <label>Learning Mode</label>
                                <select  class="form-control select2" style="width:100%" name="prg_mode_id" id="prg_mode_id">
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
                            <div class="form-group col-12 col-md-6">
                                <label>Status</label>
                                <select class="form-control select2" style="width:100%" name="reg_active" id="reg_active">
                                    <?php
                                        $sql=$conn->prepare("SELECT * FROM tbl_status WHERE 1");
                                        $sql->execute();
                                        while($status=$sql->fetch()){
                                    ?>
                                    <option value="<?php echo $status['status_id']; ?>"><?php echo $status['status_full_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="uacBtn"><span id="spinner5_acc"></span>&nbsp;<span id="indicator5_acc">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal academic-->
    
    <!--update previous education-->
    <form action="update_form_prev" method="POST" id="update_form_prev">
        <div class="modal fade" role="dialog" id="updateModal_prevedu">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="stu" id="stucodep">
                <input type="hidden" name="action" value="save_education">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Previous Education</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-12">
                            <label>Institution Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="school" maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>From</label>
                            <div class="input-group" >
                                <select name="from" class="form-control select2" style="width: 100%;" required>
                                  <?php
                                    $currentYear = date("Y");
                                    $startYear = 1970;
                                    $endYear = date('Y');
                                
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                      echo "<option value='$year'>$year</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>To</label>
                            <div class="input-group" >
                                <select name="to" class="form-control select2" style="width: 100%;" required>
                                  <?php
                                    $currentYear = date("Y");
                                    $startYear = 1970;
                                    $endYear = date('Y');
                                
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                      echo "<option value='$year'>$year</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Certificate or Degree Obtained</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="certificate" maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label>Award Obtained</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="award" maxlength="60" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><span id="spinner2_prev"></span>&nbsp;<span id="indicator2_prev">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update previous education--> 
    
    <!--update modal sponsor-->
    <form action="update_form_spon" method="POST" id="update_form_spon">
        <div class="modal fade" role="dialog" id="updateModal_spon">
            <div class="modal-dialog modal-lg" role="document">
                <input type="hidden" name="action" value="update_spon">
                <input type="hidden" name="reg_prg_id" id="reg_prg_ids">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Sponsorship</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label>Student ID</label>
                                <input type="text" class="form-control" name="reg_no" id="stu_reg_nos" readonly>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label>Sponsor</label>
                                <select class="form-control select2" style="width:100%" name="spon_id" id="spon_id" required>
                                    <?php
                                        $sql_spon = $conn->prepare("SELECT * FROM tbl_sponsor ORDER BY spon_full_name ASC");
                                        $sql_spon->execute();
                                        while($spon = $sql_spon->fetch()){
                                    ?>
                                    <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="uasBtn"><span id="spinner5_spon"></span>&nbsp;<span id="indicator5_spon">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal sponsor-->
    
    <!--update modal pass-->
    <form action="update_form_pass" method="POST" id="update_form_pass">
        <div class="modal fade" role="dialog" id="updateModal_pass">
            <div class="modal-dialog" role="document">
                <input type="hidden" name="action" value="update_pass">
                <input type="hidden" name="course_id" id="course_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating Course info.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12"> 
                                <label>Course</label>
                                <input type="text" class="form-control" name="course" id="course" required>
                            </div>
                            <div class="form-group col-12">
                                <label>Grade</label>
                                <input type="text" class="form-control" name="grade" id="grade" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="upassBtn"><span id="spinner10_pass"></span>&nbsp;<span id="indicator10_pass">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal personal-->
</div>
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var pageURL = pageURL+$("#input").val();
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>
<?php include('scripts.php'); ?>