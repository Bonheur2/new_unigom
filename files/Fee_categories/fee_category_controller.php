<?php

include ('../../meet/con.php');

$connection = $conn;

class FeeCategory {
    private $connect;

    public function __construct() {
        global $connection;
        $this->connect = $connection;
    }

    function sendFeedback($s, $m) {
        header('Content-Type: application/json');
        echo json_encode(["status" => $s, "message" => $m]);
    }

    // ===================== CASCADING DROPDOWN METHODS =====================

    // Get program types by campus
    function getProgramTypes() {
        $camp_id = $_POST['camp_id'];
        $stmt = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE campus_id = :camp_id AND status = '1'");
        $stmt->execute([':camp_id' => $camp_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data]);
    }

    // Get faculties by program type
    function getFaculties() {
        $prg_type = $_POST['prg_type'];
        $stmt = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE prg_type = :prg_type AND status = '1'");
        $stmt->execute([':prg_type' => $prg_type]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data]);
    }

    // Get departments by faculty and program type
    function getDepartments() {
        $fac_id = $_POST['fac_id'];
        $prg_type = $_POST['prg_type'];
        $stmt = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department WHERE fac_id = :fac_id AND prg_type = :prg_type AND status = '1'");
        $stmt->execute([':fac_id' => $fac_id, ':prg_type' => $prg_type]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data]);
    }

    // Get specializations by department and program type
    function getSpecializations() {
        $dept_id = $_POST['dept_id'];
        $prg_type = $_POST['prg_type'];
        $stmt = $this->connect->prepare("SELECT splz_id, splz_full_name FROM tbl_specialization WHERE dept_id = :dept_id AND prg_type = :prg_type AND status = '1'");
        $stmt->execute([':dept_id' => $dept_id, ':prg_type' => $prg_type]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data]);
    }

    // Get levels by program type
    function getLevels() {
        $prg_type = $_POST['prg_type'];
        $stmt = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE prg_type = :prg_type AND status = '1' ORDER BY level_rank ASC");
        $stmt->execute([':prg_type' => $prg_type]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data]);
    }

    // Get a single program type fee record for editing
    function viewProgramTypeFee() {
        $prg_type_id = $_POST['prg_type_id'];
        $stmt = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name, admission_fee, application_fee FROM tbl_program_type WHERE prg_type_id = :prg_type_id LIMIT 1");
        $stmt->execute([':prg_type_id' => $prg_type_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            header('Content-Type: application/json');
            echo json_encode(["status" => 200, "data" => $row]);
        } else {
            $this->sendFeedback(401, "Record not found!");
        }
    }

    // ===================== CRUD METHODS =====================

    // Save new fee category
    function save() {
        $camp_id = $_POST['camp_id'];
        $prg_type_id = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $dept_id = $_POST['dept_id'];
        $splz_id = $_POST['splz_id'];
        $level_id = $_POST['level_id'];
        $name = strtoupper(trim($_POST['name']));
        $amount = $_POST['amount'];

        if (empty($camp_id) || empty($prg_type_id) || empty($fac_id) || empty($dept_id) || empty($splz_id) || empty($level_id) || empty($name) || $amount === '') {
            $this->sendFeedback(401, "All fields are required!");
            return;
        }

        // Check duplicate
        $check = $this->connect->prepare("SELECT id FROM fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id AND UPPER(name) = :name LIMIT 1");
        $check->execute([
            ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
            ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id, ':name' => $name
        ]);
        if ($check->fetch()) {
            $this->sendFeedback(401, "This fee category already exists for the selected combination!");
            return;
        }

        $stmt = $this->connect->prepare("INSERT INTO fee_category (camp_id, prg_type_id, fac_id, dept_id, splz_id, level_id, name, amount, status) VALUES (:camp_id, :prg_type_id, :fac_id, :dept_id, :splz_id, :level_id, :name, :amount, 1)");
        $stmt->execute([
            ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
            ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id,
            ':name' => $name, ':amount' => $amount
        ]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Fee category saved successfully!");
        } else {
            $this->sendFeedback(500, "Failed to save fee category!");
        }
    }

    // View single record
    function view() {
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            header('Content-Type: application/json');
            echo json_encode(["status" => 200, "data" => $row]);
        } else {
            $this->sendFeedback(401, "Record not found!");
        }
    }

    // Update
    function update() {
        $id = $_POST['id'];
        $name = strtoupper(trim($_POST['name']));
        $amount = $_POST['amount'];

        if (empty($name) || $amount === '') {
            $this->sendFeedback(401, "Name and amount are required!");
            return;
        }

        $stmt = $this->connect->prepare("UPDATE fee_category SET name = :name, amount = :amount WHERE id = :id");
        $stmt->execute([':name' => $name, ':amount' => $amount, ':id' => $id]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Fee category updated successfully!");
        } else {
            $this->sendFeedback(401, "No changes were made!");
        }
    }

    // Update admission fee and application fee for a program type
    function updateProgramTypeFees() {
        $prg_type_id = $_POST['prg_type_id'];
        $admission_fee = $_POST['admission_fee'];
        $application_fee = $_POST['application_fee'];

        if ($prg_type_id === '' || $prg_type_id === null || $admission_fee === '' || $application_fee === '') {
            $this->sendFeedback(401, "Program type and both fees are required!");
            return;
        }

        if (!is_numeric($admission_fee) || !is_numeric($application_fee)) {
            $this->sendFeedback(401, "Fees must be numeric values!");
            return;
        }

        $stmt = $this->connect->prepare("UPDATE tbl_program_type SET admission_fee = :admission_fee, application_fee = :application_fee WHERE prg_type_id = :prg_type_id");
        $stmt->execute([
            ':admission_fee' => $admission_fee,
            ':application_fee' => $application_fee,
            ':prg_type_id' => $prg_type_id
        ]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Program type fees updated successfully!");
        } else {
            $this->sendFeedback(401, "No changes were made!");
        }
    }

    // Upload CSV (Name + Amount only, IDs come from POST)
    function uploadCsv() {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->sendFeedback(401, "Please select a valid CSV file!");
            return;
        }

        $camp_id = $_POST['camp_id'];
        $prg_type_id = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $dept_id = $_POST['dept_id'];
        $splz_id = $_POST['splz_id'];
        $level_id = $_POST['level_id'];

        if (empty($camp_id) || empty($prg_type_id) || empty($fac_id) || empty($dept_id) || empty($splz_id) || empty($level_id)) {
            $this->sendFeedback(401, "Please select all dropdowns before uploading!");
            return;
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->sendFeedback(500, "Failed to open file!");
            return;
        }

        // Skip header row
        fgetcsv($handle);

        $inserted = 0;
        $skipped = 0;

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 2) continue;

            $name = strtoupper(trim($data[0]));
            $amount = trim($data[1]);

            if (empty($name) || $amount === '') {
                $skipped++;
                continue;
            }

            // Check duplicate
            $check = $this->connect->prepare("SELECT id FROM fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id AND UPPER(name) = :name LIMIT 1");
            $check->execute([
                ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
                ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id, ':name' => $name
            ]);
            if ($check->fetch()) {
                $skipped++;
                continue;
            }

            $stmt = $this->connect->prepare("INSERT INTO fee_category (camp_id, prg_type_id, fac_id, dept_id, splz_id, level_id, name, amount, status) VALUES (:camp_id, :prg_type_id, :fac_id, :dept_id, :splz_id, :level_id, :name, :amount, 1)");
            $stmt->execute([
                ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
                ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id,
                ':name' => $name, ':amount' => $amount
            ]);
            $inserted++;
        }

        fclose($handle);
        $this->sendFeedback(200, "CSV uploaded! Inserted: $inserted, Skipped (duplicates/invalid): $skipped");
    }

    // Toggle status
    function delete() {
        $id = $_POST['id'];

        $stmt = $this->connect->prepare("SELECT status FROM fee_category WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $newStatus = ($row['status'] == 1) ? 2 : 1;
            $update = $this->connect->prepare("UPDATE fee_category SET status = :status WHERE id = :id");
            $update->execute([':status' => $newStatus, ':id' => $id]);
            $this->sendFeedback(200, "Status updated successfully!");
        } else {
            $this->sendFeedback(401, "Record not found!");
        }
    }

    // ===================== GENERATE FEE METHODS =====================

    // Fetch fee items matching criteria from fee_category
    function fetchFees() {
        $camp_id = $_POST['camp_id'];
        $prg_type_id = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $dept_id = $_POST['dept_id'];
        $splz_id = $_POST['splz_id'];
        $level_id = $_POST['level_id'];

        $stmt = $this->connect->prepare("SELECT id, name, amount FROM fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id AND status = 1");
        $stmt->execute([
            ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
            ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($data as $row) {
            $total += $row['amount'];
        }

        header('Content-Type: application/json');
        echo json_encode(["status" => 200, "data" => $data, "total" => $total]);
    }

    // Create fee in tbl_fee_category
    function createFee() {
        $camp_id = $_POST['camp_id'];
        $prg_type_id = $_POST['prg_type_id'];
        $fac_id = $_POST['fac_id'];
        $dept_id = $_POST['dept_id'];
        $splz_id = $_POST['splz_id'];
        $level_id = $_POST['level_id'];
        $name = strtoupper(trim($_POST['name']));
        $amount = $_POST['amount'];

        if (empty($name) || $amount === '') {
            $this->sendFeedback(401, "Fee name and amount are required!");
            return;
        }

        // Check duplicate
        $check = $this->connect->prepare("SELECT id FROM tbl_fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id LIMIT 1");
        $check->execute([
            ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
            ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id
        ]);
        if ($check->fetch()) {
            $this->sendFeedback(401, "A fee already exists for this combination! Please update the existing one.");
            return;
        }

        $stmt = $this->connect->prepare("INSERT INTO tbl_fee_category (camp_id, prg_type_id, fac_id, dept_id, splz_id, level_id, name, amount, status) VALUES (:camp_id, :prg_type_id, :fac_id, :dept_id, :splz_id, :level_id, :name, :amount, '1')");
        $stmt->execute([
            ':camp_id' => $camp_id, ':prg_type_id' => $prg_type_id, ':fac_id' => $fac_id,
            ':dept_id' => $dept_id, ':splz_id' => $splz_id, ':level_id' => $level_id,
            ':name' => $name, ':amount' => $amount
        ]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Fee created successfully!");
        } else {
            $this->sendFeedback(500, "Failed to create fee!");
        }
    }

    // Toggle status on tbl_fee_category
    function toggleGeneratedFee() {
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT status FROM tbl_fee_category WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $newStatus = ($row['status'] == '1') ? '2' : '1';
            $update = $this->connect->prepare("UPDATE tbl_fee_category SET status = :status WHERE id = :id");
            $update->execute([':status' => $newStatus, ':id' => $id]);
            $this->sendFeedback(200, "Status updated successfully!");
        } else {
            $this->sendFeedback(401, "Record not found!");
        }
    }
}

$feeCategory = new FeeCategory();

$action = $_POST['action'];
switch ($action) {
    case 'save':
        $feeCategory->save();
        break;
    case 'view':
        $feeCategory->view();
        break;
    case 'update':
        $feeCategory->update();
        break;
    case 'viewProgramTypeFee':
        $feeCategory->viewProgramTypeFee();
        break;
    case 'updateProgramTypeFees':
        $feeCategory->updateProgramTypeFees();
        break;
    case 'delete':
        $feeCategory->delete();
        break;
    case 'uploadCsv':
        $feeCategory->uploadCsv();
        break;
    case 'getProgramTypes':
        $feeCategory->getProgramTypes();
        break;
    case 'getFaculties':
        $feeCategory->getFaculties();
        break;
    case 'getDepartments':
        $feeCategory->getDepartments();
        break;
    case 'getSpecializations':
        $feeCategory->getSpecializations();
        break;
    case 'getLevels':
        $feeCategory->getLevels();
        break;
    case 'fetchFees':
        $feeCategory->fetchFees();
        break;
    case 'createFee':
        $feeCategory->createFee();
        break;
    case 'toggleGeneratedFee':
        $feeCategory->toggleGeneratedFee();
        break;
}

?>