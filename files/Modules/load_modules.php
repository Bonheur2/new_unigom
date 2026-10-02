<?php 
    include ('../../meet/con.php');
?>
<div class="table-responsive" style="border-radius: 5px; border: 2px solid green">
    <table class="table table-hover table-sm" id="module_to_year_table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Module Code</th>
            <th scope="col">Module Name</th>
            <th scope="col">Credits</th>
            <th scope="col">Action</th>
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
            <th>  
                <div class="buttons row">
                    <label class="custom-switch btn btn-sm btn-light">
                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $mods['module_id']; ?>" <?php echo $mods['status']==1?'checked':''; ?>>
                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $mods['module_id']; ?>"></span>&nbsp;
                    </label>
                </div>
            </th>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="card-footer bg-whitesmoke">
        <h6>Total Credits: <?=$credits; ?></h6>
    </div>
</div>