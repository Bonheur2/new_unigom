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
                                <h3>3. Language Proficiency</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Languages</a></div>
                                </div>
                            </div>
                            <?php LanguageProficiency($conn, $code);
                            
                            ?>
                            <div class="section-body">
                                
                                
                                <div class="row" id="profile">
                                    <?php
                                        $stmt=$conn->prepare("SELECT * FROM tbl_language_pro WHERE stu='".$code."'");
                                        $stmt->execute();
                                    ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Language Proficiency</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse show" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form action="update_languages" method="POST" id="update_languages" class="row">
                                                        <input type="hidden" name="action" value="update_languages">
                                                        <input type="hidden" name="stu" value="<?php echo $code; ?>">
                                                        <?php while($applan = $stmt->fetch()){ ?>
                                                        <div class="form-group col-md-4">
                                                            <label><?php echo $applan['language']; ?></label>
                                                            <input type="hidden" name="lan[]" value="<?php echo $applan['id']; ?>">
                                                            <div class="input-group">
                                                                <select name="state_<?php echo $applan['id']; ?>" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                                                    <option value="" disabled selected hidden>Choose One...</option>
                                                                    <option value="1" <?php echo $applan['state']==1?'selected':''; ?>>I can only read</option>
                                                                    <option value="2" <?php echo $applan['state']==2?'selected':''; ?>>I can read and write</option>
                                                                    <option value="3" <?php echo $applan['state']==3?'selected':''; ?>>I can read, write and speak fluently</option>
                                                                    <option value="4" <?php echo $applan['state']==4?'selected':''; ?>>I can't read, write and speak </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="form-group col-md-4">
                                                            <?php
                                                            $stmt2=$conn->prepare("SELECT * FROM tbl_language_pro WHERE stu='".$code."' ORDER BY id DESC LIMIT 1");
                                                            $stmt2->execute();
                                                            $otherLanguage=$stmt2->fetch();
                                                            
                                                            ?>
                                                            <label>Other [You can read,write and speak]</label>
                                                            <div class="input-group">
                                                               <input type="text" class="form-control" name="other_lang" id="other_lang" value="">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-whitesmoke br">
                                                            <button type="submit" class="btn btn-primary"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ReportProblem($conn, $code, $thing); ?>
                            </div>
                        </section>
                        
                        
                    </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        //Update languages
        $("#update_languages").submit(function(e){
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
                    if(data.status==401){
                        pop_wrong(data.message);
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