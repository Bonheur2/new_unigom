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
		$pdf->Cell(120,5,$data['full_name'],0,0,'R');
		$pdf->Ln(6);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(120,5,$data['po_box'],0,0,'R');
		$pdf->Ln(5);
		/*********************Contact***************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(120,5,$data['phone'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(70);
		/*********************Web&email***************************/
		$pdf->SetTextColor(53,75,136);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(70);
		$pdf->Cell(120,5,$data['email'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(70);
		$pdf->Cell(120,5,$data['website'],0,0,'R');
		$pdf->Ln(5);
	    /********************Title****************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','BU',13);
		$pdf->Cell(200,20,'ACADEMIC TRANSCRIPT',0,0,'C');
		$pdf->Ln(15);
    }
    //Page footer
    function page_footer($pdf, $data){
        $pdf->SetY(-35);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->SetFont('Arial','B',10);	
	  
    	$pdf->SetFont('Arial','B',11);	
    	$pdf->Cell(57,5,'',0,1,'C');
    	$pdf->Cell(57,5,'Academic Registrar  Signature & stamp:......................',0,1,'C');
    
    // 	$pdf->SetX(70);
	
    // 	$pdf->SetTextColor(0,0,0);
    // 	$pdf->SetFont('Arial','B',10);	
	  
    // 	$pdf->SetFont('Arial','B',11);	
    // 	$pdf->Cell(130,-5,'Deputy Principal Academic Affairs and Research',0,1,'C');

    	$pdf->SetY(-22);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','I',10);	 
    	$pdf->Cell(0,5,'Issued at KIGALI on: '.date("d-M-Y",time()),0,1,'C');
    	
    	$pdf->SetY(-13);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','I',8);	 
    	$pdf->Cell(0,5,'*This transcript is issued without any erasures or alterations and is valid only with the official stamp',0,1,'C');
    	
        // $pdf->Image('../../fpdf16/regsign.gif',15,235,50);
        // $pdf->Image('../../fpdf16/regsign.gif',130,235,50);
        $pdf->SetTextColor(44,144,60);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetY(-10);
    	$pdf->Cell(0,4,'_______________________________________________________________________________________________________',0,0,'C');
    	$pdf->Ln(5);
    //     $pdf->SetFont('Arial','',10);
    // 	$pdf->SetY(-15);
    // 	$pdf->SetTextColor(0,0,0);
    //     $pdf->SetFont('Arial','I',8);
    // 	$pdf->Ln(4);
    // 	$pdf->Cell(0,10,'ECOBANK:00000000000(FRW), GTBANK:00000000000(FRW)',0,0,'C');
    }
    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Transcript');
    $pdf->SetAutoPageBreak(false);
    
    if (isset($_REQUEST['stud'])){
        $reg_no = trim($_REQUEST['stud']);
    }
    else{
        $reg_no = "";
    }
    
    $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
    $sql->execute();
    $data=$sql->fetch();
    
    $dat = date('Y-m-d');
    
    //Get basic student's info
    $basic=$conn->prepare('SELECT 
                            tbl_register_program_ug.reg_no,
                            tbl_register_program_ug.prg_type,
                            tbl_admission.fname,
                            tbl_admission.lname,
                            tbl_program_type.prg_type_full_name,
                            tbl_faculty.fac_full_name,
                            tbl_department.dept_full_name,
                            tbl_specialization.splz_full_name,
                            tbl_level.level_full_name,
                            tbl_graduants.degree_no
                        FROM tbl_register_program_ug
                            INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                            INNER JOIN tbl_graduants ON tbl_register_program_ug.reg_no=tbl_graduants.reg_no
                            INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                            INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                            INNER JOIN tbl_department ON tbl_register_program_ug.dept_id=tbl_department.dept_id
                            INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id 
                            INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                        WHERE tbl_register_program_ug.reg_no="'.trim($_REQUEST['stud']).'" ORDER BY tbl_register_program_ug.level_id DESC LIMIT 1');
    $basic->execute();
    if($basic->rowCount()!=0){
        try {
            $getDocuments=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE upload_doc like '%photo_%' AND tracking_id='".trim($_REQUEST['stud'])."'");
            $getDocuments->execute();
            if($getDocuments->rowCount()>0){
                $documents=$getDocuments->fetch();
                $stuImg=$documents['upload_doc'];
            }else{
                $stuImg="404.png";
            }
            
            $studentData=$basic->fetch();
            
            
            if($studentData['prg_type']==2){
        	    $grade_table = 'tbl_gradeP';
        	}else{
        	    $grade_table = 'tbl_grade';
        	}
            //add student info
             $pdf->AliasNbPages();
             $pdf->AddPage();
             doc_header($pdf, $conn);
             $pdf->Rect(146, 60, 40, 40, 'D');
             
             $imagePath = '../../' . $stuImg;
             if (file_exists($imagePath) && $stuImg != '') {
                 $pdf->Image($imagePath, 146, 60, 40, 40);
             }
             
            //  echo $_REQUEST['stud'];
             
             $pdf->SetFont('Times','',12);
             $pdf->SetFont('Arial','B',9);
             $pdf->Ln(7);
        	 $pdf->Cell(12,6,'');
        	 $pdf->Cell(124,6,'Registration Number: '.strtoupper($reg_no)); 
         	 $pdf->Ln();
             $pdf->Cell(12,6,''); 
        	 $pdf->Cell(124,6,'Family Name: '.strtoupper($studentData['lname'])); 
        	 $pdf->Ln();
        	 $pdf->Cell(12,6,'');
        	 $pdf->Cell(124,6,'First Name(s): '.strtoupper($studentData['fname']));
        	 $pdf->Ln();
             $pdf->Cell(12,6,''); 
        	 $pdf->Cell(124,6,'Faculty: '.$studentData['fac_full_name']); 
        	 $pdf->Ln();
          	 $pdf->Cell(12,6,'');
        	 $pdf->Cell(124,6,'Specialization: '.$studentData['splz_full_name']);
             $pdf->Ln();
        	//end student info
    
             $total_credits=0;
             $total_credit_pts=0;
             $failed_credits=0;
             
            //get level data
             $sql=$conn->prepare('SELECT DISTINCT(tbl_register_program_ug.level_id),
                                                tbl_acad_cycle.acad_year as acad_year,
                                                tbl_level.level_full_name 
                                            FROM tbl_register_program_ug
                                                INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                                INNER JOIN tbl_acad_cycle ON tbl_register_program_ug.acad_cycle_id=tbl_acad_cycle.acad_cycle_id
                                                WHERE reg_no="'.trim($_REQUEST['stud']).'"');
             $sql->execute();
            
            // set initial column position
            $colX = 15;
            $colY = 105;
            $tracker = 0;
            $rem = $sql->rowCount();
            while($levelData=$sql->fetch()) {
                $tracker++;
                $rem--;
                // draw column border
                $pdf->SetFont('Arial','B',8);
                $pdf->SetXY($colX + 2, $colY + 2);
                $pdf->Cell(115, 6, $levelData['level_full_name'],1, 0,'L');
                $pdf->Cell(60, 6, $levelData['acad_year'], 1,0,'R');
                $pdf->Ln();
                
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetX($colX + 2);
                $pdf->Cell(20,6,'CODE',1); 
                $pdf->Cell(95,6,'MODULE NAME',1); 		 
                $pdf->Cell(20,6,'CREDITS',1); 
                $pdf->Cell(20,6,'MARKS (%)',1); 
                $pdf->Cell(20,6,'GRADE',1,0,'C');
                $pdf->Ln();
                
                //retrieve student marks
                $sqlMarks=$conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.credited_module,
                                                tbl_modules.module_credits,
                                                tbl_markby_module.marks,
                                                tbl_markby_module.enrolled
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stud']).'" AND
                                                      tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                      tbl_modules.credited_module=1 AND
                                                      tbl_markby_module.enrolled IN (1,2) AND 
                                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                      tbl_markby_module.marks IS NOT NULL');
                $sqlMarks->execute();
                $credits=0;
                $tcredits=0;
                $tcreditpts=0;
                $grade="";
                while($studentMarks=$sqlMarks->fetch()){
                    $marks=$studentMarks['marks'];
                    $tcredits+=$studentMarks['module_credits'];
                    $tcreditpts+=$marks*$studentMarks['module_credits'];
                            
                    // SELECT GRADE
                    $sqlgrade=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks.'" and m_to>"'.$marks.'"');
                    $sqlgrade->execute();
                    $resgrade=$sqlgrade->fetch();
                    $grade=$resgrade['grade_letter'];
                    if($grade=="F"){
                        $failed_credits+=$studentMarks['module_credits'];
                    };
                    //write marks
                	$pdf->SetFont('Arial','',8);
                	$pdf->SetX($colX + 2);
                	$pdf->Cell(20,5,$studentMarks['module_code'],1);
                	$pdf->Cell(95,5,($studentMarks['enrolled']==2?'*':'').$studentMarks['module_name'],1);
                	$pdf->Cell(20,5,$studentMarks['module_credits'],1,0,'C');
                	$pdf->Cell(20,5,number_format($studentMarks['marks'], 1),1,0,'C');
                	$pdf->Cell(20,5,$grade,1,0,'C');
                	$pdf->Ln();
                }
                //uncredited modules
                $sqlMarks_uncredited=$conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.credited_module,
                                                tbl_modules.module_credits,
                                                tbl_markby_module.marks,
                                                tbl_markby_module.enrolled
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stud']).'" AND
                                                      tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                      tbl_modules.credited_module=0 AND
                                                      tbl_markby_module.enrolled IN (1,2) AND 
                                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                      tbl_markby_module.marks IS NOT NULL');
                $sqlMarks_uncredited->execute();
                $grade2="";
                if($sqlMarks_uncredited->rowCount()){
                    $pdf->SetX($colX + 2);
                    $pdf->SetFont('Arial','I',8);
                    $pdf->Cell(175,5,'Uncredited modules',1);
                    $pdf->Ln();
                    while($studentMarks2=$sqlMarks_uncredited->fetch()){
                        $marks2=$studentMarks2['marks'];
                        // SELECT GRADE
                        $sqlgrade2=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks2.'" and m_to>"'.$marks2.'"');
                        $sqlgrade2->execute();
                        $resgrade2=$sqlgrade2->fetch();
                        $grade2=$resgrade2['grade_letter'];
                        //write marks
                    	$pdf->SetFont('Arial','',8);
                    	$pdf->SetX($colX + 2);
                    	$pdf->Cell(20,5,$studentMarks2['module_code'],1);
                    	$pdf->Cell(95,5,($studentMarks2['enrolled']==2?'*':'').$studentMarks2['module_name'],1);
                    	$pdf->Cell(20,5,'',1,0,'C');
                    	$pdf->Cell(20,5,number_format($studentMarks2['marks'], 1),1,0,'C');
                    	$pdf->Cell(20,5,$grade2,1,0,'C');
                    	$pdf->Ln();
                    }
                }
                    $pdf->Ln();
                    $average=$tcreditpts/($tcredits==0?1:$tcredits);
                    $pdf->SetFont('Arial','B',10);
                    $pdf->SetX($colX + 2);
                	$pdf->Cell(115,5,'Total credits',1,0,'L');
                	$pdf->Cell(60,5,$tcredits,1,0,'C');
                	$pdf->Ln();
                	$pdf->SetX($colX + 2);
                	$pdf->Cell(115,5,'Annual Average',1,0,'L');
                	$pdf->Cell(60,5,number_format($average,1)." %",1,0,'C');
                	$pdf->Ln();
                	
                	$total_credits+=$tcredits;
                	$total_credit_pts+=$tcreditpts;
                	if($rem>0 || $pdf->GetY()>200){
                        $pdf->AddPage();
                        $colY = 10;
                	}
            }
            $pdf->SetFillColor(255, 255, 100);
            $pdf->Rect($colX+2, $pdf->GetY()+2, 175, 30, 'DF');
            $pdf->SetFont('Arial','B',11);
            $pdf->SetXY($colX + 5, $pdf->GetY() + 5);
            $pdf->Cell(170, 6, 'Cumulative Summary', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 11);
            
            $pdf->SetX($colX + 2);
            $pdf->Cell(90,6,'Total Credits',1); 
            $pdf->Cell(81,6,$total_credits,1); 		 
            $pdf->Ln();
            $pdf->SetX($colX + 2);
            $pdf->Cell(90,6,'Cumulative Average',1); 
            $pdf->Cell(81,6,number_format($total_credit_pts/($total_credits==0?1:$total_credits),1)." %",1); 		 
            $pdf->Ln();
	
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    page_footer($pdf, $data);
	$dname=$reg_no.'_L'.$level;
	$pdf->Output($dname.'.pdf','I');

    exit;
    ob_end_flush();
?>
