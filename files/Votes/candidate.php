<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Candidates</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Voting</a></div>
                <div class="breadcrumb-item"><a href="#">Candidates</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Candidate</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body">
                                <form id="register" action="save_candidate" method="POST">
                                    <input type="hidden" name="action" value="save_candidate">
                                    <input type="hidden" name="campus_id" value="<?php echo $camp_id; ?>">
                                    <div class="card-body p-0 row">
                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Voting Session</label><br>
                                            <select class="form-control select2" style="width:100%" name="vote_id" required>
                                                <option disabled selected>--choose one--</option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_voting_sessions WHERE status = 1");
                                                    $sql->execute();
                                                    while($sess = $sql->fetch()){
                                                ?>
                                                <option value="<?php echo $sess['id']; ?>"><?php echo $sess['vote_session']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Position</label><br>
                                            <select class="form-control select2" style="width:100%" name="position_id" required>
                                                <option disabled selected>--choose one--</option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_voting_position");
                                                    $sql->execute();
                                                    while($pos = $sql->fetch()){
                                                ?>
                                                <option value="<?php echo $pos['id']; ?>"><?php echo $pos['position']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Student ID</label><br>
                                            <select class="form-control select2" style="width:100%" name="candidate" required>
                                                <option disabled selected>--choose one--</option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT DISTINCT(r.reg_no), a.fname, a.lname
                                                                                    FROM tbl_register_program_ug r
                                                                                        INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                                        INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                                    WHERE p.campus_id='".$camp_id."' AND r.reg_active = 1");
                                                    $sql->execute();
                                                    while($stu = $sql->fetch()){
                                                ?>
                                                <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Post file</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="post_file" accept="image/*" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-2 col-lg-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
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
                            <h4>Registered Candidates</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="candidate_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Candidate</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Vote</th> 
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = $conn->prepare("SELECT can.*, 
                                                                        ad.fname, 
                                                                        ad.lname, 
                                                                        pos.position,
                                                                        sess.vote_session
                                                                        FROM tbl_candidates can 
                                                                            INNER JOIN tbl_admission ad ON can.candidate = ad.reg_no
                                                                            INNER JOIN tbl_voting_position pos ON can.position_id = pos.id
                                                                            INNER JOIN tbl_voting_sessions sess ON can.vote_id = sess.id
                                                                        WHERE can.status = 1 AND pos.status = 1 AND sess.status = 1
                                                                        ORDER BY can.id ASC");
                                            $sql->execute();
                                            $i = 1;
                                            while ($can = $sql->fetch()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td><?php echo $can['fname']." ".$can['lname']; ?></td>
                                                <td><?php echo $can['position']; ?></td>
                                                <td><?php echo $can['vote_session']; ?></td>
                                                <th>
                                                    <div class="buttons row">
                                                        <button type="button" data-id="<?php echo $can['id']; ?>" class="btn btn-icon btn-light btn-sm edit"><span id="spinner4_<?php echo $can['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit</button>
                                                        <label class="custom-switch btn btn-light btn-sm">
                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $can['id']; ?>" <?php echo $can['status'] == 1 ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $can['id']; ?>"></span>&nbsp;
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-12 col-sm-6 col-lg-6">
                            <img src="" width="100%" id="cand_post">
                        </div>
                        <div class="form-group col-12 col-sm-6 col-lg-6 row">
                            <input type="hidden" id="id" name="id">
                            <input type="hidden" name="action" value="update_candidate">
                            <input type="hidden" name="campus_id" value="<?php echo $camp_id; ?>">
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <label>Voting Session</label><br>
                                <select class="form-control select2" style="width:100%" name="vote_id" id="vote_id" required>
                                    <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_voting_sessions WHERE status = 1");
                                        $sql->execute();
                                        while($sess = $sql->fetch()){
                                    ?>
                                    <option value="<?php echo $sess['id']; ?>"><?php echo $sess['vote_session']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <label>Position</label><br>
                                <select class="form-control select2" style="width:100%" name="position_id" id="position_id" required>
                                    <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_voting_position");
                                        $sql->execute();
                                        while($pos = $sql->fetch()){
                                    ?>
                                    <option value="<?php echo $pos['id']; ?>"><?php echo $pos['position']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <label>Student ID</label><br>
                                <select class="form-control select2" style="width:100%" name="candidate" id="candidate" required>
                                    <?php
                                        $sql = $conn->prepare("SELECT DISTINCT(r.reg_no), a.fname, a.lname
                                                                        FROM tbl_register_program_ug r
                                                                            INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                            INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                        WHERE p.campus_id='".$camp_id."' AND r.reg_active = 1");
                                        $sql->execute();
                                        while($stu = $sql->fetch()){
                                    ?>
                                    <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <label>Post file</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="post_file" id="post_file" accept="image/*">
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
    $(document).ready(function() {
        $('#candidate_table').DataTable({
            "aLengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "All"]
            ],
            "iDisplayLength": 5
        });

        //save candidate
        $("#register").submit(function(e) {
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
                        pop_up_success(data.message);
                        $('#candidate_table').load(location.href + " #candidate_table");
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
                action: 'view_candidate'
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
                    
                    var selectElement = document.getElementById('vote_id');
                    var selectElement2 = document.getElementById('position_id');
                    var selectElement3 = document.getElementById('candidate');
                    
                    var selectedOption = selectElement.querySelector('option[value="' + data.vote_id + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.position_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data.candidate + '"]');
                    
                    if (selectedOption) {
                      selectedOption.selected = true;
                      selectElement.prepend(selectedOption);
                    }

                    if (selectedOption2) {
                      selectedOption2.selected = true;
                      selectElement2.prepend(selectedOption2);
                    }
                    
                    if (selectedOption3) {
                      selectedOption3.selected = true;
                      selectElement3.prepend(selectedOption3);
                    }
                    
                    $("#cand_post").attr("src", data.post_file);
                    
                    $('#updateModal').modal('show');
                },
                error: function(error) {
                    $('#spinner4_' + data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });

        //update candidate
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
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#candidate_table').load(location.href + " #candidate_table");
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

        // activate/deactivate candidates
        $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData = {
                id: data_id,
                action: 'delete_candidate'
            };
            swal({
                title: "Are you sure?",
                text: "You are about to change this candidate's status! This operation will affect different reports",
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
                                $('#candidate_table').load(location.href + " #candidate_table");
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