<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Lecturers</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Lecturers</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Lecturer</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="lecturer_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">First Name</th>
                                                    <th scope="col">Last Name</th>
                                                    <th scope="col">Email</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT DISTINCT(ml.staff_id),
                                                                        s.staff_family_name,
                                                                        s.staff_first_name,
                                                                        s.email
                                                                        FROM tbl_module_leader ml
                                                                        INNER JOIN tbl_staff s ON ml.staff_id=s.staff_id
                                                                        WHERE s.campus='".$camp_id."'  AND ml.status=1");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($lect=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $lect['staff_family_name']; ?></td>
                                                    <td><?php echo $lect['staff_first_name']; ?></td>
                                                    <td><?php echo $lect['email']; ?></td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#lecturer_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
       });
});
</script>