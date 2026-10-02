<?php
    include ('../meet/con.php');
try{
    $prg_type=$_POST['prg_type'];
	$fac=$_POST['fac_id'];
	$dept=$_POST['dept_id'];
	$splz=$_POST['splz_id'];
	$intake=$_POST['intake_id'];
	$level=$_POST['level_id'];
	$mode=$_POST['mode'];
    $getStudents = $conn->prepare("SELECT 
                                        r.reg_no,
                                        ad.fname,
                                        ad.lname,
                                        gr.cum_marks,
                                        gr.grad_cycle_id,
                                        gr.splz_id,
                                        ad.father_names,ad.ID,ad.mother_names,gr.prg_award_id,
                                        gr.l1_marks,gr.l2_marks,gr.l3_marks,gr.l4_marks,gr.l5_marks,
                                        ad.nationality
                                        
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                        INNER JOIN tbl_graduants gr ON r.reg_no=gr.reg_no
                                        
                                    WHERE
                                        r.prg_type='".$prg_type."'  AND 
                                        r.fac_id='".$fac."' AND 
                                        r.dept_id='".$dept."' AND 
                                        r.splz_id='".$splz."' AND 
                                        
                                        r.prg_mode_id='".$mode."' 
                                       
                                        group by ad.reg_no
                                    ");
                                    
                                    //  r.fac_id='".$fac."' AND 
                                    //     r.dept_id='".$dept."' AND
                                    //     r.splz_id='".$splz."' AND
                                    //      gr.splz_id='".$splz."' AND
                                    //     r.intake_id='".$intake."' AND
                                    //     r.prg_mode_id='".$mode."' 
    
    $getStudents->execute();
    
    $getSpecs = $conn->prepare("SELECT splz_id,splz_full_name,totalCredits,degree_name FROM tbl_specialization WHERE splz_id='".$splz."' ");
    $getSpecs->execute();
    $specs=$getSpecs->fetch();
    
    $spavname=$specs['splz_full_name'];
    $creditsT=$specs['totalCredits'];
    $degree_name=$specs['degree_name'];
    
    $getSpFacult= $conn->prepare("SELECT fac_full_name,fac_short_name FROM tbl_faculty WHERE fac_id ='".$fac."' ");
    $getSpFacult->execute();
    $sFacult=$getSpFacult->fetch();
    $facName=$sFacult['fac_full_name'];
    
    $getSAcada = $conn->prepare("SELECT acad_cycle_id  FROM  tbl_intake WHERE intake_id='".$intake."' ");
    $getSAcada->execute();
    $sAcada=$getSAcada->fetch();
    $grad_cycle_id=$sAcada['acad_cycle_id'];
    
    
     
    
   
?>  

<input type="hidden" value="<?php echo $grad_cycle_id ?>" name="grad_cycle_id" >
<input type="hidden" value="<?php echo $degree_name ?>" name="prg_award_id" >
<input type="hidden" value="<?php echo $splz ?>" name="spec_id" >
<input type="hidden" value="<?php echo $dept ?>" name="prg_id" >
<input type="hidden" value="<?php echo $mode ?>" name="prg_mode_id" >

 <hr/>
 
<div class="table-responsive">
<table class="table table-hover table-sm"   width="200%" id="graduationList_table">
    <thead>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">FACULTY:<small><?php  echo " ".$facName ?> </small></h6></th></tr>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">ACADEMIC PROGRAM:<small><?php  echo " ".$spavname ?></small> </th></h6> </tr>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">AWARD:  <small><?php  echo " ".$degree_name ?> </small></h6></th></tr>
        <tr>
            <th scope="col" colspan="5">STUDENT IDENTIFICATION</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="3">ADMISSION CRITERIA</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="2">YEAR STUDY</th>
            <th scope="col" colspan="5"> Grade/Marks</th>
            <th scope="col" class="d-none d-sm-table-cell">Eligible Yes/No (HEC)</th>
        </tr>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student Code</th>
            <th scope="col" class="d-none d-sm-table-cell">Names (s)</th>
            <th scope="col" class="d-none d-sm-table-cell">Father's Names (s)</th>
            <th scope="col" class="d-none d-sm-table-cell"> Mother's Names (s)</th>
            <th scope="col" class="d-none d-sm-table-cell"> ID No / Passport No</th>
             <th scope="col" class="d-none d-sm-table-cell"> Nationality</th>
            
            
            <th scope="col" class="d-none d-sm-table-cell"> Degree</th>
            <th scope="col" class="d-none d-sm-table-cell"> Eligible Yes/No</th>
            <th scope="col" class="d-none d-sm-table-cell"> No of Credits transfered</th>
             
            <th scope="col" >Registration</th>
            <th scope="col" >completion</th>
             <th scope="col" >Level 1</th>
             <th scope="col" >Level 2</th>
             <!--<th scope="col" >Level 3</th>-->
             <!--<th scope="col" >Level 4</th>-->
            <th scope="col" > Grade/Marks</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody id="contents">
    <?php
        $i=0;
        $toveryM=0;
        
        while($students=$getStudents->fetch()){
            $i++;
            $numberModule=0;
            $everyCredit=0;
            $everyMarks=0;
            $credpts=0;
            $tcredpts=0;  
            $key_id= $students['reg_no'];
            
            $father_names=$students['father_names'];
            $mother_names=$students['mother_names'];
            $ID=$students['ID'];
            $prg_award_id=$students['prg_award_id'];
            
    $getSAcada22 = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$students['grad_cycle_id']."' ");
    $getSAcada22->execute();
    $sAcada22=$getSAcada22->fetch();  
    $complete=substr($sAcada22['acad_year'],5,20); 
    
      $sqlLastL=$db->prepare("select intake_id from tbl_register_program_ug where reg_no='".$students['reg_no']."' and splz_id='".$students['splz_id']."'   order by level_id asc");
            $sqlLastL->execute();
            $resLastL=$sqlLastL->fetch();
            $intake3=$resLastL['intake_id'];
    
      $getAcadYear = $conn->prepare("SELECT acad_cycle_id  FROM  tbl_intake WHERE intake_id='".$intake3."' ");
    $getAcadYear->execute();
    $acad_year=$getAcadYear->fetch();
    $acad_id=$acad_year['acad_cycle_id'];
    
      $getSAcada22Reg = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$acad_id."' ");
    $getSAcada22Reg->execute();
    $sAcada22Reg=$getSAcada22Reg->fetch();  
    
    $getnationality = $conn->prepare("SELECT nationality  FROM  tbl_nationality WHERE nat_id='".$intake."' ");
    $getnationality->execute();
    $nationality=$getnationality->fetch();
    $grad_cynationality=$nationality['nationality'];
    $completeReg=substr($sAcada22Reg['acad_year'],0,4); 
            // $getmarks = $conn->prepare("SELECT * FROM `tbl_markby_module` WHERE `reg_no` = '".$students['reg_no']."' and enrolled=1 group by module_id ");
            // $getmarks->execute();
            // $countM=$getmarks->rowCount();
            
            // while($marks=$getmarks->fetch()){
            //     $numberModule++;
            //     $gettotMarks = $conn->prepare("SELECT sum(marks) as totMarks FROM `tbl_markby_module` WHERE `module_id` = '".$marks['module_id']."' and reg_no='".$marks['reg_no']."' and enrolled=1 ");
            //     $gettotMarks->execute();
            //     $totMarks=$gettotMarks->fetch();
            //     $everyMarks=$everyMarks+ $totMarks['totMarks'];  
        
            //     $getcredits = $conn->prepare("SELECT sum(module_credits) as totCredit FROM `tbl_modules` WHERE `module_id` = '".$marks['module_id']."' ");
            //     $getcredits->execute();
            //     $credits=$getcredits->fetch();
            //     if($totMarks['totMarks']>50){
            //         $everyCredit=$everyCredit+$credits['totCredit'];
            //         $credpts=$totMarks['totMarks']*$credits['totCredit'];
            //         $tcredpts=$tcredpts+$credpts;
            //     }
            //     else {
            //         $credpts=$totMarks['totMarks']*0;
            //         $tcredpts=$tcredpts+$credpts;
            //     }
            // }
        
        
        
            // $sqlgrade=$db->prepare('select grade_letter from tbl_grade where marks_from<="'.$everyMarks.'" and marks_upto>="'.$everyMarks.'"');
            // $sqlgrade->execute();
            // while($resgrade=$sqlgrade->fetch()){
            //     $ugrade2=$resgrade['grade_letter'];
            //     if($ugrade2=="F")$ucfail=1;
            // }
            
            // if ($everyCredit!=0){
            //     $wavg=$tcredpts/$everyCredit;
            //     $rwavg=$wavg;
            // }
            // else {
            //     $rwavg=0;
            //     $wavg=0;
            // }
            // $pass="";
            // $dec="";
            // $pcredits="";
            // if($wavg>=60){
            //     $grade="Pass"; 
            // }
            // else{
            //     $grade="Fail";
            // }
            
            #################
        
          
            
            // $sqlevelname=$db->prepare('select level_full_name from tbl_level where level_id="'.$resLastL['level_id'].'" ');
            // $sqlevelname->execute();
            // $resevelname=$sqlevelname->fetch();
    ?>
    <input type="hidden" value="<?php echo $resLastL['level_id'] ?>" name="level_<?php echo $key_id;?>" >
    <input type="hidden" value="<?php echo $rwavg ?>" name="cum_marks_<?php echo $key_id;?>" >
        
    <tr>
            <td><?php echo $i ?> </td>  
            <td><?php echo $students['reg_no']; ?> </td>
            <td class="d-none d-sm-table-cell"><?php echo $students['fname']." ".$students['lname']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $father_names ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $mother_names ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $ID ?></td>
             <td class="d-none d-sm-table-cell"><?php  echo $grad_cynationality ?></td>
            
            
            <td class="d-none d-sm-table-cell"><?php echo $prg_award_id ?></td>
            <td class="d-none d-sm-table-cell"> Yes</td>
            <td class="d-none d-sm-table-cell"></td>
            
            <td><?php echo $completeReg ?></td>  
            <td><b><?php echo $complete ?></b></td> 
            
            <td class="d-none d-sm-table-cell"><?php echo number_format($students['l1_marks'],1);  ?></td>
            <td class="d-none d-sm-table-cell"><?php echo number_format($students['l2_marks'],1);  ?></td>
            <!--<td class="d-none d-sm-table-cell"><?php echo number_format($students['l3_marks'],1);  ?></td>-->
            <!--<td class="d-none d-sm-table-cell"><?php echo number_format($students['l4_marks'],1);  ?></td>-->
            
            <td align="right"><?php echo number_format($students['cum_marks'],1);  ?></td>
            
            <td align="right"><i class="icon-copy fa fa-check" aria-hidden="true" style="color:green"></i></td>
        </tr>
        <?php }
        
          }
			catch (PDOException $ex){
			    echo $ex->getMessage();
			}
            
        ?>
    </tbody>
</table>  
</div>
 <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
<button type="button" class="btn btn-success" onclick="exportTableToExcel('graduationList_table','DB_graduation_List')"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>
                                                    
                                                    
                                                    