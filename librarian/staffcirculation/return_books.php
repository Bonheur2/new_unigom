               
               
               
                <div class="main-content">
                     
                    <section class="section">
                        <div class="section-header">
                            <h1>Return Book</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Staff circulation</a></div>
                                <div class="breadcrumb-item"><a href="#">Return Book</a></div>
                            </div>
                        </div>
                    <section class="section">
                

                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="search by Book ID" id="input">
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
                        <div class="col-12 col-md-6 col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
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
                                          
                                          <tr><th scope="col">Staff Id:</th>
                                             <td id="reg"></td>
                                          </tr>
                                          
                                        </tbody>
                                        </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="col-12 col-sm-7 col-lg-7" id="info" hidden>
                            <div class="card">
                                <div class="card-header">
                                    <h4>Book information <i class="fa fa-address-book" aria-hidden="true"></i></h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-4">
                                            <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">Book info</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-tab4" data-toggle="tab" href="#profile4" role="tab" aria-controls="profile" aria-selected="false">Brower info</a></li>
                                                
                                            </ul>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-8">
                                        <div class="tab-content no-padding" id="myTab2Content">
                                            <div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                            
                                                        <table class="table table-sm" >
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Book:</th>
                                                                <th scope="col" id="cntr"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Book Code:</th>
                                                                <th scope="col" id="prov"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Copy Code:</th>
                                                                <th scope="col" id="copy"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Return date:</th>
                                                                <th scope="col" id="dis"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Status:</th>
                                                                <th scope="col"><div class="badge badge-warning" id="sec"></div></th>
                                                            </tr>
                                                            <tr>
                                                                <td>Scan QR-code</td>
                                                                <td id="qrcode"></td>
                                                                 
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            <tr>
                                                                <td>
                                                            
                                                                 <form id="borrowdetails" action="borrowdetails" method="POST">
                                                                 <input type="hidden" name="action" value="borrowdetails">
                                                                 <input type="hidden"  id= "e_id" name="e_id">
                                                                 <input type="hidden"  id= "c_id" name="c_id">
                                                                 <button type="submit" class="btn btn-primary sub"><span id="spinner"></span>&nbsp;<span id="indicator"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return</span></button>
                                                                 </form>
                                                                 </td>
                                                                 <td>
                                                                 <form id="lostbook" action="lostbook" method="POST">
                                                                 <input type="hidden" name="action" value="lostbook">
                                                                 <input type="hidden"  id= "e_id1" name="e_id1">
                                                                 <input type="hidden"  id= "c_id1" name="c_id1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                 &nbsp;&nbsp;<button type="submit" class="btn btn-danger sub"><span id="spinner"></span>&nbsp;<span id="indicator"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Lost</span></button>
                                                                 </form>
                                                                 
                                                                 </td>
                                                            </tr>
                                                            
                                                            
                                                            
                                                        </thead>
                                                        </table>
                                                
                                            </div>
                                            <div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
                                                <table class="table table-sm">
                                                          <thead>
                                                             <tr>
                                                                <th scope="col">Staff Id:</th>
                                                                <th scope="col" id="rn"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Firstname:</th>
                                                                <th scope="col" id="fn"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Lastname:</th>
                                                                <th scope="col" id="ln"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Gender:</th>
                                                                <th scope="col" id="gen"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Email:</th>
                                                                <th scope="col" id="em"></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Phone:</th>
                                                                <th scope="col" id="pn"></th>
                                                            </tr>
                                                        </thead>
                                                        </table>
                                            </div>
                                            
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                            
                    </section>
                </div>
              
                
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

          
          
          <script>
$(document).ready(function(){
    
     $('#book_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    
    //return book
   $("#borrowdetails").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "staffcirculation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return');
                    if(data.status==200){
                        $('#borrowdetails')[0].reset();
                        $("#info").attr("hidden",true);
                        $("#.stu").attr("hidden",true);
                        $("#contents").attr("hidden",true);
                        // $('.stu').load(location.href + ".stu");
                        // $('#info').load(location.href + "#info");
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
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return');
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
    
    //Lost book
   $("#lostbook").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "staffcirculation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return');
                    if(data.status==200){
                        $('#borrowdetails')[0].reset();
                        $("#info").attr("hidden",true);
                        $("#.stu").attr("hidden",true);
                        $("#contents").attr("hidden",true);
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
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return');
                    pop_wrong("Something went wrong!");
                    
                }
             });
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
                url: "staffcirculation/return_books_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var id = value.borrow_details_id;
                            var title=value.title;
                            var book=value.book_code_number;
                            html += '<tr class="stu" data-id='+id+'>';
                            html += '<th>' + i+ '</th>';
                            html += '<td>' + book+ '</td>';
                            html += '<td>' + title+ '</td>';
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
    
    
    
    
     $(document).on('click', '.stu', function() {
        var formData = {
            stu:$(this).data("id"),
            action:'load_info'
        }
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/return_books_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        var html = '';
                        var images = data[0].qr_code_file;
                        html += '<img alt="image" src="/librarian/books/'+images+ '">';
                        $("#contents").attr("hidden",true);
                        $("#info").attr("hidden",false);
                        $("#student").attr("hidden",false);
                         $("#names").html(data[0].staff_family_name);
                        $("#reg").html(data[0].staff_id);
                        //personal
                       
                            $("#rn").html(data[0].staff_id);
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
                            $("#e_id").val(data[0].borrow_details_id);
                            $("#e_id1").val(data[0].borrow_details_id);
                            $("#c_id").val(data[0].book_code_number);
                            $("#c_id1").val(data[0].book_code_number);
                            $('#qrcode').html(html);
                            
                           
                       
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
        
