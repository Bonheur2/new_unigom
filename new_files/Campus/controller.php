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

class Campus{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    public function save_campus(){
        $university_id = $_POST['university_id'];
        $camp_full_name = trim($_POST['camp_full_name']);
        $camp_city = trim($_POST['camp_city']);
        $camp_yor = trim($_POST['camp_yor']);
        $camp_active = $_POST['camp_active'];
        $camp_comments = trim($_POST['camp_comments']);

        if($university_id == '' || $camp_full_name == ''){
            echo json_encode(['status' => 401, 'message' => "L'université et le nom complet du campus sont obligatoires"]);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT camp_id FROM tbl_campus WHERE camp_full_name = :camp_full_name AND university_id = :university_id");
            $chk->execute([':camp_full_name' => $camp_full_name, ':university_id' => $university_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce campus existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_campus (university_id, camp_full_name, camp_city, camp_yor, camp_active, camp_comments) VALUES (:university_id, :camp_full_name, :camp_city, :camp_yor, :camp_active, :camp_comments)");
            $sql->execute([
                ':university_id' => $university_id,
                ':camp_full_name' => $camp_full_name,
                ':camp_city' => $camp_city,
                ':camp_yor' => $camp_yor,
                ':camp_active' => $camp_active,
                ':camp_comments' => $camp_comments
            ]);
            $new_id = $this->connect->lastInsertId();

            $univ = $this->connect->prepare("SELECT full_name FROM tbl_university WHERE id = :id");
            $univ->execute([':id' => $university_id]);
            $univRow = $univ->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 200,
                'message' => 'Campus enregistré avec succès',
                'camp_id' => $new_id,
                'university_id' => $university_id,
                'university_name' => $univRow ? $univRow['full_name'] : '',
                'camp_full_name' => $camp_full_name,
                'camp_city' => $camp_city,
                'camp_yor' => $camp_yor,
                'camp_active' => $camp_active,
                'camp_comments' => $camp_comments
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => "Erreur lors de l'enregistrement du campus : ".$e->getMessage()]);
        }
    }

    public function view_campus(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT camp_id, university_id, camp_full_name, camp_city, camp_yor, camp_active, camp_comments FROM tbl_campus WHERE camp_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Campus introuvable']);
        }
    }

    public function update_campus(){
        $id = $_POST['id'];
        $university_id = $_POST['university_id'];
        $camp_full_name = trim($_POST['camp_full_name']);
        $camp_city = trim($_POST['camp_city']);
        $camp_yor = trim($_POST['camp_yor']);
        $camp_active = $_POST['camp_active'];
        $camp_comments = trim($_POST['camp_comments']);

        if($university_id == '' || $camp_full_name == ''){
            echo json_encode(['status' => 401, 'message' => "L'université et le nom complet du campus sont obligatoires"]);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT camp_id FROM tbl_campus WHERE camp_full_name = :camp_full_name AND university_id = :university_id AND camp_id != :id");
            $chk->execute([':camp_full_name' => $camp_full_name, ':university_id' => $university_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Ce campus existe déjà']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_campus SET university_id = :university_id, camp_full_name = :camp_full_name, camp_city = :camp_city, camp_yor = :camp_yor, camp_active = :camp_active, camp_comments = :camp_comments WHERE camp_id = :id");
            $sql->execute([
                ':university_id' => $university_id,
                ':camp_full_name' => $camp_full_name,
                ':camp_city' => $camp_city,
                ':camp_yor' => $camp_yor,
                ':camp_active' => $camp_active,
                ':camp_comments' => $camp_comments,
                ':id' => $id
            ]);

            $univ = $this->connect->prepare("SELECT full_name FROM tbl_university WHERE id = :id");
            $univ->execute([':id' => $university_id]);
            $univRow = $univ->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 200,
                'message' => 'Campus mis à jour avec succès',
                'camp_id' => $id,
                'university_id' => $university_id,
                'university_name' => $univRow ? $univRow['full_name'] : '',
                'camp_full_name' => $camp_full_name,
                'camp_city' => $camp_city,
                'camp_yor' => $camp_yor,
                'camp_active' => $camp_active,
                'camp_comments' => $camp_comments
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du campus : '.$e->getMessage()]);
        }
    }

    public function delete_campus(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT camp_active FROM tbl_campus WHERE camp_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['camp_active'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_campus SET camp_active = :camp_active WHERE camp_id = :id");
            $upd->execute([':camp_active' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Statut du campus mis à jour']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur lors de la mise à jour du statut : '.$e->getMessage()]);
        }
    }
}

$campus = new Campus();
$action = $_POST['action'];

switch($action){
    case 'register':
        $campus->save_campus();
        break;
    case 'view':
        $campus->view_campus();
        break;
    case 'update':
        $campus->update_campus();
        break;
    case 'delete':
        $campus->delete_campus();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
