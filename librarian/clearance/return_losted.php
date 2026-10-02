
<style>
    .retun,.fineclear{
        cursor:pointer;
    }
    
</style>
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
                                                   <input type="hidden" name="book_price" id="book_price">
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
                                                                class="custom-file-input image" id="image_file" required>
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
<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Books Losted Clearance</h3>
                             <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Clearance</a></div>
                                <div class="breadcrumb-item"><a href="#">Losted Book (s)</a></div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12" style="text-align:center">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2">
                                            
                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                         <div class="card">
                                        <div class="card-body">
                                            
                                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab">
                                                    
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-12 col-lg-12">
                                                                <label>Student Reg No</label><br>
                                                                <input type="text" class="form-control" name="stu" id="stu">
                                                                <button type="button" class="btn btn-primary btn-sm" id="search" style="position: absolute; top: 50%; right: 4%;"><span id="spinner0"><i class="fa fa-search"></i></span></button>
                                                            </div> 
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                         </div>
                                        </div>
                                    </div>
                                        
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12 card tracked" hidden>
                                      <div class="table-responsive ">
                                    <table class="table table-sm" >
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Reg No</th>
                                        <th scope="col">Book</th>
                                        <th scope="col">Price (Frw)</th>
                                        <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="clearance_data">
                                       
                                    </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                    </section>
                </div>
    
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    //load levels
    $("#search").on('click', function (e) {
        var formdata = {
            stu: $("#stu").val(),
            action: 'load_losted'
        }
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
      $.ajax({
            url: "clearance/load_controller.php",
            type: "POST",
            data: formdata,
            success: function(data){
             $('#spinner0').html('<i class="fa fa-search"></i>'); 
              $("#clearance_data").html(data); 
              $(".tracked").attr('hidden',false);
          }
      });
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
                url: "clearance/save_book_price.php",
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