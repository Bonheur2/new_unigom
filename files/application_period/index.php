
<div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Application Period Management</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Application Period List</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <form id="create_application_period"  method="POST">
                                        <input type="hidden" name="campus" id="campus" value="1">
                                        <input type="hidden" name="action" id="form_action" value="create_application_period">
                                        <input type="hidden" name="period_id" id="period_id">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Create Application Period</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body row">
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="acad_year">Academic Year</label>
                                                            <select class="form-control" name="acad_year" id="acad_year" required>
                                                                <option value="">Loading academic years...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="period_name">Period Name</label>
                                                            <input type="text" class="form-control" name="period_name" id="period_name" placeholder="e.g., First Semester 2024-2025" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="start_date">Start Date</label>
                                                            <input type="date" class="form-control" name="start_date" id="start_date" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="end_date">End Date</label>
                                                            <input type="date" class="form-control" name="end_date" id="end_date" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" name="status" id="status" required>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                                <option value="closed">Closed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Optional description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div style="display:flex;flex-direction:row-reverse;" class="col-12">
                                                        <button type="submit" class="btn btn-primary btn-sm" id="cBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Create Period</span></button>
                                                        <button type="button" class="btn btn-secondary btn-sm mr-2" id="resetBtn">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Application Periods List</h4>
                                            <div class="row col-lg-10">
                                            <div class="col-12 col-lg-9"></div>
                                            <div class="col-12 col-lg-3">
                                                <button type="button" class="btn btn-success btn-sm" onclick="refreshPeriods()">
                                                    <i class="fas fa-refresh"></i> Refresh
                                                </button>
                                            </div>
                                         </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="periodsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Period Name</th>
                                                            <th>Academic Year</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="periodsTableBody">
                                                        <tr>
                                                            <td colspan="7" class="text-center">Loading application periods...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
// Notification functions using iziToast
function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Success',
        message: feedback,
        position: 'topCenter'
    });
}

$(document).ready(function() {
    // Load initial data
    loadAcademicYears();
    loadApplicationPeriods();
    
    // Form submission
    $('#create_application_period').on('submit', function(e) {
        e.preventDefault();
        
        // Validate dates
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());
        
        if (startDate >= endDate) {
            pop_wrong('End date must be after start date');
            return;
        }
        
        // Show loading spinner
        $('#spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#indicator').text('Processing...');
        $('#cBtn').prop('disabled', true);
        
        $.ajax({
            url: '../files/application_period/controller.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    pop_up_success(response.message);
                    $('#create_application_period')[0].reset();
                    $('#mycard-collapse').collapse('hide');
                    loadApplicationPeriods(); // Reload periods instead of whole page
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                pop_wrong('An error occurred while processing the request');
            },
            complete: function() {
                $('#spinner').html('');
                $('#indicator').text('Create Period');
                $('#cBtn').prop('disabled', false);
            }
        });
    });
    
    // Reset button
    $('#resetBtn').on('click', function() {
        $('#create_application_period')[0].reset();
        $('#form_action').val('create_application_period');
        $('#period_id').val('');
        $('#indicator').text('Create Period');
    });
});

function editPeriod(id) {
    $.ajax({
        url: '../files/application_period/controller.php',
        type: 'POST',
        data: {
            action: 'get_period',
            period_id: id
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var period = response.data;
                $('#period_id').val(period.id);
                $('#acad_year').val(period.acad_cycle_id);
                $('#period_name').val(period.period_name);
                $('#start_date').val(period.start_date);
                $('#end_date').val(period.end_date);
                $('#status').val(period.status);
                $('#description').val(period.description);
                $('#form_action').val('update_application_period');
                $('#indicator').text('Update Period');
                $('#mycard-collapse').collapse('show');
            } else {
                pop_wrong(response.message);
            }
        },
        error: function() {
            pop_wrong('An error occurred while fetching period data');
        }
    });
}

function deletePeriod(id) {
    if (confirm('Are you sure you want to delete this application period?')) {
        $.ajax({
            url: '../files/application_period/controller.php',
            type: 'POST',
            data: {
                action: 'delete_application_period',
                period_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    pop_up_success(response.message);
                    loadApplicationPeriods(); // Reload periods instead of whole page
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function() {
                pop_wrong('An error occurred while deleting the period');
            }
        });
    }
}

function refreshPeriods() {
    loadApplicationPeriods();
}

// Load academic years
function loadAcademicYears() {
    $.ajax({
        url: '../files/application_period/controller.php',
        type: 'POST',
        data: {
            action: 'get_academic_years'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var options = '<option value="">Select Academic Year</option>';
                $.each(response.data, function(index, year) {
                    var activeLabel = (year.status == '1') ? ' (Active)' : '';
                    options += '<option value="' + year.acad_cycle_id + '">' + 
                                                  year.acad_year + activeLabel + '</option>';
                });
                $('#acad_year').html(options);
            } else {
                $('#acad_year').html('<option value="">Error loading academic years</option>');
                pop_wrong('Failed to load academic years');
            }
        },
        error: function() {
            $('#acad_year').html('<option value="">Error loading academic years</option>');
            pop_wrong('Error loading academic years');
        }
    });
}

// Load application periods
function loadApplicationPeriods() {
    $.ajax({
        url: '../files/application_period/controller.php',
        type: 'POST',
        data: {
            action: 'get_application_periods'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var tbody = '';
                if (response.data && response.data.length > 0) {
                    $.each(response.data, function(index, period) {
                        var statusBadge = '';
                        if (period.status === 'active') {
                            statusBadge = '<span class="badge badge-success">Active</span>';
                        } else if (period.status === 'inactive') {
                            statusBadge = '<span class="badge badge-warning">Inactive</span>';
                        } else {
                            statusBadge = '<span class="badge badge-danger">Closed</span>';
                        }
                        
                        var startDate = new Date(period.start_date).toLocaleDateString('en-US', {
                            year: 'numeric', month: 'short', day: '2-digit'
                        });
                        var endDate = new Date(period.end_date).toLocaleDateString('en-US', {
                            year: 'numeric', month: 'short', day: '2-digit'
                        });
                        
                        tbody += '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + escapeHtml(period.period_name) + '</td>' +
                                '<td>' + escapeHtml(period.acad_year) + '</td>' +
                                '<td>' + startDate + '</td>' +
                                '<td>' + endDate + '</td>' +
                                '<td>' + statusBadge + '</td>' +
                                '<td>' +
                                    '<button type="button" class="btn btn-primary btn-sm" onclick="editPeriod(' + period.id + ')">' +
                                        '<i class="fas fa-edit"></i> Edit' +
                                    '</button> ' +
                                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePeriod(' + period.id + ')">' +
                                        '<i class="fas fa-trash"></i> Delete' +
                                    '</button>' +
                                '</td>' +
                                '</tr>';
                    });
                } else {
                    tbody = '<tr><td colspan="7" class="text-center">No application periods found</td></tr>';
                }
                $('#periodsTableBody').html(tbody);
            } else {
                $('#periodsTableBody').html('<tr><td colspan="7" class="text-center">Error loading application periods</td></tr>');
                pop_wrong('Failed to load application periods');
            }
        },
        error: function() {
            $('#periodsTableBody').html('<tr><td colspan="7" class="text-center">Error loading application periods</td></tr>');
            pop_wrong('Error loading application periods');
        }
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>




