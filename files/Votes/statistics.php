<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="voter" value="<?php echo $identification; ?>">
    <section class="section">
        <div class="section-header">
            <h3>Statistics</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Election</a></div>
                <div class="breadcrumb-item"><a href="#">results</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <?php
                    $stmt0 = $conn->prepare("SELECT * FROM tbl_voting_sessions WHERE status = 1 ORDER BY id DESC LIMIT 1");
                    $stmt0->execute();
                    $voteId = $stmt0->fetch()['id'];
                
                    $stmt = $conn->prepare("SELECT * FROM tbl_voting_position WHERE status = 1 ORDER BY id ASC");
                    $stmt->execute();
                    while ($pos = $stmt->fetch()) {
                ?>
                <div class="col-12 col-sm-12 col-lg-11 table-responsive m-auto" style="padding: 10px; border: 2px solid #002D62; border-radius: 5px; margin-bottom: 20px !important;">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th colspan="4"><?php echo $pos['position']; ?></th>
                            </tr>
                            <tr>
                                <th>Rank</th>
                                <th>ID</th>
                                <th>Fullname</th>
                                <th>Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = $conn->prepare("SELECT can.*, 
                                                            ad.fname, 
                                                            ad.lname
                                                            FROM tbl_candidates can 
                                                                INNER JOIN tbl_admission ad ON can.candidate = ad.reg_no
                                                                INNER JOIN tbl_voting_sessions sess ON can.vote_id = sess.id
                                                            WHERE can.position_id = ? AND can.campus_id = 1 AND can.status = 1 AND sess.status = 1
                                                            ORDER BY votes DESC");
                                $sql->execute([$pos['id']]);
                                $i = 1;
                                while ($can = $sql->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $can['candidate']; ?></td>
                                <td><?php echo $can['fname']." ".$can['lname']; ?></td>
                                <td><?php echo $can['votes']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.vote', function() {
            var data_id = $(this).data('id');
            var voter = $("#voter").val();
            var getData = {
                candidate: data_id,
                voter: voter,
                action: 'vote'
            };
            swal({
                title: "confirm!",
                text: "You are about to vote this candidate! This action is not reversible",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $('.vote').attr('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "/files/Votes/controller.php",
                        data: getData,
                        dataType: "json",
                        success: function(data) {
                            $('#spinner_' + data_id).fadeOut('fast');
                            if (data.status == 200) {
                                pop_up_success(data.message);
                                setTimeout(function(){
                                    window.location.reload();
                                }, 2000);
                            }
                        },
                        error: function(error) {
                            $('#spinner_' + data_id).fadeOut('fast');
                            $('.vote').removeAttr('disabled');
                            pop_wrong("Something went wrong!");
                        }
                    });
                } else {
                    swal("vote cancelled!!");
                }
            });
        });
    });
</script>