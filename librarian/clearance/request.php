                      <html>
	<head>
		<style>

		.image-container {
  width: 100px; /* Adjust the width and height to your desired size */
  height: 100px;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  
}


		</style>
	</head>
	<body>
       
               
               
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Clearance</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Clearance</a></div>
                                <div class="breadcrumb-item"><a href="#">Request</a></div>
                            </div>
                        </div>
                    <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-2"></div>
                        <div class="col-12 col-md-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        
                                        
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="search by reg. number or names" id="input">
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
                        <div class="col-12 col-md-12 col-lg-2"></div>
                         <div class="col-12 col-md-12 col-lg-2"></div>
                        <div class="col-12 col-md-6 col-lg-6">
                                    <table class="table table-hover table-sm">
                                       
                                        <tbody id="contents">
                                            
                                        </tbody>
                                        
                                    </table>
                        </div>
                    </div>
                </div>
            </section>
            
            <div id="info" hidden>
                 
            <div class="row" id="inf">
                
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Student info <i class="fa fa-graduation-cap" aria-hidden="true"></i></h4>
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
                                                                <td scope="col">Specialzization:</td>
                                                                <td scope="col" id="spc"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Level:</td>
                                                                <td scope="col" id="lev"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Status:</td>
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
                                                <th scope="col">Fine</th>
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
                               
                               
                                 <div class="card-body col-12 col-md-6 col-lg-6" id="bookinfo" hidden>
                                     
                                      <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <td scope="col"> <label for="retundate">Book Title:</label><label id="bookname"></label></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td id="cover"></td>
                                                                <td id="qr"></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td scope="col"><label for="retundate"><b>Book Code:</label> <label  id="bookid"></label></b></td>
                                                                <td><b>Scan QR-Code</b></td>
                                                            </tr>
                                                           
                                                            
                                                            <tr>
                                                                
                                                                <td>
                                                                    
                                                                     <form id="clear" action="clear" method="POST">
                                                                 <input type="hidden" name="action" value="clear">
                                                                 <input type="hidden"  id= "e_id2" name="e_id2">
                                                                 <input type="hidden"  id= "c_id2" name="c_id2">
                                                                 <button type="submit" class="btn btn-warning sub"  id="fineclear"><span id="spinner"></span>&nbsp;<span id="indicator"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Clear</span></button>
                                                                 </form>
                                                                 </td>
                                                                
                                                                 
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
    
 
 $("#clear").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Cleared');
                    if(data.status==200){
                        $("#contents").attr("hidden",true);
                        pop_up_success(data.message);
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
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var reg = value.reg_no;
                            var names=value.fname+" "+value.lname;
                            html += '<tr class="stu" id="stu" data-id='+reg+' style="cursor: pointer;" onmouseover="this.style.color=\'blue\';" onmouseout="this.style.color=\'\';">';
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
        stu: $(this).data("id"),
        action: 'load_info'
    };
            $("#result").attr("hidden", false);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        url: "circulation/return_books_controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
             $('#spinner').html("<i class='fas fa-search'></i>");
             $("#bookinfo").attr("hidden",false);
             $("#copyid").val(data[0].id);
             $("#bookid").html(data[0].book_code_number);
            $("#bookname").html(data[0].title);
             $("#e_id2").val(data[0].borrow_details_id);
            $("#c_id2").val(data[0].book_code_number);
            
            var html = '';
            var images = data[0].qr_code_file;
            html += '<img alt="image" src="/librarian/books/'+images+ '">';
             
             $("#qr").html(html);
             
             
            var html2 = '';
            var cover = data[0].image;
            html2 += ' <div class="image-container">';
            html2 += '<img src="/librarian/books/'+cover+ '" alt="Book cover">';
            html2 += '</div>';
             
             $("#cover").html(html2);
        },
        error: function() {
            $('#spinner').html("<i class='fas fa-search'></i>");
            pop_wrong("Something went wrong!");
        }
    });
});

 $(document).on('click', '.stu', function() {
        var formData = {
            stu:$(this).data("id"),
            action:'load_fine'
           
        }
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        $("#bookinfo").attr("hidden",true);
                        $("#inf").attr("hidden",false);
                        $("#info").attr("hidden",false);
                        $("#contents").attr("hidden",true);
                        $("#studentid").val(data[0].adm_id);
                        $("#names").html(data[0].fname);
                        $("#reg").html(data[0].reg_no);
                        
                        
                        //personal
                            $("#fn").html(data[0].fname);
                            $("#ln").html(data[0].lname);
                            $("#nid").html(data[0].ID);
                            $("#gen").html(data[0].gender);
                            $("#ftn").html(data[0].father_names);
                            $("#mtn").html(data[0].mother_names);
                        //borrow inf
                            $("#cntr").html(data[0].title);
                            $("#prov").html(data[0].book_code);
                            $("#copy").html(data[0].book_code_number);
                            $("#dis").html(data[0].date_return);
                            $("#sec").html(data[0].borrow_status);
                            
                           
    
                           
                        //contact
                            $("#em").html(data[0].email);
                            $("#pn").html(data[0].phone);
                            $("#ppn").html(data[0].parent_phone);
                            $("#secpn").html(data[0].ref_phone);
                        //academics
                            $("#spc").html(data[1].splz_full_name);
                            $("#lev").html(data[1].level_full_name);
                            var status='0';
                                if(data[1].reg_active==1){
                                   status="active"; 
                                }
                                else{
                                    status="finished";
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
        var finr = value.fine;
        var status = value.borrow_status;

        var badgeClass = "";
        if (status === "losted") {
        badgeClass = "badge badge-danger";
       } else if (status === "returned") {
       badgeClass = "badge badge-success";
      } else {
       badgeClass = "badge badge-warning";
       }

        html += '<tr class="bk" id="bk" data-id='+id+' style="cursor: pointer;" onmouseover="this.style.color=\'blue\';" onmouseout="this.style.color=\'\';">';
        html += '<th>' + i + '</th>';
        html += '<td>' + book + '</td>';
        html += '<td><div class="' + badgeClass + '">' + status + '</div></td>';
        html += '<td>' + finr + '</td>';
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
        
