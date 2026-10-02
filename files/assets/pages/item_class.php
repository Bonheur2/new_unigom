<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#"><?php echo $title; ?></a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Class</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_class" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-folder"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="iclass_name" placeholder="Name goes here" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Code (<code>To be used in numbering</code>)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-clipboard"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="iclass_code" placeholder="Type here" required>
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
                <div class="col-12 col-sm-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Registered Item Classes</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="classes">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Class</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql=$conn->prepare("SELECT * FROM tbl_item_class ORDER BY status ASC");
                                            $sql->execute();
                                            $i=1;
                                            while($classes=$sql->fetch()){
                                         ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $classes['iclass_name']; ?></td>
                                            <td><?php echo $classes['iclass_code']; ?></td>
                                            <td>  
                                                <div class="buttons row">
                                                    <button type="button" data-id="<?php echo $classes['iclass_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $classes['iclass_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                    <label class="custom-switch btn btn-light btn-sm">
                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $classes['iclass_id']; ?>" <?php echo $classes['status']==1?'checked':''; ?>>
                                                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $classes['iclass_id']; ?>"></span>&nbsp;
                                                    </label>
                                                </div>
                                            </td>
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
                        <h5 class="modal-title">Updating <span id="cl_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="iclass_id" name="iclass_id">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group">
                            <label>Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-folder"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="iclass_name" name="iclass_name" placeholder="Name goes here." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Code (<code>To be used in numbering</code>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-folder"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="iclass_code" name="iclass_code" placeholder="Type here" required>
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
    $('#classes').DataTable({
        paging: false
    });
    
    //save item class
    $("#save_class").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Class.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_class')[0].reset();
                        pop_up_success(data.message);
                        $('#classes').load(location.href + " #classes");
                    }
                    if(data.status==400){
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
                    iclass_id: data_id,
                    action:'view'
                };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Class.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#iclass_id").val(data_id);
                    $("#iclass_name").val(data.iclass_name);
                    $("#iclass_code").val(data.iclass_code);
                    $("#cl_name").html(data.iclass_name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update item class
    $("#update_form").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Class.php",
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
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#classes').load(location.href + " #classes");
                    }
                    if(data.status==400){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
          
        //Disable /Enable
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                iclass_id: data_id,
                action:'manage'
            };
            swal({
                title: "Confirm Action",
                text: "You are about to change this class's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/assets/src/Class.php",
                    data: getData,
                    dataType:"JSON",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==400){
                            pop_wrong(data.message); 
                        }
                        else if(data.status==200){
                           pop_up_success(data.message); 
                           $('#classes').load(location.href + " #classes");
                        }
    				},
    				error:function(error){
    				    $('#spinner3_'+data_id).fadeOut('fast');
                        pop_wrong("Something went wrong");
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