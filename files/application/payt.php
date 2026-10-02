<?php
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>My Payment</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Payment Info</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row" id="profile">
                        <?php
                            $stmt=$conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."'");
                            $stmt->execute();
                            $applicantData=$stmt->fetch();
                        ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Payment Information</h4>
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
                                    <input type="hidden" name="action" value="gen_ind_payment_apply_stu">
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
                                            <input type="text" class="form-control" name="reg_no" value="<?=$code ?>" required>
                                            <input type="text" class="form-control" name="acad_cycle_id" value="<?=$acad_cycle_id; ?>" required>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                            <label>Fee Category</label><br>
                                            <select class="form-control select2" style="width:100%" name="fee_id" required>
                                            <option selected disabled>Select Category</option> 
                                            <?php
                                           
                                            $select_user="SELECT * FROM tbl_applicants WHERE code='".$code."' ";
                                            $cselect_user=$conn->prepare($select_user);
                                            $cselect_user->execute();
                                            $row_cselect_user=$cselect_user->fetch(PDO::FETCH_ASSOC);
                                            
                                            $select_invoice="SELECT * FROM tbl_invoice WHERE reg_no='".$code."' AND acad_cycle_id='".$acad_cycle_id."' AND payment_status=0 ";
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
                                            <input type="number" class="form-control" name="amount" min="0.00000000001" step=".01" required>
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
        //Update Personal
        $("#update_payment").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_p').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    if(data.status==200){
                        window.location.reload();
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
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
                        pop_up_success(data.message);
                    }
                    if(data.status==200){
                        pop_up_success(data.message);
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