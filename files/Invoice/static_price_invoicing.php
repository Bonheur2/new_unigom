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
                                                <input type="text" class="form-control" placeholder="search by reg. number or names" id="input2" value="<?=$_GET['stu'] ?>">
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="spinner2">
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
                                            <tbody id="contents2"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="section-body" id="info2" hidden>
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
                                                <th>Intake</th>
                                                <th>Invoice</th>
                                                <th>Payment</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody id="academics2"></tbody>
                                        </table>
            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <?php if($_GET['stu'] && $_GET['acad'] && $_GET['type']){ ?>
                    <div class="section-body" id="invForm2">
                        <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                            <h4>Invoice Here</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="generate_static_invoice" action="generate_static_invoice" method="POST">
                                            <input type="hidden" name="action" value="gen_ind_static">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="intake_id" value="<?=$_GET['intake'] ?>">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Student ID</label><br>
                                                    <input type="text" class="form-control" name="reg_no" value="<?=$_GET['stu'] ?>" readonly required>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Fee Category</label><br>
                                                    <select class="form-control select2" style="width:100%" name="fee_id" required>
                                                        <?php
                                                            $sql_fee=$conn->prepare("SELECT id,name FROM fee_category WHERE status=1 AND known_price=1 AND id!=6 ORDER BY name ASC");
                                                            $sql_fee->execute();
                                                            while($fee=$sql_fee->fetch()){
                                                                ?>
                                                        <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <center>
                                                        <label style="visibility: hidden;">Invoice Button</label><br>
                                                        <button type="submit" class="btn btn-primary"><span id="spinner3"></span>&nbsp;<span id="indicator3">Save invoice</span></button>
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