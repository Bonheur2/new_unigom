
<?php
ob_start(); 
require('../../../fpdf/fpdf.php');
require('../../../meet/con.php');
Header('Pragma: public');
$connection=$conn;

    $reg_no = $_REQUEST['stu'];
    $acadyear = $_REQUEST['acad'];
    $fee = 1;
    $amount = $_REQUEST['total'];

    // Prepare the SELECT statement
    $stmt0 = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND acad_cycle_id = ? AND fee_id = ?");
    $stmt0->execute([$reg_no, $acadyear, $fee]);
     
    if ($stmt0->rowCount() > 0) {
        // Prepare the UPDATE statement
        $stmt1 = $this->connect->prepare("UPDATE tbl_invoice SET balance = ? WHERE reg_no = ? AND acad_cycle_id = ? AND fee_id = ?");
        $stmt1->execute([$amount, $reg_no, $acadyear, $fee]);

    } else{
           $stmt = $this->connect->prepare("INSERT INTO tbl_invoice (reg_no, acad_cycle_id, fee_id, balance) VALUES (?, ?, ?, ?)");
        $stmt->execute([$reg_no, $acadyear, $fee, $amount]); 
        }

?>