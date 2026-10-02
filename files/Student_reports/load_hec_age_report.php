<?php
include ('../../meet/con.php');
?>
<style>
    .colored{
        background-color: #87CEEB; 
        font-weight: bold;
    }
</style>
<table class="table table-hover table-sm" border="1">
    <thead>
        <tr>
            <th rowspan="2">Age</th>
            <?php
                $stmt = $conn->prepare("SELECT tbl_program_type.*, tbl_campus.camp_full_name FROM tbl_program_type 
                INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                WHERE tbl_program_type.status = 1 ORDER BY tbl_program_type.prg_type_id ASC");
                $stmt->execute();
                while($prg_type = $stmt->fetch()){
            ?>
            
            <th colspan="2"><?php echo $prg_type['prg_type_full_name']." | ".$prg_type['camp_full_name']; ?></th>
            <?php } ?>
            <th colspan="3" class="colored">Total</th>
        </tr>
        <tr>
            <?php
                $ages = [17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34];
                $today = date('Y-m-d');
                
                $stmt = $conn->prepare("SELECT * FROM tbl_program_type WHERE status = 1 ORDER BY prg_type_id ASC");
                $stmt->execute();
                
                $prgs = array();
                $stmt2 = $conn->prepare("SELECT * FROM tbl_program_type WHERE status = 1 ORDER BY prg_type_id ASC");
                $stmt2->execute();
                
                while($prg_type = $stmt2->fetch()){
                    array_push($prgs, $prg_type['prg_type_id']);
                }
                
                while($prg_type = $stmt->fetch()){
            ?>
            <th>Female</th>
            <th>Male</th>
            <?php } ?>
            <th class="colored">Female</th>
            <th class="colored">Male</th>
            <th class="colored">Total</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($ages as $age){
            $fem = 0;
            $mal = 0;
        ?>
        <tr>
            <td><?php echo $age; ?></td>
            <?php
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) = ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], $age]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored" class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <?php } ?>
        <tr>
            <td>35-39</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) <= ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 35, 39]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <tr>
            <td>40-44</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) <= ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 40, 44]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <tr>
            <td>45-49</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) <= ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 45, 49]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <tr>
            <td>50-54</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) <= ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 50, 54]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <tr>
            <td>55-59</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) <= ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 55, 59]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td>
        </tr> 
        <tr>
            <td>>59</td>
            <?php
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) > ?;
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 59]);
                    
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td><?php echo number_format($count['female_count']); ?></td>
            <td><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td> 
        </tr> 
        <tr> 
            <td class="colored">Total</td>
            <?php  
                $fem = 0;
                $mal = 0;
                foreach($prgs as $prg){
                    $stmt2 = $conn->prepare("SELECT
                                                COUNT(CASE WHEN ad.gender = 'M' THEN 1 END) AS male_count,
                                                COUNT(CASE WHEN ad.gender = 'F' THEN 1 END) AS female_count
                                            FROM
                                                tbl_admission ad
                                                INNER JOIN tbl_register_program_ug r ON ad.reg_no = r.reg_no
                                            WHERE
                                                r.prg_type = ?
                                                AND r.acad_cycle_id = ?
                                                AND r.reg_active IN (1, 6)
                                                AND ad.dob REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                                AND TIMESTAMPDIFF(YEAR, ad.dob, CURDATE()) >=?
                                            ");
        
                    $stmt2->execute([$prg, $_POST['acad_cycle_id'], 17]);
                     
                    $count = $stmt2->fetch();
                    $fem += $count['female_count'];
                    $mal += $count['male_count'];
            ?>
            <td class="colored"><?php echo number_format($count['female_count']); ?></td>
            <td class="colored"><?php echo number_format($count['male_count']); ?></td>
            <?php } ?>
            <td class="colored"><?php echo number_format($fem); ?></td>
            <td class="colored"><?php echo number_format($mal); ?></td>
            <td class="colored"><?php echo number_format($fem+$mal); ?></td> 
        </tr>
    </tbody>
</table>
<div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
    <a href="../files/Student_reports/hec_age_excel.php?acad=<?=$_POST['acad_cycle_id'] ?>" target="_blank" class="btn btn-success" ><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
</div>