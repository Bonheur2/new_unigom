<?php
    include ('../meet/con.php');
    $prg_type=$_POST['prg_type'];
	$fac=$_POST['fac_id'];
	$dept=$_POST['dept_id'];
	$splz=$_POST['splz_id'];
	$acad_cycle_id=$_POST['acad_cycle_id'];
	$mode=$_POST['mode'];

    $getStudents = $conn->prepare("SELECT
                                        r.reg_no,
                                        ad.fname,
                                        ad.lname
                                    FROM tbl_register_program_ug r
                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                    WHERE
                                        r.prg_type='".$prg_type."' AND 
                                        r.fac_id='".$fac."' AND 
                                        r.dept_id='".$dept."' AND
                                        r.splz_id='".$splz."' AND
                                        ad.acad_cycle_id='".$acad_cycle_id."' AND
                                        r.prg_mode_id='".$mode."' AND
                                        r.reg_active in (1,6)");
    $getStudents->execute();
    
    $getSpecs = $conn->prepare("SELECT * FROM tbl_specialization WHERE splz_id='".$splz."' ");
    $getSpecs->execute();
    $specs = $getSpecs->fetch();
    
    $splz_name = $specs['splz_full_name'];
    $required_credits = $specs['totalCredits'];
    $degree_name = $specs['degree_name'];
    
    $getAcadYear = $conn->prepare("SELECT acad_cycle_id  FROM  tbl_acad_cycle WHERE acad_cycle_id='".$acad_cycle_id."' ");
    $getAcadYear->execute();
    $acad_year=$getAcadYear->fetch();
    $acad_id=$acad_year['acad_cycle_id'];
?>  

<input type="hidden" value="<?php echo $splz ?>" name="splz_id" >
<input type="hidden" value="<?php echo $dept ?>" name="prg_id" >
<input type="hidden" value="<?php echo $mode ?>" name="prg_mode_id" >
<input type="hidden" value="<?php echo $degree_name ?>" name="award" >
<h6 style="text-align:center"><?php  echo $splz_name ?> <small>(<?php echo " ".$required_credits ." Required Credits" ?>)</small></h6> <hr/>
<table class="table table-hover table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Student Code</th>
            <th scope="col" class="d-none d-sm-table-cell">Names</th>
            <th scope="col" class="d-none d-sm-table-cell">Total Credits</th>
            <th scope="col" class="d-none d-sm-table-cell">Cummulative Average (%)</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody id="contents">
                                                           
        <?php 
            $j=0;
            while($students=$getStudents->fetch()){
                $j++;
                $tcredits = 0;
                $avg = 0;
                $reg_no = $students['reg_no'];

                $getLevels = $conn->prepare("SELECT DISTINCT(level_id) FROM tbl_register_program_ug WHERE reg_no='".$reg_no."' ORDER BY reg_prg_id ASC");
                $getLevels->execute();
                
                $levels = $getLevels->rowCount();
                $l = [0, 0, 0, 0];
                $i = 0; 
                while($level=$getLevels->fetch()){
                    $credits = 0;
                    $creditpts = 0;
                    $getModules = $conn->prepare('SELECT 
                                                    DISTINCT(tbl_markby_module.module_id),
                                                    modules.module_code,
                                                    tbl_modules.module_credits,
                                                    tbl_markby_module.marks,
                                                    tbl_markby_module.enrolled
                                                FROM tbl_markby_module
                                                    INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                    INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                                WHERE tbl_markby_module.reg_no="'.$reg_no.'" AND
                                                      tbl_modules.level_id="'.$level['level_id'].'" AND
                                                      tbl_modules.credited_module=1 AND
                                                      tbl_markby_module.enrolled IN (1,2) AND 
                                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6) AND
                                                      tbl_markby_module.marks IS NOT NULL AND tbl_markby_module.marks>=60
                                                    ');
                    $getModules->execute(); 
        
                    while($module=$getModules->fetch()){
                        $tcredits += $module['module_credits'];
                        $credits += $module['module_credits'];
                        $creditpts += $module['module_credits'] * $module['marks'];
                    }

                    $l[$i] = $creditpts/($credits>0?$credits:1);
                    $i++;
                }
                
                $avg = array_sum($l)/($levels>0?$levels:1);

                $sqlAwarded=$db->prepare("select reg_no from  tbl_graduants where reg_no = ? AND splz_id = ?");
                $sqlAwarded->execute([$reg_no, $splz]);
                $resAwarded=$sqlAwarded->rowCount();
                if($resAwarded==0 && $tcredits>=$required_credits){
            ?>
        <tr>
            <td><?php echo $j ?> </td>  
            <td><?php echo $reg_no; ?> </td>
            <td><?php echo $students['fname']." ".$students['lname']; ?></td>
            <td><?php echo $tcredits ?></td>
            <td><?php echo number_format($avg,1) ?></td>
            <td>
                <?php  if($tcredits >= $required_credits){ ?>
                    <input type="checkbox" name="selectedStu[]"  value="<?php echo $reg_no;?>"  checked >   
                <?php   }  else { ?>
                <input type="checkbox" disabled> 
                <?php } ?>
            </td>
               
        </tr>
           
        <?php } else if($resAwarded!=0 && $tcredits>=$required_credits){ ?>
        <tr >
            <td><?php echo $j ?> </td>  
            <td><?php echo $students['reg_no']." "; ?> </td>
            <td><?php echo $students['fname']." ".$students['lname']; ?></td>
            <td><?php echo $tcredits ?></td>   
            <td><?php echo number_format($avg,1) ?></td>
            <td >
                <i class="icon-copy fa fa-check" aria-hidden="true" style="color:green"></i>  
                <input type="checkbox" name="selectedStu[]"  value="<?php echo $reg_no;?>"  checked >
            </td>
        </tr>
            
        <?php } else{ ?>
        <tr >
            <td><?php echo $j ?> </td>  
            <td><?php echo $students['reg_no']." "; ?> </td>
            <td><?php echo $students['fname']." ".$students['lname']; ?></td>
            <td><?php echo $tcredits ?></td>   
            <td><?php echo number_format($avg,1) ?></td>
            <td >
                <i class="icon-copy fa fa-times" aria-hidden="true" style="color:red"></i>  
            </td>
        </tr>
            
        <?php } } ?>
    </tbody>
</table>