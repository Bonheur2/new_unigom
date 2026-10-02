<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Crop Image Before Upload</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="img-container">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <img src="" id="sample_image" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="preview"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="crop" class="btn btn-primary"><span
                                                    id="spinner21"></span>&nbsp;Crop</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<div class="main-content">
     <section class="section">
        <div class="section-header">
                            <h1>E-Books (For Educational Purposes Only) </h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">E-Books & Paper</a></div>
                        <div class="breadcrumb-item"><a href="#">E-book Add</a></div>
                            </div>
                        </div>
                    <section class="section">
                        <div class="section-body">
                                  <div class="row">

<div class="col-lg-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-body">  <!--add book-->
                                <div class="card">
                                        <!--Add book-->
                                        <div class="card-body row">
                                            <form id="save_online_book" action="" method="POST">
                                                <div class="card-body pb-0 row">

                                                    <div class="form-group col-sm-12 col-lg-3 col-md-3" id="depa">
                                                        <label>Department (*)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            </div>
                                                            <select class="form-control select2" style="width:100%;"
                                                                name="d_name" id="d_name" required>
                                                                <option>-----choose one-----</option>
                                                                <?php 
                                             $stmt = $conn->prepare("SELECT * FROM tbl_books_depart WHERE status=1");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                                                <option value=" <?php echo $row['book_id']; ?>">
                                                                    <?php echo $row['book_dep_name']; ?>
                                                                </option>
                                                                <?php
                                                                        
                                                                    }
                                                                }
                                                                else{
                                                                  ?>
                                                                <option value='-1'>No department found</option>
                                                               <?php }
                                                            ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12 col-lg-3 col-md-3" id="title_book">
                                                        <label>Book title (*)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fa fa-font"
                                                                        aria-hidden="true"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="b_title"
                                                                id="b_title" placeholder="Enter Book Title">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-sm-12 col-lg-3 col-md-3" id="bk_file" >
                                                        <label>Select Book</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="book_file"
                                                                class="custom-file-input image" id="book_file"
                                                                accept=".pdf">
                                                            <label class="custom-file-label"
                                                                id="selected_book">Book</label>
                                                        </div>
                                                    </div>
                                                     <div class="form-group col-sm-12 col-lg-3 col-md-3" id="isbn_book">
                                                          <label>ISBN</label>
                                                    <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fa fa-font"
                                                                        aria-hidden="true"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="isbn"
                                                                id="isbn" placeholder="Enter ISBN">
                                                        </div>
                                                      </div>    
                                                    <div class="form-group col-sm-12 col-lg-3 col-md-3" id="bk_image" >
                                                        <label>Choose Book Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image_file"
                                                                class="custom-file-input image" id="image_file"
                                                               >
                                                            <label class="custom-file-label"
                                                                id="selected_book_image">Book image</label>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="action" id="action" value="save_E_book">
                                                    <div class="form-group col-sm-12 col-lg-12 col-md-12"
                                                        id="image_show" style="display:none">
                                                        <div class="custom-file">
                                                            <img src="" id="uploaded_image" name="uploaded_image"
                                                                class="img-responsive img-circle"
                                                                style="width:80px;height:80px;" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-1 col-lg-1">
                                                        <label>&nbsp;</label>
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-primary"><span
                                                                    id="spinner20"></span>&nbsp;<span
                                                                    id="indicatorsave1">Save</span></button>
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
                       </div>
                    </section>
                </section>
            </div>
            
            
              <!-- end of Image preciew-->

    <!--javascript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
    <script src="https://unpkg.com/dropzone"></script>
    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            const fileInput = document.getElementById('book_file');

            // Get the label element
            const selectedBookLabel = document.getElementById('selected_book');

            // Add an event listener to the file input
            fileInput.addEventListener('change', function (event) {
                // Get the selected file name
                const fileName = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabel.innerText = fileName;
            });
            const fileImage = document.getElementById('image_file');

            // Get the label element
            const selectedBookLabelImage = document.getElementById('selected_book_image');

            // Add an event listener to the file input
            fileImage.addEventListener('change', function (event) {
                // Get the selected file name
                const fileNameImage = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabelImage.innerText = fileNameImage;
            });
            $("#save_online_book").submit(function (e) {
                e.preventDefault();
                $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "onlinebooks/books_master.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        $('#spinner20').fadeOut('fast');
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            $('#save_online_book')[0].reset();
                                $('#book_file').val('');
                                $('#selected_book').text('Book');
                               $('#image_file').val('');
                                $('#selected_book_image').text('Book image'); 
                                $('#image_show').css({
                                    display: 'none'
                                });

                            // Refresh the content of the 'home' tab using AJAX
                            $.ajax({
                                url: 'onlinebooks/fetch_data.php', // Replace with the actual URL of your backend script to fetch the updated content
                                type: 'GET',
                                success: function (response) {
                                    $('#home').html(response);
                                },
                                error: function () {
                                    console.log('An error occurred while refreshing the home tab content.');
                                }
                            });
                        }
                        if (data.status == 401) {
                            pop_wrong(data.message);
                        }
                        if (data.status == 500) {
                            pop_wrong(data.message);
                        }

                    }, error: function () {
                        $('#spinner20').fadeOut('fast');
                        pop_wrong("Something went wrong!");

                    }

                });
            });


            var $modal = $('#modal');

            var image = document.getElementById('sample_image');

            var cropper;

            $('#image_file').change(function (event) {
                var files = event.target.files;

                var done = function (url) {
                    image.src = url;
                    $modal.modal('show');
                };

                if (files && files.length > 0) {
                    reader = new FileReader();
                    reader.onload = function (event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 4,
                    preview: '.preview'
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $('#crop').click(function () {
                $('#spinner21').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                const fileImage = document.getElementById('image_file');

                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);

                        $.ajax({
                            url: 'onlinebooks/up.php',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                $modal.modal('hide');
                                $('#uploaded_image').attr('src', data);
                                $('#image_show').css({
                                    display: 'block'
                                });
                                $('#spinner21').fadeOut('fast');
                            }
                        });
                    };
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
    <script>
      $(document).ready(function () {
    var currentPage = <?php echo $currentpage; ?>;
    var totalPages = <?php echo $totalPages; ?>;

    // Handle next button click event
    $('#nextLink').on('click', function (e) {
        e.preventDefault();

        // Check if the current page is the last page
        if (currentPage >= totalPages) {
            return; // Exit the function if it's the last page
        }

        var nextPage = currentPage + 1;

        // Fetch the next page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_data2.php', // Replace with the actual URL of your backend script to fetch the updated content
            type: 'GET',
            data: { page: nextPage },
            success: function (response) {
                // Replace the previous three cards with the new cards
                $('#cardContainer .card').slice(0, 3).remove();
                $('#cardContainer').append(response);

                currentPage = nextPage;
            },
            error: function () {
                console.log('An error occurred while fetching the next page.');
            }
        });
    });

    // Handle previous button click event
    $('#prevLink').on('click', function (e) {
        e.preventDefault();

        // Check if the current page is the first page
        if (currentPage <= 1) {
            return; // Exit the function if it's the first page
        }

        var prevPage = currentPage - 1;

        // Fetch the previous page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_data2.php', // Replace with the actual URL of your backend script to fetch the updated content
            type: 'GET',
            data: { page: prevPage },
            success: function (response) {
                // Replace the next three cards with the new cards
                $('#cardContainer .card').slice(-3).remove();
                $('#cardContainer').prepend(response);

                currentPage = prevPage;
            },
            error: function () {
                console.log('An error occurred while fetching the previous page.');
            }
        });
    });
});


    </script>
    <!--search events-->
    <script>
    $(document).ready(function() {
        getsearch();
    });
   function getsearch(){
   document.getElementById('search_book').addEventListener('input', function(event) {
        $('#spinner22').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
        var enteredValue = event.target.value;
        var formData={
            book_name:enteredValue,
            action:"search_book"
        };
         $.ajax({
                    url: 'onlinebooks/search_book.php',
                    type: 'POST',
                    data:formData,
                    success: function (data) {
                     $('#searched_data').html(data); 
                       $('#spinner22').fadeOut('fast');
                    },
                    error: function () {
                        console.log('An error occurred while fetching the previous page.');
                    }
                });
    });    
   } 
  
