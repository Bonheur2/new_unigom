<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printed Staff Cards Report</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        
        .print-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            margin-top: 5px;
            font-size: 16px;
        }
        
        .print-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .stats-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .print-table th,
        .print-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            font-size: 12px;
        }
        
        .print-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .print-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .print-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .print-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <h1>Printed Staff Cards Report</h1>
            <div class="subtitle">Detailed Staff Card Information</div>
        </div>
        
        <div class="print-buttons no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
        
        <div class="print-info">
            <div>Generated on: <span id="currentDate"></span></div>
            <div>Year: <span id="selectedYear"></span></div>
        </div>
        
        <div class="stats-row" id="statsSection" style="display: none;">
            <div class="stat-item">
                <div class="stat-number" id="totalCards">0</div>
                <div class="stat-label">Total Cards</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="maleCount">0</div>
                <div class="stat-label">Male Staff</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="femaleCount">0</div>
                <div class="stat-label">Female Staff</div>
            </div>
        </div>
        
        <div id="loadingSection" class="loading">
            <i class="fas fa-spinner fa-spin"></i> Loading staff card data...
        </div>
        
        <div id="tableSection" style="display: none;">
            <table class="print-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="12%">Staff ID</th>
                        <th width="20%">Full Name</th>
                        <th width="8%">Gender</th>
                        <th width="18%">Position</th>
                        <th width="12%">Phone</th>
                        <th width="15%">Email</th>
                        <th width="10%">Print Date</th>
                    </tr>
                </thead>
                <tbody id="printTableBody">
                </tbody>
            </table>
        </div>
        
        <div id="noDataSection" class="no-data" style="display: none;">
            <i class="fas fa-exclamation-circle"></i><br>
            No staff cards found for the selected year.
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    const CONTROLLER_URL = '/files/Card/staff/controller.php';
    
    $(document).ready(function() {
        // Set current date
        $('#currentDate').text(new Date().toLocaleDateString());
        
        // Get year from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const year = urlParams.get('year');
        
        if (year) {
            $('#selectedYear').text(year);
            loadPrintData(year);
        } else {
            showError('No year specified');
        }
    });
    
    function loadPrintData(year) {
        $.ajax({
            url: CONTROLLER_URL,
            method: 'POST',
            data: {
                action: 'getReport',
                year: year
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayPrintData(response.data);
                } else {
                    showError('Error: ' + (response.error || 'Unknown error occurred'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {xhr, status, error});
                showError('Failed to load data. Please try again.');
            }
        });
    }
    
    function displayPrintData(data) {
        $('#loadingSection').hide();
        
        if (data.length === 0) {
            $('#noDataSection').show();
            return;
        }
        
        // Calculate statistics
        const maleCount = data.filter(item => item.gender && item.gender.toLowerCase() === 'male').length;
        const femaleCount = data.filter(item => item.gender && item.gender.toLowerCase() === 'female').length;
        
        // Update statistics
        $('#totalCards').text(data.length);
        $('#maleCount').text(maleCount);
        $('#femaleCount').text(femaleCount);
        $('#statsSection').show();
        
        // Build table rows
        let html = '';
        data.forEach(function(item, index) {
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.staff_id || 'N/A'}</td>
                    <td>${item.full_name || 'N/A'}</td>
                    <td class="text-center">${item.gender || 'N/A'}</td>
                    <td>${item.position_name || 'N/A'}</td>
                    <td>${item.phone || 'N/A'}</td>
                    <td>${item.email || 'N/A'}</td>
                    <td class="text-center">${formatDate(item.print_date)}</td>
                </tr>
            `;
        });
        
        $('#printTableBody').html(html);
        $('#tableSection').show();
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString();
        } catch (e) {
            return dateString;
        }
    }
    
    function showError(message) {
        $('#loadingSection').hide();
        $('#noDataSection').html(`
            <i class="fas fa-exclamation-triangle"></i><br>
            ${message}
        `).show();
    }
    </script>
</body>
</html>
