<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Staff card</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item">Registration</div>
            </div>
        </div>
        <div class="section-body">
            <form action="register" method="POST" id="register">
                <div class="card col-md-10 row" style="display:flex; flex-direction:row; justify-content:center;margin:auto;">
                    <div class=" col-md-10 col-12">
                        <div class=" card-body row">
                            <div class="form-group col-md-5 col-12">
                                <input type="text" name="staff_id" class="form-control" placeholder="Staff ID" required>
                            </div>
                            <div class="form-group col-md-5 col-12">
                                <input type="text" name="card_no" class="form-control" placeholder="Card number" required>
                            </div>
                            <div class="form-group col-md-2 col-6">
                                <button type="submit" class="btn btn-primary" id="spinner"><i class="fas fa-upload"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#register").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Card/staff/reg_card.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').html("<i class='fas fa-upload'></i>")
                    if(data.status == 200){
                        $("#register")[0].reset();
                        pop_up_success(data.message);
                    }
                    if(data.status == 401){
                        pop_info(data.message);
                    }
                },error: function(e){
                    $('#spinner').html("<i class='fas fa-upload'></i>")
                    pop_wrong("Something went wrong!");
                    console.log(e)
                }
            });
        });
    });      

   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Wrong',
        message: feedback,
        position: 'topCenter'
        });
    }
   function pop_info(feedback) {
        iziToast.info({
        title: 'Oooops',
        message: feedback,
        position: 'topCenter'
        });
    }
   function pop_up_success(feedback) {
        iziToast.success({
        title: 'done',
        message: feedback,
        position: 'topCenter'
        });
    }
</script>
