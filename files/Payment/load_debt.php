<?php
include ('../../meet/con.php');

$reg_no = $_REQUEST['stu'];

//Get basic debt data
$sql=$conn->prepare("SELECT a.fname, a.lname, r.reg_no, s.splz_full_name, l.level_full_name
                                FROM tbl_register_program_ug r
                                    INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
                                    INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                    INNER JOIN tbl_level l ON r.level_id = l.level_id
                                WHERE
                                    r.reg_no = '".$reg_no."' ORDER BY r.reg_prg_id DESC LIMIT 1");
$sql->execute(); 
$data=$sql->fetch();


//get debt info
$debtData=$conn->prepare("SELECT SUM(balance) as debt FROM tbl_invoice WHERE reg_no = '".$reg_no."'");
$debtData->execute();
$debtBalance=$debtData->fetch();
$debt=$debtBalance['debt'];

$payHistory=$conn->prepare("SELECT SUM(amount) as paid FROM payment WHERE reg_no = '".$reg_no."' AND status=1;");
$payHistory->execute();
$historyBalance=$payHistory->fetch();
$paid=$historyBalance['paid'];

$balance=$debt-$paid;

$ppaid=$paid*100/($debt>0?$debt:1);
$pbal=$balance*100/($debt>0?$debt:1);
?>
<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
            <tr>
                <th>Student ID:</th>
                <th><?php echo $reg_no; ?></th>
            </tr>
            <tr>
                <th>Names:</th>
                <th><?php echo $data['fname']." ".$data['lname']; ?></th>
            </tr>
            <tr>
                <th>Specialization:</th>
                <th><?php echo $data['splz_full_name']; ?></th>
            </tr>
            <tr>
                <th>Level:</th>
                <th><?php echo $data['level_full_name']; ?></th>
            </tr>
            <tr>
                <th>Total debt:</th>
                <th><?php echo number_format($debt,2); ?></th>
            </tr>
            <tr>
                <th>Paid amount:</th>
                <th><?php echo number_format($paid,2)." (".number_format($ppaid,1)."%)"; ?></th>
            </tr>
            <tr>
                <th>Unpaid amount:</th>
                <th><?php echo number_format($balance,2)." (".number_format($pbal,1)."%)"; ?></th>
            </tr>
        </thead>
    </table>    
</div>
