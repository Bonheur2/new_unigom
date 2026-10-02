<?php
include ('../../meet/con.php');
?>
<hr/>
<table class="table table-hover table-sm">
    <thead>
        <th>Year</th>
        <th>Female</th>
        <th>Male</th>
        <th>Total</th>
    </thead>
    <tbody>
        <?php
        
            $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type =? AND status = ?");
            $stmt->execute([$_POST['prg_type'], 1]);
            $total = 0;
            while($lev = $stmt->fetch()){
                $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ? AND
                                                r.fac_id = ? AND
                                                r.dept_id = ? AND
                                                r.splz_id = ? AND
                                                r.acad_cycle_id = ? AND
                                                r.level_id = ? AND
                                                r.reg_active IN (1,6)
                                            ");
    
                $stmt2->execute([$_POST['prg_type'], $_POST['fac_id'], $_POST['dept_id'], $_POST['splz_id'], $_POST['acad_cycle_id'], $lev['level_id']]);
                while($count = $stmt2->fetch()){
                    $total += $count['female_count'] + $count['male_count'];
                    $total_fem += $count['female_count'];
                    $total_mal += $count['male_count'];
        ?>
        <tr>
            <td><?php echo $lev['level_full_name']; ?></td>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <td><?php echo number_format($count['female_count'] + $count['male_count']); ?></td>
        </tr>
    <?php }} ?>
    </tbody>
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <tr>
            <td>Total</td>
            <td><?php echo number_format($total_fem); ?></td>
            <td><?php echo number_format($total_mal); ?></td>
            <td><?php echo number_format($total); ?></td>
        </tr> 
    </thead>
</table>
<br>
<table class="table table-hover table-sm">
    <thead>
        <th>#</th>
        <th>Nationality</th>
        <th>Female</th>
        <th>Male</th>
        <th>Total</th>
    </thead>
    <tbody>
        <?php
            $stmt3 = $conn->prepare("SELECT
                                            COUNT(ad.reg_no) AS count,
                                            nat.nat_id, nat.nationality,
                                            COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                            COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                        FROM
                                            tbl_admission ad
                                            INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            INNER JOIN tbl_nationality nat ON ad.nationality = nat.nat_id
                                        WHERE
                                            r.prg_type = ? AND
                                            r.fac_id = ? AND
                                            r.dept_id = ? AND
                                            r.splz_id = ? AND
                                            r.acad_cycle_id = ? AND
                                            r.reg_active IN (1,6) AND
                                            r.level_id IN (SELECT level_id FROM tbl_level WHERE prg_type = '".$_POST['prg_type']."' AND status = 1)
                                        GROUP BY nat.nat_id, nat.nationality;
                                        ");

            $stmt3->execute([$_POST['prg_type'], $_POST['fac_id'], $_POST['dept_id'], $_POST['splz_id'], $_POST['acad_cycle_id']]);
            $i = 1;
            $total2 = 0;
            while($count = $stmt3->fetch()){
                $total2 += $count['count'];
                $total2_fem += $count['female_count'];
                $total2_mal += $count['male_count'];
                
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $count['nationality']; ?></td>
            <td><?php echo $count['female_count']; ?></td>
            <td><?php echo $count['male_count']; ?></td>
            <td><?php echo $count['count']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <tr>
            <td colspan="2">Total</td>
            <td><?php echo number_format($total2_fem); ?></td>
            <td><?php echo number_format($total2_mal); ?></td>
            <td><?php echo number_format($total2); ?></td>
        </tr> 
    </thead>
</table>
<div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
    <a href="../files/Student_reports/hec_excel.php?prg_type=<?=$_POST['prg_type'] ?>&fac_id=<?=$_POST['fac_id'] ?>&dept_id=<?=$_POST['dept_id'] ?>&splz_id=<?=$_POST['splz_id'] ?>&acad_cycle_id=<?=$_POST['acad_cycle_id'] ?>" target="_blank" class="btn btn-success" ><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>