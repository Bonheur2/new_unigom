<?php
    ob_start(); 
    ini_set('log_errors', 0);
    include('../../phpqrcode/qrlib.php');
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    require('utils.php');

    $pdf=new FPDF();
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
            if($prg_type == 1){
                $honours = "";
            } else {
                $honours = "";
                
            }
            
            $class = $students['classification'];
            
            
            $pdf->AddPage();
            $pdf->Image('template.png', 0, 0, 210, 297);
            
            ##### Main #####
            $pdf->setY(110);
            $pdf->setX(20);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(170, 6, 'This is to certify that', 0, 0, 'C');
            $pdf->Ln(10);
            $pdf->setX(20);
            $pdf->SetFont('Times','BI',18);
            $pdf->Cell(170, 6, $students['lname']." ".$students['fname'], 0, 0, 'C');
            $pdf->Ln(15);
            
            $pdf->setX(20);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(170, 6, 'Having satisfied the requirements for the award of', 0, 0, 'C');
            $pdf->Ln(10);
            
            $pdf->setX(20);
            $pdf->SetFont('Times','BI',16);
            $pdf->Cell(170, 6, $students['prg_award_id'].$honours, 0, 0, 'C');
            $pdf->Ln(10);
            
            $pdf->setX(20);
            $pdf->SetFont('Times','BI',16);
            $pdf->Cell(170, 6, $class, 0, 0, 'C');
            $pdf->Ln(10);
            
            $pdf->setX(20);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(170, 6, 'At a congregation held on the', 0, 0, 'C');
            $pdf->Ln(10);
            
            $yearInWords = translateYear(date('Y', strtotime($students['grad_date'])));
            $month = date('F', strtotime($students['grad_date']));
            $day = translateDay(date('d', strtotime($students['grad_date'])));
            
            $pdf->setX(20);
            $pdf->SetFont('Times','BI',16);
            $pdf->Cell(170, 6, $day.' day of '.$month.' '.$yearInWords, 0, 0, 'C');
            
            ##### End Main #####
            ##### QR #####

            $qr_file = $students['reg_no'].".png";
            QRcode::png('https://www.eacc.stumis.rw/certificate/validate?id='.$students['reg_no'], $qr_file, QR_ECLEVEL_Q, 10, 1);
            $pdf->Rect(90, 200, 30, 35, 'DF');
            $pdf->Image($qr_file, 91, 201, 28, 28);
            
            $pdf->setXY(91, 231);
            $pdf->SetFont('Times', 'B', 10);
            $pdf->cell(28, 4, $students['reg_no'] , 0, 0, 'C');
            
            unlink($qr_file);
            ##### END QR #####
            
            $pdf->setXY(20, -40);
            $pdf->SetFont('Times','',16);
            $pdf->Cell(60, 6, '..................................', 1, 0, 'C');
            $pdf->Cell(50, 6, '', 0, 0, 'C');
            $pdf->Cell(60, 6, '..................................', 1, 0, 'C');
            
            $pdf->setXY(20, -34);
            $pdf->SetFont('Times','BI',14);
            $pdf->Cell(60, 6, 'Deputy Principal Academics', 1, 0, 'C');
            $pdf->Cell(50, 6, '', 0, 0, 'C');
            $pdf->Cell(60, 6, 'Principal', 0, 0, 'C');
        }
    }catch(Exception $e){
        echo $e;
    }
    
	$dname="Degrees";
	$pdf->Output($dname.'.pdf','I');
    
	exit;
    ob_end_flush();
?>
