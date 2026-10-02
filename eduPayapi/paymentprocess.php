<?php
/**
 * Payment Recording API
 *
 * Accepts: POST request with JSON body:
 * {
 *   "trans_code":    "TXN-20240001",
 *   "reg_no":        "REG-2024-001",
 *   "acad_cycle_id": 3,
 *   "fee_id":        2,
 *   "invoice_id":    5,
 *   "amount":        50000
 * }
 * Headers: X-API-Key: your-secret-key
 */

// ─── CORS Headers ─────────────────────────────────────────────────────────────
header("Access-Control-Allow-Origin: https://external-system.com"); // ← Change to EduPay's domain
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key");
header("Content-Type: application/json; charset=UTF-8");

// ─── Config ───────────────────────────────────────────────────────────────────
define('API_KEY',    'e7618f87ea92c2eddc260be5574d0c7077c5f9dc8a6104c3a1a5bdaa803607e9'); // ← Same key as login_api.php
define('HTTPS_ONLY', true); // Set false only during local development

// ─── 1. Force HTTPS ───────────────────────────────────────────────────────────
if (HTTPS_ONLY && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "HTTPS required."]);
    exit();
}

// ─── 2. Handle preflight OPTIONS ──────────────────────────────────────────────
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

// ─── 5. Parse & Validate JSON Body ───────────────────────────────────────────
$input = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid JSON body."]);
    exit();
}

// Required fields
$required = ['trans_code', 'reg_no', 'acad_cycle_id', 'fee_id', 'invoice_id', 'amount'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Missing required field: $field"
        ]);
        exit();
    }
}

$trans_code    = trim($input['trans_code']);
$reg_no        = trim($input['reg_no']);
$acad_cycle_id = (int)$input['acad_cycle_id'];
$fee_id        = (int)$input['fee_id'];
$invoice_id    = (int)$input['invoice_id'];
$amount        = (float)$input['amount'];

// Amount must be positive
if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Amount must be greater than zero."]);
    exit();
}

// ─── 6. Database Connection ───────────────────────────────────────────────────
function getDBConnection(): PDO {
    require_once "../meet/con.php";
    return $conn;
}

// ─── 7. Process Payment ───────────────────────────────────────────────────────
try {
    $pdo = getDBConnection();

    // Begin transaction — all steps must succeed or all roll back
    $pdo->beginTransaction();

    // Step 1: Verify the invoice exists and belongs to this reg_no and fee_id
    $invoiceStmt = $pdo->prepare("
        SELECT id, balance, payment_status
        FROM   tbl_invoice
        WHERE  id       = :invoice_id
        AND    reg_no   = :reg_no
        AND    fee_id   = :fee_id
        LIMIT  1
    ");
    $invoiceStmt->execute([
        ':invoice_id' => $invoice_id,
        ':reg_no'     => $reg_no,
        ':fee_id'     => $fee_id,
    ]);
    $invoice = $invoiceStmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Invoice not found for the given reg_no and fee_id."
        ]);
        exit();
    }

    // Step 2: Check trans_code is not already recorded (prevent duplicate payments)
    $dupStmt = $pdo->prepare("
        SELECT COUNT(*) FROM payment_trial WHERE trans_code = :trans_code
    ");
    $dupStmt->execute([':trans_code' => $trans_code]);
    if ((int)$dupStmt->fetchColumn() > 0) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode([
            "success" => false,
            "message" => "Transaction code already recorded. Duplicate payment rejected."
        ]);
        exit();
    }

    // Step 3: Insert into payment_trial
    $insertStmt = $pdo->prepare("
        INSERT INTO payment_trial (trans_code, reg_no, acad_cycle_id, fee_id, invoice_id, amount)
        VALUES (:trans_code, :reg_no, :acad_cycle_id, :fee_id, :invoice_id, :amount)
    ");
    $insertStmt->execute([
        ':trans_code'    => $trans_code,
        ':reg_no'        => $reg_no,
        ':acad_cycle_id' => $acad_cycle_id,
        ':fee_id'        => $fee_id,
        ':invoice_id'    => $invoice_id,
        ':amount'        => $amount,
    ]);

    // Step 4: Calculate total paid so far for this reg_no + fee_id
    $sumStmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) AS total_paid
        FROM   payment_trial
        WHERE  reg_no  = :reg_no
        AND    fee_id  = :fee_id
    ");
    $sumStmt->execute([
        ':reg_no'  => $reg_no,
        ':fee_id'  => $fee_id,
    ]);
    $total_paid = (float)$sumStmt->fetchColumn();

    // Step 5: Determine new payment_status
    // Rule: if (invoice.balance - total_paid) <= 0  →  1,  else  →  0
    $remaining = $invoice['balance'] - $total_paid;

    if ($remaining <= 0) {
        $new_status = '1';
    } else {
        $new_status = '0';
    }

    // Step 6: Update tbl_invoice payment_status
    $updateStmt = $pdo->prepare("
        UPDATE tbl_invoice
        SET    payment_status = :payment_status
        WHERE  id     = :invoice_id
        AND    reg_no = :reg_no
        AND    fee_id = :fee_id
    ");
    $updateStmt->execute([
        ':payment_status' => $new_status,
        ':invoice_id'     => $invoice_id,
        ':reg_no'         => $reg_no,
        ':fee_id'         => $fee_id,
    ]);

    // All steps passed — commit
    $pdo->commit();

    // ── Success Response ───────────────────────────────────────────────────────
    http_response_code(200);
    echo json_encode([
        "success"        => true,
        "message"        => "Payment recorded successfully.",
        "transaction"    => [
            "trans_code"    => $trans_code,
            "reg_no"        => $reg_no,
            "acad_cycle_id" => $acad_cycle_id,
            "fee_id"        => $fee_id,
            "invoice_id"    => $invoice_id,
            "amount_paid"   => $amount,
            "total_paid"    => $total_paid,
            "balance"       => $invoice['balance'],
            "remaining"     => max(0, $remaining),
            "payment_status"=> $new_status,
        ]
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    error_log("DB Error in payment_api.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error. Please try again later."]);
}