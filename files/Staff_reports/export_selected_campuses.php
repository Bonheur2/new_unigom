<?php
// Check if campuses parameter is set
if(isset($_GET['campuses']) && !empty($_GET['campuses'])) {
    // Set headers for Excel download
    header('Content-type: text/html; charset=utf-8');
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Selected Campus Staff Report.xls");
    
    // Include database connection
    require_once '../../meet/con.php';
    
    // Get campus IDs and convert to array
    $campusIds = explode(',', $_GET['campuses']);
    
    // Prepare placeholders for the IN clause
    $placeholders = str_repeat('?,', count($campusIds) - 1) . '?';
    
    // Query to get staff from selected campuses
    $query = $conn->prepare("SELECT s.staff_id, s.family_name, s.middleName, s.first_name, s.gender, 
                            s.phone, s.email, c.camp_full_name 
                            FROM tbl_staff_info s
                            JOIN tbl_campus c ON s.campus = c.camp_id
                            WHERE s.campus IN ($placeholders)
                            ORDER BY c.camp_full_name, s.family_name");
    
    // Execute query with campus IDs
    $query->execute($campusIds);
    
    // Get campus names for the report title
    $campusNames = [];
    $campusQuery = $conn->prepare("SELECT camp_full_name FROM tbl_campus WHERE camp_id IN ($placeholders)");
    $campusQuery->execute($campusIds);
    while($campus = $campusQuery->fetch()) {
        $campusNames[] = $campus['camp_full_name'];
    }
    
    $campusTitle = implode(', ', $campusNames);
    
    // Output Excel content
    echo '
    <html xmlns:x="urn:schemas-microsoft-com:office:excel">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--[if gte mso 9]>
        <xml>
            <x:ExcelWorkbook>
                <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                        <x:Name>Staff Report</x:Name>
                        <x:WorksheetOptions>
                            <x:Panes>
                            </x:Panes>
                        </x:WorksheetOptions>
                    </x:ExcelWorksheet>
                </x:ExcelWorksheets>
            </x:ExcelWorkbook>
        </xml>
        <![endif]-->
    </head>
    <body>
        <table border="1">
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="9" style="text-align: center; font-size: 16pt;">Staff Report - Selected Campuses: '.$campusTitle.'</td>
            </tr>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td>#</td>
                <td>Staff ID</td>
                <td>First Name</td>
                <td>Middle Name</td>
                <td>Last Name</td>
                <td>Campus</td>
                <td>Gender</td>
                <td>Phone</td>
                <td>Email</td>
            </tr>';
    
    $i = 1;
    while($row = $query->fetch()) {
        echo '<tr>
                <td>'.$i++.'</td>
                <td>'.$row['staff_id'].'</td>
                <td>'.$row['first_name'].'</td>
                <td>'.$row['middleName'].'</td>
                <td>'.$row['family_name'].'</td>
                <td>'.$row['camp_full_name'].'</td>
                <td>'.$row['gender'].'</td>
                <td>'.$row['phone'].'</td>
                <td>'.$row['email'].'</td>
            </tr>';
    }
    
    echo '</table>
    </body>
    </html>';
    
    exit;
} else {
    echo "No campuses selected.";
    exit;
}
?>