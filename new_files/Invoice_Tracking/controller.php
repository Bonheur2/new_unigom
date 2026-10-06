<?php
require_once __DIR__ . '/../../meet/session.php';
require_once __DIR__ . '/../../meet/con.php';

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

// Invoice tracking for one student. Charges live in two places and are shown as one list:
//  - tbl_invoice (older invoicing pages): one row per charge, often per module, with its own
//    level and fee; cancelled when approval_status = 2, paid when payment_status = 1.
//  - tbl_invoices + tbl_invoice_lines (newer invoices): one invoice holding several lines; the
//    status (unpaid/partial/paid/cancelled) belongs to the whole invoice.
// Each row carries a key, "old-<id>" or "new-<invoice_id>", used to cancel it.
class InvoiceTracking{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function fetchAll($sql, $params = []){
        $stmt = $this->connect->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchOne($sql, $params = []){
        $stmt = $this->connect->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search_students(){
        $q = trim($_POST['q'] ?? '');
        if(strlen($q) < 2){
            echo json_encode([]);
            return;
        }
        echo json_encode($this->fetchAll("SELECT reg_no, fname, lname FROM tbl_admission
                                          WHERE reg_no LIKE ? OR fname LIKE ? OR lname LIKE ?
                                          ORDER BY reg_no LIMIT 20", [$q . '%', $q . '%', $q . '%']));
    }

    // Levels the student has been registered on, lowest first.
    private function student_levels($reg_no){
        return $this->fetchAll("SELECT DISTINCT l.level_id, l.level_full_name, l.level_rank
                                FROM tbl_register_program_ug r
                                INNER JOIN tbl_level l ON l.level_id = r.level_id
                                WHERE r.reg_no = ?
                                ORDER BY l.level_rank, l.level_full_name", [$reg_no]);
    }

    public function load_levels(){
        echo json_encode($this->student_levels(trim($_POST['reg_no'] ?? '')));
    }

    public function load_invoices(){
        $reg_no = trim($_POST['reg_no'] ?? '');
        $student = $this->fetchOne("SELECT reg_no, applicant_code, fname, lname FROM tbl_admission WHERE reg_no = ?", [$reg_no]);
        if(!$student){
            echo json_encode(['status' => 404, 'message' => 'Aucun étudiant trouvé avec ce matricule']);
            return;
        }

        $old = $this->fetchAll("SELECT CONCAT('old-', i.id) AS row_key, 'old' AS source,
                                       i.module_id, m.module_code, m.module_name,
                                       COALESCE(fc.name, fc2.name, si.name) AS fee_category,
                                       i.comment, i.month,
                                       i.balance AS amount, ac.acad_year, i.acad_cycle_id,
                                       l.level_full_name, i.level_id,
                                       i.invoice_date, i.date AS recorded_on,
                                       CASE WHEN i.approval_status = 2 THEN 'cancelled'
                                            WHEN i.payment_status = 1 THEN 'paid'
                                            ELSE 'unpaid' END AS status,
                                       EXISTS(SELECT 1 FROM tbl_markby_module mm
                                              WHERE mm.reg_no = i.reg_no AND mm.module_id = i.module_id
                                                AND mm.acad_cycle_id = i.acad_cycle_id AND mm.enrolled = 1) AS on_proof
                                FROM tbl_invoice i
                                LEFT JOIN tbl_modules tm ON tm.module_id = i.module_id
                                LEFT JOIN modules m ON m.module_id = tm.mod_id
                                LEFT JOIN tbl_fee_category fc ON fc.id = i.fee_id
                                LEFT JOIN fee_category fc2 ON fc2.id = i.fee_id
                                LEFT JOIN tbl_special_invoice si ON si.sp_inv_id = i.special_fee_id
                                LEFT JOIN tbl_level l ON l.level_id = i.level_id
                                LEFT JOIN tbl_acad_cycle ac ON ac.acad_cycle_id = i.acad_cycle_id
                                WHERE i.reg_no = ?", [$reg_no]);

        // Newer invoices may still be under the applicant code if they were raised before admission.
        // Their level is the one the student was registered on for that academic year.
        $new = $this->fetchAll("SELECT CONCAT('new-', inv.invoice_id) AS row_key, 'new' AS source,
                                       inv.invoice_no, ln.line_id,
                                       COALESCE(fc.name, ln.fee_name) AS fee_category,
                                       ln.fee_name, ln.description,
                                       ln.amount, ac.acad_year, inv.acad_cycle_id,
                                       l.level_full_name, l.level_id,
                                       inv.created_at AS invoice_date, inv.created_at AS recorded_on,
                                       inv.status, 0 AS on_proof
                                FROM tbl_invoices inv
                                INNER JOIN tbl_invoice_lines ln ON ln.invoice_id = inv.invoice_id
                                LEFT JOIN tbl_fee_categories fc ON fc.id = ln.fee_id
                                LEFT JOIN tbl_acad_cycle ac ON ac.acad_cycle_id = inv.acad_cycle_id
                                LEFT JOIN tbl_level l ON l.level_id = (
                                    SELECT r.level_id FROM tbl_register_program_ug r
                                    WHERE r.reg_no = ? AND r.acad_cycle_id = inv.acad_cycle_id
                                    ORDER BY r.reg_prg_id DESC LIMIT 1)
                                WHERE inv.reg_no = ? OR (inv.reg_no IS NULL AND inv.application_code = ?)",
                                [$reg_no, $reg_no, $student['applicant_code'] ?? '']);

        $rows = array_merge($old, $new);
        usort($rows, function($a, $b){
            return strcmp($b['invoice_date'] ?? '', $a['invoice_date'] ?? '');
        });

        echo json_encode([
            'status' => 200,
            'student' => ['reg_no' => $student['reg_no'], 'name' => trim($student['fname'] . ' ' . $student['lname'])],
            'levels' => $this->student_levels($reg_no),
            'rows' => $rows,
        ]);
    }

    // Cancels one or more rows by key. An unpaid charge can be cancelled; anything with a payment cannot.
    // A newer invoice is cancelled as a whole, so all of its lines go together.
    public function cancel(){
        $keys = $_POST['keys'] ?? [];
        if(!is_array($keys)) $keys = [$keys];

        $done = 0;
        $refused = [];
        $this->connect->beginTransaction();
        foreach(array_unique($keys) as $key){
            if(!preg_match('/^(old|new)-(\d+)$/', $key, $m)) continue;
            $id = (int)$m[2];

            if($m[1] === 'old'){
                $row = $this->fetchOne("SELECT id, payment_status, approval_status FROM tbl_invoice WHERE id = ?", [$id]);
                if(!$row || (int)$row['approval_status'] === 2) continue;
                if((int)$row['payment_status'] === 1){
                    $refused[] = '#' . $id;
                    continue;
                }
                // Same flags the older invoicing pages use for a cancelled charge.
                $this->connect->prepare("UPDATE tbl_invoice SET invoice_status = 1, approval_status = 2 WHERE id = ?")->execute([$id]);
            } else {
                $row = $this->fetchOne("SELECT invoice_no, status FROM tbl_invoices WHERE invoice_id = ?", [$id]);
                if(!$row || $row['status'] === 'cancelled') continue;
                if($row['status'] !== 'unpaid'){
                    $refused[] = $row['invoice_no'];
                    continue;
                }
                $this->connect->prepare("UPDATE tbl_invoices SET status = 'cancelled' WHERE invoice_id = ?")->execute([$id]);
            }
            $done++;
        }
        $this->connect->commit();

        $message = $done . ' facture(s) annulée(s).';
        if($refused){
            $message .= ' Déjà payée(s), non annulée(s) : ' . implode(', ', $refused) . '.';
        }
        echo json_encode(['status' => $done ? 200 : 401, 'message' => $message]);
    }
}

if(empty($_SESSION['access']) || (int)($_SESSION['role_id'] ?? 0) !== 14){
    echo json_encode(['status' => 401, 'message' => 'Session expirée ou accès refusé, veuillez vous reconnecter']);
    exit;
}

$tracking = new InvoiceTracking();

try{
    switch($_POST['action'] ?? ''){
        case 'search':  $tracking->search_students(); break;
        case 'levels':  $tracking->load_levels(); break;
        case 'load':    $tracking->load_invoices(); break;
        case 'cancel':  $tracking->cancel(); break;
        default:
            echo json_encode(['status' => 400, 'message' => 'Action inconnue']);
    }
}catch(PDOException $e){
    if($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['status' => 500, 'message' => 'Impossible de traiter la demande']);
}
