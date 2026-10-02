<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Report.xls");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    include ('../../meet/con.php');

    $st = $conn->prepare("SELECT prg_type_full_name FROM tbl_program_type WHERE prg_type_id=?");
    $st->execute([$_REQUEST['prg_type']]);
    $prg = $st->fetch();

    $st1 = $conn->prepare("SELECT fac_full_name FROM tbl_faculty WHERE fac_id=?");
    $st1->execute([$_REQUEST['fac_id']]);
    $fac = $st1->fetch();
    
    $st2 = $conn->prepare("SELECT splz_full_name FROM tbl_specialization WHERE splz_id=?");
    $st2->execute([$_REQUEST['splz_id']]);
    $splz = $st2->fetch();

    $st3 = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id=?");
    $st3->execute([$_REQUEST['acad_cycle_id']]);
    $acad = $st3->fetch();
 
?>
<table class="table table-hover table-sm">
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <tr>
            <td>Program Type : <?=$prg['prg_type_full_name']; ?></td>
        </tr>
        <tr>
            <td>Faculty : <?=$fac['fac_full_name']; ?></td>
        </tr>
        <tr>
            <td>Specilaization : <?=$splz['splz_full_name']; ?></td>
        </tr>
        <tr>
            <td>Academic Year : <?=$acad['acad_year']; ?></td>
        </tr>
    </thead>
</table>
<br>
<br> 
<table class="table table-hover table-sm" border="1">
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <th>Year</th>
        <th>Female</th>
        <th>Male</th>
        <th>Total</th>
    </thead>
    <tbody>
        <?php
        
            $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type =? AND status = ?");
            $stmt->execute([$_REQUEST['prg_type'], 1]);
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
    
                $stmt2->execute([$_REQUEST['prg_type'], $_REQUEST['fac_id'], $_REQUEST['dept_id'], $_REQUEST['splz_id'], $_REQUEST['acad_cycle_id'], $lev['level_id']]);
                while($count = $stmt2->fetch()){
                    $total+=$count['female_count'] + $count['male_count'];
                    $total_fem += $count['female_count'];
                    $total_mal += $count['male_count'];
        ?>
        <tr
            <td><?php echo $lev['level_full_name']; ?></td>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <td><?php echo number_format($count['female_count'] + $count['male_count']); ?></td>
            </td>
        </tr>
    <?php }} ?>
    </tbody>
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <tr>
            <th>Total</th>
            <th><?php echo number_format($total_fem); ?></th>
            <th><?php echo number_format($total_mal); ?></th>
            <th><?php echo number_format($total); ?></th>
        </tr> 
    </thead>
</table>
<br>
<br>
<table class="table table-hover table-sm" border="1">
    <thead style="background-color: #87CEEB; font-weight: bold;">
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
                                            r.level_id IN (SELECT level_id FROM tbl_level WHERE prg_type = '".$_REQUEST['prg_type']."' AND status = 1)
                                        GROUP BY nat.nat_id, nat.nationality;
                                        ");

            $stmt3->execute([$_REQUEST['prg_type'], $_REQUEST['fac_id'], $_REQUEST['dept_id'], $_REQUEST['splz_id'], $_REQUEST['acad_cycle_id']]);
            $i = 1;
            $total2 = 0;
            while($count = $stmt3->fetch()){
                $total2 += $count['count'];
                $total2_fem += $count['female_count'];
                $total2_mal += $count['male_count'];
        ?>
        <tr>
            <td><?php echo $count['nationality']; ?></td>
            <td><?php echo $count['female_count']; ?></td>
            <td><?php echo $count['male_count']; ?></td>
            <td><?php echo $count['count']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <thead style="background-color: #87CEEB; font-weight: bold;">
        <tr>
            <th>Total</th>
            <th><?php echo number_format($total2_fem); ?></th>
            <th><?php echo number_format($total2_mal); ?></th>
            <th><?php echo number_format($total2); ?></th>
        </tr> 
    </thead>
</table>