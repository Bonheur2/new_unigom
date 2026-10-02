<?php
ob_start(); 
require('../../../fpdf/fpdf.php');
require('../../../meet/con.php');
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
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
        $sql->execute();
        $data=$sql->fetch();
         /**********************Logo**************************/
		 $this->Image('../../..'.$data['logo'],20,10,40);
		 $this->SetFont('Arial','B',15);
		 /**********************Info-main**************************/
		 $this->SetX(70);
		 $this->SetFont('Arial','',13);
		 $this->SetTextColor(55,24,60);
		 $this->Cell(125,5,$data['full_name'],0,0,'R');
		 $this->Ln(6);
		 $this->SetX(70);
		 $this->Cell(125,5,'',0,0,'R');
		 $this->Ln(6);
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(125,5,$data['po_box'],0,0,'R');
		 $this->Ln(5);
		 /*********************Contact***************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(125,5,$data['phone'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 /*********************Web&email***************************/
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(125,5,$data['email'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 $this->Cell(125,5,$data['website'],0,0,'R');
		 $this->Ln(10);
	     /********************Title****************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','BU',13);
		 $this->Cell(195,20,"TUITION INVOICE",0,0,'C');
		 $this->Ln(10);
    }
    //Page footer
    function Footer(){
        $this->SetY(-20);
	    $this->SetTextColor(0,0,0);
	    $this->SetFont('Arial','B',10);	
	  
    	$this->SetFont('Arial','B',11);	
    	$this->Cell(57,5,'Finance office',0,0,'C');
    	$this->SetFont('Arial','',11);	
    	$this->Cell(138,5,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    
    	$this->SetY(-17);
    	$this->SetTextColor(0,0,0);
    	$this->SetFont('Arial','I',10);	 
    	$this->Cell(0,5,'Downloaded on: '.date('Y-m-d'),0,1,'C');
    	
        $this->SetY(-15);
    	$this->Cell(0,10,'_________________________________________________________________________________________________________________________________________',0,0,'C');
    	$this->Ln(5);
    }
    var $B;
    var $I;
    var $U;
    var $HREF;
    function PDF($orientation='P',$unit='mm',$format='A4'){
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
    

    
     function save_Tuition_Invoice()
     { 
    $reg_no = $_REQUEST['stu'];
    $acadyear = $_REQUEST['acad'];
    $fee = 1;
    $amount = $_REQUEST['total'];

    $stmt0 = $this->connect->prepare("SELECT * FROM tbl_invoice WHERE reg_no = ? AND acad_cycle_id = ? AND fee_id = ?");
    $stmt0->execute([$reg_no, $acadyear, $fee]);
    if ($stmt0->rowCount() > 0) {
        // Prepare the UPDATE statement
        $stmt1 = $this->connect->prepare("UPDATE tbl_invoice SET balance = ? WHERE reg_no = ? AND acad_cycle_id = ? AND fee_id = ?");
        $stmt1->execute([$amount, $reg_no, $acadyear, $fee]);
        } else
        {
           $stmt = $this->connect->prepare("INSERT INTO tbl_invoice (reg_no, acad_cycle_id, fee_id, balance) VALUES (?, ?, ?, ?)");
           $stmt->execute([$reg_no, $acadyear, $fee, $amount]); 
        }
        }  

       }

    $pdf=new PDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Tuition invoice');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $date=date('Y-m-d');
    
    
    
    if (isset($_REQUEST['stu']) && isset($_REQUEST['acad'])){
        $reg_no = trim($_REQUEST['stu']);
        $acad = trim($_REQUEST['acad']);
    }
    else{
        $reg_no = "";
        $acad = "";
    }
    

    $getStudentData = $conn->prepare("SELECT a.fname, a.lname, r.reg_no, s.splz_full_name, ac.acad_year
                                                FROM tbl_register_program_ug r
                                                    INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
                                                    INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                                    INNER JOIN tbl_acad_cycle ac ON r.acad_cycle_id = ac.acad_cycle_id
                                                WHERE
                                                    r.reg_no = '".$reg_no."' AND r.acad_cycle_id = '".$acad."' ORDER BY r.reg_prg_id DESC LIMIT 1");
    $getStudentData->execute();
    if($getStudentData->rowCount()!=0){
        $studentData=$getStudentData->fetch();
        $fname=strtoupper($studentData['fname']);
        $lname=strtoupper($studentData['lname']);
        $splz=$studentData['splz_full_name'];
        $acad_year=$studentData['acad_year'];
    }
    try {
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',8);
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(22,5,'Student ID',1);
    	$pdf->Cell(70,5,$reg_no,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(22,5,'First name',1);
    	$pdf->Cell(70,5,$fname,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(22,5,'Family name',1);
    	$pdf->Cell(70,5,$lname,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(22,5,'Specialization',1);
    	$pdf->Cell(70,5,$splz,1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(22,5,'Academic Year',1);
    	$pdf->Cell(70,5,$acad_year,1);
    	$pdf->Ln(5);

        $sql=$conn->prepare("SELECT 
                                    tm.module_credits,
                                    tm.credit_price,
                                    lev.level_full_name,
                                    mm.module_id,
                                    m.module_code,
                                    m.module_name
                                FROM tbl_markby_module mm 
                                    INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id
                                    INNER JOIN tbl_level lev ON tm.level_id = lev.level_id
                                    INNER JOIN modules m ON tm.mod_id = m.module_id
                                WHERE mm.acad_cycle_id='".$acad."' AND 
                                    mm.reg_no='".$reg_no."' AND 
                                    mm.enrolled=1
                                ");
            $sql->execute();

            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(12,6,'S/N',1); 		 
            $pdf->Cell(88,6,'MODULE',1); ;
            $pdf->Cell(20,6,'CREDITS',1); 
            $pdf->Cell(30,6,'CREDIT PRICE',1);
            $pdf->Cell(30,6,'AMOUNT',1);
        	$pdf->Ln();
        	
        	$i=1;
        	$pager=1;
        	$counter=$sql->rowCount();
        	 
            while ($inv = $sql->fetch()){
                $amount = $inv['module_credits']*$inv['credit_price'];
                $total += $amount;
                
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(5,6,''); 
                $pdf->Cell(12,6,$i++,1); 
                $pdf->Cell(88,6,$inv['module_name'],1); 
                $pdf->Cell(20,6,$inv['module_credits'],1);
                $pdf->Cell(30,6,number_format($inv['credit_price'],1),1); 
                $pdf->Cell(30,6,number_format($amount,1),1);
            	$pdf->Ln();
                $pager++;
                if ($pdf->PageNo() == 1) {
                    if ($pdf->GetY() > 200  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(88,6,'MODULE',1); ;
                        $pdf->Cell(20,6,'CREDITS',1); 
                        $pdf->Cell(30,6,'CREDIT PRICE',1);
                        $pdf->Cell(30,6,'AMOUNT',1);
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                } else {
                    if ($pdf->GetY() > 240  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(88,6,'MODULE',1); ;
                        $pdf->Cell(20,6,'CREDITS',1); 
                        $pdf->Cell(30,6,'CREDIT PRICE',1);
                        $pdf->Cell(30,6,'AMOUNT',1);
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',7);
                    }
                }
            }
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(150, 6, 'TOTAL AMOUNT',1, 0, 'C');
            $pdf->Cell(30, 6, number_format($total,1), 1, 0, 'L');
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }
    
	$dname=$reg_no.'_'.$date;
	$pdf->Output($dname.'.pdf','I');
	$pdf->save_Tuition_Invoice();
   	
	 
	exit;
    ob_end_flush();
?>

