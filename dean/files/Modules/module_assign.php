<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Modules by Level</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Module to year</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                <div class="card-header">
                                    <div class="col-9 col-md-10">
                                        <h4>Registered Modules</h4>
                                    </div>
                                    
                                    <div class="col-3 col-md-2">
                                        <!--<a  class="btn btn-icon btn-success btn-sm" href="edu?mis=mod_assign_verify"><i class="fas fa-clipboard"></i>&nbsp;Review</a>-->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" id="module_to_year_table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Module Code</th>
                                                <th scope="col">Module Name</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Level</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $sql=$conn->prepare("SELECT modules.module_code,
                                                                    modules.module_name,
                                                                    tbl_program_type.prg_type_full_name,
                                                                    tbl_faculty.fac_full_name,
                                                                    tbl_department.dept_full_name,
                                                                    tbl_level.level_full_name,
                                                                    tbl_modules.*
                                                                    FROM tbl_modules 
                                                                    INNER JOIN tbl_program_type ON 
                                                                    tbl_modules.prg_type=tbl_program_type.prg_type_id
                                                                    INNER JOIN tbl_faculty ON 
                                                                    tbl_modules.fac_id=tbl_faculty.fac_id
                                                                    INNER JOIN tbl_department ON 
                                                                    tbl_modules.dept_id=tbl_department.dept_id
                                                                    INNER JOIN modules ON 
                                                                    modules.module_id=tbl_modules.mod_id
                                                                    INNER JOIN tbl_level ON 
                                                                    tbl_modules.level_id=tbl_level.level_id
                                                                    WHERE tbl_level.status=1 AND tbl_modules.fac_id IN($faculty) ORDER BY tbl_modules.status ASC");
                                                $sql->execute();
                                                $i=1;
                                                while($mods=$sql->fetch()){
                                             ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++; ?></th>
                                                <td><?php echo $mods['module_code']; ?></td>
                                                <td><?php echo $mods['module_name']; ?></td>
                                                <td><?php echo $mods['dept_full_name']; ?></td>
                                                <td><?php echo $mods['level_full_name']; ?></td>
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
        $('#module_to_year_table').DataTable({     
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    });
</script>