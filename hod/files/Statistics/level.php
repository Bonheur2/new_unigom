<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3></h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Statistics</a></div>
                        <div class="breadcrumb-item"><a href="#">By level</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Student statistics by level</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <table class="table table-hover table-sm" id="stat_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Level</th>
                                                    <th scope="col">Numbers</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT count(tbl_register_program_ug.reg_no) as count,
                                                                        tbl_level.level_full_name
                                                                        FROM tbl_register_program_ug 
                                                                        INNER JOIN tbl_level ON 
                                                                        tbl_register_program_ug.level_id=tbl_level.level_id
                                                                        WHERE tbl_register_program_ug.reg_active IN(1) AND tbl_register_program_ug.dept_id IN($department) GROUP BY tbl_register_program_ug.level_id ORDER BY tbl_level.level_full_name ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($stat=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $stat['level_full_name']; ?></td>
                                                    <td><?php echo $stat['count']; ?></td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if($sql->rowCount()>0){ ?>
                                    <div class="card-footer" style="display:flex; flex-direction:row-reverse; margin-top:30px;">
                                        <button type="button" class="btn btn-success" onclick="exportTableToExcel('stat_table','statistics_by_level')"><i class="fas fa-download"></i>&nbsp;Export Excel&nbsp;</button>
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