<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="voter" value="<?php echo $identification; ?>">
    <section class="section">
        <div class="section-header">
            <h3>Vote</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Election</a></div>
                <div class="breadcrumb-item"><a href="#">candidates</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <?php
                    $today = date("Y-m-d H:i:s");
                    $stmt0 = $conn->prepare("SELECT * FROM tbl_voting_sessions WHERE start <= ? AND end >=  ? AND status = 1 ORDER BY id ASC LIMIT 1");
                    $stmt0->execute([$today, $today]);
                    $voteId = $stmt0->fetch()['id'];
                
                    $stmt = $conn->prepare("SELECT * FROM tbl_voting_position WHERE status = 1 ORDER BY id ASC");
                    $stmt->execute();
                    while ($pos = $stmt->fetch()) {
                        $stmte = $conn->prepare("SELECT * FROM tbl_votes WHERE voter = ? AND vote_id = ? AND position_id = ?");
                        $stmte->execute([$identification, $voteId, $pos['id']]);
                        $voted = $stmte->rowCount();
                ?>
                <div class="col-12 col-sm-12 col-lg-11 row m-auto" style="padding: 10px; border: 2px solid #002D62; border-radius: 5px; margin-bottom: 20px !important;">
                    <h5 class="col-12 col-sm-12 col-lg-12 text-center"><?php echo $pos['position']; ?></h5>
                    <?php
                        $sql = $conn->prepare("SELECT can.*, 
                                                    ad.fname, 
                                                    ad.lname
                                                    FROM tbl_candidates can 
                                                        INNER JOIN tbl_admission ad ON can.candidate = ad.reg_no
                                                        INNER JOIN tbl_voting_sessions sess ON can.vote_id = sess.id
                                                    WHERE can.position_id = ? AND can.campus_id = 1 AND can.status = 1 AND sess.status = 1
                                                    ORDER BY can.id ASC");
                        $sql->execute([$pos['id']]);
                        while ($can = $sql->fetch()) {
                    ?>
                
                    <div class="col-12 col-sm-4 col-lg-4">
                        <div class="card">
                            <div class="collapse show" id="mycard-collapse">
                                <div class="card-body text-center mb-0">
                                    <img src="<?php echo $can['post_file']; ?>" width="100%">
                                    <br/><br/>
                                    <h6><?php echo $can['fname']." ".$can['lname']; ?></h6>
                                    <p><?php echo $can['post_text']; ?></p>
                                </div>
                                
                                <div class="card-footer text-right">
                                    <?php if($voted == 0){ ?>
                                    <button type="button" class="btn btn-primary btn-sm col-12 vote" data-id="<?php echo $can['id']; ?>"><span id="spinner_<?php echo $can['id']; ?>"></span> vote</button>
                                    <?php } else{ ?>
                                    <button type="button" class="btn btn-secondary btn-sm col-12">voted</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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