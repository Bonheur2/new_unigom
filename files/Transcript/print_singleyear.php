<?php
    ini_set('display_errors', 1);
    
    // ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    ini_set('memory_limit', '-1');

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
		$pdf->SetX(70);
		$pdf->Cell(120,5,'',0,0,'R');
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
        $pdf->SetY(-40);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->SetFont('Arial','B',10);	
	  
    	$pdf->SetFont('Arial','B',11);	
    	$pdf->Cell(57,5,'Academic Registrar',0,1,'C');
    
    // 	$pdf->SetX(70);
	
    // 	$pdf->SetTextColor(0,0,0);
    // 	$pdf->SetFont('Arial','B',10);	
	  
    // 	$pdf->SetFont('Arial','B',11);	
    // 	$pdf->Cell(130,-5,'Deputy Principal Academic Affairs and Research',0,1,'C');
    	
    	$pdf->SetY(-33);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','I',8);	 
    	$pdf->Cell(0,5,'*This transcript is issued without any erasures or alterations and is valid only with the official stamp',0,1,'C');
    	
        $pdf->SetTextColor(44,144,60);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetY(-30);
    	$pdf->Cell(0,4,'_______________________________________________________________________________________________________',0,0,'C');
    	$pdf->Ln(5);
    }

    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Transcript');
    $pdf->SetAutoPageBreak(false);
    $pdf->AliasNbPages();
    
    $splz = trim($_REQUEST['splz']);
    $level = trim($_REQUEST['lev']);
    $acad_cycle_id = trim($_REQUEST['academic']);
    
    $stmt = $conn->prepare("SELECT DISTINCT(reg_no) FROM tbl_register_program_ug WHERE acad_cycle_id = ? AND level_id = ? AND splz_id = ? AND reg_active IN (1, 6) ORDER BY reg_no ASC");
    $stmt->execute([$acad_cycle_id, $level, $splz]);
    
    while($stud = $stmt->fetch()){
        $reg_no = $stud['reg_no'];
        $getStudentData = $conn->prepare('SELECT 
                                tbl_register_program_ug.reg_no,
                                tbl_register_program_ug.prg_type,
                                tbl_admission.fname,
                                tbl_admission.lname,
                                tbl_program_type.prg_type_full_name,
                                tbl_faculty.fac_full_name,
                                tbl_department.dept_full_name,
                                tbl_specialization.degree_name,
                                tbl_level.level_full_name,
                                tbl_acad_cycle.acad_year
                            FROM tbl_register_program_ug
                                INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                                INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                                INNER JOIN tbl_department ON tbl_register_program_ug.dept_id=tbl_department.dept_id
                                INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id 
                                INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                INNER JOIN tbl_acad_cycle ON tbl_register_program_ug.acad_cycle_id=tbl_acad_cycle.acad_cycle_id
                            WHERE tbl_register_program_ug.reg_no="'.$reg_no.'" AND
                                  tbl_register_program_ug.level_id="'.$level.'"');
        
        $getStudentData->execute();
        if($getStudentData->rowCount()!=0){
            try {
                $getDocuments=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE upload_doc like '%photo_%' AND tracking_id='".$reg_no."'");
                $getDocuments->execute();
                if($getDocuments->rowCount() > 0){
                    $documents=$getDocuments->fetch();
                    $stuImg=$documents['upload_doc'];
                }else{
                    $stuImg="notfound";
                }
                
                $studentData=$getStudentData->fetch();
                if($studentData['prg_type']==2){
            	    $grade_table = 'tbl_gradeP';
            	}else{
            	    $grade_table = 'tbl_grade';
            	}
                //get stud marks

                $sqlMarks=$conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.module_credits,
                                                tbl_markby_module.cat,
                                                tbl_markby_module.final_exam,
                                                tbl_markby_module.marks,
                                                tbl_markby_module.enrolled
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.$reg_no.'" AND
                                                  tbl_modules.level_id="'.$level.'" AND
                                                  tbl_modules.credited_module=1 AND
                                                  tbl_markby_module.enrolled IN (1,2) AND 
                                                  (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                  tbl_markby_module.marks IS NOT NULL
                                                ');
                $sqlMarks->execute();
                
                if($sqlMarks->rowCount() > 0){
                    //add student info
                     $pdf->AddPage();
                     doc_header($pdf, $conn);
                     
                     $pdf->Rect(156, 60, 30, 30, 'D');
                     
                     $imagePath = '../../' . $stuImg;
                     if (file_exists($imagePath) && $stuImg != '') {
                         $pdf->Image($imagePath, 156, 60, 30, 30);
                     }
                     
                     $pdf->SetFont('Times','',12);
                     $pdf->SetFont('Arial','B',8);
                     $pdf->Ln(7);
                	 $pdf->Cell(12,6,'');
                	 $pdf->Cell(124,6,'Registration Number: '.strtoupper($reg_no)); 
                 	 $pdf->Ln();
                     $pdf->Cell(12,6,''); 
                	 $pdf->Cell(124,6,'Family Name: '.strtoupper($studentData['lname'])); 
                	 $pdf->Ln();
                	 $pdf->Cell(12,6,'');
                	 $pdf->Cell(124,6,'First Name: '.strtoupper($studentData['fname']));
                	 $pdf->Ln();
                     $pdf->Cell(12,6,''); 
                	 $pdf->Cell(124,6,'Faculty: '.$studentData['fac_full_name']); 
                	 $pdf->Ln();
                  	 $pdf->Cell(12,6,'');
                	 $pdf->Cell(124,6,'Specialization: '.$studentData['degree_name']);
                     $pdf->Ln(7);
                	//end student info
                	
                	//marks start
                     $pdf->SetFont('Arial','B',8);
                     $pdf->Ln();
                     
                     
                	 $pdf->Cell(12,6,'');
                	 $pdf->Cell(100,6,$studentData['level_full_name'],1);
                	 $pdf->Cell(64,6,'Academic Year: '.$studentData['acad_year'],1,0,'R'); 
                	 $pdf->Ln();
                	 
                     $pdf->Cell(12,6,''); 
                     $pdf->Cell(20,6,'CODE',1); 
                     $pdf->Cell(80,6,'MODULE NAME',1); 		 
                     $pdf->Cell(15,6,'CREDITS',1); 
                     $pdf->Cell(20,6,'MARKS (%)',1); 
                     $pdf->Cell(12,6,'GRADE',1,0,'C');
                     $pdf->Cell(17,6,'DECISION',1,0,'C');
                	 $pdf->Ln();
                    
                    //Marks contents
                     $tcredits=0;
                     $tmarks=0;
                     $tcredpts=0;
                     $wavg=0;
                     $average=0;
                     $grade="";
                     $fcredits=0;
                     $rt=0;
                     $crt=0;
                     $exemp=0;
                     $cexemp=0;
                     $dec="";
                     
                     while ($studentMarks = $sqlMarks->fetch()){
                    	$pdf->SetFont('Arial','',8);
                    	$pdf->Cell(12,5,'');
                    	$pdf->Cell(20,5,$studentMarks['module_code'],1);
                    	$pdf->Cell(80,5,($studentMarks['enrolled']==2?'**':'').$studentMarks['module_name'],1);
                    	$pdf->Cell(15,5,$studentMarks['module_credits'],1,0,'C');
                    	$pdf->Cell(20,5,number_format($studentMarks['marks'], 1),1,0,'R'); 
                    	
                    	if($studentMarks['marks']>=60){
                        	$tcredits+=$studentMarks['module_credits'];
                        	$tmarks+=$studentMarks['marks'];
                        	$credpts=$studentMarks['marks']*$studentMarks['module_credits'];
                        	$tcredpts+=$credpts;
                    	}
                    	
                    	$getGrade2=$conn->prepare('SELECT grade_letter FROM '.$grade_table.' WHERE m_from<="'.$studentMarks['marks'].'" AND m_to>"'.$studentMarks['marks'].'"');
                    	$getGrade2->execute();
                    	$gradeData2=$getGrade2->fetch();
                    	$grade2=$gradeData2['grade_letter'];
                    	
                    	if($grade2=='F'){
                    	    $fcredits+=$studentMarks['module_credits'];
                    	}
        
                    	
                    	$pdf->Cell(12,5,$grade2,1,0,'C');
                    	if($grade2!='F'){ $prom = 'Pass';}else{$prom = 'Fail';}
                    	$pdf->Cell(17,5,$prom,1,0,'C');
                    	$pdf->Ln();
                    }	
                
                    //uncredited modules
                    $sqlMarks_u=$conn->prepare('SELECT 
                                                    DISTINCT(tbl_markby_module.module_id),
                                                    modules.module_code,
                                                    modules.module_name,
                                                    tbl_modules.module_credits,
                                                    tbl_markby_module.cat,
                                                    tbl_markby_module.final_exam,
                                                    tbl_markby_module.marks,
                                                    tbl_markby_module.enrolled
                                                FROM tbl_markby_module
                                                    INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                    INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                WHERE tbl_markby_module.reg_no="'.$reg_no.'" AND
                                                      tbl_modules.level_id="'.$level.'" AND
                                                      tbl_modules.credited_module=0 AND
                                                      tbl_markby_module.enrolled IN (1,2) AND 
                                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                      tbl_markby_module.marks IS NOT NULL');
                    $sqlMarks_u->execute();
                    if($sqlMarks_u->rowCount()){
                        $pdf->SetFont('Arial','I',8);
                        $pdf->Cell(12,6,''); 
                        $pdf->Cell(164,6,'uncredited modules',1); 
                        $pdf->Ln();
                        while ($studentMarks_u = $sqlMarks_u->fetch()){
                        	$pdf->SetFont('Arial','',8);
                        	$pdf->Cell(12,5,'');
                        	$pdf->Cell(20,5,$studentMarks_u['module_code'],1);
                        	$pdf->Cell(95,5,($studentMarks_u['enrolled']==2?'*':'').$studentMarks_u['module_name'],1);
                        	$pdf->Cell(20,5,number_format($studentMarks_u['marks'], 1),1,0,'R'); 
                        	
                        	$getGrade2=$conn->prepare('SELECT grade_letter FROM '.$grade_table.' WHERE m_from<="'.$studentMarks_u['marks'].'" AND m_to>"'.$studentMarks_u['marks'].'"');
                        	$getGrade2->execute();
                        	$gradeData2=$getGrade2->fetch();
                        	$grade2=$gradeData2['grade_letter'];
                        	
                        	if($grade2=='F'){
                        	    $fcredits+=$studentMarks['module_credits'];
                        	}
                        	
                        	$pdf->Cell(12,5,$grade2,1,0,'C');
                        	if($grade2!='F'){ $prom = 'Pass';}else{$prom = 'Fail';}
                        	$pdf->Cell(17,5,$prom,1,0,'C');
                        	$pdf->Ln();
                        }	
                    }
                    
                    $average=$tcredpts/($tcredits>0?$tcredits:1);
                    
                	$pdf->SetFont('Arial','B',8);
                	$pdf->Cell(12,5,'');
                	$pdf->Cell(100,5,'Total',1);
                	$pdf->Cell(15,5,$tcredits,1,0,'C');
                	$pdf->Cell(49,5,'',1,0,'R'); 
                	$pdf->Ln();
                	
                	$pdf->SetFont('Arial','',8);
                	$pdf->Cell(12,5,'');
                	$pdf->Cell(164,5,'NB: Grade Division  (Min. Marks - Max. Marks); A(80-100), B(70-79), C(60-69), D(50-59), F=Fail(0-49)',1);
                	$pdf->Ln();
                	
                	$pdf->SetFont('Arial','',8);
                	$pdf->Cell(12,5,'');
                	$pdf->Cell(164,5,'Note: ** Means Module with Transferred Credits',1);
                	$pdf->Ln();
        
                	if($average<50)$dec=4;
                	if($average>50 && $fcredits==0)$dec=1;
                	if($average>50 && $fcredits>0 && $fcredits<30)$dec=2;
                	if($average>50 && $fcredits>=30)$dec=3;
                	
                	switch($dec){
                		case 1: $decision="Promoted";
                				break;
                		case 2: $decision="Promoted - Retake Failed Modules";
                				break;
                		case 3: $decision="Repeat Year with Failed Modules";
                				break;
                		case 4: $decision="Repeat Year with All Modules";
                				break;
                		case 5: $decision="Failed in Retaken Modules - Subsidiary Qualification Awarded";
                				break;
                		default: $decision="N/A";
                				break;
                	}
                	
                	$grade_a = '';
        
                    $getGrade=$conn->prepare("SELECT grade_letter FROM ".$grade_table." WHERE m_from<='".floor($average)."' AND m_to>'".floor($average)."'");
                    try {
                        $getGrade->execute();
                        while ($grade = $getGrade->fetch()) {
                            $grade_a=$grade['grade_letter'];
                        }
                    }
                    catch (PDOException $ex) {
                        echo $ex->getMessage();
                    }
                	
                	$pdf->SetFont('Arial','B',8);
                	$pdf->Cell(12,6,''); 
                    $pdf->Cell(164,6,'Marks Percentage Average (MPA): '.number_format($average,2).' %',1,0,'L');
                    
                    $pdf->Ln();
                    $pdf->Cell(12,6,''); 
                    $pdf->Cell(164,6,"Classification: ".$decision,1,0,'L');
            	    $pdf->Ln(10);
            	    
                	$pdf->SetTextColor(0,0,0);
                	$pdf->SetFont('Arial','I',10);	 
                	$pdf->Cell(0,5,'Issued at NJARA on: '.date("d-M-Y",time()),0,1,'C');
                }
        	
            }catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    }

    page_footer($pdf, $data);
	$dname = "Transcripts";
	$pdf->Output($dname.'.pdf','I');
    
	exit;
    // ob_end_flush();
?>
