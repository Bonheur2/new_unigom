                <!-- Start app main Content -->
                    <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Balance transfer</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">transfer</a></div>
                                </div><br>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-5">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New transfer</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <div class="form-group col-12">
                                                        <label>Student ID</label><br>
                                                        <input type="text" class="form-control" name="reg_no" id="reg_no">
                                                    </div>
                                                    <div class="form-group col-12">
                                                        <center>
                                                            <button id="load" type="button" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">load balance</span></button>
                                                        </center>
                                                    </div> 
                                                </div>
                                                <div class="card-body pt-0" id="contents"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--transfers-->
                                    <div class="col-12 col-sm-7 col-lg-7">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Recorded transfers</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse-2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body collapse show" id="mycard-collapse-2">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm" id="transfer_table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Student ID</th>
                                                            <th scope="col">Source</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Date </th></th>
                                                            <th scope="col">Action</th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        ini_set('display_errors', 1);
                                                        ini_set('display_startup_errors', 1);
                                                        error_reporting(E_ALL);
                                                            $sql=$conn->prepare("SELECT DISTINCT(r.reg_no),p.*,f.name as fee_name,pr.campus_id
                                                                                    FROM payment_trial p 
                                                                                        INNER JOIN tbl_fee_category f ON p.fee_id=f.id
                                                                                        INNER JOIN tbl_register_program_ug r ON p.reg_no=r.reg_no
                                                                                        INNER JOIN tbl_program_type pr ON r.prg_type=pr.prg_type_id
                                                                                    WHERE pr.campus_id='".$camp_id."' AND p.status=1  ORDER BY p.recorded_date DESC");
                                                            $sql->execute();
                                                            $i=1;
                                                            while($pay=$sql->fetch()){
                                                         ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $pay['reg_no']; ?></td>
                                                            <td><?php echo $pay['slip_no']; ?></td>
                                                            <td><?php echo number_format($pay['amount'],2); ?></td>
                                                            <td><?php echo $pay['date']; ?></td>
                                                            <th>  
                                                                <button type="button" class="btn btn-icon btn-light btn-sm del" data-id="<?php echo $pay['id']; ?>"><span id="spinner_<?php echo $pay['id']; ?>"></span>&nbsp;<i class="fas fa-trash"></i>&nbsp;<span id="indicator_<?php echo $pay['id']; ?>">discard</span></button>
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
                        
                        <!--transfer modal-->
                        <form action="transfer_payment" method="POST" id="transfer_payment">
                            <div class="modal fade" role="dialog" id="transferModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Transfer</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                            <input type="hidden" id="slip_no" name="slip_no">
                                            <input type="hidden" name="action" value="transfer">
                                            <div class="form-group col-12">
                                                <label>Receiver</label>
                                                <input type="text" class="form-control" name="reg_no" id="r_reg_no" placeholder="Type receiver's ID" required>
                                                <h6 id="receiver"></h6>
                                            </div>
                                            <div class="form-group  col-12">
                                                <label>Intake</label><br>
                                                <select class="form-control select2" style="width:100%" name="intake_id">
                                                    <option disabled selected>--choose one--</option>
                                                    <?php
                                                        $sql_intake=$conn->prepare("SELECT  i.intake_id,
                                                                                            i.intake_month,
                                                                                            p.prg_type_full_name,
                                                                                            c.camp_id,
                                                                                            a.acad_year
                                                                                        FROM tbl_intake i
                                                                                            INNER JOIN tbl_program_type p ON i.prg_type=p.prg_type_id
                                                                                            INNER JOIN tbl_campus c ON p.campus_id=c.camp_id
                                                                                            INNER JOIN tbl_acad_cycle a ON i.acad_cycle_id=a.acad_cycle_id
                                                                                        WHERE c.camp_id='".$camp_id."'");
                                                        $sql_intake->execute();
                                                        while($intake=$sql_intake->fetch()){
                                                    ?>
                                                    <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                                <span id="spinner1"></span>
                                            </div>
                                            <div class="form-group col-12">
                                                <label>Fee Category</label><br>
                                                <select class="form-control select2" style="width:100%" name="fee_id" required>
                                                    <option disabled selected>--choose one--</option>
                                                    <?php
                                                        $sql_fee=$conn->prepare("SELECT id,name FROM fee_category WHERE status=1 ORDER BY name ASC");
                                                        $sql_fee->execute();
                                                        while($fee=$sql_fee->fetch()){
                                                    ?>
                                                    <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-12">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" name="amount" id="amount" min="0.0000000000001" step=".01" required>
                                            </div>
                                            <div class="form-group col-12">
                                                <label>Comment</label>
                                                <textarea class="form-control" name="comment" placeholder="your reason goes here" rows="5" maxlength="100" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-whitesmoke br">
                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Leave</button>
                                            <button type="submit" class="btn btn-primary btn-sm" id="con"><span id="spinner2"></span>&nbsp;<span id="indicator2">Confirm</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end transfer modal-->
                    </div>
                    
                    
                    
                
    <!--javascript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    
    <script>
    $(document).ready(function(){

    $('#transfer_table').DataTable({
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
        
        
        //load student and dept info  
        $(document).on('click', '#load',function () {
            var formdata = {
                stu: $("#reg_no").val()
            };
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("loading...");
            $('#slip_no').val($("#reg_no").val());
            $.ajax({
                type: "POST",
                url: "/files/Payment/load_balance.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                success: function (data) {
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("load balance");
                    $('#main').removeClass('col-sm-12 col-lg-12').addClass('col-sm-7 col-lg-7');
                    $("#contents").html(data);
                    $("#amount").val($("#bal").val());
                    $("#amount").attr('max',$("#bal").val());
                    
                    $('#info').attr('hidden',false);
                },
                error:function(error){
                    $('#indicator').html("load balance");
                    $('#spinner').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });
    
        $(document).on('click', '#trans',function () {
            $("#transferModal").modal('show');
        });
        
        $(document).on('keyup', '#r_reg_no',function () {
            var formdata = {
                keyword: $("#r_reg_no").val(),
                action: 'search'
            };
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: formdata,
                dataType:"JSON",
                success: function (data) {
                    $('#receiver').html(data[0].fname+" "+data[0].lname);
                },
                error:function(error){
                    pop_wrong("Something went wrong!");
                }
            });
        });
        //Transfer payment
        $("#transfer_payment").submit(function(e){
                e.preventDefault();
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $('#con').attr('disabled',true);
            $.ajax({
                url: "/files/Payment/payment_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save payment");
                    $('#con').attr('disabled',false);
                    if(data.status==200){
                        $("#transferModal").modal('hide');
                        pop_up_success(data.message);
                        $('#transfer_table').load(location.href + " #transfer_table");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save payment");
                    $('#con').attr('disabled',false);
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        // discard transfer
        $(document).on('click','.del', function() {
            var data_id=$(this).data('id');
            var formData= {
                id: data_id,
                action:'revert_transfer'
            }
            swal({
                title: "Are you sure?",
                text: "This can't be undone",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator_'+data_id).html('processing...');
                $.ajax({
                    type: "POST",
                    url: "/files/Payment/payment_controller.php",
                    data: formData,
                    dataType: "JSON",
                    success:function(data){
                        $('#spinner_'+data_id).fadeOut('fast');
                        $('#indicator_'+data_id).html('discard');
                        if(data.status==401){
                            pop_info(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                            $('#transfer_table').load(location.href + " #transfer_table");
                        }
    				},
    				error:function(error){
    				    $('#indicator_'+data_id).html('discard');
    				    $('#spinner_'+data_id).fadeOut('fast');
                        pop_wrong("Something went wrong!");
    				}
                });
                }
               else {
                    swal("operation Cancelled!!");
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
       function pop_info(feedback) {
            iziToast.info({
            title: 'Ooops',
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