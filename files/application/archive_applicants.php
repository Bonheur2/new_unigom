<?php
include ('../../meet/con.php'); // Adjust path as needed

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['codes']) && is_array($_POST['codes'])) {
    try {
        $codes = array_values(array_filter($_POST['codes'], static function ($code) {
            return is_string($code) || is_numeric($code);
        }));

        if (empty($codes)) {
            echo json_encode([
                'success' => false,
                'message' => 'No valid applicants selected'
            ]);
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($codes), '?'));
        $stmt = $conn->prepare("UPDATE tbl_applicants SET submitted = 10 WHERE code IN ($placeholders)");

        foreach ($codes as $index => $code) {
            $stmt->bindValue($index + 1, $code);
        }

        if ($stmt->execute()) {
            $affected = $stmt->rowCount();
            echo json_encode([
                'success' => true,
                'message' => "$affected applicant(s) archived successfully"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to archive applicants'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No applicants selected or invalid request'
    ]);
}
?>