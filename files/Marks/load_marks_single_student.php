<?php
    include "../../meet/con.php";
    $connection ="";
    
    if($conn){
        $connection = "connected";
    }else{
       $connection = "no connection";
    }
    
    // request params
    $stu = $_REQUEST['stu'];
    $level = $_REQUEST['level'];

    $sqlMarks=$conn->prepare('SELECT 
                                    DISTINCT(tbl_markby_module.module_id),
                                    modules.module_code,
                                    modules.module_name,
                                    tbl_modules.cat AS per_cat,
                                    tbl_modules.exam AS per_exam,
                                    tbl_modules.module_credits,
                                    tbl_markby_module.cat,
                                    tbl_markby_module.final_exam,
                                    tbl_markby_module.marks
                                FROM tbl_markby_module
                                    INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                    INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                WHERE tbl_markby_module.reg_no = "'.$stu.'" AND
                                      tbl_modules.level_id = "'.$level.'" AND
                                      tbl_markby_module.enrolled = 1 AND 
                                      (tbl_markby_module.status=1 OR tbl_markby_module.status=6)
                            ');
    $sqlMarks->execute();
?>
<form action="save_ind_marks" method="POST" id="save_ind_marks">
    <input type="hidden" name="stu" value="<?php echo $stu; ?>">
    <input type="hidden" name="action" value="save_ind_marks">
    <table class="table table-hover table-sm">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Module</th>
                <th>CAT</th>
                <th>EXAM</th>
                <th>Total /100</th>
                <th>To be saved</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $i = 1;
            while($marks = $sqlMarks->fetch()){ 
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $marks['module_name']." [".$marks['module_code'].", ".$marks['module_credits']."]"; ?></td>
                <td><input  type="number" step=".01" class="form-control marks" data-id="<?php echo $marks['module_id']; ?>" name="cat_<?php echo $marks['module_id']; ?>" id="cat_<?php echo $marks['module_id']; ?>" min="0" max="<?php echo $marks['per_cat']; ?>" placeholder="CAT / <?php echo $marks['per_cat']; ?>" value="<?php echo $marks['cat']; ?>"></td>
                <td><input type="number" step=".01" class="form-control marks" data-id="<?php echo $marks['module_id']; ?>" name="exam_<?php echo $marks['module_id']; ?>" id="exam_<?php echo $marks['module_id']; ?>" min="0" max="<?php echo $marks['per_exam']; ?>" placeholder="EXAM / <?php echo $marks['per_exam']; ?>" value="<?php echo $marks['final_exam']; ?>"></td>
                <td><input type="text" step=".01" class="form-control" value="<?php echo $marks['marks']; ?>" disabled></td>
                <td><input type="checkbox" name="selectedModules[]" id="mod_<?php echo $marks['module_id']; ?>" value="<?php echo $marks['module_id']; ?>"></td>
            </tr>
        <?php } ?>
        </tbody> 
    </table>
    <button type="submit" class="btn btn-primary" id="save_btn"><span id="spinner8"></span>&nbsp;<span id="indication8">Save changes</span>
</form>