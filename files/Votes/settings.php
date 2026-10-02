<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Vote Session</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Voting</a></div>
                <div class="breadcrumb-item"><a href="#">Vote Session</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Voting Session</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_vote_session" action="save_vote_session" method="POST">
                                    <input type="hidden" name="action" value="save_session">
                                    <div class="pb-0">
                                        <div class="form-group">
                                            <label>Vote Session</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="vote_session" placeholder="Type here" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Start</label>
                                            <div class="input-group">
                                                <input type="datetime-local" class="form-control" name="start" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>End</label>
                                            <div class="input-group">
                                                <input type="datetime-local" class="form-control" name="end" required>
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
                            <h4>Registered voting sessions</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="vote_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Vote Session</th>
                                            <th scope="col">Start</th>
                                            <th scope="col">End</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_voting_sessions ORDER BY id ASC");
                                        $sql->execute();
                                        $i = 1;
                                        while ($vote = $sql->fetch()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td><?php echo $vote['vote_session']; ?></td>
                                                <td><?php echo $vote['start']; ?></td>
                                                <td><?php echo $vote['end']; ?></td>
                                                <th>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $vote['id']; ?>" class="btn btn-icon btn-light btn-sm edit"><span id="spinner4_<?php echo $vote['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $vote['id']; ?>" <?php echo $vote['status'] == 1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $vote['id']; ?>"></span>&nbsp;
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
                        <h5 class="modal-title">Updating <span id="sess_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" name="action" value="update_session">
                        <div class="form-group">
                            <label>Voting Session</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="vote_session" name="vote_session" placeholder="Type here" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Start</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="start" id="start" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>End</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="end" id="end" required>
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
        $('#vote_table').DataTable({
            "aLengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "All"]
            ],
            "iDisplayLength": 5
        });

        //save Vote Session
        $("#save_vote_session").submit(function(e) {
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
                        $('#save_vote_session')[0].reset();
                        pop_up_success(data.message);
                        $('#vote_table').load(location.href + " #vote_table");
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
                action: 'view_session'
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
                    $("#vote_session").val(data.vote_session);
                    $("#start").val(data.start);
                    $("#end").val(data.end);
                    $("#sess_name").html(data.vote_session);
                    $('#updateModal').modal('show');
                },
                error: function(error) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });

        //update Vote Session
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
                        $('#vote_table').load(location.href + " #vote_table");
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

        // activate/deactivate Vote Session
        $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData = {
                id: data_id,
                action: 'delete_session'
            };
            swal({
                title: "Are you sure?",
                text: "You are about to change this Vote Session's status! This operation will affect different reports",
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
                                $('#vote_table').load(location.href + " #vote_table");
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