<?php require'../meet/bind.php'; ?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Program Fee Category</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Program Fee Category</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="feeTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-categories" data-toggle="tab" href="#categories" role="tab">
                                <i class="fas fa-list"></i> Fee Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-generate" data-toggle="tab" href="#generate" role="tab">
                                <i class="fas fa-cogs"></i> Generate Fee
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-program-fees" data-toggle="tab" href="#program-fees" role="tab">
                                <i class="fas fa-graduation-cap"></i> Program Type Fees
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="feeTabsContent">
                <!-- ==================== TAB 1: FEE CATEGORIES ==================== -->
                <div class="tab-pane fade show active" id="categories" role="tabpanel">
                    <div class="row" style="margin-top:15px;">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Fee Category</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_fee_category" method="POST">
                                    <input type="hidden" name="action" value="save">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Campus <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="camp_id"
                                                id="camp_id" required>
                                                <option value="" disabled selected>--Choose Campus--</option>
                                                <?php
                                                    $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active = '1'");
                                                    $sql_camp->execute();
                                                    while($camp = $sql_camp->fetch()){
                                                ?>
                                                <option value="<?php echo $camp['camp_id']; ?>">
                                                    <?php echo $camp['camp_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span id="sp_camp"></span>
                                        </div>
                                        <div class="form-group col-md-4" id="prg_div" style="display:none;">
                                            <label>Program Type <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="prg_type_id"
                                                id="prg_type_id" required>
                                            </select>
                                            <span id="sp_prg"></span>
                                        </div>
                                        <div class="form-group col-md-4" id="fac_div" style="display:none;">
                                            <label>School <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="fac_id"
                                                id="fac_id" required>
                                            </select>
                                            <span id="sp_fac"></span>
                                        </div>
                                        <div class="form-group col-md-4" id="dept_div" style="display:none;">
                                            <label>Department <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="dept_id"
                                                id="dept_id" required>
                                            </select>
                                            <span id="sp_dept"></span>
                                        </div>
                                        <div class="form-group col-md-4" id="splz_div" style="display:none;">
                                            <label>Specialization <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="splz_id"
                                                id="splz_id" required>
                                            </select>
                                            <span id="sp_splz"></span>
                                        </div>
                                        <div class="form-group col-md-4" id="level_div" style="display:none;">
                                            <label>Level <code>*</code></label>
                                            <select class="form-control select2" style="width:100%" name="level_id"
                                                id="level_id" required>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Fee Name <code>*</code></label>
                                            <input type="text" class="form-control" name="name" id="single_name"
                                                placeholder="e.g. TUITION FEE" style="text-transform:uppercase">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Amount <code>*</code></label>
                                            <input type="number" class="form-control" name="amount" id="single_amount" placeholder="0.00"
                                                step="0.01" min="0">
                                        </div>
                                        <div class="form-group col-md-4" style="margin-top:30px;">
                                            <button type="submit" class="btn btn-primary btn-block" id="sBtn">
                                                <span id="spinner"></span>&nbsp;<span id="indicator">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Or Bulk Upload via CSV</h6>
                                        <small class="text-muted">First select Campus, Program, School, Department, Specialization and Level above, then download the format, fill it and upload.</small>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-top:10px;">
                                        <button type="button" class="btn btn-success btn-block" id="downloadFormatBtn">
                                            <i class="fas fa-download"></i> Download Format
                                        </button>
                                    </div>
                                    <div class="form-group col-md-5" style="margin-top:10px;">
                                        <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv">
                                    </div>
                                    <div class="form-group col-md-4" style="margin-top:10px;">
                                        <button type="button" class="btn btn-info btn-block" id="uploadBtn">
                                            <span id="upload_spinner"></span>&nbsp;<span id="upload_indicator">Upload CSV</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Registered Fee Categories</h4>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-bottom:15px;">
                                <div class="form-group col-md-4" style="margin-bottom:5px;">
                                    <label class="small">Campus</label>
                                    <select class="form-control form-control-sm" id="filter_campus">
                                        <option value="">All Campus</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" style="margin-bottom:5px;">
                                    <label class="small">Program</label>
                                    <select class="form-control form-control-sm" id="filter_program" disabled>
                                        <option value="">All Programs</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" style="margin-bottom:5px;">
                                    <label class="small">School</label>
                                    <select class="form-control form-control-sm" id="filter_school" disabled>
                                        <option value="">All Schools</option>
                                    </select>
                                </div>
                            </div>
                            <div style="margin-bottom:10px;">
                                <button class="btn btn-success btn-sm" id="btn_excel">
                                    <i class="fas fa-file-excel"></i> Download Excel
                                </button>
                                <button class="btn btn-danger btn-sm" id="btn_pdf">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="fee_cat_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Campus</th>
                                            <th>Program</th>
                                            <th>School</th>
                                            <th>Department</th>
                                            <th>Specialization</th>
                                            <th>Level</th>
                                            <th>Fee Name</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = $conn->prepare("SELECT fc.*, c.camp_full_name, pt.prg_type_full_name, f.fac_full_name, d.dept_full_name, s.splz_full_name, l.level_full_name
                                                FROM fee_category fc
                                                LEFT JOIN tbl_campus c ON fc.camp_id = c.camp_id
                                                LEFT JOIN tbl_program_type pt ON fc.prg_type_id = pt.prg_type_id
                                                LEFT JOIN tbl_faculty f ON fc.fac_id = f.fac_id
                                                LEFT JOIN tbl_department d ON fc.dept_id = d.dept_id
                                                LEFT JOIN tbl_specialization s ON fc.splz_id = s.splz_id
                                                LEFT JOIN tbl_level l ON fc.level_id = l.level_id
                                                ORDER BY fc.status ASC, fc.id DESC");
                                            $sql->execute();
                                            $i = 1;
                                            while($row = $sql->fetch()){
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['camp_full_name']; ?></td>
                                            <td><?php echo $row['prg_type_full_name']; ?></td>
                                            <td><?php echo $row['fac_full_name']; ?></td>
                                            <td><?php echo $row['dept_full_name']; ?></td>
                                            <td><?php echo $row['splz_full_name']; ?></td>
                                            <td><?php echo $row['level_full_name']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo number_format($row['amount'], 2); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit"
                                                    data-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input del"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        <?php if($row['status'] == 1) echo 'checked'; ?>>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Tab 1 -->
            </div>

            <!-- ==================== TAB 2: GENERATE FEE ==================== -->
            <div class="tab-pane fade" id="generate" role="tabpanel">
            <!-- ==================== GENERATE FEE SECTION ==================== -->
            <div class="row" style="margin-top:15px;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Generate Fee</h4>
                            <div class="card-header-action">
                                <a data-collapse="#gen-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="gen-collapse">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Campus <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_camp_id" required>
                                            <option value="" disabled selected>--Choose Campus--</option>
                                            <?php
                                                $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active = '1'");
                                                $sql_camp2->execute();
                                                while($camp2 = $sql_camp2->fetch()){
                                            ?>
                                            <option value="<?php echo $camp2['camp_id']; ?>"><?php echo $camp2['camp_full_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span id="gen_sp_camp"></span>
                                    </div>
                                    <div class="form-group col-md-4" id="gen_prg_div" style="display:none;">
                                        <label>Program Type <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_prg_type_id"></select>
                                        <span id="gen_sp_prg"></span>
                                    </div>
                                    <div class="form-group col-md-4" id="gen_fac_div" style="display:none;">
                                        <label>School <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_fac_id"></select>
                                        <span id="gen_sp_fac"></span>
                                    </div>
                                    <div class="form-group col-md-4" id="gen_dept_div" style="display:none;">
                                        <label>Department <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_dept_id"></select>
                                        <span id="gen_sp_dept"></span>
                                    </div>
                                    <div class="form-group col-md-4" id="gen_splz_div" style="display:none;">
                                        <label>Specialization <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_splz_id"></select>
                                    </div>
                                    <div class="form-group col-md-4" id="gen_level_div" style="display:none;">
                                        <label>Level <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="gen_level_id"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-sm" id="fetchFeesBtn">
                                            <i class="fas fa-search"></i> Fetch Fee Items
                                        </button>
                                    </div>
                                </div>
                                <!-- Fee items preview -->
                                <div id="fee_preview" style="display:none; margin-top:15px;">
                                    <table class="table table-bordered table-sm">
                                        <thead style="background-color:#003366; color:#fff;">
                                            <tr>
                                                <th>#</th>
                                                <th>Fee Name</th>
                                                <th style="text-align:right;">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fee_preview_body"></tbody>
                                        <tfoot>
                                            <tr style="font-weight:bold; background-color:#f0f0f0;">
                                                <td colspan="2">Total Amount</td>
                                                <td style="text-align:right;" id="fee_total">0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label>Fee Name <code>*</code></label>
                                            <input type="text" class="form-control" id="gen_fee_name" placeholder="e.g. TUITION" style="text-transform:uppercase" required>
                                        </div>
                                        <div class="form-group col-md-4" style="margin-top:30px;">
                                            <button type="button" class="btn btn-success" id="createFeeBtn">
                                                <span id="gen_spinner"></span>&nbsp;<span id="gen_indicator">Create Fee</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generated Fees Table -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Generated Fees</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="gen_fee_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Campus</th>
                                            <th>Program</th>
                                            <th>School</th>
                                            <th>Department</th>
                                            <th>Specialization</th>
                                            <th>Level</th>
                                            <th>Fee Name</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql_gen = $conn->prepare("SELECT gf.*, c.camp_full_name, pt.prg_type_full_name, f.fac_full_name, d.dept_full_name, s.splz_full_name, l.level_full_name
                                                FROM tbl_fee_category gf
                                                LEFT JOIN tbl_campus c ON gf.camp_id = c.camp_id
                                                LEFT JOIN tbl_program_type pt ON gf.prg_type_id = pt.prg_type_id
                                                LEFT JOIN tbl_faculty f ON gf.fac_id = f.fac_id
                                                LEFT JOIN tbl_department d ON gf.dept_id = d.dept_id
                                                LEFT JOIN tbl_specialization s ON gf.splz_id = s.splz_id
                                                LEFT JOIN tbl_level l ON gf.level_id = l.level_id
                                                ORDER BY gf.id DESC");
                                            $sql_gen->execute();
                                            $j = 1;
                                            while($gf = $sql_gen->fetch()){
                                        ?>
                                        <tr>
                                            <td><?php echo $j++; ?></td>
                                            <td><?php echo $gf['camp_full_name']; ?></td>
                                            <td><?php echo $gf['prg_type_full_name']; ?></td>
                                            <td><?php echo $gf['fac_full_name']; ?></td>
                                            <td><?php echo $gf['dept_full_name']; ?></td>
                                            <td><?php echo $gf['splz_full_name']; ?></td>
                                            <td><?php echo $gf['level_full_name']; ?></td>
                                            <td><?php echo $gf['name']; ?></td>
                                            <td><?php echo number_format($gf['amount'], 2); ?></td>
                                            <td>
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input toggle_gen"
                                                        data-id="<?php echo $gf['id']; ?>"
                                                        <?php if($gf['status'] == '1') echo 'checked'; ?>>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Tab 2 -->
            </div>

            <!-- ==================== TAB 3: PROGRAM TYPE FEES ==================== -->
            <div class="tab-pane fade" id="program-fees" role="tabpanel">
                <div class="row" style="margin-top:15px;">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Program Type Fees</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                    $campTabs = $conn->prepare("SELECT c.camp_id, c.camp_full_name
                                        FROM tbl_campus c
                                        WHERE EXISTS (
                                            SELECT 1 FROM tbl_program_type pt WHERE pt.campus_id = c.camp_id
                                        )
                                        ORDER BY c.camp_full_name ASC");
                                    $campTabs->execute();
                                    $campRows = $campTabs->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <?php if (!empty($campRows)) { ?>
                                    <ul class="nav nav-tabs" role="tablist" style="margin-bottom:15px; overflow-x:auto; white-space:nowrap;">
                                        <?php foreach ($campRows as $index => $campRow) { ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>" data-toggle="tab"
                                                   href="#camp-fee-<?php echo $campRow['camp_id']; ?>" role="tab">
                                                    <?php echo $campRow['camp_full_name']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                    <div class="tab-content">
                                        <?php foreach ($campRows as $index => $campRow) {
                                            $campProgramSql = $conn->prepare("SELECT prg_type_id, prg_type_full_name, admission_fee, application_fee
                                                FROM tbl_program_type
                                                WHERE campus_id = :camp_id
                                                ORDER BY prg_type_full_name ASC");
                                            $campProgramSql->execute([':camp_id' => $campRow['camp_id']]);
                                        ?>
                                            <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" id="camp-fee-<?php echo $campRow['camp_id']; ?>" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover program_fee_table" id="program_fee_table_<?php echo $campRow['camp_id']; ?>" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Program Type</th>
                                                                <th>Admission Fee</th>
                                                                <th>Application Fee</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $k = 1;
                                                                while($prg = $campProgramSql->fetch()){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $k++; ?></td>
                                                                <td><?php echo $prg['prg_type_full_name']; ?></td>
                                                                <td><?php echo number_format((float)$prg['admission_fee'], 2); ?></td>
                                                                <td><?php echo number_format((float)$prg['application_fee'], 2); ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning edit-program-fee"
                                                                        data-id="<?php echo $prg['prg_type_id']; ?>">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-info mb-0">
                                        No program types were found for any campus.
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Tab 3 -->

            </div><!-- end tab-content -->
        </div>
    </section>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Fee Category</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="update_form" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="up_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fee Name <code>*</code></label>
                        <input type="text" class="form-control" name="name" id="up_name" style="text-transform:uppercase" required>
                    </div>
                    <div class="form-group">
                        <label>Amount <code>*</code></label>
                        <input type="number" class="form-control" name="amount" id="up_amount" step="0.01" min="0"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uBtn">
                        <span id="u_spinner"></span>&nbsp;<span id="u_indicator">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Program Type Fees Modal -->
<div class="modal fade" id="programFeeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Program Type Fees</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="program_fee_form" method="POST">
                <input type="hidden" name="action" value="updateProgramTypeFees">
                <input type="hidden" name="prg_type_id" id="prg_fee_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Program Type</label>
                        <input type="text" class="form-control" id="prg_fee_name" readonly>
                    </div>
                    <div class="form-group">
                        <label>Admission Fee <code>*</code></label>
                        <input type="number" class="form-control" name="admission_fee" id="admission_fee" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Application Fee <code>*</code></label>
                        <input type="number" class="form-control" name="application_fee" id="application_fee" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="prgFeeBtn">
                        <span id="prgFeeSpinner"></span>&nbsp;<span id="prgFeeIndicator">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Adjust DataTable columns when Generate Fee tab is shown
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var targetHref = $(e.target).attr('href');

        if (targetHref === '#generate' || targetHref === '#program-fees' || targetHref.indexOf('#camp-fee-') === 0) {
            if ($.fn.dataTable && $.fn.dataTable.tables) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            }
            if ($.fn.dataTable.isDataTable('#gen_fee_table')) {
                $('#gen_fee_table').DataTable().columns.adjust();
            }
            if (targetHref === '#program-fees') {
                $('.program_fee_table').each(function() {
                    if (!$.fn.dataTable.isDataTable(this)) {
                        $(this).DataTable({
                            aLengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
                            iDisplayLength: 10,
                            autoWidth: false,
                            responsive: true
                        });
                    }
                });
            }
            $('.program_fee_table').each(function() {
                if ($.fn.dataTable.isDataTable(this)) {
                    $(this).DataTable().columns.adjust();
                }
            });
        }
    });

    var table = $('#fee_cat_table').DataTable({
        "aLengthMenu": [
            [5, 10, 25, -1],
            [5, 10, 25, "All"]
        ],
        "iDisplayLength": 10
    });

    // ==================== EXPORT FUNCTIONS ====================
    function getVisibleData() {
        var headers = ['#', 'Campus', 'Program', 'School', 'Department', 'Specialization', 'Level', 'Fee Name', 'Amount'];
        var rows = [];
        table.rows({ search: 'applied' }).every(function() {
            var data = this.data();
            rows.push([data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7], data[8]]);
        });
        return { headers: headers, rows: rows };
    }

    // Excel export
    $('#btn_excel').on('click', function() {
        var d = getVisibleData();
        var wsData = [d.headers].concat(d.rows);
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, 'Fee Categories');
        XLSX.writeFile(wb, 'Fee_Categories_Report.xlsx');
    });

    // Program type fee edit
    $(document).on('click', '.edit-program-fee', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'viewProgramTypeFee',
                prg_type_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    $('#prg_fee_id').val(response.data.prg_type_id);
                    $('#prg_fee_name').val(response.data.prg_type_full_name);
                    $('#admission_fee').val(response.data.admission_fee);
                    $('#application_fee').val(response.data.application_fee);
                    $('#programFeeModal').modal('show');
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                pop_wrong('Failed to load program type fees');
            }
        });
    });

    // PDF export
    $('#btn_pdf').on('click', function() {
        var d = getVisibleData();
        var body = [d.headers.map(function(h) { return { text: h, bold: true, fillColor: '#003366', color: '#ffffff', fontSize: 8 }; })];
        d.rows.forEach(function(row) {
            body.push(row.map(function(cell) { return { text: cell || '', fontSize: 7 }; }));
        });

        var docDef = {
            pageOrientation: 'landscape',
            pageSize: 'A4',
            content: [
                { text: 'Fee Categories Report', style: 'header' },
                { text: 'Generated: ' + new Date().toLocaleDateString(), style: 'sub', margin: [0, 0, 0, 10] },
                {
                    table: {
                        headerRows: 1,
                        widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
                        body: body
                    },
                    layout: 'lightHorizontalLines'
                }
            ],
            styles: {
                header: { fontSize: 14, bold: true, color: '#003366' },
                sub: { fontSize: 9, color: '#666666' }
            }
        };
        pdfMake.createPdf(docDef).download('Fee_Categories_Report.pdf');
    });

    // ==================== TABLE FILTERS (Cascading: Campus -> Program -> School) ====================
    function escRegex(val) {
        return val.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
    }

    // Populate Campus filter from table data
    function populateCampusFilter() {
        var values = {};
        table.rows().every(function() {
            var data = this.data();
            if (data[1]) values[data[1].trim()] = true;
        });
        var html = '<option value="">All Campus</option>';
        Object.keys(values).sort().forEach(function(val) {
            html += '<option value="' + val + '">' + val + '</option>';
        });
        $('#filter_campus').html(html);
    }

    populateCampusFilter();

    // Campus filter change
    $('#filter_campus').on('change', function() {
        var campusVal = $(this).val();
        $('#filter_program').html('<option value="">All Programs</option>').attr('disabled', true);
        $('#filter_school').html('<option value="">All Schools</option>').attr('disabled', true);

        table.column(1).search(campusVal ? '^' + escRegex(campusVal) + '$' : '', true, false);
        table.column(2).search('', true, false);
        table.column(3).search('', true, false);
        table.draw();

        if (campusVal) {
            var values = {};
            table.rows({ search: 'applied' }).every(function() {
                var data = this.data();
                if (data[2]) values[data[2].trim()] = true;
            });
            var html = '<option value="">All Programs</option>';
            Object.keys(values).sort().forEach(function(val) {
                html += '<option value="' + val + '">' + val + '</option>';
            });
            $('#filter_program').html(html).attr('disabled', false);
        }
    });

    // Program filter change
    $('#filter_program').on('change', function() {
        var programVal = $(this).val();
        $('#filter_school').html('<option value="">All Schools</option>').attr('disabled', true);

        table.column(2).search(programVal ? '^' + escRegex(programVal) + '$' : '', true, false);
        table.column(3).search('', true, false);
        table.draw();

        if (programVal) {
            var values = {};
            table.rows({ search: 'applied' }).every(function() {
                var data = this.data();
                if (data[3]) values[data[3].trim()] = true;
            });
            var html = '<option value="">All Schools</option>';
            Object.keys(values).sort().forEach(function(val) {
                html += '<option value="' + val + '">' + val + '</option>';
            });
            $('#filter_school').html(html).attr('disabled', false);
        }
    });

    // School filter change
    $('#filter_school').on('change', function() {
        var schoolVal = $(this).val();
        table.column(3).search(schoolVal ? '^' + escRegex(schoolVal) + '$' : '', true, false);
        table.draw();
    });

    // ==================== CASCADING DROPDOWNS ====================

    // Campus -> Program Types
    $('#camp_id').on('change', function() {
        var camp_id = $(this).val();
        $('#sp_camp').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#prg_div, #fac_div, #dept_div, #splz_div, #level_div').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'getProgramTypes',
                camp_id: camp_id
            },
            dataType: 'json',
            success: function(response) {
                $('#sp_camp').html('');
                var html =
                    '<option value="" disabled selected>--Choose Program Type--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.prg_type_id + '">' + item
                            .prg_type_full_name + '</option>';
                    });
                    $('#prg_type_id').html(html);
                    $('#prg_div').show();
                }
            },
            error: function() {
                $('#sp_camp').html('');
                pop_wrong('Failed to load program types');
            }
        });
    });

    // Program Type -> Schools + Levels
    $('#prg_type_id').on('change', function() {
        var prg_type = $(this).val();
        $('#sp_prg').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#fac_div, #dept_div, #splz_div').hide();

        // Load Schools
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'getFaculties',
                prg_type: prg_type
            },
            dataType: 'json',
            success: function(response) {
                $('#sp_prg').html('');
                var html = '<option value="" disabled selected>--Choose School--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.fac_id + '">' + item
                            .fac_full_name + '</option>';
                    });
                    $('#fac_id').html(html);
                    $('#fac_div').show();
                }
            },
            error: function() {
                $('#sp_prg').html('');
                pop_wrong('Failed to load schools');
            }
        });

        // Load Levels
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'getLevels',
                prg_type: prg_type
            },
            dataType: 'json',
            success: function(response) {
                var html = '<option value="" disabled selected>--Choose Level--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.level_id + '">' + item
                            .level_full_name + '</option>';
                    });
                    $('#level_id').html(html);
                    $('#level_div').show();
                }
            }
        });
    });

    // School -> Departments
    $('#fac_id').on('change', function() {
        var fac_id = $(this).val();
        var prg_type = $('#prg_type_id').val();
        $('#sp_fac').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#dept_div, #splz_div').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'getDepartments',
                fac_id: fac_id,
                prg_type: prg_type
            },
            dataType: 'json',
            success: function(response) {
                $('#sp_fac').html('');
                var html =
                    '<option value="" disabled selected>--Choose Department--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.dept_id + '">' + item
                            .dept_full_name + '</option>';
                    });
                    $('#dept_id').html(html);
                    $('#dept_div').show();
                }
            },
            error: function() {
                $('#sp_fac').html('');
                pop_wrong('Failed to load departments');
            }
        });
    });

    // Department -> Specializations
    $('#dept_id').on('change', function() {
        var dept_id = $(this).val();
        var prg_type = $('#prg_type_id').val();
        $('#sp_dept').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#splz_div').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'getSpecializations',
                dept_id: dept_id,
                prg_type: prg_type
            },
            dataType: 'json',
            success: function(response) {
                $('#sp_dept').html('');
                var html =
                    '<option value="" disabled selected>--Choose Specialization--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.splz_id + '">' + item
                            .splz_full_name + '</option>';
                    });
                    $('#splz_id').html(html);
                    $('#splz_div').show();
                }
            },
            error: function() {
                $('#sp_dept').html('');
                pop_wrong('Failed to load specializations');
            }
        });
    });

    // ==================== DOWNLOAD CSV FORMAT ====================
    $('#downloadFormatBtn').on('click', function() {
        var csvContent = "Fee Name,Amount\nTUITION FEE,500000\n";
        var blob = new Blob([csvContent], { type: 'text/csv' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'fee_category_format.csv';
        link.click();
    });

    // ==================== CSV UPLOAD ====================
    $('#uploadBtn').on('click', function() {
        var camp_id = $('#camp_id').val();
        var prg_type_id = $('#prg_type_id').val();
        var fac_id = $('#fac_id').val();
        var dept_id = $('#dept_id').val();
        var splz_id = $('#splz_id').val();
        var level_id = $('#level_id').val();
        var csv_file = $('#csv_file')[0].files[0];

        if (!camp_id || !prg_type_id || !fac_id || !dept_id || !splz_id || !level_id) {
            pop_wrong('Please select all dropdowns (Campus to Level) before uploading!');
            return;
        }
        if (!csv_file) {
            pop_wrong('Please select a CSV file!');
            return;
        }

        var formData = new FormData();
        formData.append('action', 'uploadCsv');
        formData.append('camp_id', camp_id);
        formData.append('prg_type_id', prg_type_id);
        formData.append('fac_id', fac_id);
        formData.append('dept_id', dept_id);
        formData.append('splz_id', splz_id);
        formData.append('level_id', level_id);
        formData.append('csv_file', csv_file);

        $('#uploadBtn').attr('disabled', true);
        $('#upload_spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#upload_indicator').html('Uploading...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#uploadBtn').attr('disabled', false);
                $('#upload_spinner').html('');
                $('#upload_indicator').html('Upload CSV');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#csv_file').val('');
                    location.reload();
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#uploadBtn').attr('disabled', false);
                $('#upload_spinner').html('');
                $('#upload_indicator').html('Upload CSV');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // ==================== GENERATE FEE CASCADING DROPDOWNS ====================
    $('#gen_fee_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });

    // Gen: Campus -> Program Types
    $('#gen_camp_id').on('change', function() {
        var camp_id = $(this).val();
        $('#gen_sp_camp').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#gen_prg_div, #gen_fac_div, #gen_dept_div, #gen_splz_div, #gen_level_div').hide();
        $('#fee_preview').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: { action: 'getProgramTypes', camp_id: camp_id },
            dataType: 'json',
            success: function(response) {
                $('#gen_sp_camp').html('');
                var html = '<option value="" disabled selected>--Choose Program Type--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.prg_type_id + '">' + item.prg_type_full_name + '</option>';
                    });
                    $('#gen_prg_type_id').html(html);
                    $('#gen_prg_div').show();
                }
            },
            error: function() { $('#gen_sp_camp').html(''); pop_wrong('Failed to load program types'); }
        });
    });

    // Gen: Program Type -> Schools + Levels
    $('#gen_prg_type_id').on('change', function() {
        var prg_type = $(this).val();
        $('#gen_sp_prg').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#gen_fac_div, #gen_dept_div, #gen_splz_div').hide();
        $('#fee_preview').hide();

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: { action: 'getFaculties', prg_type: prg_type },
            dataType: 'json',
            success: function(response) {
                $('#gen_sp_prg').html('');
                var html = '<option value="" disabled selected>--Choose School--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.fac_id + '">' + item.fac_full_name + '</option>';
                    });
                    $('#gen_fac_id').html(html);
                    $('#gen_fac_div').show();
                }
            },
            error: function() { $('#gen_sp_prg').html(''); pop_wrong('Failed to load schools'); }
        });

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: { action: 'getLevels', prg_type: prg_type },
            dataType: 'json',
            success: function(response) {
                var html = '<option value="" disabled selected>--Choose Level--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.level_id + '">' + item.level_full_name + '</option>';
                    });
                    $('#gen_level_id').html(html);
                    $('#gen_level_div').show();
                }
            }
        });
    });

    // Gen: School -> Departments
    $('#gen_fac_id').on('change', function() {
        var fac_id = $(this).val();
        var prg_type = $('#gen_prg_type_id').val();
        $('#gen_sp_fac').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#gen_dept_div, #gen_splz_div').hide();
        $('#fee_preview').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: { action: 'getDepartments', fac_id: fac_id, prg_type: prg_type },
            dataType: 'json',
            success: function(response) {
                $('#gen_sp_fac').html('');
                var html = '<option value="" disabled selected>--Choose Department--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.dept_id + '">' + item.dept_full_name + '</option>';
                    });
                    $('#gen_dept_id').html(html);
                    $('#gen_dept_div').show();
                }
            },
            error: function() { $('#gen_sp_fac').html(''); pop_wrong('Failed to load departments'); }
        });
    });

    // Gen: Department -> Specializations
    $('#gen_dept_id').on('change', function() {
        var dept_id = $(this).val();
        var prg_type = $('#gen_prg_type_id').val();
        $('#gen_sp_dept').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#gen_splz_div').hide();
        $('#fee_preview').hide();
        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: { action: 'getSpecializations', dept_id: dept_id, prg_type: prg_type },
            dataType: 'json',
            success: function(response) {
                $('#gen_sp_dept').html('');
                var html = '<option value="" disabled selected>--Choose Specialization--</option>';
                if (response.status == 200) {
                    $.each(response.data, function(i, item) {
                        html += '<option value="' + item.splz_id + '">' + item.splz_full_name + '</option>';
                    });
                    $('#gen_splz_id').html(html);
                    $('#gen_splz_div').show();
                }
            },
            error: function() { $('#gen_sp_dept').html(''); pop_wrong('Failed to load specializations'); }
        });
    });

    // ==================== FETCH FEE ITEMS ====================
    $('#fetchFeesBtn').on('click', function() {
        var camp_id = $('#gen_camp_id').val();
        var prg_type_id = $('#gen_prg_type_id').val();
        var fac_id = $('#gen_fac_id').val();
        var dept_id = $('#gen_dept_id').val();
        var splz_id = $('#gen_splz_id').val();
        var level_id = $('#gen_level_id').val();

        if (!camp_id || !prg_type_id || !fac_id || !dept_id || !splz_id || !level_id) {
            pop_wrong('Please select all fields (Campus to Level) before fetching!');
            return;
        }

        $(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Fetching...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'fetchFees',
                camp_id: camp_id,
                prg_type_id: prg_type_id,
                fac_id: fac_id,
                dept_id: dept_id,
                splz_id: splz_id,
                level_id: level_id
            },
            dataType: 'json',
            success: function(response) {
                $('#fetchFeesBtn').attr('disabled', false).html('<i class="fas fa-search"></i> Fetch Fee Items');
                if (response.status == 200 && response.data.length > 0) {
                    var html = '';
                    $.each(response.data, function(i, item) {
                        html += '<tr><td>' + (i + 1) + '</td><td>' + item.name + '</td><td style="text-align:right;">' + parseFloat(item.amount).toLocaleString('en', {minimumFractionDigits: 2}) + '</td></tr>';
                    });
                    $('#fee_preview_body').html(html);
                    $('#fee_total').text(parseFloat(response.total).toLocaleString('en', {minimumFractionDigits: 2}));
                    $('#fee_preview').show();
                } else {
                    $('#fee_preview').hide();
                    pop_wrong('No fee items found for this combination!');
                }
            },
            error: function() {
                $('#fetchFeesBtn').attr('disabled', false).html('<i class="fas fa-search"></i> Fetch Fee Items');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // ==================== CREATE FEE ====================
    $('#createFeeBtn').on('click', function() {
        var camp_id = $('#gen_camp_id').val();
        var prg_type_id = $('#gen_prg_type_id').val();
        var fac_id = $('#gen_fac_id').val();
        var dept_id = $('#gen_dept_id').val();
        var splz_id = $('#gen_splz_id').val();
        var level_id = $('#gen_level_id').val();
        var name = $('#gen_fee_name').val().trim();
        var amount = $('#fee_total').text().replace(/,/g, '');

        if (!name) {
            pop_wrong('Please enter a fee name!');
            return;
        }

        $(this).attr('disabled', true);
        $('#gen_spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#gen_indicator').html('Creating...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: {
                action: 'createFee',
                camp_id: camp_id,
                prg_type_id: prg_type_id,
                fac_id: fac_id,
                dept_id: dept_id,
                splz_id: splz_id,
                level_id: level_id,
                name: name,
                amount: amount
            },
            dataType: 'json',
            success: function(response) {
                $('#createFeeBtn').attr('disabled', false);
                $('#gen_spinner').html('');
                $('#gen_indicator').html('Create Fee');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#gen_fee_name').val('');
                    $('#fee_preview').hide();
                    location.reload();
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#createFeeBtn').attr('disabled', false);
                $('#gen_spinner').html('');
                $('#gen_indicator').html('Create Fee');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // ==================== SAVE ====================
    $("#save_fee_category").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#sBtn').attr('disabled', true);
        $('#spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#indicator').html('Saving...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#sBtn').attr('disabled', false);
                $('#spinner').html('');
                $('#indicator').html('Save');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#save_fee_category')[0].reset();
                    $('#prg_div, #fac_div, #dept_div, #splz_div, #level_div').hide();
                    location.reload();
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#sBtn').attr('disabled', false);
                $('#spinner').html('');
                $('#indicator').html('Save');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // ==================== UPDATE ====================
    $("#update_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#uBtn').attr('disabled', true);
        $('#u_spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#u_indicator').html('Updating...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#uBtn').attr('disabled', false);
                $('#u_spinner').html('');
                $('#u_indicator').html('Update');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    $('#updateModal').modal('hide');
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#uBtn').attr('disabled', false);
                $('#u_spinner').html('');
                $('#u_indicator').html('Update');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });

    // ==================== UPDATE PROGRAM TYPE FEES ====================
    $("#program_fee_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#prgFeeBtn').attr('disabled', true);
        $('#prgFeeSpinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#prgFeeIndicator').html('Updating...');

        $.ajax({
            url: '/files/Fee_categories/fee_category_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $('#prgFeeBtn').attr('disabled', false);
                $('#prgFeeSpinner').html('');
                $('#prgFeeIndicator').html('Update');
                if (response.status == 200) {
                    pop_up_success(response.message);
                    var prgTypeId = $('#prg_fee_id').val();
                    var admissionFee = parseFloat($('#admission_fee').val() || 0).toLocaleString('en', { minimumFractionDigits: 2 });
                    var applicationFee = parseFloat($('#application_fee').val() || 0).toLocaleString('en', { minimumFractionDigits: 2 });
                    var row = $('.edit-program-fee[data-id="' + prgTypeId + '"]').closest('tr');
                    if (row.length) {
                        row.find('td').eq(2).text(admissionFee);
                        row.find('td').eq(3).text(applicationFee);
                    }
                    $('#programFeeModal').modal('hide');
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                $('#prgFeeBtn').attr('disabled', false);
                $('#prgFeeSpinner').html('');
                $('#prgFeeIndicator').html('Update');
                pop_wrong('An error occurred. Please try again.');
            }
        });
    });
});

// Edit - load data into modal
$(document).on('click', '.edit', function() {
    var id = $(this).data('id');
    $.ajax({
        url: '/files/Fee_categories/fee_category_controller.php',
        type: 'POST',
        data: {
            action: 'view',
            id: id
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == 200) {
                $('#up_id').val(response.data.id);
                $('#up_name').val(response.data.name);
                $('#up_amount').val(response.data.amount);
                $('#updateModal').modal('show');
            } else {
                pop_wrong(response.message);
            }
        }
    });
});

// Toggle status
$(document).on('click', '.del', function() {
    var id = $(this).data('id');
    var el = $(this);
    swal({
        title: "Are you sure?",
        text: "You want to change the status?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function(willDelete) {
        if (willDelete) {
            $.ajax({
                url: '/files/Fee_categories/fee_category_controller.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        pop_up_success(response.message);
                        location.reload();
                    } else {
                        pop_wrong(response.message);
                    }
                }
            });
        } else {
            el.prop('checked', !el.prop('checked'));
        }
    });
});

// Toggle status for generated fees
$(document).on('click', '.toggle_gen', function() {
    var id = $(this).data('id');
    var el = $(this);
    swal({
        title: "Are you sure?",
        text: "You want to change the status of this generated fee?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(function(willDelete) {
        if (willDelete) {
            $.ajax({
                url: '/files/Fee_categories/fee_category_controller.php',
                type: 'POST',
                data: { action: 'toggleGeneratedFee', id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        pop_up_success(response.message);
                        location.reload();
                    } else {
                        pop_wrong(response.message);
                    }
                }
            });
        } else {
            el.prop('checked', !el.prop('checked'));
        }
    });
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