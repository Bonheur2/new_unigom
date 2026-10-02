<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Position</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Voting</a></div>
                <div class="breadcrumb-item"><a href="#">Position</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Position</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_position" action="save_position" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0">
                                        <div class="form-group">
                                            <label>Position</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="position" placeholder="Type here" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="description" placeholder="Type here" required>
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
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <h4>Registered Position</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="pos_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_voting_position ORDER BY id ASC");
                                        $sql->execute();
                                        $i = 1;
                                        while ($pos = $sql->fetch()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td><?php echo $pos['position']; ?></td>
                                                <td><?php echo $pos['description']; ?></td>
                                                <th>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $pos['id']; ?>" class="btn btn-icon btn-light btn-sm edit"><span id="spinner4_<?php echo $pos['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $pos['id']; ?>" <?php echo $pos['status'] == 1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $pos['id']; ?>"></span>&nbsp;
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
                        <h5 class="modal-title">Updating <span id="pos_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group">
                            <label>Position</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="position" name="position" placeholder="Type here" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="description" name="description" placeholder="Type here" required>
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
    $(document).ready(function() {
        $('#pos_table').DataTable({
            "aLengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "All"]
            ],
            "iDisplayLength": 5
        });

        //save position
        $("#save_position").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Votes/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if (data.status == 200) {
                        $('#save_position')[0].reset();
                        pop_up_success(data.message);
                        $('#pos_table').load(location.href + " #pos_table");
                    }
                    if (data.status == 400) {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");

                }
            });
        });

        //pre-update View
        $(document).on('click', '.edit', function() {
            var data_id = $(this).data('id');
            var getData = {
                id: data_id,
                action: 'view'
            };
            $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Votes/controller.php",
                data: getData,
                dataType: "json",
                success: function(data) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    $("#id").val(data_id);
                    $("#position").val(data.position);
                    $("#description").val(data.description);
                    $("#pos_name").html(data.position);
                    $('#updateModal').modal('show');
                },
                error: function(error) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });

        //update position
        $("#update_form").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Votes/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if (data.status == 200) {
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#pos_table').load(location.href + " #pos_table");
                    }
                    if (data.status == 400) {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });

        // activate/deactivate position
        $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData = {
                id: data_id,
                action: 'delete'
            };
            swal({
                title: "Are you sure?",
                text: "You are about to change this position's status! This operation will affect different reports",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Votes/controller.php",
                        data: getData,
                        dataType: "json",
                        success: function(data) {
                            $('#spinner3_' + data_id).fadeOut('fast');
                            if (data.status == 400) {
                                pop_wrong(data.message);
                            } else if (data.status == 200) {
                                pop_up_success(data.message);
                                $('#pos_table').load(location.href + " #pos_table");
                            }
                        },
                        error: function(error) {
                            $('#spinner3_' + data_id).fadeOut('fast');
                            pop_wrong("Something went wrong!");
                        }
                    });
                } else {
                    swal("operation cancelled!!");
                }
            });
        });
    });

  
</script>