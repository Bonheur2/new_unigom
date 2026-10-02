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

class FeeCategory{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    // Normalize an incoming scope field: '' / '0' / missing -> null
    private function nullify($val){
        return ($val === '' || $val === null || $val === '0') ? null : $val;
    }

    public function save_fee(){
        $is_common = isset($_POST['is_common']) && $_POST['is_common'] == '1' ? 1 : 0;
        $name = strtoupper(trim($_POST['name']));
        $amount = $_POST['amount'];

        if($name == '' || $amount === '' || !is_numeric($amount)){
            echo json_encode(['status' => 401, 'message' => 'Fee name and a valid amount are required']);
            return;
        }

        if($is_common){
            $camp_id = $fac_id = $level_id = $prg_type_id = $dept_id = $opt_id = null;
        } else {
            $camp_id = $this->nullify($_POST['camp_id'] ?? '');
            $fac_id = $this->nullify($_POST['fac_id'] ?? '');
            $level_id = $this->nullify($_POST['level_id'] ?? '');
            $prg_type_id = $this->nullify($_POST['prg_type_id'] ?? '');
            $dept_id = $this->nullify($_POST['dept_id'] ?? '');
            $opt_id = $this->nullify($_POST['opt_id'] ?? '');

            if($camp_id === null || $fac_id === null || $level_id === null){
                echo json_encode(['status' => 401, 'message' => 'Campus, Faculty and Level are required for a specific fee']);
                return;
            }
            if($dept_id !== null && $prg_type_id === null){
                echo json_encode(['status' => 401, 'message' => 'Program Type is required when a Department is selected']);
                return;
            }
            if($opt_id !== null && $dept_id === null){
                echo json_encode(['status' => 401, 'message' => 'Department is required when an Option is selected']);
                return;
            }
        }

        try{
            if($is_common){
                $chk = $this->connect->prepare("SELECT id FROM tbl_fee_categories WHERE is_common = 1 AND UPPER(name) = :name");
                $chk->execute([':name' => $name]);
            } else {
                $chk = $this->connect->prepare("SELECT id FROM tbl_fee_categories WHERE is_common = 0
                    AND camp_id <=> :camp_id AND fac_id <=> :fac_id AND level_id <=> :level_id
                    AND prg_type_id <=> :prg_type_id AND dept_id <=> :dept_id AND opt_id <=> :opt_id
                    AND UPPER(name) = :name");
                $chk->execute([
                    ':camp_id' => $camp_id, ':fac_id' => $fac_id, ':level_id' => $level_id,
                    ':prg_type_id' => $prg_type_id, ':dept_id' => $dept_id, ':opt_id' => $opt_id,
                    ':name' => $name
                ]);
            }
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This fee category already exists for the selected scope']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_fee_categories
                (is_common, camp_id, fac_id, level_id, prg_type_id, dept_id, opt_id, name, amount, status)
                VALUES (:is_common, :camp_id, :fac_id, :level_id, :prg_type_id, :dept_id, :opt_id, :name, :amount, 1)");
            $sql->execute([
                ':is_common' => $is_common,
                ':camp_id' => $camp_id, ':fac_id' => $fac_id, ':level_id' => $level_id,
                ':prg_type_id' => $prg_type_id, ':dept_id' => $dept_id, ':opt_id' => $opt_id,
                ':name' => $name, ':amount' => $amount
            ]);

            echo json_encode(['status' => 200, 'message' => 'Fee category saved successfully', 'id' => $this->connect->lastInsertId()]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving fee category: '.$e->getMessage()]);
        }
    }

    public function view_fee(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT * FROM tbl_fee_categories WHERE id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Fee category not found']);
        }
    }

    public function update_fee(){
        $id = $_POST['pr_id'];
        $is_common = isset($_POST['is_common']) && $_POST['is_common'] == '1' ? 1 : 0;
        $name = strtoupper(trim($_POST['name']));
        $amount = $_POST['amount'];

        if($name == '' || $amount === '' || !is_numeric($amount)){
            echo json_encode(['status' => 401, 'message' => 'Fee name and a valid amount are required']);
            return;
        }

        if($is_common){
            $camp_id = $fac_id = $level_id = $prg_type_id = $dept_id = $opt_id = null;
        } else {
            $camp_id = $this->nullify($_POST['camp_id'] ?? '');
            $fac_id = $this->nullify($_POST['fac_id'] ?? '');
            $level_id = $this->nullify($_POST['level_id'] ?? '');
            $prg_type_id = $this->nullify($_POST['prg_type_id'] ?? '');
            $dept_id = $this->nullify($_POST['dept_id'] ?? '');
            $opt_id = $this->nullify($_POST['opt_id'] ?? '');

            if($camp_id === null || $fac_id === null || $level_id === null){
                echo json_encode(['status' => 401, 'message' => 'Campus, Faculty and Level are required for a specific fee']);
                return;
            }
            if($dept_id !== null && $prg_type_id === null){
                echo json_encode(['status' => 401, 'message' => 'Program Type is required when a Department is selected']);
                return;
            }
            if($opt_id !== null && $dept_id === null){
                echo json_encode(['status' => 401, 'message' => 'Department is required when an Option is selected']);
                return;
            }
        }

        try{
            if($is_common){
                $chk = $this->connect->prepare("SELECT id FROM tbl_fee_categories WHERE is_common = 1 AND UPPER(name) = :name AND id != :id");
                $chk->execute([':name' => $name, ':id' => $id]);
            } else {
                $chk = $this->connect->prepare("SELECT id FROM tbl_fee_categories WHERE is_common = 0
                    AND camp_id <=> :camp_id AND fac_id <=> :fac_id AND level_id <=> :level_id
                    AND prg_type_id <=> :prg_type_id AND dept_id <=> :dept_id AND opt_id <=> :opt_id
                    AND UPPER(name) = :name AND id != :id");
                $chk->execute([
                    ':camp_id' => $camp_id, ':fac_id' => $fac_id, ':level_id' => $level_id,
                    ':prg_type_id' => $prg_type_id, ':dept_id' => $dept_id, ':opt_id' => $opt_id,
                    ':name' => $name, ':id' => $id
                ]);
            }
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This fee category already exists for the selected scope']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_fee_categories SET
                is_common = :is_common, camp_id = :camp_id, fac_id = :fac_id, level_id = :level_id,
                prg_type_id = :prg_type_id, dept_id = :dept_id, opt_id = :opt_id,
                name = :name, amount = :amount
                WHERE id = :id");
            $sql->execute([
                ':is_common' => $is_common,
                ':camp_id' => $camp_id, ':fac_id' => $fac_id, ':level_id' => $level_id,
                ':prg_type_id' => $prg_type_id, ':dept_id' => $dept_id, ':opt_id' => $opt_id,
                ':name' => $name, ':amount' => $amount, ':id' => $id
            ]);

            echo json_encode(['status' => 200, 'message' => 'Fee category updated successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating fee category: '.$e->getMessage()]);
        }
    }

    public function delete_fee(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_fee_categories WHERE id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_fee_categories SET status = :status WHERE id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Fee category status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }

    public function get_faculty(){
        $camp_id = $_POST['camp_id'] ?? '';
        try{
            $sql = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE campus_id = :camp_id AND status = 1 ORDER BY fac_full_name ASC");
            $sql->execute([':camp_id' => $camp_id]);
            echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading faculties: '.$e->getMessage()]);
        }
    }

    public function get_level(){
        $fac_id = $_POST['fac_id'] ?? '';
        try{
            $sql = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE fac_id = :fac_id AND status = 1 ORDER BY level_rank ASC");
            $sql->execute([':fac_id' => $fac_id]);
            echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading levels: '.$e->getMessage()]);
        }
    }

    public function get_prg_type(){
        $fac_id = $_POST['fac_id'] ?? '';
        try{
            $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
            $sql->execute([':fac_id' => $fac_id]);
            echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading program types: '.$e->getMessage()]);
        }
    }

    public function get_department(){
        $prg_type = $_POST['prg_type'] ?? '';
        try{
            $sql = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department WHERE prg_type = :prg_type AND status = 1 ORDER BY dept_full_name ASC");
            $sql->execute([':prg_type' => $prg_type]);
            echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading departments: '.$e->getMessage()]);
        }
    }

    public function get_option(){
        $dept_id = $_POST['dept_id'] ?? '';
        try{
            $sql = $this->connect->prepare("SELECT opt_id, opt_full_name FROM tbl_option WHERE dept_id = :dept_id AND status = 1 ORDER BY opt_full_name ASC");
            $sql->execute([':dept_id' => $dept_id]);
            echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading options: '.$e->getMessage()]);
        }
    }
}

$feeCategory = new FeeCategory();
$action = $_POST['action'];

switch($action){
    case 'register':
        $feeCategory->save_fee();
        break;
    case 'view':
        $feeCategory->view_fee();
        break;
    case 'update':
        $feeCategory->update_fee();
        break;
    case 'delete':
        $feeCategory->delete_fee();
        break;
    case 'get_faculty':
        $feeCategory->get_faculty();
        break;
    case 'get_level':
        $feeCategory->get_level();
        break;
    case 'get_prg_type':
        $feeCategory->get_prg_type();
        break;
    case 'get_department':
        $feeCategory->get_department();
        break;
    case 'get_option':
        $feeCategory->get_option();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}

