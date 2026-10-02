	<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
<style>

		.image_area {
		  position: relative;
		}

		img {
		  	display: block;
		  	max-width: 100%;
		}

		.preview {
  			overflow: hidden;
  			width: 160px;
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}
		.preview_edit {
  			overflow: hidden;
  			width: 160px;
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}

</style>
                  <!--start of crop image modal for insert-->
                                               <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            			  	<div class="modal-dialog modal-lg" role="document">
                            			    	<div class="modal-content">
                            			      		<div class="modal-header">
                            			        		<h5 class="modal-title">Crop Image Before Upload</h5>
                            			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            			          			<span aria-hidden="true">×</span>
                            			        		</button>
                            			      		</div>
                            			      		<div class="modal-body">
                            			        		<div class="img-container">
                            			            		<div class="row">
                            			                		<div class="col-md-4">
                            			                    		<img src="" id="sample_image" />
                            			                		</div>
                            			                		<div class="col-md-4">
                            			                    		<div class="preview"></div>
                            			                		</div>
                            			            		</div>
                            			        		</div>
                            			      		</div>
                            			      		<div class="modal-footer">
                            			      			<button type="button" id="crop" class="btn btn-primary"><span id="spinner35"></span>Crop</button>
                            			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            			      		</div>
                            			    	</div>
                            			  	</div>
                            			</div>
                   <!--end of corp image for insert-->
                   <!--start of crop image modal for edit-->
                                               <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            			  	<div class="modal-dialog modal-lg" role="document">
                            			    	<div class="modal-content">
                            			      		<div class="modal-header">
                            			        		<h5 class="modal-title">Crop Image Before Upload</h5>
                            			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            			          			<span aria-hidden="true">×</span>
                            			        		</button>
                            			      		</div>
                            			      		<div class="modal-body">
                            			        		<div class="img-container">
                            			            		<div class="row">
                            			                		<div class="col-md-4">
                            			                    		<img src="" id="sample_image_edit" />
                            			                		</div>
                            			                		<div class="col-md-4">
                            			                    		<div class="preview_edit"></div>
                            			                		</div>
                            			            		</div>
                            			        		</div>
                            			      		</div>
                            			      		<div class="modal-footer">
                            			      			<button type="button" id="crop_edit" class="btn btn-primary"><span id="spinner84"></span>Crop</button>
                            			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            			      		</div>
                            			    	</div>
                            			  	</div>
                            			</div>
                   <!--end of corp image for edit-->
                <div class="main-content">
                   <section class="section">
                       <div class="section-header">
                          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#mycard-collapse" aria-expanded="false" aria-controls="collapseExample">
                                           <i class="fas fa-user"></i> &nbsp;  Register New Employee 
                                        </button>
                         </div>
                       
                        <div class="collapse hide" id="mycard-collapse">
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                                    <form id="register_employee" action="" method="POST">
                                        <input type="hidden" name="action" value="register_new_employee">
                                        <div class="card">
                                            <div class="card-header row" style="display:flex; justify-content:center">
                                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-2 col-lg-2" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Personal info</button>
                                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;Address info</button>
                                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Contact info</button>
                                                    <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">4</span> &nbsp;Post info</button>
                                                    <button type="button" id="section-5-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">5</span> &nbsp;Account info</button>
                                            </div>
                                            <!--personal details start-->
                                            <div class="card-body row" id="section-1">
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>Image <code><b><span id="image_star"></span></b></code></label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" name="upload_image" id="upload_image">
                                                        </div>
                                                    </div>
                                    			   <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Surname (Family name) <code><b><span id="lname_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. Nshuti" required>
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
                                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Clemant" required>
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
                                                    <label>Gender <code><b><span id="gender_star"></span></b></code></label>
                                                    <select name="gender" id="gender" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Martial Status <code><b><span id="martial_star"></span></b></code></label>
                                                    <select name="mstatus" id="mstatus" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="M">Married</option>
                                                        <option value="S">Single</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label>Date of Birth</label>
                                                        <input type="date" class="form-control" id="st_dob" name="st_dob">
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
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="sect" hidden>
                                                    <label>Sector <code><b><span id="sec_star"></span></b></code></label>
                                                    <select name="sector" id="sector"  class="form-control select2" style="width:100%">
                                                        
                                                    </select><span id="spinner_sect"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="cell" hidden>
                                                    <label>Cell <code><b><span id="cel_star"></span></b></code></label>
                                                    <select name="cell_id" id="cell_id"  class="form-control select2" style="width:100%">
                                                        
                                                    </select><span id="spinner_cell"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="village" hidden>
                                                    <label>Village <code><b><span id="vil_star"></span></b></code></label>
                                                    <select name="village_id" id="village_id"  class="form-control select2" style="width:100%">
                                                        
                                                    </select><span id="spinner_vill"></span>
                                                    
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
                                                <!--<div class="form-group col-12 col-sm-6 col-lg-6">-->
                                                <!--    <label>Parent Phone (if any)</label>-->
                                                <!--    <div class="input-group">-->
                                                <!--    <div class="input-group-prepend">-->
                                                <!--        <div class="input-group-text">-->
                                                <!--        <i class="fas fa-phone"></i>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--    <input type="text" name="parent_phone" class="form-control" placeholder="e.g. +250788888888">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-12 col-sm-6 col-lg-6">-->
                                                <!--    <label>Second Phone (if any)</label>-->
                                                <!--    <div class="input-group">-->
                                                <!--    <div class="input-group-prepend">-->
                                                <!--        <div class="input-group-text">-->
                                                <!--        <i class="fas fa-phone"></i>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--    <input type="text" name="ref_phone" class="form-control" placeholder="e.g. +250788888888">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection4()"><span id="spinner3-1"></span>&nbsp; <span id="indicator3-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3-2"></span>&nbsp; <span id="indicator3-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--contact end-->
                                            
                                            <!--job info start-->
                                            <div class="card-body row" id="section-4" hidden>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus <code><b><span id="camps_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="camps_id" id="camps_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_cmp=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                            $sql_cmp->execute();
                                                            $i=1;
                                                            while($progs_camps=$sql_cmp->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_camps['camp_id']; ?>"><?php echo $progs_camps['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner30"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="dep_form" style="display:none">
                                                    <label>Department <code><b><span id="dep_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="dep_id" id="dep_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_dpt=$conn->prepare("SELECT * FROM tbl_department WHERE status=1");
                                                            $sql_dpt->execute();
                                                            $i=1;
                                                            while($progs_depart=$sql_dpt->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_depart['dept_id']; ?>"><?php echo $progs_depart['dept_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner10"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="academic_form" style="display:none">
                                                    <label>Academic / not Academic <code><b><span id="accademic_star"></span></b></code></label>
                                                    <select name="accademic" id="accademic" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="1">Academic</option>
                                                        <option value="0">No Academic</option>
                                                    </select>
                                                    <span id="spinner31"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="probition_form" style="display:none">
                                                    <label>Probation Period <code><b><span id="probition_star"></span></b></code></label>
                                                    <select name="probiton" id="probiton" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="3">3 months</option>
                                                        <option value="6">6 months</option>
                                                    </select>
                                                    <span id="spinner32"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="part_full_form" style="display:none">
                                                    <label>Fulltime / Part time <code><b><span id="probition_star"></span></b></code></label>
                                                    <select name="part_full" id="part_full" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="1">Full Time</option>
                                                        <option value="0">Part Time</option>
                                                    </select>
                                                    <span id="spinner33"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="start_date_form" style="display:none">
                                                    <div class="form-group">
                                                        <label>Start date</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="pst_id" style="display:none;">
                                                    <label>Post <code><b><span id="pos_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="post_id" id="post_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $stmt1=$conn->prepare("SELECT * FROM tbl_post WHERE status=1");
                                                            $stmt1->execute();
                                                            $i=1;
                                                            while($post=$stmt1->fetch()){
                                                                ?>
                                                        <option value="<?php echo $post['post_id']; ?>"><?php echo $post['post_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner11"></span>
                                                </div>
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="fct">-->
                                                <!--    <label>Faculty <code><b><span id="fac_star"></span></b></code></label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>-->
                                                <!--    </select>-->
                                                <!--    <span id="spinner000"></span>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="dept">-->
                                                <!--    <label>Department <code><b><span id="dept_star"></span></b></code></label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>-->
                                                <!--    </select>-->
                                                <!--    <span id="spinner0000"></span>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="spec">-->
                                                <!--    <label>Specialization</label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="spcs_id" id="spcs_id" required>-->
                                                <!--    </select>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="level">-->
                                                <!--    <label>Level</label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="level_id" id="level_id" required>-->
                                                <!--    </select>-->

                                                <!--</div>-->
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" style="display:none;" id="intake">-->
                                                <!--    <label>Intake</label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="intake_id" id="intake_id" required>-->
                                                <!--    </select>-->

                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <!--<button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>-->
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection5()"><span id="spinner4-1"></span>&nbsp; <span id="indicator4-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner4-2"></span>&nbsp; <span id="indicator4-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--job info start-->
                                            
                                            <!--Accounts start-->
                                            <div class="card-body row" id="section-5" hidden>
                                                    <div class="form-group col-12 col-sm-4 col-lg-4" id="bank_name_id">
                                                    <label>Bank Name <code><b><span id="bank_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-bank"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Example: World Bank" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="Acc_id" style="display:none;">
                                                    <label>Account Number <code><b><span id="acc_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="acc_number" id="acc_number" placeholder="ex:40099...." required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="sal_id" style="display:none;">
                                                    <label>Basic Salary <code><b><span id="salary_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-money" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                    <input type="number" class="form-control" name="salary" id="salary" placeholder="Basic Salary">
                                                    </div>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                                   <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection4()"><span id="spinner5"></span>&nbsp; <span id="indicator5">Previous</span></button>
                                                  </div>
                                              </div>
                                            </div>
                                            <!--address end-->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--start of edit form-->
                    <div class="row" id="edit_card" style="display:none">
                        <div class="col-12 col-lg-12 col-sm-12">
                             <div class="card">
                                <div class="card-header">
                                    <h4>Edit Employee Details</h4>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                   <div class="col-12 col-md-12 col-lg-12">
                                    <div class="row">
                                            <button type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-primary firstly" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">Personal info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light second" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="true">Address info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light third" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="true">Contact info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light fourth" data-toggle="collapse" data-target="#panel-body-4" aria-expanded="true">Post info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light fifth" data-toggle="collapse" data-target="#panel-body-5" aria-expanded="true">Account info</button>
                                        </div>
                                     </div>
                                </div>
                                
                                <form action="" method="POST" id="edit_employee_form">
                                    <input type="hidden" value="confirm_edit" name="action">
                                    <input type="hidden" id="staff_id" name="staff_id">
                                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-row">
                                 <div class="form-group col-md-4">
                                                        <label>Change Image <code><b><span id="image_star_edit"></span></b></code></label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                     </div>
                                                  </div>
                                               <input type="file" class="form-control" name="upload_image_edit" id="upload_image_edit">
                                            </div>
                                         </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Surname (Family name)</label>
                                            <input type="text" class="form-control" id="lname_edit" name="lname_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">First name</label>
                                            <input type="text" class="form-control" id="fname_edit" name="fname_edit">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">ID/ Passport</label>
                                            <input type="number" class="form-control" id="idn_edit" name="idn_edit">
                                        </div>
                                     <div class="form-group col-md-4">
                                            <label for="inputEmail4">Gender</label>
                                           <select name="gender_edit" id="gender_edit" class="form-control select2" style="width: 100%" required>
                                             <option value="M">Male</option>
                                                <option value="F">Female</option>
                                            </select>

                                             </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Martial Status</label>
                                            <select name="martial_edit" id="martial_edit" class="form-control select2" style="width: 100%" required>
                                             <option value="M">Married</option>
                                                <option value="S">Single</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Date of Birth</label>
                                             <input type="date" class="form-control" id="dob_edit" name="dob_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Nationality</label>
                                            <select class="form-control select2" id="nationality_edit" name="nationality_edit" style="width: 100%">
                                         </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Father's name</label>
                                            <input type="text" class="form-control" id="father_edit" name="father_edit">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputZip">Mother's name</label>
                                            <input type="text" class="form-control" id="mother_edit" name="mother_edit">
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                  <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="inputEmail4">Country of residence</label>
                                            <select class="form-control select2" id="residence_edit" name="residence_edit" style="width: 100%">
                                         </select>
                                        </div><span id="spinner21"></span>
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword4">Province</label>
                                            <select class="form-control select2" id="province_edit" name="province_edit" style="width: 100%">
                                         </select>
                                        </div><span id="spinner22"></span>
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword4">District</label>
                                            <select class="form-control select2" id="district_edit" name="district_edit" style="width: 100%">
                                         </select>
                                        </div><span id="spinner23"></span>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="inputEmail4">Sector</label>
                                             <select class="form-control select2" id="sector_edit" name="sector_edit" style="width: 100%">
                                         </select>
                                        </div><span id="spinner24"></span>
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword4">Cell</label>
                                            <select class="form-control select2" id="cell_edit" name="cell_edit" style="width: 100%">
                                         </select>
                                        </div><span id="spinner25"></span>
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword4">Village</label>
                                             <select class="form-control select2" id="village_edit" name="village_edit" style="width: 100%">
                                         </select>
                                        </div>
                                    </div>
                                  </div>
                             </div>
                             <div class="accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Phone number</label>
                                           <input type="text" class="form-control" id="phone_edit" name="phone_edit">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputPassword4">E-mail</label>
                                            <input type="email" class="form-control" id="email_edit" name="email_edit">
                                        </div>
                                    </div>
                                  </div>
                             </div>
                             <div class="accordion-body collapse" id="panel-body-4" data-parent="#accordion">
                               <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Campus</label>
                                            <select class="form-control select2" id="campus_edit" name="campus_edit" style="width: 100%">
                                         </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Department</label>
                                            <select class="form-control select2" id="department_edit" name="department_edit" style="width: 100%">
                                         </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Academic / not Academic</label>
                                           <select name="accademic_edit" id="accademic_edit" class="form-control select2" style="width: 100%" required>
                                             <option value="1">Academic</option>
                                                <option value="0">Not Academic</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Probation Period </label>
                                           <select name="probition_edit" id="probition_edit" class="form-control select2" style="width: 100%" required>
                                             <option value="3">3 Months</option>
                                                <option value="6">6 Months</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">start date</label>
                                             <input type="date" class="form-control" id="start_date_edit" name="start_date_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Fulltime / Part time</label>
                                           <select name="part_full_edit" id="part_full_edit" class="form-control select2" style="width: 100%" required>
                                             <option value="1">Full Time</option>
                                                <option value="0">Part Time</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Post</label>
                                            <select class="form-control select2" id="post_edit" name="post_edit" style="width: 100%">
                                         </select>
                                        </div>
                                    </div>
                                  </div>
                             </div>
                             <div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4">Bank Name</label>
                                              <input type="text" class="form-control" id="bank_edit" name="bank_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Account Number</label>
                                            <input type="number" class="form-control" id="account_edit" name="account_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Basic Salary</label>
                                           <input type="number" class="form-control" id="salary_edit" name="salary_edit">  
                                        </div>
                                    </div>
                                  </div>
                             </div>
                             <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right mb-1"><span id="spinner20"></span>Save changes</button>
                                </div>
                                </form>
                            </div>
                           </div>
                        </div>
                    </div>
                    <!--end of edit form-->
                    </section>
                     <!--start of table of employees-->
                        
                                 <div class="row">
                                 <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Registered Employees</h4>
                                </div>
                            <div class="card-body">
                                 <div class="table-responsive">
                                    <table class="table table-sm" id="employees_table">
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       $query1=$conn->prepare("SELECT * FROM  tbl_staff ");
                                       $query1->execute();
                                       if($rows=$query1->rowCount()>0){
                                           $a=1;
                                           while($row=$query1->fetch()){
                                           
                                           ?>
                                           <tr>
                                        <td><?php echo $a++ ?></td>
                                        <td><?php echo $row['staff_family_name'];?></td>
                                         <td><?php echo $row['staff_first_name'];?></td>
                                         <td><?php echo $row['email'];?></td>
                                         <td><?php echo $row['PhoneNumber'];?></td>
                                        <td>
                                        <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary view-employee"><span class="spinner80"></span>View</button>
                                        <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary edit-employee"><span class="spinner81"></span>edit</button>
                                        <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary delete-employee"><span class="spinner82"></span>delete</button>
                                       </td>
                                        </tr>
                                        <?php }
                                       }
                                          else{
                                              
                                          }
                                          ?>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                              </div>
                             </div>
                           </div>
                            <!--end of table of employees     -->
                            <!--view modal-->
                        <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Employee Details</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>                 
                                           </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12 col-lg-12 col-sm-12 col-md-12">
                                                             <img alt="image" src="" class="profile-widget-picture" style="width:50px;height:50px;">
                                                        </div>
                                                    </div>
                                                <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                  <div id="accordion">
                                                        <div class="accordion">
                                                            <a href="#" class="font-weight-600 dropdown-toggle active" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true" style="width:280px; color:black;">Personal Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                                            <p>Family Name : <span id="fisrt_name"> </span></p>
                                                            <p>First Name : <span id="last_name"></span></p>
                                                            <p>IDN / Passport : <span id="idn_view"></span></p>
                                                            <p>Gender : <span id="gender_view"></span></p>
                                                            <p>Martial Status : <span id="martial_view"> </span></p>
                                                            
                                                            <p>Date of Birth : <span id="dob_view"></span></p>
                                                            <p>Nationality : <span id="nationality_view"> </span></p>
                                                            <p>Fathers Name : <span id="father_view"></span></p>
                                                            <p>Mothers Name : <span id="mother_view"></span></p>
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="true" style="width:280px;color:black;">Address Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                                                                <p>Country of residence : <span id="residence_view"> </span></p>
                                                                <p>Province : <span id="province_view"></span></p>
                                                                <p>District : <span id="district_view"></span></p>
                                                                <p>Sector : <span id="sector_view"></span></p>
                                                                <p>Cell : <span id="cell_view"> </span></p>
                                                                <p>Village : <span id="village_view"></span></p>
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="true" style="width:280px;color:black;">Contact Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                                                                <p>Phone Number : <span id="phone_view"> </span></p>
                                                                <p>Email : <span id="email_view"></span></p>
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-4" aria-expanded="true" style="width:280px;color:black;">Post Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-4" data-parent="#accordion">
                                                                <p>Campus : <span id="campus_view"></span></p>
                                                                <p>Department : <span id="department_view"> </span></p>
                                                                <p>Academic : <span id="accademic_view"></span></p>
                                                                <p>Probition period : <span id="probition_view"></span></p>
                                                                 <p>Start Job : <span id="start_job_view"></span></p>
                                                                <p>Fulltime / Part time : <span id="part_full_view"></span></p>
                                                                 <p>Post : <span id="post_view"></span></p>
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-5" aria-expanded="true" style="width:280px;color:black;">Account Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-5" data-parent="#accordion">
                                                                <p>Bank Name : <span id="bank_view"> </span></p>
                                                                <p>Account Number : <span id="acc_view"></span></p>
                                                                <p>Basic Salary : <span id="salary_view"></span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                  </div>
                               </div>
                          </div>
                       </div>
                    </div>
                  </div>
              </div>
                            <!--end of view modal-->
                </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://unpkg.com/dropzone"></script>
<script src="https://unpkg.com/cropperjs"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    $('#employees_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
      } 
        );
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
            if($("#country").val()==160){
            $("#province_id").attr('required', true);
            $("#district_id").attr('required', true);
            $("#sector").attr('required', true);
            $("#cell_id").attr('required', true);
            $("#village_id").attr('required', true);
            $("#prov").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
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
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
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
            }
            else{
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#province_id").attr('required', false);
                $("#district_id").attr('required', false);
                $("#sector").attr('required', false);
                $("#cell_id").attr('required', false);
                $("#village_id").attr('required', false);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
            }
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
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    did: $('#district_id').val(),
                    action:'load_sectors'
                    };
            $("#sector").empty();
            $('#spinner_dis').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_dis').fadeOut('fast');
                    $("#sector").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector+"</option>");
                    });
                    $("#sect").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_dis').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
