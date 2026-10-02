<?php
include('../../../meet/con.php');

$sql0 = $conn->prepare("SELECT pt.prg_type_full_name, sp.splz_full_name, lv.level_full_name, ac.acad_year
                            FROM tbl_program_type pt
                            JOIN tbl_specialization sp ON sp.splz_id = '" . $_REQUEST['splz_id'] . "'
                            JOIN tbl_level lv ON lv.level_id = '" . $_REQUEST['level_id'] . "'
                            JOIN tbl_acad_cycle ac ON ac.acad_cycle_id = '" . $_REQUEST['acad_cycle_id'] . "'
                            WHERE pt.prg_type_id = '" . $_REQUEST['prg_type'] . "'");
$sql0->execute();
$info = $sql0->fetch();

$sql = $conn->prepare("SELECT SUM(balance) AS invoice, SUM(amount) AS payment
                        FROM (
                            SELECT balance, 0 AS amount
                                FROM tbl_invoice inv
                                INNER JOIN tbl_register_program_ug r ON inv.reg_no=r.reg_no AND r.splz_id='" . $_REQUEST['splz_id'] . "'  AND r.level_id='" . $_REQUEST['level_id'] . "' AND r.acad_cycle_id='" . $_REQUEST['acad_cycle_id'] . "'
                                WHERE inv.approval_status != 2
                            UNION ALL

                            SELECT 0 AS balance, amount
                                FROM payment_trial p
                                INNER JOIN tbl_register_program_ug r ON p.reg_no=r.reg_no AND r.splz_id='" . $_REQUEST['splz_id'] . "'  AND r.level_id='" . $_REQUEST['level_id'] . "' AND r.acad_cycle_id='" . $_REQUEST['acad_cycle_id'] . "'
                                WHERE p.status=1
                                ) AS spec_stats;");
$sql->execute();
$data = $sql->fetch();
$invoice = $data['invoice'];
$payment = $data['payment'];
$balance = $payment - $invoice;

$ppay = abs($payment * 100 / ($invoice > 0 ? $invoice : 1));
$pbal = abs($balance * 100 / ($invoice > 0 ? $invoice : 1));
?>
<div class="row" style="border-radius:5px; border: 2px solid green;">
    <div class="col-12 col-md-6 col-lg-6" style="border-right: 1px solid green;">
        <div class="form-group">
            <div class="article-user-details">
                <div class="text-job">Program type</div>
                <div class="user-detail-name"><a href="#"><b><?php echo $info['prg_type_full_name']; ?></b></a></div>
            </div>
            <div class="article-user-details">
                <div class="text-job">Specialization</div>
                <div class="user-detail-name"><a href="#"><b><?php echo $info['splz_full_name']; ?></b></a></div>
            </div>
            <div class="article-user-details">
                <div class="text-job">Level</div>
                <div class="user-detail-name"><a href="#"><b><?php echo $info['level_full_name']; ?></b></a></div>
            </div>
            <div class="article-user-details">
                <div class="text-job">Academic Year</div>
                <div class="user-detail-name"><a href="#"><b><?php echo $info['acad_year']; ?></b></a></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">
            <div class="article-user-details">
                <div class="text-job">Total Invoice</div>
                <div class="user-detail-name"><a href="#"><b><?php echo number_format($invoice, 2); ?></b></a></div>
            </div>
        </div>
        <div class="form-group">
            <div class="article-user-details">
                <div class="text-job"><b>Paid Amount</b></div>
                <div class="user-detail-name"><a
                        href="#"><b><?php echo number_format($payment, 2) . " | " . number_format($ppay, 2) . "%"; ?></b></a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="article-user-details">
                <div class="text-job"><b>Balance</b></div>
                <div class="user-detail-name"><a
                        href="#"><b><?php echo number_format($balance, 2) . " | " . number_format($pbal, 2) . "%"; ?></b></a>
                </div>
            </div>
        </div>
    </div>
</div>