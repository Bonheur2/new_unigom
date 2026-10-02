<div class="main-content">
    <section class="section">
        <div class="row">
                <div class="col-12 col-md-12 col-lg-2"></div>
                <div class="form-group col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="search book by name" id="name_of_book" name="name_of_book">
                                <div class="input-group-append">
                                    <div class="input-group-text" id="spinner22">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4"></div>
                <div class="col-12 col-md-4" id="spinnerE"></div>
            </div>
        <!--<div class="row">-->
        <!--     <div class="form-group col-md-6">-->
        <!--         <input type="text" class="form-control" id="name_of_book" placeholder="type book name">-->
        <!--        </div>-->
        <!--        <div class="col-12 col-lg-8"><span id="spinner22"></span></div>-->
        <!-- </div>-->
<div class="card">
    <div class="card-header">
         <h4> <span id="name"></span>  Books Report </h4>
            </div>
                <div class="card-body">
                <table class="table table-striped" id="report_name">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Author</th>
                  <th scope="col">Book code</th> 
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
       <button type="button" class="btn btn-icon btn-success btn-sm float-right" id="export">&nbsp;<i class="fa-sharp fa-solid fa-file-excel"></i>&nbsp;Download Excel&nbsp;<i class="fa-sharp fa-light fa-down-to-bracket fa-beat"></i></button>
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
    
   $('#report_name').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });
    search();
}); 
function search(){
    document.getElementById('name_of_book').addEventListener('input', function(event) {
        var name_of_book = document.getElementById("name_of_book").value;
        document.getElementById("name").innerHTML = name_of_book;
        $('#spinner22').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
        var enteredValue = event.target.value;
        var formData={
            book_name:enteredValue,
            action:"search_book_by_name"
        };
         $.ajax({
                   dataType: "json",
                    url: "booksReport/byName.php",
                    type: 'POST',
                    data:formData,
                    success: function (data) {
            $('#spinner22').html("<i class='fa fa-search'></i>");
            if (data.length > 0) {
            var i = 1;
            var html = '';
            data.forEach(function(value) {
            var  title= value.title;
            var  author=value.author;
            var  book_code=value.book_code;
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
            html += '<td>' + book_code+ '</td>';
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
        $('#data').html('<tr><td colspan="10" align="center">oops, no data found</td></tr>');
                    }
        },error: function () {
                pop_wrong("Something went wrong!");
            }
                });
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
      var nameofbook= $("#name").text();
      var nameofBook1 = "Book Name " +  nameofbook;
      var formattedDate = year + '-' + month + '-' + day;
      var headers = ['', '', '', '', '','','','','',''];
      var table = document.getElementById('report_name');

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
      var filename = 'Report ' + formattedDate+ ' '+ nameofBook1 + '.xlsx';
      XLSX.writeFile(workbook, filename);
    }
  });
</script>
<!--scripts end-->