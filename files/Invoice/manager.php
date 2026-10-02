<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Invoice</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manager</a></div>
            </div><br>
        </div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recorded Invoices</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse-2">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="payment_table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Student ID</th>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Payments</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql=$conn->prepare("SELECT
                                                                    DISTINCT(r.reg_no),
                                                                    p.amount AS payments,
                                                                    inv.balance AS invoices
                                                                FROM
                                                                    tbl_register_program_ug r
                                                                LEFT JOIN (
                                                                    SELECT reg_no, COALESCE(SUM(amount),0) AS amount
                                                                    FROM payment
                                                                    WHERE status = 1
                                                                    GROUP BY reg_no
                                                                ) p ON r.reg_no = p.reg_no
                                                                LEFT JOIN (
                                                                    SELECT reg_no, COALESCE(SUM(balance), 0) AS balance
                                                                    FROM tbl_invoice
                                                                    GROUP BY reg_no
                                                                ) inv ON r.reg_no = inv.reg_no
                                                                GROUP BY
                                                                    r.reg_no
                                                                ORDER BY
                                                                    balance DESC;
                                                                ");
                                        $sql->execute();
                                        $i=1;
                                        while($pay=$sql->fetch()){
                                     ?>
                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>
                                        <td><?php echo $pay['reg_no']; ?></td>
                                        <td><?php echo number_format($pay['invoices'],2); ?></td>
                                        <td><?php echo number_format($pay['payments'],2); ?></td>
                                        <td><?php echo number_format($pay['invoices'] - $pay['payments'], 2); ?></td>
                                        <td>  
                                            <a href="?mis=insms&stu=<?php echo $pay['reg_no']; ?>" class="btn btn-icon btn-success btn-sm" target="_blank"><i class="fas fa-envelope"></i>&nbsp;send sms</a>
                                            <button type="button" class="btn btn-icon btn-light btn-sm view" data-id="<?php echo $pay['reg_no']; ?>"><span id="c_<?php echo str_replace("/","-", $pay['reg_no']); ?>">&nbsp;</span><i class="fas fa-eye"></i>&nbsp;check more</button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--view modal-->
    <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Statement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contents">
            </div>
        </div>
    </div>
    <!--end view modal-->
</div>
                    
                
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function(){
        $('#payment_table').DataTable({     
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 10
        });
        
        $(document).on('click', '.view', function() {
            var student = $(this).data("id");
            const loader = student.replaceAll("/", "-");
            var formData = {
                stu:student
            }
            $("#contents").html("");
            $('#c_'+loader).html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Invoice/load_finances.php",
                type: "POST",
                data: formData,
                dataType: "html",
                success: function(data){
                    $('#c_'+loader).fadeOut('fast');
                    $('#contents').html(data);
                    $('#viewModal').modal('show');
                },error: function(){
                    $('#c_'+loader).fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });
    });
</script>