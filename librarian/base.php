  <html>
	<head>
		
		<style>

/*-- Chart --*/
.c3 svg {
  font: 10px sans-serif;
  -webkit-tap-highlight-color: transparent;
  font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
}

.c3 path,
.c3 line {
  fill: none;
  stroke: rgba(0, 40, 100, 0.12);
}

.c3 text {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  font-size: px2rem(12px);
}

.c3-legend-item-tile,
.c3-xgrid-focus,
.c3-ygrid,
.c3-event-rect,
.c3-bars path {
  shape-rendering: crispEdges;
}

.c3-chart-arc path {
  stroke: #fff;
}

.c3-chart-arc text {
  fill: #fff;
  font-size: 13px;
}

/*-- Axis --*/
/*-- Grid --*/
.c3-grid line {
  stroke: #f0f0f0;
}

.c3-grid text {
  fill: #aaa;
}

.c3-xgrid,
.c3-ygrid {
  stroke: #e6e6e6;
  stroke-dasharray: 2 4;
}

/*-- Text on Chart --*/
.c3-text {
  font-size: 12px;
}

.c3-text.c3-empty {
  fill: #808080;
  font-size: 2em;
}

/*-- Line --*/
.c3-line {
  stroke-width: 2px;
}

/*-- Point --*/
.c3-circle._expanded_ {
  stroke-width: 2px;
  stroke: white;
}

.c3-selected-circle {
  fill: white;
  stroke-width: 1.5px;
}

/*-- Bar --*/
.c3-bar {
  stroke-width: 0;
}

.c3-bar._expanded_ {
  fill-opacity: 1;
  fill-opacity: 0.75;
}

/*-- Focus --*/
.c3-target.c3-focused {
  opacity: 1;
}

.c3-target.c3-focused path.c3-line, .c3-target.c3-focused path.c3-step {
  stroke-width: 2px;
}

.c3-target.c3-defocused {
  opacity: 0.3 !important;
}

/*-- Region --*/
.c3-region {
  fill: steelblue;
  fill-opacity: .1;
}

/*-- Brush --*/
.c3-brush .extent {
  fill-opacity: .1;
}

/*-- Select - Drag --*/
/*-- Legend --*/
.c3-legend-item text {
  fill: #777777;
  font-size: 14px;
}

.c3-legend-item-hidden {
  opacity: 0.15;
}

.c3-legend-background {
  fill: transparent;
  stroke: lightgray;
  stroke-width: 0;
}

/*-- Title --*/
.c3-title {
  font: 14px sans-serif;
}

/*-- Tooltip --*/
.c3-tooltip-container {
  z-index: 10;
}

.c3-tooltip {
  border-collapse: collapse;
  border-spacing: 0;
  empty-cells: show;
  font-size: 11px;
  line-height: 1;
  font-weight: 700;
  color: #fff;
  border-radius: 3px;
  background: #212529;
  white-space: nowrap;
}

.c3-tooltip th {
  padding: 6px 6px;
  text-align: left;
}

.c3-tooltip td {
  padding: 4px 6px;
  font-weight: 400;
}

.c3-tooltip td > span {
  display: inline-block;
  width: 8px;
  height: 8px;
  margin-right: 8px;
  border-radius: 50%;
  vertical-align: baseline;
}

.c3-tooltip td.value {
  text-align: right;
}

/*-- Area --*/
.c3-area {
  stroke-width: 0;
  opacity: 0.1;
}

.c3-target-filled .c3-area {
  opacity: 1 !important;
}

/*-- Arc --*/
.c3-chart-arcs-title {
  dominant-baseline: middle;
  font-size: 1.3em;
}

.c3-chart-arcs .c3-chart-arcs-background {
  fill: #e0e0e0;
  stroke: none;
}

.c3-chart-arcs .c3-chart-arcs-gauge-unit {
  fill: #000;
  font-size: 16px;
}

.c3-chart-arcs .c3-chart-arcs-gauge-max {
  fill: #777;
}

.c3-chart-arcs .c3-chart-arcs-gauge-min {
  fill: #777;
}

.c3-chart-arc .c3-gauge-value {
  fill: #000;
  /*  font-size: 28px !important;*/
}

.c3-chart-arc.c3-target g path {
  opacity: 1;
}

.c3-chart-arc.c3-target.c3-focused g path {
  opacity: 1;
}

