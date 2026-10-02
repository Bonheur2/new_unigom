<?php 
    include ('../../../meet/con.php');
?>
<div class="table-responsive" style="border-radius: 5px; border: 2px solid green; width:100%;">
    <table class="table table-hover table-sm" id="module_to_year_table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col" class="d-none d-sm-table-cell">Module Code</th>
            <th scope="col">Module Name</th>
            <th scope="col" class="d-none d-sm-table-cell">Enroll</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql=$conn->prepare("SELECT m.module_code,
                                        m.module_name,
                                        tm.module_credits,
                                        mm.mark_id,
                                        mm.enrolled
                                    FROM tbl_markby_module mm
                                    INNER JOIN tbl_modules tm ON 
                                        mm.module_id=tm.module_id
                                    INNER JOIN modules m ON 
                                        tm.mod_id=m.module_id
                                    WHERE tm.level_id = '".$_POST['level']."' AND mm.reg_no = '".$_POST['stu']."' ORDER BY mm.mark_id ASC");
            $sql->execute();
            $i=1;
            $credits=0;
            while($mods=$sql->fetch()){
                if($mods['enrolled'] == 1){
                    $credits +=$mods['module_credits'];
                }
         ?>
        <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td class="d-none d-sm-table-cell"><?php echo $mods['module_code']; ?></td>
            <td><?php echo $mods['module_name']; ?></td>
            <td class="d-none d-sm-table-cell">  
                <div class="buttons row">
                    <label class="custom-switch btn btn-sm btn-light">
                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input enroll" data-id="<?php echo $mods['mark_id']; ?>" <?php echo $mods['enrolled']==1?'checked':''; ?>>
                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $mods['mark_id']; ?>"></span>&nbsp;
                    </label>
                </div>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="card-footer bg-whitesmoke">
        <h6>Total Credits: <?=$credits; ?></h6>
    </div>
</div>