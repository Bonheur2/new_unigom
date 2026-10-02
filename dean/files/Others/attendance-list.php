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
		$pdf->Cell(200,5,'',0,0,'R');
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
		$pdf->Cell(280,20,'ATTENDANCE LIST',0,0,'C');
		$pdf->Ln(15);
    }

    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('ATTENDANCE LIST');
    
    $pdf->AliasNbPages();
    $pdf->AddPage('L');
    doc_header($pdf, $conn);
    
    $splz =$_REQUEST['splz'];
    $intake = $_REQUEST['intk'];
    $module = $_REQUEST['modl'];
    $mode = $_REQUEST['md'];

    $getClassData=$conn->prepare("SELECT 
                                        c.camp_full_name,
                                        p.prg_type_full_name,
                                        f.fac_full_name,
                                        d.dept_full_name,
                                        s.splz_full_name,
                                        m.module_code,
                                        m.module_name
                                        
                                    FROM tbl_specialization s
                                        INNER JOIN tbl_department d ON 
                                            s.dept_id=d.dept_id
                                        INNER JOIN tbl_faculty f ON 
                                            s.fac_id=f.fac_id
                                        INNER JOIN tbl_program_type p ON 
                                            s.prg_type=p.prg_type_id
                                        INNER JOIN tbl_campus c ON 
                                            p.campus_id=c.camp_id
                                        INNER JOIN tbl_modules tm ON 
                                            s.splz_id=tm.splz_id AND tm.module_id='".$module."'
                                        INNER JOIN modules m ON 
                                            tm.mod_id=m.module_id
                                    WHERE 
                                    s.splz_id='".$splz."'");
    $getClassData->execute();
    $class_data = $getClassData->fetch();
    
    
    $pdf->SetFont('Times','',12);
	$pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Faculty: '.$class_data['fac_full_name']); 
	$pdf->Ln();
    $pdf->Cell(12,6,''); 
 	$pdf->Cell(124,6,'Department: '.$class_data['dept_full_name']);
    $pdf->Ln();
  	$pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Specialization: '.$class_data['splz_full_name']);
    $pdf->Ln();
  	$pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Module Code: '.$class_data['module_code']);
    $pdf->Ln();
  	$pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Module Name: '.$class_data['module_name']);
    $pdf->Ln();

	//marks start
     $pdf->SetFont('Arial','B',8);
     $pdf->Ln(10);
     
     
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(122,6,'',0);
	 $pdf->Cell(130,6,'SESSION',1,0,'C'); 
	 $pdf->Ln();
	 
    $pdf->Cell(12,6,''); 
    $pdf->Cell(12,6,'S/N',1); 
    $pdf->Cell(30,6,'Student ID',1); 
    $pdf->Cell(80,6,'NAME',1); 		 
    $pdf->Cell(13,6,'1',1,0,'C'); 
    $pdf->Cell(13,6,'2',1,0,'C'); 
    $pdf->Cell(13,6,'3',1,0,'C');
    $pdf->Cell(13,6,'4',1,0,'C');
    $pdf->Cell(13,6,'5',1,0,'C');
    $pdf->Cell(13,6,'6',1,0,'C');
    $pdf->Cell(13,6,'7',1,0,'C');
    $pdf->Cell(13,6,'8',1,0,'C');
    $pdf->Cell(13,6,'9',1,0,'C');
    $pdf->Cell(13,6,'10',1,0,'C');
    $pdf->Ln();
    $getStudents = $conn->prepare("SELECT 
                                        DISTINCT(tbl_register_program_ug.reg_no),
                                        tbl_admission.fname,
                                        tbl_admission.lname
                                            FROM tbl_markby_module
                                        INNER JOIN tbl_register_program_ug ON tbl_markby_module.reg_no=tbl_register_program_ug.reg_no
                                        INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                                            WHERE tbl_markby_module.module_id='".$module."' 
                                                AND (tbl_register_program_ug.intake_id='".$intake."' OR (tbl_markby_module.status=1 AND tbl_markby_module.module_id='".$module."'))
                                                AND tbl_register_program_ug.splz_id='".$splz."' 
                                                AND tbl_register_program_ug.prg_mode_id='".$mode."'
                                                AND tbl_register_program_ug.reg_active=1
                                                AND tbl_markby_module.intake_id='".$intake."'
                                                AND tbl_markby_module.enrolled=1
                                                AND tbl_markby_module.status=1
                                            ");
                                            
        $pdf->SetFont('Times','',11);
        try {
            $getStudents->execute();
            $i=1;
            while($student=$getStudents->fetch()){
            	$pdf->Cell(12,7,'');
            	$pdf->Cell(12,7,$i++,1);
            	$pdf->Cell(30,7,$student['reg_no'],1);
            	$pdf->Cell(80,7,$student['fname']." ".$student['lname'],1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1); 
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1); 
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Cell(13,7,'',1);
            	$pdf->Ln();
            }	
    	
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
	$dname=$reg_no.'_L'.$level;
	$pdf->Output($dname.'.pdf','I');

	exit;
    ob_end_flush();
?>