</script>
<!--button click events-->
<script>
   $(document).ready(function() {
    $(document).on('click', '#edit_book', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
            bookId:bookId,
            action:"edit_book"
         };
                  $.ajax({
                        url: 'onlinebooks/buttons_handler.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            $('#book_id2').val(data[0][0].book_id);
                            $('#bk_title').val(data[0][0].title);
                            $('#bk_isbn').val(data[0][0].isbn);
                              $("#book_dep_id").empty(); 
                            $.each(data[1], function(index, value) {
                                var option = new Option(value.dept_full_name, value.dept_id);
                                $("#book_dep_id").append(option);
                            });
                             $("#book_dep_id").val(data[0][0].department)
                     $("#book_dep_id option[value='" + data[0][0].department + "']").prop("selected", true);
                    var selectedOption1 = $("#book_dep_id option[value='" + data[0][0].department + "']");
                    $("#book_dep_id").prepend(selectedOption1); // Assuming you want to set the selected value to the department of the book
                            $('#exampleModal').modal('show');
                        },
                        error: function() {
                            console.log('An error occurred while processing the AJAX request.');
                        }
                    });


    });
    $(document).on('click', '#edit_book4', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
            bookId:bookId,
            action:"edit_book_insert"
         };
                  $.ajax({
                        url: 'onlinebooks/buttons_handler.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            $('#book_id_edt').val(data[0][0].book_id);
                            $('#bk_title_edit').val(data[0][0].title);
                            $('#bk_title_edit_isbn').val(data[0][0].isbn);
                              $("#book_dep_id_edit").empty(); 
                            $.each(data[1], function(index, value) {
                                var option = new Option(value.dept_full_name, value.dept_id);
                                $("#book_dep_id_edit").append(option);
                            });
                             $("#book_dep_id_edit").val(data[0][0].department)
                     $("#book_dep_id_edit option[value='" + data[0][0].department + "']").prop("selected", true);
                    var selectedOption1 = $("#book_dep_id_edit option[value='" + data[0][0].department + "']");
                    $("#book_dep_id_edit").prepend(selectedOption1); // Assuming you want to set the selected value to the department of the book
                            $('#exampleModal4').modal('show');
                        },
                        error: function() {
                            console.log('An error occurred while processing the AJAX request.');
                        }
                    });


    });
        $("#edit_book_after_insert").submit(function (e) {
     e.preventDefault();
     $('#spinner25').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    var title = $('#bk_title_edit').val();
        var isbn = $('#bk_title_edit_isbn').val();
        var dep_id = $('#book_dep_id_edit').val();
        var book_id2 = $('#book_id_edt').val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('isbn', isbn);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2_insert').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form_edit_insert');
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          processData: false,
          contentType: false,
          success: function (data) {
            $('#spinner25').fadeOut('fast');
             $('#exampleModal4').modal('hide');
             pop_up_success(data);
             $('#edit_book_after_insert')[0].reset();
        $('#image2_insert').val('');
        $('#selected_book_image_edit1_insert').text('change image');
              $.ajax({
              url: 'onlinebooks/fetch_data.php', // Replace with the actual URL of your backend script to fetch the updated content
              type: 'GET',
                success: function (response) {
                $('#home').html(response);
                  },
                 error: function () {
                 console.log('An error occurred while refreshing the home tab content.');
                 }
                });
             },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });
}); 
     $("#edit_book1").submit(function (e) {
     e.preventDefault();
     $('#spinner23').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
      var title = $('#bk_title').val();
        var isbn = $('#bk_isbn').val();
        var dep_id = $('#book_dep_id').val();
        var book_id2 = $('#book_id2').val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('isbn', isbn);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form');
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          contentType: false,
        processData: false,
          success: function (data) {
            $('#spinner23').fadeOut('fast');
             $('#exampleModal').modal('hide');
             pop_up_success(data);
             $('#edit_book1')[0].reset();
        $('#image2').val('');
        $('#selected_book_image_edit1').text('change image');
              $.ajax({
              url: 'onlinebooks/fetch_data.php', // Replace with the actual URL of your backend script to fetch the updated content
              type: 'GET',
                success: function (response) {
                $('#home').html(response);
                  },
                 error: function () {
                 console.log('An error occurred while refreshing the home tab content.');
                 }
                });
             },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });
}); 

