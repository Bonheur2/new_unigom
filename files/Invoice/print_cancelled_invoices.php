<?php
ob_start();
require('../../fpdf/fpdf.php');
require('../../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
Header('Pragma: public');

$connection=$conn;

class PDF_Rotate extends FPDF {
    var $angle=0;
    function Rotate($angle,$x=-1,$y=-1) {
        if($x==-1) $x=$this->x;
        if($y==-1) $y=$this->y;
        if($this->angle!=0) $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0) {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }

    function _endpage() {
        if($this->angle!=0) {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}

// Inherits watermark to PDF
class PDF extends PDF_Rotate {
    var $connect;
    function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        parent::__construct($orientation, $unit, $format);
    }
    function RotatedText($x, $y, $txt, $angle) {
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    // Page header
    function Header() {
        global $connection;
        $this->connect=$connection;
        try{
        $sql=$this->connect->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
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
        $this->Cell(190,5,"CANCELLED INVOICES REPORT",0,0,'C');
        $this->Ln(8);
        }
        catch (Exception $e) {
            $this->SetFont('Arial', 'I', 10);
            $this->Cell(190, 5, "Error loading header: " . $e->getMessage(), 0, 0, 'C');
        }
    }

    // Page footer
    function Footer() {
         
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
        $this->Ln(1);
    }

    var $B;
    var $I;
    var $U;
    var $HREF;

    function PDF($orientation='P',$unit='mm',$format='A4') {
        $this->FPDF($orientation,$unit,$format);
        $this->B=0;
        $this->I=0;
        $this->U=0;
        $this->HREF='';
    }

    function WriteHTML($html) {
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e) {
            if($i%2==0) {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            } else {
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else {
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v) {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag,$attr) {
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag) {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
    }

    function SetStyle($tag,$enable) {
        // Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s) {
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt) {
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}

$pdf=new PDF();
$pdf->SetAuthor('STUMIS');
$pdf->SetTitle('Student report by Sponsor');
$pdf->AliasNbPages();
$pdf->AddPage();
$date=date('Y-m-d');

$splz = $_REQUEST['splz']?? null;
$level = $_REQUEST['level']?? null;
$intake = $_REQUEST['intake']?? null;
$total = $_REQUEST['total']?? 0;

$getClassData = $conn->prepare("SELECT s.splz_full_name, l.level_full_name, i.intake_month, a.acad_year
                                            FROM tbl_register_program_ug r
                                                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                                INNER JOIN tbl_level l ON r.level_id = l.level_id
                                                JOIN tbl_intake i ON i.intake_id='".$intake."'
                                                INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id = a.acad_cycle_id
                                            WHERE 
                                                r.splz_id='".$splz."' AND r.level_id='".$level."'");
$getClassData->execute();
$classData=$getClassData->fetch();

try {

} catch (Exception $e) {
    echo "Error in generating report: " . $e->getMessage();
}

// New section for sponsor information
$sql=$conn->prepare("SELECT inv.*, 
                                                                                            ac.acad_year,
                                                                                            f.name as fee_name,
                                                                                            hr.room_code ,
                                                                                            rs.rest_name,
                                                                                            ad.fname,ad.lname
                                                                                        FROM tbl_invoice inv LEFT JOIN tbl_acad_cycle ac ON inv.acad_cycle_id=ac.acad_cycle_id
                                                                                                             INNER JOIN tbl_admission ad ON inv.reg_no=ad.reg_no
                                                                                                             LEFT JOIN tbl_fee_category f ON inv.fee_id=f.id
                                                                                                             LEFT JOIN tbl_hostel_room hr ON inv.room_id=hr.room_id
                                                                                                             LEFT JOIN tbl_restaurant rs ON inv.restaurant_id=rs.rest_id
                                                                                        WHERE invoice_status = 1 AND approval_status=2  
                                                                                        ORDER BY inv.acad_cycle_id DESC");
$sql->execute(); 
$i=1;

$pdf->Ln(10);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(5,6,''); 

$pdf->Ln();
$pdf->Cell(5,6,'');
$pdf->Cell(10,6,'SN',1);
$pdf->Cell(85,6,'Student ',1);
$pdf->Cell(30,6,'Academic Year',1);
$pdf->Cell(40,6,'Fee Category',1);
$pdf->Cell(25,6,'Amount',1);

$pdf->Ln();

$pdf->SetFont('Arial','',6); // Set the font size smaller for table content

while($data=$sql->fetch()){
    $s_regno=$data['reg_no'];
    $sql2=$conn->prepare("SELECT * FROM tbl_admission WHERE reg_no='".$s_regno."'    ");
    $sql2->execute(); 
    while($std=$sql2->fetch()){
        $pdf->Cell(5,6,'');
        $pdf->Cell(10,6,$i++,1);
        $pdf->Cell(85,6,$data['reg_no']." | ".$std['fname']." ".$std['lname'],1);
        $pdf->Cell(30,6,$data['acad_year'],1);
        $pdf->Cell(40,6,$data['fee_name'],1);
        $pdf->Cell(25,6,$data['balance'],1);
        $pdf->Ln();
    }
}

$pdf->SetFont('Arial','B',7); // Set the font size back to larger for the total
$pdf->Cell(5,6,'');
$pdf->Cell(165,8,'Total :',1); // Merge cells to span the table width
$pdf->Cell(25,8,$total,1);

$pdf->Output();
ob_end_flush();
?>
