<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3></h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Statistics</a></div>
                        <div class="breadcrumb-item"><a href="#">By School</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Student statistics by school</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <table class="table table-hover table-sm" id="stat_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">School</th>
                                                    <th scope="col">Program Type</th>
                                                    <th scope="col">Campus</th>
                                                    <th scope="col">Numbers</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT count(DISTINCT(tbl_register_program_ug.reg_no)) as count,
                                                                        tbl_faculty.fac_full_name,
                                                                        tbl_program_type.prg_type_full_name,tbl_program_type.prg_type_id
                                                                        FROM tbl_register_program_ug 
                                                                        INNER JOIN tbl_faculty ON 
                                                                        tbl_register_program_ug.fac_id=tbl_faculty.fac_id
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_faculty.prg_type=tbl_program_type.prg_type_id
                                                                        WHERE tbl_register_program_ug.reg_active IN(1) GROUP BY tbl_register_program_ug.fac_id ORDER BY tbl_faculty.fac_full_name ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($stat=$sql->fetch()){
                                                        $prgType=$stat['prg_type_id'];
                                                        $camp=$conn->prepare("SELECT cmp.camp_full_name FROM tbl_campus cmp INNER JOIN 
                                                        tbl_program_type prg ON prg.campus_id=cmp.camp_id WHERE prg.prg_type_id='".$prgType."'");
                                                        $camp->execute();
                                                        $dataCampus=$camp->fetch();
                                                        ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i++; ?></th>
                                                            <td><?php echo $stat['fac_full_name']; ?></td>
                                                            <td><?php echo $stat['prg_type_full_name']; ?></td>
                                                            <td><?php echo $dataCampus['camp_full_name']; ?></td>
                                                            <td><?php echo $stat['count']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if($sql->rowCount()>0){ ?>
                                    <div class="card-footer" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
                                        <button type="button" class="btn btn-success" onclick="exportTableToExcel('stat_table','statistics_by_faculty')"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
                                    </div>
                                    <?php } ?>
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
    $('#stat_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });
</script>