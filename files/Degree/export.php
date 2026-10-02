<?php
    ob_start(); 
    ini_set('log_errors', 0);
    include('../../phpqrcode/qrlib.php');
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    require('utils.php');

    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../..'.$data['logo'], 123.5, 0, 50);
		
		$pdf->SetXY(10, 50);
		$pdf->SetFont('Times', 'B', 45);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(277, 15, strtoupper($data['full_name']), 0, 0, 'C');
    }
    //Page footer
    function page_footer($pdf){
    	$pdf->SetTextColor(0,0,0);
        $pdf->SetXY(10, -30);
	    $pdf->SetTextColor(0,0,0); 
    	$pdf->SetFont('Times','B',14);	  
    	         
    	$pdf->Cell(50,4,'_____________________',0,0,'C');
    	$pdf->Cell(30,4,'',0,0,'C');
    	$pdf->Cell(70,4,'____________________________',0,0,'C');
    	$pdf->Cell(30,4,'',0,0,'C');
    	$pdf->Cell(50,4,'_____________________',0,0,'C');
    	$pdf->Cell(30,4,'',0,0,'C');
    	$pdf->Ln(10);
    	
    	$pdf->Cell(50,4,'Principal',0,0,'C');
    	$pdf->Cell(30,4,'',0,0,'C');
    	$pdf->Cell(70,4,'Deputy Principal - Academics ',0,0,'C');
    	$pdf->Cell(30,4,'',0,0,'C');
    	$pdf->Cell(50,4,'Academic Registrar',0,0,'C');
    	$pdf->Ln();
    }

    $pdf=new FPDF('L','mm','A4');
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Degree');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(255, 250, 240);
    $pdf->SetDrawColor(255, 250, 240);
    $pdf->SetLineWidth(0.01);
    $pdf->SetAutoPageBreak(false);
    
    $prg_type = $_REQUEST['ptype'];
	$fac = $_REQUEST['fac'];
	$dept = $_REQUEST['dept'];
	$splz = $_REQUEST['splz']; 
	$grad = $_REQUEST['grad'];
	
    $getStudents = $conn->prepare("SELECT 
                                        r.reg_no,
                                        f.fac_full_name,
                                        ad.fname,
                                        ad.lname,
                                        gr.prg_award_id,
                                        gr.classification,
                                        ac.grad_date
                                        
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                        INNER JOIN tbl_faculty f ON r.fac_id = f.fac_id
                                        INNER JOIN tbl_graduants gr ON r.reg_no=gr.reg_no
                                        INNER JOIN tbl_grad_cycle ac ON gr.grad_cycle_id = ac.grad_cycle_id
                                        
                                    WHERE
                                        r.prg_type='".$prg_type."' AND 
                                        r.fac_id='".$fac."' AND 
                                        r.dept_id='".$dept."' AND
                                        r.splz_id='".$splz."' AND
                                        gr.grad_cycle_id='".$grad."' AND
                                        r.reg_active in (1,6) group by ad.reg_no
                                    ");
    $getStudents->execute();

    try{
        while($students=$getStudents->fetch()){
            $class = $students['classification'];
            
            
            $pdf->AddPage();
            $pdf->Image('template.png', 0, 0, 297, 210);
            doc_header($pdf, $conn); 
            
            ##### Main #####
            $pdf->setXY(10, 70);
            
            $pdf->SetFont('Times','',18);
            $pdf->Cell(277, 6, 'By the authority of the College Council, and upon the recommendation of the Academic Senate,', 0, 0, 'C');
            $pdf->Ln(8);
            
            $pdf->setX(10);
            $pdf->SetFont('Times','',18);
            $pdf->Cell(277, 6, 'hereby confer upon', 0, 0, 'C');
            $pdf->Ln(12);
            
            $pdf->setX(10);
            $pdf->SetFont('Times','B',20);
            $pdf->Cell(277, 6, $students['fname']." ".$students['lname'], 0, 0, 'C');
            $pdf->Ln(12);
            
            $pdf->setX(10);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(277, 6, 'Having satisfied the requirements for the award of', 0, 0, 'C');
            $pdf->Ln(10);
            
            $pdf->setX(10);
            $pdf->SetFont('Times','B',16);
            $pdf->Cell(277, 6, $students['prg_award_id'].$honours, 0, 0, 'C');
            $pdf->Ln(10);
            
            $pdf->setX(10);
            $pdf->SetFont('Times','BI',16);
            $pdf->Cell(277, 6, $class, 0, 0, 'C');
            $pdf->Ln(10);
            
            $yearInWords = translateYear(date('Y', strtotime($students['grad_date'])));
            $month = date('F', strtotime($students['grad_date']));
            $day = translateDay(date('d', strtotime($students['grad_date'])));
            
            $pdf->setX(10);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(277, 6, 'With all rights privileges and honors thereunto appertaining.', 0, 0, 'C');
            $pdf->Ln(7);
            
            $pdf->setX(10);
            $pdf->Cell(277, 6, 'Given at Kigali-Rwanda, this '.$day.' day of '.$month.', in the', 0, 0, 'C');
            $pdf->Ln(7);
            
            $pdf->setX(10);
            $pdf->Cell(277, 6, 'year of our Lord, '.$yearInWords.'.', 0, 0, 'C');
            $pdf->Ln(7);
            
            ##### End Main #####
            
            ##### QR #####
            $qr_file = str_replace('/', '-', $students['reg_no']).".png";
            QRcode::png('https://www.act.ac.rw/certificate/validate?id='.$students['reg_no'], $qr_file, QR_ECLEVEL_Q, 10, 1);
            $pdf->Rect(250, 150, 30, 35, 'DF');
            $pdf->Image($qr_file, 251, 151, 28, 28);
            
            $pdf->setXY(251, 181);
            $pdf->SetFont('Times', 'B', 9);
            $pdf->cell(28, 4, $students['reg_no'] , 0, 0, 'C');
            
            unlink($qr_file);
            ##### END QR #####
            
            page_footer($pdf);
        }
    }catch(Exception $e){
        echo $e;
    }
    
	$dname="Degrees";
	$pdf->Output($dname.'.pdf','I');
    
	exit;
    ob_end_flush();
?>
