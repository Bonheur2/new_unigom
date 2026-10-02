<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Marks Access</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Marks</a></div>
                <div class="breadcrumb-item"><a href="#">Access</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <h4>Access list</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="access_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Program Type</th>
                                            <th scope="col">Faculty</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Specialization</th>
                                            <th scope="col">Level</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql=$conn->prepare("SELECT DISTINCT p.prg_type_full_name, 
                                                                            f.fac_full_name, 
                                                                            d.dept_full_name, 
                                                                            s.splz_id, 
                                                                            s.splz_full_name, 
                                                                            l.level_id, 
                                                                            l.level_full_name
                                                                        FROM tbl_register_program_ug r
                                                                            INNER JOIN tbl_program_type p ON 
                                                                                p.prg_type_id = r.prg_type
                                                                            INNER JOIN tbl_faculty f ON 
                                                                                f.fac_id = r.fac_id
                                                                            INNER JOIN tbl_department d ON 
                                                                                d.dept_id = r.dept_id
                                                                            INNER JOIN tbl_specialization s ON 
                                                                                s.splz_id = r.splz_id
                                                                            INNER JOIN tbl_level l ON 
                                                                                l.level_id = r.level_id
                                                                        WHERE p.campus_id = ? AND r.reg_active = ? ORDER BY p.prg_type_full_name ASC");
                                        $sql->execute([$camp_id, 1]);
                                        $i=1;
                                        while($class=$sql->fetch()){
                                            $id = $class['splz_id'].$class['level_id'];
                                            
                                            //check lock
                                            $sql2=$conn->prepare("SELECT id FROM tbl_marks_lock WHERE splz_id = ? AND level_id = ?");
                                            $sql2->execute([$class['splz_id'], $class['level_id']]);
                                            $locked = $sql2->rowCount();
                                     ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $class['prg_type_full_name']; ?></td>
                                            <td><?php echo $class['fac_full_name']; ?></td>
                                            <td><?php echo $class['dept_full_name']; ?></td>
                                            <td><?php echo $class['splz_full_name']; ?></td>
                                            <td><?php echo $class['level_full_name']; ?></td>
                                            <th>  
                                                <label class="custom-switch btn btn-light btn-sm">
                                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input lock" data-splz="<?php echo $class['splz_id']; ?>" data-level="<?php echo $class['level_id']; ?>" data-id="<?php echo $id; ?>" <?php echo $locked==0?'checked':''; ?>>
                                                    <span class="custom-switch-indicator"></span><span id="spinner_<?php echo $id; ?>"></span>&nbsp;
                                                </label>
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
        $('#access_table').DataTable({     
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
        });
    
        // lock
        $(document).on('click','.lock',function () {
            const href = $(this).data('id');
            const getData= {
                    splz: $(this).data('splz'),
                    level: $(this).data('level'),
                    action:'lock'
                    };
            swal({
                title: "Are you sure?",
                text: "You are about to change access to marks!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner_'+href).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Marks/mark_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner_'+href).fadeOut('fast');
                            if(data.status==200){
                               pop_up_success(data.message);
                            }
                            else if(data.status==401){
                               pop_info(data.message);
                            }
        				},
        				error:function(error){
        				    $('#spinner_'+href).fadeOut('fast');
        				    pop_wrong("Something went wrong!");
        				}
                    });
                }
                else {
                    swal("Operation cancelled!!");
                }
            });
        });
    });
</script>