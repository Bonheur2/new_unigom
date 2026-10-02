<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');

    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../..'.$data['logo'],20,10,40);
		$pdf->SetFont('Arial','B',15);
		/**********************Info-main**************************/
		$pdf->SetX(70);
		$pdf->SetFont('Arial','',13);
		$pdf->SetTextColor(55,24,60);
		$pdf->Cell(200,5,$data['full_name'],0,0,'R');
		$pdf->Ln(6);
		$pdf->SetX(70);
		$pdf->Cell(120,5,'',0,0,'R');
		$pdf->Ln(6);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(200,5,$data['po_box'],0,0,'R');
		$pdf->Ln(5);
		/*********************Contact***************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(200,5,$data['phone'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(70);
		/*********************Web&email***************************/
		$pdf->SetTextColor(53,75,136);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(200,5,$data['email'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(70);
		$pdf->Cell(200,5,$data['website'],0,0,'R');
		$pdf->Ln(5);
	    /********************Title****************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','BU',13);
		$pdf->Cell(280,20,'MODULE EVALUATION REPORT',0,0,'C');
		$pdf->Ln(15);
    }

    function get_question($conn, $id){
        $getQuestion=$conn->prepare("SELECT question FROM tbl_evaluation_question WHERE question_id=?");
        $getQuestion->execute([$id]);
        $qn=$getQuestion->fetch();
        return $qn['question'];
    }
    
    $prg_type = $_REQUEST['prg_type'];
    $dept_id = $_REQUEST['dept_id'];
    $splz_id = $_REQUEST['splz_id'];
    $level_id = $_REQUEST['level_id'];
    $mod_id = $_REQUEST['mod_id'];
    $acad_cycle_id = $_REQUEST['acad_cycle_id'];


    //Queries
    $stms_prg = $conn->prepare("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id = ?");
    $stms_dep = $conn->prepare("SELECT dept_full_name FROM tbl_department WHERE dept_id = ?");
    $stms_splz = $conn->prepare("SELECT splz_full_name FROM tbl_specialization WHERE splz_id = ?");
    $stms_lev = $conn->prepare("SELECT level_full_name FROM tbl_level WHERE level_id = ?");
    $stms_mod = $conn->prepare("SELECT m.module_code, m.module_name FROM tbl_modules tm INNER JOIN modules m ON tm.mod_id = m.module_id WHERE tm.module_id=?");
    $stms_acad = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    
    $getModes=$conn->prepare("SELECT DISTINCT(tbl_module_evaluation.mode_id) as mode,
                                    tbl_program_mode.prg_mode_full_name,
                                    tbl_modules.module_id
                                FROM tbl_module_evaluation 
                                    INNER JOIN tbl_program_mode ON tbl_module_evaluation.mode_id=tbl_program_mode.prg_mode_id 
                                    INNER JOIN tbl_modules ON tbl_module_evaluation.module_id=tbl_modules.module_id 
                                WHERE tbl_modules.splz_id=? AND tbl_module_evaluation.module_id=?");

    try {
        
        //Instanciation of inherited class
        $pdf = new FPDF();
        $pdf->SetAuthor('STUMIS');
        $pdf->AliasNbPages();
        $pdf->AddPage('L');
        $pdf->SetFont('Times', '', 12);
        
        
        $pdf->SetTitle("Module Evaluation Report");
        
        $dat = date('d/m/Y');
        $pdf->SetFont('Times', '', 9);
        
        $stms_prg->execute([$prg_type]);
        $stms_dep->execute([$dept_id]);
        $stms_splz->execute([$splz_id]);
        $stms_lev->execute([$level_id]);
        $stms_mod->execute([$mod_id]);
        $stms_acad->execute([$acad_cycle_id]);
        $getModes->execute([$splz_id, $mod_id]);
        
        $prg = $stms_prg->fetch();
        $dept = $stms_dep->fetch();
        $splz = $stms_splz->fetch();
        $level = $stms_lev->fetch();
        $module = $stms_mod->fetch();
        $acad =$stms_acad->fetch();
    
        $prg_type_full_name = $prg['prg_type_full_name'];
        $dept_full_name =  $dept['dept_full_name'];
        $splz_full_name = $splz['splz_full_name'];
        $level_full_name = $level['level_full_name'];
        $module_code = $module['module_code'];
        $module_name = $module['module_name'];
        $acad_year = $acad['acad_year'];
        doc_header($pdf, $conn);
        $pdf->SetY(60);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(1,6,''); 
        $pdf->Cell(135,6,'Program type: '.$prg_type_full_name,1,0); 
        $pdf->Cell(135,6,'Department: '.$dept_full_name,1,0);
        $pdf->Ln();
        $pdf->Cell(1,6,''); 
        $pdf->Cell(135,6,'Specialization: '.$splz_full_name,1,0);
        $pdf->Cell(135,6,'Level: '.$level_full_name,1,0); 
        $pdf->Ln();
        $pdf->Cell(1,6,''); 

        $pdf->Cell(135,6,'Module code: '.$module_code,1,0);
        $pdf->Cell(135,6,'Module name: '.$module_name,1,0); 
        $pdf->Ln();
        $pdf->Cell(1,6,'');
        $pdf->Cell(135,6,'Academic year: '.$acad_year,1,0); 
    
        $pdf->Ln(10);
        
        $pdf->SetTextColor(0, 0, 0);
    	$counter = $getModes->rowCount();
    	$incrementer = 0;
    	// reports start
        while($modes=$getModes->fetch()){
            $incrementer++;
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
                if($answer['answer']=='Very Satisfied'){$a = $answer['answer']; $answer = 'A'; }

                else if($answer['answer']=='Satisfied'){$b = $answer['answer']; $answer = 'B'; }
                else if($answer['answer']=='Neutral'){$c = $answer['answer']; $answer = 'C'; }
                else if($answer['answer']=='Dissatisfied'){$d = $answer['answer']; $answer = 'D'; }
                else if($answer['answer']=='Very Dissatisfied'){$e = $answer['answer']; $answer = 'E'; }
                $pdf->Cell(20, 6, $answer, 1, 0, 'C');

            }
            $pdf->Ln();
            $pdf->SetFont('Times', '', 10);
            $i=1;

            while($qna=$getQuestionsWithAnswers->fetch()){
                array_push($qn_with_answers, $qna['question_id']);
                $getTotalStudent=$conn->prepare("SELECT count(stu) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND acad_cycle_id='".$acad_cycle_id."' AND module_id='".$mod_id."' AND splz_id='".$splz_id."' AND mode_id='".$modes['mode']."'");
                $getTotalStudent->execute();
                $totalStudents=$getTotalStudent->fetch();
                $total=($totalStudents['count']==0?1:$totalStudents['count']);
                
                $pdf->Cell(1, 6, '');
                $pdf->Cell(10, 6, $i++, 1);
                $pdf->Cell(140, 6, $qna['question'], 1);
                $pdf->Cell(20, 6, $total, 1);
                
                
                $getAnswers=$conn->prepare("SELECT answer_id FROM tbl_evaluation_answer WHERE question_id='".$qna['question_id']."'");
                $getAnswers->execute();
    
                while($answer=$getAnswers->fetch()){
                    $getAnswerCount=$conn->prepare("SELECT COUNT(answer_id) as count FROM tbl_module_evaluation WHERE question_id='".$qna['question_id']."' AND answer_id='".$answer['answer_id']."' AND acad_cycle_id='".$acad_cycle_id."' AND module_id='".$mod_id."' AND splz_id='".$splz_id."' AND mode_id='".$modes['mode']."'");
                    $getAnswerCount->execute();
                    $result=$getAnswerCount->fetch();
                    $count=$result['count'];
                    $pdf->Cell(20, 6, number_format($count*100/$total,1).'%', 1, 0, 'C');
                }
                $pdf->Ln();
            }
            
            $pdf->Ln();
            if($incrementer<$counter){
                $pdf->AddPage();
            }
        }
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
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }
$Today_date = date('Y-m-d');
$doc_name = "module_evaluation_report:".$Today_date ;
$pdf->Output($doc_name . '.pdf', 'I');
$pdf->Output();
 
exit;
ob_end_flush();
?>