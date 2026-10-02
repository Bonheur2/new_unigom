<?php
// Approval setup: define what each ROLE may do, and the approval ROUTE each
// form follows. Both are pure configuration - no role is named in any code.
require_once(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'meet'.DIRECTORY_SEPARATOR.'approval.php');

$formsStmt = $conn->prepare("SELECT form_id, form_name FROM tbl_form_types WHERE status = 1 ORDER BY rank ASC, form_name ASC");
$formsStmt->execute();
$formTypes = $formsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Approval Setup</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Approval Setup</div>
                    </div>
                </div>
                <div class="section-body">

                    <?php if(!can('can_manage_setup')): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-lock"></i> You can view this page, but your role does not have permission to change approval setup.
                    </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs" id="setupTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-roles" data-toggle="tab" href="#pane-roles" role="tab">
                                <i class="fas fa-user-shield"></i>&nbsp; Role permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-routes" data-toggle="tab" href="#pane-routes" role="tab">
                                <i class="fas fa-route"></i>&nbsp; Approval routes
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- ============ ROLE PERMISSIONS ============ -->
                        <div class="tab-pane fade show active" id="pane-roles" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4>What each role may do</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        Tick what a role is allowed to do. <b>Sees</b> controls how wide that role looks:
                                        a <i>department</i> role sees only applicants in the user's own department,
                                        <i>faculty</i> sees the whole faculty, <i>university</i> sees everyone.
                                        The department or faculty itself comes from each user's own record.
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered" id="roles_table">
                                            <thead>
                                                <tr>
                                                    <th style="min-width:160px;">Role</th>
                                                    <th class="text-center" title="May open the review queue and act on a step">Review</th>
                                                    <th class="text-center" title="May choose which of the applicant's options is granted">Select choice</th>
                                                    <th class="text-center" title="May add or remove the courses the applicant must follow">Assign courses</th>
                                                    <th class="text-center" title="May close a request - this creates the student record">Final approve</th>
                                                    <th class="text-center" title="May raise the invoice">Invoice</th>
                                                    <th class="text-center" title="May send a rejected request back into the workflow">Reopen</th>
                                                    <th class="text-center" title="May configure fees, documents and this page">Setup</th>
                                                    <th style="min-width:130px;">Sees</th>
                                                    <th style="width:90px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="roles_body">
                                                <tr><td colspan="10" class="text-center text-muted">Loading...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============ APPROVAL ROUTES ============ -->
                        <div class="tab-pane fade" id="pane-routes" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Approval route per form</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        Steps run in order. A form with no steps has no approval stage &mdash; it behaves
                                        exactly as it does today. An <b>invoice</b> step is completed by raising the
                                        invoice rather than by approving.
                                    </p>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Form</label>
                                            <select class="form-control select2" style="width:100%" id="route_form_id">
                                                <option value="">select a form</option>
                                                <?php foreach($formTypes as $ft): ?>
                                                <option value="<?php echo $ft['form_id']; ?>"><?php echo htmlspecialchars($ft['form_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="route_block" hidden>
                                        <div class="card bg-whitesmoke mb-3">
                                            <div class="card-body" style="padding:15px;">
                                                <h6 style="font-size:14px;"><i class="fas fa-file-pdf"></i>&nbsp; Letter sent on final approval</h6>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label class="small">Document</label>
                                                        <select class="form-control form-control-sm" id="set_completion_document"<?php echo can('can_manage_setup') ? '' : ' disabled'; ?>>
                                                            <option value="none">None</option>
                                                            <option value="admission_letter">Admission letter</option>
                                                            <option value="registration_proof">Proof of registration</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="small">Signatory name</label>
                                                        <input type="text" class="form-control form-control-sm" id="set_signatory_name" placeholder="printed under the signature"<?php echo can('can_manage_setup') ? '' : ' disabled'; ?>>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="small">Signatory title</label>
                                                        <input type="text" class="form-control form-control-sm" id="set_signatory_title" placeholder="e.g. Secretaire General Academique"<?php echo can('can_manage_setup') ? '' : ' disabled'; ?>>
                                                    </div>
                                                    <?php if(can('can_manage_setup')): ?>
                                                    <div class="form-group col-md-2">
                                                        <label class="small">&nbsp;</label>
                                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="save_form_setting">Save</button>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted">The PDF is generated and emailed to the applicant as soon as the last step approves.</small>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered" id="steps_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:60px;">Step</th>
                                                        <th style="min-width:160px;">Role</th>
                                                        <th style="min-width:160px;">Step name</th>
                                                        <th style="width:110px;">Type</th>
                                                        <th style="width:130px;">Order</th>
                                                        <th style="width:70px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="steps_body"></tbody>
                                            </table>
                                        </div>

                                        <?php if(can('can_manage_setup')): ?>
                                        <div class="card bg-whitesmoke mt-3">
                                            <div class="card-body" style="padding:15px;">
                                                <h6 style="font-size:14px;"><i class="fas fa-plus"></i>&nbsp; Add a step to the end of this route</h6>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label class="small">Role that acts</label>
                                                        <select class="form-control form-control-sm" id="new_role_id">
                                                            <option value="">loading...</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="small">Step name</label>
                                                        <input type="text" class="form-control form-control-sm" id="new_step_name" placeholder="e.g. Department review">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label class="small">Type</label>
                                                        <select class="form-control form-control-sm" id="new_step_type">
                                                            <option value="approval">Approval</option>
                                                            <option value="invoice">Invoice</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label class="small">&nbsp;</label>
                                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="add_step">
                                                            <span id="spinner_step"></span>&nbsp;Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div id="route_empty" class="alert alert-light text-center">
                                        Choose a form above to see or build its approval route.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var CAN_MANAGE = <?php echo can('can_manage_setup') ? 'true' : 'false'; ?>;
var ALL_ROLES = [];

$(document).ready(function(){
    loadRoles();

    // select2 measures its container's width when it initializes. The theme's
    // global auto-init runs on page load, while the "Approval routes" tab pane
    // is still display:none (Bootstrap tabs), so #route_form_id gets initialized
    // at zero width and becomes unusable. Re-init it once its tab is actually
    // shown, the same fix used everywhere else in this project for hidden
    // select2 elements.
    $('#tab-routes').on('shown.bs.tab', function(){
        var $el = $('#route_form_id');
        if($el.data('select2')){ $el.select2('destroy'); }
        $el.select2({ width: '100%' });
    });

    $('#route_form_id').on('change', function(){
        var id = $(this).val();
        if(!id){
            $('#route_block').attr('hidden', true);
            $('#route_empty').attr('hidden', false);
            return;
        }
        $('#route_empty').attr('hidden', true);
        $('#route_block').attr('hidden', false);
        loadSteps(id);
        loadFormSetting(id);
    });

    $('#add_step').on('click', function(){
        var form_id = $('#route_form_id').val();
        var role_id = $('#new_role_id').val();
        var name    = $('#new_step_name').val().trim();

        if(!form_id){ pop_wrong("Choose a form first"); return; }
        if(!role_id){ pop_wrong("Choose the role that acts at this step"); return; }
        if(!name){ pop_wrong("Give the step a name"); return; }

        $('#spinner_step').html("<img src='../../img/ajax_loader.gif' width='15'>");
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: {
                action: 'save_step', form_id: form_id, role_id: role_id,
                step_name: name, step_type: $('#new_step_type').val()
            },
            success: function(d){
                $('#spinner_step').html("");
                if(d.status == 200){
                    $('#new_step_name').val('');
                    pop_up_success(d.message);
                    loadSteps(form_id);
                } else { pop_wrong(d.message); }
            },
            error: function(){ $('#spinner_step').html(""); pop_wrong("Could not add the step"); }
        });
    });

    // delete / move are delegated - the rows are rebuilt on every load
    $(document).on('click', '.del_step', function(){
        var id = $(this).data('id');
        swal({
            title: "Remove this step?",
            text: "The route will renumber itself.",
            icon: "warning", buttons: true, dangerMode: true
        }).then(function(ok){
            if(!ok) return;
            $.ajax({
                url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
                data: { action: 'delete_step', workflow_id: id },
                success: function(d){
                    if(d.status == 200){ pop_up_success(d.message); loadSteps($('#route_form_id').val()); }
                    else { pop_wrong(d.message); }
                },
                error: function(){ pop_wrong("Could not remove the step"); }
            });
        });
    });

    $(document).on('click', '.move_step', function(){
        var id  = $(this).data('id');
        var dir = $(this).data('dir');
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: { action: 'move_step', workflow_id: id, direction: dir },
            success: function(d){
                if(d.status == 200){ loadSteps($('#route_form_id').val()); }
                else { pop_wrong(d.message); }
            },
            error: function(){ pop_wrong("Could not move the step"); }
        });
    });

    $('#save_form_setting').on('click', function(){
        var form_id = $('#route_form_id').val();
        if(!form_id){ pop_wrong("Choose a form first"); return; }
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: { action: 'save_form_setting', form_id: form_id,
                    completion_document: $('#set_completion_document').val(),
                    signatory_name: $('#set_signatory_name').val(),
                    signatory_title: $('#set_signatory_title').val() },
            success: function(d){ if(d.status == 200){ pop_up_success(d.message); } else { pop_wrong(d.message); } },
            error: function(){ pop_wrong("Could not save settings"); }
        });
    });

    $(document).on('click', '.save_caps', function(){
        var $row = $(this).closest('tr');
        var role_id = $row.data('role-id');

        var payload = { action: 'save_role_caps', role_id: role_id, scope_level: $row.find('.cap_scope').val() };
        $row.find('input.cap').each(function(){
            payload[$(this).data('cap')] = $(this).is(':checked') ? '1' : '0';
        });

        var $btn = $(this);
        $btn.attr('disabled', true);
        $.ajax({
            url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
            data: payload,
            success: function(d){
                $btn.attr('disabled', false);
                if(d.status == 200){ pop_up_success(d.message); }
                else { pop_wrong(d.message); }
            },
            error: function(){ $btn.attr('disabled', false); pop_wrong("Could not save permissions"); }
        });
    });
});

