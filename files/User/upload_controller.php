<?php
include ('../../meet/con.php');
$connection=$conn;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Module{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	
	function upload_csv1() {
    set_time_limit(300); // Set execution time limit to 5 minutes
    $csvData = json_decode($_POST['csv_data'], true);
    $batchSize = 100;
    $insertCount = 0;

    $rows = [];
    $placeholders = [];
    $rows1 = [];
    $placeholders1 = [];
    $rows2 = [];
    $placeholders2 = [];

    try {
        $this->connect->beginTransaction();

        // Fetch university short code
        $sql = $this->connect->prepare("SELECT short_name FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $udata = $sql->fetch();
        $subcode = $udata['short_name'];
        $char = strlen($subcode);

        // Get last staff ID
        $checkcode = $this->connect->prepare("SELECT staff_id FROM tbl_staff WHERE staff_id LIKE ? ORDER BY staff_id DESC LIMIT 1");
        $checkcode->execute([$subcode . '%']);
        $codeData = $checkcode->fetch();

        // If no staff ID exists, start numbering from 1
        $prevSuffix = $codeData ? (int) substr($codeData['staff_id'], $char) : 0;

        foreach ($csvData as $row) {
            $data = str_getcsv($row);

            if (count($data) < 9) {
                continue; // Ensure the row has at least 9 columns
            }

            list($family_name, $first_name, $gender, $campus, $Nassit_Number, $Department, $qualification, $academic, $Post) = $data;

            // Generate new staff ID
            $prevSuffix++;
            $suffix = str_pad($prevSuffix, 4, '0', STR_PAD_LEFT);
            $staff_id = $subcode . $suffix;

            // Check if staff already exists
            $stmt = $this->connect->prepare("SELECT COUNT(*) FROM tbl_staff WHERE family_name = ? AND first_name = ? AND gender = ? AND campus = ? AND Nassit_Number = ?");
            $stmt->execute([$family_name, $first_name, $gender, $campus, $Nassit_Number]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                continue; // Skip if staff already exists
            }

            // Prepare batch insert data
            $rows[] = [$staff_id, $academic, $family_name, $first_name, $Department, $Post, $gender, $Nassit_Number, $campus];
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $rows1[] = [$staff_id, $academic, $Department, $Post];
            $placeholders1[] = "(?, ?, ?, ?)";

            $rows2[] = [$staff_id, $qualification];
            $placeholders2[] = "(?, ?)";

            $insertCount++;

            // Execute batch insert when batch size is reached
            if ($insertCount % $batchSize === 0) {
                $this->executeBatchInsert("INSERT INTO `tbl_staff`(`staff_id`, `is_acadmic`, `staff_family_name`, `staff_first_name`, `Department`, `Post`, `staff_sex`, `nassit_number`, `campus`) VALUES ", $placeholders, $rows);
                $this->executeBatchInsert("INSERT INTO `tbl_staff_post`(`staff_id`, `is_acadmic`, `department`, `post`) VALUES ", $placeholders1, $rows1);
                $this->executeBatchInsert("INSERT INTO `tbl_staff_degree`(`staff_id`, `degree`) VALUES ", $placeholders2, $rows2);

                // Reset batch arrays
                $rows = [];
                $placeholders = [];
                $rows1 = [];
                $placeholders1 = [];
                $rows2 = [];
                $placeholders2 = [];
            }
        }

        // Insert remaining rows
        if (!empty($rows)) {
            $this->executeBatchInsert("INSERT INTO `tbl_staff`(`staff_id`, `is_acadmic`, `staff_family_name`, `staff_first_name`, `Department`, `Post`, `staff_sex`, `nassit_number`, `campus`) VALUES ", $placeholders, $rows);
        }
        if (!empty($rows1)) {
            $this->executeBatchInsert("INSERT INTO `tbl_staff_post`(`staff_id`, `is_acadmic`, `department`, `post`) VALUES ", $placeholders1, $rows1);
        }
        if (!empty($rows2)) {
            $this->executeBatchInsert("INSERT INTO `tbl_staff_degree`(`staff_id`, `degree`) VALUES ", $placeholders2, $rows2);
        }

        $this->connect->commit();
        echo json_encode(['status' => 200, 'message' => 'Data inserted successfully.']);
    } catch (Exception $e) {
        $this->connect->rollBack();
        echo json_encode(['status' => 500, 'message' => 'Error inserting data: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * Execute batch insert query.
 */
private function executeBatchInsert($query, &$placeholders, &$rows) {
    $stmt = $this->connect->prepare($query . implode(", ", $placeholders));
    $stmt->execute(array_merge(...$rows));
}




	
}

$module=new Module();
	    $action = $_POST['action'];
		switch($action){
		    case 'upload_csv_data1':
		        $module->upload_csv1();
		        break;
		}