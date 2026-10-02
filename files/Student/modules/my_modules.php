<!-- Start app main Content -->
<input type="hidden" id="stu" value="<?php echo $identification; ?>">
                <?php
                $stmt=$conn->prepare("SELECT tbl_register_program_ug.*,
                                        tbl_level.level_full_name 
                                            FROM tbl_register_program_ug 
                                        INNER JOIN tbl_level ON tbl_register_program_ug.level_id=tbl_level.level_id 
                                            WHERE tbl_register_program_ug.reg_no='".$identification."' AND tbl_register_program_ug.reg_active=1");
                $stmt->execute();
                $regData=$stmt->fetch();
                ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h3>My modules</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">My modules</a></div>
                            
                        </div>
    
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card" id="sample-login">
                                    <div class="card-header" style="display:flex; flex-direction:column;justify-content:center;">
                                        <h4>View Mode</h4>
                                        <div class="form-group">
                                            <div class="custom-switches-stacked mt-2" style="display:flex; flex-direction:row;">
                                                <label class="custom-switch mr-2">
                                                    <input type="radio" name="view-mode" value="1" class="custom-switch-input view-mode" checked>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Tabular</span>
                                                </label>
                                                <label class="custom-switch">
                                                    <input type="radio" name="view-mode" value="2" class="custom-switch-input view-mode">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">One by One</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="tabular">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="module_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Module Code</th>
                                                    <th scope="col">Module Name</th>
                                                    <th scope="col">Credits</th>
                                                    <th scope="col">Level</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_modules.module_credits,tbl_level.level_full_name,tbl_markby_module.module_id,tbl_markby_module.enrolled,modules.module_code,modules.module_name
                                                                        FROM tbl_markby_module 
                                                                        INNER JOIN tbl_modules ON 
                                                                        tbl_markby_module.module_id=tbl_modules.module_id
                                                                        INNER JOIN tbl_level ON 
                                                                        tbl_modules.level_id=tbl_level.level_id
                                                                        INNER JOIN modules ON 
                                                                        tbl_modules.mod_id=modules.module_id
                                                                        WHERE tbl_modules.splz_id='".$regData['splz_id']."' AND tbl_modules.level_id='".$regData['level_id']."' AND tbl_markby_module.reg_no='".$identification."' AND tbl_markby_module.enrolled=1");
                                                                        
                                                                        
                                                    $sql2=$conn->prepare("SELECT tbl_modules.module_credits,tbl_level.level_full_name,tbl_markby_module.module_id,tbl_markby_module.enrolled,modules.module_code,modules.module_name
                                                                        FROM tbl_markby_module 
                                                                        INNER JOIN tbl_modules ON 
                                                                        tbl_markby_module.module_id=tbl_modules.module_id
                                                                        INNER JOIN tbl_level ON 
                                                                        tbl_modules.level_id=tbl_level.level_id
                                                                        INNER JOIN modules ON 
                                                                        tbl_modules.mod_id=modules.module_id
                                                                        WHERE tbl_modules.splz_id='".$regData['splz_id']."' AND tbl_modules.level_id='".$regData['level_id']."' AND tbl_markby_module.reg_no='".$identification."'  AND tbl_markby_module.enrolled=1");
                                                    
                                                    $sql->execute();
                                                    $sql2->execute();
                                                    $i=1;
                                                    while($mods=$sql->fetch()){
                                                        
                                                        $stmt = $conn->prepare("SELECT * FROM tbl_module_evaluation WHERE module_id=? AND stu=?");
                                                        $stmt->execute([$mods['module_id'], $identification]);
                                                        
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $mods['module_code']; ?></td>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <td><?php echo $mods['module_credits']; ?></td>
                                                    <td><?php echo $mods['level_full_name']; ?></td>
                                                    <th>  
                                                        <?php if($stmt->rowCount()==0){ ?>
                                                        <a href="edu?mis=moeva&mod=<?php echo $mods['module_id']; ?>" class="btn btn-sm btn-success">Evaluate</a>
                                                        <?php } ?>
                                                    </th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-body" id="divided" hidden>
                                        <?php
                                            while($mods2=$sql2->fetch()){
                                                    $stmt2 = $conn->prepare("SELECT * FROM tbl_module_evaluation WHERE module_id=? AND stu=?");
                                                    $stmt2->execute([$mods2['module_id'], $identification]);
                                        ?>
                                        <div class="card-body row" style="border-radius:5px;margin-bottom:10px;border: 2px solid green;">
                                            <div class="form-group col-12 col-md-6 col-lg-4">
                                                    <div class="article-user-details">
                                                        <div class="text-job"><b>Module name</b></div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $mods2['module_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Module Code</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $mods2['module_code']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Credits</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $mods2['module_credits']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-6 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job"><b>Level</b></div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $mods2['level_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <?php if($stmt2->rowCount()==0){ ?>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                <a href="edu?mis=moeva&mod=<?php echo $mods2['module_id']; ?>" class="btn btn-sm btn-success">Evaluate</a>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#module_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
        
//view mode
    $(".view-mode").change(function(e){
        if($(this).val()==1){
            $('#tabular').attr('hidden',false);
            $('#divided').attr('hidden',true);
        }
        else{
            $('#tabular').attr('hidden',true);
            $('#divided').attr('hidden',false);
        }
    });
});
</script>