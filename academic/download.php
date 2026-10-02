<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Graduation List.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    include ('../meet/con.php');
    
    $prg_type = $_REQUEST['prg_type'];
	$fac = $_REQUEST['fac_id'];
	$dept = $_REQUEST['dept_id'];
	$splz = $_REQUEST['splz_id'];
	$grad = $_REQUEST['grad'];
?>
<?php if($prg_type == 2){ ?>
<?php
    $getStudents = $conn->prepare("SELECT 
                                        r.reg_no,
                                        r.spon_id,
                                        ad.*,
                                        gr.cum_marks,
                                        gr.grad_cycle_id,
                                        gr.splz_id,
                                        gr.prg_award_id,
                                        gr.issue_date,
                                        nat.nationality
                                        
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                        INNER JOIN tbl_graduants gr ON r.reg_no=gr.reg_no
                                        LEFT JOIN tbl_nationality nat ON ad.nationality = nat.nat_id
                                        
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
    
    $spavname = $specs['splz_full_name'];
    $creditsT = $specs['totalCredits'];
    $degree_name = $specs['degree_name'];
    
    $getSpFacult = $conn->prepare("SELECT fac_full_name,fac_short_name FROM tbl_faculty WHERE fac_id ='".$fac."' ");
    $getSpFacult->execute();
    $sFacult = $getSpFacult->fetch();
    $facName = $sFacult['fac_full_name'];
    
    $getLevels = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prg_type."' AND status = 1 ORDER BY level_id ASC");
    $getLevels->execute();
    
    $getGraduation = $conn->prepare("SELECT * FROM tbl_grad_cycle WHERE grad_cycle_id='".$grad."'");
    $getGraduation->execute();
    $graduation = $getGraduation->fetch();
    
?>  

<div class="table-responsive">
<table class="table table-hover table-sm" width="100%" border="1">
    <thead>
        <tr><th scope="col" colspan="23"><h3 style="text-align:left">PROGRAM TYPE: <span style="color: blue;"><b><?php  echo " ".$progName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="23"><h3 style="text-align:left">FACULTY: <span style="color: blue;"><b><?php  echo " ".$facName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="23"><h3 style="text-align:left">ACADEMIC PROGRAM: <span style="color: blue;"><b><?php  echo " ".$spavname ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="23"><h3 style="text-align:left">AWARD: <span style="color: blue;"><b><?php  echo " ".$degree_name ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="23"><h3 style="text-align:left">Graduation Date: <span style="color: blue;"><b><?php  echo " ".$graduation['grad_date'] ?></b></span></h3></th></tr>
        <tr>
            <th scope="col" rowspan="3">S/N</th>
            <th scope="col" colspan="14">STUDENT IDENTIFICATION</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="3">ADMISSION CRITERIA</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="2">YEAR OF STUDY</th>
            <th scope="col" colspan="2">GRADES/MARKS</th>
            <th scope="col" class="d-none d-sm-table-cell" rowspan="3">Eligible to gaduate/ Not Eligible to graduate (For HEC)</th>
        </tr>
        <tr>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Firstname</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Gender (Male/ Female)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Date of Birth (Day/Month/Year)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Telephone number </th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Email address </th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">ID Number/ Passport Number</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Nationality</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Registration Number</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Sponsorship</th>
            
            <th scope="col" colspan="2" class="d-none d-sm-table-cell">Father's Names</th>
            <th scope="col" colspan="2" class="d-none d-sm-table-cell">Mothers's Names</th>
            
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Bachelor's Degree</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Eligible (Yes/No)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Number of Credits Transferred</th>
             
            <th scope="col" rowspan="2">Year of Registration</th>
            <th scope="col" rowspan="2">Year of completion</th>
            <th scope="col" rowspan="2">Cumulative average grade</th>
            <th scope="col" rowspan="2">Date of deliberations by competent academic organ</th>
        </tr>
        <tr>
            <th scope="col" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" class="d-none d-sm-table-cell">Firstname</th>
            <th scope="col" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" class="d-none d-sm-table-cell">Firstname</th>
        </tr>
    </thead>
    <tbody id="contents">
    <?php
        $i=0;
        $toveryM=0;
        while($students=$getStudents->fetch()){
            $i++;
            $numberModule = 0;
            $everyCredit = 0;
            $everyMarks = 0;
            $credpts = 0;
            $tcredpts = 0;  
            $key_id = $students['reg_no'];
            
            $father_names = $students['father_names'];
            $mother_names = $students['mother_names'];
            $ID = $students['ID'];
            $prg_award_id = $students['prg_award_id'];
            
            $getSAcada22 = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$students['grad_cycle_id']."' ");
            $getSAcada22->execute();
            $sAcada22 = $getSAcada22->fetch();  
            $complete = substr($sAcada22['acad_year'],5,20); 
    
            $sqlLastL = $db->prepare("select intake_id from tbl_register_program_ug where reg_no='".$students['reg_no']."' and splz_id='".$students['splz_id']."'   order by level_id asc");
            $sqlLastL->execute();
            $resLastL = $sqlLastL->fetch();
            $intake3 = $resLastL['intake_id'];
    
            $getAcadYear = $conn->prepare("SELECT acad_cycle_id  FROM  tbl_intake WHERE intake_id='".$intake3."' ");
            $getAcadYear->execute();
            $acad_year = $getAcadYear->fetch();
            $acad_id = $acad_year['acad_cycle_id'];
    
            $getSAcada22Reg = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$acad_id."' ");
            $getSAcada22Reg->execute();
            $sAcada22Reg = $getSAcada22Reg->fetch();  
            $completeReg = substr($sAcada22Reg['acad_year'],0,4); 
            
            $transferedCredits = $conn->prepare("SELECT COALESCE(SUM(m.module_credits), 0) AS count FROM tbl_markby_module tm INNER JOIN tbl_modules m ON tm.module_id = m.module_id WHERE reg_no = ? AND enrolled = 2");
            $transferedCredits->execute([$students['reg_no']]);
            $transferedCredits = $transferedCredits->fetch();  
            $transferedCredits = $transferedCredits['count']; 
            
    
            $sponsor = $conn->prepare("SELECT spon_full_name FROM tbl_sponsor WHERE spon_id = ?");
            $sponsor->execute([$students['spon_id']]);
            $sponsor = $sponsor->fetch()['spon_full_name'];
            
            $getLevels2 = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prg_type."' AND status = 1 ORDER BY level_id ASC");
            $getLevels2->execute();
        ?>
        <tr>
            <td><?php echo $i ?> </td>  
            <td class="d-none d-sm-table-cell"><?php echo $students['lname']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['fname']; ?></td>
            
            <td class="d-none d-sm-table-cell">
                <?php 
                    if(strtoupper($students['gender']) == 'M'){
                        echo 'Male';
                    }else if(strtoupper($students['gender']) == 'F'){
                        echo 'Female';
                    }else{
                        echo '-';
                    }
                ?>
            </td>
            <td class="d-none d-sm-table-cell"><?php echo $students['dob']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['phone']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['email']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo "&nbsp;".$ID; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $students['nationality']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $students['reg_no']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $sponsor; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $father_names)[0]; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $father_names)[1]." ".explode(" ", $father_names)[2]; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $mother_names)[0]; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $mother_names)[1]." ".explode(" ", $mother_names)[2]; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo $students['prev_degree']; ?></td>
            <td class="d-none d-sm-table-cell">Yes</td>
            <td class="d-none d-sm-table-cell"><?php echo $transferedCredits; ?></td>
            <td><?php echo $completeReg ?></td>  
            <td><b><?php echo $complete ?></b></td>   
            <th align="right"><?php echo number_format($students['cum_marks'],1);  ?></th>
            <td align="right"><?php echo $students['issue_date'];  ?></td>
            <td align="right">Yes</td>
        </tr>
        <?php } ?>
    </tbody>
