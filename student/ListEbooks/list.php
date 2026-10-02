<style>

    .image_area {
        position: relative;
    }

    img {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
     .preview11 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
.preview12 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
    .preview13 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 800px !important;
    }

    .overlay {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.5);
        overflow: hidden;
        height: 0;
        transition: .5s ease;
        width: 100%;
    }

    .image_area:hover .overlay {
        height: 50%;
        cursor: pointer;
    }

    .text {
        color: #333;
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
</style>
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                         <form action="" id="view_book" method="POST">
                        <div class="modal-header">
                             <h5 class="modal-title">Book Details</h5>
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
               
               <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                       <form action="" id="edit_book_search" method="POST">
                           <input type="hidden" name="action" value="update_data">
                        <div class="modal-header">
                             <h5 class="modal-title">Update Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            <label>Book Title</label>
                            <input type="text" class="form-control" name="bk_title"  id="bk_title">
                            </div>
                            <div class="form-group">
                                                    <label>Author</label>
                                         <input type="text"  class="form-control" name="author" id="author">
                                </div>  
                                <div class="form-group">
                                                 <label>Second Author</label>
                                                   <input type="text" class="form-control" name="sec_author" id="sec_author">
                                                </select>
                                         </div>
                                         
                                          <div class="form-group">
                                                <label>ISBN</label>
                                                 <input type="text"  class="form-control" name="isbn" id="isbn">
                                                </select>
                                         </div>
                                         <div class="form-group">
                                            <label>publisher</label>
                                            <input type="text" class="form-control" id="publisher" name="publisher">
                                        </div>
                                     <div class="form-group">
                                        <label>Program</label>
                                    <select class="custom-select" id="book_dep" name="book_dep">
                               </select>
                            </div>
                        </div>
                        <input type="hidden" id="book_id" name="book_id">
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><span id="spinner24"></span>Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                        <h1>E-Books (For Educational Purposes Only) </h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">E-Books</a></div>
                                        <div class="breadcrumb-item"><a href="#">View Book</a></div>
                                         </div>
                                    </div>
                                    
                                    <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                               <h4>All E-Books</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="listEbook">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>ISBN </th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>  
                                        
                                    <?php
                                    $sql = $conn->prepare("SELECT books_online.*,tbl_book_program.dept_full_name FROM  books_online
                                    INNER JOIN tbl_book_program ON books_online.department=tbl_book_program.dept_id
                                    
                                    WHERE books_online.type =1 and books_online.status=1");
                                    $sql->execute();
                                    $i = 1;
                                    while ($progs = $sql->fetch()) {
                                ?>
                                   <tr>
                                          <td><?php echo $i++; ?></td>
                                          <td>
                                            <a href="onlinebooks/files/<?php echo $progs['book_file']; ?>" target="_blank" style="text-decoration: none;">
                                              <?php echo $progs['title'] ?>
                                              <div class="table-links">
                                                <a href="#">Author: <?php echo $progs['author'] ?></a>
                                                <div class="bullet"></div>
                                                <a href="#">Department: <?php echo $progs['dept_full_name'] ?></a>
                                              </div>
                                            </a>
                                          </td>
                                          <td><?php echo $progs['isbn'] ?></td>
                                          <td>
                                              <div class="dropdown d-inline mr-2">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="" id="edit_book" value="<?php echo $progs['book_id']; ?>">Edit</a>
                                                    <a class="dropdown-item" href="" id="publish_book" value="<?php echo $progs['book_id']; ?>">Publish</a>
                                                    <a class="dropdown-item" href="" id="delete_book" value="<?php echo $progs['book_id']; ?>">Delete</a>
                                                </div>
                                            </div>
                                            
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
                    </div>
                </div>
                
                
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    
     $('#listEbook').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    
     
   
     $(document).on('click', '#edit_book', function(e) {
         e.preventDefault();
           var value = $(this).attr('value');
           var formData={
               value:value,
               action:"viewEdit"
           };
            $.ajax({
                        url: 'ListEbooks/list_controller.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            const rows = data[0][0];
                            const titleData1 = rows.title; 
                            const bk_id=rows.book_id;
                            $('#bk_title').val(titleData1);
                            
                            $('#book_id').val(bk_id);
                            
                            var author1 = rows.author;
                            
                              $('#author').val(author1);
                            
                            var sauthor1=rows.sec_author;
                              $('#sec_author').val(sauthor1);
                            var isbn=rows.isbn;
                            $("#isbn").val(isbn);
                            
                            var publisher = rows.publisher;
                              $('#publisher').val(publisher);
                            
                              $("#book_dep").empty(); 
                            $.each(data[1], function(index, value) {
                                var option = new Option(value.dept_full_name, value.dept_id);
                                $("#book_dep").append(option);
                            });
                             $("#book_dep").val(data[0][0].department)
                     $("#book_dep option[value='" + data[0][0].department + "']").prop("selected", true);
                    var selectedOption1 = $("#book_dep option[value='" + data[0][0].department + "']");
                    $("#book_dep").prepend(selectedOption1); // Assuming you want to set the selected value to the department of the book
                            $('#exampleModal').modal('show');
                        },
                        error: function() {
                            console.log('An error occurred while processing the AJAX request.');
                        }
                    }); 
     });     
     
 $("#edit_book_search").submit(function (e) {
     e.preventDefault();
     $('#spinner24').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
      var formData = new FormData(this);
   $.ajax({
         url: 'ListEbooks/list_controller.php',
          type: 'POST',
          data:formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function (data) {
            $('#spinner24').fadeOut('fast');
            if(data.status==200){
                $('#listEbook').load(location.href + " #listEbook");
               pop_up_success(data.message);
                        $('#exampleModal').modal('hide');
                    }
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                      },
             error: function () {
                     pop_wrong("Something went wrong !");  
                    }
                    
     });
}); 
      
      
    $(document).on('click', '#publish_book', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish_book"
   };
   $.ajax({
           url: 'ListEbooks/list_controller.php',
          type: 'POST',
          data:formData,
          dataType: 'json',
          success: function (data) {
               if(data.status==200){
               pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
             },
             error: function () {
                   pop_wrong("Something went wrong !");        
                    }
     });
     });
      
      
      $(document).on('click', '#delete_book', function(e) {
        e.preventDefault();
        Swal.fire({
                    title: "Are you sure",
                    text: "You want to delete this ?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User clicked "Yes," proceed with form submission
          var bookId = $(this).attr('value');
           var formData={
       bookId:bookId,
      action:"delete_book"
   };
   $.ajax({
         url: 'ListEbooks/list_controller.php',
          type: 'POST',
          data:formData,
          dataType: 'json',
          success: function (data) {
           if(data.status==200){
                $('#listEbook').load(location.href + " #listEbook");
               pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
               },
             error: function () {
                       pop_wrong("Something went wrong !");     
                    }
                     });
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
        
