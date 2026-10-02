<?php
// Set headers for Excel download
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff Report by Selected Schools.xls");

// Include database connection
require_once '../../meet/con.php';

// Check if schools parameter is set
if(isset($_GET['schools']) && !empty($_GET['schools'])) {
    $schoolIds = explode(',', $_GET['schools']);
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($schoolIds), '?'));
    
    // Query to get staff from selected schools
    $query = $conn->prepare("SELECT s.staff_id, s.family_name, s.middleName, s.first_name, s.gender, 
                            s.phone, s.email, f.fac_full_name 
                            FROM tbl_staff_info s
                            JOIN tbl_faculty f ON s.school = f.fac_id
                            WHERE s.school IN ($placeholders)
                            ORDER BY f.fac_full_name, s.family_name");
    
    // Bind parameters
    foreach ($schoolIds as $key => $schoolId) {
        $query->bindValue($key + 1, $schoolId);
    }
    
    $query->execute();
    
    // Get school names for header
    $schoolQuery = $conn->prepare("SELECT fac_full_name FROM tbl_faculty WHERE fac_id IN ($placeholders)");
    foreach ($schoolIds as $key => $schoolId) {
        $schoolQuery->bindValue($key + 1, $schoolId);
    }
    $schoolQuery->execute();
    
    $schoolNames = [];
    while($school = $schoolQuery->fetch()) {
        $schoolNames[] = $school['fac_full_name'];
    }
    
    $schoolNamesList = implode(', ', $schoolNames);
    
    // Output Excel content
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Staff Report by Selected Schools</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            
            th, td {
                text-align: left;
                padding: 8px;
                border: 1px solid #ddd;
            }
            
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            
            th {
                background-color: #4CAF50;
                color: white;
            }
            
            .report-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            .report-subtitle {
                font-size: 14px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="report-title">Staff Report for Selected Schools</div>
        <div class="report-subtitle">Schools: ' . $schoolNamesList . '</div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Staff ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>School</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>';
    
    $i = 1;
    while($row = $query->fetch()) {
        echo '<tr>
                <td>' . $i++ . '</td>
                <td>' . $row['staff_id'] . '</td>
                <td>' . $row['first_name'] . '</td>
                <td>' . $row['middleName'] . '</td>
                <td>' . $row['family_name'] . '</td>
                <td>' . $row['fac_full_name'] . '</td>
                <td>' . $row['gender'] . '</td>
                <td>' . $row['phone'] . '</td>
                <td>' . $row['email'] . '</td>
            </tr>';
    }
    
    echo '</tbody>
        </table>
        <div style="margin-top: 20px; font-size: 12px;">
            <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        </div>
    </body>
    </html>';
} else {
    echo '<div style="text-align: center; margin-top: 50px;">
            <h3>Error: No schools were selected</h3>
            <p>Please go back and select at least one school to generate the report.</p>
          </div>';
}
?>