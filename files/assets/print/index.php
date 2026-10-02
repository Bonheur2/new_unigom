<?php
    ob_start(); 
    include ('../../../meet/con.php');
    include ('./functions.php');
    require('../../../fpdf/fpdf.php');
    $docname = '';
    function doc_header($pdf, $conn, $categ){
        global $docname;
        $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
        /**********************Logo**************************/
		$pdf->Image('../../..'.$data['logo'],20,10,40);
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
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(200,20,'ASSETS QR CODES',0,0,'C');
		$pdf->Ln(15);
		
        $stmt = $conn->prepare("SELECT 
                                        c.catg_name,
                                        c.specifications,
                                        cls.iclass_name, 
                                        c.catg_name , 
                                        i.item_name, 
                                        b.brand_name
                                    FROM tbl_categories c 
                                        INNER JOIN tbl_items i ON c.item_id = i.item_id
                                        INNER JOIN tbl_item_class cls ON i.iclass_id = cls.iclass_id
                                        LEFT JOIN tbl_brands b ON i.brand_id = b.brand_id
                                    WHERE c.catg_id = ?");
        $stmt->execute([$categ]);
        $data = $stmt->fetch();

        if($data['brand_name']==NULL){
            $brand = 'Unknown brand';
        }else{
            $brand = $data['brand_name'];
        }
        $pdf->Cell(190,8,$data['iclass_name'].' | '.$data['item_name'].' | '.$brand.' | '.$data['catg_name'],1,0,'C');
        $docname = $data['iclass_name'].'-'.$data['item_name'].'-'.$brand.'-'.$data['catg_name'];
    }
    
    $category = $_REQUEST['cat']; 
    
    $pdf=new FPDF();
    $pdf->addPage();
    doc_header($pdf, $conn, $category);
    $pdf->SetAuthor("STUMIS");
    $pdf->SetTitle('QR CODES');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetAutoPageBreak(false);
    
    $getAssets = $conn->prepare('SELECT * FROM tbl_assets WHERE catg_id = ? AND status = 1');
    $getAssets->execute([$category]);
    $assetCount = $getAssets->rowCount();
    
    $x=10;
    $y=70;
    $c=0;   
    
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.01);
    $pdf->SetFont('helvetica', '', 10);
    
    if($assetCount > 0){
        $cursor = 1;
        while($asset = $getAssets->fetch()){
            $qr_code = $asset['asset_key'].'.png';
            $pdf->Rect($x, $y, 40, 47, 'DF');
            $pdf->Image('../QR/'.$qr_code ,$x+1, $y+1, 38, 38);
            $pdf->setXY($x, $y+40);
            $pdf->cell(40, 8, strtoupper($asset['asset_no']), 0, 0, 'C');
            
            $cursor += 1;
            if($cursor < 5){
                $x += 50;
            }else{
                $x = 10;
                $y += 57;
                $cursor = 1;
            }
            
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
    // ini_set('log_errors', 1);
    ob_end_flush();
?>