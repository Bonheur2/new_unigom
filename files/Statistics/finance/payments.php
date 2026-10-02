<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Statistics</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Payments</a></div>
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
                                                            <th>Fee Category</th>
                                                            <th>Academic year</th>
                                                            <th>Amount</th>
                                                            
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $sql=$conn->prepare("SELECT SUM(pyt.amount) AS amount, 
                                                                                            pyt.reg_no,
                                                                                            ac.acad_year,
                                                                                            ad.fname,
                                                                                            ad.lname,
                                                                                            fee.name as fee_name
                                                                                        FROM payment_trial pyt LEFT JOIN tbl_acad_cycle ac ON pyt.acad_cycle_id=ac.acad_cycle_id
                                                                                                             inner JOIN tbl_admission ad ON pyt.reg_no=ad.reg_no
                                                                                                             LEFT JOIN tbl_fee_category fee ON pyt.fee_id=fee.id
                                                                                        WHERE pyt.status=1
                                                                                        GROUP BY pyt.reg_no, pyt.fee_id
                                                                                        ORDER BY pyt.acad_cycle_id DESC");
                                                                $sql->execute(); 
                                                                $i=1;
                                                                while($data=$sql->fetch()){
                                                                    $total+=$data['amount'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['reg_no']." | ".$data['fname']." ".$data['lname']; ?></td>
                                                                <td><?php echo $data['fee_name']; ?></td>
                                                                <td><?php echo $data['acad_year']; ?></td>
                                                                <td><?php echo number_format($data['amount'],2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
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