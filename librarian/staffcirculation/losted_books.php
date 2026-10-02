
               
               
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Losted Books</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Staff circulation</a></div>
                                <div class="breadcrumb-item"><a href="#">Losted Books</a></div>
                            </div>
                        </div>
                        
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
                                                                <th scope="col">Staff ID:</th>
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
                                                                <td scope="col">Reported date:</td>
                                                                <td scope="col" id="dis"></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="col">Status:</td>
                                                                <td scope="col"><div class="badge badge-danger" id="sec"></div></td>
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
                                        <h4>Losted Books</h4>
            
                                    </div>
                                    <div class="card-body">
                                    
                                </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="book_table" >
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Book Tilte/Book Code</th>
                                                    <th scope="col">Issue date</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
<?php
    $sql = $conn->prepare("SELECT  tbl_staff_borrowdetails.borrow_status, 
    tbl_staff_borrowdetails.date_return, 
    tbl_staff_borrowdetails.issue_date,
    tbl_staff.staff_family_name,
    tbl_staff.PhoneNumber,
    tbl_staff.staff_id,
    book_copies.book_code_number,
    books.title,
    books.book_code
    FROM  tbl_staff_borrowdetails 
    JOIN tbl_staff ON  tbl_staff_borrowdetails.borrow_id =tbl_staff.id 
    JOIN book_copies ON  tbl_staff_borrowdetails.book_id = book_copies.id 
    JOIN books ON book_copies.book_id=books.book_id
    WHERE  tbl_staff_borrowdetails.borrow_status ='losted'");
    $sql->execute();
    $i = 1;
    while ($progs = $sql->fetch()) {
?>
    <tr class="stu" data-id='<?php echo $progs['staff_id']; ?>'>
        <input type="hidden"  value='<?php echo $progs['book_code_number']; ?>' name="bkid">
        <td><?php echo $i++; ?></td>
        <td><?php echo $progs['title']." [".$progs['book_code_number']."]"; ?></td>
        <td><?php echo $progs['issue_date']; ?></td>
        <td><div class="<?php echo ($progs['borrow_status'] == 'losted') ? 'badge badge-danger' : 'badge badge-success'; ?>"><?php echo $progs['borrow_status']; ?></div></td>
        <td>  
         <form>
            <div class="buttons row">
              <button type="button" data-id="<?php echo $progs['id']; ?>" class="btn btn-icon btn-success btn-sm edit"><span id="spinner4_<?php echo $progs['id']; ?>"></span>&nbsp;<i class="far fa-eye"></i>&nbsp; View</button>
            </div>
         </form>
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
    
     $('#book_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    
     
   
     $(document).on('click', '.stu', function() {
        var bkid = $(this).closest('.stu').find('input[name="bkid"]').val();
        var formData = {
            stu:$(this).data("id"),
            action:'load_lstd_info',
            bkid:bkid
           
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
                        $("#info").attr("hidden",false);
                     $("#studentid").val(data[0].id);
                        $("#names").html(data[0].staff_family_name);
                        $("#reg").html(data[0].staff_id);
                        
                        
                        //personal
                            $("#fn").html(data[0].staff_family_name);
                            $("#ln").html(data[0].staff_first_name);
                           
                            $("#gen").html(data[0].staff_sex);
                            //contact
                            $("#em").html(data[0].email);
                            $("#pn").html(data[0].PhoneNumber)
    
                           
                       
                            
                        
                            var status='0';
                                if(data[0].is_acadmic==1){
                                   status="No"; 
                                }
                                else{
                                    status="Yes";
                                }
                            $("#stt").html(status);
                       //book info
                     
                            $("#cntr").html(data[0].title);
                            $("#prov").html(data[0].book_code);
                            $("#copy").html(data[0].book_code_number);
                            $("#dis").html(data[0].issue_date);
                            $("#sec").html(data[0].borrow_status);
                        
                        
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
        
