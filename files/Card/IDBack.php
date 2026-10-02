<?php
    ob_start(); 
    require('../../fpdf/fpdf.php');
    require('../../meet/con.php');
    
    //Instanciation of inherited class
    $pdf=new FPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('L', array(85.60, 53.98));
    $pdf->SetFont('Times','',12);
    
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('Student CARD');
    $pdf->SetAutoPageBreak(false);
    $regno=$_REQUEST['stu'];
    $sql = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
    $sql->execute();
    $data = $sql->fetch();
    
    $sql2 = $conn->prepare("SELECT * FROM tbl_specialization ORDER BY splz_id DESC");
    $sql2->execute();
    $sql3 = $conn->prepare("SELECT 
                                r.prg_type,
                                r.level_id,
                                ad.fname, 
                                ad.lname,
                                d.dept_full_name,
                                s.splz_full_name,
                                l.level_full_name ,
                                fac.fac_full_name
                            FROM tbl_register_program_ug r
                                INNER JOIN tbl_admission ad ON r.reg_no = ad.reg_no
                                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                INNER JOIN tbl_department d ON r.dept_id = d.dept_id
                                INNER JOIN tbl_faculty fac ON s.fac_id =fac.fac_id 
                                INNER JOIN tbl_level l ON r.level_id = l.level_id
                            WHERE r.reg_no = '".$regno."' AND r.reg_active=1 ORDER BY r.reg_prg_id DESC LIMIT 1"); 
                            
    $sql3->execute();
    $data2 = $sql->fetch();
    //check maximum possible level
        $checkMaxLevNo = $conn->prepare("SELECT MAX(level_no) as level_no FROM tbl_level WHERE prg_type='".$data2['prg_type']."'");
        $checkMaxLevNo->execute();
        $maxLevNo = $checkMaxLevNo->fetch();
        $maxLevNo = $maxLevNo['level_no'];
        
        //check current level
        $checkCurLevNo = $conn->prepare("SELECT level_no FROM tbl_level WHERE level_id='".$data2['level_id']."'");
        $checkCurLevNo->execute();
        $curLevNo = $checkCurLevNo->fetch();
        $curLevNo = $curLevNo['level_no'];
        
        //level difference
        $diff = $maxLevNo-$curLevNo;
        $yrValid = date("Y")+$diff;
    $pdf->Image('img/student_back.png', 0, 0, 85.60, 53.98);
    $pdf->SetFont('Times', 'B', 9);
    $pdf->Text(6,4,'This card is a property of '.$data['full_name'].' if');
    $pdf->Text(6,9,'found please return or contact us on '.$data['phone']);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Text(6,48,'Expiry Date : ');
    $pdf->Text(6, 51, 'December, '.$yrValid);
    $pdf->Image('img/Stump.png', 20, 15, 38, 32);

    $pdf->setY(-7);


    $filename= "card_back_".$_REQUEST['stu'];
    $pdf->Output($filename.'.pdf','I');
    $pdf->Output();

    exit;
    ob_end_flush();
?>