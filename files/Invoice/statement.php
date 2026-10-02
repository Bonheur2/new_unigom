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
		$pdf->Cell(200,20,'Finance Statement',0,0,'C');
		$pdf->Ln(15);
    }
    //Page footer
    function page_footer($pdf, $data){
        $pdf->SetY(-35);
	    $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont('Arial', 'B', 10);	
	  
    	$pdf->SetFont('Arial', 'B', 11);	
    	$pdf->Cell(57, 5, 'Director of Finace', 0, 1, 'C');

    	$pdf->SetY(-22);
    	$pdf->SetTextColor(0, 0, 0);
    	$pdf->SetFont('Arial', 'I', 10);	 
    	$pdf->Cell(0, 5, 'Issued at KIGALI on: '.date("d-M-Y",time()), 0, 1, 'C');
    }
    
    
    $pdf=new FPDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Statement');
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
                            r.reg_no,
                            ad.fname,
                            ad.lname,
                            p.prg_type_full_name,
                            f.fac_full_name,
                            d.dept_full_name,
                            s.splz_full_name
                        FROM tbl_register_program_ug r
                            INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                            INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                            INNER JOIN tbl_faculty f ON r.fac_id=f.fac_id
                            INNER JOIN tbl_department d ON r.dept_id=d.dept_id
                            INNER JOIN tbl_specialization s ON r.splz_id=s.splz_id 
                        WHERE r.reg_no="'.$reg_no.'" ORDER BY r.reg_no DESC LIMIT 1');
    $basic->execute();
    
    if($basic->rowCount()!=0){
        $student = $basic->fetch();
        try {
            //add student info
             $pdf->AliasNbPages();
             $pdf->AddPage();
             doc_header($pdf, $conn);
             
             $pdf->SetFont('Times','',12);
             $pdf->SetFont('Arial','B',9);
             $pdf->Ln(7);
             $pdf->SetX(17);
             
        	 $pdf->Cell(124, 6, 'Registration Number: '.strtoupper($reg_no)); 
         	 $pdf->Ln();
             $pdf->SetX(17);
        	 $pdf->Cell(124, 6, 'Family Name: '.strtoupper($student['lname'])); 
        	 $pdf->Ln();
        	 $pdf->SetX(17);
        	 $pdf->Cell(124, 6, 'First Name(s): '.strtoupper($student['fname']));
        	 $pdf->Ln(); 
             $pdf->SetX(17);
        	 $pdf->Cell(124, 6, 'Faculty: '.$student['fac_full_name']); 
        	 $pdf->Ln();
          	 $pdf->SetX(17);
        	 $pdf->Cell(124, 6, 'Specialization: '.$student['splz_full_name']);
             $pdf->Ln();
             $pdf->Ln();
        	//end student info
             
            //get statement
             $sql=$conn->prepare("(
                                        SELECT
                                            reg_no1,
                                            p.recorded_date AS transaction_date,
                                            'Payment' AS transaction_type,
                                            p.amount AS transaction_amount,
                                            f.name AS fee_name
                                        FROM (
                                            SELECT DISTINCT reg.reg_no AS reg_no1
                                            FROM tbl_register_program_ug reg
                                            LEFT JOIN payment p ON reg.reg_no = p.reg_no
                                            LEFT JOIN fee_category f ON p.fee_id = f.id
                                            WHERE reg.reg_no = ? AND p.amount > 0
                                        ) AS distinct_reg_numbers
                                        LEFT JOIN payment p ON distinct_reg_numbers.reg_no1 = p.reg_no
                                        LEFT JOIN fee_category f ON p.fee_id = f.id
                                    )
                                    UNION ALL
                                    (
                                        SELECT
                                            reg_no1,
                                            inv.invoice_date AS transaction_date,
                                            'Invoice' AS transaction_type,
                                            inv.balance AS transaction_amount,
                                            f.name AS fee_name
                                        FROM (
                                            SELECT DISTINCT reg.reg_no AS reg_no1
                                            FROM tbl_register_program_ug reg
                                            LEFT JOIN tbl_invoice inv ON reg.reg_no = inv.reg_no
                                            LEFT JOIN fee_category f ON inv.fee_id = f.id
                                            WHERE reg.reg_no = ? AND inv.balance > 0
                                        ) AS distinct_reg_numbers
                                        LEFT JOIN tbl_invoice inv ON distinct_reg_numbers.reg_no1 = inv.reg_no
                                        LEFT JOIN fee_category f ON inv.fee_id = f.id
                                    )
                                    ORDER BY transaction_date;
                                ");
            $sql->execute([$reg_no, $reg_no]);
            
        	$pdf->SetFont('Arial' , 'B', 8);
        	$pdf->SetX(17);
        	$pdf->Cell(15, 5, 'SN', 1);
        	$pdf->Cell(30, 5, 'Transaction Type', 1);
        	$pdf->Cell(35, 5, 'Fee Type', 1);
        	$pdf->Cell(30, 5, 'Amount', 1);
        	$pdf->Cell(30, 5, 'Balance', 1);
        	$pdf->Cell(33, 5, 'Date', 1);
        	$pdf->Ln();
        	
            // set initial column position
            $colX = 15;
            $colY = 110;
            $i=1;
            $balance = 0;
            while($pay=$sql->fetch()) {
                if($pay['transaction_type']=='Invoice'){
                    $balance += $pay['transaction_amount'];
                }
                if($pay['transaction_type']=='Payment'){
                    $balance -= $pay['transaction_amount'];
                }
            	$pdf->SetFont('Arial','',8);
            	$pdf->SetX($colX + 2);
            	$pdf->Cell(15, 5, $i++, 1);
            	$pdf->Cell(30, 5, $pay['transaction_type'], 1);
            	$pdf->Cell(35, 5, $pay['fee_name'], 1);
            	$pdf->Cell(30, 5, number_format($pay['transaction_amount'],2), 1);
            	$pdf->Cell(30, 5, number_format($balance, 2), 1);
            	$pdf->Cell(33, 5, $pay['transaction_date'], 1);
            	$pdf->Ln();
            	if($pdf->GetY()>200){
                    $pdf->AddPage();
                    $colY = 10;
            	}
            }
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    page_footer($pdf, $data);
	$dname=$reg_no.'_Statement';
	$pdf->Output($dname.'.pdf','I');

    exit;
    ob_end_flush();
?>
