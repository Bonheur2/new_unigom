<?php
// Teaching units (UE) and their courses (EC).
// A unit belongs to a programme + level + semester, optionally narrowed to one
// department / option. Its courses are managed from the "Courses" button.


// $conn from meet/con.php is created in PDO's silent error mode, where a
// failing query just returns no rows - the list would look empty and the
// catch below would never run. Switch to exceptions for this page's queries,
// then restore the original mode so the rest of the admin shell is unaffected.
$prev_errmode = $conn->getAttribute(PDO::ATTR_ERRMODE);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$faculties = [];
$units = [];
$load_error = '';
try {
    $facStmt = $conn->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status = 1 ORDER BY fac_full_name ASC");
    $facStmt->execute();
    $faculties = $facStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    $load_error = $e->getMessage();
}

try {
    $st = $conn->prepare("SELECT u.*, pt.prg_type_full_name, fac.fac_full_name, lvl.level_full_name,
                                 dept.dept_full_name, opt.opt_full_name,
                                 (SELECT COUNT(*) FROM tbl_courses c WHERE c.ue_id = u.ue_id AND c.status = 1) AS course_count,
                                 (SELECT COALESCE(SUM(c.credits), 0) FROM tbl_courses c WHERE c.ue_id = u.ue_id AND c.status = 1) AS course_credits
                          FROM tbl_teaching_units u
                          LEFT JOIN tbl_program_type pt ON pt.prg_type_id = u.prg_type_id
                          LEFT JOIN tbl_faculty fac ON fac.fac_id = pt.fac_id
                          LEFT JOIN tbl_level lvl ON lvl.level_id = u.level_id
                          LEFT JOIN tbl_department dept ON dept.dept_id = u.dept_id
                          LEFT JOIN tbl_option opt ON opt.opt_id = u.opt_id
                          ORDER BY u.status DESC, fac.fac_full_name, pt.prg_type_full_name, lvl.level_rank, u.semester_no, u.ue_code");
    $st->execute();
    $units = $st->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    $load_error = $e->getMessage();
}

$conn->setAttribute(PDO::ATTR_ERRMODE, $prev_errmode);

?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Teaching Units &amp; Courses</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Teaching Units</div>
                    </div>
                </div>
                <div class="section-body">

                    <?php if($load_error !== ''): ?>
                    <div class="alert alert-danger">
                        Could not load units: <?php echo htmlspecialchars($load_error); ?>
                        <br><small>If the table does not exist yet, run <b>Academic/migration.sql</b> first.</small>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- ===================== NEW UNIT ===================== -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Teaching Unit (UE)</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#new-unit" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="new-unit">
                                    <div class="card-body">
                                        <form id="unit_form">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label>Faculty <code>*</code></label>
                                                    <select class="form-control select2" style="width:100%" id="fac_id">
                                                        <option value="">select faculty</option>
                                                        <?php foreach($faculties as $f): ?>
                                                        <option value="<?php echo $f['fac_id']; ?>"><?php echo htmlspecialchars($f['fac_full_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Programme <code>*</code> <span id="sp_prg"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="prg_type_id">
                                                        <option value="">select faculty first</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Level <code>*</code> <span id="sp_level"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="level_id">
                                                        <option value="">select programme first</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Semester <code>*</code></label>
                                                    <select class="form-control select2" style="width:100%" id="semester_no">
                                                        <option value="1">Semester 1</option>
                                                        <option value="2">Semester 2</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Department <small class="text-muted">(empty = all)</small> <span id="sp_dept"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="dept_id">
                                                        <option value="">-- All departments --</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option <small class="text-muted">(empty = all)</small> <span id="sp_opt"></span></label>
                                                    <select class="form-control select2" style="width:100%" id="opt_id">
                                                        <option value="">-- All options --</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>UE code <code>*</code></label>
                                                    <input type="text" class="form-control" id="ue_code" placeholder="Ex: UE101" style="text-transform:uppercase">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>UE name <code>*</code></label>
                                                    <input type="text" class="form-control" id="ue_name" placeholder="Ex: Mathematiques generales">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Credits</label>
                                                    <input type="number" class="form-control" id="credits" step="0.5" min="0" placeholder="0">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Pass mark</label>
                                                    <input type="number" class="form-control" id="pass_mark" step="0.01" min="0" value="10">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-block"><span id="sp_save"></span>&nbsp;Save unit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===================== UNIT LIST ===================== -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header"><h4>Registered Units</h4></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="units_table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Unit</th>
                                                    <th>Faculty</th>
                                                    <th>Programme</th>
                                                    <th>Level</th>
                                                    <th>Sem.</th>
                                                    <th>Department / Option</th>
                                                    <th>Credits</th>
                                                    <th>Pass</th>
                                                    <th>Courses</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; foreach($units as $u):
                                                    // the unit's credits should match the sum of its active courses
                                                    $credit_mismatch = $u['course_count'] > 0 && abs($u['course_credits'] - $u['credits']) > 0.001;
                                                ?>
                                                <tr class="<?php echo $u['status'] == 1 ? '' : 'text-muted'; ?>">
                                                    <td><?php echo $i++; ?></td>
                                                    <td><b><?php echo htmlspecialchars($u['ue_code']); ?></b></td>
                                                    <td><?php echo htmlspecialchars($u['ue_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($u['fac_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($u['prg_type_full_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($u['level_full_name'] ?? ''); ?></td>
                                                    <td><?php echo (int) $u['semester_no']; ?></td>
                                                    <td><small><?php echo $u['dept_full_name'] ? htmlspecialchars($u['dept_full_name']) : 'All departments'; ?><?php echo $u['opt_full_name'] ? ' &rsaquo; '.htmlspecialchars($u['opt_full_name']) : ''; ?></small></td>
                                                    <td>
                                                        <?php echo rtrim(rtrim($u['credits'], '0'), '.'); ?>
                                                        <?php if($credit_mismatch): ?>
                                                        <i class="fas fa-exclamation-triangle text-warning" title="The active courses add up to <?php echo rtrim(rtrim($u['course_credits'], '0'), '.'); ?> credits"></i>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo rtrim(rtrim($u['pass_mark'], '0'), '.'); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm open_courses" data-id="<?php echo $u['ue_id']; ?>">
                                                            <i class="fas fa-book"></i>&nbsp;<?php echo (int) $u['course_count']; ?>
                                                        </button>
                                                    </td>
                                                    <td style="white-space:nowrap;">
                                                        <button type="button" class="btn btn-primary btn-sm edit_unit" data-id="<?php echo $u['ue_id']; ?>"><i class="far fa-edit"></i></button>
                                                        <label class="custom-switch mb-0" style="padding-left:0;">
                                                            <input type="checkbox" class="custom-switch-input toggle_unit" data-id="<?php echo $u['ue_id']; ?>" <?php echo $u['status'] == 1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===================== EDIT UNIT MODAL ===================== -->
            <div class="modal fade" tabindex="-1" role="dialog" id="unitModal">
                <div class="modal-dialog modal-lg" role="document">
                    <form id="unit_edit_form" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit unit</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="e_ue_id">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Faculty</label>
                                    <select class="form-control" id="e_fac_id">
                                        <option value="">select faculty</option>
                                        <?php foreach($faculties as $f): ?>
                                        <option value="<?php echo $f['fac_id']; ?>"><?php echo htmlspecialchars($f['fac_full_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Programme <span id="e_sp_prg"></span></label>
                                    <select class="form-control" id="e_prg_type_id"></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Level <span id="e_sp_level"></span></label>
                                    <select class="form-control" id="e_level_id"></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Semester</label>
                                    <select class="form-control" id="e_semester_no">
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Department <small class="text-muted">(empty = all)</small> <span id="e_sp_dept"></span></label>
                                    <select class="form-control" id="e_dept_id"></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Option <small class="text-muted">(empty = all)</small> <span id="e_sp_opt"></span></label>
                                    <select class="form-control" id="e_opt_id"></select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>UE code</label>
                                    <input type="text" class="form-control" id="e_ue_code" style="text-transform:uppercase">
                                </div>
                                <div class="form-group col-md-5">
                                    <label>UE name</label>
                                    <input type="text" class="form-control" id="e_ue_name">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Credits</label>
                                    <input type="number" class="form-control" id="e_credits" step="0.5" min="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Pass mark</label>
                                    <input type="number" class="form-control" id="e_pass_mark" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm"><span id="e_sp_save"></span>&nbsp;Save changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ===================== COURSES MODAL ===================== -->
            <div class="modal fade" tabindex="-1" role="dialog" id="coursesModal">
                <div class="modal-dialog modal-xl" role="document" style="max-width:1100px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Courses of <span id="c_unit_title"></span></h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div id="c_credit_note" class="mb-2"></div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Code</th><th>Course</th><th>Credits</th><th>Hours</th>
                                            <th>CAT %</th><th>Exam %</th><th>Pass mark</th><th>Elim. exam</th><th style="width:110px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="c_body"></tbody>
                                </table>
                            </div>

                            <div class="card bg-whitesmoke mt-3 mb-0">
                                <div class="card-body" style="padding:15px;">
                                    <h6 style="font-size:14px;" id="c_form_title"><i class="fas fa-plus"></i>&nbsp; Add a course to this unit</h6>
                                    <form id="course_form">
                                        <input type="hidden" id="c_course_id">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label class="small">Code</label>
                                                <input type="text" class="form-control form-control-sm" id="c_course_code" placeholder="Ex: MAT101" style="text-transform:uppercase">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="small">Course name <code>*</code></label>
                                                <input type="text" class="form-control form-control-sm" id="c_course_name">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="small">Credits</label>
                                                <input type="number" class="form-control form-control-sm" id="c_credits" step="0.5" min="0" value="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="small">Hours</label>
                                                <input type="number" class="form-control form-control-sm" id="c_hours" min="0">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="small">CAT %</label>
                                                <input type="number" class="form-control form-control-sm" id="c_cat_weight" step="0.01" min="0" max="100" value="40">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="small">Exam %</label>
                                                <input type="number" class="form-control form-control-sm" id="c_exam_weight" step="0.01" min="0" max="100" value="60">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="small">Pass mark</label>
                                                <input type="number" class="form-control form-control-sm" id="c_pass_mark" step="0.01" min="0" value="10">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="small">Elim. exam mark <small class="text-muted">(optional)</small></label>
                                                <input type="number" class="form-control form-control-sm" id="c_exam_min_mark" step="0.01" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="small">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-sm btn-block"><span id="c_sp_save"></span>&nbsp;<span id="c_save_label">Add</span></button>
                                                <button type="button" class="btn btn-light btn-sm btn-block mt-1" id="c_cancel_edit" hidden>Cancel edit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var CTRL = "../new_files/Teaching_Units/controller.php";
var CURRENT_UE = null;
var LOADER = "<img src='../../img/ajax_loader.gif' width='15'>";

$(document).ready(function(){
    $('#units_table').DataTable({
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "iDisplayLength": 25
    });

    // ---------------- cascades (add form) ----------------
    $('#fac_id').on('change', function(){
        resetSel('#level_id', 'select programme first');
        resetSel('#dept_id', '-- All departments --');
        resetSel('#opt_id', '-- All options --');
        loadInto('get_programmes', { fac_id: $(this).val() }, '#prg_type_id', 'select programme', 'prg_type_id', 'prg_type_full_name', '#sp_prg');
    });
    $('#prg_type_id').on('change', function(){
        var p = $(this).val();
        resetSel('#opt_id', '-- All options --');
        loadInto('get_levels', { prg_type_id: p }, '#level_id', 'select level', 'level_id', 'level_full_name', '#sp_level');
        loadInto('get_departments', { prg_type_id: p }, '#dept_id', '-- All departments --', 'dept_id', 'dept_full_name', '#sp_dept');
    });
    $('#dept_id').on('change', function(){
        loadInto('get_options', { dept_id: $(this).val() }, '#opt_id', '-- All options --', 'opt_id', 'opt_full_name', '#sp_opt');
    });

    // ---------------- cascades (edit modal) ----------------
    $('#e_fac_id').on('change', function(){
        resetSel('#e_level_id', 'select programme first');
        resetSel('#e_dept_id', '-- All departments --');
        resetSel('#e_opt_id', '-- All options --');
        loadInto('get_programmes', { fac_id: $(this).val() }, '#e_prg_type_id', 'select programme', 'prg_type_id', 'prg_type_full_name', '#e_sp_prg');
    });
    $('#e_prg_type_id').on('change', function(){
        var p = $(this).val();
        resetSel('#e_opt_id', '-- All options --');
        loadInto('get_levels', { prg_type_id: p }, '#e_level_id', 'select level', 'level_id', 'level_full_name', '#e_sp_level');
        loadInto('get_departments', { prg_type_id: p }, '#e_dept_id', '-- All departments --', 'dept_id', 'dept_full_name', '#e_sp_dept');
    });
    $('#e_dept_id').on('change', function(){
        loadInto('get_options', { dept_id: $(this).val() }, '#e_opt_id', '-- All options --', 'opt_id', 'opt_full_name', '#e_sp_opt');
    });

    // ---------------- save unit ----------------
    $('#unit_form').on('submit', function(e){
        e.preventDefault();
        var data = unitPayload('');
        if(!data){ return; }
        data.action = 'save_unit';
        $('#sp_save').html(LOADER);
        post(data, function(d){
            $('#sp_save').html('');
            if(d.status == 200){ pop_up_success(d.message); setTimeout(function(){ location.reload(); }, 900); }
            else { pop_wrong(d.message); }
        }, function(){ $('#sp_save').html(''); });
    });

    // ---------------- edit unit ----------------
    $(document).on('click', '.edit_unit', function(){
        var id = $(this).data('id');
        post({ action: 'view_unit', ue_id: id }, function(d){
            if(d.status != 200){ pop_wrong(d.message); return; }
            var u = d.data;
            $('#e_ue_id').val(u.ue_id);
            $('#e_ue_code').val(u.ue_code);
            $('#e_ue_name').val(u.ue_name);
            $('#e_semester_no').val(u.semester_no);
            $('#e_credits').val(u.credits);
            $('#e_pass_mark').val(u.pass_mark);
            $('#e_fac_id').val(u.fac_id);

            // refill each dependent list, then select the saved value, in order
            loadInto('get_programmes', { fac_id: u.fac_id }, '#e_prg_type_id', 'select programme', 'prg_type_id', 'prg_type_full_name', '#e_sp_prg', u.prg_type_id)
            .then(function(){
                return $.when(
                    loadInto('get_levels', { prg_type_id: u.prg_type_id }, '#e_level_id', 'select level', 'level_id', 'level_full_name', '#e_sp_level', u.level_id),
                    loadInto('get_departments', { prg_type_id: u.prg_type_id }, '#e_dept_id', '-- All departments --', 'dept_id', 'dept_full_name', '#e_sp_dept', u.dept_id)
                );
            })
            .then(function(){
                if(u.dept_id){
                    return loadInto('get_options', { dept_id: u.dept_id }, '#e_opt_id', '-- All options --', 'opt_id', 'opt_full_name', '#e_sp_opt', u.opt_id);
                }
                resetSel('#e_opt_id', '-- All options --');
            });

            $('#unitModal').modal('show');
        });
    });

    $('#unit_edit_form').on('submit', function(e){
        e.preventDefault();
        var data = unitPayload('e_');
        if(!data){ return; }
        data.action = 'update_unit';
        data.ue_id = $('#e_ue_id').val();
        $('#e_sp_save').html(LOADER);
        post(data, function(d){
            $('#e_sp_save').html('');
            if(d.status == 200){ $('#unitModal').modal('hide'); pop_up_success(d.message); setTimeout(function(){ location.reload(); }, 900); }
            else { pop_wrong(d.message); }
        }, function(){ $('#e_sp_save').html(''); });
    });

    $(document).on('change', '.toggle_unit', function(){
        var $cb = $(this);
        post({ action: 'toggle_unit', ue_id: $cb.data('id') }, function(d){
            if(d.status == 200){ pop_up_success(d.message); }
            else { $cb.prop('checked', !$cb.prop('checked')); pop_wrong(d.message); }
        }, function(){ $cb.prop('checked', !$cb.prop('checked')); });
    });

    // ---------------- courses ----------------
    $(document).on('click', '.open_courses', function(){
        CURRENT_UE = $(this).data('id');
        $('#coursesModal').data('dirty', false);
        resetCourseForm();
        loadCourses();
        $('#coursesModal').modal('show');
    });

    // keep CAT % + Exam % at 100 while typing
    $('#c_cat_weight').on('input', function(){
        var v = parseFloat($(this).val());
        if(!isNaN(v) && v >= 0 && v <= 100){ $('#c_exam_weight').val((100 - v).toFixed(2).replace(/\.00$/, '')); }
    });
    $('#c_exam_weight').on('input', function(){
        var v = parseFloat($(this).val());
        if(!isNaN(v) && v >= 0 && v <= 100){ $('#c_cat_weight').val((100 - v).toFixed(2).replace(/\.00$/, '')); }
    });

    $('#course_form').on('submit', function(e){
        e.preventDefault();
        var name = $('#c_course_name').val().trim();
        if(!name){ pop_wrong("Course name is required"); return; }
        var cat = parseFloat($('#c_cat_weight').val()) || 0;
        var exam = parseFloat($('#c_exam_weight').val()) || 0;
        if(Math.abs(cat + exam - 100) > 0.001){ pop_wrong("CAT % and Exam % must add up to 100"); return; }

        var editing = $('#c_course_id').val() !== '';
        var data = {
            action: editing ? 'update_course' : 'save_course',
            course_id: $('#c_course_id').val(),
            ue_id: CURRENT_UE,
            course_code: $('#c_course_code').val(),
            course_name: name,
            credits: $('#c_credits').val(),
            hours: $('#c_hours').val(),
            cat_weight: cat,
            exam_weight: exam,
            pass_mark: $('#c_pass_mark').val(),
            exam_min_mark: $('#c_exam_min_mark').val()
        };
        $('#c_sp_save').html(LOADER);
        post(data, function(d){
            $('#c_sp_save').html('');
            if(d.status == 200){ $('#coursesModal').data('dirty', true); pop_up_success(d.message); resetCourseForm(); loadCourses(); }
            else { pop_wrong(d.message); }
        }, function(){ $('#c_sp_save').html(''); });
    });

    $(document).on('click', '.edit_course', function(){
        post({ action: 'view_course', course_id: $(this).data('id') }, function(d){
            if(d.status != 200){ pop_wrong(d.message); return; }
            var c = d.data;
            $('#c_course_id').val(c.course_id);
            $('#c_course_code').val(c.course_code || '');
            $('#c_course_name').val(c.course_name);
            $('#c_credits').val(c.credits);
            $('#c_hours').val(c.hours || '');
            $('#c_cat_weight').val(c.cat_weight);
            $('#c_exam_weight').val(c.exam_weight);
            $('#c_pass_mark').val(c.pass_mark);
            $('#c_exam_min_mark').val(c.exam_min_mark || '');
            $('#c_form_title').html('<i class="far fa-edit"></i>&nbsp; Edit course');
            $('#c_save_label').text('Save');
            $('#c_cancel_edit').attr('hidden', false);
            $('#c_course_name').focus();
        });
    });

    $('#c_cancel_edit').on('click', resetCourseForm);

    $(document).on('change', '.toggle_course', function(){
        var $cb = $(this);
        post({ action: 'toggle_course', course_id: $cb.data('id') }, function(d){
            if(d.status == 200){ $('#coursesModal').data('dirty', true); pop_up_success(d.message); loadCourses(); }
            else { $cb.prop('checked', !$cb.prop('checked')); pop_wrong(d.message); }
        }, function(){ $cb.prop('checked', !$cb.prop('checked')); });
    });

    // the unit list shows course counts, so refresh it once courses changed
    $('#coursesModal').on('hidden.bs.modal', function(){
        if($(this).data('dirty')){ location.reload(); }
    });
});

// ---------------- helpers ----------------
function post(data, ok, fail){
    return $.ajax({ url: CTRL, type: "POST", dataType: "JSON", data: data,
        success: ok,
        error: function(){ if(fail){ fail(); } pop_wrong("Request failed"); }
    });
}

function resetSel(sel, placeholder){
    $(sel).html("<option value=''>"+placeholder+"</option>").trigger('change.select2');
}

/** Fills a select from an endpoint; optionally selects `value` once loaded. Returns the request. */
function loadInto(action, payload, sel, placeholder, idKey, nameKey, spinner, value){
    resetSel(sel, placeholder);
    var firstVal = Object.keys(payload).map(function(k){ return payload[k]; })[0];
    if(!firstVal){ return $.Deferred().resolve().promise(); }
    $(spinner).html(LOADER);
    payload.action = action;
    return $.ajax({ url: CTRL, type: "POST", dataType: "JSON", data: payload })
        .done(function(d){
            $(spinner).html('');
            if(!d || d.status != 200){ return; }
            var html = "<option value=''>"+placeholder+"</option>";
            $.each(d.data, function(i, r){ html += "<option value='"+r[idKey]+"'>"+r[nameKey]+"</option>"; });
            $(sel).html(html);
            if(value){ $(sel).val(value); }
            $(sel).trigger('change.select2');
        })
        .fail(function(){ $(spinner).html(''); });
}

function unitPayload(p){
    var d = {
        ue_code: $('#'+p+'ue_code').val().trim(),
        ue_name: $('#'+p+'ue_name').val().trim(),
        prg_type_id: $('#'+p+'prg_type_id').val(),
        level_id: $('#'+p+'level_id').val(),
        semester_no: $('#'+p+'semester_no').val(),
        dept_id: $('#'+p+'dept_id').val(),
        opt_id: $('#'+p+'opt_id').val(),
        credits: $('#'+p+'credits').val(),
        pass_mark: $('#'+p+'pass_mark').val()
    };
    if(!d.prg_type_id){ pop_wrong("Choose the programme"); return null; }
    if(!d.level_id){ pop_wrong("Choose the level"); return null; }
    if(!d.ue_code || !d.ue_name){ pop_wrong("Unit code and name are required"); return null; }
    return d;
}

function num(v){ return v === null || v === '' ? '' : String(parseFloat(v)); }

function loadCourses(){
    $('#c_body').html('<tr><td colspan="9" class="text-center text-muted">Loading...</td></tr>');
    post({ action: 'load_courses', ue_id: CURRENT_UE }, function(d){
        if(d.status != 200){ $('#c_body').html('<tr><td colspan="9" class="text-danger">'+d.message+'</td></tr>'); return; }
        $('#c_unit_title').text(d.unit.ue_code + ' - ' + d.unit.ue_name);

        var total = 0, html = '';
        $.each(d.data, function(i, c){
            if(c.status == 1){ total += parseFloat(c.credits) || 0; }
            html += '<tr'+(c.status == 1 ? '' : ' class="text-muted"')+'>'
                  + '<td>'+(c.course_code || '')+'</td>'
                  + '<td>'+c.course_name+'</td>'
                  + '<td>'+num(c.credits)+'</td>'
                  + '<td>'+(c.hours || '')+'</td>'
                  + '<td>'+num(c.cat_weight)+'</td>'
                  + '<td>'+num(c.exam_weight)+'</td>'
                  + '<td>'+num(c.pass_mark)+'</td>'
                  + '<td>'+num(c.exam_min_mark)+'</td>'
                  + '<td style="white-space:nowrap;">'
                  + '<button type="button" class="btn btn-primary btn-sm edit_course" data-id="'+c.course_id+'"><i class="far fa-edit"></i></button> '
                  + '<label class="custom-switch mb-0" style="padding-left:0;"><input type="checkbox" class="custom-switch-input toggle_course" data-id="'+c.course_id+'"'+(c.status == 1 ? ' checked' : '')+'><span class="custom-switch-indicator"></span></label>'
                  + '</td></tr>';
        });
        $('#c_body').html(html || '<tr><td colspan="9" class="text-center text-muted">No courses yet.</td></tr>');

        var unitCredits = parseFloat(d.unit.credits) || 0;
        var note = 'Active courses: <b>'+num(total)+'</b> credits &middot; Unit: <b>'+num(unitCredits)+'</b> credits';
        if(d.data.length && Math.abs(total - unitCredits) > 0.001){
            note = '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> '+note+' &mdash; they do not match.</span>';
        }
        $('#c_credit_note').html(note);
    });
}

function resetCourseForm(){
    $('#course_form')[0] && $('#course_form')[0].reset();
    $('#c_course_id').val('');
    $('#c_cat_weight').val(40);
    $('#c_exam_weight').val(60);
    $('#c_pass_mark').val(10);
    $('#c_form_title').html('<i class="fas fa-plus"></i>&nbsp; Add a course to this unit');
    $('#c_save_label').text('Add');
    $('#c_cancel_edit').attr('hidden', true);
}

function pop_wrong(f){ iziToast.warning({ title: 'Info', message: f, position: 'topCenter' }); }
function pop_up_success(f){ iziToast.success({ title: 'Info', message: f, position: 'topCenter' }); }
</script>