$(document).on('click', '#publish_book', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
             pop_up_success(data);
             },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });


    });

$(document).on('click', '#publish_book2', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
             pop_up_success(data);
             },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });
  });
  $(document).on('click', '#publish_book4', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish_book_insert"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
             pop_up_success(data);
             },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });


    });
$(document).on('click', '#delete_book', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data.php',
                    type: 'GET',
                    success: function (response) {
                      $('#home').html(response);
                     },
                    error: function () {
                      console.log('An error occurred while refreshing the home tab content.');
                    }
                  });
               },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });


    });
    $(document).on('click', '#delete_book2', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
            getsearch2();
             $.ajax({
                    url: 'onlinebooks/fetch_data.php',
                    type: 'GET',
                    success: function (response) {
                      $('#home').html(response);
                    },
                    error: function () {
                      console.log('An error occurred while refreshing the home tab content.');
                    }
                  });
               },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });


    });
    $(document).on('click', '#delete_book4', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data.php',
                    type: 'GET',
                    success: function (response) {
                      $('#home').html(response);
                      getsearch2(); // Call the function after successful content refresh
                    },
                    error: function () {
                      console.log('An error occurred while refreshing the home tab content.');
                    }
                  });
               },
             error: function () {
                        console.log('An error occurred while updating data.');
                    }
     });


    });
});
</script>
<!--click events handlers for search -->
<script>
   $(document).ready(function() {
    $(document).on('click', '#edit_book2', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
            bookId:bookId,
            action:"edit_book_search"
         };
                  $.ajax({
                        url: 'onlinebooks/buttons_handler.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            $('#book_id_serch').val(data[0][0].book_id);
                            $('#bk_title_search').val(data[0][0].title);
                            $('#bk_title_isbn').val(data[0][0].isbn);
                              $("#book_dep_id_search").empty(); 
                            $.each(data[1], function(index, value) {
                                var option = new Option(value.dept_full_name, value.dept_id);
                                $("#book_dep_id_search").append(option);
                            });
                             $("#book_dep_id_search").val(data[0][0].department)
                     $("#book_dep_id_search option[value='" + data[0][0].department + "']").prop("selected", true);
                    var selectedOption1 = $("#book_dep_id_search option[value='" + data[0][0].department + "']");
                    $("#book_dep_id_search").prepend(selectedOption1); // Assuming you want to set the selected value to the department of the book
                            $('#exampleModal2').modal('show');
                        },
                        error: function() {
                            console.log('An error occurred while processing the AJAX request.');
                        }
                    });
    });  
  $("#edit_book_search").submit(function (e) {
  e.preventDefault();
  $('#spinner24').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
  
  // Get form data
        var title = $('#bk_title_search').val();
        var isbn = $('#bk_title_isbn').val();
        var dep_id = $('#book_dep_id_search').val();
        var book_id2 = $('#book_id_serch').val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('isbn', isbn);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2_search').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form_search');
  
  // Send AJAX request
  $.ajax({
    url: 'onlinebooks/buttons_handler.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      $('#spinner24').fadeOut('fast');
      $('#exampleModal2').modal('hide');
      pop_up_success(data);
     $('#edit_book_search')[0].reset();
        $('#image2_search').val('');
        $('#selected_book_image_edit1_search').text('change image');
      // Refresh the home tab content
      $.ajax({
        url: 'onlinebooks/fetch_data.php',
        type: 'GET',
        success: function (response) {
          $('#home').html(response);
          getsearch2(); // Call the function after successful content refresh
        },
        error: function () {
          console.log('An error occurred while refreshing the home tab content.');
        }
      });
    },
    error: function () {
      console.log('An error occurred while updating data.');
    }
  });
});

});
  function getsearch2() {
    var enteredValue = $('#search_book').val();

    $('#spinner22').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
    var formData = {
        book_name: enteredValue,
        action: "search_book"
    };

    $.ajax({
        url: 'onlinebooks/search_book.php',
        type: 'POST',
        data: formData,
        success: function (data) {
            $('#searched_data').html(data);
            $('#spinner22').fadeOut('fast');
        },
        error: function () {
            console.log('An error occurred while fetching the previous page.');
        }
    });
}
</script>
<!--input field script-->
<script>
$(document).ready(function(){
 $(document).on('change', '#depa', function(e) {
    e.preventDefault();
    $('#title_book').css({
        display: 'block'
    });
    $('#bk_file').css({
        display:'block'
    })
    $('#bk_image').css({
        display:'block'
    })
    $('#isbn_book').css({
        display:'block'
    })
    
});
});
    
