<?php
    ob_start(); 
    require('../../../../fpdf/fpdf.php');
    require('../../../../meet/con.php');
    
    class MyPDF extends FPDF {
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }
    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../../../..'.$data['logo'],20,10,40);
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
		$pdf->Cell(280,20,'STUDENT SATISFACTION REPORT',0,0,'C');
		$pdf->Ln(15);
    }

    function get_sections($db){
        $getSections = $db->prepare("SELECT * FROM tbl_satisfactory_questions_categ WHERE status = 1");
        $getSections->execute();
        return $getSections;
    }
    
    function get_questions($db, $cat_id){
        $getQuestions = $db->prepare("SELECT * FROM tbl_satisfactory_questions WHERE cat_id = ? AND status = 1");
        $getQuestions->execute([$cat_id]);
        return $getQuestions;
    }

    function get_answers($db){
        $getAnswers = $db->prepare("SELECT * FROM tbl_satisfactory_answers WHERE status=1");
        $getAnswers->execute();
        return $getAnswers;
    }
    
    try {    
        //Instanciation of inherited class
        $pdf = new MyPDF();
        $pdf->SetAuthor('STUMIS');
        $pdf->AliasNbPages();
        $pdf->AddPage('L');
        $pdf->SetTitle("STUDENT SATISFACTION REPORT");
        $pdf->SetTextColor(0, 0, 0);
        doc_header($pdf, $conn);
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }
    
    
    $type = $_REQUEST['type'];
    $acad = $_REQUEST['acad'];

    //Queries
    $stms_acad = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    $sections = get_sections($conn);
    try {
        $pdf->SetFont('Times', 'B', 12);
        $stms_acad->execute([$acad]);
        $academic =$stms_acad->fetch();
        $acad_year = $academic['acad_year'];
        
        $pdf->SetY(60);
        $pdf->Cell(1,6,'');
        $pdf->Cell(270,8,'ACADEMIC YEAR: '.$acad_year,1,0); 
        $pdf->Ln(10);
        
    	$counter = $sections->rowCount();
    	$incrementer = 0;
    	// reports start
        while($section = $sections->fetch()){
            $incrementer++;
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(1, 6, '');
            $pdf->Cell(270, 6, $section['category'], 1);
            $pdf->Ln();
            
            $questions = get_questions($conn, $section['cat_id']);

            $pdf->Cell(1, 6, '');
            $pdf->Cell(10, 6, 'S/N', 1);
            $pdf->Cell(165, 6, 'Questions', 1);
            $pdf->Cell(20, 6, 'Responses', 1);
        
            $answers = get_answers($conn);

            while($answer=$answers->fetch()){
                if($answer['answer_id']==1){$a = $answer['answer']; $answer = 'A'; }
                else if($answer['answer_id']==2){$b = $answer['answer']; $answer = 'B'; }
                else if($answer['answer_id']==3){$c = $answer['answer']; $answer = 'C'; }
                else if($answer['answer_id']==4){$d = $answer['answer']; $answer = 'D'; }
                else if($answer['answer_id']==5){$e = $answer['answer']; $answer = 'E'; }
                $pdf->Cell(15, 6, $answer, 1, 0, 'C');
            }
            
            $pdf->Ln();
            $pdf->SetFont('Times', '', 10);
            $i=1;
            
            while($question = $questions->fetch()){
                $getTotalAnswers = $conn->prepare("SELECT count(stu) as count FROM tbl_student_satisfaction WHERE question_id='".$question['qn_id']."' AND acad_cycle_id='".$acad."'");
                $getTotalAnswers->execute();
                $totalAnswers = $getTotalAnswers->fetch();
                $total = $totalAnswers['count'];
                
                $pdf->Cell(1, 6, '');
                $pdf->Cell(10, 6, $i++, 1);
                
                $position = strpos($question['question'], '(');
                if ($position !== false) {
                    $qna = trim(substr($question['question'], 0, $position));
                } else {
                    $qna = $question['question'];
                }
                
                $pdf->cell(165, 6, $qna, 1);
                $pdf->Cell(20, 6, number_format($total), 1);
                
                $answers = get_answers($conn);
                while($answer = $answers->fetch()){
                    $getAnswerCount = $db->prepare("SELECT COUNT(answer_id) as count FROM tbl_student_satisfaction WHERE question_id='".$question['qn_id']."' AND answer_id='".$answer['answer_id']."' AND acad_cycle_id='".$acad."'");
                    $getAnswerCount->execute();
                    $results = $getAnswerCount->fetch();
                    $count = $results['count'];
                    $pdf->Cell(15, 6, number_format($count*100/($total>0?$total:1),1).'%', 1, 0, 'C');
                }
                $pdf->Ln();
            }
            
            $pdf->Ln();
            if($incrementer<$counter && $pdf->getY()>=150){
                $pdf->AddPage('L');
            }
        }
        $pdf->Ln();
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(201,6,''); 
        $pdf->Cell(70,6,'Labels',1,0,'C'); 
        $pdf->Ln();
        $pdf->SetFont('Times', 'I', 10);
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
    
    $doc_name = time() . '_' . mt_rand(1000, 9999) ;
    $pdf->Output($doc_name . '.pdf', 'I');
    $pdf->Output();
     
    exit;
    ob_end_flush();
?>