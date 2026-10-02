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
                                            <div class="form-group col-12 col-md-2 col-lg-2">
                                                    <div class="article-user-details">
                                                        <div class="text-job">Semester</div>
                                                        <?php
                                                        // Get the student's semesters based on their registration number
                                                        $selectStudentSem = "SELECT * FROM tbl_student_semester WHERE reg_no = :reg_no";
                                                        $stmtStudentSem = $conn->prepare($selectStudentSem);
                                                        $stmtStudentSem->execute(['reg_no' => $_SESSION['Identification']]);
                                                        
                                                        // Fetch the active academic cycle
                                                        $selectAcadCycle = "SELECT * FROM tbl_acad_cycle WHERE  acad_cycle_id = '".$acad_info['acad_cycle_id']."'";
                                                        $stmtAcadCycle = $conn->prepare($selectAcadCycle);
                                                        $stmtAcadCycle->execute();
                                                        $rowAcadCycle = $stmtAcadCycle->fetch(PDO::FETCH_ASSOC);
                                                        
                                                        // Check if an academic cycle is found
                                                        if ($rowAcadCycle) {
                                                            $acadCycleId = $rowAcadCycle['acad_cycle_id'];
                                                        
                                                            foreach ($stmtStudentSem as $rowStudentSem) {
                                                                $semId = $rowStudentSem['sem_id'];
                                                        
                                                                // Fetch semesters based on the academic year and semester ID
                                                                $selectSem = "
                                                                    SELECT ts.*, s.* 
                                                                    FROM tbl_semester ts
                                                                    INNER JOIN semester s ON ts.semester = s.id 
                                                                    WHERE ts.acad_year = :acad_year AND ts.sem_id = :sem_id
                                                                ";
                                                                $stmtSem = $conn->prepare($selectSem);
                                                                $stmtSem->execute([
                                                                    'acad_year' => $acadCycleId,
                                                                    'sem_id' => $semId
                                                                ]);
                                                        
                                                                // Generate the options for each semester found
                                                                while ($sem = $stmtSem->fetch(PDO::FETCH_ASSOC)) {
                                                                    ?>
                                                                    <div class="user-detail-name"><a href="#"><b><?php echo $sem['name']; ?></b></a></div>
                                                                    <?php
                                                                    
                                                                    // echo "<option value=\"{$sem['semester']}\">{$sem['name']}</option>";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                                                                                
                                                      
                                                    </div>
                                            </div>
                                            
                                        </div>
                                        <form method="POST" id="save_semester">
                                            <input type="hidden" name="action" value="save_semester">
                                            <input type="hidden" name="reg_no" value="<?php echo $identification?>">
                                            
                                            
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-6">   
                                            <div class="form-group">
                                            <label>Semester</label>
                                            <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <select class="form-control" name="sem_id">
                                            <option selected disabled>Select semester</option>
                                            <?php
                                            // Fetch the active academic cycle
                                            $selectAcadCycle = "SELECT * FROM tbl_acad_cycle WHERE status = '1'";
                                            $stmtAcadCycle = $conn->prepare($selectAcadCycle);
                                            $stmtAcadCycle->execute();
                                            $rowAcadCycle = $stmtAcadCycle->fetch(PDO::FETCH_ASSOC);
                                            $acadCycleId = $rowAcadCycle['acad_cycle_id'];
                                        
                                            // Fetch all semesters the student is registered for
                                            $selectStudentSem = "SELECT sem_id FROM tbl_student_semester WHERE reg_no = :reg_no";
                                            $stmtStudentSem = $conn->prepare($selectStudentSem);
                                            $stmtStudentSem->execute(['reg_no' => $identification]);
                                            $registeredSemesters = $stmtStudentSem->fetchAll(PDO::FETCH_COLUMN, 0);
                                        
                                            // Convert the array of registered semesters to a comma-separated string for the query
                                            $registeredSemestersList = implode(',', array_map('intval', $registeredSemesters));
                                        
                                           if (!empty($registeredSemesters)) {
                                            $registeredSemestersList = implode(',', array_map('intval', $registeredSemesters));
                                            $whereClause = "AND ts.sem_id NOT IN ($registeredSemestersList)";
                                        } else {
                                            // If no registered semesters, skip the NOT IN clause
                                            $whereClause = "";
                                        }
                                        
                                        $selectSem = "
                                        SELECT ts.*, s.* 
                                        FROM tbl_semester ts
                                        INNER JOIN semester s ON ts.semester = s.id 
                                        WHERE ts.acad_year = :acad_year $whereClause";
                                        $stmtSem = $conn->prepare($selectSem);
                                        $stmtSem->execute([
                                            'acad_year' => $acadCycleId
                                        ]);

                                        
                                            // Output the semesters as options
                                            while ($sem = $stmtSem->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value=\"{$sem['sem_id']}\">{$sem['name']}</option>";
                                            }
                                            ?>
                                        </select>
                    
                                            </div>
                                        </div>
                                        </div>
                                        </div> 
                                        <button type="submit" class="btn btn-info">Register On Semester</button>
                                        </form>
                                    </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
        </div>

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('change', '#sem', function(){
            var getData= {
                    stu: $("#stu").val(),
                    splz: $("#splz").val(),
                    lev: $("#level").val(),
                    prg: $("#prg").val(),
                    sem: $("#sem").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/student.php",
                data: getData,
                success:function(data){
                    $('#spinner5').fadeOut('fast');
                    $('#table').html(data);
				},
				error:function(error){
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        })
        
        $("#save_semester").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        
            $.ajax({
                url: "../files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save option");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                }
             });
          });
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>