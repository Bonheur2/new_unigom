<?php
    include '../../meet/con.php'
?>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Transaction Type</th>
                    <th scope="col">Fee category</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql=$conn->prepare("(
                                        SELECT
                                            reg_no1,
                                            p.recorded_date AS transaction_date,
                                            'Payment' AS transaction_type,
                                            p.amount AS transaction_amount,
                                            f.name AS fee_name
                                        FROM (
                                            SELECT DISTINCT reg.reg_no AS reg_no1
                                            FROM tbl_register_program_ug reg
                                            LEFT JOIN payment p ON reg.reg_no = p.reg_no
                                            LEFT JOIN fee_category f ON p.fee_id = f.id
                                            WHERE reg.reg_no = ? AND p.amount > 0
                                        ) AS distinct_reg_numbers
                                        LEFT JOIN payment p ON distinct_reg_numbers.reg_no1 = p.reg_no
                                        LEFT JOIN fee_category f ON p.fee_id = f.id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            reg_no1,
                                            inv.invoice_date AS transaction_date,
                                            'Invoice' AS transaction_type,
                                            inv.balance AS transaction_amount,
                                            f.name AS fee_name
                                        FROM (
                                            SELECT DISTINCT reg.reg_no AS reg_no1
                                            FROM tbl_register_program_ug reg
                                            LEFT JOIN tbl_invoice inv ON reg.reg_no = inv.reg_no
                                            LEFT JOIN fee_category f ON inv.fee_id = f.id
                                            WHERE reg.reg_no = ? AND inv.balance > 0
                                        ) AS distinct_reg_numbers
                                        LEFT JOIN tbl_invoice inv ON distinct_reg_numbers.reg_no1 = inv.reg_no
                                        LEFT JOIN fee_category f ON inv.fee_id = f.id
                                    )
                                    ORDER BY transaction_date;
                                ");
                $sql->execute([$_POST['stu'], $_POST['stu']]);
                $i=1;
                $balance = 0;
                while($pay=$sql->fetch()){
                    if($pay['transaction_type']=='Invoice'){
                        $balance += $pay['transaction_amount'];
                    }
                    if($pay['transaction_type']=='Payment'){
                        $balance -= $pay['transaction_amount'];
                    }
             ?>
            <tr>
                <th scope="row"><?php echo $i++; ?></th>
                <td><?php echo $pay['transaction_type']; ?></td>
                <td><?php echo $pay['fee_name']; ?></td>
                <td><?php echo number_format($pay['transaction_amount'],2); ?></td>
                <td><?php echo number_format($balance, 2); ?></td>
                <td><?php echo $pay['transaction_date']; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer bg-whitesmoke">
    <a href="../files/Invoice/statement?stud=<?=$_POST['stu'] ?>" target="_blank" class="btn btn-success btn-sm" >Print</a>
    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Leave</button>
</div>
