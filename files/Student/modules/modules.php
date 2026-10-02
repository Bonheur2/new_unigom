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
    
                    $getAcadYearData=$conn->prepare("SELECT acad_cycle_id, enrollment FROM tbl_acad_cycle WHERE acad_cycle_id='".$regData['acad_cycle_id']."'");
                    $getAcadYearData->execute();
                    $AcadYearData=$getAcadYearData->fetch();
                    $enrollment=$AcadYearData['enrollment'];
                ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h3>Modules</h3>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Modules in <?php echo $regData['level_full_name']; ?></a></div>
                            
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
                                                    <?php if($enrollment==1){ ?>
                                                    <th scope="col">Enroll</th>
                                                    <?php } ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT 
                                                                                tm.module_credits,
                                                                                tm.credit_price,
                                                                                lev.level_full_name,
                                                                                mm.module_id,
                                                                                mm.locked,
                                                                                mm.enrolled,
                                                                                m.module_code,
                                                                                m.module_name
                                                                            FROM tbl_markby_module mm 
                                                                                INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id
                                                                                INNER JOIN tbl_level lev ON tm.level_id = lev.level_id
                                                                                INNER JOIN modules m ON tm.mod_id = m.module_id
                                                                            WHERE tm.splz_id='".$regData['splz_id']."' AND 
                                                                                tm.level_id='".$regData['level_id']."' AND 
                                                                                mm.reg_no='".$identification."'
                                                                                
                                                                            ");
                                                    
                                                    $sql2=$conn->prepare("SELECT 
                                                                                tm.module_credits,
                                                                                tm.credit_price,
                                                                                lev.level_full_name,
                                                                                mm.module_id,
                                                                                mm.enrolled,
                                                                                m.module_code,
                                                                                m.module_name
                                                                            FROM tbl_markby_module mm 
                                                                                INNER JOIN tbl_modules tm ON mm.module_id = tm.module_id
                                                                                INNER JOIN tbl_level lev ON tm.level_id = lev.level_id
                                                                                INNER JOIN modules m ON tm.mod_id = m.module_id
                                                                            WHERE tm.splz_id='".$regData['splz_id']."' AND 
                                                                                tm.level_id='".$regData['level_id']."' AND 
                                                                                mm.reg_no='".$identification."'
                                                                            ");
                                                    
                                                    $sql->execute();
                                                    $sql2->execute();
                                                    $i=1;
                                                    $totalP = 0;
                                                    $totalM = 0;
                                                    while($mods=$sql->fetch()){
                                                        if($mods['enrolled'] == 1){
                                                            $totalP += $mods['module_credits']* $mods['credit_price'];
                                                            $totalM += 1;
                                                        }
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $mods['module_code']; ?></td>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <td><?php echo $mods['module_credits']; ?></td>
                                                    <td><?php echo $mods['level_full_name']; ?></td>
                                                    <?php if($enrollment == 1 && $mods['locked'] == 0){ ?>
                                                    <th>  
                                                        <!--<label class="custom-switch btn btn-light">-->
                                                        <!--    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input enroll" data-id="<?php echo $mods['module_id']; ?>" <?php echo $mods['enrolled']==1?'checked':''; ?>>-->
                                                        <!--    <span class="custom-switch-indicator"></span><span class="spinner_<?php echo $mods['module_id']; ?>"></span>-->
                                                        <!--</label>-->
                                                    </th>
                                                    <?php } ?>
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
                                            <?php if($enrollment==1){ ?>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="user-detail-name">
                                                            <!--<label class="custom-switch btn btn-light">-->
                                                            <!--    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input enroll" data-id="<?php echo $mods2['module_id']; ?>" <?php echo $mods2['enrolled']==1?'checked':''; ?>>-->
                                                            <!--    <span class="custom-switch-indicator"></span><span class="spinner_<?php echo $mods2['module_id']; ?>"></span><span class="indicator_<?php echo $mods2['module_id']; ?>">&nbsp;Enroll</span>-->
                                                            <!--</label>-->
                                                        </div>
                                                    </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <!--<div class="card-footer">-->
                                    <!--    <table class="table col-12 col-md-6 col-lg-6" border="1">-->
                                    <!--        <tr>-->
                                    <!--            <th colspan="2"><center>Finance Summary</center></th>-->
                                    <!--        </tr> -->
                                            
                                    <!--        <tr>-->
                                    <!--            <th>Total Modules</th>-->
                                    <!--            <td><?php echo $totalM; ?></td>-->
                                    <!--        </tr>-->
                                    <!--        <tr>-->
                                    <!--            <th>Total Payable fees</th>-->
                                    <!--            <td><?php echo number_format($totalP, 2).' FRW'; ?></td>-->
                                    <!--        </tr> -->
                                    <!--        <tr>-->
                                    <!--            <th colspan="2" class="text-right"><button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('/files/Student/modules/invoice?stu=<?php echo $identification; ?>&total=<?php echo $totalP; ?>&acad=<?php echo $regData['acad_cycle_id']; ?>', 'Tuition Invoice', 800, 600);"><i class="fa fa-download"></i>Confirm & Print</button>-->
                                    <!--          </th>-->
                                    <!--        </tr> -->
                                    <!--    </table>-->
                                    <!--</div>-->
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
    //save modules
    $(".enroll").change(function(e){
        var mod=$(this).data('id');
        var formData = {
            module:mod,
            stu:$("#stu").val(),
            action:'enroll'
        }
        $(".enroll").attr('disabled',true);
        $('.spinner_'+mod).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('.indicator_'+mod).html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('.spinner_'+mod).fadeOut('fast');
                    $('.indicator_'+mod).html("Enroll");
                    $(".enroll").attr('disabled',false);
                    if(data.status==200){
                        pop_up_success(data.message);
                        // $('#module_table').load(location.href + " #module_table");
                        window.location.reload();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $(".enroll").attr('disabled',false);
                    $('.spinner_'+mod).fadeOut('fast');
                    $('.indicator_'+mod).html("Enroll");
                    pop_wrong("Something went wrong!");
                    
                }
             });
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
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>