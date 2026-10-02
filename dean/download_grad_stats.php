<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Graduation Stats.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    include ('../meet/con.php');

    $prg_type = $_REQUEST['prg_type'];
	$fac = $_REQUEST['fac_id'];
	$dept = $_REQUEST['dept_id'];
	$splz = $_REQUEST['splz_id'];
	$grad = $_REQUEST['grad'];
    
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
    
    $getGraduation = $conn->prepare("SELECT * FROM tbl_grad_cycle WHERE grad_cycle_id='".$grad."'");
    $getGraduation->execute();
    $graduation = $getGraduation->fetch();
    
    
    ################## Gender stats #####################################

    $getGenderStats = $conn->prepare("SELECT 
                                        ad.gender,
                                        COUNT(ad.reg_no) AS count
                                    FROM tbl_admission ad
                                        INNER JOIN tbl_graduants gr ON ad.reg_no = gr.reg_no
                                    WHERE
                                        gr.splz_id = '".$splz."' AND
                                        gr.grad_cycle_id = '".$grad."' 
                                    GROUP BY ad.gender
                                    ");
    
    $getGenderStats->execute();
    
    
    ################## Classification stats #####################################
    $getClassificationStats = $conn->prepare("SELECT 
                                        gr.classification,
                                        COUNT(ad.reg_no) AS count
                                    FROM tbl_admission ad
                                        INNER JOIN tbl_graduants gr ON ad.reg_no = gr.reg_no
                                    WHERE
                                        gr.splz_id = '".$splz."' AND
                                        gr.grad_cycle_id = '".$grad."' 
                                    GROUP BY gr.classification
                                    ");
    
    $getClassificationStats->execute();
?>  

<div class="table-responsive">
<table class="table table-hover table-sm" border="1">
    <thead>
        <tr><th scope="col" colspan="3"><h3 style="text-align:left">PROGRAM TYPE: <span style="color: blue;"><b><?php  echo " ".$progName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="3"><h3 style="text-align:left">FACULTY: <span style="color: blue;"><b><?php  echo " ".$facName ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="3"><h3 style="text-align:left">ACADEMIC PROGRAM: <span style="color: blue;"><b><?php  echo " ".$spavname ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="3"><h3 style="text-align:left">AWARD: <span style="color: blue;"><b><?php  echo " ".$degree_name ?></b></span></h3></th></tr>
        <tr><th scope="col" colspan="3"><h3 style="text-align:left">Graduation Date: <span style="color: blue;"><b><?php  echo " ".$graduation['grad_date'] ?></b></span></h3></th></tr>
    </thead>
</table>  

<br><br>

<table class="table table-hover table-sm" border="1">
    <thead>
        <tr>
            <th scope="col" colspan="3">Gender Statistics</th>
        </tr>
        <tr>
            <th scope="col">S/N</th>
            <th scope="col">Gender</th>
            <th scope="col">Numbers</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $i = 1;
            while($stats = $getGenderStats->fetch()){
                if(strtoupper($stats['gender'])=='F'){
                    $gender = 'Female';
                } else if(strtoupper($stats['gender'])=='M'){
                    $gender = 'Male';
                } else if(strtoupper($stats['gender'])==''){
                    $gender = 'N/A';
                } else{
                    $gender = $stats['gender'];
                }
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo strtoupper($gender); ?></td>
            <td><?php echo number_format($stats['count']); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>  

<br><br>

<table class="table table-hover table-sm" border="1">
    <thead>
        <tr>
            <th scope="col" colspan="3">Classification Statistics</th>
        </tr>
        <tr>
            <th scope="col">S/N</th>
            <th scope="col">Classification</th>
            <th scope="col">Numbers</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $i = 1;
            $total = 0;
            while($stats = $getClassificationStats->fetch()){
                $total += $stats['count'];
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $stats['classification']; ?></td>
            <td><?php echo $stats['count']; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="2">Total</th>
            <th><?php echo number_format($total); ?></th>
        </tr>
    </tbody>
</table>  
</div>