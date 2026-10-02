<?php
    ob_start(); 
    include ('../../../meet/con.php');
    include ('./functions.php');
    require('../../../fpdf/fpdf.php');
    $docname = '';
    function doc_header($pdf, $conn, $class){
        global $docname;
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../../..'.$data['logo'],20,10,40);
		$pdf->SetFont('Arial','B',15);
		/**********************Info-main**************************/
		$pdf->SetX(150);
		$pdf->SetFont('Arial','',13);
		$pdf->SetTextColor(55,24,60);
		$pdf->Cell(120,5,$data['full_name'],0,0,'R');
		$pdf->Ln(6);
		$pdf->SetX(150);
		$pdf->Cell(120,5,'',0,0,'R');
		$pdf->Ln(6);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(150);
		$pdf->Cell(120,5,$data['po_box'],0,0,'R');
		$pdf->Ln(5);
		/*********************Contact***************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(150);
		$pdf->Cell(120,5,$data['phone'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(150);
		/*********************Web&email***************************/
		$pdf->SetTextColor(53,75,136);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(150);
		$pdf->Cell(120,5,$data['email'],0,0,'R');
		$pdf->Ln(5);
		$pdf->SetX(150);
		$pdf->Cell(120,5,$data['website'],0,0,'R');
		$pdf->Ln(5);
	    /********************Title****************************/
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(270,20,'ASSETS List',0,0,'C');
		$pdf->Ln(15);
		
        $stmt = $conn->prepare("SELECT iclass_name FROM tbl_item_class WHERE iclass_id = ? AND status = 1");
        $stmt->execute([$class]);
        $data = $stmt->fetch();

        $pdf->Cell(270,8,$data['iclass_name'],1,0,'C');
        $pdf->Ln();
        $docname = $data['iclass_name'].'-'.date('dyn');
    }
    
    $class = $_REQUEST['cls']; 
    
    $pdf=new FPDF();
    $pdf->addPage("L");
    doc_header($pdf, $conn, $class);
    $pdf->SetAuthor("STUMIS");
    $pdf->SetTitle('ASSETS');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetAutoPageBreak(false);
    
    $getAssets = $conn->prepare('SELECT * FROM tbl_assets WHERE iclass_id = ? AND status = 1');
    $getAssets->execute([$class]);
    $assetCount = $getAssets->rowCount();
    
    $x=10;
    $y=70;
    $c=0;   
    
    $pdf->SetFont('helvetica', '', 10);
    
    if($assetCount > 0){
        $pdf->cell(20, 8, "S/N", 1, 0);
        $pdf->cell(40, 8, "ASSET No", 1, 0);
        $pdf->cell(70, 8, "ITEM", 1, 0);
        $pdf->cell(70, 8, "CATEGORY", 1, 0);
        $pdf->cell(70, 8, "COMMENT", 1, 0);
        $pdf->Ln();
        
        $i = 1;
        while($asset = $getAssets->fetch()){
            $pdf->cell(20, 10, $i++, 1, 0, 'C');
            $pdf->cell(40, 10, strtoupper($asset['asset_no']), 1, 0);
            $pdf->cell(70, 10, strtoupper($asset['asset_no']), 1, 0);
            $pdf->cell(70, 10, strtoupper($asset['asset_no']), 1, 0);
            $pdf->cell(70, 10, '', 1, 0, 'C');
            $pdf->Ln();
            
            if($y>=260 && $c<$assetCount){
                $pdf->addPage();
                $x=10;
                $y=10;
            }
        }
    } else{
        echo "Not Found!";
    }
    
	$dname = $docname;
	$pdf->Output($dname.'.pdf','I');

    exit;
    ob_end_flush();
?>