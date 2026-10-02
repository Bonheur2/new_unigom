<?php
// Kicks off "Continue with Google": stores an anti-forgery state in the session
// and redirects to Google's consent screen.
//
//   google_start.php            -> sign-up flow
//   google_start.php?mode=login -> sign-in flow

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."google_oauth.php");

if(!google_oauth_configured()){
    header('Content-Type: text/plain; charset=utf-8');
    echo "Google sign-in is not configured yet.\n\n";
    echo "Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in Config/google_config.php.\n";
    exit;
}

$mode = ($_GET['mode'] ?? '') === 'login' ? 'login' : 'signup';

header('Location: '.google_build_auth_url($mode));
exit;
