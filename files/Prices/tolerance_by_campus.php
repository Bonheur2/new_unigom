                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="save_campus_tolerance" action="save_campus_tolerance" method="POST">
                                                    <input type="hidden" name="action" value="save_campus_tolerance">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Campus</label><br>
                                                            <select class="form-control select2" style="width:100%" name="camp_id" id="c_camp_id" required>
                                                                <?php
                                                                    $sql_camp=$conn->prepare("SELECT * FROM tbl_campus where camp_active=1 ORDER BY camp_full_name ASC");
                                                                    $sql_camp->execute();
                                                                    while($camp=$sql_camp->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Tolerance Balance</label><br>
                                                            <input type="number" class="form-control" name="tolerance_balance" id="c_tolerance_balance" min="1" required>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Tolerance Expiration Date</label><br>
                                                            <input type="date" class="form-control" name="tolerance_expiration_date" id="c_tolerance_expiration_date"  min="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                        <div class="form-group  col-12">
                                                            <center>
                                                                <button type="submit" class="btn btn-primary"><span id="spinner_c"></span>&nbsp;<span id="indicator_c">Save</span></button>
                                                            </center>
                                                        </div> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>