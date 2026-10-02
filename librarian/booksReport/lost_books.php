<div class="main-content">
    <section class="section">
     
     <div class="card">
    <div class="card-header">
        <div class="row col-12">
            <div class="col-lg-9 col-md-9">
               <h4>Losted Books Report</h4>  
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="col-lg-3 col-sm-3">
                  <button class="btn btn-primary" type="button" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excell</button> 
            </div>
            </div>
        </div>
       
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-striped" id="report_losted">
                    <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Book code</th> 
                  <th scope="col">Issue date</th>
                  <th scope="col">Borrow Return date</th>
                  <th scope="col">Price (Frw)</th>
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
   
    getdata();
 });
 function getdata(){
     var formData={
         action:"loodbook_losted"
     }
      $.ajax({
        dataType:"json",
        url: "booksReport/byLosted.php",
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
            var studentName = value.fname ;
            var stlast=value.lname;
            var stafname = value.staffname ;
            var staflname=value.stafflname;
            var staffreg = value.staffreg_no;
            var reg_no=value.reg_no;
            var department=value.department;
            var returdate=value.return_date;
            var issue_date=value.issue_date;
            var prices=value.price;
            var staffreg=value.staffid;
            var depstaff=value.depstaff;
            html += '<tr>';
            html += '<td>' + i++  + '</td>'; 
            html += '<td>' + title+ '</td>';
            html += '<td>' + book_code + '</td>';
            html += '<td>' + issue_date + '</td>';
            html += '<td>' + returdate + '</td>';
            html += '<td>' + prices + '</td>';
            html += '</tr>'; 
            });
            $('#data').html(html);            
         }

         else{
        $('#data').html('<tr><td colspan="9" align="center">oops, no data found</td></tr>');
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
    window.open("booksReport/Excell/losted_excel.php", "_blank");

  });
  });
</script>
<!--scripts end-->