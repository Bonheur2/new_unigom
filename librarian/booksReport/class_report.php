    <div class="main-content">
        <section class="section">
             <div class="row">
                    <div class="col-12 col-md-2 col-lg-2"></div>
                        <div class="form-group col-12 col-md-9 col-lg-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="input-group col-12 col-md-9 col-lg-9"> 
                                <select name="book_class" id="book_class"  class="form-control select2" style="width:100%">
                                    <option value='' disabled selected>--Choose one--</option>
                                        <?php
                                         $sql_class=$conn->prepare("SELECT DISTINCT (book_class) FROM  books");
                                         $sql_class->execute();
                                          $i=1;
                                          while($class=$sql_class->fetch()){
                                           ?>
                                        <option value="<?php echo $class['book_class']; ?>"><?php echo $class['book_class']; ?> </option>
                                         <?php } ?>
                                     </select>   
                                     </div>
                                    <div class="col-lg-2 input-group-append">
                                        <div class="input-group-text" id="spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>

     
<div class="card">
    <div class="card-header">
         <h4>Books Within Class : <span id="class_name"></span> </h4>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table" id="report_location">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Author</th>
                  <th scope="col">ISBN</th> 
                  <th scope="col">Language</th>
                  <th scope="col">Publisher</th>
                  <th scope="col">Price</th> 
                  <th scope="col">Barcode</th>  
                  <th scope="col">Copy(s)</th> 
                  <th scope="col">Class</th>  
                  </thead>
                  <tbody id="data">
                      
               </tbody>
        </table>
       <!--<button type="button" class="btn btn-icon btn-success btn-sm float-right" id="export">&nbsp;<i class="fa-sharp fa-solid fa-file-excel"></i>&nbsp;Download Excel&nbsp;<i class="fa-sharp fa-light fa-down-to-bracket fa-beat"></i></button>-->
      </div>
    </div>
  </div>
</section>
</div>


<!--scripts start-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){
    
$('#book_class').change(function () {
    var select = document.getElementById("book_class");
     var selectedOption = select.options[select.selectedIndex];
	 $("#class_name").html(selectedOption.innerHTML);
	 $('#spinner').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
var getData = {
            book_class: $('#book_class').val(),
            action: 'loadbook_by_location'
        };
      $.ajax({
        url: "booksReport/byclass.php",
        method: 'POST',
        data:getData,
        success: function (data) {
             $('#spinner').html("<i class='fa fa-search'>");
           $("#data").html(data);
     
        },error: function () {
                pop_wrong("Something went wrong!");
            }
        
      });
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
</script>
<script>
  $(document).ready(function () {
    //export into Excel
    $("#export").click(function () {
      exportTableToExcel();
    });

    function exportTableToExcel() {
      var currentDate = new Date();

      var year = currentDate.getFullYear();
      var month = currentDate.getMonth() + 1;
      var day = currentDate.getDate();
      var dep = $("#dep_name").text();
      var nameofdep = "Department Of " + dep;
      var formattedDate = year + '-' + month + '-' + day;
      var headers = ['', '', '', '', '','','','','',''];
      var table = document.getElementById('report_department');

      // Create a new workbook and add a worksheet
      var workbook = XLSX.utils.book_new();
      var worksheet = XLSX.utils.table_to_sheet(table);

      // Add headers to the worksheet
      XLSX.utils.sheet_add_aoa(worksheet, [headers], { origin: 'A1' });

      // Add data to the worksheet
      var rows = table.rows;
      for (var i = 0; i < rows.length; i++) {
        var rowData = [];
        for (var j = 0; j < headers.length; j++) {
          var cell = rows[i].cells[j];
          rowData.push(cell.innerText);
        }
        XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: 'A' + (i + 2) });
      }

      // Add the worksheet to the workbook
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');

      // Save the workbook as an Excel file
      var filename = 'Report ' + formattedDate+ ' '+ nameofdep + '.xlsx';
      XLSX.writeFile(workbook, filename);
    }
  });
</script>
<!--scripts end-->