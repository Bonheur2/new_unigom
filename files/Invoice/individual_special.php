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
                                                <input type="text" class="form-control" placeholder="search by reg. number or names" id="input" value="<?=$_GET['stu'] ?>">
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
                                            <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body collapse show" id="mycard-collapse4">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <th>#</th>
                                                <th>Specialzization</th>
                                                <th>Level</th>
                                                <th>Academic year</th>
                                                <th>Invoice</th>
                                                <th>Payment</th>
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
                    <div class="section-body" id="invForm">
                        <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                            <h4>Invoice Here</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="generate_invoice" action="generate_invoice" method="POST">
                                            <input type="hidden" name="action" value="gen_ind_special">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="acad_cycle_id" value="<?=$_GET['acad'] ?>">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Student ID</label><br>
                                                    <input type="text" class="form-control" name="reg_no" value="<?=$_GET['stu'] ?>" readonly required>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
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
                                                    <label>Amount</label><br>
                                                    <input type="number" class="form-control" name="amount" min="0.1" step=".01" required>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label>Details</label><br>
                                                    <textarea class="form-control" name="comment" maxlength="100" required></textarea>
                                                </div>
                                                <div class="form-group col-12">
                                                    <center>
                                                        <button id="is" type="submit" class="btn btn-primary"><span id="spinner0"></span>&nbsp;<span id="indicator0">Save invoice</span></button>
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