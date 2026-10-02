<div class="main-content">
    <section class="section">
        <div class="row">
                                        <div class="col-12 col-md-2 col-lg-2"></div>
                                        <div class="form-group col-12 col-md-9 col-lg-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                    <div class="input-group col-12 col-md-9 col-lg-9"> 
                              <select name="author" id="author"  class="form-control select2" style="width:100%">
                        <option value='' disabled selected>--Select Author name--</option>
                        <?php
                         $sql_auth=$conn->prepare("SELECT DISTINCT author FROM books");
                         $sql_auth->execute();
                          $i=1;
                          while($auth=$sql_auth->fetch()){
                           ?>
                        <option value="<?php echo $auth['author']; ?>"><?php echo $auth['author']; ?> </option>
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
         <h4>Books Report By Author: <span id="author_name"></span></h4>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-striped" id="report_author">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Program</th>
                  <th scope="col">ISBN</th> 
                  <th scope="col">Language</th>
                  <th scope="col">Publisher</th>
                  <th scope="col">Section</th> 
                  <th scope="col">Price</th>  
                  <th scope="col">Location</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){
  
$('#author').change(function () {
    var select = document.getElementById("author");
     var selectedOption = select.options[select.selectedIndex];
	 $("#author_name").html(selectedOption.innerHTML);
	 $('#spinner').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
var getData = {
            author: $('#author').val(),
            action: 'loadbook_by_author'
        };
      $.ajax({
        dataType:"json",
        url: "booksReport/byAuth.php",
        method: 'POST',
        data:getData,
        success: function (data) {
            $('#spinner').html("<i class='fa fa-search'></i>");
            if (data.length > 0) {
            var i = 1;
            var html = '';
            data.forEach(function(value) {
            var book_id=value.book_id;
            var  title= value.title;
            var department=value.book_dep_name;
            var isbn=value.isbn;
            var language=value.language_publication;
            var publisher=value.publisher;
            var section=value.section;
            var price=value.price;
            var location=value.location_name;
            
            html += '<tr>';
            html += '<td>' + i++  + '</td>'; 
            html += '<td>' + title+ '</td>';
            html +='<td>' + department + '</td>';
            html += '<td>' + isbn + '</td>'; 
            html += '<td>' + language + '</td>';
            html += '<td>' + publisher + '</td>'; 
            html += '<td>' + section+ '</td>';
            html += '<td>' + price + '</td>'; 
            html += '<td>' + location+ '</td>';
            html += '</tr>'; 
            });
            $('#data').html(html);            
         }
         else{
        $('#data').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
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
      var author = $("#author_name").text();
      var nameofAuth = "Author Name : " + author;
      var formattedDate = year + '-' + month + '-' + day;
      var headers = ['', '', '', '','','','','',''];
      var table = document.getElementById('report_author');

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
      var filename = 'Report ' + formattedDate+ ' '+ nameofAuth + '.xlsx';
      XLSX.writeFile(workbook, filename);
    }
  });
</script>
<!--scripts end-->