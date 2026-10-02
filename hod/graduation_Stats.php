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
<table class="table table-hover table-sm">
    <thead>
        <tr><th scope="col" colspan="3"><h6 style="text-align:left">PROGRAM TYPE:<small><?php  echo " ".$progName ?> </small></h6></th></tr>
        <tr><th scope="col" colspan="3"><h6 style="text-align:left">FACULTY:<small><?php  echo " ".$facName ?> </small></h6></th></tr>
        <tr><th scope="col" colspan="3"><h6 style="text-align:left">ACADEMIC PROGRAM:<small><?php  echo " ".$spavname ?></small> </th></h6> </tr>
        <tr><th scope="col" colspan="3"><h6 style="text-align:left">AWARD:  <small><?php  echo " ".$degree_name ?> </small></h6></th></tr>
    </thead>
</table>  

<br><br>

<table class="table table-hover table-sm">
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

<table class="table table-hover table-sm">
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
<div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
    <a href="download_grad_stats?prg_type=<?=$prg_type; ?>&fac_id=<?=$fac; ?>&dept_id=<?=$dept; ?>&splz_id=<?=$splz; ?>&grad=<?=$grad ?>" target="_blank" class="btn btn-success"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>