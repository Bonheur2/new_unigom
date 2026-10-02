<div class="main-content" id="books">
        <section class="section">
             <div class="section-header">
             <h3>Staff Report By Status</h3>
                <div class="section-header-breadcrumb">
                   <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Staff  Reports</a></div>
                            <div class="breadcrumb-item"><a href="#">Reports By Status</a></div>
                </div>
        </div>

        </section>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-2 col-sm-12"></div>
                <div class="form-group col-12 col-md-8 col-sm-12 col-lg-8">
                    <div class="card ">
                        <div class="card-body">
                            <div class="input-group">
                                   <select class="form-control select2" id="status_id" name="status_id">
                                            <option value='-1'> Choose Status </option>
                                           <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-sm-12 col-lg-2" style="margin-top:20px;"><span id="spinner1"></span></div>
            </div>
        </div>
        
                         <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                    <div class="card-header" >
                                        <h4>Status  :&nbsp;&nbsp;&nbsp;&nbsp;<span id="status_name"></span></h4>
                                        <div class="row col-lg-8">
                                            <div class="col-12 col-lg-10"></div>
                                            <div class="col-12 col-lg-2" id="download" style="display:none">
                                            <button class="btn btn-primary" type="submit" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excell</button>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="accounts_table">
                                                <thead>
                                                <tr>
                                                <th>#</th>
                                                <th>ID</th>
												<th>First Name</th>
												<th>Last Name</th> 
												<th>NID</th>
												<th>Post</th>
												<th>Department</th>
												<th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody id="result_bank">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
        
    </div>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    $('#accounts_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       }); 
 $("#status_id").change(function(){
    var  status_id=$(this).val();
    
     var select1 = document.getElementById("status_id");
     var selectedOption1 = select1.options[select1.selectedIndex];
	 $("#status_name").html(selectedOption1.innerHTML);
   var formData= {
        status_id:status_id,
        action:'load_status_data'
        };
    $('#spinner1').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/Staff_reports/controller.php",
        data: formData,
        success:function(data){
            $('#spinner1').fadeOut('fast');
         if(data.length>0){
             $("#download").css({
                 display:'block'
             })
          $("#result_bank").html(data);   
         } 
         else{
          pop_wrong("Something went wrong!");     
         }
         
            },
		error:function(error){
		    $('#spinner_prov').fadeOut('fast');
            pop_wrong("Something went wrong!"); 
		}
    });
     
     
 }) 
   $("#exceldownload").click(function() {
       var action="bystatus";
       var status= $("#status_id").val();
    window.open("../files/Staff_reports/allstaffexcelpdf.php?action=" + action + "&status=" + status, "_blank");

  });
});
 function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
      });
    }
  
</script>