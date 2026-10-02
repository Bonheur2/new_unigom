<?php
// Controller for Formtype_Document/index.php.
//
// Manages document requirements attached to a FORM TYPE (tbl_form_types) rather
// than to a programme. These apply to everyone filling in that form, whatever
// their faculty or programme, and sit alongside the programme-based
// requirements in tbl_document_type - applicants see both lists.
//
// file_type holds one or more extensions, comma-separated (e.g. "pdf,docx").
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

// con.php sets error_reporting(0), so a failure would otherwise return an empty
// body and the caller's JSON.parse would fail with no message.
error_reporting(E_ALL);
ini_set('display_errors', '0');

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

set_exception_handler(function($e){
    if(!headers_sent()){
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'status'  => 500,
        'message' => 'Server error: '.$e->getMessage(),
        'where'   => basename($e->getFile()).':'.$e->getLine()
    ]);
    exit;
});

class FormTypeDocument{
    private $connect;
    private $upload_dir = 'uploads/document_templates/';

    // Must stay in step with the allow-lists in the form controllers' upload
    // handlers - offering a format they reject would silently drop the file.
    private $allowed_formats = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    /**
     * Normalises the posted format list: keeps only known extensions, removes
     * duplicates, and returns them comma-separated. Empty string when none
     * survive, which the callers treat as a validation failure.
     */
    private function clean_formats($raw){
        $parts = array_filter(array_map('trim', explode(',', strtolower((string) $raw))));
        $valid = array_values(array_unique(array_intersect($parts, $this->allowed_formats)));
        return implode(',', $valid);
    }

    private function handle_file_upload(){
        if(!isset($_FILES['file_name']) || $_FILES['file_name']['error'] === UPLOAD_ERR_NO_FILE){
            return null; // no new file supplied
        }

        if($_FILES['file_name']['error'] !== UPLOAD_ERR_OK){
            return false;
        }

        $ext = strtolower(pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $this->allowed_formats, true)){
            return false;
        }

