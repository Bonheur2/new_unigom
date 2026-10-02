<?php

include('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$connection = $conn;
class Payment
{
    private $connect;
    private $changes;
    private $date;
    private $bill;
    public function __construct()
    {
        global $connection;
        $this->connect = $connection;
    }

    function sendFeedBack($s, $m)
    {
        $data = array("status" => $s, "message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    function sendInvoiceEmail($reg_no, $dt, $amount, $receiver)
    {
        $sql0 = $this->connect->prepare("SELECT fname,email FROM tbl_admission WHERE reg_no='" . $reg_no . "' LIMIT 1");
        $sql0->execute();
        $sdata = $sql0->fetch();

        $fname = $sdata['fname'];
        $email = $sdata['email'];

        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $udata = $sql->fetch();

        $year = date("Y");
        $to = $email;
        $subject = "Balance transfer Notice";
        $from = $udata['email'];

        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Create email headers
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $message = '<html><head>';
        $message = ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        $message = ' <meta name="x-apple-disable-message-reformatting" />';
        $message = ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $message = ' <meta name="color-scheme" content="light dark" />';
        $message = ' <meta name="supported-color-schemes" content="light dark" />';
        $message = ' <title>' . $udata['full_name'] . '</title>';
        $message = '<body">';
        $message .= "
                    <div style='padding: 10px;border: 1px solid lightgray;'>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            Dear <b>$fname</b>,
                                            
                                        </p>
                                        <p>
                                            We are pleased to inform you that the recent money transfer from your account has been successfully completed. We want to provide you with the details of the transaction:
                                        </p>
                                        <p>
                                            <b>Transfer Amount:</b> $amount<br>
                                            <b>Transfer Date and Time:</b> $dt<br>
                                            <b>Recipient:</b> $receiver<br>
                                        </p>
                                        <p>
                                            We would like to assure you that the transfer was processed accurately and efficiently. The funds have been successfully transferred to the designated recipient's account.
                                        </p>
                                        <p>
                                            If you have any questions or require any further assistance regarding this transaction, please feel free to contact our finance office. We are available to address any concerns you may have.
                                        </p>
                                        <br>
                                        <p>
                                            Best regards,<br>
                                            Finance<br>
                                            <b>" . $udata['full_name'] . "</b>
                                            
                                        </p>
                                    </td>
                                </tr>
                                                       
                                <tr>
                                    <td style='text-align:center;font-weight:bold;'>
                                        <hr>
                                        &copy; $year ITEC. All rights reserved.<br>
                                        Designed by ITEC Ltd<br>
                                        KN 1 Rd, Kigali-Rwanda.<br>
                                        Phone (+250) 788730582
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>";
        $message .= '</body></html>';
        // Sending email
        if (mail($to, $subject, $message, $headers)) {
            return;
        }
    }

    function testIndPayment($slip, $bank)
    {
        $stmt = $this->connect->prepare("SELECT id FROM payment WHERE slip_no='" . $slip . "' AND bank_id='" . $bank . "' AND status=1");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function testNegocation($reg_no)
    {
        $stmt = $this->connect->prepare("SELECT negociate_id FROM tbl_invoice_negociation WHERE reg_no='" . $reg_no . "' AND status=1");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function disableNegociation($reg_no)
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice_negociation SET status=2 WHERE reg_no='" . $reg_no . "' AND status=1");
        $stmt->execute();
        return;
    }

    function saveIndPayment($reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $user, $amount, $date)
    {
        if ($bank_id != 0) $stmt = $this->connect->prepare("INSERT INTO payment(reg_no,acad_cycle_id,fee_id,bank_id,slip_no,user,amount,date) VALUES ('" . $reg_no . "','" . $acad_cycle_id . "','" . $fee . "','" . $bank_id . "','" . $slip_no . "','" . $user . "','" . $amount . "','" . $date . "')");
        else $stmt = $this->connect->prepare("INSERT INTO payment(reg_no,acad_cycle_id,fee_id,slip_no,user,amount,date) VALUES ('" . $reg_no . "','" . $acad_cycle_id . "','" . $fee . "','" . $slip_no . "','" . $user . "','" . $amount . "','" . $date . "')");
        $stmt->execute();
        return;
    }

    function saveNegociation($reg_no, $start_date, $end_date, $user)
    {
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice_negociation(reg_no,start_date,end_date,user) VALUES ('" . $reg_no . "','" . $start_date . "','" . $end_date . "','" . $user . "')");
        $stmt->execute();
        return;
    }

    function saveInvoice($reg_no, $acad_cycle_id, $fee, $amount, $dt, $user, $comment)
    {
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,fee_id,balance,invoice_date,user,comment,type) VALUES ('" . $reg_no . "','" . $acad_cycle_id . "','" . $fee . "','" . $amount . "','" . $dt . "','" . $user . "','" . $comment . "',2)");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function processIndPayment()
    {
        $reg_no = $_POST['reg_no'];
        $acad_cycle_id = $_POST['acad_cycle_id'];
        $fee = $_POST['fee_id'];
        $bank_id = $_POST['bank_id'];
        $slip_no = $_POST['slip_no'];
        $user = $_POST['user'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];

        $counter = $this->testIndPayment($slip_no, $bank_id);
        if ($counter == 0) {
            $this->saveIndPayment($reg_no, $acad_cycle_id, $fee, $bank_id, $slip_no, $user, $amount, $date);
            $this->sendFeedBack(200, "Payment recorded!");
        } else {
            $this->sendFeedBack(401, "Payment was recorderd before!");
        }
    }

    function cancelPayment()
    {
        $id = $_POST['pyt_id'];
        $comment = $_POST['comment'];
        $stmt = $this->connect->prepare("UPDATE payment SET comment='" . $comment . "',status=2 WHERE id='" . $id . "'");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Payment canceled!");
        } else {
            $this->sendFeedBack(401, "unable to cancel payment!");
        }
    }

    function cancelPayNegociation()
    {
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("UPDATE tbl_invoice_negociation SET status=3 WHERE negociate_id='" . $id . "'");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Agreement canceled!");
        } else {
            $this->sendFeedBack(401, "unable to cancel agreement!");
        }
    }

    function transferBalance()
    {
        $reg_no = $_POST['reg_no'];
        $acad_cycle_id = $_POST['acad_cycle_id'];
        $fee = $_POST['fee_id'];
        $slip_no = $_POST['slip_no'];
        $user = $_POST['user'];
        $amount = $_POST['amount'];
        $date = date('Y-m-d');
        $comment = $_POST['comment'];
        $bank = 0;
        try {
            $this->saveIndPayment($reg_no, $acad_cycle_id, $fee, $bank, $slip_no, $user, $amount, $date);
            $this->saveInvoice($slip_no, $acad_cycle_id, 6, $amount, $date, $user, $comment);

            $dt = date('Y-m-d H:m:s');
            $this->sendInvoiceEmail($slip_no, $dt, number_format($amount, 1), $reg_no);
            $this->sendFeedBack(200, "Transfer done successfully!");
        } catch (PDOExeption $ex) {
            $this->sendFeedBack(401, "Something went wrong!");
        }
    }

    function reverseTransfer()
    {
        $sql = $this->connect->prepare("SELECT * FROM payment WHERE id='" . $_POST['id'] . "'");
        $sql->execute();
        $data = $sql->fetch();
        $sql2 = $this->connect->prepare("DELETE FROM payment WHERE id='" . $_POST['id'] . "'");
        $sql3 = $this->connect->prepare("DELETE FROM tbl_invoice WHERE reg_no='" . $data['slip_no'] . "' AND invoice_date='" . $data['date'] . "' AND balance='" . $data['amount'] . "' AND type=2 LIMIT 1");
        if ($sql2->execute()) {
            if ($sql3->execute()) {
                $this->sendFeedBack(200, "operation done successfully!");
            } else {
                $this->sendFeedBack(401, "something went wrong!");
            }
        }
    }

    function negociatePayment()
    {
        $reg_no = $_POST['reg_no'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $user = $_POST['user'];
        $counter = $this->testNegocation($reg_no);
        if ($counter > 0) {
            $this->disableNegociation($reg_no);
        }
        $this->saveNegociation($reg_no, $start_date, $end_date, $user);
        $this->sendFeedBack(200, "agreement recorded!");
    }
    public function processInvoicePayment()
{
    $reg_no = $_POST['reg_no'];
    $acad_cycle_id = $_POST['acad_cycle_id'];
    $bank_id = $_POST['bank_id'];
    $slip_no = $_POST['slip_no'];
    $date = date('Y-m-d');

    // Check if the slip number has already been used with the same bank
    if ($this->testIndPayment($slip_no, $bank_id) > 0) {
        $this->sendFeedBack(401, "The slip number has already been used for this bank.");
        return;
    }

    // Retrieve arrays of invoice IDs and amounts from the form
    $invoice_ids = $_POST['invoice_id'];  // array of invoice IDs
    $amounts = $_POST['amount'];          // array of amounts to apply

    // Loop through each invoice item in the arrays
    foreach ($invoice_ids as $index => $invoice_id) {
        $amount = $amounts[$index];

        if ($amount > 0) {
            // Save payment for the invoice
            $this->saveInvoicePayment($invoice_id, $reg_no, $acad_cycle_id, $bank_id, $slip_no, $amount, $date);

            // After saving payment, check and update the payment status in the invoice table if fully paid
            $this->updateInvoicePaymentStatus([$invoice_id]);
        }
    }

    // Handle remaining amount, if present
    // if (isset($_POST['remaining_amount']) && $_POST['remaining_amount'] > 0) {
    //     $remaining_amount = $_POST['remaining_amount'];
    //     $this->saveRemainingAmount($reg_no, $remaining_amount, $acad_cycle_id, $date);
    // }

    $this->sendFeedBack(200, "Invoice payment recorded successfully!");
}



private function saveInvoicePayment($invoice_id, $reg_no, $acad_cycle_id, $bank_id, $slip_no, $amount, $date)
{
    $feeQuery = $this->connect->prepare("SELECT fee_id FROM tbl_invoice WHERE id = :invoice_id");
    $feeQuery->bindParam(':invoice_id', $invoice_id);
    $feeQuery->execute();
    $fee = $feeQuery->fetch(PDO::FETCH_ASSOC);

    if (!$fee || !isset($fee['fee_id'])) {
        throw new Exception("Fee ID not found for invoice ID: $invoice_id");
    }

    $fee_id = $fee['fee_id'];

    $stmt = $this->connect->prepare("INSERT INTO payment (invoice_id, reg_no, acad_cycle_id, bank_id, slip_no, amount, date, fee_id) VALUES (:invoice_id, :reg_no, :acad_cycle_id, :bank_id, :slip_no, :amount, :date, :fee_id)");
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->bindParam(':fee_id', $fee_id);
    $stmt->bindParam(':reg_no', $reg_no);
    $stmt->bindParam(':acad_cycle_id', $acad_cycle_id);
    $stmt->bindParam(':bank_id', $bank_id);
    $stmt->bindParam(':slip_no', $slip_no);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
}


private function updateInvoicePaymentStatus($invoice_ids)
{
    foreach ($invoice_ids as $invoice_id) {
        // Calculate remaining balance after payments for the current invoice
        $stmt = $this->connect->prepare("
            SELECT balance - COALESCE(SUM(p.amount), 0) AS remaining_balance 
            FROM tbl_invoice i 
            LEFT JOIN payment p ON i.id = p.invoice_id 
            WHERE i.id = :invoice_id 
            GROUP BY i.id
        ");
        $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update payment status to 1 only if remaining balance is zero or less
        if ($result && $result['remaining_balance'] <= 0) {
            $updateStmt = $this->connect->prepare("
                UPDATE tbl_invoice 
                SET payment_status = 1 
                WHERE id = :invoice_id
            ");
            $updateStmt->bindParam(':invoice_id', $invoice_id);
            $updateStmt->execute();
        }
    }
}


// private function saveRemainingAmount($reg_no, $remaining_amount, $acad_cycle_id, $date)
// {
//     // Check if a record already exists for the student in the student_remaining_money table
//     $stmt = $this->connect->prepare("SELECT remaining_amount FROM student_remaining_money WHERE reg_no = :reg_no AND acad_cycle_id = :acad_cycle_id");
//     $stmt->bindParam(':reg_no', $reg_no);
//     $stmt->bindParam(':acad_cycle_id', $acad_cycle_id);
//     $stmt->execute();
//     $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($existingRecord) {
//         // If record exists, update the remaining amount by adding the new remaining amount
//         $newRemainingAmount = $existingRecord['remaining_amount'] + $remaining_amount;
//         $updateStmt = $this->connect->prepare("UPDATE student_remaining_money SET remaining_amount = :new_remaining_amount, date = :date WHERE reg_no = :reg_no AND acad_cycle_id = :acad_cycle_id");
//         $updateStmt->bindParam(':new_remaining_amount', $newRemainingAmount);
//         $updateStmt->bindParam(':date', $date);
//         $updateStmt->bindParam(':reg_no', $reg_no);
//         $updateStmt->bindParam(':acad_cycle_id', $acad_cycle_id);
//         $updateStmt->execute();
//     } else {
//         // If no record exists, insert a new one
//         $insertStmt = $this->connect->prepare("INSERT INTO student_remaining_money (reg_no, remaining_amount, acad_cycle_id, date) VALUES (:reg_no, :remaining_amount, :acad_cycle_id, :date)");
//         $insertStmt->bindParam(':reg_no', $reg_no);
//         $insertStmt->bindParam(':remaining_amount', $remaining_amount);
//         $insertStmt->bindParam(':acad_cycle_id', $acad_cycle_id);
//         $insertStmt->bindParam(':date', $date);
//         $insertStmt->execute();
//     }
// }

function processPaymentEditDate()
{
    $paymentId = $_POST['id'];
    $newDate = $_POST['date'];

    $update_date = $this->connect->prepare("UPDATE payment SET date = :date WHERE id = :id");
    $update_date->bindParam(':date', $newDate);
    $update_date->bindParam(':id', $paymentId);

    if ($update_date->execute()) {
        $this->sendFeedBack(200, "Transaction Date done successfully!");
    } else {
        $this->sendFeedBack(401, "Something went wrong!");
    }
}
}

$payment = new Payment();
$action = $_POST['action'];
switch ($action) {
    case 'gen_ind_payment':
        $payment->processIndPayment();
        break;
    case 'cancel':
        $payment->cancelPayment();
        break;
    case 'negociate':
        $payment->negociatePayment();
        break;
    case 'cancel_negociation':
        $payment->cancelPayNegociation();
        break;
    case 'transfer':
        $payment->transferBalance();
        break;
    case 'revert_transfer':
        $payment->reverseTransfer();
        break;
    case 'invoice_payment':
        $payment->processInvoicePayment();
        break;    
}
