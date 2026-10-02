<?php 
    include ('../../../meet/con.php');
?>
<div class="table-responsive" style="border-radius: 5px; border: 2px solid green">
    <table class="table table-hover table-sm" id="module_to_year_table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Module Code</th>
            <th scope="col">Module Name</th>
            <th scope="col">Credits</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql=$conn->prepare("SELECT m.module_code,
                                        m.module_name,
                                        tm.*
                                    FROM tbl_modules tm
                                    INNER JOIN modules m ON 
                                        m.module_id=tm.mod_id
                                    WHERE tm.level_id = ? AND tm.splz_id = ? ORDER BY tm.status ASC");
            $sql->execute([$_POST['level_id'], $_POST['splz_id']]);
            $i = 1;
            $credits = 0;
            while($mods=$sql->fetch()){
                if($mods['status'] == 1){
                    $credits += $mods['module_credits'];
                }
         ?>
        <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><?php echo $mods['module_code']; ?></td>
            <td><?php echo $mods['module_name']; ?></td>
            <td><?php echo $mods['module_credits']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="card-footer bg-whitesmoke">
        <h6>Total Credits: <?=$credits; ?></h6>
    </div>
</div>