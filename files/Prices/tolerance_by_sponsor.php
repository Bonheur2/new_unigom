                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>New Settings</h4>
                                            <div class="card-header-action">
                                                <a data-collapse="#mycard-collapse-3" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="collapse hide" id="mycard-collapse-3">
                                            <div class="card-body">
                                                <form id="save_sponsor_tolerance" action="save_sponsor_tolerance" method="POST">
                                                    <input type="hidden" name="action" value="save_sponsor_tolerance">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Sponsor</label><br>
                                                            <select class="form-control select2" style="width:100%" name="spon_id" required>
                                                                <?php
                                                                    $sql_spon=$conn->prepare("SELECT * FROM tbl_sponsor where status=1 ORDER BY spon_full_name ASC");
                                                                    $sql_spon->execute();
                                                                    while($spon=$sql_spon->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Tolerance Balance</label><br>
                                                            <input type="number" class="form-control" name="tolerance_balance" min="1" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Tolerance Expiration Date</label><br>
                                                            <input type="date" class="form-control" name="tolerance_expiration_date" min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                        <div class="form-group  col-12">
                                                            <center>
                                                                <button type="submit" class="btn btn-primary"><span id="spinner_s"></span>&nbsp;<span id="indicator_s">Save</span></button>
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
                                            <h4>Registered tolerances</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="sponsor_tolerance_table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Sponsor</th>
                                                        <th scope="col">Tolerance Balance</th>
                                                        <th scope="col">Tolerance Expiration Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql=$conn->prepare("SELECT
                                                                                tbl_sponsor.spon_full_name,
                                                                                tbl_sponsor_tolerance.*
                                                                            FROM
                                                                                tbl_sponsor_tolerance
                                                                            INNER JOIN tbl_sponsor ON tbl_sponsor_tolerance.spon_id = tbl_sponsor.spon_id ORDER BY tbl_sponsor_tolerance.tolerance_expiration_date DESC");
                                                        $sql->execute();
                                                        $i=1;
                                                        while($stol=$sql->fetch()){
                                                     ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $i++; ?></th>
                                                        <td><?php echo $stol['spon_full_name']; ?></td>
                                                        <td><?php echo $stol['tolerance_balance']; ?></td>
                                                        <td><?php echo $stol['tolerance_expiration_date']; ?></td>
                                                        <th>  
                                                            <div class="buttons row">
                                                                <button type="button" data-id="<?php echo $stol['spon_tol_id']; ?>" class="btn btn-icon btn-primary btn-sm edit_s"><span id="spinner4s_<?php echo $stol['spon_tol_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
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