function loadRoles(){
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_roles' },
        success: function(d){
            if(d.status != 200){ pop_wrong(d.message || "Could not load roles"); return; }
            ALL_ROLES = d.data;

            // role picker for the route builder
            var opts = "<option value=''>select role</option>";
            $.each(d.data, function(i, r){
                opts += "<option value='"+r.role_id+"'>"+r.role+"</option>";
            });
            $('#new_role_id').html(opts);

            // permissions grid
            if(!d.data.length){
                $('#roles_body').html('<tr><td colspan="10" class="text-center text-muted">No roles found.</td></tr>');
                return;
            }

            var caps = ['can_review_applications','can_select_choice','can_assign_courses','can_final_approve',
                        'can_invoice','can_reopen_rejected','can_manage_setup'];
            var scopes = ['department','faculty','campus','university'];
            var html = '';

            $.each(d.data, function(i, r){
                html += '<tr data-role-id="'+r.role_id+'">';
                html += '<td><b>'+r.role+'</b></td>';
                $.each(caps, function(j, c){
                    html += '<td class="text-center"><input type="checkbox" class="cap" data-cap="'+c+'"'
                          + (r[c] == 1 ? ' checked' : '')
                          + (CAN_MANAGE ? '' : ' disabled') + '></td>';
                });
                html += '<td><select class="form-control form-control-sm cap_scope"'+(CAN_MANAGE?'':' disabled')+'>';
                $.each(scopes, function(j, s){
                    html += '<option value="'+s+'"'+(r.scope_level === s ? ' selected' : '')+'>'+s+'</option>';
                });
                html += '</select></td>';
                html += '<td class="text-center">'
                      + (CAN_MANAGE ? '<button type="button" class="btn btn-primary btn-sm save_caps">Save</button>' : '&mdash;')
                      + '</td>';
                html += '</tr>';
            });
            $('#roles_body').html(html);
        },
        error: function(){ pop_wrong("Could not load roles"); }
    });
}

