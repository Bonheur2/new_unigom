<?php

include('../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = $conn;
class Invoice
{
    private $connect;
    private $changes;
    private $date;
    private $bill;
    public function __construct()
    {
        global $connection;
        $this->connect = $connection;
        $this->current_date = date('Y-m-d');
        $this->changes = 0;
        $this->bill = 0;
    }

    function sendFeedBack($s, $m)
    {
        $data = array("status" => $s, "message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    function sendInvoiceEmail($reg_no, $reason, $amount)
    {
        $sql0 = $this->connect->prepare("SELECT fname,email FROM tbl_admission WHERE reg_no='".$reg_no . "' LIMIT 1");
        $sql0->execute();
        $sdata = $sql0->fetch();

        $fname = $sdata['fname'];
        $email = $sdata['email'];

        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $udata = $sql->fetch();

        $year = date("Y");
        $to = $email;
        $subject = "Urgent: Outstanding Payment Notice for " . $reason ;
        $from = "stumis";

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
                                            I hope this email finds you well. This is a gentle reminder regarding the outstanding payment of " . number_format($amount, 1) . " for $reason  related to the academic years.
                                        </p>
                                        <p>
                                            We kindly request your attention to this matter and encourage you to settle the outstanding balance at your earliest convenience. Should you require any assistance or have questions regarding the payment process, please do not hesitate to contact the university's financial aid office. They are available to provide guidance and support.
                                        </p>
                                        <p>
                                            Your cooperation in resolving this matter is greatly appreciated, as it allows us to continue providing you with an exceptional educational experience.
                                        </p>
                                        <p>
                                            Thank you for your prompt attention and understanding.
                                        </p>
                                        <br>
                                        <p>
                                            Best regards,<br>
                                            Finance<br>
                                            <b>" . $udata['full_name'] . "</b>
                                            
                                        </p>
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


    function calculateAmount($x, $y)
    {
        return $x * $y;
    }

    function dateManipulator($start, $end)
    {
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);

        $months = array();
        if ($start_date->format('Y-m') == $end_date->format('Y-m')) {
            $months[] = $start_date->format('F Y');
        } else {
            $months[] = $start_date->format('F Y');
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($start_date->add($interval), $interval, $end_date);
            foreach ($period as $date) {
                $month_name = $date->format('F Y');
                if (!in_array($month_name, $months)) {
                    $months[] = $month_name;
                }
            }
        }
        return $months;
    }

    function getFeeCategAmount($fee)
    {
        $stmt = $this->connect->prepare("SELECT amount FROM fee_category WHERE id='" . $fee . "'");
        $stmt->execute();
        $data = $stmt->fetch();
        return $data['amount'];
    }

    function getFeeName($fee)
    {
        $stmt = $this->connect->prepare("SELECT name FROM fee_category WHERE id='" . $fee . "'");
        $stmt->execute();
        $data = $stmt->fetch();
        return $data['name'];
    }

    function getModules($reg_no, $acadyear)
    {
        $stmt = $this->connect->prepare("SELECT m.module_id,ml.module_credits,ml.credit_price
                                    FROM tbl_markby_module m
                                        INNER JOIN tbl_modules ml ON
                                        m.module_id=ml.module_id
                                    WHERE m.reg_no='" . $reg_no . "' AND m.acad_cycle_id='" . $acadyear . "' AND m.enrolled=1 AND ml.credited_module=1");
        $stmt->execute();
        return $stmt;
    }

    function getHostel($reg_no, $acadyear)
    {
        $stmt = $this->connect->prepare("SELECT t.room_id,r.class_id,c.price
                                    FROM tbl_tenants t
                                        INNER JOIN tbl_hostel_room r ON t.room_id=r.room_id
                                        INNER JOIN tbl_room_class c ON r.class_id=c.class_id
                                    WHERE t.reg_no='" . $reg_no . "' AND t.acad_cycle_id='" . $acadyear . "' AND t.status=1");
        $stmt->execute();
        return $stmt;
    }

    function getRestaurant($reg_no, $acadyear)
    {
        $stmt = $this->connect->prepare("SELECT * FROM tbl_rest_subscription WHERE reg_no='" . $reg_no . "' AND acad_cycle_id='" . $acadyear . "'");
        $stmt->execute();
        return $stmt;
    }

    function testModuleInvoice($reg_no, $acadyear, $module)
    {
        $stmt = $this->connect->prepare("SELECT id FROM tbl_invoice WHERE reg_no='" . $reg_no . "' AND acad_cycle_id='" . $acadyear . "' AND module_id='" . $module . "'");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function testHostelInvoice($reg_no, $acadyear, $room)
    {
        $stmt = $this->connect->prepare("SELECT id FROM tbl_invoice WHERE reg_no='" . $reg_no . "' AND acad_cycle_id='" . $acadyear . "' AND room_id='" . $room . "'");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function testRestaurantInvoice($reg_no, $acadyear, $resto, $month)
    {
        $stmt = $this->connect->prepare("SELECT id FROM tbl_invoice WHERE reg_no='" . $reg_no . "' AND acad_cycle_id='" . $acadyear . "' AND restaurant_id='" . $resto . "' AND month='" . $month . "' ");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function testStaticInvoice($reg_no, $acadyear, $fee)
    {
        $stmt = $this->connect->prepare("SELECT id FROM tbl_invoice WHERE reg_no='" . $reg_no . "' AND acad_cycle_id='" . $acadyear . "' AND fee_id='" . $fee . "'");
        $stmt->execute();
        return $stmt->rowCount();
    }

    function saveModuleInvoice($reg_no, $acadyear, $fee, $module, $amount, $dt, $user)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,fee_id,module_id,balance,invoice_date,user) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $fee . "','" . $module . "','" . $amount . "','" . $dt . "','" . $user . "')");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function saveHostelInvoice($reg_no, $acadyear, $fee, $room, $amount, $dt, $user)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,fee_id,room_id,balance,invoice_date,user) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $fee . "','" . $room . "','" . $amount . "','" . $dt . "','" . $user . "')");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function saveRestaurantInvoice($reg_no, $acadyear, $month, $fee, $resto, $amount, $dt, $user)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,month,fee_id,restaurant_id,balance,invoice_date,user) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $month . "','" . $fee . "','" . $resto . "','" . $amount . "','" . $dt . "','" . $user . "')");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function saveStaticInvoice($reg_no, $acadyear, $fee, $amount, $dt, $user)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,fee_id,balance,invoice_date,user) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $fee . "','" . $amount . "','" . $dt . "','" . $user . "')");
        $stmt->execute();
        return;
    }

    function saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $dt, $user, $comment, $month, $level_id)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,special_fee_id,balance,invoice_date,user,comment,month,level_id) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $fee . "','" . $amount . "','" . $dt . "','" . $user . "','" . $comment . "','" . $month . "','" . $level_id . "')");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function getSpecialFeeName($fee)
    {
        $stmt = $this->connect->prepare("SELECT name FROM tbl_special_invoice WHERE sp_inv_id='" . $fee . "'");
        $stmt->execute();
        $data = $stmt->fetch();
        return $data['name'];
    }

    function cancelInvoice()
    {
        $id=$_POST['id'];
        $select="SELECT * FROM tbl_invoice WHERE id='$id'";
        $cselect=$this->connect->prepare($select);
        $cselect->execute();
        $row_cselect=$cselect->fetch(PDO::FETCH_ASSOC);
        if($row_cselect['payment_status']==1){
            $this->sendFeedBack(401, "Invoice Can Not Be canceled Becouse it is Already Payed !");
        }
        else{
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status =1, approval_status=2 WHERE id='".$id."'");
         
        if ($stmt->execute() ) {
            $this->sendFeedBack(200, "Invoice cancelled succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        } 
        }
    }
    
    function cancelInvoice_not_daf()
    {
        $id=$_POST['id'];
        $select="SELECT * FROM tbl_invoice WHERE id='$id'";
        $cselect=$this->connect->prepare($select);
        $cselect->execute();
        $row_cselect=$cselect->fetch(PDO::FETCH_ASSOC);
        if($row_cselect['payment_status']==1){
            $this->sendFeedBack(401, "Invoice Can Not Be canceled Becouse it is Already Payed !");
        }
        else{
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status =1, approval_status=2 WHERE id='".$id."'");
         
        if ($stmt->execute() ) {
            $this->sendFeedBack(200, "Invoice cancelled succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        } 
        }
        
    }
    

    
   function re_activate_invoice()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=1, approval_status='1' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
    
    
   function re_activate_invoice_approval()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=1, approval_status='1' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
        
    
   function re_activate_invoice_not_daf()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=1, approval_status='1' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
    
       function re_activate_invoice_not_daf_approval()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=1, approval_status='1' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
    
    function loadStudentInvoices()
    {
        $reg_no = $_POST['reg_no'];
        $acad_cycle_id = $_POST['acad_cycle_id'];

        $stmt = $this->connect->prepare("
            SELECT
                i.id,
                i.reg_no,
                i.fee_id,
                i.special_fee_id,
                i.balance,
                i.invoice_date,
                i.month,
                i.comment,
                i.Invoice_status,
                i.approval_status,
                i.payment_status,
                COALESCE(fc.name, si.name, 'Unknown') AS fee_name
            FROM tbl_invoice i
                LEFT JOIN tbl_fee_category fc ON i.fee_id = fc.id
                LEFT JOIN tbl_special_invoice si ON i.special_fee_id = si.sp_inv_id
            WHERE i.reg_no = :reg_no
                AND i.acad_cycle_id = :acad_cycle_id
            ORDER BY i.invoice_date DESC, i.id DESC
        ");
        $stmt->execute(['reg_no' => $reg_no, 'acad_cycle_id' => $acad_cycle_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function loadStudentInvoicesForPayment()
    {
        $reg_no = $_POST['reg_no'];
        $acad_cycle_id = $_POST['acad_cycle_id'];

        $stmt = $this->connect->prepare("
            SELECT
                i.id,
                i.reg_no,
                i.fee_id,
                i.special_fee_id,
                i.balance AS original_balance,
                i.invoice_date,
                i.month,
                i.comment,
                i.Invoice_status,
                i.approval_status,
                i.payment_status,
                COALESCE(fc.name, si.name, 'Unknown') AS fee_name,
                COALESCE(pt_sum.paid, 0) AS total_paid,
                (i.balance - COALESCE(pt_sum.paid, 0)) AS remaining_balance
            FROM tbl_invoice i
                LEFT JOIN tbl_fee_category fc ON i.fee_id = fc.id
                LEFT JOIN tbl_special_invoice si ON i.special_fee_id = si.sp_inv_id
                LEFT JOIN (
                    SELECT reg_no, acad_cycle_id, fee_id, SUM(amount) AS paid
                    FROM payment_trial
                    WHERE status = 1
                    GROUP BY reg_no, acad_cycle_id, fee_id
                ) pt_sum ON i.reg_no = pt_sum.reg_no
                    AND i.acad_cycle_id = pt_sum.acad_cycle_id
                    AND COALESCE(i.fee_id, i.special_fee_id) = pt_sum.fee_id
            WHERE i.reg_no = :reg_no
                AND i.acad_cycle_id = :acad_cycle_id
            ORDER BY i.invoice_date DESC, i.id DESC
        ");
        $stmt->execute(['reg_no' => $reg_no, 'acad_cycle_id' => $acad_cycle_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function loadBanks()
    {
        $fac_id = $_POST['fac_id'];

        $stmt = $this->connect->prepare("
            SELECT bank_id, bank_name, bank_code, account_name, account_no, currency
            FROM tbl_bank
            WHERE fac_id = :fac_id AND status = 1
            ORDER BY bank_name ASC
        ");
        $stmt->execute(['fac_id' => $fac_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function savePayment()
    {
        $reg_no        = $_POST['reg_no'];
        $acad_cycle_id = $_POST['acad_cycle_id'];
        $bank_id       = $_POST['bank_id'];
        $slip_no       = trim($_POST['slip_no']);
        $user          = $_POST['user'];
        $pay_mode      = !empty($_POST['PayMode']) ? $_POST['PayMode'] : null;
        $comment       = trim($_POST['comment'] ?? '');
        $payment_date  = $_POST['date'];

        // Decode the payments array sent from the frontend
        $payments = json_decode($_POST['payments'] ?? '[]', true);
        if (!is_array($payments) || empty($payments)) {
            $this->sendFeedBack(401, "No payment items provided!");
            return;
        }

        // Calculate total amount from individual payment items
        $amount = 0;
        foreach ($payments as $p) {
            $amount += floatval($p['amount'] ?? 0);
        }

        if (empty($reg_no) || empty($acad_cycle_id) || empty($bank_id) ||
            empty($slip_no) || empty($payment_date)) {
            $this->sendFeedBack(401, "All required fields must be filled!");
            return;
        }

        if ($amount <= 0) {
            $this->sendFeedBack(401, "Amount must be greater than zero!");
            return;
        }

        // Slip number must be globally unique
        $dupCheck = $this->connect->prepare("SELECT id FROM payment_trial WHERE slip_no = :slip_no AND status = 1");
        $dupCheck->execute(['slip_no' => $slip_no]);
        if ($dupCheck->rowCount() > 0) {
            $this->sendFeedBack(401, "A payment with this slip number already exists!");
            return;
        }

        $trans_code = 'PAY-' . date('YmdHis') . '-' . rand(100, 999);
        $recorded_date = date('Y-m-d H:i:s');

        $stmt = $this->connect->prepare("
            INSERT INTO payment_trial
                (trans_code, reg_no, acad_cycle_id, bank_id, slip_no, user, date, amount, recorded_date, comment, fee_id, invoice_id, status)
            VALUES
                (:trans_code, :reg_no, :acad_cycle_id, :bank_id, :slip_no, :user, :date, :amount, :recorded_date, :comment, :fee_id, :invoice_id, 1)
        ");

        $allSuccess = true;
        foreach ($payments as $p) {
            $payAmount = floatval($p['amount'] ?? 0);
            $feeId = $p['fee_id'] ?? null;
            $invoiceId = $p['invoice_id'] ?? null;
            $result = $stmt->execute([
                'trans_code'    => $trans_code,
                'reg_no'        => $reg_no,
                'acad_cycle_id' => $acad_cycle_id,
                'bank_id'       => $bank_id,
                'slip_no'       => $slip_no,
                'user'          => $user,
                'date'          => $payment_date,
                'amount'        => $payAmount,
                'recorded_date' => $recorded_date,
                'comment'       => $comment,
                'fee_id'        => $feeId,
                'invoice_id'    => $invoiceId
            ]);
            if (!$result) {
                $allSuccess = false;
            }
        }

        if ($allSuccess) {
            // Update payment_status for fully paid invoices
            foreach ($payments as $p) {
                $invoiceId = $p['invoice_id'] ?? null;
                if ($invoiceId) {
                    $checkStmt = $this->connect->prepare("
                        SELECT i.balance, COALESCE(SUM(pt.amount), 0) AS total_paid
                        FROM tbl_invoice i
                        LEFT JOIN payment_trial pt ON pt.reg_no = i.reg_no
                            AND pt.acad_cycle_id = i.acad_cycle_id
                            AND COALESCE(i.fee_id, i.special_fee_id) = pt.fee_id
                            AND pt.status = 1
                        WHERE i.id = :invoice_id
                        GROUP BY i.id
                    ");
                    $checkStmt->execute(['invoice_id' => $invoiceId]);
                    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    if ($row && $row['total_paid'] >= $row['balance']) {
                        $updateStmt = $this->connect->prepare("UPDATE tbl_invoice SET payment_status = 1 WHERE id = :invoice_id");
                        $updateStmt->execute(['invoice_id' => $invoiceId]);
                    }
                }
            }
            $this->sendFeedBack(200, "Payment of " . number_format($amount, 2) . " recorded! Ref: " . $trans_code);
        } else {
            $errorInfo = $stmt->errorInfo();
            $this->sendFeedBack(401, "Failed to save payment! DB Error: " . ($errorInfo[2] ?? 'Unknown'));
        }
    }

    function genTuitionInvoice($reg_no, $acadyear, $fee, $user)
    {
        $modules = $this->getModules($reg_no, $acadyear);
        if ($modules->rowCount() == 0) {
            $this->sendFeedBack(401, "No Tuition bill found!");
            return;
        }
        try {
            $total = 0;
            $this->connect->beginTransaction();
            while ($mod = $modules->fetch()) {
                $module = $mod['module_id'];
                $amount = $this->calculateAmount($mod['credit_price'], $mod['module_credits']);
                $counter = $this->testModuleInvoice($reg_no, $acadyear, $module);
                if ($counter == 0) {
                    $this->saveModuleInvoice($reg_no, $acadyear, $fee, $module, $amount, $this->current_date, $user);
                    $total += $amount;
                }
            }
            if ($this->connect->commit() && $this->changes > 0) {
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
                $this->sendFeedBack(200, "Tuition invoice saved");
            } else $this->sendFeedBack(401, "Student is already invoiced for tuition!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function genHostelInvoice($reg_no, $acadyear, $fee, $user)
    {
        $hostel = $this->getHostel($reg_no, $acadyear);
        if ($hostel->rowCount() == 0) {
            $this->sendFeedBack(401, "No hostel bill found!");
            return;
        }
        try {
            $total = 0;
            $this->connect->beginTransaction();
            while ($host = $hostel->fetch()) {
                $counter = $this->testHostelInvoice($reg_no, $acadyear, $host['room_id']);
                if ($counter == 0) {
                    $this->saveHostelInvoice($reg_no, $acadyear, $fee, $host['room_id'], $host['price'], $this->current_date, $user);
                    $total += $host['price'];
                }
            }
            if ($this->connect->commit() && $this->changes > 0) {
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
                $this->sendFeedBack(200, "Hostel invoice saved");
            } else $this->sendFeedBack(401, "Student is already invoiced for hostel!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function genRestaurantInvoice($reg_no, $acadyear, $fee, $user)
    {
        $restaurant = $this->getRestaurant($reg_no, $acadyear);
        if ($restaurant->rowCount() == 0) {
            $this->sendFeedBack(401, "No restaturant bill found!");
            return;
        }
        try {
            $total = 0;
            $this->connect->beginTransaction();
            while ($resto = $restaurant->fetch()) {
                $months = $this->dateManipulator($resto['from_date'], $resto['to_date']);
                $month_count = count($months);
                $amount = $resto['amount'] / $month_count;
                foreach ($months as $month) {
                    $counter = $this->testRestaurantInvoice($reg_no, $acadyear, $resto['rest_id'], $month);
                    if ($counter == 0) {
                        $this->saveRestaurantInvoice($reg_no, $acadyear, $month, $fee, $resto['rest_id'], $amount, $this->current_date, $user);
                        $total += $amount;
                    }
                }
            }
            if ($this->connect->commit() && $this->changes > 0) {
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
                $this->sendFeedBack(200, "Restaurant invoice saved");
            } else $this->sendFeedBack(401, "Student is already invoiced for restaurant!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }
    
    function gTuitionInvoice($reg_no,$acadyear,$fee,$user){
        $reg=$reg_no;
        $acd=$acadyear;
        $us=$user;
        $f=$fee;
        $reason='Tuition';
        $AcdQuery=$this->connect->prepare("SELECT level_id,prg_id FROM tbl_register_program_ug WHERE reg_no='".$reg."' ORDER BY reg_prg_id DESC LIMIT 1");
        $AcdQuery->execute();
        $dataAcd=$AcdQuery->fetch();
        $prg_id=$dataAcd['prg_id'];
        $levl=$dataAcd['level_id'];
        if($prg_id==1){
            $amount=1000;
        }
        else{
           $amount=1500; 
        }
        $month=date('F');
        $year=date('Y');
        $mths=$month.' '.$year;
        $check=$this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no='".$reg."' AND approval_status=1 AND level_id='".$levl."' AND balance='".$amount."'");
        $check->execute();
        $rowCheck=$check->rowCount();
        if($rowCheck>0){
          $this->sendFeedBack(500, "Already invoiced!");   
        }
        else{
         $stmt=$this->connect->prepare("INSERT INTO tbl_invoice (reg_no,acad_cycle_id,month,level_id,fee_id,balance,user) 
        VALUES('".$reg."','".$acd."','".$mths."','".$levl."','".$f."','".$amount."','".$us."') ");
        $result=$stmt->execute();
        if($result){
            $reg_no=$reg;
            $this->sendFeedBack(200, "Tution invoice saved"); 
             $this->sendInvoiceEmail($reg_no, $reason, $amount);
        }
        else{
           $this->sendFeedBack(500, "Failed");  
        }   
        }
        
    }

    function genIndDynamicInvoice()
    {
        $reg_no = $_POST['reg_no'];
        $acadyear = $_POST['acad_cycle_id'];
        $fee = $_POST['fee_id'];
        $user = $_POST['user'];

        // Check if this regular fee is already invoiced for this student + academic year
        $check = $this->connect->prepare("SELECT id FROM tbl_invoice WHERE reg_no = :reg_no AND acad_cycle_id = :acad AND fee_id = :fee");
        $check->execute(['reg_no' => $reg_no, 'acad' => $acadyear, 'fee' => $fee]);
        if ($check->rowCount() > 0) {
            $this->sendFeedBack(401, "Student is already invoiced for this fee!");
            return;
        }

        $select_level="SELECT * FROM tbl_register_program_ug WHERE reg_no = '$reg_no' AND reg_active = 1";
        $cselect_level=$this->connect->prepare($select_level);
        $cselect_level->execute();
        $row_cselect_level=$cselect_level->fetch();
        $level_id=$row_cselect_level['level_id'];

        $select_amount="SELECT * FROM tbl_fee_category WHERE id='$fee'";
        $cselect_amount=$this->connect->prepare($select_amount);
        $cselect_amount->execute();
        $row_cselect_amount=$cselect_amount->fetch();
        $reason=$row_cselect_amount['name'];

        $amount=$row_cselect_amount['amount'];
        $month=date("F");
        $invoice_date=date("Y-m-d");
        $invoice_data=[
            'reg_no'=>$reg_no,
            'acad_cycle_id'=>$acadyear,
            'month'=>$month,
            'level_id'=>$level_id,
            'fee_id'=>$fee,
            'balance'=>$amount,
            'invoice_date'=>$invoice_date
            ];

        $insert_invoice="INSERT INTO tbl_invoice(`reg_no`, `acad_cycle_id`, `month`, `level_id`, `fee_id`, `balance`, `invoice_date`) VALUES(:reg_no,:acad_cycle_id,:month,:level_id,:fee_id,:balance,:invoice_date)";
        $cinsert_invoice=$this->connect->prepare($insert_invoice);
        $cinsert_invoice->execute($invoice_data);
        if($cinsert_invoice){
            $this->sendFeedBack(200, "Fee  invoice saved");
            $this->sendInvoiceEmail($reg_no, $reason, $amount);
        }
        else{
            $this->sendFeedBack(500, "Failed");
        }
        

    }

    function genIndStaticInvoice()
    {
        $reg_no = $_POST['reg_no'];
        $acadyear = $_POST['acad_cycle_id'];
        $fee = $_POST['fee_id'];
        $user = $_POST['user'];
        $amount = $this->getFeeCategAmount($fee);
        $counter = $this->testStaticInvoice($reg_no, $acadyear, $fee);
        if ($counter == 0 && $amount > 0) {
            $this->saveStaticInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user);
            $fee_name = $this->getFeeName($fee);
            $this->sendInvoiceEmail($reg_no, $fee_name, $amount);
            $this->sendFeedBack(200, "Invoice saved!");
        } else {
            $this->sendFeedBack(401, "Student is already invoiced!");
        }
    }

    function genIndSpecialInvoice()
    {
        $reg_no = $_POST['reg_no'];
        $acadyear = $_POST['acad_cycle_id'];
        $fee = $_POST['special_fee_id'];
        $amount = $_POST['amount'];
        $comment = $_POST['comment'];
        $user = $_POST['user'];
        $month = date("F");

        $select_level = $this->connect->prepare("SELECT level_id FROM tbl_register_program_ug WHERE reg_no = '" . $reg_no . "' AND reg_active = 1");
        $select_level->execute();
        $row_level = $select_level->fetch();
        $level_id = $row_level['level_id'];

        if ($amount > 0) {
            $this->saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user, $comment, $month, $level_id);
            $fee_name = $this->getSpecialFeeName($fee);
            $this->sendInvoiceEmail($reg_no, $fee_name . " (" . $comment . ")", $amount);
            $this->sendFeedBack(200, "Special Invoice saved!");
        }
    }

    ///////// class invoices /////////////

    function genClassTuitionInvoice($stud, $acadyear, $fee, $user)
    {
        try {
            $this->connect->beginTransaction();
            foreach ($stud as $reg_no) {
                $total = 0;
                $modules = $this->getModules($reg_no, $acadyear);
                while ($mod = $modules->fetch()) {
                    $module = $mod['module_id'];
                    $amount = $this->calculateAmount($mod['credit_price'], $mod['module_credits']);
                    $counter = $this->testModuleInvoice($reg_no, $acadyear, $module);
                    if ($counter == 0) {
                        $this->saveModuleInvoice($reg_no, $acadyear, $fee, $module, $amount, $this->current_date, $user);
                        $total += $amount;
                    }
                }
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
            }
            if ($this->connect->commit() && $this->changes > 0) $this->sendFeedBack(200, "Tuition invoice saved");
            else $this->sendFeedBack(401, "class is already invoiced for tuition!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function genClassHostelInvoice($stud, $acadyear, $fee, $user)
    {
        try {
            $this->connect->beginTransaction();
            foreach ($stud as $reg_no) {
                $total = 0;
                $hostel = $this->getHostel($reg_no, $acadyear);
                while ($host = $hostel->fetch()) {
                    $counter = $this->testHostelInvoice($reg_no, $acadyear, $host['room_id']);
                    if ($counter == 0) $this->saveHostelInvoice($reg_no, $acadyear, $fee, $host['room_id'], $host['price'], $this->current_date, $user);
                    $total += $host['price'];
                }
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
            }
            if ($this->connect->commit() && $this->changes > 0) $this->sendFeedBack(200, "Hostel invoice saved");
            else $this->sendFeedBack(401, "class is already invoiced for hostel!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function genClassRestaurantInvoice($stud, $acadyear, $fee, $user)
    {
        try {
            $this->connect->beginTransaction();
            foreach ($stud as $reg_no) {
                $total = 0;
                $restaurant = $this->getRestaurant($reg_no, $acadyear);
                while ($resto = $restaurant->fetch()) {
                    $months = $this->dateManipulator($resto['from_date'], $resto['to_date']);
                    $month_count = count($months);
                    $amount = $resto['amount'] / $month_count;
                    foreach ($months as $month) {
                        $counter = $this->testRestaurantInvoice($reg_no, $acadyear, $resto['rest_id'], $month);
                        if ($counter == 0) {
                            $this->saveRestaurantInvoice($reg_no, $acadyear, $month, $fee, $resto['rest_id'], $amount, $this->current_date, $user);
                            $total += $amount;
                        }
                    }
                }
                if ($total > 0) {
                    $fee_name = $this->getFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name, $total);
                }
            }
            if ($this->connect->commit() && $this->changes > 0) $this->sendFeedBack(200, "Restaurant invoice saved");
            else $this->sendFeedBack(401, "class is already invoiced for restaurant!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function genClassDynamicInvoice()
{
    // Check if required data is provided
    if (!isset($_POST['reg_no'], $_POST['level_id'], $_POST['fee_id'], $_POST['user'])) {
        $this->sendFeedBack(400, "Missing required parameters.");
        return;
    }

    $reg_nos = $_POST['reg_no']; // Array of registration numbers
    $level_id = $_POST['level_id'];
    $fee_id = $_POST['fee_id'];
    $user = $_POST['user'];

    if (!is_array($reg_nos) || empty($reg_nos)) {
        $this->sendFeedBack(400, "No students selected.");
        return;
    }

    try {
        // Fetch the current academic cycle
        $select_academic = "SELECT * FROM tbl_acad_cycle WHERE status = 1";
        $cselect_academic = $this->connect->prepare($select_academic);
        $cselect_academic->execute();
        $row_cselect_academic = $cselect_academic->fetch(PDO::FETCH_ASSOC);

        if (!$row_cselect_academic) {
            $this->sendFeedBack(404, "No active academic cycle found.");
            return;
        }

        $acad_cycle_id = $row_cselect_academic['acad_cycle_id'];

        // Get the fee details
        $select_amount = "SELECT * FROM tbl_fee_category WHERE id = :fee_id";
        $cselect_amount = $this->connect->prepare($select_amount);
        $cselect_amount->execute(['fee_id' => $fee_id]);
        $row_cselect_amount = $cselect_amount->fetch(PDO::FETCH_ASSOC);

        if (!$row_cselect_amount) {
            $this->sendFeedBack(404, "Fee category not found.");
            return;
        }

        $balance = $row_cselect_amount['amount'];
        $invoice_date = date('Y-m-d');
        $month = date('F');

        // Process each student
        $success_count = 0;
        $fail_count = 0;

        foreach ($reg_nos as $reg_no) {
            // Check if the invoice already exists
            $select_before = "SELECT * FROM tbl_invoice 
                              WHERE reg_no = :reg_no AND acad_cycle_id = :acad_cycle_id 
                              AND level_id = :level_id AND fee_id = :fee_id";
            $cselect_before = $this->connect->prepare($select_before);
            $cselect_before->execute([
                'reg_no' => $reg_no,
                'acad_cycle_id' => $acad_cycle_id,
                'level_id' => $level_id,
                'fee_id' => $fee_id
            ]);

            $row_cselect_before = $cselect_before->fetch(PDO::FETCH_ASSOC);

            if ($row_cselect_before) {
                continue; // Skip if invoice already exists
            }

            // Insert new invoice
            $invoice_data = [
                'reg_no' => $reg_no,
                'acad_cycle_id' => $acad_cycle_id,
                'month' => $month,
                'level_id' => $level_id,
                'fee_id' => $fee_id,
                'balance' => $balance,
                'invoice_date' => $invoice_date
            ];

            $invoicing = "INSERT INTO tbl_invoice(`reg_no`, `acad_cycle_id`, `month`, `level_id`, `fee_id`, `balance`, `invoice_date`) 
                          VALUES(:reg_no, :acad_cycle_id, :month, :level_id, :fee_id, :balance, :invoice_date)";
            $cinvoicing = $this->connect->prepare($invoicing);
            if ($cinvoicing->execute($invoice_data)) {
                $success_count++;
            } else {
                $fail_count++;
            }
        }

        // Feedback to the user
        if ($success_count > 0) {
            $this->sendFeedBack(200, "$success_count invoices saved successfully. $fail_count failed.");
        } else {
            $this->sendFeedBack(500, "No invoices were saved. $fail_count failed.");
        }
    } catch (Exception $e) {
        // Handle exceptions
        $this->sendFeedBack(500, "An error occurred: " . $e->getMessage());
    }
}


    function genClassStaticInvoice()
    {
        $reg_no = $_POST['reg_no'];
        $acadyear = $_POST['acadyear'];
        $fee = $_POST['fee_id'];
        $user = $_POST['user'];
        try {
            $this->connect->beginTransaction();
            foreach ($_POST['reg_no'] as $reg_no) {
                $amount = $this->getFeeCategAmount($fee);
                $counter = $this->testStaticInvoice($reg_no, $acadyear, $fee);
                if ($counter == 0) {
                    $this->saveStaticInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user);
                    $this->changes++;
                    if ($amount > 0) {
                        $fee_name = $this->getFeeName($fee);
                        $this->sendInvoiceEmail($reg_no, $fee_name, $amount);
                    }
                }
            }
            if ($this->connect->commit() && $this->changes > 0) $this->sendFeedBack(200, "Invoice saved");
            else $this->sendFeedBack(401, "class is already invoiced!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }


    function genClassSpecialInvoice()
    {
        $fee = $_POST['special_fee_id'];
        $amount = $_POST['amount'];
        $comment = $_POST['comment'];
        $user = $_POST['user'];
        $month = date("F");

        // Fetch active academic cycle
        $select_ac = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1");
        $select_ac->execute();
        $row_ac = $select_ac->fetch(PDO::FETCH_ASSOC);
        if (!$row_ac) {
            $this->sendFeedBack(404, "No active academic cycle found.");
            return;
        }
        $acadyear = $row_ac['acad_cycle_id'];

        try {
            $this->connect->beginTransaction();
            foreach ($_POST['reg_no'] as $reg_no) {
                $select_level = $this->connect->prepare("SELECT level_id FROM tbl_register_program_ug WHERE reg_no = '" . $reg_no . "' AND reg_active = 1");
                $select_level->execute();
                $row_level = $select_level->fetch();
                $level_id = $row_level['level_id'];

                $this->saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user, $comment, $month, $level_id);
                if ($amount > 0) {
                    $fee_name = $this->getSpecialFeeName($fee);
                    $this->sendInvoiceEmail($reg_no, $fee_name . " (" . $comment . ")", $amount);
                }
            }
            if ($this->connect->commit() && $this->changes > 0) $this->sendFeedBack(200, "Invoice saved");
            else $this->sendFeedBack(401, "Something went wrong!");
        } catch (PDOException $e) {
            $this->connect->rollback();
            $this->sendFeedBack(401, "Something Went wrong, action is undone!");
        }
    }

    function sendSMS()
    {
        $sql = $this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data = $sql->fetch();

        $sql2 = $this->connect->prepare("SELECT first_name FROM tbl_users WHERE Identification = ? ORDER BY id DESC LIMIT 1");
        $sql2->execute([$_POST['user']]);
        $data2 = $sql2->fetch();

        $agent = $data2['first_name'];
        $sender = $data['short_name'];
        $receiver = $_POST['phone'];
        $message = $_POST['message'] . " . @" . $agent;

        $data = array(
            "sender" => "$sender",
            "recipients" => "$receiver",
            "message" => "$message",
        );
        $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
        $data = http_build_query($data);
        $username = "twagiramungus";
        $password = "M00dle!!@@";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result;
        $httpcode;
        echo 200;
    }
}

$invoice = new Invoice();
$action = $_POST['action'];
switch ($action) {
    case 'gen_ind_dynamic':
        $invoice->genIndDynamicInvoice();
        break;
    case 'gen_ind_static':
        $invoice->genIndStaticInvoice();
        break;
    case 'gen_class_dynamic':
        $invoice->genClassDynamicInvoice();
        break;
    case 'gen_class_static':
        $invoice->genClassStaticInvoice();
        break;
    case 'gen_ind_special':
        $invoice->genIndSpecialInvoice();
        break;
    case 'gen_class_special':
        $invoice->genClassSpecialInvoice();
        break;
        
    case 'cancel_not_daf':
        $invoice->cancelInvoice_not_daf();
        break;
    case 're_activate_invoice_not_daf':
        $invoice->re_activate_invoice_not_daf();
        break;
    case 'cancel':
        $invoice->cancelInvoice();
        break;
    case 're_activate_invoice':
        $invoice->re_activate_invoice();
        break;
        
    case 're_activate_invoice_approval':
        $invoice->re_activate_invoice_approval();
        break;
    case 're_activate_invoice_approval':
        $invoice->re_activate_invoice_not_daf_approval();
        break;    
        
        
    case 'sendinsms':
        $invoice->sendSMS();
        break;

    case 'load_student_invoices':
        $invoice->loadStudentInvoices();
        break;

    case 'load_student_invoices_for_payment':
        $invoice->loadStudentInvoicesForPayment();
        break;

    case 'load_banks':
        $invoice->loadBanks();
        break;

    case 'save_payment':
        $invoice->savePayment();
        break;
}