<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Applicant Payment</h3>
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
                                    <th>Names</th>
                                    <th>Invoice</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="academics_appl">
                                    
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if($_GET['stu']){ ?>
        <div class="section-body" id="payForm">
            <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                <h4>New payment</h4>
                <div class="card-header-action">
                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                </div>
            </div>
            <div class="collapse show" id="mycard-collapse">
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-12" id="main">
                        <div class="card">
                            <div class="card-body">
                                <form id="process_payment" action="process_payment" method="POST">
                                    <input type="hidden" name="action" value="gen_ind_payment_apply">
                                    <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                    <div class="card-body pb-0 row">
                                        <?php
                                        $select_academic="SELECT * FROM tbl_acad_cycle WHERE status=1";
                                        $cselect_academic=$conn->prepare($select_academic);
                                        $cselect_academic->execute();
                                        $row_cselect_academic=$cselect_academic->fetch(PDO::FETCH_ASSOC);
                                        $acad_cycle_id=$row_cselect_academic['acad_cycle_id'];
                                        ?>
                                        <div class="form-group  col-12 col-sm-6 col-lg-6" hidden>
                                            <input type="text" class="form-control" name="reg_no" value="<?=$_GET['stu'] ?>" required>
                                            <input type="text" class="form-control" name="acad_cycle_id" value="<?=$acad_cycle_id; ?>" required>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Fee Category</label><br>
                                            <select class="form-control select2" style="width:100%" name="fee_id" required>
                                            <option selected disabled>Select Category</option> 
                                            <?php
                                           
                                            $select_user="SELECT * FROM tbl_applicants WHERE code='".$_GET['stu']."' ";
                                            $cselect_user=$conn->prepare($select_user);
                                            $cselect_user->execute();
                                            $row_cselect_user=$cselect_user->fetch(PDO::FETCH_ASSOC);
                                            
                                            $select_invoice="SELECT * FROM tbl_invoice WHERE reg_no='".$_GET['stu']."' AND acad_cycle_id='".$acad_cycle_id."' AND payment_status=0";
                                            $cselect_invoice=$conn->prepare($select_invoice);
                                            $cselect_invoice->execute();
                                            foreach($cselect_invoice as $row_cselect_invoice){
                                            
                                            
                                            $select_fee="SELECT * FROM tbl_fee_category WHERE id='".$row_cselect_invoice['fee_id']."' ";
                                            $cselect_fee=$conn->prepare($select_fee);
                                            $cselect_fee->execute($select_data);
                                            foreach($cselect_fee as $row_cselect_fee){
                                            ?>
                                            <option value="<?php echo $row_cselect_fee['id']?>"><?php echo $row_cselect_fee['name']?></option>
                                            <?php
                                            }
                                            }
                                            ?>
                                        </select>

                                            <span id="spinner3"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Amount</label><br>
                                            <input type="number" class="form-control" name="amount"  required>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Transaction Code</label><br>
                                            <input type="text" class="form-control" name="trans_code" required>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Slip Number</label><br>
                                            <input type="text" class="form-control" name="slip_no" required>
                                        </div>
                                        
                                        <div class="form-group  col-12 col-sm-6 col-lg-4">
                                            <label>Account No</label><br>
                                            <select class="form-control select2" style="width:100%" name="bank_id" required>
                                                <?php
                                                // Check if fac_id is null and set query accordingly
                                                if ($row_cselect_fee['fac_id'] === null) {
                                                    $sql = $conn->prepare("SELECT * FROM tbl_bank WHERE account_name = 'Applicant'");
                                                } else {
                                                    $sql = $conn->prepare("SELECT * FROM tbl_bank WHERE fac_id = :fac_id ORDER BY bank_name");
                                                    $sql->bindParam(':fac_id', $fac_id, PDO::PARAM_STR);
                                                    $fac_id = $row_cselect_fee['fac_id'];
                                                }
                                            
                                                // Execute the query
                                                $sql->execute();
                                            
                                                // Fetch and process data
                                                while ($bank = $sql->fetch(PDO::FETCH_ASSOC)) {
                                            ?>

                                                <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']." (".$bank['account_no'].") | ".$bank['currency']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <span id="spinner3"></span>
                                        </div>
                                        <div class="form-group  col-12 col-sm-6 col-lg-4">
                                            <label>Paymend Date</label><br>
                                            <input type="date" class="form-control" name="date" max="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn">
                                            <center>
                                                <button id="pay" type="submit" class="btn btn-primary"><span id="spinner0"></span>&nbsp;<span id="indicator0">Save payment</span></button>
                                            </center>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <br>
             <!--payments-->
        
        </div>
    </section>
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
        $("#input").keyup(function(e){
            var formData = {
                keyword:$(this).val(),
                action:'search_applicant'
            }
                $("#info").attr("hidden",true);
                $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    url: "/files/Student/student_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data){
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            data.forEach(function(value) {
                                var reg = value.reg_no || value.code;
                                var names=value.fname+" "+value.lname;
                                html += '<tr class="stu_app" data-id='+reg+'>';
                                html += '<th>' + i+ '</th>';
                                html += '<th>' + reg+ '</th>';
                                html += '<td>' + names+ '</td>';
                                html += '</tr>';
                                i++;
                            });
                            $('#contents').html(html);
                        } else{
                            $('#contents').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                        }
                    },error: function(){
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        pop_wrong("Something went wrong!");
                    }
            });
        });
        
        $(document).on('click', '.stu_app', function() {
    var student = $(this).data("id"); // Get student ID from data attribute
    var formData = {
        stu: student,
        action: 'load_application_info_payment'
    };

    // Update UI for processing state
    $("#input").val(student);
    $("#contents").html("");
    $("#payForm").attr('hidden', true);
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');

    // AJAX request to fetch student information
    $.ajax({
        url: "/files/Student/student_controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $('#spinner').html("<i class='fas fa-search'></i>");

            if (data.length > 0) {
                $("#info").removeAttr('hidden');
                $("#academics_appl").html("");

                // Populate table rows with data
                var i = 1;
                data.forEach(function(value) {
                    var payment = value.payment;
                    var invoice = value.invoice;

                    // Create a row dynamically
                    var row = `<tr>
                        <td>${i}</td>
                        <td>${value.fname} ${value.lname}</td>
                        <td>${payment}</td>
                        <td>${invoice}</td>
                        <td>
                            <a class='btn btn-sm btn-primary edit' href='?mis=apppayt&stu=${value.code}'>
                                <span>Take fee</span>
                            </a>
                        </td>
                    </tr>`;
                    $("#academics_appl").append(row);
                    i++;
                });
            } else {
                // Handle no data found case
                $('#academics_appl').html('<tr><td colspan="8" align="center">Oops, no data found</td></tr>');
            }
        },
        error: function() {
            $('#spinner').html("<i class='fas fa-search'></i>");
            pop_wrong("Something went wrong!");
        }
    });
});


    
    
        //Process payment
        $("#process_payment").submit(function(e){
                e.preventDefault();
            var formData = new FormData(this);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator0').html("Saving...");
            $('#pay').attr('disabled',true);
            $.ajax({
                url: "/files/Payment/payment_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save payment");
                    $('#pay').attr('disabled',false);
                    if(data.status==201){
                        swal({
                    title: "Are you sure?",
                    text: data.message,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willConfirm) => {
                    if (willConfirm) {
                        formData.append('action', 'confirm_payment_finance');
                        // If user confirms, make another AJAX request
                        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                        $.ajax({
                            url: "/files/Payment/payment_controller.php", // You can create a new endpoint for confirming the payment
                            type: "POST",
                            data: formData,
                            dataType: "JSON",
                            contentType: false,
                            processData: false,
                            success: function (confirmData) {
                                $('#spinner0').fadeOut('fast');
                                if (confirmData.status == 200) {
                                    pop_up_success(confirmData.message);
                                    $('#payment_table').load(location.href + " #payment_table");
                                } else {
                                    pop_info(confirmData.message);
                                }
                            },
                            error: function () {
                                $('#spinner0').fadeOut('fast');
                                pop_wrong("Something went wrong during confirmation!");
                            }
                        });
                    } else {
                        swal("Operation cancelled!");
                    }
                });
                        
                    }
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#payment_table').load(location.href + " #payment_table");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save payment");
                    $('#pay').attr('disabled',false);
                    pop_wrong("Something went wrong!");
                }
            });
        });

        $(document).on('click','.del',function () {
            $("#pyt_id").val($(this).data('id'));
            $("#cancelModal").modal('show');
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