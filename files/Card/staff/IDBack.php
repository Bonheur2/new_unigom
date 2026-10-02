<?php
ob_start(); 
require('../../../fpdf17/fpdf.php');
require('../../../meet/con.php');
Header('Pragma: public');

$year=date('Y');
$connection=$conn;

class PDF_Rotate extends FPDF
{
var $angle=0;
function _endpage()
{
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
//Page header

var $B;
var $I;
var $U;
var $HREF;
function PDF($orientation='P',$unit='mm')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,array(54, 85.6));
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}
function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
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
function OpenTag($tag,$attr)
{
    //Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}
function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}
function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}
function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->SetAuthor('STUMIS');
$pdf->SetTitle('Student CARD');

$sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
$sql->execute();
$data=$sql->fetch();

$date=date('d/M/Y');
$pdf->SetTextColor(78,4,41);
$pdf->Image('../img/s2.png',-0.5,-0.5,54.2,85.9);
$pdf->Image('../img/Stump.png',28,30,25,22);
$pdf->SetFont('Times','B',10);
$pdf->SetTextColor(255,255,255);
$pdf->Ln(-2);
$pdf->SetX(0);

$pdf->Ln(8);
$pdf->SetFont('Times','B',8);
$pdf->SetTextColor(0,0,0);
$pdf->Text(10,34,$data['phone']);
$pdf->Text(10,40,'+23276629703');
$pdf->Text(10,51.5,$data['website']);
$pdf->SetFont('Times','B',6);
$pdf->Text(4,59,'This card is a property of '.$data['full_name'].'If lost and');
$pdf->Text(4,63,'found please call the number above or send to '.$data['short_name']);
$filename= "card_back_".$_REQUEST['stu'];
$pdf->Output($filename.'.pdf','I');
$pdf->Output();


exit;
ob_end_flush();
?>