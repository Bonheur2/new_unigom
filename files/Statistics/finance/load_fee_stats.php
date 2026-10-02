<?php
include ('../../../meet/con.php');
$fee=$_REQUEST['fee'];
$acad_cycle_id=$_REQUEST['acad_cycle_id'];
$sql=$conn->prepare("SELECT * FROM fee_category WHERE id='".$fee."'");
$sql->execute(); 
$data=$sql->fetch();

$getInvoices=$conn->prepare("SELECT SUM(balance) as invoice FROM tbl_invoice WHERE fee_id='".$fee."' AND acad_cycle_id='".$acad_cycle_id."'");
$getInvoices->execute();
$invoiceData=$getInvoices->fetch();
$invoice=$invoiceData['invoice'];
                                                                
$getPayments=$conn->prepare("SELECT SUM(amount) as amount FROM payment WHERE fee_id='".$fee."' AND acad_cycle_id='".$acad_cycle_id."' AND status=1");
$getPayments->execute();
$paymentData=$getPayments->fetch();
$payment=$paymentData['amount'];
$balance=$payment-$invoice;
                                                                
$ppay=abs($payment*100/($invoice>0?$invoice:1));
$pbal=abs($balance*100/($invoice>0?$invoice:1));
?>
            <div class="row" style="border-radius:5px; border: 2px solid green;">
                <div class="col-12 col-md-6 col-lg-6" style="border-right: 1px solid green;">
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job">Fee category</div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['name']; ?></b></a></div>
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