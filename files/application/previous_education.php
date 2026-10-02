<?php


    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                
                
                <div class="section-header">
                    <h3>2. Previous Education</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Previous Educaction</a></div>
                    </div>
                </div>
                <?php PreviousEducation($conn, $code); ?>
                <div class="section-body">
                    
                    
                    
                    <div class="row" id="profile">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">Matriculation Details</h4>
                                    </div>
                                    <div>
                                        <button class="btn btn-info me-2" id="add_M">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                        <a data-collapse="#mycard-collapse27" class="btn btn-icon btn-info" href="#">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="collapse show" id="mycard-collapse27">
                                    <div class="card-body">
                                                    <?php
                                                $select_prev = "SELECT *
                                                                FROM tbl_admittedPRG_prev 
                                                                
                                                                WHERE Stu_code = :code";
                                                
                                                $cselect_prev = $conn->prepare($select_prev);
                                                $cselect_prev->bindParam(':code', $code, PDO::PARAM_STR);
                                                $cselect_prev->execute(); 
                                                
                                                $row_cselect_prev = $cselect_prev->fetch(PDO::FETCH_ASSOC);
                                                if($row_cselect_prev){
                                                ?>
                                                
                                                <div class="">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            
                                                                <tr>
                                                                    <th scope="col">Admission Status:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['admission_status']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Matriculation Status:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['matriculation_status']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Previous ID:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['prev_code']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Campus:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['cump_id']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Program Type:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['program']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">School:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['school']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Specialization:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['specialization']); ?></th>
                                                                </tr>
                                                            
                                                                
                                                           
                                                        </thead>
                                                    </table>
                                    </div>
                                    <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                        <button class="btn btn-primary btn-sm" id="update_prev" data-id="<?php echo $row_cselect_prev['prev_id']; ?>"><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                                    </div>
                                    <?php
                                    }
                                    else{}
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        
                        <?php  
                        // $stst01=$conn->prepare("SELECT * FROM tbl_admittedPRG WHERE Stu_code='".$code."'");
                        // $stst01->execute();
                        // $data01=$stst01->fetch();
                        // $prgtype=$data01['prg_type'];
                        // if($prgtype!=2 && $prgtype!=7){
                        $stst02=$conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu='".$code."'");
                        $stst02->execute();
                        $data02=$stst02->fetch();
                        ?>
                        <!--WAEC Scratch Card information-->
                         <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>WAEC Scratch Card information</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse_waec" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                    </div>
                                </div>
                                
                                <div class="collapse show" id="mycard-collapse_waec">
                                    <div class="card-body">
                                        <form action="" method="POST" id="waec_scratch_card" class="row">
                                            <input type="hidden" name="stu" value="<?php echo $code ?>">
                                            <input type="hidden" name="action" value="save_waec">
                                            <div class="form-group col-12 col-md-6">
                                                <label>Scratch card number for first result</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" name="waec_result1" value="<?php echo $data02['waec_result1']; ?>" maxlength="60" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-6">
                                                <label>Pin for scratch card result 1</label>
                                                <div class="input-group" >
                                                     <input type="text" class="form-control" name="waec_pin1" value="<?php echo $data02['pin1']; ?>" maxlength="60" required> 
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-6">
                                                <label>Scratch card number for second result</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="waec_result2" maxlength="60" value="<?php echo $data02['waec_resilt2']; ?>">   
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-6">
                                                <label>Pin for Scratch Card Result 2</label>
                                                <div class="input-group" >
                                                     <input type="text" class="form-control" name="waec_pin2" maxlength="60" value="<?php echo $data02['pin2']; ?>"> 
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>First Exam Year</label>
                                                <div class="input-group" >
                                                     <select name="first_exam_year" class="form-control select2" style="width: 100%;" required>
                                                      <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = $currentYear;
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          $selected = ($year == $data02['first_exam_year']) ? 'selected' : '';
                                                          echo "<option value='$year' $selected>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                                                </div>
                                            </div>
                                             
                                             <div class="form-group col-12 col-md-4">
                                                <label>Examination Body</label>
                                                <div class="input-group" >
                                                 <select name="exam_body1" id="exam_body1" class="form-control select2" style="width: 100%;" required>
                                                     <option></option>
                                                     <option value="West African Examination Council" <?php if($data02['exam_body1']=='West African Examination Council') echo 'selected' ?>>West African Examination Council</option>
                                                     <option value="Others" <?php if($data02['exam_body1']=='Others') echo 'selected' ?>>Others</option>
                                                 </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4 other_body1" hidden>
                                                <label>Other examination body name</label>
                                                <div class="input-group">
                                                   <input type="text" class="form-control" name="other_body_name1" value="<?php echo $data02['other_body_name1']; ?>" maxlength="60">   
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-12 col-md-4">
                                                <label>Examination Type</label>
                                                <div class="input-group" >
                                                      <select name="exam_type1" id="exam_type1" class="form-control select2" style="width: 100%;" required>
                                                     <option></option>
                                                     <option value="Public WASSCE" <?php if($data02['exam_type1']=='Public WASSCE') echo 'selected' ?>>Public WASSCE</option>
                                                     <option value="Private WASSCE" <?php if($data02['exam_type1']=='Private WASSCE') echo 'selected' ?>>Private WASSCE</option>
                                                     <option value="GCE O Level" <?php if($data02['exam_type1']=="GCE O Level") echo 'selected' ?>>GCE 'O' Level</option>
                                                     <option value="GCE A Level" <?php if($data02['exam_type1']=="GCE A Level") echo 'selected' ?>>GCE 'A' Level</option>
                                                     <option value="Others" <?php if($data02['exam_type1']=="Others") echo 'selected' ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4 other_exam_type1" hidden>
                                                <label>Other Examination Type</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="other_exm_type1" value="<?php echo $data02['other_exm_type1'] ?>"maxlength="60">   
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>ID Number for First Examination</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="exam1_id" value="<?php echo $data02['exam1_id'] ?>"maxlength="60">   
                                                </div>
                                            </div>
                                            <!--second examination-->
                                            <div class="form-group col-12 col-md-4">
                                                <label>Second Exam Year</label>
                                                <div class="input-group" >
                                                      <select name="second_exam_year2" class="form-control select2" style="width: 100%;">
                                                       <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = $currentYear;
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          $selected = ($year == $data02['sec_exam_year2']) ? 'selected' : '';
                                                          echo "<option value='$year' $selected>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                                                </div>
                                            </div>
                                             
                                             <div class="form-group col-12 col-md-4">
                                                <label>Examination Body</label>
                                                <div class="input-group" >
                                                      <select name="exam_body2" id="exam_body2" class="form-control select2" style="width: 100%;">
                                                     <option></option>
                                                     <option value="West African Examination Council" <?php if($data02['exam_body2']=='West African Examination Council') echo 'selected' ?>>West African Examination Council</option>
                                                     <option value="Others" <?php if($data02['exam_body2']=='Others') echo 'selected' ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4 other_body2" hidden>
                                                <label>Other Examination Body Name</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="other_body_name2" value="<?php echo $data02['other_body_name2'] ?>" maxlength="60">   
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-12 col-md-4">
                                                <label>Examination Type</label>
                                                <div class="input-group" >
                                                      <select name="exam_type2" id="exam_type2" class="form-control select2" style="width: 100%;">
                                                     <option></option>
                                                     <option value="Public WASSCE" <?php if($data02['exam_type2']=='Public WASSCE') echo 'selected' ?>>Public WASSCE</option>
                                                     <option value="Private WASSCE" <?php if($data02['exam_type2']=='Private WASSCE') echo 'selected' ?>>Private WASSCE</option>
                                                     <option value="GCE O Level" <?php if($data02['exam_type2']=="GCE O Level") echo 'selected' ?>>GCE 'O' Level</option>
                                                     <option value="GCE A Level" <?php if($data02['exam_type2']=="GCE A Level") echo 'selected' ?>>GCE 'A' Level</option>
                                                     <option value="Others" <?php if($data02['exam_type2']=="Others") echo 'selected' ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4 other_exam_type2" hidden>
                                                <label>Other Examination Type</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="other_exm_type2" value="<?php echo $data02['other_exm_type2']?>" maxlength="60">   
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>ID Number for Second Examination</label>
                                                <div class="input-group" >
                                                   <input type="text" class="form-control" name="exam2_id" maxlength="60" value="<?php echo $data02['exam2_id'] ?>">   
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary"><span id="spinner_waec"></span>&nbsp;<span id="indicator_waec">Save changes</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <?php 
                            
                            
                        // }
                        ?>
                        
                        
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Last secondary schools attended</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse show" id="mycard-collapse">
                                    <div class="card-body">
                                        <form action="" method="POST" id="education" class="row">
                                            <input type="hidden" name="stu" value="<?php echo $code ?>">
                                            <input type="hidden" name="action" value="save_education">
                                            <div class="form-group col-12 col-md-8">
                                                <label>Institution Name</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" name="school" maxlength="60" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>From</label>
                                                <div class="input-group" >
                                                    <select name="from" class="form-control select2" style="width: 100%;" required>
                                                      <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = date('Y');
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          echo "<option value='$year'>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>To</label>
                                                <div class="input-group" >
                                                    <select name="to" class="form-control select2" style="width: 100%;" required>
                                                      <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = date('Y');
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          echo "<option value='$year'>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>Certificate or Degree Obtained</label>
                                                <div class="input-group col-12" >
                                                    <input type="text" class="form-control" name="certificate" maxlength="60" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <label>Award Obtained</label>
                                                <div class="input-group col-12" >
                                                    <input type="text" class="form-control" name="award" maxlength="60" required>
                                                </div>
                                            </div>
                                            <div class="bg-whitesmoke br">
                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save changes</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                
                                
                                <!--<div class="card-body collapse show" id="mycard-collapse-2">-->
                                    <div class="table-responsive">
                                        <table class="table  table-striped mb-none" id="datatable-default">  
        									<thead>
        										<tr>
        											<th>S/N</th>
                									<th>Institution Name</th>
                									<th>Joined From</th>
                									<th>Ended</th>
                									<th>Diploma/ Certificate</th>
                									<th>Award obtained</th>
                									<th>Action</th>
        										</tr>
        									</thead>
        									<tbody>
        									    <?php
                    								$stmt = $conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu = '".$code."' ORDER BY id DESC ");
                    								try {
                    									$stmt->execute(array());
                										$i = 1;
                										while ($row = $stmt->fetch()) {
                								?>
        								
        										<tr class="gradeX">
        											<td><?php echo $i++; ?></td>
        											<td><?php echo $row['school']; ?></td>
        											<td><?php echo $row['year_from']; ?></td>
        											<td><?php echo $row['year_to']; ?></td> 
        											<td><?php echo $row['certificate']; ?></td>  
        											<td><?php echo $row['award']; ?></td>  
        											<td>
        											    <button type="button" class="btn btn-success btn-sm del" data-id="<?=$row['id'] ?>"><span id="spinner_<?=$row['id'] ?>"></span>&nbsp;<i class="fa fa-trash"></i></button>
        											</td>
        										</tr>
        										
        										<?php
                    							    }
                    								}catch (PDOException $ex) {
                									   echo "Couldn't find records";
                								}
                								?>
        									</tbody>
        								</table>
							        </div>
                                <!--</div>-->
                            </div>
                        </div>
                        
                        
                    </div>
                    
                </div>
                <?php ReportProblem($conn, $code, $thing); ?>
            </section>
        </div>
        
        <!--update modal contact-->
                        <form method="POST" id="update_form_pre">
                            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_prev">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Matriculation Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update_matuculation">
                                        <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                                        <input type="hidden" name="prev_id" id="prev_id">
                    
                                        <div class="form-group">
                                            <label>Is this your first time applying for admission to Njala?</label>
                                            <select class="form-control" name="admission_status" id="admission_status">
                                                <option selected disabled>Select Status</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                    
                                        <div class="form-group">
                                            <label>Have you previously matriculated as a student of Njala?</label>
                                            <select class="form-control" id="matriculation_status" name="matriculation_status">
                                                <option selected disabled>Select Status</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                    
                                        
                                            <div class="form-group">
                                                <label>Matriculation N<sup>O</sup></label>
                                                <input type="text" name="prev_code" id="prev_code" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Campus</label>
                                                <input type="text" name="cump_id" id="cump_id" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Program</label>
                                                <input type="text" name="program" id="program" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>School</label>
                                                <input type="text" name="school" id="school" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Specialization</label>
                                                <input type="text" name="specialization" id="specialization" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>From</label>
                                                <select name="year_from" id="year_from" class="form-control select2" style="width: 100%;" required>
                                                                          <?php
                                                                            $currentYear = date("Y");
                                                                            $startYear = 1970;
                                                                            $endYear = date('Y');
                                                                        
                                                                            for ($year = $startYear; $year <= $endYear; $year++) {
                                                                              echo "<option value='$year'>$year</option>";
                                                                            }
                                                                          ?>
                                                                        </select>
                                            </div>
                                            <div class="form-group">
                                                <label>To</label>
                                                <select name="year_to" id="year_to" class="form-control select2" style="width: 100%;" required>
                                                                          <?php
                                                                            $currentYear = date("Y");
                                                                            $startYear = 1970;
                                                                            $endYear = date('Y');
                                                                        
                                                                            for ($year = $startYear; $year <= $endYear; $year++) {
                                                                              echo "<option value='$year'>$year</option>";
                                                                            }
                                                                          ?>
                                                                        </select>
                                            </div>
                                        
                                    </div>
                                    <div class="modal-footer bg-whitesmoke">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="e_appBtn">
                                            <span id="spinne1r2"></span>&nbsp;<span id="indicator21">Save changes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        
    <form  method="POST" id="add_mattuculation">
    <div class="modal fade" tabindex="-1" role="dialog" id="addModal_prev">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Matriculation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_mattuculation">
                    <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">

                    <div class="form-group">
                        <label>Is this your first time applying for admission to Njala?</label>
                        <select class="form-control" name="admission_status">
                            <option selected disabled>Select Status</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Have you previously matriculated as a student of Njala?</label>
                        <select class="form-control" name="matriculation_status" id="matriculated_status">
                            <option selected disabled>Select Status</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="additional-fields">
                        <div class="form-group">
                            <label>Matriculation N<sup>O</sup></label>
                            <input type="text" name="prev_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Campus</label>
                            <input type="text" name="cump_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Program</label>
                            <input type="text" name="program" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>School</label>
                            <input type="text" name="school" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Specialization</label>
                            <input type="text" name="specialization" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>From</label>
                            <select name="year_from" class="form-control select2" style="width: 100%;" required>
                                                      <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = date('Y');
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          echo "<option value='$year'>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <select name="year_to" class="form-control select2" style="width: 100%;" required>
                                                      <?php
                                                        $currentYear = date("Y");
                                                        $startYear = 1970;
                                                        $endYear = date('Y');
                                                    
                                                        for ($year = $startYear; $year <= $endYear; $year++) {
                                                          echo "<option value='$year'>$year</option>";
                                                        }
                                                      ?>
                                                    </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="e_appBtn">
                        <span id="spinne1r2"></span>&nbsp;<span id="indicator21">Save changes</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


