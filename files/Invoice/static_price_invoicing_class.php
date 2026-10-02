                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Generate Invoice</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body pb-0 row">
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                    <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="s_camp_id" required>
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
                                                            &nbsp;<span id="spinner0s"></span>
                                                </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id" id="s_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-3">
                                                     <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="s_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2s"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-3">
                                                    <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="s_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3s"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-3">
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="s_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4s"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-3">
                                                     <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="s_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5s"></span>
                                                </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                                
                                    <div class="col-12 col-sm-12 col-lg-12" id="s_list" hidden>
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="generate_static_invoice" action="generate_static_invoice" method="POST">
                                                    <input type="hidden" name="action" value="gen_class_static">
                                                    <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                                    <input type="hidden" name="intake" id="s_intake">
                                                    <div class="row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Fee Category</label><br>
                                                            <select class="form-control select2" style="width:100%" name="fee_id" required>
                                                                <?php
                                                                    $sql_fee=$conn->prepare("SELECT id,name FROM fee_category WHERE status=1 AND known_price=1 ORDER BY name ASC");
                                                                    $sql_fee->execute();
                                                                    while($fee=$sql_fee->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <table class="table table-hover table-sm" id="s_student_list" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Names</th>
                                                                <th scope="col">Registration Number</th>
                                                                <th scope="col">Selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="s_students">
                                                        </tbody>
                                                    </table> 
                                                    <div class="buttons" style="display:flex; flex-direction:row-reverse; margin-top:50px;">
                                                        <button type="submit" class="btn btn-icon btn-primary"><span id="s_spinner"></span>&nbsp;<i class="fas fa-file-invoice"></i>&nbsp;<span id="s_indicator">Save invoice</span>&nbsp;</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>