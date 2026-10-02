<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Statistics</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Balances</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="default" aria-selected="true"><b>General</b></a></li>
                                            </ul>
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show table-responsive active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                    <table class="table table-hover table-sm" id="stats_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Student</th>
                                                            <th>Invoice</th>
                                                            <th>Payment</th>
                                                            <th>Balance</th>
                                                            
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                              $sql=$conn->prepare("SELECT DISTINCT(r.reg_no),ad.* FROM tbl_register_program_ug r INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no");
                                                              $sql->execute();
                                                              $i=1;
                                                              while($stu=$sql->fetch()){
                                                                $sql1=$conn->prepare("SELECT SUM(balance) AS invoice FROM tbl_invoice WHERE reg_no='".$stu['reg_no']."'");
                                                                $sql1->execute(); 
                                                                $invData=$sql1->fetch();
                                                                
                                                                $sql2=$conn->prepare("SELECT SUM(amount) AS amount FROM payment_trial WHERE reg_no='".$stu['reg_no']."' AND status=1");
                                                                $sql2->execute(); 
                                                                $pytData=$sql2->fetch();
                                                                $total+=$invData['invoice']-$pytData['amount'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?></td>
                                                                <td><?php echo number_format($invData['invoice'],2); ?></td>
                                                                <td><?php echo number_format($pytData['amount'],2); ?></td>
                                                                <td><?php echo number_format($invData['invoice']-$pytData['amount'],2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
                                                        <a href="/files/Invoice/print_balances?splz=<?php echo $splz; ?>&total=<?php echo $total; ?>&intake=<?php echo $intake; ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print All</a>
                                                     </div>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px; padding:10px;">
                                                        <button type="button" class="btn btn-success">Total Payments: <?php echo number_format($total,2); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
    });
</script>