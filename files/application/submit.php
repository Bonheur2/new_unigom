<?php
    $checkPermission = $conn->prepare("
    SELECT * 
    FROM tbl_admittedPRG 
    WHERE Stu_code = :code
");

$checkPermission->bindParam(':code', $code, PDO::PARAM_STR);
$checkPermission->execute();

$applicantData = $checkPermission->fetch(PDO::FETCH_ASSOC);

$permission = $checkPermission->rowCount();

$cump_id = '';
$prg_type = '';
if ($applicantData) {
    $cump_id = $applicantData['cump_id'];
    $prg_type = $applicantData['prg_type'];
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Application Submittion</h1>
        </div>
        <?php 
       
        ApplicationSummary($conn, $code, $prg_type);
        
        ?>
        <div class="section-body">
            <h2 class="section-title"><?php  echo date("F d Y");
            ?></h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="card card-large-icons">
                            <div class="card-body">
                                <?php
                               
                                $select_before="SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted='1'";
                                $cselect_before=$conn->prepare($select_before);
                                $cselect_before->execute();
                                $row_cselect_before=$cselect_before->fetch();
                                if($row_cselect_before){
                                    echo "
                                    <div class='alert alert-primary'>
                                        <h4><i class='fas fa-check-circle'></i> Application Successfully Submitted!</h4>
                                        <hr>
                                        <p>Dear <strong>" . $family_name . " " . $first_name . "</strong>,</p>
                                        <p>Your application has been successfully submitted. Your application code is: <strong>" . $code . "</strong>. Please keep this code for future reference.</p>
                                        
                                        <p>Your application is currently under review by our admissions committee. 
                                        Rest assured that we are committed to providing a thorough and fair evaluation.</p>
                                        
                                        <p>Please note that the review process may take some time. We appreciate your patience during this period.</p>
                                        
                                        <p>If you have any questions, please feel free to reach out to our admissions office at 
                                        <a href='mailto:helpdesk@njala.edu.sl'>helpdesk@njala.edu.sl</a>.</p>
                                        
                                        <p>Thank you for choosing Njala University. We look forward to the possibility of welcoming you to our community.</p>
                                    </div>
                                    ";
                                } else{
                                ?>
                                <form class="submit" action="submit" method="POST">
                                    <p>
            			               By clicking '<b>Submit</b>', you acknowledge that you are in substantial agreement with all Njala University policies. Additionally, you agree to adhere to the rules and regulations outlined by the institution, including but not limited to academic integrity, campus conduct, and compliance with safety protocols.
            			            </p>
            			            <p>
            			                Thank you for your interest in joining our team. We look forward to reviewing your application!
            			            </p>
            			           
                                    <input type="hidden" name="code" value="<?=$code; ?>">
                                    <input type="hidden" name="action" value="submit">
                                    <br><br>
                                    <div class="form-group col-12">
                                        <div class="input-group">
                                            <?php
                                            $Select_first="SELECT * FROM tbl_admittedPRG where sts='1' AND Stu_code='".$code."'";
                                            $cSelect_first=$conn->prepare($Select_first);
                                            $cSelect_first->execute();
                                            $row_cSelect_first=$cSelect_first->fetch(PDO::FETCH_ASSOC);
                                            
                                            // Check if documents are uploaded
                                            $select_docs = "SELECT `appl_doc_id`, `tracking_id`, `upload_doc` FROM `tbl_application_doc` WHERE tracking_id='".$code."'";
                                            $cselect_docs = $conn->prepare($select_docs);
                                            $cselect_docs->execute();
                                            $row_docs = $cselect_docs->fetch(PDO::FETCH_ASSOC);
                                            
                                            $oc_overall_percent = isset($GLOBALS['app_overall_percent']) ? $GLOBALS['app_overall_percent'] : 0;

                                            if($oc_overall_percent < 100){
                                                ?>
                                                <div class="alert alert-primary" style="width: 100%">
                                                    <strong>Not Ready!</strong> Your application is only <strong><?php echo $oc_overall_percent; ?>%</strong> complete. Please fill all sections before submitting.
                                                </div>
                                                <?php
                                            } elseif($row_cSelect_first && $row_docs){
                                                ?>
                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Submit</span></button>
                                                <?php
                                            } elseif($row_cSelect_first && !$row_docs){
                                                ?>
                                                <div class="alert alert-warning">
                                                    <strong>Warning!</strong> You must upload all required documents before submitting your application.
                                                </div>
                                                <button type="button" class="btn btn-secondary" disabled>Submit (Documents Required)</button>
                                                <?php
                                            } else {
                                            ?>
                                            
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ReportProblem($conn, $code, $thing); ?>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function(){
        $(".submit").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Submitting");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    pop_up_success(formData)
                    setTimeout(function() {
                        window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";
                    }, 3000);

                },error: function(){
                    pop_info("Something went wrong!");
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                }
            });
        });
    })
</script>