<?php
ob_start(); 
include('../fpdf/fpdf.php');
include('../../meet/con.php');


    // request vars
    
    $id =$_REQUEST['identity'];
    $class =$_REQUEST['class'];
    $month ='%'.$_REQUEST['month'].'%';
    $name ='%'.$_REQUEST['name'].'%';
    $hasAttended =0;
    
try{    
    if(isset($_REQUEST['class']) && isset($_REQUEST['name']) && isset($_REQUEST['identity'])){
        
        $pdf =new FPDF();
        $pdf->AddPage();
          
        // margins 
        $pdf->SetLeftMargin(20);
        // $pdf->SetRightMargin(20);        
        $pdf->image('../../files/img/lg_stumis.png', 10,1,20,20);
        
        // vars
        $specName =$conn->prepare("select *from tbl_specialization where splz_id =:splz_id");
        $specName->bindParam(':splz_id',$class);
        $specName->execute();
        $theSpec =$specName->fetch();          
        
        $attendanceDetails=$conn->prepare("SELECT * from tbl_class_attendance where specialization =:spec_id and date like :date_id and attendance_lecturer =:lecturer_id and attendance_session like :session_id group by specialization");
        $attendanceDetails->bindParam(':date_id', $month, PDO::PARAM_STR);
        $attendanceDetails->bindParam(':spec_id', $class, PDO::PARAM_INT);
        $attendanceDetails->bindParam(':lecturer_id', $id, PDO::PARAM_STR);
        $attendanceDetails->bindParam(':session_id', $name, PDO::PARAM_STR);
        $attendanceDetails->execute(); 
        $theDetails =$attendanceDetails->fetch();
        
        $module=$conn->prepare("SELECT * from modules where module_id =:module_id");
        $module->bindParam(':module_id', $theDetails['module_id'], PDO::PARAM_INT);
        $module->execute(); 
        $theModule =$module->fetch();    
        
        $moduleTerm=$conn->prepare("SELECT * from tbl_modules where splz_id =:splz_id and mod_id =:module_id and level_id =:level");
        $moduleTerm->bindParam(':module_id', $theDetails['module_id'], PDO::PARAM_STR);
        $moduleTerm->bindParam(':splz_id', $theDetails['specialization'], PDO::PARAM_STR);
        $moduleTerm->bindParam(':level', $theDetails['level'], PDO::PARAM_STR);
        $moduleTerm->execute(); 
        $theTerm =$moduleTerm->fetch();               
        
        $list=$conn->prepare("SELECT * from tbl_class_attendance where specialization =:spec and date like :date and attendance_lecturer =:lecturer and attendance_session like :session");
        $list->bindParam(':date', $month, PDO::PARAM_STR);
        $list->bindParam(':spec', $class, PDO::PARAM_INT);
        $list->bindParam(':lecturer', $id, PDO::PARAM_STR);
        $list->bindParam(':session', $name, PDO::PARAM_STR);
        $list->execute();
        
        $programType =$conn->prepare("select prg_type_short_name from tbl_program_type where prg_type_id =:prg_type_id");
        $programType->bindParam(':prg_type_id',$theDetails['program_type']);
        $programType->execute();
        $theType =$programType->fetch();
                            
        $theTypeName =$theType['prg_type_short_name'];
        $type  =$theDept['prg_type'];lik
        
        $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
        $prograMode->bindParam(':prg_mode_id',$theDetails['program_mode']);
        $prograMode->execute();
        $theMode =$prograMode->fetch();
                                            
        $theModeName =$theMode['prg_mode_full_name'];  
        
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(0,10," Module: ".$theModule['module_name']."Date: ".$theDetails['date'], 0, 0, 'C');
        $pdf->Ln(12);
        
        $pdf->Cell(0,10,$theSpec['splz_full_name']." Level: ".$theDetails['level']." Term: ".$theTerm['term_id']." Program-Type: ".$theTypeName." Program-Mode: ".$theModeName, 0, 0, 'C');
        
        $pdf->SetLineWidth(0.1);
        $pdf->Line(30, 30, 190, 30);
        
        $pdf->Ln();
        $pdf->SetY($pdf->GetY()+8);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetX($pdf->GetX()+15);
        $pdf->Cell(40,10,'Reg_number',1);
        $pdf->Cell(45,10,'Attendance Status',1);
        $pdf->Cell(40,10,'Additional Info',1);
            
        $pdf->Ln(); 
        
        while($theList =$list->fetch()){
  
            $pdf->SetAuthor('mis.itec.rw');
            $pdf->SetTitle('Attendance List');
            
            $pdf->SetFont('Arial','',14);
            $pdf->SetX($pdf->GetX()+15);
            $pdf->Cell(40,10,$theList['student_reg_number'],1);
            
            if($theList['attendance_status'] ==1){
                $hasAttended ="Attended";
            }
            else{
                $hasAttended ="Not attended";
            }
            $pdf->Cell(45,10,$hasAttended,1);
            $pdf->Cell(40,10,$theList['additional_info'],1);
            
             $pdf->Ln();
        }
    }
}
catch(Exception $exc){
    $exc->getMessage();
    $exc->getTraceAsString();
}
    $pdf->Output($theSpec['splz_short_name']."_attendance.pdf",'I');

?>