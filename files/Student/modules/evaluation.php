<!-- Start app main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h3>Module Evaluation</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Module Evaluation</a></div>
                        </div>
                    </div>
                    <?php 
                        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
                        $sql->execute();
                        $data=$sql->fetch();
                        
                        $getAns = $conn->prepare("SELECT * FROM tbl_module_evaluation WHERE stu='".$identification."' AND module_id='".$_GET['mod']."'");
                        $getAns->execute();
                        if($getAns->rowCount()==0){
                        ?>
                    <div class="section-body">
                        <div class="row" style="display:flex; flex-direction:row;justify-content:center;">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card" id="sample-login">
                                    <div class="card-header" style="background-color:rgba(0,0,0,0.04); border-left:3px solid #563d7c;">
                                        <p>Dear <?php echo $first_name; ?>, <?php echo $data['full_name']; ?> values your contribution to maintain quality standards through your module’s evaluation. The SER will indicate how students are satisfied with the studied modules and helps the College management to take different measures to strengthen quality teaching and learning at <?php echo $data['full_name']; ?>.  Thank you for your contribution.</p>
                                    </div>
                                        <div class="card-header">
                                            <?php
                                                $sql=$conn->prepare("SELECT tm.splz_id,tm.module_credits,mm.module_id,m.module_code,m.module_name
                                                                            FROM tbl_markby_module mm
                                                                            INNER JOIN tbl_modules tm ON 
                                                                            mm.module_id=tm.module_id
                                                                            INNER JOIN modules m ON 
                                                                            tm.mod_id=m.module_id
                                                                            WHERE mm.module_id='".$_GET['mod']."' AND mm.reg_no='".$identification."' AND mm.enrolled=1");
                                                        $sql->execute();
                                                        $mods=$sql->fetch()
                                            ?>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo 'Module name: '.$mods['module_name']; ?></th>
                                                        <th><?php echo 'Module code: '.$mods['module_code']; ?></th>
                                                    </tr>  
                                                </thead>
                                            </table>
                                            
                                        </div>
                                        <form id="evaluation" action="evaluation" method="POST">
                                            <input type="hidden" name="splz_id" value="<?php echo $mods['splz_id']; ?>">
                                            <input type="hidden" name="stu" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="module_id" value="<?php echo $_GET['mod']; ?>">
                                            <input type="hidden" name="action" value="evaluation">
                                            <?php
                                                $sql2=$conn->prepare("SELECT * FROM tbl_evaluation_question WHERE status=1");
                                                $sql2->execute();
                                                $i=1;
                                                while($qn=$sql2->fetch()){
                                            ?>
                                            <div class="card-header">
                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <h6><?php echo $i++.". ".$qn['question']; ?></h6>
                                                    <input type="hidden" name='question[]' value="<?php echo $qn['question_id']; ?>">
                                                    <div class="row col-12 col-sm-12 col-lg-12" style="display:flex;flex-direction:row; justify-content:space-between;">
                                                    <?php
                                                        $sql3=$conn->prepare("SELECT * FROM tbl_evaluation_answer WHERE question_id='".$qn['question_id']."' AND status=1");
                                                        $sql3->execute();
                                                        if($sql3->rowCount()){
                                                            while($ans=$sql3->fetch()){
                                                    ?>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" value="<?php echo $ans['answer_id']; ?>" name="answer_<?php echo $qn['question_id']; ?>" required>
                                                            <label class="form-check-label" for="exampleRadios1"><?php echo $ans['answer']; ?></label>
                                                        </div>
                                                    <?php } 
                                                        }
                                                        else{
                                                    ?>
                                                        <div class="input-group">
                                                            <textarea style="border-radius:0px;width:100%; height:max-content;" name="answer_<?php echo $qn['question_id']; ?>"  maxlength="160" placeholder="write your answer here"></textarea>
                                                        </div>
                                                    <?php } ?>
                                                    </div>
                                                </div> 
                                            </div>
                                            <?php } ?>
                                            <div class="card-body col-12" style="display:flex; flex-direction:row; justify-content:center">
                                                <button type="submit" id="send_btn" class="btn btn-icon btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Submit</span>&nbsp;</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="section-body">
                            <div class="col-12 mb-4">
                                <div class="hero align-items-center bg-success text-white">
                                    <div class="hero-inner text-center">
                                        <h2>Thank You for Your Feedback</h2>
                                        <p class="lead">Thank you for taking the time to provide us with your valuable feedback. Your input is important to us and will help us to improve the quality of our courses. We appreciate your commitment to your education and your willingness to share your thoughts with us. Please don't hesitate to reach out if you have any additional comments or suggestions. Thank you again for your participation and for helping us to make a difference in the lives of our students.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </section>
                </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $("#evaluation").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('.indicator').html("Sending...");
        $("#send_btn").attr('disabled',true);
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType:false,
                processData:false,
                success: function(data){
                    $('.spinner').fadeOut('fast');
                    $('.indicator').html("Submit");
                    $("#send_btn").attr('disabled',false);
                    if(data.status==200){
                        window.location.reload();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#send_btn").attr('disabled',false);
                    $('.spinner').fadeOut('fast');
                    $('.indicator').html("Submit");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
        });

function pop_wrong(feedback) {
    iziToast.warning({
    title: 'Info:',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>