                <?php

                $sql=$conn->prepare("SELECT tbl_modules.module_credits,tbl_level.level_full_name,tbl_markby_module.marks,tbl_markby_module.module_id,tbl_markby_module.enrolled,modules.module_code,modules.module_name
                                            FROM tbl_markby_module 
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN tbl_level ON tbl_modules.level_id=tbl_level.level_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                            WHERE tbl_markby_module.reg_no='".$identification."' AND tbl_markby_module.status=16");
                
                $sql2=$conn->prepare("SELECT tbl_modules.module_credits,tbl_level.level_full_name,tbl_markby_module.marks,tbl_markby_module.module_id,tbl_markby_module.enrolled,modules.module_code,modules.module_name
                                             FROM tbl_markby_module 
                                                INNER JOIN tbl_modules ON tbl_markby_module.module_id=tbl_modules.module_id
                                                INNER JOIN tbl_level ON tbl_modules.level_id=tbl_level.level_id
                                                INNER JOIN modules ON tbl_modules.mod_id=modules.module_id
                                             WHERE tbl_markby_module.reg_no='".$identification."' AND tbl_markby_module.status=16");
                                                    
                                                    $sql->execute();
                                                    $sql2->execute();
                ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h3>Repeated Modules</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Repeated modules</a></div>
                            
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
                                                    <th scope="col">Marks/100</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i=1;
                                                    while($mods=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $mods['module_code']; ?></td>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <td><?php echo $mods['module_credits']; ?></td>
                                                    <td><?php echo $mods['level_full_name']; ?></td>
                                                    <td><?php echo $mods['marks']; ?></td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-body" id="divided" hidden>
                                        <?php
                                        while($mods2=$sql2->fetch()){
                                        ?>
                                        <div class="card-body row" style="border-radius:5px;margin-bottom:10px;<?php if($mods2['enrolled']==1){echo 'border: 2px solid green';} else{echo 'border: 2px solid grey';} ?>">
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
                                            <div class="form-group col-6 col-md-6 col-lg-2">
                                                <div class="article-user-details">
                                                    <div class="text-job"><b>Marks/100</b></div>
                                                    <div class="user-detail-name"><a href="#"><b><?php echo $mods2['marks']; ?></b></a></div>
                                                </div>
                                            </div>
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

    $('#module_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       });
       
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
   function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Wrong',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'Info:',
        message: feedback,
        position: 'topCenter'
      });
    }
</script>