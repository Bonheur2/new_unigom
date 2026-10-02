<?php if($role_id==18){ 
    $sql0=$conn->prepare("SELECT u.*,c.cntr_name FROM tbl_university u INNER JOIN tbl_country c ON u.country=c.cntr_id ORDER BY u.id DESC LIMIT 1");
    $sql0->execute();
    $data=$sql0->fetch();
?>

                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Settings</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=on">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">settings</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                               <div class="col-12 col-md-4 col-ld-4">
                                    <article class="article">
                                        <div class="article-header">
                                            <div class="article-image" data-background="../..<?php echo $data['logo']; ?>" alt="logo"></div>
                                            <div class="article-title">
                                                <button type="button" class="btn btn-light btn-sm" id="lfd"><i class="fas fa-pencil"></i>&nbsp;change logo</button>
                                            </div>
                                        </div>
                                    </article>
                               </div>
                               <div class="card col-12 col-md-8 col-ld-8" style="border-radius:3px;border: 2px solid green;">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job"><b>Full name</b></div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['full_name']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">Short name</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['short_name']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job"><b>Country</b></div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['cntr_name']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job"><b>Location</b></div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['location']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">Email</div>
                                                <div class="user-detail-name"><a href="mailto:<?php echo $data['email']; ?>" target="_blank"><b><?php echo $data['email']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">Website</div>
                                                <div class="user-detail-name"><a href="https://<?php echo $data['website']; ?>" target="_blank"><b><?php echo $data['website']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">Phone</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['phone']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">P.O Box</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $data['po_box']; ?></b></a></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <button type="button" class="btn btn-light btn-sm" id="ifd"><i class="fas fa-pencil"></i>&nbsp;Update details</button>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!--upload logo-->
            <form action="upload_logo" id="upload_logo" method="POST" enctype="multipart/form-data">
                <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Changing Logo</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body pb-0 row">
                                    <div class="form-group  col-12 col-sm-12 col-lg-12">
                                        <input type="hidden" name="action" value="upload_logo">
                                        <input class="form-control" type="file" name="logo" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary btn-sm cl" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm up"><span id="spinner2"></span>&nbsp;<span id="indicator2">Upload</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>  
            
            <!--update details-->
            <form action="update_details" id="update_details" method="POST">
                <div class="modal fade" role="dialog" id="updateModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Updating details</span></h5>
                                <button type="button" class="close cl ucl" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body pb-0 row">
                                    <input type="hidden" name="action" value="update_details">
                                    <div class="form-group col-12">
                                        <label>Full name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-city"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="full_name" value="<?php echo $data['full_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Short name (<i>optional</i>)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="short_name" value="<?php echo $data['short_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Country</label>
                                        <select class="form-control select2" style="width:100%;" name="country">
                                            <?php
                                                $sql=$conn->prepare("SELECT * FROM tbl_country");
                                                $sql->execute();
                                                while($cntr=$sql->fetch()){
                                                    if($cntr['cntr_id']==$data['country']){ ?>
                                                        <option value="<?php echo $cntr['cntr_id']; ?>" selected><?php echo $cntr['cntr_name']; ?></option>
                                                    <?php } else{ ?>
                                                        <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']; ?></option>
                                                    <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Location</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-location-dot"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="location" placeholder="e.g. kG 2923" value="<?php echo $data['location']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Phone number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control phone-number" name="phone" placeholder="e.g. +250788888888" value="<?php echo $data['phone']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="email" class="form-control" name="email" placeholder="example@example.example" value="<?php echo $data['email']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Website</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-globe"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="website" placeholder="www.example.example" value="<?php echo $data['website']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>P.O Box</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-mailbox"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="po_box" placeholder="P.O Box .............." value="<?php echo $data['po_box']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary btn-sm ucl" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm sc"><span id="spinner"></span>&nbsp;<span id="indicator">Save changes</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>   
<?php } ?>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $(document).on('click','#lfd',function(){
        $('#uploadModal').modal('show');
    });
    
    $(document).on('click','#ifd',function(){
        $('#updateModal').modal('show');
    });
    //upload logo
    $("#upload_logo").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Uploading...");
        $('.cl,.up').attr('disabled', true);
        $.ajax({
            url: "/files/Settings/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Upload");
                $('.cl,.up').attr('disabled', false);
                if(data == 200){
                    $('#upload_logo')[0].reset();
                    pop_up_success("logo changed successfully!");
                    window.location.reload();
                }
                if(data == 401){
                    pop_wrong("file size is loo large");
                }
                if(data == 500){
                    pop_info("Something went wrong!");
                }
            },
            error: function(){
                $('.cl,.up').attr('disabled', false);
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Upload");
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    //update details
    $("#update_details").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $('.ucl,.sc').attr('disabled', true);
        $.ajax({
            url: "/files/Settings/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Upload");
                $('.ucl,.sc').attr('disabled', false);
                if(data == 200){
                    $('#update_details')[0].reset();
                    pop_up_success("data changed successfully!");
                    window.location.reload();
                }
                if(data == 500){
                    pop_info("Something went wrong!");
                }
            },
            error: function(){
                $('.ucl,.sc').attr('disabled', false);
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Upload");
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
        title: 'ooops',
        message: feedback,
        position: 'topCenter'
      });
    }
    function pop_up_success(feedback) {
     iziToast.success({
        title: 'info:',
        message: feedback,
        position: 'topCenter'
      });
    }
    </script>