 <style>
     .stu tr {
    cursor: pointer;
}
 </style>
 <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1><?php  echo $title ?></h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Promote By One</div>
                    </div>
                    
                  
                </div>
               <div class="section-body">
                 
                <div class="invoice">
                    <div class="invoice-print">
                          <div class="row">
                               <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                      <!--<div class="float-right">-->
                            <form action="">
                                    <div class="input-group">
                                    <input type="text" id="input" class="form-control" placeholder="Search by name Or RegNumber" value="<?php echo $_REQUEST['regno'] ?>">
                                    <div class="input-group-append">                                            
                                    <button type="button"class="btn btn-primary" id="spinner"><i class="fas fa-search"></i></button>
                                    </div>
                                    </div>
                            </form>
                                    <!--</div>-->
                                    
                                    <br>
                                          <div class="card"  id="tableResult">
                                <div class="card-body">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="contents">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                    </div>
                                    <div class="col-lg-2"></div>
                                          <br><br>
                                   
                                      <div class="col-lg-12">
                                           <div id="contentsAll">
                                          <?php include'StuResult.php'; ?>
                                 </div>
                                  </div>
                                
                                  </div>
                            
                   
                </div>
            </div>
            </section>
        </div>
         <?php  include'chooseLevel.php';  ?>
       
<script>
$(document).ready(function(){
    
    $('#tableResult').hide();
    $("#input").keyup(function(e){
         $('#tableResult').show();
          $('#contentsAll').hide();
        var formData = {
            keyword:$(this).val(),
            action:'search'
        }
            $("#info").attr("hidden",true);
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var reg = value.reg_no;
                            var names=value.fname+" "+value.lname;
                            html += '<tr style=" cursor: pointer;" class="stu" data-id='+reg+'>';
                            html += '<th>' + i+ '</th>';
                            html += '<th>' + reg+ '</th>';
                            html += '<td>' + names+ '</td>';
                            html += '</tr>';
                            i++;
                        });
                        $('#contents').html(html);
                    } else{
                        $('#contents').html('<tr><td colspan="3" align="center"> no data found</td></tr>');
                    }
                },error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    pop_wrong("Something went wrong!");
                }
        });
    });
    
     $(document).on('click', '.stu', function() {
         var st= $(".stu").val();
        var formData = {
            stu:$(this).data("id"),
            action:'load_info'
            
        };
          
        
         
            $('#spinner').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#input").val();
            $.ajax({
                url: "/files/Student/stuInfo.php",
                type: "POST",
                data: formData,
               cache:false,
                success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>");
                
                
                $('#contentsAll').html(data);
                $('#tableResult').hide();
                 window.location.href = "edu?mis=prmone&regno="+data;
                //  $('#contentsAll').show();
                }
                ,error: function(){
                    $('#spinner').html("<i class='fas fa-search'></i>");
                    
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
    
    function pop_up_success(feedback) {
        iziToast.success({
        title: 'Info:',
        message: feedback,
        position: 'topCenter'
  });
    }
</script>


 <script>
     


$(document).ready(function(){
   //alert(3);
  
$("#save_promo").submit(function(e){
            e.preventDefault();
     
           var formdata = new FormData(this);
            document.getElementById("comfbutton").disabled = true;
       
            $.ajax({
                url: "/files/promotion/save_promo.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data", 
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                   // alert(3);
                  
               if(formData==0){
                   
                pop_wrong(formData);  
               
               }
               
               else{
                   $('#closeM').trigger('click');
                   pop_up_success(formData);
                
                    setTimeout(function(){// wait for 5 secs(2)
      
        location.reload();
        
     }, 2000); 
               }
               
              document.getElementById("comfbutton").disabled = false; 
            
                },error: function(){
                    alert("okey");
                }
             });
          });
          
});

   function pop_wrong(feedback) {
          iziToast.warning({
    title: 'Something Wrong',
    message: 'Try again',
    position: 'topRight'
  });
    }
    
      function pop_up_success(feedback) {
    iziToast.success({
    title: 'Well Done',
    message: 'Student Promoted Successfully',
    position: 'topRight'
  });
    }
 </script>