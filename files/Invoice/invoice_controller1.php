<?php

include('../../meet/con.php');
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
        $subject = "Urgent: Outstanding Payment Notice for " . $reason . " Fees";
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
                                            I hope this email finds you well. This is a gentle reminder regarding the outstanding payment of " . number_format($amount, 1) . " for $reason fees related to the academic years.
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

    function saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $dt, $user, $comment)
    {
        $this->bill += $amount;
        $stmt = $this->connect->prepare("INSERT INTO tbl_invoice(reg_no,acad_cycle_id,fee_id,balance,invoice_date,user,comment) VALUES ('" . $reg_no . "','" . $acadyear . "','" . $fee . "','" . $amount . "','" . $dt . "','" . $user . "','" . $comment . "')");
        if ($stmt->execute()) {
            $this->changes++;
        }
        return;
    }

    function cancelInvoice()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=0, approval_status=0 WHERE id='" . $_POST['id'] . "'");
         
        if ($stmt->execute() ) {
            $this->sendFeedBack(200, "Invoice cancelled succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
    
    function cancelInvoice_not_daf()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=1, approval_status=2 WHERE id='" . $_POST['id'] . "'");
         
        if ($stmt->execute() ) {
            $this->sendFeedBack(200, "Invoice cancelled succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
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
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=0, approval_status='3' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
        }
    }
    
       function re_activate_invoice_not_daf_approval()
    {
        $stmt = $this->connect->prepare("UPDATE tbl_invoice SET Invoice_status=0, approval_status='3' WHERE id='" . $_POST['id'] . "' ");
        if ($stmt->execute()) {
            $this->sendFeedBack(200, "Invoice Re Activated succesfully!");
        } else {
            $this->sendFeedBack(401, "operation failed!");
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

        if ($fee == 1) { //Tuition fees
            // $this->genTuitionInvoice($reg_no, $acadyear, $fee, $user);
            $this->gTuitionInvoice($reg_no,$acadyear,$fee,$user);
        } else if ($fee == 2) { //hostel fees
            $this->genHostelInvoice($reg_no, $acadyear, $fee, $user);
        } else if ($fee == 3) { //restaurant fees
            $this->genRestaurantInvoice($reg_no, $acadyear, $fee, $user);
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
        $fee = $_POST['fee_id'];
        $amount = $_POST['amount'];
        $comment = $_POST['comment'];
        $user = $_POST['user'];
        if ($amount > 0) {
            $this->saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user, $comment);
            $fee_name = $this->getFeeName($fee);
            $this->sendInvoiceEmail($reg_no, $fee_name . " (" . $comment . ")", $amount);
            $this->sendFeedBack(200, "Invoice saved!");
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
        $acadyear = $_POST['acadyear'];
        $fee = $_POST['fee_id'];
        $user = $_POST['user'];
        if ($fee == 1) { //Tuition fees
            $this->genClassTuitionInvoice($_POST['reg_no'], $acadyear, $fee, $user);
        } else if ($fee == 2) { //hostel fees
            $this->genClassHostelInvoice($_POST['reg_no'], $acadyear, $fee, $user);
        } else if ($fee == 3) { //restaurant fees
            $this->genClassRestaurantInvoice($_POST['reg_no'], $acadyear, $fee, $user);
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
        
        $acadyear = $_POST['acad_cycle_id'];
        $fee = $_POST['fee_id'];
        $amount = $_POST['amount'];
        $comment = $_POST['comment'];
        $user = $_POST['user'];
        try {
            $this->connect->beginTransaction();
            foreach ($_POST['reg_no'] as $reg_no) {
                $this->saveSpecialInvoice($reg_no, $acadyear, $fee, $amount, $this->current_date, $user, $comment);
                if ($amount > 0) {
                    $fee_name = $this->getFeeName($fee);
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
}
