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
.bk:hover {
    background-color: #6D071A;
    color:white;/* Set the desired background color on hover */
}
.scrollable-popover {
    max-height: 150px; /* Set the maximum height for the popover content */
    overflow-y: auto; /* Enable vertical scrolling when content exceeds the maximum height */
}

		</style>
	</head>
	<body>
       
               
               
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>New Borrower</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Circulation</a></div>
                                <div class="breadcrumb-item"><a href="#">Book Issue</a></div>
                            </div>
                        </div>
                        
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-12 col-md-2"></div>
                                            <div class="col-12 col-md-8 col-lg-8">
                                                <div class="card">
                                                <div class="card-body " id="result">
                                                         <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            
                                                        </div>
                                                        <input type="text" name="search" id="searchbook" name="book" class="form-control" placeholder="Search book" data-toggle="popover" data-placement="bottom">
                                                            <div class="input-group-append">
                                                             <div class="input-group-text" id="spinner9">
                                                            <i class="fas fa-search"></i>
                                                        </div>
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
                                  <div class="" id="card_data" style="display:none">
                                 <div class="row" id="bookinfo" hidden>
                                      <!--<table class="table table-sm">-->
                                      <!--                  <thead>-->
                                      <!--                      <tr>-->
                                      <!--                          <td ></td>-->
                                                                <!--<td id="qr"></td>-->
                                                                
                                      <!--                      </tr>-->
                                                            <!--<tr>-->
                                                            <!--    <td scope="col"><label id="stts"></label></td>-->
                                                            <!--    <td><b>Scan QR-Code</b></td>-->
                                                            <!--</tr>-->
                                                            
                                      <!--                  </thead>-->
                                      <!--                  </table>-->
                                      <div class="col-12 col-md-7 col-lg-7">
                                           <div class="card">
                                     <div class="card-body">
                                       <div class="list-group" >
                                            <div class="row">
                                         <div class="col-12 col-md-6 col-lg-6" id="cover">
                                           </div>  
                                             <div class="col-12 col-md-6 col-lg-6">
                                       <a href="#" class="list-group-item-action flex-column align-items-start"><br>
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Book Title: <span id="bookname"></span></h6>
                                          </div><br>
                                        <h6>Department: <span id="department"><span></h6><br>
                                        <h6>Author: <span id="autho"></span></h6><br>
                                         <h6>Book Code :<span id="bookid"></span></h6>
                                        </a> 
                                    </div>
                                    </div>
                                    </div>
                                    </div> 
                                    </div>
                                    </div>
                                    <div class="col-12 col-md-5 col-lg-5">
                                    <div class="card">
                                     <div class="card-body">
                                         <form id="myForm">
                                    <div class="form-group mt-2">
                                                    <label>Brower-occupation</label>
                                                    <div class="input-group col-12 col-sm-12 col-lg-12">
                                                        <select class="form-control select2" style="width:100%;" name="lease" id="lease">
                                                         <option value="none">select occupation</option>
                                                         <option value="Student"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Student</option>
                                                         <option value="Staff"><i class="fa fa-user-circle" aria-hidden="true"></i>Staff</option>
                                                     </select>
                                                    </div>
                                                </div>  <br>  
                                    <div class="form-group col-12 col-sm-12 col-lg-12" hidden id="student1">
                                                    <label>Search student (*)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" id="spinner8">
                                                                &nbsp;<i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="input" data-toggle="popover" data-placement="bottom">
                                                    </div>
                                                </div><br>
                                                <div class="form-group col-12 col-sm-12 col-lg-12" hidden id="staff">
                                                    <label>Search staff (*)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" id="authorsearcher">
                                                                &nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="staff1" id="staff1" data-toggle="popover" data-placement="bottom">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-12 col-md-5"></div>
                                                    <div class="col-12 col-md-2"><span id="spinnerD"></span>
                                                    </div>
                                                </div>
                                            </form>
                                            
                                            
                                    </div>
                                </div>
                                </div>
                            </div>   
                                
                            </div>  
                   
            
            <div id="info" hidden>
            <div class="row" id="stfinf">
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
                                                                <td scope="col" id="fn1"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Lastname:</td>
                                                                <td scope="col" id="ln1"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Gender:</td>
                                                                <td scope="col" id="gen1"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Email:</td>
                                                                <td scope="col" id="em1"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Phone:</td>
                                                                <td scope="col" id="pn1"></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td scope="col">Is acadmic:</td>
                                                                <td scope="col" id="stt1"></td>
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
                                     <table class="table table-sm" id="brwdbk1">
                                                        <thead>
                                                <tr><th scope="col">#</th>
                                                <th scope="col">Browed Books</th>
                                                <th scope="col">Startus</th>
                                                <th scope="col">Return Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="browinf1">
                                            
                                        </tbody>
                                                        </table>
                                    <form id="borrowdetails1" action="borrowdetails" method="POST">
                                            <input type="hidden" name="action" value="borrowdetails">
                                             <input type="hidden" class="form-control " name="studentid" id="studentid1" required>
                                             <input type="hidden" class="form-control " name="copyid" id="copyid1" required>
                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                    <label>Return days</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="retundate" id="retundate1" placeholder="Enter return days" required>
                                                   <br>
                                                   <label id="datertn1"></label>
                                                   <br>
                                                    </div>
                                                </div>
                                            <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Borrow</span></button>
                                            
                                    </form>
                                </div>
                            </div>
                           
                        </div>
                    </div>    
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
                                                <th scope="col">Return Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="browinf">
                                            
                                        </tbody>
                                                        </table>
                                
                                            <form id="borrowdetails" action="borrowdetails" method="POST">
                                            <input type="hidden" name="action" value="borrowdetails">
                                             <input type="hidden" class="form-control " name="studentid" id="studentid" required>
                                             <input type="hidden" class="form-control " name="copyid" id="copyid" required>
                                            
                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                    <label>Return days</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" style="width:100%;" name="retundate" placeholder="Enter return days" id="retundate" required>
                                                   <br>
                                                   <label id="datertn"></label>
                                                   <br>
                                                    </div>
                                                </div>
                                            <button type="submit" class="btn btn-primary"><span id="indicatorA">Borrow</span></button>
                                            <br>
                                            
                                            <br>
                                        </form>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                        </div>
            
              
                
                
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(function() {
  $('[data-toggle="popover"]').popover();
  
 $('#searchbook').keyup(function(e) {
     $("#card_data").css({
         display:'none'
     });
  var formData = {
            book:$(this).val(),
            action:'loadcopy'
  };
            $("#inf").attr("hidden",true);
            $("#bookinfo").attr("hidden",true);
            $("#result").attr("hidden", false);
            $('#spinner9').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
           $.ajax({
             url: "circulation/bookissuecontroller.php",
             type: "POST",
             data: formData,
             dataType: "JSON",
  success: function(data) {
    $('#spinner9').html('&nbsp;<i class="fas fa-search" aria-hidden="true"></i>&nbsp;');
    if (data.length > 0) {
      var i = 1;
      var html = '';
      data.forEach(function(value) {
       
         var book = value.book_code_number;
         var id=value.id;
         var barCode=value.bar_code_copy;
        html += '<tr class="bk" id="bk" data-id=' + id + ' style="cursor: pointer;">';
        html += '<td>&nbsp;<i class="fa fa-address-book" aria-hidden="true"></i>&nbsp;</td>';
        html += '<td>' + book +'[ '+ barCode +' ]'+ '</td>';
        html += '</tr>';
         
        i++;
      });
    } else {
      html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops,Book not found</td></tr>';
    }

    var popoverContent = $('<div>').addClass('scrollable-popover').append($('<table>').addClass('table table-sm').html(html));

    $('#searchbook').popover('dispose').popover({
      content: popoverContent,
      html: true,
      trigger: 'manual'
    });

    $('#searchbook').popover('show');

  },
  error: function() {
    $('#author').popover('dispose').popover({
      content: 'Something went wrong',
      trigger: 'manual'
    });

    $('#author').popover('show');
    $('#secondaryauthor').attr("hidden",true);
  }
});


});

});
</script>
<script>
$(function() {
  $('[data-toggle="popover"]').popover();
  
 $('#input').keyup(function(e) {
  var formData = {
            keyword:$(this).val(),
            action:'search'
        }
              $("#info").attr("hidden",true);
            $("#student").attr("hidden",true);
            $('#spinner8').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
  success: function(data) {
    $('#spinner8').html('&nbsp;<i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;');
    if (data.length > 0) {
      var i = 1;
      var html = '';
      data.forEach(function(value) {
          if (i <= 5) {
         var reg = value.reg_no;
         var names=value.fname+" "+value.lname;
        html += '<tr class="stu" id="stu" style="cursor: pointer;" data-id='+reg+'>';
        html += '<td>&nbsp;<i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;</td>';
        html += '<td style="color:black";font-weight:bold;font-size:18px;">' + reg+ '</td>';
        html += '<td>' + names+ '</td>';
        html += '</tr>';
          }
        i++;
      });
    } else {
      html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops,Student not found</td></tr>';
    }

    var popoverContent = $('<table class="table table-sm">').html(html).prop('outerHTML');

    $('#input').popover('dispose').popover({
      content: popoverContent,
      html: true,
      trigger: 'manual'
    });

    $('#input').popover('show');

  },
  error: function() {
    $('#input').popover('dispose').popover({
      content: 'Something went wrong',
      trigger: 'manual'
    });

    $('#input').popover('show');

  }
});


});

});
</script>
<script>
$(function() {
  $('[data-toggle="popover"]').popover();
  
 $('#staff1').keyup(function(e) {
  var formData = {
            keyword:$(this).val(),
            action:'search'
        }
        ;
              $("#info").attr("hidden",true);
            $("#student").attr("hidden",true);
            $('#authorsearcher').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
  success: function(data) {
    $('#authorsearcher').html('&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;');
    if (data.length > 0) {
      var i = 1;
      var html = '';
      data.forEach(function(value) {
          if (i <= 5) {
         var reg = value.staff_id;
         var names=value.staff_family_name+" "+value.staff_first_name;
        html += '<tr class="stf" id="stf" style="cursor: pointer;" data-id='+reg+'>';
        html += '<td>&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;</td>';
        html += '<td>' + reg+ '</td>';
        html += '<td>' + names+ '</td>';
        html += '</tr>';
          }
        i++;
      });
    } else {
      html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops,Student not found</td></tr>';
    }

    var popoverContent = $('<table class="table table-sm">').html(html).prop('outerHTML');

    $('#staff1').popover('dispose').popover({
      content: popoverContent,
      html: true,
      trigger: 'manual'
    });

    $('#staff1').popover('show');

  },
  error: function() {
    $('#staff1').popover('dispose').popover({
      content: 'Something went wrong',
      trigger: 'manual'
    });

    $('#staff1').popover('show');

  }
});


});

});
</script>
<script>
$(document).ready(function(){
    //chosing brower
$('#lease').on('change', function() {
    $("#info").attr("hidden",true);
        var selectedOption = $(this).val();
        if (selectedOption === 'Student') {
            $('#student1').attr('hidden', false);
            $('#staff').attr('hidden', true);
        } else if (selectedOption === 'Staff') {
            $('#student1').attr('hidden', true);
            $('#staff').attr('hidden', false);
        }else{
             $('#student1').attr('hidden', true);
             $('#staff').attr('hidden', true);
        }
    });  
     //save borrowe detail
$("#borrowdetails").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#indicatorA').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#facheck").css({
                display:'block'
            })
            $.ajax({
                url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#indicatorA').fadeOut('fast');
                    $("#facheck").css({
                        display:'block'
                    })
                    if(data.status==200){
                          $("#card_data").css({
                         display:'none'
                     });
                     $("#info").attr("hidden",true);
                     $('#searchbook').val('');
                    $('#borrowdetails')[0].reset();
                     $('#myForm')[0].reset();
                      $('#lease').val('none');
                      $('#student1').attr('hidden', true);
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
  
$("#borrowdetails1").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
               url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html('Borrow');
                    if(data.status==200){
                        $("#card_data").css({
                         display:'none'
                     });
                     $("#info").attr("hidden",true);
                     $('#searchbook').val('');
                    $('#borrowdetails')[0].reset();
                    $('#brwdbk1').load(location.href + " #brwdbk1");
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
$("#retundate1").keyup(function(e) {
  var daysToAdd = parseInt($(this).val());

  var currentDate = new Date();
  var targetDate = calculateTargetDate(currentDate, daysToAdd);

  var dayOfWeek = targetDate.getDay();
  var isWeekend = isWeekendDay(dayOfWeek);

  var formattedDate = getFormattedDate(targetDate, dayOfWeek);

  if (isWeekend) {
    $('#datertn1').html('Please choose another day. It is a weekend day.');
  } else {
    $('#datertn1').html('Return Date:'+formattedDate);
  }
});

$(document).on('click', '.bk', function() {
    var formData = {
        bk: $(this).data("id"),
        action: 'copyinfo'
    };
 var selectedName = $(this).find('td:nth-child(2)').text();
      $('#searchbook').val(selectedName);
      $('#searchbook').popover('hide');
    $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
     $('#spinnerE').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
    $.ajax({
        url: "circulation/bookissuecontroller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            $('#spinnerE').fadeOut('fast');
             $('#spinner').html("<i class='fas fa-search'></i>");
             
             $("#bookinfo").attr("hidden",false);
            //  $("#browers").attr("hidden",false);
             $("#copyid").val(data.id);
             $("#copyid1").val(data.id);
             $("#bookid").html(data.book_code_number);
            $("#bookname").html(data.title);
            $("#department").html(data.book_dep_name);
            $("#autho").html(data.author);
            $("#stts").html(data.status);
            
            // var html = '';
            // var images = data.qr_code_file;
            // html += ' <div class="image-container">';
            // html += '<img alt="image" src="/librarian/books/'+images+ '">';
            // html += '</div>';
            //  $("#qr").html(html);
             
             
            var html2 = '';
            var cover = data.image;
            html2 += '<img src="/librarian/books/'+cover+ '" alt="Book cover" style="width:200px;height:250px;">';
            
             
             $("#cover").html(html2);
             $("#card_data").css({
                 display:'block'
             })
        },
        error: function() {
            $('#spinner').html("<i class='fas fa-search'></i>");
            pop_wrong("Something went wrong!");
        }
    });
});

 $(document).on('click', '.stf', function() {
        var formData = {
            stu:$(this).data("id"),
            action:'load_info'
           
        }
        var selectedName = $(this).find('td:nth-child(3)').text();
      $('#staff1').val(selectedName);
      $('#staff1').popover('hide');
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "staffcirculation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        
                        $("#stfinf").attr("hidden",false);
                        $("#inf").attr("hidden",true);
                        $("#info").attr("hidden",false);
                        $("#student").attr("hidden",false);
                        $("#contents").attr("hidden",true);
                        $("#studentid1").val(data[0].id);
                        $("#names1").html(data[0].staff_family_name);
                        $("#reg1").html(data[0].staff_id);
                        
                        
                        //personal
                            $("#fn1").html(data[0].staff_family_name);
                            $("#ln1").html(data[0].staff_first_name);
                           
                            $("#gen1").html(data[0].staff_sex);
                            //contact
                            $("#em1").html(data[0].email);
                            $("#pn1").html(data[0].PhoneNumber);
                        //borrow inf
                            $("#cntr1").html(data[0].title);
                            $("#prov1").html(data[0].book_code);
                            $("#copy1").html(data[0].book_code_number);
                            $("#dis1").html(data[0].date_return);
                            $("#sec1").html(data[0].borrow_status);
                            
                           
    
                           
                       
                            
                        
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

    $('#browinf1').html(html);

}
 else{
                            $('#browinf1').html('<tr><td colspan="7" align="center">oops, no data found</td></tr>');
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

$(document).on('click', '.stu', function() {
    $('#spinnerD').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
    var stud=$(this).data("id");
        var formData = {
            stu:stud,
            action:'load_info'
           
        }
      var selectedName = $(this).find('td:nth-child(3)').text();
      $('#input').val(stud+" "+"[ "+selectedName+" ]");
      $('#input').popover('hide');
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "circulation/bookissuecontroller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinnerD').fadeOut('fast');
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        
                        $("#stfinf").attr("hidden",true);
                        $("#inf").attr("hidden",false);
                        $("#info").attr("hidden",false);
                        $("#student").attr("hidden",false);
                        $("#contents").attr("hidden",true);
                        $("#studentid").val(data[0].reg_no);
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
        var status = value.borrow_status;

        var badgeClass = "";
        if (status === "losted") {
        badgeClass = "badge badge-danger";
       } else if (status === "pending") {
       badgeClass = "badge badge-warning";
      } else {
       badgeClass = "badge btn-warning";
       }


        html += '<tr class="st" data-id=' + id + '>';
        html += '<th>' + i + '</th>';
        html += '<td>' + book + '</td>';
        html += '<td><div class="' + badgeClass + '">' + status + '</div></td>';
        html += '<td>' + returndate + '</td>';
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
        
