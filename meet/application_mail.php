<?php
// Shared helper: sends the "application received" confirmation email after a
// successful submission. Used by all five application-form controllers so the
// branded template lives in one place.
//
// Mirrors the template already used by Application_form/apply_controller.php.

// send_mail() lives in Config/mailer.php - without this the function is
// undefined in the form controllers and no mail is ever attempted.
if(defined('SITE_ROOT') && file_exists(SITE_ROOT.DS."Config".DS."mailer.php")){
    require_once(SITE_ROOT.DS."Config".DS."mailer.php");
}

if(!function_exists('send_integration_confirmation')){
    // Re-integration request: the applicant is resuming after an interruption,
    // so the wording differs from both a new application and a domain change.
    function send_integration_confirmation($conn, $to, $fname, $lname, $application_code, $prg_type_id, $dept_id = null, $level_id = null){
        if(trim((string) $to) === ''){
            error_log('[integration_mail] no recipient for '.$application_code);
            return false;
        }

        if(!function_exists('send_mail')){
            error_log('[integration_mail] send_mail() undefined - Config/mailer.php not loaded');
            return false;
        }

        $sql = $conn->prepare("SELECT full_name, logo, email, phone FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $univ = $sql->fetch(PDO::FETCH_ASSOC);

        $lookup = function($sql_text, $id, $col) use ($conn){
            if($id === null || $id === ''){ return ''; }
            $st = $conn->prepare($sql_text);
            $st->execute([':id' => $id]);
            $row = $st->fetch(PDO::FETCH_ASSOC);
            return $row ? $row[$col] : '';
        };

        $program_name = $lookup("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id = :id", $prg_type_id, 'prg_type_full_name');
        $dept_name    = $lookup("SELECT dept_full_name FROM tbl_department WHERE dept_id = :id", $dept_id, 'dept_full_name');
        $level_name   = $lookup("SELECT level_full_name FROM tbl_level WHERE level_id = :id", $level_id, 'level_full_name');

        $university_name = ($univ && $univ['full_name']) ? $univ['full_name'] : (defined('APP_NAME') ? APP_NAME : 'UNIGOM');
        $logo_url = ($univ && !empty($univ['logo']) && defined('SITE_URL')) ? SITE_URL.$univ['logo'] : '';
        $contact_email = ($univ && !empty($univ['email'])) ? $univ['email'] : (defined('MAIL_REPLY_TO') ? MAIL_REPLY_TO : '');
        $contact_phone = ($univ && !empty($univ['phone'])) ? $univ['phone'] : '';

        $subject = html_entity_decode('Demande de r&eacute;int&eacute;gration re&ccedil;ue - '.$application_code, ENT_QUOTES, 'UTF-8');

        $logo_html = $logo_url
            ? '<img src="'.htmlspecialchars($logo_url).'" alt="'.htmlspecialchars($university_name).'" width="72" height="72" style="display:block;margin:0 auto 12px;border-radius:8px;">'
            : '';

        $contact_lines = '';
        if($contact_email){
            $contact_lines .= '<a href="mailto:'.htmlspecialchars($contact_email).'" style="color:#2f6fed;text-decoration:none;">'.htmlspecialchars($contact_email).'</a>';
        }
        if($contact_phone){
            $contact_lines .= ($contact_lines ? ' &nbsp;|&nbsp; ' : '').htmlspecialchars($contact_phone);
        }

        $row_html = function($label, $value){
            if($value === '' || $value === null){ return ''; }
            return '<tr>'
                 . '<td style="padding:6px 0;color:#667085;font-size:13px;">'.$label.'</td>'
                 . '<td style="padding:6px 0;color:#12294d;font-size:13px;font-weight:600;text-align:right;">'.htmlspecialchars($value).'</td>'
                 . '</tr>';
        };

        $details = $row_html('Fili&egrave;re', $program_name)
                 . $row_html('D&eacute;partement', $dept_name)
                 . $row_html('Classe', $level_name);

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">

    <div style="background:#12294d;padding:28px 24px;text-align:center;">
      '.$logo_html.'
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.htmlspecialchars($university_name).'</div>
    </div>

    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Cher/Ch&egrave;re '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Nous avons bien re&ccedil;u votre <strong>demande de r&eacute;int&eacute;gration</strong>.
        Elle est actuellement en attente d&rsquo;examen par le service des admissions.
      </p>

      '.($details ? '<div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;letter-spacing:.03em;text-transform:uppercase;margin-bottom:8px;">Classe demand&eacute;e</div>
        <table style="width:100%;border-collapse:collapse;">'.$details.'</table>
      </div>' : '').'

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Votre code de candidature reste <strong>'.htmlspecialchars($application_code).'</strong>.
      </p>

      <p style="margin:0 0 24px;color:#475467;font-size:14px;line-height:1.6;">
        Vous serez inform&eacute;(e) par e-mail d&egrave;s que votre demande aura &eacute;t&eacute; trait&eacute;e.
      </p>

      '.($contact_lines ? '<p style="margin:0 0 24px;color:#475467;font-size:13px;">'.$contact_lines.'</p>' : '').'

      <p style="margin:0;color:#1d2939;font-size:14px;">Cordialement,<br><strong>'.htmlspecialchars($university_name).'</strong></p>
    </div>

    <div style="background:#f9fafb;padding:16px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>

  </div>
</div>';

        try {
            $sent = send_mail($to, $subject, $html);
            error_log('[integration_mail] '.($sent ? 'sent' : 'FAILED').' to '.$to.' for '.$application_code);
            return (bool) $sent;
        } catch (Exception $e) {
            error_log('[integration_mail] exception for '.$application_code.': '.$e->getMessage());
            return false;
        }
    }
}

if(!function_exists('send_domain_change_confirmation')){
    // A change-of-domain request is not a new application, so it gets its own
    // wording: no application code panel, and the requested programme instead.
    // Returns true when the mail was accepted, false otherwise.
    function send_domain_change_confirmation($conn, $to, $fname, $lname, $application_code, $prg_type_id, $dept_id = null, $level_id = null){
        if(trim((string) $to) === ''){
            error_log('[domain_change_mail] no recipient for '.$application_code);
            return false;
        }

        if(!function_exists('send_mail')){
            error_log('[domain_change_mail] send_mail() undefined - Config/mailer.php not loaded');
            return false;
        }

        $sql = $conn->prepare("SELECT full_name, logo, email, phone FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $univ = $sql->fetch(PDO::FETCH_ASSOC);

        $lookup = function($sql_text, $id, $col) use ($conn){
            if($id === null || $id === ''){ return ''; }
            $st = $conn->prepare($sql_text);
            $st->execute([':id' => $id]);
            $row = $st->fetch(PDO::FETCH_ASSOC);
            return $row ? $row[$col] : '';
        };

        $program_name = $lookup("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id = :id", $prg_type_id, 'prg_type_full_name');
        $dept_name    = $lookup("SELECT dept_full_name FROM tbl_department WHERE dept_id = :id", $dept_id, 'dept_full_name');
        $level_name   = $lookup("SELECT level_full_name FROM tbl_level WHERE level_id = :id", $level_id, 'level_full_name');

        $university_name = ($univ && $univ['full_name']) ? $univ['full_name'] : (defined('APP_NAME') ? APP_NAME : 'UNIGOM');
        $logo_url = ($univ && !empty($univ['logo']) && defined('SITE_URL')) ? SITE_URL.$univ['logo'] : '';
        $contact_email = ($univ && !empty($univ['email'])) ? $univ['email'] : (defined('MAIL_REPLY_TO') ? MAIL_REPLY_TO : '');
        $contact_phone = ($univ && !empty($univ['phone'])) ? $univ['phone'] : '';

        $subject = html_entity_decode('Demande de changement de domaine re&ccedil;ue - '.$application_code, ENT_QUOTES, 'UTF-8');

        $logo_html = $logo_url
            ? '<img src="'.htmlspecialchars($logo_url).'" alt="'.htmlspecialchars($university_name).'" width="72" height="72" style="display:block;margin:0 auto 12px;border-radius:8px;">'
            : '';

        $contact_lines = '';
        if($contact_email){
            $contact_lines .= '<a href="mailto:'.htmlspecialchars($contact_email).'" style="color:#2f6fed;text-decoration:none;">'.htmlspecialchars($contact_email).'</a>';
        }
        if($contact_phone){
            $contact_lines .= ($contact_lines ? ' &nbsp;|&nbsp; ' : '').htmlspecialchars($contact_phone);
        }

        $row_html = function($label, $value){
            if($value === '' || $value === null){ return ''; }
            return '<tr>'
                 . '<td style="padding:6px 0;color:#667085;font-size:13px;">'.$label.'</td>'
                 . '<td style="padding:6px 0;color:#12294d;font-size:13px;font-weight:600;text-align:right;">'.htmlspecialchars($value).'</td>'
                 . '</tr>';
        };

        $details = $row_html('Fili&egrave;re', $program_name)
                 . $row_html('D&eacute;partement', $dept_name)
                 . $row_html('Classe', $level_name);

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">

    <div style="background:#12294d;padding:28px 24px;text-align:center;">
      '.$logo_html.'
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.htmlspecialchars($university_name).'</div>
    </div>

    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Cher/Ch&egrave;re '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Nous avons bien re&ccedil;u votre <strong>demande de changement de domaine</strong>.
        Elle est actuellement en attente d&rsquo;examen par le service des admissions.
      </p>

      '.($details ? '<div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;letter-spacing:.03em;text-transform:uppercase;margin-bottom:8px;">Nouveau domaine demand&eacute;</div>
        <table style="width:100%;border-collapse:collapse;">'.$details.'</table>
      </div>' : '').'

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Votre code de candidature reste <strong>'.htmlspecialchars($application_code).'</strong>.
        Aucune autre d&eacute;marche n&rsquo;est n&eacute;cessaire pour le moment.
      </p>

      <p style="margin:0 0 24px;color:#475467;font-size:14px;line-height:1.6;">
        Vous serez inform&eacute;(e) par e-mail d&egrave;s que votre demande aura &eacute;t&eacute; trait&eacute;e.
      </p>

      '.($contact_lines ? '<p style="margin:0 0 24px;color:#475467;font-size:13px;">'.$contact_lines.'</p>' : '').'

      <p style="margin:0;color:#1d2939;font-size:14px;">Cordialement,<br><strong>'.htmlspecialchars($university_name).'</strong></p>
    </div>

    <div style="background:#f9fafb;padding:16px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>

  </div>
</div>';

        try {
            $sent = send_mail($to, $subject, $html);
            error_log('[domain_change_mail] '.($sent ? 'sent' : 'FAILED').' to '.$to.' for '.$application_code);
            return (bool) $sent;
        } catch (Exception $e) {
            error_log('[domain_change_mail] exception for '.$application_code.': '.$e->getMessage());
            return false;
        }
    }
}

if(!function_exists('send_application_confirmation')){
    // Returns true when the mail was accepted, false otherwise. Callers may
    // ignore it, but it is logged either way so failures are diagnosable.
    function send_application_confirmation($conn, $to, $fname, $lname, $application_code, $camp_id, $prg_type_id, $form_name = ''){
        if(trim((string) $to) === ''){
            error_log('[application_mail] no recipient for '.$application_code);
            return false;
        }

        if(!function_exists('send_mail')){
            error_log('[application_mail] send_mail() undefined - Config/mailer.php not loaded');
            return false;
        }

        $sql = $conn->prepare("SELECT tbl_university.full_name, tbl_university.logo,
                                      tbl_university.email, tbl_university.phone,
                                      tbl_campus.camp_full_name
                                 FROM tbl_campus
                                 LEFT JOIN tbl_university ON tbl_university.id = tbl_campus.university_id
                                WHERE tbl_campus.camp_id = :camp_id");
        $sql->execute([':camp_id' => $camp_id]);
        $univ = $sql->fetch(PDO::FETCH_ASSOC);

        $program_name = '';
        if($prg_type_id !== null && $prg_type_id !== ''){
            $sql2 = $conn->prepare("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id = :id");
            $sql2->execute([':id' => $prg_type_id]);
            $prg = $sql2->fetch(PDO::FETCH_ASSOC);
            $program_name = $prg ? $prg['prg_type_full_name'] : '';
        }

        $university_name = ($univ && $univ['full_name']) ? $univ['full_name'] : (defined('APP_NAME') ? APP_NAME : 'UNIGOM');
        $campus_name = ($univ && $univ['camp_full_name']) ? $univ['camp_full_name'] : '';
        $logo_url = ($univ && !empty($univ['logo']) && defined('SITE_URL')) ? SITE_URL.$univ['logo'] : '';
        $contact_email = ($univ && !empty($univ['email'])) ? $univ['email'] : (defined('MAIL_REPLY_TO') ? MAIL_REPLY_TO : '');
        $contact_phone = ($univ && !empty($univ['phone'])) ? $univ['phone'] : '';

        $subject = 'Candidature re&ccedil;ue - '.$application_code;
        $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');

        $logo_html = $logo_url
            ? '<img src="'.htmlspecialchars($logo_url).'" alt="'.htmlspecialchars($university_name).'" width="72" height="72" style="display:block;margin:0 auto 12px;border-radius:8px;">'
            : '';

        $contact_lines = '';
        if($contact_email){
            $contact_lines .= '<a href="mailto:'.htmlspecialchars($contact_email).'" style="color:#2f6fed;text-decoration:none;">'.htmlspecialchars($contact_email).'</a>';
        }
        if($contact_phone){
            $contact_lines .= ($contact_lines ? ' &nbsp;|&nbsp; ' : '').htmlspecialchars($contact_phone);
        }

        $html = '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">

    <div style="background:#12294d;padding:28px 24px;text-align:center;">
      '.$logo_html.'
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.htmlspecialchars($university_name).'</div>
      '.($campus_name ? '<div style="color:#c7d3e8;font-size:13px;margin-top:2px;">Campus de '.htmlspecialchars($campus_name).'</div>' : '').'
    </div>

    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Cher/Ch&egrave;re '.htmlspecialchars($fname).' '.htmlspecialchars($lname).',</p>

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Nous avons bien re&ccedil;u votre candidature'.($form_name ? ' (<strong>'.htmlspecialchars($form_name).'</strong>)' : '').
        ($program_name ? ' pour la fili&egrave;re <strong>'.htmlspecialchars($program_name).'</strong>' : '').'.
        Elle est actuellement en attente d&rsquo;examen.
      </p>

      <div style="background:#f2f6ff;border:1px solid #d6e4ff;border-radius:8px;padding:16px 20px;text-align:center;margin:0 0 20px;">
        <div style="color:#667085;font-size:12px;letter-spacing:.03em;text-transform:uppercase;margin-bottom:6px;">Votre code de candidature</div>
        <div style="color:#12294d;font-size:22px;font-weight:700;letter-spacing:.04em;">'.htmlspecialchars($application_code).'</div>
      </div>

      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">
        Conservez ce code &mdash; il vous sera demand&eacute; pour suivre l&rsquo;&eacute;tat de votre candidature.
      </p>

      <p style="margin:0 0 24px;color:#475467;font-size:14px;line-height:1.6;">
        Nous vous contacterons par e-mail pour la suite de la proc&eacute;dure.
      </p>

      '.($contact_lines ? '<p style="margin:0 0 24px;color:#475467;font-size:13px;">'.$contact_lines.'</p>' : '').'

      <p style="margin:0;color:#1d2939;font-size:14px;">Cordialement,<br><strong>'.htmlspecialchars($university_name).'</strong></p>
    </div>

    <div style="background:#f9fafb;padding:16px 24px;text-align:center;border-top:1px solid #eaecf0;">
      <div style="color:#98a2b3;font-size:11px;">Powered by <strong style="color:#667085;">ITEC</strong></div>
    </div>

  </div>
</div>';

        // send_mail() returns false rather than throwing, so the result must be
        // checked. The try/catch only guards against a transport-level throw -
        // a failed email must never fail the submission.
        try {
            $sent = send_mail($to, $subject, $html);
            error_log('[application_mail] '.($sent ? 'sent' : 'FAILED').' to '.$to.' for '.$application_code);
            return (bool) $sent;
        } catch (Exception $e) {
            error_log('[application_mail] exception for '.$application_code.': '.$e->getMessage());
            return false;
        }
    }
}