<script>
    $(document).ready(function(){
        $('#add_M').click(function(){
            $('#addModal_prev').modal('show');
            
        });
        $('.additional-fields').hide();
    
    // Listen for changes on the matriculation status dropdown
    $('#matriculated_status').on('change', function() {
        var selectedValue = $(this).val();
        
        if (selectedValue === 'Yes') {
            // Show fields if 'Yes' is selected
            $('.additional-fields').slideDown();
        } else {
            // Hide fields if 'No' is selected
            $('.additional-fields').slideUp();
        }
    });
        
        $('#update_prev').click(function () {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'view_previouse'
        };
        $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: getData,
            dataType:"JSON",
            success:function(data){
                console.log(data);
                // $('#spinner_c').fadeOut('fast');
                $("#prev_id").val(data_id);
                $("#prev_code").val(data[0].prev_code);
                $("#cump_id").val(data[0].cump_id);
                $("#school").val(data[0].school);
                $("#program").val(data[0].program);    
                $("#specialization").val(data[0].specialization);
                $("#year_to").val(data[0].year_to).trigger('change');
                $("#year_from").val(data[0].year_from).trigger('change');
                $("#admission_status").val(data[0].admission_status).trigger('change');
                $("#matriculation_status").val(data[0].matriculation_status).trigger('change');

                
                $('#updateModal_prev').modal('show');
                
			},
			error:function(error){
			    $('#spinner_c').fadeOut('fast');
			    pop_wrong("Something went wrong!");
			}
        });
    });
    
    
        
    
    
    
    
    
        
        
        $("#education").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_up_success(formData.message)
                    window.location.reload();
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                }
            });
        });
        
        // waec scratch card submission
        
          $("#waec_scratch_card").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner_waec').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_waec').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner_waec').fadeOut('fast');
                    $('#indicator_waec').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner_waec').fadeOut('fast');
                    $('#indicator_waec').html("Save");
                }
            });
        });
        $("#add_mattuculation").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner_waec').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_waec').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner21').fadeOut('fast');
                    $('#indicator_21').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner21').fadeOut('fast');
                    $('#indicator_21').html("Save");
                }
            });
        });
        
        $("#update_form_pre").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner_waec').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_waec').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner21').fadeOut('fast');
                    $('#indicator_21').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner21').fadeOut('fast');
                    $('#indicator_21').html("Save");
                }
            });
        });
        
        $(document).on('click', '.del', function(){
            var entry = $(this).data('id');
            var formdata = {
                id: entry,
                action: 'delete_education'
            }

            $('#spinner_'+entry).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                success: function(formData){
                    $('#spinner_'+entry).fadeOut('fast');
                    pop_up_success(formData.message)
                    window.location.reload();
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner_'+entry).fadeOut('fast');
                }
            });
        });
        
        $("#exam_body1").change(function(){
            var value = $("#exam_body1").val();
            if(value == "Others"){
                $(".other_body1").removeAttr('hidden');  
            } else {
                $(".other_body1").attr('hidden', true); 
            }
        });
        $("#exam_type1").change(function(){
            var value = $("#exam_type1").val();
            if(value == "Others"){
                $(".other_exam_type1").removeAttr('hidden');  
            } else {
                $(".other_exam_type1").attr('hidden', true); 
            }
        });
        
        $("#exam_body2").change(function(){
            var value = $("#exam_body2").val();
            if(value == "Others"){
                $(".other_body2").removeAttr('hidden');  
            } else {
                $(".other_body2").attr('hidden', true); 
            }
        });
        $("#exam_type2").change(function(){
            var value = $("#exam_type2").val();
            if(value == "Others"){
                $(".other_exam_type2").removeAttr('hidden');  
            } else {
                $(".other_exam_type2").attr('hidden', true); 
            }
        });
    })

   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>