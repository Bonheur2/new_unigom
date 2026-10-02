<?php

header("Access-Control-Allow-Origin: https://external-system.com");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key");
header("Content-Type: application/json; charset=UTF-8");

// ─── Config ───────────────────────────────────────────────────────────────────
define('API_KEY',       'ee21fabf12c2875229ce6bf3933faf9dc0e15e5a7d00c89bf0a1740f48f5881d');
define('RATE_LIMIT',    5);     // max attempts
define('RATE_WINDOW',   900);   // seconds (15 minutes)
define('HTTPS_ONLY',    true);  // set false only during local development

// ─── 1. Force HTTPS ───────────────────────────────────────────────────────────
if (HTTPS_ONLY && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "HTTPS required."]);
    exit();
}

// ─── 2. Handle preflight OPTIONS ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ─── 3. Only allow POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed. Use POST."]);
    exit();
}

// ─── 4. Validate API Key ──────────────────────────────────────────────────────
$api_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
if (empty($api_key) || !hash_equals(API_KEY, $api_key)) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Forbidden. Invalid API key."]);
    exit();
}

// ─── 5. Rate Limiting (per IP) ────────────────────────────────────────────────
$ip         = $_SERVER['REMOTE_ADDR'];
$rate_file  = sys_get_temp_dir() . "/rl_" . md5($ip) . ".json";

$attempts = ["count" => 0, "time" => time()];
if (file_exists($rate_file)) {
    $stored = json_decode(file_get_contents($rate_file), true);
    // Reset window if expired
    if (time() - $stored['time'] <= RATE_WINDOW) {
        $attempts = $stored;
    }
}

if ($attempts['count'] >= RATE_LIMIT) {
    $retry_after = RATE_WINDOW - (time() - $attempts['time']);
    http_response_code(429);
    header("Retry-After: $retry_after");
    echo json_encode([
        "success" => false,
        "message" => "Too many failed attempts. Try again in " . ceil($retry_after / 60) . " minute(s)."
    ]);
    exit();
}

// ─── 6. Parse & Validate JSON Body ───────────────────────────────────────────
$input = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid JSON body."]);
    exit();
}

$phone_no = trim($input['phone_no'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($phone_no) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "phone_no and password are required."]);
    exit();
}

// ─── 7. Database Connection ───────────────────────────────────────────────────
function getDBConnection(): PDO {
    require_once "../meet/con.php";
    return $conn;
}

// ─── 8. Authenticate & Fetch Data ────────────────────────────────────────────
try {
    $pdo = getDBConnection();

    // Step 1: Fetch user by phone number
    $stmt = $pdo->prepare("
        SELECT phone_no,
               password,
               family_name,
               first_name,
               Identification AS registration_number
        FROM   tbl_users
        WHERE  phone_no = :phone_no
        LIMIT  1
    ");
    $stmt->execute([':phone_no' => $phone_no]);
    $user = $stmt->fetch();

    // Step 2: Verify password — always run password_verify even if user not found
    //         to prevent timing attacks that reveal valid phone numbers
    $dummy_hash     = '$2y$10$invalidsaltinvalidsaltinvalidsa.invalidhashXXXXXXXXXXXX';
    $password_valid = $user && password_verify($password, $user['password']);
    if (!$user) { password_verify($password, $dummy_hash); } // consume equal time

    if (!$password_valid) {
        // Increment rate limit counter on failure
        $attempts['count']++;
        file_put_contents($rate_file, json_encode($attempts), LOCK_EX);

        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid phone number or password."]);
        exit();
    }

    // Step 3: Login success — clear rate limit for this IP
    if (file_exists($rate_file)) { unlink($rate_file); }

    // Step 4: Fetch all invoices linked to this user
    $invoiceStmt = $pdo->prepare("
        SELECT id,
               reg_no,
               balance,
               acad_cycle_id,
               fee_id,
               payment_status
        FROM   tbl_invoice
        WHERE  reg_no = :reg_no
        ORDER BY id DESC
    ");
    $invoiceStmt->execute([':reg_no' => $user['registration_number']]);
    $invoices = $invoiceStmt->fetchAll();

    // ── Success Response ───────────────────────────────────────────────────────
    http_response_code(200);
    echo json_encode([
        "success"  => true,
        "message"  => "Login successful.",
        "user"     => [
            "phone_no"            => $user['phone_no'],
            "first_name"          => $user['first_name'],
            "family_name"         => $user['family_name'],
            "registration_number" => $user['registration_number'],
        ],
        "invoices" => $invoices
    ]);

} catch (PDOException $e) {
    error_log("DB Error in login_api.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error. Please try again later."]);
}