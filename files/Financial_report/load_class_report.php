<?php
include ('../../meet/con.php');

$splz = $_REQUEST['splz'];
$level = $_REQUEST['level'];
$intake = $_REQUEST['intake'];
$type = $_REQUEST['type'];
?>
<?php if($type==1){ ?>
    <div class="card">
        <div class="card-header" style="display:flex; flex-direction:row; justify-content:center; border-radius:2px; border: 2px solid green;">
            <h5>Class Finance Report</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="report_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Student ID</th>
                            <th scope="col">Names</th>
                            <th scope="col">Payable Amount</th></th>
                            <th scope="col">Paid amount</th>
                            <th scope="col">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                            $stmt=$conn->prepare("SELECT 
                                                        r.reg_no,
                                                        r.acad_cycle_id,
                                                        a.fname,
                                                        a.lname
                                                        FROM tbl_register_program_ug r
                                                            INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                        WHERE r.splz_id='".$splz."' AND r.level_id='".$level."' AND a.acad_cycle_id='".$intake."'");
                            $stmt->execute();
                            $i=1;
                            while($sdata=$stmt->fetch()){
                                $acad_cycle_id = $sdata['acad_cycle_id'];
                                $stmt2=$conn->prepare("SELECT COALESCE(SUM(balance), 0) AS debt FROM tbl_invoice 
                                                            WHERE acad_cycle_id='".$acad_cycle_id."' AND reg_no='".$sdata['reg_no']."'");
                                $stmt2->execute();
                                $debtData=$stmt2->fetch();
                                $debt=$debtData['debt'];
                                $stmt3=$conn->prepare("SELECT COALESCE(SUM(amount), 0) AS paid FROM payment
                                                            WHERE acad_cycle_id='".$acad_cycle_id."' AND reg_no='".$sdata['reg_no']."' AND status=1");
                                $stmt3->execute();
                                $paymentData=$stmt3->fetch();
                                $paid=$paymentData['paid'];
                                $balance=$debt-$paid;
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $sdata['reg_no']; ?></td>
                            <td><?php echo $sdata['fname']." ".$sdata['lname']; ?></td>
                            <td><?php echo number_format($debt,2); ?></td>
                            <td><?php echo number_format($paid,2); ?></td>
                            <td><?php echo number_format($balance,2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($stmt->rowCount()!=0){ ?>
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Financial_report/print_class_report?splz=<?php echo $splz; ?>&level=<?php echo $level; ?>&intake=<?php echo $intake; ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
        <?php } ?>
    </div>
    
<?php } ?>