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

class ProgramType{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_prg_type(){
        $fac_id = $_POST['fac_id'];
        $pt_f_name = trim($_POST['pt_f_name']);
        $pt_s_name = trim($_POST['pt_s_name']);

        if($fac_id == '' || $fac_id == '0' || $pt_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'La faculté et le nom complet sont obligatoires']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT prg_type_id FROM tbl_program_type WHERE prg_type_full_name = :prg_type_full_name AND fac_id = :fac_id");
            $chk->execute([':prg_type_full_name' => $pt_f_name, ':fac_id' => $fac_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce type de programme existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_program_type (fac_id, prg_type_full_name, prg_type_short_name, status) VALUES (:fac_id, :prg_type_full_name, :prg_type_short_name, 1)");
            $sql->execute([
                ':fac_id' => $fac_id,
                ':prg_type_full_name' => $pt_f_name,
                ':prg_type_short_name' => $pt_s_name
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Type de programme enregistré avec succès',
                'prg_type_id' => $new_id,
                'fac_id' => $fac_id,
                'prg_type_full_name' => $pt_f_name,
                'prg_type_short_name' => $pt_s_name
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de l\'enregistrement du type de programme : '.$e->getMessage()]);
        }
    }

    public function view_prg_type(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT tbl_program_type.*, tbl_faculty.campus_id
                                        FROM tbl_program_type
                                        INNER JOIN tbl_faculty ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                        WHERE tbl_program_type.prg_type_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Type de programme introuvable']);
        }
    }

    public function update_prg_type(){
        $id = $_POST['pr_id'];
        $fac_id = $_POST['fac_id'];
        $pt_f_name = trim($_POST['pt_f_name']);
        $pt_s_name = trim($_POST['pt_s_name']);

        if($fac_id == '' || $pt_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'La faculté et le nom complet sont obligatoires']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT prg_type_id FROM tbl_program_type WHERE prg_type_full_name = :prg_type_full_name AND fac_id = :fac_id AND prg_type_id != :id");
            $chk->execute([':prg_type_full_name' => $pt_f_name, ':fac_id' => $fac_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce type de programme existe déjà']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT fac_id FROM tbl_program_type WHERE prg_type_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $fac_changed = $old && $old['fac_id'] != $fac_id;

            $sql = $this->connect->prepare("UPDATE tbl_program_type SET fac_id = :fac_id, prg_type_full_name = :prg_type_full_name, prg_type_short_name = :prg_type_short_name WHERE prg_type_id = :id");
            $sql->execute([
                ':fac_id' => $fac_id,
                ':prg_type_full_name' => $pt_f_name,
                ':prg_type_short_name' => $pt_s_name,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'Type de programme mis à jour avec succès',
                'prg_type_id' => $id,
                'fac_id' => $fac_id,
                'prg_type_full_name' => $pt_f_name,
                'prg_type_short_name' => $pt_s_name,
                'fac_changed' => $fac_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du type de programme : '.$e->getMessage()]);
        }
    }

    public function delete_prg_type(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_program_type WHERE prg_type_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_program_type SET status = :status WHERE prg_type_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Statut du type de programme mis à jour']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du statut : '.$e->getMessage()]);
        }
    }

    public function get_faculty(){
        $camp_id = $_POST['camp_id'];

        $sql = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE campus_id = :camp_id AND status = 1 ORDER BY fac_full_name ASC");
        $sql->execute([':camp_id' => $camp_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$prg_type = new ProgramType();
$action = $_POST['action'];

switch($action){
    case 'register':
        $prg_type->save_prg_type();
        break;
    case 'view':
        $prg_type->view_prg_type();
        break;
    case 'update':
        $prg_type->update_prg_type();
        break;
    case 'delete':
        $prg_type->delete_prg_type();
        break;
    case 'get_faculty':
        $prg_type->get_faculty();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
