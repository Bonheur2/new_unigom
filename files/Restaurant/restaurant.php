<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Restaurants</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Restaurants</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-5">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New Restaurants</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_rest" action="save_rest" method="POST">
                                                        <input type="hidden" name="action" value="save_rest">
                                                        <input type="hidden" name="camp_id" value="<?php echo $camp_id; ?>">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Class</label>
                                                                <select type="text" class="form-control select2" style="width:100%;" name="class_id" required>
                                                                    <?php
                                                                        $sql=$conn->prepare("SELECT * FROM tbl_rest_class WHERE status=1");
                                                                        $sql->execute();
                                                                        while($cls=$sql->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $cls['class_id']; ?>"><?php echo $cls['class_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Classification</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-utensils"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="rest_name" placeholder="restaurant name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Monthly fees</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" name="price" placeholder="Type here" min="0.1" step=".01" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-7 col-lg-7">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered restaurants</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="rest_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Class</th>
                                                                <th scope="col">Monthly fees (FRW)</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql2=$conn->prepare("SELECT r.*,c.class_name
                                                                                        FROM tbl_restaurant r
                                                                                            INNER JOIN tbl_rest_class c ON r.class_id=c.class_id
                                                                                        WHERE r.camp_id='".$camp_id."' ORDER BY r.status ASC");
                                                                $sql2->execute();
                                                                $i=1;
                                                                while($rest=$sql2->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $rest['rest_name']; ?></td>
                                                                <td><?php echo $rest['class_name']; ?></td>
                                                                <td><?php echo number_format($rest['price']); ?></td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $rest['rest_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $rest['rest_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                        <label class="custom-switch btn btn-sm btn-light">
                                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $rest['rest_id']; ?>" <?php echo $rest['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $rest['rest_id']; ?>"></span>&nbsp;
                                                                        </label>
                                                                    </div>
        
                                                                </th>
                                                            </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="r_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="rest_id" id="rest_id">
                                                    <input type="hidden" name="action" value="update_rest">
                                                    <div class="form-group">
                                                        <label>Class</label>
                                                        <select type="text" class="form-control select2" style="width:100%;" name="class_id" id="class_id" required>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_rest_class WHERE status=1");
                                                                $sql->execute();
                                                                while($cls=$sql->fetch()){
                                                            ?>
                                                                <option value="<?php echo $cls['class_id']; ?>"><?php echo $cls['class_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Classification</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-utensils"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="rest_name" id="rest_name" placeholder="restaurant name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Monthly fees</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="number" class="form-control" name="price" id="price" placeholder="Type here" min="0.1" step=".01" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#rest_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    //save restaurant
    $("#save_rest").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Restaurant/rest_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_rest')[0].reset();
                        pop_up_success(data.message);
                        $('#rest_table').load(location.href + " #rest_table");
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
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_rest'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Restaurant/rest_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#rest_id").val(data_id);
                    $("#rest_name").val(data.rest_name);
                    $("#price").val(data.price);
                    $("#r_name").html(data.rest_name+" | "+data.class_name);
                    var selectElement = document.getElementById('class_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data.class_id + '"]');
                    selectedOption.selected = true;
                    selectElement.prepend(selectedOption);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update restaurant
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Restaurant/rest_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#rest_table').load(location.href + " #rest_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });

    // delete restaurant
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_rest'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this restaurant's status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Restaurant/rest_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#rest_table').load(location.href + " #rest_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
           else {
                swal("operation Cancelled!!");
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