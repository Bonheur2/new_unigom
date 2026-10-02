<?php
ob_start(); 
require('../../fpdf17/fpdf.php');
require('../../meet/con.php');
Header('Pragma: public');

$connection=$conn;

//Page header
function Header_($pdf, $conn){
    $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
    $sql->execute();
    $data=$sql->fetch();
    
    /**********************Logo**************************/
    $pdf->Image('../..'.$data['logo'],85,10,35);
    $pdf->setY(50);
    /********************Title****************************/
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(190,12,"STUDENT FILE",0,0,'C');
    $pdf->Ln(15);
}
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
    //Page footer
    function Footer(){
        $this->SetY(-20);
    	$this->SetFont('Times','',12);	
    	$this->Cell(260,20,'Page '.$this->PageNo().'/{nb}',0,0,'R');
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

    $pdf = new PDF();
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('STUDENT File');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    Header_($pdf, $conn);
    $pdf->SetFillColor(208, 240, 192);

    
    if (isset($_REQUEST['stu'])){
        $reg_no = trim($_REQUEST['stu']);
    }
    else{
        $reg_no = "";
    }
    $getPersonalInfo = $conn->prepare("SELECT ad.*,
                                            ac.acad_year,
                                            nat.nationality as natio,
                                            cnt.cntr_name as cname,
                                            provinces.provincename as pname,
                                            districts.namedistrict as dname,
                                            sectors.namesector as sname,
                                            cells.namecell as cellname,
                                            villages.VillageName as vname
                                        FROM tbl_admission ad 
                                            LEFT JOIN tbl_nationality nat ON ad.nationality=nat.nat_id
                                            LEFT JOIN tbl_country cnt ON ad.country=cnt.cntr_id
                                            LEFT JOIN provinces ON ad.province_id=provinces.provincecode
                                            LEFT JOIN districts ON ad.district_id=districts.districtcode
                                            LEFT JOIN sectors ON ad.sector=sectors.sectorcode
                                            LEFT JOIN cells ON ad.cell_id=cells.codecell
                                            LEFT JOIN villages ON ad.village_id=villages.CodeVillage
                                            LEFT JOIN tbl_acad_cycle ac ON ad.acad_cycle_id = ac.acad_cycle_id
                                        WHERE ad.reg_no='".$reg_no."'");
    $getPersonalInfo->execute();
    
    $getAcadInfo = $conn->prepare("SELECT splz.*
                                        FROM tbl_register_program_ug ug 
                                            LEFT JOIN tbl_specialization splz ON ug.splz_id=splz.splz_id
                                        WHERE ug.reg_no='".$reg_no."' ORDER BY ug.reg_prg_id ASC LIMIT 1");
    $getAcadInfo->execute();
    
    if($getPersonalInfo->rowCount()!=0){
        $personalInfo=$getPersonalInfo->fetch();
    
        try {
            ####################### Page 1 ################################
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(190,6,'I. PERSONAL DETAILS',0,0,'C', true);
        	$pdf->Ln(10);
        	$pdf->Rect(10, $pdf->getY(), 190, 180);
        	$pdf->Ln();
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(30,6,'FULL NAME',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(55,6,'Family: '.$personalInfo['lname'],0);
        	$pdf->Cell(55,6,'First: '.$personalInfo['fname'],0);
        	$pdf->Cell(45,6,'Middle: '.$personalInfo['mname'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->Cell(185,6,'Name(s) on previous records: '.$personalInfo['prevname'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(20,6,'EMAIL: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(70,6,$personalInfo['email'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(50,6,'TELEPHONE NUMBER: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(45,6,$personalInfo['phone'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(35,6,'DATE OF BIRTH: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(55,6,$personalInfo['dob'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(25,6,'GENDER: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(70,6,$personalInfo['gender'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(35,6,'NATIONALITY: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(55,6,$personalInfo['natio'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(45,6,'Country of residence: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(50,6,$personalInfo['cname'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(35,6,'ID/ Passport No.: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(150,6,$personalInfo['ID'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(40,6,'MARITAL STATUS: ',0);
        	$pdf->SetFont('Times','',12);
        	$pdf->Cell(145,6,$personalInfo['marital_status'],0);
        	$pdf->Ln(10);
        	$pdf->Cell(190,6,'____________________________________________________________________________________', 0, 0, 'C');
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(185,6,'MOTHER & FATHER Information: ',0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(25,6,'Mother Name: ',0);
        	$pdf->Cell(65,6,$personalInfo['mother_names'],0);
        	$pdf->Cell(25,6,'Father Name: ',0);
        	$pdf->Cell(70,6,$personalInfo['father_names'],0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->Cell(25,6,'Telephone No: ',0);
        	$pdf->Cell(65,6,$personalInfo['parent_phone'],0);
        	$pdf->Cell(25,6,'Telephone No: ',0);
        	$pdf->Cell(70,6,$personalInfo['ref_phone'],0);
        	$pdf->Ln(10);
        	$pdf->Cell(190,6,'____________________________________________________________________________________________', 0, 0, 'C');
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(185,6,'CURRENT CONTACT DETAILS: ',0);
        	$pdf->Ln(10);
        	
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(17,6,'Province: ',0);
        	$pdf->Cell(68,6,$personalInfo['pname'],0);
        	$pdf->Cell(15,6,'District: ',0);
        	$pdf->Cell(85,6,$personalInfo['dname'],0);
        	$pdf->Ln(10);
        	
        	
        	$pdf->Cell(5,6,'');
        	$pdf->Cell(15,6,'Street: ',0);
        	$pdf->Cell(80,6,$personalInfo['street'],0);
        	$pdf->Ln(10);
        	
        	
            ####################### Page 2 ################################
            $acadInfo = $getAcadInfo->fetch();
            $pdf->addPage();
            $pdf->setXY(10, 10);
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(190,6,'PROGRAM AND INTAKE',0,0,'L');
        	$pdf->Ln();
        	$pdf->Rect(10, $pdf->getY(), 190, 23);
        	$pdf->Ln();
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(25,6,'PROGRAM:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(90,6,$acadInfo['splz_full_name'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(28,6,'STUDENT ID:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(37,6,$reg_no,0);
        	$pdf->Ln();
        	$pdf->Cell(5,6,'');
        	$pdf->SetFont('Times','B',11);
        	
        	$pdf->Cell(45,6,'Intended Year of Entry:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(90,6,$personalInfo['acad_year'],0);
        	$pdf->Ln(20);
        	
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(190,6,'NEXT OF KIN/GUARDIAN (to be contacted in case of emergency)',0,0,'L');
        	$pdf->Ln();
        	$pdf->Rect(10, $pdf->getY(), 190, 35);
        	$pdf->Ln();
        	$pdf->Cell(5,8,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(15,8,'Name:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(170,8,$personalInfo['kin_name'],0);
        	$pdf->Ln();
        	$pdf->Cell(5,8,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(50,8,'Relationship to applicant:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(65,8,$personalInfo['kin_relation'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(20,8,'Address :',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(50,8,$personalInfo['kin_address'],0);
        	
        	$pdf->Ln();
        	$pdf->Cell(5,8,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(15,8,'Email:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(70,8,$personalInfo['kin_email'],0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(30,8,'Telephone No.:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(70,8,$personalInfo['kin_tel'],0);
        	$pdf->Ln(20);
        	
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(190,6,'II. PREVIOUS EDUCATION',0,0,'C', true);
        	$pdf->Ln(10);
        	
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(10,6,'S/N',1);
        	$pdf->Cell(75,6,'Institution Name',1);
        	$pdf->Cell(15,6,'From',1);
        	$pdf->Cell(15,6,'To',1);
        	$pdf->Cell(75,6,'Combination or Degree',1);
        	$pdf->Ln();
        	
        	$stmt = $conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu='".$reg_no."'");
        	$stmt->execute();
        	$i = 1;
        	while($row = $stmt->fetch()){
            	$pdf->SetFont('Times','',11);
            	$pdf->Cell(10,6,$i++,1);
            	$pdf->Cell(75,6,$row['school'],1);
            	$pdf->Cell(15,6,$row['year_from'],1);
            	$pdf->Cell(15,6,$row['year_to'],1);
            	$pdf->Cell(75,6,$row['award'],1);
            	$pdf->Ln();
        	}

        	$pdf->Ln(10);

            $pdf->SetFont('Times','B',12);
            $pdf->Cell(190,6,'III. Language Proficiency',0,0,'C', true);
        	$pdf->Ln(10);
        	
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(10,6,'S/N',1);
        	$pdf->Cell(30,6,'Language',1);
        	$pdf->Cell(30,6,'I Can only read',1);
        	$pdf->Cell(40,6,'I Can read and write',1);
        	$pdf->Cell(80,6,'I Can read, write and speak fluently',1);
        	$pdf->Ln();
        	
        	$stmt = $conn->prepare("SELECT * FROM tbl_language_pro WHERE stu='".$reg_no."'");
        	$stmt->execute();
        	$i = 1;
        	while($row = $stmt->fetch()){
            	$pdf->SetFont('Times','',11);
            	$pdf->Cell(10,6,$i++,1);
            	$pdf->Cell(30,6,$row['language'],1);
            	$pdf->Cell(30,6,$row['state']==1?'yes':'-',1);
            	$pdf->Cell(40,6,$row['state']==2?'yes':'-',1);
            	$pdf->Cell(80,6,$row['state']==3?'yes':'-',1);
            	$pdf->Ln();
        	}
        	
        	$pdf->Ln(10);
        	
            $stmt=$conn->prepare("SELECT c.*, cnt.cntr_name FROM tbl_applicant_church c INNER JOIN tbl_country cnt ON c.country = cnt.cntr_id WHERE c.stu='".$reg_no."'");
            $stmt->execute();
            $stuchurch=$stmt->fetch();
            
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(190,6,'IV. Religious belief',0,0,'C', true);
        	$pdf->Ln();
        	
        	
        	$pdf->Cell(5,8,'');
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(20,8,'Church:',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(30,8,$stuchurch['church'],0);
        	
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(15,8,'Country :',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(50,8,$stuchurch['cntr_name'],0);
        	
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(15,8,'City :',0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(50,8,$stuchurch['city'],0);
        	
        	$pdf->Ln(20);
        	
            $pdf->addpage();
        	
        	$pdf->SetFont('Times','',11);
        	$pdf->MultiCell(0,6,'I, '.$personalInfo['fname'].' '.$personalInfo['mname'].' '.$personalInfo['lname'].', am in substantial agreement with the doctrinal and faith Statement of Njala University',0);
        	$pdf->Ln(10);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(95,6, 'Signature', 0);
        	$pdf->SetFont('Times','',11);
        	$pdf->Cell(95,6, 'Date ', 0, 0, 'R');
        	$pdf->Ln(10);
        	$pdf->SetFont('courier','BI',11);
        	$pdf->Cell(95,6, $personalInfo['fname'].' '.$personalInfo['mname'].' '.$personalInfo['lname'], 0);
        	$pdf->SetFont('Times','B',11);
        	$pdf->Cell(95,6, date('Y/m/d'), 0, 0, 'R');
            
        }catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
	$dname=$reg_no.'_'.date('Y-m-d');
	$pdf->Output($dname.'.pdf','I');

	exit;
    ob_end_flush();
?>
