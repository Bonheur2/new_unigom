    <!-- Start app main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div></div>
            <div class="section-body">
                <h2 class="section-title"><?php  echo date("F d Y"); ?></h2>
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-12" style="display:flex; flex-direction:row;flex-wrap:wrap;padding:0px;">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Modules</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $mods=$conn->prepare("SELECT COUNT(DISTINCT(module_id)) as count FROM tbl_module_leader WHERE staff_id='".$identification."' AND status=1");
                                            $mods->execute();
                                            $mod_cnt=$mods->fetch();
                                            echo number_format($mod_cnt['count']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Teaching modes</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $mode=$conn->prepare("SELECT COUNT(DISTINCT(mode)) as count FROM tbl_module_leader WHERE staff_id='".$identification."' AND status=1");
                                            $mode->execute();
                                            $m_count=$mode->fetch();
                                            echo number_format($m_count['count']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Timetable</h4>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="Ident" value="<?php echo $_SESSION['Identification']; ?>">
                                    <select class="form-control select2" style="width:100%" name="semester" id="semester">
                                        <option>--choose semester--</option>
                                        <?php
                                        
                                            $select_ac="SELECT * FROM tbl_acad_cycle WHERE status='1'";
                                        $cselect_ac=$conn->prepare($select_ac);
                                        $cselect_ac->execute();
                                        $row_cselect_ac=$cselect_ac->fetch(PDO::FETCH_ASSOC);
                                            $sql=$conn->prepare("SELECT 
                                            ts.*,
                                                s.* 
                                            FROM tbl_semester ts
                                            INNER JOIN semester s ON ts.semester = s.id where acad_year='".$row_cselect_ac['acad_cycle_id']."'");
                                            $sql->execute();
                                            while($sem=$sql->fetch()){
                                                ?>
                                        <option value="<?php echo $sem['semester']; ?>"><?php echo $sem['name']; ?> </option>
                                        <?php } ?>
                                    </select><span id="spinner5"></span>
                                </div>
                                <div class="card-body">
                                    <div id ="table"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-description">Module - Students</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                                <ul class="list-unstyled list-unstyled-border">
                                    <?php 
                                        $sql=$conn->prepare("SELECT ml.module_id,ml.mode, 
                                                                    md.module_name,
                                                                    s.splz_id,
                                                                    s.splz_full_name,
                                                                    l.level_full_name,
                                                                    prg.prg_mode_full_name
                                                                FROM tbl_module_leader ml 
                                                                    INNER JOIN tbl_modules m ON ml.module_id=m.module_id 
                                                                    INNER JOIN modules md ON m.mod_id=md.module_id
                                                                    INNER JOIN tbl_specialization s ON m.splz_id=s.splz_id 
                                                                    INNER JOIN tbl_level l ON m.level_id=l.level_id 
                                                                    INNER JOIN tbl_program_mode prg ON ml.mode=prg.prg_mode_id 
                                                                WHERE ml.staff_id='".$identification."' AND ml.status=1");
                                        $sql->execute();
                                        while($module=$sql->fetch()){
                                            $sql2=$conn->prepare("SELECT COUNT(DISTINCT(mm.reg_no)) as count 
                                                                    FROM tbl_markby_module mm 
                                                                        INNER JOIN tbl_register_program_ug r ON mm.reg_no=r.reg_no AND mm.splz_id=r.splz_id
                                                                    WHERE mm.module_id='".$module['module_id']."' AND 
                                                                          mm.splz_id='".$module['splz_id']."' AND 
                                                                          r.prg_mode_id='".$module['mode']."' AND mm.enrolled=1 AND mm.status=1");
                                            $sql2->execute();
                                            $total=$sql2->fetch();
                                    ?>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="55" src="../img/group.svg" alt="">
                                        <div class="media-body">
                                            <div class="float-right"><div class="font-weight-600 text-muted text-small"><?php echo $module['prg_mode_full_name'] ?></div></div>
                                            <div class="media-title"><?php echo $module['module_name'] ?></div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo $module['splz_full_name'] ?></div>
                                                </div>
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo $module['level_full_name']." &nbsp;&nbsp;|&nbsp;&nbsp; ".number_format($total['count'])." students" ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
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
        $(document).on('change', '#semester', function(){
            var getData= {
                    sem: $("#semester").val(),
                    staff_id: $("#Ident").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/lecturer.php",
                data: getData,
                success:function(data){
                    $('#spinner5').fadeOut('fast');
                    $('#table').html(data);
				},
				error:function(error){
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        })
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>