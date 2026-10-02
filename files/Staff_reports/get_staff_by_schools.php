<?php
// Include database connection
require_once '../../meet/con.php';
// Check if schools parameter is set
if(isset($_POST['schools']) && !empty($_POST['schools'])) {
    $schools = $_POST['schools'];
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($schools), '?'));
    
    // Query to get staff from selected schools
    $query = $conn->prepare("SELECT s.staff_id, s.family_name, s.middleName, s.first_name, s.gender, 
                            s.phone, s.email, f.fac_full_name 
                            FROM tbl_staff_info s
                            JOIN tbl_faculty f ON s.school = f.fac_id
                            WHERE s.school IN ($placeholders)
                            ORDER BY f.fac_full_name, s.family_name");
    
    // Bind parameters
    foreach ($schools as $key => $schoolId) {
        $query->bindValue($key + 1, $schoolId);
    }
    
    $query->execute();
    
    // Build the HTML response
    // Count the actual number of columns we're displaying
    $columnCount = 9; // This matches the number of <th> elements
    
    $html = '<div class="table-responsive">
                <table class="table table-hover table-sm" id="selectedSchoolsTable">
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
    $hasRecords = false;
    
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $hasRecords = true;
        $html .= '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . htmlspecialchars($row['staff_id']) . '</td>
                    <td>' . htmlspecialchars($row['first_name']) . '</td>
                    <td>' . htmlspecialchars($row['middleName']) . '</td>
                    <td>' . htmlspecialchars($row['family_name']) . '</td>
                    <td>' . htmlspecialchars($row['fac_full_name']) . '</td>
                    <td>' . htmlspecialchars($row['gender']) . '</td>
                    <td>' . htmlspecialchars($row['phone']) . '</td>
                    <td>' . htmlspecialchars($row['email']) . '</td>
                </tr>';
    }
    
    if(!$hasRecords) {
        // No records found
        $html .= '<tr><td colspan="' . $columnCount . '" class="text-center">No staff records found for selected schools</td></tr>';
    }
    
    $html .= '</tbody>
            </table>
        </div>';
    
    // Add the script after the HTML has been output
    $html .= '<script>
        $(document).ready(function() {
            // First check if the DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable("#selectedSchoolsTable")) {
                $("#selectedSchoolsTable").DataTable().destroy();
            }
            
            // Then initialize a new DataTable
            $("#selectedSchoolsTable").DataTable({
                "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                "iDisplayLength": 10,
                "responsive": true
            });
        });
        </script>';
    
    echo $html;
} else {
    echo '<div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> No schools were selected. Please select at least one school.
          </div>';
}
?>