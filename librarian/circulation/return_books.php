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
.stu:hover {
    background-color: #6D071A;
    color:white;/* Set the desired background color on hover */
}
.scrollable-popover {
    max-height: 200px; /* Set the maximum height for the popover content */
    overflow-y: auto;
   /* Enable vertical scrolling when content exceeds the maximum height */
}
.img1{
    margin-left:-15px;
    width:210px;
    height:220px;
}
.img3{
  width:60px;
  height:60px;
  margin-top:160px;
  margin-right:300px;
}
@media (max-width: 480px) {
  .img3{
      display:none;
  }
  .img1{
    width:250px !important;
    height:250px !important;
}
}
</style>
	</head>
	<body>              
           <div class="modal fade" tabindex="-1" role="dialog" id="add_info">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add fine Receipt</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>                 
                                           </div>
                                                <div class="modal-body">
                                             <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <form action="" id="add_document" method="POST">
                                                   <input type="hidden" name="book_borrowd_id" id="book_borrowd_id">
                                                   <input type="hidden" name="action" id="uplaod_receipt" value="uplaod_receipt">
                                                    <label>Fine Paid</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                              Rwf
                                                            </div>
                                                        </div>
                                                         <input type="text" name="amunt_clreared" id="amunt_clreared" class="form-control">
                                                    </div><br><br>
                                                    <div class="custom-file">
                                                            <input type="file" name="image_file"
                                                                class="custom-file-input image" id="image_file"
                                                                required>
                                                            <label class="custom-file-label"
                                                                id="selected_file_fine">Receipt image</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-lg-4"></div>
                                                        <div class="col-12 col-lg-8">
                                                            <button type="submit" class="btn btn-primary" style="width:80px;"><span id="EspinnerDocum"></span>Save</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                  
                                                </form>
                                            </div>
                                        </div> 
                                  </div>
                               </div>
                          </div>
                       </div>    
               
                <div class="main-content">
                     
                    <section class="section">
                        <div class="section-header">
                            <h1>Return Book</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Circulation</a></div>
                                <div class="breadcrumb-item"><a href="#">Return Book</a></div>
                            </div>
                        </div>
                    <section class="section">
                

                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-2 col-lg-2"></div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="search by book ID" id="input" data-toggle="popover" data-placement="bottom">
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
                         
                        <!--<div class="col-12 col-md-6 col-lg-7">-->
                        <!--    <div class="card">-->
                        <!--        <div class="card-body">-->
                        <!--            <table class="table table-hover table-sm">-->
                        <!--                <thead>-->
                        <!--                    <tr>-->
                        <!--                        <th scope="col"></th>-->
                        <!--                        <th scope="col"></th>-->
                                               
                        <!--                    </tr>-->
                        <!--                </thead>-->
                        <!--                <tbody id="contents">-->
                                            
                        <!--                </tbody>-->
                        <!--                </form>-->
                        <!--            </table>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    
                </div>
            </section>
            <div class="row">
                        <div class="col-12 col-md-5"></div>
                           <div class="col-12 col-md-4" id="spinnerE"></div>
                    </div>
             <div id="info" hidden>
                           <div class="row" id="inf">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Borrower Info <i class="fa fa-user"></i></h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        
                                            <thead>
                                                           <tr >
                                                                <td scope="col">Reg No:</td>
                                                                <td scope="col" id="reg"></td>
                                                            </tr><br><br>
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
                                                                <td scope="col"></td>
                                                                <td scope="col"></td>
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
                                    <div class="row">
                                        <div class="col-12 col-md-6" >
                                         <div class="row">
                                  <div class="col-12 col-lg-5" id="cover">
                                  </div>
                                  <div class="col-12 col-md-7" id="qrcode"></div>
                                  </div>   
                                    </div>
                                        <div class="col-12 col-md-6" >
                                            <div class="row">
                                         <div class="col-12 col-md-12"><b>Book:<span scope="col" id="cntr"></span></b></div> 
                                         <div class="col-12 col-md-12 mt-3"><b>Copy Code: <span scope="col" id="copy"></span></b></div>
                                         <div class="col-12 col-md-12 mt-3"><b>Issued date: <span scope="col" id="issue_copy"></span></b></div>
                                         <div class="col-12 col-md-12 mt-3"><b>Return date: <span scope="col" id="return_copy"></span></b></div>
                                          <div class="col-12 col-md-12 mt-3"><b>Status: <span scope="col"><div class="badge badge-warning" id="sec"></div></span></b></div>
                                          <div class="col-12 col-md-12 mt-3" id="finCheck"><b>Fine: <label id="fine"></label></b></div>
                                          <div class="col-12 col-md-5 mt-3">
                                               <form id="borrowdetails" action="borrowdetails" method="POST">
                                                 <input type="hidden" name="action" value="borrowdetails">
                                                    <input type="hidden"  id= "e_id" name="e_id">
                                                       <input type="hidden"  id= "c_id" name="c_id">
                                                         <button type="submit" class="btn btn-primary sub"><span id="spinner76"></span>&nbsp;<span id="indicator"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Return</span></button>
                                                 </form>
                                                </div>
                                            <div class="col-12 col-md-5 mt-3">
                                             <form id="lostbook" action="lostbook" method="POST">
                                                    <input type="hidden" name="action" value="lostbook">
                                                        <input type="hidden"  id= "e_id1" name="e_id1">
                                                            <input type="hidden"  id= "c_id1" name="c_id1">
                                                        <button type="submit" class="btn btn-danger sub"><span id="spinner89"></span>&nbsp;<span id="indicator"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Lost</span></button>
                                                 </form>   
                                            </div>
                                            <div class="col-12 col-md-5 mt-3">
                                              <!--<form id="clear" action="clear" method="POST">-->
                                                <input type="hidden" name="action" value="clear">
                                                         <input type="hidden"  id= "e_id2" name="e_id2">
                                                     <input type="hidden"  id= "c_id2" name="c_id2">
                                                   <button type="type" class="btn btn-warning sub"  id="fineclear"><span id="spinner_fine"></span>&nbsp;<span id="indicator_fine"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Clear</span></button>
                                              <!--</form> -->
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-12 col-md-4" id="finerow"></div>-->
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
            $('#spinner76').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner76').fadeOut('fast');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#borrowdetails')[0].reset();
                        $('#input').val('');
                        $("#info").attr("hidden",true);
                        $("#.stu").attr("hidden",true);
                        $("#contents").attr("hidden",true);
                        // $('.stu').load(location.href + ".stu");
                        // $('#info').load(location.href + "#info");
                        
                        
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
            $('#spinner89').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner89').fadeOut('fast');
                    if(data.status==200){
                        
                        pop_up_success(data.message);
                        $('#borrowdetails')[0].reset();
                        $('#input').val('');
                        $("#info").attr("hidden",true);
                        $("#.stu").attr("hidden",true);
                        $("#contents").attr("hidden",true);
                        
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
          
 //clear fine
 $("#clear").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner_fine').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_fine').html("Clearing...");
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner_fine').fadeOut('fast');
                    $('#indicator_fine').html('<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Cleared');
                    if(data.status==200){
                        $("#contents").attr("hidden",true);
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
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
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                     var popoverContent = $('<div>').addClass('scrollable-popover').append($('<table>').addClass('table table-sm').html(html));
            
                $('#input').popover('dispose').popover({
                  content: popoverContent,
                  html: true,
                  trigger: 'manual'
                });
                    if (data.length > 0) {
                       
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var id = value.borrow_details_id;
                            var names=value.fname+" "+value.lname;
                            var title=value.title;
                            var book=value.book_code_number;
                            html += '<tr class="stu" data-id='+id+'  style="cursor: pointer;">';
                            html += '<td>' + i+ '</td>';
                            html += '<td>' + book+ '</td>';
                            html += '<td>' + title+ '</td>';
                            html += '</tr>';
                            i++;
                        });
                  var popoverContent = $('<div>').addClass('scrollable-popover').append($('<table>').addClass('table table-sm').html(html));
            
                $('#input').popover('dispose').popover({
                  content: popoverContent,
                  html: true,
                  trigger: 'manual'
                });
                $('#input').popover('show');
                    } else{
                         $('#spinner').html("oops, no data found");
                        $('#contents').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                        $('#input').popover('hide');
                    }
               
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
        });
    });
    
    
    
    
     $(document).on('click', '.stu', function() {
         $('#input').popover('hide');
         var stud=$(this).data("id");
            var formData = {
                stu:stud,
                action:'load_info'
               
            }
            var book=$(this).find('td:nth-child(2)').text();
            var selectedName = $(this).find('td:nth-child(3)').text();
      $('#input').val(book+" "+"[ "+selectedName+" ]");
         $('#spinnerE').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    $('#spinnerE').fadeOut('fast');
                    if(data.length>0){
                        var html = '';
                        var images = data[0].qr_code_file;
                        var im=data[3].qr_code_file;
                        var fine = data[2].fines;
                        var staff=data[3].staff_id;
                        if(staff){
                         $("#fineclear").attr("hidden", true);  
                         $("#finCheck").attr("hidden",true);
                        }
                        else{
                         if (fine >0) {
                              $("#fineclear").attr("hidden", false);
                              $("#fine").html(fine+' frw');
                               $("#finCheck").attr("hidden",false);
                            } else {
                              $("#fineclear").attr("hidden", true);
                             $("#finCheck").attr("hidden",true);
                            }    
                        }
                           


                        if(images){
                         html += '<img alt="image" src="/librarian/books/'+images+ '" class="img3">';    
                        }
                        else{
                             html += '<img alt="image" src="/librarian/books/'+im+ '" class="img3">';
                        }
                        $("#contents").attr("hidden",true);
                        $("#info").attr("hidden",false);
                        $("#student").attr("hidden",false);
                         $("#names").html(data[0].fname);
                         if(data[0].reg_no){
                           $("#reg").html(data[0].reg_no); 
                           $("#rn").html(data[0].reg_no);
                         }
                         else{
                              $("#reg").html(data[3].staff_id);
                             $("#rn").html(data[0].staff_id);
                         }
                        
                        //personal
                         if(data[0].fname || data[0].lname){
                         $("#fn").html(data[0].fname);
                            $("#ln").html(data[0].lname);    
                         }
                            else{
                                $("#fn").html(data[3].staff_first_name);
                            $("#ln").html(data[3].staff_family_name);
                            }
                            
                            $("#nid").html(data[0].ID);
                            if(data[0].gender){
                               var gen= data[0].gender;
                               if(gen=="M"){
                                $("#gen").html("Male");      
                               }
                               else{
                                   $("#gen").html("Female");   
                               }
                             
                            }
                            else{
                                var gen=data[3].staff_sex;
                                if(gen=="M"){
                                $("#gen").html("Male");    
                                }
                                else{
                                    $("#gen").html("Female");
                                }
                                
                            }
                        //borrow inf
                        if(data[0].title){
                          $("#cntr").html(data[0].title);   
                        }
                        else{
                             $("#cntr").html(data[3].title);
                        }
                        $("#prov").html(data[0].book_code);
                        if(data[0].book_code_number){
                        $("#copy").html(data[0].book_code_number);    
                        }
                        else{
                        $("#copy").html(data[3].book_code_number);        
                        }
                        if(data[0].issue_date){
                        $("#issue_copy").html(data[0].issue_date);    
                        }
                        else{
                        $("#issue_copy").html(data[3].issue_date);    
                        }
                        if(data[0].date_return){
                          $("#return_copy").html(data[0].date_return);    
                         }
                        else{
                          $("#return_copy").html(data[3].date_return);    
                         }
                          
                        $("#dis").html(data[0].date_return);
                        if(data[0].borrow_status){
                         $("#sec").html(data[0].borrow_status);        
                            }
                        else{
                         $("#sec").html(data[3].borrow_status);    
                        }
                        if(data[0].borrow_details_id){
                         $("#e_id").val(data[0].borrow_details_id);   
                        }
                        else{
                        $("#e_id").val(data[3].borrow_details_id);       
                        }
                        if(data[0].borrow_details_id){
                        $("#e_id1").val(data[0].borrow_details_id);    
                        }
                        else{
                        $("#e_id1").val(data[3].borrow_details_id);    
                        }
                        if(data[0].book_code_number){
                        $("#c_id").val(data[0].book_code_number);    
                        }
                        else{
                        $("#c_id").val(data[3].book_code_number);    
                        }
                        if(data[0].borrow_details_id){
                         $("#e_id2").val(data[0].borrow_details_id);    
                        }
                        else{
                         $("#e_id2").val(data[3].borrow_details_id);    
                        }
                        if(data[0].book_code_number){
                         $("#c_id2").val(data[0].book_code_number);    
                        }    
                        else{
                         $("#c_id2").val(data[3].book_code_number);    
                        }
                        if(data[0].book_code_number){
                        $("#c_id1").val(data[0].book_code_number);    
                        }
                        else{
                        $("#c_id1").val(data[3].book_code_number);
                        }
                            
                            $('#qrcode').html(html);
                            
                             var html2 = '';
                             if(data[0].image){
                            var cover = data[0].image;   
                             html2 += '<img src="/librarian/books/'+cover+ '" alt="Book cover" class="img1">';
                             }
                             else{
                             var cover = data[3].image;   
                             html2 += '<img src="/librarian/books/'+cover+ '" alt="Book cover" class="img1">';    
                             }
            
           
                            $("#cover").html(html2);
                           
                        //contact
                        if(data[0].email){
                        $("#em").html(data[0].email);    
                        }
                        else{
                            $("#em").html(data[3].email);
                        }
                        if(data[0].phone){
                         $("#pn").html(data[0].phone);   
                        }
                        else{
                         $("#pn").html(data[3].PhoneNumber);   
                        }
                           
                            $("#ppn").html(data[0].parent_phone);
                            $("#secpn").html(data[0].ref_phone);
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
    
    // clear credit
     $(document).on('click', '#fineclear', function() {
         var book_id=$("#e_id2").val();
         var paid=parseInt($("#fine").text());
         $("#book_borrowd_id").val(book_id);
         $("#amunt_clreared").val(paid)
         $("#add_info").modal('show');
     });
     
  
   const fileImage = document.getElementById('image_file');
       // Get the label element
   const selectedFileFine = document.getElementById('selected_file_fine');

            // Add an event listener to the file input
   fileImage.addEventListener('change', function (event) {
                // Get the selected file name
   const fileNameImage = event.target.files[0].name;

                // Update the label text with the selected file name
   selectedFileFine.innerText = fileNameImage;
            });
            
              //  submit form clear
 $("#add_document").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#EspinnerDocum').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/return_books_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data){
                  $('#EspinnerDocum').fadeOut('fast'); 
                  if(data.status==200){
                  pop_up_success(data.message);
                     $("#add_info").modal('hide');
                     var fine_paid=parseInt(data.fine);
                     var suppposed_pay=parseInt($("#fine").text());
                     if(fine_paid==suppposed_pay || fine_paid>suppposed_pay){
                      $("#finCheck").attr("hidden",true);
                      $("#fineclear").attr("hidden", true);     
                     }
                     else{
                      var remain=suppposed_pay-fine_paid;
                      $("#fine").html(remain+' frw');
                     }    
                  }
                  if(data.status==500){
                   pop_wrong(data.message);    
                  }
                  },error: function(){
                    $('#EspinnerDocum').fadeOut('fast');
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
        
