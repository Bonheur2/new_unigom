<?php
ob_start(); 
include('../fpdf/fpdf.php');
include('../../meet/con.php');

$class =$_REQUEST['class'];
$levelId =$_REQUEST['levelId'];
$termId =$_REQUEST['termId'];

$prgType =$_REQUEST['type'];
$prgMode =$_REQUEST['mode'];
$singleClassName ="";

// capture single timetable

if(isset($class) && isset($levelId) && isset($termId)){
    
    // class names
    $classname =$conn->prepare("select splz_id, splz_full_name  from tbl_specialization where splz_id='".$class."'");
    $classname->execute();
    $theClass =$classname->fetch(); 
    $singleClassName=$theClass['splz_full_name'];
    
    // type
    $programType =$conn->prepare("select prg_type_full_name from tbl_program_type where prg_type_id =:prg_type_id");
    $programType->bindParam(':prg_type_id',$prgType);
    $programType->execute();
    $theType =$programType->fetch();
                    
    $theTypeName =$theType['prg_type_full_name'];
                    
    // mode
    $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
    $prograMode->bindParam(':prg_mode_id',$prgMode);
    $prograMode->execute();
    $theMode =$prograMode->fetch();
                    
    $theModeName =$theMode['prg_mode_full_name'];    
    
    $pdf =new FPDF('L');
    $pdf->AddPage();
    
    // margins 
    // $pdf->SetLeftMargin(20);
    // $pdf->SetRightMargin(20);
    $pdf->SetAuthor('STUMIS');
    $pdf->SetTitle('MIS Timetable');
    
    $pdf->image('../../img/logo/act.png', 10,10,40,40);
        
    $pdf->Ln(4);
    
    $pdf->SetFont('Times','B',14);
    $pdf->SetTextColor(50,60,100);
    
    $levelId =trim($levelId);
    $pdf->setY(50);
    $pdf->Cell(0, 10, "$singleClassName", 0, 1, 'L');
    $pdf->Ln(-5);
    $pdf->Cell(0, 10, "Level: $levelId", 0, 1, 'L');
    $pdf->Ln(-5);
    $pdf->Cell(0, 10, "Term: $termId", 0, 1, 'L');
    $pdf->Ln(-5);
    $pdf->Cell(0, 10, "Mode: $theModeName", 0, 1, 'L');
    $pdf->SetLineWidth(0.1);
    $pdf->Line(10, 80, 280, 80);
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(45,10,'Hours/Days',1);
    $days =$conn->prepare("select *from tbl_t_days");
    $days->execute();
    while($theLearningDays =$days->fetch()){
        
        /**
         * draw columns
         */
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(45,10,$theLearningDays['day_name'],1);
        
    }
        $hours =$conn->prepare("select *from tbl_t_hours");
        $hours->execute();
        $pdf->Ln();
        while($theDayHour =$hours->fetch()){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(45,10,$theDayHour['hour_lower_limit'].'-'.$theDayHour['hour_upper_limit'],1);
            
            /**
             * go through all
             * days
             */
            $insideDay =$conn->prepare("select *from tbl_t_days");
            $insideDay->execute();
            while($theInsideDay =$insideDay->fetch()){        
            
                // select modules
        		$moduleDay =$conn->prepare("select *from tbl_t_schedule where program_type = :prgType and program_mode = :prgMode and sub_class_id = :sub_class
                and learning_day_id = :day and hour_id = :hour and level_id = :level and term_id = :term and class_module_id >0");
                                    									                   
                $moduleDay->bindParam(':prgType', $_REQUEST['type'], PDO::PARAM_INT);
                $moduleDay->bindParam(':prgMode', $_REQUEST['mode'], PDO::PARAM_INT);
                $moduleDay->bindParam(':sub_class', $_REQUEST['class'], PDO::PARAM_INT);
                $moduleDay->bindParam(':day', $theInsideDay['day_id'], PDO::PARAM_INT);
                $moduleDay->bindParam(':hour', $theDayHour['hour_id'], PDO::PARAM_INT);
                $moduleDay->bindParam(':level', $_REQUEST['levelId'], PDO::PARAM_INT);
                $moduleDay->bindParam(':term', $_REQUEST['termId'], PDO::PARAM_INT);
                $moduleDay->execute();
                $theModule =$moduleDay->fetch();
    									                   
    			// module 
                $moduleCode =$conn->prepare("select *from tbl_modules where splz_id = :splz_id and level_id = :level_id and term_id = :term_id and mod_id = :module");
                                    									                   
                $moduleCode->bindParam(':splz_id', $theModule['sub_class_id'], PDO::PARAM_INT);
                $moduleCode->bindParam(':level_id', $_REQUEST['levelId'], PDO::PARAM_INT);
                $moduleCode->bindParam(':term_id', $_REQUEST['termId'], PDO::PARAM_INT);
                $moduleCode->bindParam(':module', $theModule['class_module_id'], PDO::PARAM_INT);
                $moduleCode->execute();
                $theModuleCode =$moduleCode->fetch();
                                    									                   
                //module code
                $thisModCode =$conn->prepare("select *from modules where module_id = :module_id");
                                    									                   
                $thisModCode->bindParam(':module_id', $theModule['class_module_id'], PDO::PARAM_INT);
                $thisModCode->execute();
                $theCode =$thisModCode->fetch();            									                   
                                    									                   
                //room name
                $roomName =$conn->prepare("select *from tbl_block_rooms where room_id = :room_id");
                                    									                   
                $roomName->bindParam(':room_id', $theModule['room_id'], PDO::PARAM_INT);
                $roomName->execute();
                $theRoomName =$roomName->fetch();
                                    									                   
                // module teacher 
                $modTeacher =$conn->prepare("select staff_id, staff_first_name, staff_family_name from tbl_staff where staff_id 
                IN(select staff_id from  tbl_module_leader where module_id = :module_leader)");
                                									                   
                $modTeacher->bindParam(':module_leader', $theModuleCode['module_id'], PDO::PARAM_INT);
                $modTeacher->execute();
                $theTeacher =$modTeacher->fetch();
                // echo "Type: ".$_REQUEST['type'];
                if($moduleDay->rowCount()==1){
                	if($theModule['class_module_id'] ==0){
                        $pdf->SetFont('Arial','',8);
                        $pdf->Cell(45,10,'',1);            	}
                	else{
                        $pdf->SetFont('Arial','',6);
                        $pdf->Cell(45,10,$theCode['module_code']."    [".$theModuleCode['module_credits']."] ".$theRoomName['room_code'],1);
                	}
            	}
            	else if($moduleDay->rowCount()==2){
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(45,10,'Module Id '.$theModule['class_module_id'].'Room Id '.$theModule['room_id'],1);          	    
            		
            		$theModule =$moduleDay->fetch();
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(45,10,"Module Id ". $theModule['class_module_id']."Room Id ".$theModule['room_id'],1); 
            	}
        		else{
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(45,10,'',1); 
                }        	
            }
            $pdf->Ln();
        }
}
else{
    $pdf =new FPDF('L');
	$classTimeTable =$conn->prepare("select * from tbl_t_schedule where term_id ='".$_REQUEST['term']."' GROUP BY sub_class_id");
    $classTimeTable->execute();
	$counter =0;
	while($theTable =$classTimeTable->fetch()){
									      
	    //   level 
    	$classLevel =$conn->prepare("select level_id from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."'  GROUP BY level_id");
    	$classLevel->execute();
    	while($theClassLevel =$classLevel->fetch()){
    									  
            // 	  the term
            $levelId =$theClassLevel['level_id'];
        	$levelTerm =$conn->prepare("select * from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."' and level_id ='".$theClassLevel['level_id']."' GROUP BY term_id");
        	$levelTerm->execute();  
        	while($theLevelTerm =$levelTerm->fetch()){
        	    
                
                $pdf->AddPage();
                
                // margins 
                // $pdf->SetLeftMargin(20);
                // $pdf->SetRightMargin(20);
                $pdf->SetAuthor('STUMIS');
                $pdf->SetTitle('Timetable');
                
                $pdf->image('../../img/logo/act.png', 10,1,20,20); 
                $pdf->Ln(10);
                
                // vars
        	   
            	$levelTermId =$theLevelTerm['term_id'];
            	$intake =$theLevelTerm['intake_id'];
            	$counter +=1;
            									      
            	//   class names
            	
            	$classToPrint =$conn->prepare("select splz_full_name from tbl_specialization where splz_id ='".$theTable['sub_class_id']."'");
            	$classToPrint->execute(); 
            	$theClassToPrint =$classToPrint->fetch();
            	
                // type
                $programType =$conn->prepare("select prg_type_full_name from tbl_program_type where prg_type_id =:prg_type_id");
                $programType->bindParam(':prg_type_id',$theTable['program_type']);
                $programType->execute();
                $theType =$programType->fetch();
                                
                $theTypeName =$theType['prg_type_full_name'];
                                
                // mode
                $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
                $prograMode->bindParam(':prg_mode_id',$theTable['program_mode']);
                $prograMode->execute();
                $theMode =$prograMode->fetch();
                                
                $theModeName =$theMode['prg_mode_full_name'];                	
            									    
            	// $the class name
            	
            	$class =$theClassToPrint['splz_full_name'];
        	
                $pdf->SetFont('Times','B',14);
                $pdf->SetTextColor(50,60,100);
                $pdf->cell(50,10,$counter,0);
                $pdf->Cell(100,10,$class." Level: ".$levelId." Term: ".$levelTermId." Type: ".$theTypeName." Mode: ".$theModeName,0,1,'C');
                
                
                $pdf->Ln(7); 
                
                $pdf->SetFont('Arial','B',14);
                $pdf->Cell(35,10,'Hours/Days',1);
                
                $workDay =$conn->prepare("select *from tbl_t_days");
                $workDay->execute();
                        									    
                while($theLearningDays=$workDay->fetch()){
    
                    /**
                     * draw columns
                     */
                    $pdf->SetFont('Arial','',14);
                    $pdf->Cell(30,10,$theLearningDays['day_name'],1);
                }	     
                $hours =$conn->prepare("select *from tbl_t_hours");
                $hours->execute();
                $pdf->Ln();
                while($theHours =$hours->fetch()){
                    
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(35,10,$theHours['hour_label'],1);
                            
                    /**
                    * go through all
                    * days
                    */
                    $days =$conn->prepare("select *from tbl_t_days");
                    $days->execute();
                    while($theDays =$days->fetch()){
                        
                        $moduleDay =$conn->prepare("select *from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."'
                        and learning_day_id ='".$theDays['day_id']."' and hour_id ='".$theHours['hour_id']."' and level_id ='".$levelId."' and term_id ='".$levelTermId."' and class_module_id >0");
                        $moduleDay->execute();
                        $theModule =$moduleDay->fetch();
                        									                   
                        // module 
                        $moduleCode =$conn->prepare("select *from tbl_modules where splz_id ='".$theModule['sub_class_id']."'
                        and level_id ='".$levelId."' and term_id ='".$levelTermId."' and mod_id='".$theModule['class_module_id']."'");
                        $moduleCode->execute();
                        $theModuleCode =$moduleCode->fetch();
                        
            			//module code
            			$thisModCode =$conn->prepare("select *from modules where module_id ='".$theModule['class_module_id']."'");
            			$thisModCode->execute();
            			$theCode =$thisModCode->fetch();            									                                           					                        
                        									                   
                        //room name
                        $roomName =$conn->prepare("select *from tbl_block_rooms where room_id ='".$theModule['room_id']."'");
                        $roomName->execute();
                        $theRoomName =$roomName->fetch();
                        
                        if($moduleDay->rowCount()==1){
                            if($theModule['class_module_id'] ==0){
                                $pdf->SetFont('Arial','',8);
                                $pdf->Cell(30,10,'',1);
                            }
                            else{
                                $pdf->SetFont('Arial','',6);
                                $pdf->Cell(30,10,$theCode['module_code']."    [".$theModuleCode['module_credits']."] ".$theRoomName['room_code'],1);
                            }
                        }
                        else if($moduleDay->rowCount()==2){
                            $pdf->SetFont('Arial','',12);
                            $pdf->Cell(30,10,$theModule['class_module_id']."    [".$theModuleCode['module_credits']."]",1);          	    
                    		
                    		$theModule =$moduleDay->fetch();
                            $pdf->SetFont('Arial','',12);
                            $pdf->Cell(30,10,$theModule['class_module_id']."    [".$theModuleCode['module_credits']."]",1); 
                        }
                		else{
                            $pdf->SetFont('Arial','',8);
                            $pdf->Cell(30,10,'',1); 
                        }
                    }
                    $pdf->Ln();
                }
        	}
        	
    	}
	}
}
if(isset($class) && isset($levelId) && isset($termId)){
    $pdf->Output($singleClassName."_level ".trim($levelId)."_Timetable.pdf",'I');
}
else{
   $pdf->Output("School_Timetables.pdf",'I'); 
}
?>