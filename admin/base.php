<?php
    $d="itecdemo_edu";
?>
    <!-- Start app main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title"><?php  echo date("F d Y"); ?></h2>
                <?php
                    $getUsers=$conn->prepare("SELECT COUNT(u.role_id) as count, r.role FROM tbl_users u INNER JOIN tbl_user_roles r ON u.role_id=r.role_id WHERE u.status=1 GROUP BY r.role ORDER BY count DESC");
                    $getUsers->execute();
                    $roles=array();
                    $users=array();
                    while($user=$getUsers->fetch()){
                        array_push($roles,$user['role']);
                        array_push($users,$user['count']);
                    }
                    $jsonRoles = json_encode($roles);
                    $jsonUsers = json_encode($users);
                ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="card-description">System information</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                            <?php
                                function formatBytes($bytes, $precision = 2) {
                                    $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                    $bytes = max($bytes, 0);
                                    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                    $pow = min($pow, count($units) - 1);
                                    $bytes /= (1 << (10 * $pow));
                                    return round($bytes, $precision) . ' ' . $units[$pow];
                                }
                                
                                $stmt = $conn->prepare("SELECT SUM(data_length + index_length) AS allocated_size
                                                       FROM information_schema.TABLES
                                                       WHERE table_schema = '$d'");
                                $stmt->execute();
                                $data = $stmt->fetch();
                                $allocatedSize = $data['allocated_size'];
                                $usedStorage = formatBytes($allocatedSize);
                                
                                $users=$conn->prepare("SELECT COUNT(id) as count FROM tbl_users WHERE status=1");
                                $users->execute();
                                $users_cnt=$users->fetch();
                                
                                $roles=$conn->prepare("SELECT COUNT(role_id) as count FROM tbl_user_roles");
                                $roles->execute();
                                $roles_cnt=$roles->fetch();
                                
                                
                                $camps=$conn->prepare("SELECT COUNT(camp_id) as count FROM tbl_campus WHERE camp_active=1");
                                $camps->execute();
                                $camps_cnt=$camps->fetch();
                            ?>
                                <ul class="list-unstyled list-unstyled-border">
                                    <li class="media">
                                        <img class="mr-3 rounded" width="35" src="../img/database.png" alt="">
                                        <div class="media-body">
                                            <div class="media-title">Used storage space</div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo $usedStorage; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="35" src="../img/city.png" alt="">
                                        <div class="media-body">
                                            <div class="media-title">Campuses</div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($camps_cnt['count']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="35" src="../img/users.png" alt="">
                                        <div class="media-body">
                                            <div class="media-title">System users</div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($users_cnt['count']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="35" src="../img/role.png" alt="">
                                        <div class="media-body">
                                            <div class="media-title">System roles</div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($roles_cnt['count']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" style="display:flex; flex-direction:row;flex-wrap:wrap;padding:0px;">
                        <div class="card col-12">
                            <div class="card-body">
                                <div id="users"></div>
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
    var jsonRoles = <?php echo $jsonRoles; ?>;
    var jsonUsers = <?php echo $jsonUsers; ?>;
    console.log(jsonRoles);
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: ['#5cb85c'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%'	
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [{
            name: 'Users',
            data: jsonUsers
        }],
        xaxis: {
            categories: jsonRoles,
        },
        yaxis: {
            title: {
                text: 'users'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    var fVal = val.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });

                    return fVal
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#users"),
        options
    );

    chart.render();
})
</script>