<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Universities</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">University</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New University</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body row">
                                        <form id="save_university" action="save_university" method="POST" enctype="multipart/form-data">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Full Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="full_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Short Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-pencil"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="short_name" placeholder="Short name">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Country</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-globe"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="country" placeholder="Country">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-2 col-lg-2">
                                                    <label>Email</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-envelope"></i>
                                                            </div>
                                                        </div>
                                                        <input type="email" class="form-control" id="email" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Website</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-link"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="website" placeholder="https://...">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Phone</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="phone" placeholder="Phone">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Location</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="location" placeholder="Location">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>P.O. Box</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-mail-bulk"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="po_box" placeholder="P.O. Box">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Registration Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" id="reg_date">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Logo</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-image"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="logo" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-2 col-lg-2">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
                                    <h4>Registered Universities</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm university_table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Logo</th>
                                                <th scope="col">Full Name</th>
                                                <th scope="col">Short Name</th>
                                                <th scope="col">Country</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT id, full_name, short_name, country, email, website, phone, location, po_box, logo, reg_date FROM tbl_university ORDER BY id DESC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch()):
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td>
                                                    <?php if(!empty($row['logo'])): ?>
                                                    <img src="<?php echo $row['logo']; ?>" alt="logo" width="35" height="35" style="object-fit:contain">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['short_name']; ?></td>
                                                <td><?php echo $row['country']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $row['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
                                                        <button type="button" data-id="<?php echo $row['id']; ?>" class="btn btn-icon btn-danger btn-sm del">
                                                            <span id="spinner3_<?php echo $row['id']; ?>"></span>&nbsp;<i class="far fa-trash-alt"></i>&nbsp;delete
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </th>
                                            </tr>
                                            <?php endwhile; ?>
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
            <form action="update_form" method="POST" id="update_form" enctype="multipart/form-data">
                <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Updating <span id="f_name"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="e_id" name="e_id">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="e_full_name" placeholder="Full name" required>
                                </div>
                                <div class="form-group">
                                    <label>Short Name</label>
                                    <input type="text" class="form-control" id="e_short_name" placeholder="Short name">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" id="e_country" placeholder="Country">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="e_email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control" id="e_website" placeholder="https://...">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" id="e_phone" placeholder="Phone">
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" class="form-control" id="e_location" placeholder="Location">
                                </div>
                                <div class="form-group">
                                    <label>P.O. Box</label>
                                    <input type="text" class="form-control" id="e_po_box" placeholder="P.O. Box">
                                </div>
                                <div class="form-group">
                                    <label>Registration Date</label>
                                    <input type="date" class="form-control" id="e_reg_date">
                                </div>
                                <div class="form-group">
                                    <label>Logo</label>
                                    <div id="e_logo_preview" class="mb-2"></div>
                                    <input type="file" class="form-control" id="e_logo" accept="image/*">
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
    $('.university_table').each(function() {
        $(this).DataTable({
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });

    //save university
    $("#save_university").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('full_name', $("#full_name").val());
        formData.append('short_name', $("#short_name").val());
        formData.append('country', $("#country").val());
        formData.append('email', $("#email").val());
        formData.append('website', $("#website").val());
        formData.append('phone', $("#phone").val());
        formData.append('location', $("#location").val());
        formData.append('po_box', $("#po_box").val());
        formData.append('reg_date', $("#reg_date").val());
        formData.append('logo', $("#logo")[0].files[0]);
        formData.append('action', 'register');

        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/University/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_university')[0].reset();
                    pop_up_success(data.message);
                    location.reload();
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

    // delete university
    $(document).on('click','.del',function () {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Are you sure?",
            text: "You are about to delete this university!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/University/controller.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==500){
                            pop_wrong(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                            location.reload();
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

    //pre-update View
    $(document).on('click','.edit',function () {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'view'
        };
        $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "../new_files/University/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_full_name").val(data.full_name);
                $("#e_short_name").val(data.short_name);
                $("#e_country").val(data.country);
                $("#e_email").val(data.email);
                $("#e_website").val(data.website);
                $("#e_phone").val(data.phone);
                $("#e_location").val(data.location);
                $("#e_po_box").val(data.po_box);
                $("#e_reg_date").val(data.reg_date);
                $("#f_name").html(data.full_name);
                $("#e_logo_preview").html(data.logo ? "<img src='"+data.logo+"' width='60' height='60' style='object-fit:contain'>" : "");
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update university
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = new FormData();
        formData.append('id', $("#e_id").val());
        formData.append('full_name', $("#e_full_name").val());
        formData.append('short_name', $("#e_short_name").val());
        formData.append('country', $("#e_country").val());
        formData.append('email', $("#e_email").val());
        formData.append('website', $("#e_website").val());
        formData.append('phone', $("#e_phone").val());
        formData.append('location', $("#e_location").val());
        formData.append('po_box', $("#e_po_box").val());
        formData.append('reg_date', $("#e_reg_date").val());
        if($("#e_logo")[0].files[0]){
            formData.append('logo', $("#e_logo")[0].files[0]);
        }
        formData.append('action', 'update');

        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/University/controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save Changes");
                if(data.status==200){
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    location.reload();
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

});

function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Error',
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