</script>
<script>
 $(document).ready(function() {
      const fileImage2 = document.getElementById('image2');

            // Get the label element
            const selectedBookLabelImage2 = document.getElementById('selected_book_image_edit1');

            // Add an event listener to the file input
            fileImage2.addEventListener('change', function (event) {
                // Get the selected file name
                const fileNameImage2 = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabelImage2.innerText = fileNameImage2;
            });
  var $modal = $('#modal11');

            var image = document.getElementById('sample_image11');

            var cropper;

            $('#image2').change(function (event) {
                var files = event.target.files;

                var done = function (url) {
                    image.src = url;
                    $modal.modal('show');
                };

                if (files && files.length > 0) {
                    reader = new FileReader();
                    reader.onload = function (event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 4,
                    preview: '.preview11'
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $('#crop11').click(function () {
                $('#spinner26').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                const fileImage = document.getElementById('image2');

                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"edit_first_image");

                        $.ajax({
                            url: 'onlinebooks/up_image.php',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                $modal.modal('hide');
                               $('#spinner26').fadeOut('fast');
                            }
                        });
                    };
                });
            });
});
 
</script>

<script>
 $(document).ready(function() {
     const fileImage3 = document.getElementById('image2_search');

            // Get the label element
            const selectedBookLabelImage3 = document.getElementById('selected_book_image_edit1_search');

            // Add an event listener to the file input
            fileImage3.addEventListener('change', function (event) {
                // Get the selected file name
                const fileNameImage3 = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabelImage3.innerText = fileNameImage3;
            });
  var $modal = $('#modal12');

            var image = document.getElementById('sample_image12');

            var cropper;

            $('#image2_search').change(function (event) {
                var files = event.target.files;

                var done = function (url) {
                    image.src = url;
                    $modal.modal('show');
                };

                if (files && files.length > 0) {
                    reader = new FileReader();
                    reader.onload = function (event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 4,
                    preview: '.preview12'
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $('#crop12').click(function () {
                $('#spinner27').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                const fileImage = document.getElementById('image2_search');

                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"edit_search_image");

                        $.ajax({
                            url: 'onlinebooks/up_image.php',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                $modal.modal('hide');
                              $('#spinner27').fadeOut('fast');
                            }
                        });
                    };
                });
            });         
});
 
</script>

<script>
 $(document).ready(function() {
     const fileImage4 = document.getElementById('image2_insert');

            // Get the label element
            const selectedBookLabelImage4 = document.getElementById('selected_book_image_edit1_insert');

            // Add an event listener to the file input
            fileImage4.addEventListener('change', function (event) {
                // Get the selected file name
                const fileNameImage4 = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabelImage4.innerText = fileNameImage4;
            });
            
             var $modal = $('#modal13');

            var image = document.getElementById('sample_image13');

            var cropper;

            $('#image2_insert').change(function (event) {
                var files = event.target.files;

                var done = function (url) {
                    image.src = url;
                    $modal.modal('show');
                };

                if (files && files.length > 0) {
                    reader = new FileReader();
                    reader.onload = function (event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 4,
                    preview: '.preview13'
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $('#crop13').click(function () {
                $('#spinner28').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                const fileImage = document.getElementById('image2_insert');

                canvas.toBlob(function (blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"edit_any_action_image");

                        $.ajax({
                            url: 'onlinebooks/up_image.php',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                $modal.modal('hide');
                              $('#spinner28').fadeOut('fast');
                            }
                        });
                    };
                });
            });         
      
});
 
</script>