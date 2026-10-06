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

class FormType{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_form_type(){
        $form_name = trim($_POST['form_name']);
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'];

        if($form_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Le nom du formulaire est obligatoire']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT form_id FROM tbl_form_types WHERE form_name = :form_name");
            $chk->execute([':form_name' => $form_name]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce formulaire de candidature existe déjà']);
                return;
            }

            $rankSql = $this->connect->query("SELECT COALESCE(MAX(`rank`), 0) + 1 AS next_rank FROM tbl_form_types");
            $next_rank = $rankSql->fetch(PDO::FETCH_ASSOC)['next_rank'];

            $sql = $this->connect->prepare("INSERT INTO tbl_form_types (form_name, description, status, `rank`) VALUES (:form_name, :description, :status, :rank)");
            $sql->execute([
                ':form_name' => $form_name,
                ':description' => $description,
                ':status' => $status,
                ':rank' => $next_rank
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Formulaire de candidature enregistré avec succès',
                'form_id' => $new_id,
                'form_name' => $form_name,
                'description' => $description,
                'status_val' => $status
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => "Erreur lors de l'enregistrement du formulaire : ".$e->getMessage()]);
        }
    }

    public function view_form_type(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT form_id, form_name, description, status FROM tbl_form_types WHERE form_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Formulaire de candidature introuvable']);
        }
    }

    public function update_form_type(){
        $id = $_POST['id'];
        $form_name = trim($_POST['form_name']);
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'];

        if($form_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Le nom du formulaire est obligatoire']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT form_id FROM tbl_form_types WHERE form_name = :form_name AND form_id != :id");
            $chk->execute([':form_name' => $form_name, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce formulaire de candidature existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_form_types SET form_name = :form_name, description = :description, status = :status WHERE form_id = :id");
            $sql->execute([
                ':form_name' => $form_name,
                ':description' => $description,
                ':status' => $status,
                ':id' => $id
            ]);

            echo json_encode([
                'status' => 200,
                'message' => 'Formulaire de candidature mis à jour avec succès',
                'form_id' => $id,
                'form_name' => $form_name,
                'description' => $description,
                'status_val' => $status
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du formulaire : '.$e->getMessage()]);
        }
    }

    public function delete_form_type(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_form_types WHERE form_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_form_types SET status = :status WHERE form_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Statut du formulaire mis à jour']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du statut : '.$e->getMessage()]);
        }
    }

    public function reorder_form_types(){
        $order = $_POST['order'] ?? [];

        if(!is_array($order) || empty($order)){
            echo json_encode(['status' => 401, 'message' => 'Données de classement non valides']);
            return;
        }

        try{
            $this->connect->beginTransaction();

            $upd = $this->connect->prepare("UPDATE tbl_form_types SET `rank` = :rank WHERE form_id = :id");
            foreach($order as $index => $form_id){
                $upd->execute([':rank' => $index + 1, ':id' => (int) $form_id]);
            }

            $this->connect->commit();

            echo json_encode(['status' => 200, 'message' => 'Ordre enregistré']);
        } catch(PDOException $e){
            $this->connect->rollBack();
            echo json_encode(['status' => 500, 'message' => "Erreur lors de l'enregistrement de l'ordre : ".$e->getMessage()]);
        }
    }
}

$form_type = new FormType();
$action = $_POST['action'];

switch($action){
    case 'register':
        $form_type->save_form_type();
        break;
    case 'view':
        $form_type->view_form_type();
        break;
    case 'update':
        $form_type->update_form_type();
        break;
    case 'delete':
        $form_type->delete_form_type();
        break;
    case 'reorder':
        $form_type->reorder_form_types();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
