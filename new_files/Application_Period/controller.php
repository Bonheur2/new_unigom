<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__, 2));
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class ApplicationPeriod{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function get_acad_year($acad_cycle_id){
        $sql = $this->connect->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id = :id");
        $sql->execute([':id' => $acad_cycle_id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['acad_year'] : '';
    }

    public function save_period(){
        $acad_cycle_id = $_POST['acad_cycle_id'];
        $period_name = trim($_POST['period_name']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $status = $_POST['status'];
        $description = trim($_POST['description']);

        if($acad_cycle_id == '' || $period_name == '' || $start_date == '' || $end_date == ''){
            echo json_encode(['status' => 401, 'message' => 'Academic year, period name, start and end date are required']);
            return;
        }

        if(strtotime($end_date) < strtotime($start_date)){
            echo json_encode(['status' => 401, 'message' => 'End date cannot be before start date']);
            return;
        }

        if(!in_array($status, ['active', 'inactive', 'closed', 'hold'])){
            echo json_encode(['status' => 401, 'message' => 'Invalid status value']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT id FROM tbl_application_periods WHERE period_name = :period_name AND acad_cycle_id = :acad_cycle_id");
            $chk->execute([':period_name' => $period_name, ':acad_cycle_id' => $acad_cycle_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This application period already exists']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_application_periods (acad_cycle_id, period_name, start_date, end_date, status, description, created_at, updated_at)
                                            VALUES (:acad_cycle_id, :period_name, :start_date, :end_date, :status, :description, NOW(), NOW())");
            $sql->execute([
                ':acad_cycle_id' => $acad_cycle_id,
                ':period_name' => $period_name,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':status' => $status,
                ':description' => $description
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Application period saved successfully',
                'id' => $new_id,
                'acad_cycle_id' => $acad_cycle_id,
                'acad_year' => $this->get_acad_year($acad_cycle_id),
                'period_name' => $period_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status_val' => $status,
                'description' => $description
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving application period: '.$e->getMessage()]);
        }
    }

    public function view_period(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT * FROM tbl_application_periods WHERE id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Application period not found']);
        }
    }

    public function update_period(){
        $id = $_POST['id'];
        $acad_cycle_id = $_POST['acad_cycle_id'];
        $period_name = trim($_POST['period_name']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $status = $_POST['status'];
        $description = trim($_POST['description']);

        if($acad_cycle_id == '' || $period_name == '' || $start_date == '' || $end_date == ''){
            echo json_encode(['status' => 401, 'message' => 'Academic year, period name, start and end date are required']);
            return;
        }

        if(strtotime($end_date) < strtotime($start_date)){
            echo json_encode(['status' => 401, 'message' => 'End date cannot be before start date']);
            return;
        }

        if(!in_array($status, ['active', 'inactive', 'closed', 'hold'])){
            echo json_encode(['status' => 401, 'message' => 'Invalid status value']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT id FROM tbl_application_periods WHERE period_name = :period_name AND acad_cycle_id = :acad_cycle_id AND id != :id");
            $chk->execute([':period_name' => $period_name, ':acad_cycle_id' => $acad_cycle_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This application period already exists']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_application_periods SET acad_cycle_id = :acad_cycle_id, period_name = :period_name, start_date = :start_date, end_date = :end_date, status = :status, description = :description, updated_at = NOW() WHERE id = :id");
            $sql->execute([
                ':acad_cycle_id' => $acad_cycle_id,
                ':period_name' => $period_name,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':status' => $status,
                ':description' => $description,
                ':id' => $id
            ]);

            echo json_encode([
                'status' => 200,
                'message' => 'Application period updated successfully',
                'id' => $id,
                'acad_cycle_id' => $acad_cycle_id,
                'acad_year' => $this->get_acad_year($acad_cycle_id),
                'period_name' => $period_name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status_val' => $status,
                'description' => $description
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating application period: '.$e->getMessage()]);
        }
    }

    public function change_status(){
        $id = $_POST['id'];
        $new_status = $_POST['status'];

        $allowed = ['active', 'inactive', 'closed', 'hold'];
        if(!in_array($new_status, $allowed)){
            echo json_encode(['status' => 401, 'message' => 'Invalid status value']);
            return;
        }

        try{
            $upd = $this->connect->prepare("UPDATE tbl_application_periods SET status = :status, updated_at = NOW() WHERE id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Application period status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }
}

$period = new ApplicationPeriod();
$action = $_POST['action'];

switch($action){
    case 'register':
        $period->save_period();
        break;
    case 'view':
        $period->view_period();
        break;
    case 'update':
        $period->update_period();
        break;
    case 'change_status':
        $period->change_status();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
