        <!-- Start app main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title"><?php  echo date("F d Y"); ?></h2>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12" style="display:flex; flex-direction:row;flex-wrap:wrap;padding:0px;">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <?php
                                        $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 LIMIT 1");
                                        $acad->execute();
                                        $ac=$acad->fetch();
                                                
                                        $acad5=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE reg_active=1");
                                        $acad5->execute();
                                        $ac5=$acad5->fetch();
                                        $total=$ac5['count'];
                                    ?>
                                    <div class="card-stats-title">Students' Statistics - 
                                        <div class="dropdown d-inline">
                                            <a class="font-weight-600" href="#"><?php echo $ac['acad_year']; ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Active students</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo number_format($total); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <?php
                                        $cards=$conn->prepare("SELECT COALESCE(COUNT(reg_no),0) AS count FROM tbl_student_card");
                                        $cards->execute();
                                        $total=$cards->fetch();
                                    ?>
                                    <div class="card-stats-title">Cards' Statistics - 
                                        <div class="dropdown d-inline">
                                            <a class="font-weight-600" href="#"><?php echo $ac['acad_year']; ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Cards issued</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo number_format($total['count']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12" hidden>
                            <div class="card card-statistic-1">
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-user-minus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Students with no card</h4>
                                    </div>
                                    <div class="card-body">
                                    <?php 
                                        $noCard=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_admission WHERE card_no IS NULL");
                                        $noCard->execute();
                                        $ncard=$noCard->fetch();
                                        echo number_format($ncard['count']);
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12" hidden>
                            <div class="card card-statistic-1">
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-user-minus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Staff with no card</h4>
                                    </div>
                                    <div class="card-body">
                                    <?php 
                                        $noCard=$conn->prepare("SELECT COALESCE(COUNT(staff_id),0) AS count FROM tbl_staff WHERE card_no IS NULL");
                                        $noCard->execute();
                                        $ncard=$noCard->fetch();
                                        echo number_format($ncard['count']);
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Student Card - Front</h4>
                                </div>
                                <div class="card-body">
                                    <img src="../files/Card/img/student_front.png" width="100%" heght="250" onclick="OpenPopupCenter('/files/Card/img/front.png', 'Student CARD - FRONT', 400, 300);" style="border:1px solid grey;">
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Student Card - Rear</h4>
                                </div>
                                <div class="card-body">
                                    <img src="../files/Card/img/student_back.png" width="100%" heght="250" onclick="OpenPopupCenter('/files/Card/img/back.png', 'Student CARD - REAR', 400, 300);" style="border:1px solid grey;">
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h4>1 card +</h4>
                                <div class="card-description">Cardholders</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                                <ul class="list-unstyled list-unstyled-border">
                                    <?php 
                                        $sql=$conn->prepare("SELECT * FROM (SELECT DISTINCT(reg_no),COUNT(reg_no) as count FROM tbl_student_card GROUP BY reg_no)  AS cardholders WHERE count>1");
                                        $sql->execute();
                                        while($cholder=$sql->fetch()){
                                            $sql2=$conn->prepare("SELECT fname,lname FROM tbl_admission WHERE reg_no='".$cholder['reg_no']."'");
                                            $sql2->execute();
                                            while($stu=$sql2->fetch()){
                                    ?>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="40" src="../img/user.png" alt="">
                                        <div class="media-body">
                                            <div class="media-title"><?php echo $cholder['reg_no'] ?></div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($cholder['count'])." cards" ?> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <?php }} ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>