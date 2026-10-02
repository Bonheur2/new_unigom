                <!-- Start app main Content -->
                    <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Payment negotiation</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Negotiation</a></div>
                                </div><br>
                            </div>
                            <div class="section-body">
                                <div class="card-header" style="display:flex;flex-direction:row;justify-content:space-between;">
                                    <h4>New agreement</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-lg-12" id="main">
                                            <div class="card">
                                                <div class="card-body">
                                                    <form id="negociate" action="negociate" method="POST">
                                                        <input type="hidden" name="action" value="negociate">
                                                        <input type="hidden" name="user" value="<?php echo $identification; ?>">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-6 col-lg-4 stu">
                                                                <label>Student ID</label><br>
                                                                <select class="form-control select2" style="width:100%" name="reg_no" id="reg_no">
                                                                    <option disabled selected>--choose one--</option>
                                                                    <?php
                                                                        $sql_stu=$conn->prepare("SELECT DISTINCT(r.reg_no),
                                                                                                        a.fname,a.lname,
                                                                                                        p.status
                                                                                                        FROM tbl_register_program_ug r
                                                                                                            INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                                                            INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                                                        WHERE p.campus_id='".$camp_id."'");
                                                                        $sql_stu->execute();
                                                                        while($stu=$sql_stu->fetch()){
                                                                            ?>
                                                                    <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span id="spinner1"></span>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-6 col-lg-4 amt">
                                                                <label>Days count</label><br>
                                                                <input type="number" class="form-control" min="1" value="7" id="days" required>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-6 col-lg-4 amt" >
                                                                <label>Start Date</label><br>
                                                                <input type="date" class="form-control" name="start_date" id="s_date" value="<?php echo date('Y-m-d'); ?>" required>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-6 col-lg-4 amt" >
                                                                <label>End Date (<i>automatic</i>)</label><br>
                                                                <input type="date" class="form-control" name="end_date" id="e_date" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" readonly>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn" hidden>
                                                                <center>
                                                                    <button id="neg" type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save Agreement</span></button>
                                                                </center>
                                                            </div> 
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-5 col-lg-5" id="info" hidden>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Debt information</h4>
                                                </div>
                                                <div class="card-body" id="contents">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                 <!--negociations-->
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Registered negotiations</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse-2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body collapse show" id="mycard-collapse-2">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm" id="negociation_table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Student ID</th>
                                                            <th scope="col">Start Date</th></th>
                                                            <th scope="col">End date</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Action</th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $sql=$conn->prepare("SELECT n.*
                                                                                    FROM tbl_invoice_negociation n
                                                                                        INNER JOIN tbl_register_program_ug r ON n.reg_no=r.reg_no AND r.reg_active=1
                                                                                        INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                                    WHERE p.campus_id='".$camp_id."' ORDER BY n.status ASC");
                                                            $sql->execute();
                                                            $i=1;
                                                            while($neg=$sql->fetch()){
                                                         ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $neg['reg_no']; ?></td>
                                                            <td><?php echo $neg['start_date']; ?></td>
                                                            <td><?php echo $neg['end_date']; ?></td>
                                                            <td>
                                                                <?php 
                                                                    if($neg['status']==1){
                                                                        echo "<span class='badge badge-success'>active</span>"; 
                                                                    }else if($neg['status']==2){
                                                                        echo "<span class='badge badge-warning'>inactive</span>"; 
                                                                    }
                                                                    else{
                                                                        echo "<span class='badge badge-light'>canceled</span>"; 
                                                                    }
                                                                ?>
                                                            </td>
                                                            <th>  
                                                                <?php if($neg['status']==1){ ?>
                                                                <button type="button" class="btn btn-icon btn-light btn-sm del" data-id="<?php echo $neg['negociate_id']; ?>"><span id="spinner_<?php echo $neg['negociate_id']; ?>"></span>&nbsp;<i class="fas fa-trash"></i>&nbsp;cancel</button>
                                                                <?php } ?>
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
                    </div>
                    
                
    <!--javascript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    
    <script>
    $(document).ready(function(){
        
        $('#negociation_table').DataTable({     
          "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
       });

    //date manipulation
    $(document).on('click keyup change','#days, #s_date',function () {
        if($("#days").val()===''){
            pop_info("Add days count");
        }
        else{
            var days = parseInt($("#days").val());
            var fromDate=new Date($("#s_date").val());
            var to = new Date(fromDate.setDate(fromDate.getDate() + days));
            $("#e_date").val(to.toISOString().slice(0,10));
        }
    });
        //load student and dept info  
        $(document).on('change', '#reg_no',function () {
            var formdata = {
                stu: $("#reg_no").val()
            };
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $('.amt').attr('hidden',true);
            $('#btn').attr('hidden',true);
            $.ajax({
                type: "POST",
                url: "/files/Payment/load_debt.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $('#main').removeClass('col-sm-12 col-lg-12').addClass('col-sm-7 col-lg-7');
                    $('.amt,.stu').removeClass('col-lg-4').addClass('col-lg-6');
                    $("#contents").html(data);
                    $('.amt').attr('hidden',false);
                    $('#btn').attr('hidden',false);
                    $('#info').attr('hidden',false);
                },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        });
    
        //Negociate payment
        $("#negociate").submit(function(e){
                e.preventDefault();
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $('#pay').attr('disabled',true);
            $.ajax({
                url: "/files/Payment/payment_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save Agreement");
                    $('#neg').attr('disabled',false);
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#negociation_table').load(location.href + " #negociation_table");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save Agreement");
                    $('#neg').attr('disabled',false);
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        // cancel negociation
        $(document).on('click','.del',function (e) {
            var data_id=$(this).data('id');
            var formData= {
                id:data_id,
                action:'cancel_negociation'
            }
            swal({
                title: "Are you sure?",
                text: "You are about to cancel this agreeement!, this can't be undone",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/Payment/payment_controller.php",
                    data: formData,
                    dataType: "JSON",
                    success:function(data){
                        $('#spinner_'+data_id).fadeOut('fast');
                        if(data.status==401){
                            pop_info(data.message);
                        }
                        else if(data.status==200){
                            pop_up_success(data.message);
                            $('#negociation_table').load(location.href + " #negociation_table");
                        }
    				},
    				error:function(error){
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