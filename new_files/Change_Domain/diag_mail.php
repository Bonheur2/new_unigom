<?php
// TEMPORARY diagnostic - delete from the server once mail is confirmed working.
//
// Open in the browser:
//   /new_files/Change_Domain/diag_mail.php                    -> checks wiring only
//   /new_files/Change_Domain/diag_mail.php?to=you@example.com -> also sends a real test email
//
// Reports each link in the chain so a silent failure can be located:
// config loaded -> send_mail() defined -> Resend reachable -> message accepted.

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

header('Content-Type: text/plain; charset=utf-8');

require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."application_mail.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

function line($label, $ok, $detail = ''){
    printf("%-34s %s%s\n", $label, $ok ? 'OK' : 'FAIL', $detail !== '' ? '  -- '.$detail : '');
}

echo "=== MAIL DIAGNOSTIC ===\n\n";

// 1. config file
$cfg = SITE_ROOT.DS."Config".DS."mailer.php";
line('Config/mailer.php present', file_exists($cfg), $cfg);

// 2. functions defined
line('send_mail() defined', function_exists('send_mail'));
line('send_application_confirmation()', function_exists('send_application_confirmation'));
line('send_domain_change_confirmation()', function_exists('send_domain_change_confirmation'));

// 3. constants
foreach(['MAIL_TRANSPORT','RESEND_FROM','RESEND_FROM_NAME','SITE_URL'] as $c){
    line('const '.$c, defined($c), defined($c) ? constant($c) : 'not defined');
}
line('const RESEND_API_KEY', defined('RESEND_API_KEY') && RESEND_API_KEY !== '',
     defined('RESEND_API_KEY') ? 'set, length '.strlen(RESEND_API_KEY) : 'not defined');

// 4. outbound HTTP capability
line('curl extension', function_exists('curl_init'));
line('allow_url_fopen', (bool) ini_get('allow_url_fopen'), ini_get('allow_url_fopen') ? 'on' : 'off');

// 5. can we reach Resend at all?
if(function_exists('curl_init')){
    $ch = curl_init('https://api.resend.com');
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_NOBODY => true]);
    curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    line('reach api.resend.com', $err === '', $err !== '' ? $err : 'HTTP '.$code);
}

// 6. error log location (where the [application_mail] lines land)
$log = ini_get('error_log');
echo "\nerror_log -> ".($log !== '' ? $log : '(server default / stderr)')."\n";

// 7. optional live send
$to = $_GET['to'] ?? '';
if($to === ''){
    echo "\nNo ?to= given, so no email was sent.\n";
    echo "Add ?to=your@address to attempt a real send.\n";
} else {
    echo "\n=== SENDING TEST TO ".$to." ===\n";
    if(!function_exists('send_mail')){
        echo "send_mail() is undefined - cannot send. Fix the checks above first.\n";
    } else {
        $ok = send_mail($to, 'UNIGOM test email', '<p>This is a test from diag_mail.php. If you are reading this, mail works.</p>');
        line('send_mail() returned', (bool) $ok, $ok ? 'accepted' : 'returned false - see error_log for the Resend error');

        echo "\n--- domain-change template ---\n";
        $ok2 = send_domain_change_confirmation($conn, $to, 'Test', 'User', 'APP-TEST', null, null, null);
        line('domain change mail', (bool) $ok2);
    }
}

echo "\nDelete this file from the server when finished.\n";
