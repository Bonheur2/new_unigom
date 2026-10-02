<?php
/**
 * Documents issued when an approval request is finally approved:
 * the admission letter and the proof of registration.
 *
 * Generated with FPDF, saved under uploads/approval_documents/, emailed to the
 * applicant as an attachment and logged in tbl_approval_documents. Which
 * document a form issues is set per form in tbl_approval_form_settings.
 *
 * Nothing here may fail an approval: every error is caught, logged on the
 * document row, and reported back as text.
 */

if(!function_exists('approval_issue_document')){

    /**
     * FPDF is not bundled with this module, so look in the usual places.
     * Returns true when the FPDF class is available.
     */
    function approval_load_fpdf(){
        if(class_exists('FPDF')){ return true; }

        $root = defined('SITE_ROOT') ? rtrim(SITE_ROOT, '/\\') : rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        $candidates = [
            $root.'/fpdf/fpdf.php',
            $root.'/lib/fpdf/fpdf.php',
            $root.'/libs/fpdf/fpdf.php',
            $root.'/vendor/setasign/fpdf/fpdf.php',
            $root.'/vendor/fpdf/fpdf.php',
            $root.'/new_files/fpdf/fpdf.php',
            $root.'/assets/fpdf/fpdf.php'
        ];
        foreach($candidates as $f){
            if(is_file($f)){ require_once($f); break; }
        }
        return class_exists('FPDF');
    }

    /** FPDF's core fonts are Latin-1; convert UTF-8 text (accents, apostrophes). */
    function pdf_txt($s){
        $s = (string) $s;
        $out = @iconv('UTF-8', 'windows-1252//TRANSLIT', $s);
        return $out === false ? $s : $out;
    }

    /** Everything a document needs to know about the request, in one array. */
    function approval_document_data($conn, $request){
        $st = $conn->prepare("SELECT * FROM tbl_applicants WHERE applicant_id = :id");
        $st->execute([':id' => $request['applicant_id']]);
        $a = $st->fetch(PDO::FETCH_ASSOC) ?: [];

        $p = approval_placement($conn, $request) ?: [];

        $name = function($table, $id_col, $name_col, $id) use ($conn){
            if($id === null || $id === ''){ return ''; }
            $s = $conn->prepare("SELECT $name_col FROM $table WHERE $id_col = :id LIMIT 1");
            $s->execute([':id' => $id]);
            $v = $s->fetchColumn();
            return $v === false ? '' : $v;
        };

        $st = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $st->execute();
        $univ = $st->fetch(PDO::FETCH_ASSOC) ?: [];

        // academic year: the placement's cycle, otherwise the active one
        $acad_year = '';
        if(!empty($p['acad_id'])){
            $acad_year = $name('tbl_acad_cycle', 'acad_cycle_id', 'acad_year', $p['acad_id']);
        }
        if($acad_year === ''){
            $st = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
            $st->execute();
            $acad_year = (string) ($st->fetchColumn() ?: '');
        }

        return [
            'univ_name'   => $univ['full_name'] ?? 'UNIGOM',
            'univ_logo'   => $univ['logo'] ?? '',
            'univ_lines'  => array_values(array_filter([
                                 $univ['location'] ?? '',
                                 trim(($univ['phone'] ?? '').(!empty($univ['email']) ? '  |  '.$univ['email'] : '')),
                                 $univ['website'] ?? ''
                             ])),
            'full_name'   => implode(' ', array_filter(array_map('trim', [$a['fname'] ?? '', $a['mname'] ?? '', $a['lname'] ?? '']), 'strlen')),
            'gender'      => $a['gender'] ?? '',
            'dob'         => $a['dob'] ?? '',
            'email'       => $a['email'] ?? '',
            'app_code'    => $request['application_code'],
            'reg_no'      => $request['reg_no'] ?? '',
            'acad_year'   => $acad_year,
            'campus'      => $name('tbl_campus', 'camp_id', 'camp_full_name', $p['camp_id'] ?? ''),
            'faculty'     => $name('tbl_faculty', 'fac_id', 'fac_full_name', $p['fac_id'] ?? ''),
            'programme'   => $name('tbl_program_type', 'prg_type_id', 'prg_type_full_name', $p['prg_type_id'] ?? ''),
            'department'  => $name('tbl_department', 'dept_id', 'dept_full_name', $p['dept_id'] ?? ''),
            'option'      => $name('tbl_option', 'opt_id', 'opt_full_name', $p['opt_id'] ?? ''),
            'level'       => $name('tbl_level', 'level_id', 'level_full_name', $p['level_id'] ?? ''),
            'courses'     => approval_assigned_courses($conn, $request['request_id'])
        ];
    }

    /**
     * Builds the PDF and writes it to $file.
     * $type: 'admission_letter' | 'registration_proof'
     */
    function approval_build_pdf($type, $d, $setting, $file){
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(20, 18, 20);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // ---- letterhead
        $root = defined('SITE_ROOT') ? rtrim(SITE_ROOT, '/\\') : rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        $logo = $d['univ_logo'] !== '' ? $root.'/'.ltrim($d['univ_logo'], '/\\') : '';
        $text_x = 20;
        if($logo !== '' && is_file($logo) && preg_match('/\.(png|jpe?g|gif)$/i', $logo)){
            try { $pdf->Image($logo, 20, 14, 22); $text_x = 46; } catch(Exception $e){ /* bad image - skip the logo */ }
        }
        $pdf->SetXY($text_x, 16);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 7, pdf_txt($d['univ_name']), 0, 1);
        $pdf->SetFont('Arial', '', 9);
        foreach($d['univ_lines'] as $line){
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, pdf_txt($line), 0, 1);
        }
        $pdf->Ln(4);
        $pdf->SetDrawColor(18, 41, 77);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(8);

        // ---- reference + date
        $pdf->SetFont('Arial', '', 10);
        $ref = ($type === 'admission_letter' ? 'ADM' : 'REG').'/'.($d['reg_no'] ?: $d['app_code']);
        $pdf->Cell(95, 6, pdf_txt('Réf. : '.$ref), 0, 0);
        $pdf->Cell(0, 6, pdf_txt('Fait le '.date('d/m/Y')), 0, 1, 'R');
        $pdf->Ln(6);

        // ---- title
        $pdf->SetFont('Arial', 'B', 15);
        $title = $type === 'admission_letter' ? "LETTRE D'ADMISSION" : "ATTESTATION D'INSCRIPTION";
        $pdf->Cell(0, 9, pdf_txt($title), 0, 1, 'C');
        if($d['acad_year'] !== ''){
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 6, pdf_txt('Année académique '.$d['acad_year']), 0, 1, 'C');
        }
        $pdf->Ln(6);

        // ---- body
        $pdf->SetFont('Arial', '', 11);
        $civ = $d['gender'] === 'F' ? 'Madame' : ($d['gender'] === 'M' ? 'Monsieur' : 'Madame, Monsieur');
        if($type === 'admission_letter'){
            $body = $civ.' '.$d['full_name'].",\n\n"
                  ."Nous avons le plaisir de vous informer que votre candidature (code ".$d['app_code'].") "
                  ."a été examinée et retenue. Vous êtes admis(e) au sein de ".$d['univ_name']
                  ." dans le programme ci-dessous.";
        } else {
            $body = "Nous soussignés, ".$d['univ_name'].", attestons que ".$civ.' '.$d['full_name']
                  .($d['dob'] ? ", né(e) le ".date('d/m/Y', strtotime($d['dob'])) : '')
                  .", est régulièrement inscrit(e) dans notre institution"
                  .($d['acad_year'] !== '' ? " pour l'année académique ".$d['acad_year'] : '')
                  .", dans le programme ci-dessous.";
        }
        $pdf->MultiCell(0, 6, pdf_txt($body));
        $pdf->Ln(4);

        // ---- placement table
        $rows = array_filter([
            'Matricule'        => $d['reg_no'],
            'Code candidat'    => $d['app_code'],
            'Campus'           => $d['campus'],
            'Faculté'          => $d['faculty'],
            'Programme'        => $d['programme'],
            'Département'      => $d['department'],
            'Option'           => $d['option'],
            'Promotion'        => $d['level']
        ], function($v){ return $v !== null && $v !== ''; });

        $pdf->SetFillColor(242, 246, 255);
        foreach($rows as $label => $value){
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 7, pdf_txt($label), 1, 0, 'L', true);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, pdf_txt($value), 1, 1);
        }
        $pdf->Ln(5);

        // ---- assigned courses
        if(!empty($d['courses'])){
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 7, pdf_txt('Cours à suivre'), 0, 1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(18, 41, 77);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(28, 7, pdf_txt('Code'), 1, 0, 'L', true);
            $pdf->Cell(88, 7, pdf_txt('Cours'), 1, 0, 'L', true);
            $pdf->Cell(36, 7, pdf_txt('Unité'), 1, 0, 'L', true);
            $pdf->Cell(18, 7, pdf_txt('Crédits'), 1, 1, 'C', true);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 9);
            $total = 0;
            foreach($d['courses'] as $c){
                $total += (float) $c['credits'];
                $pdf->Cell(28, 6, pdf_txt($c['course_code'] ?? ''), 1, 0);
                $pdf->Cell(88, 6, pdf_txt(mb_strimwidth($c['course_name'], 0, 55, '...')), 1, 0);
                $pdf->Cell(36, 6, pdf_txt($c['ue_code'] ?? ''), 1, 0);
                $pdf->Cell(18, 6, rtrim(rtrim(number_format((float) $c['credits'], 2, '.', ''), '0'), '.'), 1, 1, 'C');
            }
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(152, 6, pdf_txt('Total crédits'), 1, 0, 'R');
            $pdf->Cell(18, 6, rtrim(rtrim(number_format($total, 2, '.', ''), '0'), '.'), 1, 1, 'C');
            $pdf->Ln(4);
        }

        // ---- closing
        $pdf->SetFont('Arial', '', 11);
        if($type === 'admission_letter'){
            $close = "Nous vous prions de vous présenter au service des inscriptions muni(e) de la présente lettre "
                   ."afin de finaliser votre inscription.\n\nVeuillez agréer, ".$civ.", l'expression de nos salutations distinguées.";
        } else {
            $close = "La présente attestation lui est délivrée pour servir et valoir ce que de droit.";
        }
        $pdf->MultiCell(0, 6, pdf_txt($close));
        $pdf->Ln(14);

        // ---- signature
        $pdf->SetX(120);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, '______________________________', 0, 1, 'C');
        if(!empty($setting['signatory_name'])){
            $pdf->SetX(120);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, pdf_txt($setting['signatory_name']), 0, 1, 'C');
        }
        if(!empty($setting['signatory_title'])){
            $pdf->SetX(120);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(0, 5, pdf_txt($setting['signatory_title']), 0, 1, 'C');
        }

        $pdf->Output('F', $file);
    }

    /** Branded email body for the attached document. */
    function approval_document_email($type, $d){
        $what = $type === 'admission_letter' ? "votre lettre d'admission" : "votre attestation d'inscription";
        $intro = $type === 'admission_letter'
            ? "Félicitations ! Votre candidature a été retenue."
            : "Votre inscription a été confirmée.";
        $reg = $d['reg_no'] ? '<p style="margin:0 0 16px;color:#475467;font-size:14px;">Votre matricule : <b>'.htmlspecialchars($d['reg_no']).'</b></p>' : '';

        return '
<div style="background:#f2f4f7;padding:32px 16px;font-family:Segoe UI,Helvetica,Arial,sans-serif;">
  <div style="max-width:520px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(16,24,40,0.08);">
    <div style="background:#12294d;padding:24px;text-align:center;">
      <div style="color:#ffffff;font-size:18px;font-weight:600;">'.htmlspecialchars($d['univ_name']).'</div>
    </div>
    <div style="padding:28px 24px;">
      <p style="margin:0 0 16px;color:#1d2939;font-size:15px;">Cher/Chère '.htmlspecialchars($d['full_name']).',</p>
      <p style="margin:0 0 16px;color:#475467;font-size:14px;line-height:1.6;">'.$intro.' Vous trouverez '.$what.' en pièce jointe.</p>
      '.$reg.'
      <p style="margin:0;color:#1d2939;font-size:14px;">Cordialement,<br><strong>'.htmlspecialchars($d['univ_name']).'</strong></p>
    </div>
  </div>
</div>';
    }

    /**
     * Generates the form's completion document for a request, emails it and
     * logs it. Safe to call after the approval has committed: it never throws.
     *
     * @param string|null $type force a type (resend); null = use the form setting
     * @return array ['ok' => bool, 'message' => string, 'doc_type' => ?string]
     */
    function approval_issue_document($conn, $request_id, $type = null){
        try {
            $st = $conn->prepare("SELECT * FROM tbl_approval_requests WHERE request_id = :id");
            $st->execute([':id' => $request_id]);
            $request = $st->fetch(PDO::FETCH_ASSOC);
            if(!$request){ return ['ok' => false, 'message' => 'Request not found', 'doc_type' => null]; }

            $setting = approval_form_setting($conn, $request['form_id']);
            if($type === null){ $type = $setting['completion_document']; }
            if(!in_array($type, ['admission_letter', 'registration_proof'], true)){
                return ['ok' => true, 'message' => '', 'doc_type' => null];   // form sends no document
            }

            $label = $type === 'admission_letter' ? 'Admission letter' : 'Proof of registration';
            $log = $conn->prepare("INSERT INTO tbl_approval_documents
                (request_id, doc_type, file_path, sent_to, sent_status, note, created_by)
                VALUES (:r, :t, :f, :to, :s, :n, :by)");

            if(!approval_load_fpdf()){
                $log->execute([':r' => $request_id, ':t' => $type, ':f' => null, ':to' => null,
                               ':s' => 'failed', ':n' => 'FPDF library not found', ':by' => $_SESSION['acc_id'] ?? null]);
                return ['ok' => false, 'message' => $label.' not generated: FPDF library not found on the server.', 'doc_type' => $type];
            }

            $d = approval_document_data($conn, $request);

            $root = defined('SITE_ROOT') ? rtrim(SITE_ROOT, '/\\') : rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
            $rel_dir = 'uploads/approval_documents/';
            if(!is_dir($root.'/'.$rel_dir)){ @mkdir($root.'/'.$rel_dir, 0755, true); }
            $safe = preg_replace('/[^A-Za-z0-9_-]/', '', $d['reg_no'] ?: $d['app_code']);
            $filename = $type.'_'.$safe.'_'.date('YmdHis').'.pdf';
            $abs = $root.'/'.$rel_dir.$filename;

            approval_build_pdf($type, $d, $setting, $abs);

            $sent = false;
            $note = null;
            if($d['email'] === ''){
                $note = 'Applicant has no email address';
            } elseif(!function_exists('send_mail')){
                $note = 'Mailer not loaded';
            } else {
                $subject = html_entity_decode(
                    ($type === 'admission_letter' ? "Lettre d&rsquo;admission" : "Attestation d&rsquo;inscription").' - '.($d['reg_no'] ?: $d['app_code']),
                    ENT_QUOTES, 'UTF-8');
                $attach_name = ($type === 'admission_letter' ? 'Lettre_admission_' : 'Attestation_inscription_').$safe.'.pdf';
                $sent = send_mail($d['email'], $subject, approval_document_email($type, $d),
                                  [['path' => $abs, 'filename' => $attach_name]]);
                if(!$sent){ $note = 'Email sending failed - see the server error log'; }
            }

            $log->execute([':r' => $request_id, ':t' => $type, ':f' => '/'.$rel_dir.$filename,
                           ':to' => $d['email'] ?: null, ':s' => $sent ? 'sent' : 'failed',
                           ':n' => $note, ':by' => $_SESSION['acc_id'] ?? null]);

            return [
                'ok' => $sent,
                'message' => $sent ? $label.' emailed to '.$d['email'].'.' : $label.' generated but not emailed ('.$note.').',
                'doc_type' => $type
            ];
        } catch(Throwable $e){
            error_log('[approval_documents] '.$e->getMessage());
            return ['ok' => false, 'message' => 'Document could not be generated: '.$e->getMessage(), 'doc_type' => $type];
        }
    }
}
