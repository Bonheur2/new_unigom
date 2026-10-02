<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('converter/image_converter.php');
    include ('../../meet/con.php');
    
    //Instanciation of inherited class
    $pdf=new FPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('L', array(85.60, 53.98));
    $pdf->SetFont('Times','',12);
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Student CARD');
    $pdf->SetAutoPageBreak(false);
    
    $regno=$_REQUEST['stu'];
    if($regno == NULL){
    	echo "Sorry. Student ID is mandatory";
    }
    // get university data
    $sql0=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
    
    // get student's data
    $sql = $conn->prepare("SELECT 
                                r.prg_type,
                                r.level_id,
                                ad.fname, 
                                ad.lname,
                                d.dept_full_name,
                                s.splz_full_name,
                                l.level_full_name  
                            FROM tbl_register_program_ug r
                                INNER JOIN tbl_admission ad ON r.reg_no = ad.reg_no
                                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                INNER JOIN tbl_department d ON r.dept_id = d.dept_id
                                INNER JOIN tbl_level l ON r.level_id = l.level_id
                            WHERE r.reg_no = '".$regno."' AND r.reg_active=1 ORDER BY r.reg_prg_id DESC LIMIT 1"); 
    $sql2 = $conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE tracking_id = '".$regno."' AND upload_doc like '%student_docs/photo%'");
    try {
        $sql0->execute();
        $sql->execute();
        $sql2->execute(); 
        
        $date=date("Y-m-d");
        
        $udata=$sql0->fetch();
        $data = $sql->fetch();
        $photo = $sql2->fetch();
        
        $fname = $data['fname'];
        $lname = $data['lname'];
        $stuImg = $photo['upload_doc'];
        $dept = $data['dept_full_name'];
        $splz = $data['splz_full_name'];
        $level = $data['level_full_name'];
        $date = date("Y-m-d");
        
        //check maximum possible level
        $checkMaxLevNo = $conn->prepare("SELECT MAX(level_no) as level_no FROM tbl_level WHERE prg_type='".$data['prg_type']."'");
        $checkMaxLevNo->execute();
        $maxLevNo = $checkMaxLevNo->fetch();
        $maxLevNo = $maxLevNo['level_no'];
        
        //check current level
        $checkCurLevNo = $conn->prepare("SELECT level_no FROM tbl_level WHERE level_id='".$data['level_id']."'");
        $checkCurLevNo->execute();
        $curLevNo = $checkCurLevNo->fetch();
        $curLevNo = $curLevNo['level_no'];
        
        //level difference
        $diff = $maxLevNo-$curLevNo;
        $yrValid = date("Y")+$diff;
        
    }catch (PDOException $ex) {
        echo $ex->getMessage();
    }

    $pdf->Image('img/front5.png', 0, 0, 85.60, 53.98);
    $pdf->SetTextColor(0, 0, 0);
    
    $image = "../..".$stuImg;
    $pdf->Image($image, 2.5, 20.5, 20, 20);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Text(2.5, 45, strtoupper($regno));
    
    $pdf->Text(25, 28, 'FIRST NAME: '.$fname);
    $pdf->Text(25, 33, 'LAST NAME: ' .$lname);
    $pdf->Text(25, 38, 'PROGRAM: ');
    $pdf->setXY(36.5, 35.8);
    $pdf->MultiCell(48, 3, $splz);
    
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Text(30, 52, 'Validity: December, '.$yrValid);
    
    //watermark
    $pdf->SetFont('Times','B',18);
    $pdf->SetTextColor(255,192,222);
    $pdf->Text(20,30,'');
    
    $filename='card_front_'.$regno;
    $pdf->Output($filename.'.pdf','I');
    $pdf->Output();
    
    exit;
    ob_end_flush();
?>