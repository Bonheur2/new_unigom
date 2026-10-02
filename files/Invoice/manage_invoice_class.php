<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Filter by Class</h4>
                </div>
                <div class="card-body">
                    <div class="card-body pb-0 row">
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>Campus</label>
                            <select class="form-control select2" style="width:100%;" id="mc_camp_id">
                                <option selected disabled>Select Campus</option>
                                <?php
                                    $sql_camp=$conn->prepare("SELECT * FROM tbl_campus");
                                    $sql_camp->execute();
                                    while($camp=$sql_camp->fetch()){
                                ?>
                                <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                            &nbsp;<span id="mc_spinner0"></span>
                        </div>
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>Program</label>
                            <select class="form-control select2" style="width:100%;" id="mc_prg_type_id">
                                <option selected disabled>Select Program</option>
                            </select>
                            &nbsp;<span id="mc_spinner1"></span>
                        </div>
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>School</label>
                            <select class="form-control select2" style="width:100%;" id="mc_fac_id">
                                <option selected disabled>Select School</option>
                            </select>
                            &nbsp;<span id="mc_spinner2"></span>
                        </div>
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>Department</label>
                            <select class="form-control select2" style="width:100%;" id="mc_dept_id">
                                <option selected disabled>Select Department</option>
                            </select>
                            &nbsp;<span id="mc_spinner3"></span>
                        </div>
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>Specialization</label>
                            <select class="form-control select2" style="width:100%;" id="mc_splz_id">
                                <option selected disabled>Select Specialization</option>
                            </select>
                            &nbsp;<span id="mc_spinner4"></span>
                        </div>
                        <div class="form-group col-12 col-sm-4 col-lg-3">
                            <label>Level</label>
                            <select class="form-control select2" style="width:100%;" id="mc_level_id">
                                <option selected disabled>Select Level</option>
                            </select>
                            &nbsp;<span id="mc_spinner5"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student List Section -->
<div class="section-body" id="mc_student_list_section" hidden>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Students</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger" id="mc_btn_class_pdf"><i class="fas fa-file-pdf"></i> Print Class PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm" id="mc_student_table" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Registration Number</th>
                                <th>Names</th>
                            </tr>
                        </thead>
                        <tbody id="mc_students"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Detail Section -->
<div class="section-body" id="mc_invoice_detail" hidden>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Invoice Records</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger" id="mc_btn_pdf"><i class="fas fa-file-pdf"></i> Print PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mc_acad_selector" class="mb-3"></div>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fee Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="mc_invoice_rows"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>