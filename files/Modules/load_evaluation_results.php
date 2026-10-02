<?php include '../../meet/con.php';?>
<?php
    $getModuleData=$db->prepare("SELECT m.module_code, m.module_name FROM tbl_modules tm INNER JOIN modules m ON tm.mod_id = m.module_id WHERE tm.module_id='".$_REQUEST['mod_id']."'");
    $getModuleData->execute();
    $moduleData=$getModuleData->fetch();

    $getModes=$db->prepare("SELECT DISTINCT(ev.mode_id) as mode,
                                    pm.prg_mode_full_name,
                                    tm.module_id
                                FROM tbl_module_evaluation ev
                                    INNER JOIN tbl_program_mode pm ON ev.mode_id=pm.prg_mode_id 
                                    INNER JOIN tbl_modules tm ON ev.module_id=tm.module_id 
                                WHERE tm.splz_id='".$_REQUEST['splz_id']."' AND ev.module_id='".$_REQUEST['mod_id']."'");
    $getModes->execute();
    function get_question($db, $id){
        $getQuestion=$db->prepare("SELECT question FROM tbl_evaluation_question WHERE question_id=?");
        $getQuestion->execute([$id]);
        $qn=$getQuestion->fetch();
        return $qn['question'];
    }
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <hr>
        <div class="card">
            <div class="card-header">
                <h4 class="text-center"><b>Evaluation Results</b></h4>
            </div>
            <div class="card-header" style="display:flex; flex-direction:row; justify-content:space-between; margin-top: 15px; padding-top: 15px;">
                <h4><b><?php echo 'Module code: '.$moduleData['module_code']; ?></b></h4>
                <h4><b><?php echo 'Module name: '.$moduleData['module_name']; ?></b></h4>
            </div>
        </div>
    </div>
    <?php
        while($modes=$getModes->fetch()){
    ?>
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card">
            <div class="card-header" style="background-color:cyan; padding: 10px;">
                <h4><b><?php echo 'Learning mode: '.$modes['prg_mode_full_name']; ?></b></h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover" border="1">
                    <?php
                    $getAllQuestions=$db->prepare("SELECT * FROM tbl_evaluation_question WHERE status=1");
                    $getAllQuestions->execute();
                    $getQuestionsWithAnswers=$db->prepare("SELECT DISTINCT(tbl_evaluation_answer.question_id),
                                                                                    tbl_evaluation_question.*
                                                                                FROM tbl_evaluation_question 
                                                                                    INNER JOIN tbl_evaluation_answer ON tbl_evaluation_question.question_id=tbl_evaluation_answer.question_id WHERE tbl_evaluation_question.status=1 AND tbl_evaluation_answer.status=1;");
                    $getQuestionsWithAnswers->execute();
                    
                    $qn_with_answers = array();
                    $all_questions = array();
                    while($qn=$getAllQuestions->fetch()){
                        array_push($all_questions, $qn['question_id']);
                    }
                    
                    $i=1;
                    while($qna=$getQuestionsWithAnswers->fetch()){
                        array_push($qn_with_answers, $qna['question_id']);
                        $getTotalStudent=$db->prepare("SELECT count(stu) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND acad_cycle_id='".$_REQUEST['acad_cycle_id']."' AND module_id='".$_REQUEST['mod_id']."' AND splz_id='".$_REQUEST['splz_id']."' AND mode_id='".$modes['mode']."'");
                        $getTotalStudent->execute();
                        $totalStudents=$getTotalStudent->fetch();
                        $total=$totalStudents['count'];
                    ?>
                    <tr><th colspan="5"><?php echo $i++.". ".$qna['question']." | ".$total." responses"; ?></th></tr>
                    <tr>
                        <?php
                            $getAnswers=$db->prepare("SELECT answer_id,answer FROM tbl_evaluation_answer WHERE question_id='".$qna['question_id']."'");
                            $getAnswers->execute();

                            while($answer=$getAnswers->fetch()){
                                $getAnswerCount=$db->prepare("SELECT COUNT(answer_id) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND answer_id='".$answer['answer_id']."' AND acad_cycle_id='".$_REQUEST['acad_cycle_id']."' AND module_id='".$_REQUEST['mod_id']."' AND splz_id='".$_REQUEST['splz_id']."' AND mode_id='".$modes['mode']."'");
                                $getAnswerCount->execute();
                                $result=$getAnswerCount->fetch();
                                $count=$result['count'];
                        ?>
                        <th style="text-align:center;
                        <?php 
                            if($answer['answer']=='Very Satisfied'){echo 'background-color:rgba(0,255,0,0.5)';}
                            if($answer['answer']=='Satisfied'){echo 'background-color:rgba(0,255,0,0.2)';}
                            if($answer['answer']=='Dissatisfied'){echo 'background-color:rgba(255,0,0,0.2)';}
                            if($answer['answer']=='Very Dissatisfied'){echo 'background-color:rgba(255,0,0,0.5)';}
                        ?>
                        ">
                            <label><?php echo $answer['answer']; ?></label><br>
                            <label><?php echo number_format($count*100/$total,1).'%'; ?></label>
                        </th>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                    <?php 
                        $optional_questions = array_values(array_diff($all_questions, $qn_with_answers)); 
                        for($iu=0; $iu<count($optional_questions); $iu++){
                            $question = get_question($db, $optional_questions[$iu]);
                            $getAnswers = $db->prepare("SELECT answer FROM tbl_module_evaluation WHERE question_id='".$optional_questions[$iu]."' AND acad_cycle_id='".$_REQUEST['acad_cycle_id']."' AND module_id='".$_REQUEST['mod_id']."' AND splz_id='".$_REQUEST['splz_id']."' AND mode_id='".$modes['mode']."' AND answer !=''");
                            $getAnswers->execute();
                            $total = $getAnswers->rowCount();
                    ?>
                    <tr><th colspan="5"><?php echo $i++.". ".$question." | ".$total." responses"; ?></th></tr>
                    <tr>
                        <td colspan="5">
                            <ul>
                                <?php while($ansu = $getAnswers->fetch()){ ?>
                                <li><label><?php echo $ansu['answer']; ?></label><br></li>
                                <?php }?>
                            </ul>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" style="display:flex;flex-direction:row; justify-content:flex-end;">
        <button type="button" class="btn btn-primary" onclick="OpenPopupCenter('/files/Modules/export_ev_report_pdf?prg_type=<?php echo $_POST['prg_type']; ?>&dept_id=<?php echo $_POST['dept_id']; ?>&splz_id=<?php echo $_POST['splz_id']; ?>&level_id=<?php echo $_POST['level_id']; ?>&mod_id=<?php echo $_POST['mod_id']; ?>&acad_cycle_id=<?php echo $_POST['acad_cycle_id']; ?>', 'Evaluation Report', 800, 600);"><i class="fa fa-file"></i>&nbsp;Export PDF</span></button> &nbsp;
        <button type="button" class="btn btn-primary" onclick="OpenPopupCenter('/files/Modules/export_all_ev_report_pdf?prg_type=<?php echo $_POST['prg_type']; ?>', 'Evaluation Report', 800, 600);"><i class="fa fa-file"></i>&nbsp;Export All</span></button> &nbsp;
    </div>
</div>