.c3-axis {
  fill: #9aa0ac;
}

		</style>
	</head>
	<body>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>LIBRARIAN</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Activities</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row" id="statistics">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total books</h4>
                                </div>
                                <div class="card-body">
                                     <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as total_books FROM books");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['total_books']; }}?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <a href="edu?mis=copies"><div class="card card-statistic-1">
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Available copies</h4>
                                </div>
                                <div class="card-body">
                                      <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as available_copies FROM book_copies WHERE status='Available'");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['available_copies']; }}?></span>
                                </div>
                            </div>
                        </div></a> 
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                       <a href="edu?mis=rptbooklost">
                           <div class="card card-statistic-1">
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Lost copies</h4>
                                </div>
                                <div class="card-body">
                                    <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as lost_copies FROM book_copies WHERE status='lost'");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['lost_copies']; }}?></span>
                                </div>
                            </div>
                        </div>
                      </a> 
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                       <a href="edu?mis=brwdbook">
                           <div class="card card-statistic-1">
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Borrowed copies</h4>
                                </div>
                                <div class="card-body">
                                      <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as borrowed_copies FROM book_copies WHERE status='Borrowd' OR status='Staff'");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['borrowed_copies']; }}?></span>
                                </div>
                            </div>
                        </div>
                        </a> 
                    </div>
                      
                </div> 
            </div>
                    </div>
                    <div>
                       <?php
                        $departments = array();
                        $counts=array();
                        $colors = array();
                        $departs=array();
                        $colorDepart=array();
                        $countBooks=array();
                        $st = $conn->prepare("SELECT DISTINCT c.dept_id,c.dept_full_name FROM tbl_register_program_ug b 
                                              INNER JOIN tbl_department c ON b.dept_id=c.dept_id 
                                              INNER JOIN borrowdetails br ON b.reg_no=br.borrow_id ");
                        $st->execute();
                        while ($data_dep = $st->fetch()) {
                            $dept=$data_dep['dept_id'];
                            $st2=$conn->prepare("SELECT COUNT(borrow_details_id) AS ct FROM borrowdetails b 
                                                    INNER JOIN tbl_register_program_ug prug ON b.borrow_id=prug.reg_no 
                                                    INNER JOIN tbl_department dp ON dp.dept_id=prug.dept_id
                                                    WHERE prug.reg_active=1 AND dp.dept_id='".$dept."'");
                            $st2->execute();
                            $data_st2=$st2->fetch();
                            $count=$data_st2['ct'];
                            array_push($counts,$count);
                            array_push($departments, $data_dep['dept_full_name']);
                        }
                        
                        for ($i = 0; $i < count($departments); $i++) {
                            $colors[] = '#' . substr(md5(mt_rand()), 0, 6);
                        }
                        $jsonColors = json_encode($colors);
                        $jsonCounts= json_encode($counts);
                        $jsonDepartment = json_encode($departments);
                        
                        $st3=$conn->prepare("SELECT DISTINCT c.book_id,c.book_dep_name FROM tbl_books_depart c 
                                              INNER JOIN books b ON c.book_id=b.department ");
                         $st3->execute();
                         while($data_st3=$st3->fetch()){
                             $dp=$data_st3['book_id'];
                             $st4=$conn->prepare("SELECT COUNT(book_id) AS cts FROM books WHERE department='".$dp."' ");
                             $st4->execute();
                             $data_st4=$st4->fetch();
                             array_push($countBooks,$data_st4['cts']);
                             array_push($departs,$data_st3['book_dep_name']);
                         }
                         for ($i = 0; $i < count($departs); $i++) {
                            $colorDepart[] = '#' . substr(md5(mt_rand()), 0, 6);
                        }
                        $jsonColorsDepart = json_encode($colorDepart);
                        $jsonCountBooks=json_encode($countBooks);
                         $deprt=json_encode($departs);
                        ?>
                        
                    </div>
                    <div class="row row-cards">
                      <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Books circulated In Departments</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="myChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    
                    <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Books Registered In Departments </h4>
                                </div>
                                 <div class="card-body">
                                    <canvas id="myChart3"></canvas>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="row row-deck">
                     <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Location Books Counts </h4>
                                </div>
                                 <div class="card-body">
                                      <?php
                            $st9=$conn->prepare("SELECT * FROM books_location");
                            $st9->execute();
                            while($data_st9=$st9->fetch()){
                                $id=$data_st9['id'];
                                $st10=$conn->prepare("SELECT COUNT(book_id) AS tbooks FROM books WHERE location='".$id."' ");
                                $st10->execute();
                                $data_st10=$st10->fetch();
                               
                                $st11=$conn->prepare("SELECT COUNT(id) AS copynumbers FROM  book_copies WHERE block='".$id."'");
                                $st11->execute();
                                $data_st11=$st11->fetch();
                                ?>
                            <div class="card card-hero">
                                    <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <p><?php echo $data_st10['tbooks'] ?>  Book(s)  &&  <?php echo $data_st11['copynumbers'] ?> Copy(s)</p>
                                    <div class="card-description" style="color:black"><?php echo $data_st9['location_name']  ?>
                                    </div>
                                    </div>
                                </div>
                            <?php }
                            ?>
                                
                                </div>
                            </div>
                        </div>
                    <div class="col-lg-6">
                        <div class="card gradient-bottom">
                            <div class="card-header">
                            <h4>Department Book with Copies</h4>
                                 <select name="department" id="department"  class="form-control select2" style="width:100%">
                                  <?php
                                     $sql_dep=$conn->prepare("SELECT * FROM tbl_books_depart WHERE status=1 ORDER BY book_dep_name ASC");
                                     $sql_dep->execute();
                                      $i=1;
                                      while($dep=$sql_dep->fetch()){
                                       ?>
                                    <option value="<?php echo $dep['book_id']; ?>"><?php echo $dep['book_dep_name']; ?> </option>
                                     <?php } ?>
                                 </select>
                               
                            </div>
                            <div class="card-body" id="top-5-scroll">
                            <ul class="list-unstyled list-unstyled-border">
                                <?php
                                $i=0;
                                $st5=$conn->prepare("SELECT * FROM books WHERE department=17 ");
                                $st5->execute();
                                while($data_st5=$st5->fetch()){
                                    $i++;
                                    $book_id=$data_st5['book_id'];
                                    $st6=$conn->prepare("SELECT COUNT(id) AS copies FROM  book_copies WHERE book_id='".$book_id."'");
                                    $st6->execute();
                                    $data_st6=$st6->fetch();
                                    
                                    $st7=$conn->prepare("SELECT COUNT(br.borrow_details_id) AS borrcopies FROM  borrowdetails br
                                    INNER JOIN book_copies bc ON  br.book_id=bc.id INNER JOIN books bk ON bk.book_id=bc.book_id WHERE bc.book_id='".$book_id."' 
                                    AND br.borrow_status='pending' ");
                                    $st7->execute();
                                    $data_st7=$st7->fetch();
                                    
                                     $st8=$conn->prepare("SELECT COUNT(br.borrow_details_id) AS lostcopies FROM  borrowdetails br
                                    INNER JOIN book_copies bc ON  br.book_id=bc.id INNER JOIN books bk ON bk.book_id=bc.book_id WHERE bc.book_id='".$book_id."' 
                                    AND br.borrow_status='lost' ");
                                    $st8->execute();
                                    $data_st8=$st8->fetch();
                                    ?>
                                 <li class="media">
                                <div class="media-body">
                                    <div class="float-right"><div class="font-weight-600 text-muted text-small"><?php echo $data_st6['copies'] ?> Copy(s)</div></div>
                                    <div class="media-title"><span class="badge badge-primary"><?php echo $i ?></span> &nbsp;<?php echo $data_st5['title']; ?> </div>
                                    <div class="mt-1">
                                    <div class="budget-price">
                                        <div class="budget-price-square bg-primary" data-width="43%"></div>
                                        <div class="budget-price-label"><?php echo $data_st7['borrcopies']; ?> Borrowed</div>
                                    </div>
                                    <div class="budget-price">
                                        <div class="budget-price-square bg-danger" data-width="43%"></div>
                                        <div class="budget-price-label"><?php echo $data_st8['lostcopies']; ?> Losted</div>
                                    </div>
                                    </div>
                                </div>
                                </li>
                                <?php }
                                ?>
                               
                               
                                
                                
                            </ul>
                            </div>
                            <div class="card-footer pt-3 d-flex justify-content-center">
                            <div class="budget-price justify-content-center">
                                <div class="budget-price-square bg-primary" data-width="20"></div>
                                <div class="budget-price-label">Borrowed</div>
                            </div>
                            <div class="budget-price justify-content-center">
                                <div class="budget-price-square bg-danger" data-width="20"></div>
                                <div class="budget-price-label">Losted</div>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                    
                   
                    
                    
                  </div>
                </div>
            </section>
        </div>
                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>
<script src="../assets/modules/chart.min.js"></script>



 <script>
$(document).ready(function(){

    // department vs books borrowed
    var jsonDepartment = <?php echo $jsonDepartment ?>;
    var jsonCounts=<?php echo $jsonCounts ?>;
    var jsonColors = <?php echo $jsonColors ?>;
   
    
    var ctx = document.getElementById("myChart4").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: jsonCounts,
                backgroundColor: jsonColors,
                label: 'Dataset 1'
            }],
            labels: jsonDepartment,
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
        }
    });
    
var jsonDeptNames = <?php echo $deprt ?>;
var jsoncolorsDepat=<?php echo $jsonColorsDepart ?>;
var jsonCountBooks=<?php echo $jsonCountBooks ?>;
var ctx = document.getElementById("myChart3").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    datasets: [{
      data:jsonCountBooks ,
      backgroundColor: jsoncolorsDepat,
      label: 'Dataset 1'
    }],
    labels:jsonDeptNames ,
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
    },
  }
});

$('#department').change(function () {
    $("#top-5-scroll").empty();
   var getData = {
            dep: $('#department').val(),
            action: 'load_data'
        };
      $.ajax({
        url: "load_controller.php",
        method: 'POST',
        data:getData,
        success: function (data) {
          $("#top-5-scroll").html(data);   
        },error: function () {
                pop_wrong("Something went wrong!");
            }
        
      });
});

 function pop_wrong(feedback) {
            iziToast.warning({
                title: 'Error',
                message: feedback,
                position: 'topCenter'
            });
        }

        function pop_up_success(feedback) {
            iziToast.success({
                title: 'info',
                message: feedback,
                position: 'topCenter'
            });
        }
});

</script>
        </div>
            