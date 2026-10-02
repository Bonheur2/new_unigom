<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Marks Validation</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Check marks</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Class information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM tbl_department WHERE dept_id IN($department)");
                            $stmt->execute();
                            $data = $stmt->fetch();
                        ?>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="">
                                <div class="card-body row">
                                    <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>Specialization</label>
                                        <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                            <?php 
                                                $stmt = $conn->prepare("SELECT * FROM tbl_specialization WHERE dept_id IN($department)");
                                                $stmt->execute();
                                                while($splz = $stmt->fetch()){
                                            ?>
                                            <option value="<?php echo $splz['splz_id']; ?>"><?php echo $splz['splz_full_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span id="spinner2"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label>Intake</label>
                                        <select class="form-control select2" style="width:100%" name="intake_id" id="intake_id">
                                            <?php
                                                $sql_intakes=$conn->prepare("SELECT i.intake_id, i.intake_month, a.acad_year, b.prg_type_short_name 
                                                        			FROM tbl_intake i,
                                                        			tbl_acad_cycle a,
                                                        			tbl_program_type b 
                                                        			WHERE i.acad_cycle_id=a.acad_cycle_id AND i.prg_type=b.prg_type_id 
                                                        			ORDER BY i.intake_id DESC");
                                                $sql_intakes->execute();
                                                while($intake=$sql_intakes->fetch()){
                                                    
                                                    ?>
                                            <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year'].' [ '.$intake['prg_type_short_name'].' ]'; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>Level</label>
                                        <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                            <?php 
                                                $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type = '".$data['prg_type']."'");
                                                $stmt->execute();
                                                while($lev = $stmt->fetch()){
                                            ?>
                                            <option value="<?php echo $lev['level_id']; ?>"><?php echo $lev['level_full_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group  col-12 col-sm-4 col-lg-4">
                                        <label>Learning mode</label>
                                        <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
                                            <?php
                                                $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status=1");
                                                $sql_mode->execute();
                                                while($progs_mode=$sql_mode->fetch()){
                                            ?>
                                            <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader">
                                        <button type="button" id="load_btn" class="btn btn-icon btn-primary"><i class="fa fa-wifi"></i>&nbsp;Load data</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $("#load_btn").click(function(){
            var prg_type = parseInt($("#prg_type").val());
            var intake = parseInt($("#intake_id").val());
            var splz = parseInt($("#splz_id").val());
            var mode = parseInt($("#mode_id").val());
            var level = parseInt($("#level_id").val());
            
            if(Number.isInteger(prg_type) && Number.isInteger(intake) && Number.isInteger(splz) && Number.isInteger(mode) && Number.isInteger(level)){
                window.location.href = '/files/Marks/validate_excel?prg_type=' +prg_type+ '&intake=' + intake + '&splz='+ splz + '&mode='+ mode + '&level='+ level;
            }else{
                pop_info("Some field are not filled!");
            }
        });
    });
</script>