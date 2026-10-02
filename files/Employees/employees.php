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
                       <?php  if($role_id == 7 || $role_id == 26 || $role_id == 1){?>
                       <div class="section-header">
                          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#mycard-collapse" aria-expanded="false" aria-controls="collapseExample">
                                           <i class="fas fa-user"></i> &nbsp;  Register <?php echo $title;?> 
                                        </button>
                         </div>
                         
                        <?php } ?>
                       
                        <div class="collapse hide" id="mycard-collapse">
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                                    <form id="register_employee" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="register_new_employee">
                                        <div class="card">
                                            <div class="card-header row" style="display:flex; justify-content:center">
                                                    <button type="button" id="section-1-indicator" class="btn btn-primary col-12 col-md-2 col-lg-2" style="margin-bottom:10px;"><span class="badge badge-transparent">1</span> &nbsp;Personal info</button>
                                                    <button type="button" id="section-2-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">2</span> &nbsp;Address info</button>
                                                    <button type="button" id="section-3-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">3</span> &nbsp;Contact info</button>
                                                    <button type="button" id="section-4-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">4</span> &nbsp;Post info</button>
                                                   
                                                    <button type="button" id="section-5-indicator" class="btn btn-light col-12 col-md-2 col-lg-2"  style="margin-bottom:10px;"><span class="badge badge-transparent">5</span> &nbsp;Payment info</button>
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
                                                    <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. Mohamed" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Middle Name <code><b><span id=""></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="midleName" id="midleName" placeholder="e.g. Lamin">
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
                                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Conteh" required>
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
                                                    <label>Marital Status <code><b><span id="martial_star"></span></b></code></label>
                                                    <select name="mstatus" id="mstatus" class="form-control select2" style="width:100%" required>
                                                        <option value="" disabled selected hidden>Choose One...</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Widowed">Widowed</option>
                                                        <option value="Divorced">Divorced</option>
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
                                                 <div class="form-group col-12 col-sm-6 col-lg-4">
                                                    <label>Nassit Number<code><b><span id="rssb_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <input type="text" name="nassit_number" id="nassit_number" class="form-control" placeholder="Nassit Number">
                                                    </div>
                                                </div>
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
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="cellF" hidden>
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
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="e.g. annet@example.com">
                                                    </div>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection4()"><span id="spinner3-1"></span>&nbsp; <span id="indicator3-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection2()"><span id="spinner3-2"></span>&nbsp; <span id="indicator3-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--contact end-->
                                            
                                            <!--post info start-->
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
                                                
                                                
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="type_form" style="display:none">
                                                    <label>Staff Category <code><b><span id="accademic_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="accademic" id="accademic" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_type=$conn->prepare("SELECT * FROM  tbl_staff_type WHERE status=1");
                                                            $sql_type->execute();
                                                            $i=1;
                                                            while($progs_type=$sql_type->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_type['staff_type_id']; ?>"><?php echo $progs_type['staff_type_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner31"></span>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="Schoolform" style="display:none">
                                                    <label>School <code><b><span id="Schoolsr"></span></b></code></label>
                                                    <select class="form-control select2" style="width:100%" name="School" id="School" disabled>
                                                        <option value="">-- Select Campus First --</option>
                                                    </select>
                                                    <span id="spinner30"></span>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="Departmentfld" style="display:none">
                                                    <label>Department <code><b><span id="Departmentsr"></span></b></code></label>
                                                    <select class="form-control select2" style="width:100%" name="Departmentin" id="Departmentin" disabled>
                                                        <option value="">-- Select School First --</option>
                                                    </select>
                                                    <span id="spinner30"></span>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="dvfiledType" style="display:none">
                                                    <label>Position <span><i> <i class="fa-solid fa-circle-info"></i> Multi-position selection enabled</i></span> <code><b><span id="dep_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="filedType[]" id="filedType" multiple>
                                                        
                                                    </select>
                                                    <span id="spinner10"></span>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="grade_id" style="display:none;">
                                                    <label>Academic Grade <code><b><span id="grade_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="ac_grade_id" id="ac_grade_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $stmt3=$conn->prepare("SELECT * FROM tbl_acad_grade WHERE status=1 order by acad_grad_full_name asc");
                                                            $stmt3->execute();
                                                            $i=1;
                                                            while($grade=$stmt3->fetch()){
                                                                ?>
                                                        <option value="<?php echo $grade['acad_grad_id']; ?>"><?php echo $grade['acad_grad_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner11"></span>
                                                </div>
                                                
                                                
                                                
                                                
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" id="pst_id" style="display:none;">-->
                                                <!--    <label>Employee Post <code><b><span id="pos_star"></span></b></code></label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="post_id" id="post_id" required>-->
                                                <!--        <option value=""></option>-->
                                                        <?php
                                                            // $stmt1=$conn->prepare("SELECT * FROM staff_post WHERE status=1 order by staff_post_full_name asc");
                                                            // $stmt1->execute();
                                                            // $i=1;
                                                            // while($post=$stmt1->fetch()){
                                                                ?>
                                                        <!--<option value="<?php echo $post['staff_post_id']; ?>"><?php echo $post['staff_post_full_name']; ?> </option>-->
                                                        <?php 
                                                        // } 
                                                        ?>
                                                <!--    </select>-->
                                                <!--    <span id="spinner11"></span>-->
                                                <!--</div>-->
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="part_full_form" style="display:none">
                                                    <label>Contract Type<code><b><span id="cntr_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="part_full" id="part_full" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_contract_type WHERE status=1");
                                                            $sql_cntr->execute();
                                                            $i=1;
                                                            while($progs_cntr=$sql_cntr->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_cntr['contr_type_id']; ?>"><?php echo $progs_cntr['contr_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner33"></span>
                                                </div>
                                                
                                                
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="start_date_form" style="display:none">
                                                    <div class="form-group">
                                                        <label>Start date</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="frole_id" style="display:none">
                                                    <label>Role type<code><b><span id="cntr_star"></span></b></code></label>
                                                    <select  class="form-control select2" style="width:100%" name="role_id" id="role_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_user_roles order by role asc");
                                                            $sql_cntr->execute();
                                                            $i=1;
                                                            while($progs_cntr=$sql_cntr->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_cntr['role_id']; ?>"><?php echo $progs_cntr['role']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner33"></span>
                                                </div>
                                                
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" id="probition_form" style="display:none">-->
                                                <!--    <label>Probation Period <code><b><span id="probition_star"></span></b></code></label>-->
                                                <!--    <select name="probiton" id="probiton" class="form-control select2" style="width:100%" required>-->
                                                <!--        <option value="" disabled selected hidden>Choose One...</option>-->
                                                <!--        <option value="1">1 Year</option>-->
                                                <!--        <option value="2">2 Years</option>-->
                                                <!--        <option value="3">3 Years</option>-->
                                                <!--        <option value="4">4 Years</option>-->
                                                <!--        <option value="5">5 Years</option>-->
                                                <!--    </select>-->
                                                <!--    <span id="spinner32"></span>-->
                                                <!--    <input type="range" id="volume" class="form-control" name="volume" min="0" max="100" value="50">-->
                                                <!--</div>-->
                                                
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" id="probition_form" style="display:none">-->
                                                <!--    <label for="probation_range">-->
                                                <!--        Probation Period (Years): -->
                                                <!--        <code><b><span id="probition_star"></span></b></code>-->
                                                <!--    </label>-->
                                                    
                                                <!--    <input type="range" class="form-control-range" id="probation_range" name="probation_years" min="1" max="5" value="1" step="1" required>-->
                                                    
                                                <!--    <small>Selected: <span id="probation_display">1</span> Year(s)</small>-->
                                                    
                                                <!--    <span id="spinner32"></span>-->
                                                <!--</div>-->
                                                
     

                                                <div class="mb-3" id="probation_form" style="display:none">
                                                    <label for="probation_range">
                                                        Probation Period (Years): 
                                                        <code><b><span id="probation_star"></span></b></code>
                                                    </label>
                                                    <select class="form-select form-select-sm select2" style="width: 100%" id="rc2probation_range" name="probation_years">
                                                           <option value="" selected disabled>Choose one</option>
                                                          <option value="1"> 1 Year</option>
                                                          <option value="2"> 2 Years</option>
                                                          <option value="3"> 3 Years</option>
                                                          <option value="4"> 4 Years</option>
                                                          <option value="5"> 5 Years</option>
                                                        </select>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="QualificationsF" style="display:none">
                                                    <label>Qualifications<code><b><span id="rssb_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <input type="text" class="form-control" name="Qualifications" id="Qualifications" placeholder="Qualifications here...">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-6 col-lg-4" id="DepartmentUntF" style="display:none">
                                                    <label>Department / Unit <code><b><span id="rssb_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <input type="text" class="form-control" name="DepartmentUnt" id="DepartmentUnt" placeholder="Department / Unit here...">
                                                    </div>
                                                </div>
                                                
                                                
                                             

                                                <!--<div class="mb-3" id="" style="display:none">-->
                                                <!--    <label for="probation_range">-->
                                                         
                                                <!--        <code><b><span id="probation_star"></span></b></code>-->
                                                <!--    </label>-->
                                                    
                                                <!--</div>-->
                                                
                                                <!--<div class="mb-3" id="" style="display:none">-->
                                                <!--    <label for="probation_range">-->
                                                        
                                                <!--        <code><b><span id="probation_star"></span></b></code>-->
                                                <!--    </label>-->
                                                    
                                                <!--</div>-->
                                                

                                                
                                                
                                                
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" id="fSupervisor" style="display:none">-->
                                                <!--    <label>Supervisor position<code><b><span id="cntr_star"></span></b></code></label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="fSupervisor_id" id="fSupervisor_id" required>-->
                                                <!--        <option value=""></option>-->
                                                        <?php
                                                            // $sql_cntr=$conn->prepare("SELECT staff_post.* FROM staff_post WHERE 
                                                            // staff_post.staff_post_id IN (SELECT tbl_staff_info.Post FROM tbl_staff_info)
                                                            // ORDER BY staff_post.staff_post_full_name ASC");
                                                            // $sql_cntr->execute();
                                                            // $i=1;
                                                            // while($progs_cntr=$sql_cntr->fetch()){
                                                                ?>
                                                        <!--<option value="-->
                                                        <?php 
                                                        // echo $progs_cntr['staff_post_id']; 
                                                        ?>
                                                        
                                                            <?php 
                                                            // echo $progs_cntr['staff_post_full_name']; 
                                                            ?> 
                                                            <!--</option>-->
                                                        <?php 
                                                        // }
                                                        ?>
                                                <!--    </select>-->
                                                <!--    <span id="spinner33"></span>-->
                                                <!--</div>-->
                                                
                                                <!--<div class="form-group col-12 col-sm-4 col-lg-4" id="PSupervisor" style="display:none">-->
                                                <!--    <label>Supervisor<code><b><span id="cntr_star"></span></b></code></label>-->
                                                <!--    <select  class="form-control select2" style="width:100%" name="PSupervisor_id" id="PSupervisor_id" required>-->
                                                <!--        <option value=""></option>-->
                                                        
                                                <!--    </select>-->
                                                <!--    <span id="spinner33"></span>-->
                                                <!--</div>-->
                                                

                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm" style="margin-right:10px;" onclick="goToSection5()"><span id="spinner4-1"></span>&nbsp; <span id="indicator4-1">Next</span></button>
                                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection3()"><span id="spinner4-2"></span>&nbsp; <span id="indicator4-2">Previous</span></button>
                                                </div>
                                            </div>
                                            <!--post info start-->
                                            
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
                                                    <label>Gross Salary <code><b><span id="salary_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-money" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                    <input type="number" class="form-control" name="salary" id="salary" placeholder="Basic Salary">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="ScaleF" style="display:none;">
                                                    <label>Scale of Salary <code><b><span id="salary_star"></span></b></code></label>
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                        <i class="fas fa-money" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                    <input type="number" class="form-control" name="ScaleofSalary" id="ScaleofSalary" placeholder="Scale of Salary">
                                                    </div>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                                   <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;" onclick="goBackToSection4()"><span id="spinner5"></span>&nbsp; <span id="indicator5">Previous..</span></button>
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
                   <?php
                //   include 'edit_form.php';
                  include 'Emploviewinfo.php';
                   ?>
                    <!--end of edit form-->
                    </section>
                     <!--start of table of employees-->
                        <?php include 'table_employee.php' ?>
                                 
                            <!--end of table of employees     -->
                            <!--view modal-->
                        <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                                        <div class="modal-dialog modal-lg" role="document">
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
                                                             <img alt="image" src="" class="rounded-circle profile-widget-picture m-auto" style="width:100px;height:100px;">
                                                        </div>
                                                    </div>
                                                <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                  <div id="accordion">
                                                        <div class="accordion">
                                                            <a href="#" class="btn btn-light font-weight-600 dropdown-toggle active" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true" style="width:100%; color:black;">Personal Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Family Name</th>
                                                                            <th><span id="last_name"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>First Name</th>
                                                                            <th><span id="fisrt_name"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>ID / Passport</th>
                                                                            <th><span id="idn_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Gender</th>
                                                                            <th><span id="gender_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Marital Status</th>
                                                                            <th><span id="martial_view"> </span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Date of Birth</th>
                                                                            <th><span id="dob_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Nationality</th>
                                                                            <th><span id="nationality_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Father's Name</th>
                                                                            <th><span id="father_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Mother's Name</th>
                                                                            <th><span id="mother_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Qualifications</th>
                                                                            <th><span id="QualificationsView"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Department / Unit</th>
                                                                            <th><span id="DepartmentuView"></span></th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="accordion">
                                                            <a href="#" class="btn btn-light font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="true" style="width:100%; color:black;">Contact Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Phone Number</th>
                                                                            <th><span id="phone_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Email</th>
                                                                            <th><span id="email_view"></span></th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <div class="accordion">
                                                            <a href="#" class="btn btn-light font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-4" aria-expanded="true" style="width:100%; color:black;">Post Information</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-4" data-parent="#accordion">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Campus</th>
                                                                            <th><span id="campus_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>School</th>
                                                                            <th><span id="School_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Department</th>
                                                                            <th><span id="Department_vieww"></span></th>
                                                                        </tr>
                                                                        
                                                                        <tr>
                                                                            <th>Employee Type</th>
                                                                            <th><span id="accademic_view"></span></th>
                                                                        </tr>
                                                                        <!--<tr>-->
                                                                        <!--    <th>Academic Grade</th>-->
                                                                        <!--    <th><span id="grade_view"></span></th>-->
                                                                        <!--</tr>-->
                                                                        <!--<tr>-->
                                                                        <!--    <th>Probation period</th>-->
                                                                        <!--    <th><span id="probition_view"></span></th>-->
                                                                        <!--</tr>-->
                                                                        <!--<tr>-->
                                                                        <!--    <th>Job Start</th>-->
                                                                        <!--    <th><span id="start_job_view"></span></th>-->
                                                                        <!--</tr>-->
                                                                        <tr>
                                                                            <th>Contract Type</th>
                                                                            <th><span id="part_full_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Post</th>
                                                                            <th><span id="post_view"></span></th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="accordion">
                                                            <a href="#" class="btn btn-light font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="true" style="width:100%; color:black;">Address Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Country of residence</th>
                                                                            <th><span id="residence_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Province</th>
                                                                            <th><span id="province_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>District</th>
                                                                            <th><span id="district_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Sector</th>
                                                                            <th><span id="sector_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Cell</th>
                                                                            <th><span id="cell_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Village</th>
                                                                            <th><span id="village_view"></span></th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="accordion">
                                                            <a href="#" class="btn btn-light font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-5" aria-expanded="true" style="width:100%; color:black;">Payment Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-5" data-parent="#accordion">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Bank</th>
                                                                            <th><span id="bank_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Account Number</th>
                                                                            <th><span id="acc_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Gross Salary</th>
                                                                            <th><span id="salary_view"></span></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Scale of Salary</th>
                                                                            <th><span id="ScaleofSalaryView"></span></th>
                                                                        </tr>
                                                                        
                                                                    </thead>
                                                                </table>
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
                
        <!--all scripts-->
<?php include 'scripts_handler.php' ?>