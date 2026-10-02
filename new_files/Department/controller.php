<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class Department{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_department(){
        $prg_type = $_POST['prg_type'];
        $d_f_name = trim($_POST['d_f_name']);
        $d_s_name = trim($_POST['d_s_name']);

        if($prg_type == '' || $prg_type == '0' || $d_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Program type and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT dept_id FROM tbl_department WHERE dept_full_name = :dept_full_name AND prg_type = :prg_type");
            $chk->execute([':dept_full_name' => $d_f_name, ':prg_type' => $prg_type]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This department already exists']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_department (prg_type, dept_full_name, dept_short_name, status) VALUES (:prg_type, :dept_full_name, :dept_short_name, 1)");
            $sql->execute([
                ':prg_type' => $prg_type,
                ':dept_full_name' => $d_f_name,
                ':dept_short_name' => $d_s_name
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Department saved successfully',
                'dept_id' => $new_id,
                'prg_type' => $prg_type,
                'dept_full_name' => $d_f_name,
                'dept_short_name' => $d_s_name
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving department: '.$e->getMessage()]);
        }
    }

    public function view_department(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT tbl_department.*, tbl_program_type.fac_id, tbl_faculty.campus_id
                                        FROM tbl_department
                                        INNER JOIN tbl_program_type ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                        INNER JOIN tbl_faculty ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                        WHERE tbl_department.dept_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Department not found']);
        }
    }

    public function update_department(){
        $id = $_POST['pr_id'];
        $prg_type = $_POST['prg_type'];
        $d_f_name = trim($_POST['d_f_name']);
        $d_s_name = trim($_POST['d_s_name']);

        if($prg_type == '' || $d_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Program type and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT dept_id FROM tbl_department WHERE dept_full_name = :dept_full_name AND prg_type = :prg_type AND dept_id != :id");
            $chk->execute([':dept_full_name' => $d_f_name, ':prg_type' => $prg_type, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This department already exists']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT prg_type FROM tbl_department WHERE dept_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $prg_type_changed = $old && $old['prg_type'] != $prg_type;

            $sql = $this->connect->prepare("UPDATE tbl_department SET prg_type = :prg_type, dept_full_name = :dept_full_name, dept_short_name = :dept_short_name WHERE dept_id = :id");
            $sql->execute([
                ':prg_type' => $prg_type,
                ':dept_full_name' => $d_f_name,
                ':dept_short_name' => $d_s_name,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'Department updated successfully',
                'dept_id' => $id,
                'prg_type' => $prg_type,
                'dept_full_name' => $d_f_name,
                'dept_short_name' => $d_s_name,
                'prg_type_changed' => $prg_type_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating department: '.$e->getMessage()]);
        }
    }

    public function delete_department(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_department WHERE dept_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_department SET status = :status WHERE dept_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Department status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }

    public function get_faculty(){
        $camp_id = $_POST['camp_id'];

        $sql = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE campus_id = :camp_id AND status = 1 ORDER BY fac_full_name ASC");
        $sql->execute([':camp_id' => $camp_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function get_prg_type(){
        $fac_id = $_POST['fac_id'];

        $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
        $sql->execute([':fac_id' => $fac_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$department = new Department();
$action = $_POST['action'];

switch($action){
    case 'register':
        $department->save_department();
        break;
    case 'view':
        $department->view_department();
        break;
    case 'update':
        $department->update_department();
        break;
    case 'delete':
        $department->delete_department();
        break;
    case 'get_faculty':
        $department->get_faculty();
        break;
    case 'get_prg_type':
        $department->get_prg_type();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
