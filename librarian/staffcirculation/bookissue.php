               
               
               
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>borrowdetails</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Staff circulation</a></div>
                                <div class="breadcrumb-item"><a href="#">Book Issue</a></div>
                            </div>
                        </div>
                    <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="search by Staff_Id or names" id="input">
                                            <div class="input-group-append">
                                                <div class="input-group-text" id="spinner">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="contents">
                                            
                                        </tbody>
                                        <tbody id="student" hidden>
                                          <tr><th scope="col">Name:</th>
                                             <td id="names"></td>
                                          </tr>
                                          
                                          <tr><th scope="col">Staff ID:</th>
                                             <td id="reg"></td>
                                          </tr>
                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <div id="info" hidden>
                 
            <div class="row" id="inf">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Staff info <i class="fa-sharp fa-light fa-school"></i></h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        
                                            <thead>
                                                            <tr>
                                                                <td scope="col">Firstname:</td>
                                                                <td scope="col" id="fn"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Lastname:</td>
                                                                <td scope="col" id="ln"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Gender:</td>
                                                                <td scope="col" id="gen"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Email:</td>
                                                                <td scope="col" id="em"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Phone:</td>
                                                                <td scope="col" id="pn"></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td scope="col">Is acadmic:</td>
                                                                <td scope="col" id="stt"></td>
                                                            </tr>
                                                        </thead>
                                            
                                    </table>
                                </div>
                                
                            </div>
                            
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Library info <i class="fa fa-book" aria-hidden="true"></i></h4>
                                </div>
                                <div class="card-body">
                                     <table class="table table-sm" id="brwdbk">
                                                        <thead>
                                                <tr><th scope="col">#</th>
                                                <th scope="col">Browed Books</th>
                                                <th scope="col">Startus</th>
                                                <th scope="col">Return Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="browinf">
                                            
                                        </tbody>
                                                        </table>
                                </div>
                            </div>
                           
                        </div>
                    </div>

           
                        <div class="card card-warning">
                                <div class="card-header">
                                    <h4>New brower <i class="fa fa-address-book" aria-hidden="true"></i> </h4>
                                   <form class="card-header-form">
                                        <div class="input-group">
                                            <input type="text" name="search" id="searchbook" name="book" class="form-control" placeholder="Search book">
                                    
                                         </div>
                                    </form>
                                            
                                </div>
                                <div class="card-body" id="result" hidden>
                                    <table class="table table-hover table-sm" id="bksch">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">ID</th>
                                                <th scope="col">Status</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="bok">
                                            
                                        </tbody>
                                    </table>
                                    </div>
                                 <div class="card-body" id="bookinfo" hidden>
                                     
                                      <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <td scope="col"> <label for="retundate">Book Title:</label><label id="bookname"></label></td>
                                                            </tr>
                                                            <tr>
                                                                <td id="qr"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col"><label for="retundate">Book Code:</label> <label  id="bookid"></label></td>
                                                                
                                                            </tr>
                                                           
                                                            
                                                            <tr>
                                                                
                                                                <th>
                                                            
                                                                 <form id="borrowdetails" action="borrowdetails" method="POST">
                                            <input type="hidden" name="action" value="borrowdetails">
                                             <input type="hidden" class="form-control " name="studentid" id="studentid" required>
                                             <input type="hidden" class="form-control " name="copyid" id="copyid" required>
                                            
                                            <label for="retundate">Days:</label>
                                            <input type="number" class="form-control" style="width:20%;" name="retundate" id="retundate" required>
                                            <br>
                                            <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Borrow</span></button>
                                            <br>
                                            <label id="datertn"></label>
                                            <br>
                                            
                                            
                                                                 
                                                                 </form>
                                                                 </th>
                                                                
                                                                 
                                                            </tr>
                                                            
                                                            
                                                            
                                                        </thead>
                                                        </table>
                                                
                                     
                                     
                                     
                                    
                                    
                                        
                                        
                                        
              
                                    
                                    
                                
                            </div>   
                                
                            </div>     
            
                        
                            
                          
                    
                        </div>
              
                
                
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    
     
     //save borrowe detail
    $("#borrowdetails").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Borrow');
                    if(data.status==200){
                    $('#borrowdetails')[0].reset();
                    pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
    $("#searchbook").keyup(function(e){
        var formData = {
            book:$(this).val(),
            action:'loadcopy'
        }
            $("#inf").attr("hidden",true);
            $("#bookinfo").attr("hidden",true);
            $("#result").attr("hidden", false);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                           var book = value.book_code_number;
                            var id=value.id;
                            var stts=value.status;
                            
                            html += '<tr class="bk" id="bk" data-id='+id+'>';
                            html += '<th>' + i+ '</th>';
                            html += '<td>' + book + '</td>';
                            html += '<td>' + stts + '</td>';
                            html += '</tr>';
                            i++;
                            
                        });
                      
                        $('#bok').html(html);
                       
                    } else{
                        $('#bok').html('oops, no data found');
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
        });
    });         
          
   $("#retundate").keyup(function(e) {
  var daysToAdd = parseInt($(this).val());

  var currentDate = new Date();
  var targetDate = calculateTargetDate(currentDate, daysToAdd);

  var dayOfWeek = targetDate.getDay();
  var isWeekend = isWeekendDay(dayOfWeek);

  var formattedDate = getFormattedDate(targetDate, dayOfWeek);

  if (isWeekend) {
    $('#datertn').html('Please choose another day. It is a weekend day.');
  } else {
    $('#datertn').html('Return Date:'+formattedDate);
  }
});




    $("#input").keyup(function(e){
        var formData = {
            keyword:$(this).val(),
            action:'search'
        }
            $("#info").attr("hidden",true);
            $("#student").attr("hidden",true);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var reg = value.staff_id;
                            var names=value.staff_family_name+" "+value.staff_first_name;
                            html += '<tr class="stu" id="stu" data-id='+reg+'>';
                            html += '<th>' + i+ '</th>';
                            html += '<th>' + reg+ '</th>';
                            html += '<td>' + names+ '</td>';
                            html += '</tr>';
                            i++;
                        });
                         $("#contents").attr("hidden",false);
                        $('#contents').html(html);
                    } else{
                        $('#contents').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
        });
    });
    
