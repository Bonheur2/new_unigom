<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Staff Card Report</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item">Staff Card Report</div>
            </div>
        </div>
        <div class="section-body">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-filter"></i> Filter Options</h4>
                        </div>
                        <div class="card-body">
                            <form id="reportForm" method="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="year">Select Year:</label>
                                            <select class="form-control" id="year" name="year" required>
                                                <option value="">-- Select Year --</option>
                                                <!-- Years will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label><br>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Generate Report
                                            </button>
                                            
                                            <button type="button" class="btn btn-success" id="downloadExcel">
                                                <i class="fas fa-download"></i> Download Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Results Section -->
            <div class="row" id="reportResults" style="display: none;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-bar"></i> Staff Card Report</h4>
                            <div class="card-header-action">
                                <span class="badge badge-primary" id="totalCards">Total Cards: 0</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="staffCardTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Staff ID</th>
                                            <th>Full Name</th>
                                            <th>Gender</th>
                                            <th>Position</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Print Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTableBody">
                                        <!-- Data will be populated here -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
// Configuration
const CONTROLLER_URL = '/files/Card/staff/controller.php'; // Updated to relative path

$(document).ready(function() {
    // Load available years on page load
    loadAvailableYears();
    
    // Handle form submission
    $('#reportForm').on('submit', function(e) {
        e.preventDefault();
        generateReport();
    });
    
    // Print Report
    $('#printReport').on('click', function() {
        printReport();
    });
    
    // Download Excel
    $('#downloadExcel').on('click', function() {
        downloadExcel();
    });
    
    // Initialize empty DataTable to prevent errors
    initializeEmptyTable();
});

/**
 * Initialize empty table structure
 */
function initializeEmptyTable() {
    $('#reportTableBody').html('<tr><td colspan="8" class="text-center text-muted">Select a year and click "Generate Report" to view data</td></tr>');
}

/**
 * Load available years from the database
 */
function loadAvailableYears() {
    $.ajax({
        url: CONTROLLER_URL,
        method: 'GET',
        data: { action: 'getAvailableYears' },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const yearSelect = $('#year');
                yearSelect.find('option:not(:first)').remove(); // Keep the default option
                
                response.data.forEach(function(year) {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                });
            } else {
                // Fallback to current year range if no data
                const currentYear = new Date().getFullYear();
                const yearSelect = $('#year');
                for(let i = currentYear; i >= 2020; i--) {
                    yearSelect.append(`<option value="${i}">${i}</option>`);
                }
            }
        },
        error: function() {
            console.error('Failed to load available years');
            // Fallback to current year range
            const currentYear = new Date().getFullYear();
            const yearSelect = $('#year');
            for(let i = currentYear; i >= 2020; i--) {
                yearSelect.append(`<option value="${i}">${i}</option>`);
            }
        }
    });
}

/**
 * Generate the staff card report
 */
