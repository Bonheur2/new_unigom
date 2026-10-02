<div class="main-content">
    <section class="section">
     
     <div class="card">
    <div class="card-header">
        
        <div class="row col-12">
            <div class="col-lg-9 col-sm-9">
          <h4>Delayed Books Report</h4>      
            </div>
            <div class="col-lg-3 col-sm-3">
                  <button class="btn btn-primary" type="button" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excell</button> 
            </div>
        </div>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-striped" id="report_delayed">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Book code</th> 
                  <th scope="col">Reg No</th> 
                  <th scope="col">Students</th> 
                  <th scope="col">Department</th>
                  <th scope="col">Issue Date</th>
                  <th scope="col">return date</th>
                  <th scope="col">Addition Days</th>
                  <th scope="col">Fine</th>
                  </thead>
                  <tbody id="data">
                 </tbody>
            </table>
            </div>
      
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
   
    getdata();
 });
 function getdata(){
     var formData={
         action:"loodbook_delayed"
     }
      $.ajax({
        dataType:"json",
        url: "booksReport/byDelayed.php",
        method: 'POST',
        data:formData,
        success: function (data) {
            $('#spinner').fadeOut('fast');
         if (data.length > 0) {
            var i = 1;
            var html = '';
            data.forEach(function(value) {
            var title=value.title;
            var book_code=value.book_code;
            var reg_no=value.reg_no;
            var student=value.fname+' '+value.lname;
            var department=value.department;
            var date_return=value.date_return;
            var isse_date=value.date_isse;
            var passed_days=value.days;
            var fine=value.fines;
            html += '<tr>';
            html += '<td>' + i++  + '</td>'; 
            html += '<td>' + title+ '</td>';
            html += '<td>' + book_code + '</td>';
            html += '<td>' + reg_no + '</td>';
            html += '<td>' + student + '</td>';
            html += '<td>' + department + '</td>';
            html +='<td>' +isse_date + '</td>';
            html += '<td>' +date_return + '</td>';
            if(passed_days>0){
            html += '<td>' + passed_days + '</td>';    
            }
            else{
                html += '<td></td>';
            }
               html += '<td>' +fine + '</td>';
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
       $("#exceldownload").click(function() {
    window.open("booksReport/Excell/delayed_excel.php", "_blank");

  });
  });
</script>
<!--scripts end-->