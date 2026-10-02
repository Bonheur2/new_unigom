<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="campus" value="<?php echo $camp_id; ?>">
            <section class="section">
                <div class="section-header">
                    <h3>Announcements</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Announcements</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <?php 
                            $stmt=$conn->prepare("SELECT * FROM tbl_staff WHERE staff_id='".$identification."'");
                            $stmt->execute();
                            if($stmt->rowCount()>0 || $role_id==18 || $role_id==1){
                        ?>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Create announcement</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_ann" action="save_ann" method="POST">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" name="action" value="register">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-md-12">
                                                    <label>Title</label>
                                                    <textarea class="form-control" name="title" placeholder="Type title here" minlength="10" maxlength="160" required></textarea>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Target audience</label>
                                                    <select class="form-control select2" style="width:100%;" name="target" required>
                                                        <?php 
                                                            if($stmt->rowCount()>0){
                                                        ?>
                                                        <option value="1">Public</option>
                                                        <option value="2">Students</option>
                                                        <option value="3">Staff</option>
                                                        <?php } else if($role_id==18 || $role_id==1){ ?>
                                                        <option value="2">Students</option>
                                                        <option value="3">Staff</option>
                                                        <?php } else{} ?>
                                                        
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Expires at</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="expires_at" min="<?php echo date('Y-m-d'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Upload file</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-file"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" name="file" accept=".pdf, image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer pt-">
                                                <center>
                                                    <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </center>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if($role_id==4 || $role_id==5){ ?>
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card gradient-bottom">
                                    <div class="card-header">
                                        <h4>Recent announcements</h4>
                                    </div>
                                    <div class="card-body" id="top-5-scroll">
                                        <ul class="list-unstyled list-unstyled-border">
                                            <?php
                                                if($role_id==4){
                                                    $sql=$conn->prepare("SELECT * FROM tbl_announcement WHERE (target=1 OR target=2) ORDER BY date_published DESC");
                                                }
                                                else{
                                                    $sql=$conn->prepare("SELECT * FROM tbl_announcement WHERE (target=1) ORDER BY date_published DESC");
                                                }
                                                $sql->execute();
                                                while($ann=$sql->fetch()){
                                            ?>
                                            <li class="media iNot" data-href="edu?mis=det_ann&an=<?php echo 'rub_'.$ann['id'].'_78'.$ann['id']; ?>" data-target="_blank">
                                                <i class=" fas fa-bell mr-3 rounded" width="55"></i>
                                                <div class="media-body">
                                                    <div class="float-right">
                                                        <div class="font-weight-600 text-muted text-small">
                                                        <?php 
                                                            $dateTime = new DateTime($ann['date_published']);
                                                            $formattedDate = $dateTime->format('Y-m-d');
                                                            echo $formattedDate; 
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <div class="media-title"><?php echo $ann['title']; ?></div>
                                                    <div class="mt-1">
                                                        <div class="budget-price">
                                                            <?php
                                                            $query = $conn->prepare("SELECT readers FROM tbl_announcement WHERE id='".$ann['id']."'");
                                                            $query->execute();
                                                            $row = $query->fetch();
                                                            $readers = json_decode($row['readers'], true);
                                                            $views = count($readers);
                                                            
                                                            ?>
                                                            <div class="budget-price-label"><?php echo $views." views" ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php } else{ ?>
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Recent announcement</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="ann_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Audience</th>
                                                    <th scope="col">Published</th>
                                                    <th scope="col">Expires at</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT * FROM tbl_announcement ORDER BY date_published DESC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($ann=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $ann['title']; ?></td>
                                                    <td><?php echo $ann['target']==1?'Public':($ann['target']==2?'Staff':'Students'); ?></td>
                                                    <td><?php echo $ann['date_published']; ?></td>
                                                    <td><?php echo $ann['expires_at']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <a href="edu?mis=det_ann&an=<?php echo 'rub_'.$ann['id'].'_78'.$ann['id']; ?>" class="btn btn-icon btn-success btn-sm"><i class="fas fa-eye"></i>&nbsp; view</a>
                                                            <?php if($ann['user']==$identification){?>
                                                                <button type="button" data-id="<?php echo $ann['id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $ann['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; edit</button>
                                                                <button type="button" data-id="<?php echo $ann['id']; ?>" class="btn btn-icon btn-light btn-sm del"><span id="spinner3_<?php echo $ann['id']; ?>"></span>&nbsp;<i class="fas fa-trash"></i>&nbsp; delete</button>
                                                            <?php } ?>
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
                        <?php } ?>
                    </div>
                </div>
            </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating Announcement</span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="id" name="id">
                                                    <input type="hidden" name="action" value="update">
                                                    <div class="card-body pb-0 row">
                                                    <div class="form-group col-12">
                                                        <label>Title</label>
                                                        <textarea class="form-control" name="title" id="title" placeholder="Type title here" minlength="10" maxlength="160" required></textarea>
                                                    </div>
                                                    <div class="form-group col-12">
                                                        <label>Target audience</label>
                                                        <select class="form-control select2" style="width:100%;" name="target" id="target">
                                                            <option value="1">Public</option>
                                                            <option value="2">Students</option>
                                                            <option value="3">Staff</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12">
                                                        <label>Expires at</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" name="expires_at" id="expires_at">
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

    $('#ann_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });  
    //save announcement
    $("#save_ann").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/announcements/controller.php",
                type: "POST",
                data: formData,
                dataType:'JSON',
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_ann')[0].reset();
                        $('#ann_table').load(location.href + " #ann_table");
                        pop_up_success(data.message);
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
      
        // delete announcement
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to delete this announcement!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/announcements/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_info(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#ann_table').load(location.href + " #ann_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            }
           else {
                swal("Operation cancelled!!");
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
                url: "/files/announcements/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $('#id').val(data_id);
                    $('#title').val(data.title);
                    $('#expires_at').val(data.expires_at);
                    var selectElement = document.getElementById('target');
                    var selectedOption = selectElement.querySelector('option[value="' + data.target + '"]');
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

    //update announcement
    $("#update_form").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/announcements/controller.php",
                type: "POST",
                data: formData,
                dataType:'JSON',
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        $('#ann_table').load(location.href + " #ann_table");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          
    $('body').on('click', '.iNot',function() {
        var href = $(this).data("href");
        var target = $(this).data("target");
        if(href!="#"){
            window.open(href, target);
        }
    });
});

   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
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
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>