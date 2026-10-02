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

class DocumentType{
    private $connect;
    private $upload_dir = 'uploads/document_templates/';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function handle_file_upload(){
        if(!isset($_FILES['file_name']) || $_FILES['file_name']['error'] !== UPLOAD_ERR_OK){
            return null;
        }

        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){
            return false;
        }

        $target_dir = SITE_ROOT.$this->upload_dir;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }

        $filename = 'doc_'.uniqid().'.'.$ext;
        $target_path = $target_dir.$filename;

        if(move_uploaded_file($_FILES['file_name']['tmp_name'], $target_path)){
            return '/'.$this->upload_dir.$filename;
        }
        return false;
    }

    public function save_doc(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';
        $document_name = trim($_POST['document_name']);
        $file_type = trim($_POST['file_type']);
        $international_required = $_POST['international_required'];

        if($prg_type_id == '' || $prg_type_id == '0' || $document_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Programme and document name are required']);
            return;
        }

        // fac_id is still stored so existing rows and any faculty-based reads keep working
        $fac_id = $this->faculty_for_program_type($prg_type_id);
        if($fac_id === null){
            echo json_encode(['status' => 401, 'message' => 'Invalid programme selected']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT doc_id FROM tbl_document_type WHERE document_name = :document_name AND prg_type_id = :prg_type_id");
            $chk->execute([':document_name' => $document_name, ':prg_type_id' => $prg_type_id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This document already exists']);
                return;
            }

            $file_name = $this->handle_file_upload();
            if($file_name === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid template file type']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_document_type (prg_type_id, fac_id, document_name, file_type, file_name, international_required, status)
                                            VALUES (:prg_type_id, :fac_id, :document_name, :file_type, :file_name, :international_required, 1)");
            $sql->execute([
                ':prg_type_id' => $prg_type_id,
                ':fac_id' => $fac_id,
                ':document_name' => $document_name,
                ':file_type' => $file_type,
                ':file_name' => $file_name,
                ':international_required' => $international_required
            ]);
            $new_id = $this->connect->lastInsertId();

            echo json_encode([
                'status' => 200,
                'message' => 'Document saved successfully',
                'doc_id' => $new_id,
                'prg_type_id' => $prg_type_id,
                'fac_id' => $fac_id,
                'document_name' => $document_name,
                'file_type' => $file_type,
                'file_name' => $file_name,
                'international_required' => $international_required
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving document: '.$e->getMessage()]);
        }
    }

    public function view_doc(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT tbl_document_type.*,
                                               tbl_program_type.fac_id AS prg_fac_id,
                                               tbl_faculty.campus_id
                                        FROM tbl_document_type
                                        LEFT JOIN tbl_program_type ON tbl_document_type.prg_type_id = tbl_program_type.prg_type_id
                                        LEFT JOIN tbl_faculty ON tbl_faculty.fac_id = COALESCE(tbl_program_type.fac_id, tbl_document_type.fac_id)
                                        WHERE tbl_document_type.doc_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Document not found']);
        }
    }

    public function update_doc(){
        $id = $_POST['id'];
        $prg_type_id = $_POST['prg_type_id'] ?? '';
        $document_name = trim($_POST['document_name']);
        $file_type = trim($_POST['file_type']);
        $international_required = $_POST['international_required'];

        if($prg_type_id == '' || $document_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Programme and document name are required']);
            return;
        }

        $fac_id = $this->faculty_for_program_type($prg_type_id);
        if($fac_id === null){
            echo json_encode(['status' => 401, 'message' => 'Invalid programme selected']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT doc_id FROM tbl_document_type WHERE document_name = :document_name AND prg_type_id = :prg_type_id AND doc_id != :id");
            $chk->execute([':document_name' => $document_name, ':prg_type_id' => $prg_type_id, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This document already exists']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT prg_type_id FROM tbl_document_type WHERE doc_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $prg_changed = !$old || $old['prg_type_id'] != $prg_type_id;

            $file_name = $this->handle_file_upload();
            if($file_name === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid template file type']);
                return;
            }

            if($file_name !== null){
                $sql = $this->connect->prepare("UPDATE tbl_document_type SET prg_type_id=:prg_type_id, fac_id=:fac_id, document_name=:document_name, file_type=:file_type, file_name=:file_name, international_required=:international_required WHERE doc_id=:id");
                $sql->execute([
                    ':prg_type_id' => $prg_type_id,
                    ':fac_id' => $fac_id,
                    ':document_name' => $document_name,
                    ':file_type' => $file_type,
                    ':file_name' => $file_name,
                    ':international_required' => $international_required,
                    ':id' => $id
                ]);
            } else {
                $sql = $this->connect->prepare("UPDATE tbl_document_type SET prg_type_id=:prg_type_id, fac_id=:fac_id, document_name=:document_name, file_type=:file_type, international_required=:international_required WHERE doc_id=:id");
                $sql->execute([
                    ':prg_type_id' => $prg_type_id,
                    ':fac_id' => $fac_id,
                    ':document_name' => $document_name,
                    ':file_type' => $file_type,
                    ':international_required' => $international_required,
                    ':id' => $id
                ]);
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Document updated successfully',
                'doc_id' => $id,
                'prg_type_id' => $prg_type_id,
                'fac_id' => $fac_id,
                'document_name' => $document_name,
                'file_type' => $file_type,
                'file_name' => $file_name,
                'international_required' => $international_required,
                'prg_changed' => $prg_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating document: '.$e->getMessage()]);
        }
    }

    public function delete_doc(){
        $id = $_POST['id'];

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_document_type WHERE doc_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_document_type SET status = :status WHERE doc_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Document status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }

    // documents are keyed on the programme now; fac_id is kept in step with it
    private function faculty_for_program_type($prg_type_id){
        $sql = $this->connect->prepare("SELECT fac_id FROM tbl_program_type WHERE prg_type_id = :prg_type_id");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['fac_id'] : null;
    }

    public function get_program_types(){
        $fac_id = $_POST['fac_id'] ?? '';

        $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
        $sql->execute([':fac_id' => $fac_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function get_faculty(){
        $camp_id = $_POST['camp_id'];

        $sql = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE campus_id = :camp_id AND status = 1 ORDER BY fac_full_name ASC");
        $sql->execute([':camp_id' => $camp_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$doc = new DocumentType();
$action = $_POST['action'];

switch($action){
    case 'register':
        $doc->save_doc();
        break;
    case 'view':
        $doc->view_doc();
        break;
    case 'update':
        $doc->update_doc();
        break;
    case 'delete':
        $doc->delete_doc();
        break;
    case 'get_faculty':
        $doc->get_faculty();
        break;
    case 'get_program_types':
        $doc->get_program_types();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
