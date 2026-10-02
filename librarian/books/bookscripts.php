        <!-- end of Image preciew-->                        
                                   
                                   <!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<!-- Add the SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(function() {
  $('[data-toggle="popover"]').popover();
  
 $('#author').keyup(function(e) {
  var formData = {
    author: $(this).val(),
    action: 'searchAutho'
  };
  $('#authorsearcher').html("&nbsp;<img src='/img/ajax_loader.gif' width='15'>&nbsp;").fadeIn('fast');

 $.ajax({
  url: "books/book_controller.php",
  type: "POST",
  data: formData,
  dataType: "json",
  success: function(data) {
    $('#authorsearcher').html('&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;');
    if (data.length > 0) {
      var i = 1;
      var html = '';
      data.forEach(function(value) {
        var names = value.author_name;
        var id = value.id;
        html += '<tr class="stu" id="stu" data-id=' + id + ' style="cursor: pointer;">';
        html += '<td>&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;</td>';
        html += '<td>' + names + '</td>';
        html += '</tr>';
        i++;
      });
    } else {
      html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>oops,not found write full names then click</td></tr>';
      html += '<tr><td colspan="2"><button id="saveAuthorBtn" class="btn btn-primary"><span id="spinnerG"></span>Save</button></td></tr>';
    }

    var popoverContent = $('<div>').addClass('scrollable-popover').append($('<table>').addClass('table table-sm').html(html));

    $('#author').popover('dispose').popover({
      content: popoverContent,
      html: true,
      trigger: 'manual'
    });

    $('#author').popover('show');

    
    $('.stu').click(function() {
      var selectedName = $(this).find('td:nth-child(2)').text();
      $('#author').val(selectedName);
      $('#author').popover('hide');
      $('#authorsearcher').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
      
    });

    $('#saveAuthorBtn').click(function() {
   $('#spinnerG').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');    
  var formData = {
    author_name: $('#author').val(),
    action: 'save_author'
  };
$('#authorsearcher').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
  $.ajax({
    url: "books/book_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function(data) {
      if (data.status == 200) {
          $('#spinnerG').fadeOut('fast');
        $('#author').popover('hide');
        pop_up_success(data.message);
       $('#authorsearcher').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
       
      } else if (data.status == 401) {
        pop_wrong(data.message);
      } else if (data.status == 500) {
        pop_wrong(data.message);
      }
    },
    error: function() {
      $('#spinner').fadeOut('fast');
      $('#indicator').html("Save");
      $('#secondaryauthor').attr("hidden",true);
      pop_wrong("Something went wrong!");
    }
  });
});

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

// secondary Author 
 $('#secondauthor').keyup(function(e) {
 $('#authorsearcherSecondary').html("&nbsp;<img src='/img/ajax_loader.gif' width='15'>&nbsp;").fadeIn('fast');
 var formData = {
    sec_author: $(this).val(),
    action: 'searchAuthoSecondary'
  };
   $.ajax({
  url: "books/book_controller.php",
  type: "POST",
  data: formData,
  dataType: "json",
  success: function(data) {
    
    if (data.length > 0) {
        $('#authorsearcherSecondary').html('<i class="fa fa-search"></i>');
      var i = 1;
      var html = '';
      data.forEach(function(value) {
        var names = value.names;
        var id = value.auth_id;
        html += '<tr class="stu2" id="stu2" data-id=' + id + ' style="cursor: pointer;">';
        html += '<td>&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;</td>';
        html += '<td>' + names + '</td>';
        html += '</tr>';
        i++;
      });
    } else {
        $('#authorsearcherSecondary').html('<i class="fa fa-search"></i>');
      html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops,not found write full names then click</td></tr>';
      html += '<tr><td colspan="2"><button id="saveAuthorBtn2" class="btn btn-primary"><span id="spinnerF"></span>Save</button></td></tr>';
    }

    var popoverContent = $('<div>').addClass('scrollable-popover').append($('<table>').addClass('table table-sm').html(html));

    $('#secondauthor').popover('dispose').popover({
      content: popoverContent,
      html: true,
      trigger: 'manual'
    });

    $('#secondauthor').popover('show');

    
    $('.stu2').click(function() {
      var selectedName = $(this).find('td:nth-child(2)').text();
      $('#secondauthor').val(selectedName);
      $('#secondauthor').popover('hide');
      $('#authorsearcherSecondary').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
      
    });

    $('#saveAuthorBtn2').click(function() {
  var formData = {
    author_name_second: $('#secondauthor').val(),
    action: 'save_second_author'
  };
$('#spinnerF').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
  $.ajax({
    url: "books/book_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function(data) {
         $('#authorsearcherSecondary').html('<i class="fa fa-check"></i>');
      if (data.status == 200) {
        var selectedName = $('#secondauthor').val();
        $('#secondauthor').popover('hide');
       $('#spinnerF').fadeOut('fast');
       pop_up_success(data.message);
      } else if (data.status == 401) {
        pop_wrong(data.message);
      } else if (data.status == 500) {
        pop_wrong(data.message);
      }
    },
    error: function() {
      $('#spinner').fadeOut('fast');
      $('#indicator').html("Save");
      $('#secondaryauthor').attr("hidden",true);
      pop_wrong("Something went wrong!");
    }
  });
});

  },
  error: function() {
    $('#secondauthor').popover('dispose').popover({
      content: 'Something went wrong',
      trigger: 'manual'
    });

    $('#secondauthor').popover('show');
  }
});
});


});

//  end of secondary Author
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
  
$(document).ready(function(){
    
    var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

// var croppedImageData;
// $('#crop').click(function(){
// 	canvas = cropper.getCroppedCanvas({
// 		width: 400,
// 		height: 500
// 	});

// 	canvas.toBlob(function(blob){
// 		url = URL.createObjectURL(blob);
// 		var reader = new FileReader();
// 		reader.readAsDataURL(blob);
// 		reader.onloadend = function(){
// 			croppedImageData = reader.result; 
// 			$('#upload_image1').val(croppedImageData); 
// 			$modal.modal('hide');
// 		};
// 	});
// });
   $('#crop').click(function () {
                $('#crop').html("<img src='../../img/ajax_loader.gif' width='18'>").fadeIn('fast');
                canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                const fileImage = document.getElementById('upload_image');

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
                            url: 'books/up.php',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                 $('#crop').html("Crop");
                                 $modal.modal('hide');
                                $('#uploaded_image').attr('src', data);
                                $('#image_show').css({
                                    display: 'block'
                                });
                              
                            }
                        });
                    };
                });
            });
     //save location
    $("#save_location").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "books/book_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_location')[0].reset();
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
     //save dissertion
    $("#save_dissertation").submit(function(e){
            e.preventDefault();
             $('#spinnersave2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicatorsave2').html("Saving...");
            console.log('go');
            var formData = new FormData(this);
          
            $.ajax({
                url: "books/book_dissertation.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                 dataType:"JSON",
                success: function(data){
                  $('#spinnersave2').fadeOut('fast');
                    $('#indicatorsave2').html("Save");
                    if(data.status==200){
                        $('#save_dissertation')[0].reset();
                        pop_up_success(data.message);
                         }
                    if(data.status==404){
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
     //save book
    $("#save_book").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinnersave1').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicatorsave1').html("Saving...");
            $.ajax({
                url: "books/book_controller.php",
                type: "POST",
                
                // data: {image: croppedImageData},
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinnersave1').fadeOut('fast');
                    $('#indicatorsave1').html("Save");
                    if(data.status==200){
                        $('#save_book')[0].reset();
                        pop_up_success(data.message);
                         $('#upload_image').val('');
                                $('#selected_book_image').text('Book Cover'); 
                                $('#image_show').css({
                                    display: 'none'
                                });
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinnersave1').fadeOut('fast');
                    $('#indicatorsave1').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
          
          
           //save Copyies
$("#save_copy").submit(function(e) {
  e.preventDefault();
var book_codes=$("#book_codes").val();
var selectedOption = $('#b_location').find('option:selected');
var locationName = selectedOption.text().split('|')[1].trim();
var selectedOption2 = $('#b_tp').find('option:selected');
var bookName = selectedOption2.text().split('|')[1].trim();
var styledText = 'Do you want to save? copy of '+ '<span style="font-size: 20px; color: #6D071A;">'+bookName +'</span>'+ ' In ' + '<span style="font-size: 18px; color: #6D071A;">' + locationName + '</span>' +' Block ' ;
  // Display confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    html: styledText,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No"
  }).then((result) => {
    if (result.isConfirmed) {
      // User clicked "Yes," proceed with form submission
    $('#spinnerH').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
      var formData = new FormData(this);
      
      $.ajax({
        url: "books/book_controller.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(data) {
           $('#spinnerH').fadeOut('fast');
          if (data.status === "200") {
            pop_up_success(data.message);
          } else if (data.status === "500") {
            pop_wrong(data.message);
          }
           else if (data.status === "400") {
            pop_wrong(data.message);
          }
        },
        error: function() {
          $('#spinner').fadeOut('fast');
          $('#indicator').html("Save");
          pop_wrong("Something went wrong!");
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
  <script>
 $(document).on('click', function(event) {
  var popover = $('#secondauthor').data('bs.popover');
  var popover2=$('#author').data('bs.popover');
  if (!$(event.target).closest('.popover').length &&
      !$(event.target).is('#secondauthor') &&
      popover && $('#secondauthor').is(':visible')) {
    $('#secondauthor').popover('hide');
  }
  if (!$(event.target).closest('.popover').length &&
      !$(event.target).is('#author') &&
      popover2 && $('#author').is(':visible')) {
    $('#author').popover('hide');
  }
});
  </script> 
  
  
  
  <script>
          $(document).ready(function () {
          const fileImage = document.getElementById('upload_image');

            // Get the label element
            const selectedBookLabelImage = document.getElementById('selected_book_image');

            // Add an event listener to the file input
            fileImage.addEventListener('change', function (event) {
                // Get the selected file name
                const fileNameImage = event.target.files[0].name;

                // Update the label text with the selected file name
                selectedBookLabelImage.innerText = fileNameImage;
            });    
          });
  </script>