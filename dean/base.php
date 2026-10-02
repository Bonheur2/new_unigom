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
                                        $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                                        $acad->execute();

                                        $acs = array();
                                        while($a = $acad->fetch()){
                                            array_push($acs, $a['acad_cycle_id']);
                                        }

                                        $acad2=$conn->prepare("SELECT COALESCE(COUNT(reg_active),0) AS count FROM tbl_register_program_ug WHERE acad_cycle_id IN ('".implode("','", $acs)."') AND reg_active=1 AND fac_id IN ($faculty)");
                                        $acad2->execute();
                                        $ac2=$acad2->fetch();
                                        $active=$ac2['count'];
                                                
                                        $acad3=$conn->prepare("SELECT COALESCE(COUNT(reg_active),0) AS count FROM tbl_register_program_ug WHERE acad_cycle_id IN ('".implode("','", $acs)."') AND reg_active=3 AND fac_id IN ($faculty)");
                                        $acad3->execute();
                                        $ac3=$acad3->fetch();
                                        $suspended=$ac3['count'];
                                                
                                        $acad4=$conn->prepare("SELECT COALESCE(COUNT(reg_active),0) AS count FROM tbl_register_program_ug WHERE acad_cycle_id IN ('".implode("','", $acs)."') AND reg_active=5 AND fac_id IN ($faculty)");
                                        $acad4->execute();
                                        $ac4=$acad4->fetch();
                                        $dropout=$ac4['count'];
                                                
                                        $acad5=$conn->prepare("SELECT COALESCE(COUNT(reg_active),0) AS count FROM tbl_register_program_ug WHERE acad_cycle_id IN ('".implode("','", $acs)."') AND fac_id IN ($faculty)");
                                        $acad5->execute();
                                        $ac5=$acad5->fetch();
                                        $total=$ac5['count'];
                                    ?>
                                    <div class="card-stats-title">Student Statistics 
                                        <div class="dropdown d-inline">
                                            <a class="font-weight-600" href="#"></a>
                                        </div>
                                    </div>
                                    <div class="card-stats-items">
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?php echo number_format($active); ?></div>
                                            <div class="card-stats-item-label">Active</div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?php echo number_format($suspended); ?></div>
                                            <div class="card-stats-item-label">Suspended</div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?php echo number_format($dropout); ?></div>
                                            <div class="card-stats-item-label">Dropout</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-success">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo number_format($total); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <?php
                                        $prog_stats=$conn->prepare("SELECT count(tbl_register_program_ug.reg_no) as count,
                                                                        tbl_program_type.prg_type_full_name
                                                                        FROM tbl_register_program_ug 
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_register_program_ug.prg_type=tbl_program_type.prg_type_id
                                                                        WHERE tbl_register_program_ug.reg_active=1 AND acad_cycle_id IN ('".implode("','", $acs)."') AND tbl_register_program_ug.fac_id IN ($faculty) GROUP BY tbl_register_program_ug.prg_type ORDER BY tbl_program_type.prg_type_full_name ASC");
                                        $prog_stats->execute();
                                        $name=array();
                                        $count=array();
                                        while($stat=$prog_stats->fetch()){
                                            array_push($name,$stat['prg_type_full_name']);
                                            array_push($count,$stat['count']);
                                        }
                                        $jsonNames = json_encode($name);
                                        $jsonCount = json_encode($count);
                                    ?>
                                    <div class="card-stats-title">Program types 
                                        <div class="dropdown d-inline">
                                            <a class="font-weight-600" href="#"></a>
                                        </div>
                                    </div>
                                    <canvas id="myChart2" height="155"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <a href="edu?mis=pdept">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Departments</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $depts=$conn->prepare("SELECT COUNT(dept_id) as count FROM tbl_department WHERE status=1 AND fac_id IN ($faculty)");
                                                $depts->execute();
                                                $depts_cnt=$depts->fetch();
                                                echo number_format($depts_cnt['count']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <a href="edu?mis=splz">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Specializations</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $splz=$conn->prepare("SELECT COUNT(splz_id) as count FROM tbl_specialization WHERE status=1 AND fac_id IN ($faculty)");
                                                $splz->execute();
                                                $splz_cnt=$splz->fetch();
                                                echo number_format($splz_cnt['count']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <?php 
                                    $sql02=$conn->prepare("SELECT COUNT(grad_id) as graduants FROM tbl_graduants WHERE grad_cycle_id='".$ac['acad_cycle_id']."' AND splz_id IN (SELECT splz_id FROM tbl_specilaization WHERE fac_id IN ($faculty))");
                                    $sql02->execute();
                                    $tot=$sql02->fetch();
                                ?>
                                <h4><?php echo number_format($tot['graduants']); ?></h4>
                                <div class="card-description">Graduants</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                                <ul class="list-unstyled list-unstyled-border">
                                    <?php 
                                        $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY acad_cycle_id DESC");
                                        $sql->execute();
                                        while($acad=$sql->fetch()){
                                            $sql2=$conn->prepare("SELECT COUNT(grad_id) as graduants FROM tbl_graduants WHERE grad_cycle_id='".$acad['acad_cycle_id']."' AND splz_id IN (SELECT splz_id FROM tbl_specilaization WHERE fac_id IN ($faculty))");
                                            $sql2->execute();
                                            $tot=$sql2->fetch();
                                    ?>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="40" src="../img/graduation-cap.svg" alt="">
                                        <div class="media-body">
                                            <div class="media-title"><?php echo $acad['acad_year'] ?></div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($tot['graduants'])." graduates" ?> </div>
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
<script src="../assets/modules/chart.min.js"></script>
<script>
    var jsonNames = <?php echo $jsonNames; ?>;
    var jsonCount = <?php echo $jsonCount; ?>;
    var ctx = document.getElementById("myChart2").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: jsonNames,
        datasets: [{
          label: 'Students',
          data: jsonCount,
          borderWidth: 2,
          backgroundColor: '#6777ef',
          borderColor: '#6777ef',
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              stepSize: 150
            }
          }],
          xAxes: [{
            ticks: {
              display: true
            },
            gridLines: {
              display: false
            }
          }]
        },
      }
    });
</script>
