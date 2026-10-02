<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Restaurant Subscriptions</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Students</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Add student</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="save_dinner" action="save_dinner" method="POST">
                                                        <input type="hidden" name="action" value="save_dinner">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                                                <label>Student ID</label>
                                                                <select class="form-control select2" style="width:100%;" name="reg_no" required>
                                                                    <?php
                                                                        $sql1=$conn->prepare("SELECT r.reg_no, a.lname, a.fname FROM tbl_register_program_ug r INNER JOIN tbl_admission a ON r.reg_no=a.reg_no WHERE r.reg_active=1");
                                                                        $sql1->execute();
                                                                        while($stu=$sql1->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['fname']." ".$stu['lname']." | ".$stu['reg_no']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                                                <label>Restaurant</label>
                                                                <select class="form-control select2" style="width:100%;" name="rest_id" required>
                                                                    <?php
                                                                        $sql=$conn->prepare("SELECT r.*,c.class_name FROM tbl_restaurant r INNER JOIN tbl_rest_class c ON r.class_id=c.class_id WHERE r.camp_id='".$camp_id."' AND r.status=1");
                                                                        $sql->execute();
                                                                        while($rest=$sql->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $rest['rest_id']; ?>"><?php echo $rest['rest_name']." | ".$rest['class_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-4" id="cls">
                                                                <label>Month count</label>
                                                                <input type="number" class="form-control" name="month_count" id="month_count" min="1" value="1" required>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                                                <label>From</label>
                                                                <input type="date" class="form-control" name="from_date" id="from" value="<?php echo date('Y-m-d'); ?>">
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                                                <label>To</label>
                                                                <input type="date" class="form-control" name="to_date" id="to" value="<?php echo date('Y-m-d', strtotime(' + 1 months')); ?>" readonly>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <center>
                                                                    <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered students</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="restu_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Student ID</th>
                                                                <th scope="col">Names</th>
                                                                <th scope="col">restaurant</th>
                                                                <th scope="col">Months</th>
                                                                <th scope="col">Valid until</th>
                                                                <th scope="col">Amount</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql2=$conn->prepare("SELECT r.*,a.fname,a.lname,rst.rest_name,c.class_name
                                                                                        FROM tbl_rest_subscription r
                                                                                            INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                                            INNER JOIN tbl_restaurant rst ON r.rest_id=rst.rest_id
                                                                                            INNER JOIN tbl_rest_class c ON rst.class_id=c.class_id
                                                                                        WHERE rst.camp_id='".$camp_id."' ORDER BY r.status ASC");
                                                                $sql2->execute();
                                                                $i=1;
                                                                while($din=$sql2->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $din['reg_no']; ?></td>
                                                                <td><?php echo $din['fname']."".$din['lname']; ?></td>
                                                                <td><?php echo $din['rest_name']." | ".$din['class_name']; ?></td>
                                                                <td><?php echo $din['month_count']; ?></td>
                                                                <td><?php echo $din['to_date']; ?></td>
                                                                <td><?php echo number_format($din['amount'],2); ?></td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $din['restu_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $din['restu_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                                        <label class="custom-switch btn btn-sm btn-light">
                                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $din['restu_id']; ?>" <?php echo $din['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $din['restu_id']; ?>"></span>&nbsp;
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
                                            <div class="modal fade" role="dialog" id="updateModal">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Updating <span id="din_name"></span></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="din_id" id="din_id">
                                                            <input type="hidden" name="reg_no" id="e_reg_no">
                                                            <input type="hidden" name="action" value="update_dinner">
                                                            <div class="form-group col-12">
                                                                <label>Restaurant</label>
                                                                <select class="form-control select2" style="width:100%;" name="rest_id" id="rest_id" required>
                                                                    <option disabled selected></option>
                                                                    <?php
                                                                        $sql=$conn->prepare("SELECT r.*,c.class_name FROM tbl_restaurant r INNER JOIN tbl_rest_class c ON r.class_id=c.class_id WHERE r.camp_id='".$camp_id."' AND r.status=1");
                                                                        $sql->execute();
                                                                        while($rest=$sql->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $rest['rest_id']; ?>"><?php echo $rest['rest_name']." | ".$rest['class_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>Month count</label>
                                                                <input type="number" class="form-control" name="month_count" id="e_month_count" min="1" value="1" required>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>From</label>
                                                                <input type="date" class="form-control" name="from_date" id="e_from_date" value="<?php echo date('Y-m-d'); ?>">
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>To</label>
                                                                <input type="date" class="form-control" name="to_date" id="e_to_date" readonly>
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
    $('#restu_table').DataTable({     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });
    
    
    //date manipulation
    $(document).on('click keyup change','#month_count, #from',function () {
        if($("#month_count").val()===''){
            pop_wrong("Add months count");
        }
        else{
            var months = parseInt($("#month_count").val());
            var fromDate=new Date($("#from").val());
            var to  = new Date(fromDate.setMonth(fromDate.getMonth() + months));
            $("#to").val(to.toISOString().slice(0,10));
        }
    });
    
    $(document).on('click keyup change','#e_month_count, #e_from_date',function () {
        if($("#e_month_count").val()===''){
            pop_wrong("Add months count");
        }
        else{
            var months = parseInt($("#e_month_count").val());
            var fromDate=new Date($("#e_from_date").val());
            var to  = new Date(fromDate.setMonth(fromDate.getMonth() + months));
            $("#e_to_date").val(to.toISOString().slice(0,10));
        }
    });
    //save subscription
    $("#save_dinner").submit(function(e){
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
                        pop_up_success(data.message);
                        $('#restu_table').load(location.href + " #restu_table");
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
                    action:'view_dinner'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Restaurant/rest_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    var selectElement = document.getElementById('rest_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data.rest_id + '"]');
                    selectedOption.selected = true;
                    selectElement.prepend(selectedOption);
                    
                    $("#din_id").val(data_id);
                    $("#e_reg_no").val(data.reg_no);
                    $("#e_month_count").val(data.month_count);
                    $("#e_from_date").val(data.from_date);
                    $("#e_to_date").val(data.to_date);
                    $("#din_name").html(data.reg_no+" | "+selectedOption.textContent);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update subscription
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
                        $('#restu_table').load(location.href + " #restu_table");
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

    // delete subscription
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_dinner'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this subscription's status",
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
                        $('#restu_table').load(location.href + " #restu_table");
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