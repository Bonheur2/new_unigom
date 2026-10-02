        <!-- Start app main Content -->
        <div class="main-content" id="books">
            <section class="section">
                <div class="section-header">
                    <h1><i class="fa fa-file-o" aria-hidden="true"></i>Reports</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Report</a></div>
                        <div class="breadcrumb-item">Book Report</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            
                            
                            
                            <div class="row">
    <div class="col-12 col-sm-12 col-md-4">
        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">Delayed Books</a></li>
            <li class="nav-item"><a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4" role="tab" aria-controls="contact" aria-selected="false">Losted Book</a></li>
            <li class="nav-item"><a class="nav-link" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="false">Statistics</a></li>
        </ul>
    </div>
    <div class="col-12 col-sm-12 col-md-8">
        <div class="tab-content no-padding" id="myTab2Content">
            <div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
               <form id="Returned_books" action="Returned_books" method="POST">
                                            <input type="hidden" name="action" value="retun">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>from</label>
                                                     <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="from" id="from"  placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Up to</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-calendar"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="to" id="to" placeholder="" required>
                                                    </div>
                                                </div>
                                                
                                            <div class="form-group col-12 col-sm-1 col-lg-1">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                     
                                                    <button type="submit" class="btn btn-primary"> <span id="spinner">
                                                    <i class="fas fa-search"></i>
                                                </span>search</button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
            </div>
            <div class="tab-pane fade" id="contact4" role="tabpanel" aria-labelledby="contact-tab4">
                <form id="Deleyed_books" action="Deleyed_books" method="POST">
                                            <input type="hidden" name="action" value="deryd">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>from</label>
                                                     <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-calendar"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="from" id="from"  placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Up to</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-calendar"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="to" id="to" placeholder="" required>
                                                    </div>
                                                </div>
                                                
                                            <div class="form-group col-12 col-sm-1 col-lg-1">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                     
                                                    <button type="submit" class="btn btn-primary"> <span id="spinner">
                                                    <i class="fas fa-search"></i>
                                                </span>search</button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
            </div>
            <div class="tab-pane fade" id="new" role="tabpanel" aria-labelledby="new-tab">
               <div class="row" id="statistics">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Copies</h4>
                                </div>
                                <div class="card-body">
                                     <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as total_copies FROM book_copies");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['total_copies']; }}?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Losted</h4>
                                </div>
                                <div class="card-body">
                                    <?php 
                                             $stmt = $conn->prepare("SELECT COUNT(*) as lost_copies FROM book_copies WHERE status='losted'");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                    <span ><?php echo $row['lost_copies']; }}?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-file"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Borrowed</h4>
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
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Available</h4>
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
                        </div>
                    </div>  
                </div> 
                
                <div class="">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Circulation Statistics</h4>
                                </div>
                                <div class="card-body">
                                    <div id="apex-basic"></div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>

            <div class="col-12 col-sm-12 col-lg-12">
                            
                            <div class="card" id="sample-login" hidden>
                                    <div class="card-header">
                                        <h4 id="report_name"></h4>
                                       <div class="card-header-action">
                                        <button type="button" class="btn btn-icon btn-success btn-sm " id="export">&nbsp;<i class="fa-sharp fa-solid fa-file-excel"></i>&nbsp;Download xlsx&nbsp;<i class="fa-sharp fa-light fa-down-to-bracket fa-beat"></i></button>
                                       <button type="button" onclick="generatePDF()" class="btn btn-secondary btn-sm">&nbsp;<i class="fa fa-file-pdf" aria-hidden="true"></i>&nbsp;Download Pdf&nbsp;</button>

                                    </div> 
            
                                    </div>
                                    <div class="card-body">
                                </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="book_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Names/RegNumber</th>
                                                    <th scope="col">Telephone</th>
                                                    <th scope="col">Book Tilte/Book Code</th>
                                                    <th scope="col">Issue date</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Fine</th>
                                                </tr>
                                                </thead>
                                                <tbody id="data">

</tbody>
</table>
</div>
</div>
</div>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>



          
          
          <script>
