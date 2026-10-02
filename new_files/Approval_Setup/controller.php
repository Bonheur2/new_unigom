<?php
// Controller for Approval_Setup/index.php - configures the approval route for
// each form, and the capabilities of each role.
//
// No role is referenced by id or name anywhere here; everything is data.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(LIB_PATH.DS."approval.php");

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

class ApprovalSetup{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    /** Every setup action requires the same capability. */
    private function guard(){
        if(!can('can_manage_setup')){
            echo json_encode(['status' => 401, 'message' => 'You do not have permission to manage approval setup']);
            return false;
        }
        return true;
    }

    // ---------------- workflow steps ----------------

    public function load_steps(){
        $form_id = $_POST['form_id'] ?? '';

        $sql = $this->connect->prepare("SELECT w.workflow_id, w.step_order, w.role_id, w.step_name,
                                               w.step_type, w.is_required, w.status,
                                               r.role AS role_name
                                        FROM tbl_approval_workflows w
                                        LEFT JOIN tbl_user_roles r ON r.role_id = w.role_id
                                        WHERE w.form_id = :form_id
                                        ORDER BY w.step_order ASC");
        $sql->execute([':form_id' => $form_id]);

        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function save_step(){
        if(!$this->guard()){ return; }

        $form_id    = $_POST['form_id'] ?? '';
        $role_id    = $_POST['role_id'] ?? '';
        $step_name  = trim($_POST['step_name'] ?? '');
        $step_type  = ($_POST['step_type'] ?? 'approval') === 'invoice' ? 'invoice' : 'approval';

        if($form_id === '' || $role_id === '' || $step_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Form, role and step name are required']);
            return;
        }

        try{
            // append to the end of this form's route
            $nxt = $this->connect->prepare("SELECT COALESCE(MAX(step_order), 0) + 1 AS nxt
                                            FROM tbl_approval_workflows WHERE form_id = :form_id");
            $nxt->execute([':form_id' => $form_id]);
            $step_order = (int) $nxt->fetch(PDO::FETCH_ASSOC)['nxt'];

            $sql = $this->connect->prepare("INSERT INTO tbl_approval_workflows
                (form_id, step_order, role_id, step_name, step_type, is_required, status)
                VALUES (:form_id, :step_order, :role_id, :step_name, :step_type, 1, 1)");
            $sql->execute([
                ':form_id'    => $form_id,
                ':step_order' => $step_order,
                ':role_id'    => $role_id,
                ':step_name'  => $step_name,
                ':step_type'  => $step_type
            ]);

            echo json_encode(['status' => 200, 'message' => 'Step added', 'step_order' => $step_order]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error adding step: '.$e->getMessage()]);
        }
    }

    public function delete_step(){
        if(!$this->guard()){ return; }

        $workflow_id = $_POST['workflow_id'] ?? '';

        try{
            $get = $this->connect->prepare("SELECT form_id, step_order FROM tbl_approval_workflows WHERE workflow_id = :id");
            $get->execute([':id' => $workflow_id]);
            $row = $get->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                echo json_encode(['status' => 401, 'message' => 'Step not found']);
                return;
            }

            // A route with requests in flight must not be re-numbered underneath
            // them - a pending request points at a step_order that would move.
            $inflight = $this->connect->prepare("SELECT request_id FROM tbl_approval_requests
                                                 WHERE form_id = :form_id AND status = 'pending' LIMIT 1");
            $inflight->execute([':form_id' => $row['form_id']]);
            if($inflight->fetch()){
                echo json_encode(['status' => 401, 'message' => 'This form has requests still in progress. Finish or cancel them before changing its route.']);
                return;
            }

            $this->connect->beginTransaction();

            $del = $this->connect->prepare("DELETE FROM tbl_approval_workflows WHERE workflow_id = :id");
            $del->execute([':id' => $workflow_id]);

            // close the gap so step_order stays 1..n with no holes
            $shift = $this->connect->prepare("UPDATE tbl_approval_workflows
                                              SET step_order = step_order - 1
                                              WHERE form_id = :form_id AND step_order > :step_order");
            $shift->execute([':form_id' => $row['form_id'], ':step_order' => $row['step_order']]);

            $this->connect->commit();

            echo json_encode(['status' => 200, 'message' => 'Step removed']);
        } catch(PDOException $e){
            if($this->connect->inTransaction()){ $this->connect->rollBack(); }
            echo json_encode(['status' => 500, 'message' => 'Error removing step: '.$e->getMessage()]);
        }
    }

    /** Move a step one place up or down in its form's route. */
    public function move_step(){
        if(!$this->guard()){ return; }

        $workflow_id = $_POST['workflow_id'] ?? '';
        $direction   = ($_POST['direction'] ?? '') === 'up' ? 'up' : 'down';

        try{
            $get = $this->connect->prepare("SELECT form_id, step_order FROM tbl_approval_workflows WHERE workflow_id = :id");
            $get->execute([':id' => $workflow_id]);
            $row = $get->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                echo json_encode(['status' => 401, 'message' => 'Step not found']);
                return;
            }

            $inflight = $this->connect->prepare("SELECT request_id FROM tbl_approval_requests
                                                 WHERE form_id = :form_id AND status = 'pending' LIMIT 1");
            $inflight->execute([':form_id' => $row['form_id']]);
            if($inflight->fetch()){
                echo json_encode(['status' => 401, 'message' => 'This form has requests still in progress. Finish or cancel them before changing its route.']);
                return;
            }

            $op = $direction === 'up' ? '<' : '>';
            $ord = $direction === 'up' ? 'DESC' : 'ASC';
            $nb = $this->connect->prepare("SELECT workflow_id, step_order FROM tbl_approval_workflows
                                           WHERE form_id = :form_id AND step_order $op :step_order
                                           ORDER BY step_order $ord LIMIT 1");
            $nb->execute([':form_id' => $row['form_id'], ':step_order' => $row['step_order']]);
            $neighbour = $nb->fetch(PDO::FETCH_ASSOC);

            if(!$neighbour){
                echo json_encode(['status' => 401, 'message' => 'Already at the '.($direction === 'up' ? 'first' : 'last').' position']);
                return;
            }

            $this->connect->beginTransaction();

            // park on a value that cannot collide with the UNIQUE (form_id, step_order)
            $park = $this->connect->prepare("UPDATE tbl_approval_workflows SET step_order = 0 WHERE workflow_id = :id");
            $park->execute([':id' => $workflow_id]);

            $mv = $this->connect->prepare("UPDATE tbl_approval_workflows SET step_order = :so WHERE workflow_id = :id");
            $mv->execute([':so' => $row['step_order'], ':id' => $neighbour['workflow_id']]);
            $mv->execute([':so' => $neighbour['step_order'], ':id' => $workflow_id]);

            $this->connect->commit();

            echo json_encode(['status' => 200, 'message' => 'Step moved']);
        } catch(PDOException $e){
            if($this->connect->inTransaction()){ $this->connect->rollBack(); }
            echo json_encode(['status' => 500, 'message' => 'Error moving step: '.$e->getMessage()]);
        }
    }

    // ---------------- per-form settings ----------------

    public function load_form_setting(){
        echo json_encode(['status' => 200, 'data' => approval_form_setting($this->connect, $_POST['form_id'] ?? 0)]);
    }

    public function save_form_setting(){
        if(!$this->guard()){ return; }

        $form_id = (int) ($_POST['form_id'] ?? 0);
        $doc = $_POST['completion_document'] ?? 'none';
        if($form_id <= 0){
            echo json_encode(['status' => 401, 'message' => 'Choose a form first']);
            return;
        }
        if(!in_array($doc, ['none','admission_letter','registration_proof'], true)){
            echo json_encode(['status' => 401, 'message' => 'Invalid document type']);
            return;
        }

        try{
            $sql = $this->connect->prepare("INSERT INTO tbl_approval_form_settings
                    (form_id, completion_document, signatory_name, signatory_title)
                VALUES (:f, :d, :n, :t)
                ON DUPLICATE KEY UPDATE completion_document = VALUES(completion_document),
                    signatory_name = VALUES(signatory_name), signatory_title = VALUES(signatory_title)");
            $sql->execute([
                ':f' => $form_id, ':d' => $doc,
                ':n' => trim($_POST['signatory_name'] ?? '') ?: null,
                ':t' => trim($_POST['signatory_title'] ?? '') ?: null
            ]);
            echo json_encode(['status' => 200, 'message' => 'Form settings saved']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving settings: '.$e->getMessage()]);
        }
    }

    // ---------------- role capabilities ----------------

    public function load_roles(){
        // SELECT * so the grid keeps working if a capability column is
        // added later (or has not been migrated yet)
        $sql = $this->connect->prepare("SELECT * FROM tbl_user_roles ORDER BY role ASC");
        $sql->execute();
        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function save_role_caps(){
        if(!$this->guard()){ return; }

        $role_id = $_POST['role_id'] ?? '';
        if($role_id === ''){
            echo json_encode(['status' => 401, 'message' => 'Role is required']);
            return;
        }

        $flags = ['can_review_applications','can_select_choice','can_assign_courses','can_final_approve',
                  'can_invoice','can_reopen_rejected','can_manage_setup'];

        // only write capabilities whose column exists, so saving still works
        // before a newer capability's migration has been run
        $cols = $this->connect->query("SELECT * FROM tbl_user_roles LIMIT 1")->fetch(PDO::FETCH_ASSOC) ?: [];
        if($cols){ $flags = array_values(array_filter($flags, function($f) use ($cols){ return array_key_exists($f, $cols); })); }
        $allowed_scopes = ['department','faculty','campus','university'];

        $scope = $_POST['scope_level'] ?? 'department';
        if(!in_array($scope, $allowed_scopes, true)){ $scope = 'department'; }

        try{
            $sets = [];
            $params = [':role_id' => $role_id, ':scope_level' => $scope];
            foreach($flags as $f){
                $sets[] = "$f = :$f";
                $params[':'.$f] = (isset($_POST[$f]) && $_POST[$f] == '1') ? 1 : 0;
            }
            $sets[] = "scope_level = :scope_level";

            $sql = $this->connect->prepare("UPDATE tbl_user_roles SET ".implode(', ', $sets)." WHERE role_id = :role_id");
            $sql->execute($params);

            // if the signed-in user just changed their OWN role, refresh the
            // session so the UI reflects it without a re-login
            if((int) $role_id === (int) ($_SESSION['role_id'] ?? 0)){
                load_capabilities($this->connect, $role_id);
            }

            echo json_encode(['status' => 200, 'message' => 'Role permissions saved']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving permissions: '.$e->getMessage()]);
        }
    }
}

$setup = new ApprovalSetup();
$action = $_POST['action'] ?? '';

switch($action){
    case 'load_steps':
        $setup->load_steps();
        break;
    case 'save_step':
        $setup->save_step();
        break;
    case 'delete_step':
        $setup->delete_step();
        break;
    case 'move_step':
        $setup->move_step();
        break;
    case 'load_roles':
        $setup->load_roles();
        break;
    case 'save_role_caps':
        $setup->save_role_caps();
        break;
    case 'load_form_setting':
        $setup->load_form_setting();
        break;
    case 'save_form_setting':
        $setup->save_form_setting();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
