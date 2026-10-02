<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Blocks</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-5 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Entry</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="register" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0">
                                        <div class="form-group">
                                            <label>Block Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-city"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="block_full_name" placeholder="EX: Block 1" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Block Short name (Nickname: <code>optional</code>)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-city"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="block_short_name" placeholder="EX: B1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label>Campus</label>
                                        <select class="form-control select2" style="width:100%;" name="camp_id" required>
                                            <option selected disabled>Select Campus</option>
                                            <?php
                                                $sqlCampus = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active = 1");
                                                $sqlCampus->execute();
                                                while($campus = $sqlCampus->fetch()){
                                                    ?>
                                            <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name'] ?></option>
                                            <?php } ?>
                                        </select>
    
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
                    <div class="card">
                        <div class="card-header">
                            <h4>Registered Blocks</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="blocks">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Full name</th>
                                        <th scope="col">Nickname</th>
                                        <th scope="col" >Campus</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql=$conn->prepare("SELECT * FROM tbl_blocks ORDER BY block_id ASC");
                                        $sql->execute();
                                        $i=1;
                                        while($block = $sql->fetch()){
                                            $camp=$block['campus'];
                                            $stC=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_id ='".$camp."'");
                                            $stC->execute();
                                            $campUN=$stC->fetch();
                                            
                                     ?>
                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>
                                        <td><?php echo $block['block_full_name']; ?></td>
                                        <td><?php echo $block['block_short_name']; ?></td>
                                        <td><?php echo $campUN['camp_full_name']; ?></td>
                                        <th>  
                                            <div class="buttons row">
                                                <button type="button" data-id="<?php echo $block['block_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $block['block_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                <label class="custom-switch btn btn-light btn-sm">
                                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $block['block_id']; ?>" <?php echo $block['status']==1?'checked':''; ?>>
                                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $block['block_id']; ?>"></span>&nbsp;
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
                        <h5 class="modal-title">Updating <span id="block_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="block_id" name="block_id">
                        <div class="form-group">
                            <label>Block Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-city"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="block_full_name" id="block_full_name" placeholder="EX:Block 1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Block Short name (Nickname: <code>optional</code>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-city"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="block_short_name" id="block_short_name" placeholder="EX:B1">
                            </div>
                        </div>
                        <div class="form-group">
                                        <label>Campus</label>
                                        <select class="form-control select2" style="width:100%;" name="camp_id" id="camp_id" required>
                                            <option selected disabled>Select Campus</option>
                                            <?php
                                                $sqlCampus = $conn->prepare("SELECT * FROM tbl_campus WHERE camp_active = 1");
                                                $sqlCampus->execute();
                                                while($campus = $sqlCampus->fetch()){
                                                    ?>
                                            <option value="<?php echo $campus['camp_id']; ?>"><?php echo $campus['camp_full_name'] ?></option>
                                            <?php } ?>
                                        </select>
    
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
        $('#blocks').DataTable({     
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    
        $("#register").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Block/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#register')[0].reset();
                        pop_up_success(data.message);
                        $('#blocks').load(location.href + " #blocks");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
            });
        });
      
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                };
            swal({
                title: "Are you sure?",
                text: "You are about to change this block's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Block/controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==200){
                               pop_up_success(data.message);
                               $('#blocks').load(location.href + " #blocks");
                            } else{
                                pop_wrong(data.message);
                            }
        				},
        				error:function(error){
        				    $('#spinner3_'+data_id).fadeOut('fast');
                            pop_wrong("Something went wrong!");
        				}
                    });
                } else {
                    swal("operation cancelled!!");
                }
            });
        });
        
        //pre-update View
        $(document).on('click', '.edit', function () {
    var data_id = $(this).data('id');
    var getData = {
        id: data_id,
        action: 'view'
    };
    $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');

    $.ajax({
        type: "POST",
        url: "/files/Block/controller.php",
        data: getData,
        dataType: "json",
        success: function (data) {
            $('#spinner4_' + data_id).fadeOut('fast');

            // Populate form fields with retrieved data
            $("#block_id").val(data_id);
            $("#block_full_name").val(data.block_full_name);
            $("#block_short_name").val(data.block_short_name);
            $("#block_name").html(data.block_full_name + " | " + data.block_short_name);

            // Set the selected campus option
            $("#camp_id").val(data.campus).trigger('change'); 

            // Show the modal
            $('#updateModal').modal('show');
        },
        error: function (error) {
            $('#spinner4_' + data_id).fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});

        
        //update
        $("#update_form").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Block/controller.php",
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
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#blocks').load(location.href + " #blocks");
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
        title: 'Info',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'success',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>