$(document).ready(function(){
   $("#new-tab").click(function() {
    $.ajax({
    url: "report/chart.php",
    type: "GET",
    dataType: "json",
    success: function(data) {
        var seriesData = [];
        var categories = [];

        data.forEach(function(row) {
            var bookTitle = row.title;
            var status = row.status;
            var count = parseInt(row.count);

            if (seriesData[status]) {
                seriesData[status].push(count);
            } else {
                seriesData[status] = [count];
            }

            if (!categories.includes(bookTitle)) {
                categories.push(bookTitle);
            }
        });

        var series = Object.keys(seriesData).map(function(status) {
            return {
                name: status,
                data: seriesData[status]
            };
        });

        var options = {
            chart: {
                type: 'bar'
            },
            series: series,
            xaxis: {
                categories: categories
            }
        };

        var chart = new ApexCharts(
            document.querySelector("#apex-basic"),
            options
        );

        chart.render();
    },
    error: function() {
        console.log("Error: Failed to fetch data");
    }
});

});
 
    
    
    
        
    $("#export").click(function () {
            exportTableToExcel();
            $('#spinner4d').html('<i class="fa-regular fa-check-double"></i>');
        });
   $("#myTab4").click(function () {
        $("#sample-login").attr("hidden",true);    
        });
     //browwed books report
      $('#Borrowed_books').submit(function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
      type: 'POST',
      url: 'report/book_report_controller.php',
      data: formData,
      contentType: false,
      processData: false,
      dataType:"JSON",
      success: function(data) {
          $('#spinner').html("<i class='fas fa-search'></i>")
         if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
            var fname = value.fname;
            var reg_no = value.reg_no; 
            var phone = value.phone; 
            var title = value.title;
            var book_code_number = value.book_code_number; 
            var issue_date = value.issue_date;
            var date_return = value.date_return; 
            var borrow_status = value.borrow_status;
            $("#sample-login").attr("hidden",false);
            html += '<tr>';
            html += '<td>' + i++ + '</td>'; 
            html += '<td>' + fname + '[' + reg_no + ']</td>';
            html += '<td>' + phone + '</td>'; 
            html += '<td>' + title + '[' + book_code_number + ']</td>'; 
            html += '<td>' + issue_date + '</td>';
            html += '<td>' + date_return + '</td>'
            html += '<td>'+borrow_status+' </td>';
            html += '</tr>'; 
            });
            $("#report_name").html('Borowed books');
            $('#data').html(html);            
         }
         else{
        $("#sample-login").attr("hidden",false);
        $('#data').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                    }
      },
      error: function() {
         $('#spinner').html("<i class='fas fa-search'></i>")
        pop_wrong("Something went wrong!");
      }
    });
  });
  
  
       //returned books report
      $('#Returned_books').submit(function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
      type: 'POST',
      url: 'report/book_report_controller.php',
      data: formData,
      contentType: false,
      processData: false,
      dataType:"JSON",
      success: function(data) {
          $('#spinner').html("<i class='fas fa-search'></i>")
         if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
            var fname = value.fname;
            var reg_no = value.reg_no; 
            var phone = value.phone; 
            var title = value.title;
            var book_code_number = value.book_code_number; 
            var issue_date = value.issue_date;
            var date_return = value.date_return; 
            var borrow_status = value.borrow_status;
            var fine = value.fine;
            $("#sample-login").attr("hidden",false);
            html += '<tr>';
            html += '<td>' + i++ + '</td>'; 
            html += '<td>' + fname + '[' + reg_no + ']</td>';
            html += '<td>' + phone + '</td>'; 
            html += '<td>' + title + '[' + book_code_number + ']</td>'; 
            html += '<td>' + issue_date + '</td>';
            html += '<td>'+borrow_status+' </td>';
            html += '<td>'+fine+' </td>';
            html += '</tr>'; 
            });
            $("#report_name").html('Returned books');
            $('#data').html(html);            
         }
         else{
        $("#sample-login").attr("hidden",false);
        $('#data').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                    }
      },
      error: function() {
         $('#spinner').html("<i class='fas fa-search'></i>")
        pop_wrong("Something went wrong!");
      }
    });
  });
       
       //Deleyed_books report
      $('#Deleyed_books').submit(function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
      type: 'POST',
      url: 'report/book_report_controller.php',
      data: formData,
      contentType: false,
      processData: false,
      dataType:"JSON",
      success: function(data) {
          $('#spinner').html("<i class='fas fa-search'></i>")
         if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
            var fname = value.fname;
            var reg_no = value.reg_no; 
            var phone = value.phone; 
            var title = value.title;
            var book_code_number = value.book_code_number; 
            var issue_date = value.issue_date;
            var date_return = value.date_return; 
            var borrow_status = value.borrow_status;
            var fine = value.fine;
            $("#sample-login").attr("hidden",false);
            html += '<tr>';
            html += '<td>' + i++ + '</td>'; 
            html += '<td>' + fname + '[' + reg_no + ']</td>';
            html += '<td>' + phone + '</td>'; 
            html += '<td>' + title + '[' + book_code_number + ']</td>'; 
            html += '<td>' + issue_date + '</td>';
            html += '<td>'+borrow_status+' </td>';
            html += '<td>' + fine + '</td>'
            html += '</tr>'; 
            });
            $("#report_name").html('Deleyed books');
            $('#data').html(html);            
         }
         else{
        $("#sample-login").attr("hidden",false);
        $('#data').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                    }
      },
      error: function() {
         $('#spinner').html("<i class='fas fa-search'></i>")
        pop_wrong("Something went wrong!");
      }
    });
  });
              
       
          
          
 
    });
    
 function exportTableToExcel() {

    var table = document.getElementById('book_table');
    var rows = table.rows;
    var data = [];
    for (var i = 0; i < rows.length; i++) {
        var rowData = rows[i].cells;
        var rowDataArray = [];
        for (var j = 0; j < rowData.length; j++) {
            rowDataArray.push(rowData[j].innerHTML);
        }
        data.push(rowDataArray);
    }

    // Convert the data to an Excel workbook
    var workbook = XLSX.utils.book_new();
    var sheetName = 'Sheet1';
    var worksheet = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
    
    
    

    
    var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    var blob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    var from = document.getElementById('from');
    var to = document.getElementById('to');
    var fileName = 'books report from' + from.value + ' to ' + to.value + '.xlsx';
    if (navigator.msSaveBlob) {
        navigator.msSaveBlob(blob, fileName);
    } else {
        var link = document.createElement('a');
        if (link.download !== undefined) {
            var url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', fileName);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

 function generatePDF() {
     var qrcodesElement = document.getElementById('book_table');
    
    html2canvas(qrcodesElement)
      .then(function(canvas) {
        var imageData = canvas.toDataURL('image/png');
        var doc = new jsPDF('p', 'mm', 'a4');
        var imageHeight = canvas.height * 210 / canvas.width; 
        doc.addImage(imageData, 'PNG', 10, 10, 190, imageHeight);
        // var name = document.getElementById('titlebk');
        doc.save('Report.pdf');
      });
  }

    
    
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
</script>
          
          
        </div>