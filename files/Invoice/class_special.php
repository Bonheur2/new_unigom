                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Generate Invoice</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body pb-0 row">
                                                    <div class="form-group col-12 col-sm-4 col-lg-3">
                                                        <label>Program Types</label>
                                                        <select class="form-control select2" style="width:100%" id="prg_type">
                                                            <option disabled selected>--choose one--</option>
                                                            <?php
                                                                $sql_progs=$conn->prepare("SELECT prg_type_id,prg_type_full_name FROM tbl_program_type WHERE campus_id='".$camp_id."'");
                                                                $sql_progs->execute();
                                                                while($prg_type=$sql_progs->fetch()){
                                                            ?>
                                                            <option value="<?php echo $prg_type['prg_type_id']; ?>"><?php echo $prg_type['prg_type_full_name']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                        <span id="spinner1"></span>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3" id="spec" hidden>
                                                        <label>Specialization</label>
                                                        <select class="form-control select2" style="width:100%" id="splz_id">
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-3" id="level" hidden>
                                                        <label>Level</label>
                                                        <select class="form-control select2" style="width:100%" id="level_id">
                                                        </select>
                                                    </div>
                                                    <div class="form-group  col-12 col-sm-4 col-lg-3" id="intke" hidden>
                                                        <label>Intake</label>
                                                        <select class="form-control select2" style="width:100%" id="intake_id">
                                                            <option disabled selected>--choose one--</option>
                                                            <?php
                                                                $sql_intakes=$conn->prepare("SELECT i.intake_id,i.intake_month,a.acad_year 
                                                                                                FROM tbl_intake i INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id=a.acad_cycle_id");
                                                                $sql_intakes->execute();
                                                                while($intake=$sql_intakes->fetch()){
                                                            ?>
                                                            <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12" id="loader" hidden>
                                                        <center><span id="spinner2"></span></center>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                                
                                    <div class="col-12 col-sm-12 col-lg-12" id="list" hidden>
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="generate_class_invoice" action="generate_class_invoice" method="POST">
                                                    <input type="hidden" name="action" value="gen_class_special">
                                                    <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                                    <input type="hidden" name="intake" id="intake">
                                                    <input type="hidden" name="acad_cycle_id" value="<?=$_GET['acad'] ?>">
                                                    <table class="table table-hover table-sm" id="student_list" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col" class="d-none d-sm-table-cell">Names</th>
                                                                <th scope="col">Registration Number</th>
                                                                <th scope="col">Selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="students">
                                                        </tbody>
                                                    </table> 
                                                    <hr/>
                                                    <br>
                                                    <div class="row">
                                                        <div class="form-group  col-12 col-sm-6 col-lg-4">
                                                            <label>Fee Category</label><br>
                                                            <select class="form-control select2" style="width:100%" name="fee_id" required>
                                                                <?php
                                                                    $sql_fee=$conn->prepare("SELECT id,name FROM fee_category WHERE id=6 AND status=1");
                                                                    $sql_fee->execute();
                                                                    while($fee=$sql_fee->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-6 col-lg-4">
                                                            <label>Amount/ Student</label><br>
                                                            <input type="number" class="form-control" name="amount" min="0.1" step=".01" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-6 col-lg-4">
                                                            <label>Details</label><br>
                                                            <textarea class="form-control" name="comment" maxlength="100" required></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="buttons" style="display:flex; flex-direction:row-reverse; margin-top:10px;">
                                                        <button type="submit" id="cs" class="btn btn-icon btn-primary"><span id="spinner9"></span>&nbsp;<i class="fas fa-file-invoice"></i>&nbsp;<span id="indicator9">Save invoice</span>&nbsp;</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>