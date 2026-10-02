<?php
include ('../../meet/con.php');
$sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
$sql->execute();
$data=$sql->fetch();
?>

<style>
td{padding:3px;color:black}
#header{width:800px;}
#transinfo{width:750px;}
#transdetail{width:750px;}
#footer{width:750px;}

#header td{font-family:Arial;font-size:20px;font-weight:bold;}
#transinfo td{border-bottom:1px solid black;border-right:1px solid black;font-family:Arial;font-size:12px;font-weight:bold;}
#transdetail td{border-bottom:1px solid black;border-right:1px solid black;font-family:Arial;font-size:12px;}
#footer td{font-family:Arial;font-size:14px;}

.cellleft{border-left:1px solid black;}
.celltop{border-top:1px solid black;}
.fontbold{font-weight:bold;}
.fontitalic{font-style:italic;}
</style>


<?php

///// SELECTING STUDENTS AND RELATED INFO
$sql=$conn->prepare('SELECT 
                        tbl_register_program_ug.reg_no,
                        tbl_register_program_ug.prg_type,
                        tbl_admission.fname,
                        tbl_admission.lname,
                        tbl_program_type.prg_type_full_name,
                        tbl_faculty.fac_full_name,
                        tbl_department.dept_full_name,
                        tbl_specialization.splz_full_name,
                        tbl_level.level_full_name
                    FROM tbl_register_program_ug
                        INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                        INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                        INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                        INNER JOIN tbl_department ON tbl_register_program_ug.dept_id=tbl_department.dept_id
                        INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id 
                        INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                    WHERE tbl_register_program_ug.reg_no="'.trim($_REQUEST['stu']).'" AND
                          tbl_register_program_ug.level_id="'.$_REQUEST['level'].'"');
$sql->execute();
if($sql->rowCount()!=0){
    $getDocuments=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE upload_doc like '%photo_%' AND tracking_id='".trim($_REQUEST['stu'])."'");
    $getDocuments->execute();
    $documents=$getDocuments->fetch();
    $stuImg=$documents['upload_doc'];
    $studentData=$sql->fetch();
    
    if($studentData['prg_type']==2){
	    $grade_table = 'tbl_gradeP';
	}else{
	    $grade_table = 'tbl_grade';
	}
    //Get Marks
    $sqlMarks=$conn->prepare('SELECT 
                                    DISTINCT(tbl_markby_module.module_id),
                                    modules.module_code,
                                    modules.module_name,
                                    tbl_modules.module_credits,
                                    tbl_markby_module.cat,
                                    tbl_markby_module.final_exam,
                                    tbl_markby_module.marks,
                                    tbl_markby_module.enrolled
                                FROM tbl_markby_module
                                    INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                    INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stu']).'" AND
                                      tbl_modules.level_id="'.$_REQUEST['level'].'" AND
                                      tbl_modules.credited_module=1 AND
                                      tbl_markby_module.enrolled IN (1,2) AND 
                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                      tbl_markby_module.marks IS NOT NULL
                                      ');
    $sqlMarks->execute();

    if($sqlMarks->rowCount()!=0){
        // CHECKING IF THE TRANSCRIPT IS ALREADY GENERATED
        $sqlchecktrans=$conn->prepare('SELECT * FROM tbl_transcripts WHERE reg_no="'.$_REQUEST['stu'].'" AND level_id="'.$_REQUEST['level'].'"');
        $sqlchecktrans->execute();
        if($sqlchecktrans->rowCount()==0){
            // GENERATE A NEW TRANSCRIPT
            $inserttrans=$conn->prepare("INSERT INTO tbl_transcripts(reg_no,level_id,issue_date)values('".trim($_REQUEST['stu'])."','".$_REQUEST['level']."','".date('Y-m-d')."')");
            $inserttrans->execute();
        }
        //SELECTING THE TRASCRIPT
        $sqlselecttrans=$conn->prepare('SELECT * FROM tbl_transcripts WHERE reg_no="'.$_REQUEST['stu'].'" AND level_id="'.$_REQUEST['level'].'"');
        $sqlselecttrans->execute();
        $resselecttrans=$sqlselecttrans->fetch();
        $transno=$resselecttrans['trans_no'];
        $issuedate=date('Y-m-d');
    }
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12" style="margin:10px;text-align:center;">
                <p><i><code>*</code>If you are using a small device, rotate it for a better experience</i></p>
            </div>
            <div class="col-12 col-sm-4 col-ld-4" style="display:flex; flex-direction,justify-content:center;">
                <img src="../..<?php echo $data['logo']; ?>" width="230" style="margin:auto;">
            </div>
            <div class="col-12 col-sm-8 col-ld-8" style="text-align:center;">
                <h4><?php echo $data['full_name']; ?></h4>
                <h4><a href="https://<?php echo $data['website']; ?>" target="_blank"><?php echo $data['website']; ?></a></h4>
            </div>
            <div class="col-12" style="margin-top:50px;text-align:center;">
                <h4><u>Progress Report</u></h4>
            </div>
        </div>
        <div class="row" style="justify-content:center; margin-bottom:50px;">
            <div class="col-12 col-sm-8 col-ld-8" style="display:flex;flex-direction:column; justify-content:flex-start;">
                <label class="fontbold">Registration Number: <?php echo trim($_REQUEST['stu']);?></label>
                <label class="fontbold">Family Name: <?php echo $studentData['lname'];?></label>
                <label class="fontbold">First Name(s): <?php echo $studentData['fname'];?></label>
                <label class="fontbold">Faculty: <?php echo $studentData['fac_full_name'];?></label>
                <label class="fontbold">Specialization: <?php echo $studentData['splz_full_name'];?></label>
                <label class="fontbold"><?php echo $studentData['level_full_name'];?></h6>
            </div>
            <div class="col-12 col-sm-2 col-ld-2" style="text-align:center; padding:0px;">
                <img src="../..<?php echo $stuImg; ?>" style="max-width:100%;; border:1px solid black">
            </div>
        </div>
        <div class="row" style="justify-content:center;margin-top:30px;">
            <div class="col-12 col-sm-10 col-lg-10 table-responsive">
                <table id="transdetail" cellspacing="0" cellpadding="0" style="margin:auto; width: 100%;">
                    <tr>
                        <td class="cellleft celltop fontbold">Module Code</td>
                        <td class="celltop fontbold">Module Name</td>
                        <td class="celltop fontbold">Credits</td>
                        <!--<td class="celltop fontbold">CATs</td>-->
                        <!--<td class="celltop fontbold">Final Exam</td>-->
                        <!--<td class="celltop fontbold">Marks/100</td>-->
                        <!--<td class="celltop fontbold">Credit Points</td>-->
                        <td class="celltop fontbold">Grade</td>
                        <td class="celltop fontbold">Decision</td>
                    </tr> 
                        <?php
                        $total_credits=0;
                        $total_marks=0;
                        $total_credit_pts=0;
                        $credit_pts=0;
                        $average=0;
                        $grade="";
                        $failed_credits=0;
                        $fcredits=0;
                        $totalPcreditsEquivalent=0;
                        //ACCESS Marks
                        while($studentMarks=$sqlMarks->fetch()){
                            
                            $module_avg=0;
                            $module_code=$studentMarks['module_code'];
                            $module_name=($studentMarks['enrolled']==2?'*':'').$studentMarks['module_name'];
                            $module_credits=$studentMarks['module_credits'];
                            $cat=$studentMarks['cat'];
                            $fexam=$studentMarks['final_exam'];
                            $marks=$studentMarks['marks'];
                            $total_credits+=$module_credits;
                            if($marks>=60){
                                $credit_pts=$marks*$module_credits;
                           
                                $total_marks+=$marks;
                                $total_credit_pts+=$credit_pts;
                            }else{
                                $credit_pts = 0;
                            }
                        
                            // SELECTING GRADE MARKS
                            $sqlgrade=$db->prepare('select grade_letter,equival_grade from '.$grade_table.' where m_from<="'.$marks.'" and m_to>="'.$marks.'"');
                            $sqlgrade->execute();
                            while($resgrade=$sqlgrade->fetch()){
                                $grade2=$resgrade['grade_letter'];
                                $grade_equivalent=$resgrade['equival_grade'];
                                
                            	if($grade2=='E' || $grade2=='F'){
                            	    $fcredits+=$studentMarks['module_credits'];
                            	}
                            }
                            $creditWithEquivalent=$studentMarks['module_credits']*$grade_equivalent;
                            $totalPcreditsEquivalent+=$creditWithEquivalent;
                            
                        ?>
                    <tr>
                        <td class="cellleft"><?php echo $module_code;?></td>
                        <td><?php echo $module_name;?></td>
                        <td align="center"><?php echo $module_credits;?></td>
                        <!--<td align="right"><?php echo number_format($cat,1);?></td>-->
                        <!--<td align="right"><?php echo number_format($fexam,1);?></td>-->
                        <!--<td align="right"><?php echo number_format($marks,1);?></td>-->
                        <!--<td align="right"><?php echo number_format($credit_pts,1);?></td>-->
                        <td align="center"><?php echo $grade2;?></td>
                        <td align="center"><?php if($grade2=='A') echo 'Excellent'; else if($grade2=='E' || $grade2=='F') echo 'Fail';else if($grade2=='B') echo 'Good'; else if($grade2=='C') echo 'Fair'; else if($grade2=='D') echo 'Pass'; ?></td>
                    </tr>
                        <?php } ?>
                    <tr>
                        <td class="cellleft fontbold" colspan="2">Total</td>
                        <td class="fontbold" align="center"><?php echo $total_credits;?></td>
                        <!--<td class="fontbold" align="right" colspan="3"></td>-->
                        <td class="fontbold" align="right"></td>
                        <td class="fontbold" align="right"></td>
                    </tr>
                        
                    <!--credited modules start-->
                    <!--<tr>-->
                    <!--    <td class="cellleft fontitalic" colspan="9">Uncredited Module(s)</td>-->
                    <!--</tr>-->
                        <?php
                        $total_marks=0;
                        $average=0;
                        $grade="";
                        $sqlMarks_u=$conn->prepare('SELECT 
                                                        DISTINCT(tbl_markby_module.module_id),
                                                        modules.module_code,
                                                        modules.module_name,
                                                        tbl_modules.module_credits,
                                                        tbl_markby_module.cat,
                                                        tbl_markby_module.final_exam,
                                                        tbl_markby_module.marks,
                                                        tbl_markby_module.enrolled
                                                    FROM tbl_markby_module
                                                        INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                        INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                    WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stu']).'" AND
                                                          tbl_modules.level_id="'.$_REQUEST['level'].'" AND
                                                          tbl_modules.credited_module=0 AND
                                                          tbl_markby_module.enrolled IN (1,2) AND 
                                                          (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                          tbl_markby_module.marks IS NOT NULL');
                                                          
                        $sqlMarks_u->execute();
                        while($studentMarks_u=$sqlMarks_u->fetch()){
                            $module_avg=0;
                            $module_code=$studentMarks_u['module_code'];
                            $module_name=($studentMarks_u['enrolled']==2?'*':'').$studentMarks_u['module_name'];
                            $module_credits=$studentMarks_u['module_credits'];
                            $cat=$studentMarks_u['cat'];
                            $fexam=$studentMarks_u['final_exam'];
                            $marks=$studentMarks_u['marks'];
                            $total_marks+=$marks;
                            // SELECTING GRADE MARKS
                            $sqlgrade=$db->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks.'" and m_to>"'.$marks.'"');
                            $sqlgrade->execute();
                            while($resgrade=$sqlgrade->fetch()){
                                $grade2=$resgrade['grade_letter'];
                            }
                        ?>
                    <tr>
                        <td class="cellleft"><?php echo $module_code;?></td>
                        <td><?php echo $module_name;?></td>
                        <td align="center"></td>
                        <td align="right"><?php echo number_format($cat,1);?></td>
                        <td align="right"><?php echo number_format($fexam,1);?></td>
                        <td align="right"><?php echo number_format($marks,1);?></td>
                        <td align="right"></td>
                        <td align="center"><?php echo $grade2;?></td>
                        <td align="center"><?php if($grade2!='F'){echo 'Pass';}else{ echo 'Fail';}?></td>
                    </tr>
                        <?php } ?>
                         
                        <!--end uncredited modules-->
                        <?php 
                            if ($total_credits!=0)
                            $Overall_avg=$total_credit_pts/$total_credits;
                            $gradePoints=$totalPcreditsEquivalent/$total_credits;
                            $pass="";
                            $dec="";
                            $pcredits="";
                            $sqlgrade=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.floor($Overall_avg).'" and m_to>"'.floor($Overall_avg).'"');
                            $sqlgrade->execute();
                            while($resgrade=$sqlgrade->fetch())
                            {
                                $grade=$resgrade['grade_letter'];
                                
                            }
                            if($gradePoints>=3 && $fcredits==0 && $Inccredits==0)$dec=1;
                        	if($gradePoints>=3 && $fcredits>0)$dec=2;
                        	
                        	switch($dec){
                        		case 1: $decision="Promoted on clear standing";
                        				break;
                        		case 2: $decision="Promoted - Retake Failed Modules";
                        				break;
                        		case 3: $decision="Repeat Year with Failed Modules";
                        				break;
                        		case 4: $decision="Repeat Year with All Modules";
                        				break;
                        		case 5: $decision="Failed in Retaken Modules - Subsidiary Qualification Awarded";
                        				break;
                        		default: $decision="N/A";
                        				break;
                        	}
                        ?>
                    <tr>
                        <td class="cellleft fontbold" colspan="5">Grade Points Average (GPA): <?php echo number_format($gradePoints,2);?></td>
                    </tr>
                    <!--<tr>-->
                    <!--    <td class="cellleft fontbold" colspan="9">Grade Obtained: <?php echo strtoupper($grade); ?></td>-->
                    <!--</tr>-->
                    <tr>
                        <td class="cellleft fontbold" colspan="5">Senate's Decision: <?php echo $decision; ?></td>
                    </tr>
                    <tr>
                        <td class="fontitalic export" style="font-size:13px;border:0px;margin-top:40px;" colspan="5" align="center">Issued at NJARA on: <?php echo $issuedate;?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row export" style="justify-content:center;margin-top:30px;">
            <div class="col-12 col-sm-5 col-lg-5" style="padding-top:100px;">
                <label class="fontbold">Academic Registrar</label>
            </div>
            <div class="col-12 col-sm-5 col-lg-5" style="padding-top:100px;">
                <label class="fontbold">Deputy Principal Academic Affairs and Research</label>
            </div>
        </div>
        <div class="row export" style="margin-top:30px;">
            <div class="col-12 col-sm-10 col-lg-10"  style="display:flex;justify-content:center;">
                <label>*<i>This progress report is issued without any erasures or alterations and is valid only with the official stamp</i></label>
            </div>
            <div class="col-12 col-sm-10 col-lg-10" style="display:flex;justify-content:center;">
                <label style="font-size:14;color:green;">Scientia et Sapientia</label>
            </div>
        </div>
        <div class="row export" style="margin-top:30px;">
            <div class="col-12 col-sm-10 col-lg-10"  style="display:flex;justify-content:flex-end;">
                <a href="/files/Transcript/print_transcript.php?lev=<?php echo $_REQUEST['level'];?>&stu=<?php echo $_REQUEST['stu'];?>" target="_blank" class="btn btn-danger"> <i class="fa fa-file-pdf-o"></i> Export Pdf</a>
            </div>
        </div>
    </div>  
</div>
<?php } 
    else{
        echo '<span style="color:red;">No Data Found For <b>(Reg. #:</b> '.$_REQUEST['stu'].'<b>)</b> in <b>(Level:</b> '.$_REQUEST['level'].'<b>)</b></span>';
    }
?>
