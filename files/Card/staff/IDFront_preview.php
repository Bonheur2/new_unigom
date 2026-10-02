<?php
ob_start(); 
require('../../../fpdf17/fpdf.php');
require('../converter/image_converter.php');
include ('../../../meet/con.php');
Header('Pragma: public');

class PDF_Rotate extends FPDF
{
function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){
	$wide = $baseline;
	$narrow = $baseline / 3 ; 
	$gap = $narrow;
    $barChar['0'] = 'nnnwwnwnn';
    $barChar['1'] = 'wnnwnnnnw';
    $barChar['2'] = 'nnwwnnnnw';
    $barChar['3'] = 'wnwwnnnnn';
    $barChar['4'] = 'nnnwwnnnw';
    $barChar['5'] = 'wnnwwnnnn';
    $barChar['6'] = 'nnwwwnnnn';
    $barChar['7'] = 'nnnwnnwnw';
    $barChar['8'] = 'wnnwnnwnn';
    $barChar['9'] = 'nnwwnnwnn';
    $barChar['A'] = 'wnnnnwnnw';
    $barChar['B'] = 'nnwnnwnnw';
    $barChar['C'] = 'wnwnnwnnn';
    $barChar['D'] = 'nnnnwwnnw';
    $barChar['E'] = 'wnnnwwnnn';
    $barChar['F'] = 'nnwnwwnnn';
    $barChar['G'] = 'nnnnnwwnw';
    $barChar['H'] = 'wnnnnwwnn';
    $barChar['I'] = 'nnwnnwwnn';
    $barChar['J'] = 'nnnnwwwnn';
    $barChar['K'] = 'wnnnnnnww';
    $barChar['L'] = 'nnwnnnnww';
    $barChar['M'] = 'wnwnnnnwn';
    $barChar['N'] = 'nnnnwnnww';
    $barChar['O'] = 'wnnnwnnwn'; 
    $barChar['P'] = 'nnwnwnnwn';
    $barChar['Q'] = 'nnnnnnwww';
    $barChar['R'] = 'wnnnnnwwn';
    $barChar['S'] = 'nnwnnnwwn';
    $barChar['T'] = 'nnnnwnwwn';
    $barChar['U'] = 'wwnnnnnnw';
    $barChar['V'] = 'nwwnnnnnw';
    $barChar['W'] = 'wwwnnnnnn';
    $barChar['X'] = 'nwnnwnnnw';
    $barChar['Y'] = 'wwnnwnnnn';
    $barChar['Z'] = 'nwwnwnnnn';
    $barChar['-'] = 'nwnnnnwnw';
	$barChar['*'] = 'nwnnwnwnn';

	$this->SetFillColor(0);
	$code = '*'.strtoupper($code).'*';
		
	for($i=0; $i<strlen($code); $i++){
		$char = $code[$i];
		if(!isset($barChar[$char])){
			$this->Error('Invalid character in barcode: '.$char);
		}
		$seq = $barChar[$char];
		for($bar=0; $bar<9; $bar++){
			if($seq[$bar] == 'n'){
				$lineWidth = $narrow;
			}else{
				$lineWidth = $wide;
			}
			if($bar % 2 == 0){
				$this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
			}
			$xpos += $lineWidth;
		}
		$xpos += $gap;
	}
}
function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

function Footer()
{   global $conn;
    $code = $_REQUEST['st'];
    $roleE=$conn->prepare("SELECT Post FROM tbl_staff_info WHERE staff_id='".$code."' LIMIT 1");
    $roleE->execute();
    $data_roleE=$roleE->fetch();
    $role_idE=$data_roleE['Post'];
    $get_roleE=$conn->prepare("SELECT staff_post_full_name FROM  staff_post WHERE staff_post_id='".$role_idE."' ");
    $get_roleE->execute();
    $data_role_nameE=$get_roleE->fetch();
    $rol_nameE=$data_role_nameE['staff_post_full_name'];
    $this->Code39(13, 67, $code, $baseline=0.5, $height=5);
    $this->SetFont('Arial','B',7);
    $this->SetTextColor(0,0,0);
    
    // Handle long role names - split into multiple lines
    $roleLines = array();
    $roleLength = strlen($rol_nameE);
    
    if ($roleLength > 20) {
        // Split long role into multiple lines
        $words = explode(' ', $rol_nameE);
        $currentLine = '';
        
        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine ? ' ' : '') . $word;
            
            if (strlen($testLine) <= 20) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $roleLines[] = $currentLine;
                    $currentLine = $word;
                } else {
                    $currentLine = $word;
                }
            }
        }
        if ($currentLine) {
            $roleLines[] = $currentLine;
        }
    } else {
        $roleLines[] = $rol_nameE;
    }
    
    // Display role with multiple lines if needed
    $startY = 61; // Move down to leave margin from picture (was 59)
    foreach ($roleLines as $index => $line) {
        $this->SetFont('Arial','B',6); // Reduce font size from 7 to 6
        $lineLength = strlen($line);
        if($lineLength > 13 && $lineLength < 17){
            $this->Text(16, $startY, $line);   
        }
        else if($lineLength >= 17 && $lineLength <= 19){
            $this->Text(14, $startY, $line);     
        }
        else if($lineLength > 19 && $lineLength <= 22){
            $this->Text(12, $startY, $line);         
        }
        else if($lineLength > 22){
            $this->Text(10, $startY, $line);      
        }
        else{
            $this->Text(19, $startY, $line);    
        }
        $startY += 2.5; // Reduce line spacing from 3 to 2.5
    }
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
$pdf->SetTitle('Staff CARD');

$staff=$_REQUEST['st'];
if($staff == NULL)
{
	echo "Sorry. Staff ID is mandatory";
}
// get role
$role=$conn->prepare("SELECT role_id FROM tbl_users WHERE Identification='".$staff."' LIMIT 1");
$role->execute();
$data_role=$role->fetch();
$role_id=$data_role['role_id'];
$get_role=$conn->prepare("SELECT role FROM  tbl_user_roles WHERE role_id='".$role_id."' ");
$get_role->execute();
$data_role_name=$get_role->fetch();
$rol_name=$data_role_name['role'];
// get university data
$sql0=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");

// get member's data
$sql = $conn->prepare("SELECT * FROM tbl_staff_info WHERE staff_id = '".$staff."'");

try {
    $sql0->execute();
    $sql->execute();
    
    $date=date("Y-m-d");
    
    $udata=$sql0->fetch();
    $data = $sql->fetch();
    
    $fname = $data['first_name'];
    $lname = $data['family_name'];
    $phone = $data['phone'];
    $stImg = '../../employees/'.$data['staff_image'];
    $logo='../../..'.$udata['logo'];
    $webst=$udata['website'];
    $post = $data['post'];
    
}catch (PDOException $ex) {
    echo $ex->getMessage();
}

$pdf->Image('../img/s1.png',-0.5,-0.5,54.2,85.9);
$pdf->Image($stImg,13.8,25,25.5,23.95);

$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(35);
$pdf->SetX(0);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',9);
$pdf->cell(54,20,$fname.' '.$lname,0,0,'C');
// $pdf->Text(19,72,$phone);
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(255,255,255);
$pdf->Text(19,79,$webst);
$filename='card_front_'.$staff;
$pdf->Output($filename.'.pdf','I');
$pdf->Output();

exit;
ob_end_flush();
?>