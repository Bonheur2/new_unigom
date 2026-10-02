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

class Faculty{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_faculty(){
        $campus_id = $_POST['campus_id'];
        $ft_f_name = trim($_POST['ft_f_name']);
        $ft_s_name = trim($_POST['ft_s_name']);
        $ft_code = trim($_POST['ft_code']);

        if($campus_id == '' || $campus_id == '0' || $ft_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Campus and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT fac_id FROM tbl_faculty WHERE fac_full_name = :fac_full_name AND campus_id = :campus_id");
            $chk->execute([':fac_full_name' => $ft_f_name, ':campus_id' => $campus_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This school already exists']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_faculty (campus_id, fac_full_name, fac_short_name, code, status) VALUES (:campus_id, :fac_full_name, :fac_short_name, :code, 1)");
            $sql->execute([
                ':campus_id' => $campus_id,
                ':fac_full_name' => $ft_f_name,
                ':fac_short_name' => $ft_s_name,
                ':code' => $ft_code
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'School saved successfully',
                'fac_id' => $new_id,
                'campus_id' => $campus_id,
                'fac_full_name' => $ft_f_name,
                'fac_short_name' => $ft_s_name,
                'code' => $ft_code
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving school: '.$e->getMessage()]);
        }
    }

    public function view_faculty(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE fac_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'School not found']);
        }
    }

    public function update_faculty(){
        $id = $_POST['pr_id'];
        $campus_id = $_POST['campus_id'];
        $ft_f_name = trim($_POST['ft_f_name']);
        $ft_s_name = trim($_POST['ft_s_name']);
        $ft_code = trim($_POST['ft_s_codee']);

        if($campus_id == '' || $ft_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Campus and full name are required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT fac_id FROM tbl_faculty WHERE fac_full_name = :fac_full_name AND campus_id = :campus_id AND fac_id != :id");
            $chk->execute([':fac_full_name' => $ft_f_name, ':campus_id' => $campus_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This school already exists']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT campus_id FROM tbl_faculty WHERE fac_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $campus_changed = $old && $old['campus_id'] != $campus_id;

            $sql = $this->connect->prepare("UPDATE tbl_faculty SET campus_id = :campus_id, fac_full_name = :fac_full_name, fac_short_name = :fac_short_name, code = :code WHERE fac_id = :id");
            $sql->execute([
                ':campus_id' => $campus_id,
                ':fac_full_name' => $ft_f_name,
                ':fac_short_name' => $ft_s_name,
                ':code' => $ft_code,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'School updated successfully',
                'fac_id' => $id,
                'campus_id' => $campus_id,
                'fac_full_name' => $ft_f_name,
                'fac_short_name' => $ft_s_name,
                'code' => $ft_code,
                'campus_changed' => $campus_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating school: '.$e->getMessage()]);
        }
    }

    public function delete_faculty(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_faculty WHERE fac_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_faculty SET status = :status WHERE fac_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'School status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }
}

$faculty = new Faculty();
$action = $_POST['action'];

switch($action){
    case 'register':
        $faculty->save_faculty();
        break;
    case 'view':
        $faculty->view_faculty();
        break;
    case 'update':
        $faculty->update_faculty();
        break;
    case 'delete':
        $faculty->delete_faculty();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
