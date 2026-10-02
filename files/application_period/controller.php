<?php
include ('../../meet/con.php');

// Initialize variables
$academic_years = [];
$application_periods = [];
$camp_id = isset($_SESSION['camp_id']) ? $_SESSION['camp_id'] : 1; 

// Fetch academic years for dropdown
try {
    $stmt = $conn->query("SELECT `acad_cycle_id`, `acad_year`, `enrollment`, `status` FROM `tbl_acad_cycle` WHERE 1 ORDER BY `acad_year` DESC");
    $academic_years = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Error fetching academic years: " . $e->getMessage());
}

// Fetch application periods with academic year info
try {
    $stmt = $conn->query("
        SELECT ap.*, ac.acad_year, ac.enrollment 
        FROM tbl_application_periods ap 
        LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
        ORDER BY ap.created_at DESC
    ");
    $application_periods = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Table might not exist yet, we'll create it
    createApplicationPeriodsTable($conn);
    $application_periods = [];
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'create_application_period':
            echo json_encode(createApplicationPeriod($conn, $_POST));
            exit;
            
        case 'update_application_period':
            echo json_encode(updateApplicationPeriod($conn, $_POST));
            exit;
            
        case 'delete_application_period':
            echo json_encode(deleteApplicationPeriod($conn, $_POST['period_id']));
            exit;
            
        case 'get_period':
            echo json_encode(getApplicationPeriod($conn, $_POST['period_id']));
            exit;
            
        case 'get_academic_years':
            echo json_encode(getAcademicYears($conn));
            exit;
            
        case 'get_application_periods':
            echo json_encode(getApplicationPeriods($conn));
            exit;
    }
}

function createApplicationPeriodsTable($conn) {
    try {
        $sql = "
        CREATE TABLE IF NOT EXISTS `tbl_application_periods` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `acad_cycle_id` int(11) NOT NULL,
            `period_name` varchar(255) NOT NULL,
            `start_date` date NOT NULL,
            `end_date` date NOT NULL,
            `status` enum('active','inactive','closed') DEFAULT 'active',
            `description` text,
            `campus_id` int(11) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_acad_cycle` (`acad_cycle_id`),
            KEY `idx_status` (`status`),
            KEY `idx_campus` (`campus_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $conn->exec($sql);
        return true;
    } catch(PDOException $e) {
        error_log("Error creating table: " . $e->getMessage());
        return false;
    }
}

function createApplicationPeriod($conn, $data) {
    try {
        // Validate required fields
        $required_fields = ['acad_year', 'period_name', 'start_date', 'end_date', 'status'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "Field '$field' is required"];
            }
        }
        
        // Validate dates
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        
        if ($start_date >= $end_date) {
            return ['success' => false, 'message' => 'End date must be after start date'];
        }
        
        // Check for overlapping periods with the same academic year
        $stmt = $conn->prepare("
            SELECT COUNT(*) FROM tbl_application_periods 
            WHERE acad_cycle_id = ? 
            AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?))
        ");
        $stmt->execute([
            $data['acad_year'],
            $data['start_date'], $data['start_date'],
            $data['end_date'], $data['end_date']
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'This period overlaps with an existing application period'];
        }
        
        // Insert new application period
        $stmt = $conn->prepare("
            INSERT INTO tbl_application_periods 
            (acad_cycle_id, period_name, start_date, end_date, status, description, campus_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['acad_year'],
            $data['period_name'],
            $data['start_date'],
            $data['end_date'],
            $data['status'],
            $data['description'] ?? '',
            $data['campus'] ?? null
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Application period created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create application period'];
        }
        
    } catch(PDOException $e) {
        error_log("Error creating application period: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function updateApplicationPeriod($conn, $data) {
    try {
        // Validate required fields
        $required_fields = ['period_id', 'acad_year', 'period_name', 'start_date', 'end_date', 'status'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "Field '$field' is required"];
            }
        }
        
        // Validate dates
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        
        if ($start_date >= $end_date) {
            return ['success' => false, 'message' => 'End date must be after start date'];
        }
        
        // Check for overlapping periods (excluding current period)
        $stmt = $conn->prepare("
            SELECT COUNT(*) FROM tbl_application_periods 
            WHERE acad_cycle_id = ? AND id != ?
            AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?))
        ");
        $stmt->execute([
            $data['acad_year'], $data['period_id'],
            $data['start_date'], $data['start_date'],
            $data['end_date'], $data['end_date']
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'This period overlaps with an existing application period'];
        }
        
        // Update application period
        $stmt = $conn->prepare("
            UPDATE tbl_application_periods 
            SET acad_cycle_id = ?, period_name = ?, start_date = ?, end_date = ?, 
                status = ?, description = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $data['acad_year'],
            $data['period_name'],
            $data['start_date'],
            $data['end_date'],
            $data['status'],
            $data['description'] ?? '',
            $data['period_id']
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Application period updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update application period'];
        }
        
    } catch(PDOException $e) {
        error_log("Error updating application period: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteApplicationPeriod($conn, $period_id) {
    try {
        if (empty($period_id)) {
            return ['success' => false, 'message' => 'Period ID is required'];
        }
        
        // Check if period exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_application_periods WHERE id = ?");
        $stmt->execute([$period_id]);
        
        if ($stmt->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Application period not found'];
        }
        
        // Delete the application period
        $stmt = $conn->prepare("DELETE FROM tbl_application_periods WHERE id = ?");
        $result = $stmt->execute([$period_id]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Application period deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete application period'];
        }
        
    } catch(PDOException $e) {
        error_log("Error deleting application period: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function getApplicationPeriod($conn, $period_id) {
    try {
        if (empty($period_id)) {
            return ['success' => false, 'message' => 'Period ID is required'];
        }
        
        $stmt = $conn->prepare("
            SELECT ap.*, ac.acad_year, ac.enrollment 
            FROM tbl_application_periods ap 
            LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
            WHERE ap.id = ?
        ");
        $stmt->execute([$period_id]);
        $period = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($period) {
            return ['success' => true, 'data' => $period];
        } else {
            return ['success' => false, 'message' => 'Application period not found'];
        }
        
    } catch(PDOException $e) {
        error_log("Error fetching application period: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

// Helper function to get active academic year
function getActiveAcademicYear($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status = '1' LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching active academic year: " . $e->getMessage());
        return false;
    }
}

// Helper function to check if application period is currently active
function isApplicationPeriodActive($start_date, $end_date, $status) {
    $current_date = new DateTime();
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    return ($status === 'active' && $current_date >= $start && $current_date <= $end);
}

// Function to get academic years for AJAX
function getAcademicYears($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM tbl_acad_cycle WHERE status = '1' LIMIT 1");
        $academic_years = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'data' => $academic_years];
    } catch(PDOException $e) {
        error_log("Error fetching academic years: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error fetching academic years'];
    }
}

// Function to get application periods for AJAX
function getApplicationPeriods($conn) {
    try {
        $stmt = $conn->query("
            SELECT ap.*, ac.acad_year, ac.enrollment 
            FROM tbl_application_periods ap 
            LEFT JOIN tbl_acad_cycle ac ON ap.acad_cycle_id = ac.acad_cycle_id 
            ORDER BY ap.created_at DESC
        ");
        $application_periods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'data' => $application_periods];
    } catch(PDOException $e) {
        error_log("Error fetching application periods: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error fetching application periods', 'data' => []];
    }
}
?>