$(document).on('click', '.bk', function() {
    var formData = {
        bk: $(this).data("id"),
        action: 'copyinfo'
    };

    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    
    $.ajax({
        url: "staffcirculation/bookissuecontroller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
             $('#spinner').html("<i class='fas fa-search'></i>");
             $("#result").attr("hidden", true);
             $("#bookinfo").attr("hidden",false);
             $("#copyid").val(data.id);
             $("#bookid").html(data.book_code_number);
            $("#bookname").html(data.title);
            
            var html = '';
            var images = data.qr_code_file;
            html += '<img alt="image" src="/librarian/books/'+images+ '">';
             
             $("#qr").html(html);
        },
        error: function() {
            $('#spinner').html("<i class='fas fa-search'></i>");
            pop_wrong("Something went wrong!");
        }
    });
});

    
  $(document).on('click', '#student', function() {
    
 $("#inf").attr("hidden",false);
$("#info").attr("hidden",false);

    
});  
    
     $(document).on('click', '.stu', function() {
        var formData = {
            stu:$(this).data("id"),
            action:'load_info'
           
        }
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        
                        $("#inf").attr("hidden",false);
                        $("#info").attr("hidden",false);
                        $("#student").attr("hidden",false);
                        $("#contents").attr("hidden",true);
                        $("#studentid").val(data[0].id);
                        $("#names").html(data[0].staff_family_name);
                        $("#reg").html(data[0].staff_id);
                        
                        
                        //personal
                            $("#fn").html(data[0].staff_family_name);
                            $("#ln").html(data[0].staff_first_name);
                           
                            $("#gen").html(data[0].staff_sex);
                            //contact
                            $("#em").html(data[0].email);
                            $("#pn").html(data[0].PhoneNumber);
                        //borrow inf
                            $("#cntr").html(data[0].title);
                            $("#prov").html(data[0].book_code);
                            $("#copy").html(data[0].book_code_number);
                            $("#dis").html(data[0].date_return);
                            $("#sec").html(data[0].borrow_status);
                            
                           
    
                           
                       
                            
                        
                            var status='0';
                                if(data[0].is_acadmic==1){
                                   status="No"; 
                                }
                                else{
                                    status="Yes";
                                }
                            $("#stt").html(status);
                       //academics
                     
                        
                       if (data[2].length > 0) {
    var i = 1;
    var html = '';

    data[2].forEach(function(value) {
        var id = value.borrow_details_id;
        var returndate = value.date_return;
        var book = value.book_code_number;
        
        var status = value.borrow_status;

        var badgeClass = "";
        if (status === "losted") {
        badgeClass = "badge badge-danger";
       } else if (status === "pending") {
       badgeClass = "badge badge-warning";
      } else {
       badgeClass = "badge-success";
       }


        html += '<tr class="st" data-id=' + id + '>';
        html += '<th>' + i + '</th>';
        html += '<td>' + book + '</td>';
        html += '<td><div class="' + badgeClass + '">' + status + '</div></td>';
        html += '<td>' + returndate + '</td>';
        html += '<td></td>';
        html += '</tr>';

        i++;
    });

    $('#browinf').html(html);

}
 else{
                            $('#browinf').html('<tr><td colspan="7" align="center">oops, no data found</td></tr>');
                        }
                    }
                    else{
                        pop_wrong("No data!");
                        $("#info").attr("hidden",true);
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
        });
    });
          
          
          
    });
    
 function calculateTargetDate(currentDate, daysToAdd) {
  var targetDate = new Date(currentDate.getTime());
  targetDate.setDate(targetDate.getDate() + daysToAdd);
  return targetDate;
}

function isWeekendDay(dayOfWeek) {
  return dayOfWeek === 0 || dayOfWeek === 6; 
}

function getFormattedDate(date) {
  var year = date.getFullYear();
  var month = String(date.getMonth() + 1).padStart(2, '0');
  var day = String(date.getDate()).padStart(2, '0');
  return year + '-' + month + '-' + day;
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
        