function generateReport() {
    const year = $('#year').val();
    if (!year) {
        showAlert('Please select a year', 'warning');
        return;
    }
    
    // Destroy existing DataTable before loading new data
    if ($.fn.DataTable.isDataTable('#staffCardTable')) {
        $('#staffCardTable').DataTable().destroy();
    }
    
    // Show loading with proper structure
    $('#reportTableBody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
    $('#reportResults').show();
    
    // Make AJAX request to controller
    $.ajax({
        url: CONTROLLER_URL,
        method: 'POST',
        data: {
            action: 'getReport',
            year: year
        },
        dataType: 'json',
        timeout: 30000, // 30 second timeout
        success: function(response) {
            if (response.success) {
                displayReport(response.data);
                showAlert(`Report generated successfully. Found ${response.data.length} records.`, 'success');
            } else {
                showAlert('Error: ' + (response.error || 'Unknown error occurred'), 'danger');
                $('#reportResults').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {xhr, status, error});
            let errorMessage = 'Failed to fetch report data. ';
            
            if (status === 'timeout') {
                errorMessage += 'Request timed out. Please try again.';
            } else if (xhr.status === 404) {
                errorMessage += 'Controller not found. Please check the file path.';
            } else if (xhr.status === 500) {
                errorMessage += 'Server error. Please check the controller for errors.';
            } else {
                errorMessage += 'Please try again.';
            }
            
            showAlert('Error: ' + errorMessage, 'danger');
            $('#reportResults').hide();
        }
    });
}

/**
 * Display report data in the table
 */
function displayReport(data) {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#staffCardTable')) {
        $('#staffCardTable').DataTable().destroy();
    }
    
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="8" class="text-center">No staff cards found for the selected year</td></tr>';
    } else {
        data.forEach(function(item, index) {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.staff_id || 'N/A'}</td>
                    <td>${item.full_name || 'N/A'}</td>
                    <td>${item.gender || 'N/A'}</td>
                    <td>${item.position_name || 'N/A'}</td>
                    <td>${item.phone || 'N/A'}</td>
                    <td>${item.email || 'N/A'}</td>
                    <td>${formatDate(item.print_date)}</td>
                </tr>
            `;
        });
    }
    
    // Clear and populate table body
    $('#reportTableBody').empty().html(html);
    $('#totalCards').text(`Total Cards: ${data.length}`);
    
    // Initialize DataTable only if there's data
    if (data.length > 0) {
        try {
            $('#staffCardTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                columnDefs: [
                    { className: "text-center", targets: [0, 3, 7] } // Center align specific columns
                ],
                language: {
                    search: "Search records:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });
        } catch (error) {
            console.error('DataTable initialization error:', error);
            showAlert('Table loaded successfully but advanced features may not be available.', 'warning');
        }
    }
}

/**
 * Print report in new window
 */
function printReport() {
    const year = $('#year').val();
    if (!year) {
        showAlert('Please select a year first', 'warning');
        return;
    }
    
    // Open print page in new window
    const printUrl = `print_staff_cards.php?year=${year}`;
    window.open(printUrl, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
}

/**
 * Download Excel file
 */
function downloadExcel() {
    const year = $('#year').val();
    if (!year) {
        showAlert('Please select a year first', 'warning');
        return;
    }
    
    // Check if we have data in the table
    const tableBody = $('#reportTableBody');
    const rows = tableBody.find('tr');
    
    if (rows.length === 0 || rows.first().find('td').length === 1) {
        showAlert('Please generate a report first', 'warning');
        return;
    }
    
    try {
        // Create Excel data with header
        const ws_data = [
            ['Njala University PRINTED STAFF CARD'], // Main header
            [], // Empty row
            ['Staff ID', 'Full Name', 'Gender', 'Position', 'Phone', 'Email', 'Print Date'] // Column headers
        ];
        
        // Add data rows
        rows.each(function(index) {
            const cells = $(this).find('td');
            if (cells.length > 1) { // Skip empty rows
                const rowData = [];
                cells.each(function(cellIndex) {
                    if (cellIndex > 0) { // Skip the # column
                        rowData.push($(this).text().trim());
                    }
                });
                ws_data.push(rowData);
            }
        });
        
        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        
        // Style the header
        if (ws['A1']) {
            ws['A1'].s = {
                font: { bold: true, sz: 16, color: { rgb: "FFFFFF" } },
                fill: { fgColor: { rgb: "1F4E79" } },
                alignment: { horizontal: "center", vertical: "center" }
            };
        }
        
        // Merge cells for main header (A1:G1)
        if (!ws['!merges']) ws['!merges'] = [];
        ws['!merges'].push({ s: { r: 0, c: 0 }, e: { r: 0, c: 6 } });
        
        // Style column headers (row 3)
        const headerCells = ['A3', 'B3', 'C3', 'D3', 'E3', 'F3', 'G3'];
        headerCells.forEach(function(cell) {
            if (ws[cell]) {
                ws[cell].s = {
                    font: { bold: true, color: { rgb: "FFFFFF" } },
                    fill: { fgColor: { rgb: "4472C4" } },
                    alignment: { horizontal: "center", vertical: "center" },
                    border: {
                        top: { style: "thin", color: { rgb: "000000" } },
                        bottom: { style: "thin", color: { rgb: "000000" } },
                        left: { style: "thin", color: { rgb: "000000" } },
                        right: { style: "thin", color: { rgb: "000000" } }
                    }
                };
            }
        });
        
        // Set column widths for auto-fit
        ws['!cols'] = [
            { wch: 15 }, // Staff ID
            { wch: 25 }, // Full Name
            { wch: 10 }, // Gender
            { wch: 20 }, // Position
            { wch: 15 }, // Phone
            { wch: 30 }, // Email
            { wch: 12 }  // Print Date
        ];
        
        // Add borders to all data cells
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let R = 3; R <= range.e.r; ++R) {
            for (let C = range.s.c; C <= range.e.c; ++C) {
                const cell_address = XLSX.utils.encode_cell({ r: R, c: C });
                if (!ws[cell_address]) continue;
                
                if (!ws[cell_address].s) ws[cell_address].s = {};
                ws[cell_address].s.border = {
                    top: { style: "thin", color: { rgb: "000000" } },
                    bottom: { style: "thin", color: { rgb: "000000" } },
                    left: { style: "thin", color: { rgb: "000000" } },
                    right: { style: "thin", color: { rgb: "000000" } }
                };
                
                // Center align Gender and Print Date columns
                if (C === 2 || C === 6) { // Gender and Print Date
                    ws[cell_address].s.alignment = { horizontal: "center", vertical: "center" };
                }
            }
        }
        
        // Create workbook
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Staff Card Report");
        
        // Download file
        XLSX.writeFile(wb, `Njala_University_Staff_Card_Report_${year}.xlsx`);
        showAlert('Excel file downloaded successfully!', 'success');
        
    } catch (error) {
        console.error('Excel download error:', error);
        showAlert('Failed to download Excel file. Please try again.', 'danger');
    }
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    } catch (e) {
        return dateString;
    }
}

/**
 * Show alert messages
 */
function showAlert(message, type = 'info') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the section body
    $('.section-body').prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
