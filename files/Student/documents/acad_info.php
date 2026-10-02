<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Academic information</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Academic information</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-body" id="applications">
                                        <?php
                                            $sql=$conn->prepare("SELECT
                                                                    tbl_register_program_ug.*,
                                                                    tbl_program_type.prg_type_full_name,
                                                                    tbl_faculty.fac_full_name,
                                                                    tbl_department.dept_full_name,
                                                                    tbl_specialization.splz_full_name,
                                                                    tbl_level.level_full_name,
                                                                    tbl_acad_cycle.acad_year,
                                                                    tbl_program_mode.prg_mode_full_name
                                                                FROM
                                                                    tbl_register_program_ug
                                                                INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no = tbl_admission.reg_no
                                                                INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                                                INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                                                INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                                                INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                                                INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                                                INNER JOIN tbl_acad_cycle ON tbl_register_program_ug.acad_cycle_id = tbl_acad_cycle.acad_cycle_id
                                                                INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
                                                                WHERE
                                                                     tbl_register_program_ug.reg_no = '".$identification."' OR tbl_register_program_ug.reg_no = '".$_GET['stu']."';
                                                                    ");
                                            $sql->execute();
                                            $i=1;
                                            while($acad_info=$sql->fetch()){
                                                $sql2=$conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id = '".$acad_info['acad_cycle_id']."'");
                                                $sql2->execute();
                                                $acad_year=$sql2->fetch();
                                        ?>
                                        <div class="card-body row" style="border-radius:5px;margin-bottom:10px;
                                                                        <?php if($acad_info['reg_active']==1){
                                                                        echo 'border: 2px solid green';
                                                                        } else{
                                                                        echo 'border: 2px solid orange';
                                                                        }
                                                                         ?>">
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Student ID</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['reg_no']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job"><b>Program Type</b></div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['prg_type_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-6 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Faculty</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['fac_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Department</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['dept_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Specialization</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['splz_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Level</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['level_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Academic Year</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_year['acad_year']; ?></b></a></div>
                                                    </div>
                                            </div>
                                            <!--<div class="form-group col-12 col-md-2 col-lg-2">-->
                                            <!--        <div class="article-user-details">-->
                                            <!--            <div class="text-job">Intake</div>-->
                                            <!--            <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['intake_month'].' / '.$acad_info['acad_year']; ?></b></a></div>-->
                                            <!--        </div>-->
                                            <!--</div>-->
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Learning Mode</div>
                                                        <div class="user-detail-name"><a href="#"><b><?php echo $acad_info['prg_mode_full_name']; ?></b></a></div>
                                                    </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
        </div>

</script>