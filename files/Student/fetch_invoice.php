<?php
include ('../../meet/con.php');  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_no = $_POST['reg_no'];
    $acad_cycle_id = $_POST['acad_cycle_id'];

    try {
       
            // Query if level_id and intake_id are not NULL, use acad_cycle_id as a filter
            $query = "
                SELECT 
                    i.id AS invoice_id, 
                    i.balance, 
                    f.name AS fee_name  
                FROM tbl_invoice i
                INNER JOIN tbl_fee_category f ON i.fee_id = f.id
                WHERE i.reg_no = :reg_no 
                  AND i.acad_cycle_id = :acad_cycle_id
                  AND i.payment_status = '0'
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':reg_no', $reg_no);
            $stmt->bindParam(':acad_cycle_id', $acad_cycle_id);
            $stmt->execute();
            $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        // Calculate remaining balance for each invoice
        foreach ($invoices as &$invoice) {
            // Sum payments for the current invoice, including records where invoice_id is NULL
            $paymentQuery = "
                SELECT COALESCE(SUM(p.amount), 0) AS total_paid
                FROM payment p
                WHERE p.reg_no = :reg_no
                  AND p.fee_id = :fee_id
            ";
            $paymentStmt = $conn->prepare($paymentQuery);
            $paymentStmt->bindParam(':reg_no', $reg_no);
            $paymentStmt->bindParam(':fee_id', $invoice['fee_id']);
            $paymentStmt->execute();
            $payment = $paymentStmt->fetch(PDO::FETCH_ASSOC);

            // Calculate remaining balance
            $totalPaid = $payment['total_paid'];
            $remainingBalance = $invoice['balance'] - $totalPaid;

            // Update the invoice data with the calculated remaining balance
            $invoice['remaining_balance'] = $remainingBalance;
        }

        echo json_encode($invoices);

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
