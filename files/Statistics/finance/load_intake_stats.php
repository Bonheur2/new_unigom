<?php
include ('../../../meet/con.php');
$intake=$_REQUEST['intake'];
$sql=$conn->prepare("SELECT i.*,ac.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle ac ON i.acad_cycle_id=ac.acad_cycle_id WHERE i.intake_id='".$intake."'");
$sql->execute(); 
$data=$sql->fetch();

$getInvoices=$conn->prepare("SELECT SUM(balance) as invoice FROM tbl_invoice WHERE intake_id='".$intake."'");
$getInvoices->execute();
$invoiceData=$getInvoices->fetch();
$invoice=$invoiceData['invoice'];
                                                                
$getPayments=$conn->prepare("SELECT SUM(amount) as amount FROM payment WHERE AND intake_id='".$intake."' AND status=1");
$getPayments->execute();
$paymentData=$getPayments->fetch();
$payment=$paymentData['amount'];
$balance=$payment-$invoice;
                                                                
$ppay=abs($payment*100/$invoice);
$pbal=abs($balance*100/$invoice);
?>
            <div class="row" style="border-radius:5px; border: 2px solid green;">
                <div class="col-12 col-md-6 col-lg-6" style="border-right: 1px solid green;">
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job">Intake</div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['intake_month']." | ".$data['acad_year']; ?></b></a></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job">Total Invoice</div>
                            <div class="user-detail-name"><a href="#"><b><?php echo number_format($invoice,2); ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job"><b>Paid Amount</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo number_format($payment,2)." | ".number_format($ppay,2)."%"; ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job"><b>Balance</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo number_format($balance,2)." | ".number_format($pbal,2)."%"; ?></b></a></div>
                        </div>
                    </div>
                </div>
            </div>