        $target_dir = SITE_ROOT.$this->upload_dir;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }

        $filename = 'tpl_'.uniqid().'.'.$ext;
        if(move_uploaded_file($_FILES['file_name']['tmp_name'], $target_dir.$filename)){
            return '/'.$this->upload_dir.$filename;
        }
        return false;
    }

    public function save_doc(){
        $form_id = $_POST['form_id'] ?? '';
        $document_name = trim($_POST['document_name'] ?? '');
        $file_type = $this->clean_formats($_POST['file_type'] ?? '');
        $max_size_mb = trim($_POST['max_size_mb'] ?? '') ?: null;
        $is_required = $_POST['is_required'] ?? 1;
        $international_required = $_POST['international_required'] ?? 0;

        if($form_id === '' || $form_id === '0' || $document_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Form type and document name are required']);
            return;
        }

        if($file_type === ''){
            echo json_encode(['status' => 401, 'message' => 'Please choose at least one valid file format']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT doc_id FROM tbl_formtype_document WHERE document_name = :document_name AND form_id = :form_id");
            $chk->execute([':document_name' => $document_name, ':form_id' => $form_id]);
            if($chk->fetch()){
                echo json_encode(['status' => 401, 'message' => 'This document already exists for that form type']);
                return;
            }

            $file_name = $this->handle_file_upload();
            if($file_name === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid template file type']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_formtype_document
                                            (form_id, document_name, file_type, max_size_mb, file_name, is_required, international_required, status)
                                            VALUES (:form_id, :document_name, :file_type, :max_size_mb, :file_name, :is_required, :international_required, 1)");
            $sql->execute([
                ':form_id' => $form_id,
                ':document_name' => $document_name,
                ':file_type' => $file_type,
                ':max_size_mb' => $max_size_mb,
                ':file_name' => $file_name,
                ':is_required' => $is_required,
                ':international_required' => $international_required
            ]);

            echo json_encode([
                'status' => 200,
                'message' => 'Document saved successfully',
                'doc_id' => $this->connect->lastInsertId(),
                'form_id' => $form_id,
                'document_name' => $document_name,
                'file_type' => $file_type,
                'max_size_mb' => $max_size_mb,
                'file_name' => $file_name,
                'is_required' => $is_required,
                'international_required' => $international_required
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving document: '.$e->getMessage()]);
        }
    }

    public function view_doc(){
        $id = $_POST['id'] ?? '';

        $sql = $this->connect->prepare("SELECT * FROM tbl_formtype_document WHERE doc_id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Document not found']);
        }
    }

    public function update_doc(){
        $id = $_POST['id'] ?? '';
        $form_id = $_POST['form_id'] ?? '';
        $document_name = trim($_POST['document_name'] ?? '');
        $file_type = $this->clean_formats($_POST['file_type'] ?? '');
        $max_size_mb = trim($_POST['max_size_mb'] ?? '') ?: null;
        $is_required = $_POST['is_required'] ?? 1;
        $international_required = $_POST['international_required'] ?? 0;

        if($form_id === '' || $document_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Form type and document name are required']);
            return;
        }

        if($file_type === ''){
            echo json_encode(['status' => 401, 'message' => 'Please choose at least one valid file format']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT doc_id FROM tbl_formtype_document WHERE document_name = :document_name AND form_id = :form_id AND doc_id != :id");
            $chk->execute([':document_name' => $document_name, ':form_id' => $form_id, ':id' => $id]);
            if($chk->fetch()){
                echo json_encode(['status' => 401, 'message' => 'This document already exists for that form type']);
                return;
            }

            $chkOld = $this->connect->prepare("SELECT form_id FROM tbl_formtype_document WHERE doc_id = :id");
            $chkOld->execute([':id' => $id]);
            $old = $chkOld->fetch(PDO::FETCH_ASSOC);
            $form_changed = !$old || $old['form_id'] != $form_id;

            $file_name = $this->handle_file_upload();
            if($file_name === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid template file type']);
                return;
            }

            if($file_name !== null){
                $sql = $this->connect->prepare("UPDATE tbl_formtype_document SET form_id=:form_id, document_name=:document_name,
                                                file_type=:file_type, max_size_mb=:max_size_mb, file_name=:file_name,
                                                is_required=:is_required, international_required=:international_required
                                                WHERE doc_id=:id");
                $sql->execute([
                    ':form_id' => $form_id,
                    ':document_name' => $document_name,
                    ':file_type' => $file_type,
                    ':max_size_mb' => $max_size_mb,
                    ':file_name' => $file_name,
                    ':is_required' => $is_required,
                    ':international_required' => $international_required,
                    ':id' => $id
                ]);
            } else {
                $sql = $this->connect->prepare("UPDATE tbl_formtype_document SET form_id=:form_id, document_name=:document_name,
                                                file_type=:file_type, max_size_mb=:max_size_mb,
                                                is_required=:is_required, international_required=:international_required
                                                WHERE doc_id=:id");
                $sql->execute([
                    ':form_id' => $form_id,
                    ':document_name' => $document_name,
                    ':file_type' => $file_type,
                    ':max_size_mb' => $max_size_mb,
                    ':is_required' => $is_required,
                    ':international_required' => $international_required,
                    ':id' => $id
                ]);
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Document updated successfully',
                'doc_id' => $id,
                'form_id' => $form_id,
                'document_name' => $document_name,
                'file_type' => $file_type,
                'max_size_mb' => $max_size_mb,
                'file_name' => $file_name,
                'is_required' => $is_required,
                'international_required' => $international_required,
                'form_changed' => $form_changed
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating document: '.$e->getMessage()]);
        }
    }

    public function delete_doc(){
        $id = $_POST['id'] ?? '';

        try{
            $sql = $this->connect->prepare("SELECT status FROM tbl_formtype_document WHERE doc_id = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                echo json_encode(['status' => 401, 'message' => 'Document not found']);
                return;
            }

            $new_status = $row['status'] == 1 ? 0 : 1;

            $upd = $this->connect->prepare("UPDATE tbl_formtype_document SET status = :status WHERE doc_id = :id");
            $upd->execute([':status' => $new_status, ':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'Document status updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }

    /**
     * Used by the applicant-facing forms: the document requirements for a form
     * type. Callers merge these with the programme list from tbl_document_type.
     */
    public function load_documents(){
        $form_id = $_POST['form_id'] ?? '';

        $sql = $this->connect->prepare("SELECT doc_id, document_name, file_type, max_size_mb, file_name, is_required, international_required
                                        FROM tbl_formtype_document
                                        WHERE form_id = :form_id AND status = 1
                                        ORDER BY document_name ASC");
        $sql->execute([':form_id' => $form_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$doc = new FormTypeDocument();
$action = $_POST['action'] ?? '';

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
    case 'load_documents':
        $doc->load_documents();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
