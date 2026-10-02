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
                            <a href="edu?mis=stf_dept">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Departments</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $depts=$conn->prepare("SELECT COUNT(staff_dept_id) as count FROM tbl_staff_dept WHERE status=1");
                                                $depts->execute();
                                                $dept_cnt=$depts->fetch();
                                                echo number_format($dept_cnt['count']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <a href="edu?mis=empList">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Employees</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $emp=$conn->prepare("SELECT COUNT(DISTINCT(staff_id)) as count FROM tbl_staff_post WHERE status=1");
                                                $emp->execute();
                                                $emp_cnt=$emp->fetch();
                                                echo number_format($emp_cnt['count']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Employees / Departments</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                        $departments=array();
                                        $employees=array();
                                        $getData=$conn->prepare("SELECT COUNT(sp.staff_id) as count, sd.staff_dept_full_name FROM tbl_staff_post sp INNER JOIN tbl_staff_dept sd ON sp.department = sd.staff_dept_id WHERE sp.status=1 GROUP BY sp.department");
                                        $getData->execute();
                                        while($data=$getData->fetch()){
                                            array_push($departments,$data['staff_dept_full_name']);
                                            array_push($employees,$data['count']);
                                        }
                                        $jsonDeps = json_encode($departments);
                                        $jsonCount = json_encode($employees);
                                        ?>
                                    <div id="myChart2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <?php 
                                    $sql = "SELECT SUM(gross_salary) as total FROM tbl_staff_salary WHERE ending_date IS NULL OR DATE_FORMAT(ending_date, '%Y-%m') >= '".date('Y-m')."'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $payroll = $stmt->fetch();
                                ?>
                                <h4><?php echo number_format($payroll['total'],2); ?></h4>
                                <div class="card-description">Monthly payroll</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                                <ul class="list-unstyled list-unstyled-border">
                                    <!--<li class="media">-->
                                    <!--    <img class="mr-3 rounded" width="40" src="" alt="">-->
                                    <!--    <div class="media-body">-->
                                    <!--        <div class="media-title"></div>-->
                                    <!--        <div class="mt-1">-->
                                    <!--            <div class="budget-price">-->
                                    <!--                <div class="budget-price-label"></div>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</li>-->
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
$(document).ready(function() {
    var jsonDeps = <?php echo $jsonDeps; ?>;
    var jsonCount = <?php echo $jsonCount; ?>;
    var ctx = document.getElementById("myChart2");
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: jsonDeps,
        datasets: [{
          label: 'Employees',
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
});
</script>
