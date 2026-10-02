                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>New Settings</h4>
                                            <div class="card-header-action">
                                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="collapse hide" id="mycard-collapse">
                                            <div class="card-body">
                                                <form id="save_price" action="save_price" method="POST">
                                                    <input type="hidden" name="action" value="register_prices">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3">
                                                            <label>Campus</label><br>
                                                            <select class="form-control select2" style="width:100%" name="camp_id" id="camp_id">
                                                                <option></option>
                                                                <?php
                                                                    $sql_camp=$conn->prepare("SELECT * FROM tbl_campus where camp_active=1 ORDER BY camp_full_name ASC");
                                                                    $sql_camp->execute();
                                                                    while($camp=$sql_camp->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner0"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3"  id="prg" hidden>
                                                            <label>Program Type</label><br>
                                                            <select class="form-control select2" style="width:100%" name="prg_type_Id" id="prg_type_Id" required></select>
                                                            <span id="spinner1"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="spec" hidden>
                                                            <label>Specialization</label><br>
                                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id" required></select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="intake" hidden>
                                                            <label>Intake</label><br>
                                                            <select class="form-control select2" style="width:100%" name="intake_id" id="intake_id" required>
                                                                <?php
                                                                    $sql_intakes=$conn->prepare("SELECT i.intake_id,i.intake_month,a.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id = a.acad_cycle_id WHERE i.status=1");
                                                                    $sql_intakes->execute();
                                                                    while($intake=$sql_intakes->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month'].' | '.$intake['acad_year']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="lev" hidden>
                                                            <label>Level</label><br>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id" required></select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="amt" hidden>
                                                            <label>Amount to pay</label><br>
                                                            <input type="number" class="form-control" name="amount" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="tb" hidden>
                                                            <label>Tolerance Balance</label><br>
                                                            <input type="number" class="form-control" name="tolerance_balance" min="1" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-3 col-lg-3" id="ted" hidden>
                                                            <label>Tolerance Expiration Date</label><br>
                                                            <input type="date" class="form-control" name="tolerance_expiration_date" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" hidden>
                                                            <center>
                                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                            </center>
                                                        </div> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Registered prices</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="price_table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Campus</th>
                                                        <th scope="col">Program</th>
                                                        <th scope="col">Specialization</th>
                                                        <th scope="col">Level</th>
                                                        <th scope="col">Intake</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT
                                                                                tbl_campus.camp_full_name,
                                                                                tbl_program_type.prg_type_full_name,
                                                                                tbl_specialization.splz_full_name,
                                                                                tbl_level.level_full_name,
                                                                                tbl_intake.intake_month,
                                                                                tbl_amount_to_pay.*
                                                                            FROM
                                                                                tbl_amount_to_pay
                                                                            INNER JOIN tbl_campus ON tbl_amount_to_pay.camp_id = tbl_campus.camp_id
                                                                            INNER JOIN tbl_program_type ON tbl_amount_to_pay.prg_type_Id = tbl_program_type.prg_type_id
                                                                            INNER JOIN tbl_specialization ON tbl_amount_to_pay.splz_id = tbl_specialization.splz_id
                                                                            INNER JOIN tbl_level ON tbl_amount_to_pay.level_id = tbl_level.level_id
                                                                            INNER JOIN tbl_intake ON tbl_amount_to_pay.intake_id = tbl_intake.intake_id
                                                                            ORDER BY tbl_amount_to_pay.tolerance_expiration_date DESC");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($price=$sql->fetch()){
                                                     ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $i++; ?></th>
                                                        <td><?php echo $price['camp_full_name']; ?></td>
                                                        <td><?php echo $price['prg_type_full_name']; ?></td>
                                                        <td><?php echo $price['splz_full_name']; ?></td>
                                                        <td><?php echo $price['level_full_name']; ?></td>
                                                        <td><?php echo $price['intake_month']; ?></td>
                                                        <th>  
                                                            <div class="buttons row">
                                                                <button type="button" data-id="<?php echo $price['m_to_pay_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $price['m_to_pay_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                <button type="button" data-id="<?php echo $price['m_to_pay_id']; ?>" class="btn btn-icon btn-success btn-sm view"><span id="spinner3_<?php echo $price['m_to_pay_id']; ?>"></span>&nbsp;<i class="far fa-eye"></i>&nbsp;view</button>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>