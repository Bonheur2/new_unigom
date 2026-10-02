 <div class="row" id="edit_card" style="display:none">
                        <div class="col-12 col-lg-12 col-sm-12">
                             <div class="card">
                                <div class="card-header">
                                    <h4>Edit Employee Details</h4>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                   <div class="col-12 col-md-12 col-lg-12">
                                    <div class="row m-auto">
                                           <button type="button" class="accordion-header col-12 col-lg-3 col-sm-12 btn btn-light firstly" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">Personal info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light second" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="true">Address info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light third" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="true">Contact info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-2 col-sm-12 btn btn-light fourth" data-toggle="collapse" data-target="#panel-body-4" aria-expanded="true">Post info</button>
                                           <button  type="button" class="accordion-header col-12 col-lg-3 col-sm-12 btn btn-light fifth" data-toggle="collapse" data-target="#panel-body-5" aria-expanded="true">Payment info</button>
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
                                              <option value="W">Widowed</option>
                                             <option value="D">Divorced</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Date of Birth</label>
                                             <input type="date" class="form-control" id="dob_edit" name="dob_edit">
                                        </div>
                                        <div class="form-group col-md-4">
                                                    <label>Nationality <code><b><span id="nat_star_edit"></span></b></code></label>
                                                    <select name="nationality_edit" id="nationality_edit"  class="form-control select2" style="width:100%">
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
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">rssb</label>
                                            <input type="text" class="form-control" id="rssb_edit" name="rssb_edit">
                                        </div>
                                    </div>
                                 </div>
                                </div>
                                  <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                                    <label>Country of residence <code><b><span id="res_star_edit"></span></b></code></label>
                                                    <select name="residence_edit" id="residence_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_cntry=$conn->prepare("SELECT * FROM tbl_country");
                                                            $sql_cntry->execute();
                                                            $i=1;
                                                            while($cntry=$sql_cntry->fetch()){
                                                        ?>
                                                         <option value="<?php echo $cntry['cntr_id']; ?>"><?php echo $cntry['cntr_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div><span id="spinner21"></span>
                                         <div class="form-group col-md-4">
                                                    <label>Province <code><b><span id="prov_star_edit"></span></b></code></label>
                                                    <select name="province_edit" id="province_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_prvnc=$conn->prepare("SELECT * FROM provinces");
                                                            $sql_prvnc->execute();
                                                            $i=1;
                                                            while($prv=$sql_prvnc->fetch()){
                                                        ?>
                                                         <option value="<?php echo $prv['provincecode']; ?>"><?php echo $prv['provincename']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div><span id="spinner22"></span>
                                        <div class="form-group col-md-4">
                                                    <label>District <code><b><span id="dist_star_edit"></span></b></code></label>
                                                    <select name="district_edit" id="district_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_distr=$conn->prepare("SELECT * FROM districts");
                                                            $sql_distr->execute();
                                                            $i=1;
                                                            while($distr=$sql_distr->fetch()){
                                                        ?>
                                                         <option value="<?php echo $distr['districtcode']; ?>"><?php echo $distr['namedistrict']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div><span id="spinner23"></span>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                                    <label>Sector <code><b><span id="sect_star_edit"></span></b></code></label>
                                                    <select name="sector_edit" id="sector_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_sector=$conn->prepare("SELECT * FROM sectors");
                                                            $sql_sector->execute();
                                                            $i=1;
                                                            while($sector=$sql_sector->fetch()){
                                                        ?>
                                                         <option value="<?php echo $sector['sectorcode']; ?>"><?php echo $sector['namesector']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div><span id="spinner24"></span>
                                         <div class="form-group col-md-4">
                                                    <label>Cell <code><b><span id="cell_star_edit"></span></b></code></label>
                                                    <select name="cell_edit" id="cell_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_cell=$conn->prepare("SELECT * FROM cells");
                                                            $sql_cell->execute();
                                                            $i=1;
                                                            while($cell=$sql_cell->fetch()){
                                                        ?>
                                                         <option value="<?php echo $cell['codecell']; ?>"><?php echo $cell['nameCell']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div><span id="spinner25"></span>
                                        <div class="form-group col-md-4">
                                                    <label>Village <code><b><span id="village_star_edit"></span></b></code></label>
                                                    <select name="village_edit" id="village_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_vllg=$conn->prepare("SELECT * FROM villages");
                                                            $sql_vllg->execute();
                                                            $i=1;
                                                            while($villg=$sql_vllg->fetch()){
                                                        ?>
                                                         <option value="<?php echo $villg['CodeVillage']; ?>"><?php echo $villg['VillageName']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
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
                                                    <label>Campus <code><b><span id="cmps_star_edit"></span></b></code></label>
                                                    <select name="campus_edit" id="campus_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_cmps=$conn->prepare("SELECT * FROM tbl_campus");
                                                            $sql_cmps->execute();
                                                            $i=1;
                                                            while($cmps=$sql_cmps->fetch()){
                                                        ?>
                                                         <option value="<?php echo $cmps['camp_id']; ?>"><?php echo $cmps['camp_full_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                        <div class="form-group col-md-4">
                                                    <label>Department <code><b><span id="spt_star_edit"></span></b></code></label>
                                                    <select name="department_edit" id="department_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_dpt=$conn->prepare("SELECT * FROM tbl_staff_dept WHERE status=1");
                                                            $sql_dpt->execute();
                                                            $i=1;
                                                            while($dep=$sql_dpt->fetch()){
                                                        ?>
                                                         <option value="<?php echo $dep['staff_dept_id']; ?>"><?php echo $dep['staff_dept_full_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                         <div class="form-group col-md-4">
                                                    <label>Employee Type <code><b><span id="stf_star_edit"></span></b></code></label>
                                                    <select name="accademic_edit" id="accademic_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_type=$conn->prepare("SELECT * FROM  tbl_staff_type WHERE status=1");
                                                            $sql_type->execute();
                                                            $i=1;
                                                            while($type=$sql_type->fetch()){
                                                        ?>
                                                         <option value="<?php echo $type['staff_type_id']; ?>"><?php echo $type['staff_type_full_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                          <div class="form-group col-md-4">
                                                    <label>Academic Grade <code><b><span id="stf_star_grade_edit"></span></b></code></label>
                                                    <select name="acc_grade_edit" id="acc_grade_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_grade=$conn->prepare("SELECT * FROM  tbl_acad_grade WHERE status=1");
                                                            $sql_grade->execute();
                                                            $i=1;
                                                            while($grades=$sql_grade->fetch()){
                                                        ?>
                                                         <option value="<?php echo $grades['acad_grad_id']; ?>"><?php echo $grades['acad_grad_full_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
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
                                                    <label>Contract Type<code><b><span id="cntr_star_edit"></span></b></code></label>
                                                    <select name="part_full_edit" id="part_full_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_cntr=$conn->prepare("SELECT * FROM tbl_contract_type WHERE status=1");
                                                            $sql_cntr->execute();
                                                            $i=1;
                                                            while($cntr=$sql_cntr->fetch()){
                                                        ?>
                                                         <option value="<?php echo $cntr['contr_type_id']; ?>"><?php echo $cntr['contr_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                        <div class="form-group col-md-4">
                                                    <label>Employee Post <code><b><span id="pst_star_edit"></span></b></code></label>
                                                    <select name="post_edit" id="post_edit"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_pst=$conn->prepare("SELECT * FROM staff_post WHERE status=1");
                                                            $sql_pst->execute();
                                                            $i=1;
                                                            while($post=$sql_pst->fetch()){
                                                        ?>
                                                         <option value="<?php echo $post['staff_post_id']; ?>"><?php echo $post['staff_post_full_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
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