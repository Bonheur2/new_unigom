<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    ini_set('memory_limit', '-1');
    
    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../..'.$data['logo'],10,10,30);
		$pdf->SetFont('Arial','B',15);
		/**********************Info-main**************************/
		$pdf->SetX(70);
		$pdf->SetFont('Arial','',13);
		$pdf->SetTextColor(55,24,60);
		$pdf->Cell(130,5,$data['full_name'],0,0,'R');
		$pdf->Ln(6);
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		
		$pdf->SetX(70);
		$pdf->Cell(130,5,$data['po_box'],0,0,'R');
		$pdf->Ln(5);
		/*********************Contact***************************/
		
		$pdf->SetX(70);
		$pdf->Cell(130,5,$data['phone'],0,0,'R');
		$pdf->Ln(5);

		/*********************Web&email***************************/
		$pdf->SetTextColor(53,75,136);

		$pdf->SetX(70);
		$pdf->Cell(130,5,$data['email'],0,0,'R');
		$pdf->Ln(5);
		
		$pdf->SetX(70);
		$pdf->Cell(130,5,$data['website'],0,0,'R');
		$pdf->Ln(5);
	    /********************Title****************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','BU',13);
		$pdf->Cell(200,20,'ACADEMIC TRANSCRIPT',0,0,'C');
		$pdf->Ln(15);
    }
    //Page footer
    function page_footer($pdf, $data){
    	$pdf->SetY(-51);
        	
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','I',8);	 
    	$pdf->Cell(0,5,'Issued at NJARA on: '.date("d-M-Y",time()),0,1,'C');
            	
        $pdf->SetY(-35);
	    $pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','B',11);	
    	$pdf->Cell(12,5,'',0);
    	$pdf->Cell(57,5,'Academic Registrar',0,0,'L');
    // 	$pdf->Cell(110,5,'Deputy Principal Academic Affairs and Research',0,0,'R');
    	
    	$pdf->SetY(-28);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','I',8);	 
    	$pdf->Cell(0,5,'*This transcript is issued without any erasures or alterations and is valid only with the official stamp',0,1,'C');
    	
        $pdf->SetY(-27);
    	$pdf->Cell(0,4,'_______________________________________________________________________________________________________',0,0,'C');
    }
    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->setFillColor(255, 255, 255);
    $pdf->SetTitle('Transcript');
    $pdf->SetAutoPageBreak(false);
    $pdf->AliasNbPages();
    
    $splz = trim($_REQUEST['splz']);
    $acad_cycle_id = trim($_REQUEST['academic']);
    
    $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
    $sql->execute();
    $data=$sql->fetch();
    
    $stmt = $conn->prepare("SELECT DiSTINCT(reg_no) FROM tbl_register_program_ug WHERE acad_cycle_id = ? AND splz_id = ? AND reg_active IN (1, 6) ORDER BY reg_no ASC");
    $stmt->execute([$acad_cycle_id, $splz]);
    
    while($stud = $stmt->fetch()){
        $reg_no = $stud['reg_no'];
        
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
                                tbl_specialization.degree_name,
                                tbl_level.level_full_name,
                                tbl_graduants.classification,
                                tbl_graduants.cum_marks
                            FROM tbl_register_program_ug
                                INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                                INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                                INNER JOIN tbl_department ON tbl_register_program_ug.dept_id=tbl_department.dept_id
                                INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id 
                                INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                INNER JOIN tbl_graduants ON tbl_register_program_ug.reg_no=tbl_graduants.reg_no
                            WHERE tbl_register_program_ug.reg_no="'.trim($reg_no).'" ORDER BY tbl_register_program_ug.level_id DESC LIMIT 1');
        
        $basic->execute();
        if($basic->rowCount()!=0){
            try {
                $getDocuments=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE upload_doc like '%photo_%' AND tracking_id='".trim($reg_no)."'");
                $getDocuments->execute();
                if($getDocuments->rowCount()>0){
                    $documents=$getDocuments->fetch();
                    $stuImg=$documents['upload_doc'];
                }else{
                    $stuImg="404.png";
                }
                
                $studentData=$basic->fetch();
                $prg_type = $studentData['prg_type'];
                
                if($studentData['prg_type']==2){
            	    $grade_table = 'tbl_gradeP';
            	}else{
            	    $grade_table = 'tbl_grade';
            	}
                //add student info
                 $pdf->AliasNbPages();
                 $pdf->AddPage();
                 doc_header($pdf, $conn);
                 page_footer($pdf, $data);
                 $pdf->Rect(170, 50, 30, 30, 'D');
                 
                 $imagePath = '../../' . $stuImg;
                 if (file_exists($imagePath) && $stuImg != '') {
                     $pdf->Image($imagePath, 170.5, 50.5, 29, 29);
                 }
                 $pdf->SetY(50);
                 $pdf->SetFont('Arial','B',9);
            	 $pdf->Cell(124,5,'Registration Number: '.strtoupper($reg_no)); 
             	 $pdf->Ln();
            	 $pdf->Cell(124,5,'Family Name: '.strtoupper($studentData['lname'])); 
            	 $pdf->Ln();
            	 $pdf->Cell(124,5,'First Name: '.strtoupper($studentData['fname']));
            	 $pdf->Ln();
            	 $pdf->Cell(124,5,'Faculty: '.$studentData['fac_full_name']); 
            	 $pdf->Ln();
            	 $pdf->Cell(124,5,'Specialization: '.$studentData['degree_name']);
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
                                                    WHERE reg_no="'.trim($reg_no).'"');
                 $sql->execute();
                
                // set initial column position
                $colX = 10;
                $colY = 82;
                $prevColY = $colY;
                $tracker = 0;
                $levels = $sql->rowCount();
                while($levelData=$sql->fetch()) {
                    //retrieve student marks
                    $sqlMarks=$conn->prepare('SELECT 
                                                    DISTINCT(tbl_markby_module.module_id),
                                                    modules.module_code,
                                                    modules.module_name,
                                                    tbl_modules.credited_module,
                                                    tbl_modules.module_credits,
                                                    tbl_modules.is_project,
                                                    tbl_markby_module.marks,
                                                    tbl_markby_module.enrolled
                                                FROM tbl_markby_module
                                                    INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                    INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                WHERE tbl_markby_module.reg_no="'.trim($reg_no).'" AND
                                                          tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                          tbl_modules.credited_module=1 AND
                                                          tbl_markby_module.enrolled IN (1,2) AND 
                                                          (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                          tbl_markby_module.marks IS NOT NULL');
                    $sqlMarks->execute();
                    if($sqlMarks->rowCount() == 0){
                        continue;
                    }
                    $tracker++;
    
                    $pdf->SetFont('Arial','B',7);
                    $pdf->SetXY($colX, $colY + 2);
                    $pdf->Cell(50, 4, $levelData['level_full_name'],1, 0,'L');
                    $pdf->Cell(40, 4, 'Academic Year: '.$levelData['acad_year'], 1,0,'R');
                    $pdf->Ln();
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetX($colX);
                    $pdf->Cell(25,4,'CODE',1); 
                    $pdf->Cell(25,4,'CREDITS',1); 
                    $pdf->Cell(20,4,'MARKS (%)',1); 
                    $pdf->Cell(10,4,'GRADE',1,0,'C');
                    $pdf->Cell(10,4,'GP',1,0,'C');
                    $pdf->Ln();
                    
                    $credits=0;
                    $tcredits=0;
                    $tcreditpts=0;
                    $grade="";
                    
                    $colY += 8;
                    
                    while($studentMarks=$sqlMarks->fetch()){
                        $marks=$studentMarks['marks'];
                        if($marks>=60){
                            $tcredits+=$studentMarks['module_credits'];
                            $tcreditpts+=$marks*$studentMarks['module_credits'];
                        }       
                        // SELECT GRADE
                        $sqlgrade=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks.'" and m_to>"'.$marks.'"');
                        $sqlgrade->execute();
                        $resgrade=$sqlgrade->fetch();
                        $grade=$resgrade['grade_letter'];
                        
                        if($grade=="F"){
                            $failed_credits+=$studentMarks['module_credits'];
                        };
                        
                        if ($grade=="A") {
                            $gp = 5;
                        } elseif ($grade=="B") {
                            $gp = 4;
                        } elseif ($grade=="C") {
                            $gp = 3;
                        } elseif ($grade=="D") {
                            $gp = 2;
                        } else {
                            $gp = 0;
                        }
    
                        //write marks
                    	$pdf->SetFont('Arial','',7);
                    	$pdf->SetX($colX);
                    	$pdf->Cell(25,4,($studentMarks['enrolled']==2?'** ':'').$studentMarks['module_code'],1);
                    	$pdf->Cell(25,4,$studentMarks['module_credits'],1,0,'C');
                    	$pdf->Cell(20,4,number_format($studentMarks['marks'], 1),1,0,'C');
                    	$pdf->Cell(10,4,$grade,1,0,'C');
                    	$pdf->Cell(10,4,$gp,1,0,'C');
                    	$colY += 4;
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
                                                WHERE tbl_markby_module.reg_no="'.trim($reg_no).'" AND
                                                          tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                          tbl_modules.credited_module=0 AND
                                                          tbl_markby_module.enrolled IN (1,2) AND 
                                                          (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                          tbl_markby_module.marks IS NOT NULL');
                    $sqlMarks_uncredited->execute();
                    $grade2="";
                    if($sqlMarks_uncredited->rowCount()){
                        $pdf->SetX($colX);
                        $pdf->SetFont('Arial','I',7);
                        $pdf->Cell(90,5,'Uncredited modules',1);
                        $colY += 5;
                        $pdf->Ln();
                        while($studentMarks2=$sqlMarks_uncredited->fetch()){
                            $marks2=$studentMarks2['marks'];
                            // SELECT GRADE
                            $sqlgrade2=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks2.'" and m_to>"'.$marks2.'"');
                            $sqlgrade2->execute();
                            $resgrade2=$sqlgrade2->fetch();
                            
                            $grade2=$resgrade2['grade_letter'];
                            
                            if ($grade2=="A") {
                                $gp = 5;
                            } elseif ($grade2=="B") {
                                $gp = 4;
                            } elseif ($grade2=="C") {
                                $gp = 3;
                            } elseif ($grade2=="D") {
                                $gp = 2;
                            } else {
                                $gp = 0;
                            }
                            //write marks
                        	$pdf->SetFont('Arial','',7);
                        	$pdf->SetX($colX);
                        	$pdf->Cell(25,4,($studentMarks2['enrolled']==2?'** ':'').$studentMarks2['module_code'],1);
                        	$pdf->Cell(25,4,'',1,0,'C');
                        	$pdf->Cell(20,4,number_format($studentMarks2['marks'], 1),1,0,'C');
                        	$pdf->Cell(10,4,$grade2,1,0,'C');
                        	$pdf->Cell(10,4,$gp,1,0,'C');
                        	$colY += 4;
                        	$pdf->Ln();
                        }
                    }
                    
                    $average=$tcreditpts/($tcredits==0?1:$tcredits);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->SetX($colX);
                	$pdf->Cell(50,4,'Total credits',1,0,'L');
                	$pdf->Cell(40,4,$tcredits,1,0,'C');
                	$pdf->Ln();
                	$pdf->SetX($colX);
                	$pdf->Cell(50,4,'Annual Average',1,0,'L');
                	$pdf->Cell(40,4,number_format($average,1)." %",1,0,'C');
                	$pdf->Ln();
                	
                	$colY += 8;
                	
                	$total_credits+=$tcredits;
                	$total_credit_pts+=$tcreditpts;
                	
                	if($tracker % 2 == 0){
                	    $colX = 10;
                	    $colY += 12;
                	} else{
                	    $colX += 100;
                	    
                	    if($tracker == 1 && $levels != 1){
                	        $prevColY = $colY;
                	        $colY = 82;
                	    } else if($tracker == 3){
                	        $colY = $prevColY + 4;
                	    }else{
                	        $colY = $colY;
                	    }
                	}
                }
                
                #################### Classification ###########################
                
                if($prg_type == 1){
                    $honours = ", with Honours";
                } else {
                    $honours = "";
                    
                }
                
                $class = $studentData['classification'];
                $cavg = $studentData['cum_marks'];
                
                #################### Classification ###########################
                
                if($levels == 1){
                    $pdf->Ln();
                    $colX = 10;
                    $pdf->SetFillColor(255, 255, 100);
                    $pdf->Rect($colX, $pdf->GetY(), 90, 10, 'DF');
                    $pdf->SetFont('Arial','B',7);
                    
                    $pdf->SetX($colX);
                    $pdf->Cell(45,5,'Total Credits: '.$total_credits,1); 
                    $pdf->Cell(45,5,'Cumulative Average: '.number_format($cavg, 1)." %",1); 
                    $pdf->Ln();
                    $pdf->SetX($colX);
                    $pdf->Cell(90,5,'Classification: '.$class,1); 
                    $pdf->Ln();
                }else if($levels == 2){
                    $colX = 110;
            	    $pdf->SetY($colY-5);
                    $pdf->SetFillColor(255, 255, 100);
                    $pdf->Rect($colX, $colY-5, 90, 10, 'DF');
                    $pdf->SetFont('Arial','B',7);
                    
                    $pdf->SetX($colX);
                    $pdf->Cell(45,5,'Total Credits: '.$total_credits,1); 
                    $pdf->Cell(45,5,'Cumulative Average: '.number_format($cavg,1)." %",1); 
                    $pdf->Ln();
                    $pdf->SetX($colX);
                    $pdf->Cell(90,5,'Classification: '.$class,1); 
                    $pdf->Ln();
                }else if($levels == 3){
                    $colX = 110;
            	    $pdf->SetY($colY+9);
                    $pdf->SetFillColor(255, 255, 100);
                    $pdf->Rect($colX, $colY+9, 90, 10, 'DF');
                    $pdf->SetFont('Arial','B',7);
                    
                    $pdf->SetX($colX);
                    $pdf->Cell(45,5,'Total Credits: '.$total_credits,1); 
                    $pdf->Cell(45,5,'Cumulative Average: '.number_format($cavg,1)." %",1); 
                    $pdf->Ln();
                    $pdf->SetX($colX);
                    $pdf->Cell(90,5,'Classification: '.$class,1,0); 
                    $pdf->Ln();
                } else if($levels == 4){
                    $colX = 110;
            	    $pdf->SetY($colY-5);
                    $pdf->SetFillColor(255, 255, 100);
                    $pdf->Rect($colX, $colY-5, 90, 8, 'DF');
                    $pdf->SetFont('Arial','B',7);
                    
                    $pdf->SetX($colX);
                    $pdf->Cell(45,4,'Total Credits: '.$total_credits,1); 
                    $pdf->Cell(45,4,'Cumulative Average: '.number_format($cavg,1)." %",1); 
                    $pdf->Ln();
                    $pdf->SetX($colX);
                    $pdf->Cell(90,4,'Classification: '.$class,1,0); 
                    $pdf->Ln();
                }
                
               
                ################ Module Coding ######################
                $sqlModules = $conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.trim($reg_no).'" AND
                                                      tbl_markby_module.enrolled IN (1,2) AND 
                                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                      tbl_markby_module.marks IS NOT NULL');
                $sqlModules->execute();
                
                if($levels == 1){ 
            	    $pdf->SetY(84);
            	    $colX += 100;
            	    
            	    $pdf->SetX($colX + 2);
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(90, 4, 'Module Coding',1, 0,'L');
                    $pdf->Ln();
                    $pdf->SetX($colX + 2);
                    $pdf->Cell(25,4,"CODE",1);
                    $pdf->Cell(65,4,"MODULE NAME",1);
                    $pdf->Ln();
                    
                    $pdf->SetFont('Arial','',7);
                    while($modules = $sqlModules->fetch()){
                        $pdf->SetX($colX + 2);
                        $pdf->Cell(25,4,$modules['module_code'],1);
                        $pdf->Cell(65,4,$modules['module_name'],1);
                        $pdf->Ln();
                    }
                    
                	$pdf->SetX($colX + 2);
                	$pdf->MultiCell(90,4,'Note: ** Means Module with Transferred Credits',1);
                	$pdf->Ln(5);
                	
                    ############# GP Legend #############
                    $sqlgrade=$conn->prepare('select * from '.$grade_table.'');
                    $sqlgrade->execute();
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetX($colX + 2);
                    $pdf->Cell(40,4,"Grading Scale",1);
                    $pdf->Ln();
                    $pdf->SetX($colX + 2);
                    $pdf->Cell(20,4,"Marks",1);
                    $pdf->Cell(10,4,"Grade",1);
                    $pdf->Cell(10,4,"GP",1);
                    $pdf->Ln();
                    
                    while($resgrade = $sqlgrade->fetch()){
                        $grade = $resgrade['grade_letter'];
                        if ($grade == "A") {
                            $gp = 5;
                        } elseif ($grade == "B") {
                            $gp = 4;
                        } elseif ($grade == "C") {
                            $gp = 3;
                        } elseif ($grade == "D") {
                            $gp = 2;
                        } else {
                            $gp = 0;
                        }
                        
                    	$pdf->SetFont('Arial','',7);
                        $pdf->SetX($colX + 2);
                        $pdf->Cell(20,4,$resgrade['m_from']." - ".($resgrade['m_to'] - 1),1);
                        $pdf->Cell(10,4,$grade,1);
                        $pdf->Cell(10,4,$gp,1);
                        $pdf->Ln();
                    }
                    
                    ############# GP Legend #############
                    
                } else if($levels == 2){
    
                    //get level data
                     $sql=$conn->prepare('SELECT DISTINCT(tbl_register_program_ug.level_id),
                                                        tbl_level.level_full_name 
                                                    FROM tbl_register_program_ug
                                                        INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                                        WHERE reg_no="'.trim($reg_no).'"');
                     $sql->execute();
                    
                    // set initial column position
                    $colX = 10;
                    $colY = 160;
                    $colYF = 160;
                    $prevColY = $colY;
                    $tracker = 0;
                    
                    $pdf->SetX($colX);
                    $pdf->SetY($prevColY);
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(190, 6, 'Module Coding',1, 0,'C');
                    $pdf->Ln();                
                    $colY += 6;
                    while($levelData=$sql->fetch()) {
                        //retrieve Modules
                        $sqlModules=$conn->prepare('SELECT 
                                                        DISTINCT(tbl_markby_module.module_id),
                                                        modules.module_code,
                                                        modules.module_name
                                                    FROM tbl_markby_module
                                                        INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                        INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                    WHERE tbl_markby_module.reg_no="'.trim($reg_no).'" AND
                                                              tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                              tbl_markby_module.enrolled IN (1,2) AND 
                                                              (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                              tbl_markby_module.marks IS NOT NULL');
                        $sqlModules->execute();
                        if($sqlModules->rowCount() == 0){
                            continue;
                        }
                        
                        $tracker++;
    
                        $pdf->SetFont('Arial','B',7);
                        $pdf->SetXY($colX, $colYF + 8);
                        $pdf->Cell(90, 5, $levelData['level_full_name'],1, 0,'L');
                        $pdf->Ln();
                        
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetX($colX);
                        $pdf->Cell(20,3,"CODE",1);
                        $pdf->Cell(70,3,"MODULE NAME",1);
                        $pdf->Ln();
                        
                        $colY += 8;
                        
                        while($modules=$sqlModules->fetch()){
                        	$pdf->SetFont('Arial','',7);
                        	$pdf->SetX($colX);
                        	$pdf->Cell(20,4,$modules['module_code'],1);
                        	$pdf->Cell(70,4,$modules['module_name'],1);
                        	$colY += 5;
                        	$pdf->Ln();
                        }
                    	
                    	if($tracker % 2 == 0){
                    	    $colX = 10;
                    	} else{
                    	    $colX += 100;
                    	    
                    	    if($tracker == 1){
                    	        $prevColY = $colY;
                    	        $colY = 20;
                    	    } else if($tracker == 3){
                    	        $colY = $prevColY;
                    	    }else{
                    	        $colY = $colY;
                    	    }
                    	}
                    }
                    
    
                	$pdf->SetFont('Arial','',8);
                	$pdf->SetX(110);
                	$pdf->MultiCell(90,4,'Note: ** Means Module with Transferred Credits',1);
                	$pdf->Ln(10);
                	
                    ############# GP Legend #############
                    $sqlgrade=$conn->prepare('select * from '.$grade_table.'');
                    $sqlgrade->execute();
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetX($colX);
                    $pdf->Cell(40,4,"Grading Scale",1);
                    
                    while($resgrade = $sqlgrade->fetch()){
                        $grade = $resgrade['grade_letter'];
                        if ($grade == "A") {
                            $gp = 5;
                        } elseif ($grade == "B") {
                            $gp = 4;
                        } elseif ($grade == "C") {
                            $gp = 3;
                        } elseif ($grade == "D") {
                            $gp = 2;
                        } else {
                            $gp = 0;
                        }
                        
                    	$pdf->SetFont('Arial','',7);
                        $pdf->Cell(30,4,$resgrade['m_from']." - ".($resgrade['m_to'] - 1).' => '.$grade.'  |  GP: '.$gp,1);
                    }
                    
                    ############# GP Legend #############
                    
                    
                } else if($levels == 3){
                    $pdf->Ln(8);
                    ############# GP Legend #############
                    $sqlgrade=$conn->prepare('select * from '.$grade_table.'');
                    $sqlgrade->execute();
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetX($colX );
                    $pdf->Cell(40,4,"Grading Scale",1);
                    $pdf->Ln();
                    $pdf->SetX($colX);
                    $pdf->Cell(20,4,"Marks",1);
                    $pdf->Cell(10,4,"Grade",1);
                    $pdf->Cell(10,4,"GP",1);
                    $pdf->Ln();
                    
                    while($resgrade = $sqlgrade->fetch()){
                        $grade = $resgrade['grade_letter'];
                        if ($grade == "A") {
                            $gp = 5;
                        } elseif ($grade == "B") {
                            $gp = 4;
                        } elseif ($grade == "C") {
                            $gp = 3;
                        } elseif ($grade == "D") {
                            $gp = 2;
                        } else {
                            $gp = 0;
                        }
                        
                    	$pdf->SetFont('Arial','',7);
                        $pdf->SetX($colX);
                        $pdf->Cell(20,4,$resgrade['m_from']." - ".($resgrade['m_to'] - 1),1);
                        $pdf->Cell(10,4,$grade,1);
                        $pdf->Cell(10,4,$gp,1);
                        $pdf->Ln();
                    }
                    
                    ############# GP Legend #############
                	
                }else {
                    $pdf->Ln(5);
                    
                    ############# GP Legend #############
                    $sqlgrade=$conn->prepare('select * from '.$grade_table.'');
                    $sqlgrade->execute();
                    
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(40,4,"Grading Scale",1);
                    
                    while($resgrade = $sqlgrade->fetch()){
                        $grade = $resgrade['grade_letter'];
                        if ($grade == "A") {
                            $gp = 5;
                        } elseif ($grade == "B") {
                            $gp = 4;
                        } elseif ($grade == "C") {
                            $gp = 3;
                        } elseif ($grade == "D") {
                            $gp = 2;
                        } else {
                            $gp = 0;
                        }
                        
                    	$pdf->SetFont('Arial','',7);
                        $pdf->Cell(30,4,$resgrade['m_from']." - ".($resgrade['m_to'] - 1).' => '.$grade.'  |  GP: '.$gp,1);
                    }
                    
                    ############# GP Legend #############
                }
               
               
            }catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    }
    
	$dname = "Transcripts";
	$pdf->Output($dname.'.pdf','I');

    exit;
    ob_end_flush();
?>