//load cells
        $('#sector').change(function () {
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    sid: $('#sector').val(),
                    action:'load_cells'
                    };
            $("#cell_id").empty();
            $('#spinner_sect').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_sect').fadeOut('fast');
                    $("#cell_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell+"</option>");
                    });
                    $("#cell").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_sect').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
//load villages
        $('#cell_id').change(function () {
            $("#village").attr('hidden', true);
            var getData= {
                    cid: $('#cell_id').val(),
                    action:'load_villages'
                    };
            $("#village_id").empty();
            $('#spinner_cell').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_cell').fadeOut('fast');
                    $.each(data, function (index, value) {
                        $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName+"</option>");
                    });
                    $("#village").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_cell').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
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
        });
        
//register new employee
    $("#register_employee").submit(function(e){
            e.preventDefault();
                var formData = new FormData(this);
                $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator').html("Saving...");
                    $.ajax({
                        url: "/files/employees/employees_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function(data){
                          $('#spinner').fadeOut('fast');
                          if(data.status==200){
                              pop_up_success(data.message);
                              location.reload();
                           }
                          if(data.status==401){
                              pop_wrong(data.message)
                          }
                          if(data.status==500){
                              pop_wrong(data.message)
                          }
                            
                        },error: function(){
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
                     });
        });
    });
    //  $("#prg_type").submit(function (e) { 
    //      e.preventDefault();
    //     function goToSection5() {
            

    //     }
    // });

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
                if (prov === '' || dis === '' || sec === '' || cel==='' || vil === '') {
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
            var dep = document.getElementById('dep_id').value.trim();
            var post = document.getElementById('post_id').value.trim();
            if (dep === '' || post === '') {
                pop_wrong("All fields are required"); 
                dep===''?$("#dep_star").html("*"):$("#dep_star").html("");
                post===''?$("#pos_star").html("*"):$("#pos_star").html("");
                
                return false;
            }
            return true;
        }
    </script>


