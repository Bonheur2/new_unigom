<?php
try {
    include ('../../../meet/con.php');
    $schoolId = isset($_POST['school_id']) ? $_POST['school_id'] : null;
    
    if ($schoolId) {
        $query = "SELECT dept_id, dept_full_name FROM tbl_department WHERE fac_id = :school_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':school_id', $schoolId, PDO::PARAM_INT);
        $stmt->execute();
        
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($departments);
    } else {
        echo json_encode(['error' => 'No school ID provided']);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>