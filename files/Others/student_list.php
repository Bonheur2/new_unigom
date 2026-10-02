<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    function doc_header($pdf, $conn){
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../..'.$data['logo'],20,10,30);
		$pdf->SetFont('Arial','B',15);
		/**********************Info-main**************************/
		$pdf->SetX(70);
		$pdf->SetFont('Arial','',13);
		$pdf->SetTextColor(55,24,60);
		$pdf->Cell(200,5,$data['full_name'],0,0,'R');
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
		$pdf->Cell(280,20,'STUDENT LIST',0,0,'C');
		$pdf->Ln(15);
    }

    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('STUDENT LIST');
    
    $pdf->AliasNbPages();
    $pdf->AddPage('L');
    doc_header($pdf, $conn);
    
    $prg_type =urldecode($_REQUEST['prg_type'] ?? '');
    $fac_id = urldecode($_REQUEST['fac_id'] ?? '');
    $dept_id =urldecode($_REQUEST['dept_id'] ?? '');
    $splz_id = urldecode($_REQUEST['splz_id'] ?? '');
    $level_id =urldecode($_REQUEST['level_id'] ?? '');
    $acad_cycle_id = urldecode($_REQUEST['acad_cycle_id'] ?? '');
    $filename = urldecode($_GET['filename'] ?? 'Student_List.pdf');
    $download = isset($_GET['download']) ? true : false;

    $getClassData=$conn->prepare("SELECT 
    c.camp_full_name,
    p.prg_type_full_name,
    f.fac_full_name,
    d.dept_full_name,
    s.splz_full_name
FROM tbl_specialization s
INNER JOIN tbl_department d ON s.dept_id = d.dept_id
INNER JOIN tbl_faculty f ON s.fac_id = f.fac_id
INNER JOIN tbl_program_type p ON s.prg_type = p.prg_type_id
INNER JOIN tbl_campus c ON p.campus_id = c.camp_id
WHERE s.splz_id = '".$splz_id."'
");
    $getClassData->execute();
    $class_data = $getClassData->fetch();
    
    $select_level="SELECT * FROM tbl_level WHERE level_id='$level_id'";
    $cselect_level=$conn->prepare($select_level);
    $cselect_level->execute();
    $row_cselect_level=$cselect_level->fetch(PDO::FETCH_ASSOC);
    
    $getIntakeData=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    $getIntakeData->execute([$acad_cycle_id]);
    $intake_data = $getIntakeData->fetch();
    
    
    $pdf->SetFont('Times','',12);
    $pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Campus: '.$class_data['camp_full_name']); 
	$pdf->Ln();
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
	$pdf->Cell(124,6,'Academic Year: '.$intake_data['acad_year']);
    $pdf->Ln();
    $pdf->Cell(12,6,'');
	$pdf->Cell(124,6,'Level: '.$row_cselect_level['level_full_name']);
    $pdf->Ln();

	//marks start
     $pdf->SetFont('Arial','B',8);
     $pdf->Ln(10);
     
	 
    $pdf->Cell(12,6,''); 
    $pdf->Cell(12,6,'S/N',1); 
    $pdf->Cell(70,6,'Student ID',1); 
    $pdf->Cell(120,6,'NAME',1); 		 
    
    $pdf->Ln();
    $getStudents = $conn->prepare("SELECT tbl_register_program_ug.*,tbl_admission.* FROM tbl_register_program_ug INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
    WHERE tbl_register_program_ug.acad_cycle_id='$acad_cycle_id' AND tbl_register_program_ug.splz_id='$splz_id' AND tbl_register_program_ug.level_id='$level_id' AND tbl_register_program_ug.prg_type='$prg_type' AND tbl_register_program_ug.fac_id='$fac_id' AND tbl_register_program_ug.dept_id='$dept_id'");
                                            
        $pdf->SetFont('Times','',11);
        try {
            $getStudents->execute();
            $i=1;
            while($student=$getStudents->fetch()){
            	$pdf->Cell(12,7,'');
            	$pdf->Cell(12,7,$i++,1);
            	$pdf->Cell(70,7,$student['reg_no'],1);
            	$pdf->Cell(120,7,$student['fname']." ".$student['lname'],1);
            	
            	$pdf->Ln();
            }	
    	
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
	
	header('Content-Type: application/pdf');
if ($download) {
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $pdf->Output('D', $filename);
} else {
    header("Content-Disposition: inline; filename=\"$filename\"");
    $pdf->Output('I', $filename);
}

	exit;
    ob_end_flush();
?>