<script>
   $(document).ready(function() {
       $('#camps_id').change(function(e) {
        e.preventDefault();
        $('#dep_form').css({
            display: 'block'
        });
    });
    $('#dep_id').change(function(e) {
        e.preventDefault();
        $('#academic_form').css({
            display: 'block'
        });
    });
    $('#accademic').change(function(e) {
        e.preventDefault();
        $('#probition_form').css({
            display: 'block'
        });
    });
    $('#probiton').change(function(e) {
        e.preventDefault();
        $('#part_full_form').css({
            display: 'block'
        });
    });
    $('#part_full').change(function(e) {
        e.preventDefault();
        $('#start_date_form').css({
            display: 'block'
        });
    });
    $('#start_date').change(function(e) {
        e.preventDefault();
        $('#pst_id').css({
            display: 'block'
        });
    });
    $('#bank_name').on('input', function() {
    if ($(this).val().trim() !== '') {
      $('#Acc_id').css('display', 'block');
    } else {
      $('#Acc_id').css('display', 'none');
    }
  });
    $('#acc_number').on('input',function(){
     if($(this).val().trim() !=''){
      $('#sal_id').css('display', 'block');   
     } 
     else{
         $('#sal_id').css('display', 'none');
     }
    });
    // buttons handler
   $('.view-employee').on('click', function(e) {
    var spinner = $(this).find('.spinner80'); // Find the spinner within the clicked button
    spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    e.preventDefault();
    var value = $(this).data('emp-id'); // Use .data('emp-id') to access the emp_id value
    var formData = {
        emp_id: value,
        action: "view_employee"
    };
    $.ajax({
      url: "/files/employees/employees_controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data){
      spinner.fadeOut('fast');
      $('.spinner15').fadeOut('fast');
     $('#fisrt_name').html(data.staff_family_name);
     $('#last_name').html(data.staff_first_name);
     $('#idn_view').html(data.nid);
     var gender = data.staff_sex;
       if (gender === "M") {
          $('#gender_view').html("Male");
       } else if (gender === "F") {
          $('#gender_view').html("Female");
       } else {
          $('#gender_view').html("Unknown");
       }
       var martialStatus = data.MartialStatus;
       if (martialStatus === "S") {
          $('#martial_view').html("Single");
       } else if (gender === "M") {
          $('#martial_view').html("Married");
       } else {
          $('#martial_view').html("Unknown");
       }
     $('#dob_view').html(data.staff_dob);
     $('#nationality_view').html(data.nationality);
     $('#father_view').html(data.father_name);
     $('#mother_view').html(data.mother_name);
     $('#residence_view').html(data.cntr_name);
     $('#province_view').html(data.provincename);
     $('#district_view').html(data.namedistrict);
     $('#sector_view').html(data.namesector);
     $('#cell_view').html(data.nameCell);
     $('#village_view').html(data.VillageName);
     $('#phone_view').html(data.PhoneNumber);
     $('#email_view').html(data.email);
     $('#campus_view').html(data.camp_full_name);
     $('#department_view').html(data.dept_full_name);
     var academic = data.is_acadmic;
        if (academic ==1) {
           $('#accademic_view').html("Is Academic");
        } else if (academic ==0) {
           $('#accademic_view').html("Not Academic");
        }
      var probition = data.probation_period;
        if (probition ==3) {
           $('#probition_view').html("3 Months");
        } else if (probition ==6) {
           $('#probition_view').html("6 Months");
        }  
        $('#start_job_view').html(data.staff_doj);
        var part_full_time = data.fulltime;
        if (part_full_time ==1) {
           $('#part_full_view').html("Full Time");
        } else if (part_full_time ==0) {
           $('#part_full_view').html("Part Time");
        }  
     $('#post_view').html(data.post_name);
     $('#bank_view').html(data.Bank);
     $('#acc_view').html(data.AccountNumber);
     $('#salary_view').html(data.basic_salary);
     var imge = data.staff_image; // Assuming the value of `data.staff_image` is the name of the image file
    var imagePath = "https://mis.itecdemo.site/files/employees/images/"; // Path to the folder containing the images
    var imageUrl = imagePath + imge;
    
    var imgElement = document.querySelector('.profile-widget-picture');
    imgElement.src = imageUrl;
     
     $('#viewModal').modal('show');
       },error: function(){
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
    });
});

$('.edit-employee').on('click', function(e) {
  e.preventDefault();
    var spinner = $(this).find('.spinner81'); // Find the spinner within the clicked button
    spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    e.preventDefault();
    var value = $(this).data('emp-id'); // Use .data('emp-id') to access the emp_id value
   var formData={
     emp_id:value,
     action:"edit_employee"
    };
    $.ajax({
      url: "/files/employees/employees_controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data){
        spinner.fadeOut('fast');
    $('#lname_edit').val(data[0].staff_family_name);
    $('#fname_edit').val(data[0].staff_first_name);
    $('#idn_edit').val(data[0].nid);
    var gender = data[0].staff_sex;
    var selectElement = $("#gender_edit");
    selectElement.val(gender);
    selectElement.prepend(selectElement.find("option[value='" + gender + "']"));
    var martial = data[0].MartialStatus;
    var selectMartial = $("#martial_edit");
    selectMartial.val(martial);
    selectMartial.prepend(selectMartial.find("option[value='" + martial + "']"));
    $('#dob_edit').val(data[0].staff_dob);
    $("#nationality_edit").empty(); 
     $.each(data[1], function(index, value) {
      var option = new Option(value.nationality, value.nat_id);
      $("#nationality_edit").append(option);
      });
     $("#nationality_edit").val(data[0].Nationality)
      $("#nationality_edit option[value='" + data[0].Nationality + "']").prop("selected", true);
      var selectedOption1 = $("#nationality_edit option[value='" + data[0].Nationality + "']");
      $("#nationality_edit").prepend(selectedOption1); 
      $('#father_edit').val(data[0].father_name);
      $('#mother_edit').val(data[0].mother_name);
      $("#residence_edit").empty(); 
    $.each(data[2], function(index, value) {
      var option = new Option(value.cntr_name, value.cntr_id);
      $("#residence_edit").append(option);
      });
     $("#residence_edit").val(data[0].Country)
      $("#residence_edit option[value='" + data[0].Country + "']").prop("selected", true);
      var selectedOption1 = $("#residence_edit option[value='" + data[0].Country + "']");
      $("#residence_edit").prepend(selectedOption1); 
      
      $("#province_edit").empty(); 
    $.each(data[3], function(index, value) {
      var option = new Option(value.provincename, value.provincecode);
      $("#province_edit").append(option);
      });
     $("#province_edit").val(data[0].Province)
      $("#province_edit option[value='" + data[0].Province + "']").prop("selected", true);
      var selectedOption1 = $("#province_edit option[value='" + data[0].Province + "']");
      $("#province_edit").prepend(selectedOption1); 
      
      $("#district_edit").empty(); 
    $.each(data[4], function(index, value) {
      var option = new Option(value.namedistrict, value.districtcode);
      $("#district_edit").append(option);
      });
     $("#district_edit").val(data[0].District)
      $("#district_edit option[value='" + data[0].District + "']").prop("selected", true);
      var selectedOption1 = $("#district_edit option[value='" + data[0].District + "']");
      $("#district_edit").prepend(selectedOption1); 
      
      $("#sector_edit").empty(); 
    $.each(data[5], function(index, value) {
      var option = new Option(value.namesector, value.sectorcode);
      $("#sector_edit").append(option);
      });
     $("#sector_edit").val(data[0].Sector)
      $("#sector_edit option[value='" + data[0].Sector + "']").prop("selected", true);
      var selectedOption1 = $("#sector_edit option[value='" + data[0].Sector + "']");
      $("#sector_edit").prepend(selectedOption1); 
      
      $("#cell_edit").empty(); 
    $.each(data[6], function(index, value) {
      var option = new Option(value.nameCell, value.codecell);
      $("#cell_edit").append(option);
      });
     $("#cell_edit").val(data[0].Cell)
      $("#cell_edit option[value='" + data[0].Cell + "']").prop("selected", true);
      var selectedOption1 = $("#cell_edit option[value='" + data[0].Cell + "']");
      $("#cell_edit").prepend(selectedOption1); 
      
       $("#village_edit").empty(); 
    $.each(data[7], function(index, value) {
      var option = new Option(value.VillageName, value.CodeVillage);
      $("#village_edit").append(option);
      });
     $("#village_edit").val(data[0].Village)
      $("#village_edit option[value='" + data[0].Village + "']").prop("selected", true);
      var selectedOption1 = $("#village_edit option[value='" + data[0].Village + "']");
      $("#village_edit").prepend(selectedOption1); 
      $("#phone_edit").val(data[0].PhoneNumber);
      $("#email_edit").val(data[0].email);
      
      $("#campus_edit").empty(); 
    $.each(data[10], function(index, value) {
      var option = new Option(value.camp_full_name, value.camp_id);
      $("#campus_edit").append(option);
      });
     $("#campus_edit").val(data[0].campus)
      $("#campus_edit option[value='" + data[0].campus + "']").prop("selected", true);
      var selectedOption1 = $("#campus_edit option[value='" + data[0].campus + "']");
      $("#campus_edit").prepend(selectedOption1);
      
      $("#department_edit").empty(); 
    $.each(data[8], function(index, value) {
      var option = new Option(value.dept_full_name, value.dept_id);
      $("#department_edit").append(option);
      });
     $("#department_edit").val(data[0].Department)
      $("#department_edit option[value='" + data[0].Department + "']").prop("selected", true);
      var selectedOption1 = $("#department_edit option[value='" + data[0].Department + "']");
      $("#department_edit").prepend(selectedOption1);
      
    var accademic = data[0].is_acadmic;
    var selectAccademic = $("#accademic_edit");
    selectAccademic.val(accademic);
    selectAccademic.prepend(selectAccademic.find("option[value='" + accademic + "']"));
    
     var probition = data[0].probation_period;
    var selectProbition = $("#probition_edit");
    selectProbition.val(probition);
    selectProbition.prepend(selectProbition.find("option[value='" + probition + "']")); 
     
      $('#start_date_edit').val(data[0].staff_doj);
      
       var partfull = data[0].fulltime;
    var selectPartFull = $("#part_full_edit");
    selectPartFull.val(partfull);
    selectPartFull.prepend(selectPartFull.find("option[value='" + partfull + "']")); 
      $("#post_edit").empty(); 
    $.each(data[9], function(index, value) {
      var option = new Option(value.post_name, value.post_id);
      $("#post_edit").append(option);
      });
     $("#post_edit").val(data[0].Post)
      $("#post_edit option[value='" + data[0].Post + "']").prop("selected", true);
      var selectedOption1 = $("#post_edit option[value='" + data[0].Post + "']");
      $("#post_edit").prepend(selectedOption1);
      $("#bank_edit").val(data[0].Bank);
      $("#account_edit").val(data[0].AccountNumber);
      $("#salary_edit").val(data[0].basic_salary);
      $("#staff_id").val(data[0].id);
   $('#edit_card').css({
         display:'block'
     });
       },error: function(){
                            spinner.fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
    });
});


// editform load province

$('#residence_edit').change(function () {
           var getData= {
              c_id:$('#residence_edit').val(),
              action:'load_provinces'
                    };
            $('#spinner21').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/employees/employees_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner21').fadeOut('fast');
                    $("#province_edit").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#province_edit").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                    });
				},
				error:function(error){
				    $('#spinner21').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            });
            
      $('#province_edit').change(function () {
        var getData = {
            p_id: $('#province_edit').val(),
            action: 'load_districts'
        };
        $('#spinner22').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/employees/employees_controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner22').fadeOut('fast');
                var districtSelect = $('#district_edit');
                districtSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    districtSelect.append("<option value='" + value.districtcode + "'>" + value.namedistrict + "</option>");
                });
            },
            error: function (error) {
                $('#spinner22').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    $('#district_edit').change(function () {
        var getData = {
            d_id: $('#district_edit').val(),
            action: 'load_sectors'
        };
        $('#spinner23').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/employees/employees_controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner23').fadeOut('fast');
                var sectorSelect = $('#sector_edit');
                sectorSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    sectorSelect.append("<option value='" + value.sectorcode + "'>" + value.namesector + "</option>");
                });
            },
            error: function (error) {
                $('#spinner23').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $('#sector_edit').change(function () {
        var getData = {
            s_id: $('#sector_edit').val(),
            action: 'load_cells'
        };
        $('#spinner24').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/employees/employees_controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner24').fadeOut('fast');
                var cellSelect = $('#cell_edit');
                cellSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    cellSelect.append("<option value='" + value.codecell + "'>" + value.nameCell + "</option>");
                });
            },
            error: function (error) {
                $('#spinner24').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $('#cell_edit').change(function () {
        var getData = {
            cell_id_code: $('#cell_edit').val(),
            action: 'load_villages'
        };
        $('#spinner25').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/employees/employees_controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner25').fadeOut('fast');
                var villageSelect = $('#village_edit');
                villageSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    villageSelect.append("<option value='" + value.CodeVillage + "'>" + value.VillageName + "</option>");
                });
            },
            error: function (error) {
                $('#spinner25').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
  $('.delete-employee').on('click', function(e) {
  e.preventDefault();
  var spinner = $(this).find('.spinner82'); // Find the spinner within the clicked button
    spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    e.preventDefault();
    var value = $(this).data('emp-id'); // Use .data('emp-id') to access the emp_id value
    var formData={
     staff_id:value,
     action:"delete_employee"
    };
    $.ajax({
      url: "/files/employees/employees_controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data){
     if(data.status==200){
         spinner.fadeOut('fast');
         pop_wrong(data.message);
         $('#employees_table').load(location.href + " #employees_table");
     }
     if(data.status==500){
         pop_wrong(data.message);
         spinner.fadeOut('fast');
         
     }
      },error: function () {
                        spinner.fadeOut('fast');
                        $('#spinner20').fadeOut('fast');
                        pop_wrong("Something went wrong!");

                    }
    });
  });
    
$("#edit_employee_form").submit(function(e){
     e.preventDefault();
   $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "/files/employees/employees_controller.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                          $('#spinner20').fadeOut('fast');
                     if(data.status==200){
                         pop_up_success(data.message);
                         $('#employees_table').load(location.href + " #employees_table");
                          $('#edit_card').css({
                                 display:'none'
                             });
                     }
                     if(data.status==500){
                         pop_wrong(data.message);
                     }

                    }, error: function () {
                        $('#spinner20').fadeOut('fast');
                        pop_wrong("Something went wrong!");

                    }
});
});

