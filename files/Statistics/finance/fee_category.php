<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Statistics</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Fee category</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="default" aria-selected="true"><b>General</b></a></li>
                                                <li class="nav-item"><a class="nav-link" id="specific-tab" data-toggle="tab" href="#specific" role="tab" aria-controls="specific" aria-selected="false"><b>Specific</b></a></li>
                                            </ul>
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show table-responsive active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                    <table class="table table-hover table-sm" id="stats_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Fee category</th>
                                                            <th>Invoice</th>
                                                            <th>Payments</th>
                                                            <th>Balance</th>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                            $sql=$conn->prepare("SELECT * FROM fee_category WHERE status=1");
                                                            $sql->execute(); 
                                                            $i=1;
                                                            while($data=$sql->fetch()){
                                                                $getInvoices=$conn->prepare("SELECT SUM(balance) as invoice FROM tbl_invoice WHERE fee_id='".$data['id']."'");
                                                                $getInvoices->execute();
                                                                $invoiceData=$getInvoices->fetch();
                                                                $invoice=$invoiceData['invoice'];
                                                                
                                                                $getPayments=$conn->prepare("SELECT SUM(amount) as amount FROM payment WHERE fee_id='".$data['id']."' AND status=1");
                                                                $getPayments->execute();
                                                                $paymentData=$getPayments->fetch();
                                                                $payment=$paymentData['amount'];
                                                                
                                                                $balance=$payment-$invoice;
                                                                
                                                                if($payment==0 && $invoice==0){continue;}
                                                                $ppay=abs($payment*100/($invoice>0?$invoice:1));
                                                                $pbal=abs($balance*100/($invoice>0?$invoice:1));
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['name']; ?></td>
                                                                <td><?php echo number_format($invoice,2); ?></td>
                                                                <td><?php echo number_format($payment,2)."| ".number_format($ppay,2)." %"; ?></td>
                                                                <td><?php echo number_format($balance,2)."| ".number_format($pbal,2)." %"; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade" id="specific" role="tabpanel" aria-labelledby="specific-tab">
                                                    <form id="load_general_stats" action="load_general_stats">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Fee category</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="fee">
                                                                <?php
                                                                    $sql_fee=$conn->prepare("SELECT * FROM fee_category WHERE status=1");
                                                                    $sql_fee->execute();
                                                                    while($fee=$sql_fee->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" >
                                                                <label>Academic year</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="acad_cycle_id" id="acad_cycle_id">
                                                                <?php
                                                                    $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE 1 ORDER BY acad_cycle_id DESC");
                                                                    $sql->execute();
                                                                    while($acad=$sql->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $acad['acad_cycle_id']; ?>"><?php echo $acad['acad_year']; ?> </option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" style="padding-top:10px;">
                                                                <label></label><br>
                                                                <button type="submit" class="btn btn-primary"><span id="spinner20"></span>&nbsp;<span id="indicator20">Load data</span></button>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-lg-12" id="statistics">
                                                            </div>
                                                        </div>
                                                    </form>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
    //load_stats
    $("#load_general_stats").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/files/Statistics/finance/load_fee_stats.php",
                type: "POST",
                data: formData,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
                    $("#statistics").html(data);
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
    });
   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>