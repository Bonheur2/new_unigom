<?php
    include ('../../meet/con.php');
    $prg_type=$_POST['prg'];
    $fac=$_POST['fac'];
    $dept=$_POST['dept'];
    $spec=$_POST['spec'];
    $level=$_POST['lev'];
    $mode=$_POST['mode'];
    $campus=$_POST['cms'];
    $getMods = $conn->prepare("SELECT 
                                            tbl_modules.module_id,
                                            modules.module_code,
                                            modules.module_name,
                                            tbl_module_leader.staff_id 
                                        FROM tbl_modules
                                            LEFT JOIN tbl_module_leader ON tbl_modules.module_id=tbl_module_leader.module_id AND tbl_module_leader.mode='".$mode."'
                                            INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                        WHERE tbl_modules.prg_type='".$prg_type."' AND 
                                        tbl_modules.fac_id='".$fac."' AND 
                                        tbl_modules.dept_id='".$dept."' AND 
                                        tbl_modules.splz_id='".$spec."' AND 
                                        tbl_modules.level_id='".$level."'");
        
    $getLeaders = $conn->prepare("SELECT family_name,first_name,Identification FROM tbl_users WHERE campus_id='".$campus."' AND role_id in(10,12) AND status=1");
        
    $getMods->execute();
    $getLeaders->execute();
?>
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Module Code</th>
        <th scope="col">Module Name</th>
        <th scope="col">Module Leader</th>
        <th scope="col">Assistants</th>
    </tr>
</thead>
<tbody>
    <?php
        $i=1;
        while($mod=$getMods->fetch()){
            // while($leader=$getLeaders->fetch()){
    ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $mod['module_code']; ?></td>
        <td><?php echo $mod['module_name']; ?></td>
        <td>
            <select class="form-control select2" style="width:100%" name="leader_<?php echo $mod['module_id'] ?>">
                <?php
                    while($leader=$getLeaders->fetch()){
                ?>
                <option value="<?php echo $leader['Identification'] ?>"><?php echo $leader['first_name']." ".$leader['family_name'] ?></option>
                <?php } ?>
            </select>
        </td>
        <td></td>
    </tr>
    <?php } ?>
</tbody>