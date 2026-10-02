<?php
/**
 * Approval workflow engine - shared by every review page and form controller.
 *
 * Deliberately contains NO role names and NO role ids. What a role may do comes
 * from capability columns on tbl_user_roles; which role acts at which step comes
 * from tbl_approval_workflows. Both are configuration, so adding a role or
 * re-ordering a route needs no code change.
 */

if(!function_exists('load_capabilities')){

    /**
     * Reads the signed-in user's capabilities into the session. Called once at
     * login; every later can() check is then an in-memory lookup.
     */
    function load_capabilities($conn, $role_id){
        $caps = [
            'can_review_applications' => 0,
            'can_select_choice'       => 0,
            'can_assign_courses'      => 0,
            'can_final_approve'       => 0,
            'can_invoice'             => 0,
            'can_reopen_rejected'     => 0,
            'can_manage_setup'        => 0,
            'scope_level'             => 'department'
        ];

        try {
            // SELECT * rather than naming columns: a capability whose column
            // has not been added yet then simply stays 0, instead of the whole
            // query failing and switching every capability off.
            $sql = $conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id = :role_id");
            $sql->execute([':role_id' => $role_id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            if($row){ $caps = array_merge($caps, array_intersect_key($row, $caps)); }
        } catch(PDOException $e){
            // columns not added yet - fall back to no capabilities rather than
            // crashing every page
            error_log('[approval] capability lookup failed: '.$e->getMessage());
        }

        $caps['_role_id'] = $role_id;
        $_SESSION['caps'] = $caps;
        return $caps;
    }

    /**
     * Makes sure $_SESSION['caps'] actually reflects the signed-in user's
     * CURRENT role_id before anything reads it.
     *
     * load_capabilities() is meant to run once at login, but nothing in the
     * existing login code (log_data.php) calls it - it only ever ran as a
     * side effect of saving your OWN role from the setup page, which is a
     * chicken-and-egg problem (you need can_manage_setup=true to reach that
     * save button in the first place). This closes that gap: any can() or
     * scope_level() call loads capabilities on demand if they are missing, or
     * if the cached caps belong to a different role_id than the one now in
     * the session (e.g. after the role's own id changes mid-session).
     */
    function ensure_capabilities_loaded(){
        $role_id = $_SESSION['role_id'] ?? null;
        if($role_id === null){ return; }

        if(isset($_SESSION['caps']) && ($_SESSION['caps']['_role_id'] ?? null) == $role_id){
            return; // already loaded for this role
        }

        global $conn;
        if(!isset($conn)){ return; }

        load_capabilities($conn, $role_id);
    }

    /** True when the signed-in user's role has the named capability. */
    function can($capability){
        ensure_capabilities_loaded();
        return !empty($_SESSION['caps'][$capability]);
    }

    /** How wide the signed-in role can see. */
    function scope_level(){
        ensure_capabilities_loaded();
        return $_SESSION['caps']['scope_level'] ?? 'department';
    }

    /**
     * Builds the WHERE fragment that limits a queue to what this user may see.
     *
     * The role says HOW WIDE (scope_level); the user's own row says WHICH ONE
     * (their dept_id / fac_id / campus_id). So two users of the same role see
     * different applicants, which is what makes one role reusable.
     *
     * $alias is the table alias holding dept_id / fac_id / camp_id - normally
     * the tbl_admittedPRG row of the applicant's selected choice.
     *
     * @return array [sql_fragment, params]
     */
    function scope_filter($alias = 'prg'){
        $a = $alias !== '' ? $alias.'.' : '';
        return scope_condition($a.'dept_id', $a.'fac_id', $a.'cump_id', 'p');
    }

    /**
     * Scope condition against arbitrary columns, so the same rule can be applied
     * to tbl_admittedPRG and to the request tables that name their columns
     * differently. $tag keeps parameter names unique when several conditions
     * are combined in one query.
     *
     * @return array [sql_fragment, params]
     */
    function scope_condition($dept_col, $fac_col, $camp_expr, $tag){
        switch(scope_level()){
            case 'university':
                return ['1=1', []];

            case 'campus':
                $camp = $_SESSION['camp_id'] ?? null;
                if(!$camp){ return ['1=0', []]; }   // no campus set - see nothing
                return [$camp_expr.' = :sc_camp_'.$tag, [':sc_camp_'.$tag => $camp]];

            case 'faculty':
                $fac = $_SESSION['fac_id'] ?? null;
                if(!$fac){ return ['1=0', []]; }
                return [$fac_col.' = :sc_fac_'.$tag, [':sc_fac_'.$tag => $fac]];

            case 'department':
            default:
                $dept = $_SESSION['dept_id'] ?? null;
                if(!$dept){ return ['1=0', []]; }
                return [$dept_col.' = :sc_dept_'.$tag, [':sc_dept_'.$tag => $dept]];
        }
    }

    /**
     * Can the signed-in user see request r? An applicant's placement lives in
     * different places depending on the form:
     *   - application forms  -> their options in tbl_admittedPRG
     *   - change of domain   -> tbl_domain_change_requests.requested_*
     *   - re-integration     -> tbl_integration_requests.requested_*
     * The request is visible if ANY of those falls inside the user's scope.
     * Once a choice has been selected, only that option counts.
     *
     * @return array [sql_fragment, params] - $r is the tbl_approval_requests alias
     */
    function approval_visibility($r = 'r'){
        if(scope_level() === 'university'){
            return ['1=1', []];
        }

        list($p, $pp) = scope_condition('vprg.dept_id', 'vprg.fac_id', 'vprg.cump_id', 'p');
        list($d, $dp) = scope_condition('vdc.requested_dept_id', 'vdc.requested_fac_id',
                            '(SELECT fx.campus_id FROM tbl_faculty fx WHERE fx.fac_id = vdc.requested_fac_id)', 'd');
        list($i, $ip) = scope_condition('vir.requested_dept_id', 'vir.requested_fac_id',
                            '(SELECT fy.campus_id FROM tbl_faculty fy WHERE fy.fac_id = vir.requested_fac_id)', 'i');

        $sql = "(
            ($r.source_table IS NULL AND EXISTS (
                SELECT 1 FROM tbl_admittedPRG vprg
                WHERE vprg.Stu_code = $r.application_code
                  AND ($r.selected_aprg_id IS NULL OR vprg.Aprg_id = $r.selected_aprg_id)
                  AND ($p)))
            OR ($r.source_table = 'tbl_domain_change_requests' AND EXISTS (
                SELECT 1 FROM tbl_domain_change_requests vdc
                WHERE vdc.request_id = $r.source_id AND ($d)))
            OR ($r.source_table = 'tbl_integration_requests' AND EXISTS (
                SELECT 1 FROM tbl_integration_requests vir
                WHERE vir.request_id = $r.source_id AND ($i)))
        )";

        return [$sql, array_merge($pp, $dp, $ip)];
    }

    /** May the signed-in user open this request at all? */
    function approval_can_view($conn, $request_id){
        if(!can('can_review_applications')){ return false; }

        list($vis, $vp) = approval_visibility('r');
        $sql = $conn->prepare("SELECT 1 FROM tbl_approval_requests r WHERE r.request_id = :rid AND $vis");
        $sql->execute(array_merge([':rid' => $request_id], $vp));
        return $sql->fetch() !== false;
    }

    /**
     * The list behind the review page. 'pending' = waiting on MY role at its
     * current step; 'approved' / 'rejected' = finished requests in my scope.
     *
     * Each row carries a display placement: the requested placement for the
     * request forms, otherwise the selected option (or the first choice while
     * nothing is selected yet).
     */
    function approval_queue($conn, $status){
        if(!in_array($status, ['pending','approved','rejected'], true)){ $status = 'pending'; }

        list($vis, $vp) = approval_visibility('r');

        $placement = "
            LEFT JOIN tbl_admittedPRG sel ON r.source_table IS NULL AND sel.Aprg_id = COALESCE(
                    r.selected_aprg_id,
                    (SELECT MIN(p2.Aprg_id) FROM tbl_admittedPRG p2 WHERE p2.Stu_code = r.application_code))
            LEFT JOIN tbl_domain_change_requests dc ON r.source_table = 'tbl_domain_change_requests' AND dc.request_id = r.source_id
            LEFT JOIN tbl_integration_requests ir ON r.source_table = 'tbl_integration_requests' AND ir.request_id = r.source_id
            LEFT JOIN tbl_faculty fac ON fac.fac_id = COALESCE(dc.requested_fac_id, ir.requested_fac_id, sel.fac_id)
            LEFT JOIN tbl_campus camp ON camp.camp_id = fac.campus_id
            LEFT JOIN tbl_program_type pt ON pt.prg_type_id = COALESCE(dc.requested_prg_type_id, ir.requested_prg_type_id, sel.prg_type)
            LEFT JOIN tbl_department dept ON dept.dept_id = COALESCE(dc.requested_dept_id, ir.requested_dept_id, sel.dept_id)";

        $cols = "r.request_id, r.form_id, r.application_code, r.current_step, r.status,
                 r.selected_aprg_id, r.submitted_at, r.completed_at, r.reg_no,
                 a.fname, a.lname, a.email, a.phone,
                 f.form_name,
                 camp.camp_full_name, fac.fac_full_name, pt.prg_type_full_name, dept.dept_full_name";

        if($status === 'pending'){
            $sql = "SELECT $cols, w.step_name, w.step_type
                    FROM tbl_approval_requests r
                    INNER JOIN tbl_applicants a ON a.applicant_id = r.applicant_id
                    LEFT JOIN tbl_form_types f ON f.form_id = r.form_id
                    INNER JOIN tbl_approval_workflows w
                           ON w.form_id = r.form_id AND w.step_order = r.current_step AND w.status = 1
                    $placement
                    WHERE r.status = 'pending'
                      AND w.role_id = :my_role
                      AND $vis
                    ORDER BY r.submitted_at ASC";
            $params = array_merge([':my_role' => (int) ($_SESSION['role_id'] ?? 0)], $vp);
        } else {
            $sql = "SELECT $cols, NULL AS step_name, NULL AS step_type
                    FROM tbl_approval_requests r
                    INNER JOIN tbl_applicants a ON a.applicant_id = r.applicant_id
                    LEFT JOIN tbl_form_types f ON f.form_id = r.form_id
                    $placement
                    WHERE r.status = :status
                      AND $vis
                    ORDER BY r.completed_at DESC, r.submitted_at DESC";
            $params = array_merge([':status' => $status], $vp);
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    //  Workflow
    // ------------------------------------------------------------------

    /** The configured steps for a form, in order. Empty = no approval stage. */
    function workflow_steps($conn, $form_id){
        try {
            $sql = $conn->prepare("SELECT step_order, role_id, step_name, step_type, is_required
                                   FROM tbl_approval_workflows
                                   WHERE form_id = :form_id AND status = 1
                                   ORDER BY step_order ASC");
            $sql->execute([':form_id' => $form_id]);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            error_log('[approval] workflow lookup failed: '.$e->getMessage());
            return [];
        }
    }

    /** The single step a request is currently sitting on, or null. */
    function current_step($conn, $form_id, $step_order){
        $sql = $conn->prepare("SELECT step_order, role_id, step_name, step_type
                               FROM tbl_approval_workflows
                               WHERE form_id = :form_id AND step_order = :step_order AND status = 1");
        $sql->execute([':form_id' => $form_id, ':step_order' => $step_order]);
        return $sql->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Opens a request when a form is submitted. Called by each form controller.
     * A form with no configured workflow gets no request row, so existing
     * behaviour is unchanged until you configure one.
     *
     * @return int|null the new request_id, or null when the form has no workflow
     */
    function open_approval_request($conn, $form_id, $applicant_id, $application_code,
                                   $source_table = null, $source_id = null){
        if(!$form_id){ return null; }

        $steps = workflow_steps($conn, $form_id);
        if(empty($steps)){ return null; }

        try {
            $sql = $conn->prepare("INSERT INTO tbl_approval_requests
                (form_id, applicant_id, application_code, source_table, source_id, current_step, status)
                VALUES (:form_id, :applicant_id, :application_code, :source_table, :source_id, :current_step, 'pending')");
            $sql->execute([
                ':form_id'          => $form_id,
                ':applicant_id'     => $applicant_id,
                ':application_code' => $application_code,
                ':source_table'     => $source_table,
                ':source_id'        => $source_id,
                ':current_step'     => $steps[0]['step_order']
            ]);
            return $conn->lastInsertId();
        } catch(PDOException $e){
            // a duplicate simply means the applicant already has a request for
            // this form - not an error worth failing the submission over
            error_log('[approval] open request failed: '.$e->getMessage());
            return null;
        }
    }

    /** Append to the audit trail. Never updated, never deleted. */
    function log_action($conn, $request_id, $step_order, $action, $comment = null){
        $sql = $conn->prepare("INSERT INTO tbl_approval_actions
            (request_id, step_order, role_id, user_id, action, comment)
            VALUES (:request_id, :step_order, :role_id, :user_id, :action, :comment)");
        $sql->execute([
            ':request_id' => $request_id,
            ':step_order' => $step_order,
            ':role_id'    => $_SESSION['role_id'] ?? 0,
            ':user_id'    => $_SESSION['acc_id'] ?? 0,
            ':action'     => $action,
            ':comment'    => ($comment !== null && $comment !== '') ? $comment : null
        ]);
    }

    /**
     * May the signed-in user act on this request right now?
     * Three things must hold: the role has the capability, the role is the one
     * configured for the current step, and the request is still pending.
     */
    function may_act_on($conn, $request){
        if(!$request || $request['status'] !== 'pending'){ return false; }
        if(!can('can_review_applications')){ return false; }

        $step = current_step($conn, $request['form_id'], $request['current_step']);
        if(!$step){ return false; }

        if((int) $step['role_id'] !== (int) ($_SESSION['role_id'] ?? 0)){ return false; }

        if($step['step_type'] === 'invoice' && !can('can_invoice')){ return false; }

        // the right role is not enough - the request must also be inside this
        // user's own department / faculty / campus, or anyone holding the role
        // could act on any request by posting its id
        if(!approval_can_view($conn, $request['request_id'])){ return false; }

        return true;
    }

    /** Is this the last step of the route? */
    function is_final_step($conn, $form_id, $step_order){
        $sql = $conn->prepare("SELECT MAX(step_order) AS last_step
                               FROM tbl_approval_workflows
                               WHERE form_id = :form_id AND status = 1");
        $sql->execute([':form_id' => $form_id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row && (int) $row['last_step'] === (int) $step_order;
    }

    /** The step after this one, or null when there is none. */
    function next_step_order($conn, $form_id, $step_order){
        $sql = $conn->prepare("SELECT MIN(step_order) AS nxt
                               FROM tbl_approval_workflows
                               WHERE form_id = :form_id AND status = 1 AND step_order > :step_order");
        $sql->execute([':form_id' => $form_id, ':step_order' => $step_order]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['nxt'] !== null) ? (int) $row['nxt'] : null;
    }

    // ------------------------------------------------------------------
    //  Placement, courses, form settings
    // ------------------------------------------------------------------

    /**
     * Where the request places the applicant: the requested placement for the
     * change-of-domain / re-integration forms, otherwise the selected option
     * (or the first choice while nothing is selected yet).
     *
     * @return array|null prg_type_id, fac_id, camp_id, dept_id, opt_id, level_id, acad_id
     */
    function approval_placement($conn, $request){
        if($request['source_table'] === 'tbl_domain_change_requests' || $request['source_table'] === 'tbl_integration_requests'){
            $st = $conn->prepare("SELECT s.requested_prg_type_id AS prg_type_id, s.requested_fac_id AS fac_id,
                                         f.campus_id AS camp_id, s.requested_dept_id AS dept_id,
                                         s.requested_opt_id AS opt_id, s.requested_level_id AS level_id,
                                         NULL AS acad_id
                                  FROM ".$request['source_table']." s
                                  LEFT JOIN tbl_faculty f ON f.fac_id = s.requested_fac_id
                                  WHERE s.request_id = :id");
            $st->execute([':id' => $request['source_id']]);
            return $st->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        $st = $conn->prepare("SELECT prg_type AS prg_type_id, fac_id, cump_id AS camp_id, dept_id,
                                     splz AS opt_id, level AS level_id, acad_id
                              FROM tbl_admittedPRG
                              WHERE Stu_code = :code
                                AND (:sel IS NULL OR Aprg_id = :sel2)
                              ORDER BY Aprg_id ASC LIMIT 1");
        $st->execute([':code' => $request['application_code'],
                      ':sel'  => $request['selected_aprg_id'],
                      ':sel2' => $request['selected_aprg_id']]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Courses a reviewer may assign: every active course of the applicant's
     * programme, at any level (so earlier-level courses can be added for
     * catch-up), restricted to units common to the programme or belonging to
     * the applicant's department / option.
     */
    function approval_course_choices($conn, $request){
        $p = approval_placement($conn, $request);
        if(!$p || empty($p['prg_type_id'])){ return []; }

        $st = $conn->prepare("SELECT c.course_id, c.course_code, c.course_name, c.credits,
                                     u.ue_id, u.ue_code, u.ue_name, u.semester_no,
                                     lvl.level_full_name, lvl.level_rank
                              FROM tbl_courses c
                              INNER JOIN tbl_teaching_units u ON u.ue_id = c.ue_id
                              LEFT JOIN tbl_level lvl ON lvl.level_id = u.level_id
                              WHERE u.prg_type_id = :prg
                                AND c.status = 1 AND u.status = 1
                                AND (u.dept_id IS NULL OR u.dept_id = :dept)
                                AND (u.opt_id IS NULL OR u.opt_id = :opt)
                              ORDER BY lvl.level_rank, u.semester_no, u.ue_code, c.course_name");
        $st->execute([':prg' => $p['prg_type_id'], ':dept' => $p['dept_id'] ?? 0, ':opt' => $p['opt_id'] ?? 0]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Courses currently assigned to the request (removed ones are history). */
    function approval_assigned_courses($conn, $request_id, $include_removed = false){
        $st = $conn->prepare("SELECT ac.*, c.course_code, c.course_name, c.credits,
                                     u.ue_code, u.ue_name, u.semester_no, lvl.level_full_name,
                                     ua.first_name AS added_first, ua.family_name AS added_last, ra.role AS added_role,
                                     ur.first_name AS removed_first, ur.family_name AS removed_last, rr.role AS removed_role_name
                              FROM tbl_approval_courses ac
                              INNER JOIN tbl_courses c ON c.course_id = ac.course_id
                              LEFT JOIN tbl_teaching_units u ON u.ue_id = c.ue_id
                              LEFT JOIN tbl_level lvl ON lvl.level_id = u.level_id
                              LEFT JOIN tbl_users ua ON ua.id = ac.assigned_by
                              LEFT JOIN tbl_user_roles ra ON ra.role_id = ac.assigned_role
                              LEFT JOIN tbl_users ur ON ur.id = ac.removed_by
                              LEFT JOIN tbl_user_roles rr ON rr.role_id = ac.removed_role
                              WHERE ac.request_id = :id".($include_removed ? "" : " AND ac.status = 'active'")."
                              ORDER BY ac.status ASC, lvl.level_rank, u.semester_no, u.ue_code, c.course_name");
        $st->execute([':id' => $request_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Per-form settings, with defaults when the form has no row yet. */
    function approval_form_setting($conn, $form_id){
        $default = ['form_id' => $form_id, 'completion_document' => 'none',
                    'signatory_name' => '', 'signatory_title' => ''];
        try {
            $st = $conn->prepare("SELECT * FROM tbl_approval_form_settings WHERE form_id = :f");
            $st->execute([':f' => $form_id]);
            $row = $st->fetch(PDO::FETCH_ASSOC);
            return $row ? array_merge($default, $row) : $default;
        } catch(PDOException $e){
            return $default;   // table not created yet
        }
    }

    // ------------------------------------------------------------------
    //  Identifiers
    // ------------------------------------------------------------------

    /**
     * Next registration number: 2-digit year + 'UG' + 4-digit sequence.
     * Relies on the UNIQUE index on tbl_admission.reg_no - the caller retries
     * on a duplicate-key error, which is what makes concurrent approvals safe.
     */
    function generate_reg_no($conn){
        $prefix = date('y').'UG';

        $sql = $conn->prepare("SELECT reg_no FROM tbl_admission
                               WHERE reg_no LIKE :prefix
                               ORDER BY adm_id DESC LIMIT 1");
        $sql->execute([':prefix' => $prefix.'%']);
        $last = $sql->fetch(PDO::FETCH_ASSOC);

        $next = $last ? ((int) substr($last['reg_no'], -4)) + 1 : 1;
        return $prefix.str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /** Next invoice number: 2-digit year + 'INV' + 5-digit sequence. */
    function generate_invoice_no($conn){
        $prefix = date('y').'INV';

        $sql = $conn->prepare("SELECT invoice_no FROM tbl_invoices
                               WHERE invoice_no LIKE :prefix
                               ORDER BY invoice_id DESC LIMIT 1");
        $sql->execute([':prefix' => $prefix.'%']);
        $last = $sql->fetch(PDO::FETCH_ASSOC);

        $next = $last ? ((int) substr($last['invoice_no'], -5)) + 1 : 1;
        return $prefix.str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
