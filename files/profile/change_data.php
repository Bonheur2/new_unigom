<?php
// Saves the profile edit form (profile.php).
//
// Staff sit in tbl_users, applicants in tbl_applicants (linked to
// tbl_student_login by Identification = application_code). The form posts the
// same three fields either way, so decide which table to update from the
// session rather than trusting the posted id.
//
// Echoes 1 on success, 0 on failure - profile.php checks for 1.

require'../../meet/bind.php';

if(isset($_POST) && !empty($_POST)){

    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if($fname === '' || $lname === ''){
        echo 0;
        return;
    }

    $updated = false;

    try {
        // --- staff: tbl_users, keyed on the logged-in account id ---
        $acc_id = $_SESSION['acc_id'] ?? 0;

        $chk = $db->prepare("SELECT id FROM tbl_users WHERE id = ?");
        $chk->execute(array($acc_id));

        if($chk->fetch()){
            $upd = $db->prepare("UPDATE tbl_users SET first_name = ?, family_name = ?, phone_no = ? WHERE id = ?");
            $upd->execute(array($fname, $lname, $phone, $acc_id));
            $updated = true;
        } else {
            // --- applicant: tbl_applicants, keyed on the session identification
            // (tbl_student_login.Identification = tbl_applicants.application_code) ---
            $identification = $_SESSION['identification'] ?? '';

            if($identification !== ''){
                $upd = $db->prepare("UPDATE tbl_applicants
                                     SET fname = ?, lname = ?, phone = ?, updated_at = NOW()
                                     WHERE application_code = ?");
                $upd->execute(array($fname, $lname, $phone, $identification));
                $updated = $upd->rowCount() >= 0;

                // keep the session in step so the header shows the new name at once
                $_SESSION['f_name'] = $fname;
                $_SESSION['l_name'] = $lname;
                $_SESSION['mob']    = $phone;
            }
        }
    } catch (PDOException $ex) {
        error_log('[change_data] '.$ex->getMessage());
        echo 0;
        return;
    }

    echo $updated ? 1 : 0;
}
?>
