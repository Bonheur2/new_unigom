<?php
    include ('../../meet/con.php');
    $keyword = $_POST['keyword'];
    
    //Get academic data
    $sql = $conn->prepare("SELECT 
                                ad.intake_id,
                                ad.reg_no,
                                ad.fname, 
                                ad.lname,
                                s.splz_full_name,
                                l.level_full_name,
                                sp.spon_cat_id
                            FROM tbl_register_program_ug r
                                INNER JOIN tbl_admission ad ON r.reg_no = ad.reg_no
                                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                INNER JOIN tbl_level l ON r.level_id = l.level_id
                                LEFT JOIN tbl_sponsor sp ON r.spon_id = sp.spon_id
                            WHERE ad.card_no = '".$keyword."' ORDER BY r.reg_prg_id DESC LIMIT 1");
    $sql->execute(); 
    $data=$sql->fetch();
    $keyword = $data['reg_no'];
    $intake = $data['intake_id'];
    $spon_cat = $data['spon_cat_id'];

    //Get student Photo
    $sql2 = $conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE tracking_id = '".$keyword."' AND upload_doc like '%student_docs/photo%'");
    $sql2->execute(); 
    if($sql2->rowCount()>0){
        $photo = $sql2->fetch();
        $stuImg = $photo['upload_doc'];
    }else{
        $stuImg = "student_docs/no-image.jpg";
    }
?>
<?php if($sql->rowCount()>0){ ?>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <div class="card card-body">
            <img src="../..<?php echo $stuImg; ?>" style=" max-width: 100%; height: auto;" alt="404 - Not Found">
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 col-12">
        <div class="card" style="border-radius:5px; border: 2px solid green;">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="article-user-details">
                            <div class="text-job"><b>Student ID</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $keyword; ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="article-user-details">
                            <div class="text-job"><b>Names</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['fname']." ".$data['lname']; ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="article-user-details">
                            <div class="text-job"><b>Specialization</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['splz_full_name']; ?></b></a></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="article-user-details">
                            <div class="text-job"><b>Level</b></div>
                            <div class="user-detail-name"><a href="#"><b><?php echo $data['level_full_name']; ?></b></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    // $sql2 = $conn->prepare("SELECT * FROM tbl_intake WHERE intake_id = '".$intake."' AND status = 1");
    // $sql2->execute();
    
    // $getInvoiceAndPayment = $conn->prepare("SELECT 
    //                                                 COALESCE(SUM(inv.balance), 0) as invoice, 
    //                                                 COALESCE(SUM(pay.amount), 0) as payments 
    //                                             FROM tbl_register_program_ug r
    //                                                 LEFT JOIN tbl_invoice inv ON r.reg_no = inv.reg_no AND inv.type = 1
    //                                                 LEFT JOIN payment pay ON r.reg_no = pay.reg_no AND pay.status = 1
    //                                             WHERE r.reg_no = '".$keyword."'");
    // $getInvoiceAndPayment->execute();
    // $financeData = $getInvoiceAndPayment->fetch();
    
    // if($financeData['invoice'] > $financeData['payments']){
    //     $checkNegotiation = $conn->prepare("SELECT * FROM tbl_invoice_negotiation WHERE reg_no = '".$keyword."' AND start_date >= ? AND end_date <= ?");
    //     $checkNegotiation->execute();
    //     if($checkNegotiation->rowCount() > 0){
    //         $negotiated = true;
    //     }else{
    //         $negotiated = false;
    //     }
    // } 
    
    // if($data['reg_active'] != 1 || $sql2->rowCount() == 0){
    //     $allowed = false;
    //     $message = "Entry denied!<br> Contact Registrar.";
    // } else if(($financeData['invoice'] > $financeData['payments']) && ($negotiated == false || $spon_cat != 1)){
    //     $allowed = false;
    //     $message = "Entry denied!<br> Contact Finance.";
    // }else{
    //     $allowed = true;
    //     $message = "Entry Allowed!";
    // }
?>
<?php //if($allowed){ ?>
    <div class="col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="card" style="border-radius:5px; border: 2px solid green;">
            <div class="card-body">
                <img src="../../files/Card/img/check.jpg" style=" max-width: 100%; height: auto;">
            </div>
            <div class="card-footer bg-whitesmoke text-center p-10 pb-0 pt-0">
                <hr>
                <h5 class="text-black">Welcome!</h5>
            </div>
        </div>
    </div>
<?php //} else{  ?>
    <!--<div class="col-lg-3 col-md-3 col-sm-12 col-12">-->
    <!--    <div class="card" style="border-radius:5px; border: 2px solid red;">-->
    <!--        <div class="card-body">-->
    <!--            <img src="../../files/Card/img/cross.jpg" style=" max-width: 100%; height: auto;">-->
    <!--        </div>-->
    <!--        <div class="card-footer bg-whitesmoke text-center p-10 pb-0 pt-0">-->
    <!--            <h5 class="text-danger"><?=$message; ?></h5>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
<?php //} ?>

<?php } else { ?>
    <div class="col-12">
        <div class="card" style="border-radius:5px; border: 2px solid red; width: 200px; height: 200px; margin: auto;">
            <div class="card-body text-center">
                <img src="../../files/Card/img/cross.jpg" style="max-width: 100%; height: auto;">
            </div>
            <div class="card-footer bg-whitesmoke text-center p-10 pb-0 pt-0">
                <h5 class="text-danger">Not Found!</h5>
            </div>
        </div>
    </div>
<?php } ?>