<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Report By Campus</title>
    
    <style>
        .nav-tabs .nav-link.active {
            font-weight: bold;
            background-color: #f8f9fa;
            border-bottom-color: transparent;
        }
        .select2-container {
            width: 100% !important;
        }
        .card {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Staff Report By Campus</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Staff Reports</a></div>
                <div class="breadcrumb-item"><a href="#">By Campus</a></div>
            </div>
        </div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Staff Report By Campus</h4>
                        </div>
                        <div class="card-body">

                            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-campuses-tab" data-toggle="tab" href="#all-campuses" role="tab" aria-controls="all-campuses" aria-selected="true">All Campuses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="select-campuses-tab" data-toggle="tab" href="#select-campuses" role="tab" aria-controls="select-campuses" aria-selected="false">Select Campuses</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="reportTabsContent">
                                <!-- All Campuses Tab -->
                                <div class="tab-pane fade show active" id="all-campuses" role="tabpanel" aria-labelledby="all-campuses-tab">
                                    <div class="row mb-3">
                                        <div class="col-md-9"></div>
                                        <div class="col-md-3 text-right">
                                            <button class="btn btn-primary" id="exportAllCampuses"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;Download Excel</button>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="allCampusesTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Staff ID</th>
                                                    <th>First Name</th>
                                                    <th>Middle Name</th>
                                                    <th>Last Name</th>
                                                    <th>Campus</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Query to get staff from all campuses
                                                $query = $conn->prepare("SELECT s.staff_id, s.family_name, s.middleName, s.first_name, s.gender, 
                                                                        s.phone, s.email, c.camp_full_name 
                                                                        FROM tbl_staff_info s
                                                                        JOIN tbl_campus c ON s.campus = c.camp_id
                                                                        ORDER BY c.camp_full_name, s.family_name");
                                                $query->execute();
                                                $i = 1;
                                                while($row = $query->fetch()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $row['staff_id']; ?></td>
                                                    <td><?php echo $row['first_name']; ?></td>
                                                    <td><?php echo $row['middleName']; ?></td>
                                                    <td><?php echo $row['family_name']; ?></td>
                                                    <td><?php echo $row['camp_full_name']; ?></td>
                                                    <td><?php echo $row['gender']; ?></td>
                                                    <td><?php echo $row['phone']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Select Campuses Tab -->
                                <div class="tab-pane fade" id="select-campuses" role="tabpanel" aria-labelledby="select-campuses-tab">
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Select Campuses</label>
                                                <select class="form-control select2" id="campusSelector" multiple="multiple">
                                                    <?php
                                                    // Query to get all campuses
                                                    $campusesQuery = $conn->prepare("SELECT camp_id, camp_full_name FROM tbl_campus ORDER BY camp_full_name");
                                                    $campusesQuery->execute();
                                                    while($campus = $campusesQuery->fetch()) {
                                                    ?>
                                                    <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
    <div class="col-md-12">
        <div class="form-group mt-4 pt-2 d-flex">
            <button class="btn btn-primary mr-2" id="generateReport">
                <i class="fas fa-search"></i>&nbsp;&nbsp;Generate Report
            </button>
            <button class="btn btn-success" id="exportSelectedCampuses" disabled>
                <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Download Excel
            </button>
        </div>
    </div>
</div>
                                    </div>
                                    
                                    <div id="selectedCampusesReport">
                                        <!-- Report content will be loaded here -->
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Select one or more campuses and click "Generate Report" to view staff data.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Include JS files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable for all campuses table
    $('#allCampusesTable').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10,
        "responsive": true
    });
    
    // Initialize Select2 dropdown
    $('.select2').select2({
        placeholder: "Select campuses",
        allowClear: true
    });
    
    // Export All Campuses report to Excel
    $('#exportAllCampuses').click(function() {
        window.open('/files/Staff_reports/staff_report_by_campus.php?export=excel', '_blank');
    });
    
    // Generate report for selected campuses
    $('#generateReport').click(function() {
        var selectedCampuses = $('#campusSelector').val();
        
        if (selectedCampuses && selectedCampuses.length > 0) {
            $.ajax({
                url: '/files/Staff_reports/get_staff_by_campuses.php',
                type: 'POST',
                data: {campuses: selectedCampuses},
                success: function(response) {
                    $('#selectedCampusesReport').html(response);
                    
                    // Initialize DataTable for the new table
                    $('#selectedCampusesTable').DataTable({
                        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                        "iDisplayLength": 10,
                        "responsive": true
                    });
                    
                    // Enable export button
                    $('#exportSelectedCampuses').prop('disabled', false);
                },
                error: function() {
                    swal("Error", "Failed to generate report. Please try again.", "error");
                }
            });
        } else {
            swal("Warning", "Please select at least one campus.", "warning");
        }
    });
    
    // Export Selected Campuses report to Excel
    $('#exportSelectedCampuses').click(function() {
        var selectedCampuses = $('#campusSelector').val();
        if (selectedCampuses && selectedCampuses.length > 0) {
            var campusIds = selectedCampuses.join(',');
            window.open('/files/Staff_reports/export_selected_campuses.php?campuses=' + campusIds, '_blank');
        }
    });
});
</script>

</body>
</html>