<?php
/**
 * Reusable mailer built on the Resend HTTP API.
 *
 * Usage from any page/controller:
 *
 *     require_once __DIR__ . '/../config/mailer.php';   // adjust the relative path
 *     send_mail($to, $subject, $htmlBody);
 *
 * Returns true on success, false otherwise. On failure the Resend error is
 * written to the PHP error log so the calling page can stay simple.
 */

require_once __DIR__ . '/mail_config.php';

if (!function_exists('send_mail')) {

    /**
     * @param string $to       Recipient email address.
     * @param string $subject  Email subject.
     * @param string $html     HTML body.
     * @param array  $attachments Optional files to attach, each
     *                           ['path' => '/abs/file.pdf', 'filename' => 'Name.pdf'].
     *                           Unreadable files are skipped and logged.
     * @return bool
     */
    function send_mail($to, $subject, $html, $attachments = array())
    {
        // Build the "From" header: "Display Name <address@domain>".
        $from = (defined('RESEND_FROM_NAME') && RESEND_FROM_NAME !== '')
            ? RESEND_FROM_NAME . ' <' . RESEND_FROM . '>'
            : RESEND_FROM;

        // Local-dev transport: don't actually send, just log the message.
        if (defined('MAIL_TRANSPORT') && MAIL_TRANSPORT === 'log') {
            error_log("[MAIL:log] To: $to | Subject: $subject | From: $from");
            return true;
        }

        if (!defined('RESEND_API_KEY') || RESEND_API_KEY === '' || strpos(RESEND_API_KEY, 'xxxx') !== false) {
            error_log('Resend mailer: API key is not configured in config/mail_config.php');
            return false;
        }

        $payload = array(
            'from'    => $from,
            'to'      => array($to),
            'subject' => $subject,
            'html'    => $html,
        );

        // Resend takes attachments inline as base64 content.
        if (!empty($attachments)) {
            $payload['attachments'] = array();
            foreach ($attachments as $att) {
                $path = isset($att['path']) ? $att['path'] : '';
                if ($path === '' || !is_readable($path)) {
                    error_log('Resend mailer: attachment not readable, skipped: ' . $path);
                    continue;
                }
                $payload['attachments'][] = array(
                    'filename' => isset($att['filename']) ? $att['filename'] : basename($path),
                    'content'  => base64_encode(file_get_contents($path)),
                );
            }
            if (empty($payload['attachments'])) {
                unset($payload['attachments']);
            }
        }

        if (defined('MAIL_REPLY_TO') && MAIL_REPLY_TO !== '') {
            $payload['reply_to'] = MAIL_REPLY_TO;
        }

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . RESEND_API_KEY,
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        // Resend returns 200/201 with an { "id": ... } on success.
        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log('Resend mailer failed (HTTP ' . $httpCode . '): ' . ($curlErr ?: $response));
        return false;
    }
}
