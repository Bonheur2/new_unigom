<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Filter Students By Sponsors</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Filter Students By Sponsors</a></div>
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
                                                            
                                                            <th>Sponsor Name</th>
                                                            <th>Short Name</th>
                                                            <th>Contacts</th>
                                                            
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $sql=$conn->prepare("SELECT * FROM tbl_stu_sponsor ss
                                                                                           INNER JOIN tbl_sponsor s ON ss.sponsor_id=s.spon_id
                                                                                            INNER JOIN fee_category f ON ss.fee_id=f.id
                                                                                        ORDER BY ss.status ASC");
                                                                $sql->execute(); 
                                                                $i=1;
                                                                while($data=$sql->fetch()){
                                                                    $s_regno=$data['reg_no'];
                                                                $sql2=$conn->prepare("SELECT * FROM tbl_admission WHERE reg_no='".$s_regno."' ");
                                                                $sql2->execute(); 
                                                               while($std=$sql2->fetch()){
                                                                  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $data['reg_no']." | ".$std['fname']." ".$std['lname']; ?></td>
                                                                
                                                                <td><?php echo $data['spon_full_name']; ?></td>
                                                                <td><?php echo $data['spon_short_name']; ?></td>
                                                                <td><?php echo $data['phone']; ?></td>
                                                                
                                                            </tr>
                                                        <?php }} ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
        <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <a href="/files/Sponsors/print_student_by_spon_report?splz=<?php echo $splz; ?>&level=<?php echo $level; ?>&intake=<?php echo $intake; ?>&type=<?php echo $type; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>&nbsp; Print</a>
        </div>
     
                    </section>
                </div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        function updateExportUrl() {
            var sponsorId = $('#sponsor').val();
            var intakeId = $('#intake_id').val();
            var modeId = $('#mode_id').val();
            var exportButton = $('#exportButton');

            if (sponsorId && intakeId && modeId) {
                var url = '/files/Sponsors/print_student_sponsor_by_intake?spon_id=' + sponsorId + '&intake_id=' + intakeId + '&prog_mode_id=' + modeId;
                exportButton.attr('href', url);
                exportButton.removeClass('disabled').show();
            } else {
                exportButton.attr('href', '#');
                exportButton.addClass('disabled').hide();
            }
        }

        $('#sponsor, #intake_id, #mode_id').change(updateExportUrl);
    });
</script>
<script>

$(document).ready(function(){

    $('#stats_table').DataTable({    
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
    });
</script>