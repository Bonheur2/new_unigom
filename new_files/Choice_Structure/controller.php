<?php
// Controller for the "structure des choix" document (Choice_Structure/index.php).
// Applicants see the active document as a "View PDF" button on the Choix
// academique step of the application forms.
//
// Exactly one row is active at a time: activating one deactivates the rest,
// so the applicant-facing button never has to choose between candidates.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__, 2));
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class ChoiceStructure{
    private $connect;
    private $upload_dir = 'uploads/choice_structure/';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    // Returns the stored path on success, false on a rejected file,
    // null when no file was supplied.
    private function handle_file_upload(){
        if(!isset($_FILES['structure_file']) || $_FILES['structure_file']['error'] !== UPLOAD_ERR_OK){
            return null;
        }

        $allowed = ['pdf'];
        $ext = strtolower(pathinfo($_FILES['structure_file']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){
            return false;
        }

        $target_dir = SITE_ROOT.$this->upload_dir;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }

        $filename = 'structure_'.uniqid().'.'.$ext;
        $target_path = $target_dir.$filename;

        if(move_uploaded_file($_FILES['structure_file']['tmp_name'], $target_path)){
            return '/'.$this->upload_dir.$filename;
        }
        return false;
    }

    private function deactivate_all(){
        $this->connect->exec("UPDATE tbl_choice_structure SET status = 0");
    }

    public function save_structure(){
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $make_active = !empty($_POST['make_active']);

        if($title === ''){
            echo json_encode(['status' => 401, 'message' => 'Le titre est requis']);
            return;
        }

        $file_path = $this->handle_file_upload();
        if($file_path === false){
            echo json_encode(['status' => 401, 'message' => 'Le fichier doit &ecirc;tre un PDF']);
            return;
        }
        if($file_path === null){
            echo json_encode(['status' => 401, 'message' => 'Veuillez choisir un fichier PDF']);
            return;
        }

        try{
            if($make_active){
                $this->deactivate_all();
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_choice_structure (title, description, file_path, file_type, status, uploaded_by)
                                            VALUES (:title, :description, :file_path, 'pdf', :status, :uploaded_by)");
            $sql->execute([
                ':title' => $title,
                ':description' => $description,
                ':file_path' => $file_path,
                ':status' => $make_active ? 1 : 0,
                ':uploaded_by' => $_SESSION['acc_id'] ?? null
            ]);

            echo json_encode([
                'status' => 200,
                'message' => 'Document enregistr&eacute; avec succ&egrave;s',
                'structure_id' => $this->connect->lastInsertId(),
                'title' => $title,
                'description' => $description,
                'file_path' => $file_path,
                'active' => $make_active ? 1 : 0
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur: '.$e->getMessage()]);
        }
    }

    public function activate_structure(){
        $id = $_POST['id'] ?? '';

        try{
            $chk = $this->connect->prepare("SELECT structure_id FROM tbl_choice_structure WHERE structure_id = :id");
            $chk->execute([':id' => $id]);
            if($chk->rowCount() === 0){
                echo json_encode(['status' => 401, 'message' => 'Document introuvable']);
                return;
            }

            $this->deactivate_all();

            $upd = $this->connect->prepare("UPDATE tbl_choice_structure SET status = 1 WHERE structure_id = :id");
            $upd->execute([':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Document activ&eacute;']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur: '.$e->getMessage()]);
        }
    }

    public function delete_structure(){
        $id = $_POST['id'] ?? '';

        try{
            $sql = $this->connect->prepare("SELECT file_path, status FROM tbl_choice_structure WHERE structure_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                echo json_encode(['status' => 401, 'message' => 'Document introuvable']);
                return;
            }

            $del = $this->connect->prepare("DELETE FROM tbl_choice_structure WHERE structure_id = :id");
            $del->execute([':id' => $id]);

            // Remove the file only after the row is gone, so a failed delete
            // never leaves a row pointing at a missing file.
            $full = SITE_ROOT.ltrim($row['file_path'], '/');
            if(is_file($full)){
                @unlink($full);
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Document supprim&eacute;',
                'was_active' => (int) $row['status']
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Erreur: '.$e->getMessage()]);
        }
    }

    // Used by the application forms to show the "View PDF" button.
    public function get_active(){
        $sql = $this->connect->prepare("SELECT structure_id, title, description, file_path
                                          FROM tbl_choice_structure
                                         WHERE status = 1
                                      ORDER BY structure_id DESC LIMIT 1");
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        echo json_encode($row ?: []);
    }
}

$structure = new ChoiceStructure();
$action = $_POST['action'] ?? '';

switch($action){
    case 'save':
        $structure->save_structure();
        break;
    case 'activate':
        $structure->activate_structure();
        break;
    case 'delete':
        $structure->delete_structure();
        break;
    case 'get_active':
        $structure->get_active();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Action non valide']);
        break;
}
