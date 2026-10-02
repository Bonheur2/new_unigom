<?php
include ('../../meet/con.php');

$reg_no = $_REQUEST['stu'];
$type = $_REQUEST['type'];

//Get personal data
$sql=$conn->prepare("SELECT fname, lname FROM  tbl_admission WHERE reg_no = '".$reg_no."'");
$sql->execute(); 
$data=$sql->fetch();


//get debt info
$debtData=$conn->prepare("SELECT SUM(balance) as debt FROM tbl_invoice WHERE reg_no = '".$reg_no."'");
$debtData->execute();
$debtBalance=$debtData->fetch();
$debt=$debtBalance['debt'];

$paidTotal=$conn->prepare("SELECT SUM(amount) as paid FROM payment WHERE reg_no = '".$reg_no."' AND status=1;");
$paidTotal->execute();
$paidBalance=$paidTotal->fetch();
$paid=$paidBalance['paid'];

$balance=$debt-$paid;

$ppaid=$paid*100/($debt>0?$debt:1);
$pbal=$balance*100/($debt>0?$debt:1);
?>
<?php if($type==1){ ?>
    <div class="card" style="border-radius:5px; border: 2px solid green;">
        <div class="card-body" style="margin-bottom:10px; display:flex; flex-direction:row; justify-content:center;">
            <div class="row col-12 col-md-10 col-lg-10">
                <div class="col-12 col-md-6 col-lg-6" style="border-right:1px solid grey;">
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job"><b>Student ID</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $reg_no; ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job">Names</div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['fname']." ".$data['lname']; ?></b></a></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job">Total debt</div>
                            <div class="user-detail-name"><a href="#"><b><?php echo number_format($debt,2); ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="article-user-details">
                            <div class="text-job"><b>Paid Amount</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo number_format($paid,2)." | ".number_format($ppaid,2)."%"; ?></b></a></div>
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
        </div>
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Financial_report/print_financial_report?stu=<?php echo base64_encode(json_encode($reg_no)); ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
    </div>
<?php } if($type==2){ ?>
    <div class="card">
        <div class="card-header" style="display:flex; flex-direction:row; justify-content:center; border-radius:2px; border: 2px solid green;">
            <h5>INVOICES FOR "<?php echo $data['fname']." ".$data['lname']; ?> | <?php echo $reg_no; ?>"</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="report_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Academic Year</th>
                            <th scope="col">Fee category</th></th>
                            <th scope="col">Invoice date</th>
                            <th scope="col">Description</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                            $stmt=$conn->prepare("SELECT inv.*,ac.acad_year,f.name as fee_name 
                                                        FROM tbl_invoice inv
                                                            LEFT JOIN tbl_acad_cycle ac ON inv.acad_cycle_id=ac.acad_cycle_id
                                                            LEFT JOIN tbl_fee_category f ON inv.fee_id=f.id
                                                        WHERE inv.reg_no='".$reg_no."' AND invoice_status=1 AND approval_status=1 ");
                            $stmt->execute();
                            $i=1;
                            while($inv=$stmt->fetch()){
                                $total+=$inv['balance'];
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo number_format($inv['balance'],2); ?></td>
                            <td><?php echo $inv['acad_year']; ?></td>
                            <td><?php echo $inv['fee_name']; ?></td>
                            <td><?php echo $inv['invoice_date']; ?></td>
                            <td><?php echo $inv['month']!=null?$inv['month']:$inv['comment']; ?></td>
                            <?php
                            if($inv['payment_status']==1){
                            ?>
                            <td></td>
                            <?php
                            }
                            else{
                            ?>
                             <td><button type="button" data-id="<?php echo $inv['id']; ?>" class="btn btn-sm btn-light cancel"><i class="fas fa-cancel"></i>&nbsp;<span id="spinner_<?php echo $data['id']; ?>"></span>&nbsp;Cancel</button></td>
                             <?php
                            }
                             ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($stmt->rowCount()!=0){ ?>
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Financial_report/print_financial_report?stu=<?php echo base64_encode(json_encode($reg_no)); ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
        <?php } ?>
    </div>
    
<?php } if($type==3){ ?>
    <div class="card">
        <div class="card-header" style="display:flex; flex-direction:row; justify-content:center; border-radius:2px; border: 2px solid green;">
            <h5>PAYMENTS BY "<?php echo $data['fname']." ".$data['lname']; ?> | <?php echo $reg_no; ?>"</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="report_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Academic Year</th>
                            <th scope="col">Fee category</th></th>
                            <th scope="col">Account no</th>
                            <th scope="col">Slip No</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                            $stmt=$conn->prepare("SELECT p.*,ac.acad_year,b.bank_code,b.account_no,f.name as fee_name 
                                                        FROM payment p
                                                            INNER JOIN tbl_acad_cycle ac ON p.acad_cycle_id=ac.acad_cycle_id
                                                            INNER JOIN tbl_fee_category f ON p.fee_id=f.id
                                                            INNER JOIN tbl_bank b ON p.bank_id=b.bank_id
                                                        WHERE p.reg_no='".$reg_no."'");
                            $stmt->execute();
                            $i=1;
                            while($pay=$stmt->fetch()){
                                $total+=$pay['amount'];
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo number_format($pay['amount'],2); ?></td>
                            <td><?php echo $pay['acad_year']; ?></td>
                            <td><?php echo $pay['fee_name']; ?></td>
                            <td><?php echo $pay['bank_code']." | ".$pay['account_no']; ?></td>
                            <td><?php echo $pay['slip_no']; ?></td>
                            <td><?php echo $pay['date']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($stmt->rowCount()!=0){ ?>
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Financial_report/print_financial_report?stu=<?php echo base64_encode(json_encode($reg_no)); ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
        <?php } ?>
    </div>
    
<?php } else if($type==4){ ?>
    <div class="card">
        <div class="card-header" style="display:flex; flex-direction:row; justify-content:center; border-radius:2px; border: 2px solid green;">
            <h5>Payment - Invoice BY "<?php echo $data['fname']." ".$data['lname']; ?> | <?php echo $reg_no; ?>"</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" border="1" id="report_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Debt</th>
                            <th scope="col">Payment</th>
                            <th scope="col">Balance</th></th>
                            <th scope="col">Academic Year</th>
                            <th scope="col">Fee category</th>
                            <th scope="col">Description</th>
                            <th scope="col">Account</th>
                            <th scope="col">Slip No</th>
                            <th scope="col">Transaction code</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                            $stmt=$conn->prepare("SELECT p.amount as amount, p.slip_no as slip_no, p.date as date,p.trans_code,
                                                        ac.acad_year, b.bank_code as bank_code, b.account_no as account_no,
                                                        f.name as fee_name, NULL as balance, NULL as comment, NULL as month
                                                    FROM payment p
                                                        INNER JOIN tbl_acad_cycle ac ON p.acad_cycle_id=ac.acad_cycle_id
                                                        INNER JOIN tbl_fee_category f ON p.fee_id=f.id
                                                        INNER JOIN tbl_bank b ON p.bank_id=b.bank_id
                                                        WHERE p.reg_no='".$reg_no."'
                                                    UNION ALL
                                                    SELECT NULL as amount, NULL as slip_no, inv.invoice_date as date,NULL as trans_code,
                                                               ac.acad_year, NULL as bank_code, NULL as account_no,
                                                               f.name as fee_name, inv.balance as balance, inv.comment as comment, inv.month as month
                                                    FROM tbl_invoice inv
                                                        INNER JOIN tbl_acad_cycle ac ON inv.acad_cycle_id=ac.acad_cycle_id
                                                        INNER JOIN tbl_fee_category f ON inv.fee_id=f.id
                                                        WHERE inv.reg_no='".$reg_no."'
                                                        ORDER BY date ASC");
                            $stmt->execute();
                            $i=1;
                            $debt=0;
                            $paid=0;
                            $bal=0;
                            while($pay=$stmt->fetch()){
                                $debt+=$pay['balance'];
                                $paid+=$pay['amount'];
                                $bal=$debt-$paid;
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $pay['date']; ?></td>
                            <td><?php echo $pay['balance']!=null?number_format($pay['balance'],1):''; ?></td>
                            <td><?php echo $pay['amount']!=null?number_format($pay['amount'],1):''; ?></td>
                            <td><?php echo number_format($bal,1); ?></td>
                            <td><?php echo $pay['acad_year']; ?></td>
                            <td><?php echo $pay['fee_name']; ?></td>
                            <td><?php echo $pay['comment']; ?></td>
                            <td><?php echo $pay['bank_code']!=null?$pay['bank_code']." | ".$pay['account_no']:""; ?></td>
                            <td><?php echo $pay['slip_no']; ?></td>
                            <td><?php echo $pay['trans_code']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($stmt->rowCount()!=0){ ?>
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Financial_report/print_financial_report?stu=<?php echo base64_encode(json_encode($reg_no)); ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
        <?php } ?>
    </div>
    
<?php } ?>

<script>
$(document).ready(function(){

    $('#stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
    });
    
    // cancel invoice
    $(document).on('click','.cancel',function () {
        var role_id = $(this).data('role_id');
        if(role_id==13){
         var action_setter='cancel';   
        }else{
           var action_setter='cancel_not_daf';  
        }
        
        var data_id = $(this).data('id');
        
        var getData= {
                id: data_id,
                action: action_setter
            };
        swal({
            title: "Are you sure?",
            text: "You are about to cancel this invoice. Are You Sure You Want to Cancel This Invoice? Click OK to Confirm",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Invoice/invoice_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_info(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#stats_table').load(location.href + " #stats_table");
                        $('#tot').load(location.href + " #tot");
                        $('#stats_table').DataTable().draw();
                    }
				},
				error:function(error){
				    $('#spinner_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
           else {
                swal("operation Cancelled!!");
            }
        });
    }
    
    
    
    );
});

    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Error',
            message: feedback,
            position: 'topCenter'
      });
    }
    function pop_info(feedback) {
        iziToast.info({
            title: 'Ooops',
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