<?php
    include ('../../meet/con.php');
    $semester = $_POST['sem'];
?>

<div class="card-body" style="border: 2px solid #0a7033; width: 95%; margin: auto; margin-bottom: 20px; border-radius: 5px">
    <div class="form-group col-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Level</th>
                    <th scope="col">Program Mode</th>
                    <th scope="col">Academic Year</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql=$conn->prepare("SELECT DISTINCT
                                                sc.splz_id,
                                                sc.level_id,
                                                sc.prg_mode,
                                                splz.splz_full_name,
                                                level.level_full_name,
                                                prg.prg_mode_full_name,
                                                ac.acad_year
                                            FROM tbl_t_schedule sc 
                                                INNER JOIN tbl_specialization splz ON sc.splz_id = splz.splz_id
                                                INNER JOIN tbl_level level ON sc.level_id = level.level_id
                                                INNER JOIN tbl_program_mode prg ON sc.prg_mode = prg.prg_mode_id
                                                LEFT JOIN tbl_acad_cycle ac ON sc.acad_cycle_id = ac.acad_cycle_id
                                            WHERE sc.term_id = ?
                                            ");
                    $sql->execute([$semester]);
                    $i=1;
                    while($tms = $sql->fetch()){
                 ?>
                <tr>
                    <th scope="row"><?php echo $i++; ?></th>
                    <td><?php echo $tms['splz_full_name']; ?></td>
                    <td><?php echo $tms['level_full_name']; ?></td>
                    <td><?php echo $tms['prg_mode_full_name']; ?></td>
                    <td><?php echo $tms['acad_year']; ?></td>
                    <td><a href="edu?mis=vt&splz=<?php echo $tms['splz_id']; ?>&lev=<?php echo $tms['level_id']; ?>&prg=<?php echo $tms['prg_mode']; ?>&sem=<?php echo $semester; ?>&intake=<?php echo $tms['intake_id']; ?>" class="btn btn-primary btn-sm" target="_blank">open</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-body" style="border: 2px solid #CD5C5C; width: 95%; margin: auto; margin-bottom: 20px; border-radius: 5px">
    <div class="form-group col-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Level</th>
                    <th scope="col">Program Mode</th>
                    <th scope="col">Academic Year</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql=$conn->prepare("SELECT DISTINCT
                                                sc.splz_id,
                                                sc.level_id,
                                                sc.prg_mode,
                                                splz.splz_full_name,
                                                level.level_full_name,
                                                prg.prg_mode_full_name,
                                                ac.acad_year
                                            FROM tbl_special_schedule sc 
                                                INNER JOIN tbl_specialization splz ON sc.splz_id = splz.splz_id
                                                INNER JOIN tbl_level level ON sc.level_id = level.level_id
                                                INNER JOIN tbl_program_mode prg ON sc.prg_mode = prg.prg_mode_id
                                                LEFT JOIN tbl_acad_cycle ac ON sc.acad_cycle_id = ac.acad_cycle_id
                                            ");
                    $sql->execute();
                    $i=1;
                    while($tms = $sql->fetch()){
                 ?>
                <tr>
                    <th scope="row"><?php echo $i++; ?></th>
                    <td><?php echo $tms['splz_full_name']; ?></td>
                    <td><?php echo $tms['level_full_name']; ?></td>
                    <td><?php echo $tms['prg_mode_full_name']; ?></td>
                    <td><?php echo $tms['acad_year']; ?></td>
                    <td><a href="edu?mis=vt2&splz=<?php echo $tms['splz_id']; ?>&lev=<?php echo $tms['level_id']; ?>&prg=<?php echo $tms['prg_mode']; ?>&intake=<?php echo $tms['intake_id']; ?>" class="btn btn-primary btn-sm" target="_blank">open</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
