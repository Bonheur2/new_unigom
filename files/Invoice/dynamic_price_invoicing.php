<div class="section-body">
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Generate Invoice</h4>
                </div>
                <div class="card-body">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group col-12 col-md-6 m-auto">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    placeholder="search by reg. number or names" id="input"
                                                    value="<?=$_GET['stu'] ?>">
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="spinner">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card-body">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="contents">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-body" id="info" hidden>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Student information</h4>
                                        <div class="card-header-action">
                                            <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info"
                                                href="#"><i class="fas fa-minus"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body collapse show" id="mycard-collapse4">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <th>#</th>
                                                <th>Specialization</th>
                                                <th>Level</th>
                                                <th>Academic year</th>
                                                <th>Invoice</th>
                                                <th>Payment</th>
                                                <th>Special</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody id="academics">

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($_GET['stu'] && $_GET['acad']){ ?>
                    <?php
                        $reg=$_GET['stu'];
                        $select_user = "SELECT * FROM tbl_register_program_ug WHERE reg_no = :reg AND reg_active = 1";
                        $cselect_user = $conn->prepare($select_user);
                        $cselect_user->bindParam(':reg', $reg, PDO::PARAM_STR);
                        $cselect_user->execute();
                        $row_cselect_user = $cselect_user->fetch(PDO::FETCH_ASSOC);

                        $select_ac="SELECT * FROM tbl_acad_cycle WHERE status= 1";
                        $cselect_ac=$conn->prepare($select_ac);
                        $cselect_ac->execute();
                        $row_cselect_ac=$cselect_ac->fetch(PDO::FETCH_ASSOC);

                        // Fetch already invoiced fee IDs for the student
                        $select_invoiced = "SELECT fee_id FROM tbl_invoice WHERE reg_no = :reg AND acad_cycle_id='".$row_cselect_ac['acad_cycle_id']."' AND fee_id IS NOT NULL";
                        $cselect_invoiced = $conn->prepare($select_invoiced);
                        $cselect_invoiced->bindParam(':reg', $reg, PDO::PARAM_STR);
                        $cselect_invoiced->execute();
                        $invoiced_fees = $cselect_invoiced->fetchAll(PDO::FETCH_COLUMN);

                        // Convert the invoiced fee IDs to a string for the NOT IN clause
                        $invoiced_ids = implode(',', array_map('intval', $invoiced_fees));
                        if (empty($invoiced_ids)) {
                            $invoiced_ids = '0';
                        }

                        // Fetch available fees for the student that are not invoiced
                        $select_fee = "
                            SELECT id, name
                            FROM tbl_fee_category
                            WHERE status = 1
                              AND known_price = 0
                              AND id NOT IN ($invoiced_ids)
                              AND (
                                  (prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id)
                                  OR (prg_type_id IS NULL AND fac_id IS NULL AND dept_id IS NULL AND splz_id IS NULL AND level_id IS NULL)
                              )
                            ORDER BY name ASC
                        ";
                        $sql_fee = $conn->prepare($select_fee);
                        $sql_fee->bindParam(':prg_type_id', $row_cselect_user['prg_type'], PDO::PARAM_INT);
                        $sql_fee->bindParam(':fac_id', $row_cselect_user['fac_id'], PDO::PARAM_INT);
                        $sql_fee->bindParam(':dept_id', $row_cselect_user['dept_id'], PDO::PARAM_INT);
                        $sql_fee->bindParam(':splz_id', $row_cselect_user['splz_id'], PDO::PARAM_INT);
                        $sql_fee->bindParam(':level_id', $row_cselect_user['level_id'], PDO::PARAM_INT);
                        $sql_fee->execute();
                    ?>
                    <!-- Regular Fee Invoice -->
                    <div class="section-body" id="invForm">
                        <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                            <h4>Invoice - Regular Fee</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="generate_invoice" action="generate_invoice" method="POST">
                                            <input type="hidden" name="action" value="gen_ind_dynamic">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="acad_cycle_id" value="<?=$_GET['acad'] ?>">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Student ID</label><br>
                                                    <input type="text" class="form-control" name="reg_no"
                                                        value="<?=$_GET['stu'] ?>" readonly required>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Fee Category</label><br>
                                                    <select class="form-control select2" style="width:100%"
                                                        name="fee_id" required>
                                                        <?php
                                                        while ($fee = $sql_fee->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='" . htmlspecialchars($fee['id']) . "'>" . htmlspecialchars($fee['name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <center>
                                                        <label style="visibility: hidden;">Invoice Button</label><br>
                                                        <button id="pay" type="submit" class="btn btn-primary"><span
                                                                id="spinner0"></span>&nbsp;<span id="indicator0">Save
                                                                invoice</span></button>
                                                    </center>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Fee Invoice -->
                    <div class="section-body" id="spInvForm">
                        <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                            <h4>Invoice - Special Fee</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="generate_special_invoice" method="POST">
                                            <input type="hidden" name="action" value="gen_ind_special">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="acad_cycle_id" value="<?=$_GET['acad'] ?>">
                                            <input type="hidden" name="reg_no" value="<?=$_GET['stu'] ?>">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Special Fee Category <code>*</code></label>
                                                    <select class="form-control select2" style="width:100%"
                                                        name="special_fee_id" id="special_fee_select" required>
                                                        <option value="" disabled selected>--Choose Special Fee--</option>
                                                        <?php
                                                        $sql_sp = $conn->prepare("SELECT sp_inv_id, name, has_known_price, amount FROM tbl_special_invoice WHERE status = 1 ORDER BY name ASC");
                                                        $sql_sp->execute();
                                                        while($sp = $sql_sp->fetch()){
                                                        ?>
                                                        <option value="<?php echo $sp['sp_inv_id']; ?>"
                                                            data-has-price="<?php echo $sp['has_known_price']; ?>"
                                                            data-amount="<?php echo $sp['amount']; ?>">
                                                            <?php echo $sp['name']; ?>
                                                            <?php if($sp['has_known_price'] == 1) echo ' (' . number_format($sp['amount'], 2) . ')'; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-2 col-lg-2">
                                                    <label>Amount <code>*</code></label>
                                                    <input type="number" class="form-control" name="amount"
                                                        id="special_amount" placeholder="0.00" step="0.01" min="0" required>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control" name="comment"
                                                        placeholder="Reason / comment">
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <center>
                                                        <label style="visibility: hidden;">Button</label><br>
                                                        <button type="submit" class="btn btn-warning"><span
                                                                id="spinner_sp"></span>&nbsp;<span id="indicator_sp">Save
                                                                Special Invoice</span></button>
                                                    </center>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
