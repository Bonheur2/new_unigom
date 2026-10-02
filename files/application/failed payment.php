<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Failed Payments</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Failed Payments</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="card-body">
                                        <div class="form-group col-12 col-md-6 m-auto">
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
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        
                                        
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Balance</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
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

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" id="reg_no" name="reg_no">
                    <input type="hidden" id="fee_id" name="fee_id">
                    <input type="hidden" id="invoice_id" name="invoice_id">
                    
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" class="form-control" id="student_name" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Transaction Code</label>
                        <input type="text" class="form-control" id="trans_code" name="trans_code" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Slip Number</label>
                        <input type="text" class="form-control" id="slip_no" name="slip_no" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Payment Mode</label>
                        <select class="form-control" id="user" name="user" required>
                            <option value="">Select Payment Mode</option>
                            <option value="B-AGENT">SLCB</option>
                            <option value="AFRICELL">AFRICELL</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Comment (Optional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePayment">Save Payment</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){
    //load student and dept info  
    $("#input").keyup(function(e){
        var formData = {
            keyword:$(this).val(),
            action:'search'
        }
        $("#info").attr("hidden",true);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/application/failed_payment_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    var i = 1;
                    var html = '';
                    data.forEach(function(value) {
                        var tracking = value.code || value.applicant_id;
                        var names = value.fname + " " + (value.mname ? value.mname + " " : "") + value.lname;
                        var phone = value.phone || 'N/A';
                        var email = value.email || 'N/A';
                        var balance = value.balance || '0.00';
                        
                        html += '<tr class="stu" data-id="' + value.applicant_id + '">';
                        html += '<td>' + i + '</td>';
                        html += '<td>' + tracking + '</td>';
                        html += '<td>' + names + '</td>';
                        html += '<td>' + phone + '</td>';
                        html += '<td>' + email + '</td>';
                        html += '<td>' + balance + '</td>';
                        html += '<td><button class="btn btn-sm btn-success record-payment" data-reg="' + value.code + '" data-name="' + names + '" data-invoice="' + value.invoice_id + '" data-balance="' + balance + '">Record Payment</button></td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#applications tbody').html(html);
                } else{
                    $('#applications tbody').html('<tr><td colspan="7" align="center">No applicants with failed payments found</td></tr>');
                }
            },error: function(){
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Handle record payment button click
    $(document).on('click', '.record-payment', function(){
        var reg_no = $(this).data('reg');
        var student_name = $(this).data('name');
        var invoice_id = $(this).data('invoice');
        var balance = $(this).data('balance');
        
        $('#reg_no').val(reg_no);
        $('#student_name').val(student_name);
        $('#invoice_id').val(invoice_id);
        $('#amount').val(balance);
        
        $('#paymentModal').modal('show');
    });
    
    // Handle save payment
    $('#savePayment').click(function(){
        var formData = {
            reg_no: $('#reg_no').val(),
            trans_code: $('#trans_code').val(),
            slip_no: $('#slip_no').val(),
            user: $('#user').val(),
            amount: $('#amount').val(),
            comment: $('#comment').val(),
            action: 'save_payment'
        };
        
        $.ajax({
            url: "/files/application/failed_payment_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response){
                if(response.success){
                    pop_up_success(response.message);
                    $('#paymentModal').modal('hide');
                    $('#paymentForm')[0].reset();
                    // Refresh search results
                    $('#input').trigger('keyup');
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function(){
                pop_wrong("Error saving payment!");
            }
        });
    });
});
   function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info',
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
