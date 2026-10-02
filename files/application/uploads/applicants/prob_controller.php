<?php

include ('../../meet/con.php');
$connection = $conn;

class ProblemController {
    private $connect;

    public function __construct() {
        global $connection;
        $this->connect = $connection;
    }

    // Save or update an answer and mark as solved
    function save_answer() {
        $id     = $_POST['id'];
        $answer = $_POST['answer'];
        $status = $_POST['status']; // always 1 (solved)

        $stmt = $this->connect->prepare(
            "UPDATE tbl_applicant_prob SET problem_answer = ?, status = ? WHERE id = ?"
        );
        if ($stmt->execute([$answer, $status, $id])) {
            $this->respond(200, "Answer saved successfully!");
        } else {
            $this->respond(500, "Failed to save answer.");
        }
    }

    // Re-open a solved problem back to pending
    function reopen_problem() {
        $id = $_POST['id'];
        $stmt = $this->connect->prepare(
            "UPDATE tbl_applicant_prob SET status = 0 WHERE id = ?"
        );
        if ($stmt->execute([$id])) {
            $this->respond(200, "Problem re-opened successfully.");
        } else {
            $this->respond(500, "Failed to re-open problem.");
        }
    }

    private function respond($status, $message) {
        header('Content-Type: application/json');
        echo json_encode(["status" => (string)$status, "message" => $message]);
        exit;
    }
}

$prob   = new ProblemController();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'answer':
        $prob->save_answer();
        break;
    case 'reopen':
        $prob->reopen_problem();
        break;
 
    default:
        header('Content-Type: application/json');
        echo json_encode(["status" => "400", "message" => "Invalid action"]);
        break;
}