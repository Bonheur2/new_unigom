<?php
include ('../../../meet/con.php');
$intake=$_REQUEST['intake'];
$campus=$_REQUEST['campus'];
$fee=$_REQUEST['fee'];
?>
                                                    <table class="table table-hover table-sm" id="spec_stats_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Campus | Program Type</th>
                                                            <th>Intake</th>
                                                            <th>Fee category</th>
                                                            <th>Amount</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $sql=$conn->prepare("SELECT SUM(inv.balance) AS invoice, 
                                                                                            i.intake_month,
                                                                                            ac.acad_year,
                                                                                            p.prg_type_full_name,
                                                                                            c.camp_full_name,
                                                                                            f.name
                                                                                        FROM tbl_invoice inv LEFT JOIN tbl_intake i ON inv.intake_id=i.intake_id
                                                                                                             LEFT JOIN fee_category f ON inv.fee_id=f.id
                                                                                                             LEFT JOIN tbl_acad_cycle ac ON i.acad_cycle_id=ac.acad_cycle_id
                                                                                                             LEFT JOIN tbl_program_type p ON i.prg_type=p.prg_type_id
                                                                                                             LEFT JOIN tbl_campus c ON p.campus_id=c.camp_id
                                                                                        WHERE inv.fee_id='".$fee."' AND c.camp_id='".$campus."'
                                                                                        GROUP BY inv.intake_id
                                                                                        ORDER BY inv.intake_id DESC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($data=$sql->fetch()){
                                                                    $total+=$data['invoice'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['camp_full_name']." | ".$data['prg_type_full_name']; ?></td>
                                                                <td><?php echo $data['intake_month']." | ".$data['acad_year']; ?></td>
                                                                <td><?php echo $data['name']; ?></td>
                                                                <td><?php echo number_format($data['invoice'],2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px; padding:10px;">
                                                        <button type="button" class="btn btn-success">Total invoice: <?php echo number_format($total,2); ?></button>
                                                    </div>