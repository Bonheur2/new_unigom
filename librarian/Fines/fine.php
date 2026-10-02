 <div class="main-content">
     <section class="section">
        <div class="section-header">
                            <h1>Fines</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Setting</a></div>
                        <div class="breadcrumb-item"><a href="#">Fines</a></div>
                            </div>
                        </div>
                    <section class="section">
                        <div class="section-body">
                                  <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Current Fine</h4>
                                </div>
                                 
                                <div class="card-body">
                                  <table class="table" id="data">
                                     <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
                                   $query=$conn->prepare("SELECT * FROM books_fine WHERE status=1");
                                   $query->execute();
                                   $row=$query->fetch();
                                   ?>  
                                    <tr>
                                        <th scope="row">1</th>
                                        <td><?php echo $row['fine'];?></td>
                                        <td><span class="badge badge-primary">Active</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                  </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                <h4>Register Fine</h4>
                                </div>
                                <form action="" id="setfine" method="POST">
                                <input type="hidden" value="saving_fine" name="action">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Set Fine</label>
                                        <div class="input-group">
                                        <input type="number" class="form-control phone-number" name="money" id="money">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-12 col-lg-5 col-md-5 col-sm-5"></div>
                                <div class="col-12 col-sm-5 col-lg-5 col-md-5">
                                <button type="submit" class="btn btn-primary"  id="fineadd">Save</span></button>
                                </div>
                                </div><br>
                                </form>
                            </div>
                        </div>
                    </div>
                                
                    </div>
               </section>
    </section>
</div>
<!--scripts start-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){
$("#setfine").submit(function(e){
    e.preventDefault();
   var formData = new FormData(this);
            $('#fineadd').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "Fines/fine_controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#data').load(location.href + " #data");
                    $("#setfine")[0].reset();
                    $('#fineadd').html('Save');
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#fineadd').html('Save');
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
<!--scripts end-->