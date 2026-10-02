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
		 $this->Cell(120,5,$data['full_name'],0,0,'R');
		 $this->Ln(6);
		 $this->SetX(70);
		 $this->Cell(120,5,'',0,0,'R');
		 $this->Ln(6);
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,$data['po_box'],0,0,'R');
		 $this->Ln(5);
		 /*********************Contact***************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,$data['phone'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 /*********************Web&email***************************/
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,$data['email'],0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
		 $this->Cell(120,5,$data['website'],0,0,'R');
		 $this->Ln(10);
	     /********************Title****************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','BU',13);
		 $this->Cell(190,10,"CLASS FINANCIAL REPORT",0,0,'C');
		 $this->Ln(15);
    }
    //Page footer
    function Footer(){
        $this->SetY(-30);
	    $this->SetTextColor(0,0,0);
	    $this->SetFont('Arial','B',10);	
	  
    	$this->SetFont('Arial','B',11);	
    	$this->Cell(57,5,'Finance office',0,1,'C');
    
    	$this->SetY(-27);
    	$this->SetTextColor(0,0,0);
    	$this->SetFont('Arial','I',10);	 
    	$this->Cell(0,5,'Issued on: '.date('Y-m-d'),0,1,'C');
    	
        $this->SetY(-25);
    	$this->Cell(0,10,'_____________________________________________________________________________________________________',0,0,'C');
    	$this->Ln(5);
    	
        // global $connection;
        // $this->connect=$connection;
        // $sql=$this->connect->prepare("SELECT * FROM tbl_bank");
        // $sql->execute();
        // $this->SetY(-15);
        // $this->SetFont('Arial','I',7);	
        // $counter=0;
        // while($bank=$sql->fetch()){
        //     if($this->GetY()==297){
        //         break;
        //     }
        //     if($this->GetX()+15>195){
        //         $this->Ln(5);
        //     }
        //     $this->Cell(63,4,$bank['bank_name']." (".$bank['currency']."): ".$bank['account_no'],'LR',0,'C');
        //     $counter++;
        //     if($counter==6) break;
        // }
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
}

    $pdf=new PDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Financial report');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $date=date('Y-m-d');
    
    $splz = $_REQUEST['splz'];
    $level = $_REQUEST['level'];
    $acad_cycle_id = $_REQUEST['intake'];
    $type = $_REQUEST['type'];

    $getClassData = $conn->prepare("SELECT s.splz_full_name, l.level_full_name, a.acad_year
        FROM tbl_register_program_ug r
        INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
        INNER JOIN tbl_level l ON r.level_id = l.level_id
        INNER JOIN tbl_acad_cycle a ON r.acad_cycle_id = a.acad_cycle_id
        WHERE r.splz_id = :splz AND r.level_id = :level AND a.acad_cycle_id = :acad_cycle_id");

$getClassData->bindParam(':splz', $splz, PDO::PARAM_INT);
$getClassData->bindParam(':level', $level, PDO::PARAM_INT);
$getClassData->bindParam(':acad_cycle_id', $acad_cycle_id, PDO::PARAM_INT);

$getClassData->execute();  // Execute after binding parameters

$classData = $getClassData->fetch();  // Fetch the data

    try {
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',8);
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(25,5,'Specialization',1);
    	$pdf->Cell(77,5,$classData['splz_full_name'],1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(25,5,'Level',1);
    	$pdf->Cell(77,5,$classData['level_full_name'],1);
    	$pdf->Ln();
    	$pdf->Cell(5,6,'');
    	$pdf->Cell(25,5,'Academic year',1);
    	$pdf->Cell(77,5,$classData['acad_year'],1);
    	$pdf->Ln(10);
    	if($type==1){
            $stmt=$conn->prepare("SELECT 
                                        r.reg_no,
                                        r.acad_cycle_id,
                                        a.fname,
                                        a.lname
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                    WHERE r.splz_id='".$splz."' AND r.level_id='".$level."' AND a.acad_cycle_id='".$acad_cycle_id."'");
            $stmt->execute();

            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(182,6,'STUDENTS'."' ".'BALANCE' ,1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(5,6,''); 
            $pdf->Cell(12,6,'S/N',1); 		 
            $pdf->Cell(30,6,'STUDENT ID',1); ;
            $pdf->Cell(60,6,'NAMES',1); 
            $pdf->Cell(30,6,'PAYABLE AMOUNT',1);
            $pdf->Cell(25,6,'PAID AMOUNT',1);
            $pdf->Cell(25,6,'BALANCE',1); 
        	$pdf->Ln();
        	 
        	$i=1;
        	$pager=1;
        	$counter=$stmt->rowCount();
        	 
            $i=1;
            while($sdata=$stmt->fetch()){
                $stmt2=$conn->prepare("SELECT COALESCE(SUM(balance), 0) AS debt FROM tbl_invoice 
                                            WHERE acad_cycle_id='".$sdata['acad_cycle_id']."' AND reg_no='".$sdata['reg_no']."'");
                $stmt2->execute();
                $debtData=$stmt2->fetch();
                $debt=$debtData['debt'];
                $stmt3=$conn->prepare("SELECT COALESCE(SUM(amount), 0) AS paid FROM payment
                                                        WHERE acad_cycle_id='".$sdata['acad_cycle_id']."' AND reg_no='".$sdata['reg_no']."' AND status=1");
                $stmt3->execute();
                $paymentData=$stmt3->fetch();
                $paid=$paymentData['paid'];
                $balance=$debt-$paid;
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(5,6,''); 
                $pdf->Cell(12,6,$i++,1); 
                $pdf->Cell(30,6,$sdata['reg_no'],1); 
                $pdf->Cell(60,6,$sdata['fname']." ".$sdata['lname'],1);
                $pdf->Cell(30,6,number_format($debt,1),1); 
                $pdf->Cell(25,6,number_format($paid,1),1);
                $pdf->Cell(25,6,number_format($balance,1),1); 
            	$pdf->Ln();
            	
                $pager++;
                if ($pdf->PageNo() == 1) {
                    if ($pdf->GetY() > 225  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(30,6,'STUDENT ID',1); ;
                        $pdf->Cell(60,6,'NAMES',1); 
                        $pdf->Cell(30,6,'PAYABLE AMOUNT',1);
                        $pdf->Cell(25,6,'PAID AMOUNT',1);
                        $pdf->Cell(25,6,'BALANCE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',8);
                    }
                } else {
                    if ($pdf->GetY() > 240  && $pager<$counter){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Ln(10);
                        $pdf->Cell(5,6,''); 
                        $pdf->Cell(12,6,'S/N',1); 		 
                        $pdf->Cell(30,6,'STUDENT ID',1); ;
                        $pdf->Cell(60,6,'NAMES',1); 
                        $pdf->Cell(30,6,'PAYABLE AMOUNT',1);
                        $pdf->Cell(25,6,'PAID AMOUNT',1);
                        $pdf->Cell(25,6,'BALANCE',1); 
                    	$pdf->Ln();
                        $pdf->SetFont('Arial','',8);
                    }
                }
            }
    	}
	
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }
	 $dname=$classData['splz_full_name']."_".$classData['level_full_name'].'_'.$classData['acad_year'];
	 $pdf->Output($dname.'.pdf','I');

	exit;
ob_end_flush();
?>
