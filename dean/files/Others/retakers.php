<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Retakers</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Retakers</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="rtks_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Student ID</th>
                                                    <th scope="col">First Name</th>
                                                    <th scope="col">Last Name</th>
                                                    <th scope="col">Module</th>
                                                    <th scope="col">Specialization</th>
                                                    <th scope="col">Level</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $rtks=$conn->prepare("SELECT * FROM tbl_markby_module WHERE status=16");
                                                    $rtks->execute();
                                                    $i=1;
                                                    while($rtk=$rtks->fetch()){
                                                        $cur_rtks=$conn->prepare("SELECT mm.reg_no,
                                                                                         modu.module_name
                                                                                        FROM tbl_markby_module mm 
                                                                                         INNER JOIN tbl_modules ms ON mm.module_id=ms.module_id
                                                                                         INNER JOIN modules modu ON ms.mod_id=modu.module_id
                                                                                        WHERE mm.reg_no='".$rtk['reg_no']."' AND mm.module_id='".$rtk['module_id']."' AND mm.status=1 AND mm.enrolled=1");
                                                        $cur_rtks->execute();
                                                        while($cr=$cur_rtks->fetch()){
                                                            $sql=$conn->prepare("SELECT 
                                                                                        ad.fname,
                                                                                        ad.lname,
                                                                                        s.splz_full_name,
                                                                                        l.level_full_name
                                                                                    FROM tbl_register_program_ug r
                                                                                        INNER JOIN tbl_admission ad ON r.reg_no=ad.reg_no
                                                                                        INNER JOIN tbl_specialization s ON r.splz_id=s.splz_id
                                                                                        INNER JOIN tbl_level l ON r.level_id=l.level_id
                                                                                    WHERE r.reg_no='".$cr['reg_no']."' ORDER BY r.reg_prg_id DESC LIMIT 1");
                                                            $sql->execute();
                                                            while($rt=$sql->fetch()){
                                                         ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $cr['reg_no']; ?></td>
                                                            <td><?php echo $rt['fname']; ?></td>
                                                            <td><?php echo $rt['lname']; ?></td>
                                                            <td><?php echo $cr['module_name']; ?></td>
                                                            <td><?php echo $rt['splz_full_name']; ?></td>
                                                            <td><?php echo $rt['level_full_name']; ?></td>
                                                        </tr>
                                                        <?php }}} ?>
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
    $('#rtks_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
});
</script>