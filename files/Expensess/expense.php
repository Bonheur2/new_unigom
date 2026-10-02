<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Expenses</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Expenses</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Record new expense</h4>
                                            <div class="card-header-action">
                                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="collapse hide" id="mycard-collapse">
                                            <div class="card-body">
                                                <form id="save_expense" action="save_expense" method="POST">
                                                    <input type="hidden" name="action" value="save_expense">
                                                    <input type="hidden" name="user" value="<?php echo $acc_id; ?>">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Beneficiary name</label><br>
                                                            <input type="text" class="form-control" name="bn_name" placeholder="Full name" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Phone number</label><br>
                                                            <input type="text" class="form-control" name="bn_phone" placeholder="Tel: 078...." required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Beneficiary address</label><br>
                                                            <input type="text" class="form-control" name="bn_address" placeholder="Type address here" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Debit account</label><br>
                                                            <select class="form-control select2" style="width:100%" name="bankId" required>
                                                                <?php
                                                                    $sql=$conn->prepare("SELECT * FROM tbl_bank ORDER BY bank_name ASC");
                                                                    $sql->execute();
                                                                    while($bank=$sql->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']." (".$bank['account_no'].") | ".$bank['currency']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Amount</label><br>
                                                            <input type="number" class="form-control" name="expenseAmount" min="0.1" step="0.01" placeholder="e.g. 50000" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Expense Date</label><br>
                                                            <input type="date" class="form-control" name="transferDate" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Purpose/ Details of Payment</label><br>
                                                            <textarea class="form-control" name="expenseDescr" maxlength="60" placeholder="Type description here" required></textarea>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12">
                                                            <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                        </div> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card" id="sample-login">
                                            <div class="card-header">
                                                <h4>Registered Expenses</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm" id="expense_table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Beneficiary name</th>
                                                            <th scope="col">Beneficiary Address</th>
                                                            <th scope="col">Beneficiary Telephone</th>
                                                            <th scope="col">Debit Bank</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Details</th>
                                                            <th scope="col">Record date</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $sql=$conn->prepare("SELECT tbl_bank.bank_name, 
                                                                                        tbl_bank.account_no, 
                                                                                        tbl_bank.currency,
                                                                                        tbl_funds_transfer.* 
                                                                                    FROM tbl_funds_transfer 
                                                                                        INNER JOIN tbl_bank ON tbl_funds_transfer.bankId=tbl_bank.bank_id
                                                                                    ORDER BY tbl_funds_transfer.transferDate DESC");
                                                            $sql->execute();
                                                            $i=1;
                                                            while($exp=$sql->fetch()){
                                                         ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $exp['bn_name']; ?></td>
                                                            <td><?php echo $exp['bn_address']; ?></td>
                                                            <td><?php echo $exp['bn_phone']; ?></td>
                                                            <td><?php echo $exp['bank_name']." (".$exp['account_no'].") | ".$exp['currency']; ?></td>
                                                            <td><?php echo number_format($exp['expenseAmount']); ?></td>
                                                            <td><?php echo $exp['expenseDescr']; ?></td>
                                                            <td><?php echo $exp['transferDate']; ?></td>
                                                            <th>  
                                                                <div class="buttons row">
                                                                    <button type="button" data-id="<?php echo $exp['expenseId']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $exp['expenseId']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                </div>
                                                            </th>
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
                </div>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating expense</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="expenseId" id="expenseId">
                                                    <input type="hidden" name="action" value="update_expense">
                                                    <input type="hidden" name="user" value="<?php echo $acc_id; ?>">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Beneficiary name</label><br>
                                                            <input type="text" class="form-control" name="bn_name" id="bn_name" placeholder="Full name">
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Phone number</label><br>
                                                            <input type="text" class="form-control" name="bn_phone" id="bn_phone" placeholder="Tel: 078....">
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Beneficiary address</label><br>
                                                            <input type="text" class="form-control" name="bn_address" id="bn_address" placeholder="Type address here">
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Debit account</label><br>
                                                            <select class="form-control select2" style="width:100%" name="bankId" id="bankId" required>
                                                                <?php
                                                                    $sql=$conn->prepare("SELECT * FROM tbl_bank ORDER BY bank_name ASC");
                                                                    $sql->execute();
                                                                    while($bank=$sql->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']." (".$bank['account_no'].") | ".$bank['currency']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Amount</label><br>
                                                            <input type="number" class="form-control" name="expenseAmount" id="expenseAmount" min="0.1" step="0.01" placeholder="e.g. 50000">
                                                        </div>
                                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                                            <label>Expense Date</label><br>
                                                            <input type="date" class="form-control" name="transferDate" id="transferDate">
                                                        </div>
                                                        <div class="form-group col-12">
                                                            <label>Purpose/ Details of Payment</label><br>
                                                            <textarea class="form-control" name="expenseDescr" id="expenseDescr" maxlength="60" placeholder="Type description here"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                   <!--end update modal-->
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#expense_table').DataTable({     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       });

    //save expense
    $("#save_expense").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Expenses/expense_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_expense')[0].reset();
                        $('#expense_table').load(location.href + " #expense_table");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_info(data.message); 
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });

//pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_expense'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Expenses/expense_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $('#expenseId').val(data.expenseId);
                    $('#bn_name').val(data.bn_name);
                    $('#bn_phone').val(data.bn_phone);
                    $('#bn_address').val(data.bn_address);
                    
                    $('#expenseAmount').val(data.expenseAmount);
                    $('#transferDate').val(data.transferDate);
                    $('#expenseDescr').val(data.expenseDescr);

                    var selectElement = document.getElementById('bankId');
                    var selectedOption = selectElement.querySelector('option[value="' + data.bankId + '"]');
                    selectedOption.selected = true;
                    selectElement.prepend(selectedOption);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    //update expense
    $("#update_form").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Expenses/expense_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#expense_table').load(location.href + " #expense_table");
                        pop_up_success(data.message);
                        
                        $("#updateModal").modal('hide');
                    }
                    if(data.status==401){
                        pop_info(data.message); 
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
    });
</script>