function loadFormSetting(form_id){
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_form_setting', form_id: form_id },
        success: function(d){
            if(d.status != 200){ return; }
            $('#set_completion_document').val(d.data.completion_document || 'none');
            $('#set_signatory_name').val(d.data.signatory_name || '');
            $('#set_signatory_title').val(d.data.signatory_title || '');
        }
    });
}

function loadSteps(form_id){
    $('#steps_body').html('<tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>');
    $.ajax({
        url: "../new_files/Approval_Setup/controller.php", type: "POST", dataType: "JSON",
        data: { action: 'load_steps', form_id: form_id },
        success: function(d){
            if(d.status != 200){ pop_wrong(d.message || "Could not load the route"); return; }
            if(!d.data.length){
                $('#steps_body').html('<tr><td colspan="6" class="text-center text-muted">'
                    + 'No steps yet &mdash; this form has no approval stage.</td></tr>');
                return;
            }

            var html = '';
            $.each(d.data, function(i, s){
                var last = (i === d.data.length - 1);
                html += '<tr>';
                html += '<td class="text-center"><span class="badge badge-primary">'+s.step_order+'</span></td>';
                html += '<td>'+(s.role_name || '<i class="text-danger">role deleted</i>')+'</td>';
                html += '<td>'+s.step_name+'</td>';
                html += '<td>'+(s.step_type === 'invoice'
                        ? '<span class="badge badge-warning">invoice</span>'
                        : '<span class="badge badge-light">approval</span>')+'</td>';
                html += '<td class="text-center">';
                if(CAN_MANAGE){
                    html += '<button type="button" class="btn btn-icon btn-light btn-sm move_step" data-id="'+s.workflow_id+'" data-dir="up"'+(i===0?' disabled':'')+'><i class="fas fa-arrow-up"></i></button> ';
                    html += '<button type="button" class="btn btn-icon btn-light btn-sm move_step" data-id="'+s.workflow_id+'" data-dir="down"'+(last?' disabled':'')+'><i class="fas fa-arrow-down"></i></button>';
                } else { html += '&mdash;'; }
                html += '</td>';
                html += '<td class="text-center">'
                      + (CAN_MANAGE ? '<button type="button" class="btn btn-icon btn-danger btn-sm del_step" data-id="'+s.workflow_id+'"><i class="fas fa-trash"></i></button>' : '&mdash;')
                      + '</td>';
                html += '</tr>';
            });
            $('#steps_body').html(html);
        },
        error: function(){ $('#steps_body').html('<tr><td colspan="6" class="text-center text-danger">Could not load the route.</td></tr>'); }
    });
}

function pop_wrong(feedback) {
    iziToast.warning({ title: 'Info', message: feedback, position: 'topCenter' });
}
function pop_up_success(feedback) {
    iziToast.success({ title: 'Info', message: feedback, position: 'topCenter' });
}
</script>
