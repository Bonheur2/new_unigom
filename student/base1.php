<!-- Start app main Content -->
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        
        <div class="section-body">
            <?php
                $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 LIMIT 1");
                $acad->execute();
                $ac=$acad->fetch();
            ?>
            <h2 class="section-title"><?php  echo date("F d Y")." | ".$ac['acad_year']; ?></h2>
            <?php
            
    $select="SELECT * FROM tbl_register_program_ug WHERE reg_no='$identification' AND reg_active='1'";
    $cselect=$conn->prepare($select);
    $cselect->execute();
    $row_cselect=$cselect->fetch(PDO::FETCH_ASSOC);
    
    $select_invoice="SELECT * FROM tbl_invoice WHERE reg_no='".$row_cselect['reg_no']."' and level_id='".$row_cselect['level_id']."'";
    $cselect_invoice=$conn->prepare($select_invoice);
    $cselect_invoice->execute();
    $row_cselect_invoice=$cselect_invoice->fetch(PDO::FETCH_ASSOC);
    
    $select_payment="SELECT SUM(amount) AS total_paid FROM payment_trial where reg_no='".$row_cselect_invoice['reg_no']."' AND fee_id='".$row_cselect_invoice['fee_id']."'";
    $cselect_payment=$conn->prepare($select_payment);
    $cselect_payment->execute();
    $row_cselect_payment=$cselect_payment->fetch(PDO::FETCH_ASSOC);
    $amount =$row_cselect_invoice['balance'];
    
    $sixty_percent = round($amount * (60 / 100), 1);
    $hundred_percent = round($amount * (100 / 100), 1);
    
    $is_allowed = false;
    if ($row_cselect['level_id'] == 1) {
        $is_allowed = ($row_cselect_payment['total_paid'] >= $hundred_percent);
    } else {
        $is_allowed = ($row_cselect_payment['total_paid'] >= $sixty_percent);
    }
    // If payment_status is not 1, restrict to only the first page
    if ($is_allowed) {
            
            
            ?>
            <div class="row">
                <div class="col-md-8" style="display:flex; flex-direction:row; justify-content:center; flex-wrap:wrap; padding:0px;">
                    <div class="col-md-6">
                        <a href="edu?mis=regm">
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
                                            $mds=$conn->prepare("SELECT COUNT(DISTINCT(module_id)) as count FROM tbl_markby_module WHERE reg_no='".$identification."'");
                                            $mds->execute();
                                            $mds_cnt=$mds->fetch();
                                            echo number_format($mds_cnt['count']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> 
                    <div class="col-md-6">
                        <a href="edu?mis=reoMo">
                            <div class="card card-statistic-1">
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-refresh"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Retake modules</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $rptm=$conn->prepare("SELECT * FROM tbl_markby_module WHERE reg_no='".$identification."' AND status=16");
                                            $rptm->execute();
                                            $rptm_cnt=0;
                                            while($rtk=$rptm->fetch()){
                                                $cur_rtks=$conn->prepare("SELECT * FROM tbl_markby_module WHERE reg_no='".$rtk['reg_no']."' AND module_id='".$rtk['module_id']."' AND status=1 AND enrolled=1");
                                                $cur_rtks->execute();
                                                while($c=$cur_rtks->fetch()){
                                                    $rptm_cnt++;
                                                }
                                            }
                                            echo number_format($rptm_cnt);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                    <?php
                        $sql = $conn->prepare("select * from tbl_register_program_ug where reg_no ='".$_SESSION['Identification']."' and reg_active =1 ORDER BY reg_prg_id DESC LIMIT 1");
                        $sql->execute();
                        if($sql->rowCount() > 0){
                            $sql = $sql->fetch();
                    ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Timetable</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="stu" value="<?php echo $_SESSION['Identification']; ?>">
                                <input type="hidden" id="splz" value="<?php echo $sql['splz_id']; ?>">
                                <input type="hidden" id="level" value="<?php echo $sql['level_id']; ?>">
                                <input type="hidden" id="prg" value="<?php echo $sql['prg_mode_id']; ?>">
                                <select class="form-control select2" id="sem" style="width:100%;">
                                    <option>--choose semester--</option>
                                    <?php
// Get the student's semesters based on their registration number
$selectStudentSem = "SELECT * FROM tbl_student_semester WHERE reg_no = :reg_no";
$stmtStudentSem = $conn->prepare($selectStudentSem);
$stmtStudentSem->execute(['reg_no' => $_SESSION['Identification']]);

// Fetch the active academic cycle
$selectAcadCycle = "SELECT * FROM tbl_acad_cycle WHERE status = '1'";
$stmtAcadCycle = $conn->prepare($selectAcadCycle);
$stmtAcadCycle->execute();
$rowAcadCycle = $stmtAcadCycle->fetch(PDO::FETCH_ASSOC);

// Check if an academic cycle is found
if ($rowAcadCycle) {
    $acadCycleId = $rowAcadCycle['acad_cycle_id'];

    foreach ($stmtStudentSem as $rowStudentSem) {
        $semId = $rowStudentSem['sem_id'];

        // Fetch semesters based on the academic year and semester ID
        $selectSem = "
            SELECT ts.*, s.* 
            FROM tbl_semester ts
            INNER JOIN semester s ON ts.semester = s.id 
            WHERE ts.acad_year = :acad_year AND ts.sem_id = :sem_id
        ";
        $stmtSem = $conn->prepare($selectSem);
        $stmtSem->execute([
            'acad_year' => $acadCycleId,
            'sem_id' => $semId
        ]);

        // Generate the options for each semester found
        while ($sem = $stmtSem->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"{$sem['semester']}\">{$sem['name']}</option>";
        }
    }
}
?>

                                </select><span id="spinner5"></span>
                            </div>

                            <div class="card-body">
                                <div id ="table"></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <div class="card card-hero">
                        <div class="card-header"> 
                            <div class="card-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="card-description" style="text-align:center;">Current modules - Attendance</div>
                        </div>
                        <div class="card-body" id="top-5-scroll">
                            <ul class="list-unstyled list-unstyled-border">
                                <?php 
                                    $modules=$conn->prepare("SELECT m.module_credits,
                                                                    md.module_name 
                                                                FROM tbl_markby_module mm
                                                                    INNER JOIN tbl_modules m ON mm.module_id=m.module_id
                                                                    INNER JOIN modules md ON m.mod_id=md.module_id
                                                                WHERE mm.reg_no='".$identification."' AND mm.enrolled=1 AND mm.status=1");
                                    $modules->execute();
                                    if($modules->rowCount()>0){
                                        while($mod=$modules->fetch()){
                                ?>
                                <li class="media">
                                    <img class="mr-3 rounded bg-success" width="50" src="../img/mod_icon.png" alt="">
                                    <div class="media-body">
                                        <div class="media-title"><?php echo $mod['module_name'] ?></div>
                                        <div class="mt-1">
                                            <div class="budget-price">
                                                <div class="budget-price-label"><?php echo  number_format($mod['module_credits'])." credits" ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php }} else{ ?>
                                <li class="media">
                                    <div class="media-body">
                                        <div class="media-title">No modules found</div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }
    else
    {
        ?>
        <div class="row">
            <div class="col-md-6">
                        <a href="edu?mis=regm">
                            <div class="card card-statistic-1">
                                
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Annancement</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Pay Your Fee </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
        </div>
        
        <?php
    }
            
            ?>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('change', '#sem', function(){
            var getData= {
                    stu: $("#stu").val(),
                    splz: $("#splz").val(),
                    lev: $("#level").val(),
                    prg: $("#prg").val(),
                    sem: $("#sem").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/student.php",
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