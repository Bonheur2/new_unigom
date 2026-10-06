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

class Level{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    // level_no and level_rank are INT columns: store empty input as NULL
    private function int_or_null($value){
        $value = trim((string)$value);
        return $value === '' ? null : (int)$value;
    }

    public function save_level(){
        $prg_type_id = $_POST['prg_type_id'];
        $l_no = $this->int_or_null($_POST['l_no']);
        $l_f_name = trim($_POST['l_f_name']);
        $l_rank = $this->int_or_null($_POST['l_rank']);

        if($prg_type_id == '' || $prg_type_id == '0' || $l_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Le type de programme et le nom complet sont obligatoires']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT level_id FROM tbl_level WHERE level_full_name = :level_full_name AND prg_type_id = :prg_type_id");
            $chk->execute([':level_full_name' => $l_f_name, ':prg_type_id' => $prg_type_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce niveau existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_level (prg_type_id, level_no, level_full_name, level_rank, status) VALUES (:prg_type_id, :level_no, :level_full_name, :level_rank, 1)");
            $sql->execute([
                ':prg_type_id' => $prg_type_id,
                ':level_no' => $l_no,
                ':level_full_name' => $l_f_name,
                ':level_rank' => $l_rank
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Niveau enregistré avec succès',
                'level_id' => $new_id,
                'prg_type_id' => $prg_type_id,
                'level_no' => $l_no,
                'level_full_name' => $l_f_name,
                'level_rank' => $l_rank
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => "Erreur lors de l'enregistrement du niveau : ".$e->getMessage()]);
        }
    }

    public function view_level(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT tbl_level.*, tbl_program_type.fac_id, tbl_faculty.campus_id
                                        FROM tbl_level
                                        INNER JOIN tbl_program_type ON tbl_level.prg_type_id = tbl_program_type.prg_type_id
                                        INNER JOIN tbl_faculty ON tbl_program_type.fac_id = tbl_faculty.fac_id
                                        WHERE tbl_level.level_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Niveau introuvable']);
        }
    }

    public function update_level(){
        $id = $_POST['pr_id'];
        $prg_type_id = $_POST['prg_type_id'];
        $l_no = $this->int_or_null($_POST['l_no']);
        $l_f_name = trim($_POST['l_f_name']);
        $l_rank = $this->int_or_null($_POST['l_rank']);

        if($prg_type_id == '' || $l_f_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Le type de programme et le nom complet sont obligatoires']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT level_id FROM tbl_level WHERE level_full_name = :level_full_name AND prg_type_id = :prg_type_id AND level_id != :id");
            $chk->execute([':level_full_name' => $l_f_name, ':prg_type_id' => $prg_type_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce niveau existe déjà']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT prg_type_id FROM tbl_level WHERE level_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $prg_type_changed = $old && $old['prg_type_id'] != $prg_type_id;

            $sql = $this->connect->prepare("UPDATE tbl_level SET prg_type_id = :prg_type_id, level_no = :level_no, level_full_name = :level_full_name, level_rank = :level_rank WHERE level_id = :id");
            $sql->execute([
                ':prg_type_id' => $prg_type_id,
                ':level_no' => $l_no,
                ':level_full_name' => $l_f_name,
                ':level_rank' => $l_rank,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'Niveau mis à jour avec succès',
                'level_id' => $id,
                'prg_type_id' => $prg_type_id,
                'level_no' => $l_no,
                'level_full_name' => $l_f_name,
                'level_rank' => $l_rank,
                'prg_type_changed' => $prg_type_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du niveau : '.$e->getMessage()]);
        }
    }

    public function delete_level(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_level WHERE level_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_level SET status = :status WHERE level_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Statut du niveau mis à jour']);
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

    public function get_prg_type(){
        $fac_id = $_POST['fac_id'];

        $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
        $sql->execute([':fac_id' => $fac_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$level = new Level();
$action = $_POST['action'];

switch($action){
    case 'register':
        $level->save_level();
        break;
    case 'view':
        $level->view_level();
        break;
    case 'update':
        $level->update_level();
        break;
    case 'delete':
        $level->delete_level();
        break;
    case 'get_faculty':
        $level->get_faculty();
        break;
    case 'get_prg_type':
        $level->get_prg_type();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
