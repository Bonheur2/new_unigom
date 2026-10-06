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

class AcadYear{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_acad_year(){
        $acad_year = trim($_POST['acad_year']);
        $status = $_POST['status'];

        if($acad_year == ''){
            echo json_encode(['status' => 401, 'message' => "L'année académique est obligatoire"]);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE acad_year = :acad_year");
            $chk->execute([':acad_year' => $acad_year]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Cette année académique existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_acad_cycle (acad_year, status) VALUES (:acad_year, :status)");
            $sql->execute([
                ':acad_year' => $acad_year,
                ':status' => $status
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Année académique enregistrée avec succès',
                'acad_cycle_id' => $new_id,
                'acad_year' => $acad_year,
                'status_val' => $status
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => "Erreur lors de l'enregistrement de l'année académique : ".$e->getMessage()]);
        }
    }

    public function view_acad_year(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT acad_cycle_id, acad_year, status FROM tbl_acad_cycle WHERE acad_cycle_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Année académique introuvable']);
        }
    }

    public function update_acad_year(){
        $id = $_POST['id'];
        $acad_year = trim($_POST['acad_year']);
        $status = $_POST['status'];

        if($acad_year == ''){
            echo json_encode(['status' => 401, 'message' => "L'année académique est obligatoire"]);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE acad_year = :acad_year AND acad_cycle_id != :id");
            $chk->execute([':acad_year' => $acad_year, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Cette année académique existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_acad_cycle SET acad_year = :acad_year, status = :status WHERE acad_cycle_id = :id");
            $sql->execute([
                ':acad_year' => $acad_year,
                ':status' => $status,
                ':id' => $id
            ]);
            echo json_encode([
                'status' => 200,
                'message' => 'Année académique mise à jour avec succès',
                'acad_cycle_id' => $id,
                'acad_year' => $acad_year,
                'status_val' => $status
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => "Erreur lors de la mise à jour de l'année académique : ".$e->getMessage()]);
        }
    }

    public function delete_acad_year(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_acad_cycle WHERE acad_cycle_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            // 1 = en cours, 2 = clôturée: the switch moves a year between the two
            $new_status = $row['status'] == 1 ? 2 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_acad_cycle SET status = :status WHERE acad_cycle_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => "Statut de l'année académique mis à jour", 'status_val' => $new_status]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du statut : '.$e->getMessage()]);
        }
    }
}

$acad_year = new AcadYear();
$action = $_POST['action'];

switch($action){
    case 'register':
        $acad_year->save_acad_year();
        break;
    case 'view':
        $acad_year->view_acad_year();
        break;
    case 'update':
        $acad_year->update_acad_year();
        break;
    case 'delete':
        $acad_year->delete_acad_year();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