// classes oof add primary and light on edit form

$('.second').on('click', function(e) {
  e.preventDefault();
   $(".second").removeClass('btn-light');
 $(".second").addClass('btn-primary');
 $(".firstly").removeClass('btn-primary');
 $(".firstly").addClass('btn-light');
});
$('.third').on('click', function(e) {
  e.preventDefault();
   $(".third").removeClass('btn-light');
 $(".third").addClass('btn-primary');
   $(".second").removeClass('btn-primary');
 $(".second").addClass('btn-light');
 $(".firstly").removeClass('btn-primary');
 $(".firstly").addClass('btn-light');
});
$('.fourth').on('click', function(e) {
  e.preventDefault();
   $(".fourth").removeClass('btn-light');
 $(".fourth").addClass('btn-primary');
   $(".third").removeClass('btn-primary');
 $(".third").addClass('btn-light');
});
$('.fifth').on('click', function(e) {
  e.preventDefault();
   $(".fifth").removeClass('btn-light');
 $(".fifth").addClass('btn-primary');
   $(".fourth").removeClass('btn-primary');
 $(".fourth").addClass('btn-light');
});
 
 	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;
       var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 1,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
	    $('#spinner35').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});
        const fileImage = document.getElementById('upload_image');
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"upload_image");
				$.ajax({
					url:'/files/employees/employees_controller.php',
					method:'POST',
					dataType:"JSON",
					data:formData,
					processData: false,
                    contentType: false,
					success:function(data)
					{
					    $('#spinner35').fadeOut('fast');
					    $modal.modal('hide');
					}
				});
			};
		});
	});


});
</script>
<script>
    $(document).ready(function(){
    var $modal = $('#modal_edit');
  var image = document.getElementById('sample_image_edit');

	var cropper;

	$('#upload_image_edit').change(function(event){
		var files = event.target.files;
       var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 1,
			preview:'.preview_edit'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop_edit').click(function(){
	    $('#spinner84').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});
        const fileImage = document.getElementById('upload_image_edit');
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file
                         var staff_id=$('#staff_id').val();
                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"upload_image_edit");
				$.ajax({
					url:'/files/employees/employees_controller.php',
					method:'POST',
					dataType:"JSON",
					data:formData,
					processData: false,
                    contentType: false,
					success:function(data)
					{
					    $('#spinner84').fadeOut('fast');
					    $modal.modal('hide');
					}
				});
			};
		});
	});
 
    })
</script>