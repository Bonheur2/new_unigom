<?php
ob_start(); 
require('../../fpdf17/fpdf.php');
require('../../meet/con.php');
Header('Pragma: public');

$connection=$conn;
class PDF_Rotate extends FPDF{
    var $angle=0;
    function Rotate($angle,$x=-1,$y=-1){
    	if($x==-1)
    		$x=$this->x;
    	if($y==-1)
    		$y=$this->y;
    	if($this->angle!=0)
    		$this->_out('Q');
    	$this->angle=$angle;
    	if($angle!=0)
    	{
    		$angle*=M_PI/180;
    		$c=cos($angle);
    		$s=sin($angle);
    		$cx=$x*$this->k;
    		$cy=($this->h-$y)*$this->k;
    		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    	}
    }

    function _endpage(){
    	if($this->angle!=0)
    	{
    		$this->angle=0;
    		$this->_out('Q');
    	}
    	parent::_endpage();
    }

}
//inherits watermark to pdf

class PDF extends PDF_Rotate
{
    var $connect;
    function RotatedText($x, $y, $txt, $angle){
    	$this->Rotate($angle,$x,$y);
    	$this->Text($x,$y,$txt);
    	$this->Rotate(0);
    }
    //Page header
    function Header(){
        global $connection;
        $this->connect=$connection;
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
         /**********************Logo**************************/
		 $this->Image('../..'.$data['logo'],20,10,40);
		 $this->SetFont('Arial','B',15);
		 /**********************Info-main**************************/
		 $this->SetX(70);
		 $this->SetFont('Arial','',13);
		 $this->SetTextColor(55,24,60);
		 $this->Cell(200,5,$data['full_name'],0,0,'R');
		 $this->Ln(6);
		 $this->SetX(70);
		 $this->Cell(200,5,'',0,0,'R');
		 $this->Ln(6);
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(200,5,$data['po_box'],0,0,'R');
		 $this->Ln(5);
		 /*********************Contact***************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
// 		 $this->Cell(200,5,$data['phone'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 /*********************Web&email***************************/
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
// 		 $this->Cell(200,5,$data['email'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 $this->Cell(200,5,$data['website'],0,0,'R');
		 $this->Ln(10);
	     /********************Title****************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','BU',13);
		 $this->Cell(300,20,"STUDENT'S FINANCIAL REPORT",0,0,'C');
		 $this->Ln(15);
    }
    //Page footer
    function Footer(){
        $this->SetY(-20);
	    $this->SetTextColor(0,0,0);
	    $this->SetFont('Arial','B',10);	
	  
    	$this->SetFont('Arial','B',11);	
    	$this->Cell(57,5,'Finance office',0,1,'C');
    
    	$this->SetY(-17);
    	$this->SetTextColor(0,0,0);
    	$this->SetFont('Arial','I',10);	 
    	$this->Cell(0,5,'Issued on: '.date('Y-m-d'),0,1,'C');
    	
        $this->SetY(-15);
    	$this->Cell(0,10,'_________________________________________________________________________________________________________________________________________',0,0,'C');
    	$this->Ln(5);
    }
    var $B;
    var $I;
    var $U;
    var $HREF;
    function PDF($orientation='L',$unit='mm',$format='A4'){
        $this->FPDF($orientation,$unit,$format);
        $this->B=0;
        $this->I=0;
        $this->U=0;
        $this->HREF='';
    }
    function WriteHTML($html){
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e){
            if($i%2==0){
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else{
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else{
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }
    function OpenTag($tag,$attr){
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }
    function CloseTag($tag){
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
    }
    function SetStyle($tag,$enable){
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s){
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }
    function PutLink($URL,$txt){
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}

    $pdf=new PDF("L");
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Financial report');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $date=date('Y-m-d');
    
    if (isset($_REQUEST['stu']) && isset($_REQUEST['type'])){
        $reg_no = trim(json_decode(base64_decode($_REQUEST['stu']), true));
        $type= trim($_REQUEST['type']);
    }
    else{
        $reg_no = "";
        $type = "";
    }
    $getStudentData = $conn->prepare("SELECT a.fname, a.lname, r.reg_no, s.splz_full_name
                                                FROM tbl_register_program_ug r
                                                    INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
                                                    INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                                WHERE
                                                    r.reg_no = '".$reg_no."' ORDER BY r.reg_prg_id DESC LIMIT 1");
    $getStudentData->execute();
    if($getStudentData->rowCount()!=0){
        $studentData=$getStudentData->fetch();
        $fname=strtoupper($studentData['fname']);
        $lname=strtoupper($studentData['lname']);
        $splz=$studentData['splz_full_name'];
    }
    try {
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',8);
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(32,5,'Student ID',1);
    	$pdf->Cell(85,5,$reg_no,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(32,5,'First name',1);
    	$pdf->Cell(85,5,$fname,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(32,5,'Family name(s)',1);
    	$pdf->Cell(85,5,$lname,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(32,5,'Specialization',1);
    	$pdf->Cell(85,5,$splz,1);
    	$pdf->Ln(10);
    	if($type==1){
            $debtData=$conn->prepare("SELECT SUM(balance) as debt FROM tbl_invoice WHERE reg_no = '".$reg_no."'");
            $debtData->execute();
            $debtBalance=$debtData->fetch();
            $debt=$debtBalance['debt'];
            
            $paidTotal=$conn->prepare("SELECT SUM(amount) as paid FROM payment_trial WHERE reg_no = '".$reg_no."' AND status=1;");
            $paidTotal->execute();
            $paidBalance=$paidTotal->fetch();
            $paid=$paidBalance['paid'];
            
            $balance=$debt-$paid;
            
            $ppaid=$paid*100/($debt>0?$debt:1);
            $pbal=$balance*100/($debt>0?$debt:1);
            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(92,6,'TOTAL DEBT',1,0,'C'); 		 
            $pdf->Cell(87,6,'PAID AMOUNT',1,0,'C'); 
            $pdf->Cell(86,6,'BALANCE',1,0,'C');
        	$pdf->Ln();
            $pdf->Cell(5,6,''); 
            $pdf->Cell(92,6,number_format($debt,1),1,0,'C'); 		 
            $pdf->Cell(87,6,number_format($paid,1)." | ".number_format($ppaid,1)."%",1,0,'C'); 
            $pdf->Cell(86,6,number_format($balance,1)." | ".number_format($pbal,1)."%",1,0,'C');

    	}
    	else if($type==2){
            $getReport=$conn->prepare("SELECT inv.*,a.acad_year,f.name as fee_name 
                                                        FROM tbl_invoice inv
                                                            INNER JOIN tbl_acad_cycle a ON inv.acad_cycle_id=a.acad_cycle_id
                                                            INNER JOIN fee_category f ON inv.fee_id=f.id
                                                        WHERE inv.reg_no='".$reg_no."'");
            $getReport->execute();

            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(259,6,'INVOICES',1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(12,6,'S/N',1); 		 
            $pdf->Cell(40,6,'AMOUNT',1); ;
            $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
            $pdf->Cell(60,6,'FEE CATEGORY',1);
            $pdf->Cell(67,6,'DESCRIPTION',1);
            $pdf->Cell(40,6,'INVOICE DATE',1); 
        	$pdf->Ln();
        	 
        	$i=1;
        	$pager=1;
        	$counter=$getReport->rowCount();
        	 
            while ($inv = $getReport->fetch()){
                $total+=$inv['balance'];
                
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(5,6,''); 
                $pdf->Cell(12,6,$i++,1); 
                $pdf->Cell(40,6,number_format($inv['balance'],1),1); 
                $pdf->Cell(40,6,$inv['acad_year'],1);
                $pdf->Cell(60,6,$inv['fee_name'],1); 
                $pdf->Cell(67,6,$inv['comment']!=null?$inv['comment']:($inv['month']!=null?$inv['month']:"-"),1);
                $pdf->Cell(40,6,$inv['invoice_date'],1); 
            	$pdf->Ln();
                $pager++;
                if ($pdf->PageNo() == 1) {
                    if ($pdf->GetY() > 160  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(40,6,'AMOUNT',1); ;
                        $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
                        $pdf->Cell(60,6,'FEE CATEGORY',1);
                        $pdf->Cell(67,6,'DESCRIPTION',1);
                        $pdf->Cell(40,6,'INVOICE DATE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                } else {
                    if ($pdf->GetY() > 200  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(40,6,'AMOUNT',1); ;
                        $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
                        $pdf->Cell(60,6,'FEE CATEGORY',1);
                        $pdf->Cell(67,6,'DESCRIPTION',1);
                        $pdf->Cell(40,6,'INVOICE DATE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                }
            }
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(92, 6, 'OUTSTANDING PAYMENT',1, 0, 'C');
            $pdf->Cell(60, 6, number_format($total,1), 1, 0, 'C');
    	}
    	else if($type==3){
            $getReport=$conn->prepare("SELECT p.*,a.acad_year,b.bank_code,b.account_no,f.name as fee_name 
                                                        FROM payment p
                                                            INNER JOIN tbl_acad_cycle a ON p.acad_cycle_id=a.acad_cycle_id
                                                            INNER JOIN fee_category f ON p.fee_id=f.id
                                                            INNER JOIN tbl_bank b ON p.bank_id=b.bank_id
                                                        WHERE p.reg_no='".$reg_no."'");
            $getReport->execute();

            $pdf->Ln(10);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(259,6,'PAYMENTS',1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(12,6,'S/N',1); 		 
            $pdf->Cell(40,6,'AMOUNT',1); ;
            $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
            $pdf->Cell(50,6,'FEE CATEGORY',1);
            $pdf->Cell(60,6,'ACCOUNT NO',1);
            $pdf->Cell(30,6,'SLIP NO',1); 
            $pdf->Cell(27,6,'DATE',1); 
        	$pdf->Ln();
        	 
        	$i=1;
        	$pager=1;
        	$counter=$getReport->rowCount();
        	 
            while ($pay = $getReport->fetch()){
                $total+=$pay['amount'];
                
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(5,6,''); 
                $pdf->Cell(12,6,$i++,1); 
                $pdf->Cell(40,6,number_format($pay['amount'],1),1); 
                $pdf->Cell(40,6,$pay['acad_year'],1);
                $pdf->Cell(50,6,$pay['fee_name'],1); 
                $pdf->Cell(60,6,$pay['bank_code']." | ".$pay['account_no'],1);
                $pdf->Cell(30,6,$pay['slip_no'],1); 
                $pdf->Cell(27,6,$pay['date'],1); 
            	$pdf->Ln();
                $pager++;
                if ($pdf->PageNo() == 1) {
                    if ($pdf->GetY() > 160  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(40,6,'AMOUNT',1); ;
                        $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
                        $pdf->Cell(50,6,'FEE CATEGORY',1);
                        $pdf->Cell(60,6,'ACCOUNT NO',1);
                        $pdf->Cell(30,6,'SLIP NO',1); 
                        $pdf->Cell(27,6,'DATE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                } else {
                    if ($pdf->GetY() > 200  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(40,6,'AMOUNT',1); ;
                        $pdf->Cell(40,6,'ACADEMIC YEAR',1); 
                        $pdf->Cell(50,6,'FEE CATEGORY',1);
                        $pdf->Cell(60,6,'ACCOUNT NO',1);
                        $pdf->Cell(30,6,'SLIP NO',1); 
                        $pdf->Cell(27,6,'DATE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                }
            }
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(92, 6, 'TOTAL PAYMENT',1, 0, 'C');
            $pdf->Cell(50, 6, number_format($total,1), 1, 0, 'C');
    	}
    	else if($type==4){
            $getReport=$conn->prepare("SELECT p.amount as amount, p.slip_no as slip_no, p.date as date,p.trans_code,
                                                            a.acad_year, b.bank_code as bank_code, b.account_no as account_no,
                                                            f.name as fee_name, NULL as balance, NULL as comment, NULL as month
                                                        FROM payment p
                                                            INNER JOIN tbl_acad_cycle a ON p.acad_cycle_id=a.acad_cycle_id
                                                            INNER JOIN fee_category f ON p.fee_id=f.id
                                                            INNER JOIN tbl_bank b ON p.bank_id=b.bank_id
                                                            WHERE p.reg_no='".$reg_no."'
                                                        UNION ALL
                                                        SELECT NULL as amount, NULL as slip_no, inv.invoice_date as date,NULL as trans_code,
                                                                   a.acad_year, NULL as bank_code, NULL as account_no,
                                                                   f.name as fee_name, inv.balance as balance, inv.comment as comment, inv.month as month
                                                        FROM tbl_invoice inv
                                                            INNER JOIN tbl_acad_cycle a ON inv.acad_cycle_id=a.acad_cycle_id
                                                            INNER JOIN fee_category f ON inv.fee_id=f.id
                                                            WHERE inv.reg_no='".$reg_no."'
                                                            ORDER BY date");
            $getReport->execute();
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(269,6,'PAYMENT - INVOICE',1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(12,6,'S/N',1); 
            $pdf->Cell(20,6,'DATE',1); 		 
            $pdf->Cell(20,6,'DEPT',1,0,'C'); 
            $pdf->Cell(20,6,'PAYMENT',1,0,'C'); 
            $pdf->Cell(20,6,'BALANCE',1,0,'C');
            $pdf->Cell(30,6,'ACADEMIC YEAR',1);
            $pdf->Cell(30,6,'FEE CATEGORY',1);
            $pdf->Cell(30,6,'Description',1);
            $pdf->Cell(57,6,'ACCOUNT',1);
            $pdf->Cell(30,6,'SLIP NO',1);
            // $pdf->Cell(35,6,'TRANSACTION CODE',1);
        	$pdf->Ln();
        	 
        	$i=1;
        	$pager=1;
        	$counter=$getReport->rowCount();
        	 
            while ($pay = $getReport->fetch()){
                $debt+=$pay['balance'];
                $paid+=$pay['amount'];
                $bal=$debt-$paid;
                
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(5,6,''); 
                $pdf->Cell(12,6,$i++,1); 
                $pdf->Cell(20,6,$pay['date'],1,0,'C'); 		 
                $pdf->Cell(20,6,$pay['balance']!=null?number_format($pay['balance'],1):'-',1,0,'C'); 
                $pdf->Cell(20,6,$pay['amount']!=null?number_format($pay['amount'],1):'-',1,0,'C'); 
                $pdf->Cell(20,6,number_format($bal,1),1,0,'C');
                $pdf->Cell(30,6,$pay['acad_year'],1);
                $pdf->Cell(30,6,$pay['fee_name'],1); 
                $pdf->Cell(30,6,$pay['comment']!=null?$pay['comment']:($pay['month']!=null?$pay['month']:'-'),1); 
                $pdf->Cell(57,6,$pay['bank_code']!=null?$pay['bank_code']." | ".$pay['account_no']:"-",1);
                $pdf->Cell(30,6,$pay['slip_no']!=null?$pay['slip_no']:'-',1);
                // $pdf->Cell(35,6,$pay['trans_code']!=null?$pay['trans_code']:'-',1);
            	$pdf->Ln();
                $pager++;
                if ($pdf->PageNo() == 1) {
                    if ($pdf->GetY() > 160  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 
                        $pdf->Cell(20,6,'DATE',1); 		 
                        $pdf->Cell(20,6,'DEPT',1,0,'C'); 
                        $pdf->Cell(20,6,'PAYMENT',1,0,'C'); 
                        $pdf->Cell(20,6,'BALANCE',1,0,'C');
                        $pdf->Cell(30,6,'ACADEMIC YEAR',1);
                        $pdf->Cell(30,6,'FEE CATEGORY',1);
                        $pdf->Cell(30,6,'Description',1);
                        $pdf->Cell(57,6,'ACCOUNT',1);
                        $pdf->Cell(30,6,'SLIP NO',1);
                        // $pdf->Cell(35,6,'TRANSACTION CODE',1);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                } else {
                    if ($pdf->GetY() > 200  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 
                        $pdf->Cell(20,6,'DATE',1); 		 
                        $pdf->Cell(20,6,'DEPT',1,0,'C'); 
                        $pdf->Cell(20,6,'PAYMENT',1,0,'C'); 
                        $pdf->Cell(20,6,'BALANCE',1,0,'C');
                        $pdf->Cell(30,6,'ACADEMIC YEAR',1);
                        $pdf->Cell(30,6,'FEE CATEGORY',1);
                        $pdf->Cell(30,6,'Description',1);
                        $pdf->Cell(57,6,'ACCOUNT',1);
                        $pdf->Cell(30,6,'SLIP NO',1);
                        // $pdf->Cell(35,6,'TRANSACTION CODE',1);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                }
            }
    	}
	
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }
	 $dname=$reg_no.'_'.$date;
	 $pdf->Output($dname.'.pdf','I');

	exit;
ob_end_flush();
?>