</table>  
</div>
<?php } else{ ?>
<?php
    $prg_type = $_REQUEST['prg_type'];
	$fac = $_REQUEST['fac_id'];
	$dept = $_REQUEST['dept_id'];
	$splz = $_REQUEST['splz_id'];
	$grad = $_REQUEST['grad'];
    $getStudents = $conn->prepare("SELECT 
                                        r.reg_no,
                                        r.spon_id,
                                        ad.*,
                                        gr.l1_marks,
                                        gr.l2_marks,
                                        gr.l3_marks,
                                        gr.l4_marks,
                                        gr.cum_marks,
                                        gr.grad_cycle_id,
                                        gr.splz_id,
                                        gr.prg_award_id,
                                        nat.nationality
                                        
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                        INNER JOIN tbl_graduants gr ON r.reg_no=gr.reg_no
                                        LEFT JOIN tbl_nationality nat ON ad.nationality = nat.nat_id
                                        
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
    
    $spavname = $specs['splz_full_name'];
    $creditsT = $specs['totalCredits'];
    $degree_name = $specs['degree_name'];
    
    $getSpFacult = $conn->prepare("SELECT fac_full_name,fac_short_name FROM tbl_faculty WHERE fac_id ='".$fac."' ");
    $getSpFacult->execute();
    $sFacult = $getSpFacult->fetch();
    $facName = $sFacult['fac_full_name'];
    
    $getLevels = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prg_type."' AND status = 1 ORDER BY level_id ASC");
    $getLevels->execute();
    
    $getGraduation = $conn->prepare("SELECT * FROM tbl_grad_cycle WHERE grad_cycle_id='".$grad."'");
    $getGraduation->execute();
    $graduation = $getGraduation->fetch();
    
    $sponsor = $conn->prepare("SELECT spon_full_name FROM tbl_sponsor WHERE spon_id = ?");
    $sponsor->execute([$students['spon_id']]);
    $sponsor = $sponsor->fetch()['spon_full_name'];
    
?>  

<div class="table-responsive">
<table class="table table-hover table-sm"   width="100%" border="1">
    <thead>
        <tr><th scope="col" colspan="<?php echo $getLevels->rowCount() + 23 ?>"><h3 style="text-align:left">PROGRAM TYPE: <span style="color: blue;"><b><?php  echo " ".$progName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="<?php echo $getLevels->rowCount() + 23 ?>"><h3 style="text-align:left">FACULTY: <span style="color: blue;"><b><?php  echo " ".$facName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="<?php echo $getLevels->rowCount() + 23 ?>"><h3 style="text-align:left">ACADEMIC PROGRAM: <span style="color: blue;"><b><?php  echo " ".$spavname ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="<?php echo $getLevels->rowCount() + 23 ?>"><h3 style="text-align:left">AWARD: <span style="color: blue;"><b><?php  echo " ".$degree_name ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="<?php echo $getLevels->rowCount() + 23 ?>"><h3 style="text-align:left">Graduation Date: <span style="color: blue;"><b><?php  echo " ".$graduation['grad_date'] ?></b></span></h3></th></tr>
        <tr>
            <th scope="col" rowspan="3">S/N</th>
            <th scope="col" colspan="14">STUDENT IDENTIFICATION</th>
            <th scope="col" class="d-none d-sm-table-cell" colspan="6">ADMISSION CRITERIA</th>
            <th scope="col" colspan="6">Grades/marks scored by the student</th>
        </tr>
        <tr>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Firstname</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Gender (Male/ Female)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Date of Birth (Day/Month/Year)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Telephone number </th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Email address </th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">ID Number/ Passport Number</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Nationality</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Registration Number</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Sponsorship</th>
            
            <th scope="col" colspan="2" class="d-none d-sm-table-cell">Father's Names</th>
            <th scope="col" colspan="2" class="d-none d-sm-table-cell">Mothers's Names</th>
            
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Combination attended at high school/ Section</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">No of Principal Passes</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Eligible (Yes/No)</th>
            <th scope="col" rowspan="2" class="d-none d-sm-table-cell">Number of Credits Transferred</th>
            <th scope="col" rowspan="2">Year of Registration</th>
            <th scope="col" rowspan="2">Year of completion</th>
            
            <?php while($levels = $getLevels->fetch()){ ?>
            <th scope="col" rowspan="2">Average score in <?php echo $levels['level_full_name']; ?></th>
            <?php } ?>
            <th scope="col"rowspan="2">Cumulative Average</th>
            <th scope="col" rowspan="2">Eligible to gaduate/ Not Eligible to graduate (For HEC)</th>
        </tr>
        <tr>
            <th scope="col" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" class="d-none d-sm-table-cell">Firstname</th>
            <th scope="col" class="d-none d-sm-table-cell">Surname</th>
            <th scope="col" class="d-none d-sm-table-cell">Firstname</th>
        </tr>
    </thead>
    <tbody id="contents">
    <?php
        $i=0;
        $toveryM=0;
        while($students=$getStudents->fetch()){
            $i++;
            $numberModule = 0;
            $everyCredit = 0;
            $everyMarks = 0;
            $credpts = 0;
            $tcredpts = 0;  
            $key_id = $students['reg_no'];
            
            $father_names = $students['father_names'];
            $mother_names = $students['mother_names'];
            $ID = $students['ID'];
            $prg_award_id = $students['prg_award_id'];
            
            $getSAcada22 = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$students['grad_cycle_id']."' ");
            $getSAcada22->execute();
            $sAcada22 = $getSAcada22->fetch();  
            $complete = substr($sAcada22['acad_year'],5,20); 
    
            $sqlLastL = $db->prepare("select intake_id from tbl_register_program_ug where reg_no='".$students['reg_no']."' and splz_id='".$students['splz_id']."'   order by level_id asc");
            $sqlLastL->execute();
            $resLastL = $sqlLastL->fetch();
            $intake3 = $resLastL['intake_id'];
    
            $getAcadYear = $conn->prepare("SELECT acad_cycle_id  FROM  tbl_intake WHERE intake_id='".$intake3."' ");
            $getAcadYear->execute();
            $acad_year = $getAcadYear->fetch();
            $acad_id = $acad_year['acad_cycle_id'];
    
            $getSAcada22Reg = $conn->prepare("SELECT *  FROM  tbl_acad_cycle WHERE acad_cycle_id ='".$acad_id."' ");
            $getSAcada22Reg->execute();
            $sAcada22Reg = $getSAcada22Reg->fetch();  
            $completeReg = substr($sAcada22Reg['acad_year'],0,4); 
            
            $noPrincipalPasses = $conn->prepare("SELECT count(id) AS count FROM tbl_principal_pass WHERE reg_no = ? ");
            $noPrincipalPasses->execute([$students['reg_no']]);
            $pPasses = $noPrincipalPasses->fetch();  
            $pPasses = $pPasses['count']; 
            
            $transferedCredits = $conn->prepare("SELECT COALESCE(SUM(m.module_credits), 0) AS count FROM tbl_markby_module tm INNER JOIN tbl_modules m ON tm.module_id = m.module_id WHERE reg_no = ? AND enrolled = 2");
            $transferedCredits->execute([$students['reg_no']]);
            $transferedCredits = $transferedCredits->fetch();  
            $transferedCredits = $transferedCredits['count']; 
            
    
            $sponsor = $conn->prepare("SELECT spon_full_name FROM tbl_sponsor WHERE spon_id = ?");
            $sponsor->execute([$students['spon_id']]);
            $sponsor = $sponsor->fetch()['spon_full_name'];
            
            $getLevels2 = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type='".$prg_type."' AND status = 1 ORDER BY level_id ASC");
            $getLevels2->execute();
        ?>
        <tr>
            <td><?php echo $i ?> </td>  
            <td class="d-none d-sm-table-cell"><?php echo $students['lname']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['fname']; ?></td>
            
            <td class="d-none d-sm-table-cell">
                <?php 
                    if(strtoupper($students['gender']) == 'M'){
                        echo 'Male';
                    }else if(strtoupper($students['gender']) == 'F'){
                        echo 'Female';
                    }else{
                        echo '-';
                    }
                ?>
            </td>
            <td class="d-none d-sm-table-cell"><?php echo $students['dob']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['phone']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo $students['email']; ?></td>
            <td class="d-none d-sm-table-cell"><?php echo "&nbsp;".$ID; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $students['nationality']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $students['reg_no']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $sponsor; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $father_names)[0]; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $father_names)[1]." ".explode(" ", $father_names)[2]; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $mother_names)[0]; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo explode(" ", $mother_names)[1]." ".explode(" ", $mother_names)[2]; ?></td>
            
            <td class="d-none d-sm-table-cell"><?php  echo $students['prev_degree']; ?></td>
            <td class="d-none d-sm-table-cell"><?php  echo $pPasses; ?></td>
            <td class="d-none d-sm-table-cell">Yes</td>
            <td class="d-none d-sm-table-cell"><?php echo $transferedCredits; ?></td>
            <td><?php echo $completeReg ?></td>  
            <td><b><?php echo $complete ?></b></td>   
            <?php while($levels = $getLevels2->fetch()){ ?>
            <td scope="col"><?php echo $students['l'.$levels['level_no'].'_marks']; ?></td>
            <?php } ?>
            <th align="right"><?php echo number_format($students['cum_marks'],1);  ?></th>
            <td align="right">Yes</td>
        </tr>
        <?php } ?>
    </tbody>
</table>  
</div>
<?php } ?>