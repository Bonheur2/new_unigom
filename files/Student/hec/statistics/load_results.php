<?php include '../../../../meet/con.php';?>
<?php
    function get_sections($db){
        $getSections=$db->prepare("SELECT * FROM tbl_satisfactory_questions_categ WHERE status = 1");
        $getSections->execute();
        return $getSections;
    }
    
    function get_questions($db, $cat_id){
        $getQuestions=$db->prepare("SELECT * FROM tbl_satisfactory_questions WHERE cat_id = ? AND status = 1");
        $getQuestions->execute([$cat_id]);
        return $getQuestions;
    }

    function get_answers($db){
        $getAnswers=$db->prepare("SELECT * FROM tbl_satisfactory_answers WHERE status=1");
        $getAnswers->execute();
        return $getAnswers;
    }
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <hr>
        <div class="card">
            <div class="card-header">
                <h4 class="text-center"><b>Results</b></h4>
            </div>
        </div>
    </div>
    <?php
        $type = $_REQUEST['type'];
        if($type == 1){
            $acad = $_REQUEST['acad_cycle_id'];
            $sections = get_sections($db);
            while($section = $sections->fetch()){
    ?>
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card">
            <div class="card-header" style="background-color:cyan; padding: 10px;">
                <h4><b><?php echo $section['category']; ?></b></h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover" border="1">
                    <?php
                        $questions = get_questions($db, $section['cat_id']);
                        $i=1;
                        while($question = $questions->fetch()){
                            $getTotalAnswers = $db->prepare("SELECT count(stu) as count FROM tbl_student_satisfaction WHERE question_id='".$question['qn_id']."' AND acad_cycle_id='".$acad."'");
                            $getTotalAnswers->execute();
                            $totalAnswers = $getTotalAnswers->fetch();
                            $total = $totalAnswers['count'];
                    ?>
                    <tr><th colspan="5"><?php echo $i++.". ".$question['question']." | ".$total." responses"; ?></th></tr>
                    <tr>
                        <?php
                            $answers = get_answers($db);

                            while($answer = $answers->fetch()){
                                $getAnswerCount = $db->prepare("SELECT COUNT(answer_id) as count FROM tbl_student_satisfaction WHERE question_id='".$question['qn_id']."' AND answer_id='".$answer['answer_id']."' AND acad_cycle_id='".$acad."'");
                                $getAnswerCount->execute();
                                $results = $getAnswerCount->fetch();
                                $count = $results['count'];
                        ?>
                        <th style="text-align:center;
                        <?php 
                            if($answer['answer_id']==1){echo 'background-color:rgba(0,255,0,0.5)';}
                            if($answer['answer_id']==2){echo 'background-color:rgba(0,255,0,0.2)';}
                            if($answer['answer_id']==4){echo 'background-color:rgba(255,0,0,0.2)';}
                            if($answer['answer_id']==5){echo 'background-color:rgba(255,0,0,0.5)';}
                        ?>
                        ">
                            <label><?php echo $answer['answer']; ?></label><br>
                            <label><?php echo number_format($count*100/($total>0?$total:1),1).'%'; ?></label>
                        </th>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row; justify-content:flex-end;">
        <button type="button" class="btn btn-primary" onclick="OpenPopupCenter('/files/Student/hec/statistics/download?type=1&acad=<?php echo $acad; ?>', 'Student Satisfaction Report', 800, 600);"><i class="fa fa-file"></i>&nbsp;Export PDF</span></button> &nbsp;
    </div>
    <?php } ?>
</div>