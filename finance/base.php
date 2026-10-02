    <!-- Start app main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
                
            </div>
            <?php
                $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                $acad->execute();
                
                $acs = array();
                while($a = $acad->fetch()){
                    array_push($acs, $a['acad_cycle_id']);
                }
            ?>
            <div class="section-body">
                <h2 class="section-title"><?php  echo date("F d Y"); ?></h2>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12" style="display:flex; flex-direction:row;flex-wrap:wrap;padding:0px;">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <a href="edu?mis=allinvc">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Invoices</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $invoice=$conn->prepare("SELECT COALESCE(SUM(balance),0) as balance FROM tbl_invoice WHERE
                                                acad_cycle_id IN ('".implode("','", $acs)."') AND invoice_status =1 AND 
                                                approval_status = 1");
                                                $invoice->execute();
                                                $inv_balance=$invoice->fetch();
                                                echo number_format($inv_balance['balance'],2);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="edu?mis=cancelled">
                                   <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-cancel"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Cancelled Invoices</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $cancelled_invoice=$conn->prepare("SELECT COALESCE(SUM(balance),0) as balance FROM tbl_invoice WHERE 
                                                invoice_status=1 AND 
                                                approval_status= 2 AND 
                                                acad_cycle_id IN ('".implode("','", $acs)."')");
                                                
                                                $cancelled_invoice->execute();
                                                $cancelled_invoice1=$cancelled_invoice->fetch();
                                                echo number_format($cancelled_invoice1['balance'],2);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                </a>
                                 <a href="edu?mis=feescateg">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Fee category</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $fee=$conn->prepare("SELECT COUNT(id) as count FROM tbl_fee_category WHERE status=1");
                                                $fee->execute();
                                                $fee_cnt=$fee->fetch();
                                                echo number_format($fee_cnt['count']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <a href="edu?mis=spyt">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Payments</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                $payment=$conn->prepare("SELECT COALESCE(SUM(amount),0) as amount FROM payment WHERE acad_cycle_id IN ('".implode("','", $acs)."')");
                                                $payment->execute();
                                                $pyt=$payment->fetch();
                                                echo number_format($pyt['amount'],2);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="edu?mis=sbal">
                                <div class="card card-statistic-1">
                                    <div class="card-icon shadow-primary bg-success">
                                        <i class="fas fa-balance-scale"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Balance</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                                echo number_format($inv_balance['balance']-$pyt['amount'],2);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">

                            </a>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <?php
                                        $acad2=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE reg_active=1");
                                        $acad2->execute();
                                        $ac2=$acad2->fetch();
                                        $active=$ac2['count'];
                                        
        

                                        //current retakers
                                        $rtks=$conn->prepare("SELECT * FROM tbl_markby_module WHERE status=16");
                                        $rtks->execute();
                                        $rtks_cnt=0;
                                        while($rtk=$rtks->fetch()){
                                            $cur_rtks=$conn->prepare("SELECT * FROM tbl_markby_module WHERE reg_no='".$rtk['reg_no']."' AND module_id='".$rtk['module_id']."' AND status=1 AND enrolled=1");
                                            $cur_rtks->execute();
                                            if($cur_rtks->rowCount()>0){
                                                $rtks_cnt++;
                                            }
                                        }
    
                                        //current student count    
                                        $acad5=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE 1");
                                        $acad5->execute();
                                        $ac5=$acad5->fetch();
                                        $total=$ac5['count'];
                                        
                                         //Suspended Students
                                        $susp=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE reg_active=3 ");
                                        $susp->execute();
                                        $susp=$susp->fetch();
                                        $susp_total=$susp['count'];
                                         //Suspended Students
                                        $drp=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE reg_active=5 ");
                                        $drp->execute();
                                        $drp=$drp->fetch();
                                        $drp_total=$drp['count'];
                                         //Suspended Students
                                        $graduants=$conn->prepare("SELECT COALESCE(COUNT(DISTINCT(reg_no)),0) AS count FROM tbl_register_program_ug WHERE reg_active=17 ");
                                        $graduants->execute();
                                        $graduants=$graduants->fetch();
                                        $graduants_total=$graduants['count'];
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
                                            <div class="card-stats-item-count"><?php echo number_format($rtks_cnt); ?></div>
                                            <div class="card-stats-item-label">Retakers</div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?php echo number_format($rpt_cnt); ?></div>
                                            <div class="card-stats-item-label">Repeaters</div>
                                        </div>

                                    </div>
                                    <div class="card-stats-items" >
                                        <div class="card-stats-item" style="display: inline-block; margin-top: 5px;">
                                            <div class="card-stats-item-count"><?php echo number_format($susp_total); ?></div>
                                            <div class="card-stats-item-label">Suspended</div>
                                        </div>    
                                         <div class="card-stats-item" >
                                            <div class="card-stats-item-count"><?php echo number_format($drp_total); ?></div>
                                            <div class="card-stats-item-label">Drop Out</div>
                                        </div> 
                                         <div class="card-stats-item" >
                                            <div class="card-stats-item-count"><?php echo number_format($graduants_total); ?></div>
                                            <div class="card-stats-item-label">Graduands</div>
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
                                        $getProgs=$conn->prepare("SELECT prg_type_id,prg_type_full_name FROM tbl_program_type");
                                        $getProgs->execute();
                                        $progs=array();
                                        $balance=array();
                                        while($prg=$getProgs->fetch()){
                                            $blce = 0;
                                            $invoice=$conn->prepare("WITH unique_ug AS (
                                                                        SELECT DISTINCT reg_no
                                                                        FROM tbl_register_program_ug
                                                                        WHERE prg_type = '".$prg['prg_type_id']."'
                                                                    )
                                                                    SELECT COALESCE(SUM(inv.balance), 0) as balance
                                                                    FROM tbl_invoice inv
                                                                    INNER JOIN unique_ug ug ON inv.reg_no = ug.reg_no;
                                                                    ");
                                            $invoice->execute();
                                            $inv_balance=$invoice->fetch();
                                            
                                            $payment=$conn->prepare("SELECT COALESCE(SUM(p.amount), 0) as amount
                                                                                    FROM payment p
                                                                                    INNER JOIN (
                                                                                        SELECT DISTINCT reg_no
                                                                                        FROM tbl_register_program_ug
                                                                                        WHERE prg_type = '".$prg['prg_type_id']."'
                                                                                    ) ug ON p.reg_no = ug.reg_no
                                                                                    WHERE p.status = 1;
                                                                                    ");
                                            $payment->execute();
                                            $pyt=$payment->fetch();
                                            $blce+=$inv_balance['balance']-$pyt['amount'];

                                            array_push($progs,$prg['prg_type_full_name']);
                                            array_push($balance,$blce); 
                                        }
                                        $jsonNames = json_encode($progs);
                                        $jsonCount = json_encode($balance);
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
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <?php 
                                    $tpayments=$conn->prepare("SELECT COALESCE(SUM(amount),0) as pay FROM payment WHERE status=1");
                                    $tpayments->execute();
                                    $tpay=$tpayments->fetch();
                                    $tinvoices=$conn->prepare("SELECT COALESCE(SUM(balance),0) as inv FROM tbl_invoice");
                                    $tinvoices->execute();
                                    $tinv=$tinvoices->fetch();
                                    $bal=$tinv['inv']-$tpay['pay'];
                                ?>
                                <h5><?php echo number_format($bal); ?></h5>
                                <div class="card-description">Cumulative balance</div>
                            </div>
                            <div class="card-body" id="top-5-scroll">
                                <ul class="list-unstyled list-unstyled-border">
                                    <?php 
                                    $sql=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status!=1 ORDER BY acad_cycle_id DESC");
                                    $sql->execute();
                                    
                                    while($acad=$sql->fetch()){
                                            $payment=$conn->prepare("SELECT COALESCE(SUM(amount),0) as amount FROM payment WHERE acad_cycle_id='".$acad['acad_cycle_id']."'");
                                            $payment->execute();
                                            $pyt=$payment->fetch();
                                            
                                            $invoice=$conn->prepare("SELECT COALESCE(SUM(balance),0) as balance FROM tbl_invoice WHERE acad_cycle_id='".$acad['acad_cycle_id']."'");
                                            $invoice->execute();
                                            $inv_balance=$invoice->fetch();
                                            
                                            $cumbal=$inv_balance['balance']-$pyt['amount'];
                                    ?>
                                    <li class="media">
                                        <img class="mr-3 rounded" width="35" src="../img/cash-money.svg" alt="">
                                        <div class="media-body">
                                            <div class="media-title"><?php echo $acad['acad_year'] ?></div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-label"><?php echo number_format($cumbal,2); ?> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Invoice-payment analytics</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                        $getAcadYears=$conn->prepare("SELECT acad_cycle_id, acad_year
                                                                        FROM (
                                                                          SELECT *
                                                                          FROM tbl_acad_cycle
                                                                          ORDER BY acad_cycle_id DESC
                                                                          LIMIT 5
                                                                        ) AS last_5
                                                                        ORDER BY acad_cycle_id ASC;");
                                        $getAcadYears->execute();
                                        $acads=array();
                                        $invoices=array();
                                        $payments=array();
                                        $balances=array();
                                        while($acadYear=$getAcadYears->fetch()){
                                            $invce=0;
                                            $paymt=0;
                                            $blce=0;

                                            $invoice=$conn->prepare("SELECT COALESCE(SUM(balance),0) as balance FROM tbl_invoice WHERE acad_cycle_id='".$acadYear['acad_cycle_id']."'");
                                            $invoice->execute();
                                            $inv_balance=$invoice->fetch();
                                            
                                            $payment=$conn->prepare("SELECT COALESCE(SUM(amount),0) as amount FROM payment WHERE acad_cycle_id='".$acadYear['acad_cycle_id']."' AND status=1");
                                            $payment->execute();
                                            $pyt=$payment->fetch();
                                            
                                            $invce+=$inv_balance['balance'];
                                            $paymt+=$pyt['amount'];


                                            $blce=$invce-$paymt;
                                            array_push($acads,$acadYear['acad_year']);
                                            array_push($invoices,$invce);
                                            array_push($payments,$paymt);
                                            array_push($balances,$blce);
                                        }
                                        $jsonAcads = json_encode($acads);
                                        $jsonInvoices = json_encode($invoices);
                                        $jsonPayments = json_encode($payments);
                                        $jsonBalances = json_encode($balances);
                                    ?>
                                <div id="analysis-column"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Fee category analytics</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                    $getFees=$conn->prepare("SELECT id,name FROM tbl_fee_category");
                                    $getFees->execute();
                                    $fees=array();
                                    $fInvoices=array();
                                    $fPayments=array();
                                    $fBalances=array();
                                    while($fee=$getFees->fetch()){
                                        $fblce=0;
                                        $finvce=0;
                                        $fpaymt=0;
                                        $fblce=0;

                                        $invoice=$conn->prepare("SELECT COALESCE(SUM(balance),0) as balance FROM tbl_invoice WHERE fee_id='".$fee['id']."'");
                                        $invoice->execute();
                                        $inv_balance=$invoice->fetch();

                                        $payment=$conn->prepare("SELECT COALESCE(SUM(amount),0) as amount FROM payment WHERE fee_id='".$fee['id']."' AND status=1");
                                        $payment->execute();
                                        $pyt=$payment->fetch();
                                        $finvce+=$inv_balance['balance'];
                                        $fpaymt+=$pyt['amount'];

                                        $fblce=$finvce-$fpaymt;
                                        array_push($fees,$fee['name']);
                                        array_push($fInvoices,$finvce);
                                        array_push($fPayments,$fpaymt);
                                        array_push($fBalances,$fblce);
                                    }
                                    $jsonFees = json_encode($fees);
                                    $jsonFInvoices = json_encode($fInvoices);
                                    $jsonFPayments = json_encode($fPayments);
                                    $jsonFBalances = json_encode($fBalances);
                                    
                                    ?>
                                <div id="fee-categ-column"></div>
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
    var jsonNames = <?php echo $jsonNames; ?>;
    var jsonCount = <?php echo $jsonCount; ?>;
    var ctx = document.getElementById("myChart2").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: jsonNames,
        datasets: [{
          label: 'Balance',
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
              stepSize: 10000000
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
    

// analytics by acad year
    var jsonAcads = <?php echo $jsonAcads; ?>;
    var jsonInvoices = <?php echo $jsonInvoices; ?>;
    var jsonPayments = <?php echo $jsonPayments; ?>;
    var jsonBalances = <?php echo $jsonBalances; ?>;
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: ['#e8769f', '#5cb85c', '#5a5278'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'	
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
            name: 'Invoice',
            data: jsonInvoices
        }, {
            name: 'Payment',
            data: jsonPayments
        }, {
            name: 'Balance',
            data: jsonBalances
        }],
        xaxis: {
            categories: jsonAcads,
        },
        yaxis: {
            title: {
                text: 'amount'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    var fVal = val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    return fVal
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#analysis-column"),
        options
    );

    chart.render();
    

// analytics by acad year
    var jsonFees = <?php echo $jsonFees; ?>;
    var jsonFInvoices = <?php echo $jsonFInvoices; ?>;
    var jsonFPayments = <?php echo $jsonFPayments; ?>;
    var jsonFBalances = <?php echo $jsonFBalances; ?>;
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: ['#e8769f', '#5cb85c', '#5a5278'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'	
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
            name: 'Invoice',
            data: jsonFInvoices
        }, {
            name: 'Payment',
            data: jsonFPayments
        }, {
            name: 'Balance',
            data: jsonFBalances
        }],
        xaxis: {
            categories: jsonFees,
        },
        yaxis: {
            title: {
                text: 'amount'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    var fVal = val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    return fVal
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#fee-categ-column"),
        options
    );

    chart.render();
});
</script>