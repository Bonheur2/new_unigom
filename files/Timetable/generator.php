<?php
    include ('../../meet/con.php');
    $prg_type = $_POST['prg_type'];
    $splz = $_POST['splz'];
    $level = $_POST['level'];
    $semester = $_POST['semester'];
?>

<div class="card-body" style="border: 2px solid #0a7033; width: 95%; margin: auto; margin-bottom: 20px; border-radius: 5px">
    <form action="" id="generator" class="row" method="POST">
        <input type="hidden" name="splz_id" value="<?php echo $splz; ?>">
        <input type="hidden" name="level_id" value="<?php echo $level; ?>">
        <input type="hidden" name="term_id" value="<?php echo $semester; ?>">
        <input type="hidden" class="form-check-input" value="yes" name="include_exam" required>
        <div class="form-group col-12 col-sm-7 col-lg-7" style="border-right: 1px solid grey;">
            <h6>Modules List</h6>
            <hr/>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Selected</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql=$conn->prepare("SELECT 
                                                    tm.module_id,
                                                    m.module_code,
                                                    m.module_name
                                                FROM tbl_modules tm 
                                                    INNER JOIN modules m ON 
                                                        tm.mod_id = m.module_id
                                                WHERE tm.splz_id = ? AND tm.level_id = ? AND term_id = ? AND tm.status = ?
                                                ORDER BY m.module_name ASC");
                        $sql->execute([$splz, $level, $semester, 1]);
                        $i=1;
                        while($mods=$sql->fetch()){
                     ?>
                    <tr>
                        <th scope="row"><?php echo $i++; ?></th>
                        <td><?php echo $mods['module_name']; ?></td>
                        <th><input type="checkbox" name="selectedModules[]" value="<?php echo $mods['module_id']; ?>"></th>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group col-12 col-sm-5 col-lg-5">
            <h6>Table Settings</h6>
            <hr/>
            <div class="form-group col-12">
                <label>Learning mode</label>
                <select class="form-control select2" style="width:100%" name="prg_mode">
                    <?php
                        $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status = 1");
                        $sql_mode->execute();
                        while($progs_mode=$sql_mode->fetch()){
                    ?>
                    <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group col-12">
                <label>Start Date</label>
                <input type="date" class="form-control" name="start_date" value="<?php echo date("Y-m-d"); ?>" required>
            </div>
            
            <div class="form-group col-12" id="dmodules">
                <label>Modules to exam</label>
                <input type="number" class="form-control" name="modules" id="modules" value="2" required>
            </div>
            <div class="form-group col-12" id="dweeks">
                <label>Weeks to Exam</label>
                <input type="number" class="form-control" name="weeks" id="weeks" value="3" required>
            </div>
        </div>
        <div class="modal-footer bg-whitesmoke col-12">
            <button type="submit" class="btn btn-primary" id="genBtn"><span id="spinner200"></span>&nbsp;<span id="indicator200">Generate</span></button>
        </div>
    </form>
</div>
