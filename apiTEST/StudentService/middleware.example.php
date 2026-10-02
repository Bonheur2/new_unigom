<?php
class TelcoTokenUtil {
    private static $secretKey = 'CHANGE_ME';
    private static $encryptionPassword = 'CHANGE_ME';
    private static $cipher = 'AES-256-CBC';
    
    /**
     * Generate a secure token with payload data
     * 
     * @param array $payload Data to include in token
     * @return string Generated token
     */
    public static function generateToken(array $payload): string {
        $payloadJson = json_encode($payload);
        
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        $key = hash_pbkdf2('sha256', self::$encryptionPassword, self::$secretKey, 10000, 32, true);
        
        $encrypted = openssl_encrypt($payloadJson, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        $hmac = hash_hmac('sha256', $encrypted, self::$secretKey, true);
        
        $token = base64_encode($iv . $hmac . $encrypted);
        
        return $token;
    }
    
    /**
     * Validate and decode a token
     * 
     * @param string $token Token to validate
     * @return array|false Payload if valid, false otherwise
     */
    public static function validateToken(string $token) {
        // Decode the token
        $tokenData = base64_decode($token);
        if ($tokenData === false) {
            return false;
        }
        
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        if (strlen($tokenData) < $ivlen + 32) {
            return false; // Token too short
        }
        
        $iv = substr($tokenData, 0, $ivlen);
        $hmac = substr($tokenData, $ivlen, 32);
        $encrypted = substr($tokenData, $ivlen + 32);
        
        $key = hash_pbkdf2('sha256', self::$encryptionPassword, self::$secretKey, 10000, 32, true);
        
        $calculatedHmac = hash_hmac('sha256', $encrypted, self::$secretKey, true);
        if (!hash_equals($hmac, $calculatedHmac)) {
            return false; // Invalid signature
        }
        
        
        $decrypted = openssl_decrypt($encrypted, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            return false; // Decryption failed
        }
        
        
        $payload = json_decode($decrypted, true);
        if ($payload === null) {
            return false; // Invalid JSON
        }
        
        return $payload;
    }
}

// Example usage:

/*
// Generate a token with data
$data = [
    'partner_id' => '2',
    'bank' => 'SLCB',
    'permissions' => ['RED', 'DEPOSIT']
];
$token = TelcoTokenUtil::generateToken($data);
echo "Generated Token: $token\n";
// Validate token
$result = TelcoTokenUtil::validateToken($token);
if ($result !== false) {
    echo "Token is valid. Data:\n";
    print_r($result);
} else {
    echo "Invalid token!\n";
}
*/

// MIDLEWARE TO VERIFY TOKEN ON EVERY REQUEST AS BEARER TOKEN
/**/



/*off


$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        $payload = TelcoTokenUtil::validateToken($token);
        if ($payload === false) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: Provided Authorization token is invalid']);
            exit;
        } else {
            // array(3) {
            //     ["partner_id"]=>
            //         string(1) "2"
            //     ["bank"]=>
            //         string(4) "SLCB"
            //     ["permissions"]=>
            //         array(2) {
            //             [0]=>
            //                 string(3) "RED"
            //             [1]=>
            //                 string(7) "DEPOSIT"
            //         }
            // }
            // allow request to pass 
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized : No Bearer Token Found']);
        exit;
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: No Token Provided']);
    exit;
}

*/