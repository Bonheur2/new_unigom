<?php
include ('../../meet/con.php');
$sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
$sql->execute();
$data=$sql->fetch();
?>

<style>
td{padding:3px;color:black}
#header{width:800px;}
#transinfo{width:750px;}
#transdetail{width:'max-content';}
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

$date=date('Y-m-d',time());
//Get basic student's info
$basic=$conn->prepare('SELECT 
                        tbl_register_program_ug.reg_no,
                        tbl_register_program_ug.prg_type,
                        tbl_admission.fname,
                        tbl_admission.lname,
                        tbl_program_type.prg_type_full_name,
                        tbl_faculty.fac_full_name,
                        tbl_department.dept_full_name,
                        tbl_specialization.splz_full_name,
                        tbl_level.level_full_name,
                        tbl_graduants.degree_no
                    FROM tbl_register_program_ug
                        INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no=tbl_admission.reg_no
                        INNER JOIN tbl_graduants ON tbl_register_program_ug.reg_no=tbl_graduants.reg_no
                        INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                        INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                        INNER JOIN tbl_department ON tbl_register_program_ug.dept_id=tbl_department.dept_id
                        INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id=tbl_specialization.splz_id 
                        INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                    WHERE tbl_register_program_ug.reg_no="'.trim($_REQUEST['stud']).'" ORDER BY tbl_register_program_ug.level_id DESC LIMIT 1');
$basic->execute();
if($basic->rowCount()!=0){
    $getDocuments=$conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE upload_doc like '%photo_%' AND tracking_id='".trim($_REQUEST['stud'])."'");
    $getDocuments->execute();
    $documents=$getDocuments->fetch();
    $stuImg=$documents['upload_doc'];
    $studentData=$basic->fetch();
        
    if($studentData['prg_type']==2){
	    $grade_table = 'tbl_gradeP';
	}else{
	    $grade_table = 'tbl_grade';
	}
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4 col-ld-4" style="display:flex; flex-direction,justify-content:center;">
                <img src="../..<?php echo $data['logo']; ?>" width="230" style="margin:auto;">
            </div>
            <div class="col-12 col-sm-8 col-ld-8" style="text-align:center;">
                <h4><?php echo $data['full_name']; ?></h4>
                <h4><a href="https://<?php echo $data['website']; ?>"><?php echo $data['website']; ?></a></h4>
            </div>
            <div class="col-12" style="margin-top:50px;text-align:center;">
                <h4><u>ACADEMIC TRANSCRIPT</u></h4>
            </div>
        </div>
        <div class="row" style="justify-content:center; margin-bottom:50px;">
            <div class="col-12 col-sm-8 col-ld-8" style="display:flex;flex-direction:column; justify-content:flex-start;">
                <label class="fontbold">Registration Number: <?php echo trim($_REQUEST['stud']);?></label>
                <label class="fontbold">Family Name: <?php echo $studentData['lname'];?></label>
                <label class="fontbold">First Name(s): <?php echo $studentData['fname'];?></label>
                <label class="fontbold">Faculty: <?php echo $studentData['fac_full_name'];?></label>
                <label class="fontbold">Specialization: <?php echo $studentData['splz_full_name'];?></label>
            </div>
            <div class="col-12 col-sm-2 col-ld-2" style="text-align:center; padding:0px;">
                <img src="../../<?php echo $stuImg; ?>" style="max-width:100%; border:1px solid black">
            </div>
        </div>
        <div class="row" style="margin-top:30px;">
            <?php 
                $total_credits=0;
                $total_credit_pts=0;
                $failed_credits=0;
                //get level data
                $sql=$conn->prepare('SELECT DISTINCT(tbl_register_program_ug.level_id),
                                            tbl_level.level_full_name 
                                        FROM tbl_register_program_ug
                                            INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id
                                            WHERE reg_no="'.trim($_REQUEST['stud']).'"');
                $sql->execute();
                
                //Get Marks
                while($levelData=$sql->fetch()){
                $sqlMarks=$conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.credited_module,
                                                tbl_modules.module_credits,
                                                tbl_markby_module.marks,
                                                tbl_markby_module.enrolled
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stud']).'" AND
                                                  tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                  tbl_modules.credited_module=1 AND
                                                  tbl_markby_module.enrolled IN(1,2) AND 
                                                  (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                  tbl_markby_module.marks IS NOT NULL');
                $sqlMarks->execute();
            ?>
            
            <div class="col-12 col-sm-6 col-lg-6" style="margin-top:20px;">
                <table id="transdetail" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td class="cellleft celltop fontbold" colspan="5"><?php echo $levelData['level_full_name']; ?></td>
                    </tr>
                    <tr>
                        <td class="cellleft celltop fontbold">Module Code</td>
                        <td class="celltop fontbold">Module Name</td>
                        <td class="celltop fontbold">Credits</td>
                        <td class="celltop fontbold">Marks (%)</td>
                        <td class="celltop fontbold">Grade</td>
                    </tr> 
                        <?php
                        $credits=0;
                        $tcredits=0;
                        $tcreditpts=0;
                        $grade="";
                        
                        //ACCESS Marks
                        while($studentMarks=$sqlMarks->fetch()){
                            
                            $module_code=$studentMarks['module_code'];
                            $module_name=($studentMarks['enrolled']==2?'*':'').$studentMarks['module_name'];
                            $module_credits=$studentMarks['module_credits'];
                            $marks=$studentMarks['marks'];
                            
                            if($marks>=60){
                                $tcredits+=$module_credits;
                                $tcreditpts+=$marks*$module_credits;
                            }
                        
                            // SELECTING GRADE MARKS
                            $sqlgrade=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks.'" and m_to>"'.$marks.'"');
                            $sqlgrade->execute();
                            $resgrade=$sqlgrade->fetch();
                            $grade=$resgrade['grade_letter'];
                            if($grade=="F")$failed_credits+=$module_credits;
                        ?>
                    <tr>
                        <td class="cellleft"><?php echo $module_code;?></td>
                        <td><?php echo $module_name;?></td>
                        <td align="center"><?php echo $module_credits;?></td>
                        <td align="right"><?php echo number_format($marks,1);?></td>
                        <td align="center"><?php echo $grade;?></td>
                    </tr>
                        <?php } ?>
                        
                    <!--uncredited modules start-->
                    <tr>
                        <td class="cellleft fontitalic" colspan="9">Uncredited Module(s)</td>
                    </tr>
                        <?php
                        $total_marks=0;
                        $grade2="";
                        $sqlMarks_u=$conn->prepare('SELECT 
                                                DISTINCT(tbl_markby_module.module_id),
                                                modules.module_code,
                                                modules.module_name,
                                                tbl_modules.credited_module,
                                                tbl_markby_module.marks,
                                                tbl_markby_module.enrolled
                                            FROM tbl_markby_module
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no="'.trim($_REQUEST['stud']).'" AND
                                                  tbl_modules.level_id="'.$levelData['level_id'].'" AND
                                                  tbl_modules.credited_module=0 AND
                                                  tbl_markby_module.enrolled IN(1,2) AND 
                                                  (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                  tbl_markby_module.marks IS NOT NULL');
                                                          
                        $sqlMarks_u->execute();
                        while($studentMarks_u=$sqlMarks_u->fetch()){
                            $module_code=$studentMarks_u['module_code'];
                            $module_name=($studentMarks_u['enrolled']==2?'*':'').$studentMarks_u['module_name'];
                            $marks=$studentMarks_u['marks'];
                            // SELECTING GRADE MARKS
                            $sqlgrade=$conn->prepare('select grade_letter from '.$grade_table.' where m_from<="'.$marks.'" and m_to>"'.$marks.'"');
                            $sqlgrade->execute();
                            $resgrade=$sqlgrade->fetch();
                            $grade2=$resgrade['grade_letter'];
                        ?>
                    <tr>
                        <td class="cellleft"><?php echo $module_code;?></td>
                        <td><?php echo $module_name;?></td>
                        <td align="center"></td></td>
                        <td align="right"><?php echo number_format($marks,1);?></td>
                        <td align="center"><?php echo $grade2;?></td>
                    </tr>
                        <?php } 
                         //end uncredited modules
                            $total_credits+=$tcredits;
                            $total_credit_pts+=$tcreditpts;
                        ?>
                        
                    <tr>
                        <td class="cellleft fontbold" colspan="2">Total Credits</td>
                        <td class="fontbold" align="center" colspan="3"><?php echo $tcredits;?></td>
                    </tr>
                    <tr>
                        <td class="cellleft fontbold" colspan="2"><?php echo $levelData['level_full_name']; ?> Average (%)</td>
                        <td class="fontbold" align="center" colspan="3"><?php echo number_format($tcreditpts/($tcredits==0?1:$tcredits),1).' %';?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
        <div class="row" style="margin-top:30px;">
            <?php 
                if ($total_credits!=0){
                    $cum_avg=$total_credit_pts/$total_credits;
                }else{
                    $cum_avg=0;
                }
            ?>
            <div class="col-12 col-sm-6 col-lg-6">
                <table border="1" width="100%" style="background-color:rgba(255,255,0,0.4);">
                    <tr>
                        <td class="cellleft fontbold">Total Credits</td>
                        <td class="cellleft fontbold"><?php echo $total_credits; ?></td>
                    </tr>
                    <tr>
                        <td class="cellleft fontbold">Cummulative Average</td>
                        <td class="cellleft fontbold"><?php echo number_format($cum_avg,1);?> %</td>
                    </tr>
                </table> 
            </div>
        </div>
        <div class="row" style="justify-content:center;margin-top:30px;">
            <div class="col-12 col-sm-5 col-lg-5" style="padding-top:100px;">
                <label class="fontbold">Signature & Stamp: .....................................</label></br></br>
                <label class="fontbold">Academic Registrar</label>
            </div>
            <div class="col-12 col-sm-5 col-lg-5" style="padding-top:100px;">
                <label class="fontbold">Signature & Stamp: .....................................</label></br></br>
                <label class="fontbold">Deputy Principal - Academics </label>
            </div>
        </div>
        <div class="row" style="margin-top:30px;">
            <div class="col-12 col-sm-10 col-lg-10"  style="display:flex;justify-content:center;">
                <label>*<i>This transcript is issued without any erasures or alterations and is valid only with the official stamp</i></label>
            </div>
            <div class="col-12 col-sm-10 col-lg-10" style="display:flex;justify-content:center;">
                <label style="font-size:14;color:green;">Scientia et Sapientia</label>
            </div>
        </div>
        <div class="row" style="margin-top:30px;">
            <div class="col-12 col-sm-10 col-lg-10" style="display:flex;justify-content:flex-end;">
                <!--<a href="/files/Transcript/print_cum_transcript.php?stud=<?php echo $_REQUEST['stud'];?>" target="_blank" class="btn btn-danger"> <i class="fa fa-file-pdf-o"></i> Export Pdf</a>&nbsp;-->
                <a href="/files/Transcript/print_cum_transcript_v2.php?stud=<?php echo $_REQUEST['stud'];?>" target="_blank" class="btn btn-success"> <i class="fa fa-file-pdf-o"></i> Export Pdf</a>
            </div>
        </div>
    </div>
</div>
<?php } 
    else{
        echo '<span style="color:red;">No Data Found For <b>(Reg. #:</b> '.$_REQUEST['stud'].'<b>)</b></span>';
    }
?>
