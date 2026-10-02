<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Statistics</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Invoices</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="default" aria-selected="true"><b>General</b></a></li>
                                            </ul>
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show table-responsive active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                    <table class="table table-hover table-sm" id="stats_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Student</th>
                                                            <th>Academic year</th>
                                                            <th>Fee category</th>
                                                            <th>Amount</th>
                                                           <!-- <th>Description</th>-->
                                                           <th>Status</th>
                                                           <!--<th>Action</th>-->
                                                           
                                                            
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $sql=$conn->prepare("SELECT inv.*, 
                                                                                            ac.acad_year,
                                                                                            f.name as fee_name,
                                                                                            hr.room_code ,
                                                                                            rs.rest_name,
                                                                                            ad.fname,ad.lname
                                                                                        FROM tbl_invoice inv LEFT JOIN tbl_acad_cycle ac ON inv.acad_cycle_id=ac.acad_cycle_id
                                                                                                             INNER JOIN tbl_admission ad ON inv.reg_no=ad.reg_no
                                                                                                             LEFT JOIN fee_category f ON inv.fee_id=f.id
                                                                                                             LEFT JOIN tbl_hostel_room hr ON inv.room_id=hr.room_id
                                                                                                             LEFT JOIN tbl_restaurant rs ON inv.restaurant_id=rs.rest_id
                                                                                        WHERE invoice_status = 1 AND approval_status=2
                                                                                        ORDER BY inv.acad_cycle_id DESC");
                                                                $sql->execute(); 
                                                                $i=1;
                                                                while($data=$sql->fetch()){
                                                                    $total+=$data['balance'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['reg_no']." | ".$data['fname']." ".$data['lname']; ?></td>
                                                                <td><?php echo $data['acad_year']; ?></td>
                                                                <td><?php echo $data['fee_name']; ?></td>
                                                                <td><?php echo number_format($data['balance'],2); ?></td>
                                                                 <td>Pending</td>
                                                             
                                                               <!-- <td><?php echo $data['comment']!=''?$data['comment']:($data['room_code']!=NULL?$data['room_code']:($data['rest_name']!=NULL?$data['rest_name']:'')); ?></td>-->
                                                              <!--<td><button type="button" data-id="<?php echo $data['id']; ?>" class="btn btn-sm btn-light cancel"><i class="fas fa-toggle-on"></i>&nbsp;<span id="spinner_<?php echo $data['id']; ?>"></span>&nbsp;Approve</button></td>-->
                                                             

                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px; padding:10px;">
                                                        <button type="button" class="btn btn-success">Total invoice: <span  id="tot"><?php echo number_format($total,2); ?></span></button>
                                                    </div>
                                                </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    $('#stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
    });
    
    // Re Approve Invoice cancelling
    $(document).on('click','.cancel',function () {
        var data_id = $(this).data('id');
        var getData= {
                id: data_id,
                action:'cancel'
            };
        swal({
            title: "Are you sure?",
            text: "You are about to Re-Activate this invoice. Are You Sure You Want to this cancel Invoice Request? Click OK to Confirm",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
             $('#spinner_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Invoice/invoice_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_info(data.message);  
                    }
                    else if(data.status==200){
                        pop_up_success(data.message); 
                        $('#stats_table').load(location.href + " #stats_table");
                        $('#tot').load(location.href + " #tot");
                        $('#stats_table').DataTable().draw();
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