<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');

    function doc_header($pdf, $conn, $prg_type_full_name){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../..'.$data['logo'],105,50,80);
	    /********************Title****************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',13);
		$pdf->SetY(150);
		$pdf->Cell(280,20,'MODULE EVALUATION REPORT',0,0,'C');
		$pdf->Ln(10);
		$pdf->Cell(280,20,"Program Type: ".$prg_type_full_name,0,0,'C');
		$pdf->Ln(15);
    }

    function get_question($conn, $id){
        $getQuestion=$conn->prepare("SELECT question FROM tbl_evaluation_question WHERE question_id=?");
        $getQuestion->execute([$id]);
        $qn=$getQuestion->fetch();
        return $qn['question'];
    }
    
    $prg_type = $_REQUEST['prg_type'];

    //Queries
    $stms_prg = $conn->prepare("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id = ?");
    $stms_splz = $conn->prepare("SELECT splz_id, splz_full_name FROM tbl_specialization WHERE prg_type = ?");
    $stms_lev = $conn->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE prg_type = ?");
    $stms_mod = $conn->prepare("SELECT tm.module_id, m.module_code, m.module_name FROM tbl_modules tm INNER JOIN modules m ON tm.mod_id = m.module_id WHERE tm.splz_id = ? AND tm.level_id = ?");
    
    try {
        //Instanciation of inherited class
        $pdf = new FPDF();
        $pdf->SetAuthor('STUMIS');
        $pdf->AliasNbPages();
        $pdf->SetTitle("Module Evaluation Report");
        $pdf->AddPage('L');
        $stms_prg->execute([$prg_type]);
        $prg = $stms_prg->fetch();
        $prg_type_full_name = $prg['prg_type_full_name'];
        doc_header($pdf, $conn, $prg_type_full_name);

        $stms_splz->execute([$prg_type]);
        $stms_lev->execute([$prg_type]);
                        
        while($splz = $stms_splz->fetch()){
            while($lev = $stms_lev->fetch()){
                $stms_mod->execute([$splz['splz_id'], $lev['level_id']]);
                while($module = $stms_mod->fetch()){
                    $getModes=$conn->prepare("SELECT DISTINCT(tbl_module_evaluation.mode_id) as mode,
                                                    tbl_program_mode.prg_mode_full_name,
                                                    tbl_modules.module_id,
                                                    tbl_acad_cycle.*
                                                FROM tbl_module_evaluation 
                                                    INNER JOIN tbl_program_mode ON tbl_module_evaluation.mode_id=tbl_program_mode.prg_mode_id 
                                                    INNER JOIN tbl_modules ON tbl_module_evaluation.module_id=tbl_modules.module_id 
                                                    INNER JOIN tbl_acad_cycle ON tbl_module_evaluation.acad_cycle_id=tbl_acad_cycle.acad_cycle_id
                                                WHERE tbl_modules.splz_id=? AND tbl_module_evaluation.module_id=?");
                    $getModes->execute([$splz['splz_id'], $module['module_id']]);
                    while($modes=$getModes->fetch()){
                        $pdf->AddPage('L');
                        $pdf->SetY(30);
                        $pdf->SetFont('Times', '', 12);
                        
                        $stms_prg->execute([$prg_type]);
                        
                        $prg = $stms_prg->fetch();
                        $prg_type_full_name = $prg['prg_type_full_name'];
    
                        $splz_full_name = $splz['splz_full_name'];
                        $level_full_name = $lev['level_full_name'];
                        $module_code = $module['module_code'];
                        $module_name = $module['module_name'];
                        $acad_year = $modes['acad_year'];
                
                        $pdf->Cell(1,6,''); 
                        $pdf->Cell(90,6,'Program type: '.$prg_type_full_name,1,0); 
                        $pdf->Cell(180,6,'Specialization: '.$splz_full_name,1,0);
                        $pdf->Ln();
                        $pdf->Cell(1,6,''); 
                        $pdf->Cell(90,6,'Academic year: '.$acad_year,1,0); 
                        $pdf->Cell(180,6,'Level: '.$level_full_name,1,0); 
                        $pdf->Ln();
                        $pdf->Cell(1,6,''); 
                        $pdf->Cell(90,6,'Module code: '.$module_code,1,0);
                        $pdf->Cell(180,6,'Module name: '.$module_name,1,0); 
                        $pdf->Ln();
                        $pdf->Cell(1,6,'');
                        $pdf->Ln(10);
                        
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Times', 'B', 11);
                        $pdf->Cell(1, 6, '');
                        $pdf->Cell(270, 6, 'Learning mode: '.$modes['prg_mode_full_name'], 1);
                        $pdf->Ln();
                        
                        $getAllQuestions=$conn->prepare("SELECT * FROM tbl_evaluation_question WHERE status=1");
                        $getAllQuestions->execute();
                        
                        $getQuestionsWithAnswers=$conn->prepare("SELECT DISTINCT(tbl_evaluation_answer.question_id),
                                                                                        tbl_evaluation_question.*
                                                                                    FROM tbl_evaluation_question 
                                                                                        INNER JOIN tbl_evaluation_answer ON tbl_evaluation_question.question_id=tbl_evaluation_answer.question_id WHERE tbl_evaluation_question.status=1 AND tbl_evaluation_answer.status=1;");
                        $getQuestionsWithAnswers->execute();
                        
                        $qn_with_answers = array();
                        $all_questions = array();
                        while($qn=$getAllQuestions->fetch()){
                            array_push($all_questions, $qn['question_id']);
                        }
                        
                        $getAnswers=$conn->prepare("SELECT DISTINCT(answer) FROM tbl_evaluation_answer");
                        $getAnswers->execute();
                
                        $pdf->Cell(1, 6, '');
                        $pdf->Cell(10, 6, 'S/N', 1);
                        $pdf->Cell(140, 6, 'Questions', 1);
                        $pdf->Cell(20, 6, 'Responses', 1);
            
                        while($answer=$getAnswers->fetch()){
                            $an = $answer['answer'];
                            if($an=='Very Satisfied'){$a = $an; $answer = 'A'; }
                            if($an=='Satisfied'){$b = $an; $answer = 'B'; }
                            if($an=='Neutral'){$c = $an; $answer = 'C'; }
                            if($an=='Dissatisfied'){$d = $an; $answer = 'D'; }
                            if($an=='Very Dissatisfied'){$e = $an; $answer = 'E'; }
                            $pdf->Cell(20, 6, $answer, 1, 0, 'C');
                        }
                        $pdf->Ln();
                        $pdf->SetFont('Times', '', 10);
                        $i=1;
                        while($qna=$getQuestionsWithAnswers->fetch()){
                            array_push($qn_with_answers, $qna['question_id']);
                            $getTotalStudent=$conn->prepare("SELECT count(stu) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND acad_cycle_id='".$modes['acad_cycle_id']."' AND module_id='".$module['module_id']."' AND splz_id='".$splz['splz_id']."' AND mode_id='".$modes['mode']."'");
                            $getTotalStudent->execute();
                            $totalStudents=$getTotalStudent->fetch();
                            $total=$totalStudents['count'];
                            
                            $pdf->Cell(1, 6, '');
                            $pdf->Cell(10, 6, $i++, 1);
                            $pdf->Cell(140, 6, $qna['question'], 1);
                            $pdf->Cell(20, 6, $total, 1);
                            
                            
                            $getAnswers=$conn->prepare("SELECT answer_id FROM tbl_evaluation_answer WHERE question_id='".$qna['question_id']."'");
                            $getAnswers->execute();
                
                            while($answer=$getAnswers->fetch()){
                                $getAnswerCount=$conn->prepare("SELECT COUNT(answer_id) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND answer_id='".$answer['answer_id']."' AND acad_cycle_id='".$modes['acad_cycle_id']."' AND module_id='".$module['module_id']."' AND splz_id='".$splz['splz_id']."' AND mode_id='".$modes['mode']."'");
                                $getAnswerCount->execute();
                                $result=$getAnswerCount->fetch();
                                $count=$result['count'];
                                if($total>0){
                                    $pdf->Cell(20, 6, number_format($count*100/$total,1).'%', 1, 0, 'C');
                                }
                            }
                            $pdf->Ln();
                            
                        }
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(201,6,''); 
                        $pdf->Cell(10,6,'A',1,0); 
                        $pdf->Cell(60,6,$a,1,0);
                        $pdf->Ln();
                        $pdf->Cell(201,6,''); 
                        $pdf->Cell(10,6,'C',1,0); 
                        $pdf->Cell(60,6,$b,1,0);
                        $pdf->Ln();
                        $pdf->Cell(201,6,''); 
                        $pdf->Cell(10,6,'C',1,0); 
                        $pdf->Cell(60,6,$c,1,0);
                        $pdf->Ln();
                        $pdf->Cell(201,6,''); 
                        $pdf->Cell(10,6,'D',1,0); 
                        $pdf->Cell(60,6,$d,1,0);
                        $pdf->Ln();
                        $pdf->Cell(201,6,''); 
                        $pdf->Cell(10,6,'E',1,0); 
                        $pdf->Cell(60,6,$e,1,0);
                        $pdf->Ln();
                    }
                }
            }
        }
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }

$Today_date = date('Y-m-d');
$doc_name = "module_evaluation_report:".$Today_date ;
$pdf->Output($doc_name . '.pdf', 'I');
// $pdf->Output();
 
exit;
ob_end_flush();
?>