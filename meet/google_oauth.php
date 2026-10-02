<?php
/**
 * Google OAuth 2.0 helper - authorisation URL, code exchange, profile fetch.
 *
 * Deliberately dependency-free (plain cURL) so it needs no composer install on
 * the shared host. Used by Create_account/google_start.php and google_callback.php.
 */

if(defined('SITE_ROOT') && file_exists(SITE_ROOT.DS."Config".DS."google_config.php")){
    require_once(SITE_ROOT.DS."Config".DS."google_config.php");
}

if(!function_exists('google_build_auth_url')){

    /**
     * Creates the single-use anti-forgery token tying the callback to this
     * browser session, and returns the URL to send the user to.
     *
     * @param string $mode 'login' or 'signup' - remembered across the round trip
     *                     so the callback knows which flow started it.
     */
    function google_build_auth_url($mode = 'signup'){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['google_oauth_state'] = $state;
        $_SESSION['google_oauth_mode']  = ($mode === 'login') ? 'login' : 'signup';

        $params = [
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'prompt'        => 'select_account',
            // so returning users are not forced through consent every time
            'access_type'   => 'online'
        ];

        return GOOGLE_AUTH_ENDPOINT.'?'.http_build_query($params);
    }

    /**
     * Verifies the state parameter returned by Google. Single use - the stored
     * value is cleared whether or not it matched, so a replayed callback fails.
     */
    function google_verify_state($state){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $expected = $_SESSION['google_oauth_state'] ?? '';
        unset($_SESSION['google_oauth_state']);

        return $expected !== '' && is_string($state) && hash_equals($expected, $state);
    }

    function google_oauth_mode(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        return $_SESSION['google_oauth_mode'] ?? 'signup';
    }

    /**
     * Exchanges the one-time code for an access token.
     * @return array ['ok'=>bool, 'access_token'=>string, 'error'=>string]
     */
    function google_exchange_code($code){
        $post = http_build_query([
            'code'          => $code,
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code'
        ]);

        $res = google_http_post(GOOGLE_TOKEN_ENDPOINT, $post);
        if(!$res['ok']){
            return ['ok' => false, 'error' => $res['error']];
        }

        $data = json_decode($res['body'], true);
        if(!is_array($data) || empty($data['access_token'])){
            $msg = is_array($data) && !empty($data['error_description'])
                 ? $data['error_description']
                 : 'Token response did not contain an access token';
            return ['ok' => false, 'error' => $msg];
        }

        return ['ok' => true, 'access_token' => $data['access_token']];
    }

    /**
     * Fetches the signed-in user's profile.
     * @return array ['ok'=>bool, 'email'=>, 'given_name'=>, 'family_name'=>, 'sub'=>, 'email_verified'=>bool, 'error'=>]
     */
    function google_fetch_profile($access_token){
        $res = google_http_get(GOOGLE_USERINFO_ENDPOINT, ['Authorization: Bearer '.$access_token]);
        if(!$res['ok']){
            return ['ok' => false, 'error' => $res['error']];
        }

        $p = json_decode($res['body'], true);
        if(!is_array($p) || empty($p['email'])){
            return ['ok' => false, 'error' => 'Profile response did not contain an email address'];
        }

        return [
            'ok'             => true,
            'email'          => $p['email'],
            'given_name'     => $p['given_name'] ?? '',
            'family_name'    => $p['family_name'] ?? '',
            'name'           => $p['name'] ?? '',
            'sub'            => $p['sub'] ?? '',
            'email_verified' => !empty($p['email_verified'])
        ];
    }

    // --- tiny cURL wrappers, shared error shape ---

    function google_http_post($url, $body){
        if(!function_exists('curl_init')){
            return ['ok' => false, 'error' => 'The cURL extension is not available on this server'];
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded']
        ]);
        $out  = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($out === false){
            return ['ok' => false, 'error' => 'Network error contacting Google: '.$err];
        }
        if($code >= 400){
            $d = json_decode($out, true);
            $msg = is_array($d) && !empty($d['error_description']) ? $d['error_description'] : ('HTTP '.$code);
            return ['ok' => false, 'error' => $msg];
        }
        return ['ok' => true, 'body' => $out];
    }

    function google_http_get($url, $headers = []){
        if(!function_exists('curl_init')){
            return ['ok' => false, 'error' => 'The cURL extension is not available on this server'];
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => $headers
        ]);
        $out  = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($out === false){
            return ['ok' => false, 'error' => 'Network error contacting Google: '.$err];
        }
        if($code >= 400){
            return ['ok' => false, 'error' => 'HTTP '.$code.' from Google'];
        }
        return ['ok' => true, 'body' => $out];
    }
}
