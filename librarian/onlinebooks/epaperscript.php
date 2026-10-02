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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>

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
                                url: 'onlinebooks/fetch_data_paper.php', // Replace with the actual URL of your backend script to fetch the updated content
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
    
         $('#nextspan').html("<img src='../../img/ajax_loader.gif' width='22'>").fadeIn('fast');

        // Check if the current page is the last page
        if (currentPage >= totalPages) {
            return; // Exit the function if it's the last page
        }

        var nextPage = currentPage + 1;

        // Fetch the next page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_data2_paper.php', // Replace with the actual URL of your backend script to fetch the updated content
            type: 'GET',
            data: { page: nextPage },
            success: function (response) {
                $('#nextspan').fadeOut('fast');
                // Replace the previous three cards with the new cards
                $('#cardContainer .card').slice(0, 4).remove();
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
$('#nextspan').html("<img src='../../img/ajax_loader.gif' width='22'>").fadeIn('fast');
        // Check if the current page is the first page
        if (currentPage <= 1) {
            return; // Exit the function if it's the first page
        }

        var prevPage = currentPage - 1;

        // Fetch the previous page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_prev_paper.php', // Replace with the actual URL of your backend script to fetch the updated content
            type: 'GET',
            data: { page: prevPage },
            success: function (response) {
                  $('#nextspan').fadeOut('fast');
                // Replace the next three cards with the new cards
                 $('#cardContainer .card').slice(-4).remove();
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
                    url: 'onlinebooks/search_book_paper.php',
                    type: 'POST',
                    data:formData,
                    success: function (data) {
                     $('#searched_data').html(data); 
                       $('#spinner22').html("<i class='fa fa-search'></i>");
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
                        url: 'onlinebooks/buttons_handler_paper.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            const rows = data[0][0];
                            const titleData1 = rows.title; 
                            const bk_id=rows.book_id;
                            $('#bk_title').val(titleData1);
                            
                            $('#book_id2').val(bk_id);
                            
                            var author1 = rows.author;
                            var selectElement1 = $("#author1");
                            selectElement1.val(author1);
                            
                            // Check if the option with the specified value exists
                            var existingOption = selectElement1.find("option[value='" + author1 + "']");
                            
                            if (existingOption.length > 0) {
                              // Move the existing option to the top of the list
                              existingOption.prependTo(selectElement1);
                            } else {
                              // Option not found, you may choose to handle this case
                              // For example, you can add the option if it doesn't exist
                              selectElement1.append('<option value="' + author1 + '">' + author1 + '</option>');
                            }

                            
                            var sauthor1=rows.sec_author;
                            
                            var selectElement = $("#secondauthor1");
                            selectElement.val(sauthor1);
                            selectElement.prepend(selectElement.find("option[value='" + sauthor1 + "']"));
                            
                              $('#publisher1').val(data[0][0].publisher);
                            
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
    
    
    
     $(document).on('click', '#edit_book22', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
            bookId:bookId,
            action:"edit_book"
         };
                  $.ajax({
                        url: 'onlinebooks/buttons_handler_paper.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            const rows = data[0][0];
                            const titleData1 = rows.title; 
                            const bk_id=rows.book_id;
                            $('#bk_title').val(titleData1);
                            
                            $('#book_id2').val(bk_id);
                            
                            var author1 = rows.author;
                            var selectElement1 = $("#author1");
                            selectElement1.val(author1);
                            
                            // Check if the option with the specified value exists
                            var existingOption = selectElement1.find("option[value='" + author1 + "']");
                            
                            if (existingOption.length > 0) {
                              // Move the existing option to the top of the list
                              existingOption.prependTo(selectElement1);
                            } else {
                              // Option not found, you may choose to handle this case
                              // For example, you can add the option if it doesn't exist
                              selectElement1.append('<option value="' + author1 + '">' + author1 + '</option>');
                            }

                            
                            var sauthor1=rows.sec_author;
                            
                            var selectElement = $("#secondauthor1");
                            selectElement.val(sauthor1);
                            selectElement.prepend(selectElement.find("option[value='" + sauthor1 + "']"));
                            
                              $('#publisher1').val(data[0][0].publisher);
                            
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
    
    $(document).on('click', '#edit_book33', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
            bookId:bookId,
            action:"edit_book"
         };
                  $.ajax({
                        url: 'onlinebooks/buttons_handler_paper.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            const rows = data[0][0];
                            const titleData1 = rows.title; 
                            const bk_id=rows.book_id;
                            $('#bk_title').val(titleData1);
                            
                            $('#book_id2').val(bk_id);
                            
                            var author1 = rows.author;
                            var selectElement1 = $("#author1");
                            selectElement1.val(author1);
                            
                            // Check if the option with the specified value exists
                            var existingOption = selectElement1.find("option[value='" + author1 + "']");
                            
                            if (existingOption.length > 0) {
                              // Move the existing option to the top of the list
                              existingOption.prependTo(selectElement1);
                            } else {
                              // Option not found, you may choose to handle this case
                              // For example, you can add the option if it doesn't exist
                              selectElement1.append('<option value="' + author1 + '">' + author1 + '</option>');
                            }

                            
                            var sauthor1=rows.sec_author;
                            
                            var selectElement = $("#secondauthor1");
                            selectElement.val(sauthor1);
                            selectElement.prepend(selectElement.find("option[value='" + sauthor1 + "']"));
                            
                              $('#publisher1').val(data[0][0].publisher);
                            
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
                        url: 'onlinebooks/buttons_handler_paper.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            $('#book_id_edt').val(data[0][0].book_id);
                            $('#bk_title_edit').val(data[0][0].title);
                            
                             var author2= data[0][0].author;
                            var selectElement = $("#author2");
                            selectElement.val(author2);
                            selectElement.prepend(selectElement.find("option[value='" + author2 + "']"));
                            
                            var secondauthor2= data[0][0].sec_author;
                            var selectElement = $("#secondauthor2");
                            selectElement.val(secondauthor2);
                            selectElement.prepend(selectElement.find("option[value='" + secondauthor2 + "']"));
                            
                              $('#publisher2').val(data[0][0].publisher);
                            
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
        var author2=$("#author2").val();
        var secauthor2=$("#secondauthor2").val();
        var publisher2=$("#publisher2").val();
        
        var dep_id = $('#book_dep_id_edit').val();
        var book_id2 = $('#book_id_edt').val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('author2', author2);
        formData.append('secauthor2', secauthor2);
        formData.append('publisher2', publisher2);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2_insert').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form_edit_insert');
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
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
              url: 'onlinebooks/fetch_data_paper.php', // Replace with the actual URL of your backend script to fetch the updated content
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
        var dep_id = $('#book_dep_id').val();
        var book_id2 = $('#book_id2').val();
        var author1=$("#author1").val();
        var secondauthor1=$("#secondauthor1").val();
        var publisher=$("#publisher1").val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('author1', author1);
        formData.append('secondauthor1', secondauthor1);
        formData.append('publisher', publisher);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form');
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          contentType: false,
        processData: false,
          success: function (data) {
            $('#spinner23').fadeOut('fast');
             $('#exampleModal').modal('hide');
             pop_up_success(data);
             $('#edit_book1')[0].reset();
        $('#selected_book_image_edit1').text('change image');
              $.ajax({
              url: 'onlinebooks/fetch_data_paper.php', // Replace with the actual URL of your backend script to fetch the updated content
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
          url: 'onlinebooks/buttons_handler_paper.php',
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
    
    $(document).on('click', '#publish_book22', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
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

 $(document).on('click', '#publish_book33', function(e) {
        e.preventDefault();
         var bookId = $(this).attr('value');
         var formData={
       bookId:bookId,
      action:"publish book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
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
          url: 'onlinebooks/buttons_handler_paper.php',
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
          url: 'onlinebooks/buttons_handler_paper.php',
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
         
         Swal.fire({
                    title: "Are you sure",
                    text: "You want to delete this book adrien?",
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
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data_paper.php',
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
                    }
                    
                });
         


    });
    
    $(document).on('click', '#delete_book22', function(e) {
        e.preventDefault();
         
         Swal.fire({
                    title: "Are you sure",
                    text: "You want to delete this book Mr adrien?",
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
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data_paper.php',
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
                    }
                    
                });
         


    });
    
     $(document).on('click', '#delete_book33', function(e) {
        e.preventDefault();
         
         Swal.fire({
                    title: "Are you sure",
                    text: "You want to delete this book Mr kido adrien?",
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
      action:"delete book"
   };
   $.ajax({
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data_paper.php',
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
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
            getsearch2();
             $.ajax({
                    url: 'onlinebooks/fetch_data_paper.php',
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
          url: 'onlinebooks/buttons_handler_paper.php',
          type: 'POST',
          data:formData,
          success: function (data) {
            pop_wrong("book removed successfully!");
             $.ajax({
                    url: 'onlinebooks/fetch_data_paper.php',
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
                        url: 'onlinebooks/buttons_handler_paper.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            $('#book_id_serch').val(data[0][0].book_id);
                            $('#bk_title_search').val(data[0][0].title);
                            
                            var author3= data[0][0].author;
                            var selectElement = $("#author3");
                            selectElement.val(author3);
                            selectElement.prepend(selectElement.find("option[value='" + author3 + "']"));
                            
                            var secondauthor3= data[0][0].sec_author;
                            var selectElement = $("#secondauthor3");
                            selectElement.val(secondauthor3);
                            selectElement.prepend(selectElement.find("option[value='" + secondauthor3 + "']"));
                            
                              $('#publisher3').val(data[0][0].publisher);
                              
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
        var author3=$("#author3").val();
        var secondauthor3=$("#secondauthor3").val();
        var publisher3=$("#publisher3").val();
        var dep_id = $('#book_dep_id_search').val();
        var book_id2 = $('#book_id_serch').val();
        var formData = new FormData();
        formData.append('title', title);
        formData.append('author3', author3);
        formData.append('secondauthor3', secondauthor3);
        formData.append('publisher3', publisher3);
        formData.append('dep_id', dep_id);
        formData.append('book_id2', book_id2);
        var imageFile = document.getElementById('image2_search').files[0];
        formData.append('image2', imageFile);
        formData.append('action', 'submit_form_search');
  
  // Send AJAX request
  $.ajax({
    url: 'onlinebooks/buttons_handler_paper.php',
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
        url: 'onlinebooks/fetch_data_paper.php',
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
        url: 'onlinebooks/search_book_paper.php',
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
 
 
});
</script>






  
  