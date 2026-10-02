<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Statistics</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Invoices by campus</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="default" aria-selected="true"><b>General</b></a></li>
                                                <li class="nav-item"><a class="nav-link" id="specific-tab" data-toggle="tab" href="#specific" role="tab" aria-controls="specific" aria-selected="false"><b>Specific</b></a></li>
                                            </ul>
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show table-responsive active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                    <table class="table table-hover table-sm" id="stats_table">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Campus | Program Type</th>
                                                            <th>Intake</th>
                                                            <th>Amount</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $sql=$conn->prepare("SELECT SUM(inv.balance) AS invoice, 
                                                                                            i.intake_month,
                                                                                            ac.acad_year,
                                                                                            p.prg_type_full_name,
                                                                                            c.camp_full_name
                                                                                        FROM tbl_invoice inv LEFT JOIN tbl_intake i ON inv.intake_id=i.intake_id
                                                                                                             LEFT JOIN tbl_acad_cycle ac ON i.acad_cycle_id=ac.acad_cycle_id
                                                                                                             LEFT JOIN tbl_program_type p ON i.prg_type=p.prg_type_id
                                                                                                             LEFT JOIN tbl_campus c ON p.campus_id=c.camp_id
                                                                                        GROUP BY inv.intake_id
                                                                                        ORDER BY inv.intake_id DESC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($data=$sql->fetch()){
                                                                    $total+=$data['invoice'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['camp_full_name']." | ".$data['prg_type_full_name']; ?></td>
                                                                <td><?php echo $data['intake_month']." | ".$data['acad_year']; ?></td>
                                                                <td><?php echo number_format($data['invoice'],2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div class="row" style="display:flex; flex-direction:row-reverse; margin-top:30px; padding:10px;">
                                                        <button type="button" class="btn btn-success">Total invoice: <?php echo number_format($total,2); ?></button>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="specific" role="tabpanel" aria-labelledby="specific-tab">
                                                    <form id="load_invoice_stats" action="load_invoice_stats">
                                                        <div class="card-body pb-0 row">
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Campus | Program Type</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="campus">
                                                                <?php
                                                                    $sql_campus=$conn->prepare("SELECT  * FROM tbl_campus WHERE camp_active=1");
                                                                    $sql_campus->execute();
                                                                    while($camp=$sql_campus->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Intake</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="intake">
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
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                                <label>Fee category</label><br>
                                                                <select class="form-control select2" style="width:100%;" name="fee">
                                                                <?php
                                                                    $sql_fee=$conn->prepare("SELECT * FROM fee_category WHERE status=1");
                                                                    $sql_fee->execute();
                                                                    while($fee=$sql_fee->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $fee['id']; ?>"><?php echo $fee['name']; ?> </option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group  col-12 col-sm-4 col-lg-4" style="padding-top:10px;">
                                                                <button type="submit" class="btn btn-primary"><span id="spinner20"></span>&nbsp;<span id="indicator20">Load data</span></button>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-lg-12" id="statistics">
                                                            </div>
                                                        </div>
                                                    </form>
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
       
    $('#spec_stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
        
    //load_stats
    $("#load_invoice_stats").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Loading...");
            $.ajax({
                url: "/files/Statistics/finance/load_camp_invoice_stats.php",
                type: "POST",
                data: formData,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
                    $("#statistics").html(data);
                    $("#spec_stats_table").DataTable().draw();
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Load data");
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