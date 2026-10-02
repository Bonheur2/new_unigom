<?php
include('../../../meet/access.php');
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Payment</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Individual</a></div>
            </div><br>
        </div>
        
        
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group col-12 col-md-4 m-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="search by reg. number or names" id="input" value="<?=$_GET['stu'] ?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
  

                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="contents">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section-body" id="info" hidden>
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Student information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Specialzization</th>
                                    <th>Level</th>
                                    <th>Academic year</th>
                                    <!--<th>Fee Category</th>-->
                                    <th>Invoice</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="academics">
                                    
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section-body" id="payForm" hidden>
    <div class="card-header">
        <h4>New Payment</h4>
    </div>
    <div class="collapse show" id="mycard-collapse">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12" id="main">
                <div class="card">
                    <div class="card-body">
                       <!-- Hidden fields populated by "Next" button -->
                       <input type="hidden" name="reg_no" value="" class="form-control">
                       <input type="hidden" name="acad_cycle_id" value="" class="form-control">
                       <input type="hidden" name="bank_id" value="" class="form-control">
                       <input type="hidden" name="slip_no" value="" class="form-control">
                       <input type="hidden" name="amount" value="" class="form-control">
                       <input type="hidden" name="action" value="invoice_payment" class="form-control">

                       <!-- Header showing total amount to be dispatched -->
                       <h4>Amount To Be Dispatched in Invoices: <span id="totalAmountDisplay"></span></h4>
                       
                       <!-- Container for invoice table -->
                       <div class="container my-4">
                           <table class="table table-bordered">
                               <thead>
                                   <tr>
                                       <th>Fee Category</th>
                                       <th>Invoice Balance</th>
                                       <th>Amount to Apply</th>
                                   </tr>
                               </thead>
                               <tbody id="invoiceTableBody">
                                   <!-- Dynamic content will be added here -->
                               </tbody>
                               
                           </table>
                           <button id="recordPaymentBtn" class="btn btn-primary" type="button">Record Payment</button>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            
            <br>
             <!--payments-->
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recorded payments</h4>
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
                                        <th scope="col">Academic Year</th></th>
                                        <th scope="col">Account</th>
                                        <th scope="col">Slip Number</th>
                                        <th scope="col">Fee category</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Transaction Date</th>
                                        <th scope="col">Action</th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql=$conn->prepare("SELECT p.*,b.bank_name,b.account_no,f.name as fee_name,r.reg_prg_id,pr.campus_id, ac.acad_year
                                                                FROM payment p 
                                                                    INNER JOIN tbl_bank b ON p.bank_id=b.bank_id
                                                                    INNER JOIN tbl_fee_category f ON p.fee_id=f.id
                                                                    INNER JOIN tbl_acad_cycle ac ON p.acad_cycle_id=ac.acad_cycle_id
                                                                    INNER JOIN tbl_register_program_ug r ON p.reg_no=r.reg_no AND p.acad_cycle_id=r.acad_cycle_id
                                                                    INNER JOIN tbl_program_type pr ON r.prg_type=pr.prg_type_id
                                                                WHERE pr.campus_id='".$camp_id."' AND p.status=1 ORDER BY p.recorded_date DESC");
                                        $sql->execute();
                                        $i=1;
                                        while($pay=$sql->fetch()){
                                     ?>
                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>
                                        <td><?php echo $pay['reg_no']; ?></td>
                                        <td><?php echo $pay['acad_year']; ?></td>
                                        <td><?php echo $pay['bank_name']." | ".$pay['account_no']; ?></td>
                                        <td><?php echo $pay['slip_no']; ?></td>
                                        <td><?php echo $pay['fee_name']; ?></td>
                                        <td><?php echo number_format($pay['amount'],2); ?></td>
                                        <td><?php echo $pay['date']; ?></td>
                                                                <?php 
                                                                $total+=$pay['amount'];
                                                                ?>
                                        <th>  
                                            <button type="button" class="btn btn-icon btn-light btn-sm del" data-id="<?php echo $pay['id']; ?>"><i class="fas fa-trash"></i>&nbsp;cancel</button>
                                        </th>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                                    <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
                                                        <a href="/files/Payment/print_all_payments?splz=<?php echo $splz; ?>&total=<?php echo $total; ?>&intake=<?php echo $intake; ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print All</a>
                                                     </div>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px; padding:10px;">
                                                        <button type="button" class="btn btn-success">Total invoice: <span  id="tot"><?php echo number_format($total,2); ?></span></button>
                                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Transaction Date Modal -->
<div class="modal fade" id="transactionDateModal" tabindex="-1" aria-labelledby="transactionDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDateModalLabel">Edit Transaction Date</h5>
                
            </div>
            <div class="modal-body">
                <form id="editTransactionDateForm">
                    <input type="hidden" id="paymentId">
                    <input type="hidden" name="action" value="date_edit_pay" class="form-control">
                    <div class="mb-3">
                        <label for="transactionDate" class="form-label">Transaction Date</label>
                        <input type="date" class="form-control" id="transactionDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


    
    <!--cancel modal-->
    <form action="cancel_payment" method="POST" id="cancel_payment">
        <div class="modal fade" tabindex="-1" role="dialog" id="cancelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment canceling</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="pyt_id" name="pyt_id">
                        <input type="hidden" name="action" value="cancel">
                        <div class="form-group col-12 col-sm-12 col-lg-12">
                            <label>Reason for canceling</label>
                            <textarea class="form-control" name="comment" placeholder="your reason goes here" rows="5" maxlength="100" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Leave</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner10"></span>&nbsp;<span id="indicator210">Cancel payment</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end cancel modal-->
    
    <!--slip verification-->
    <form action="verification_payment" method="POST" id="verification_payment">
    <div class="modal fade" tabindex="-1" role="dialog" id="cancelModal1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reg_no" name="reg_no" placeholder="Registration No">
                    <input type="hidden" id="acad" name="acad_cycle_id" placeholder="Academic Year">
                    
                    <input type="hidden" name="action" value="cancel">
                    <div class="form-group col-12 col-sm-12 col-lg-12">
                        <label>Account No</label>
                        <select class="form-control select2" style="width:100%" id="bank_id" name="bank_id" required>
                        <?php
                        $sql=$conn->prepare("SELECT * FROM tbl_bank ORDER BY bank_name ASC");
                        $sql->execute();
                        while($bank=$sql->fetch()){
                            ?>
                        <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']." (".$bank['account_no'].") | ".$bank['currency']; ?> </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-12 col-sm-12 col-lg-12">
                        <label>Slip Number</label>
                        <input type="text" id="slip_number" name="slip_no" class="form-control">
                    </div>
                    <div class="form-group col-12 col-sm-12 col-lg-12">
                        <label>Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <!-- Changed 'a' tag to 'button' and added an id to handle the click event -->
                    <button type="button" id="nextBtn" class="btn btn-primary btn-sm">
                        <span id="spinner10"></span>&nbsp;<span id="indicator210">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
    <!-- endof slip velification-->
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

        //load student and dept info  
        $("#input").keyup(function(e) {
    var formData = {
        keyword: $(this).val(),
        action: 'search'
    };

    $("#info").attr("hidden", true);
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

    $.ajax({
        url: "/files/Student/student_controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            console.log("Search Data Received:", data); // Debugging output

            $('#spinner').html("<i class='fas fa-search'></i>");

            if (data.length > 0) {
                let i = 1;
                let html = '';
                data.forEach(function(value) {
                    let reg = value.reg_no;
                    let names = value.fname + " " + value.lname;
                    html += `<tr class="stu" data-id="${reg}">`;
                    html += `<th>${i}</th>`;
                    html += `<th>${reg}</th>`;
                    html += `<td>${names}</td>`;
                    html += `</tr>`;
                    i++;
                });
                $('#contents').html(html);
            } else {
                $('#contents').html('<tr><td colspan="3" align="center">Oops, no data found</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#spinner').html("<i class='fas fa-search'></i>");
            console.error("Search Error:", error); // Error log
            pop_wrong("Something went wrong!");
        }
    });
});
        
        $(document).on('click', '.stu', function() {
    var student = $(this).data("id");
    var formData = {
        stu: student,
        action: 'load_info_payment'
    };

    $("#input").val($(this).data("id"));
    $("#contents").html("");
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

    $.ajax({
        url: "/files/Student/student_controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            console.log("Student Data Received:", data); // Debugging output
            $('#spinner').html("<i class='fas fa-search'></i>");

            if (data.length > 0) {
                $("#info").removeAttr('hidden');
                $("#academics").html("");
                let i = 1;

                const numberFormat = new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const uniqueAcadCycleIds = new Set();

                data.forEach(function(value) {
                    let row = `<tr>`;
                    row += `<td>${i}</td>`;
                    row += value.splz_full_name ? `<td>${value.splz_full_name}</td>` : `<td>-</td>`;
                    row += value.level_full_name ? `<td>${value.level_full_name}</td>` : `<td>-</td>`;
                    row += `<td>${value.acad_year}</td>`;
                    // row += `<td>${value.fee_name}</td>`;
                    row += `<td>${numberFormat.format(value.invoice)}</td>`;
                    row += `<td>${numberFormat.format(value.payment)}</td>`;

                    // Add "Take fee" button only if this acad_cycle_id hasn't been added before
                    if (!uniqueAcadCycleIds.has(value.acad_cycle_id)) {
                        uniqueAcadCycleIds.add(value.acad_cycle_id);
                        row += `<td><button class="btn btn-sm btn-primary approveR" data-id="${value.reg_no}" data-acad_cycle_id="${value.acad_cycle_id}" type="button"><span>Take fee</span></button></td>`;
                    } else {
                        row += `<td></td>`; // Empty cell if button is not added
                    }

                    row += `</tr>`;

                    $("#academics").append(row);
                    i++;
                });
            } else {
                $('#academics').html('<tr><td colspan="8" align="center">Oops, no data found</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#spinner').html("<i class='fas fa-search'></i>");
            console.error("Load Info Error:", error); // Error log
            pop_wrong("Something went wrong!");
        }
    });
});






$(document).on('click', '.approveR', function() {
    // Get the reg_no and acad_cycle_id from the data attributes of the clicked button
    var reg_no = $(this).data('id');
    var acad_cycle_id = $(this).data('acad_cycle_id');

    // Set the values in the hidden fields inside the modal
    $('#reg_no').val(reg_no);
    $('#acad').val(acad_cycle_id);

    // Show the modal
    $("#cancelModal1").modal('show');
});


document.getElementById('nextBtn').addEventListener('click', function() {
    var regNo = document.getElementById('reg_no').value;
    var acad = document.getElementById('acad').value;
    var accountNo = document.getElementById('bank_id').value;
    var slipNumber = document.getElementById('slip_number').value;
    var amount = parseFloat(document.getElementById('amount').value);

    // Populate the hidden inputs inside #payForm
    document.querySelector("#payForm input[name='reg_no']").value = regNo;
    document.querySelector("#payForm input[name='acad_cycle_id']").value = acad;
    document.querySelector("#payForm input[name='bank_id']").value = accountNo;
    document.querySelector("#payForm input[name='slip_no']").value = slipNumber;
    document.querySelector("#payForm input[name='amount']").value = amount;

    // Display the total amount in the header
    document.getElementById('totalAmountDisplay').innerText = amount.toFixed(2);

    // Make #payForm visible
    document.getElementById('payForm').removeAttribute('hidden');

    // Hide the modal
    $('#cancelModal1').modal('hide');

    $.ajax({
        url: "/files/Student/fetch_invoice.php",
        type: "POST",
        data: {
            reg_no: regNo,
            acad_cycle_id: acad
        },
        dataType: "JSON",
        success: function(data) {
            const tableBody = document.getElementById('invoiceTableBody');
            tableBody.innerHTML = ''; // Clear any previous data

            if (data && data.length > 0) {
                // Check if level_id and intake_id are NULL for all invoices (use the first record for simplicity)
                const isNullCondition = data[0].level_id === null && data[0].intake_id === null;

                if (isNullCondition) {
                    // Display only one input for the total remaining balance if level_id and intake_id are NULL
                    let totalRemainingBalance = 0;

                    data.forEach(invoice => {
                        totalRemainingBalance += parseFloat(invoice.remaining_balance) || 0;
                    });

                    const row = document.createElement('tr');

                    const feeNameCell = document.createElement('td');
                    feeNameCell.textContent = "Total Remaining Balance";
                    row.appendChild(feeNameCell);

                    const balanceCell = document.createElement('td');
                    balanceCell.textContent = totalRemainingBalance.toFixed(2);
                    row.appendChild(balanceCell);

                    const inputCell = document.createElement('td');
                    const amountInput = document.createElement('input');
                    amountInput.type = 'number';
                    amountInput.classList.add('form-control');
                    amountInput.min = 0;
                    amountInput.max = totalRemainingBalance;
                    amountInput.step = '0.01';
                    amountInput.value = 0;

                    amountInput.addEventListener('input', function() {
                        let appliedAmount = parseFloat(amountInput.value) || 0;

                        if (appliedAmount > totalRemainingBalance) {
                            alert(`Amount cannot exceed the remaining balance of ${totalRemainingBalance.toFixed(2)}`);
                            amountInput.value = totalRemainingBalance.toFixed(2);
                        }

                        const remainingAmount = amount - appliedAmount;
                        document.getElementById('totalAmountDisplay').innerText = remainingAmount < 0 ? '0.00' : remainingAmount.toFixed(2);

                        if (remainingAmount < 0) {
                            alert("The total applied amount cannot exceed the available balance.");
                            amountInput.value = (appliedAmount + remainingAmount).toFixed(2);
                        }
                    });

                    inputCell.appendChild(amountInput);
                    row.appendChild(inputCell);

                    tableBody.appendChild(row);
                } else {
                    // Display each invoice separately if level_id and intake_id are not NULL
                    data.forEach(invoice => {
                        const row = document.createElement('tr');

                        const feeNameCell = document.createElement('td');
                        feeNameCell.textContent = invoice.fee_name;
                        row.appendChild(feeNameCell);

                        const remainingBalance = parseFloat(invoice.remaining_balance);
                        const balanceCell = document.createElement('td');
                        balanceCell.textContent = remainingBalance.toFixed(2);
                        row.appendChild(balanceCell);

                        const inputCell = document.createElement('td');
                        const amountInput = document.createElement('input');
                        amountInput.type = 'number';
                        amountInput.classList.add('form-control');
                        amountInput.min = 0;
                        amountInput.max = remainingBalance;
                        amountInput.step = '0.01';
                        amountInput.value = 0;

                        const invoiceIdInput = document.createElement('input');
                        invoiceIdInput.type = 'hidden';
                        invoiceIdInput.name = 'invoice_id';
                        invoiceIdInput.value = invoice.invoice_id;

                        amountInput.addEventListener('input', function() {
                            let appliedTotal = 0;

                            if (parseFloat(this.value) > remainingBalance) {
                                alert(`Amount cannot exceed the remaining balance of ${remainingBalance.toFixed(2)}`);
                                this.value = remainingBalance.toFixed(2);
                            }

                            document.querySelectorAll('#invoiceTableBody input[type="number"]').forEach(input => {
                                appliedTotal += parseFloat(input.value) || 0;
                            });

                            const remainingAmount = amount - appliedTotal;
                            document.getElementById('totalAmountDisplay').innerText = remainingAmount.toFixed(2);

                            if (remainingAmount < 0) {
                                const excess = appliedTotal - amount;
                                this.value = (parseFloat(this.value) - excess).toFixed(2);
                                document.getElementById('totalAmountDisplay').innerText = '0.00';
                                alert("The total applied amount cannot exceed the available balance.");
                            }
                        });

                        inputCell.appendChild(amountInput);
                        row.appendChild(inputCell);
                        row.appendChild(invoiceIdInput);

                        tableBody.appendChild(row);
                    });
                }
            } else {
                 pop_wrong('No invoices found for the selected student.');
                // alert("No invoices found for the selected student");
            }
        },
        error: function() {
            console.error("Error fetching invoices.");
            pop_wrong('Failed to retrieve invoices.');
            // alert("Failed to retrieve invoices.");
        }
    });
});

document.getElementById('recordPaymentBtn').addEventListener('click', function(e) {
    e.preventDefault();

    const regNo = document.getElementById('reg_no').value;
    const acad = document.getElementById('acad').value;
    const bankId = document.getElementById('bank_id').value;
    const slipNo = document.getElementById('slip_number').value;
    const amount = parseFloat(document.getElementById('amount').value);

    // Calculate remaining amount from totalAmountDisplay
    const remainingAmount = parseFloat(document.getElementById('totalAmountDisplay').innerText) || 0;

    // Initialize FormData
    const formData = new FormData();
    formData.append("reg_no", regNo);
    formData.append("acad_cycle_id", acad);
    formData.append("bank_id", bankId);
    formData.append("slip_no", slipNo);
    formData.append("amount", amount);
    formData.append("action", "invoice_payment");

    if (remainingAmount > 0) {
        formData.append("remaining_amount", remainingAmount);
    }

    // Loop through each invoice and add to FormData
    let hasAmount = false;
    document.querySelectorAll('#invoiceTableBody tr').forEach((row, index) => {
        const appliedAmount = parseFloat(row.querySelector('input[type="number"]').value) || 0;
        const invoiceId = row.querySelector('input[name="invoice_id"]').value;

        if (appliedAmount > 0) {
            formData.append(`invoice_id[${index}]`, invoiceId);
            formData.append(`amount[${index}]`, appliedAmount);
            hasAmount = true;  // Flag to indicate at least one amount is applied
        }
    });

    // Check if there is at least one non-zero amount
    if (!hasAmount) {
        pop_wrong('Please enter at least one amount to apply.');
        // alert("Please enter at least one amount to apply.");
        return;
    }

    // Debugging output to check the contents of FormData
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // AJAX request to save the payment data
    $.ajax({
        url: "/files/Payment/payment_controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.status == 200) {
                 pop_up_success('Payments saved successfully.');
                // alert("Payments saved successfully.");
            } else {
                alert("Error: " + data.message);
            }
        },
        error: function() {
             pop_wrong('An error occurred while saving payments.');
            // alert("An error occurred while saving payments.");
        }
    });
});






        

        $(document).on('click','.del',function () {
            $("#pyt_id").val($(this).data('id'));
            $("#cancelModal").modal('show');
        });
        
        //reciept
        
// Event listener to open modal on clicking "Transaction Date"
document.querySelectorAll('#payment_table tbody tr').forEach(row => {
    row.querySelector('td:nth-child(8)').addEventListener('click', function() {
        const paymentId = row.querySelector('.del').getAttribute('data-id');
        const currentDate = row.querySelector('td:nth-child(8)').innerText;

        // Set values in the modal
        document.getElementById('paymentId').value = paymentId;
        document.getElementById('transactionDate').value = currentDate;

        // Show the modal
        new bootstrap.Modal(document.getElementById('transactionDateModal')).show();
    });
});

// Handle form submission for date change
document.getElementById('editTransactionDateForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Create FormData from the specific form being submitted
    const form = e.target;
    const formData = new FormData(form);

    // Add any additional values you might need, if not in the form
    formData.append('id', document.getElementById('paymentId').value);
    formData.append('date', document.getElementById('transactionDate').value);

    $.ajax({
        url: '/files/Payment/payment_controller.php', 
        type: 'POST',
        data: formData,
        processData: false, // Necessary for FormData
        contentType: false, // Necessary for FormData
        success: function(response) {
            if (response.status === 200) {
                document.querySelector(`#payment_table tbody tr .del[data-id="${formData.get('id')}"]`).closest('tr').querySelector('td:nth-child(8)').innerText = formData.get('date');
                $('#transactionDateModal').modal('hide'); 
                pop_up_success('Transaction date updated successfully.');
                $('#payment_table').load(location.href + " #payment_table");
            } else {
                pop_info(response.message);
            }
        },
        error: function() {
            pop_wrong("Something went wrong!");
        }
    });
});


        // cancel payment
        $("#cancel_payment").submit(function (e) {
             e.preventDefault();
            var formData= new FormData(this);
            swal({
                title: "Are you sure?",
                text: "You are about to cancel this payment!, this can't be undone",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner10').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/Payment/payment_controller.php",
                    data: formData,
                    dataType: "JSON",
                    contentType:false,
                    processData: false,
                    success:function(data){
                        $('#spinner10').fadeOut('fast');
                        if(data.status==401){
                            pop_info(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                            $("#cancel_payment")[0].reset();
                            $("#cancelModal").modal('hide');
                            $('#payment_table').load(location.href + " #payment_table");
                        }
    				},
    				error:function(error){
    				    $('#spinner10').fadeOut('fast');
                        pop_wrong("Something went wrong!");
    				}
                });
                }
               else {
                    swal("operation Cancelled!!");
                }
            });
        });
    });
</script>