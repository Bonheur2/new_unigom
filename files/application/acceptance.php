<?php
    ob_start(); 
    require('../../fpdf16/fpdf.php');
    require('../../meet/con.php');
//     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
     	$pdf->Image('../..'.$data['logo'], 20, 20, 20);
		$pdf->SetFont('Arial', 'B', 15);
		/**********************Info-main**************************/
		$pdf->SetXY(80, 20);
		$pdf->SetFont('Arial', '', 13);
		$pdf->SetTextColor(55, 24, 60);
		$pdf->Cell(110, 5, $data['full_name'], 0, 0, 'R');
		$pdf->Ln(6);
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
// 		$pdf->Cell(200, 20, 'ADMISSION LETTER', 0, 0, 'C');
		$pdf->Ln();
		$pdf->Cell(0, 4, '_______________________________________________________________________________________________________', 0, 0, 'C');
		$pdf->Ln(5);
    }
    //Page footer
    function page_footer($pdf){
        $pdf->SetXY(20, -60);
	    $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont('Arial', 'B', 10);	
	  
        $pdf->SetFont('Arial', '', 11);	
        $pdf->Cell(57, 5, 'Dr. Muneer Jalloh. .', 0, 1, 'L');
        $pdf->Ln();
        
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
    	$footer_time=date("d-M-Y",time());
    	$pdf->Cell(0, 5, 'Issued at NJALA University on: '.$footer_time, 0, 1, 'C');
    	
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetY(-30);
    	$pdf->Cell(0, 4, '_______________________________________________________________________________________________________', 0, 0, 'C');
    	$pdf->Ln(5);
    }

    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('OFFER OF ADMISSION');
    $pdf->setFillColor(255, 255, 255);
    $pdf->SetAutoPageBreak(false);
    
    if (isset($_REQUEST['k'])){
        $Stu_code = trim($_REQUEST['k']);
    }
    else{
        $Stu_code = "";
    }
    
        $checkType = $conn->prepare("SELECT tbl_admittedPRG.*, tbl_campus.*, tbl_program_type.*, tbl_faculty.*, 
           tbl_department.*, tbl_specialization.*, tbl_program_mode.*, 
           tbl_level.*
    FROM tbl_admittedPRG
    INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
    INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
    INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
    INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
    INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
    INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
    INNER JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
    WHERE tbl_admittedPRG.Stu_code ='".$Stu_code."'");
        $checkType->execute();
        $progData = $checkType->fetch();
        
        $stu_code = $progData['Stu_code'];
        $splz_id = $progData['splz_id'];
        $prg_type = $progData["prg_type"];
        $prog_shortName=$progData['prg_type_short_name'];
        
        
        
        
        $fees='92,000';
        
        $fetchDepartment = $conn->prepare("SELECT fac_id FROM tbl_specialization WHERE splz_id = '".$splz_id."'");
        $fetchDepartment->execute();
        $progDataDetails=$fetchDepartment->fetch();
        $fac_id=$progDataDetails['fac_id'];
        $selectfac=$conn->prepare("SELECT * FROM  tbl_faculty WHERE fac_id ='".$fac_id."'");
        $selectfac->execute();
        $dataFac=$selectfac->fetch();
        $fac_shortName=$dataFac['fac_short_name'];
        $facFullName=$dataFac['fac_full_name'];
        $fetchDept =  $conn->prepare("SELECT dept_id FROM tbl_specialization WHERE splz_id = '".$splz_id."'");
        $fetchDept->execute();
        $DeptDataDetails=$fetchDept->fetch();
        $stmt0 =  $conn->prepare("SELECT * FROM tbl_applicants WHERE code = :stu_code");
        $stmt0->bindParam(':stu_code', $stu_code);
        $stmt0->execute();
        $sql = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $university = $sql->fetch();
    
    $getStudentData = $conn->prepare("SELECT tbl_admittedPRG.*,tbl_applicants.*,
                                            tbl_campus.camp_full_name,
                                            tbl_program_type.prg_type_full_name,
                                            tbl_faculty.fac_full_name,
                                            tbl_department.dept_full_name,
                                            tbl_specialization.splz_full_name,
                                            tbl_program_mode.prg_mode_full_name
                                            FROM
                                                tbl_admittedPRG
                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                            INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                            INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                            INNER JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                            INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                            INNER JOIN tbl_applicants ON tbl_admittedPRG.Stu_code=tbl_applicants.code
                                            WHERE
                                                 tbl_admittedPRG.Stu_code = '".$stu_code."' 
                                                             ");
    
    $getStudentData->execute();
    if($getStudentData->rowCount()!=0){
        try {
            $student = $getStudentData->fetch();
            $intake_id=$student['intake_id'];
            $names = $student['fname'].' '.$student['lname'];
            $acadData = $conn->prepare("SELECT tbl_acad_cycle.acad_year  FROM tbl_acad_cycle  WHERE status= 1 ORDER BY acad_cycle_id  DESC  LIMIT 1");
            $acadData->execute();
            $acad=$acadData->fetch();
             
            $acad_cycle=$acad['acad_year'];
            $previousYear=$conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE  acad_year<'".$acad_cycle."' ORDER BY acad_cycle_id  DESC  LIMIT 1");
            $previousYear->execute();
            $prevAcad=$previousYear->fetch();
            $aclike=$prevAcad['acad_year'];
            $splz = $student['splz_full_name'];
            $splId=$student['dept_id'];
            
            $camp_id = $progData['cump_id'];  // Should this be 'campus_id'?
$prg_type_id = $progData['prg_type'];  
$fac_id = $progData['fac_id'];  
$dept_id = $progData['dept_id'];  
$splz_id = $progData['splz'];  
$level_id = $progData['level'];  

// Log values correctly (concatenate or use json_encode)
error_log("camp_id: $camp_id, prg_type_id: $prg_type_id, fac_id: $fac_id, dept_id: $dept_id, splz_id: $splz_id, level_id: $level_id");

$select_fee="SELECT * FROM tbl_fee_category WHERE camp_id='$camp_id' AND prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' AND level_id='$level_id'";
$cselect_fee=$conn->prepare($select_fee);
$cselect_fee->execute();
$row_cselect_fee=$cselect_fee->fetch(PDO::FETCH_ASSOC);
            if($row_cselect_fee){
                $ffesPay=$row_cselect_fee['amount']." NLE";
            }
            else{
                $ffesPay="Check the school fees on misnjala.edu.sl/fees_structure for payment information";
            }
            //bank number
            $select_bank="SELECT * FROM tbl_bank WHERE fac_id='$fac_id'";
            $cselect_bank=$conn->prepare($select_bank);
            $cselect_bank->execute();
            $row_cselect_bank=$cselect_bank->fetch(PDO::FETCH_ASSOC);
            $account=$row_cselect_bank['account_no'];
            // $acad_year = $student['acad_year'];
            
            $levels = $conn->prepare("SELECT count(level_id) AS levels FROM tbl_level WHERE prg_type = ? ");
            $levels->execute([$student['prg_type']]);
            $validYears = $levels->fetch()['levels'];
            $date=date('Y');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            doc_header($pdf, $conn);
            
            $pdf->SetFont('Times', '', 12);
            $pdf->setX(20);
            $pdf->Cell(60, 6, 'Your Ref : '.$prog_shortName.' - '.$fac_shortName.' '.$stu_code, 0, 0, 'L');
            $pdf->SetFont('Times', '', 12);
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->Cell(60, 6, 'Our Ref  RE/ST/ : '.$date, 0, 0, 'L');
            $pdf->SetFont('Times', '', 12);
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->Cell(60, 6, date('jS/F/Y'), 0, 0, 'L');
            $pdf->SetFont('Times', '', 12);
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->Cell(60, 6, 'C/O Dean, '.$facFullName, 0, 0, 'L');
            $pdf->SetFont('Times', '', 12);
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->Cell(60, 6, 'Dear , '.$student['fname'], 0, 0, 'L');
            $pdf->SetFont('Times', '', 12);
            // $pdf->Cell(160, 6, $names, 0, 0, 'L');
            $pdf->Ln(10);
            $pdf->setX(80);
            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(20, 6, 'OFFER OF ADMISSION', 0, 0, 'L');
            
            $pdf->SetFont('Times', '', 12);
            $pdf->Ln(10);
            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "I am pleased to inform you that, you have been offered admission to Njala University to pursue a programme of study leading to the  ".$splz."in , ".$facFullName." for the ".$aclike."/".$acad_cycle." academic year");
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "The date for the re-opening of the University and the Registration of new students will be 25th November ".$aclike.".All new students are therefore required to pay the FULL YEAR'S FEES not later than the stated date.");
            $pdf->setX(20);
            $pdf->MultiCell(170, 5, "Payment is to be made to the Njala University Fees Account: ".$account." at the Sierra Leone Commercial Bank in Freetown, Bo and Njala. ".$ffesPay.". In the case of foreign students, payments will be accepted only in Dollars or Pound Sterling, except where an exemption has been granted by the University. If your fees are not paid by 25th November,  that would indicate that you are no longer interested in the offer and your space will be forfeited.");
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->MultiCell(170,6,"You are required to return the acceptance of offer form attached to your letter with a receipt of payment of your fees at Registration. Copy of the current University fees is attached.");
            $pdf->setX(20);
            $pdf->MultiCell(170,6,"The University residential facilities for students are limited and therefore student accommodation is provided on a first come first serve basis.");
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->MultiCell(170,6,"On behalf of the University, I wish to congratulate you on this Offer of Admission. If you have any further enquiries to make, please feel free to contact me on the telephone number stated above or the Deputy Registrar  Students Services on 232 76 678962 / 034141258");
            $pdf->setX(20);
            $pdf->Ln(5);
            $pdf->setX(20);
            $pdf->MultiCell(170,5,"Yours Sincerely,");
            $pdf->Ln(5);
           $pdf->Image('../Card/img/Stump.jpg',24,218,23,20);
            
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    page_footer($pdf);
    
	$dname = "OFFER OF ADMISSION_".$names."/".$Stu_code;
	$pdf->Output($dname.'.pdf','I');
    
	exit;
    ob_end_flush();
?>
