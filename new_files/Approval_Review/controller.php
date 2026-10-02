<?php
// Controller for Approval_Review/index.php - the reviewer queue.
//
// Every permission decision comes from capability columns and the configured
// workflow. No role is named or numbered anywhere in this file.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(LIB_PATH.DS."approval.php");
require_once(LIB_PATH.DS."approval_documents.php");
if(file_exists(SITE_ROOT.DS."Config".DS."mailer.php")){
    require_once(SITE_ROOT.DS."Config".DS."mailer.php");
}

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

class ApprovalReview{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function my_role(){
        return (int) ($_SESSION['role_id'] ?? 0);
    }

    /**
     * The queue: requests sitting on a step this user's role is configured for,
     * narrowed to what their scope lets them see.
     *
     * Before a choice is selected the request has no single department yet, so
     * a department-scoped reviewer must see it if ANY of the applicant's options
     * falls inside their scope - otherwise a second-choice applicant would be
     * invisible to the department that might want them.
     */
    /** The queue, via the shared approval_queue() so the list page and this
     *  endpoint can never disagree about who sees what. */
    public function load_queue(){
        if(!can('can_review_applications')){
            echo json_encode(['status' => 401, 'message' => 'Your role cannot review applications']);
            return;
        }

        try{
            $rows = approval_queue($this->connect, $_POST['status'] ?? 'pending');
            echo json_encode(['status' => 200, 'data' => $rows]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading queue: '.$e->getMessage()]);
        }
    }

    /** One request in full: applicant, their options, and the action history. */
    public function view_request(){
        if(!can('can_review_applications')){
            echo json_encode(['status' => 401, 'message' => 'Your role cannot review applications']);
            return;
        }

        $request_id = $_POST['request_id'] ?? '';

        if(!approval_can_view($this->connect, $request_id)){
            echo json_encode(['status' => 401, 'message' => 'This request is outside your scope']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT r.*, a.fname, a.mname, a.lname, a.email, a.phone,
                                                   a.gender, a.dob, a.place_of_birth,
                                                   f.form_name
                                            FROM tbl_approval_requests r
                                            INNER JOIN tbl_applicants a ON a.applicant_id = r.applicant_id
                                            LEFT JOIN tbl_form_types f ON f.form_id = r.form_id
                                            WHERE r.request_id = :id");
            $sql->execute([':id' => $request_id]);
            $request = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$request){
                echo json_encode(['status' => 401, 'message' => 'Request not found']);
                return;
            }

            // the applicant's options
            $opts = $this->connect->prepare("SELECT prg.Aprg_id, prg.sts,
                                                    camp.camp_full_name, fac.fac_full_name,
                                                    pt.prg_type_full_name, dept.dept_full_name,
                                                    opt.opt_full_name, lvl.level_full_name
                                             FROM tbl_admittedPRG prg
                                             LEFT JOIN tbl_campus camp ON camp.camp_id = prg.cump_id
                                             LEFT JOIN tbl_faculty fac ON fac.fac_id = prg.fac_id
                                             LEFT JOIN tbl_program_type pt ON pt.prg_type_id = prg.prg_type
                                             LEFT JOIN tbl_department dept ON dept.dept_id = prg.dept_id
                                             LEFT JOIN tbl_option opt ON opt.opt_id = prg.splz
                                             LEFT JOIN tbl_level lvl ON lvl.level_id = prg.level
                                             WHERE prg.Stu_code = :code
                                             ORDER BY prg.Aprg_id ASC");
            $opts->execute([':code' => $request['application_code']]);

            // history
            $log = $this->connect->prepare("SELECT act.step_order, act.action, act.comment, act.acted_at,
                                                   u.first_name, u.family_name, ro.role
                                            FROM tbl_approval_actions act
                                            LEFT JOIN tbl_users u ON u.id = act.user_id
                                            LEFT JOIN tbl_user_roles ro ON ro.role_id = act.role_id
                                            WHERE act.request_id = :id
                                            ORDER BY act.acted_at ASC");
            $log->execute([':id' => $request_id]);

            // the configured route, so the reviewer sees where this sits
            $route = $this->connect->prepare("SELECT w.step_order, w.step_name, w.step_type, ro.role
                                              FROM tbl_approval_workflows w
                                              LEFT JOIN tbl_user_roles ro ON ro.role_id = w.role_id
                                              WHERE w.form_id = :form_id AND w.status = 1
                                              ORDER BY w.step_order ASC");
            $route->execute([':form_id' => $request['form_id']]);

            echo json_encode([
                'status'   => 200,
                'request'  => $request,
                'options'  => $opts->fetchAll(PDO::FETCH_ASSOC),
                'history'  => $log->fetchAll(PDO::FETCH_ASSOC),
                'route'    => $route->fetchAll(PDO::FETCH_ASSOC),
                'may_act'  => may_act_on($this->connect, $request),
                'may_select' => can('can_select_choice')
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error loading request: '.$e->getMessage()]);
        }
    }

    /**
     * Approve the current step. If a choice must be selected and this role may
     * select, the chosen option is recorded here. Moving past the last step
     * finalises the request.
     */
    public function approve(){
        $request_id = $_POST['request_id'] ?? '';
        $comment    = trim($_POST['comment'] ?? '');
        $aprg_id    = $_POST['selected_aprg_id'] ?? '';

        try{
            $sql = $this->connect->prepare("SELECT * FROM tbl_approval_requests WHERE request_id = :id");
            $sql->execute([':id' => $request_id]);
            $request = $sql->fetch(PDO::FETCH_ASSOC);

            if(!may_act_on($this->connect, $request)){
                echo json_encode(['status' => 401, 'message' => 'This request is not waiting on your role at its current step']);
                return;
            }

            $step = current_step($this->connect, $request['form_id'], $request['current_step']);

            // record a choice if one was sent and this role may select
            if($aprg_id !== '' && can('can_select_choice')){
                $chk = $this->connect->prepare("SELECT Aprg_id FROM tbl_admittedPRG
                                                WHERE Aprg_id = :aprg AND Stu_code = :code");
                $chk->execute([':aprg' => $aprg_id, ':code' => $request['application_code']]);
                if(!$chk->fetch()){
                    echo json_encode(['status' => 401, 'message' => 'That option does not belong to this applicant']);
                    return;
                }

                $this->connect->beginTransaction();

                $upd = $this->connect->prepare("UPDATE tbl_approval_requests SET selected_aprg_id = :aprg WHERE request_id = :id");
                $upd->execute([':aprg' => $aprg_id, ':id' => $request_id]);

                // mark the chosen row, clear the others
                $clr = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 0 WHERE Stu_code = :code");
                $clr->execute([':code' => $request['application_code']]);
                $set = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 1 WHERE Aprg_id = :aprg");
                $set->execute([':aprg' => $aprg_id]);

                log_action($this->connect, $request_id, $request['current_step'], 'selected', 'Selected option #'.$aprg_id);

                $this->connect->commit();
                $request['selected_aprg_id'] = $aprg_id;
            }

            // a request cannot be finalised without a placement
            if(is_final_step($this->connect, $request['form_id'], $request['current_step'])
               && empty($request['selected_aprg_id'])){
                echo json_encode(['status' => 401, 'message' => 'No option has been selected for this applicant yet, so the request cannot be completed.']);
                return;
            }

            $next = next_step_order($this->connect, $request['form_id'], $request['current_step']);

            $this->connect->beginTransaction();

            log_action($this->connect, $request_id, $request['current_step'],
                       $step['step_type'] === 'invoice' ? 'invoiced' : 'approved', $comment);

            if($next === null){
                if(!can('can_final_approve')){
                    $this->connect->rollBack();
                    echo json_encode(['status' => 401, 'message' => 'Your role cannot give final approval']);
                    return;
                }
                // This is where the applicant becomes a student: a reg_no is
                // issued, tbl_admission is written, and they are registered on
                // the option that was selected earlier in the route.
                $reg_no = $this->admit_applicant($request);

                $fin = $this->connect->prepare("UPDATE tbl_approval_requests
                                                SET status = 'approved', reg_no = :reg_no, completed_at = NOW()
                                                WHERE request_id = :id");
                $fin->execute([':reg_no' => $reg_no, ':id' => $request_id]);
                $message = 'Approved. Registration number '.$reg_no.' issued.';
            } else {
                $mv = $this->connect->prepare("UPDATE tbl_approval_requests SET current_step = :next WHERE request_id = :id");
                $mv->execute([':next' => $next, ':id' => $request_id]);
                $message = 'Approved and passed to the next step.';
            }

            $this->connect->commit();

            // The admission is committed. Issuing the letter happens afterwards
            // and can never undo it - a failure is reported, not rolled back.
            if($next === null){
                $doc = approval_issue_document($this->connect, $request_id);
                if($doc['message'] !== ''){ $message .= ' '.$doc['message']; }
            }

            echo json_encode(['status' => 200, 'message' => $message, 'final' => $next === null]);
        } catch(Exception $e){
            // Exception, not PDOException: admit_applicant() throws RuntimeException
            // for a missing applicant or option, and that must roll back too -
            // otherwise the request would be marked approved with no student record.
            if($this->connect->inTransaction()){ $this->connect->rollBack(); }
            echo json_encode(['status' => 500, 'message' => 'Error approving: '.$e->getMessage()]);
        }
    }

    /**
     * Turns an approved applicant into a student.
     *
     * Copies their identity from tbl_applicants into tbl_admission with a fresh
     * reg_no, then registers them in tbl_register_program_ug on the option that
     * was selected during the route. Runs inside the caller's transaction, so a
     * failure here rolls the whole approval back rather than leaving a student
     * half-created.
     *
     * reg_no generation reads the last number and adds one, which two
     * simultaneous approvals could collide on - the UNIQUE index on
     * tbl_admission.reg_no is what catches that, and this retries.
     *
     * @return string the issued reg_no
     */
    private function admit_applicant($request){
        // the applicant's full record
        $app = $this->connect->prepare("SELECT * FROM tbl_applicants WHERE applicant_id = :id");
        $app->execute([':id' => $request['applicant_id']]);
        $a = $app->fetch(PDO::FETCH_ASSOC);
        if(!$a){
            throw new RuntimeException('Applicant record not found');
        }

        // the selected placement
        $sel = $this->connect->prepare("SELECT * FROM tbl_admittedPRG WHERE Aprg_id = :aprg");
        $sel->execute([':aprg' => $request['selected_aprg_id']]);
        $prg = $sel->fetch(PDO::FETCH_ASSOC);
        if(!$prg){
            throw new RuntimeException('The selected programme option no longer exists');
        }

        // already admitted? reuse rather than issuing a second reg_no
        $chk = $this->connect->prepare("SELECT reg_no FROM tbl_admission WHERE applicant_code = :code LIMIT 1");
        $chk->execute([':code' => $request['application_code']]);
        $existing = $chk->fetch(PDO::FETCH_ASSOC);
        if($existing){
            return $existing['reg_no'];
        }

        $ins = $this->connect->prepare("INSERT INTO tbl_admission
            (reg_no, applicant_code, intake_id, acad_cycle_id, fname, mname, lname, email,
             nationality, marital_status, phone, gender, father_names, mother_names,
             parent_phone, ref_phone, country, created_at)
            VALUES
            (:reg_no, :applicant_code, :intake_id, :acad_cycle_id, :fname, :mname, :lname, :email,
             :nationality, :marital_status, :phone, :gender, :father_names, :mother_names,
             :parent_phone, :ref_phone, :country, NOW())");

        $reg_no = null;
        $attempts = 0;
        while($reg_no === null && $attempts < 5){
            $attempts++;
            $candidate = generate_reg_no($this->connect);
            try {
                $ins->execute([
                    ':reg_no'         => $candidate,
                    ':applicant_code' => $request['application_code'],
                    ':intake_id'      => $prg['intake_id'] ?? null,
                    ':acad_cycle_id'  => $prg['acad_id'] ?? null,
                    ':fname'          => $a['fname'] ?? null,
                    ':mname'          => $a['mname'] ?? null,
                    ':lname'          => $a['lname'] ?? null,
                    ':email'          => $a['email'] ?? null,
                    ':nationality'    => $a['nationality_id'] ?? null,
                    ':marital_status' => $a['marital_status'] ?? null,
                    ':phone'          => $a['phone'] ?? null,
                    ':gender'         => $a['gender'] ?? null,
                    ':father_names'   => $a['father_name'] ?? null,
                    ':mother_names'   => $a['mother_name'] ?? null,
                    ':parent_phone'   => $a['parent_phone'] ?? null,
                    ':ref_phone'      => $a['ref_phone'] ?? null,
                    ':country'        => $a['country_id'] ?? null
                ]);
                $reg_no = $candidate;
            } catch(PDOException $dup){
                // 23000 = duplicate key: another approval took this number first
                if($dup->getCode() == 23000 && $attempts < 5){
                    continue;
                }
                throw $dup;
            }
        }

        if($reg_no === null){
            throw new RuntimeException('Could not allocate a registration number');
        }

        // register them on the selected programme
        $reg = $this->connect->prepare("INSERT INTO tbl_register_program_ug
            (reg_no, acad_cycle_id, prg_id, splz_id, level_id, intake_id, prg_mode_id,
             prg_type, fac_id, dept_id, camp_id, reg_active)
            VALUES
            (:reg_no, :acad_cycle_id, :prg_id, :splz_id, :level_id, :intake_id, :prg_mode_id,
             :prg_type, :fac_id, :dept_id, :camp_id, 1)");
        $reg->execute([
            ':reg_no'        => $reg_no,
            ':acad_cycle_id' => $prg['acad_id'] ?? null,
            ':prg_id'        => $prg['prg_type'] ?? null,
            ':splz_id'       => $prg['splz'] ?? null,
            ':level_id'      => $prg['level'] ?? null,
            ':intake_id'     => $prg['intake_id'] ?? null,
            ':prg_mode_id'   => $prg['mode'] ?? null,
            ':prg_type'      => $prg['prg_type'] ?? null,
            ':fac_id'        => $prg['fac_id'] ?? null,
            ':dept_id'       => $prg['dept_id'] ?? null,
            ':camp_id'       => $prg['cump_id'] ?? null
        ]);

        return $reg_no;
    }

    /** Reject. Terminal, and a reason is required. */
    public function reject(){
        $request_id = $_POST['request_id'] ?? '';
        $comment    = trim($_POST['comment'] ?? '');

        if($comment === ''){
            echo json_encode(['status' => 401, 'message' => 'Please give a reason for the rejection']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT * FROM tbl_approval_requests WHERE request_id = :id");
            $sql->execute([':id' => $request_id]);
            $request = $sql->fetch(PDO::FETCH_ASSOC);

            if(!may_act_on($this->connect, $request)){
                echo json_encode(['status' => 401, 'message' => 'This request is not waiting on your role at its current step']);
                return;
            }

            $this->connect->beginTransaction();

            log_action($this->connect, $request_id, $request['current_step'], 'rejected', $comment);

            $upd = $this->connect->prepare("UPDATE tbl_approval_requests
                                            SET status = 'rejected', completed_at = NOW()
                                            WHERE request_id = :id");
            $upd->execute([':id' => $request_id]);

            $this->connect->commit();

            echo json_encode(['status' => 200, 'message' => 'Request rejected']);
        } catch(PDOException $e){
            if($this->connect->inTransaction()){ $this->connect->rollBack(); }
            echo json_encode(['status' => 500, 'message' => 'Error rejecting: '.$e->getMessage()]);
        }
    }

    /** Send a rejected request back into the workflow at a chosen step. */
    public function reopen(){
        if(!can('can_reopen_rejected')){
            echo json_encode(['status' => 401, 'message' => 'Your role cannot reopen rejected requests']);
            return;
        }

        $request_id = $_POST['request_id'] ?? '';
        $step       = $_POST['step_order'] ?? '1';
        $comment    = trim($_POST['comment'] ?? '');

        if($comment === ''){
            echo json_encode(['status' => 401, 'message' => 'Please say why this is being reopened']);
            return;
        }

        try{
            $sql = $this->connect->prepare("SELECT * FROM tbl_approval_requests WHERE request_id = :id");
            $sql->execute([':id' => $request_id]);
            $request = $sql->fetch(PDO::FETCH_ASSOC);

            if(!$request || $request['status'] !== 'rejected'){
                echo json_encode(['status' => 401, 'message' => 'Only a rejected request can be reopened']);
                return;
            }

            if(!current_step($this->connect, $request['form_id'], $step)){
                echo json_encode(['status' => 401, 'message' => 'That step does not exist on this form\'s route']);
                return;
            }

            $this->connect->beginTransaction();

            log_action($this->connect, $request_id, $step, 'reopened', $comment);

            $upd = $this->connect->prepare("UPDATE tbl_approval_requests
                                            SET status = 'pending', current_step = :step, completed_at = NULL
                                            WHERE request_id = :id");
            $upd->execute([':step' => $step, ':id' => $request_id]);

            $this->connect->commit();

            echo json_encode(['status' => 200, 'message' => 'Request reopened']);
        } catch(PDOException $e){
            if($this->connect->inTransaction()){ $this->connect->rollBack(); }
            echo json_encode(['status' => 500, 'message' => 'Error reopening: '.$e->getMessage()]);
        }
    }
    // ------------------------------------------------------------ courses

    /** May the signed-in user change the courses of this request right now? */
    private function may_edit_courses($request){
        return can('can_assign_courses') && may_act_on($this->connect, $request);
    }

    private function load_request($request_id){
        $st = $this->connect->prepare("SELECT * FROM tbl_approval_requests WHERE request_id = :id");
        $st->execute([':id' => $request_id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Adds a course. The role acting on the current step may add any active
     * course of the applicant's programme that is not already assigned.
     */
    public function assign_course(){
        $request = $this->load_request($_POST['request_id'] ?? 0);
        $course_id = (int) ($_POST['course_id'] ?? 0);

        if(!$request || !$this->may_edit_courses($request)){
            echo json_encode(['status' => 401, 'message' => 'You cannot change the courses of this request at its current step']);
            return;
        }

        // only courses offered to this applicant's programme are allowed
        $allowed = array_column(approval_course_choices($this->connect, $request), 'course_id');
        if(!in_array($course_id, array_map('intval', $allowed), true)){
            echo json_encode(['status' => 401, 'message' => 'That course is not part of this applicant\'s programme']);
            return;
        }

        try{
            $dup = $this->connect->prepare("SELECT approval_course_id FROM tbl_approval_courses
                                            WHERE request_id = :r AND course_id = :c AND status = 'active'");
            $dup->execute([':r' => $request['request_id'], ':c' => $course_id]);
            if($dup->fetch()){
                echo json_encode(['status' => 401, 'message' => 'This course is already assigned']);
                return;
            }

            $ins = $this->connect->prepare("INSERT INTO tbl_approval_courses
                (request_id, course_id, status, assigned_step, assigned_role, assigned_by)
                VALUES (:r, :c, 'active', :step, :role, :by)");
            $ins->execute([
                ':r' => $request['request_id'], ':c' => $course_id,
                ':step' => $request['current_step'],
                ':role' => $this->my_role(), ':by' => $_SESSION['acc_id'] ?? 0
            ]);

            echo json_encode(['status' => 200, 'message' => 'Course assigned']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error assigning course: '.$e->getMessage()]);
        }
    }

    /**
     * Removes a course - including one added at an earlier step. The row is
     * kept and stamped, so the history shows who added it and who removed it.
     */
    public function remove_course(){
        $request = $this->load_request($_POST['request_id'] ?? 0);
        $id = (int) ($_POST['approval_course_id'] ?? 0);

        if(!$request || !$this->may_edit_courses($request)){
            echo json_encode(['status' => 401, 'message' => 'You cannot change the courses of this request at its current step']);
            return;
        }

        try{
            $upd = $this->connect->prepare("UPDATE tbl_approval_courses
                SET status = 'removed', removed_step = :step, removed_role = :role,
                    removed_by = :by, removed_at = NOW()
                WHERE approval_course_id = :id AND request_id = :r AND status = 'active'");
            $upd->execute([
                ':step' => $request['current_step'], ':role' => $this->my_role(),
                ':by' => $_SESSION['acc_id'] ?? 0, ':id' => $id, ':r' => $request['request_id']
            ]);

            if($upd->rowCount() === 0){
                echo json_encode(['status' => 401, 'message' => 'Course not found or already removed']);
                return;
            }
            echo json_encode(['status' => 200, 'message' => 'Course removed']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error removing course: '.$e->getMessage()]);
        }
    }

    // ------------------------------------------------------------ documents

    /**
     * Re-generates and re-emails the completion document of an approved
     * request (e.g. after the applicant changed email, or a send failed).
     */
    public function resend_document(){
        $request = $this->load_request($_POST['request_id'] ?? 0);

        if(!$request || !approval_can_view($this->connect, $request['request_id']) || !can('can_final_approve')){
            echo json_encode(['status' => 401, 'message' => 'You cannot send documents for this request']);
            return;
        }
        if($request['status'] !== 'approved'){
            echo json_encode(['status' => 401, 'message' => 'Documents are only sent for approved requests']);
            return;
        }

        $type = $_POST['doc_type'] ?? '';
        if(!in_array($type, ['admission_letter', 'registration_proof'], true)){
            echo json_encode(['status' => 401, 'message' => 'Choose a document type']);
            return;
        }

        $res = approval_issue_document($this->connect, $request['request_id'], $type);
        echo json_encode(['status' => $res['ok'] ? 200 : 500, 'message' => $res['message']]);
    }
}

$review = new ApprovalReview();
$action = $_POST['action'] ?? '';

switch($action){
    case 'load_queue':
        $review->load_queue();
        break;
    case 'view_request':
        $review->view_request();
        break;
    case 'approve':
        $review->approve();
        break;
    case 'reject':
        $review->reject();
        break;
    case 'reopen':
        $review->reopen();
        break;
    case 'assign_course':
        $review->assign_course();
        break;
    case 'remove_course':
        $review->remove_course();
        break;
    case 'resend_document':
        $review->resend_document();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
