<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Fee Categories</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Fee Category</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Fee Category</h4>
                                    <div class="card-header-action">
                                        <a href="#" class="btn btn-icon btn-info" data-toggle="modal" data-target="#addFeeModal"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Registered Fee Categories</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <ul class="nav nav-tabs" id="feeTypeTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab-common-fee" data-toggle="tab" href="#pane-common-fee" role="tab">Common Fees (All Students)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab-specific-fee" data-toggle="tab" href="#pane-specific-fee" role="tab">Specific Fees</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-2" id="feeTypeTabsContent">
                                        <div class="tab-pane fade show active" id="pane-common-fee" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="common_fee_table" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Fee Name</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Action</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql_common = $conn->prepare("SELECT * FROM tbl_fee_categories WHERE is_common = 1 ORDER BY status DESC, id DESC");
                                                        $sql_common->execute();
                                                        $i = 1;
                                                        while($row = $sql_common->fetch()):
                                                    ?>
                                                    <tr data-row-id="<?php echo $row['id']; ?>">
                                                        <th scope="row"><?php echo $i++; ?></th>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo number_format($row['amount'], 2); ?></td>
                                                        <th>
                                                            <?php if($role_id == 18 || $role_id == 2 || $role_id == 3): ?>
                                                            <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                                <span id="spinner4_<?php echo $row['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                            </button>
                                                            <?php endif; ?>
                                                        </th>
                                                        <td>
                                                            <?php if($role_id == 18 || $role_id == 2 || $role_id == 3): ?>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['id']; ?>" <?php echo $row['status']==1 ? 'checked' : ''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['id']; ?>"></span>
                                                            </label>
                                                            <?php else: ?>
                                                                <?php echo $row['status']==1 ? 'Active' : 'Inactive'; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pane-specific-fee" role="tabpanel">
                                            <div class="row" style="margin-bottom:10px;">
                                                <div class="form-group col-md-3">
                                                    <label class="small">Campus</label>
                                                    <select class="form-control form-control-sm" id="filter_campus">
                                                        <option value="">All Campus</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="small">Faculty</label>
                                                    <select class="form-control form-control-sm" id="filter_faculty">
                                                        <option value="">All Faculties</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="specific_fee_table" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Campus</th>
                                                        <th scope="col">Faculty</th>
                                                        <th scope="col">Level</th>
                                                        <th scope="col">Refinement</th>
                                                        <th scope="col">Fee Name</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Action</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql = $conn->prepare("SELECT fc.*, camp.camp_full_name, fac.fac_full_name, lvl.level_full_name,
                                                                pt.prg_type_full_name, dept.dept_full_name, opt.opt_full_name
                                                            FROM tbl_fee_categories fc
                                                            LEFT JOIN tbl_campus camp ON fc.camp_id = camp.camp_id
                                                            LEFT JOIN tbl_faculty fac ON fc.fac_id = fac.fac_id
                                                            LEFT JOIN tbl_level lvl ON fc.level_id = lvl.level_id
                                                            LEFT JOIN tbl_program_type pt ON fc.prg_type_id = pt.prg_type_id
                                                            LEFT JOIN tbl_department dept ON fc.dept_id = dept.dept_id
                                                            LEFT JOIN tbl_option opt ON fc.opt_id = opt.opt_id
                                                            WHERE fc.is_common = 0
                                                            ORDER BY fc.status DESC, fc.id DESC");
                                                        $sql->execute();
                                                        $i = 1;
                                                        while($row = $sql->fetch()):
                                                            $refine = array_filter([$row['prg_type_full_name'], $row['dept_full_name'], $row['opt_full_name']]);
                                                            $refine_text = count($refine) ? implode(' &rsaquo; ', $refine) : '&mdash;';
                                                    ?>
                                                    <tr data-row-id="<?php echo $row['id']; ?>">
                                                        <th scope="row"><?php echo $i++; ?></th>
                                                        <td><?php echo $row['camp_full_name'] ?? '&mdash;'; ?></td>
                                                        <td><?php echo $row['fac_full_name'] ?? '&mdash;'; ?></td>
                                                        <td><?php echo $row['level_full_name'] ?? '&mdash;'; ?></td>
                                                        <td><?php echo $refine_text; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo number_format($row['amount'], 2); ?></td>
                                                        <th>
                                                            <?php if($role_id == 18 || $role_id == 2 || $role_id == 3): ?>
                                                            <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                                <span id="spinner4_<?php echo $row['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                            </button>
                                                            <?php endif; ?>
                                                        </th>
                                                        <td>
                                                            <?php if($role_id == 18 || $role_id == 2 || $role_id == 3): ?>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['id']; ?>" <?php echo $row['status']==1 ? 'checked' : ''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['id']; ?>"></span>
                                                            </label>
                                                            <?php else: ?>
                                                                <?php echo $row['status']==1 ? 'Active' : 'Inactive'; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!--add modal-->
            <form id="save_fee">
                <div class="modal fade" tabindex="-1" role="dialog" id="addFeeModal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">New Fee Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="custom-switch">
                                            <input type="checkbox" class="custom-switch-input" id="is_common">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">This is a common fee &mdash; applies to <strong>all students</strong> (e.g. Student Card), regardless of campus, faculty or level</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row" id="specific_block">
                                    <div class="form-group col-md-4">
                                        <label>Campus <code>*</code></label>
                                        <select class="form-control select2" style="width:100%" id="camp_id">
                                            <option value="">select campus</option>
                                            <?php
                                                $sql_camp = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                $sql_camp->execute();
                                                while($campus = $sql_camp->fetch()):
                                            ?>
                                            <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Faculty <code>*</code> <span id="spinner_fac"></span></label>
                                        <select class="form-control select2" style="width:100%" id="fac_id">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Level <code>*</code> <span id="spinner_level"></span></label>
                                        <select class="form-control select2" style="width:100%" id="level_id">
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small text-muted mb-0">
                                            <i class="fas fa-filter"></i> Narrow further by Program/Department/Option (optional)
                                        </label>
                                    </div>
                                    <div class="col-md-12" id="narrow_block">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Program Type <span id="spinner_prg"></span></label>
                                                <select class="form-control select2" style="width:100%" id="prg_type_id">
                                                    <option value="">-- Any --</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Department <span id="spinner_dept"></span></label>
                                                <select class="form-control select2" style="width:100%" id="dept_id" disabled>
                                                    <option value="">-- Any --</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Option <span id="spinner_opt"></span></label>
                                                <select class="form-control select2" style="width:100%" id="opt_id" disabled>
                                                    <option value="">-- Any --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-7">
                                        <label>Fee Name <code>*</code></label>
                                        <input type="text" class="form-control" id="name" placeholder="Ex: TUITION FEE / STUDENT CARD" style="text-transform:uppercase">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label>Amount <code>*</code></label>
                                        <input type="number" class="form-control" id="amount" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end add modal-->

            <!--update modal-->
            <form id="update_form">
                <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Updating <span id="f_name"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="e_id">
                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input type="checkbox" class="custom-switch-input" id="e_is_common">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Common fee &mdash; applies to all students</span>
                                    </label>
                                </div>
                                <div class="row" id="e_specific_block">
                                    <div class="form-group col-md-6">
                                        <label>Campus</label>
                                        <select class="form-control select2" style="width:100%" id="e_camp_id">
                                            <option value="">-- Select Campus --</option>
                                            <?php
                                                $sql_camp2 = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                                $sql_camp2->execute();
                                                while($camp2 = $sql_camp2->fetch()):
                                            ?>
                                            <option value="<?php echo $camp2['camp_id']; ?>"><?php echo $camp2['camp_full_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Faculty <span id="spinner_e_fac"></span></label>
                                        <select class="form-control select2" style="width:100%" id="e_fac_id">
                                            <option value="">-- Select Campus first --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Level <span id="spinner_e_level"></span></label>
                                        <select class="form-control select2" style="width:100%" id="e_level_id">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Program Type <span id="spinner_e_prg"></span></label>
                                        <select class="form-control select2" style="width:100%" id="e_prg_type_id">
                                            <option value="">-- Any --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Department <span id="spinner_e_dept"></span></label>
                                        <select class="form-control select2" style="width:100%" id="e_dept_id" disabled>
                                            <option value="">-- Any --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Option <span id="spinner_e_opt"></span></label>
                                        <select class="form-control select2" style="width:100%" id="e_opt_id" disabled>
                                            <option value="">-- Any --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fee Name</label>
                                    <input type="text" class="form-control" id="e_name" style="text-transform:uppercase">
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" id="e_amount" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end update modal-->
        </div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var pendingEFacId = null, pendingELevelId = null, pendingEPrgId = null, pendingEDeptId = null, pendingEOptId = null;

$(document).ready(function(){
    var table = $('#fee_cat_table').DataTable({
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 10
    });

    // ==================== populate filter dropdowns from rendered table ====================
    function escRegex(val){ return val.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&'); }

    function populateFilter($select, colIndex){
        var values = {};
        table.rows().every(function(){
            var d = this.data();
            var v = $('<div>').html(d[colIndex]).text().trim();
            if(v && v !== '—') values[v] = true;
        });
        var html = $select.find('option').first().prop('outerHTML');
        Object.keys(values).sort().forEach(function(v){
            html += '<option value="'+v+'">'+v+'</option>';
        });
        $select.html(html);
    }
    populateFilter($('#filter_campus'), 2);
    populateFilter($('#filter_faculty'), 3);

    function applyFilters(){
        var type = $('#filter_type').val();
        var camp = $('#filter_campus').val();
        var fac = $('#filter_faculty').val();
        table.column(1).search(type ? escRegex(type) : '', true, false);
        table.column(2).search(camp ? '^'+escRegex(camp)+'$' : '', true, false);
        table.column(3).search(fac ? '^'+escRegex(fac)+'$' : '', true, false);
        table.draw();
    }
    $('#filter_type, #filter_campus, #filter_faculty').on('change', applyFilters);

    // ==================== ADD FORM ====================
    $('#is_common').on('change', function(){
        if($(this).is(':checked')){
            $('#specific_block').hide();
        } else {
            $('#specific_block').show();
        }
    });

    $('#camp_id').on('change', function(){
        var camp_id = $(this).val();
        $('#fac_id, #level_id').empty();
        $('#prg_type_id, #dept_id, #opt_id').empty().append("<option value=''>-- Any --</option>");
        $('#dept_id, #opt_id').attr('disabled', true);
        if(!camp_id) return;
        $('#spinner_fac').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php",
            type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" },
            dataType: "JSON",
            success: function(data){
                $('#spinner_fac').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load faculties!"); return; }
                $("#fac_id").append("<option value=''>select faculty</option>");
                $.each(data.data, function(i, v){ $("#fac_id").append("<option value='"+v.fac_id+"'>"+v.fac_full_name+"</option>"); });
            },
            error: function(){ $('#spinner_fac').html(""); pop_wrong("Failed to load faculties!"); }
        });
    });

    $('#fac_id').on('change', function(){
        var fac_id = $(this).val();
        $('#level_id').empty();
        $('#prg_type_id').empty().append("<option value=''>-- Any --</option>");
        $('#dept_id, #opt_id').empty().append("<option value=''>-- Any --</option>").attr('disabled', true);
        if(!fac_id) return;

        $('#spinner_level').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { fac_id: fac_id, action: "get_level" }, dataType: "JSON",
            success: function(data){
                $('#spinner_level').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load levels!"); return; }
                $("#level_id").append("<option value=''>select level</option>");
                $.each(data.data, function(i, v){ $("#level_id").append("<option value='"+v.level_id+"'>"+v.level_full_name+"</option>"); });
            },
            error: function(){ $('#spinner_level').html(""); pop_wrong("Failed to load levels!"); }
        });

        $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { fac_id: fac_id, action: "get_prg_type" }, dataType: "JSON",
            success: function(data){
                $('#spinner_prg').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load program types!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.prg_type_id+"'>"+v.prg_type_full_name+"</option>"; });
                $("#prg_type_id").html(html);
            },
            error: function(){ $('#spinner_prg').html(""); pop_wrong("Failed to load program types!"); }
        });
    });

    $('#prg_type_id').on('change', function(){
        var prg_type = $(this).val();
        $('#dept_id, #opt_id').empty().append("<option value=''>-- Any --</option>").attr('disabled', true);
        if(!prg_type) return;
        $('#dept_id').attr('disabled', false);
        $('#spinner_dept').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { prg_type: prg_type, action: "get_department" }, dataType: "JSON",
            success: function(data){
                $('#spinner_dept').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load departments!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.dept_id+"'>"+v.dept_full_name+"</option>"; });
                $("#dept_id").html(html);
            },
            error: function(){ $('#spinner_dept').html(""); pop_wrong("Failed to load departments!"); }
        });
    });

    $('#dept_id').on('change', function(){
        var dept_id = $(this).val();
        $('#opt_id').empty().append("<option value=''>-- Any --</option>").attr('disabled', true);
        if(!dept_id) return;
        $('#opt_id').attr('disabled', false);
        $('#spinner_opt').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { dept_id: dept_id, action: "get_option" }, dataType: "JSON",
            success: function(data){
                $('#spinner_opt').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load options!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.opt_id+"'>"+v.opt_full_name+"</option>"; });
                $("#opt_id").html(html);
            },
            error: function(){ $('#spinner_opt').html(""); pop_wrong("Failed to load options!"); }
        });
    });

    $("#save_fee").submit(function(e){
        e.preventDefault();
        var isCommon = $('#is_common').is(':checked');

        if(!$('#name').val().trim()){ pop_wrong("Fee name is required!"); return; }
        if($('#amount').val() === '' || isNaN($('#amount').val()) || Number($('#amount').val()) < 0){ pop_wrong("A valid amount is required!"); return; }
        if(!isCommon){
            if(!$('#camp_id').val()){ pop_wrong("Campus is required for a specific fee!"); return; }
            if(!$('#fac_id').val()){ pop_wrong("Faculty is required for a specific fee!"); return; }
            if(!$('#level_id').val()){ pop_wrong("Level is required for a specific fee!"); return; }
        }

        var formData = {
            is_common: isCommon ? '1' : '0',
            camp_id: $('#camp_id').val(),
            fac_id: $('#fac_id').val(),
            level_id: $('#level_id').val(),
            prg_type_id: $('#prg_type_id').val(),
            dept_id: $('#dept_id').val(),
            opt_id: $('#opt_id').val(),
            name: $('#name').val(),
            amount: $('#amount').val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST", data: formData, dataType: "JSON",
            success: function(data){
                $('#spinner').html(""); $('#indicator').html("Save");
                if(data.status==200){
                    pop_up_success(data.message);
                    location.reload();
                } else {
                    pop_wrong(data.message);
                }
            },
            error: function(){ $('#spinner').html(""); $('#indicator').html("Save"); pop_wrong("Something went wrong!"); }
        });
    });

    // ==================== TOGGLE STATUS ====================
    $(document).on('click', '.del', function(){
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        swal({
            title: "Are you sure?",
            text: "You are about to change this fee category's status!",
            icon: "warning", buttons: true, dangerMode: true
        }).then((willDelete) => {
            if(willDelete){
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>");
                $.ajax({
                    type: "POST", url: "../new_files/fee_category/controller.php",
                    data: { id: data_id, action: 'delete' }, dataType: "json",
                    success: function(data){
                        $('#spinner3_'+data_id).html("");
                        if(data.status==200){ pop_up_success(data.message); }
                        else { $checkbox.prop('checked', !$checkbox.prop('checked')); pop_wrong(data.message); }
                    },
                    error: function(){
                        $('#spinner3_'+data_id).html("");
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
                        pop_wrong("Something went wrong");
                    }
                });
            } else {
                $checkbox.prop('checked', !$checkbox.prop('checked'));
            }
        });
    });

    // ==================== EDIT ====================
    $(document).on('click', '.edit', function(){
        var data_id = $(this).data('id');
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            type: "POST", url: "../new_files/fee_category/controller.php",
            data: { id: data_id, action: 'view' }, dataType: "json",
            success: function(data){
                $('#spinner4_'+data_id).html("");
                $("#e_id").val(data.id);
                $("#e_name").val(data.name);
                $("#e_amount").val(data.amount);
                $("#f_name").html(data.name);

                var isCommon = data.is_common == 1;
                $('#e_is_common').prop('checked', isCommon).trigger('change');

                if(!isCommon){
                    pendingEFacId = data.fac_id;
                    pendingELevelId = data.level_id;
                    pendingEPrgId = data.prg_type_id;
                    pendingEDeptId = data.dept_id;
                    pendingEOptId = data.opt_id;
                    $("#e_camp_id").val(data.camp_id).trigger('change');
                }
                $('#updateModal').modal('show');
            },
            error: function(){ $('#spinner4_'+data_id).html(""); pop_wrong("Something went wrong!"); }
        });
    });

    $('#e_is_common').on('change', function(){
        if($(this).is(':checked')){ $('#e_specific_block').hide(); } else { $('#e_specific_block').show(); }
    });

    $('#e_camp_id').on('change', function(){
        var camp_id = $(this).val();
        $('#e_fac_id, #e_level_id').empty();
        $('#e_prg_type_id, #e_dept_id, #e_opt_id').empty().append("<option value=''>-- Any --</option>");
        if(!camp_id) return;
        $('#spinner_e_fac').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { camp_id: camp_id, action: "get_faculty" }, dataType: "JSON",
            success: function(data){
                $('#spinner_e_fac').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load faculties!"); return; }
                $("#e_fac_id").empty().append("<option value=''>-- Select Faculty --</option>");
                $.each(data.data, function(i, v){ $("#e_fac_id").append("<option value='"+v.fac_id+"'>"+v.fac_full_name+"</option>"); });
                if(pendingEFacId){ $("#e_fac_id").val(pendingEFacId).trigger('change'); pendingEFacId = null; }
            },
            error: function(){ $('#spinner_e_fac').html(""); pop_wrong("Failed to load faculties!"); }
        });
    });

    $('#e_fac_id').on('change', function(){
        var fac_id = $(this).val();
        $('#e_level_id').empty();
        $('#e_prg_type_id').empty().append("<option value=''>-- Any --</option>");
        $('#e_dept_id, #e_opt_id').empty().append("<option value=''>-- Any --</option>");
        if(!fac_id) return;

        $('#spinner_e_level').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { fac_id: fac_id, action: "get_level" }, dataType: "JSON",
            success: function(data){
                $('#spinner_e_level').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load levels!"); return; }
                $("#e_level_id").empty().append("<option value=''>-- Select Level --</option>");
                $.each(data.data, function(i, v){ $("#e_level_id").append("<option value='"+v.level_id+"'>"+v.level_full_name+"</option>"); });
                if(pendingELevelId){ $("#e_level_id").val(pendingELevelId); pendingELevelId = null; }
            },
            error: function(){ $('#spinner_e_level').html(""); pop_wrong("Failed to load levels!"); }
        });

        $('#spinner_e_prg').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { fac_id: fac_id, action: "get_prg_type" }, dataType: "JSON",
            success: function(data){
                $('#spinner_e_prg').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load program types!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.prg_type_id+"'>"+v.prg_type_full_name+"</option>"; });
                $("#e_prg_type_id").html(html);
                if(pendingEPrgId){ $("#e_prg_type_id").val(pendingEPrgId).trigger('change'); pendingEPrgId = null; }
            },
            error: function(){ $('#spinner_e_prg').html(""); pop_wrong("Failed to load program types!"); }
        });
    });

    $('#e_prg_type_id').on('change', function(){
        var prg_type = $(this).val();
        $('#e_dept_id, #e_opt_id').empty().append("<option value=''>-- Any --</option>");
        if(!prg_type) return;
        $('#spinner_e_dept').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { prg_type: prg_type, action: "get_department" }, dataType: "JSON",
            success: function(data){
                $('#spinner_e_dept').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load departments!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.dept_id+"'>"+v.dept_full_name+"</option>"; });
                $("#e_dept_id").html(html);
                if(pendingEDeptId){ $("#e_dept_id").val(pendingEDeptId).trigger('change'); pendingEDeptId = null; }
            },
            error: function(){ $('#spinner_e_dept').html(""); pop_wrong("Failed to load departments!"); }
        });
    });

    $('#e_dept_id').on('change', function(){
        var dept_id = $(this).val();
        $('#e_opt_id').empty().append("<option value=''>-- Any --</option>");
        if(!dept_id) return;
        $('#spinner_e_opt').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST",
            data: { dept_id: dept_id, action: "get_option" }, dataType: "JSON",
            success: function(data){
                $('#spinner_e_opt').html("");
                if(!data || data.status != 200){ pop_wrong((data && data.message) || "Failed to load options!"); return; }
                var html = "<option value=''>-- Any --</option>";
                $.each(data.data, function(i, v){ html += "<option value='"+v.opt_id+"'>"+v.opt_full_name+"</option>"; });
                $("#e_opt_id").html(html);
                if(pendingEOptId){ $("#e_opt_id").val(pendingEOptId); pendingEOptId = null; }
            },
            error: function(){ $('#spinner_e_opt').html(""); pop_wrong("Failed to load options!"); }
        });
    });

    $("#update_form").submit(function(e){
        e.preventDefault();
        var eIsCommon = $('#e_is_common').is(':checked');

        if(!$('#e_name').val().trim()){ pop_wrong("Fee name is required!"); return; }
        if($('#e_amount').val() === '' || isNaN($('#e_amount').val()) || Number($('#e_amount').val()) < 0){ pop_wrong("A valid amount is required!"); return; }
        if(!eIsCommon){
            if(!$('#e_camp_id').val()){ pop_wrong("Campus is required for a specific fee!"); return; }
            if(!$('#e_fac_id').val()){ pop_wrong("Faculty is required for a specific fee!"); return; }
            if(!$('#e_level_id').val()){ pop_wrong("Level is required for a specific fee!"); return; }
        }

        var formData = {
            pr_id: $("#e_id").val(),
            is_common: eIsCommon ? '1' : '0',
            camp_id: $('#e_camp_id').val(),
            fac_id: $('#e_fac_id').val(),
            level_id: $('#e_level_id').val(),
            prg_type_id: $('#e_prg_type_id').val(),
            dept_id: $('#e_dept_id').val(),
            opt_id: $('#e_opt_id').val(),
            name: $('#e_name').val(),
            amount: $('#e_amount').val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/fee_category/controller.php", type: "POST", data: formData, dataType: "JSON",
            success: function(data){
                $('#spinner2').html(""); $('#indicator2').html("Save Changes");
                if(data.status==200){
                    pop_up_success(data.message);
                    location.reload();
                } else {
                    pop_wrong(data.message);
                }
            },
            error: function(){ $('#spinner2').html(""); $('#indicator2').html("Save Changes"); pop_wrong("Something went wrong!"); }
        });
    });
});

function pop_wrong(feedback) {
    iziToast.warning({ title: 'Error', message: feedback, position: 'topCenter' });
}
function pop_up_success(feedback) {
    iziToast.success({ title: 'info', message: feedback, position: 'topCenter' });
}
</script>
