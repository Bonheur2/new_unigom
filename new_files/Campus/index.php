<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Campuses</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Campus</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Campus</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_campus" action="save_campus" method="POST">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>University</label>
                                                    <select class="form-control select2" style="width:100%" id="university_id" required>
                                                        <option value="">-- Select University --</option>
                                                        <?php
                                                            $sql_univ = $conn->prepare("SELECT id, full_name FROM tbl_university ORDER BY full_name ASC");
                                                            $sql_univ->execute();
                                                            while($univ = $sql_univ->fetch()):
                                                        ?>
                                                        <option value="<?php echo $univ['id']; ?>"><?php echo $univ['full_name']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Campus Full Name</label>
                                                    <input type="text" class="form-control" id="camp_full_name" placeholder="Full name" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>City</label>
                                                    <input type="text" class="form-control" id="camp_city" placeholder="City">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Year of Registration</label>
                                                    <input type="text" class="form-control" id="camp_yor" placeholder="Ex: 2010">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Active</label>
                                                    <select class="form-control select2" style="width:100%" id="camp_active">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label>Comments</label>
                                                    <textarea class="form-control" id="camp_comments" placeholder="Comments" rows="2"></textarea>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary d-block"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
                                    <h4>Registered Campuses</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm campus_table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">University</th>
                                                <th scope="col">Full Name</th>
                                                <th scope="col">City</th>
                                                <th scope="col">Year of Registration</th>
                                                <th scope="col">Comments</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql = $conn->prepare("SELECT tbl_campus.camp_id, tbl_campus.camp_full_name, tbl_campus.camp_city, tbl_campus.camp_yor, tbl_campus.camp_active, tbl_campus.camp_comments, tbl_campus.university_id, tbl_university.full_name AS university_name
                                                                        FROM tbl_campus
                                                                        LEFT JOIN tbl_university ON tbl_university.id = tbl_campus.university_id
                                                                        ORDER BY tbl_campus.camp_id DESC");
                                                $sql->execute();
                                                $i = 1;
                                                while($row = $sql->fetch()):
                                            ?>
                                            <tr data-row-id="<?php echo $row['camp_id']; ?>">
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td class="col-university"><?php echo $row['university_name']; ?></td>
                                                <td class="col-full-name"><?php echo $row['camp_full_name']; ?></td>
                                                <td class="col-city"><?php echo $row['camp_city']; ?></td>
                                                <td class="col-yor"><?php echo $row['camp_yor']; ?></td>
                                                <td class="col-comments"><?php echo $row['camp_comments']; ?></td>
                                                <th>
                                                    <?php if($role_id == 18 || $role_id == 2): ?>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $row['camp_id']; ?>" class="btn btn-icon btn-primary btn-sm edit">
                                                            <span id="spinner4_<?php echo $row['camp_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit
                                                        </button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['camp_id']; ?>" <?php echo $row['camp_active']==1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['camp_id']; ?>"></span>&nbsp;
                                                        </label>
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
            <form action="update_form" method="POST" id="update_form">
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
                                    <label>University</label>
                                    <select class="form-control select2" style="width:100%" id="e_university_id" required>
                                        <option value="">-- Select University --</option>
                                        <?php
                                            $sql_univ2 = $conn->prepare("SELECT id, full_name FROM tbl_university ORDER BY full_name ASC");
                                            $sql_univ2->execute();
                                            while($univ2 = $sql_univ2->fetch()):
                                        ?>
                                        <option value="<?php echo $univ2['id']; ?>"><?php echo $univ2['full_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Campus Full Name</label>
                                    <input type="text" class="form-control" id="e_camp_full_name" placeholder="Full name" required>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" id="e_camp_city" placeholder="City">
                                </div>
                                <div class="form-group">
                                    <label>Year of Registration</label>
                                    <input type="text" class="form-control" id="e_camp_yor" placeholder="Ex: 2010">
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <select class="form-control select2" style="width:100%" id="e_camp_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea class="form-control" id="e_camp_comments" placeholder="Comments" rows="2"></textarea>
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
    var campusDt = $('.campus_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });

    // build a table row for a campus and add it to the DataTable
    function addCampusRow(row){
        var canManage = <?php echo ($role_id == 18 || $role_id == 2) ? 'true' : 'false'; ?>;
        var actionsHtml = '';
        if(canManage){
            actionsHtml = '<div class="buttons row">'
                + '<button type="button" data-id="'+row.camp_id+'" class="btn btn-icon btn-primary btn-sm edit">'
                + '<span id="spinner4_'+row.camp_id+'"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>'
                + '<label class="custom-switch btn btn-light btn-sm">'
                + '<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="'+row.camp_id+'" checked>'
                + '<span class="custom-switch-indicator"></span><span id="spinner3_'+row.camp_id+'"></span>&nbsp;</label></div>';
        }
        var $tr = $(campusDt.row.add([
            campusDt.rows().count() + 1,
            row.university_name || '',
            row.camp_full_name,
            row.camp_city || '',
            row.camp_yor || '',
            row.camp_comments || '',
            actionsHtml
        ]).draw(false).node());
        $tr.attr('data-row-id', row.camp_id);
    }

    //save campus
    $("#save_campus").submit(function(e){
        e.preventDefault();

        var formData = {
            university_id: $("#university_id").val(),
            camp_full_name: $("#camp_full_name").val(),
            camp_city: $("#camp_city").val(),
            camp_yor: $("#camp_yor").val(),
            camp_active: $("#camp_active").val(),
            camp_comments: $("#camp_comments").val(),
            action: 'register'
        };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "../new_files/Campus/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save");
                if(data.status==200){
                    $('#save_campus')[0].reset();
                    $('#camp_active').val('1').trigger('change');
                    pop_up_success(data.message);
                    addCampusRow(data);
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

    // delete/toggle campus
    $(document).on('click','.del',function () {
        var $checkbox = $(this);
        var data_id = $checkbox.data('id');
        var getData = {
            id: data_id,
            action: 'delete'
        };
        swal({
            title: "Are you sure?",
            text: "You are about to change this campus's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "../new_files/Campus/controller.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==500){
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            pop_wrong(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                        }
                    },
                    error:function(error){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
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
            url: "../new_files/Campus/controller.php",
            data: getData,
            dataType:"json",
            success:function(data){
                $('#spinner4_'+data_id).fadeOut('fast');
                $("#e_id").val(data_id);
                $("#e_university_id").val(data.university_id).trigger('change');
                $("#e_camp_full_name").val(data.camp_full_name);
                $("#e_camp_city").val(data.camp_city);
                $("#e_camp_yor").val(data.camp_yor);
                $("#e_camp_active").val(data.camp_active).trigger('change');
                $("#e_camp_comments").val(data.camp_comments);
                $("#f_name").html(data.camp_full_name);
                $('#updateModal').modal('show');
            },
            error:function(error){
                $('#spinner4_'+data_id).fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //update campus
    $("#update_form").submit(function(e){
        e.preventDefault();

        var formData = {
            id: $("#e_id").val(),
            university_id: $("#e_university_id").val(),
            camp_full_name: $("#e_camp_full_name").val(),
            camp_city: $("#e_camp_city").val(),
            camp_yor: $("#e_camp_yor").val(),
            camp_active: $("#e_camp_active").val(),
            camp_comments: $("#e_camp_comments").val(),
            action: 'update'
        };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
        $.ajax({
            url: "../new_files/Campus/controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save Changes");
                if(data.status==200){
                    $('#update_form')[0].reset();
                    $('#updateModal').modal('hide');
                    pop_up_success(data.message);
                    var $row = $("tr[data-row-id='"+data.camp_id+"']");
                    $row.find('.col-university').text(data.university_name || '');
                    $row.find('.col-full-name').text(data.camp_full_name);
                    $row.find('.col-city').text(data.camp_city || '');
                    $row.find('.col-yor').text(data.camp_yor || '');
                    $row.find('.col-comments').text(data.camp_comments || '');
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
