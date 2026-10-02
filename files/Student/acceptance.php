<?php
    ob_start(); 
    require('../../fpdf16/fpdf.php');
    require('../../meet/con.php');

    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
        $pdf->Image('../..'.$data['logo'], 20, 20, 25);
		$pdf->SetFont('Arial', 'B', 15);
		/**********************Info-main**************************/
		$pdf->SetXY(80, 20);
		$pdf->SetFont('Arial', '', 13);
		$pdf->SetTextColor(55, 24, 60);
		$pdf->Cell(110, 5, $data['full_name'], 0, 0, 'R');
		$pdf->Ln(6);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetX(80);
		$pdf->Cell(110, 5, $data['po_box'], 0, 0, 'R');
		$pdf->Ln(5);
		/*********************Contact***************************/
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetX(80);
		$pdf->Cell(110, 5, $data['phone'], 0, 0, 'R');
		$pdf->Ln(5);

		/*********************Web&email***************************/
		$pdf->SetTextColor(53, 75, 136);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetX(80); 
		$pdf->Cell(110, 5, $data['email'], 0, 0, 'R');
		$pdf->Ln(5);
		$pdf->SetX(80);
		$pdf->Cell(110, 5, $data['website'], 0, 0, 'R');
		$pdf->Ln(5);
	    /********************Title****************************/
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 13);
		$pdf->Cell(200, 20, 'ADMISSION LETTER', 0, 0, 'C');
		$pdf->Ln();
		$pdf->Cell(0, 4, '_______________________________________________________________________________________________________', 0, 0, 'C');
		$pdf->Ln(20);
    }
    //Page footer
    function page_footer($pdf){
        $pdf->SetXY(20, -70);
	    $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont('Arial', 'B', 10);	
	  
        $pdf->SetFont('Arial', '', 11);	
        $pdf->Cell(57, 5, 'MRS. .', 0, 1, 'L');
        $pdf->Ln(2);
        
        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 11);	
        $pdf->Cell(57, 5, 'Academic Registrar', 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 9);	
        $pdf->Cell(57, 5, 'Tel. (+232) 78701222 Email: registrar@njala.edu.sl', 0, 1, 'l');
        $pdf->Ln();

    	$pdf->SetY(-32);
    	$pdf->SetTextColor(0, 0, 0);
    	$pdf->SetFont('Arial', 'I', 10);	 
    	$pdf->Cell(0, 5, 'Issued at NJALA on: '.date("d-M-Y",time()), 0, 1, 'C');
    	
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetY(-30);
    	$pdf->Cell(0, 4, '_______________________________________________________________________________________________________', 0, 0, 'C');
    	$pdf->Ln(5);
    }

    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('ADMISSION LETTER');
    $pdf->setFillColor(255, 255, 255);
    $pdf->SetAutoPageBreak(false);
    
    if (isset($_REQUEST['k'])){
        $reg_no = trim($_REQUEST['k']);
    }
    else{
        $reg_no = "";
    }
    
    $sql = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
    $sql->execute();
    $university = $sql->fetch();
    
    $getStudentData = $conn->prepare('SELECT 
                            ug.reg_no,
                            ug.prg_type,
                            ad.fname,
                            ad.lname,
                            prg.prg_type_full_name,
                            splz.splz_full_name,
                            ac.acad_year
                        FROM tbl_register_program_ug ug
                            INNER JOIN tbl_admission ad ON ug.reg_no = ad.reg_no
                            INNER JOIN tbl_program_type prg ON ug.prg_type = prg.prg_type_id
                            INNER JOIN tbl_specialization splz ON ug.splz_id = splz.splz_id 
                            INNER JOIN tbl_acad_cycle ac ON ug.acad_cycle_id = ac.acad_cycle_id
                        WHERE ug.reg_no = ? 
                        ORDER BY ug.reg_prg_id ASC LIMIT 1');
    
    $getStudentData->execute([$reg_no]);
    if($getStudentData->rowCount()!=0){
        try {
            $student = $getStudentData->fetch();
            
            $names = $student['fname'].' '.$student['lname'];
            $splz = $student['splz_full_name'];
            $acad_year = $student['acad_year'];
            
            $levels = $conn->prepare("SELECT count(level_id) AS levels FROM tbl_level WHERE prg_type = ? ");
            $levels->execute([$student['prg_type']]);
            $validYears = $levels->fetch()['levels'];
            
            $pdf->AliasNbPages();
            $pdf->AddPage();
            doc_header($pdf, $conn);
            $pdf->SetFont('Times', '', 12);
            $pdf->setX(20);
            $pdf->Cell(10, 6, 'Dear', 0, 0, 'L');
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(160, 6, $names.' - '.$reg_no.',', 0, 0, 'L');
            $pdf->Ln(10);
            
            $pdf->SetFont('Times', 'U', 12);
            $pdf->setX(20);
            $pdf->Cell(10, 6, 'Re:', 0, 0, 'L');
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(160, 6, 'Admission into '.$splz, 0, 0, 'L');
            $pdf->Ln();
            $pdf->Ln();
            
            $pdf->SetFont('Times', '', 12);
            
            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "Warm greetings from ".$university['full_name']."(".$university['short_name']."). On behalf of the ".$university['short_name']." Admissions Committee, I am pleased to inform you that you have been admitted to ".$university['short_name']."'s ".$splz." for the Academic Year ".$acad_year.".");
            $pdf->Ln(5);

            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "This admission is valid for ".$validYears." years from the date indicated above, after which your admission into ".$university['short_name']." for the above-mentioned program will lapse and a new application will be required. Until then, this letter and the admission it grants will remain valid.");
            $pdf->Ln(5);
            
            $pdf->setX(20);
            $pdf->Cell(0, 8, 'For the cost of attendance, we encourage you to explore information about our fee structure at ', 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->setX(20);
    		$pdf->SetTextColor(0, 0, 255);
    		$pdf->SetFont('Times', 'U', 12);
    		$pdf->Cell(70, 8, 'https://misnjala.edu.sl/fees_structure', 0, 0, 'L');
    		$pdf->Ln(10);
    		
    		$pdf->SetTextColor(0, 0, 0);
    		$pdf->SetFont('Times', '', 12);
            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "To accept this, offer of admission, please sign and date the Acceptance of Admission at the end of this original letter and return this to the Admissions Office as soon as possible. You should retain the enclosed copy of this letter for your records. Your admission will not be valid until your acceptance has been received by the Admissions Office. We look forward to welcoming you to ".$university['short_name']."! Please do not hesitate to get in touch with us if you need further information.");
            $pdf->Ln(5);
            
            $pdf->setX(20);
            $pdf->Cell(15, 8, 'Yours truly,', 0, 0, 'L');
            $pdf->Ln();

        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    page_footer($pdf);
	$dname = "Admission_".$reg_no;
	$pdf->Output($dname.'.pdf','I');
    
	exit;
    ob_end_flush();
?>
