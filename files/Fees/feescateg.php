<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Fees Category</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Fees Category</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <?php if($role_id == 3){ ?>
                                    <div class="col-12 col-sm-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New category</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_feecateg" action="save_feecateg" method="POST">
                                                        <input type="hidden" name="action" value="register">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Category name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="name" name="name" placeholder="name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea class="form-control" id="description" name="description" placeholder="description" rows="3"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Is Amount specified?</label><br>
                                                                <div style="display:flex;flex-direction:row;">
                                                                    <div class="form-check" style="margin-right:10px;">
                                                                        <input type="radio" class="form-check-input known_price" value="1" name="known_price" required>
                                                                        <label class="form-check-label" for="exampleRadios1">Yes</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input known_price" value="0" name="known_price" required>
                                                                        <label class="form-check-label" for="exampleRadios1">No</label> 
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group" id="amt" hidden>
                                                                <label>Amount</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" id="amount" placeholder="Type here" required>
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
                                    <?php } ?>
                                    <div class="col-12 <?php echo $role_id == 3?'col-sm-8 col-lg-8':'' ?>">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered Categories</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="fee_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Category name</th>
                                                                <th scope="col">Description</th>
                                                                <th scope="col">Amount</th>
                                                                <?php if($role_id == 3){ ?>
                                                                <th scope="col">Action</th>
                                                                <?php } ?>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM fee_category ORDER BY id ASC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($fee=$sql->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $fee['name']; ?></td>
                                                                <td><?php echo $fee['description']; ?></td>
                                                                <td><?php echo $fee['amount']; ?></td>
                                                                <?php if($role_id == 3){ ?>
                                                                <th>
                                                                    <div class="buttons row">
                                                                    <button type="button" data-id="<?php echo $fee['id']; ?>" class="btn btn-icon btn-light btn-sm edit"><span id="spinner4_<?php echo $fee['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                                    <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $fee['id']; ?>" <?php echo $fee['status']==1?'checked':''; ?>>
                                                                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $fee['id']; ?>"></span>&nbsp;
                                                                    </label>
                                                                    </div>
                                                                     
                                                                </th>
                                                                <?php } ?>
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
                                                    <h5 class="modal-title">Updating <span id="fee_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="fee_id" name="fee_id">
                                                    <input type="hidden" name="action" value="update">
                                                    <div class="form-group">
                                                        <label>Category name</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="e_name" name="name" placeholder="name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea class="form-control" id="e_description" name="description" placeholder="description" rows="3"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Is Amount specified?</label><br>
                                                        <div style="display:flex;flex-direction:row;">
                                                            <div class="form-check" style="margin-right:10px;">
                                                                <input type="radio" class="form-check-input e_known_price" value="1" name="e_known_price" required>
                                                                <label class="form-check-label" for="exampleRadios1">Yes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input e_known_price" value="0" name="e_known_price" required>
                                                                <label class="form-check-label" for="exampleRadios1">No</label> 
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="form-group" id="e_amt" hidden>
                                                        <label>Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="number" class="form-control" id="e_amount" placeholder="Type here" required>
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
    $('#fee_table').DataTable(
         {     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
        
    //UI master
    $('.known_price').click(function () {
        if($(this).val()==='1'){
            $("#amt").removeAttr('hidden');
            $("#amount").attr('required',true);
            $("#amount").attr('name','amount');
        }
        else{
            $("#amt").attr('hidden',true);
            $("#amount").removeAttr('required'); 
            $("#amount").removeAttr('name');
        }
    });
    
    $('.e_known_price').click(function () {
        if($(this).val()==='1'){
            $("#e_amt").removeAttr('hidden');
            $("#e_amount").attr('required',true);
            $("#e_amount").attr('name','amount');
        }
        else{
            $("#e_amt").attr('hidden',true);
            $("#e_amount").removeAttr('required'); 
            $("#e_amount").removeAttr('name');
        }
    });
    //save fee categ
    $("#save_feecateg").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Fees/fee_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_feecateg')[0].reset();
                        pop_up_success(data.message);
                        $('#fee_table').load(location.href + " #fee_table");
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
                    action:'view'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Fees/fee_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#fee_id").val(data_id);
                    $("#e_name").val(data.name);
                    $("#e_description").val(data.description);
                    if(data.known_price==1){
                        $('input[name="e_known_price"][value="1"]').prop('checked', true);
                        $("#e_amt").removeAttr('hidden');
                        $("#e_amount").attr('required',true);
                        $("#e_amount").attr('name','amount');
                        $("#e_amount").val(data.amount);
                    }
                    else{
                        $('input[name="e_known_price"][value="0"]').prop('checked', true);
                        $("#e_amt").attr('hidden',true);
                        $("#e_amount").removeAttr('required'); 
                        $("#e_amount").removeAttr('name');
                    }
                    $("#fee_name").html(data.name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update fee categ
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Fees/fee_controller.php",
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
                        $('#fee_table').load(location.href + " #fee_table");
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
          
// activate/deactivate fee categ
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this category's status! This operation will affect different reports",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Fees/fee_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                       pop_up_success(data.message);
                      $('#fee_table').load(location.href + " #fee_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
            }
           else {
                swal("operation cancelled!!");
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