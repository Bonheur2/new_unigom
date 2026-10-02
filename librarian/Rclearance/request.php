<div class="main-content">
    <section class="section">
        <div class="section-header">
          <h1>Student Clearance</h1>
             <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Clearance</a></div>
                      <div class="breadcrumb-item"><a href="#">Requests</a></div>
                            </div>
                        </div>
        <section class="section">
                <div class="section-body">
                                <div class="card-body">
                                    <form action="" id="search_student">
                                    <div class="row">
                                     <div class="form-group col-12 col-lg-6">
                                            <input type="text" class="form-control" placeholder="search student by reg. number" name="students_info" id="students_info">
                                            </div>
                                      <div class="form-group col-12 col-lg-2">       
                                             <button type="submit" class="btn btn-primary"> <span id="spinner">
                                             <i class="fas fa-search"></i>
                                        </span>search</button>
                                 </div>
                            </div>
                            </form>
                    </div>
            </section>
            <div class="card">
    <div class="card-header">
         <h4> <span id="name"></span>  Clearance Request data </h4>
            </div>
                <div class="card-body">
                <table class="table table-striped" id="clearance_table">
                    <thead>
                        <th scope="col" id="names"></th>
                        <th scope="col" id="depart"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                      </thead>
                  <thead> 
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Copy code</th> 
                  <th scope="col">Issue Date</th>
                  </thead>
                  <tbody id="data">
                      
               </tbody>
            </table>
        <button type="button" onclick="generatePDF()" class="btn btn-secondary btn-sm float-right">&nbsp;<i class="fa fa-file-pdf" aria-hidden="true"></i>&nbsp;Download Pdf&nbsp;</button>    </div>
        </div>
    </section>
</div>
    
    
    <!--scripts start-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<script>
$(document).ready(function(){
    
   $('#clearance_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
    });
    search();
}); 
function search(){
    $("#search_student").submit(function(e){
     e.preventDefault();
      $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        var formData={
            student_info:$("#students_info").val(),
            action:"search_students_clearance"
        };
         $.ajax({
                   dataType: "json",
                    url: "Rclearance/request_controller.php",
                    type: 'POST',
                    data:formData,
                    success: function (data) {
            $('#spinner').fadeOut('fast');
            if (data.length > 0) {
            var i = 1;
            var html = '';
            var students = '';
            var full_names='';
            var depart='';
            var full_department='';
            data.forEach(function(value) {
            var  title= value.title;
            var  book_code=value.book_code_number;
            var  department=value.department;
            var studentName = value.fname + ' ' + value.lname;
            var Departmet=value.department; 
            var issue_date=value.issue_date;
            html += '<tr>';
            html += '<td>' + i++  + '</td>'; 
            html += '<td>' + title+ '</td>';
            html += '<td>' + book_code+ '</td>';
            html += '<td>' + issue_date+ '</td>';
            html += '</tr>'; 
            students = studentName;
            full_names="Names: "+students;
            depart=Departmet;
            full_department="Department Of: "+depart;
            });
            $('#data').html(html); 
            $('#names').html(full_names);
            $('#depart').html(full_department);
         }
         else{
        $('#data').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                    }
        },error: function () {
                pop_wrong("Something went wrong!");
            }
      });
    })
        
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
  function generatePDF() {
     var qrcodesElement = document.getElementById('clearance_table');
    
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
</script>