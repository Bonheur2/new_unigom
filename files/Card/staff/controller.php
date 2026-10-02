<?php
// Staff Card Report Controller
// This file handles all backend operations for the staff card report

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
include ('../../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$connection=$conn;
// Staff Card Report Service
class StaffCardReportService {
    private $connect;
    
    public function __construct() {
        global $connection; // Make sure this matches your con.php variable name
        if (!$connection) {
            throw new Exception("Database connection not available");
        }
        $this->connect = $connection;
    }
    
    /**
     * Get staff card report by year
     */
    public function getStaffCardReport($year) {
        try {
            if (!$this->connect) {
                throw new Exception("Database connection is not available");
            }
            
            // Get staff cards printed in the selected year
            $cardQuery = "SELECT `id`, `staff_id`, `print_date` FROM `tbl_staff_card` WHERE YEAR(print_date) = :year";
            $cardStmt = $this->connect->prepare($cardQuery);
            $cardStmt->bindParam(':year', $year, PDO::PARAM_INT);
            $cardStmt->execute();
            $staffCards = $cardStmt->fetchAll(PDO::FETCH_ASSOC);

            
            if (empty($staffCards)) {
                return [
                    'success' => true,
                    'data' => [],
                    'message' => 'No staff cards found for the selected year'
                ];
            }
            
            $reportData = [];
            
            foreach ($staffCards as $card) {
                // Get staff information for each card
                $staffInfo = $this->getStaffInfo($card['staff_id']);
                
                if ($staffInfo) {
                    $fullName = $this->formatFullName($staffInfo);
                    $positionName = $this->getPositionName($staffInfo['Post']);
                    
                    $reportData[] = [
                        'id' => $card['id'],
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $fullName,
                        'first_name' => $staffInfo['first_name'],
                        'middle_name' => $staffInfo['middleName'],
                        'family_name' => $staffInfo['family_name'],
                        'gender' => $staffInfo['gender'],
                        'school' => $staffInfo['school'],
                        'position' => $staffInfo['Post'],
                        'position_name' => $positionName,
                        'phone' => $staffInfo['phone'],
                        'email' => $staffInfo['email'],
                        'nationality' => $staffInfo['nationality'],
                        'campus' => $staffInfo['campus'],
                        'staff_category' => $staffInfo['staffCategory'],
                        'contract' => $staffInfo['contract'],
                        'supervisor' => $staffInfo['supervisor'],
                        'print_date' => $card['print_date']
                    ];
                }
            }
            
            return [
                'success' => true,
                'data' => $reportData,
                'total' => count($reportData)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch report data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get staff information by staff ID
     */
    private function getStaffInfo($staffId) {
        try {
            if (!$this->connect) {
                throw new Exception("Database connection is not available");
            }
            
            $staffQuery = "SELECT `id`, `staff_id`, `family_name`, `middleName`, `first_name`, `gender`, `dob`, `marital_status`, `nationality`, `nid`, `phone`, `email`, `staff_image`, `country`, `province`, `district`, `sector`, `cell`, `village`, `mother_name`, `father_name`, `bank`, `acc_no`, `rssb`, `campus`, `card_no`, `Nassit_Number`, `street`, `supervisor`, `Post`, `probation_years`, `staffCategory`, `contract`, `qualifications`, `deptunt`, `school`, `department` FROM `tbl_staff_info` WHERE `staff_id` = :staff_id";
            
            $staffStmt = $this->connect->prepare($staffQuery);
            $staffStmt->bindParam(':staff_id', $staffId);
            $staffStmt->execute();
            
            return $staffStmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error fetching staff info for ID $staffId: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get detailed staff information for view details
     */
    public function getStaffDetails($staffId) {
        try {
            $staffInfo = $this->getStaffInfo($staffId);
            
            if (!$staffInfo) {
                return [
                    'success' => false,
                    'error' => 'Staff member not found'
                ];
            }
            
            return [
                'success' => true,
                'data' => $staffInfo
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch staff details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get available years from staff cards
     */
    public function getAvailableYears() {
        try {
            if (!$this->connect) {
                throw new Exception("Database connection is not available");
            }
            
            $query = "SELECT DISTINCT YEAR(print_date) AS year 
                     FROM `tbl_staff_card` 
                     WHERE YEAR(print_date) IS NOT NULL 
                     ORDER BY year DESC";
            
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            return [
                'success' => true,
                'data' => array_column($years, 'year')
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch available years: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Format full name from name components
     */
    private function formatFullName($staffInfo) {
        $nameParts = array_filter([
            $staffInfo['first_name'] ?? '',
            $staffInfo['middleName'] ?? '',
            $staffInfo['family_name'] ?? ''
        ]);
        
        return implode(' ', $nameParts);
    }
    
    /**
     * Get summary statistics
     */
    public function getReportSummary($year = null) {
        try {
            if (!$this->connect) {
                throw new Exception("Database connection is not available");
            }
            
            $whereClause = $year ? "WHERE YEAR(print_date) = :year" : "";
            
            $query = "SELECT 
                        COUNT(*) as total_cards,
                        COUNT(DISTINCT staff_id) as unique_staff,
                        MIN(print_date) as earliest_date,
                        MAX(print_date) as latest_date
                     FROM `tbl_staff_card` $whereClause";
            
            $stmt = $this->connect->prepare($query);
            if ($year) {
                $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            }
            $stmt->execute();
            $summary = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $summary
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to fetch summary: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get position name by position ID
     */
    private function getPositionName($postId) {
        try {
            if (empty($postId) || !$this->connect) {
                return null;
            }
            
            $query = "SELECT `staff_post_full_name` FROM `staff_post` WHERE `staff_post_id` = :post_id AND `status` = 1";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':post_id', $postId);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['staff_post_full_name'] : null;
            
        } catch (Exception $e) {
            error_log("Error fetching position name for ID $postId: " . $e->getMessage());
            return null;
        }
    }
}

// Controller to handle requests
class StaffCardReportController {
    private $service;
    
    public function __construct() {
        $this->service = new StaffCardReportService();
    }
    
    public function handleRequest() {
        try {
            // Validate request method
            if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
                throw new Exception('Invalid request method');
            }
            
            $action = $_POST['action'] ?? $_GET['action'] ?? '';
            
            if (empty($action)) {
                throw new Exception('Action parameter is required');
            }
            
            switch ($action) {
                case 'getReport':
                    $this->getReport();
                    break;
                    
                case 'getStaffDetails':
                    $this->getStaffDetails();
                    break;
                    
                case 'getAvailableYears':
                    $this->getAvailableYears();
                    break;
                    
                case 'getSummary':
                    $this->getSummary();
                    break;
                    
                default:
                    echo json_encode([
                        'success' => false,
                        'error' => 'Invalid action specified: ' . $action
                    ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getReport() {
        try {
            $year = $_POST['year'] ?? $_GET['year'] ?? '';
            
            if (empty($year) || !is_numeric($year)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Valid year is required'
                ]);
                return;
            }
            
            // Validate year range
            $currentYear = date('Y');
            if ($year < 2000 || $year > $currentYear) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Year must be between 2000 and ' . $currentYear
                ]);
                return;
            }
            
            $result = $this->service->getStaffCardReport($year);
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to get report: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getStaffDetails() {
        try {
            $staffId = $_POST['staff_id'] ?? $_GET['staff_id'] ?? '';
            
            if (empty($staffId)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Staff ID is required'
                ]);
                return;
            }
            
            $result = $this->service->getStaffDetails($staffId);
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to get staff details: ' . $e->getMessage()
            ]);
        }
    }
      private function getAvailableYears() {
        try {
            $result = $this->service->getAvailableYears();
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to get available years: ' . $e->getMessage()
            ]);
        }
    }

    private function getSummary() {
        try {
            $year = $_POST['year'] ?? $_GET['year'] ?? null;
            
            if ($year !== null && (!is_numeric($year) || $year < 2000 || $year > date('Y'))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Invalid year provided'
                ]);
                return;
            }
            
            $result = $this->service->getReportSummary($year);
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to get summary: ' . $e->getMessage()
            ]);
        }
    }
}

// Initialize and handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new StaffCardReportController();
    $controller->handleRequest();
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
}
?>
