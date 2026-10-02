<style>
    th span {
        transform-origin: 0 50%;
        transform: rotate(-90deg); 
        white-space: nowrap; 
        display: block;
        position: absolute;
        bottom: 0;
        left: 50%;
    }
</style>
<?php
    include ('../meet/con.php');

    $prg_type=$_POST['prg_type'];
	$fac=$_POST['fac_id'];
	$dept=$_POST['dept_id'];
	$splz=$_POST['splz_id'];
	$grad=$_POST['acad_grad'];
    $getStudents = $conn->prepare("SELECT 
                                        r.reg_no,
                                        ad.fname,
                                        ad.lname,
                                        gr.cum_marks,
                                        gr.grad_cycle_id,
                                        gr.splz_id,
                                        ad.father_names,
                                        ad.ID,
                                        ad.mother_names,
                                        gr.prg_award_id
                                        
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                        INNER JOIN tbl_graduants gr ON r.reg_no=gr.reg_no
                                        
                                    WHERE
                                        r.prg_type='".$prg_type."' AND 
                                        r.fac_id='".$fac."' AND 
                                        r.dept_id='".$dept."' AND
                                        r.splz_id='".$splz."' AND
                                        gr.splz_id='".$splz."' AND
                                        gr.grad_cycle_id='".$grad."' AND
                                        r.reg_active in (1,6) 
                                    GROUP BY ad.reg_no
                                    ");
    
    $getStudents->execute();
    
    $getProgType = $conn->prepare("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id='".$prg_type."' ");
    $getProgType->execute();
    $progType = $getProgType->fetch();
    
    $progName = $progType['prg_type_full_name'];
    
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
?>  

<div class="table-responsive">
<table class="table table-hover table-sm"   width="200%" id="graduationList_table">
    <thead>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">PROGRAM TYPE:<small><?php  echo " ".$progName ?> </small></h6></th></tr>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">FACULTY:<small><?php  echo " ".$facName ?> </small></h6></th></tr>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">ACADEMIC PROGRAM:<small><?php  echo " ".$spavname ?></small> </th></h6> </tr>
        <tr><th scope="col" colspan="13"><h6 style="text-align:left">AWARD:  <small><?php  echo " ".$degree_name ?> </small></h6></th></tr>
        <tr>
            <th scope="col" colspan="6">STUDENT IDENTIFICATION</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="4">ADMISSION CRITERIA</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="2">YEAR OF STUDY</th>
            <th scope="col" >GRADE</th>
            <th scope="col" class="d-none d-sm-table-cell" rowspan="2">Eligible Yes/No (HEC)</th>
        </tr>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student Code</th>
            <th scope="col" class="d-none d-sm-table-cell">Names</th>
            <th scope="col" class="d-none d-sm-table-cell">Father's Names</th>
            <th scope="col" class="d-none d-sm-table-cell"> Mother's Names</th>
            <th scope="col" class="d-none d-sm-table-cell"> ID / Passport</th>
            
            <th scope="col" class="d-none d-sm-table-cell">No of principal passes</th>
            <th scope="col" class="d-none d-sm-table-cell">Degree</th>
            <th scope="col" class="d-none d-sm-table-cell">Eligible Yes/No</th>
            <th scope="col" class="d-none d-sm-table-cell">No of Credits transfered</th>
             
            <th scope="col">Year of Registration</th>
            <th scope="col">Year of completion</th>
            <th scope="col">Cumulative Average</th>
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
            $completeReg=substr($sAcada22Reg['acad_year'],0,4); 
            
            $noPrincipalPasses = $conn->prepare("SELECT count(id) AS count FROM tbl_principal_pass WHERE reg_no = ? ");
            $noPrincipalPasses->execute([$students['reg_no']]);
            $pPasses = $noPrincipalPasses->fetch();  
            $pPasses = $pPasses['count']; 

        ?>
        <tr>
            <td><?php echo $i ?> </td>  
            <td><?php echo $students['reg_no']; ?> </td>
            <td class="d-none d-sm-table-cell"><?php echo $students['fname']." ".$students['lname']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $father_names ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $mother_names ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $ID ?></td>
            
            <td class="d-none d-sm-table-cell"><?php echo $pPasses ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $prg_award_id ?></td>
            <td class="d-none d-sm-table-cell"> Yes</td>
            <td class="d-none d-sm-table-cell"></td>
            
            <td><?php echo $completeReg ?></td>  
            <td><b><?php echo $complete ?></b></td>   
            <td align="right"><?php echo number_format($students['cum_marks'],1);  ?></td>
            <td align="right"><i class="icon-copy fa fa-check" aria-hidden="true" style="color:green"></i> Yes</td>
        </tr>
        <?php } ?>
    </tbody>
</table>  
</div>
<div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
    <a href="download?prg_type=<?=$prg_type; ?>&fac_id=<?=$fac; ?>&dept_id=<?=$dept; ?>&splz_id=<?=$splz; ?>&grad=<?=$grad ?>" target="_blank" class="btn btn-success"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>