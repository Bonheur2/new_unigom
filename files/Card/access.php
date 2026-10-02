<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css" rel="stylesheet">
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Student Access</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item">Student Access</div>
            </div>
        </div>
        <div class="section-body" style="opacity: 0; height: 0;">
            <div class="row card" style="display:flex; flex-direction:row; justify-content:center;">
                <div class=" card-body col-md-8">
                    <form method="POST" id="search">
                        <div class="input-group">
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Search by Student ID" autofocus>
                            <div class="input-group-append">                                            
                                <button type="submit" class="btn btn-primary" id="spinner"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row" id="searchResult" style="display:flex; flex-direction:row; justify-content:center;"></div>
    </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
<script>
    $(document).ready(function(){
        $("#search").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#keyword').val('');
            $('#searchResult').html("<img src='/img/ajax_loader.gif' width='50'>");
            $.ajax({
                url: "/files/Card/load_card_info_gate.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#keyword').focus();
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    $('#searchResult').html(data);
                },error: function(){
                    $('#keyword').focus();
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
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
<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>
