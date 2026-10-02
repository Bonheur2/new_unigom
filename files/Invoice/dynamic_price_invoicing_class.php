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
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="camp_id" id="e_camp_id" required>
                                                            <option selected disabled>Select Campus</option>
                                                            <?php
                                                                    $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                            <option value="<?php echo $progs_faculty['camp_id']; ?>">
                                                                <?php echo $progs_faculty['camp_full_name']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                        &nbsp;<span id="spinner0"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>Program</label>
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="prg_type_id" id="e_prg_type_id" required>
                                                            <option selected Disabled>Select Program</option>
                                                        </select>
                                                        &nbsp;<span id="spinner1"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>School</label>
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="fac_id" id="e_fac_id" required>
                                                            <option selected disabled>Select School</option>

                                                        </select>
                                                        &nbsp;<span id="spinner2"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>Department</label>
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="dept_id" id="e_dept_id" required>
                                                            <option selected disabled>Select Department</option>
                                                        </select>
                                                        &nbsp;<span id="spinner3"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>Specialization</label>
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="splz_id" id="e_splz_id" required>
                                                            <option selected disabled>Select Specialization</option>
                                                        </select>
                                                        &nbsp;<span id="spinner4"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>Level</label>
                                                        <select class="form-control select2" style="width:100%;"
                                                            name="level_id" id="e_level_id" required>
                                                            <option selected disabled>Select Level</option>

                                                        </select>
                                                        &nbsp;<span id="spinner5"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-lg-12" id="list" hidden>
                                        <!-- Regular Fee Invoice -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Invoice - Regular Fee</h4>
                                            </div>
                                            <div class="card-body">
                                                <form id="generate_invoice" action="generate_invoice" method="POST">
                                                    <input type="hidden" name="action" value="gen_class_dynamic">
                                                    <input type="hidden" name="user"
                                                        value="<?php echo $identification; ?>">
                                                    <input type="hidden" name="prg_type_id" id="u_prg_type_id">
                                                    <input type="hidden" name="fac_id" id="u_fac_id">
                                                    <input type="hidden" name="dept_id" id="u_dept_id">
                                                    <input type="hidden" name="splz_id" id="u_splz_id">
                                                    <input type="hidden" name="level_id" id="u_level_id">
                                                    <div class="row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Fee Category</label><br>
                                                            <select class="form-control select2" style="width:100%"
                                                                name="fee_id" required>
                                                                <option selected disabled>Select Fee Category</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <table class="table table-hover table-sm" id="student_list"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Names
                                                                </th>
                                                                <th scope="col">Registration Number</th>
                                                                <th scope="col">Selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="students">
                                                        </tbody>
                                                    </table>
                                                    <div class="buttons"
                                                        style="display:flex; flex-direction:row-reverse; margin-top:50px;">
                                                        <button type="submit" class="btn btn-icon btn-primary"><span
                                                                id="spinner"></span>&nbsp;<i
                                                                class="fas fa-file-invoice"></i>&nbsp;<span
                                                                id="indicator">Save invoice</span>&nbsp;</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Special Fee Invoice -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Invoice - Special Fee</h4>
                                            </div>
                                            <div class="card-body">
                                                <form id="generate_class_special_invoice" method="POST">
                                                    <input type="hidden" name="action" value="gen_class_special">
                                                    <input type="hidden" name="user"
                                                        value="<?php echo $identification; ?>">
                                                    <div class="row">
                                                        <div class="form-group col-12 col-sm-3 col-lg-3">
                                                            <label>Special Fee Category <code>*</code></label>
                                                            <select class="form-control select2" style="width:100%"
                                                                name="special_fee_id" id="class_special_fee_select"
                                                                required>
                                                                <option value="" disabled selected>--Choose Special
                                                                    Fee--</option>
                                                                <?php
                                                                $sql_sp = $conn->prepare("SELECT sp_inv_id, name, has_known_price, amount FROM tbl_special_invoice WHERE status = 1 ORDER BY name ASC");
                                                                $sql_sp->execute();
                                                                while($sp = $sql_sp->fetch()){
                                                                ?>
                                                                <option value="<?php echo $sp['sp_inv_id']; ?>"
                                                                    data-has-price="<?php echo $sp['has_known_price']; ?>"
                                                                    data-amount="<?php echo $sp['amount']; ?>">
                                                                    <?php echo $sp['name']; ?>
                                                                    <?php if($sp['has_known_price'] == 1) echo ' (' . number_format($sp['amount'], 2) . ')'; ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-2 col-lg-2">
                                                            <label>Amount <code>*</code></label>
                                                            <input type="number" class="form-control" name="amount"
                                                                id="class_special_amount" placeholder="0.00" step="0.01"
                                                                min="0" required>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-3 col-lg-3">
                                                            <label>Comment</label>
                                                            <input type="text" class="form-control" name="comment"
                                                                placeholder="Reason / comment">
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <center>
                                                                <label style="visibility: hidden;">Button</label><br>
                                                                <button type="submit" class="btn btn-warning"><span
                                                                        id="spinner_class_sp"></span>&nbsp;<span
                                                                        id="indicator_class_sp">Save Special
                                                                        Invoice</span></button>
                                                            </center>
                                                        </div>
                                                    </div>
                                                    <table class="table table-hover table-sm" id="student_list_special"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Names
                                                                </th>
                                                                <th scope="col">Registration Number</th>
                                                                <th scope="col">Selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="students_special">
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>