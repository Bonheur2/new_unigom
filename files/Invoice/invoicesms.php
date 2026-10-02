<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>SMS Sender</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">SMS</a></div>
            </div><br>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($_GET['stu']){ ?>
                            <?php
                                $sql=$conn->prepare("SELECT
                                                        r.reg_no,
                                                        ad.fname,
                                                        ad.phone,
                                                        COALESCE(p.amount, 0) AS payments,
                                                        COALESCE(inv.balance, 0) AS invoices,
                                                        COALESCE(inv.balance, 0) - COALESCE(p.amount, 0) AS balance
                                                    FROM
                                                        tbl_register_program_ug r
                                                    LEFT JOIN 
                                                        tbl_admission ad ON r.reg_no = ad.reg_no
                                                    LEFT JOIN (
                                                        SELECT reg_no, COALESCE(SUM(amount), 0) AS amount
                                                        FROM payment
                                                        WHERE status = 1
                                                        GROUP BY reg_no
                                                    ) p ON r.reg_no = p.reg_no
                                                    LEFT JOIN (
                                                        SELECT reg_no, COALESCE(SUM(balance), 0) AS balance
                                                        FROM tbl_invoice
                                                        GROUP BY reg_no
                                                    ) inv ON r.reg_no = inv.reg_no
                                                    WHERE
                                                        r.reg_no = ?
                                                    GROUP BY
                                                        r.reg_no;
                                                        ");
                                $sql->execute([$_GET['stu']]);
                                $i=1;
                                $pay=$sql->fetch();
                             ?>
                            <div class="section-body">
                                <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                                    <h4>Compose SMS Here</h4>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="sendSMS" action="sendSMS" method="POST">
                                                    <input type="hidden" name="action" value="sendinsms">
                                                    <input type="hidden" name="phone" value="<?php echo $pay['phone']; ?>">
                                                    <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <label>Student ID</label><br>
                                                            <input type="text" class="form-control" name="reg_no" value="<?=$_GET['stu'] ?>" readonly required>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <label>Balance</label><br>
                                                            <input type="text" class="form-control" name="balance" value="<?=$pay['balance'] ?>" readonly required>
                                                        </div>
                                                        <div class="form-group col-12">
                                                            <label>Message</label><br>
                                                            <textarea class="form-control" name="message" maxlength="145" minlength="30" required oninput="setDefaultMessage(this)">Dear <?=$pay['fname']; ?>, </textarea>
                                                        </div>
                                                        <div class="form-group col-12">
                                                            <center>
                                                                <?php if($pay['balance']>0){ ?>
                                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<i class="fas fa-send"></i>&nbsp;<span id="indicator">Send</span></button>
                                                                <?php }else{ ?>
                                                                Not eligible: This student needs a refund! (Payments > Invoices)
                                                                <?php } ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function setDefaultMessage(textarea) {
        var fname = <?php echo json_encode($pay['fname']); ?>;
        var defaultMessage = "Dear " + fname+', ';
    
        if (textarea.value.trim() === "") {
            textarea.value = defaultMessage;
        }
    
        textarea.addEventListener("input", function () {
            if (!textarea.value.startsWith(defaultMessage)) {
                textarea.value = defaultMessage + textarea.value.substring(defaultMessage.length);
            }
        });
    }
</script>
                
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $("#sendSMS").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Sending...");
            $.ajax({
                url: "/files/Invoice/invoice_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Send");
                    if(data==200){
                        pop_up_success("Message sent Succesfully!");
                    }
                    else{
                        pop_info("Failed to send message!");
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Send");
                    pop_wrong("Something went wrong!");
                    
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