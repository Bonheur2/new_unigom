<!-- Start app main Content -->
<div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Graduation days</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">days</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New date</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_date" action="save_date" method="POST">
                                            <div class="row">
                                                <input type="hidden" name="action" value="register">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Academic Year</label>
                                                    <select class="form-control select2" style="width:100%" name="acad_cycle_id" required>
                                                        <?php
                                                            $sql_acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                                                            $sql_acad->execute();
                                                            $i=1;
                                                            while($grads_acad=$sql_acad->fetch()){
                                                                ?>
                                                        <option value="<?php echo $grads_acad['acad_cycle_id']; ?>"><?php echo $grads_acad['acad_year']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4"  style="padding:0px;">
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                        <label>Graduation date</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" name="grad_date" placeholder="" min="<?php echo date(); ?>" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn btn-primary form-control btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                    </div>
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
                                        <h4>Registered Graduation dates</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="graduation_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Academic Year</th>
                                                    <th scope="col">Graduation date</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT gc.*,
                                                                        ac.acad_year
                                                                        FROM tbl_grad_cycle gc 
                                                                        INNER JOIN tbl_acad_cycle ac ON 
                                                                        gc.acad_cycle_id = ac.acad_cycle_id
                                                                        ORDER BY gc.grad_cycle_id ASC
                                                                        ");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($grads=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $grads['acad_year']; ?></td>
                                                    <td><?php echo $grads['grad_date']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $grads['grad_cycle_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $grads['grad_cycle_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; Edit</button>
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
                                <h5 class="modal-title">Updating graduation date</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" id="grad_cycle_id" name="grad_cycle_id">
                                <div class="card-body pb-0 row">
                                    <div class="form-group col-12 col-sm-6 col-lg-6">
                                        <label>Academic Year</label>
                                        <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id" required>
                                            <?php
                                                $sql_acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1");
                                                $sql_acad->execute();
                                                $i=1;
                                                while($grads_acad=$sql_acad->fetch()){
                                                    ?>
                                            <option value="<?php echo $grads_acad['acad_cycle_id']; ?>"><?php echo $grads_acad['acad_year']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-lg-6"  style="padding:0px;">
                                        <div class="form-group col-12 col-sm-12 col-lg-12">
                                            <label>Graduation date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </div>
                                                </div>
                                                <input type="date" class="form-control" name="grad_date" id="grad_date" placeholder="" min="<?php echo date(); ?>" required>
                                            </div>
                                        </div>
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
        $('#graduation_table').DataTable({     
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
        
        //save date
        $("#save_date").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Graduation/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_date')[0].reset();
                        pop_up_success(data.message);
                        $('#graduation_table').load(location.href + " #graduation_table");
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
                url: "/files/Graduation/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    console.log(data);
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#grad_cycle_id").val(data_id);
                    $("#grad_date").val(data.grad_date);
                    
                    var selectElement2 = document.getElementById('acad_cycle_id');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.acad_cycle_id + '"]');
                    // selectedOption2.selected = true;
                    selectElement2.prepend(selectedOption2);
                    $('#updateModal').modal('show');
    			},
    			error:function(error){
    			    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
    			}
            });
        });
            
    //update date
        $("#update_form").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Graduation/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#graduation_table').load(location.href + " #graduation_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
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