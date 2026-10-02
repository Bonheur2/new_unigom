<?php
// Include database connection
require_once '../../meet/con.php';

// Check if campuses are provided
if(isset($_POST['campuses']) && !empty($_POST['campuses'])) {
    $campuses = $_POST['campuses'];
    
    // Prepare placeholders for the IN clause
    $placeholders = str_repeat('?,', count($campuses) - 1) . '?';
    
    // Query to get staff from selected campuses
    $query = $conn->prepare("SELECT s.staff_id, s.family_name, s.middleName, s.first_name, s.gender, 
                            s.phone, s.email, c.camp_full_name 
                            FROM tbl_staff_info s
                            JOIN tbl_campus c ON s.campus = c.camp_id
                            WHERE s.campus IN ($placeholders)
                            ORDER BY c.camp_full_name, s.family_name");
    
    // Execute query with campus IDs
    $query->execute($campuses);
    
    // Start building the response HTML
    $output = '<div class="table-responsive">
                <table class="table table-hover table-sm" id="selectedCampusesTable">
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
                    <tbody>';
    
    $i = 1;
    while($row = $query->fetch()) {
        $output .= '<tr>
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
    
    $output .= '</tbody>
                </table>
                </div>';
    
    echo $output;
} else {
    echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No campuses selected.</div>';
}
?>