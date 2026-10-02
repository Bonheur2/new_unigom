<div class="main-content">
    <section class="section">
         <div class="row">
                                        <div class="col-12 col-md-2 col-lg-2"></div>
                                        <div class="form-group col-12 col-md-9 col-lg-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                    <div class="input-group col-12 col-md-9 col-lg-9"> 
                                <select name="department" id="department"  class="form-control select2" style="width:100%">
                    <option value='' disabled selected>--Choose one--</option>
                        <?php
                         $sql_dep=$conn->prepare("SELECT * FROM tbl_books_depart WHERE status=1 ORDER BY book_dep_name ASC");
                         $sql_dep->execute();
                          $i=1;
                          while($dep=$sql_dep->fetch()){
                           ?>
                        <option value="<?php echo $dep['book_id']; ?>"><?php echo $dep['book_dep_name']; ?> </option>
                         <?php } ?>
                     </select>
                                                <!--      <i  id="spinner7">-->
                                                    
                                                <!--</i>-->
                                                
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
         <h4>Books Report In Department Of: <span id="dep_name"></span></h4>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-striped" id="report_department">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Author</th>
                  <th scope="col">ISBN</th> 
                  <th scope="col">Language</th>
                  <th scope="col">Publisher</th>
                  <th scope="col">Section</th> 
                  <th scope="col">Price</th>  
                  </thead>
                  <tbody id="data">
                      
               </tbody>
        </table>
        </div>
       <!--<button type="button" class="btn btn-icon btn-success btn-sm float-right" id="export">&nbsp;<i class="fa-sharp fa-solid fa-file-excel"></i>&nbsp;Download Excel&nbsp;<i class="fa-sharp fa-light fa-down-to-bracket fa-beat"></i></button>-->
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
    
$('#department').change(function () {
    var select = document.getElementById("department");
     var selectedOption = select.options[select.selectedIndex];
	 $("#dep_name").html(selectedOption.innerHTML);
	 $('#spinner').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
var getData = {
            dep: $('#department').val(),
            action: 'loadbook_by_departmet'
        };
      $.ajax({
        dataType: "json",
        url: "booksReport/bydepartment.php",
        method: 'POST',
        data:getData,
        success: function (data) {
             $('#spinner').html("<i class='fa fa-search'>");
            if (data.length > 0 ) {
            var i = 1;
            var html = '';
            data.forEach(function(value) {
            var  title= value.title;
            var  author=value.author;
            var isbn=value.isbn;
            var language=value.language_publication;
            var publisher=value.publisher;
            var section=value.section;
            var price=value.price;
            var location=value.location_name;
            
            html += '<tr>';
            html += '<td>' + i++  + '</td>'; 
            html += '<td>' + title+ '</td>';
            html += '<td>' + author + '</td>'; 
            html += '<td>' + isbn + '</td>'; 
            html += '<td>' + language + '</td>';
            html += '<td>' + publisher + '</td>'; 
            html += '<td>' + section+ '</td>';
            html += '<td>' + price + '</td>'; 
            html += '</tr>'; 
            });
            $('#data').html(html);            
         }
         else{
        $('#data').html('<tr><td colspan="8" style="text-align:center">oops, no data found</td></tr>');
                    }
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