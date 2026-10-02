<?php
include ('../../../meet/con.php');
header('Content-Type: application/json');

if (isset($_POST['campus_id']) && !empty($_POST['campus_id'])) {
    $campusId = $_POST['campus_id'];
    
    try {
        $sql = $conn->prepare("SELECT tbl_faculty.* FROM tbl_faculty
        JOIN tbl_program_type ON tbl_program_type.prg_type_id = tbl_faculty. prg_type
        WHERE tbl_program_type.campus_id = :campus_id");
        $sql->bindParam(':campus_id', $campusId);
        $sql->execute();
        
        $schools = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($schools);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>