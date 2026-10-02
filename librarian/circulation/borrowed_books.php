
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                         <form action="" id="edit_book_search" method="POST">
                        <div class="modal-header">
                             <h5 class="modal-title">Borrow Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"  id="names"></li>
                                        <li class="list-group-item"  id="reg"></li>
                                        <li class="list-group-item"  id="de"></li>
                                        <li class="list-group-item"  id="po"></li>
                                    </ul>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
               
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Borrowed Books</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Reports</a></div>
                                <div class="breadcrumb-item"><a href="#">Borrowed Books</a></div>
                            </div>
                        </div>
                        
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
                                                                <th scope="col">Reg No:</th>
                                                                <th scope="col" id="reg"></th>
                                                            </tr>
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
                                                            <tr>
                                                                <th scope="col">Book:</th>
                                                                <th scope="col" id="cntr"></th>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Book Code:</td>
                                                                <td scope="col" id="prov"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Copy Code:</td>
                                                                <td scope="col" id="copy"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Issue date:</td>
                                                                <td scope="col" id="dis"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Status:</td>
                                                                <td scope="col"><div class="badge badge-warning" id="sec"></div></td>
                                                            </tr>
                                        </thead>

                                                        </table>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                            

                        </div>
                        
                    <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            
                            <div class="col-12 col-sm-12 col-lg-12">
                            
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Borrowed Books</h4>
            
                                    </div>
                                    <div class="card-body">
                                    
                                </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="book_table" >
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Book Tilte</th>
                                                     <th scope="col">Book code</th>
                                                    <th scope="col">Issue date</th>
                                                    <th scope="col">Return date</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
<?php
    $sql = $conn->prepare("SELECT borrowdetails.*,
    book_copies.book_code_number,book_copies.bar_code_copy,
    books.title,
    books.book_code
    FROM borrowdetails 
    JOIN book_copies ON borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id
    WHERE borrowdetails.borrow_status ='pending' ");
    $sql->execute();
    $i = 1;
    while ($progs = $sql->fetch()) {
?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $progs['title'] ?></td>
        <td><?php echo $progs['book_code_number'].' [ '.$progs['bar_code_copy'].' ] '; ?></td>
        <td><?php echo $progs['issue_date']; ?></td>
        <td><?php echo $progs['date_return'] ?></td>
        <td>  
        <button type="button" data-id="<?php echo $progs['borrow_details_id']; ?>" class="btn btn-icon btn-success btn-sm edit"><span id="spinner4_<?php echo $progs['borrow_details_id']; ?>"></span>&nbsp;<i class="far fa-eye"></i>&nbsp; View</button>
        </td>
    </tr>
<?php 
    } 
?>

</tbody>
</table>
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
     $('.edit').click(function() {
        
        var dataId = $(this).data('id');
       var spinner = $('#spinner4_' + dataId);
        spinner.html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
        var formData={
         dataId:dataId,
         action:"getedata"
        };
       $.ajax({
        url: "circulation/borrowed.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            spinner.fadeOut('fast');
            var  dept_full_name= data[0].dept_full_name;
            var dep_staff=data[1].dept_full_name;
            var fnameStudent=data[0].fname;
            var lnameStudent=data[0].lname;
            var fnamestaff=data[1].staff_family_name;
            var lnamestaff=data[1].staff_first_name;
            var reg_no=data[0].reg_no;
            var staff_reg=data[1].staff_id;
            if(fnameStudent && lnameStudent){
            $("#names").html("Names : "+fnameStudent+" "+lnameStudent);    
            }
            else{
              $("#names").html("Names : "+fnamestaff+" "+lnamestaff);  
            }
            if(reg_no){
             $("#reg").html("Reg No : "+reg_no); 
             $("#po").html("Position : Student "); 
            }
            else{
             $("#reg").html("Reg No : "+staff_reg); 
             $("#po").html("Position : Staff "); 
            }
            if(dept_full_name){
            $("#de").html("Department : "+dept_full_name);
            }
            else{
             $("#de").html("Department : "+dep_staff);  
            }
            
            $('#exampleModal2').modal('show');            
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
        
