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

class OptionEntity{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_option(){
        $dept_id = $_POST['dept_id'];
        $o_f_name = trim($_POST['o_f_name']);
        $o_s_name = trim($_POST['o_s_name']);

        if($dept_id == '' || $dept_id == '0' || $o_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Department and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT opt_id FROM tbl_option WHERE opt_full_name = :opt_full_name AND dept_id = :dept_id");
            $chk->execute([':opt_full_name' => $o_f_name, ':dept_id' => $dept_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This option already exists']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_option (dept_id, opt_full_name, opt_short_name, status) VALUES (:dept_id, :opt_full_name, :opt_short_name, 1)");
            $sql->execute([
                ':dept_id' => $dept_id,
                ':opt_full_name' => $o_f_name,
                ':opt_short_name' => $o_s_name
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Option saved successfully',
                'opt_id' => $new_id,
                'dept_id' => $dept_id,
                'opt_full_name' => $o_f_name,
                'opt_short_name' => $o_s_name
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving option: '.$e->getMessage()]);
        }
    }

    public function view_option(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT tbl_option.*, tbl_department.prg_type, tbl_program_type.fac_id, tbl_faculty.campus_id
                                        FROM tbl_option
                                        INNER JOIN tbl_department ON tbl_option.dept_id = tbl_department.dept_id
                                        INNER JOIN tbl_program_type ON tbl_department.prg_type = tbl_program_type.prg_type_id
                                        INNER JOIN tbl_faculty ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                        WHERE tbl_option.opt_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Option not found']);
        }
    }

    public function update_option(){
        $id = $_POST['pr_id'];
        $dept_id = $_POST['dept_id'];
        $o_f_name = trim($_POST['o_f_name']);
        $o_s_name = trim($_POST['o_s_name']);

        if($dept_id == '' || $o_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Department and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT opt_id FROM tbl_option WHERE opt_full_name = :opt_full_name AND dept_id = :dept_id AND opt_id != :id");
            $chk->execute([':opt_full_name' => $o_f_name, ':dept_id' => $dept_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This option already exists']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT dept_id FROM tbl_option WHERE opt_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $dept_changed = $old && $old['dept_id'] != $dept_id;

            $sql = $this->connect->prepare("UPDATE tbl_option SET dept_id = :dept_id, opt_full_name = :opt_full_name, opt_short_name = :opt_short_name WHERE opt_id = :id");
            $sql->execute([
                ':dept_id' => $dept_id,
                ':opt_full_name' => $o_f_name,
                ':opt_short_name' => $o_s_name,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'Option updated successfully',
                'opt_id' => $id,
                'dept_id' => $dept_id,
                'opt_full_name' => $o_f_name,
                'opt_short_name' => $o_s_name,
                'dept_changed' => $dept_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating option: '.$e->getMessage()]);
        }
    }

    public function delete_option(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_option WHERE opt_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_option SET status = :status WHERE opt_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Option status updated']);
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

    public function get_department(){
        $prg_type = $_POST['prg_type'];

        $sql = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department WHERE prg_type = :prg_type AND status = 1 ORDER BY dept_full_name ASC");
        $sql->execute([':prg_type' => $prg_type]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$option = new OptionEntity();
$action = $_POST['action'];

switch($action){
    case 'register':
        $option->save_option();
        break;
    case 'view':
        $option->view_option();
        break;
    case 'update':
        $option->update_option();
        break;
    case 'delete':
        $option->delete_option();
        break;
    case 'get_faculty':
        $option->get_faculty();
        break;
    case 'get_prg_type':
        $option->get_prg_type();
        break;
    case 'get_department':
        $option->get_department();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
