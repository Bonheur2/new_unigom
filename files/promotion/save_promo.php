<?php 
    include ('../../meet/con.php');
?>

 <?php 

    $spec_id=$_POST['spec_id'];
    $year_id=$_POST['year_id'];
    $intake_id_new=$_POST['intake_id'];
    $intake_id2=$_POST['intake_id2'];
    $mode_id=$_POST['mode_id'];
    $fac_id=$_POST['fac_id'];
    $reg_no=$_POST['reg_no'];
            
    $sqModule=$conn->prepare("SELECT * FROM  tbl_modules  WHERE level_id='".$year_id."' and splz_id='".$spec_id."'  and fac_id='".$fac_id."' ORDER BY module_id ASC");
        $sqModule->execute();
        $i=1;
        while($lvModule=$sqModule->fetch()){
               
                $sqMarkData=$conn->prepare("SELECT * FROM  tbl_markby_module  WHERE module_id='".$lvModule['module_id']."' and intake_id='".$intake_id2."' 
                and reg_no='".$reg_no."' and status=1 and enrolled=1
                ORDER BY module_id ASC");
                $sqMarkData->execute(); 
                $MarkData=$sqMarkData->fetch();
                
                                                       
                $sqMark=$conn->prepare("SELECT * FROM  tbl_markby_module  WHERE module_id='".$lvModule['module_id']."' and intake_id='".$intake_id_new."' 
                and reg_no='".$reg_no."' and status=1
                 ORDER BY module_id ASC");
                $sqMark->execute();
                $dataMark=$sqMark->rowCount();
                if($dataMark>0){
                 // echo $reg_no." Exist<br>";  
                 echo 0;
                }
                 else{
                     if($MarkData['marks']<50  and !empty($MarkData['marks'])){
                         $i=100;
        $stmtu = $conn->prepare("UPDATE tbl_markby_module SET status=16 WHERE module_id='".$MarkData['module_id']."' and intake_id='".$intake_id2."' 
        and reg_no='".$reg_no."' and status=16 and enrolled=1 ");
        $stmtu->execute() ;
        
                
         $stmtu3 = $conn->prepare("UPDATE tbl_register_program_ug SET reg_active=14 WHERE splz_id='".$spec_id."' and intake_id='".$intake_id2."' 
        and reg_no='".$reg_no."'  ");
        $stmtu3->execute() ;
        
     
                    
                     }
                     
            
                    
                 }  
                 
                 ## module insert
                 
        //echo $MarkData['module_id']."/".$reg_no." Yes #".$intake_id2."#".$intake_id_new."#".$MarkData['marks']."<br>"; 
             
    $stmt2 = $conn->prepare("INSERT INTO tbl_markby_module(reg_no,module_id,intake_id,splz_id,status,enrolled) 
                            VALUES('".$reg_no."','".$lvModule['module_id']."','".$intake_id_new."','".$spec_id."',1,0)");
                    $stmt2->execute();
                                                        
                                                    }
              if($i==1){
    $stmtu3 = $conn->prepare("UPDATE tbl_register_program_ug SET reg_active=6 WHERE splz_id='".$spec_id."' and intake_id='".$intake_id2."' 
        and reg_no='".$reg_no."'  ");
        $stmtu3->execute() ;   
              }                                      
       
        
    $sqMarkUG=$conn->prepare("SELECT * FROM  tbl_register_program_ug  WHERE intake_id='".$intake_id2."' and fac_id='".$fac_id."'  and reg_no='".$reg_no."' and splz_id='".$spec_id."' ");
        $sqMarkUG->execute(); 
        $MarkUG=$sqMarkUG->fetch();
        
        $prg_type=$MarkUG['prg_type'];
        $fac_id=$MarkUG['fac_id'];
        $dept_id=$MarkUG['dept_id'];
        $spon_id=$MarkUG['spon_id'];
        $prg_id=$MarkUG['prg_id'];
             $day=date("Y-m-d");   
           $stmt4 = $conn->prepare("INSERT INTO tbl_register_program_ug(reg_no,intake_id,fac_id,splz_id,prg_type,dept_id,spon_id,prg_id,level_id,reg_date,prg_mode_id,acad_cycle_id) 
                      	VALUES('".$reg_no."','".$intake_id_new."','".$fac_id."','".$spec_id."','".$prg_type."','".$dept_id."','".$spon_id."','".$prg_id."','".$year_id."'
                      	,'".$day."','".$mode_id."','".$intake_id_new."')");
                $stmt4->execute();    
                                                    
                 echo 1;       
                    
                    
                    ?> 