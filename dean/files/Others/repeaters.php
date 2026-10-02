<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Repeaters</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Repeaters</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="rpts_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Student ID</th>
                                                    <th scope="col">First Name</th>
                                                    <th scope="col">Last Name</th>
                                                    <th scope="col">Specialization</th>
                                                    <th scope="col">Level</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 LIMIT 1");
                                                    $acad->execute();
                                                    $ac=$acad->fetch();
                                                
                                                    $rpts=$conn->prepare("SELECT * FROM tbl_register_program_ug WHERE acad_cycle_id!='".$ac['acad_cycle_id']."' AND reg_active=16");
                                                    $rpts->execute();
                                                    $i=1;
                                                    while($rpt=$rpts->fetch()){
                                                        $cur_rpts=$conn->prepare("SELECT r.reg_no,
                                                                                         ad.fname,
                                                                                         ad.lname,
                                                                                         s.splz_full_name,
                                                                                         l.level_full_name
                                                                                    FROM tbl_register_program_ug r
                                                                                         INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                                                                         INNER JOIN tbl_specialization s ON r.splz_id=s.splz_id
                                                                                         INNER JOIN tbl_level l ON r.level_id=l.level_id
                                                                                        WHERE r.acad_cycle_id='".$ac['acad_cycle_id']."' AND 
                                                                                            r.prg_type='".$rpt['prg_type']."' AND 
                                                                                            r.splz_id='".$rpt['splz_id']."' AND
                                                                                            r.level_id='".$rpt['level_id']."' AND
                                                                                            r.reg_no='".$rpt['reg_no']."' AND 
                                                                                            r.reg_active=1");
                                                        $cur_rpts->execute();
                                                        while($cr=$cur_rpts->fetch()){
                                                         ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $cr['reg_no']; ?></td>
                                                            <td><?php echo $cr['fname']; ?></td>
                                                            <td><?php echo $cr['lname']; ?></td>
                                                            <td><?php echo $cr['splz_full_name']; ?></td>
                                                            <td><?php echo $cr['level_full_name']; ?></td>
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
            </section>
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#rpts_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
});
</script>