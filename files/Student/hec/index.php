<!-- Start app main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <!--<img src="/files/Student/hec/hec.png" width="50" height="50">-->
                        <h3 style="padding-top: 20px;">Student Satisfaction</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Questionaire</a></div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row" style="display:flex; flex-direction:row;justify-content:center;">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:rgba(0,0,0,0.04); border-left:3px solid #563d7c;">
                                        <p>Dear <?php echo $first_name; ?>, The questionnaire presented below has been prepared to measure your satisfaction with the activities and services of your Higher Learning Institution.
                                        Your feedback plays a pivotal role in helping us maintain and enhance the quality of education and services in Higher Learning Institutions. For this reason, it is important to fill in the questionnaire carefully.<br/><br/>
                                        Your answers will be evaluated anonymously, and your personal information will be kept confidential. Thank you for your contribution.</p><br><br>
                                    </div>
                                    <div class="card-header" style="background-color:rgba(0,0,0,0.04); border-left:3px solid red;">
                                    <?php
                                        $getMode = $conn->prepare("SELECT ug.prg_mode_id, 
                                                                            ug.splz_id, 
                                                                            ug.level_id, 
                                                                            ad.intake_id 
                                                                        FROM tbl_register_program_ug ug
                                                                            INNER JOIN tbl_admission ad ON ug.reg_no = ad.reg_no
                                                                        WHERE ug.reg_no='".$identification."' AND 
                                                                            ug.reg_active=1 
                                                                            ORDER BY ug.reg_prg_id DESC LIMIT 1
                                                                        ");
                                        $getMode->execute();
                                        $mode=$getMode->fetch();
                                        $mode_id=$mode['prg_mode_id'];
                                        $splz_id=$mode['splz_id'];
                                        $level_id=$mode['level_id'];
                                        $intake_id=$mode['intake_id'];

                                        $all=$conn->prepare("SELECT * FROM tbl_satisfactory_questions_categ WHERE status=1 ORDER BY cat_id ASC");
                                        $all->execute();
                                        $all = $all->rowCount();
                                    
                                        $done = $conn->prepare("SELECT DISTINCT(category_id) FROM tbl_student_satisfaction WHERE stu = ? AND splz_id = ? AND intake_id = ? AND level_id = ?");
                                        $done->execute([$identification, $splz_id, $intake_id, $level_id]);
                                        $done = $done->rowCount();
                                        
                                        $rem = $all-$done;
                                        
                                        if($rem>0){
                                    ?>
                                    <p style="color: red; font-size: 16px;">You are remaining with <?=$rem ?> sections.</p>
                                    <?php }else{ ?>
                                    <p style="color: green; font-size: 16px;">Great job, all sections have been covered!</p>
                                    <?php } ?>
                                    </div>
                                </div>
                                <?php
                                    $sql=$conn->prepare("SELECT * FROM tbl_satisfactory_questions_categ WHERE status=1 ORDER BY cat_id ASC");
                                    $sql->execute();
                                    while($categories = $sql->fetch()){
                                        $hasAnswered = $conn->prepare("SELECT answer_id FROM tbl_student_satisfaction WHERE stu = ? AND category_id = ? AND splz_id = ? AND intake_id = ? AND level_id = ? LIMIT 1");
                                        $hasAnswered->execute([$identification, $categories['cat_id'], $splz_id, $intake_id, $level_id]);
                                        $answered = $hasAnswered->rowCount();
                                ?>
                                <div class="card">
                                    <div class="card-body" style="background-color:rgba(0,0,0,0.04); border-left:3px solid <?php echo $answered==1?'#0b871d':'#6D071A' ?>;">
                                        <div class="card-header">
                                            <h6><?=$categories['category']; ?></h6>
                                        </div>
                                        <form class="rating" action="" method="POST" data-id="<?=$categories['cat_id']; ?>">
                                            <input type="hidden" name="stu" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="category" value="<?=$categories['cat_id']; ?>">
                                            <input type="hidden" name="action" value="rating">
                                            <?php
                                                $sql2=$conn->prepare("SELECT * FROM tbl_satisfactory_questions WHERE cat_id = ? AND status=1");
                                                $sql2->execute([$categories['cat_id']]);
                                                $i=1;
                                                while($question = $sql2->fetch()){
                                                    $ratings = array("answer_id" => '');
                                                    $hasRated = $conn->prepare("SELECT answer_id FROM tbl_student_satisfaction WHERE stu = ? AND category_id = ? AND question_id = ? AND splz_id = ? AND intake_id = ? AND level_id = ? LIMIT 1");
                                                    $hasRated->execute([$identification, $categories['cat_id'], $question['qn_id'], $splz_id, $intake_id, $level_id]);
                                                    $rated = $hasRated->rowCount();
                                                    $ratings = $hasRated->fetch();
                                                    $rate = $ratings['answer_id'];
                                            ?>
                                            <div class="card-header">
                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <h6><?php echo $i++.". ".$question['question']; ?></h6>
                                                    <input type="hidden" name='questions[]' value="<?php echo $question['qn_id']; ?>">
                                                    <div class="row col-12 col-sm-12 col-lg-12" style="display:flex;flex-direction:row; justify-content:space-between;">
                                                        <?php
                                                            $sql3=$conn->prepare("SELECT * FROM tbl_satisfactory_answers WHERE status=1");
                                                            $sql3->execute();
                                                            while($answer = $sql3->fetch()){
                                                        ?>
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" value="<?php echo $answer['answer_id']; ?>" name="answer_<?php echo $question['qn_id']; ?>" <?php echo $rate==$answer['answer_id']?'checked disabled':'' ?> required>
                                                            <label class="form-check-label" for="exampleRadios1"><?php echo $answer['answer']; ?></label>
                                                        </div>
                                                        <?php } ?> 
                                                    </div>
                                                </div> 
                                            </div>
                                            <?php } ?>
                                            <?php if($rated==0){ ?>
                                            <div class="card-body col-12" style="display:flex; flex-direction:row; flex-direction:row-reverse">
                                                <button type="submit" class="btn btn-icon btn-primary send_btn"><span id="spinner_<?=$categories['cat_id']; ?>"></span>&nbsp;<span id="indicator_<?=$categories['cat_id']; ?>">Submit</span>&nbsp;</button>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <?php } ?>
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
    $(".rating").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        const identifier = $(this).data('id');
        $('#spinner_'+identifier).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator_'+identifier).html("Sending...");
        $(".send_btn").attr('disabled',true);
            $.ajax({
                url: "/files/Student/hec/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType:false,
                processData:false,
                success: function(data){
                    $('#spinner_'+identifier).fadeOut('fast');
                    $('#indicator_'+identifier).html("Submit");
                    $(".send_btn").attr('disabled',false);
                    if(data.status==200){
                        pop_up_success("Data saved");
                        setTimeout(()=>{
                            window.location.reload();
                        }, 2000);
                        
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $(".send_btn").attr('disabled',false);
                    $('#spinner_'+identifier).fadeOut('fast');
                    $('#indicator_'+identifier).html("Submit");
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