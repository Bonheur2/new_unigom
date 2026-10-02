<?php
// Shared helper: resolves which tbl_form_types row an application form belongs to,
// so each form's controller can stamp tbl_applicants.form_id on submit.
//
// tbl_form_types.form_path holds router routes, not file paths - e.g.
// 'applicant/edu?mis=Transifer' - so controllers identify themselves by their
// own 'mis' value and we match on 'mis=<value>' exactly. Matching the whole
// token avoids false hits between values that are prefixes of one another.

if(!function_exists('resolve_form_id')){
    function resolve_form_id($conn, $mis){
        $sql = $conn->prepare("SELECT form_id FROM tbl_form_types WHERE form_path LIKE :needle LIMIT 1");
        $sql->execute([':needle' => '%mis='.$mis]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['form_id'] : null;
    }
}
