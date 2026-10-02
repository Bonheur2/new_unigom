<?php

include ('../../meet/con.php');

$connection = $conn;

class SpecialInvoice {
    private $connect;

    public function __construct() {
        global $connection;
        $this->connect = $connection;
    }

    function sendFeedback($s, $m) {
        header('Content-Type: application/json');
        echo json_encode(["status" => $s, "message" => $m]);
    }

    // Save new special fee category
    function save() {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $has_known_price = (isset($_POST['amount']) && $_POST['amount'] !== '') ? 1 : 0;
        $amount = $has_known_price ? $_POST['amount'] : null;

        if (empty($name)) {
            $this->sendFeedback(401, "Name is required!");
            return;
        }

        // Check duplicate
        $check = $this->connect->prepare("SELECT sp_inv_id FROM tbl_special_invoice WHERE name = :name LIMIT 1");
        $check->execute([':name' => $name]);
        if ($check->fetch()) {
            $this->sendFeedback(401, "This special fee category name already exists!");
            return;
        }

        $stmt = $this->connect->prepare("INSERT INTO tbl_special_invoice (name, description, has_known_price, amount, status) VALUES (:name, :description, :has_known_price, :amount, 1)");
        $stmt->execute([':name' => $name, ':description' => $description, ':has_known_price' => $has_known_price, ':amount' => $amount]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Special fee category saved successfully!");
        } else {
            $this->sendFeedback(500, "Failed to save special fee category!");
        }
    }

    // View single record
    function view() {
        $id = $_POST['sp_inv_id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_special_invoice WHERE sp_inv_id = :id LIMIT 1");
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
        $id = $_POST['sp_inv_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $has_known_price = (isset($_POST['amount']) && $_POST['amount'] !== '') ? 1 : 0;
        $amount = $has_known_price ? $_POST['amount'] : null;

        if (empty($name)) {
            $this->sendFeedback(401, "Name is required!");
            return;
        }

        // Check duplicate excluding self
        $check = $this->connect->prepare("SELECT sp_inv_id FROM tbl_special_invoice WHERE name = :name AND sp_inv_id != :id LIMIT 1");
        $check->execute([':name' => $name, ':id' => $id]);
        if ($check->fetch()) {
            $this->sendFeedback(401, "This special fee category name already exists!");
            return;
        }

        $stmt = $this->connect->prepare("UPDATE tbl_special_invoice SET name = :name, description = :description, has_known_price = :has_known_price, amount = :amount WHERE sp_inv_id = :id");
        $stmt->execute([':name' => $name, ':description' => $description, ':has_known_price' => $has_known_price, ':amount' => $amount, ':id' => $id]);

        if ($stmt->rowCount() > 0) {
            $this->sendFeedback(200, "Special fee category updated successfully!");
        } else {
            $this->sendFeedback(401, "No changes were made!");
        }
    }

    // Soft delete (toggle status)
    function delete() {
        $id = $_POST['sp_inv_id'];

        $stmt = $this->connect->prepare("SELECT status FROM tbl_special_invoice WHERE sp_inv_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $newStatus = ($row['status'] == 1) ? 2 : 1;
            $update = $this->connect->prepare("UPDATE tbl_special_invoice SET status = :status WHERE sp_inv_id = :id");
            $update->execute([':status' => $newStatus, ':id' => $id]);
            $this->sendFeedback(200, "Status updated successfully!");
        } else {
            $this->sendFeedback(401, "Record not found!");
        }
    }
}

$specialInvoice = new SpecialInvoice();
$action = $_POST['action'];
switch ($action) {
    case 'save':
        $specialInvoice->save();
        break;
    case 'view':
        $specialInvoice->view();
        break;
    case 'update':
        $specialInvoice->update();
        break;
    case 'delete':
        $specialInvoice->delete();
        break;
}

?>